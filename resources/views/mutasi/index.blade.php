@extends('layouts.app')
@section('title', 'Mutasi Saldo - Pulsa Pro')
@section('header', 'Mutasi Saldo')

@section('content')
<div class="mb-4 flex gap-2 flex-wrap">
    <a href="{{ route('mutasi.index') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium {{ !request('tipe') ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">Semua</a>
    @foreach(['deposit', 'transaksi', 'refund', 'bonus', 'referral'] as $t)
    <a href="{{ route('mutasi.index', ['tipe' => $t]) }}" class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request('tipe') === $t ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ ucfirst($t) }}</a>
    @endforeach
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Tipe</th>
                    <th class="px-5 py-3 text-right">Jumlah</th>
                    <th class="px-5 py-3 text-right">Saldo Sebelum</th>
                    <th class="px-5 py-3 text-right">Saldo Sesudah</th>
                    <th class="px-5 py-3 text-left">Keterangan</th>
                    <th class="px-5 py-3 text-left">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($mutasis as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3"><span class="text-xs px-2 py-1 rounded-full font-medium bg-gray-100 text-gray-700">{{ ucfirst($m->tipe) }}</span></td>
                    <td class="px-5 py-3 text-right font-semibold {{ $m->jumlah >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $m->jumlah >= 0 ? '+' : '' }}{{ number_format($m->jumlah, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right text-gray-500">{{ number_format($m->saldo_sebelum, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right font-medium">{{ number_format($m->saldo_sesudah, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500 max-w-xs truncate">{{ $m->keterangan ?? '-' }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada mutasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $mutasis->links() }}</div>
</div>
@endsection
