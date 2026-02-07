<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiSaldo extends Model
{
    use HasFactory;

    protected $table = 'mutasi_saldos';

    protected $fillable = [
        'user_id', 'tipe', 'jumlah', 'currency_id',
        'saldo_sebelum', 'saldo_sesudah', 'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
