@extends('layouts.app')

@section('title', 'Dashboard Saya')
@section('meta_description', 'Kelola pendaftaran event dan tiket Anda di BravePay.')

@section('content')
<section class="bg-[#F0F4FF] min-h-[calc(100vh-72px)] py-12">
    <div class="section-container">
        <div class="mb-8">
            <p class="text-sm font-bold text-[#2563EB] uppercase tracking-widest mb-2">Area Peserta</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0B1040]">Selamat datang, {{ auth()->user()->name }}</h1>
            <p class="text-[#475569] mt-2">Kelola pendaftaran dan tiket event Anda di sini.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('competitions.index') }}" class="card p-6 hover:shadow-lg transition-shadow">
                <p class="text-sm text-[#64748B] mb-2">Cari event</p>
                <h2 class="text-lg font-bold text-[#0B1040]">Lihat Event & Lomba</h2>
                <span class="text-[#2563EB] text-sm font-semibold mt-4 inline-block">Jelajahi event &rarr;</span>
            </a>
            <a href="{{ route('customer.history') }}" class="card p-6 hover:shadow-lg transition-shadow">
                <p class="text-sm text-[#64748B] mb-2">Aktivitas Anda</p>
                <h2 class="text-lg font-bold text-[#0B1040]">Riwayat Pendaftaran</h2>
                <span class="text-[#2563EB] text-sm font-semibold mt-4 inline-block">Lihat riwayat &rarr;</span>
            </a>
            <a href="{{ route('customer.tickets') }}" class="card p-6 hover:shadow-lg transition-shadow">
                <p class="text-sm text-[#64748B] mb-2">Akses masuk</p>
                <h2 class="text-lg font-bold text-[#0B1040]">Tiket Saya</h2>
                <span class="text-[#2563EB] text-sm font-semibold mt-4 inline-block">Lihat tiket &rarr;</span>
            </a>
        </div>

        <div class="card p-6">
            <h2 class="text-xl font-bold text-[#0B1040] mb-2">Butuh event baru?</h2>
            <p class="text-[#64748B] mb-4">Temukan kompetisi dan event yang sesuai untuk Anda.</p>
            <a href="{{ route('competitions.index') }}" class="btn-primary btn-sm">Lihat semua event</a>
        </div>
    </div>
</section>
@endsection
