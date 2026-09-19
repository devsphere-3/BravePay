@extends('layouts.admin')

@section('title', 'Statistik Sponsor')
@section('page_title', 'Statistik Peserta')

@section('content')
@php
use App\Models\Competition;
use App\Models\Registration;

$competitions = Competition::with('registrations')->orderByDesc('event_date')->get();
$chartData = $competitions->map(function ($competition) {
    return [
        'name' => $competition->name,
        'participants' => (int) $competition->registrations->sum('participant_count'),
        'registrations' => $competition->registrations->count(),
        'paid' => $competition->registrations->where('status', 'paid')->count(),
    ];
})->sortByDesc('participants')->values();

$maxParticipants = max(1, (int) $chartData->max('participants'));
$totalParticipants = (int) $chartData->sum('participants');
$totalRegistrations = (int) $chartData->sum('registrations');
$totalPaid = (int) $chartData->sum('paid');
@endphp

<div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-[#0B1040]">Statistik Peserta</h1>
        <p class="text-slate-500 text-sm mt-1">Perbandingan jumlah peserta di setiap event.</p>
    </div>
    <a href="{{ route('sponsor.dashboard') }}" class="text-sm font-semibold text-[#2563EB] hover:underline">Kembali ke dashboard</a>
</div>

<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @foreach([
        ['label' => 'Total Peserta', 'value' => number_format($totalParticipants), 'color' => 'bg-amber-50 text-amber-600'],
        ['label' => 'Total Registrasi', 'value' => number_format($totalRegistrations), 'color' => 'bg-blue-50 text-blue-600'],
        ['label' => 'Registrasi Lunas', 'value' => number_format($totalPaid), 'color' => 'bg-emerald-50 text-emerald-600'],
    ] as $stat)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <p class="text-xs font-semibold text-slate-500 mb-2">{{ $stat['label'] }}</p>
        <p class="text-2xl font-extrabold text-[#0B1040]">{{ $stat['value'] }}</p>
        <div class="w-8 h-1 rounded-full {{ $stat['color'] }} mt-4"></div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
        <div>
            <h2 class="font-bold text-[#0B1040]">Peserta per Event</h2>
            <p class="text-xs text-slate-500 mt-1">Grafik berdasarkan jumlah peserta terdaftar.</p>
        </div>
        <span class="text-xs font-semibold text-slate-500">{{ $chartData->count() }} event</span>
    </div>

    @if($chartData->isEmpty())
        <div class="py-16 text-center text-sm text-slate-400">Belum ada data peserta untuk ditampilkan.</div>
    @else
        <div class="space-y-5">
            @foreach($chartData as $item)
            @php $width = max(3, round($item['participants'] / $maxParticipants * 100)); @endphp
            <div>
                <div class="flex items-center justify-between gap-4 mb-2">
                    <span class="text-sm font-semibold text-[#1E3A8A] truncate">{{ $item['name'] }}</span>
                    <span class="text-sm font-extrabold text-[#0B1040] whitespace-nowrap">{{ number_format($item['participants']) }} peserta</span>
                </div>
                <div class="h-8 rounded-lg bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-lg bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-end px-3 text-xs font-bold text-white transition-all" style="width: {{ $width }}%">
                        {{ $item['paid'] }} lunas
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-1">{{ number_format($item['registrations']) }} registrasi</p>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
