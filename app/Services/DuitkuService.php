<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService implements PaymentGatewayInterface
{
    protected string $merchantCode;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantCode = Setting::get('pg_duitku_merchant_code', '');
        $this->apiKey = Setting::get('pg_duitku_api_key', '');

        $mode = Setting::get('pg_duitku_mode', 'sandbox');
        $this->baseUrl = $mode === 'production'
            ? 'https://passport.duitku.com/webapi/api/merchant'
            : 'https://sandbox.duitku.com/webapi/api/merchant';
    }

    public function getName(): string
    {
        return 'duitku';
    }

    public function isConfigured(): bool
    {
        return !empty($this->merchantCode) && !empty($this->apiKey);
    }

    public function getPaymentChannels(): array
    {
        return [
            ['code' => 'BC', 'name' => 'BCA Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'M2', 'name' => 'Mandiri Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'VA', 'name' => 'Maybank Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'I1', 'name' => 'BNI Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'B1', 'name' => 'CIMB Niaga Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'BT', 'name' => 'Permata Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'A1', 'name' => 'ATM Bersama', 'group' => 'Virtual Account'],
            ['code' => 'AG', 'name' => 'Bank Artha Graha', 'group' => 'Virtual Account'],
            ['code' => 'NC', 'name' => 'Bank Neo/BNC', 'group' => 'Virtual Account'],
            ['code' => 'BR', 'name' => 'BRI Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'S1', 'name' => 'Bank Sahabat Sampoerna', 'group' => 'Virtual Account'],
            ['code' => 'DM', 'name' => 'DANA', 'group' => 'E-Wallet'],
            ['code' => 'OV', 'name' => 'OVO', 'group' => 'E-Wallet'],
            ['code' => 'SA', 'name' => 'ShopeePay', 'group' => 'E-Wallet'],
            ['code' => 'LQ', 'name' => 'LinkAja', 'group' => 'E-Wallet'],
            ['code' => 'SP', 'name' => 'QRIS', 'group' => 'QR Code'],
            ['code' => 'FT', 'name' => 'Alfamart', 'group' => 'Retail'],
            ['code' => 'IR', 'name' => 'Indomaret', 'group' => 'Retail'],
        ];
    }

    public function createTransaction(array $params): ?array
    {
        $merchantRef = $params['merchant_ref'];
        $amount = (int) $params['amount'];
        $method = $params['method'];

        $datetime = now()->format('Y-m-d H:i:s');
        $signature = md5($this->merchantCode . $merchantRef . $amount . $this->apiKey);

        $payload = [
            'merchantCode' => $this->merchantCode,
            'paymentAmount' => $amount,
            'paymentMethod' => $method,
            'merchantOrderId' => $merchantRef,
            'productDetails' => $params['description'] ?? 'Order #' . $merchantRef,
            'customerVaName' => $params['customer_name'] ?? 'Guest',
            'email' => $params['customer_email'] ?? 'guest@pulsapro.com',
            'phoneNumber' => $params['customer_phone'] ?? '',
            'callbackUrl' => $params['callback_url'] ?? url('/api/payment-gateway/callback'),
            'returnUrl' => $params['return_url'] ?? url('/track?invoice_id=' . $merchantRef),
            'expiryPeriod' => 1440,
            'signature' => $signature,
        ];

        try {
            $response = Http::post($this->baseUrl . '/v2/inquiry', $payload);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['paymentUrl'])) {
                    return [
                        'reference' => $data['reference'] ?? '',
                        'pay_url' => $data['paymentUrl'] ?? '',
                        'pay_code' => $data['vaNumber'] ?? $data['paymentUrl'] ?? '',
                        'amount' => $data['amount'] ?? $amount,
                        'expired_at' => null,
                        'raw' => $data,
                    ];
                }
            }

            Log::error('Duitku createTransaction failed', ['response' => $response->json()]);
        } catch (\Exception $e) {
            Log::error('Duitku createTransaction: ' . $e->getMessage());
        }
        return null;
    }

    public function getTransactionDetail(string $reference): ?array
    {
        $signature = md5($this->merchantCode . $reference . $this->apiKey);

        try {
            $response = Http::post($this->baseUrl . '/transactionStatus', [
                'merchantCode' => $this->merchantCode,
                'merchantOrderId' => $reference,
                'signature' => $signature,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Duitku getTransactionDetail: ' . $e->getMessage());
        }
        return null;
    }

    public function verifyCallback(string $payload): bool
    {
        $data = json_decode($payload, true);
        $merchantOrderId = $data['merchantOrderId'] ?? '';
        $amount = $data['amount'] ?? '';
        $expected = md5($this->merchantCode . $amount . $merchantOrderId . $this->apiKey);
        $received = $data['signature'] ?? '';
        return hash_equals($expected, $received);
    }

    public function parseCallback(string $payload): array
    {
        $data = json_decode($payload, true);
        $resultCode = $data['resultCode'] ?? '';
        $statusMap = ['00' => 'paid', '01' => 'pending', '02' => 'failed'];

        return [
            'merchant_ref' => $data['merchantOrderId'] ?? '',
            'status' => $statusMap[$resultCode] ?? 'pending',
            'raw' => $data,
        ];
    }
}
