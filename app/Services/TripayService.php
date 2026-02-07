<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService implements PaymentGatewayInterface
{
    protected string $apiKey;
    protected string $privateKey;
    protected string $merchantCode;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = Setting::get('pg_tripay_api_key', '');
        $this->privateKey = Setting::get('pg_tripay_private_key', '');
        $this->merchantCode = Setting::get('pg_tripay_merchant_code', '');

        $mode = Setting::get('pg_tripay_mode', 'sandbox');
        $this->baseUrl = $mode === 'production'
            ? 'https://tripay.co.id/api'
            : 'https://tripay.co.id/api-sandbox';
    }

    public function getName(): string
    {
        return 'tripay';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->privateKey) && !empty($this->merchantCode);
    }

    public function getPaymentChannels(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/merchant/payment-channel');

            if ($response->successful() && $response->json('success')) {
                return collect($response->json('data', []))->map(fn($ch) => [
                    'code' => $ch['code'],
                    'name' => $ch['name'],
                    'group' => $ch['group'] ?? 'other',
                    'fee_flat' => $ch['total_fee']['flat'] ?? 0,
                    'fee_percent' => $ch['total_fee']['percent'] ?? 0,
                    'icon_url' => $ch['icon_url'] ?? '',
                ])->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Tripay getPaymentChannels: ' . $e->getMessage());
        }
        return [];
    }

    public function createTransaction(array $params): ?array
    {
        $method = $params['method'];
        $merchantRef = $params['merchant_ref'];
        $amount = (int) $params['amount'];

        $signature = hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);

        $payload = [
            'method' => $method,
            'merchant_ref' => $merchantRef,
            'amount' => $amount,
            'customer_name' => $params['customer_name'] ?? 'Guest',
            'customer_email' => $params['customer_email'] ?? 'guest@pulsapro.com',
            'customer_phone' => $params['customer_phone'] ?? '',
            'order_items' => $params['order_items'] ?? [
                ['name' => 'Order #' . $merchantRef, 'price' => $amount, 'quantity' => 1],
            ],
            'callback_url' => $params['callback_url'] ?? url('/api/payment-gateway/callback'),
            'return_url' => $params['return_url'] ?? url('/track'),
            'expired_time' => (int)(now()->addHours(24)->timestamp),
            'signature' => $signature,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->baseUrl . '/transaction/create', $payload);

            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                return [
                    'reference' => $data['reference'] ?? '',
                    'pay_url' => $data['checkout_url'] ?? '',
                    'pay_code' => $data['pay_code'] ?? '',
                    'amount' => $data['amount'] ?? $amount,
                    'expired_at' => isset($data['expired_time']) ? date('Y-m-d H:i:s', $data['expired_time']) : null,
                    'raw' => $data,
                ];
            }

            Log::error('Tripay createTransaction failed', ['response' => $response->json()]);
        } catch (\Exception $e) {
            Log::error('Tripay createTransaction: ' . $e->getMessage());
        }
        return null;
    }

    public function getTransactionDetail(string $reference): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/transaction/detail', ['reference' => $reference]);

            if ($response->successful() && $response->json('success')) {
                return $response->json('data');
            }
        } catch (\Exception $e) {
            Log::error('Tripay getTransactionDetail: ' . $e->getMessage());
        }
        return null;
    }

    public function verifyCallback(string $payload): bool
    {
        $signature = hash_hmac('sha256', $payload, $this->privateKey);
        $callbackSignature = request()->server('HTTP_X_CALLBACK_SIGNATURE', '');
        return hash_equals($signature, $callbackSignature);
    }

    public function parseCallback(string $payload): array
    {
        $data = json_decode($payload, true);
        $statusMap = ['PAID' => 'paid', 'EXPIRED' => 'expired', 'FAILED' => 'failed', 'UNPAID' => 'pending'];

        return [
            'merchant_ref' => $data['merchant_ref'] ?? '',
            'status' => $statusMap[$data['status'] ?? ''] ?? 'pending',
            'raw' => $data,
        ];
    }
}
