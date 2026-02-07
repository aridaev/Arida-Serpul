@extends('layouts.app')
@section('title', 'Beli Pulsa - Pulsa Pro')
@section('header', 'Beli Pulsa / Paket Data')

@section('content')
<div class="max-w-2xl" x-data="{ selectedKategori: null, selectedProduk: null, kategoris: {{ $kategoris->toJson() }} }">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('transaksi.store') }}" class="space-y-5">
            @csrf
            {{-- Nomor Tujuan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Tujuan</label>
                <input type="text" name="tujuan" value="{{ old('tujuan') }}" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                    <template x-for="kat in kategoris" :key="kat.id">
                        <button type="button" @click="selectedKategori = kat; selectedProduk = null"
                            :class="selectedKategori?.id === kat.id ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 hover:border-gray-300'"
                            class="border rounded-lg p-3 text-center text-xs font-medium transition">
                            <div class="text-lg mb-1" x-text="kat.icon || '📦'"></div>
                            <span x-text="kat.nama"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Produk --}}
            <div x-show="selectedKategori" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Produk</label>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    <template x-for="produk in (selectedKategori?.produks || [])" :key="produk.id">
                        <label @click="selectedProduk = produk"
                            :class="selectedProduk?.id === produk.id ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                            class="flex items-center justify-between border rounded-lg p-3 cursor-pointer transition">
                            <div>
                                <p class="text-sm font-medium text-gray-800" x-text="produk.nama_produk"></p>
                                <p class="text-xs text-gray-500" x-text="'Kode: ' + produk.kode_provider"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-indigo-600" x-text="'Rp ' + Number(produk.harga_jual_idr).toLocaleString('id-ID')"></p>
                            </div>
                            <input type="radio" name="produk_id" :value="produk.id" class="hidden" x-bind:checked="selectedProduk?.id === produk.id">
                        </label>
                    </template>
                </div>
                <p x-show="selectedKategori && (!selectedKategori.produks || selectedKategori.produks.length === 0)" class="text-sm text-gray-400 text-center py-4">Tidak ada produk tersedia</p>
            </div>

            {{-- Summary --}}
            <div x-show="selectedProduk" x-cloak class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Ringkasan</h4>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Produk</span>
                    <span class="font-medium" x-text="selectedProduk?.nama_produk"></span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span class="text-gray-500">Harga</span>
                    <span class="font-bold text-indigo-600" x-text="'Rp ' + Number(selectedProduk?.harga_jual_idr || 0).toLocaleString('id-ID')"></span>
                </div>
            </div>

            <button type="submit" x-bind:disabled="!selectedProduk" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-lg font-semibold text-sm hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                Beli Sekarang
            </button>
        </form>
    </div>
</div>
@endsection
