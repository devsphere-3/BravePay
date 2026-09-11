@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan')

@section('content')

<div class="max-w-2xl space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-[#0B1040]">Pengaturan Sistem</h1>
        <p class="text-slate-500 text-sm mt-0.5">Konfigurasi platform BravePay</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
        @csrf

        {{-- General --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Umum</h2>
            <div>
                <label class="form-label">Nama Platform</label>
                <input type="text" name="app_name" value="{{ config('app.name', 'BravePay') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Email Kontak</label>
                <input type="email" name="contact_email" placeholder="admin@bravepay.id" class="form-input">
            </div>
            <div>
                <label class="form-label">Nomor WhatsApp Admin</label>
                <input type="text" name="whatsapp_number" placeholder="+6281234567890" class="form-input">
            </div>
        </div>

        {{-- Payment --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Pembayaran</h2>
            <div>
                <label class="form-label">Payment Gateway</label>
                <select name="payment_gateway" class="form-select">
                    <option value="midtrans">Midtrans</option>
                    <option value="xendit">Xendit</option>
                    <option value="duitku">Duitku</option>
                    <option value="manual">Manual (Konfirmasi Admin)</option>
                </select>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">API Key (Server)</label>
                    <input type="password" name="pg_server_key" placeholder="••••••••" class="form-input font-mono text-sm">
                </div>
                <div>
                    <label class="form-label">API Key (Client)</label>
                    <input type="password" name="pg_client_key" placeholder="••••••••" class="form-input font-mono text-sm">
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-xs text-amber-700 font-medium">Simpan API key di file <code class="font-mono bg-amber-100 px-1 rounded">.env</code>, bukan di database.</p>
            </div>
        </div>

        {{-- Notifications --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Notifikasi</h2>
            <div class="space-y-3">
                @foreach([
                    ['key'=>'notif_email_registration','label'=>'Email konfirmasi pendaftaran'],
                    ['key'=>'notif_email_payment','label'=>'Email konfirmasi pembayaran + e-ticket'],
                    ['key'=>'notif_wa_ticket','label'=>'WhatsApp e-ticket setelah pembayaran'],
                ] as $n)
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="{{ $n['key'] }}" value="1" checked class="sr-only peer">
                        <div class="w-10 h-5 bg-slate-200 peer-checked:bg-[#2563EB] rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm text-[#475569] font-medium">{{ $n['label'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

@endsection
