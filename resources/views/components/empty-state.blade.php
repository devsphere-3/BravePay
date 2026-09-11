@props([
    'title'      => 'Tidak Ada Data',
    'message'    => 'Belum ada data yang tersedia saat ini.',
    'icon'       => 'search',
    'action'     => null,
    'actionText' => 'Lihat Semua',
    'actionUrl'  => '#',
])

@php
$icons = [
    'search'     => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
    'event'      => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    'ticket'     => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
    'payment'    => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
];
$path = $icons[$icon] ?? $icons['search'];
@endphp

<div class="flex flex-col items-center justify-center py-16 px-6 text-center">
    {{-- Illustration --}}
    <div class="relative mb-6">
        <div class="w-20 h-20 rounded-3xl bg-[#EFF6FF] flex items-center justify-center">
            <svg class="w-9 h-9 text-[#93C5FD]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
            </svg>
        </div>
        {{-- Batik dots decoration --}}
        <div class="absolute -top-2 -right-2 flex gap-1">
            <div class="w-2 h-2 rounded-full bg-[#BFDBFE]"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-[#93C5FD] mt-0.5"></div>
        </div>
    </div>

    <h3 class="text-[#1E3A8A] font-bold text-lg mb-2">{{ $title }}</h3>
    <p class="text-[#64748B] text-sm max-w-xs leading-relaxed">{{ $message }}</p>

    @if($action)
    <a href="{{ $actionUrl }}" class="mt-6 btn-primary btn-sm">{{ $actionText }}</a>
    @endif
</div>
