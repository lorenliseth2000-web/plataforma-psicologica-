<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotivationalMessage extends Model
{
    protected $fillable = [
        'sort_order',
        'category',
        'message',
        'context_tags',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'context_tags' => 'array',
            'active' => 'boolean',
        ];
    }

    public function userLogs(): HasMany
    {
        return $this->hasMany(UserMessageLog::class, 'message_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
