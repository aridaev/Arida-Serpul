<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $produk->nama_produk }} - {{ $appSettings['app_name'] ?? 'PulsaPro' }}</title>
    @if(!empty($appSettings['app_favicon_url']))<link rel="icon" href="{{ $appSettings['app_favicon_url'] }}">@endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <style>[x-cloak]{display:none!important} body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white/80 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight text-gray-900">{{ $appSettings['app_name'] ?? 'PulsaPro' }}</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('guest.track') }}" class="text-gray-500 hover:text-gray-900 transition">Lacak Order</a>
                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 transition">Masuk</a>
            </div>
        </div>
    </nav>

    <div class="max-w-lg mx-auto px-4 py-8" x-data="{ tipeBayar: '{{ $manualEnabled ? 'manual' : 'gateway' }}', metode: '' }">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
            <span>/</span>
            <a href="{{ route('guest.kategori', $produk->kategori->slug ?? $produk->kategori_id) }}" class="hover:text-indigo-600">{{ $produk->kategori->nama ?? 'Produk' }}</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">Checkout</span>
        </div>

        {{-- Product Summary --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400">Produk</p>
                    <h2 class="font-bold text-gray-900 mt-0.5">{{ $produk->nama_produk }}</h2>
                    @if($produk->brand)<p class="text-xs text-gray-500">{{ $produk->brand }}</p>@endif
                </div>
                <p class="text-xl font-extrabold text-indigo-600">Rp {{ number_format($produk->harga_jual_idr, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('guest.process-checkout', $produk->slug) }}" class="space-y-5">
            @csrf
            <input type="hidden" name="tipe_bayar" :value="tipeBayar">
            <input type="hidden" name="metode" :value="metode">

            {{-- Customer Data --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                <h3 class="font-semibold text-gray-800 text-sm">Data Pembelian</h3>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Tujuan *</label>
                    <input type="text" name="tujuan" value="{{ old('tujuan') }}" required placeholder="08xxxxxxxxxx" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama *</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">No HP *</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxx" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Email (opsional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-800 text-sm mb-3">Metode Pembayaran</h3>

                {{-- Tabs --}}
                @if($manualEnabled && $gatewayEnabled)
                <div class="flex gap-2 mb-4">
                    <button type="button" @click="tipeBayar='manual'; metode=''" :class="tipeBayar==='manual' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-2 rounded-lg text-xs font-medium transition">Transfer Manual</button>
                    <button type="button" @click="tipeBayar='gateway'; metode=''" :class="tipeBayar==='gateway' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-2 rounded-lg text-xs font-medium transition">Bayar Otomatis</button>
                </div>
                @endif

                {{-- Manual Transfer --}}
                <div x-show="tipeBayar==='manual'" x-cloak>
                    @if($bankAccounts->where('tipe','bank')->count() > 0)
                    <p class="text-xs text-gray-500 mb-2">Transfer Bank</p>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        @foreach($bankAccounts->where('tipe','bank') as $bank)
                        <label @click="metode='bank_{{ $bank->kode }}'" :class="metode==='bank_{{ $bank->kode }}' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'" class="border rounded-lg p-3 cursor-pointer transition">
                            <span class="text-sm font-semibold text-gray-800">{{ $bank->nama_bank }}</span>
                            <span class="block text-xs text-gray-500 mt-0.5">{{ $bank->no_rekening }}</span>
                            <span class="block text-xs text-gray-400">a/n {{ $bank->atas_nama }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif

                    @if($bankAccounts->where('tipe','ewallet')->count() > 0)
                    <p class="text-xs text-gray-500 mb-2">E-Wallet</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($bankAccounts->where('tipe','ewallet') as $bank)
                        <label @click="metode='ewallet_{{ $bank->kode }}'" :class="metode==='ewallet_{{ $bank->kode }}' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'" class="border rounded-lg p-3 cursor-pointer transition">
                            <span class="text-sm font-semibold text-gray-800">{{ $bank->nama_bank }}</span>
                            <span class="block text-xs text-gray-500 mt-0.5">{{ $bank->no_rekening }}</span>
                            <span class="block text-xs text-gray-400">a/n {{ $bank->atas_nama }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif

                    @if($bankAccounts->count() === 0)
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada rekening bank tersedia.</p>
                    @endif
                </div>

                {{-- Gateway (Tripay) --}}
                <div x-show="tipeBayar==='gateway'" x-cloak>
                    <p class="text-xs text-gray-500 mb-2">Virtual Account</p>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        @foreach(['BRIVA' => 'BRI VA', 'BCAVA' => 'BCA VA', 'BNIVA' => 'BNI VA', 'MANDIRIVA' => 'Mandiri VA', 'PERMATAVA' => 'Permata VA'] as $code => $label)
                        <label @click="metode='{{ $code }}'" :class="metode==='{{ $code }}' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'" class="border rounded-lg p-3 text-center cursor-pointer transition">
                            <span class="text-sm font-semibold text-gray-800">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>

                    <p class="text-xs text-gray-500 mb-2">E-Wallet & QRIS</p>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        @foreach(['QRIS' => 'QRIS', 'QRISC' => 'QRIS Custom', 'OVO' => 'OVO', 'DANA' => 'DANA', 'SHOPEEPAY' => 'ShopeePay'] as $code => $label)
                        <label @click="metode='{{ $code }}'" :class="metode==='{{ $code }}' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'" class="border rounded-lg p-2.5 text-center cursor-pointer transition">
                            <span class="text-xs font-semibold text-gray-800">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>

                    <p class="text-xs text-gray-500 mb-2">Minimarket</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['ALFAMART' => 'Alfamart', 'INDOMARET' => 'Indomaret'] as $code => $label)
                        <label @click="metode='{{ $code }}'" :class="metode==='{{ $code }}' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'" class="border rounded-lg p-3 text-center cursor-pointer transition">
                            <span class="text-sm font-semibold text-gray-800">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="bg-gray-100 rounded-xl p-4 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Produk</span><span class="font-medium text-gray-800">{{ $produk->nama_produk }}</span></div>
                <div class="flex justify-between mt-1"><span class="text-gray-500">Harga</span><span class="font-bold text-indigo-600">Rp {{ number_format($produk->harga_jual_idr, 0, ',', '.') }}</span></div>
                <p class="text-xs text-gray-400 mt-2" x-show="tipeBayar==='manual'">+ Kode unik akan ditambahkan saat checkout</p>
            </div>

            <button type="submit" :disabled="!metode" :class="metode ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-300 cursor-not-allowed'" class="w-full text-white py-3 rounded-xl font-semibold text-sm transition">Buat Pesanan</button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-4">Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a> untuk harga lebih murah.</p>
    </div>
</body>
</html>
