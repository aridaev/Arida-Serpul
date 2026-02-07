@extends('layouts.admin')
@section('title', 'Users - Admin')
@section('header', 'Users')
@section('subheader', 'Daftar member & reseller terdaftar')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 text-[11px] text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3 text-left font-medium">User</th>
                        <th class="px-4 py-3 text-left font-medium">No HP</th>
                        <th class="px-4 py-3 text-right font-medium">Saldo</th>
                        <th class="px-4 py-3 text-center font-medium">Level</th>
                        <th class="px-4 py-3 text-center font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-50 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-brand-600">{{ strtoupper(substr($u->username, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-800">{{ $u->username }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $u->nama }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-gray-600 font-mono">{{ $u->no_hp }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <p class="text-xs font-bold text-gray-800">{{ $u->currency->symbol ?? 'Rp' }} {{ number_format($u->saldo, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($u->level === 'reseller')
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-violet-50 text-violet-700 font-medium">Reseller</span>
                            @else
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-medium">Member</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 text-[11px] {{ $u->status ? 'text-emerald-600' : 'text-red-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u->status ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                                {{ $u->status ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <p class="text-[11px] text-gray-500">{{ $u->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $u->created_at->diffForHumans() }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            <p class="text-sm text-gray-400">Belum ada user terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
