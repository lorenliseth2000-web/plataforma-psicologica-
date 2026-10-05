<?php

namespace App\Services;

/**
 * ChatbotService — Acompañante emocional preventivo de Sukha.
 * Integra OpenAI GPT si hay API key; si no, usa respuestas de script empáticas.
 * Incluye detección de crisis con protocolo de seguridad.
 */
class ChatbotService
{
    // ─── Palabras clave de crisis ───────────────────────────────────────────
    private array $crisisKeywords = [
        'suicidar', 'suicidio', 'quitarme la vida', 'no quiero vivir',
        'ya no quiero existir', 'me quiero morir', 'me voy a matar',
        'voy a matarme', 'me quiero quitar la vida', 'quisiera estar muerta',
        'quisiera estar muerto', 'no vale la pena vivir', 'hacerme daño',
        'hacerme dano', 'lastimarme', 'cortarme', 'autolesion', 'autolesión',
        'me corto', 'me quemo', 'matar a alguien', 'quiero matar',
        'en peligro ahora', 'me están golpeando', 'me están atacando',
    ];

    // ─── Pool de respuestas empáticas (fallback sin API key) ───────────────
    private array $fallbackResponses = [
        'Gracias por compartirlo conmigo. Cuéntame un poco más sobre lo que estás viviendo, si quieres.',
        'Entiendo que no es fácil. ¿Hay algo en particular que pese más en este momento?',
        'Lo que describes suena como algo que merece atención. ¿Qué es lo que más te preocupa ahora?',
        'A veces hablar de lo que sentimos ayuda a que todo se vea un poco más claro. ¿Qué más quieres contarme?',
        'Está bien estar aquí y expresar lo que sientes. No tienes que resolverlo todo ahora.',
        'Me parece importante lo que compartes. ¿Desde cuándo te sientes así?',
        '¿Hay algo que te haya dado algo de alivio, aunque sea pequeño, en estos días?',
        '¿Qué necesitarías en este momento para sentirte un poco mejor?',
        '¿Hay alguien cercano con quien puedas hablar de esto?',
        'Gracias por la confianza. ¿Quieres explorar cómo te hace sentir esta situación?',
        'Es válido sentir lo que sientes. No hay emociones incorrectas. ¿Qué parte de esto te resulta más difícil?',
        'A veces el cuerpo también siente lo que la mente está procesando. ¿Cómo está tu cuerpo ahora?',
        '¿Qué opciones sientes que tienes ahora mismo, aunque sean pequeñas?',
        'Eso suena agotador. Date un momento. No tienes que tener una respuesta ahora.',
        '¿Te gustaría probar un ejercicio breve para volver al presente, o prefieres seguir hablando?',
    ];

    // ─── Detección de crisis ────────────────────────────────────────────────
    public function detectCrisis(string $message): array
    {
        $lower = mb_strtolower($message);
        foreach ($this->crisisKeywords as $keyword) {
            if (str_contains($lower, $keyword)) {
                return [
                    'is_crisis' => true,
                    'response'  => $this->buildCrisisResponse(),
                ];
            }
        }
        return ['is_crisis' => false, 'response' => ''];
    }

    private function buildCrisisResponse(): string
    {
        return "Lo que me estás contando es muy serio, y quiero que sepas que no estás solo o sola en esto.\n\n"
            . "Lo más importante ahora es tu seguridad. Por favor comunícate con una línea de emergencias:\n\n"
            . "**Colombia:** Línea 106 (Salud Mental) · 123 (Emergencias)\n"
            . "**México:** 800 290 0024 · 800 711 2000 (SAPTEL)\n"
            . "**España:** 024 (Línea de atención a la conducta suicida)\n"
            . "**Internacional:** 112\n\n"
            . "Si estás en peligro inmediato, llama ahora al número de emergencias de tu país.\n\n"
            . "Si puedes, habla también con alguien de confianza que esté cerca de ti. Estoy aquí, pero una persona real puede acompañarte mejor en este momento.";
    }

