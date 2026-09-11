@extends('layouts.app')

@section('title', 'E-Ticket — ' . ($ticket->ticket_code ?? 'BRV-TKT-A8F92K'))

@push('head')
<style>
@media print {
    body { background: white !important; }
    .no-print { display: none !important; }
    .print-only { display: block !important; }
    main { padding-top: 0 !important; }
}
</style>
@endpush

@section('content')

@php
$t = $ticket ?? null;
$ticket_code  = $t ? $t->ticket_code : 'BRV-TKT-A8F92K';
$participant  = $t && $t->participant ? $t->participant->name : 'Wahyu Perwira';
$comp_name    = $t && $t->registration && $t->registration->competition ? $t->registration->competition->name : 'Basket Competition';
$event_name   = $t && $t->registration && $t->registration->competition ? ($t->registration->competition->event_name ?? 'GEN FEST 2026') : 'GEN FEST 2026';
$category     = $t && $t->registration && $t->registration->competition ? ($t->registration->competition->category ?? 'Olahraga') : 'Olahraga';
$event_date   = $t && $t->registration && $t->registration->competition ? $t->registration->competition->event_date : '2026-09-20';
$location     = $t && $t->registration && $t->registration->competition ? ($t->registration->competition->location ?? 'Batam') : 'Batam';
$order_code   = $t && $t->registration ? $t->registration->order_code : 'BRV-20260911-0001';
$status       = $t ? $t->status : 'active'; // active|used|cancelled
$qr_url       = $t ? ($t->qr_code ?? null) : null;
@endphp

