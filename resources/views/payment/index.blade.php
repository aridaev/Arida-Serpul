@extends('layouts.app')
@section('title', 'Payment - Pulsa Pro')
@section('header', 'Riwayat Payment')

@section('content')
<div class="mb-4">
    <a href="{{ route('payment.deposit') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-emerald-600 hover:to-teal-600 transition">+ Deposit Saldo</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Invoice</th>
                    <th class="px-5 py-3 text-left">Metode</th>
                    <th class="px-5 py-3 text-right">Jumlah</th>
                    <th class="px-5 py-3 text-right">Total Bayar</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-left">Waktu</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs">{{ $p->invoice_id }}</td>
                    <td class="px-5 py-3">{{ $p->metode }}</td>
                    <td class="px-5 py-3 text-right">{{ $p->currency->symbol ?? 'Rp' }} {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right font-semibold">{{ $p->currency->symbol ?? 'Rp' }} {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $p->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $p->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $p->status === 'waiting_approval' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $p->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $p->status === 'expired' ? 'bg-gray-100 text-gray-700' : '' }}
                        ">{{ ucfirst(str_replace('_', ' ', $p->status)) }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('payment.show', $p->id) }}" class="text-indigo-600 hover:underline text-xs">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada payment</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $payments->links() }}</div>
</div>
@endsection
