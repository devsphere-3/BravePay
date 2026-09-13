@extends('layouts.app')

@section('title', $competition->name ?? 'Basket Competition')
@section('meta_description', $competition->description ?? 'Detail lomba dan pendaftaran event.')

@section('content')

@php
$comp = $competition;
$registered_count = isset($comp->registrations_count) ? $comp->registrations_count : 0;
$registered_pct   = $comp->quota > 0 ? min(100, round(($registered_count / $comp->quota) * 100)) : 0;
$quota_left       = max(0, $comp->quota - $registered_count);
$competition_unit = $comp->unit ?? 'peserta';
$competition_unit_label = $competition_unit === 'team' ? 'team' : 'peserta';
$minPurchase      = max(1, (int) ($comp->min_purchase ?? 1));

// Parse schedule JSON jika tersimpan sebagai string
$schedule = [];
if (!empty($comp->schedule)) {
    $decoded = json_decode($comp->schedule, true);
    $schedule = is_array($decoded) ? $decoded : [];
}
@endphp

{{-- Page Header --}}
<div class="relative overflow-hidden bg-gradient-to-br from-[#EFF6FF] via-[#DBEAFE] to-[#EFF6FF] py-8">
    <div class="absolute inset-0 batik-kawung pointer-events-none"></div>
    <div class="section-container relative z-10">
        <div class="flex items-center gap-2 text-sm text-[#64748B]">
            <a href="{{ route('home') }}" class="hover:text-[#2563EB] transition-colors">Beranda</a>
            <svg class="w-4 h-4 text-[#CBD5E1]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('competitions.index') }}" class="hover:text-[#2563EB] transition-colors">Event & Lomba</a>
            <svg class="w-4 h-4 text-[#CBD5E1]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-[#1E3A8A] font-semibold truncate max-w-[200px]">{{ $comp->name }}</span>
        </div>
    </div>
</div>

