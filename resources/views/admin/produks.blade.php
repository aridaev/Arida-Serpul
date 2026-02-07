@extends('layouts.admin')
@section('title', 'Produk - Admin')
@section('header', 'Produk')

@section('content')
<div class="space-y-5">

    {{-- Actions --}}
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.create-produk') }}" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">+ Tambah Produk</a>
        <form method="POST" action="{{ route('admin.sync-digiflazz') }}" class="inline">
            @csrf
            <button type="submit" onclick="return confirm('Sync produk dari DigiFlazz? Harga beli & jual akan diupdate.')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Sync DigiFlazz</button>
        </form>
        <span class="text-xs text-gray-400">Total: {{ $produks->total() }} produk</span>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-56">
        <select name="kategori_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kat)
            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
            @endforeach
        </select>
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Filter</button>
        @if(request()->hasAny(['search','kategori_id','status']))
        <a href="{{ route('admin.produks') }}" class="text-xs text-gray-500 hover:text-gray-700 self-center">Reset</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Produk</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-right">Harga Beli</th>
                        <th class="px-4 py-3 text-right">Harga Jual</th>
                        <th class="px-4 py-3 text-right">Margin</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($produks as $produk)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $produk->nama_produk }}</div>
                            @if($produk->brand)<span class="text-xs text-gray-400">{{ $produk->brand }}</span>@endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $produk->kategori->nama ?? '-' }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $produk->kode_provider }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">Rp {{ number_format($produk->harga_beli_idr, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-800">Rp {{ number_format($produk->harga_jual_idr, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right {{ $produk->laba() > 0 ? 'text-emerald-600' : 'text-red-500' }}">Rp {{ number_format($produk->laba(), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <form method="POST" action="{{ route('admin.toggle-produk', $produk->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs px-2 py-1 rounded {{ $produk->status ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                                    {{ $produk->status ? 'Aktif' : 'Off' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.edit-produk', $produk->id) }}" class="text-xs px-2 py-1 rounded border border-gray-200 text-gray-600 hover:bg-gray-50">Edit</a>
                                <form method="POST" action="{{ route('admin.delete-produk', $produk->id) }}" onsubmit="return confirm('Hapus produk ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs px-2 py-1 rounded border border-gray-200 text-red-500 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $produks->withQueryString()->links() }}
</div>
@endsection
