@extends('layouts.admin')

@section('title', 'E-Ticket')
@section('page_title', 'Manajemen E-Ticket')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-[#0B1040]">Manajemen E-Ticket</h1>
        <p class="text-slate-500 text-sm mt-0.5">
            {{ $groups->count() }} grup order
            &bull; {{ $groups->sum('ticket_count') }} tiket total
        </p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.tickets.index') }}" class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_auto_auto_auto] gap-3">
        <input
            type="search" name="q" value="{{ $q ?? '' }}"
            placeholder="Cari order ID, email, nama peserta, lomba..."
            class="form-input text-sm min-w-0"
        >
        <select name="status" class="form-select text-sm w-full sm:w-auto min-w-0 sm:min-w-[140px]" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active"    {{ ($filterStatus ?? '') === 'active'    ? 'selected' : '' }}>Aktif</option>
            <option value="used"      {{ ($filterStatus ?? '') === 'used'      ? 'selected' : '' }}>Digunakan</option>
            <option value="cancelled" {{ ($filterStatus ?? '') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="btn-primary btn-sm">Filter</button>
        @if(($q ?? '') !== '' || ($filterStatus ?? '') !== '')
        <a href="{{ route('admin.tickets.index') }}" class="btn-secondary btn-sm">Reset</a>
        @endif
    </div>
</form>

{{-- Grouped ticket list --}}
<div class="space-y-4"
    x-data="{
        openGroup: null,
        qrModal: { open: false, code: '', participant: '' },
        toggleGroup(id) { this.openGroup = this.openGroup === id ? null : id; },
        openQr(code, participant) {
            this.qrModal = { open: true, code, participant };
        },
        closeQr() { this.qrModal.open = false; }
    }"
>
    @forelse($groups as $group)
    @php
        $gStatus = $group->status ?? 'pending';
        $gId     = $loop->index;
    @endphp

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Group header row --}}
        <div
            class="flex flex-wrap items-center gap-3 sm:gap-4 px-4 sm:px-5 py-4 cursor-pointer hover:bg-slate-50 transition-colors select-none"
            @click="toggleGroup({{ $gId }})"
        >
            {{-- Expand chevron --}}
            <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center transition-transform"
                 :class="openGroup === {{ $gId }} ? 'rotate-90' : ''">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>

            {{-- Order info --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-0.5">
                    <span class="font-mono text-sm font-bold text-[#0B1040]">{{ $group->order_code ?? '—' }}</span>
                    <x-status-badge :status="$gStatus"/>
                </div>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-slate-400">
                    <span class="truncate max-w-[180px]">{{ $group->comp_name }}</span>
                    <span class="hidden sm:inline">&bull;</span>
                    <span class="truncate max-w-[160px]">{{ $group->email }}</span>
                    @if($group->created_at)
                    <span class="hidden md:inline">&bull;</span>
                    <span class="hidden md:inline">{{ \Carbon\Carbon::parse($group->created_at)->format('d M Y H:i') }}</span>
                    @endif
                </div>
            </div>

            {{-- Ticket count badge --}}
            <div class="flex-shrink-0 flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 bg-[#EFF6FF] text-[#2563EB] border border-[#BFDBFE] rounded-xl px-3 py-1 text-xs font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    {{ $group->ticket_count }} tiket
                </span>

                {{-- Resend button (stop propagation so it doesn't toggle) --}}
                <form action="{{ route('admin.tickets.resend', $group->order_code ?? 0) }}" method="POST"
                      @click.stop>
                    @csrf
                    <button type="submit"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                            title="Kirim ulang semua tiket">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Expanded ticket rows --}}
        <div
            x-show="openGroup === {{ $gId }}"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="border-t border-slate-100"
        >
            <div class="divide-y divide-slate-100">
                @foreach($group->tickets as $idx => $t)
                    <div class="flex flex-wrap items-center gap-3 px-4 sm:px-5 py-3.5 hover:bg-slate-50/60 transition-colors">

                    {{-- Nomor urut --}}
                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-[10px] font-bold text-slate-500">{{ $idx + 1 }}</span>
                    </div>

                    {{-- QR code preview (klik untuk buka modal) --}}
                    <button
                        type="button"
                        class="flex-shrink-0 w-10 h-10 rounded-xl border-2 border-slate-200 bg-[#F8FAFF] flex items-center justify-center hover:border-[#2563EB] hover:bg-[#EFF6FF] transition-colors group"
                        title="Lihat QR Code"
                        @click="openQr('{{ $t->ticket_code }}', '{{ addslashes($t->participant) }}')"
                    >
                        <svg viewBox="0 0 100 100" class="w-7 h-7 text-slate-400 group-hover:text-[#2563EB] transition-colors" fill="currentColor">
                            <rect x="5" y="5" width="35" height="35" rx="3" fill="none" stroke="currentColor" stroke-width="7"/>
                            <rect x="14" y="14" width="17" height="17" rx="1"/>
                            <rect x="60" y="5" width="35" height="35" rx="3" fill="none" stroke="currentColor" stroke-width="7"/>
                            <rect x="69" y="14" width="17" height="17" rx="1"/>
                            <rect x="5" y="60" width="35" height="35" rx="3" fill="none" stroke="currentColor" stroke-width="7"/>
                            <rect x="14" y="69" width="17" height="17" rx="1"/>
                            <rect x="60" y="60" width="8" height="8" rx="1"/>
                            <rect x="72" y="60" width="8" height="8" rx="1"/>
                            <rect x="84" y="60" width="8" height="8" rx="1"/>
                            <rect x="60" y="72" width="8" height="8" rx="1"/>
                            <rect x="84" y="72" width="8" height="8" rx="1"/>
                            <rect x="72" y="84" width="8" height="8" rx="1"/>
                            <rect x="84" y="84" width="8" height="8" rx="1"/>
                        </svg>
                    </button>

                    {{-- Peserta & kode --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-[#0B1040] text-sm truncate">{{ $t->participant }}</p>
                        <p class="font-mono text-xs text-[#2563EB]">{{ $t->ticket_code }}</p>
                    </div>

                    {{-- Status --}}
                    <div class="flex-shrink-0">
                        <x-status-badge :status="$t->status"/>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <button
                            type="button"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-[#2563EB] hover:bg-blue-50 transition-colors"
                            title="Lihat QR Code"
                            @click="openQr('{{ $t->ticket_code }}', '{{ addslashes($t->participant) }}')"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </button>
                        <a
                            href="{{ route('ticket.show', $t->ticket_code) }}"
                            target="_blank"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors"
                            title="Lihat tiket"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Group footer --}}
            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                <p class="text-xs text-slate-400">
                    {{ $group->phone }}
                    @if($group->created_at)
                     &bull; {{ \Carbon\Carbon::parse($group->created_at)->format('d M Y H:i') }}
                    @endif
                </p>
                <a href="{{ route('admin.registrations.show', $group->order_code) }}"
                   class="text-xs font-semibold text-[#2563EB] hover:underline flex items-center gap-1">
                    Lihat Pendaftaran
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-12 text-center">
        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        </div>
        <p class="font-semibold text-slate-500 text-sm">Belum ada e-ticket yang terdaftar.</p>
        <p class="text-xs text-slate-400 mt-1">Tiket akan muncul setelah peserta menyelesaikan pembayaran.</p>
    </div>
    @endforelse

    {{-- ═══ QR Code Modal ═══ --}}
    <div
        x-show="qrModal.open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4"
        @keydown.escape.window="closeQr()"
        @click.self="closeQr()"
    >
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-sm p-6 text-center"
        >
            {{-- Close --}}
            <div class="flex items-center justify-between mb-4">
                <div class="text-left">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">QR Code Tiket</p>
                    <p class="font-extrabold text-[#0B1040] text-sm" x-text="qrModal.participant"></p>
                </div>
                <button @click="closeQr()"
                    class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- QR display --}}
            <div class="bg-[#F8FAFF] rounded-2xl border-2 border-[#E2E8F7] p-6 mb-4 flex items-center justify-center">
                {{-- SVG QR placeholder — dalam produksi diganti dengan QR Code library --}}
                <svg viewBox="0 0 120 120" class="w-44 h-44 text-[#0B1040]" fill="currentColor">
                    {{-- Corner squares --}}
                    <rect x="5" y="5" width="40" height="40" rx="4" fill="none" stroke="currentColor" stroke-width="5"/>
                    <rect x="14" y="14" width="22" height="22" rx="2"/>
                    <rect x="75" y="5" width="40" height="40" rx="4" fill="none" stroke="currentColor" stroke-width="5"/>
                    <rect x="84" y="14" width="22" height="22" rx="2"/>
                    <rect x="5" y="75" width="40" height="40" rx="4" fill="none" stroke="currentColor" stroke-width="5"/>
                    <rect x="14" y="84" width="22" height="22" rx="2"/>
                    {{-- Data dots --}}
                    <rect x="55" y="55" width="8" height="8" rx="1"/>
                    <rect x="67" y="55" width="8" height="8" rx="1"/>
                    <rect x="79" y="55" width="8" height="8" rx="1"/>
                    <rect x="91" y="55" width="8" height="8" rx="1"/>
                    <rect x="103" y="55" width="8" height="8" rx="1"/>
                    <rect x="55" y="67" width="8" height="8" rx="1"/>
                    <rect x="79" y="67" width="8" height="8" rx="1"/>
                    <rect x="103" y="67" width="8" height="8" rx="1"/>
                    <rect x="55" y="79" width="8" height="8" rx="1"/>
                    <rect x="67" y="79" width="8" height="8" rx="1"/>
                    <rect x="91" y="79" width="8" height="8" rx="1"/>
                    <rect x="55" y="91" width="8" height="8" rx="1"/>
                    <rect x="79" y="91" width="8" height="8" rx="1"/>
                    <rect x="55" y="103" width="8" height="8" rx="1"/>
                    <rect x="67" y="103" width="8" height="8" rx="1"/>
                    <rect x="79" y="103" width="8" height="8" rx="1"/>
                    <rect x="91" y="103" width="8" height="8" rx="1"/>
                    <rect x="103" y="103" width="8" height="8" rx="1"/>
                </svg>
            </div>

            {{-- Ticket code --}}
            <div class="bg-slate-50 rounded-xl px-4 py-2.5 mb-4">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-0.5">Ticket Code</p>
                <p class="font-mono font-extrabold text-[#1D4ED8] text-lg" x-text="qrModal.code"></p>
            </div>

            <p class="text-xs text-slate-400 mb-4">Scan QR ini saat check-in peserta di lokasi event.</p>

            <div class="flex gap-2">
                <a :href="'/ticket/' + qrModal.code"
                   target="_blank"
                   class="btn-secondary flex-1 justify-center text-sm gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Tiket
                </a>
                <button @click="closeQr()" class="btn-primary flex-1 justify-center text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
