@extends('layouts.admin')

@section('title', 'Laporan Sponsor')
@section('page_title', 'Laporan Sponsor')

@section('content')
@php
use App\Models\Competition;
use App\Models\Registration;
$totalEvents = Competition::count();
$totalParticipants = (int) Registration::where('status', 'paid')->sum('participant_count');
$totalRegistrations = Registration::where('status', 'paid')->count();
@endphp

<div class="mb-6"><h1 class="text-2xl font-extrabold text-[#0B1040]">Laporan Sponsor</h1><p class="text-slate-500 text-sm mt-1">Ringkasan performa event untuk kebutuhan sponsorship.</p></div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @foreach([['label'=>'Total Event','value'=>$totalEvents],['label'=>'Peserta Lunas','value'=>$totalParticipants],['label'=>'Registrasi Lunas','value'=>$totalRegistrations]] as $stat)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm"><p class="text-xs font-semibold text-slate-500 mb-2">{{ $stat['label'] }}</p><p class="text-2xl font-extrabold text-[#0B1040]">{{ number_format($stat['value']) }}</p></div>
    @endforeach
</div>
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6"><h2 class="font-bold text-[#0B1040] mb-2">Ringkasan</h2><p class="text-sm text-slate-500">Gunakan statistik dan info event untuk memantau jangkauan sponsorship BravePay.</p></div>
@endsection
