<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'no_hp', 'email', 'currency_id',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
