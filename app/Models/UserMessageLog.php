<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMessageLog extends Model
{
    public $timestamps = false;

    protected $table = 'user_message_log';

    protected $fillable = [
        'user_id',
        'message_id',
        'shown_at',
    ];

    protected function casts(): array
    {
        return [
            'shown_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(MotivationalMessage::class, 'message_id');
    }
}
