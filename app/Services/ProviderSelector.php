<?php

namespace App\Services;

use App\Models\Produk;
use App\Models\Provider;
use Illuminate\Support\Collection;

class ProviderSelector
{
    /**
     * Find the best provider for a product:
     * 1. Filter active providers that have this product
     * 2. Filter providers with sufficient saldo
     * 3. Sort by cheapest harga_beli_idr
     * 4. Return the best one
     */
    public function selectBest(Produk $produk): ?Provider
    {
        // Get all active providers that sell this product (same kode_provider)
        $candidates = Produk::where('kode_provider', $produk->kode_provider)
            ->where('status', true)
            ->with(['provider' => function ($q) {
                $q->where('status', true);
            }])
            ->get()
            ->filter(fn($p) => $p->provider && $p->provider->saldo >= $p->harga_beli_idr)
            ->sortBy('harga_beli_idr');

        $bestProduk = $candidates->first();

        return $bestProduk?->provider;
    }

    /**
     * Get all available providers for a product, sorted by cheapest price
     */
    public function getAvailable(Produk $produk): Collection
    {
        return Produk::where('kode_provider', $produk->kode_provider)
            ->where('status', true)
            ->with(['provider' => function ($q) {
                $q->where('status', true);
            }])
            ->get()
            ->filter(fn($p) => $p->provider && $p->provider->saldo >= $p->harga_beli_idr)
            ->sortBy('harga_beli_idr')
            ->map(fn($p) => $p->provider);
    }

    /**
     * Send order to provider API
     * Returns ['status' => 'success'|'failed', 'sn' => ..., 'ref' => ...]
     */
    public function sendOrder(Provider $provider, Produk $produk, string $tujuan): array
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(30)->post($provider->api_url, [
                'api_key' => $provider->api_key,
                'code' => $produk->kode_provider,
                'target' => $tujuan,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => $data['status'] ?? 'pending',
                    'sn' => $data['sn'] ?? null,
                    'ref' => $data['ref'] ?? null,
                    'response' => json_encode($data),
                ];
            }

            return [
                'status' => 'failed',
                'sn' => null,
                'ref' => null,
                'response' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'sn' => null,
                'ref' => null,
                'response' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send order with auto-retry: if first provider fails, try next cheapest
     */
    public function sendOrderWithRetry(Produk $produk, string $tujuan, int $maxRetries = 3): array
    {
        $candidates = $this->getAvailable($produk);
        $attempt = 0;

        foreach ($candidates as $provider) {
            if ($attempt >= $maxRetries) break;
            $attempt++;

            $result = $this->sendOrder($provider, $produk, $tujuan);

            if ($result['status'] !== 'failed') {
                $result['provider_id'] = $provider->id;
                $result['harga_beli'] = Produk::where('kode_provider', $produk->kode_provider)
                    ->where('provider_id', $provider->id)
                    ->value('harga_beli_idr');
                return $result;
            }
        }

        return [
            'status' => 'failed',
            'sn' => null,
            'ref' => null,
            'response' => 'All providers failed after ' . $attempt . ' attempts',
            'provider_id' => null,
            'harga_beli' => $produk->harga_beli_idr,
        ];
    }
}
