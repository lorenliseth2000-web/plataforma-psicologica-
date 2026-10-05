<?php

namespace App\Services;

use App\Models\MotivationalMessage;
use App\Models\User;
use App\Models\UserMessageLog;
use Illuminate\Support\Carbon;

class MotivationalMessageService
{
    /**
     * Determina la categoría más relevante según el perfil actual del usuario.
     */
    public function getRelevantCategory(User $user): string
    {
        $latestAssessment = $user->latestAssessment;
        $lastSession = $user->techniqueSessions()->latest()->first();
        $lastLog = $user->emotionalLogs()->latest()->first();

        // Sin actividad reciente → adherencia
        $daysSinceLastSession = $lastSession
            ? Carbon::parse($lastSession->created_at)->diffInDays(now())
            : 999;

        if ($daysSinceLastSession > 5) {
            return 'adherencia';
        }

        // Si tiene evaluación reciente
        if ($latestAssessment) {
            $riskLevel = $latestAssessment->risk_level;
            $daysOld = Carbon::parse($latestAssessment->completed_at)->diffInDays(now());

            if ($daysOld <= 7) {
                if ($riskLevel === 'alto') {
                    return 'busqueda_ayuda';
                }
                if ($riskLevel === 'moderado') {
                    return random_int(0, 1) ? 'estres' : 'regulacion';
                }
            }

            // Ansiedad predominante
            if ($latestAssessment->score_anxiety > $latestAssessment->score_stress + 2) {
                return 'ansiedad';
            }

            // Estrés predominante
            if ($latestAssessment->score_stress > $latestAssessment->score_anxiety + 2) {
                return 'estres';
            }
        }

        // Si el registro emocional reciente es negativo
        if ($lastLog && $lastLog->emotion_score <= 3) {
            return match (true) {
                in_array($lastLog->primary_emotion ?? '', ['ansiedad', 'preocupacion', 'miedo']) => 'ansiedad',
                in_array($lastLog->primary_emotion ?? '', ['tristeza', 'vacio', 'desmotivacion']) => 'depresion',
                default => 'regulacion',
            };
        }

        // Si hay mejoría reciente → progreso
        if ($lastLog && $lastLog->emotion_score >= 7) {
            return 'progreso';
        }

        // Categorías por día de la semana para variedad
        $weekday = now()->dayOfWeek;
        $rotations = [
            0 => 'autocuidado',
            1 => 'respiracion',
            2 => 'habitos',
            3 => 'autoestima',
            4 => 'descanso',
            5 => 'perseverancia',
            6 => 'regulacion',
        ];

        return $rotations[$weekday] ?? 'autocuidado';
    }

    /**
     * Obtiene el siguiente mensaje secuencial (1..1000) para el usuario.
     * Al llegar a 1000, reinicia automáticamente en 1.
     */
    public function getNextSequentialMessageForUser(User $user): MotivationalMessage
    {
        $lastLog = UserMessageLog::where('user_id', $user->id)
            ->latest('id')
            ->with('message')
            ->first();

        $lastOrder = $lastLog?->message?->sort_order ?? 0;
        $nextOrder = ($lastOrder % 1000) + 1;

        $message = MotivationalMessage::active()
            ->where('sort_order', $nextOrder)
            ->first();

        if (!$message) {
            $message = MotivationalMessage::active()->orderBy('sort_order')->first();
        }

        if (!$message) {
            $message = new MotivationalMessage([
                'sort_order' => 1,
                'category'   => 'autocuidado',
                'message'    => 'Cada pequeño paso también cuenta.',
                'active'     => true,
            ]);
            $message->id = 1;
        }

        return $message;
    }

    /**
     * Prepara y registra la frase motivacional exclusiva para este inicio de sesión.
     */
    public function prepareLoginPhraseForSession(User $user): string
    {
        if (session()->has('login_motivational_phrase')) {
            return session('login_motivational_phrase');
        }

        $message = $this->getNextSequentialMessageForUser($user);

        if ($message->exists) {
            UserMessageLog::create([
                'user_id'    => $user->id,
                'message_id' => $message->id,
                'shown_at'   => now(),
            ]);
        }

        session([
            'login_motivational_phrase' => $message->message,
            'show_login_toast'          => true,
            'login_phrase_order'        => $message->sort_order,
        ]);

        return $message->message;
    }

    /**
     * Obtiene el mensaje del día para el usuario (usa la secuencia 1..1000).
     */
    public function getDailyMessage(User $user): ?MotivationalMessage
    {
        if (session()->has('login_phrase_order')) {
            $order = session('login_phrase_order');
            $msg = MotivationalMessage::where('sort_order', $order)->first();
            if ($msg) {
                return $msg;
            }
        }

        return $this->getNextSequentialMessageForUser($user);
    }

    /**
     * Obtiene la frase de inicio de sesión secuencial para vistas o fallback.
     */
    public static function getLoginPhrase(): string
    {
        if (auth()->check()) {
            return (new self())->prepareLoginPhraseForSession(auth()->user());
        }

        $guestOrder = (session('guest_phrase_order', 0) % 1000) + 1;
        session(['guest_phrase_order' => $guestOrder]);

        $msg = MotivationalMessage::where('sort_order', $guestOrder)->value('message');

        return $msg ?? 'Cada pequeño paso también cuenta.';
    }
}
