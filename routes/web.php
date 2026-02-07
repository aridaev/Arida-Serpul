<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\GuestOrderController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProdukController;
use App\Http\Controllers\Admin\AdminNotifikasiController;
use Illuminate\Support\Facades\Route;

// Public routes (guest can access) - SEO friendly slugs
Route::get('/', [GuestOrderController::class, 'home'])->name('home');
Route::get('/track', [GuestOrderController::class, 'trackOrder'])->name('guest.track');
Route::get('/guest/payment/{paymentId}', [GuestOrderController::class, 'payment'])->name('guest.payment');
Route::post('/guest/payment/{paymentId}/upload', [GuestOrderController::class, 'uploadProof'])->name('guest.upload-proof');
Route::get('/beli/{produk}', [GuestOrderController::class, 'checkout'])->name('guest.checkout');
Route::post('/beli/{produk}', [GuestOrderController::class, 'processCheckout'])->name('guest.process-checkout');

// Auth routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');

    // Payment / Deposit
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/deposit', [PaymentController::class, 'deposit'])->name('payment.deposit');
    Route::post('/payment/deposit', [PaymentController::class, 'storeDeposit'])->name('payment.store-deposit');
    Route::get('/payment/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{payment}/upload', [PaymentController::class, 'uploadProof'])->name('payment.upload-proof');

    // Mutasi Saldo
    Route::get('/mutasi', [MutasiController::class, 'index'])->name('mutasi.index');

    // Referral
    Route::get('/referral', [ReferralController::class, 'index'])->name('referral.index');
});

// Admin Console routes
Route::prefix('console')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('admin.users');
        Route::get('/transaksis', [AdminDashboardController::class, 'transaksis'])->name('admin.transaksis');
        Route::get('/payments', [AdminDashboardController::class, 'payments'])->name('admin.payments');
        Route::get('/payments/pending', [AdminDashboardController::class, 'pendingPayments'])->name('admin.pending-payments');
        Route::post('/payments/verify/{proof}', [AdminDashboardController::class, 'verifyPayment'])->name('admin.verify-payment');
        // Kategori
        Route::get('/kategoris', [AdminProdukController::class, 'kategoris'])->name('admin.kategoris');
        Route::post('/kategoris', [AdminProdukController::class, 'storeKategori'])->name('admin.store-kategori');
        Route::delete('/kategoris/{kategori}', [AdminProdukController::class, 'deleteKategori'])->name('admin.delete-kategori');

        // Produk
        Route::get('/produks', [AdminProdukController::class, 'produks'])->name('admin.produks');
        Route::get('/produks/create', [AdminProdukController::class, 'createProduk'])->name('admin.create-produk');
        Route::post('/produks', [AdminProdukController::class, 'storeProduk'])->name('admin.store-produk');
        Route::get('/produks/{produk}/edit', [AdminProdukController::class, 'editProduk'])->name('admin.edit-produk');
        Route::put('/produks/{produk}', [AdminProdukController::class, 'updateProduk'])->name('admin.update-produk');
        Route::delete('/produks/{produk}', [AdminProdukController::class, 'deleteProduk'])->name('admin.delete-produk');
        Route::post('/produks/{produk}/toggle', [AdminProdukController::class, 'toggleProduk'])->name('admin.toggle-produk');

        // DigiFlazz sync
        Route::post('/digiflazz/sync', [AdminProdukController::class, 'syncDigiflazz'])->name('admin.sync-digiflazz');
        Route::get('/digiflazz/balance', [AdminProdukController::class, 'digiflazzBalance'])->name('admin.digiflazz-balance');

        // Notifikasi
        Route::get('/notifikasi', [AdminNotifikasiController::class, 'index'])->name('admin.notifikasi');
        Route::get('/notifikasi/{id}', [AdminNotifikasiController::class, 'show'])->name('admin.notifikasi.show');
        Route::post('/notifikasi/mark-read', [AdminNotifikasiController::class, 'markRead'])->name('admin.notifikasi.mark-read');
        Route::delete('/notifikasi/{id}', [AdminNotifikasiController::class, 'destroy'])->name('admin.notifikasi.destroy');
        Route::post('/notifikasi/delete-batch', [AdminNotifikasiController::class, 'destroyBatch'])->name('admin.notifikasi.destroy-batch');
        Route::get('/devices', [AdminNotifikasiController::class, 'devices'])->name('admin.devices');
        Route::post('/devices/{id}/toggle', [AdminNotifikasiController::class, 'toggleDevice'])->name('admin.devices.toggle');
        Route::delete('/devices/{id}', [AdminNotifikasiController::class, 'destroyDevice'])->name('admin.devices.destroy');

        Route::get('/providers', [AdminDashboardController::class, 'providers'])->name('admin.providers');
        Route::post('/providers', [AdminDashboardController::class, 'storeProvider'])->name('admin.store-provider');
        Route::get('/bank-accounts', [AdminDashboardController::class, 'bankAccounts'])->name('admin.bank-accounts');
        Route::post('/bank-accounts', [AdminDashboardController::class, 'storeBankAccount'])->name('admin.store-bank');
        Route::post('/bank-accounts/{bank}/toggle', [AdminDashboardController::class, 'toggleBankAccount'])->name('admin.toggle-bank');
        Route::delete('/bank-accounts/{bank}', [AdminDashboardController::class, 'deleteBankAccount'])->name('admin.delete-bank');
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
        Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('admin.update-settings');
    });
});

// SEO slug catch-all for kategori (MUST be last to avoid conflicts)
Route::get('/{kategori}', [GuestOrderController::class, 'produkByKategori'])->name('guest.kategori');
