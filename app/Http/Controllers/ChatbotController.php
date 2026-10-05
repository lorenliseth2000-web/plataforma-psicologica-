<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    public function __construct(protected ChatbotService $chatbot) {}

    public function index()
    {
        // Limpiar historial al entrar de nuevo (privacidad)
        if (request()->query('nueva')) {
            session()->forget('chatbot_history');
        }
        return view('chatbot.index');
    }

    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = trim($request->input('message'));
        $history = session('chatbot_history', []);

        // Añadir mensaje del usuario al historial
        $history[] = ['role' => 'user', 'content' => $userMessage];

        // Detectar crisis antes de llamar a la IA
        $crisisCheck = $this->chatbot->detectCrisis($userMessage);

        if ($crisisCheck['is_crisis']) {
            $response = $crisisCheck['response'];
            $isCrisis = true;
        } else {
            $response = $this->chatbot->respond($history);
            $isCrisis = false;
        }

        // Añadir respuesta al historial
        $history[] = ['role' => 'assistant', 'content' => $response];

        // Guardar historial en sesión (máx. 30 turnos por privacidad)
        if (count($history) > 60) {
            $history = array_slice($history, -60);
        }
        session(['chatbot_history' => $history]);

        return response()->json([
            'response' => $response,
            'is_crisis' => $isCrisis,
        ]);
    }
}
