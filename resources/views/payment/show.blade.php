@extends('layouts.app')

@section('title', 'Pembayaran — ' . ($payment->registration->order_code ?? 'BRV-20260911-0001'))

@section('content')

@php
$reg = $registration ?? null;
$payment_obj = $payment ?? null;

$order_code   = $reg ? $reg->order_code : 'BRV-20260911-0001';
$amount       = $reg ? $reg->total_amount : 150000;
$comp_name    = $reg ? ($reg->competition->name ?? 'Basket Competition') : 'Basket Competition';
$participants = $reg ? $reg->total_participants : 3;
$status       = $payment_obj ? $payment_obj->status : 'pending'; // pending|paid|failed|expired
$expires_at   = $payment_obj && $payment_obj->expired_at ? $payment_obj->expired_at : now()->addMinutes(15)->toISOString();
$qris_url     = $payment_obj ? $payment_obj->qris_url ?? null : null;
@endphp

<div class="relative bg-gradient-to-b from-[#EFF6FF] to-white py-10">
    <div class="absolute inset-0 batik-dots pointer-events-none opacity-60"></div>
    <div class="section-container relative z-10">

        <div class="mb-8">
            <x-step-indicator :current="3"/>
        </div>

        <div class="max-w-lg mx-auto">

            {{-- ── PENDING state ── --}}
            @if($status === 'pending')
            <div x-data="paymentCountdown('{{ $expires_at }}')" x-init="init()" @destroy="destroy()">

                <div class="text-center mb-6">
                    <h1 class="text-2xl font-extrabold text-[#0B1040] mb-1">Selesaikan Pembayaran</h1>
                    <p class="text-[#64748B] text-sm">Scan QRIS sebelum waktu habis</p>
                </div>

                {{-- Timer --}}
                <div class="flex justify-center mb-6">
                    <div class="flex items-center gap-3 px-5 py-3 rounded-2xl"
                         :class="isUrgent ? 'bg-red-50 border-2 border-red-200' : 'bg-[#EFF6FF] border-2 border-[#BFDBFE]'">
                        <svg class="w-5 h-5 flex-shrink-0" :class="isUrgent ? 'text-red-500' : 'text-[#2563EB]'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-center">
                            <p class="text-xs font-semibold" :class="isUrgent ? 'text-red-500' : 'text-[#64748B]'">Sisa Waktu</p>
                            <p class="text-2xl font-extrabold font-mono" :class="isUrgent ? 'text-red-600' : 'text-[#0B1040]'" x-text="formattedTime"></p>
                        </div>
                    </div>
                </div>

                {{-- Payment card --}}
                <div class="card p-6 mb-6">
                    {{-- Order info --}}
                    <div class="flex justify-between items-start mb-5 pb-5 border-b border-[#E2E8F7]">
                        <div>
                            <p class="text-xs text-[#94A3B8] font-medium mb-1">ORDER ID</p>
                            <p class="font-extrabold text-[#0B1040] font-mono text-sm">{{ $order_code }}</p>
                            <p class="text-xs text-[#64748B] mt-0.5">{{ $comp_name }} &bull; {{ $participants }} peserta</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-[#94A3B8] font-medium mb-1">TOTAL</p>
                            <p class="font-extrabold text-[#2563EB] text-lg">Rp{{ number_format($amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- QRIS --}}
                    <div class="text-center">
                        <p class="text-sm font-semibold text-[#0B1040] mb-4">Scan QRIS untuk Membayar</p>

                        {{-- QRIS image / placeholder --}}
                        <div class="inline-flex flex-col items-center">
                            <div class="w-56 h-56 bg-white border-2 border-[#E2E8F7] rounded-2xl flex items-center justify-center p-3 mx-auto mb-3 shadow-sm">
                                @if($qris_url)
                                    <img src="{{ $qris_url }}" alt="QRIS" class="w-full h-full object-contain">
                                @else
                                    {{-- Placeholder QRIS pattern --}}
                                    <div class="w-full h-full relative">
                                        <svg viewBox="0 0 200 200" class="w-full h-full text-[#0B1040]" fill="currentColor">
                                            <!-- QR finder patterns -->
                                            <rect x="10" y="10" width="60" height="60" rx="4" fill="none" stroke="currentColor" stroke-width="8"/>
                                            <rect x="25" y="25" width="30" height="30" rx="2"/>
                                            <rect x="130" y="10" width="60" height="60" rx="4" fill="none" stroke="currentColor" stroke-width="8"/>
                                            <rect x="145" y="25" width="30" height="30" rx="2"/>
                                            <rect x="10" y="130" width="60" height="60" rx="4" fill="none" stroke="currentColor" stroke-width="8"/>
                                            <rect x="25" y="145" width="30" height="30" rx="2"/>
                                            <!-- Data modules -->
                                            <rect x="85" y="10" width="12" height="12" rx="1"/>
                                            <rect x="100" y="10" width="12" height="12" rx="1"/>
                                            <rect x="85" y="25" width="12" height="12" rx="1"/>
                                            <rect x="100" y="40" width="12" height="12" rx="1"/>
                                            <rect x="85" y="55" width="12" height="12" rx="1"/>
                                            <rect x="85" y="85" width="12" height="12" rx="1"/>
                                            <rect x="100" y="85" width="12" height="12" rx="1"/>
                                            <rect x="115" y="85" width="12" height="12" rx="1"/>
                                            <rect x="85" y="100" width="12" height="12" rx="1"/>
                                            <rect x="115" y="100" width="12" height="12" rx="1"/>
                                            <rect x="100" y="115" width="12" height="12" rx="1"/>
                                            <rect x="130" y="85" width="12" height="12" rx="1"/>
                                            <rect x="145" y="85" width="12" height="12" rx="1"/>
                                            <rect x="160" y="85" width="12" height="12" rx="1"/>
                                            <rect x="175" y="85" width="12" height="12" rx="1"/>
                                            <rect x="130" y="100" width="12" height="12" rx="1"/>
                                            <rect x="160" y="100" width="12" height="12" rx="1"/>
                                            <rect x="130" y="115" width="12" height="12" rx="1"/>
                                            <rect x="145" y="115" width="12" height="12" rx="1"/>
                                            <rect x="175" y="115" width="12" height="12" rx="1"/>
                                            <rect x="85" y="130" width="12" height="12" rx="1"/>
                                            <rect x="85" y="145" width="12" height="12" rx="1"/>
                                            <rect x="100" y="145" width="12" height="12" rx="1"/>
                                            <rect x="115" y="130" width="12" height="12" rx="1"/>
                                            <rect x="115" y="160" width="12" height="12" rx="1"/>
                                            <rect x="85" y="160" width="12" height="12" rx="1"/>
                                            <rect x="100" y="175" width="12" height="12" rx="1"/>
                                            <rect x="115" y="175" width="12" height="12" rx="1"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 px-4 py-1.5 bg-[#F1F5FE] rounded-full">
                                <span class="text-xs font-bold text-[#1D4ED8]">QRIS</span>
                                <span class="text-xs text-[#64748B]">— Semua E-Wallet & Bank</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Instructions --}}
                <div class="card p-5 mb-4">
                    <p class="text-sm font-bold text-[#0B1040] mb-4">Cara Membayar:</p>
                    <div class="space-y-3">
                        @foreach(['Buka aplikasi banking atau e-wallet (GoPay, OVO, DANA, ShopeePay, dll)','Pilih menu "Scan QR" atau "Pay"','Arahkan kamera ke QRIS di atas','Konfirmasi pembayaran sebesar Rp'.number_format($amount,0,',','.'),'Tunggu konfirmasi otomatis dari BravePay'] as $i => $step)
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#DBEAFE] text-[#1D4ED8] text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i+1 }}</span>
                            <p class="text-sm text-[#475569]">{{ $step }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Status check --}}
                <div class="text-center">
                    <button
                        onclick="window.location.reload()"
                        class="btn-secondary btn-sm gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh Status Pembayaran
                    </button>
                    <p class="text-xs text-[#94A3B8] mt-2">Status diperbarui otomatis setelah pembayaran dikonfirmasi</p>
                </div>
            </div>

            {{-- ── EXPIRED state ── --}}
            @elseif($status === 'expired')
            <div class="text-center py-8">
                <div class="w-20 h-20 rounded-3xl bg-slate-100 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#0B1040] mb-2">Waktu Pembayaran Habis</h2>
                <p class="text-[#64748B] text-sm mb-6 max-w-xs mx-auto">Sesi pembayaran untuk order <span class="font-mono font-bold">{{ $order_code }}</span> telah kadaluarsa.</p>
                <a href="{{ route('competitions.index') }}" class="btn-primary">Daftar Ulang</a>
            </div>

            {{-- ── FAILED state ── --}}
            @elseif($status === 'failed')
            <div class="text-center py-8">
                <div class="w-20 h-20 rounded-3xl bg-red-50 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#0B1040] mb-2">Pembayaran Gagal</h2>
                <p class="text-[#64748B] text-sm mb-6 max-w-xs mx-auto">Pembayaran untuk order <span class="font-mono font-bold">{{ $order_code }}</span> gagal diproses.</p>
                <a href="{{ route('competitions.index') }}" class="btn-primary">Coba Lagi</a>
            </div>

            @endif
        </div>
    </div>
</div>

@endsection
