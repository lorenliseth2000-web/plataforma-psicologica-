<?php

namespace App\Services;

use App\Models\AiRecommendation;
use App\Models\Technique;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIRecommendationService
{
    /**
     * Obtiene o genera una recomendación inteligente para el usuario.
     */
    public function getOrGenerateRecommendation(User $user, bool $forceRefresh = false): AiRecommendation
    {
        $cacheKey = "user_ai_rec_{$user->id}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($user) {
            return $this->generate($user);
        });
    }

    /**
     * Construye el contexto clínico del usuario y genera la recomendación.
     */
    public function generate(User $user): AiRecommendation
    {
        // 1. Recopilar datos de contexto
        $latestAssessment = $user->latestAssessment;
        $recentLogs = $user->emotionalLogs()->where('log_date', '>=', now()->subDays(7))->get();
        $recentSessions = $user->techniqueSessions()->with('technique')->latest()->take(5)->get();

        $avgAnxiety = $recentLogs->count() > 0 ? round($recentLogs->avg('anxiety_level'), 1) : null;
        $avgStress = $recentLogs->count() > 0 ? round($recentLogs->avg('stress_level'), 1) : null;
        $avgSleep = $recentLogs->count() > 0 ? round($recentLogs->avg('sleep_hours'), 1) : null;
        $avgMood = $recentLogs->count() > 0 ? round($recentLogs->avg('mood_level'), 1) : null;

        $riskLevel = $latestAssessment ? $latestAssessment->risk_level : 'no_evaluado';
        $anxietyScore = $latestAssessment ? $latestAssessment->score_anxiety : 0;
        $stressScore = $latestAssessment ? $latestAssessment->score_stress : 0;

        $frequentTechniques = $recentSessions->pluck('technique.name')->unique()->values()->toArray();

        $contextData = [
            'risk_level' => $riskLevel,
            'anxiety_score' => $anxietyScore,
            'stress_score' => $stressScore,
            'avg_anxiety_7d' => $avgAnxiety,
            'avg_stress_7d' => $avgStress,
            'avg_sleep_7d' => $avgSleep,
            'avg_mood_7d' => $avgMood,
            'recent_techniques' => $frequentTechniques,
            'user_name' => $user->name,
        ];

        // 2. Intentar llamar a API de IA si hay configuración disponible
        $aiResult = $this->callExternalAiApi($contextData);

        if ($aiResult) {
            $recommendation = AiRecommendation::create([
                'user_id' => $user->id,
                'message' => $aiResult['message'],
                'suggested_techniques' => $aiResult['techniques'],
                'context_data' => $contextData,
                'source' => $aiResult['source'],
                'generated_at' => now(),
            ]);

            return $recommendation;
        }

        // 3. Fallback clínico en PHP basado en reglas de evidencia
        $fallback = $this->generateClinicalFallback($contextData);

        return AiRecommendation::create([
            'user_id' => $user->id,
            'message' => $fallback['message'],
            'suggested_techniques' => $fallback['techniques'],
            'context_data' => $contextData,
            'source' => 'rule_based_fallback',
            'generated_at' => now(),
        ]);
    }

    /**
     * Intenta consultar APIs de OpenAI o Gemini con prompt estructurado de seguridad.
     */
    protected function callExternalAiApi(array $context): ?array
    {
        $geminiKey = env('GEMINI_API_KEY');
        $openAiKey = env('OPENAI_API_KEY');

        $systemPrompt = <<<EOT
Eres el asistente clínico inteligente de MenteGuía IA, una plataforma de apoyo para la regulación de síntomas leves y moderados de ansiedad y estrés.
REGLAS ESTRICTAS DE SEGURIDAD:
1. NUNCA diagnostiques enfermedades ni uses términos clínicos diagnósticos (prohibido decir "tienes trastorno de ansiedad", "sufres de pánico", etc.).
2. NUNCA abordes temas fuera de alcance (depresión mayor, suicidio, bipolaridad, esquizofrenia, adicciones).
3. Si el nivel de riesgo es 'alto', sugiere firmemente acudir con un profesional de la salud mental y consultar las rutas de atención de la plataforma.
4. Genera una recomendación empática, clara, personalizada en 2 o 3 párrafos concisos, sugiriendo técnicas concretas (ej. Respiración diafragmática, 4-7-8, Técnica STOP, Relajación muscular progresiva, Grounding 5-4-3-2-1).
5. Responde en formato JSON puro con la estructura: {"message": "texto de la recomendacion", "techniques": ["slug-o-nombre-de-tecnica-1", "slug-o-nombre-de-tecnica-2"]}
EOT;

        $userPrompt = "Datos del usuario: " . json_encode($context, JSON_UNESCAPED_UNICODE);

        if (! empty($geminiKey)) {
            try {
                $response = Http::timeout(6)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiKey}", [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $systemPrompt . "\n\n" . $userPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                    ]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($text) {
                        $parsed = json_decode($text, true);
                        if (isset($parsed['message'])) {
                            return [
                                'message' => $parsed['message'],
                                'techniques' => $parsed['techniques'] ?? [],
                                'source' => 'gemini',
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini API call failed, using fallback: ' . $e->getMessage());
            }
        }

        if (! empty($openAiKey)) {
            try {
                $response = Http::timeout(6)->withToken($openAiKey)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'response_format' => ['type' => 'json_object'],
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $text = $json['choices'][0]['message']['content'] ?? null;
                    if ($text) {
                        $parsed = json_decode($text, true);
                        if (isset($parsed['message'])) {
                            return [
                                'message' => $parsed['message'],
                                'techniques' => $parsed['techniques'] ?? [],
                                'source' => 'openai',
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('OpenAI API call failed, using fallback: ' . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Generador de fallback clínico determinístico en PHP basado en evidencia.
     */
    public function generateClinicalFallback(array $context): array
    {
        $userName = $context['user_name'] ?? 'Usuario';
        $riskLevel = $context['risk_level'] ?? 'no_evaluado';
        $avgAnxiety = $context['avg_anxiety_7d'] ?? 5;
        $avgStress = $context['avg_stress_7d'] ?? 5;
        $avgSleep = $context['avg_sleep_7d'] ?? 7;

        $suggestedSlugs = [];
        $paragraphs = [];

        // Análisis contextual del estado
        if ($riskLevel === 'alto') {
            $paragraphs[] = "Hola {$userName}, observamos indicadores que reflejan un nivel considerable de sobrecarga o tensión en tus registros recientes. Cuidar de tu bienestar emocional es fundamental en este momento.";
            $paragraphs[] = "Te recomendamos enfáticamente programar un espacio de consulta con un profesional de la salud mental. Recuerda que en la sección de **Rutas de Atención** cuentas con canales directos y líneas gratuitas de orientación psicológica.";
            $paragraphs[] = "Como apoyo complementario e inmediato para aliviar la agitación, te sugerimos realizar ejercicios de conexión sensorial como el **Grounding 5-4-3-2-1** o la **Respiración 4-7-8**.";
            $suggestedSlugs = ['grounding-5-4-3-2-1', 'respiracion-4-7-8', 'respiracion-diafragmatica'];
        } elseif ($riskLevel === 'moderado' || ($avgStress && $avgStress >= 6) || ($avgAnxiety && $avgAnxiety >= 6)) {
            $paragraphs[] = "Hola {$userName}, al revisar tu seguimiento de los últimos días se aprecian momentos de tensión acumulada y exigencia cotidiana.";
            
            if ($avgSleep !== null && $avgSleep < 6.5) {
                $paragraphs[] = "Hemos notado que tus horas de sueño promedio ({$avgSleep}h) están por debajo de lo recomendado, lo cual incrementa la sensibilidad al estrés. Practicar una sesión de **Relajación Muscular Progresiva (Jacobson)** o **Escaneo Corporal** antes de acostarte favorecerá la calidad de tu descanso.";
                $suggestedSlugs[] = 'relajacion-muscular-progresiva';
                $suggestedSlugs[] = 'escaneo-corporal';
            } else {
                $paragraphs[] = "Para regular la activación corporal en momentos de sobrecarga, te recomendamos realizar una **Pausa Consciente** o aplicar la **Técnica STOP** durante tu jornada de estudio o trabajo.";
                $suggestedSlugs[] = 'tecnica-stop';
                $suggestedSlugs[] = 'pausa-consciente';
            }

            $paragraphs[] = "Acompaña tu rutina con una práctica guiada de **Respiración Diafragmática** para restaurar el equilibrio de tu sistema nervioso.";
            $suggestedSlugs[] = 'respiracion-diafragmatica';
        } else {
            // Riesgo bajo / adaptativo
            $paragraphs[] = "Hola {$userName}, tus registros reflejan un estado general de balance y estabilidad emocional.";
            $paragraphs[] = "Para continuar fortaleciendo tus recursos de autorregulación y prevención, te invitamos a mantener hábitos saludables, pausas activas y explorar el **Mindfulness Breve** o la **Visualización de un Lugar Seguro**.";
            $suggestedSlugs = ['mindfulness-breve', 'visualizacion-lugar-seguro', 'respiracion-en-caja'];
        }

        // Buscar IDs o nombres de las técnicas sugeridas existentes
        $techniques = Technique::whereIn('slug', $suggestedSlugs)->pluck('id')->toArray();
        if (empty($techniques)) {
            $techniques = Technique::take(3)->pluck('id')->toArray();
        }

        return [
            'message' => implode("\n\n", $paragraphs),
            'techniques' => $techniques,
        ];
    }
}
