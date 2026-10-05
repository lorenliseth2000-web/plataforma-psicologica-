<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RiskAssessmentService
{
    // Umbrales de riesgo clínico ajustables
    public const THRESHOLD_LOW_MAX = 7;
    public const THRESHOLD_MODERATE_MAX = 15;
    public const THRESHOLD_HIGH_MIN = 16;

    // Constantes de nivel de riesgo
    public const RISK_LOW = 'bajo';
    public const RISK_MODERATE = 'moderado';
    public const RISK_HIGH = 'alto';

    /**
     * Determina el nivel de riesgo según el puntaje total acumulado.
     */
    public function determineRiskLevel(int $totalScore): string
    {
        if ($totalScore <= self::THRESHOLD_LOW_MAX) {
            return self::RISK_LOW;
        }

        if ($totalScore <= self::THRESHOLD_MODERATE_MAX) {
            return self::RISK_MODERATE;
        }

        return self::RISK_HIGH;
    }

    /**
     * Genera una interpretación respetuosa y NO diagnóstica basada en el nivel y puntajes.
     */
    public function generateFeedback(string $riskLevel, int $anxietyScore, int $stressScore): string
    {
        $categoryHighlight = '';
        if ($anxietyScore > $stressScore + 3) {
            $categoryHighlight = 'Se observa una mayor presencia de manifestaciones asociadas a inquietud o tensión ansiosa.';
        } elseif ($stressScore > $anxietyScore + 3) {
            $categoryHighlight = 'Se observa una mayor presencia de factores vinculados a sobrecarga, fatiga y estrés acumulado.';
        } else {
            $categoryHighlight = 'Las manifestaciones de tensión e inquietud se encuentran distribuidas de manera equilibrada.';
        }

        return match ($riskLevel) {
            self::RISK_LOW => "Presentas indicadores leves o mínimos de tensión. Tu nivel actual sugiere una adecuada capacidad de adaptación frente a las exigencias cotidianas. {$categoryHighlight} Te sugerimos mantener hábitos de autocuidado, pausas conscientes y explorar técnicas de respiración para preservar tu bienestar.",
            self::RISK_MODERATE => "Presentas indicadores moderados de malestar emocional relacionados con tensión o sobrecarga. {$categoryHighlight} Te recomendamos incorporar sesiones regulares de regulación emocional (como respiración 4-7-8, relajación muscular y técnica STOP) y considerar una consulta con un profesional de la salud mental para orientación preventiva.",
            self::RISK_HIGH => "Presentas indicadores significativos de malestar emocional que podrían estar interfiriendo en tu rutina diaria, concentración o descanso. {$categoryHighlight} Es importante priorizar tu bienestar: te sugerimos acudir a valoración con un profesional de la salud mental y consultar de inmediato las líneas de apoyo y rutas de atención disponibles.",
            default => "Evaluación completada exitosamente. Te invitamos a explorar las técnicas de regulación disponibles en la plataforma.",
        };
    }

    /**
     * Procesa y persiste una evaluación completa con sus respuestas en una transacción atómica.
     *
     * @param User $user
     * @param array<int, int> $answersMap question_id => answer_value (0-3)
     * @return Assessment
     */
    public function processAssessment(User $user, array $answersMap): Assessment
    {
        return DB::transaction(function () use ($user, $answersMap) {
            $questions = Question::whereIn('id', array_keys($answersMap))->get()->keyBy('id');

            $scoreAnxiety = 0;
            $scoreStress = 0;

            foreach ($answersMap as $questionId => $value) {
                $question = $questions->get($questionId);
                if (! $question) {
                    continue;
                }

                $numericValue = (int) $value;
                $weightedValue = $numericValue * ($question->weight ?: 1);

                if ($question->type === 'ansiedad') {
                    $scoreAnxiety += $weightedValue;
                } elseif ($question->type === 'estres') {
                    $scoreStress += $weightedValue;
                }
            }

            $totalScore = $scoreAnxiety + $scoreStress;
            $riskLevel = $this->determineRiskLevel($totalScore);
            $feedback = $this->generateFeedback($riskLevel, $scoreAnxiety, $scoreStress);

            $assessment = Assessment::create([
                'user_id' => $user->id,
                'type' => 'general',
                'score_anxiety' => $scoreAnxiety,
                'score_stress' => $scoreStress,
                'total_score' => $totalScore,
                'risk_level' => $riskLevel,
                'non_diagnostic_feedback' => $feedback,
                'completed_at' => now(),
            ]);

            foreach ($answersMap as $questionId => $value) {
                AssessmentAnswer::create([
                    'assessment_id' => $assessment->id,
                    'question_id' => $questionId,
                    'value' => (int) $value,
                ]);
            }

            return $assessment;
        });
    }
}
