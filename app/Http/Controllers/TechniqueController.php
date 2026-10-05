<?php

namespace App\Http\Controllers;

use App\Models\Technique;
use App\Models\TechniqueSession;
use Illuminate\Http\Request;

class TechniqueController extends Controller
{
    /**
     * Catálogo público/autenticado de técnicas.
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $query = Technique::active();

        if ($category && in_array($category, ['ansiedad', 'estres'])) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $techniques = $query->orderBy('order', 'asc')->get();

        // Obtener historial de sesiones recientes del usuario para mostrar técnicas completadas
        $completedTechniqueIds = [];
        if ($request->user()) {
            $completedTechniqueIds = $request->user()->techniqueSessions()
                ->pluck('technique_id')
                ->unique()
                ->toArray();
        }

        return view('techniques.index', compact('techniques', 'category', 'search', 'completedTechniqueIds'));
    }

    /**
     * Vista interactiva de práctica guiada de una técnica.
     */
    public function show(Technique $technique)
    {
        // La reestructuración cognitiva tiene su propio ejercicio interactivo
        if ($technique->slug === 'reestructuracion-cognitiva') {
            return view('techniques.cognitive-exercise', compact('technique'));
        }

        $relatedTechniques = Technique::active()
            ->where('category', $technique->category)
            ->where('id', '!=', $technique->id)
            ->take(3)
            ->get();

        return view('techniques.show', compact('technique', 'relatedTechniques'));
    }

    /**
     * Guarda el registro de sesión y retroalimentación del usuario.
     */
    public function storeSession(Request $request, Technique $technique)
    {
        $validated = $request->validate([
            'duration_seconds' => ['required', 'integer', 'min:1'],
            'rating_before'    => ['nullable', 'integer', 'between:1,10'],
            'rating_after'     => ['nullable', 'integer', 'between:1,10'],
            'satisfaction'     => ['nullable', 'integer', 'between:1,5'],
            'feedback_notes'   => ['nullable', 'string', 'max:1000'],
        ]);

        $session = TechniqueSession::create([
            'user_id'          => $request->user()->id,
            'technique_id'     => $technique->id,
            'duration_seconds' => $validated['duration_seconds'],
            'rating_before'    => $validated['rating_before'] ?? null,
            'rating_after'     => $validated['rating_after'] ?? null,
            'satisfaction'     => $validated['satisfaction'] ?? 5,
            'feedback_notes'   => $validated['feedback_notes'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sesión registrada exitosamente.',
                'session' => $session,
            ]);
        }

        return redirect()->route('techniques.index')
            ->with('status', "¡Felicitaciones! Has completado la práctica de {$technique->name}.");
    }

    /**
     * Guarda una sesión de reestructuración cognitiva interactiva.
     */
    public function storeCognitive(Request $request, Technique $technique)
    {
        $validated = $request->validate([
            'situation'          => ['required', 'string', 'max:2000'],
            'emotion'            => ['nullable', 'string', 'max:50'],
            'emotion_before'     => ['required', 'integer', 'between:1,10'],
            'negative_thought'   => ['required', 'string', 'max:2000'],
            'evidence_for'       => ['nullable', 'string', 'max:2000'],
            'evidence_against'   => ['nullable', 'string', 'max:2000'],
            'cognitive_traps'    => ['nullable', 'array'],
            'cognitive_traps.*'  => ['string', 'max:100'],
            'alternative_thought'=> ['required', 'string', 'max:2000'],
            'emotion_after'      => ['required', 'integer', 'between:1,10'],
            'duration_seconds'   => ['nullable', 'integer', 'min:0'],
        ]);

        $cogSession = \App\Models\CognitiveSession::create([
            'user_id'            => $request->user()->id,
            'situation'          => $validated['situation'],
            'emotion'            => $validated['emotion'] ?? null,
            'emotion_before'     => $validated['emotion_before'],
            'negative_thought'   => $validated['negative_thought'],
            'evidence_for'       => $validated['evidence_for'] ?? null,
            'evidence_against'   => $validated['evidence_against'] ?? null,
            'cognitive_traps'    => $validated['cognitive_traps'] ?? [],
            'alternative_thought'=> $validated['alternative_thought'],
            'emotion_after'      => $validated['emotion_after'],
            'duration_seconds'   => $validated['duration_seconds'] ?? 0,
        ]);

        // También guardar en TechniqueSession para el historial general
        TechniqueSession::create([
            'user_id'          => $request->user()->id,
            'technique_id'     => $technique->id,
            'duration_seconds' => $validated['duration_seconds'] ?? 60,
            'rating_before'    => $validated['emotion_before'],
            'rating_after'     => $validated['emotion_after'],
            'satisfaction'     => 5,
            'feedback_notes'   => 'Ejercicio de reestructuración cognitiva completado.',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'session_id'  => $cogSession->id,
                'improvement' => $cogSession->moodImprovement(),
                'message'     => 'Ejercicio guardado exitosamente.',
            ]);
        }

        return redirect()->route('techniques.cognitive.history')
            ->with('status', '¡Ejercicio guardado! Puedes revisarlo en tu historial.');
    }

    /**
     * Historial de sesiones cognitivas del usuario.
     */
    public function cognitiveHistory(Request $request)
    {
        $sessions = \App\Models\CognitiveSession::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('techniques.cognitive-history', compact('sessions'));
    }
}
