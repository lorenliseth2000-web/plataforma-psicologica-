<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmotionalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'anxiety_level',
        'stress_level',
        'mood_level',
        'sleep_hours',
        'notes',
        'log_date',
    ];

    protected function casts(): array
    {
        return [
            'anxiety_level' => 'integer',
            'stress_level' => 'integer',
            'mood_level' => 'integer',
            'sleep_hours' => 'decimal:1',
            'log_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMoodLabelAttribute(): string
    {
        return match ($this->mood_level) {
            1 => 'Muy bajo',
            2 => 'Bajo / Desanimado',
            3 => 'Neutral / Regular',
            4 => 'Bueno / Estable',
            5 => 'Excelente / Muy bien',
            default => 'No especificado',
        };
    }
}
