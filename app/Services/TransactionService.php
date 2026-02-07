<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\MutasiSaldo;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Guest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    protected ProviderSelector $providerSelector;
    protected ReferralService $referralService;

    public function __construct(ProviderSelector $providerSelector, ReferralService $referralService)
    {
        $this->providerSelector = $providerSelector;
        $this->referralService = $referralService;
    }

    /**
     * Process transaction for registered user (pay from saldo)
     */
    public function processForUser(User $user, Produk $produk, string $tujuan): Transaksi
    {
        return DB::transaction(function () use ($user, $produk, $tujuan) {
            $currency = $user->currency;
            $hargaIdr = $produk->harga_jual_idr;
            $hargaUser = $hargaIdr / $currency->rate_to_idr;

            // Check saldo
            if ($user->saldo < $hargaUser) {
                throw new \Exception('Saldo tidak mencukupi. Dibutuhkan: ' . number_format($hargaUser, 2) . ' ' . $currency->code);
            }

            // Deduct saldo
            $saldoSebelum = $user->saldo;
            $user->saldo -= $hargaUser;
            $user->save();

            // Record mutasi
            MutasiSaldo::create([
                'user_id' => $user->id,
                'tipe' => 'transaksi',
                'jumlah' => -$hargaUser,
                'currency_id' => $currency->id,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $user->saldo,
                'keterangan' => 'Pembelian ' . $produk->nama_produk . ' ke ' . $tujuan,
            ]);

            // Send to provider with auto-retry
            $result = $this->providerSelector->sendOrderWithRetry($produk, $tujuan);

            $providerId = $result['provider_id'] ?? $produk->provider_id;
            $modal = $result['harga_beli'] ?? $produk->harga_beli_idr;
            $laba = $hargaIdr - $modal;

            // Create transaksi
            $transaksi = Transaksi::create([
                'trx_id' => $this->generateTrxId(),
                'user_id' => $user->id,
                'guest_id' => null,
                'produk_id' => $produk->id,
                'provider_id' => $providerId,
                'payment_id' => null,
                'referral_komisi' => 0,
                'tujuan' => $tujuan,
                'harga' => $hargaIdr,
                'modal' => $modal,
                'laba' => $laba,
                'currency_id' => $currency->id,
                'sn' => $result['sn'],
                'provider_ref' => $result['ref'],
                'response_provider' => $result['response'],
                'status' => $result['status'] === 'failed' ? 'failed' : 'success',
            ]);

            // If failed, refund saldo
            if ($transaksi->status === 'failed') {
                $this->refundUser($user, $hargaUser, $currency, $transaksi);
            } else {
                // Deduct provider saldo
                if ($result['provider_id']) {
                    $provider = \App\Models\Provider::find($result['provider_id']);
                    if ($provider) {
                        $provider->decrement('saldo', $modal);
                    }
                }

                // Process referral commission
                $komisi = $this->referralService->processCommission($user, $transaksi);
                if ($komisi > 0) {
                    $transaksi->update(['referral_komisi' => $komisi]);
                }
            }

            return $transaksi;
        });
    }

    /**
     * Process transaction for guest (requires payment first)
     */
    public function processForGuest(Guest $guest, Produk $produk, string $tujuan, int $paymentId): Transaksi
    {
        return DB::transaction(function () use ($guest, $produk, $tujuan, $paymentId) {
            $currency = $guest->currency;
            $hargaIdr = $produk->harga_jual_idr;

            // Send to provider with auto-retry
            $result = $this->providerSelector->sendOrderWithRetry($produk, $tujuan);

            $providerId = $result['provider_id'] ?? $produk->provider_id;
            $modal = $result['harga_beli'] ?? $produk->harga_beli_idr;
            $laba = $hargaIdr - $modal;

            $transaksi = Transaksi::create([
                'trx_id' => $this->generateTrxId(),
                'user_id' => null,
                'guest_id' => $guest->id,
                'produk_id' => $produk->id,
                'provider_id' => $providerId,
                'payment_id' => $paymentId,
                'referral_komisi' => 0,
                'tujuan' => $tujuan,
                'harga' => $hargaIdr,
                'modal' => $modal,
                'laba' => $laba,
                'currency_id' => $currency->id,
                'sn' => $result['sn'],
                'provider_ref' => $result['ref'],
                'response_provider' => $result['response'],
                'status' => $result['status'] === 'failed' ? 'failed' : 'success',
            ]);

            // Deduct provider saldo on success
            if ($transaksi->status !== 'failed' && $result['provider_id']) {
                $provider = \App\Models\Provider::find($result['provider_id']);
                if ($provider) {
                    $provider->decrement('saldo', $modal);
                }
            }

            return $transaksi;
        });
    }

    /**
     * Refund user saldo on failed transaction
     */
    protected function refundUser(User $user, float $amount, Currency $currency, Transaksi $transaksi): void
    {
        $saldoSebelum = $user->saldo;
        $user->saldo += $amount;
        $user->save();

        MutasiSaldo::create([
            'user_id' => $user->id,
            'tipe' => 'refund',
            'jumlah' => $amount,
            'currency_id' => $currency->id,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $user->saldo,
            'keterangan' => 'Refund transaksi gagal #' . $transaksi->trx_id,
        ]);
    }

    protected function generateTrxId(): string
    {
        return 'TRX' . date('Ymd') . strtoupper(Str::random(8));
    }
}
