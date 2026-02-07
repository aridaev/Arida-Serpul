<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService implements PaymentGatewayInterface
{
    protected string $secretKey;
    protected string $callbackToken;
    protected string $baseUrl = 'https://api.xendit.co';

    public function __construct()
    {
        $this->secretKey = Setting::get('pg_xendit_secret_key', '');
        $this->callbackToken = Setting::get('pg_xendit_callback_token', '');
    }

    public function getName(): string
    {
        return 'xendit';
    }

    public function isConfigured(): bool
    {
        return !empty($this->secretKey);
    }

    public function getPaymentChannels(): array
    {
        return [
            ['code' => 'BCA', 'name' => 'BCA Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'BNI', 'name' => 'BNI Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'BRI', 'name' => 'BRI Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'MANDIRI', 'name' => 'Mandiri Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'PERMATA', 'name' => 'Permata Virtual Account', 'group' => 'Virtual Account'],
            ['code' => 'ID_OVO', 'name' => 'OVO', 'group' => 'E-Wallet'],
            ['code' => 'ID_DANA', 'name' => 'DANA', 'group' => 'E-Wallet'],
            ['code' => 'ID_SHOPEEPAY', 'name' => 'ShopeePay', 'group' => 'E-Wallet'],
            ['code' => 'ID_LINKAJA', 'name' => 'LinkAja', 'group' => 'E-Wallet'],
            ['code' => 'QRIS', 'name' => 'QRIS', 'group' => 'QR Code'],
            ['code' => 'ALFAMART', 'name' => 'Alfamart', 'group' => 'Retail Outlet'],
            ['code' => 'INDOMARET', 'name' => 'Indomaret', 'group' => 'Retail Outlet'],
        ];
    }

    public function createTransaction(array $params): ?array
    {
        $merchantRef = $params['merchant_ref'];
        $amount = (int) $params['amount'];
        $method = $params['method'];

        try {
            // Xendit uses Invoice API for unified payments
            $payload = [
                'external_id' => $merchantRef,
                'amount' => $amount,
                'description' => $params['description'] ?? 'Order #' . $merchantRef,
                'customer' => [
                    'given_names' => $params['customer_name'] ?? 'Guest',
                    'email' => $params['customer_email'] ?? 'guest@pulsapro.com',
                    'mobile_number' => $this->formatPhone($params['customer_phone'] ?? ''),
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ['email'],
                    'invoice_paid' => ['email'],
                ],
                'success_redirect_url' => $params['return_url'] ?? url('/track?invoice_id=' . $merchantRef),
                'failure_redirect_url' => $params['return_url'] ?? url('/track?invoice_id=' . $merchantRef),
                'payment_methods' => [$method],
                'currency' => 'IDR',
                'invoice_duration' => 86400,
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/v2/invoices', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'reference' => $data['id'] ?? '',
                    'pay_url' => $data['invoice_url'] ?? '',
                    'pay_code' => '',
                    'amount' => $data['amount'] ?? $amount,
                    'expired_at' => $data['expiry_date'] ?? null,
                    'raw' => $data,
                ];
            }

            Log::error('Xendit createTransaction failed', ['response' => $response->json()]);
        } catch (\Exception $e) {
            Log::error('Xendit createTransaction: ' . $e->getMessage());
        }
        return null;
    }

    public function getTransactionDetail(string $reference): ?array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get($this->baseUrl . '/v2/invoices/' . $reference);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Xendit getTransactionDetail: ' . $e->getMessage());
        }
        return null;
    }

    public function verifyCallback(string $payload): bool
    {
        if (empty($this->callbackToken)) return false;
        $headerToken = request()->header('x-callback-token', '');
        return hash_equals($this->callbackToken, $headerToken);
    }

    public function parseCallback(string $payload): array
    {
        $data = json_decode($payload, true);
        $statusMap = ['PAID' => 'paid', 'SETTLED' => 'paid', 'EXPIRED' => 'expired', 'FAILED' => 'failed', 'PENDING' => 'pending'];

        return [
            'merchant_ref' => $data['external_id'] ?? '',
            'status' => $statusMap[$data['status'] ?? ''] ?? 'pending',
            'raw' => $data,
        ];
    }

    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '+62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '62')) {
            $phone = '+' . $phone;
        }
        return $phone;
    }
}
