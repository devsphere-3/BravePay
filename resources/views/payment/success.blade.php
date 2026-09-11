@extends('layouts.app')

@section('title', 'Pembayaran Berhasil!')

@section('content')

@php
$reg = $registration ?? null;
$order_code   = $reg ? $reg->order_code : 'BRV-20260911-0001';
$comp_name    = $reg ? ($reg->competition->name ?? 'Basket Competition') : 'Basket Competition';
$participants = $reg ? $reg->total_participants : 3;
$total_amount = $reg ? $reg->total_amount : 150000;
$tickets      = $reg ? $reg->tickets : collect([]);
@endphp

<div class="relative bg-gradient-to-b from-[#EFF6FF] to-white py-10">
    <div class="absolute inset-0 batik-dots pointer-events-none opacity-60"></div>
    <div class="section-container relative z-10">

        <div class="mb-8">
            <x-step-indicator :current="4"/>
        </div>

        <div class="max-w-lg mx-auto">

            {{-- Success card --}}
            <div class="card overflow-hidden mb-6">
                {{-- Confetti-style header --}}
                <div class="bg-gradient-to-br from-[#10B981] to-[#059669] relative overflow-hidden px-6 py-8 text-center">
                    <div class="absolute inset-0 batik-geo opacity-10"></div>
                    {{-- Glow --}}
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="relative">
                        <div class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-4 border-2 border-white/30">
                            <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-extrabold text-white mb-1">Pembayaran Berhasil!</h1>
                        <p class="text-green-100 text-sm">E-Ticket sedang dikirim ke email & WhatsApp kamu</p>
                    </div>
                </div>

                {{-- Details --}}
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-[#F8FAFF] rounded-xl p-3">
                            <p class="text-xs text-[#94A3B8] mb-0.5">Order ID</p>
                            <p class="font-bold text-[#0B1040] text-sm font-mono">{{ $order_code }}</p>
                        </div>
                        <div class="bg-[#F8FAFF] rounded-xl p-3">
                            <p class="text-xs text-[#94A3B8] mb-0.5">Total Dibayar</p>
                            <p class="font-bold text-[#10B981] text-sm">Rp{{ number_format($total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-[#F8FAFF] rounded-xl p-3">
                            <p class="text-xs text-[#94A3B8] mb-0.5">Lomba</p>
                            <p class="font-bold text-[#0B1040] text-sm truncate">{{ $comp_name }}</p>
                        </div>
                        <div class="bg-[#F8FAFF] rounded-xl p-3">
                            <p class="text-xs text-[#94A3B8] mb-0.5">Peserta</p>
                            <p class="font-bold text-[#0B1040] text-sm">{{ $participants }} orang</p>
                        </div>
                    </div>

                    {{-- Email notice --}}
                    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <svg class="w-5 h-5 text-[#2563EB] flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-[#1D4ED8]">E-Ticket sudah dikirim!</p>
                            <p class="text-xs text-[#475569] mt-0.5">Periksa inbox atau folder spam email kamu. E-Ticket juga dikirim via WhatsApp.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ticket list --}}
            @if($tickets->count() > 0)
            <div class="space-y-3 mb-6">
                <p class="text-xs font-bold text-[#94A3B8] uppercase tracking-widest">E-Ticket Kamu</p>
                @foreach($tickets as $ticket)
                <a href="{{ route('ticket.show', $ticket->ticket_code) }}" class="card p-4 flex items-center gap-4 hover:border-[#2563EB] transition-colors group">
                    <div class="w-10 h-10 rounded-xl bg-[#EFF6FF] flex items-center justify-center flex-shrink-0 group-hover:bg-[#2563EB] transition-colors">
                        <svg class="w-5 h-5 text-[#2563EB] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-[#0B1040] text-sm truncate">{{ $ticket->participant->name ?? 'Peserta' }}</p>
                        <p class="font-mono text-xs text-[#2563EB]">{{ $ticket->ticket_code }}</p>
                    </div>
                    <svg class="w-4 h-4 text-[#CBD5E1] group-hover:text-[#2563EB] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>
            @else
            {{-- Demo ticket --}}
            <div class="space-y-3 mb-6">
                <p class="text-xs font-bold text-[#94A3B8] uppercase tracking-widest">E-Ticket Kamu</p>
                <a href="{{ route('ticket.show', 'BRV-TKT-DEMO01') }}" class="card p-4 flex items-center gap-4 hover:border-[#2563EB] transition-colors group">
                    <div class="w-10 h-10 rounded-xl bg-[#EFF6FF] flex items-center justify-center flex-shrink-0 group-hover:bg-[#2563EB] transition-colors">
                        <svg class="w-5 h-5 text-[#2563EB] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-[#0B1040] text-sm">Wahyu Perwira</p>
                        <p class="font-mono text-xs text-[#2563EB]">BRV-TKT-A8F92K</p>
                    </div>
                    <svg class="w-4 h-4 text-[#CBD5E1] group-hover:text-[#2563EB] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3">
                @if($tickets->count() > 0)
                    <a href="{{ route('ticket.show', $tickets->first()->ticket_code) }}" class="btn-primary btn-lg flex-1 justify-center">
                        Lihat E-Ticket
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('ticket.show', 'BRV-TKT-DEMO01') }}" class="btn-primary btn-lg flex-1 justify-center">
                        Lihat E-Ticket
                    </a>
                @endif
                <a href="{{ route('competitions.index') }}" class="btn-secondary btn-lg flex-1 justify-center">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