<div class="section-container py-10">
    <div class="grid lg:grid-cols-3 gap-8 items-start">

        {{-- ── Left: Detail content ─── --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Poster + title --}}
            <div class="card overflow-hidden">
                {{-- Poster --}}
                <div class="relative aspect-[16/7] overflow-hidden bg-gradient-to-br from-[#DBEAFE] to-[#EFF6FF]">
                    @if($comp->poster)
                        <img src="{{ asset('storage/'.$comp->poster) }}" alt="{{ $comp->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 batik-geo"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <div class="w-20 h-20 rounded-3xl bg-white/80 shadow flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-[#1E3A8A] font-bold text-xl">{{ $comp->event_name ?? $comp->name }}</p>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4 flex gap-2">
                        <x-status-badge :status="$comp->status"/>
                        <span class="badge badge-info">{{ $comp->category }}</span>
                    </div>
                </div>

                {{-- Info row --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-0 border-t border-[#E2E8F7]">
                    @foreach([
                        ['icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','label'=>'Tanggal','value'=> $comp->event_date ? \Carbon\Carbon::parse($comp->event_date)->translatedFormat('d F Y') : 'TBA'],
                        ['icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z','label'=>'Lokasi','value'=>$comp->location ?? 'TBA'],
                        ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','label'=>'Sisa Kuota','value'=> $quota_left .' / '.($comp->quota ?? 0)],
                        ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Biaya','value'=>'Rp'.number_format($comp->price,0,',','.')],
                    ] as $info)
                    <div class="flex items-start gap-3 p-4 border-r last:border-r-0 border-[#E2E8F7]">
                        <svg class="w-5 h-5 text-[#2563EB] flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $info['icon'] }}"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="text-xs text-[#94A3B8] font-medium">{{ $info['label'] }}</p>
                            <p class="text-sm font-bold text-[#0B1040] truncate">{{ $info['value'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Description --}}
            <div class="card p-6">
                <h2 class="text-xl font-bold text-[#0B1040] mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full bg-[#2563EB] inline-block"></span>
                    Deskripsi Lomba
                </h2>
                <div class="prose prose-sm text-[#475569] leading-relaxed max-w-none">
                    {!! nl2br(e($comp->description)) !!}
                </div>
            </div>

            {{-- Rules --}}
            @if(!empty($comp->rules))
            <div class="card p-6">
                <h2 class="text-xl font-bold text-[#0B1040] mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full bg-[#2563EB] inline-block"></span>
                    Peraturan
                </h2>
                <div class="text-[#475569] text-sm leading-relaxed whitespace-pre-line">{{ $comp->rules }}</div>
            </div>
            @endif

            {{-- Requirements --}}
            @if(!empty($comp->requirements))
            <div class="card p-6">
                <h2 class="text-xl font-bold text-[#0B1040] mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full bg-[#2563EB] inline-block"></span>
                    Persyaratan
                </h2>
                <div class="text-[#475569] text-sm leading-relaxed whitespace-pre-line">{{ $comp->requirements }}</div>
            </div>
            @endif

            {{-- Schedule --}}
            @if(!empty($schedule))
            <div class="card p-6">
                <h2 class="text-xl font-bold text-[#0B1040] mb-5 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full bg-[#2563EB] inline-block"></span>
                    Jadwal Acara
                </h2>
                <div class="space-y-0 border border-[#E2E8F7] rounded-xl overflow-hidden">
                    @foreach($schedule as $i => $sched)
                    <div class="flex items-center gap-4 px-5 py-3.5 {{ $i % 2 === 0 ? 'bg-white' : 'bg-[#F8FAFF]' }} border-b border-[#E2E8F7] last:border-b-0">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-bold text-[#2563EB] font-mono">{{ is_array($sched) ? $sched['time'] : $sched->time }}</span>
                        </div>
                        <span class="text-sm text-[#475569] font-medium">{{ is_array($sched) ? $sched['event'] : $sched->event }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- FAQ --}}
            {{-- Kolom faq dapat ditambahkan ke tabel competitions di migrasi berikutnya --}}

        </div>

        {{-- ── Right: Sticky registration card ─── --}}
        <div class="lg:sticky lg:top-24">
            <div class="card p-6 space-y-5">

                {{-- Title --}}
                @if($comp->event_name ?? null)
                <p class="text-xs font-bold text-[#64748B] uppercase tracking-widest">{{ $comp->event_name }}</p>
                @endif
                <h1 class="text-xl font-extrabold text-[#0B1040] leading-tight">{{ $comp->name }}</h1>

                {{-- Status --}}
                <div class="flex items-center gap-2">
                    <x-status-badge :status="$comp->status"/>
                    <span class="badge badge-info">{{ $comp->category }}</span>
                </div>

                {{-- Price --}}
                <div class="py-4 border-y border-[#E2E8F7]">
                    <p class="text-xs text-[#94A3B8] font-medium mb-1">BIAYA PENDAFTARAN</p>
                    <p class="text-3xl font-extrabold text-[#0B1040]">
                        Rp{{ number_format($comp->price, 0, ',', '.') }}
                        <span class="text-sm font-medium text-[#94A3B8]">/ {{ $competition_unit_label }}</span>
                    </p>
                    @if($minPurchase > 1)
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="text-xs bg-[#EFF6FF] text-[#1D4ED8] border border-[#BFDBFE] rounded-full px-2.5 py-1 font-semibold">
                            min. {{ $minPurchase }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Info list --}}
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-[#2563EB] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-[#475569]">
                            {{ $comp->event_date ? \Carbon\Carbon::parse($comp->event_date)->translatedFormat('d F Y') : 'TBA' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-[#2563EB] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm text-[#475569]">{{ $comp->location ?? 'TBA' }}</span>
                    </div>
                </div>

                {{-- Quota bar --}}
                @if($comp->quota > 0)
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-[#64748B] font-medium">Kuota Pendaftaran</span>
                        <span class="font-bold text-[#0B1040]">{{ $quota_left }} tersisa</span>
                    </div>
                    <div class="w-full h-2 bg-[#E2E8F7] rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $registered_pct >= 90 ? 'bg-red-500' : ($registered_pct >= 70 ? 'bg-amber-500' : 'bg-[#2563EB]') }}"
                             style="width: {{ $registered_pct }}%"></div>
                    </div>
                    <p class="text-xs text-[#94A3B8] mt-1">{{ $comp->registrations_count }} dari {{ $comp->quota }} peserta terdaftar</p>
                </div>
                @endif

                {{-- CTA --}}
                @if($comp->status === 'open')
                    <a href="{{ route('register.form', $comp->slug) }}" class="btn-primary btn-lg w-full justify-center">
                        Daftar Sekarang
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @elseif($comp->status === 'coming_soon')
                    <button disabled class="btn-secondary btn-lg w-full justify-center opacity-60 cursor-not-allowed">
                        Pendaftaran Belum Dibuka
                    </button>
                @elseif($comp->status === 'full')
                    <button disabled class="w-full py-3 px-6 rounded-[10px] bg-red-50 border-2 border-red-200 text-red-600 font-semibold text-sm cursor-not-allowed">
                        Kuota Penuh
                    </button>
                @else
                    <button disabled class="btn-secondary btn-lg w-full justify-center opacity-60 cursor-not-allowed">
                        Pendaftaran Ditutup
                    </button>
                @endif

                {{-- Trust note --}}
                <div class="flex items-center gap-2 text-xs text-[#94A3B8] justify-center pt-1">
                    <svg class="w-4 h-4 text-[#10B981] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Pembayaran aman & terverifikasi
                </div>
            </div>

            {{-- Share --}}
            <div class="card p-4 mt-4">
                <p class="text-xs font-bold text-[#64748B] uppercase tracking-wide mb-3">Bagikan</p>
                <div class="flex gap-2">
                    <a href="https://wa.me/?text={{ urlencode($comp->name . ' - ' . request()->url()) }}" target="_blank"
                       class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-green-50 hover:bg-green-100 text-green-700 text-xs font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href).then(()=>alert('Link disalin!'))"
                            class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-[#EFF6FF] hover:bg-[#DBEAFE] text-[#1D4ED8] text-xs font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Salin Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sticky mobile CTA --}}
@if($comp->status === 'open')
<div class="fixed bottom-0 left-0 right-0 z-40 lg:hidden bg-white border-t border-[#E2E8F7] px-4 py-3 shadow-xl no-print">
    <div class="flex items-center gap-3">
        <div class="flex-1 min-w-0">
            <p class="text-xs text-[#64748B] truncate">{{ $comp->name }}</p>
            <p class="text-base font-extrabold text-[#0B1040]">Rp{{ number_format($comp->price,0,',','.') }}<span class="text-xs font-normal text-[#94A3B8]">/peserta</span></p>
        </div>
        <a href="{{ route('register.form', $comp->slug) }}" class="btn-primary btn-sm flex-shrink-0">
            Daftar Sekarang
        </a>
    </div>
</div>
<div class="h-20 lg:hidden"></div>
@endif

@endsection
