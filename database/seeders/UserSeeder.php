<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $idr = Currency::where('code', 'IDR')->first();

        User::create([
            'username' => 'demo',
            'nama' => 'Demo User',
            'email' => 'demo@pulsapro.com',
            'no_hp' => '081234567890',
            'password' => 'demo1234',
            'saldo' => 500000,
            'currency_id' => $idr->id,
            'level' => 'member',
            'referral_code' => strtoupper(Str::random(8)),
            'status' => true,
        ]);

        User::create([
            'username' => 'reseller',
            'nama' => 'Reseller User',
            'email' => 'reseller@pulsapro.com',
            'no_hp' => '081298765432',
            'password' => 'reseller1234',
            'saldo' => 2000000,
            'currency_id' => $idr->id,
            'level' => 'reseller',
            'referral_code' => strtoupper(Str::random(8)),
            'status' => true,
        ]);
    }
}
