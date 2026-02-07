<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\MutasiSaldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalTransaksi = Transaksi::where('user_id', $user->id)->count();
        $totalSuccess = Transaksi::where('user_id', $user->id)->where('status', 'success')->count();
        $totalFailed = Transaksi::where('user_id', $user->id)->where('status', 'failed')->count();
        $totalPending = Transaksi::where('user_id', $user->id)->where('status', 'pending')->count();
        $recentTransaksi = Transaksi::where('user_id', $user->id)
            ->with(['produk', 'provider'])
            ->latest()
            ->take(10)
            ->get();
        $recentMutasi = MutasiSaldo::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'user', 'totalTransaksi', 'totalSuccess', 'totalFailed',
            'totalPending', 'recentTransaksi', 'recentMutasi'
        ));
    }
}
