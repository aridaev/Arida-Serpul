<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'symbol', 'rate_to_idr', 'status',
    ];

    protected $casts = [
        'rate_to_idr' => 'decimal:4',
        'status' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function convertFromIdr(float $amountIdr): float
    {
        return $amountIdr / $this->rate_to_idr;
    }

    public function convertToIdr(float $amount): float
    {
        return $amount * $this->rate_to_idr;
    }
}
