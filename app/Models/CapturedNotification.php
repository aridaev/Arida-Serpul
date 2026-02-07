<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapturedNotification extends Model
{
    protected $fillable = [
        'device_id',
        'device_name',
        'package_name',
        'app_name',
        'title',
        'text',
        'big_text',
        'ticker',
        'tag',
        'category',
        'extras',
        'posted_at',
        'is_read',
    ];

    protected $casts = [
        'extras' => 'array',
        'posted_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function device()
    {
        return $this->belongsTo(DeviceToken::class, 'device_id', 'device_id');
    }
}
