<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pulsa Pro')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <style>[x-cloak]{display:none!important} body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false }">
    {{-- Sidebar --}}
    <div class="flex h-screen overflow-hidden">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false" class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed lg:static lg:translate-x-0 z-40 w-64 h-full bg-gradient-to-b from-indigo-900 to-indigo-800 text-white transition-transform duration-200 flex flex-col">
            <div class="p-5 border-b border-indigo-700">
                <h1 class="text-xl font-bold tracking-tight">⚡ Pulsa Pro</h1>
                <p class="text-indigo-300 text-xs mt-1">Web Pulsa Professional</p>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('transaksi.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('transaksi.create') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                    Beli Pulsa
                </a>
                <a href="{{ route('transaksi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('transaksi.index') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Riwayat Transaksi
                </a>
                <a href="{{ route('payment.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('payment.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Payment
                </a>
                <a href="{{ route('mutasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('mutasi.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                    Mutasi Saldo
                </a>
                <a href="{{ route('referral.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('referral.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Referral
                </a>
            </nav>
            <div class="p-4 border-t border-indigo-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-sm font-bold">{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->nama }}</p>
                        <p class="text-xs text-indigo-300">{{ Auth::user()->level }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top bar --}}
            <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h2 class="text-lg font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg text-sm font-semibold">
                        {{ Auth::user()->currency->symbol ?? 'Rp' }} {{ number_format(Auth::user()->saldo, 0, ',', '.') }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-600 text-sm">Logout</button>
                    </form>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
