<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — BravePay</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="antialiased bg-[#F0F4FF] overflow-x-hidden" x-data="{ sidebarOpen: false }">

@php
    $user     = auth()->user();
    $roleName = $user?->role?->name ?? 'admin';
    $roleLabel = [
        'superadmin' => 'Super Admin',
        'admin'      => 'Administrator',
        'finance'    => 'Team Finance',
        'sponsor'    => 'Sponsor',
        'customer'   => 'Customer',
    ][$roleName] ?? 'Panel';

    // Warna aksen per role
    $accentClass = match($roleName) {
        'superadmin' => 'bg-violet-500',
        'finance'    => 'bg-emerald-500',
        'sponsor'    => 'bg-amber-500',
        default      => 'bg-blue-500',
    };

    // Inisial avatar
    $initial = strtoupper(substr($user?->name ?? 'U', 0, 1));
@endphp

    {{-- Mobile overlay --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-30 bg-black/40 lg:hidden"
        x-cloak
    ></div>

    {{-- ── SIDEBAR ──────────────────────────────────────────────── --}}
    <aside
        class="admin-sidebar transition-transform duration-300 ease-in-out"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        {{-- Brand --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-9 h-9 rounded-xl {{ $accentClass }} flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                </svg>
            </div>
            <div>
                <span class="text-white font-bold text-lg leading-none">BravePay</span>
                <span class="block text-blue-300 text-xs font-medium mt-0.5">{{ $roleLabel }}</span>
            </div>
        </div>

        {{-- ── Navigation per Role ───────────────────────────────── --}}
        <nav class="py-4 flex flex-col gap-1 overflow-y-auto" style="max-height: calc(100vh - 64px - 72px)">

            {{-- ════════════════ SUPERADMIN ════════════════ --}}
            @if($roleName === 'superadmin')

                <x-sidebar-section label="Overview"/>
                <x-sidebar-link route="superadmin.dashboard" label="Dashboard" icon="home"/>

                <x-sidebar-section label="User Management"/>
                @can('superadmin.manage_customers')
                <x-sidebar-link route="superadmin.users.index" label="Semua User" icon="users"/>
                @endcan
                @can('superadmin.manage_admins')
                <x-sidebar-link route="admin.dashboard" label="Panel Admin" icon="shield-check"/>
                @endcan

                <x-sidebar-section label="Event & Tiket"/>
                @can('superadmin.manage_events')
                <x-sidebar-link route="superadmin.competitions.index" label="Kompetisi" icon="document-text"/>
                @endcan
                @can('superadmin.manage_tickets')
                <x-sidebar-link route="superadmin.registrations.index" label="Registrasi" icon="collection"/>
                @endcan
                @can('superadmin.view_transactions')
                <x-sidebar-link route="superadmin.payments.index" label="Pembayaran" icon="credit-card"/>
                @endcan

                <x-sidebar-section label="Sistem"/>
                @can('superadmin.manage_roles')
                <x-sidebar-link route="superadmin.roles.index" label="Role & Permission" icon="lock-closed"/>
                @endcan
                @can('superadmin.view_logs')
                <x-sidebar-link route="superadmin.logs.index" label="Audit Log" icon="clock"/>
                @endcan
                @can('superadmin.manage_settings')
                <x-sidebar-link route="superadmin.settings" label="Pengaturan" icon="cog"/>
                @endcan

            {{-- ════════════════ ADMIN ════════════════ --}}
            @elseif($roleName === 'admin')

                <x-sidebar-section label="Menu"/>
                @can('admin.view_dashboard')
                <x-sidebar-link route="admin.dashboard" label="Dashboard" icon="home"/>
                @endcan

                <x-sidebar-section label="Peserta"/>
                @can('admin.view_registrations')
                <x-sidebar-link route="admin.registrations.index" label="Pendaftaran" icon="collection"/>
                @endcan
                @can('admin.scan_ticket')
                <x-sidebar-link route="admin.checkin" label="QR Check-in" icon="qrcode"/>
                @endcan
                @can('admin.manage_ticket')
                <x-sidebar-link route="admin.tickets.index" label="E-Ticket" icon="ticket"/>
                @endcan

                <x-sidebar-section label="Kompetisi"/>
                @can('admin.manage_competition')
                <x-sidebar-link route="admin.competitions.index" label="Lomba" icon="document-text"/>
                @endcan
                @can('admin.view_payments')
                <x-sidebar-link route="admin.payments.index" label="Pembayaran" icon="credit-card"/>
                @endcan

                <x-sidebar-section label="Sistem"/>
                @can('admin.view_activity_logs')
                <x-sidebar-link route="admin.activity-logs.index" label="Riwayat Aktivitas" icon="clock"/>
                @endcan
                <x-sidebar-link route="admin.settings" label="Pengaturan" icon="cog"/>

            {{-- ════════════════ FINANCE ════════════════ --}}
            @elseif($roleName === 'finance')

                <x-sidebar-section label="Menu"/>
                @can('finance.view_dashboard')
                <x-sidebar-link route="finance.dashboard" label="Dashboard" icon="home"/>
                @endcan

                <x-sidebar-section label="Keuangan"/>
                @can('finance.view_payments')
                <x-sidebar-link route="finance.payments.index" label="Pembayaran" icon="credit-card"/>
                @endcan
                @can('finance.view_registrations')
                <x-sidebar-link route="finance.registrations.index" label="Registrasi" icon="collection"/>
                @endcan
                @can('finance.view_reports')
                <x-sidebar-link route="finance.reports" label="Laporan" icon="chart-bar"/>
                @endcan

            {{-- ════════════════ SPONSOR ════════════════ --}}
            @elseif($roleName === 'sponsor')

                <x-sidebar-section label="Menu"/>
                @can('sponsor.view_dashboard')
                <x-sidebar-link route="sponsor.dashboard" label="Dashboard" icon="home"/>
                @endcan

                <x-sidebar-section label="Informasi"/>
                @can('sponsor.view_statistics')
                <x-sidebar-link route="sponsor.statistics" label="Statistik" icon="chart-bar"/>
                @endcan
                @can('sponsor.view_participants')
                <x-sidebar-link route="sponsor.participants" label="Peserta" icon="users"/>
                @endcan
                @can('sponsor.view_event_info')
                <x-sidebar-link route="sponsor.events" label="Info Event" icon="document-text"/>
                @endcan
                @can('sponsor.view_reports')
                <x-sidebar-link route="sponsor.reports" label="Laporan" icon="chart-bar"/>
                @endcan

            @endif

        </nav>

        {{-- User info --}}
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full {{ $accentClass }} flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-bold">{{ $initial }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-white text-sm font-semibold truncate">{{ $user?->name ?? 'User' }}</p>
                    <p class="text-white/50 text-xs truncate">{{ $user?->email ?? '' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout"
                            class="text-white/40 hover:text-white/80 transition-colors p-1 rounded">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── MAIN CONTENT ─────────────────────────────────────────── --}}
    <div class="lg:ml-[260px] min-h-screen min-w-0 flex flex-col">

        {{-- Top bar --}}
        <header class="sticky top-0 z-20 bg-white border-b border-slate-200 shadow-sm">
            <div class="flex items-center justify-between px-4 md:px-6 h-16">
                {{-- Mobile menu --}}
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Brand (mobile) --}}
                <div class="flex items-center gap-2 lg:hidden">
                    <span class="font-bold text-[#0B1040] text-base">BravePay</span>
                </div>

                {{-- Breadcrumb (desktop) --}}
                <div class="hidden lg:flex items-center gap-2 text-sm">
                    <span class="text-slate-400 font-medium">{{ $roleLabel }}</span>
                    <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-slate-700 font-semibold">@yield('page_title', 'Dashboard')</span>
                </div>

                {{-- Right --}}
                <div class="flex items-center gap-3">
                    {{-- Role badge --}}
                    <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ match($roleName) {
                            'superadmin' => 'bg-violet-100 text-violet-700',
                            'finance'    => 'bg-emerald-100 text-emerald-700',
                            'sponsor'    => 'bg-amber-100 text-amber-700',
                            default      => 'bg-blue-100 text-blue-700',
                        } }}">
                        {{ $roleLabel }}
                    </span>

                    {{-- Avatar --}}
                    <div class="w-8 h-8 rounded-full {{ $accentClass }} flex items-center justify-center">
                        <span class="text-white text-xs font-bold">{{ $initial }}</span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 min-w-0 p-4 sm:p-5 md:p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
