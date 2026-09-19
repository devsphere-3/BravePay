@extends('layouts.admin')

@section('title', 'SuperAdmin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
@php
use App\Models\Registration;
use App\Models\User;
use App\Models\Competition;
use App\Models\Role;

$totalUsers       = User::count();
$totalRegistrations = Registration::count();
$paidCount        = Registration::where('status','paid')->count();
$pendingCount     = Registration::where('status','pending')->count();
$failedCount      = Registration::where('status','failed')->count();
$totalRevenue     = (int) Registration::where('status','paid')->sum('total_amount');
$totalCompetitions = Competition::count();
$activeCompetitions = Competition::where('status','open')->count();

// Users per role
$usersByRole = Role::withCount('users')->orderByDesc('level')->get();

// Recent 8 registrations
$recentRegs = Registration::with('competition')->latest()->limit(8)->get();
@endphp

{{-- Welcome banner --}}
<div class="mb-6 rounded-2xl bg-gradient-to-r from-[#0B1040] to-[#1a2a8f] p-6 flex items-center justify-between gap-4 shadow">
    <div>
        <h2 class="text-white font-bold text-xl leading-tight">Selamat datang, {{ auth()->user()->name }} 👋</h2>
        <p class="text-blue-200 text-sm mt-1">Anda login sebagai <span class="font-semibold text-white">Super Administrator</span> — akses penuh ke seluruh sistem.</p>
    </div>
    <div class="hidden sm:flex items-center gap-2 text-xs text-blue-300">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- Stats Row 1 --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    @foreach([
        ['label'=>'Total User',        'value'=> number_format($totalUsers),         'icon'=>'users',       'color'=>'bg-violet-50 text-violet-600', 'accent'=>'bg-violet-500'],
        ['label'=>'Total Pendaftaran', 'value'=> number_format($totalRegistrations),  'icon'=>'collection',  'color'=>'bg-blue-50 text-blue-600',    'accent'=>'bg-blue-500'],
        ['label'=>'Total Revenue',     'value'=>'Rp'.number_format($totalRevenue,0,',','.'), 'icon'=>'credit-card', 'color'=>'bg-emerald-50 text-emerald-600','accent'=>'bg-emerald-500'],
        ['label'=>'Kompetisi Aktif',   'value'=> $activeCompetitions.'/'.$totalCompetitions,'icon'=>'document-text','color'=>'bg-amber-50 text-amber-600', 'accent'=>'bg-amber-500'],
    ] as $s)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-slate-500 mb-2">{{ $s['label'] }}</p>
                <p class="text-2xl font-extrabold text-[#0B1040] leading-none">{{ $s['value'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl {{ $s['color'] }} flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Stats Row 2 --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @foreach([
        ['label'=>'Lunas',   'value'=> $paidCount,    'color'=>'bg-green-50  border-green-200',  'text'=>'text-green-700'],
        ['label'=>'Pending', 'value'=> $pendingCount,  'color'=>'bg-amber-50  border-amber-200',  'text'=>'text-amber-700'],
        ['label'=>'Gagal',   'value'=> $failedCount,   'color'=>'bg-red-50    border-red-200',    'text'=>'text-red-700'],
    ] as $s)
    <div class="rounded-2xl border {{ $s['color'] }} p-5">
        <p class="text-xs font-semibold text-slate-500 mb-1">{{ $s['label'] }}</p>
        <p class="text-2xl font-extrabold {{ $s['text'] }}">{{ number_format($s['value']) }}</p>
    </div>
    @endforeach
</div>

{{-- Content Grid --}}
<div class="grid lg:grid-cols-3 gap-6">

    {{-- Recent Registrations --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-[#0B1040] text-base">Pendaftaran Terbaru</h2>
            <a href="{{ route('superadmin.registrations.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        <th class="px-5 py-3 text-left">Order</th>
                        <th class="px-5 py-3 text-left hidden sm:table-cell">Email</th>
                        <th class="px-5 py-3 text-left hidden md:table-cell">Lomba</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentRegs as $reg)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('superadmin.registrations.show', $reg->id) }}"
                               class="font-mono text-xs text-[#2563EB] hover:underline font-bold">
                                {{ $reg->order_code }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5 hidden sm:table-cell text-slate-600 text-xs truncate max-w-[140px]">{{ $reg->email }}</td>
                        <td class="px-5 py-3.5 hidden md:table-cell text-slate-600 text-xs truncate max-w-[140px]">{{ $reg->competition?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5"><x-status-badge :status="$reg->status"/></td>
                        <td class="px-5 py-3.5 text-right font-bold text-[#0B1040] text-xs">
                            Rp{{ number_format($reg->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right column --}}
    <div class="space-y-5">

        {{-- Users per Role --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h2 class="font-bold text-[#0B1040] text-base mb-4">User per Role</h2>
            <div class="space-y-3">
                @foreach($usersByRole as $role)
                @php
                    $barColor = match($role->name) {
                        'superadmin' => 'bg-violet-500',
                        'admin'      => 'bg-blue-500',
                        'finance'    => 'bg-emerald-500',
                        'sponsor'    => 'bg-amber-500',
                        default      => 'bg-slate-400',
                    };
                    $pct = $totalUsers > 0 ? min(100, round($role->users_count / $totalUsers * 100)) : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-semibold text-slate-600 capitalize">{{ $role->display_name }}</span>
                        <span class="font-bold text-[#0B1040]">{{ $role->users_count }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h2 class="font-bold text-[#0B1040] text-base mb-4">Aksi Cepat</h2>
            <div class="space-y-2">
                @foreach([
                    ['route'=>'superadmin.users.index',        'label'=>'Kelola User',         'color'=>'bg-violet-100 group-hover:bg-violet-600', 'icon'=>'text-violet-600 group-hover:text-white', 'path'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route'=>'superadmin.roles.index',        'label'=>'Role & Permission',   'color'=>'bg-blue-100 group-hover:bg-blue-600',     'icon'=>'text-blue-600 group-hover:text-white',   'path'=>'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                    ['route'=>'superadmin.competitions.index', 'label'=>'Kelola Kompetisi',    'color'=>'bg-amber-100 group-hover:bg-amber-500',   'icon'=>'text-amber-600 group-hover:text-white',  'path'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['route'=>'superadmin.logs.index',         'label'=>'Audit Log',           'color'=>'bg-slate-100 group-hover:bg-slate-600',   'icon'=>'text-slate-500 group-hover:text-white',  'path'=>'M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ] as $action)
                <a href="{{ route($action['route']) }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#F0F4FF] transition-colors group">
                    <div class="w-8 h-8 rounded-lg {{ $action['color'] }} flex items-center justify-center transition-colors flex-shrink-0">
                        <svg class="w-4 h-4 {{ $action['icon'] }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $action['path'] }}"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-[#1E3A8A]">{{ $action['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
