<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $produks = [
            // Pulsa Telkomsel
            ['kategori_id' => 1, 'brand' => 'Telkomsel', 'kode_provider' => 'TSEL5', 'nama_produk' => 'Pulsa Telkomsel 5.000', 'provider_id' => 1, 'harga_beli_idr' => 5200, 'harga_jual_idr' => 5500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Telkomsel', 'kode_provider' => 'TSEL10', 'nama_produk' => 'Pulsa Telkomsel 10.000', 'provider_id' => 1, 'harga_beli_idr' => 10100, 'harga_jual_idr' => 10500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Telkomsel', 'kode_provider' => 'TSEL25', 'nama_produk' => 'Pulsa Telkomsel 25.000', 'provider_id' => 1, 'harga_beli_idr' => 24800, 'harga_jual_idr' => 25500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Telkomsel', 'kode_provider' => 'TSEL50', 'nama_produk' => 'Pulsa Telkomsel 50.000', 'provider_id' => 1, 'harga_beli_idr' => 49200, 'harga_jual_idr' => 50500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Telkomsel', 'kode_provider' => 'TSEL100', 'nama_produk' => 'Pulsa Telkomsel 100.000', 'provider_id' => 1, 'harga_beli_idr' => 97500, 'harga_jual_idr' => 100000, 'status' => true],

            // Pulsa Indosat
            ['kategori_id' => 1, 'brand' => 'Indosat', 'kode_provider' => 'ISAT5', 'nama_produk' => 'Pulsa Indosat 5.000', 'provider_id' => 1, 'harga_beli_idr' => 5150, 'harga_jual_idr' => 5500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Indosat', 'kode_provider' => 'ISAT10', 'nama_produk' => 'Pulsa Indosat 10.000', 'provider_id' => 1, 'harga_beli_idr' => 10050, 'harga_jual_idr' => 10500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Indosat', 'kode_provider' => 'ISAT25', 'nama_produk' => 'Pulsa Indosat 25.000', 'provider_id' => 1, 'harga_beli_idr' => 24600, 'harga_jual_idr' => 25500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Indosat', 'kode_provider' => 'ISAT50', 'nama_produk' => 'Pulsa Indosat 50.000', 'provider_id' => 1, 'harga_beli_idr' => 49000, 'harga_jual_idr' => 50500, 'status' => true],

            // Pulsa XL
            ['kategori_id' => 1, 'brand' => 'XL', 'kode_provider' => 'XL5', 'nama_produk' => 'Pulsa XL 5.000', 'provider_id' => 2, 'harga_beli_idr' => 5200, 'harga_jual_idr' => 5500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'XL', 'kode_provider' => 'XL10', 'nama_produk' => 'Pulsa XL 10.000', 'provider_id' => 2, 'harga_beli_idr' => 10100, 'harga_jual_idr' => 10500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'XL', 'kode_provider' => 'XL25', 'nama_produk' => 'Pulsa XL 25.000', 'provider_id' => 2, 'harga_beli_idr' => 24700, 'harga_jual_idr' => 25500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'XL', 'kode_provider' => 'XL50', 'nama_produk' => 'Pulsa XL 50.000', 'provider_id' => 2, 'harga_beli_idr' => 49100, 'harga_jual_idr' => 50500, 'status' => true],

            // Pulsa Tri
            ['kategori_id' => 1, 'brand' => 'Tri', 'kode_provider' => 'TRI5', 'nama_produk' => 'Pulsa Tri 5.000', 'provider_id' => 2, 'harga_beli_idr' => 5100, 'harga_jual_idr' => 5500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Tri', 'kode_provider' => 'TRI10', 'nama_produk' => 'Pulsa Tri 10.000', 'provider_id' => 2, 'harga_beli_idr' => 10000, 'harga_jual_idr' => 10500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Tri', 'kode_provider' => 'TRI25', 'nama_produk' => 'Pulsa Tri 25.000', 'provider_id' => 2, 'harga_beli_idr' => 24500, 'harga_jual_idr' => 25500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Tri', 'kode_provider' => 'TRI50', 'nama_produk' => 'Pulsa Tri 50.000', 'provider_id' => 2, 'harga_beli_idr' => 48800, 'harga_jual_idr' => 50500, 'status' => true],

            // Pulsa Smartfren
            ['kategori_id' => 1, 'brand' => 'Smartfren', 'kode_provider' => 'SF5', 'nama_produk' => 'Pulsa Smartfren 5.000', 'provider_id' => 1, 'harga_beli_idr' => 5100, 'harga_jual_idr' => 5500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Smartfren', 'kode_provider' => 'SF10', 'nama_produk' => 'Pulsa Smartfren 10.000', 'provider_id' => 1, 'harga_beli_idr' => 10000, 'harga_jual_idr' => 10500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Smartfren', 'kode_provider' => 'SF25', 'nama_produk' => 'Pulsa Smartfren 25.000', 'provider_id' => 1, 'harga_beli_idr' => 24500, 'harga_jual_idr' => 25500, 'status' => true],
            ['kategori_id' => 1, 'brand' => 'Smartfren', 'kode_provider' => 'SF50', 'nama_produk' => 'Pulsa Smartfren 50.000', 'provider_id' => 1, 'harga_beli_idr' => 48800, 'harga_jual_idr' => 50500, 'status' => true],

            // Paket Data Telkomsel
            ['kategori_id' => 2, 'brand' => 'Telkomsel', 'kode_provider' => 'TSELDATA1', 'nama_produk' => 'Telkomsel Data 1GB/30hr', 'provider_id' => 1, 'harga_beli_idr' => 18000, 'harga_jual_idr' => 20000, 'status' => true],
            ['kategori_id' => 2, 'brand' => 'Telkomsel', 'kode_provider' => 'TSELDATA3', 'nama_produk' => 'Telkomsel Data 3GB/30hr', 'provider_id' => 1, 'harga_beli_idr' => 33000, 'harga_jual_idr' => 36000, 'status' => true],
            ['kategori_id' => 2, 'brand' => 'Telkomsel', 'kode_provider' => 'TSELDATA5', 'nama_produk' => 'Telkomsel Data 5GB/30hr', 'provider_id' => 1, 'harga_beli_idr' => 48000, 'harga_jual_idr' => 52000, 'status' => true],
            ['kategori_id' => 2, 'brand' => 'Telkomsel', 'kode_provider' => 'TSELDATA10', 'nama_produk' => 'Telkomsel Data 10GB/30hr', 'provider_id' => 1, 'harga_beli_idr' => 78000, 'harga_jual_idr' => 85000, 'status' => true],

            // Paket Data Indosat
            ['kategori_id' => 2, 'brand' => 'Indosat', 'kode_provider' => 'ISATDATA1', 'nama_produk' => 'Indosat Data 1GB/30hr', 'provider_id' => 1, 'harga_beli_idr' => 16000, 'harga_jual_idr' => 18000, 'status' => true],
            ['kategori_id' => 2, 'brand' => 'Indosat', 'kode_provider' => 'ISATDATA3', 'nama_produk' => 'Indosat Data 3GB/30hr', 'provider_id' => 1, 'harga_beli_idr' => 30000, 'harga_jual_idr' => 33000, 'status' => true],
            ['kategori_id' => 2, 'brand' => 'Indosat', 'kode_provider' => 'ISATDATA5', 'nama_produk' => 'Indosat Data 5GB/30hr', 'provider_id' => 1, 'harga_beli_idr' => 45000, 'harga_jual_idr' => 49000, 'status' => true],

            // Paket Data XL
            ['kategori_id' => 2, 'brand' => 'XL', 'kode_provider' => 'XLDATA1', 'nama_produk' => 'XL Data 1GB/30hr', 'provider_id' => 2, 'harga_beli_idr' => 17000, 'harga_jual_idr' => 19000, 'status' => true],
            ['kategori_id' => 2, 'brand' => 'XL', 'kode_provider' => 'XLDATA3', 'nama_produk' => 'XL Data 3GB/30hr', 'provider_id' => 2, 'harga_beli_idr' => 32000, 'harga_jual_idr' => 35000, 'status' => true],
            ['kategori_id' => 2, 'brand' => 'XL', 'kode_provider' => 'XLDATA5', 'nama_produk' => 'XL Data 5GB/30hr', 'provider_id' => 2, 'harga_beli_idr' => 46000, 'harga_jual_idr' => 50000, 'status' => true],

            // Token PLN
            ['kategori_id' => 3, 'brand' => 'PLN', 'kode_provider' => 'PLN20', 'nama_produk' => 'Token PLN 20.000', 'provider_id' => 1, 'harga_beli_idr' => 19800, 'harga_jual_idr' => 21000, 'status' => true],
            ['kategori_id' => 3, 'brand' => 'PLN', 'kode_provider' => 'PLN50', 'nama_produk' => 'Token PLN 50.000', 'provider_id' => 1, 'harga_beli_idr' => 49500, 'harga_jual_idr' => 51000, 'status' => true],
            ['kategori_id' => 3, 'brand' => 'PLN', 'kode_provider' => 'PLN100', 'nama_produk' => 'Token PLN 100.000', 'provider_id' => 1, 'harga_beli_idr' => 99000, 'harga_jual_idr' => 101000, 'status' => true],
            ['kategori_id' => 3, 'brand' => 'PLN', 'kode_provider' => 'PLN200', 'nama_produk' => 'Token PLN 200.000', 'provider_id' => 1, 'harga_beli_idr' => 198000, 'harga_jual_idr' => 201000, 'status' => true],

            // Game - Mobile Legends
            ['kategori_id' => 4, 'brand' => 'Mobile Legends', 'kode_provider' => 'ML86', 'nama_produk' => 'Mobile Legends 86 Diamond', 'provider_id' => 3, 'harga_beli_idr' => 18000, 'harga_jual_idr' => 20000, 'status' => true],
            ['kategori_id' => 4, 'brand' => 'Mobile Legends', 'kode_provider' => 'ML172', 'nama_produk' => 'Mobile Legends 172 Diamond', 'provider_id' => 3, 'harga_beli_idr' => 35000, 'harga_jual_idr' => 39000, 'status' => true],
            ['kategori_id' => 4, 'brand' => 'Mobile Legends', 'kode_provider' => 'ML344', 'nama_produk' => 'Mobile Legends 344 Diamond', 'provider_id' => 3, 'harga_beli_idr' => 68000, 'harga_jual_idr' => 75000, 'status' => true],

            // Game - Free Fire
            ['kategori_id' => 4, 'brand' => 'Free Fire', 'kode_provider' => 'FF100', 'nama_produk' => 'Free Fire 100 Diamond', 'provider_id' => 3, 'harga_beli_idr' => 14000, 'harga_jual_idr' => 16000, 'status' => true],
            ['kategori_id' => 4, 'brand' => 'Free Fire', 'kode_provider' => 'FF310', 'nama_produk' => 'Free Fire 310 Diamond', 'provider_id' => 3, 'harga_beli_idr' => 42000, 'harga_jual_idr' => 47000, 'status' => true],

            // Game - PUBG Mobile
            ['kategori_id' => 4, 'brand' => 'PUBG Mobile', 'kode_provider' => 'PUBG60', 'nama_produk' => 'PUBG Mobile 60 UC', 'provider_id' => 3, 'harga_beli_idr' => 13000, 'harga_jual_idr' => 15000, 'status' => true],
            ['kategori_id' => 4, 'brand' => 'PUBG Mobile', 'kode_provider' => 'PUBG325', 'nama_produk' => 'PUBG Mobile 325 UC', 'provider_id' => 3, 'harga_beli_idr' => 65000, 'harga_jual_idr' => 72000, 'status' => true],

            // E-Wallet - DANA
            ['kategori_id' => 5, 'brand' => 'DANA', 'kode_provider' => 'DANA25', 'nama_produk' => 'DANA 25.000', 'provider_id' => 2, 'harga_beli_idr' => 25200, 'harga_jual_idr' => 26000, 'status' => true],
            ['kategori_id' => 5, 'brand' => 'DANA', 'kode_provider' => 'DANA50', 'nama_produk' => 'DANA 50.000', 'provider_id' => 2, 'harga_beli_idr' => 50200, 'harga_jual_idr' => 51500, 'status' => true],
            ['kategori_id' => 5, 'brand' => 'DANA', 'kode_provider' => 'DANA100', 'nama_produk' => 'DANA 100.000', 'provider_id' => 2, 'harga_beli_idr' => 100200, 'harga_jual_idr' => 102000, 'status' => true],

            // E-Wallet - OVO
            ['kategori_id' => 5, 'brand' => 'OVO', 'kode_provider' => 'OVO25', 'nama_produk' => 'OVO 25.000', 'provider_id' => 2, 'harga_beli_idr' => 25300, 'harga_jual_idr' => 26000, 'status' => true],
            ['kategori_id' => 5, 'brand' => 'OVO', 'kode_provider' => 'OVO50', 'nama_produk' => 'OVO 50.000', 'provider_id' => 2, 'harga_beli_idr' => 50300, 'harga_jual_idr' => 51500, 'status' => true],
            ['kategori_id' => 5, 'brand' => 'OVO', 'kode_provider' => 'OVO100', 'nama_produk' => 'OVO 100.000', 'provider_id' => 2, 'harga_beli_idr' => 100300, 'harga_jual_idr' => 102000, 'status' => true],

            // E-Wallet - GoPay
            ['kategori_id' => 5, 'brand' => 'GoPay', 'kode_provider' => 'GOPAY25', 'nama_produk' => 'GoPay 25.000', 'provider_id' => 2, 'harga_beli_idr' => 25200, 'harga_jual_idr' => 26000, 'status' => true],
            ['kategori_id' => 5, 'brand' => 'GoPay', 'kode_provider' => 'GOPAY50', 'nama_produk' => 'GoPay 50.000', 'provider_id' => 2, 'harga_beli_idr' => 50200, 'harga_jual_idr' => 51500, 'status' => true],
            ['kategori_id' => 5, 'brand' => 'GoPay', 'kode_provider' => 'GOPAY100', 'nama_produk' => 'GoPay 100.000', 'provider_id' => 2, 'harga_beli_idr' => 100200, 'harga_jual_idr' => 102000, 'status' => true],

            // E-Wallet - ShopeePay
            ['kategori_id' => 5, 'brand' => 'ShopeePay', 'kode_provider' => 'SPAY25', 'nama_produk' => 'ShopeePay 25.000', 'provider_id' => 2, 'harga_beli_idr' => 25200, 'harga_jual_idr' => 26000, 'status' => true],
            ['kategori_id' => 5, 'brand' => 'ShopeePay', 'kode_provider' => 'SPAY50', 'nama_produk' => 'ShopeePay 50.000', 'provider_id' => 2, 'harga_beli_idr' => 50200, 'harga_jual_idr' => 51500, 'status' => true],
        ];

        foreach ($produks as $p) {
            $p['slug'] = Str::slug($p['nama_produk']);
            Produk::create($p);
        }
    }
}
