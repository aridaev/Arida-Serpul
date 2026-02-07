<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'dari_user_id', 'trx_id', 'komisi', 'currency_id',
    ];

    protected $casts = [
        'komisi' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function upline()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function downline()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
