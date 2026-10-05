<?php

namespace App\Services;

class RelaxationCatalog
{
    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        return array_map(fn (array $session) => $this->hydrate($session), config('relaxation.sessions', []));
    }

    public function find(string $slug): ?array
    {
        foreach (config('relaxation.sessions', []) as $session) {
            if (($session['slug'] ?? '') === $slug) {
                return $this->hydrate($session);
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $session
     * @return array<string, mixed>
     */
    protected function hydrate(array $session): array
    {
        $relative = trim((string) config('relaxation.audio_path', 'audio/relajacion'), '/');
        $session['audio_url'] = $this->resolveAudioUrl($relative, $session['slug']);
        $session['duration_label'] = $this->formatDuration((int) ($session['duration_seconds'] ?? 0));

        return $session;
    }

    protected function resolveAudioUrl(string $relative, string $slug): ?string
    {
        foreach (['mp3', 'wav', 'ogg', 'm4a'] as $ext) {
            $path = public_path($relative.'/'.$slug.'.'.$ext);
            if (is_file($path)) {
                return asset($relative.'/'.$slug.'.'.$ext);
            }
        }

        return null;
    }

    protected function formatDuration(int $seconds): string
    {
        $minutes = (int) floor($seconds / 60);

        return $minutes.' min';
    }
}
