<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technique extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'benefits',
        'instructions',
        'animation_type',
        'animation_path',
        'audio_path',
        'duration_minutes',
        'active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'instructions' => 'array',
            'active' => 'boolean',
            'duration_minutes' => 'integer',
            'order' => 'integer',
        ];
    }

    public function media(): HasMany
    {
        return $this->hasMany(TechniqueMedia::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TechniqueSession::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order', 'asc');
    }

    public function scopeAnxiety($query)
    {
        return $query->where('category', 'ansiedad');
    }

    public function scopeStress($query)
    {
        return $query->where('category', 'estres');
    }
}
