@extends('layouts.app')
@section('title', 'Detail Transaksi - Pulsa Pro')
@section('header', 'Detail Transaksi')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-gray-800">{{ $transaksi->trx_id }}</h3>
            <span class="text-sm px-3 py-1 rounded-full font-medium {{ $transaksi->status === 'success' ? 'bg-emerald-100 text-emerald-700' : ($transaksi->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($transaksi->status) }}</span>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Produk</span><span class="font-medium">{{ $transaksi->produk->nama_produk ?? '-' }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Tujuan</span><span class="font-medium">{{ $transaksi->tujuan }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Harga</span><span class="font-bold text-indigo-600">Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Provider</span><span class="font-medium">{{ $transaksi->provider->nama ?? '-' }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">SN</span><span class="font-mono">{{ $transaksi->sn ?? '-' }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Ref</span><span class="font-mono">{{ $transaksi->provider_ref ?? '-' }}</span></div>
            <div class="flex justify-between py-2"><span class="text-gray-500">Waktu</span><span>{{ $transaksi->created_at->format('d/m/Y H:i:s') }}</span></div>
        </div>
        <a href="{{ route('transaksi.index') }}" class="inline-block mt-6 text-sm text-indigo-600 hover:underline">&larr; Kembali</a>
    </div>
</div>
@endsection