    // ─── Respuesta principal ────────────────────────────────────────────────
    public function respond(array $history): string
    {
        $apiKey = config('services.openai.key', env('OPENAI_API_KEY', ''));

        if (!empty($apiKey)) {
            try {
                return $this->callOpenAI($history, $apiKey);
            } catch (\Throwable) {
                // Si la API falla, caemos al fallback
            }
        }

        return $this->fallbackResponse($history);
    }

    // ─── Llamada a OpenAI ───────────────────────────────────────────────────
    private function callOpenAI(array $history, string $apiKey): string
    {
        $messages = [['role' => 'system', 'content' => $this->buildSystemPrompt()]];
        foreach ($history as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        $payload = json_encode([
            'model'       => 'gpt-4o-mini',
            'messages'    => $messages,
            'max_tokens'  => 350,
            'temperature' => 0.75,
        ]);

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                "Authorization: Bearer {$apiKey}",
            ],
        ]);

        $result = curl_exec($ch);
        $error  = curl_error($ch);
        curl_close($ch);

        if ($error || !$result) {
            return $this->fallbackResponse($history);
        }

        $data = json_decode($result, true);
        $text = trim($data['choices'][0]['message']['content'] ?? '');

        return $text ?: $this->fallbackResponse($history);
    }

    // ─── System prompt completo ─────────────────────────────────────────────
    private function buildSystemPrompt(): string
    {
        return 'Eres Sukha, un acompañante conversacional de bienestar emocional preventivo.'
            . ' Eres cálida, empática, tranquila, respetuosa, paciente y profundamente no juzgadora.'
            . ' Hablas en español de manera natural y cercana. Tu único propósito es acompañar y facilitar'
            . ' que la persona llegue a sus propias respuestas. Tú NO tienes respuestas para la vida de nadie.'
            . "\n\nPRINCIPIO FUNDAMENTAL: La persona que habla contigo es la única autoridad sobre su propia vida."
            . " Sus decisiones le pertenecen exclusivamente a ella. Tú no sabes qué es mejor para nadie."
            . " Tu función es ayudarla a escucharse a sí misma, no dirigirla."
            . "\n\nREGLAS ABSOLUTAS — NUNCA las violes:"
            . "\n1. NUNCA diagnostiques. Jamás digas 'tienes ansiedad', 'eso es depresión', 'eso es un trauma', 'eso es apego ansioso'."
            . "\n2. NUNCA digas qué debe hacer. Ni con 'debes', 'tienes que', 'lo mejor sería', 'te recomiendo', 'lo ideal es', 'podrías intentar'."
            . "\n3. NUNCA respondas la pregunta '¿qué debo hacer?'. En cambio, explora con la persona: '¿Qué opciones estás viendo tú?' o '¿Qué sientes que necesitas?'"
            . "\n4. NUNCA asumas lo que la persona siente. Si lloras no significa que estés triste. Pregunta antes de nombrar una emoción."
            . "\n5. NUNCA hagas preguntas que sugieran una respuesta. Evita '¿No crees que sería mejor...?' o '¿No te parece que...?'"
            . "\n6. NUNCA minimices. No digas 'no es para tanto', 'todo va a estar bien', 'intenta no preocuparte'."
            . "\n7. NUNCA des consejos de vida, de pareja, laborales, médicos, financieros o de ningún tipo."
            . "\n8. Si alguien insiste en que le digas qué hacer, responde con algo como: 'Eso es algo que solo tú puedes saber."
            . " Yo puedo acompañarte mientras explorar tus propias respuestas. ¿Qué opciones estás considerando?'"
            . "\n\nLO QUE SÍ HACES:"
            . "\n- Escuchas activamente, validas sin diagnosticar, reflejas lo que la persona expresa sin añadir interpretaciones."
            . "\n- Haces preguntas abiertas y neutras: '¿Qué es lo que más pesa de esto?', '¿Cómo te sientes cuando piensas en eso?', '¿Qué necesitas ahora mismo?'"
            . "\n- Ofreces técnicas de regulación solo como opciones libres: 'Si te apetece, podemos hacer un ejercicio breve de respiración. Tú decides.'"
            . "\n- Alternas entre escuchar, validar, reflejar y preguntar. No terminas TODOS los mensajes con una pregunta."
            . "\n- Si la persona parece agobiada, puedes simplemente estar presente: 'Estoy aquí. No tienes que tener respuestas ahora.'"
            . "\n\nESTILO: Respuestas de 2-4 oraciones. Cálido pero no empalagoso. Natural, humano, no clínico. Sin emojis. Tuteo.";
    }

    // ─── Fallback contextual ────────────────────────────────────────────────
    private function fallbackResponse(array $history): string
    {
        $lastUser = '';
        foreach (array_reverse($history) as $msg) {
            if ($msg['role'] === 'user') {
                $lastUser = mb_strtolower($msg['content']);
                break;
            }
        }

        if (str_contains($lastUser, 'gracias')) {
            return 'De nada. Aquí puedes volver cuando lo necesites. ¿Hay algo más que quieras explorar?';
        }
        if (str_contains($lastUser, 'no sé') || str_contains($lastUser, 'no se')) {
            return 'Está bien no saber. A veces la incertidumbre es parte del proceso. ¿Qué sí sabes de esta situación, aunque sea una pequeña cosa?';
        }
        if (str_contains($lastUser, 'cansad') || str_contains($lastUser, 'agotad')) {
            return 'El cansancio también es información. A veces el cuerpo pide pausa antes de que la mente lo acepte. ¿Qué crees que te está agotando más?';
        }
        if (str_contains($lastUser, 'ansiedad') || str_contains($lastUser, 'ansios')) {
            return 'Gracias por contármelo. Cuando esa sensación aparece, a veces ayuda volver al cuerpo. ¿Te gustaría probar un ejercicio breve de respiración, o prefieres que sigamos hablando?';
        }
        if (str_contains($lastUser, 'triste') || str_contains($lastUser, 'tristeza') || str_contains($lastUser, 'llorar')) {
            return 'La tristeza también necesita espacio. No hay que resolverla de inmediato. ¿Quieres contarme un poco más sobre lo que está pasando?';
        }
        if (str_contains($lastUser, 'relación') || str_contains($lastUser, 'pareja') || str_contains($lastUser, 'novio') || str_contains($lastUser, 'novia')) {
            return 'Las relaciones pueden ser fuente de mucho, tanto de bienestar como de dolor. ¿Qué parte de esta situación es la que más te afecta en este momento?';
        }
        if (str_contains($lastUser, 'trabajo') || str_contains($lastUser, 'jefe') || str_contains($lastUser, 'laboral')) {
            return 'El ámbito laboral puede generar una presión que a veces se lleva a todo el día. ¿Qué es lo que más te pesa de esa situación?';
        }
        if (str_contains($lastUser, 'familia') || str_contains($lastUser, 'mamá') || str_contains($lastUser, 'papá') || str_contains($lastUser, 'hermano')) {
            return 'Las dinámicas familiares pueden ser complejas y agotadoras. ¿Quieres contarme más sobre lo que está ocurriendo?';
        }
        if (str_contains($lastUser, 'relajar') || str_contains($lastUser, 'calmar') || str_contains($lastUser, 'respirar')) {
            return 'Sukha tiene sesiones de relajación guiada por voz que pueden ayudar. Puedes encontrarlas en el menú principal. ¿Te gustaría explorar eso, o prefieres seguir hablando primero?';
        }
        if (str_contains($lastUser, 'estudio') || str_contains($lastUser, 'examen') || str_contains($lastUser, 'universidad') || str_contains($lastUser, 'escuela')) {
            return 'La presión académica puede sentirse muy intensa. ¿Qué parte de lo que estudias es lo que más te genera estrés en este momento?';
        }

        // Respuesta aleatoria del pool general basada en el turno de la conversación
        $index = count($history) % count($this->fallbackResponses);
        return $this->fallbackResponses[$index];
    }
}

