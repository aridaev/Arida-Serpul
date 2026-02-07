<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['kode' => 'bca', 'nama_bank' => 'Bank BCA', 'no_rekening' => '1234567890', 'atas_nama' => 'PT Pulsa Pro', 'tipe' => 'bank', 'urutan' => 1],
            ['kode' => 'bni', 'nama_bank' => 'Bank BNI', 'no_rekening' => '0987654321', 'atas_nama' => 'PT Pulsa Pro', 'tipe' => 'bank', 'urutan' => 2],
            ['kode' => 'bri', 'nama_bank' => 'Bank BRI', 'no_rekening' => '5566778899', 'atas_nama' => 'PT Pulsa Pro', 'tipe' => 'bank', 'urutan' => 3],
            ['kode' => 'mandiri', 'nama_bank' => 'Bank Mandiri', 'no_rekening' => '1122334455', 'atas_nama' => 'PT Pulsa Pro', 'tipe' => 'bank', 'urutan' => 4],
            ['kode' => 'dana', 'nama_bank' => 'DANA', 'no_rekening' => '081234567890', 'atas_nama' => 'Pulsa Pro', 'tipe' => 'ewallet', 'urutan' => 5],
            ['kode' => 'ovo', 'nama_bank' => 'OVO', 'no_rekening' => '081234567890', 'atas_nama' => 'Pulsa Pro', 'tipe' => 'ewallet', 'urutan' => 6],
            ['kode' => 'gopay', 'nama_bank' => 'GoPay', 'no_rekening' => '081234567890', 'atas_nama' => 'Pulsa Pro', 'tipe' => 'ewallet', 'urutan' => 7],
        ];

        foreach ($banks as $b) {
            BankAccount::create($b);
        }
    }
}
