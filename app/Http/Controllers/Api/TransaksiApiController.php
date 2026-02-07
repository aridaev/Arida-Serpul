<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MutasiSaldo;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\ReferralLog;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransaksiApiController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $transaksis = Transaksi::where('user_id', $request->user()->id)
            ->with(['produk', 'provider', 'currency'])
            ->latest()
            ->paginate(20);

        return response()->json($transaksis);
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'tujuan' => 'required|string|max:50',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $user = $request->user();

        try {
            $transaksi = $this->transactionService->processForUser($user, $produk, $request->tujuan);
            $transaksi->load(['produk', 'provider', 'currency']);

            return response()->json([
                'message' => $transaksi->status === 'success' ? 'Transaksi berhasil' : 'Transaksi gagal',
                'data' => $transaksi,
            ], $transaksi->status === 'success' ? 200 : 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaksi->load(['produk', 'provider', 'currency']);
        return response()->json(['data' => $transaksi]);
    }

    public function saldo(Request $request)
    {
        $user = $request->user()->load('currency');
        return response()->json([
            'saldo' => $user->saldo,
            'currency' => $user->currency->code,
            'symbol' => $user->currency->symbol,
        ]);
    }

    public function mutasi(Request $request)
    {
        $mutasis = MutasiSaldo::where('user_id', $request->user()->id)
            ->with('currency')
            ->latest()
            ->paginate(20);

        return response()->json($mutasis);
    }

    public function referralInfo(Request $request)
    {
        $user = $request->user();
        $logs = ReferralLog::where('user_id', $user->id)
            ->with(['downline', 'currency'])
            ->latest('created_at')
            ->paginate(20);
        $totalKomisi = ReferralLog::where('user_id', $user->id)->sum('komisi');

        return response()->json([
            'referral_code' => $user->referral_code,
            'total_referrals' => \App\Models\User::where('referred_by', $user->id)->count(),
            'total_komisi_idr' => $totalKomisi,
            'logs' => $logs,
        ]);
    }
}
