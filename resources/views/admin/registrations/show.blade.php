@extends('layouts.admin')

@section('title', 'Detail Pendaftaran')
@section('page_title', 'Detail Pendaftaran')

@section('content')

@php
$reg = $registration ?? null;
$order_code   = $reg ? $reg->order_code      : 'BRV-20260911-0001';
$email        = $reg ? $reg->email           : 'wahyu@email.com';
$phone        = $reg ? $reg->phone           : '+62812345678';
$comp_name    = $reg ? ($reg->competition->name ?? 'Basket Competition') : 'Basket Competition';
$participants = $reg ? $reg->participants    : collect([
    (object)['name'=>'Wahyu Perwira','date_of_birth'=>'2005-03-15'],
    (object)['name'=>'Budi Santoso', 'date_of_birth'=>'2004-07-22'],
    (object)['name'=>'Sari Dewi',    'date_of_birth'=>'2006-01-10'],
]);
$total_participants = $reg ? $reg->total_participants : 3;
$total_amount       = $reg ? $reg->total_amount       : 150000;
$payment_status     = $reg && $reg->payment ? $reg->payment->status : 'paid';
$payment_method     = $reg && $reg->payment ? ($reg->payment->payment_method ?? 'QRIS') : 'QRIS';
$paid_at            = $reg && $reg->payment && $reg->payment->paid_at ? \Carbon\Carbon::parse($reg->payment->paid_at)->format('d M Y H:i') : '11 Sep 2026 09:14';
$created_at         = $reg ? $reg->created_at->format('d M Y H:i') : '11 Sep 2026 08:00';
$tickets            = $reg ? $reg->tickets  : collect([
    (object)['ticket_code'=>'BRV-TKT-A8F92K','status'=>'active','participant'=>(object)['name'=>'Wahyu Perwira']],
    (object)['ticket_code'=>'BRV-TKT-X92KD1','status'=>'used',  'participant'=>(object)['name'=>'Budi Santoso']],
    (object)['ticket_code'=>'BRV-TKT-P7A21M','status'=>'active','participant'=>(object)['name'=>'Sari Dewi']],
]);
@endphp

{{-- Back + actions --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.registrations.index') }}" class="p-2 rounded-xl hover:bg-white border border-slate-200 text-slate-500 hover:text-[#2563EB] transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        </a>
        <div>
            <h1 class="text-lg font-extrabold text-[#0B1040] font-mono">{{ $order_code }}</h1>
            <p class="text-slate-400 text-xs">Didaftarkan: {{ $created_at }}</p>
        </div>
    </div>
    <x-status-badge :status="$payment_status"/>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Left --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Contact --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide mb-4">Kontak</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 mb-0.5">Email</p>
                    <p class="font-semibold text-[#0B1040] text-sm">{{ $email }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 mb-0.5">WhatsApp</p>
                    <p class="font-semibold text-[#0B1040] text-sm">{{ $phone }}</p>
                </div>
            </div>
        </div>

        {{-- Participants --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide mb-4">Peserta ({{ $total_participants }})</h2>
            <div class="space-y-2">
                @foreach($participants as $i => $p)
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-3">
                    <div class="w-8 h-8 rounded-full bg-[#DBEAFE] flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-[#1D4ED8]">{{ $i + 1 }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-[#0B1040] text-sm">{{ $p->name }}</p>
                        <p class="text-xs text-slate-400">
                            TTL: {{ $p->date_of_birth ? \Carbon\Carbon::parse($p->date_of_birth)->translatedFormat('d F Y') : '—' }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Tickets --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide mb-4">E-Ticket</h2>
            <div class="space-y-2">
                @foreach($tickets as $t)
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-3">
                    <div class="flex-1">
                        <p class="font-semibold text-[#0B1040] text-sm">{{ $t->participant->name ?? '—' }}</p>
                        <p class="font-mono text-xs text-[#2563EB]">{{ $t->ticket_code }}</p>
                    </div>
                    <x-status-badge :status="$t->status"/>
                    <div class="flex gap-1.5">
                        <a href="{{ route('ticket.show', $t->ticket_code) }}" target="_blank"
                           class="p-1.5 rounded-lg text-slate-400 hover:text-[#2563EB] hover:bg-blue-50 transition-colors" title="Lihat tiket">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right: Payment + event summary --}}
    <div class="space-y-5">
        {{-- Event --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide mb-3">Lomba</h2>
            <p class="font-bold text-[#0B1040]">{{ $comp_name }}</p>
            @if($reg && $reg->competition)
            <p class="text-xs text-slate-400 mt-1">
                {{ $reg->competition->event_date ? \Carbon\Carbon::parse($reg->competition->event_date)->translatedFormat('d F Y') : '' }}
                &bull; {{ $reg->competition->location ?? '' }}
            </p>
            @else
            <p class="text-xs text-slate-400 mt-1">20 Sep 2026 &bull; Batam</p>
            @endif
        </div>

        {{-- Payment --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide mb-4">Pembayaran</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Status</span>
                    <x-status-badge :status="$payment_status"/>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Metode</span>
                    <span class="font-semibold text-[#0B1040]">{{ $payment_method }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Dibayar Pada</span>
                    <span class="font-semibold text-[#0B1040]">{{ $paid_at }}</span>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-between">
                    <span class="font-bold text-[#0B1040]">Total</span>
                    <span class="font-extrabold text-[#2563EB]">Rp{{ number_format($total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide mb-3">Aksi</h2>
            <div class="space-y-2">
                <form action="{{ route('admin.tickets.resend', 0) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-secondary w-full justify-center text-sm py-2.5 gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Kirim Ulang E-Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
