@props(['current' => 1])

@php
$steps = [
    1 => 'Data',
    2 => 'Review',
    3 => 'Pembayaran',
    4 => 'E-Ticket',
];
@endphp

<div class="flex items-center justify-center gap-0 w-full max-w-lg mx-auto">
    @foreach($steps as $num => $label)
        {{-- Step --}}
        <div class="flex flex-col items-center">
            <div class="step-circle
                @if($num < $current) completed
                @elseif($num === $current) active
                @else inactive
                @endif
            ">
                @if($num < $current)
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    {{ $num }}
                @endif
            </div>
            <span class="step-label mt-1.5
                @if($num < $current) text-[#10B981]
                @elseif($num === $current) text-[#2563EB]
                @else text-[#94A3B8]
                @endif
                hidden sm:block
            ">{{ $label }}</span>
        </div>

        {{-- Connector (not after last) --}}
        @if(!$loop->last)
        <div class="step-connector mx-1 sm:mx-2 -mt-5 sm:-mt-6 {{ $num < $current ? 'done' : '' }}"></div>
        @endif
    @endforeach
</div>
