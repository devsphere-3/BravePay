@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

@php
$stats = $stats ?? [
    'total_registrations'  => 0,
    'total_participants'   => 0,
    'total_transactions'   => 0,
    'paid_transactions'    => 0,
    'pending_transactions' => 0,
    'failed_transactions'  => 0,
    'total_revenue'        => 0,
    'checked_in'           => 0,
];
$recent_registrations = $recent_registrations ?? collect([]);
@endphp

{{-- Stats grid --}}
<div class="grid grid-cols-1 min-[420px]:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label'=>'Total Pendaftaran','value'=>number_format($stats['total_registrations']),'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'bg-blue-50 text-blue-600','accent'=>'bg-blue-600'],
        ['label'=>'Total Peserta','value'=>number_format($stats['total_participants']),'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','color'=>'bg-indigo-50 text-indigo-600','accent'=>'bg-indigo-600'],
        ['label'=>'Pembayaran Lunas','value'=>number_format($stats['paid_transactions']),'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'bg-green-50 text-green-600','accent'=>'bg-green-600'],
        ['label'=>'Total Revenue','value'=>'Rp'.number_format($stats['total_revenue'],0,',','.'),'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'bg-amber-50 text-amber-600','accent'=>'bg-amber-500'],
    ] as $stat)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 mb-2">{{ $stat['label'] }}</p>
                <p class="text-2xl font-extrabold text-[#0B1040] leading-none">{{ $stat['value'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl {{ $stat['color'] }} flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Second row --}}
<div class="grid grid-cols-1 min-[420px]:grid-cols-3 gap-4 mb-8">
    @foreach([
        ['label'=>'Menunggu Pembayaran','value'=>number_format($stats['pending_transactions']),'color'=>'bg-amber-50 border-amber-200','text'=>'text-amber-600'],
        ['label'=>'Pembayaran Gagal','value'=>number_format($stats['failed_transactions']),'color'=>'bg-red-50 border-red-200','text'=>'text-red-600'],
        ['label'=>'Check-in Berhasil','value'=>number_format($stats['checked_in']),'color'=>'bg-green-50 border-green-200','text'=>'text-green-700'],
    ] as $stat)
    <div class="rounded-2xl border {{ $stat['color'] }} p-5">
        <p class="text-xs font-semibold text-slate-500 mb-1">{{ $stat['label'] }}</p>
        <p class="text-2xl font-extrabold {{ $stat['text'] }}">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Content grid --}}
<div class="grid lg:grid-cols-3 gap-6">

    {{-- Recent Registrations --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-[#0B1040] text-base">Pendaftaran Terbaru</h2>
            <a href="{{ route('admin.registrations.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        <th class="px-5 py-3 text-left">Order ID</th>
                        <th class="px-5 py-3 text-left">Nama</th>
                        <th class="px-5 py-3 text-left hidden sm:table-cell">Lomba</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recent_registrations as $reg)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.registrations.show', $reg->id ?? $reg->order_code) }}" class="font-mono text-xs text-[#2563EB] hover:underline font-bold">{{ $reg->order_code ?? '—' }}</a>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-[#0B1040] truncate max-w-[120px]">{{ $reg->email ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5 hidden sm:table-cell">
                            <span class="text-slate-600 text-xs truncate max-w-[120px] block">{{ optional($reg->competition)->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <x-status-badge :status="optional($reg->payment)->status ?? 'pending'"/>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-[#0B1040]">
                            Rp{{ number_format((int) ($reg->total_amount ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center">
                            <p class="text-sm text-slate-400">Belum ada pendaftaran.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick actions + payment status --}}
    <div class="space-y-5">
        {{-- Payment breakdown --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h2 class="font-bold text-[#0B1040] text-base mb-4">Status Pembayaran</h2>
            <div class="space-y-3">
                @foreach([
                    ['Lunas','paid',$stats['paid_transactions'],'bg-green-500'],
                    ['Pending','pending',$stats['pending_transactions'],'bg-amber-400'],
                    ['Gagal','failed',$stats['failed_transactions'],'bg-red-400'],
                ] as $item)
                @php $pct = $stats['total_transactions'] > 0 ? round($item[2] / $stats['total_transactions'] * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-semibold text-slate-600">{{ $item[0] }}</span>
                        <span class="font-bold text-[#0B1040]">{{ $item[2] }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $item[3] }}" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h2 class="font-bold text-[#0B1040] text-base mb-4">Aksi Cepat</h2>
            <div class="space-y-2">
                <a href="{{ route('admin.competitions.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#F0F4FF] transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 group-hover:bg-blue-600 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-blue-600 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-[#1E3A8A]">Tambah Lomba Baru</span>
                </a>
                <a href="{{ route('admin.checkin') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#F0F4FF] transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 group-hover:bg-purple-600 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-purple-600 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 4v4m0-4h.01M4 4h4m12 4V4m0 0H8"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-[#1E3A8A]">QR Check-in Scanner</span>
                </a>
                <a href="{{ route('admin.registrations.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#F0F4FF] transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-green-100 group-hover:bg-green-600 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-green-600 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h8"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-[#1E3A8A]">Kelola Pendaftaran</span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
