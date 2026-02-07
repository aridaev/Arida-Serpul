<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentApiController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $payments = Payment::where('user_id', $request->user()->id)
            ->with('currency')
            ->latest()
            ->paginate(20);

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
            'metode' => 'required|string',
        ]);

        $user = $request->user();
        $payment = $this->paymentService->createManualPayment(
            $user, null, $request->jumlah, $user->currency, $request->metode
        );

        return response()->json([
            'message' => 'Invoice deposit berhasil dibuat',
            'data' => $payment,
        ], 201);
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        if ($payment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $proof = $this->paymentService->uploadProof($payment, $request->file('file'));
            return response()->json([
                'message' => 'Bukti pembayaran berhasil diupload',
                'data' => $proof,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
