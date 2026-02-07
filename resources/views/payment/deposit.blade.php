@extends('layouts.app')
@section('title', 'Deposit - Pulsa Pro')
@section('header', 'Deposit Saldo')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('payment.store-deposit') }}" class="space-y-5" x-data="{ metode: '' }">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Deposit</label>
                <input type="number" name="jumlah" value="{{ old('jumlah') }}" required min="10000" step="1000" placeholder="Minimal 10.000" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['bank_bca' => 'BCA', 'bank_bni' => 'BNI', 'bank_mandiri' => 'Mandiri', 'bank_bri' => 'BRI', 'ewallet_dana' => 'DANA', 'ewallet_ovo' => 'OVO', 'ewallet_gopay' => 'GoPay', 'qris' => 'QRIS'] as $val => $label)
                    <label @click="metode='{{ $val }}'" :class="metode==='{{ $val }}' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'" class="border rounded-lg p-3 text-center text-sm font-medium cursor-pointer transition">
                        {{ $label }}
                        <input type="radio" name="metode" value="{{ $val }}" class="hidden" x-bind:checked="metode==='{{ $val }}'">
                    </label>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-3 rounded-lg font-semibold text-sm hover:from-emerald-600 hover:to-teal-600 transition">Buat Invoice Deposit</button>
        </form>
    </div>
</div>
@endsection
