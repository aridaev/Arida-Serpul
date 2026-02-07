@extends('layouts.app')
@section('title', 'Referral - Pulsa Pro')
@section('header', 'Referral Program')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl p-5">
        <p class="text-sm opacity-80">Kode Referral Anda</p>
        <p class="text-2xl font-bold mt-1 tracking-wider">{{ $user->referral_code }}</p>
        <p class="text-xs mt-2 opacity-70">Bagikan kode ini ke teman Anda</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Downline</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $referrals->count() }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Komisi (IDR)</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Downline List --}}
@if($referrals->count() > 0)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="p-5 border-b border-gray-100"><h3 class="font-semibold text-gray-800">Daftar Downline</h3></div>
    <div class="divide-y divide-gray-50">
        @foreach($referrals as $ref)
        <div class="px-5 py-3 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $ref->nama }}</p>
                <p class="text-xs text-gray-500">{{ $ref->username }}</p>
            </div>
            <span class="text-xs px-2 py-1 rounded-full {{ $ref->status ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $ref->status ? 'Aktif' : 'Nonaktif' }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Referral Logs --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100"><h3 class="font-semibold text-gray-800">Riwayat Komisi</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Dari</th>
                    <th class="px-5 py-3 text-left">TRX ID</th>
                    <th class="px-5 py-3 text-right">Komisi (IDR)</th>
                    <th class="px-5 py-3 text-left">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium">{{ $log->downline->username ?? '-' }}</td>
                    <td class="px-5 py-3 font-mono text-xs">{{ $log->trx_id }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-emerald-600">+Rp {{ number_format($log->komisi, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada komisi referral</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $logs->links() }}</div>
</div>
@endsection
