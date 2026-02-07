<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->with('currency')
            ->latest()
            ->paginate(20);

        return view('payment.index', compact('payments'));
    }

    public function deposit()
    {
        $currencies = Currency::where('status', true)->get();
        return view('payment.deposit', compact('currencies'));
    }

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
            'metode' => 'required|string|in:bank_bca,bank_bni,bank_mandiri,bank_bri,ewallet_dana,ewallet_ovo,ewallet_gopay,qris',
        ]);

        $user = Auth::user();
        $currency = $user->currency;

        $payment = $this->paymentService->createManualPayment(
            $user, null, $request->jumlah, $currency, $request->metode
        );

        return redirect()->route('payment.show', $payment->id)
            ->with('success', 'Invoice deposit berhasil dibuat. Silakan upload bukti transfer.');
    }

    public function show(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }
        $payment->load(['currency', 'proofs']);
        return view('payment.show', compact('payment'));
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $this->paymentService->uploadProof($payment, $request->file('file'));
            return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
