<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReminder extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'label',
        'reminder_time',
        'frequency',
        'days_of_week',
        'active',
        'last_triggered_at',
    ];

    protected function casts(): array
    {
        return [
            'days_of_week' => 'array',
            'active' => 'boolean',
            'last_triggered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Labels en español por tipo de recordatorio
     */
    public static function typeLabels(): array
    {
        return [
            'respiracion'       => 'Ejercicio de respiración',
            'relajacion'        => 'Práctica de relajación',
            'registro_emocional'=> 'Registrar estado emocional',
            'pausa'             => 'Pausa consciente',
            'progreso'          => 'Revisar mi progreso',
        ];
    }

    /**
     * Labels de frecuencia
     */
    public static function frequencyLabels(): array
    {
        return [
            'daily'    => 'Todos los días',
            'weekdays' => 'Lunes a viernes',
            'weekends' => 'Fines de semana',
            'custom'   => 'Días personalizados',
        ];
    }

    /**
     * Íconos SVG path por tipo
     */
    public static function typeIcon(string $type): string
    {
        return match ($type) {
            'respiracion'        => 'M12 4C7.6 4 4 7.6 4 12s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 2c1.1 0 2.1.3 3 .7L5.7 15c-.4-.9-.7-1.9-.7-3 0-3.3 2.7-6 6-6zm0 12c-1.1 0-2.1-.3-3-.7l9.3-9.3c.4.9.7 1.9.7 3 0 3.3-2.7 6-6 6z',
            'relajacion'         => 'M12 2a10 10 0 100 20A10 10 0 0012 2zm0 18a8 8 0 110-16 8 8 0 010 16zm-1-9H7v2h4v4h2v-4h4v-2h-4V7h-2v4z',
            'registro_emocional' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            'pausa'              => 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'progreso'           => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            default              => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        };
    }
}
