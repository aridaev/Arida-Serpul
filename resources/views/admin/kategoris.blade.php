@extends('layouts.admin')
@section('title', 'Kategori Produk - Admin')
@section('header', 'Kategori Produk')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Add New --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Tambah Kategori</h3>
        </div>
        <form method="POST" action="{{ route('admin.store-kategori') }}" class="p-5">
            @csrf
            <div class="flex gap-3">
                <input type="text" name="nama" required placeholder="Nama kategori (misal: Pulsa)" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <input type="text" name="icon" placeholder="Icon (emoji)" class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm text-center">
                <button type="submit" class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Tambah</button>
            </div>
        </form>
    </div>

    {{-- List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Daftar Kategori ({{ $kategoris->count() }})</h3>
        </div>
        @if($kategoris->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($kategoris as $kat)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-lg">{{ $kat->icon ?? '📦' }}</span>
                    <div>
                        <span class="font-medium text-sm text-gray-800">{{ $kat->nama }}</span>
                        <span class="text-xs text-gray-400 ml-2">/{{ $kat->slug }}</span>
                    </div>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $kat->produks_count }} produk</span>
                </div>
                <form method="POST" action="{{ route('admin.delete-kategori', $kat->id) }}" onsubmit="return confirm('Hapus kategori {{ $kat->nama }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition">Hapus</button>
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-sm text-gray-400">Belum ada kategori.</div>
        @endif
    </div>
</div>
@endsection
