@extends('layouts.app')
@section('title', 'Dashboard - Pulsa Pro')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Saldo</p>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ $user->currency->symbol ?? 'Rp' }} {{ number_format($user->saldo, 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
            </div>
        </div>
        <a href="{{ route('payment.deposit') }}" class="inline-block mt-3 text-xs text-indigo-600 font-medium hover:underline">+ Deposit</a>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total Transaksi</p>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ number_format($totalTransaksi) }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Sukses</p>
                <p class="text-xl font-bold text-emerald-600 mt-1">{{ number_format($totalSuccess) }}</p>
            </div>
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Gagal</p>
                <p class="text-xl font-bold text-red-600 mt-1">{{ number_format($totalFailed) }}</p>
            </div>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Transactions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Transaksi Terakhir</h3>
            <a href="{{ route('transaksi.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentTransaksi as $trx)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $trx->produk->nama_produk ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $trx->tujuan }} &middot; {{ $trx->created_at->format('d/m H:i') }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full font-medium {{ $trx->status === 'success' ? 'bg-emerald-100 text-emerald-700' : ($trx->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($trx->status) }}</span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada transaksi</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Mutasi --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Mutasi Saldo Terakhir</h3>
            <a href="{{ route('mutasi.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentMutasi as $m)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ ucfirst($m->tipe) }}</p>
                    <p class="text-xs text-gray-500">{{ $m->keterangan ?? '-' }}</p>
                </div>
                <span class="text-sm font-semibold {{ $m->jumlah >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $m->jumlah >= 0 ? '+' : '' }}{{ number_format($m->jumlah, 0, ',', '.') }}
                </span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada mutasi</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
    <a href="{{ route('transaksi.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl p-4 text-center hover:from-indigo-700 hover:to-purple-700 transition">
        <div class="text-2xl mb-1">📱</div>
        <p class="text-sm font-medium">Beli Pulsa</p>
    </a>
    <a href="{{ route('payment.deposit') }}" class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl p-4 text-center hover:from-emerald-600 hover:to-teal-600 transition">
        <div class="text-2xl mb-1">💰</div>
        <p class="text-sm font-medium">Deposit</p>
    </a>
    <a href="{{ route('mutasi.index') }}" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl p-4 text-center hover:from-amber-600 hover:to-orange-600 transition">
        <div class="text-2xl mb-1">📊</div>
        <p class="text-sm font-medium">Mutasi</p>
    </a>
    <a href="{{ route('referral.index') }}" class="bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl p-4 text-center hover:from-pink-600 hover:to-rose-600 transition">
        <div class="text-2xl mb-1">👥</div>
        <p class="text-sm font-medium">Referral</p>
    </a>
</div>
@endsection
