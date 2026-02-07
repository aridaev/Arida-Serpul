<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'IDR', 'symbol' => 'Rp', 'rate_to_idr' => 1, 'status' => true],
            ['code' => 'USD', 'symbol' => '$', 'rate_to_idr' => 15500, 'status' => true],
            ['code' => 'MYR', 'symbol' => 'RM', 'rate_to_idr' => 3500, 'status' => true],
        ];

        foreach ($currencies as $c) {
            Currency::create($c);
        }
    }
}
