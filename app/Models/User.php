<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nickname',
        'email',
        'token',
        'refresh_token',
        'avatar_url',
    ];

    protected $hidden = [
        'token',
        'refresh_token',
        'remember_token',
    ];

    public function setTokenAttribute($value)
    {
        $this->attributes['token'] = Crypt::encrypt($value);
    }

    public function getTokenAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    public function setRefreshTokenAttribute($value)
    {
        $this->attributes['refresh_token'] = Crypt::encrypt($value);
    }

    public function getRefreshTokenAttribute($value)
    {
        return Crypt::decrypt($value);
    }
}
