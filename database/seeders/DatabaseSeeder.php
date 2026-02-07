<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CurrencySeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            BankAccountSeeder::class,
            KategoriProdukSeeder::class,
            ProviderSeeder::class,
            ProdukSeeder::class,
        ]);
    }
}
