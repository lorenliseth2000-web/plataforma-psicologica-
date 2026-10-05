<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechniqueMedia extends Model
{
    use HasFactory;

    protected $table = 'technique_media';

    protected $fillable = [
        'technique_id',
        'resource_type',
        'title',
        'path',
    ];

    public function technique(): BelongsTo
    {
        return $this->belongsTo(Technique::class);
    }
}
