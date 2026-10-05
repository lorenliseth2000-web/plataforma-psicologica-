<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttentionRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institution',
        'category',
        'phone',
        'whatsapp',
        'email',
        'website',
        'address',
        'available_hours',
        'risk_level',
        'description',
        'is_emergency',
        'active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_emergency' => 'boolean',
            'active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order', 'asc');
    }

    public function scopeEmergencies($query)
    {
        return $query->where('is_emergency', true)->where('active', true)->orderBy('order', 'asc');
    }

    public function scopeForRiskLevel($query, string $riskLevel)
    {
        return $query->where('active', true)
            ->where(function ($q) use ($riskLevel) {
                $q->where('risk_level', $riskLevel)
                  ->orWhere('risk_level', 'todos');
            });
    }
}
