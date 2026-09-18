@extends('layouts.admin')

@section('title', 'QR Check-in')
@section('page_title', 'QR Check-in Scanner')

@push('head')
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
@endpush

@section('content')

<div class="max-w-lg mx-auto" x-data="{
    mode: 'input',
    ticketCode: '',
    loading: false,
    result: null,
    status: null,

    async validate() {
        if (!this.ticketCode.trim()) return;
        this.loading = true;
        this.result = null;
        this.status = null;
        try {
            const res = await fetch('/admin/api/checkin/' + encodeURIComponent(this.ticketCode.trim()), {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            this.result = await res.json();
            this.status = this.result.status;
        } catch {
            this.status = 'error';
            this.result = { message: 'Gagal menghubungi server.' };
        } finally {
            this.loading = false;
        }
    },

    async doCheckin() {
        if (!this.ticketCode.trim()) return;
        this.loading = true;
        try {
            const res = await fetch('/admin/api/checkin/' + encodeURIComponent(this.ticketCode.trim()), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            this.result = await res.json();
            this.status = this.result.status;
        } catch {
            this.status = 'error';
        } finally {
            this.loading = false;
        }
    },

    reset() {
        this.ticketCode = '';
        this.result = null;
        this.status = null;
        this.$nextTick(() => { const inp = this.$el.querySelector('input[name=ticket_code]'); if(inp) inp.focus(); });
    }
}">

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-5">
        {{-- Header --}}
        <div class="bg-[#0B1040] relative overflow-hidden px-6 py-5">
            <div class="absolute inset-0 batik-kawung opacity-15"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 4v4m0-4h.01M4 4h4m12 4V4m0 0H8"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-extrabold text-lg leading-tight">QR Check-in</h1>
                        <p class="text-blue-200/60 text-xs">Validasi tiket peserta</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input mode --}}
        <div class="p-6">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Masukkan Kode Tiket</p>
            <div class="flex gap-2">
                <input
                    type="text"
                    name="ticket_code"
                    x-model="ticketCode"
                    @keydown.enter="validate()"
                    placeholder="BRV-TKT-A8F92K"
                    class="form-input flex-1 font-mono text-sm uppercase"
                    autocomplete="off"
                    autocorrect="off"
                    spellcheck="false"
                >
                <button
                    @click="validate()"
                    :disabled="!ticketCode.trim() || loading"
                    class="btn-primary btn-sm flex-shrink-0 disabled:opacity-50 min-w-[80px]"
                >
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-show="!loading">Cek</span>
                </button>
            </div>
            <p class="text-xs text-slate-400 mt-2">Ketik atau scan kode QR, lalu tekan Enter atau Cek</p>
        </div>
    </div>

    {{-- Result --}}
    <div x-show="result" x-cloak>

        {{-- VALID --}}
        <div x-show="status === 'valid'" class="bg-white rounded-2xl border-2 border-green-400 shadow-sm overflow-hidden mb-4">
            <div class="bg-green-500 px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-extrabold text-base">TIKET VALID</p>
                    <p class="text-green-100 text-xs">Siap untuk check-in</p>
                </div>
            </div>
            <div class="p-6 space-y-3">
                <template x-if="result">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Peserta</p>
                            <p class="font-bold text-[#0B1040] text-sm" x-text="result.participant_name ?? '—'"></p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Lomba</p>
                            <p class="font-bold text-[#0B1040] text-sm" x-text="result.competition_name ?? '—'"></p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Kode Tiket</p>
                            <p class="font-mono font-bold text-[#1D4ED8] text-sm" x-text="result.ticket_code ?? ticketCode"></p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Kategori</p>
                            <p class="font-bold text-[#0B1040] text-sm" x-text="result.category ?? '—'"></p>
                        </div>
                    </div>
                </template>
                <div class="flex gap-3 mt-4">
                    <button @click="reset()" class="btn-secondary flex-1 justify-center btn-sm">Scan Lagi</button>
                    <button @click="doCheckin()" :disabled="loading" class="btn-primary flex-1 justify-center btn-sm bg-green-600 hover:bg-green-700 disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Check-in Sekarang
                    </button>
                </div>
            </div>
        </div>

        {{-- CHECKED IN / USED --}}
        <div x-show="status === 'used' || status === 'already_checked_in'" class="bg-white rounded-2xl border-2 border-amber-300 shadow-sm overflow-hidden mb-4">
            <div class="bg-amber-400 px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-extrabold text-base">SUDAH CHECK-IN</p>
                    <p class="text-amber-100 text-xs" x-text="result?.checked_in_at ? 'Pada: ' + result.checked_in_at : 'Tiket sudah digunakan'"></p>
                </div>
            </div>
            <div class="p-4 text-center">
                <button @click="reset()" class="btn-secondary btn-sm">Scan Tiket Lain</button>
            </div>
        </div>

        {{-- CHECKED IN SUCCESS --}}
        <div x-show="status === 'checked_in'" class="bg-white rounded-2xl border-2 border-green-500 shadow-sm overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-5 text-center">
                <svg class="w-12 h-12 text-white mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-white font-extrabold text-xl">CHECK-IN BERHASIL!</p>
                <p class="text-green-100 text-sm mt-1" x-text="result?.participant_name ? 'Selamat datang, ' + result.participant_name + '!' : 'Selamat datang!'"></p>
            </div>
            <div class="p-4 text-center">
                <button @click="reset()" class="btn-primary btn-sm">Scan Tiket Berikutnya</button>
            </div>
        </div>

        {{-- INVALID --}}
        <div x-show="status === 'invalid' || status === 'not_found'" class="bg-white rounded-2xl border-2 border-red-400 shadow-sm overflow-hidden mb-4">
            <div class="bg-red-500 px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-extrabold text-base">TIKET TIDAK VALID</p>
                    <p class="text-red-100 text-xs" x-text="result?.message ?? 'Kode tiket tidak ditemukan dalam sistem'"></p>
                </div>
            </div>
            <div class="p-4 text-center">
                <button @click="reset()" class="btn-secondary btn-sm">Coba Lagi</button>
            </div>
        </div>

        {{-- ERROR --}}
        <div x-show="status === 'error'" class="bg-red-50 border border-red-200 rounded-2xl p-5 text-center mb-4">
            <p class="font-bold text-red-700 mb-2">Terjadi Kesalahan</p>
            <p class="text-sm text-red-600" x-text="result?.message ?? 'Gagal menghubungi server.'"></p>
            <button @click="reset()" class="btn-secondary btn-sm mt-3">Coba Lagi</button>
        </div>
    </div>

    {{-- Recent check-ins --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-bold text-[#0B1040] text-sm">Check-in Terakhir</h2>
        </div>
        @forelse($recent_checkins ?? [] as $ci)
        <div class="flex items-center gap-3 px-5 py-3 border-b border-slate-50 last:border-0">
            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-[#0B1040] truncate">{{ $ci->ticket->participant->name ?? '—' }}</p>
                <p class="text-xs text-slate-400 font-mono">{{ $ci->ticket->ticket_code ?? '—' }}</p>
            </div>
            <span class="text-xs text-slate-400">{{ $ci->checked_in_at ? \Carbon\Carbon::parse($ci->checked_in_at)->format('H:i') : '' }}</span>
        </div>
        @empty
        <div class="px-5 py-8 text-center">
            <p class="text-sm text-slate-400">Belum ada check-in hari ini.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
