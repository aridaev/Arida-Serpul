<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'guest_id', 'invoice_id', 'metode', 'tipe_pembayaran',
        'jumlah', 'currency_id', 'fee', 'kode_unik', 'total_bayar', 'status',
        'provider_payment', 'response_payment', 'paid_at',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'fee' => 'decimal:2',
        'kode_unik' => 'integer',
        'total_bayar' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function proofs()
    {
        return $this->hasMany(PaymentProof::class);
    }
}
