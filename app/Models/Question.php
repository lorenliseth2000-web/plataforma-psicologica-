<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'text',
        'symptom_focus',
        'weight',
        'order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'weight' => 'integer',
            'order' => 'integer',
        ];
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order', 'asc');
    }

    public function scopeAnxiety($query)
    {
        return $query->where('type', 'ansiedad');
    }

    public function scopeStress($query)
    {
        return $query->where('type', 'estres');
    }
}
