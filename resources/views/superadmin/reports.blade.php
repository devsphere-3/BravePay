@extends('layouts.admin')
@section('title', 'Laporan Sistem')
@section('page_title', 'Laporan Sistem')
@section('content')
@php use App\Models\Competition; use App\Models\Registration; use App\Models\User; $stats = [['label'=>'Total User','value'=>User::count()],['label'=>'Total Event','value'=>Competition::count()],['label'=>'Total Registrasi','value'=>Registration::count()],['label'=>'Registrasi Lunas','value'=>Registration::where('status','paid')->count()]]; @endphp
<div class="mb-6"><h1 class="text-2xl font-extrabold text-[#0B1040]">Laporan Sistem</h1><p class="text-slate-500 text-sm mt-1">Ringkasan kondisi operasional BravePay.</p></div><div class="grid grid-cols-2 lg:grid-cols-4 gap-4">@foreach($stats as $stat)<div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm"><p class="text-xs font-semibold text-slate-500 mb-2">{{ $stat['label'] }}</p><p class="text-2xl font-extrabold text-[#0B1040]">{{ number_format($stat['value']) }}</p></div>@endforeach</div>
@endsection
