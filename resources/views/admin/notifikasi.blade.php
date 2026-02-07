@extends('layouts.admin')
@section('title', 'Captured Notifications - Admin')
@section('header', 'Captured Notifications')
@section('subheader', 'Notifikasi yang ditangkap dari perangkat Android')

@section('content')
<div class="space-y-4">

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Total</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Hari Ini</p>
            <p class="text-xl font-bold text-blue-600 mt-1">{{ number_format($stats['today']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Belum Dibaca</p>
            <p class="text-xl font-bold text-amber-600 mt-1">{{ number_format($stats['unread']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Device Aktif</p>
            <p class="text-xl font-bold text-emerald-600 mt-1">{{ $stats['devices'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.notifikasi') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="text-[10px] text-gray-400 uppercase tracking-wider block mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, teks, app..."
                       class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>
            <div class="min-w-[140px]">
                <label class="text-[10px] text-gray-400 uppercase tracking-wider block mb-1">Device</label>
                <select name="device" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <option value="">Semua Device</option>
                    @foreach($devices as $d)
                    <option value="{{ $d->device_id }}" {{ request('device') === $d->device_id ? 'selected' : '' }}>{{ $d->device_name ?? $d->device_id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <label class="text-[10px] text-gray-400 uppercase tracking-wider block mb-1">Aplikasi</label>
                <select name="app" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <option value="">Semua App</option>
                    @foreach($apps as $a)
                    <option value="{{ $a->package_name }}" {{ request('app') === $a->package_name ? 'selected' : '' }}>{{ $a->app_name ?? $a->package_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[100px]">
                <label class="text-[10px] text-gray-400 uppercase tracking-wider block mb-1">Status</label>
                <select name="read" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <option value="">Semua</option>
                    <option value="no" {{ request('read') === 'no' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="yes" {{ request('read') === 'yes' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-xs font-medium hover:bg-gray-800 transition">Filter</button>
                <a href="{{ route('admin.notifikasi') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-500 rounded-lg text-xs font-medium hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Actions --}}
    @if($stats['unread'] > 0)
    <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('admin.notifikasi.mark-read') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs text-gray-600 hover:bg-gray-50 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Tandai Semua Dibaca
            </button>
        </form>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 text-[11px] text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3 text-center font-medium w-8"></th>
                        <th class="px-4 py-3 text-left font-medium">Aplikasi</th>
                        <th class="px-4 py-3 text-left font-medium">Konten</th>
                        <th class="px-4 py-3 text-left font-medium">Device</th>
                        <th class="px-4 py-3 text-right font-medium">Waktu</th>
                        <th class="px-4 py-3 text-center font-medium w-16">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm" x-data>
                    @forelse($notifications as $n)
                    <tr class="hover:bg-gray-50/50 transition {{ !$n->is_read ? 'bg-brand-50/30' : '' }}" x-data="{ showDetail: false }">
                        <td class="px-4 py-3 text-center">
                            @if(!$n->is_read)
                            <span class="w-2 h-2 rounded-full bg-brand-500 inline-block"></span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-semibold text-gray-800">{{ $n->app_name ?? 'Unknown' }}</p>
                            <p class="text-[10px] text-gray-400 font-mono truncate max-w-[140px]">{{ $n->package_name ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 max-w-sm">
                            <p class="text-xs font-medium text-gray-800 {{ !$n->is_read ? 'font-semibold' : '' }}">{{ Str::limit($n->title, 60) ?: '(no title)' }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ Str::limit($n->text, 80) ?: '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[11px] text-gray-500">{{ Str::limit($n->device_name ?? $n->device_id, 20) }}</p>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <p class="text-[11px] text-gray-500">{{ $n->posted_at ? $n->posted_at->format('d M Y') : $n->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $n->posted_at ? $n->posted_at->format('H:i:s') : $n->created_at->format('H:i:s') }}</p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button @click="showDetail = !showDetail"
                                        class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-brand-600 transition" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.notifikasi.destroy', $n->id) }}" onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 rounded hover:bg-red-50 text-gray-400 hover:text-red-500 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- Expandable detail row --}}
                    <tr x-show="showDetail" x-cloak class="bg-gray-50/50">
                        <td colspan="6" class="px-6 py-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Title</span>
                                        <p class="text-gray-800 font-medium mt-0.5">{{ $n->title ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Text</span>
                                        <p class="text-gray-700 mt-0.5 whitespace-pre-wrap">{{ $n->text ?: '-' }}</p>
                                    </div>
                                    @if($n->big_text)
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Big Text</span>
                                        <p class="text-gray-700 mt-0.5 whitespace-pre-wrap">{{ $n->big_text }}</p>
                                    </div>
                                    @endif
                                    @if($n->ticker)
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Ticker</span>
                                        <p class="text-gray-700 mt-0.5">{{ $n->ticker }}</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Package</span>
                                        <p class="text-gray-700 font-mono mt-0.5">{{ $n->package_name ?: '-' }}</p>
                                    </div>
                                    @if($n->tag)
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Tag</span>
                                        <p class="text-gray-700 mt-0.5">{{ $n->tag }}</p>
                                    </div>
                                    @endif
                                    @if($n->category)
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Category</span>
                                        <p class="text-gray-700 mt-0.5">{{ $n->category }}</p>
                                    </div>
                                    @endif
                                    @if($n->extras)
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Extras</span>
                                        <pre class="text-[11px] text-gray-600 mt-0.5 bg-gray-100 rounded p-2 overflow-x-auto">{{ json_encode($n->extras, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Device</span>
                                        <p class="text-gray-700 mt-0.5">{{ $n->device_name ?? $n->device_id }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Posted At</span>
                                        <p class="text-gray-700 mt-0.5">{{ $n->posted_at ? $n->posted_at->format('d M Y H:i:s') : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            <p class="text-sm text-gray-400">Belum ada notifikasi yang ditangkap</p>
                            <p class="text-xs text-gray-300 mt-1">Hubungkan perangkat Android untuk mulai menangkap notifikasi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($notifications->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">{{ $notifications->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
