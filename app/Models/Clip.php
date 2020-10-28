<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clip extends Model
{
    protected $fillable = [
        'slug',
        'game',
        'broadcast_id',
        'title',
        'views_at_import',
        'duration',
        'thumbnail_medium',
        'thumbnail_small',
        'thumbnail_tiny',
        'video_file_path',
        'created_at', // fillable for import.
    ];

    public function curator(): BelongsTo
    {
        return $this->belongsTo(Curator::class);
    }

    public function broadcaster(): BelongsTo
    {
        return $this->belongsTo(Broadcaster::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
