<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTransaksi = Transaksi::count();
        $totalSuccess = Transaksi::where('status', 'success')->count();
        $totalFailed = Transaksi::where('status', 'failed')->count();
        $totalRevenue = Transaksi::where('status', 'success')->sum('laba');
        $pendingPayments = Payment::where('status', 'waiting_approval')->count();
        $providers = Provider::all();
        $recentTransaksi = Transaksi::with(['user', 'produk', 'provider'])->latest()->take(20)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalTransaksi', 'totalSuccess', 'totalFailed',
            'totalRevenue', 'pendingPayments', 'providers', 'recentTransaksi'
        ));
    }

    public function users()
    {
        $users = User::with('currency')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function transaksis(Request $request)
    {
        $query = Transaksi::with(['user', 'guest', 'produk', 'provider', 'currency']);
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $transaksis = $query->latest()->paginate(20);
        return view('admin.transaksis', compact('transaksis'));
    }

    public function payments()
    {
        $payments = Payment::with(['user', 'guest', 'currency', 'proofs'])
            ->latest()
            ->paginate(20);
        return view('admin.payments', compact('payments'));
    }

    public function pendingPayments()
    {
        $payments = Payment::where('status', 'waiting_approval')
            ->with(['user', 'guest', 'currency', 'proofs'])
            ->latest()
            ->paginate(20);
        return view('admin.pending-payments', compact('payments'));
    }

    public function verifyPayment(Request $request, PaymentProof $proof)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $paymentService = app(PaymentService::class);
        $adminId = Auth::guard('admin')->id();
        $valid = $request->action === 'approve';

        $paymentService->verifyProof($proof, $adminId, $valid);

        return back()->with('success', $valid ? 'Payment approved.' : 'Payment rejected.');
    }

    public function providers()
    {
        $providers = Provider::all();
        return view('admin.providers', compact('providers'));
    }

    public function storeProvider(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'api_url' => 'required|url|max:255',
            'api_key' => 'required|string|max:255',
            'tipe' => 'required|in:pulsa,pln,game,ewallet,paket_data',
            'saldo' => 'required|numeric|min:0',
        ]);

        Provider::create($request->only('nama', 'api_url', 'api_key', 'tipe', 'saldo'));
        return back()->with('success', 'Provider berhasil ditambahkan.');
    }

    public function settings()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');

        // Handle file uploads
        if ($request->hasFile('app_logo_file')) {
            $path = $request->file('app_logo_file')->store('settings', 'public');
            Setting::set('app_logo_url', '/storage/' . $path, 'general');
        }
        if ($request->hasFile('app_favicon_file')) {
            $path = $request->file('app_favicon_file')->store('settings', 'public');
            Setting::set('app_favicon_url', '/storage/' . $path, 'general');
        }
        if ($request->hasFile('seo_og_image_file')) {
            $path = $request->file('seo_og_image_file')->store('settings', 'public');
            Setting::set('seo_og_image', '/storage/' . $path, 'seo');
        }

        // Update text settings
        $fileKeys = ['app_logo_file', 'app_favicon_file', 'seo_og_image_file'];
        foreach ($data as $key => $value) {
            if (in_array($key, $fileKeys)) continue;
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value ?? '']);
                \Illuminate\Support\Facades\Cache::forget("setting.{$key}");
            }
        }
        \Illuminate\Support\Facades\Cache::forget('settings.all');

        return back()->with('success', 'Settings berhasil disimpan.');
    }

    public function bankAccounts()
    {
        $banks = BankAccount::orderBy('urutan')->get();
        return view('admin.bank-accounts', compact('banks'));
    }

    public function storeBankAccount(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:30',
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
            'tipe' => 'required|in:bank,ewallet',
        ]);

        $maxUrutan = BankAccount::max('urutan') ?? 0;
        BankAccount::create(array_merge($request->only('kode', 'nama_bank', 'no_rekening', 'atas_nama', 'tipe'), [
            'urutan' => $maxUrutan + 1,
        ]));

        return back()->with('success', 'Bank account berhasil ditambahkan.');
    }

    public function toggleBankAccount(BankAccount $bank)
    {
        $bank->update(['status' => !$bank->status]);
        return back()->with('success', 'Status bank berhasil diubah.');
    }

    public function deleteBankAccount(BankAccount $bank)
    {
        $bank->delete();
        return back()->with('success', 'Bank account berhasil dihapus.');
    }
}
