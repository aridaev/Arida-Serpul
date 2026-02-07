<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Order - Pulsa Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-700">⚡ Pulsa Pro</a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 font-medium">Login</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">Daftar</a>
            </div>
        </div>
    </nav>

    <div class="max-w-lg mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Lacak Pesanan</h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" action="{{ route('guest.track') }}" class="flex gap-3">
                <input type="text" name="invoice_id" value="{{ request('invoice_id') }}" required placeholder="Masukkan Invoice ID (INV...)" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-mono">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Cari</button>
            </form>
        </div>

        @if(request('invoice_id') && !$payment)
        <div class="bg-red-50 border border-red-200 rounded-xl p-5 text-center">
            <p class="text-red-600 text-sm font-medium">Invoice tidak ditemukan.</p>
            <p class="text-red-400 text-xs mt-1">Pastikan Invoice ID yang Anda masukkan benar.</p>
        </div>
        @endif

        @if($payment)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">{{ $payment->invoice_id }}</h3>
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $payment->status === 'waiting_approval' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $payment->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                    {{ $payment->status === 'expired' ? 'bg-gray-100 text-gray-700' : '' }}
                ">{{ ucfirst(str_replace('_', ' ', $payment->status)) }}</span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Metode</span><span class="font-medium">{{ strtoupper(str_replace('_', ' ', $payment->metode)) }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Total Bayar</span><span class="font-bold text-indigo-600">Rp {{ number_format($payment->total_bayar, 0, ',', '.') }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Dibuat</span><span>{{ $payment->created_at->format('d/m/Y H:i') }}</span></div>
                @if($payment->paid_at)
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Dibayar</span><span class="text-emerald-600 font-medium">{{ $payment->paid_at->format('d/m/Y H:i') }}</span></div>
                @endif
            </div>
        </div>

        @if($transaksi)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Detail Transaksi</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">TRX ID</span><span class="font-mono font-medium">{{ $transaksi->trx_id }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Produk</span><span class="font-medium">{{ $transaksi->produk->nama_produk ?? '-' }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Tujuan</span><span class="font-medium">{{ $transaksi->tujuan }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50">
                    <span class="text-gray-500">Status</span>
                    <span class="text-xs px-2 py-1 rounded-full font-medium {{ $transaksi->status === 'success' ? 'bg-emerald-100 text-emerald-700' : ($transaksi->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($transaksi->status) }}</span>
                </div>
                @if($transaksi->sn)
                <div class="flex justify-between py-1.5 bg-emerald-50 rounded-lg px-3 -mx-1">
                    <span class="text-emerald-700 font-medium">SN / Token</span>
                    <span class="font-mono font-bold text-emerald-800 select-all">{{ $transaksi->sn }}</span>
                </div>
                @endif
            </div>
        </div>
        @elseif($payment->status === 'paid')
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center">
            <p class="text-blue-700 text-sm">Pembayaran sudah diverifikasi. Transaksi sedang diproses...</p>
        </div>
        @elseif($payment->status === 'waiting_approval')
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-center">
            <p class="text-amber-700 text-sm">Bukti pembayaran sedang diverifikasi admin. Mohon tunggu.</p>
        </div>
        @endif
        @endif

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-indigo-600">&larr; Kembali ke Home</a>
        </div>
    </div>
</body>
</html>
