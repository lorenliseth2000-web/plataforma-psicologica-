<?php

namespace App\Http\Controllers;

use App\Models\EmotionalLog;
use App\Services\AIRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmotionalLogController extends Controller
{
    public function __construct(
        protected AIRecommendationService $aiRecommendationService
    ) {}

    /**
     * Listado histórico y tabla de seguimiento emocional.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = EmotionalLog::where('user_id', $user->id);

        if ($startDate) {
            $query->where('log_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('log_date', '<=', $endDate);
        }

        $logs = $query->orderBy('log_date', 'desc')->paginate(15);

        // Registro de hoy
        $today = now()->toDateString();
        $todayLog = EmotionalLog::where('user_id', $user->id)->where('log_date', $today)->first();

        // Métricas promedio de los últimos 30 días
        $recent30 = EmotionalLog::where('user_id', $user->id)
            ->where('log_date', '>=', now()->subDays(30))
            ->get();

        $avgAnxiety = $recent30->count() > 0 ? round($recent30->avg('anxiety_level'), 1) : 0;
        $avgStress = $recent30->count() > 0 ? round($recent30->avg('stress_level'), 1) : 0;
        $avgSleep = $recent30->count() > 0 ? round($recent30->avg('sleep_hours'), 1) : 0;
        $avgMood = $recent30->count() > 0 ? round($recent30->avg('mood_level'), 1) : 0;

        return view('emotional-logs.index', compact(
            'logs',
            'todayLog',
            'startDate',
            'endDate',
            'avgAnxiety',
            'avgStress',
            'avgSleep',
            'avgMood'
        ));
    }

    /**
     * Formulario para check-in diario.
     */
    public function create(Request $request)
    {
        $today = now()->toDateString();
        $todayLog = EmotionalLog::where('user_id', $request->user()->id)
            ->where('log_date', $today)
            ->first();

        return view('emotional-logs.create', compact('todayLog', 'today'));
    }

    /**
     * Guarda el check-in diario (1 por día por usuario).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anxiety_level' => ['required', 'integer', 'between:1,10'],
            'stress_level' => ['required', 'integer', 'between:1,10'],
            'mood_level' => ['required', 'integer', 'between:1,5'],
            'sleep_hours' => ['required', 'numeric', 'between:0,24'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'log_date' => ['required', 'date'],
        ]);

        $user = $request->user();

        $log = EmotionalLog::updateOrCreate(
            [
                'user_id' => $user->id,
                'log_date' => $validated['log_date'],
            ],
            [
                'anxiety_level' => $validated['anxiety_level'],
                'stress_level' => $validated['stress_level'],
                'mood_level' => $validated['mood_level'],
                'sleep_hours' => $validated['sleep_hours'],
                'notes' => $validated['notes'] ?? null,
            ]
        );

        // Actualizar caché de IA
        $this->aiRecommendationService->getOrGenerateRecommendation($user, forceRefresh: true);

        return redirect()->route('emotional-logs.index')
            ->with('status', '¡Tu registro diario de bienestar ha sido guardado exitosamente!');
    }

    /**
     * Endpoint API para consultar datos de evolución temporal.
     */
    public function chartData(Request $request)
    {
        $days = (int) $request->query('days', 30);
        $user = $request->user();

        $logs = EmotionalLog::where('user_id', $user->id)
            ->where('log_date', '>=', now()->subDays($days))
            ->orderBy('log_date', 'asc')
            ->get();

        return response()->json([
            'labels' => $logs->pluck('log_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')),
            'anxiety' => $logs->pluck('anxiety_level'),
            'stress' => $logs->pluck('stress_level'),
            'sleep' => $logs->pluck('sleep_hours'),
            'mood' => $logs->pluck('mood_level'),
        ]);
    }

    /**
     * Vista imprimible / exportable de seguimiento emocional.
     */
    public function printable(Request $request)
    {
        $user = $request->user();
        $logs = EmotionalLog::where('user_id', $user->id)
            ->orderBy('log_date', 'desc')
            ->take(60)
            ->get();

        $latestAssessment = $user->latestAssessment;

        return view('emotional-logs.printable', compact('user', 'logs', 'latestAssessment'));
    }
}
