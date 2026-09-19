@extends('layouts.admin')

@section('title', 'Dashboard Finance')
@section('page_title', 'Dashboard Finance')

@section('content')
@php
use App\Models\Registration;

$totalTransactions = Registration::count();
$paidTransactions = Registration::where('status', 'paid')->count();
$pendingTransactions = Registration::where('status', 'pending')->count();
$failedTransactions = Registration::where('status', 'failed')->count();
$totalRevenue = (int) Registration::where('status', 'paid')->sum('total_amount');
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-[#0B1040]">Selamat datang, {{ auth()->user()->name }}</h1>
    <p class="text-slate-500 text-sm mt-1">Pantau transaksi dan laporan keuangan BravePay.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Total Transaksi', 'value' => number_format($totalTransactions), 'color' => 'bg-blue-50 text-blue-600'],
        ['label' => 'Pembayaran Lunas', 'value' => number_format($paidTransactions), 'color' => 'bg-emerald-50 text-emerald-600'],
        ['label' => 'Menunggu Pembayaran', 'value' => number_format($pendingTransactions), 'color' => 'bg-amber-50 text-amber-600'],
        ['label' => 'Pendapatan Lunas', 'value' => 'Rp'.number_format($totalRevenue, 0, ',', '.'), 'color' => 'bg-violet-50 text-violet-600'],
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
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-bold text-[#0B1040]">Ringkasan Pembayaran</h2>
                <p class="text-xs text-slate-500 mt-1">Status transaksi saat ini</p>
            </div>
            <a href="{{ route('finance.payments.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">Lihat transaksi</a>
        </div>
        <div class="space-y-4">
            @foreach([
                ['label' => 'Lunas', 'value' => $paidTransactions, 'color' => 'bg-emerald-500'],
                ['label' => 'Pending', 'value' => $pendingTransactions, 'color' => 'bg-amber-400'],
                ['label' => 'Gagal', 'value' => $failedTransactions, 'color' => 'bg-red-500'],
            ] as $item)
            @php $percentage = $totalTransactions > 0 ? round($item['value'] / $totalTransactions * 100) : 0; @endphp
            <div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="font-semibold text-slate-600">{{ $item['label'] }}</span>
                    <span class="font-bold text-[#0B1040]">{{ number_format($item['value']) }} ({{ $percentage }}%)</span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="font-bold text-[#0B1040] mb-4">Akses Finance</h2>
        <div class="space-y-3">
            <a href="{{ route('finance.payments.index') }}" class="block rounded-xl bg-[#F0F4FF] p-4 hover:bg-blue-50 transition-colors">
                <span class="block text-sm font-bold text-[#1E3A8A]">Data Pembayaran</span>
                <span class="block text-xs text-slate-500 mt-1">Periksa transaksi pelanggan</span>
            </a>
            <a href="{{ route('finance.registrations.index') }}" class="block rounded-xl bg-[#F0F4FF] p-4 hover:bg-blue-50 transition-colors">
                <span class="block text-sm font-bold text-[#1E3A8A]">Data Registrasi</span>
                <span class="block text-xs text-slate-500 mt-1">Lihat registrasi untuk rekonsiliasi</span>
            </a>
            <a href="{{ route('finance.reports') }}" class="block rounded-xl bg-[#F0F4FF] p-4 hover:bg-blue-50 transition-colors">
                <span class="block text-sm font-bold text-[#1E3A8A]">Laporan Keuangan</span>
                <span class="block text-xs text-slate-500 mt-1">Buka ringkasan laporan</span>
            </a>
        </div>
    </div>
</div>
@endsection
