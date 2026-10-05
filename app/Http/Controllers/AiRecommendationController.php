<?php

namespace App\Http\Controllers;

use App\Services\AIRecommendationService;
use Illuminate\Http\Request;

class AiRecommendationController extends Controller
{
    public function __construct(
        protected AIRecommendationService $aiRecommendationService
    ) {}

    public function refresh(Request $request)
    {
        $user = $request->user();
        $recommendation = $this->aiRecommendationService->getOrGenerateRecommendation($user, forceRefresh: true);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'recommendation' => $recommendation,
            ]);
        }

        return back()->with('status', 'Recomendación actualizada según tus últimos registros.');
    }
}
