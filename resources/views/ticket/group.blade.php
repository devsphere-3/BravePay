@extends('layouts.app')

@section('title', 'E-Ticket Grup — ' . ($order_code ?? ''))

@push('head')
<style>
@media print {
    body { background: white !important; }
    .no-print { display: none !important; }
    .ticket-card { break-inside: avoid; page-break-inside: avoid; margin-bottom: 1.5rem; }
}
</style>
@endpush

@section('content')

@php
$comp       = $competition ?? null;
$comp_name  = $comp ? ($comp->name ?? 'Event') : 'Event';
$event_name = $comp ? ($comp->event_name ?? '') : '';
$event_date = $comp ? ($comp->event_date ?? null) : null;
$location   = $comp ? ($comp->location ?? '') : '';
$category   = $comp ? ($comp->category ?? '') : '';
$reg        = $registration ?? null;
$total      = $tickets->count();
@endphp

<div class="bg-[#F0F4FF] min-h-screen py-8">
    <div class="section-container">

        {{-- Top bar --}}
        <div class="flex items-center justify-between mb-6 no-print">
            <a href="javascript:history.back()" class="flex items-center gap-2 text-[#64748B] hover:text-[#2563EB] text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali
            </a>
            <button onclick="window.print()" class="btn-secondary btn-sm gap-2 no-print">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Semua
            </button>
        </div>

        {{-- Group header --}}
        <div class="max-w-2xl mx-auto mb-6">
            <div class="card p-5 flex items-center gap-4 bg-[#EFF6FF] border-[#BFDBFE]">
                <div class="w-12 h-12 rounded-2xl bg-[#2563EB] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    @if($event_name)
                    <p class="text-xs font-bold text-[#64748B] uppercase tracking-wide">{{ $event_name }}</p>
                    @endif
                    <p class="font-extrabold text-[#0B1040] truncate">{{ $comp_name }}</p>
                    <p class="text-xs text-[#64748B] mt-0.5">
                        {{ $event_date ? \Carbon\Carbon::parse($event_date)->translatedFormat('d F Y') : 'TBA' }}
                        @if($location) &bull; {{ $location }} @endif
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <div class="inline-flex items-center gap-1.5 bg-white border border-[#BFDBFE] rounded-xl px-3 py-1.5">
                        <span class="text-xl font-extrabold text-[#2563EB]">{{ $total }}</span>
                        <span class="text-xs font-semibold text-[#64748B]">tiket</span>
                    </div>
                    <p class="text-xs text-[#94A3B8] mt-1 font-mono">{{ $order_code }}</p>
                </div>
            </div>
        </div>

        {{-- Ticket cards --}}
        <div class="max-w-2xl mx-auto space-y-5">
            @foreach($tickets as $i => $t)
            @php
                $ticket_code = $t->ticket_code ?? '—';
                $participant = $t->participant ?? 'Peserta ' . ($i + 1);
                $status      = $t->status ?? 'active';
            @endphp
            <div class="ticket-card card overflow-hidden border-2 {{ $status === 'used' ? 'border-green-200' : ($status === 'cancelled' ? 'border-red-200' : 'border-[#E2E8F7]') }}">

                {{-- Header --}}
                <div class="relative overflow-hidden bg-[#0B1040] px-6 pt-5 pb-8">
                    <div class="absolute inset-0 batik-kawung opacity-20 pointer-events-none"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-500 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                                </svg>
                            </div>
                            <span class="text-white font-extrabold text-sm">BravePay</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-blue-600/40 flex items-center justify-center text-blue-200 text-xs font-extrabold">{{ $i + 1 }}</span>
                            <span class="text-blue-200/60 text-xs font-mono uppercase tracking-widest">E-TICKET</span>
                        </div>
                    </div>
                    <div class="relative mt-4">
                        <p class="text-blue-300/70 text-xs font-bold uppercase tracking-widest mb-0.5">{{ $event_name ?: $comp_name }}</p>
                        <h2 class="text-white font-extrabold text-lg leading-tight">{{ $comp_name }}</h2>
                    </div>
                </div>

                {{-- Tear edge --}}
                <div class="relative flex items-center h-0">
                    <div class="absolute -left-3 w-6 h-6 rounded-full bg-[#F0F4FF] border border-[#E2E8F7]"></div>
                    <div class="flex-1 border-t-2 border-dashed border-[#E2E8F7] mx-3"></div>
                    <div class="absolute -right-3 w-6 h-6 rounded-full bg-[#F0F4FF] border border-[#E2E8F7]"></div>
                </div>

                {{-- Body --}}
                <div class="bg-white px-6 pt-6 pb-5">
                    @if($status === 'used')
                    <div class="flex items-center gap-2 mb-4 px-3 py-2 bg-green-50 border border-green-200 rounded-xl">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs font-bold text-green-800">Tiket Sudah Digunakan — Check-in berhasil</p>
                    </div>
                    @endif

                    <div class="flex items-start gap-5">
                        {{-- QR placeholder --}}
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 rounded-xl border-2 border-[#E2E8F7] flex items-center justify-center bg-[#F8FAFF] p-2">
                                <svg viewBox="0 0 100 100" class="w-full h-full text-[#0B1040]" fill="currentColor">
                                    <rect x="5" y="5" width="35" height="35" rx="3" fill="none" stroke="currentColor" stroke-width="5"/>
                                    <rect x="12" y="12" width="21" height="21" rx="1"/>
                                    <rect x="60" y="5" width="35" height="35" rx="3" fill="none" stroke="currentColor" stroke-width="5"/>
                                    <rect x="67" y="12" width="21" height="21" rx="1"/>
                                    <rect x="5" y="60" width="35" height="35" rx="3" fill="none" stroke="currentColor" stroke-width="5"/>
                                    <rect x="12" y="67" width="21" height="21" rx="1"/>
                                    <rect x="50" y="50" width="8" height="8" rx="1"/>
                                    <rect x="62" y="50" width="8" height="8" rx="1"/>
                                    <rect x="74" y="50" width="8" height="8" rx="1"/>
                                    <rect x="86" y="50" width="8" height="8" rx="1"/>
                                    <rect x="50" y="62" width="8" height="8" rx="1"/>
                                    <rect x="74" y="62" width="8" height="8" rx="1"/>
                                    <rect x="62" y="74" width="8" height="8" rx="1"/>
                                    <rect x="86" y="74" width="8" height="8" rx="1"/>
                                    <rect x="50" y="86" width="8" height="8" rx="1"/>
                                    <rect x="62" y="86" width="8" height="8" rx="1"/>
                                    <rect x="86" y="86" width="8" height="8" rx="1"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0 space-y-3">
                            <div>
                                <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-0.5">PESERTA</p>
                                <p class="font-extrabold text-[#0B1040] text-base leading-tight">{{ $participant }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-0.5">TANGGAL</p>
                                    <p class="font-semibold text-[#0B1040] text-xs">
                                        {{ $event_date ? \Carbon\Carbon::parse($event_date)->translatedFormat('d F Y') : 'TBA' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-0.5">LOKASI</p>
                                    <p class="font-semibold text-[#0B1040] text-xs">{{ $location ?: '—' }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-0.5">TICKET CODE</p>
                                <p class="font-extrabold text-[#1D4ED8] font-mono text-sm">{{ $ticket_code }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-4 pt-3 border-t border-[#E2E8F7] flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <x-status-badge :status="$status"/>
                            <span class="text-[10px] text-[#94A3B8] font-mono">{{ $order_code }}</span>
                        </div>
                        <a href="{{ route('ticket.show', $ticket_code) }}" class="text-xs font-semibold text-[#2563EB] hover:underline flex items-center gap-1 no-print">
                            Lihat Detail
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Bottom actions --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2 no-print">
                <button onclick="window.print()" class="btn-secondary flex-1 justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Semua Tiket
                </button>
                <a href="{{ route('competitions.index') }}" class="btn-primary flex-1 justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
