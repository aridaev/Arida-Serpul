<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kategori->nama }} - Pulsa Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-gray-50 min-h-screen">
    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-700">⚡ Pulsa Pro</a>
            <div class="flex items-center gap-3">
                <a href="{{ route('guest.track') }}" class="text-sm text-gray-600 hover:text-indigo-600 font-medium">Lacak Order</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $kategori->nama }}</span>
        </div>

        {{-- Kategori Tabs --}}
        <div class="flex gap-2 flex-wrap mb-4">
            @foreach($kategoris as $kat)
            <a href="{{ route('guest.kategori', $kat->slug) }}" class="px-3 py-1.5 rounded-lg text-sm font-medium {{ $kat->id === $kategori->id ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $kat->icon ?? '📦' }} {{ $kat->nama }}
            </a>
            @endforeach
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $kategori->icon ?? '📦' }} {{ $kategori->nama }}</h1>

        {{-- Brand Sub-Menu (Operator) --}}
        @if($brands->count() > 1)
        <div class="flex gap-2 flex-wrap mb-6">
            <a href="{{ route('guest.kategori', $kategori->slug) }}" class="px-4 py-2 rounded-full text-sm font-medium transition {{ !$selectedBrand ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                Semua
            </a>
            @foreach($brands as $brand)
            <a href="{{ route('guest.kategori', ['kategori' => $kategori->slug, 'brand' => $brand]) }}" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $selectedBrand === $brand ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $brand }}
            </a>
            @endforeach
        </div>
        @endif

        @if($produks->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($produks as $produk)
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:border-indigo-200 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $produk->nama_produk }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Kode: {{ $produk->kode_provider }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Harga</p>
                        <p class="text-lg font-bold text-indigo-600">Rp {{ number_format($produk->harga_jual_idr, 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('guest.checkout', $produk->slug) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Beli</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-xl p-12 text-center shadow-sm border border-gray-100">
            <p class="text-gray-400">Belum ada produk tersedia untuk kategori ini.</p>
        </div>
        @endif
    </div>
</body>
</html>
