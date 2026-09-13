@props([
    'id'          => 1,
    'slug'        => 'basket-competition',
    'name'        => 'Basket Competition',
    'category'    => 'Olahraga',
    'date'        => '20 September 2026',
    'location'    => 'Batam',
    'price'       => 50000,
    'quota'       => 100,
    'registered'  => 45,
    'status'      => 'open',   // open | closed | full | coming_soon
    'unit'        => 'peserta',
    'poster'      => null,
    'event_name'  => 'GEN FEST 2026',
])

@php
$statusLabel = match($status) {
    'open'        => 'Buka',
    'closed'      => 'Ditutup',
    'full'        => 'Penuh',
    'coming_soon' => 'Segera',
    default       => 'Buka',
};
$statusClass = match($status) {
    'open'        => 'badge-active',
    'closed'      => 'badge-gray',
    'full'        => 'badge-error',
    'coming_soon' => 'badge-pending',
    default       => 'badge-active',
};
$unitLabel = ($unit ?? 'peserta') === 'team' ? 'team' : 'peserta';
$quota_pct = $quota > 0 ? min(100, round(($registered / $quota) * 100)) : 0;
$quota_color = $quota_pct >= 90 ? 'bg-red-500' : ($quota_pct >= 70 ? 'bg-amber-500' : 'bg-[#2563EB]');
@endphp

<article class="card overflow-hidden group flex flex-col h-full">
    {{-- Poster --}}
    <div class="relative overflow-hidden aspect-[4/3] bg-gradient-to-br from-[#DBEAFE] to-[#EFF6FF]">
        @if($poster)
            <img
                src="{{ $poster }}"
                alt="{{ $name }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
            >
        @else
            {{-- Placeholder with batik geo --}}
            <div class="absolute inset-0 batik-geo"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                <div class="w-16 h-16 rounded-2xl bg-white/80 shadow-sm flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-[#1E3A8A] font-bold text-sm text-center">{{ $event_name }}</span>
            </div>
        @endif

        {{-- Status badge overlay --}}
        <div class="absolute top-3 left-3">
            <span class="badge {{ $statusClass }} shadow-sm">{{ $statusLabel }}</span>
        </div>

        {{-- Category badge --}}
        <div class="absolute top-3 right-3">
            <span class="badge badge-info shadow-sm">{{ $category }}</span>
        </div>
    </div>

    {{-- Content --}}
    <div class="flex flex-col flex-1 p-5">
        {{-- Event name (small) --}}
        @if($event_name)
        <p class="text-xs font-semibold text-[#64748B] uppercase tracking-widest mb-1">{{ $event_name }}</p>
        @endif

        {{-- Competition name --}}
        <h3 class="font-bold text-[#0B1040] text-base leading-tight mb-3 line-clamp-2 group-hover:text-[#2563EB] transition-colors">
            {{ $name }}
        </h3>

        {{-- Details --}}
        <div class="space-y-2 mb-4 flex-1">
            <div class="flex items-center gap-2 text-[#64748B] text-sm">
                <svg class="w-4 h-4 flex-shrink-0 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="truncate">{{ $date }}</span>
            </div>
            <div class="flex items-center gap-2 text-[#64748B] text-sm">
                <svg class="w-4 h-4 flex-shrink-0 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate">{{ $location }}</span>
            </div>
        </div>

        {{-- Quota bar --}}
        @if($quota > 0)
        <div class="mb-4">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs text-[#64748B]">Kuota tersisa</span>
                <span class="text-xs font-semibold text-[#1E3A8A]">{{ $quota - $registered }} / {{ $quota }}</span>
            </div>
            <div class="w-full h-1.5 bg-[#E2E8F7] rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $quota_color }}" style="width: {{ $quota_pct }}%"></div>
            </div>
        </div>
        @endif

        {{-- Price + CTA --}}
        <div class="flex items-center justify-between gap-3 pt-4 border-t border-[#E2E8F7]">
            <div>
                <p class="text-xs text-[#64748B]">Biaya</p>
                <p class="text-base font-bold text-[#0B1040]">
                    Rp{{ number_format($price, 0, ',', '.') }}
                    <span class="text-xs font-normal text-[#64748B]">/{{ $unitLabel }}</span>
                </p>
            </div>
            @if($status === 'open')
            <a href="{{ route('competitions.show', $slug) }}" class="btn-primary btn-sm flex-shrink-0">
                Daftar
            </a>
            @elseif($status === 'coming_soon')
            <button disabled class="btn-secondary btn-sm flex-shrink-0 opacity-60 cursor-not-allowed">Segera</button>
            @else
            <button disabled class="btn-secondary btn-sm flex-shrink-0 opacity-60 cursor-not-allowed">Tutup</button>
            @endif
        </div>
    </div>
</article>
