<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username', 'password', 'nama', 'no_hp', 'email',
        'saldo', 'currency_id', 'level', 'referral_code',
        'referred_by', 'komisi_referral', 'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'saldo' => 'decimal:2',
        'komisi_referral' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function mutasiSaldos()
    {
        return $this->hasMany(MutasiSaldo::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function referralLogs()
    {
        return $this->hasMany(ReferralLog::class);
    }
}
