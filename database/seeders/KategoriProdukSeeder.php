<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Pulsa', 'icon' => '📱'],
            ['nama' => 'Paket Data', 'icon' => '🌐'],
            ['nama' => 'Token PLN', 'icon' => '⚡'],
            ['nama' => 'Game', 'icon' => '🎮'],
            ['nama' => 'E-Wallet', 'icon' => '💳'],
            ['nama' => 'BPJS', 'icon' => '🏥'],
            ['nama' => 'TV Kabel', 'icon' => '📺'],
            ['nama' => 'Voucher', 'icon' => '🎟️'],
        ];

        foreach ($kategoris as $k) {
            $k['slug'] = Str::slug($k['nama']);
            KategoriProduk::create($k);
        }
    }
}
