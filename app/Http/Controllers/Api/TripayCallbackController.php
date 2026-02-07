<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentGatewayManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TripayCallbackController extends Controller
{
    public function handle(Request $request, ?string $driver = null)
    {
        $gateway = PaymentGatewayManager::driver($driver);
        $json = $request->getContent();

        if (!$gateway->verifyCallback($json)) {
            Log::warning("PG callback [{$gateway->getName()}]: invalid signature");
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $parsed = $gateway->parseCallback($json);
        $merchantRef = $parsed['merchant_ref'] ?? null;
        $status = $parsed['status'] ?? 'pending';

        if (!$merchantRef) {
            return response()->json(['success' => false, 'message' => 'Missing merchant_ref'], 400);
        }

        $payment = Payment::where('invoice_id', $merchantRef)->first();
        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        if ($status === 'paid') {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'response_payment' => $json,
            ]);
        } elseif (in_array($status, ['expired', 'failed'])) {
            $payment->update([
                'status' => $status,
                'response_payment' => $json,
            ]);
        }

        Log::info("PG callback [{$gateway->getName()}] {$status}: {$merchantRef}");
        return response()->json(['success' => true]);
    }
}
