<?php

namespace App\Services;

use App\Models\MutasiSaldo;
use App\Models\ReferralLog;
use App\Models\Transaksi;
use App\Models\User;

class ReferralService
{
    /**
     * Process referral commission when a downline makes a transaction.
     * Returns the commission amount in IDR.
     */
    public function processCommission(User $downline, Transaksi $transaksi): float
    {
        // Check if user has an upline
        if (!$downline->referred_by) {
            return 0;
        }

        $upline = User::find($downline->referred_by);
        if (!$upline || !$upline->status) {
            return 0;
        }

        // Calculate commission based on upline's komisi_referral percentage
        $komisiPersen = $upline->komisi_referral;
        if ($komisiPersen <= 0) {
            return 0;
        }

        $komisiIdr = $transaksi->laba * ($komisiPersen / 100);
        if ($komisiIdr <= 0) {
            return 0;
        }

        // Convert to upline's currency
        $uplineCurrency = $upline->currency;
        $komisiUpline = $komisiIdr / $uplineCurrency->rate_to_idr;

        // Add saldo to upline
        $saldoSebelum = $upline->saldo;
        $upline->saldo += $komisiUpline;
        $upline->save();

        // Record mutasi saldo
        MutasiSaldo::create([
            'user_id' => $upline->id,
            'tipe' => 'referral',
            'jumlah' => $komisiUpline,
            'currency_id' => $uplineCurrency->id,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $upline->saldo,
            'keterangan' => 'Komisi referral dari ' . $downline->username . ' - TRX#' . $transaksi->trx_id,
        ]);

        // Record referral log
        ReferralLog::create([
            'user_id' => $upline->id,
            'dari_user_id' => $downline->id,
            'trx_id' => $transaksi->trx_id,
            'komisi' => $komisiIdr,
            'currency_id' => $uplineCurrency->id,
        ]);

        return $komisiIdr;
    }
}
