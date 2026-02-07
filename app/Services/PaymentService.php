<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Guest;
use App\Models\MutasiSaldo;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Create a manual payment (bank transfer / manual)
     */
    public function createManualPayment(
        ?User $user,
        ?Guest $guest,
        float $jumlah,
        Currency $currency,
        string $metode,
        float $fee = 0
    ): Payment {
        $kodeUnik = $this->generateKodeUnik();

        return Payment::create([
            'user_id' => $user?->id,
            'guest_id' => $guest?->id,
            'invoice_id' => $this->generateInvoiceId(),
            'metode' => $metode,
            'tipe_pembayaran' => 'manual',
            'jumlah' => $jumlah,
            'currency_id' => $currency->id,
            'fee' => $fee,
            'kode_unik' => $kodeUnik,
            'total_bayar' => $jumlah + $fee + $kodeUnik,
            'status' => 'pending',
        ]);
    }

    /**
     * Create auto payment via gateway
     */
    public function createAutoPayment(
        ?User $user,
        ?Guest $guest,
        float $jumlah,
        Currency $currency,
        string $metode,
        float $fee = 0
    ): Payment {
        $kodeUnik = $this->generateKodeUnik();

        $payment = Payment::create([
            'user_id' => $user?->id,
            'guest_id' => $guest?->id,
            'invoice_id' => $this->generateInvoiceId(),
            'metode' => $metode,
            'tipe_pembayaran' => 'auto',
            'jumlah' => $jumlah,
            'currency_id' => $currency->id,
            'fee' => $fee,
            'kode_unik' => $kodeUnik,
            'total_bayar' => $jumlah + $fee + $kodeUnik,
            'status' => 'pending',
        ]);

        // TODO: Integrate with actual payment gateway (Midtrans, Xendit, etc.)
        // $gatewayResponse = PaymentGateway::charge($payment);
        // $payment->update([
        //     'provider_payment' => $gatewayResponse['provider'],
        //     'response_payment' => json_encode($gatewayResponse),
        // ]);

        return $payment;
    }

    /**
     * Upload payment proof (for manual payments)
     * File stored in non-public storage, renamed randomly, validated type & size
     */
    public function uploadProof(Payment $payment, UploadedFile $file): PaymentProof
    {
        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('Tipe file tidak diizinkan. Gunakan: jpg, png, pdf');
        }

        // Validate file size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            throw new \Exception('Ukuran file maksimal 2MB');
        }

        // Store with random name in non-public storage
        $randomName = Str::random(40) . '.' . $extension;
        $path = $file->storeAs('payment-proofs', $randomName, 'local');

        $payment->update(['status' => 'waiting_approval']);

        return PaymentProof::create([
            'payment_id' => $payment->id,
            'file_path' => $path,
            'uploaded_at' => now(),
            'status' => 'pending',
        ]);
    }

    /**
     * Admin verifies payment proof
     */
    public function verifyProof(PaymentProof $proof, int $adminId, bool $valid): void
    {
        DB::transaction(function () use ($proof, $adminId, $valid) {
            $proof->update([
                'verified_by' => $adminId,
                'verified_at' => now(),
                'status' => $valid ? 'valid' : 'invalid',
            ]);

            $payment = $proof->payment;

            if ($valid) {
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // If payment is for deposit, add saldo to user
                if ($payment->user_id) {
                    $user = $payment->user;
                    $saldoSebelum = $user->saldo;
                    $currency = $payment->currency;
                    $amountInUserCurrency = $payment->jumlah / $currency->rate_to_idr * $user->currency->rate_to_idr;

                    // If same currency, use jumlah directly
                    if ($payment->currency_id === $user->currency_id) {
                        $amountInUserCurrency = $payment->jumlah;
                    }

                    $user->saldo += $amountInUserCurrency;
                    $user->save();

                    MutasiSaldo::create([
                        'user_id' => $user->id,
                        'tipe' => 'deposit',
                        'jumlah' => $amountInUserCurrency,
                        'currency_id' => $user->currency_id,
                        'saldo_sebelum' => $saldoSebelum,
                        'saldo_sesudah' => $user->saldo,
                        'keterangan' => 'Deposit via ' . $payment->metode . ' - INV#' . $payment->invoice_id,
                    ]);
                }
            } else {
                $payment->update(['status' => 'rejected']);
            }
        });
    }

    /**
     * Generate 3-digit unique code (1-999) for easy payment validation.
     * Ensures no duplicate active kode_unik for pending/waiting payments.
     */
    protected function generateKodeUnik(): int
    {
        $maxAttempts = 50;
        for ($i = 0; $i < $maxAttempts; $i++) {
            $kode = rand(1, 999);
            $exists = Payment::where('kode_unik', $kode)
                ->whereIn('status', ['pending', 'waiting_approval'])
                ->exists();
            if (!$exists) {
                return $kode;
            }
        }
        return rand(1, 999);
    }

    protected function generateInvoiceId(): string
    {
        return 'INV' . date('Ymd') . strtoupper(Str::random(8));
    }
}
