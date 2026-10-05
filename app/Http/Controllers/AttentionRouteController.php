<?php

namespace App\Http\Controllers;

use App\Models\AttentionRoute;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class AttentionRouteController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $riskFilter = $request->query('risk');
        $search = $request->query('search');

        $query = AttentionRoute::active();

        if ($category) {
            $query->where('category', $category);
        }

        if ($riskFilter) {
            $query->where(function ($q) use ($riskFilter) {
                $q->where('risk_level', $riskFilter)
                  ->orWhere('risk_level', 'todos');
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $routes = $query->orderBy('is_emergency', 'desc')->orderBy('order', 'asc')->get();
        $emergencies = AttentionRoute::emergencies()->get();

        $primaryPhone = SystemSetting::get('emergency_primary_phone', '106');
        $nationalPhone = SystemSetting::get('emergency_national_phone', '192');

        $user = $request->user();
        $userRisk = $user && $user->latestAssessment ? $user->latestAssessment->risk_level : null;

        return view('routes.index', compact('routes', 'emergencies', 'primaryPhone', 'nationalPhone', 'userRisk', 'category', 'riskFilter', 'search'));
    }
}
