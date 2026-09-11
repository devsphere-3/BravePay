@extends('layouts.app')

@section('title', 'Cek Status Pembayaran')

@section('content')

<div class="relative bg-gradient-to-b from-[#EFF6FF] to-white min-h-[70vh] flex items-center py-16">
    <div class="absolute inset-0 batik-dots pointer-events-none opacity-60"></div>
    <div class="section-container relative z-10">
        <div class="max-w-md mx-auto">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-[#DBEAFE] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0B1040] mb-2">Cek Status Pembayaran</h1>
                <p class="text-[#64748B] text-sm">Masukkan kode order untuk mengecek status</p>
            </div>

            <div class="card p-6">
                <form action="{{ route('payment.check') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label">Kode Order</label>
                        <input type="text" name="order_code" value="{{ old('order_code') }}" required
                               placeholder="BRV-20260911-0001"
                               class="form-input font-mono @error('order_code') border-red-400 @enderror"
                               autocomplete="off">
                        @error('order_code')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center">
                        Cek Status
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>

                @if(isset($registration))
                <div class="mt-5 pt-5 border-t border-[#E2E8F7]">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-[#64748B]">Order ID</span>
                            <span class="font-mono font-bold text-[#0B1040]">{{ $registration->order_code }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-[#64748B]">Lomba</span>
                            <span class="font-semibold text-[#0B1040]">{{ $registration->competition->name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-[#64748B]">Total</span>
                            <span class="font-bold text-[#0B1040]">Rp{{ number_format($registration->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-[#64748B]">Status Pembayaran</span>
                            <x-status-badge :status="$registration->payment ? $registration->payment->status : 'pending'"/>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('not_found'))
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    Order tidak ditemukan. Periksa kembali kode order kamu.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
