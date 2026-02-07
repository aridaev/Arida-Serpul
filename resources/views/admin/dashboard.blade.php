@extends('layouts.admin')
@section('title', 'Dashboard - Admin')
@section('header', 'Dashboard')
@section('subheader', 'Ringkasan aktivitas sistem')

@section('content')
<div class="space-y-6">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Users</p>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTransaksi) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Transaksi</p>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-emerald-600">{{ number_format($totalSuccess) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Sukses</p>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-red-500">{{ number_format($totalFailed) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Gagal</p>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-violet-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-violet-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Laba</p>
        </div>

        <a href="{{ route('admin.pending-payments') }}" class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:border-amber-200 hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                @if($pendingPayments > 0)<span class="text-[10px] bg-amber-500 text-white px-1.5 py-0.5 rounded-full">!</span>@endif
            </div>
            <p class="text-2xl font-bold {{ $pendingPayments > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ number_format($pendingPayments) }}</p>
            <p class="text-xs text-gray-500 mt-0.5 group-hover:text-amber-600 transition">Menunggu Verifikasi</p>
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Recent Transactions --}}
        <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Transaksi Terakhir</h3>
                    <p class="text-xs text-gray-400 mt-0.5">20 transaksi terbaru</p>
                </div>
                <a href="{{ route('admin.transaksis') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/80">
                        <tr class="text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-2.5 text-left font-medium">Produk</th>
                            <th class="px-4 py-2.5 text-left font-medium">User / Tujuan</th>
                            <th class="px-4 py-2.5 text-right font-medium">Harga</th>
                            <th class="px-4 py-2.5 text-center font-medium">Status</th>
                            <th class="px-4 py-2.5 text-right font-medium">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentTransaksi as $trx)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-2.5">
                                <p class="font-medium text-gray-800 text-xs">{{ Str::limit($trx->produk->nama_produk ?? '-', 30) }}</p>
                            </td>
                            <td class="px-4 py-2.5">
                                <p class="text-xs text-gray-700">{{ $trx->user->username ?? ($trx->guest->nama ?? 'Guest') }}</p>
                                <p class="text-[11px] text-gray-400 font-mono">{{ $trx->tujuan }}</p>
                            </td>
                            <td class="px-4 py-2.5 text-right text-xs font-medium text-gray-700">Rp {{ number_format($trx->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-center">
                                @if($trx->status === 'success')
                                <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Sukses
                                </span>
                                @elseif($trx->status === 'failed')
                                <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-full bg-red-50 text-red-600 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Gagal
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Pending
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-right text-[11px] text-gray-400">{{ $trx->created_at->format('d M H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400 text-xs">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">

            {{-- Provider Saldo --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Saldo Provider</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Status koneksi & saldo</p>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($providers as $prov)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $prov->status ? 'bg-emerald-50' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 {{ $prov->status ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-800">{{ $prov->nama }}</p>
                            <p class="text-[11px] text-gray-400">{{ ucfirst($prov->tipe) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-800">Rp {{ number_format($prov->saldo, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center gap-1 text-[10px] {{ $prov->status ? 'text-emerald-600' : 'text-red-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $prov->status ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                                {{ $prov->status ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-5 text-center text-xs text-gray-400">Belum ada provider</div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-4 grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.produks') }}" class="flex flex-col items-center gap-1.5 p-3 rounded-lg border border-gray-100 hover:border-brand-200 hover:bg-brand-50/50 transition text-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                        <span class="text-[11px] font-medium text-gray-600">Kelola Produk</span>
                    </a>
                    <a href="{{ route('admin.pending-payments') }}" class="flex flex-col items-center gap-1.5 p-3 rounded-lg border border-gray-100 hover:border-amber-200 hover:bg-amber-50/50 transition text-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[11px] font-medium text-gray-600">Verifikasi</span>
                    </a>
                    <a href="{{ route('admin.bank-accounts') }}" class="flex flex-col items-center gap-1.5 p-3 rounded-lg border border-gray-100 hover:border-gray-200 hover:bg-gray-50 transition text-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18"/></svg>
                        <span class="text-[11px] font-medium text-gray-600">Bank</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex flex-col items-center gap-1.5 p-3 rounded-lg border border-gray-100 hover:border-gray-200 hover:bg-gray-50 transition text-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.212-1.281c-.063-.374-.313-.686-.645-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-[11px] font-medium text-gray-600">Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
