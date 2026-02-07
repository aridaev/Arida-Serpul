<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'nama' => 'DigiFlazz',
                'api_url' => 'https://api.digiflazz.com/v1/transaction',
                'api_key' => 'your-digiflazz-api-key',
                'tipe' => 'pulsa',
                'saldo' => 5000000,
                'status' => true,
            ],
            [
                'nama' => 'MobilePulsa',
                'api_url' => 'https://api.mobilepulsa.net/v1/legacy/index',
                'api_key' => 'your-mobilepulsa-api-key',
                'tipe' => 'pulsa',
                'saldo' => 3000000,
                'status' => true,
            ],
            [
                'nama' => 'Unipin',
                'api_url' => 'https://api.unipin.com/v1/order',
                'api_key' => 'your-unipin-api-key',
                'tipe' => 'game',
                'saldo' => 2000000,
                'status' => true,
            ],
        ];

        foreach ($providers as $p) {
            Provider::create($p);
        }
    }
}
