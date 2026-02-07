<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProdukApiController;
use App\Http\Controllers\Api\TransaksiApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\DigiflazzWebhookController;
use App\Http\Controllers\Api\TripayCallbackController;
use App\Http\Controllers\Api\NotificationCaptureController;
use Illuminate\Support\Facades\Route;

// Payment gateway callbacks (no auth, no CSRF) - supports any driver
Route::post('/payment-gateway/callback', [TripayCallbackController::class, 'handle']);
Route::post('/payment-gateway/callback/{driver}', [TripayCallbackController::class, 'handle']);

// DigiFlazz seller webhook
Route::post('/digiflazz/webhook', [DigiflazzWebhookController::class, 'handle']);

// Public API
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
Route::get('/kategoris', [ProdukApiController::class, 'kategoris']);
Route::get('/produks/{kategoriId}', [ProdukApiController::class, 'byKategori']);

// Notification Capture API (device token auth)
Route::post('/device/register', [NotificationCaptureController::class, 'registerDevice']);
Route::post('/device/ping', [NotificationCaptureController::class, 'ping']);
Route::post('/notifications/capture', [NotificationCaptureController::class, 'capture']);
Route::post('/notifications/capture-batch', [NotificationCaptureController::class, 'captureBatch']);

// Authenticated API (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/profile', [AuthApiController::class, 'profile']);
    Route::get('/saldo', [TransaksiApiController::class, 'saldo']);
    Route::get('/mutasi', [TransaksiApiController::class, 'mutasi']);
    Route::get('/transaksi', [TransaksiApiController::class, 'index']);
    Route::post('/transaksi', [TransaksiApiController::class, 'store']);
    Route::get('/transaksi/{transaksi}', [TransaksiApiController::class, 'show']);
    Route::get('/referral', [TransaksiApiController::class, 'referralInfo']);
    Route::get('/payments', [PaymentApiController::class, 'index']);
    Route::post('/payments', [PaymentApiController::class, 'store']);
    Route::post('/payments/{payment}/upload', [PaymentApiController::class, 'uploadProof']);
});
