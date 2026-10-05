<?php

namespace App\Http\Controllers;

use App\Services\ElevenLabsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoiceController extends Controller
{
    public function __construct(protected ElevenLabsService $elevenLabsService)
    {
    }

    /**
     * Genera o retorna desde caché el audio de voz guiada (Rudra / ElevenLabs en español).
     */
    public function synthesize(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $audioUrl = $this->elevenLabsService->textToSpeech($validated['text']);

        if ($audioUrl) {
            return response()->json([
                'success'   => true,
                'audio_url' => $audioUrl,
                'source'    => 'elevenlabs',
            ]);
        }

        return response()->json([
            'success'   => false,
            'audio_url' => null,
            'fallback'  => true,
            'message'   => 'Usando síntesis local o audio predeterminado.',
        ]);
    }
}
