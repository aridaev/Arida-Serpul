<?php

namespace App\Console\Commands;

use App\Models\Provider;
use Illuminate\Console\Command;

class CheckProviderSaldo extends Command
{
    protected $signature = 'provider:saldo';
    protected $description = 'Check all provider saldo balances';

    public function handle()
    {
        $providers = Provider::all();

        $this->table(
            ['ID', 'Nama', 'Tipe', 'Saldo', 'Status'],
            $providers->map(fn($p) => [
                $p->id,
                $p->nama,
                $p->tipe,
                'Rp ' . number_format($p->saldo, 0, ',', '.'),
                $p->status ? 'Active' : 'Off',
            ])
        );

        return Command::SUCCESS;
    }
}
