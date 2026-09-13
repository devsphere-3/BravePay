@extends('layouts.app')

@section('title', 'Daftar — ' . ($competition->name ?? 'Basket Competition'))

@section('content')

@php
$comp = $competition ?? (object)[
    'id'   => 1, 'slug' => 'basket-competition',
    'name' => 'Basket Competition', 'event_name' => 'GEN FEST 2026',
    'price'=> 50000, 'location' => 'Batam',
    'event_date' => '2026-09-20', 'category' => 'Olahraga',
    'unit' => 'peserta', 'min_purchase' => 1,
];
$unitLabel = ($comp->unit ?? 'peserta') === 'team' ? 'team' : 'peserta';
$minPurchase = max(1, (int) ($comp->min_purchase ?? 1));
@endphp

<div class="relative bg-gradient-to-b from-[#EFF6FF] to-white py-10">
    <div class="absolute inset-0 batik-dots pointer-events-none opacity-60"></div>
    <div class="section-container relative z-10">
        {{-- Step indicator --}}
        <div class="mb-8">
            <x-step-indicator :current="1"/>
        </div>

        <div class="max-w-2xl mx-auto">
            {{-- Event summary banner --}}
            <div class="card p-4 mb-6 flex items-center gap-4 bg-[#EFF6FF] border-[#BFDBFE]">
                <div class="w-12 h-12 rounded-xl bg-[#DBEAFE] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    @if($comp->event_name ?? null)
                    <p class="text-xs font-bold text-[#64748B] uppercase tracking-wide">{{ $comp->event_name }}</p>
                    @endif
                    <p class="font-bold text-[#0B1040] truncate">{{ $comp->name }}</p>
                    <p class="text-xs text-[#64748B]">
                        {{ $comp->event_date ? \Carbon\Carbon::parse($comp->event_date)->translatedFormat('d F Y') : 'TBA' }}
                        &bull; {{ $comp->location ?? '' }}
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-[#94A3B8]">per {{ $unitLabel }}</p>
                    <p class="font-extrabold text-[#0B1040]">Rp{{ number_format($comp->price, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Form --}}
            <form
                action="{{ route('register.store', $comp->slug) }}"
                method="POST"
                x-data="registrationForm({{ $comp->price }}, {{ $minPurchase }})"
                class="space-y-6"
            >
                @csrf

                {{-- Contact info --}}
                <div class="card p-6">
                    <h2 class="font-bold text-[#0B1040] text-base mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2563EB] text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</span>
                        Informasi Kontak
                    </h2>
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="email">
                                Email <span class="text-red-500">*</span>
                                <span class="font-normal text-[#94A3B8] ml-1">(untuk e-ticket)</span>
                            </label>
                            <input
                                type="email" id="email" name="email"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                required
                                class="form-input @error('email') border-red-400 @enderror"
                            >
                            @error('email')
                            <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" for="phone">
                                WhatsApp / No. HP <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-[#64748B] font-medium">+62</span>
                                <input
                                    type="tel" id="phone" name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="8xxxxxxxxxx"
                                    required
                                    class="form-input pl-12 @error('phone') border-red-400 @enderror"
                                >
                            </div>
                            @error('phone')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-[#94A3B8] mt-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#10B981] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        E-ticket akan dikirim ke email dan WhatsApp ini.
                    </p>
                </div>

                {{-- Participant count --}}
                <div class="card p-6">
                    <h2 class="font-bold text-[#0B1040] text-base mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2563EB] text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
                        Jumlah Peserta
                    </h2>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center border-2 border-[#E2E8F7] rounded-xl overflow-hidden">
                            <button
                                type="button"
                                @click="updateCount(participantCount - 1)"
                                class="w-11 h-11 flex items-center justify-center text-[#2563EB] hover:bg-[#EFF6FF] transition-colors font-bold text-lg"
                                :disabled="participantCount <= 1"
                            >−</button>
                            <input
                                type="number" name="participant_count"
                                x-model="participantCount"
                                @change="updateCount($event.target.value)"
                                min="1" max="20"
                                class="w-14 h-11 text-center font-bold text-[#0B1040] border-x-2 border-[#E2E8F7] outline-none text-base"
                            >
                            <button
                                type="button"
                                @click="updateCount(participantCount + 1)"
                                class="w-11 h-11 flex items-center justify-center text-[#2563EB] hover:bg-[#EFF6FF] transition-colors font-bold text-lg"
                                :disabled="participantCount >= 20"
                            >+</button>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0B1040]" x-text="participantCount + ' ' + (participantCount > 1 ? '{{ ucfirst($unitLabel) }}' : '{{ ucfirst($unitLabel) }}')"></p>
                            <p class="text-xs text-[#94A3B8]">Minimal {{ $minPurchase }} {{ $unitLabel }} per transaksi. Maks. 20 per transaksi.</p>
                        </div>
                    </div>
                </div>

                {{-- Participant forms (dynamic) --}}
                <template x-for="(p, i) in participants" :key="i">
                    <div class="card p-6">
                        <h2 class="font-bold text-[#0B1040] text-base mb-5 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#0B1040] text-white text-xs font-bold flex items-center justify-center flex-shrink-0" x-text="i + 1"></span>
                            <span>Data Peserta <span x-text="'#' + (i + 1)"></span></span>
                        </h2>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label" :for="'name_' + i">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    :id="'name_' + i"
                                    :name="'participants[' + i + '][name]'"
                                    x-model="p.name"
                                    type="text"
                                    placeholder="Nama sesuai KTP/KTM"
                                    required
                                    class="form-input"
                                >
                            </div>
                            <div>
                                <label class="form-label" :for="'dob_' + i">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input
                                    :id="'dob_' + i"
                                    :name="'participants[' + i + '][date_of_birth]'"
                                    x-model="p.date_of_birth"
                                    type="date"
                                    required
                                    class="form-input"
                                >
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Order summary --}}
                <div class="card p-6 bg-[#F8FAFF]">
                    <h2 class="font-bold text-[#0B1040] text-sm mb-4 uppercase tracking-wide">Ringkasan</h2>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-[#64748B]">{{ $comp->name }}</span>
                            <span class="font-semibold text-[#0B1040]">Rp{{ number_format($comp->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-[#64748B]" x-text="'× ' + participantCount + ' peserta'"></span>
                            <span class="font-semibold text-[#0B1040]" x-text="formattedTotal"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-[#E2E8F7]">
                        <span class="font-bold text-[#0B1040]">Total Pembayaran</span>
                        <span class="text-xl font-extrabold text-[#2563EB]" x-text="formattedTotal"></span>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary btn-lg w-full justify-center">
                    Lanjutkan ke Review
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
