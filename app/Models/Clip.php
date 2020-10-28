<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Clip extends Model
{
    protected $appends = [
        'video_url'
    ];

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
        'video_file_disk',
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

    public function getVideoUrlAttribute()
    {
        return Storage::disk($this->video_file_disk)->url($this->video_file_path);
    }

    public static function getGames()
    {
        return static::query()->groupBy('game')->select('game')->get()->map(function ($clip) {
            return $clip->game;
        })->filter()->values();
    }
}
