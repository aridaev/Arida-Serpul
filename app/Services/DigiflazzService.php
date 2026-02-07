<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DigiflazzService
{
    protected string $username;
    protected string $apiKey;
    protected string $baseUrl = 'https://api.digiflazz.com/v1';

    public function __construct()
    {
        $this->username = Setting::get('digiflazz_username', '');
        $this->apiKey = Setting::get('digiflazz_api_key', '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->username) && !empty($this->apiKey);
    }

    /**
     * Generate sign: md5(username + apiKey + ref_id)
     */
    protected function sign(string $refId): string
    {
        return md5($this->username . $this->apiKey . $refId);
    }

    /**
     * Get price list (prabayar).
     * commands: "pricelist", sign: md5(username+apiKey+"pricelist")
     */
    public function getPriceList(string $type = 'prepaid'): array
    {
        $cmd = $type === 'postpaid' ? 'pasca-pricelist' : 'pricelist';
        $sign = md5($this->username . $this->apiKey . $cmd);

        try {
            $response = Http::post($this->baseUrl . '/price-list', [
                'cmd' => $cmd,
                'username' => $this->username,
                'sign' => $sign,
            ]);

            if ($response->successful()) {
                return $response->json('data', []);
            }

            Log::error('DigiFlazz getPriceList failed', ['response' => $response->body()]);
        } catch (\Exception $e) {
            Log::error('DigiFlazz getPriceList: ' . $e->getMessage());
        }
        return [];
    }

    /**
     * Create topup transaction (prabayar).
     */
    public function topup(string $refId, string $pulsaCode, string $hp, ?int $price = null): ?array
    {
        $sign = $this->sign($refId);

        $payload = [
            'username' => $this->username,
            'commands' => 'topup',
            'ref_id' => $refId,
            'hp' => $hp,
            'pulsa_code' => $pulsaCode,
            'sign' => $sign,
        ];

        if ($price) {
            $payload['price'] = $price;
        }

        try {
            $response = Http::post($this->baseUrl . '/transaction', $payload);

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::error('DigiFlazz topup failed', ['response' => $response->body()]);
        } catch (\Exception $e) {
            Log::error('DigiFlazz topup: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Check transaction status.
     */
    public function checkStatus(string $refId, string $pulsaCode, string $hp): ?array
    {
        $sign = $this->sign($refId);

        try {
            $response = Http::post($this->baseUrl . '/transaction', [
                'username' => $this->username,
                'commands' => 'topup',
                'ref_id' => $refId,
                'hp' => $hp,
                'pulsa_code' => $pulsaCode,
                'sign' => $sign,
            ]);

            if ($response->successful()) {
                return $response->json('data');
            }
        } catch (\Exception $e) {
            Log::error('DigiFlazz checkStatus: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Check deposit/balance.
     * sign: md5(username+apiKey+"depo")
     */
    public function checkBalance(): ?array
    {
        $sign = md5($this->username . $this->apiKey . 'depo');

        try {
            $response = Http::post($this->baseUrl . '/cek-saldo', [
                'cmd' => 'deposit',
                'username' => $this->username,
                'sign' => $sign,
            ]);

            if ($response->successful()) {
                return $response->json('data');
            }
        } catch (\Exception $e) {
            Log::error('DigiFlazz checkBalance: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Parse DigiFlazz webhook/callback payload.
     * status: 0=pending, 1=success, 2=failed
     */
    public function parseStatus(array $data): string
    {
        $status = $data['status'] ?? '';
        return match ($status) {
            '1', 1 => 'success',
            '2', 2 => 'failed',
            default => 'pending',
        };
    }
}
