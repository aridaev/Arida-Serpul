@extends('layouts.admin')
@section('title', 'Providers - Admin Pulsa Pro')
@section('header', 'Manajemen Providers')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Add Provider Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Tambah Provider</h3>
        <form method="POST" action="{{ route('admin.store-provider') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama</label>
                <input type="text" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">API URL</label>
                <input type="url" name="api_url" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">API Key</label>
                <input type="text" name="api_key" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
                <select name="tipe" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
                    <option value="pulsa">Pulsa</option>
                    <option value="pln">PLN</option>
                    <option value="game">Game</option>
                    <option value="ewallet">E-Wallet</option>
                    <option value="paket_data">Paket Data</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Saldo Awal</label>
                <input type="number" name="saldo" value="0" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
            </div>
            <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Tambah</button>
        </form>
    </div>

    {{-- Provider List --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Tipe</th>
                        <th class="px-4 py-3 text-left">API URL</th>
                        <th class="px-4 py-3 text-right">Saldo</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($providers as $prov)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $prov->nama }}</td>
                        <td class="px-4 py-3"><span class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-700">{{ $prov->tipe }}</span></td>
                        <td class="px-4 py-3 text-xs text-gray-500 max-w-xs truncate">{{ $prov->api_url }}</td>
                        <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($prov->saldo, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center"><span class="w-2 h-2 rounded-full inline-block {{ $prov->status ? 'bg-emerald-500' : 'bg-red-500' }}"></span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada provider</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
