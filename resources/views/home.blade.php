<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSettings['seo_title'] ?? 'PulsaPro - Beli Pulsa, Paket Data, Token PLN & Game Murah' }}</title>
    <meta name="description" content="{{ $appSettings['seo_description'] ?? '' }}">
    <meta name="keywords" content="{{ $appSettings['seo_keywords'] ?? '' }}">
    <meta property="og:title" content="{{ $appSettings['seo_title'] ?? ($appSettings['app_name'] ?? 'PulsaPro') }}">
    <meta property="og:description" content="{{ $appSettings['seo_description'] ?? '' }}">
    @if(!empty($appSettings['seo_og_image']))<meta property="og:image" content="{{ url($appSettings['seo_og_image']) }}">@endif
    @if(!empty($appSettings['app_favicon_url']))<link rel="icon" href="{{ $appSettings['app_favicon_url'] }}">@endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>[x-cloak]{display:none!important} body{font-family:'Inter',sans-serif}</style>
    <script>
    tailwind.config = {
        theme: { extend: { colors: { brand: { 50:'#eef2ff',100:'#e0e7ff',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81' }}}}
    }
    </script>
</head>
<body class="bg-white min-h-screen text-gray-900">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            @if(!empty($appSettings['app_logo_url']))
            <a href="{{ route('home') }}"><img src="{{ $appSettings['app_logo_url'] }}" alt="{{ $appSettings['app_name'] ?? 'PulsaPro' }}" class="h-7"></a>
            @else
            <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight text-gray-900">{{ $appSettings['app_name'] ?? 'PulsaPro' }}</a>
            @endif
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('guest.track') }}" class="text-gray-500 hover:text-gray-900 transition hidden sm:block">Lacak Order</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-brand-600 text-white px-4 py-1.5 rounded-lg font-medium hover:bg-brand-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-brand-600 text-white px-4 py-1.5 rounded-lg font-medium hover:bg-brand-700 transition">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-brand-50 to-white">
        <div class="max-w-5xl mx-auto px-4 pt-16 pb-12 text-center relative z-10">
            <span class="inline-block text-xs font-semibold tracking-wider uppercase text-brand-600 bg-brand-100 px-3 py-1 rounded-full mb-4">{{ $appSettings['hero_badge_text'] ?? 'Bayar Tagihan & Isi Ulang' }}</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight text-gray-900">{{ $appSettings['hero_title'] ?? 'Isi Pulsa & Bayar Tagihan' }}<br class="hidden sm:block"><span class="text-brand-600">{{ $appSettings['hero_subtitle'] ?? 'Cepat, Murah, Tanpa Ribet' }}</span></h1>
            <p class="mt-3 text-gray-500 max-w-xl mx-auto text-sm sm:text-base">{{ $appSettings['app_description'] ?? 'Langsung pilih produk dan bayar. Tidak perlu daftar akun.' }}</p>
            <div class="mt-6 flex justify-center gap-3">
                <a href="#produk" class="bg-brand-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-brand-700 transition shadow-sm shadow-brand-200">Beli Sekarang</a>
                <a href="{{ route('register') }}" class="bg-white text-gray-700 border border-gray-200 px-5 py-2.5 rounded-lg text-sm font-semibold hover:border-gray-300 hover:bg-gray-50 transition">Jadi Reseller</a>
            </div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image:url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><circle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;1.5&quot; fill=&quot;%23000&quot;/></svg>');"></div>
    </section>

    {{-- Features strip --}}
    <section class="border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-4 py-6 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center text-xs sm:text-sm">
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="font-semibold text-gray-800">Proses Instan</span>
            </div>
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                </div>
                <span class="font-semibold text-gray-800">Harga Termurah</span>
            </div>
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="font-semibold text-gray-800">Aman & Tercatat</span>
            </div>
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="font-semibold text-gray-800">Tanpa Daftar</span>
            </div>
        </div>
    </section>

    {{-- Kategori Produk --}}
    <section id="produk" class="max-w-5xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">Pilih Layanan</h2>
            <a href="{{ route('guest.track') }}" class="text-xs text-brand-600 hover:underline font-medium sm:hidden">Lacak Order</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($kategoris as $kat)
            <a href="{{ route('guest.kategori', $kat->slug) }}" class="group relative bg-white rounded-xl border border-gray-150 p-4 hover:border-brand-300 hover:shadow-md hover:shadow-brand-50 transition-all duration-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-50 group-hover:bg-brand-50 flex items-center justify-center flex-shrink-0 transition">
                        @switch($kat->nama)
                            @case('Pulsa')
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-600 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                                @break
                            @case('Paket Data')
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-600 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                                @break
                            @case('Token PLN')
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-600 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                @break
                            @case('Game')
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-600 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 00.657-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58z"/></svg>
                                @break
                            @case('E-Wallet')
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-600 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 013 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 013 6v3"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-600 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        @endswitch
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-sm text-gray-800 group-hover:text-brand-700 transition truncate">{{ $kat->nama }}</h3>
                        <p class="text-xs text-gray-400">{{ $kat->produks_count }} produk</p>
                    </div>
                </div>
                <svg class="absolute top-1/2 right-3 -translate-y-1/2 w-4 h-4 text-gray-300 group-hover:text-brand-400 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </a>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-gray-50 border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-4 py-10 text-center">
            <h2 class="text-lg font-bold text-gray-900">{{ $appSettings['cta_title'] ?? 'Mau Harga Lebih Murah?' }}</h2>
            <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">{{ $appSettings['cta_description'] ?? 'Daftar sebagai member untuk mendapatkan harga reseller, saldo system, dan komisi referral.' }}</p>
            <a href="{{ route('register') }}" class="inline-block mt-4 bg-brand-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-brand-700 transition">Daftar Gratis</a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 bg-white">
        <div class="max-w-5xl mx-auto px-4 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-400">
            <span class="font-semibold text-gray-600">{{ $appSettings['app_name'] ?? 'PulsaPro' }}</span>
            <span>{{ $appSettings['footer_text'] ?? '' }} &copy; {{ date('Y') }} {{ $appSettings['app_name'] ?? 'PulsaPro' }}. All rights reserved.</span>
        </div>
    </footer>
</body>
</html>
