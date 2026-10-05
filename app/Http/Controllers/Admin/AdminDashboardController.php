<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\EmotionalLog;
use App\Models\Technique;
use App\Models\TechniqueSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Estadísticas clave
        $totalUsers = User::count();
        $totalAssessments = Assessment::count();
        $totalSessions = TechniqueSession::count();
        $totalLogs = EmotionalLog::count();

        // Distribución por nivel de riesgo
        $riskDistribution = [
            'bajo' => Assessment::where('risk_level', 'bajo')->count(),
            'moderado' => Assessment::where('risk_level', 'moderado')->count(),
            'alto' => Assessment::where('risk_level', 'alto')->count(),
        ];

        // Técnicas más utilizadas
        $topTechniques = Technique::withCount('sessions')
            ->orderBy('sessions_count', 'desc')
            ->take(5)
            ->get();

        // Satisfacción promedio
        $avgSatisfaction = round(TechniqueSession::avg('satisfaction') ?? 5, 1);

        // Actividades recientes de auditoría
        $recentLogs = ActivityLog::with('user')->latest()->take(8)->get();

        // Evaluaciones recientes
        $recentAssessments = Assessment::with('user')->latest('completed_at')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAssessments',
            'totalSessions',
            'totalLogs',
            'riskDistribution',
            'topTechniques',
            'avgSatisfaction',
            'recentLogs',
            'recentAssessments'
        ));
    }
}
