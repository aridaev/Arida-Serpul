<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Pulsa Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-700">⚡ Pulsa Pro</a>
            <a href="{{ route('guest.track') }}" class="text-sm text-gray-600 hover:text-indigo-600 font-medium">Lacak Order</a>
        </div>
    </nav>

    <div class="max-w-lg mx-auto px-4 py-8">
        @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        {{-- Order Summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800 text-lg">Invoice</h2>
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $payment->status === 'waiting_approval' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $payment->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                ">{{ ucfirst(str_replace('_', ' ', $payment->status)) }}</span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Invoice ID</span><span class="font-mono font-medium">{{ $payment->invoice_id }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Produk</span><span class="font-medium">{{ $produk->nama_produk }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Tujuan</span><span class="font-medium">{{ $order['tujuan'] }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Metode</span><span class="font-medium">{{ strtoupper(str_replace('_', ' ', $payment->metode)) }}</span></div>
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Harga</span><span class="font-medium">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span></div>
                @if($payment->kode_unik > 0)
                <div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-gray-500">Kode Unik</span><span class="font-medium text-amber-600">+{{ $payment->kode_unik }}</span></div>
                @endif
                <div class="flex justify-between py-2 bg-indigo-50 rounded-lg px-3 -mx-1">
                    <span class="text-indigo-700 font-semibold">Total Transfer</span>
                    <span class="font-bold text-lg text-indigo-700">Rp {{ number_format($payment->total_bayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Payment Instructions --}}
        @if($payment->status === 'pending' || $payment->status === 'waiting_approval')
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-6">
            <h3 class="font-semibold text-amber-800 text-sm mb-2">Instruksi Pembayaran</h3>
            <ol class="text-sm text-amber-700 space-y-1 list-decimal list-inside">
                <li>Transfer <strong>TEPAT</strong> sejumlah <strong class="text-lg">Rp {{ number_format($payment->total_bayar, 0, ',', '.') }}</strong></li>
                @if($payment->kode_unik > 0)
                <li class="text-amber-800 font-medium">Pastikan 3 digit terakhir: <strong>{{ $payment->kode_unik }}</strong> (kode unik untuk verifikasi otomatis)</li>
                @endif
                <li>Upload bukti transfer di form bawah</li>
                <li>Tunggu verifikasi admin (maks 1x24 jam)</li>
                <li>SN akan dikirim setelah pembayaran diverifikasi</li>
            </ol>
        </div>

        {{-- Upload Proof --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-3">Upload Bukti Pembayaran</h3>
            <form method="POST" action="{{ route('guest.upload-proof', $payment->id) }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2.5 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm file:font-medium">
                <p class="text-xs text-gray-400">Format: JPG, PNG, PDF. Maks 2MB.</p>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Upload Bukti</button>
            </form>
        </div>
        @endif

        {{-- Track info --}}
        <div class="bg-gray-100 rounded-xl p-5 text-center">
            <p class="text-sm text-gray-600">Simpan Invoice ID Anda untuk melacak pesanan:</p>
            <p class="font-mono font-bold text-lg text-gray-800 mt-1 select-all">{{ $payment->invoice_id }}</p>
            <a href="{{ route('guest.track', ['invoice_id' => $payment->invoice_id]) }}" class="inline-block mt-3 text-sm text-indigo-600 font-medium hover:underline">Lacak Pesanan &rarr;</a>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-indigo-600">&larr; Kembali ke Home</a>
        </div>
    </div>
</body>
</html>
