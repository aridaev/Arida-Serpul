<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'trx_id', 'user_id', 'guest_id', 'produk_id', 'provider_id',
        'payment_id', 'referral_komisi', 'tujuan', 'harga', 'modal',
        'laba', 'currency_id', 'sn', 'provider_ref', 'response_provider', 'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'modal' => 'decimal:2',
        'laba' => 'decimal:2',
        'referral_komisi' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
