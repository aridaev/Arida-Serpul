@extends('layouts.app')
@section('title', 'Riwayat Transaksi - Pulsa Pro')
@section('header', 'Riwayat Transaksi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">TRX ID</th>
                    <th class="px-5 py-3 text-left">Produk</th>
                    <th class="px-5 py-3 text-left">Tujuan</th>
                    <th class="px-5 py-3 text-right">Harga</th>
                    <th class="px-5 py-3 text-left">SN</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-left">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($transaksis as $trx)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs">{{ $trx->trx_id }}</td>
                    <td class="px-5 py-3 font-medium">{{ $trx->produk->nama_produk ?? '-' }}</td>
                    <td class="px-5 py-3">{{ $trx->tujuan }}</td>
                    <td class="px-5 py-3 text-right font-semibold">Rp {{ number_format($trx->harga, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 font-mono text-xs">{{ $trx->sn ?? '-' }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $trx->status === 'success' ? 'bg-emerald-100 text-emerald-700' : ($trx->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($trx->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $transaksis->links() }}</div>
</div>
@endsection
