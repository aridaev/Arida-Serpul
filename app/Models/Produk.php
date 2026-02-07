<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'kategori_id', 'brand', 'kode_provider', 'nama_produk', 'slug',
        'provider_id', 'harga_beli_idr', 'harga_jual_idr', 'status',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $casts = [
        'harga_beli_idr' => 'decimal:2',
        'harga_jual_idr' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function hargaInCurrency(Currency $currency): float
    {
        return $this->harga_jual_idr / $currency->rate_to_idr;
    }

    public function laba(): float
    {
        return $this->harga_jual_idr - $this->harga_beli_idr;
    }
}
