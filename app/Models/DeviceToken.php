<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DeviceToken extends Model
{
    protected $fillable = [
        'device_id',
        'device_name',
        'device_model',
        'android_version',
        'app_version',
        'api_token',
        'last_seen_at',
        'is_active',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = ['api_token'];

    public function notifications()
    {
        return $this->hasMany(CapturedNotification::class, 'device_id', 'device_id');
    }

    public static function generateToken(): string
    {
        return Str::random(64);
    }
}
