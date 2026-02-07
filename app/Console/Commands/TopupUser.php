<?php

namespace App\Console\Commands;

use App\Models\MutasiSaldo;
use App\Models\User;
use Illuminate\Console\Command;

class TopupUser extends Command
{
    protected $signature = 'user:topup {username} {amount}';
    protected $description = 'Manually topup user saldo';

    public function handle()
    {
        $user = User::where('username', $this->argument('username'))->first();
        if (!$user) {
            $this->error('User not found.');
            return Command::FAILURE;
        }

        $amount = (float) $this->argument('amount');
        $saldoSebelum = $user->saldo;
        $user->saldo += $amount;
        $user->save();

        MutasiSaldo::create([
            'user_id' => $user->id,
            'tipe' => 'deposit',
            'jumlah' => $amount,
            'currency_id' => $user->currency_id,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $user->saldo,
            'keterangan' => 'Manual topup by admin via CLI',
        ]);

        $this->info("Topup {$amount} to {$user->username}. New saldo: {$user->saldo}");
        return Command::SUCCESS;
    }
}
