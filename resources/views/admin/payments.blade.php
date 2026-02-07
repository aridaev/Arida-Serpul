@extends('layouts.admin')
@section('title', 'Riwayat Payment - Admin')
@section('header', 'Riwayat Payment')
@section('subheader', 'Semua pembayaran deposit & pembelian')

@section('content')
<div class="space-y-4">

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 text-[11px] text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3 text-left font-medium">Invoice</th>
                        <th class="px-4 py-3 text-left font-medium">User</th>
                        <th class="px-4 py-3 text-left font-medium">Metode</th>
                        <th class="px-4 py-3 text-left font-medium">Tipe</th>
                        <th class="px-4 py-3 text-right font-medium">Total Bayar</th>
                        <th class="px-4 py-3 text-center font-medium">Status</th>
                        <th class="px-4 py-3 text-center font-medium">Bukti</th>
                        <th class="px-4 py-3 text-right font-medium">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($payments as $p)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3">
                            <span class="font-mono text-[11px] text-gray-500 bg-gray-50 px-1.5 py-0.5 rounded">{{ $p->invoice_id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-medium text-gray-800">{{ $p->user->username ?? ($p->guest->nama ?? 'Guest') }}</p>
                            @if($p->user)
                            <p class="text-[11px] text-gray-400">Member</p>
                            @elseif($p->guest)
                            <p class="text-[11px] text-gray-400">{{ $p->guest->no_hp ?? 'Guest' }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-medium text-gray-700">{{ $p->metode ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($p->tipe_pembayaran === 'manual')
                            <span class="text-[11px] px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-medium">Manual</span>
                            @else
                            <span class="text-[11px] px-2 py-0.5 rounded bg-brand-50 text-brand-700 font-medium">Gateway</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <p class="text-xs font-bold text-gray-800">{{ $p->currency->symbol ?? 'Rp' }} {{ number_format($p->total_bayar, 0, ',', '.') }}</p>
                            @if($p->kode_unik > 0)
                            <p class="text-[10px] text-gray-400">+{{ $p->kode_unik }} unik</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $statusConfig = [
                                    'paid' => ['bg' => 'emerald', 'label' => 'Paid'],
                                    'pending' => ['bg' => 'amber', 'label' => 'Pending'],
                                    'waiting_approval' => ['bg' => 'blue', 'label' => 'Menunggu'],
                                    'rejected' => ['bg' => 'red', 'label' => 'Ditolak'],
                                    'expired' => ['bg' => 'gray', 'label' => 'Expired'],
                                    'failed' => ['bg' => 'red', 'label' => 'Gagal'],
                                ];
                                $sc = $statusConfig[$p->status] ?? ['bg' => 'gray', 'label' => ucfirst($p->status)];
                            @endphp
                            <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-full bg-{{ $sc['bg'] }}-50 text-{{ $sc['bg'] }}-700 font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-{{ $sc['bg'] }}-500 {{ $p->status === 'pending' || $p->status === 'waiting_approval' ? 'animate-pulse' : '' }}"></span>
                                {{ $sc['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($p->proofs && $p->proofs->count() > 0)
                            <span class="text-[11px] px-2 py-0.5 rounded bg-blue-50 text-blue-600 font-medium">{{ $p->proofs->count() }} file</span>
                            @else
                            <span class="text-[11px] text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <p class="text-[11px] text-gray-500">{{ $p->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $p->created_at->format('H:i:s') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                            <p class="text-sm text-gray-400">Belum ada payment</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">{{ $payments->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
