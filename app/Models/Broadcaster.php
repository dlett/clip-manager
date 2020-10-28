<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broadcaster extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'channel_url',
        'logo_url',
    ];

    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }

    public function getRouteKeyName()
    {
        return 'name';
    }
}
