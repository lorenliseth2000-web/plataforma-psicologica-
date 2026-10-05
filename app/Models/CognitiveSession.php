<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CognitiveSession extends Model
{
    protected $fillable = [
        'user_id',
        'situation',
        'emotion',
        'emotion_before',
        'negative_thought',
        'evidence_for',
        'evidence_against',
        'cognitive_traps',
        'alternative_thought',
        'emotion_after',
        'duration_seconds',
    ];

    protected $casts = [
        'cognitive_traps' => 'array',
        'emotion_before'  => 'integer',
        'emotion_after'   => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Reducción porcentual de malestar */
    public function moodImprovement(): int
    {
        if (!$this->emotion_before) return 0;
        $diff = $this->emotion_before - $this->emotion_after;
        return (int) round(($diff / $this->emotion_before) * 100);
    }
}
