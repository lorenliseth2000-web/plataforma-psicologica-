<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'score_anxiety',
        'score_stress',
        'total_score',
        'risk_level',
        'non_diagnostic_feedback',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'score_anxiety' => 'integer',
            'score_stress' => 'integer',
            'total_score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    public function isHighRisk(): bool
    {
        return $this->risk_level === 'alto';
    }

    public function isModerateRisk(): bool
    {
        return $this->risk_level === 'moderado';
    }

    public function isLowRisk(): bool
    {
        return $this->risk_level === 'bajo';
    }
}
