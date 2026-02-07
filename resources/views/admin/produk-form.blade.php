@extends('layouts.admin')
@section('title', ($produk ? 'Edit' : 'Tambah') . ' Produk - Admin')
@section('header', ($produk ? 'Edit' : 'Tambah') . ' Produk')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ $produk ? route('admin.update-produk', $produk->id) : route('admin.store-produk') }}" class="p-6 space-y-4">
            @csrf
            @if($produk) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kategori *</label>
                    <select name="kategori_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Pilih kategori</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id', $produk->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $produk->brand ?? '') }}" placeholder="Telkomsel, XL, dll" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Produk *</label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" required placeholder="Pulsa Telkomsel 10.000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kode Provider / SKU *</label>
                <input type="text" name="kode_provider" value="{{ old('kode_provider', $produk->kode_provider ?? '') }}" required placeholder="tsel10" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono">
                <p class="text-xs text-gray-400 mt-1">Kode SKU dari DigiFlazz atau provider lain</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Harga Beli (IDR) *</label>
                    <input type="number" name="harga_beli_idr" value="{{ old('harga_beli_idr', $produk->harga_beli_idr ?? '') }}" required min="0" step="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Harga Jual (IDR) *</label>
                    <input type="number" name="harga_jual_idr" value="{{ old('harga_jual_idr', $produk->harga_jual_idr ?? '') }}" required min="0" step="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="1" {{ old('status', $produk->status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status', $produk->status ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            @if($errors->any())
            <div class="p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
            @endif

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 transition">{{ $produk ? 'Update' : 'Simpan' }}</button>
                <a href="{{ route('admin.produks') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
