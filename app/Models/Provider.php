<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'api_url', 'api_key', 'tipe', 'saldo', 'status',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
