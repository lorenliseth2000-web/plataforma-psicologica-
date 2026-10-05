<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AttentionRoute;
use App\Models\Question;
use App\Models\Technique;
use App\Services\AIRecommendationService;
use App\Services\RiskAssessmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AssessmentController extends Controller
{
    public function __construct(
        protected RiskAssessmentService $riskAssessmentService,
        protected AIRecommendationService $aiRecommendationService
    ) {}

    /**
     * Listado histórico de evaluaciones del usuario.
     */
    public function index(Request $request)
    {
        $assessments = $request->user()->assessments()->paginate(10);
        return view('assessments.index', compact('assessments'));
    }

    /**
     * Formulario interactivo de evaluación.
     */
    public function create()
    {
        $anxietyQuestions = Question::active()->anxiety()->get();
        $stressQuestions = Question::active()->stress()->get();
        $allQuestions = Question::active()->get();

        return view('assessments.create', compact('anxietyQuestions', 'stressQuestions', 'allQuestions'));
    }

    /**
     * Procesa y persiste la evaluación clínica.
     */
    public function store(Request $request)
    {
        $request->validate([
            'answers' => ['required', 'array', 'min:5'],
            'answers.*' => ['required', 'integer', 'between:0,3'],
        ], [
            'answers.required' => 'Es necesario responder a las preguntas del cuestionario.',
            'answers.*.between' => 'Los valores de respuesta deben encontrarse entre 0 y 3.',
        ]);

        $user = $request->user();
        $answers = $request->input('answers', []);

        $assessment = $this->riskAssessmentService->processAssessment($user, $answers);

        // Regenerar recomendación inteligente de IA considerando el nuevo test
        $this->aiRecommendationService->getOrGenerateRecommendation($user, forceRefresh: true);

        return redirect()->route('assessments.show', $assessment)
            ->with('status', 'Evaluación completada exitosamente.');
    }

    /**
     * Visualización detallada del informe de resultados.
     */
    public function show(Assessment $assessment)
    {
        Gate::authorize('view', $assessment);

        $assessment->load(['answers.question']);

        // Técnicas recomendadas para el resultado
        $preferredCategory = $assessment->score_anxiety >= $assessment->score_stress ? 'ansiedad' : 'estres';
        $recommendedTechniques = Technique::active()
            ->where('category', $preferredCategory)
            ->take(3)
            ->get();

        // Rutas institucionales según el nivel de riesgo
        $routes = AttentionRoute::forRiskLevel($assessment->risk_level)->take(4)->get();
        $emergencies = AttentionRoute::emergencies()->take(3)->get();

        return view('assessments.show', compact('assessment', 'recommendedTechniques', 'routes', 'emergencies'));
    }
}
