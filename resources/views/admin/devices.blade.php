@extends('layouts.admin')
@section('title', 'Perangkat Terhubung - Admin')
@section('header', 'Perangkat Terhubung')
@section('subheader', 'Daftar perangkat Android yang mengirim notifikasi')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 text-[11px] text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3 text-left font-medium">Device</th>
                        <th class="px-4 py-3 text-left font-medium">Model</th>
                        <th class="px-4 py-3 text-left font-medium">Android</th>
                        <th class="px-4 py-3 text-left font-medium">App Ver</th>
                        <th class="px-4 py-3 text-right font-medium">Notifikasi</th>
                        <th class="px-4 py-3 text-center font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Terakhir Online</th>
                        <th class="px-4 py-3 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($devices as $d)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-800">{{ $d->device_name ?? 'Unknown' }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono">{{ Str::limit($d->device_id, 24) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $d->device_model ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $d->android_version ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $d->app_version ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-xs font-bold text-gray-800">{{ number_format($d->notifications_count) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($d->is_active)
                                @if($d->last_seen_at && $d->last_seen_at->diffInMinutes(now()) < 5)
                                <span class="inline-flex items-center gap-1 text-[11px] text-emerald-600 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Online
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 text-[11px] text-gray-500 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Offline
                                </span>
                                @endif
                            @else
                            <span class="inline-flex items-center gap-1 text-[11px] text-red-500 font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Disabled
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($d->last_seen_at)
                            <p class="text-[11px] text-gray-500">{{ $d->last_seen_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $d->last_seen_at->diffForHumans() }}</p>
                            @else
                            <span class="text-[11px] text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <form method="POST" action="{{ route('admin.devices.toggle', $d->id) }}">
                                    @csrf
                                    <button type="submit" class="p-1 rounded hover:bg-gray-100 transition {{ $d->is_active ? 'text-emerald-500 hover:text-amber-500' : 'text-gray-400 hover:text-emerald-500' }}" title="{{ $d->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.devices.destroy', $d->id) }}" onsubmit="return confirm('Hapus device ini dan semua notifikasinya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 rounded hover:bg-red-50 text-gray-400 hover:text-red-500 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                            <p class="text-sm text-gray-400">Belum ada perangkat terhubung</p>
                            <p class="text-xs text-gray-300 mt-1">Install app Android dan register device untuk mulai</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($devices->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">{{ $devices->links() }}</div>
        @endif
    </div>
</div>
@endsection
