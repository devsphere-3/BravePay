@extends('layouts.admin')

@section('title', 'Laporan Keuangan')
@section('page_title', 'Laporan Keuangan')

@section('content')
@php
use App\Models\Registration;

$paidRegistrations = Registration::where('status', 'paid')->count();
$pendingRegistrations = Registration::where('status', 'pending')->count();
$failedRegistrations = Registration::where('status', 'failed')->count();
$totalRevenue = (int) Registration::where('status', 'paid')->sum('total_amount');
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-[#0B1040]">Laporan Keuangan</h1>
    <p class="text-slate-500 text-sm mt-1">Ringkasan status pembayaran dan pendapatan BravePay.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Pendapatan Lunas', 'value' => 'Rp'.number_format($totalRevenue, 0, ',', '.'), 'color' => 'bg-emerald-50 text-emerald-600'],
        ['label' => 'Transaksi Lunas', 'value' => number_format($paidRegistrations), 'color' => 'bg-blue-50 text-blue-600'],
        ['label' => 'Transaksi Pending', 'value' => number_format($pendingRegistrations), 'color' => 'bg-amber-50 text-amber-600'],
        ['label' => 'Transaksi Gagal', 'value' => number_format($failedRegistrations), 'color' => 'bg-red-50 text-red-600'],
    ] as $stat)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <p class="text-xs font-semibold text-slate-500 mb-2">{{ $stat['label'] }}</p>
        <p class="text-xl lg:text-2xl font-extrabold text-[#0B1040] truncate">{{ $stat['value'] }}</p>
        <div class="w-8 h-1 rounded-full {{ $stat['color'] }} mt-4"></div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-bold text-[#0B1040]">Status Transaksi</h2>
            <p class="text-xs text-slate-500 mt-1">Data agregat dari seluruh registrasi</p>
        </div>
        <a href="{{ route('finance.payments.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">Lihat pembayaran</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-right">Jumlah</th>
                    <th class="px-6 py-3 text-right">Persentase</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php $totalTransactions = $paidRegistrations + $pendingRegistrations + $failedRegistrations; @endphp
                @foreach([
                    ['label' => 'Lunas', 'value' => $paidRegistrations, 'class' => 'text-emerald-600'],
                    ['label' => 'Pending', 'value' => $pendingRegistrations, 'class' => 'text-amber-600'],
                    ['label' => 'Gagal', 'value' => $failedRegistrations, 'class' => 'text-red-600'],
                ] as $row)
                <tr>
                    <td class="px-6 py-4 font-semibold {{ $row['class'] }}">{{ $row['label'] }}</td>
                    <td class="px-6 py-4 text-right font-bold text-[#0B1040]">{{ number_format($row['value']) }}</td>
                    <td class="px-6 py-4 text-right text-slate-500">{{ $totalTransactions > 0 ? round($row['value'] / $totalTransactions * 100) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
