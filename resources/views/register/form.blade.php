@extends('layouts.app')

@section('title', 'Daftar — ' . ($competition->name ?? 'Basket Competition'))

@section('content')

@php
$comp = $competition ?? (object)[
    'id'   => 1, 'slug' => 'basket-competition',
    'name' => 'Basket Competition', 'event_name' => 'GEN FEST 2026',
    'price'=> 50000, 'location' => 'Harbour Bay, Jodoh River, Batu Ampar, Batam City, Riau Islands',
    'event_date' => '2026-09-20', 'category' => 'Olahraga',
    'unit' => 'peserta', 'min_purchase' => 1,
];
$unitLabel = ($comp->unit ?? 'peserta') === 'team' ? 'team' : 'peserta';
$minPurchase = max(1, (int) ($comp->min_purchase ?? 1));
$flashError = session('error');
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
                x-data="registrationForm({{ $comp->price }}, {{ $minPurchase }}, '{{ $unitLabel }}', @js($flashError ?? ''), @js($comp->name ?? ''))"
                x-on:submit="if (participantCount < minPurchase) { showMinError(); $event.preventDefault(); }"
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
                                :disabled="participantCount <= minPurchase"
                            >−</button>
                            <input
                                type="number" name="participant_count"
                                value="{{ $minPurchase }}"
                                x-model="participantCount"
                                @input="if ($event.target.value === '') { return; } if (Number($event.target.value) < minPurchase) { $event.target.value = minPurchase; showMinError(); } updateCount($event.target.value);"
                                @change="updateCount($event.target.value)"
                                :min="minPurchase"
                                class="w-14 h-11 text-center font-bold text-[#0B1040] border-x-2 border-[#E2E8F7] outline-none text-base"
                            >
                            <button
                                type="button"
                                @click="updateCount(participantCount + 1)"
                                class="w-11 h-11 flex items-center justify-center text-[#2563EB] hover:bg-[#EFF6FF] transition-colors font-bold text-lg"
                            >+</button>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0B1040]" x-text="participantCount + ' ' + '{{ ucfirst($unitLabel) }}'"></p>
                            <p class="text-xs text-[#94A3B8]">min. {{ $minPurchase }} • Minimal {{ $minPurchase }} {{ $unitLabel }} per transaksi.</p>
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
                            <span class="text-[#64748B]" x-text="'× ' + participantCount + ' {{ $unitLabel }}'"></span>
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

            <div
                x-show="minErrorOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
                @keydown.escape.window="closeMinError()"
            >
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        {{-- Icon: biru untuk info, amber untuk warning --}}
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                            :class="minErrorIsInfo ? 'bg-blue-100 text-blue-600' : 'bg-amber-100 text-amber-600'"
                        >
                            {{-- Info icon --}}
                            <svg x-show="minErrorIsInfo" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{-- Warning icon --}}
                            <svg x-show="!minErrorIsInfo" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 3h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p
                                class="text-sm font-semibold uppercase tracking-wide"
                                :class="minErrorIsInfo ? 'text-blue-600' : 'text-amber-600'"
                                x-text="minErrorIsInfo ? 'Perhatian' : 'Peringatan'"
                            ></p>
                            <h3 class="text-lg font-extrabold text-[#0B1040]"
                                x-text="minErrorIsInfo ? 'Minimum Pembelian Berlaku' : 'Jumlah minimum belum tercukupi'"
                            ></h3>
                        </div>
                    </div>

                    {{-- Info box showing admin-configured minimum --}}
                    <div
                        class="rounded-xl p-3 mb-4 flex items-center gap-3"
                        :class="minErrorIsInfo ? 'bg-blue-50 border border-blue-200' : 'bg-amber-50 border border-amber-200'"
                    >
                        <span class="text-2xl font-extrabold" :class="minErrorIsInfo ? 'text-blue-600' : 'text-amber-600'"
                              x-text="minPurchase"></span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Minimum per transaksi</p>
                            <p class="text-sm font-semibold text-slate-700" x-text="unitLabel === 'team' ? unitLabel + ' (termasuk seluruh anggota)' : unitLabel"></p>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 leading-relaxed" x-text="minErrorMessage"></p>

                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="closeMinError()"
                            class="btn-primary btn-sm"
                            :class="minErrorIsInfo ? '' : 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-300'"
                        >
                            <span x-text="minErrorIsInfo ? 'Mengerti, Lanjutkan' : 'Oke, Saya Perbaiki'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
