<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ElevenLabsService
{
    protected string $apiKey;
    protected string $voiceId;
    protected string $modelId;

    public function __construct()
    {
        $this->apiKey = (string) config('services.elevenlabs.key', env('ELEVENLABS_API_KEY', ''));
        $this->voiceId = (string) config('services.elevenlabs.voice_id', env('ELEVENLABS_VOICE_ID', 'rHhok70RpCi5GgianXRA')); // Rudra
        $this->modelId = (string) config('services.elevenlabs.model', 'eleven_multilingual_v2');
    }

    /**
     * Verifica si el servicio tiene una API key configurada.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Sintetiza texto a audio MP3 utilizando la voz de Rudra en español.
     * Guarda en caché local para no consumir créditos de API repetidamente.
     *
     * @param string $text
     * @return string|null URL pública del archivo de audio o null si falla
     */
    public function textToSpeech(string $text): ?string
    {
        $text = trim($text);
        if (empty($text)) {
            return null;
        }

        $cacheDir = public_path('audio/tts_cache');
        if (!File::isDirectory($cacheDir)) {
            File::makeDirectory($cacheDir, 0755, true);
        }

        // Hash para la caché basado en voz y texto
        $hash = md5($this->voiceId . '_' . $this->modelId . '_' . $text);
        $filename = "rudra_{$hash}.mp3";
        $filePath = "{$cacheDir}/{$filename}";
        $publicUrl = asset("audio/tts_cache/{$filename}");

        // Si ya está en caché, retornamos inmediatamente
        if (File::exists($filePath) && File::size($filePath) > 1000) {
            return $publicUrl;
        }

        // Si no hay API key configurada, no podemos llamar al API
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $endpoint = "https://api.elevenlabs.io/v1/text-to-speech/{$this->voiceId}";

            $response = Http::withHeaders([
                'xi-api-key'   => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept'       => 'audio/mpeg',
            ])->timeout(20)->post($endpoint, [
                'text'     => $text,
                'model_id' => $this->modelId, // eleven_multilingual_v2 soporta español fluido
                'voice_settings' => [
                    'stability'        => 0.65, // Tono sereno y constante
                    'similarity_boost' => 0.80,
                    'style'            => 0.15, // Suave, pausado e íntimo
                    'use_speaker_boost' => true,
                ],
            ]);

            if ($response->successful()) {
                File::put($filePath, $response->body());
                return $publicUrl;
            }

            Log::warning('ElevenLabs TTS error:', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return null;
        } catch (\Throwable $e) {
            Log::error('ElevenLabs exception: ' . $e->getMessage());
            return null;
        }
    }
}
