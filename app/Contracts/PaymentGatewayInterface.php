<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Get the driver name (e.g. 'tripay', 'xendit', 'duitku').
     */
    public function getName(): string;

    /**
     * Check if this gateway is properly configured.
     */
    public function isConfigured(): bool;

    /**
     * Get available payment channels/methods.
     * Returns array of ['code' => ..., 'name' => ..., 'group' => ..., 'fee_flat' => ..., 'fee_percent' => ...]
     */
    public function getPaymentChannels(): array;

    /**
     * Create a payment transaction.
     * Returns ['reference' => ..., 'pay_url' => ..., 'pay_code' => ..., 'amount' => ..., 'expired_at' => ..., 'raw' => ...]
     * or null on failure.
     */
    public function createTransaction(array $params): ?array;

    /**
     * Get transaction detail by reference.
     */
    public function getTransactionDetail(string $reference): ?array;

    /**
     * Verify callback/webhook signature. Returns true if valid.
     */
    public function verifyCallback(string $payload): bool;

    /**
     * Parse callback payload into normalized status.
     * Returns ['merchant_ref' => ..., 'status' => 'paid'|'expired'|'failed'|'pending', 'raw' => ...]
     */
    public function parseCallback(string $payload): array;
}
