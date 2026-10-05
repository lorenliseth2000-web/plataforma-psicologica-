<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechniqueSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'technique_id',
        'duration_seconds',
        'rating_before',
        'rating_after',
        'satisfaction',
        'feedback_notes',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'rating_before' => 'integer',
            'rating_after' => 'integer',
            'satisfaction' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function technique(): BelongsTo
    {
        return $this->belongsTo(Technique::class);
    }
}
