@extends('layouts.app')

@section('title', 'BravePay')
@section('meta_description', 'Platform registrasi event dan pembayaran digital Indonesia. Daftar lomba dengan mudah, bayar QRIS, dapatkan e-ticket instan.')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden hero-gradient min-h-[90vh] flex items-center">

    {{-- Batik background layers --}}
    <div class="absolute inset-0 batik-kawung pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-white/60 to-transparent pointer-events-none"></div>

    {{-- Decorative circles --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-[#BFDBFE]/40 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-[#93C5FD]/30 blur-3xl pointer-events-none"></div>

    {{-- Batik ornament shapes --}}
    <div class="absolute top-12 right-8 hidden xl:block pointer-events-none" aria-hidden="true">
        <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="opacity-10">
            <polygon points="100,10 190,55 190,145 100,190 10,145 10,55" stroke="#1E40AF" stroke-width="2" fill="none"/>
            <polygon points="100,30 170,67 170,133 100,170 30,133 30,67" stroke="#1E40AF" stroke-width="1.5" fill="none"/>
            <polygon points="100,50 150,79 150,121 100,150 50,121 50,79" stroke="#1E40AF" stroke-width="1" fill="none"/>
            <circle cx="100" cy="100" r="20" stroke="#1E40AF" stroke-width="1.5" fill="none"/>
            <circle cx="100" cy="100" r="8" fill="#1E40AF" fill-opacity="0.2"/>
        </svg>
    </div>
    <div class="absolute bottom-16 left-8 hidden xl:block pointer-events-none" aria-hidden="true">
        <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="opacity-10">
            <path d="M60 10 L110 37 L110 83 L60 110 L10 83 L10 37 Z" stroke="#1E40AF" stroke-width="2" fill="none"/>
            <circle cx="60" cy="60" r="15" stroke="#1E40AF" stroke-width="1.5" fill="none"/>
            <circle cx="60" cy="60" r="5" fill="#1E40AF" fill-opacity="0.3"/>
        </svg>
    </div>

    <div class="section-container relative z-10 py-20 lg:py-28">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- Left: text --}}
            <div class="text-center lg:text-left">
                {{-- Tagline pill --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#DBEAFE] border border-[#BFDBFE] mb-6 fade-up">
                    <span class="w-2 h-2 rounded-full bg-[#2563EB] animate-pulse-soft"></span>
                    <span class="text-[#1D4ED8] text-sm font-semibold">Platform Event Indonesia #1</span>
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-[#0B1040] leading-[1.05] mb-6 fade-up" style="transition-delay: 0.1s">
                    Daftar Event.<br>
                    <span class="text-gradient-blue">Bayar.</span><br>
                    Dapatkan<br class="sm:hidden"> E-Ticket.
                </h1>

                {{-- Sub --}}
                <p class="text-[#475569] text-lg sm:text-xl leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0 fade-up" style="transition-delay: 0.2s">
                    Registrasi lomba dan event tanpa login. Cukup isi data, bayar QRIS, dan e-ticket langsung ke WhatsApp & Email kamu.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4 justify-center lg:justify-start mb-10 fade-up" style="transition-delay: 0.3s">
                    <a href="{{ route('competitions.index') }}" class="btn-primary btn-lg">
                        Lihat Event Sekarang
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="#how-it-works" class="btn-secondary btn-lg">
                        Cara Kerja
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="flex flex-wrap items-center gap-6 justify-center lg:justify-start fade-up" style="transition-delay: 0.4s">
                    <div class="flex items-center gap-2 text-[#475569] text-sm">
                        <svg class="w-5 h-5 text-[#10B981]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="font-medium">Pembayaran Aman</span>
                    </div>
                    <div class="flex items-center gap-2 text-[#475569] text-sm">
                        <svg class="w-5 h-5 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="font-medium">E-Ticket Instan</span>
                    </div>
                    <div class="flex items-center gap-2 text-[#475569] text-sm">
                        <svg class="w-5 h-5 text-[#F59E0B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2v-1a2 2 0 00-2-2H8a2 2 0 00-2 2v1a2 2 0 002 2zM12 3v2m0 14v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M3 12H1m22 0h-2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                        </svg>
                        <span class="font-medium">Tanpa Login</span>
                    </div>
                </div>
            </div>

            {{-- Right: visual card --}}
            <div class="relative flex justify-center lg:justify-end fade-up" style="transition-delay: 0.25s">
                {{-- Main visual card --}}
                <div class="relative w-full max-w-[400px]">
                    {{-- Floating card: event ticket mockup --}}
                    <div class="card p-0 overflow-hidden shadow-xl">
                        {{-- Ticket header --}}
                        <div class="bg-[#0B1040] relative overflow-hidden px-6 pt-6 pb-8">
                            <div class="absolute inset-0 batik-kawung opacity-20"></div>
                            <div class="relative flex items-center justify-between mb-5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                                        </svg>
                                    </div>
                                    <span class="text-white font-bold text-sm">BravePay</span>
                                </div>
                                <span class="badge badge-success text-xs">✓ Lunas</span>
                            </div>
                            <h3 class="relative text-white font-extrabold text-xl leading-tight mb-1">GEN FEST 2026</h3>
                            <p class="relative text-blue-200/70 text-sm">Basket Competition</p>
                        </div>

                        {{-- Tear edge --}}
                        <div class="relative bg-white h-0 flex items-center">
                            <div class="absolute -left-3 w-6 h-6 rounded-full bg-[#F0F4FF] border border-[#E2E8F7] z-10"></div>
                            <div class="flex-1 border-t-2 border-dashed border-[#E2E8F7] mx-3"></div>
                            <div class="absolute -right-3 w-6 h-6 rounded-full bg-[#F0F4FF] border border-[#E2E8F7] z-10"></div>
                        </div>

                        {{-- Ticket body --}}
                        <div class="bg-white px-6 pt-5 pb-6">
                            <div class="flex gap-4 mb-5">
                                <div class="flex-1">
                                    <p class="text-xs text-[#94A3B8] font-medium mb-1">PESERTA</p>
                                    <p class="text-[#0B1040] font-bold text-sm">Wahyu Perwira</p>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-[#94A3B8] font-medium mb-1">KATEGORI</p>
                                    <p class="text-[#0B1040] font-bold text-sm">Pelajar</p>
                                </div>
                            </div>
                            <div class="flex gap-4 mb-5">
                                <div class="flex-1">
                                    <p class="text-xs text-[#94A3B8] font-medium mb-1">TANGGAL</p>
                                    <p class="text-[#0B1040] font-semibold text-sm">20 Sep 2026</p>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-[#94A3B8] font-medium mb-1">LOKASI</p>
                                    <p class="text-[#0B1040] font-semibold text-sm">Batam</p>
                                </div>
                            </div>
                            {{-- QR placeholder --}}
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-xl bg-[#F1F5FE] border-2 border-dashed border-[#BFDBFE] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-10 h-10 text-[#93C5FD]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 4v4m0-4h.01M4 4h4m12 4V4m0 0H8"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-[#94A3B8] font-medium mb-1">TICKET CODE</p>
                                    <p class="text-[#1D4ED8] font-bold font-mono text-sm">BRV-TKT-A8F92K</p>
                                    <p class="text-xs text-[#94A3B8] mt-1">Scan untuk check-in</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating badge: payment --}}
                    <div class="absolute -top-4 -left-4 bg-white rounded-2xl shadow-lg border border-[#E2E8F7] px-4 py-3 flex items-center gap-3 animate-float">
                        <div class="w-9 h-9 rounded-xl bg-[#DBEAFE] flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-[#64748B]">Pembayaran</p>
                            <p class="text-sm font-bold text-[#10B981]">Berhasil!</p>
                        </div>
                    </div>

                    {{-- Floating badge: QRIS --}}
                    <div class="absolute -bottom-3 -right-4 bg-white rounded-2xl shadow-lg border border-[#E2E8F7] px-4 py-3 flex items-center gap-3" style="animation: float 4s ease-in-out infinite; animation-delay: 1.5s;">
                        <div class="w-9 h-9 rounded-xl bg-[#EFF6FF] flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#1D4ED8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 4v4m0-4h.01M4 4h4m12 4V4m0 0H8"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-[#64748B]">Metode</p>
                            <p class="text-sm font-bold text-[#0B1040]">QRIS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave --}}
    <div class="absolute bottom-0 left-0 right-0 pointer-events-none">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-12 sm:h-14">
            <path d="M0 60L60 50C120 40 240 20 360 15C480 10 600 20 720 25C840 30 960 30 1080 25C1200 20 1320 10 1380 5L1440 0V60H0Z" fill="white"/>
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FEATURED EVENTS
═══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-white" id="featured-events">
    <div class="section-container">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div class="fade-up">
                <p class="text-sm font-bold text-[#2563EB] uppercase tracking-widest mb-2">Event Terkini</p>
                <h2 class="section-heading">Lomba & Event Pilihan</h2>
            </div>
            <a href="{{ route('competitions.index') }}" class="btn-secondary btn-sm flex-shrink-0 fade-up">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featured_competitions ?? [] as $c)
                <div class="fade-up" style="transition-delay: {{ $loop->index * 0.08 }}s">
                    <x-competition-card
                        :id="$c->id"
                        :slug="$c->slug"
                        :name="$c->name"
                        :category="$c->category"
                        :date="$c->event_date ? \Carbon\Carbon::parse($c->event_date)->translatedFormat('d F Y') : 'TBA'"
                        :location="$c->location"
                        :price="$c->price"
                        :quota="$c->quota ?? 0"
                        :registered="$c->registrations_count ?? 0"
                        :status="$c->status"
                        :unit="$c->unit ?? 'peserta'"
                        :poster="$c->poster ? asset('storage/'.$c->poster) : null"
                    />
                </div>
            @empty
                {{-- Demo cards when no DB data --}}
                @foreach([
                    ['name'=>'Basket Competition','cat'=>'Olahraga','date'=>'20 Sep 2026','loc'=>'Batam','price'=>50000,'status'=>'open','event'=>'GEN FEST 2026'],
                    ['name'=>'Futsal Championship','cat'=>'Olahraga','date'=>'5 Okt 2026','loc'=>'Batam','price'=>75000,'status'=>'open','event'=>'YOUTH SPORT FEST'],
                    ['name'=>'Desain Grafis','cat'=>'Kreatif','date'=>'12 Okt 2026','loc'=>'Online','price'=>35000,'status'=>'coming_soon','event'=>'CREATIVE FEST 2026'],
                ] as $i => $demo)
                <div class="fade-up" style="transition-delay: {{ $i * 0.1 }}s">
                    <x-competition-card
                        :name="$demo['name']"
                        :category="$demo['cat']"
                        :date="$demo['date']"
                        :location="$demo['loc']"
                        :price="$demo['price']"
                        :quota="100"
                        :registered="rand(20,80)"
                        :status="$demo['status']"
                        :event_name="$demo['event']"
                    />
                </div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════════════════════ --}}
<section class="py-20 relative overflow-hidden bg-[#F0F4FF]" id="how-it-works">
    <div class="absolute inset-0 batik-parang pointer-events-none"></div>
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#93C5FD]/60 to-transparent pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#93C5FD]/60 to-transparent pointer-events-none"></div>

    <div class="section-container relative z-10">
        <div class="text-center mb-14 fade-up">
            <p class="text-sm font-bold text-[#2563EB] uppercase tracking-widest mb-2">Mudah & Cepat</p>
            <h2 class="section-heading mb-3">Cara Kerja BravePay</h2>
            <p class="section-subheading mx-auto">Proses pendaftaran dan pembayaran yang simpel, hanya butuh beberapa menit.</p>
        </div>

        {{-- Steps --}}
        <div class="relative">
            {{-- Connector line (desktop) --}}
            <div class="hidden lg:block absolute top-11 left-[calc(10%+3.5rem)] right-[calc(10%+3.5rem)] h-0.5 bg-gradient-to-r from-[#BFDBFE] via-[#93C5FD] to-[#BFDBFE]"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-4">
                @foreach([
                    ['num'=>'01','icon'=>'M4 6h16M4 10h16M4 14h8','label'=>'Pilih Lomba','desc'=>'Browse dan pilih event atau lomba yang ingin kamu ikuti'],
                    ['num'=>'02','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','label'=>'Isi Data','desc'=>'Lengkapi data diri dan informasi peserta yang akan didaftarkan'],
                    ['num'=>'03','icon'=>'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 4v4m0-4h.01M4 4h4m12 4V4m0 0H8','label'=>'Bayar QRIS','desc'=>'Scan QRIS melalui aplikasi banking atau e-wallet manapun'],
                    ['num'=>'04','icon'=>'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z','label'=>'Dapat E-Ticket','desc'=>'E-Ticket dikirim otomatis ke email dan WhatsApp kamu'],
                    ['num'=>'05','icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','label'=>'Check-In','desc'=>'Tunjukkan QR Code saat event untuk verifikasi kehadiran'],
                ] as $i => $step)
                <div class="flex flex-col items-center text-center fade-up" style="transition-delay: {{ $i * 0.1 }}s">
                    {{-- Number + icon --}}
                    <div class="relative mb-5">
                        <div class="w-20 h-20 rounded-2xl bg-white shadow-md border border-[#E2E8F7] flex items-center justify-center group hover:bg-[#2563EB] hover:shadow-lg transition-all duration-300">
                            <svg class="w-8 h-8 text-[#2563EB] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-[#0B1040] text-white text-xs font-extrabold flex items-center justify-center">{{ $step['num'] }}</span>
                    </div>
                    <h3 class="font-bold text-[#0B1040] text-base mb-2">{{ $step['label'] }}</h3>
                    <p class="text-[#64748B] text-sm leading-relaxed max-w-[160px]">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center mt-14 fade-up">
            <a href="{{ route('competitions.index') }}" class="btn-primary btn-lg">
                Mulai Sekarang — Gratis
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     WHY BRAVEPAY
═══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-white" id="why-bravepay">
    <div class="section-container">
        <div class="text-center mb-14 fade-up">
            <p class="text-sm font-bold text-[#2563EB] uppercase tracking-widest mb-2">Keunggulan</p>
            <h2 class="section-heading mb-3">Kenapa BravePay?</h2>
            <p class="section-subheading mx-auto">Dirancang khusus untuk event dan lomba Indonesia.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z','title'=>'Tanpa Akun','desc'=>'Peserta tidak perlu membuat akun. Langsung daftar, bayar, dan dapat tiket.','color'=>'bg-blue-50 text-blue-600'],
                ['icon'=>'M13 10V3L4 14h7v7l9-11h-7z','title'=>'E-Ticket Instan','desc'=>'Setelah pembayaran dikonfirmasi, e-ticket langsung dikirim ke email dan WhatsApp.','color'=>'bg-indigo-50 text-indigo-600'],
                ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','title'=>'Pembayaran Aman','desc'=>'QRIS terverifikasi melalui payment gateway. Status hanya berubah setelah konfirmasi webhook.','color'=>'bg-green-50 text-green-600'],
                ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','title'=>'Multi Peserta','desc'=>'Daftarkan satu atau banyak peserta sekaligus dalam satu transaksi.','color'=>'bg-sky-50 text-sky-600'],
                ['icon'=>'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 4v4m0-4h.01M4 4h4m12 4V4m0 0H8','title'=>'QR Check-in','desc'=>'Panitia cukup scan QR Code peserta untuk verifikasi kehadiran secara real-time.','color'=>'bg-purple-50 text-purple-600'],
                ['icon'=>'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Bantuan 24/7','desc'=>'Lupa e-ticket? Kirim ulang via WhatsApp kapan saja melalui tombol bantuan.','color'=>'bg-amber-50 text-amber-600'],
            ] as $i => $feat)
            <div class="card p-6 fade-up" style="transition-delay: {{ $i * 0.08 }}s">
                <div class="w-12 h-12 rounded-xl {{ $feat['color'] }} flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feat['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="font-bold text-[#0B1040] text-base mb-2">{{ $feat['title'] }}</h3>
                <p class="text-[#64748B] text-sm leading-relaxed">{{ $feat['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     EVENT CATEGORIES
═══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-[#F0F4FF] relative overflow-hidden" id="categories">
    <div class="absolute inset-0 batik-dots pointer-events-none"></div>

    <div class="section-container relative z-10">
        <div class="text-center mb-12 fade-up">
            <p class="text-sm font-bold text-[#2563EB] uppercase tracking-widest mb-2">Kategori</p>
            <h2 class="section-heading mb-3">Semua Jenis Event</h2>
            <p class="section-subheading mx-auto">BravePay mendukung berbagai jenis event dan lomba.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                ['emoji'=>'🏀','label'=>'Olahraga','count'=>'12 Event'],
                ['emoji'=>'🎨','label'=>'Kreatif','count'=>'8 Event'],
                ['emoji'=>'🎵','label'=>'Seni & Musik','count'=>'5 Event'],
                ['emoji'=>'🏆','label'=>'Akademik','count'=>'9 Event'],
                ['emoji'=>'💻','label'=>'Teknologi','count'=>'6 Event'],
                ['emoji'=>'📸','label'=>'Fotografi','count'=>'4 Event'],
                ['emoji'=>'🎭','label'=>'Teater & Drama','count'=>'3 Event'],
                ['emoji'=>'🤝','label'=>'Komunitas','count'=>'7 Event'],
            ] as $i => $cat)
            <a href="{{ route('competitions.index', ['category' => strtolower($cat['label'])]) }}"
               class="card p-5 text-center hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white group transition-all duration-200 fade-up"
               style="transition-delay: {{ $i * 0.06 }}s">
                <div class="text-3xl mb-3">{{ $cat['emoji'] }}</div>
                <p class="font-bold text-[#0B1040] text-sm group-hover:text-white transition-colors">{{ $cat['label'] }}</p>
                <p class="text-xs text-[#94A3B8] group-hover:text-blue-100 transition-colors mt-0.5">{{ $cat['count'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FAQ
═══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-white" id="faq">
    <div class="section-container">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-12 fade-up">
                <p class="text-sm font-bold text-[#2563EB] uppercase tracking-widest mb-2">FAQ</p>
                <h2 class="section-heading mb-3">Pertanyaan Umum</h2>
                <p class="section-subheading mx-auto">Jawaban untuk pertanyaan yang sering ditanyakan.</p>
            </div>

            <div x-data="faq()" class="space-y-3 fade-up">
                @foreach([
                    ['q'=>'Apakah saya perlu membuat akun untuk mendaftar?','a'=>'Tidak perlu. BravePay dirancang tanpa login untuk peserta. Cukup isi data diri, bayar, dan e-ticket langsung kamu terima.'],
                    ['q'=>'Berapa lama e-ticket dikirim setelah pembayaran?','a'=>'E-ticket dikirim otomatis dalam hitungan menit setelah pembayaran dikonfirmasi oleh sistem payment gateway kami.'],
                    ['q'=>'Metode pembayaran apa saja yang tersedia?','a'=>'Saat ini kami mendukung QRIS yang dapat di-scan menggunakan semua aplikasi banking dan e-wallet (GoPay, OVO, DANA, ShopeePay, dll).'],
                    ['q'=>'Bagaimana jika saya tidak menerima e-ticket?','a'=>'Gunakan tombol bantuan (?) di pojok kanan bawah, pilih "Kirim Ulang E-Ticket", dan masukkan nomor WhatsApp yang digunakan saat pendaftaran.'],
                    ['q'=>'Bisakah mendaftarkan lebih dari satu peserta?','a'=>'Bisa! Kamu dapat mendaftarkan beberapa peserta dalam satu transaksi. Setiap peserta akan mendapatkan e-ticket dan QR code unik masing-masing.'],
                    ['q'=>'Apakah ada biaya tambahan di luar biaya pendaftaran?','a'=>'Tidak ada biaya tersembunyi. Harga yang tertera sudah termasuk biaya administrasi. Kamu hanya membayar sesuai yang ditampilkan.'],
                ] as $i => $item)
                <div class="card overflow-hidden">
                    <button
                        @click="toggle({{ $i }})"
                        class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left"
                        :aria-expanded="active === {{ $i }}"
                    >
                        <span class="font-semibold text-[#0B1040] text-sm leading-snug">{{ $item['q'] }}</span>
                        <svg class="w-5 h-5 text-[#2563EB] flex-shrink-0 transition-transform duration-200"
                             :class="active === {{ $i }} ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div
                        x-show="active === {{ $i }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-cloak
                    >
                        <div class="px-5 pb-5 pt-0 text-[#64748B] text-sm leading-relaxed border-t border-[#E2E8F7]">
                            <div class="pt-4">{{ $item['a'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CTA SECTION
═══════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden navy-gradient py-20">
    <div class="absolute inset-0 batik-geo opacity-20 pointer-events-none"></div>
    {{-- Glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="section-container relative z-10 text-center">
        {{-- Batik ornament line --}}
        <div class="flex justify-center gap-1 mb-8 fade-up">
            @foreach(['bg-navy-700','bg-blue-700','bg-blue-500','bg-blue-400','bg-blue-500','bg-blue-700','bg-navy-700'] as $c)
            <div class="w-8 h-1 rounded-full {{ $c }} opacity-70"></div>
            @endforeach
        </div>

        <h2 class="text-4xl sm:text-5xl font-extrabold text-white mb-4 fade-up">
            Siap Bergabung?
        </h2>
        <p class="text-blue-200/80 text-lg mb-8 max-w-md mx-auto fade-up" style="transition-delay:0.1s">
            Ratusan peserta sudah menggunakan BravePay. Giliran kamu!
        </p>
        <div class="flex flex-wrap justify-center gap-4 fade-up" style="transition-delay:0.2s">
            <a href="{{ route('competitions.index') }}" class="btn-primary btn-lg bg-white text-[#1D4ED8] hover:bg-blue-50">
                Lihat Semua Event
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
            <a href="{{ route('home') }}#how-it-works" class="btn-secondary btn-lg border-white/30 text-white hover:bg-white/10 hover:border-white/50">
                Cara Kerja
            </a>
        </div>
    </div>
</section>

@endsection