<div class="bg-[#F0F4FF] min-h-screen py-8">
    <div class="section-container">
        {{-- Actions bar --}}
        <div class="flex items-center justify-between mb-6 no-print">
            <a href="javascript:history.back()" class="flex items-center gap-2 text-[#64748B] hover:text-[#2563EB] text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali
            </a>
            <div class="flex gap-2">
                <button onclick="window.print()" class="btn-secondary btn-sm gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </button>
                <a href="{{ route('ticket.download', $ticket_code) }}" class="btn-primary btn-sm gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>

        {{-- ═══ E-TICKET CARD ═══ --}}
        <div class="max-w-lg mx-auto print-ticket">

            {{-- Status banner (not active) --}}
            @if($status === 'used')
            <div class="flex items-center gap-3 px-5 py-3 bg-green-50 border-2 border-green-200 rounded-2xl mb-4 no-print">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-bold text-green-800 text-sm">Tiket Sudah Digunakan</p>
                    <p class="text-xs text-green-600">Check-in berhasil dilakukan</p>
                </div>
            </div>
            @elseif($status === 'cancelled')
            <div class="flex items-center gap-3 px-5 py-3 bg-red-50 border-2 border-red-200 rounded-2xl mb-4 no-print">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-bold text-red-800 text-sm">Tiket Dibatalkan</p>
            </div>
            @endif

            {{-- Main ticket --}}
            <div class="card overflow-hidden border-2 {{ $status === 'used' ? 'border-green-200' : ($status === 'cancelled' ? 'border-red-200' : 'border-[#E2E8F7]') }}">

                {{-- Ticket header with batik --}}
                <div class="relative overflow-hidden bg-[#0B1040] px-6 pt-6 pb-10">
                    {{-- Batik layer --}}
                    <div class="absolute inset-0 batik-kawung opacity-20 pointer-events-none"></div>
                    {{-- Decorative hexagons --}}
                    <div class="absolute top-2 right-2 pointer-events-none opacity-15" aria-hidden="true">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                            <polygon points="40,5 73,22 73,58 40,75 7,58 7,22" stroke="white" stroke-width="1.5" fill="none"/>
                            <polygon points="40,15 63,28 63,52 40,65 17,52 17,28" stroke="white" stroke-width="1" fill="none"/>
                        </svg>
                    </div>

                    {{-- BravePay brand --}}
                    <div class="relative flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                                </svg>
                            </div>
                            <span class="text-white font-extrabold text-base">BravePay</span>
                        </div>
                        <span class="text-blue-200/60 text-xs font-mono uppercase tracking-widest">E-TICKET</span>
                    </div>

                    {{-- Event name --}}
                    <div class="relative">
                        <p class="text-blue-300/70 text-xs font-bold uppercase tracking-widest mb-1">{{ $event_name }}</p>
                        <h1 class="text-white font-extrabold text-2xl leading-tight">{{ $comp_name }}</h1>
                    </div>

                    {{-- Batik dots row --}}
                    <div class="relative flex gap-1 mt-4">
                        @for($i = 0; $i < 8; $i++)
                        <div class="w-2 h-2 rounded-full {{ $i % 2 === 0 ? 'bg-blue-400' : 'bg-blue-700' }} opacity-60"></div>
                        @endfor
                    </div>
                </div>

                {{-- Tear edge --}}
                <div class="relative flex items-center h-0">
                    <div class="absolute -left-3 w-6 h-6 rounded-full bg-[#F0F4FF] border border-[#E2E8F7]"></div>
                    <div class="flex-1 border-t-2 border-dashed border-[#E2E8F7] mx-3"></div>
                    <div class="absolute -right-3 w-6 h-6 rounded-full bg-[#F0F4FF] border border-[#E2E8F7]"></div>
                </div>

                {{-- Ticket body --}}
                <div class="bg-white px-6 pt-7 pb-6">
                    {{-- Participant info --}}
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-1">PESERTA</p>
                            <p class="font-extrabold text-[#0B1040] text-base leading-tight">{{ $participant }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-1">KATEGORI</p>
                            <p class="font-bold text-[#0B1040]">{{ $category }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-1">TANGGAL</p>
                            <p class="font-bold text-[#0B1040] text-sm">
                                {{ $event_date ? \Carbon\Carbon::parse($event_date)->translatedFormat('d F Y') : 'TBA' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-1">LOKASI</p>
                            <p class="font-bold text-[#0B1040] text-sm">{{ $location }}</p>
                        </div>
                    </div>

                    {{-- Divider with batik dots --}}
                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex-1 h-px bg-[#E2E8F7]"></div>
                        <div class="flex gap-1">
                            @for($i = 0; $i < 5; $i++)
                            <div class="w-1.5 h-1.5 rounded-full {{ $i === 2 ? 'bg-[#2563EB]' : 'bg-[#BFDBFE]' }}"></div>
                            @endfor
                        </div>
                        <div class="flex-1 h-px bg-[#E2E8F7]"></div>
                    </div>

                    {{-- QR + ticket code --}}
                    <div class="flex items-center gap-5">
                        {{-- QR Code area --}}
                        <div class="flex-shrink-0">
                            @if($qr_url)
                                <img src="{{ $qr_url }}" alt="QR Code" class="w-28 h-28 rounded-xl border-2 border-[#E2E8F7]">
                            @else
                                {{-- Placeholder QR --}}
                                <div class="w-28 h-28 rounded-xl border-2 border-[#E2E8F7] flex items-center justify-center bg-[#F8FAFF] p-2">
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
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest mb-1">TICKET CODE</p>
                            <p class="font-extrabold text-[#1D4ED8] font-mono text-base leading-tight">{{ $ticket_code }}</p>
                            <p class="text-[10px] text-[#94A3B8] mt-2 mb-1">ORDER ID</p>
                            <p class="text-xs font-mono text-[#64748B]">{{ $order_code }}</p>
                            <div class="mt-3">
                                <x-status-badge :status="$status"/>
                            </div>
                        </div>
                    </div>

                    {{-- Footer note --}}
                    <div class="mt-5 pt-4 border-t border-[#E2E8F7]">
                        <p class="text-[10px] text-[#94A3B8] text-center">
                            Tunjukkan tiket ini kepada panitia saat check-in &bull; Dilarang memfoto/menyebarkan QR Code
                        </p>
                        {{-- Batik bottom accent --}}
                        <div class="flex justify-center gap-0.5 mt-3">
                            @foreach(['bg-navy-900','bg-blue-700','bg-blue-500','bg-blue-400','bg-blue-300','bg-blue-400','bg-blue-500','bg-blue-700','bg-navy-900'] as $c)
                            <div class="h-1 flex-1 rounded-full {{ $c }} opacity-50"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Share/download --}}
            <div class="flex gap-3 mt-6 no-print">
                <button onclick="window.print()" class="btn-secondary flex-1 justify-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Tiket
                </button>
                <a href="{{ route('ticket.download', $ticket_code) }}" class="btn-primary flex-1 justify-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
