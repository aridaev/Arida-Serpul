@extends('layouts.admin')
@section('title', 'Riwayat Transaksi - Admin')
@section('header', 'Riwayat Transaksi')
@section('subheader', 'Semua transaksi pembelian produk')

@section('content')
<div class="space-y-4">

    {{-- Status Tabs --}}
    <div class="flex items-center gap-2 flex-wrap">
        @php
            $tabs = ['' => 'Semua', 'pending' => 'Pending', 'success' => 'Sukses', 'failed' => 'Gagal'];
            $colors = ['' => 'gray', 'pending' => 'amber', 'success' => 'emerald', 'failed' => 'red'];
        @endphp
        @foreach($tabs as $val => $label)
        <a href="{{ route('admin.transaksis', $val ? ['status' => $val] : []) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition
           {{ request('status', '') === $val ? 'bg-gray-900 text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
            @if($val)<span class="w-1.5 h-1.5 rounded-full bg-{{ $colors[$val] }}-500"></span>@endif
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 text-[11px] text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3 text-left font-medium">TRX ID</th>
                        <th class="px-4 py-3 text-left font-medium">User</th>
                        <th class="px-4 py-3 text-left font-medium">Produk</th>
                        <th class="px-4 py-3 text-left font-medium">Tujuan</th>
                        <th class="px-4 py-3 text-right font-medium">Harga</th>
                        <th class="px-4 py-3 text-right font-medium">Modal</th>
                        <th class="px-4 py-3 text-right font-medium">Laba</th>
                        <th class="px-4 py-3 text-center font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">SN</th>
                        <th class="px-4 py-3 text-right font-medium">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($transaksis as $trx)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3">
                            <span class="font-mono text-[11px] text-gray-500 bg-gray-50 px-1.5 py-0.5 rounded">{{ $trx->trx_id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-medium text-gray-800">{{ $trx->user->username ?? ($trx->guest->nama ?? 'Guest') }}</p>
                            @if($trx->user)
                            <p class="text-[11px] text-gray-400">Member</p>
                            @elseif($trx->guest)
                            <p class="text-[11px] text-gray-400">Guest</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-medium text-gray-800">{{ Str::limit($trx->produk->nama_produk ?? '-', 28) }}</p>
                            <p class="text-[11px] text-gray-400">{{ $trx->provider->nama ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs text-gray-700">{{ $trx->tujuan }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-xs font-medium text-gray-800">Rp {{ number_format($trx->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-xs text-gray-400">{{ number_format($trx->modal, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-xs font-semibold {{ $trx->laba > 0 ? 'text-emerald-600' : 'text-gray-400' }}">
                                {{ $trx->laba > 0 ? '+' : '' }}{{ number_format($trx->laba, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
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
                        <td class="px-4 py-3">
                            @if($trx->sn)
                            <span class="font-mono text-[11px] text-gray-500 bg-gray-50 px-1.5 py-0.5 rounded">{{ Str::limit($trx->sn, 20) }}</span>
                            @else
                            <span class="text-[11px] text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <p class="text-[11px] text-gray-500">{{ $trx->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $trx->created_at->format('H:i:s') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                            <p class="text-sm text-gray-400">Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transaksis->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">{{ $transaksis->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
