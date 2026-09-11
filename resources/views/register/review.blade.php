@extends('layouts.app')

@section('title', 'Review Pendaftaran')

@section('content')

@php
$reg = $registration ?? null;
$comp = $reg->competition ?? (object)[
    'name' => 'Basket Competition', 'event_name' => 'GEN FEST 2026',
    'price' => 50000, 'location' => 'Batam',
    'event_date' => '2026-09-20', 'category' => 'Olahraga',
    'slug' => 'basket-competition',
];
$participants = $reg ? $reg->participants : collect([
    (object)['name' => 'Wahyu Perwira', 'date_of_birth' => '2005-03-15'],
    (object)['name' => 'Budi Santoso',  'date_of_birth' => '2004-07-22'],
]);
$total_participants = $reg ? $reg->total_participants : 2;
$total_amount = $reg ? $reg->total_amount : 100000;
$order_code = $reg ? $reg->order_code : 'BRV-20260911-0001';
$email = $reg ? $reg->email : 'wahyu@email.com';
$phone = $reg ? $reg->phone : '+6281234567890';
@endphp

<div class="relative bg-gradient-to-b from-[#EFF6FF] to-white py-10">
    <div class="absolute inset-0 batik-dots pointer-events-none opacity-60"></div>
    <div class="section-container relative z-10">

        <div class="mb-8">
            <x-step-indicator :current="2"/>
        </div>

        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-extrabold text-[#0B1040] mb-2 text-center">Review Pendaftaran</h1>
            <p class="text-[#64748B] text-sm text-center mb-8">Periksa kembali data sebelum melanjutkan ke pembayaran.</p>

            {{-- Order card --}}
            <div class="card mb-6 overflow-hidden">
                {{-- Header --}}
                <div class="bg-[#0B1040] relative overflow-hidden px-6 py-5">
                    <div class="absolute inset-0 batik-kawung opacity-15"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-blue-200/60 text-xs font-semibold uppercase tracking-widest mb-1">Order ID</p>
                            <p class="text-white font-extrabold text-lg font-mono">{{ $order_code }}</p>
                        </div>
                        <span class="badge badge-pending">Menunggu Pembayaran</span>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Event --}}
                    <div>
                        <p class="text-xs font-bold text-[#94A3B8] uppercase tracking-widest mb-2">Event</p>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#EFF6FF] flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                @if($comp->event_name ?? null)
                                <p class="text-xs text-[#94A3B8] font-medium">{{ $comp->event_name }}</p>
                                @endif
                                <p class="font-bold text-[#0B1040]">{{ $comp->name }}</p>
                                <p class="text-sm text-[#64748B]">
                                    {{ $comp->event_date ? \Carbon\Carbon::parse($comp->event_date)->translatedFormat('d F Y') : 'TBA' }}
                                    &bull; {{ $comp->location ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-[#E2E8F7]"></div>

                    {{-- Contact --}}
                    <div>
                        <p class="text-xs font-bold text-[#94A3B8] uppercase tracking-widest mb-3">Kontak</p>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div class="bg-[#F8FAFF] rounded-xl p-3">
                                <p class="text-xs text-[#94A3B8] mb-0.5">Email</p>
                                <p class="font-semibold text-[#0B1040] text-sm">{{ $email }}</p>
                            </div>
                            <div class="bg-[#F8FAFF] rounded-xl p-3">
                                <p class="text-xs text-[#94A3B8] mb-0.5">WhatsApp</p>
                                <p class="font-semibold text-[#0B1040] text-sm">{{ $phone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-[#E2E8F7]"></div>

                    {{-- Participants --}}
                    <div>
                        <p class="text-xs font-bold text-[#94A3B8] uppercase tracking-widest mb-3">
                            Peserta ({{ $total_participants }} orang)
                        </p>
                        <div class="space-y-2">
                            @foreach($participants as $i => $p)
                            <div class="flex items-center gap-3 bg-[#F8FAFF] rounded-xl p-3">
                                <div class="w-8 h-8 rounded-full bg-[#DBEAFE] flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-[#1D4ED8]">{{ $i + 1 }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-[#0B1040] text-sm">{{ $p->name }}</p>
                                    <p class="text-xs text-[#94A3B8]">
                                        {{ $p->date_of_birth ? \Carbon\Carbon::parse($p->date_of_birth)->translatedFormat('d F Y') : '' }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-[#E2E8F7]"></div>

                    {{-- Pricing --}}
                    <div>
                        <p class="text-xs font-bold text-[#94A3B8] uppercase tracking-widest mb-3">Rincian Biaya</p>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-[#64748B]">{{ $comp->name }}</span>
                                <span class="text-[#0B1040]">Rp{{ number_format($comp->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-[#64748B]">× {{ $total_participants }} peserta</span>
                                <span class="text-[#0B1040]">Rp{{ number_format($total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-[#E2E8F7]">
                            <span class="font-bold text-[#0B1040]">Total</span>
                            <span class="text-2xl font-extrabold text-[#2563EB]">
                                Rp{{ number_format($total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('register.form', $comp->slug) }}"
                   class="btn-secondary btn-lg flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Edit Data
                </a>
                <form action="{{ route('payment.create', $order_code) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="btn-primary btn-lg w-full justify-center">
                        Lanjutkan Pembayaran
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
