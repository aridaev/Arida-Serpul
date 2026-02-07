<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Setting;

class PaymentGatewayManager
{
    protected static array $drivers = [
        'tripay' => TripayService::class,
        'xendit' => XenditService::class,
        'duitku' => DuitkuService::class,
    ];

    /**
     * Get the active payment gateway driver.
     */
    public static function driver(?string $name = null): PaymentGatewayInterface
    {
        $name = $name ?: Setting::get('pg_active_driver', 'tripay');
        $class = static::$drivers[$name] ?? static::$drivers['tripay'];
        return app($class);
    }

    /**
     * Get all registered driver names.
     */
    public static function availableDrivers(): array
    {
        return [
            'tripay' => 'Tripay',
            'xendit' => 'Xendit',
            'duitku' => 'Duitku',
        ];
    }

    /**
     * Check if the active gateway is configured.
     */
    public static function isConfigured(): bool
    {
        return static::driver()->isConfigured();
    }

    /**
     * Get the active driver name.
     */
    public static function activeDriver(): string
    {
        return Setting::get('pg_active_driver', 'tripay');
    }
}
