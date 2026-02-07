<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Guest;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\Setting;
use App\Services\PaymentService;
use App\Services\TransactionService;
use App\Services\PaymentGatewayManager;
use Illuminate\Http\Request;

class GuestOrderController extends Controller
{
    protected TransactionService $transactionService;
    protected PaymentService $paymentService;

    public function __construct(TransactionService $transactionService, PaymentService $paymentService)
    {
        $this->transactionService = $transactionService;
        $this->paymentService = $paymentService;
    }

    public function home()
    {
        $kategoris = KategoriProduk::withCount(['produks' => function ($q) {
            $q->where('status', true);
        }])->get();

        return view('home', compact('kategoris'));
    }

    public function produkByKategori(Request $request, KategoriProduk $kategori)
    {
        // Get all unique brands in this category
        $brands = Produk::where('kategori_id', $kategori->id)
            ->where('status', true)
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand');

        $selectedBrand = $request->brand;

        $query = Produk::where('kategori_id', $kategori->id)->where('status', true);
        if ($selectedBrand) {
            $query->where('brand', $selectedBrand);
        }
        $produks = $query->get();

        $kategoris = KategoriProduk::all();

        return view('guest.produk', compact('kategori', 'produks', 'kategoris', 'brands', 'selectedBrand'));
    }

    public function checkout(Produk $produk)
    {
        $bankAccounts = BankAccount::active()->get();
        $manualEnabled = Setting::get('payment_manual_enabled', '1') === '1';
        $gatewayEnabled = Setting::get('payment_gateway_enabled', '0') === '1';

        return view('guest.checkout', compact('produk', 'bankAccounts', 'manualEnabled', 'gatewayEnabled'));
    }

    public function processCheckout(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'tujuan' => 'required|string|max:50',
            'tipe_bayar' => 'required|in:manual,gateway',
            'metode' => 'required|string|max:50',
        ]);

        $currency = \App\Models\Currency::where('code', 'IDR')->first();

        $guest = Guest::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'currency_id' => $currency->id,
        ]);

        if ($request->tipe_bayar === 'gateway') {
            // Tripay auto payment
            $payment = $this->paymentService->createAutoPayment(
                null, $guest, $produk->harga_jual_idr, $currency, $request->metode
            );

            $gateway = PaymentGatewayManager::driver();
            if ($gateway->isConfigured()) {
                $driverName = $gateway->getName();
                $result = $gateway->createTransaction([
                    'method' => $request->metode,
                    'merchant_ref' => $payment->invoice_id,
                    'amount' => (int) $payment->total_bayar,
                    'customer_name' => $request->nama,
                    'customer_email' => $request->email ?: 'guest@pulsapro.com',
                    'customer_phone' => $request->no_hp,
                    'order_items' => [['name' => $produk->nama_produk, 'price' => (int) $produk->harga_jual_idr, 'quantity' => 1]],
                    'callback_url' => url('/api/payment-gateway/callback/' . $driverName),
                    'return_url' => url('/track?invoice_id=' . $payment->invoice_id),
                    'description' => $produk->nama_produk,
                ]);

                if ($result) {
                    $payment->update([
                        'provider_payment' => $driverName,
                        'response_payment' => json_encode($result),
                    ]);
                }
            }
        } else {
            // Manual bank transfer
            $payment = $this->paymentService->createManualPayment(
                null, $guest, $produk->harga_jual_idr, $currency, $request->metode
            );
        }

        session([
            'guest_order' => [
                'guest_id' => $guest->id,
                'produk_id' => $produk->id,
                'payment_id' => $payment->id,
                'tujuan' => $request->tujuan,
            ]
        ]);

        return redirect()->route('guest.payment', $payment->id);
    }

    public function payment(int $paymentId)
    {
        $order = session('guest_order');
        if (!$order || $order['payment_id'] != $paymentId) {
            return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
        }

        $payment = \App\Models\Payment::with('currency')->findOrFail($paymentId);
        $produk = Produk::findOrFail($order['produk_id']);

        return view('guest.payment', compact('payment', 'produk', 'order'));
    }

    public function uploadProof(Request $request, int $paymentId)
    {
        $order = session('guest_order');
        if (!$order || $order['payment_id'] != $paymentId) {
            return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
        }

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $payment = \App\Models\Payment::findOrFail($paymentId);

        try {
            $this->paymentService->uploadProof($payment, $request->file('file'));
            return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin. Anda akan menerima SN setelah diverifikasi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function trackOrder(Request $request)
    {
        $transaksi = null;
        $payment = null;

        if ($request->invoice_id) {
            $payment = \App\Models\Payment::where('invoice_id', $request->invoice_id)
                ->with(['currency', 'proofs'])
                ->first();

            if ($payment) {
                $transaksi = \App\Models\Transaksi::where('payment_id', $payment->id)
                    ->with(['produk', 'provider'])
                    ->first();
            }
        }

        return view('guest.track', compact('transaksi', 'payment'));
    }
}
