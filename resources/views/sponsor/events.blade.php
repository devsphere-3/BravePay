@extends('layouts.admin')

@section('title', 'Event Sponsor')
@section('page_title', 'Info Event')

@section('content')
@php
use App\Models\Competition;
$competitions = Competition::withCount('registrations')->orderByDesc('event_date')->get();
@endphp

<div class="mb-6"><h1 class="text-2xl font-extrabold text-[#0B1040]">Info Event</h1><p class="text-slate-500 text-sm mt-1">Daftar event dan jumlah registrasi peserta.</p></div>
<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($competitions as $competition)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-start justify-between gap-3"><h2 class="font-bold text-[#0B1040]">{{ $competition->name }}</h2><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $competition->status === 'open' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">{{ ucfirst(str_replace('_', ' ', $competition->status)) }}</span></div>
        <p class="text-sm text-slate-500 mt-3">{{ $competition->category }} · {{ $competition->location ?: 'Lokasi belum ditentukan' }}</p>
        <div class="flex justify-between border-t border-slate-100 mt-5 pt-4 text-sm"><span class="text-slate-500">Registrasi</span><strong class="text-[#0B1040]">{{ number_format($competition->registrations_count) }}</strong></div>
    </div>
    @empty
    <div class="md:col-span-2 xl:col-span-3 bg-white rounded-2xl border border-slate-200 p-12 text-center text-sm text-slate-400">Belum ada event.</div>
    @endforelse
</div>
@endsection
