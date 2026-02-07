@extends('layouts.app')
@section('title', 'Detail Payment - Pulsa Pro')
@section('header', 'Detail Payment')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-gray-800">{{ $payment->invoice_id }}</h3>
            <span class="text-sm px-3 py-1 rounded-full font-medium
                {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : '' }}
                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $payment->status === 'waiting_approval' ? 'bg-blue-100 text-blue-700' : '' }}
                {{ $payment->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
            ">{{ ucfirst(str_replace('_', ' ', $payment->status)) }}</span>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Metode</span><span class="font-medium">{{ strtoupper(str_replace('_', ' ', $payment->metode)) }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Jumlah</span><span class="font-medium">{{ $payment->currency->symbol ?? 'Rp' }} {{ number_format($payment->jumlah, 0, ',', '.') }}</span></div>
            @if($payment->fee > 0)
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Fee</span><span>{{ $payment->currency->symbol ?? 'Rp' }} {{ number_format($payment->fee, 0, ',', '.') }}</span></div>
            @endif
            @if($payment->kode_unik > 0)
            <div class="flex justify-between py-2 border-b border-gray-50"><span class="text-gray-500">Kode Unik</span><span class="font-medium text-amber-600">+{{ $payment->kode_unik }}</span></div>
            @endif
            <div class="flex justify-between py-2 bg-indigo-50 rounded-lg px-3 -mx-1"><span class="text-indigo-700 font-semibold">Total Transfer</span><span class="font-bold text-lg text-indigo-700">{{ $payment->currency->symbol ?? 'Rp' }} {{ number_format($payment->total_bayar, 0, ',', '.') }}</span></div>
            <div class="flex justify-between py-2"><span class="text-gray-500">Waktu</span><span>{{ $payment->created_at->format('d/m/Y H:i:s') }}</span></div>
        </div>

        @if(($payment->status === 'pending' || $payment->status === 'waiting_approval') && $payment->kode_unik > 0)
        <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
            Transfer <strong>TEPAT</strong> Rp {{ number_format($payment->total_bayar, 0, ',', '.') }} — 3 digit terakhir <strong>{{ $payment->kode_unik }}</strong> adalah kode unik untuk verifikasi.
        </div>
        @endif

        @if($payment->status === 'pending' || $payment->status === 'waiting_approval')
        <div class="mt-6 pt-6 border-t border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Upload Bukti Pembayaran</h4>
            @if($payment->proofs->count() > 0)
            <div class="mb-3 p-3 bg-blue-50 text-blue-700 rounded-lg text-xs">Bukti sudah diupload. Menunggu verifikasi admin.</div>
            @endif
            <form method="POST" action="{{ route('payment.upload-proof', $payment->id) }}" enctype="multipart/form-data" class="flex gap-3">
                @csrf
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required class="flex-1 text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm file:font-medium">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Upload</button>
            </form>
            <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, PDF. Maks 2MB.</p>
        </div>
        @endif

        <a href="{{ route('payment.index') }}" class="inline-block mt-6 text-sm text-indigo-600 hover:underline">&larr; Kembali</a>
    </div>
</div>
@endsection
