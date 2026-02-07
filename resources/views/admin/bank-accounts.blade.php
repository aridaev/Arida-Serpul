@extends('layouts.admin')
@section('title', 'Bank Accounts - Admin')
@section('header', 'Bank & E-Wallet Accounts')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Add New --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Tambah Rekening Baru</h3>
        </div>
        <form method="POST" action="{{ route('admin.store-bank') }}" class="p-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kode</label>
                    <input type="text" name="kode" required placeholder="bca, dana, ovo..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Bank / E-Wallet</label>
                    <input type="text" name="nama_bank" required placeholder="Bank BCA" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
                    <select name="tipe" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="bank">Bank Transfer</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No Rekening / No HP</label>
                    <input type="text" name="no_rekening" required placeholder="1234567890" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Atas Nama</label>
                    <input type="text" name="atas_nama" required placeholder="PT Pulsa Pro" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Tambah</button>
                </div>
            </div>
        </form>
    </div>

    {{-- List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Daftar Rekening ({{ $banks->count() }})</h3>
        </div>
        @if($banks->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($banks as $bank)
            <div class="px-5 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-lg {{ $bank->status ? 'bg-emerald-50' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                        @if($bank->tipe === 'bank')
                        <svg class="w-5 h-5 {{ $bank->status ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                        @else
                        <svg class="w-5 h-5 {{ $bank->status ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 013 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 013 6v3"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-sm text-gray-800">{{ $bank->nama_bank }}</span>
                            <span class="text-xs px-1.5 py-0.5 rounded {{ $bank->tipe === 'bank' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">{{ $bank->tipe === 'bank' ? 'Bank' : 'E-Wallet' }}</span>
                            @if(!$bank->status)<span class="text-xs px-1.5 py-0.5 rounded bg-red-50 text-red-600">Nonaktif</span>@endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $bank->no_rekening }} &middot; {{ $bank->atas_nama }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('admin.toggle-bank', $bank->id) }}">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border {{ $bank->status ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }} transition">
                            {{ $bank->status ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.delete-bank', $bank->id) }}" onsubmit="return confirm('Hapus rekening ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-sm text-gray-400">Belum ada rekening bank.</div>
        @endif
    </div>
</div>
@endsection
