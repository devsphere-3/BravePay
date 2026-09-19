@extends('layouts.admin')

@section('title', 'Peserta Sponsor')
@section('page_title', 'Data Peserta')

@section('content')
@php
use App\Models\Registration;
$registrations = Registration::with('competition')->where('status', 'paid')->latest()->limit(50)->get();
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-[#0B1040]">Data Peserta</h1>
    <p class="text-slate-500 text-sm mt-1">Ringkasan peserta dari registrasi yang sudah lunas.</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-bold text-[#0B1040]">Peserta Terbaru</h2>
        <span class="text-xs text-slate-500">{{ $registrations->count() }} data</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr><th class="px-6 py-3 text-left">Event</th><th class="px-6 py-3 text-left">Kontak</th><th class="px-6 py-3 text-right">Peserta</th><th class="px-6 py-3 text-left">Tanggal</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($registrations as $registration)
                <tr><td class="px-6 py-4 font-semibold text-[#1E3A8A]">{{ $registration->competition?->name ?? '—' }}</td><td class="px-6 py-4 text-slate-600">{{ $registration->email }}</td><td class="px-6 py-4 text-right font-bold text-[#0B1040]">{{ number_format($registration->participant_count) }}</td><td class="px-6 py-4 text-slate-500">{{ optional($registration->created_at)->format('d M Y') }}</td></tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-sm text-slate-400">Belum ada data peserta.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
