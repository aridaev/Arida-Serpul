<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'kode', 'nama_bank', 'no_rekening', 'atas_nama',
        'logo_url', 'tipe', 'status', 'urutan',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('urutan');
    }
}
