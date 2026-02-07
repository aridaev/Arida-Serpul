<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username', 'password', 'role', 'last_login',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'last_login' => 'datetime',
    ];

    public function verifiedProofs()
    {
        return $this->hasMany(PaymentProof::class, 'verified_by');
    }
}
