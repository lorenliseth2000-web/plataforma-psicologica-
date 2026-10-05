<?php

namespace App\Http\Controllers;

use App\Models\AttentionRoute;
use App\Models\EmotionalLog;
use App\Models\Technique;
use App\Services\AIRecommendationService;
use App\Services\MotivationalMessageService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected AIRecommendationService $aiRecommendationService,
        protected MotivationalMessageService $motivationalMessageService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Última evaluación
        $latestAssessment = $user->latestAssessment;

        // 2. Registro emocional de hoy
        $today = now()->toDateString();
        $todayLog = EmotionalLog::where('user_id', $user->id)
            ->where('log_date', $today)
            ->first();

        // 3. Recomendación de IA (con fallback y caché)
        $aiRecommendation = $this->aiRecommendationService->getOrGenerateRecommendation($user);

        // 4. Últimos 30 días de registros para gráficas
        $logs = EmotionalLog::where('user_id', $user->id)
            ->where('log_date', '>=', now()->subDays(30))
            ->orderBy('log_date', 'asc')
            ->get();

        $chartLabels  = $logs->pluck('log_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $anxietyData  = $logs->pluck('anxiety_level')->toArray();
        $stressData   = $logs->pluck('stress_level')->toArray();
        $sleepData    = $logs->pluck('sleep_hours')->toArray();
        $moodData     = $logs->pluck('mood_level')->toArray();

        // 5. Técnicas recomendadas
        $category = 'ansiedad';
        if ($latestAssessment && $latestAssessment->score_stress > $latestAssessment->score_anxiety) {
            $category = 'estres';
        }
        $recommendedTechniques = Technique::active()->where('category', $category)->take(3)->get();
        if ($recommendedTechniques->isEmpty()) {
            $recommendedTechniques = Technique::active()->take(3)->get();
        }

        // 6. Contactos de emergencia para nivel alto
        $emergencyRoutes = [];
        if ($latestAssessment && $latestAssessment->isHighRisk()) {
            $emergencyRoutes = AttentionRoute::emergencies()->take(3)->get();
        }

        // 7. Estadísticas del usuario
        $totalAssessments = $user->assessments()->count();
        $totalSessions    = $user->techniqueSessions()->count();
        $totalLogs        = $user->emotionalLogs()->count();

        // 8. Mensaje motivacional del día (personalizado)
        $dailyMessage = $this->motivationalMessageService->getDailyMessage($user);

        // 9. Recordatorios activos del usuario
        $activeReminders = $user->reminders()->where('active', true)->get();

        return view('dashboard', compact(
            'user',
            'latestAssessment',
            'todayLog',
            'aiRecommendation',
            'chartLabels',
            'anxietyData',
            'stressData',
            'sleepData',
            'moodData',
            'recommendedTechniques',
            'emergencyRoutes',
            'totalAssessments',
            'totalSessions',
            'totalLogs',
            'dailyMessage',
            'activeReminders'
        ));
    }
}
