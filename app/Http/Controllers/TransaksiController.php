<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transaksis = Transaksi::where('user_id', Auth::id())
            ->with(['produk', 'provider', 'currency'])
            ->latest()
            ->paginate(20);

        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $kategoris = KategoriProduk::with(['produks' => function ($q) {
            $q->where('status', true);
        }])->get();

        return view('transaksi.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'tujuan' => 'required|string|max:50',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $user = Auth::user();

        try {
            $transaksi = $this->transactionService->processForUser($user, $produk, $request->tujuan);

            if ($transaksi->status === 'success') {
                return redirect()->route('transaksi.show', $transaksi->id)
                    ->with('success', 'Transaksi berhasil! SN: ' . $transaksi->sn);
            }

            return redirect()->route('transaksi.show', $transaksi->id)
                ->with('error', 'Transaksi gagal. Saldo telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        $transaksi->load(['produk', 'provider', 'currency']);
        return view('transaksi.show', compact('transaksi'));
    }
}
