@extends('layouts.admin')

@section('title', 'Dashboard Sponsor')
@section('page_title', 'Dashboard Sponsor')

@section('content')
@php
use App\Models\Competition;
use App\Models\Registration;

$totalEvents = Competition::count();
$activeEvents = Competition::where('status', 'open')->count();
$totalParticipants = (int) Registration::sum('participant_count');
$paidRegistrations = Registration::where('status', 'paid')->count();
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-[#0B1040]">Selamat datang, {{ auth()->user()->name }}</h1>
    <p class="text-slate-500 text-sm mt-1">Pantau informasi event dan statistik peserta yang relevan untuk sponsor.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Total Event', 'value' => number_format($totalEvents), 'color' => 'bg-blue-50 text-blue-600'],
        ['label' => 'Event Aktif', 'value' => number_format($activeEvents), 'color' => 'bg-emerald-50 text-emerald-600'],
        ['label' => 'Total Peserta', 'value' => number_format($totalParticipants), 'color' => 'bg-amber-50 text-amber-600'],
        ['label' => 'Registrasi Lunas', 'value' => number_format($paidRegistrations), 'color' => 'bg-violet-50 text-violet-600'],
    ] as $stat)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <p class="text-xs font-semibold text-slate-500 mb-2">{{ $stat['label'] }}</p>
        <p class="text-xl lg:text-2xl font-extrabold text-[#0B1040] truncate">{{ $stat['value'] }}</p>
        <div class="w-8 h-1 rounded-full {{ $stat['color'] }} mt-4"></div>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="font-bold text-[#0B1040]">Ringkasan Sponsor</h2>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">Gunakan dashboard ini untuk melihat perkembangan event, jumlah peserta, dan informasi event yang relevan dengan sponsorship Anda.</p>

        <div class="grid sm:grid-cols-2 gap-4 mt-6">
            <a href="{{ route('sponsor.statistics') }}" class="rounded-xl border border-amber-100 bg-amber-50 p-5 hover:bg-amber-100 transition-colors">
                <span class="block text-sm font-bold text-amber-900">Statistik Peserta</span>
                <span class="block text-xs text-amber-700 mt-1">Lihat perkembangan jumlah peserta</span>
            </a>
            <a href="{{ route('sponsor.events') }}" class="rounded-xl border border-blue-100 bg-blue-50 p-5 hover:bg-blue-100 transition-colors">
                <span class="block text-sm font-bold text-blue-900">Info Event</span>
                <span class="block text-xs text-blue-700 mt-1">Lihat event yang tersedia</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="font-bold text-[#0B1040] mb-4">Menu Sponsor</h2>
        <div class="space-y-3">
            <a href="{{ route('sponsor.participants') }}" class="block rounded-xl bg-[#F0F4FF] p-4 hover:bg-blue-50 transition-colors">
                <span class="block text-sm font-bold text-[#1E3A8A]">Data Peserta</span>
                <span class="block text-xs text-slate-500 mt-1">Lihat ringkasan peserta</span>
            </a>
            <a href="{{ route('sponsor.reports') }}" class="block rounded-xl bg-[#F0F4FF] p-4 hover:bg-blue-50 transition-colors">
                <span class="block text-sm font-bold text-[#1E3A8A]">Laporan Sponsor</span>
                <span class="block text-xs text-slate-500 mt-1">Buka laporan sponsorship</span>
            </a>
        </div>
    </div>
</div>
@endsection
