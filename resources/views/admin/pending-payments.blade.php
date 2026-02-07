@extends('layouts.admin')
@section('title', 'Menunggu Verifikasi - Admin')
@section('header', 'Menunggu Verifikasi')
@section('subheader', 'Payment yang perlu di-approve atau reject')

@section('content')
<div class="space-y-4">

    @if($payments->count() > 0)
    <div class="text-xs text-gray-500 flex items-center gap-2">
        <span class="inline-flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            {{ $payments->total() }} payment menunggu verifikasi
        </span>
    </div>
    @endif

    @forelse($payments as $p)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5">
            <div class="flex flex-col lg:flex-row lg:items-start gap-5">
                {{-- Info --}}
                <div class="flex-1 space-y-3">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-mono text-[11px] text-gray-500 bg-gray-50 px-2 py-0.5 rounded">{{ $p->invoice_id }}</span>
                        <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Menunggu Verifikasi
                        </span>
                        <span class="text-[11px] text-gray-400">{{ $p->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">User</p>
                            <p class="text-xs font-semibold text-gray-800">{{ $p->user->username ?? ($p->guest->nama ?? 'Guest') }}</p>
                            @if($p->guest && $p->guest->no_hp)
                            <p class="text-[11px] text-gray-400 font-mono">{{ $p->guest->no_hp }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Metode</p>
                            <p class="text-xs font-medium text-gray-700">{{ $p->metode ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Total Bayar</p>
                            <p class="text-sm font-bold text-gray-900">{{ $p->currency->symbol ?? 'Rp' }} {{ number_format($p->total_bayar, 0, ',', '.') }}</p>
                            @if($p->kode_unik > 0)
                            <p class="text-[10px] text-gray-400">+{{ $p->kode_unik }} kode unik</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Waktu</p>
                            <p class="text-xs text-gray-600">{{ $p->created_at->format('d M Y') }}</p>
                            <p class="text-[11px] text-gray-400">{{ $p->created_at->format('H:i:s') }}</p>
                        </div>
                    </div>

                    {{-- Proof files --}}
                    @if($p->proofs && $p->proofs->count() > 0)
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-2">Bukti Transfer ({{ $p->proofs->count() }})</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($p->proofs as $proof)
                            <a href="{{ asset('storage/' . $proof->file_path) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-brand-300 hover:bg-brand-50/50 transition text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V5.25a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                Lihat Bukti {{ $loop->iteration }}
                                @if($proof->status === 'pending')
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                @elseif($proof->status === 'approved')
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                @else
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex lg:flex-col gap-2 lg:w-32 flex-shrink-0">
                    @foreach($p->proofs->where('status', 'pending') as $proof)
                    <form method="POST" action="{{ route('admin.verify-payment', $proof->id) }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-xs font-medium hover:bg-emerald-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.verify-payment', $proof->id) }}" class="flex-1" onsubmit="return confirm('Reject payment ini?')">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-lg text-xs font-medium hover:bg-red-50 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
        <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-800 mb-1">Semua Beres!</h3>
        <p class="text-xs text-gray-400">Tidak ada payment yang menunggu verifikasi saat ini.</p>
    </div>
    @endforelse

    @if($payments->hasPages())
    <div class="flex justify-center">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
