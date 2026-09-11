<nav class="navbar" x-data="navbar()" @keydown.escape.window="close()">
    <div class="section-container">
        <div class="flex items-center justify-between h-[72px]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0" @click="close()">
                <div class="w-9 h-9 rounded-xl bg-[#2563EB] flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                    </svg>
                </div>
                <span class="text-[#0B1040] font-extrabold text-xl tracking-tight">BravePay</span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                          {{ request()->routeIs('home') ? 'text-[#2563EB] bg-[#EFF6FF]' : 'text-[#1E3A8A] hover:text-[#2563EB] hover:bg-[#EFF6FF]' }}">
                    Beranda
                </a>
                <a href="{{ route('competitions.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                          {{ request()->routeIs('competitions.*') ? 'text-[#2563EB] bg-[#EFF6FF]' : 'text-[#1E3A8A] hover:text-[#2563EB] hover:bg-[#EFF6FF]' }}">
                    Event & Lomba
                </a>
                <a href="{{ route('home') }}#how-it-works"
                   class="px-4 py-2 rounded-lg text-sm font-semibold text-[#1E3A8A] hover:text-[#2563EB] hover:bg-[#EFF6FF] transition-colors">
                    Cara Kerja
                </a>
                <a href="{{ route('home') }}#faq"
                   class="px-4 py-2 rounded-lg text-sm font-semibold text-[#1E3A8A] hover:text-[#2563EB] hover:bg-[#EFF6FF] transition-colors">
                    FAQ
                </a>
            </div>

            {{-- Desktop CTA --}}
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('competitions.index') }}" class="btn-primary btn-sm">
                    Daftar Sekarang
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            {{-- Mobile menu button --}}
            <button
                class="md:hidden p-2 rounded-lg text-[#1E3A8A] hover:bg-[#EFF6FF] transition-colors"
                @click="toggle()"
                aria-label="Buka menu"
                :aria-expanded="open.toString()"
            >
                <svg x-show="!open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-t border-[#E2E8F7] bg-white"
        x-cloak
    >
        <div class="section-container py-4 flex flex-col gap-1">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors
                      {{ request()->routeIs('home') ? 'text-[#2563EB] bg-[#EFF6FF]' : 'text-[#1E3A8A] hover:bg-[#F1F5FE]' }}"
               @click="close()">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <a href="{{ route('competitions.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors
                      {{ request()->routeIs('competitions.*') ? 'text-[#2563EB] bg-[#EFF6FF]' : 'text-[#1E3A8A] hover:bg-[#F1F5FE]' }}"
               @click="close()">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Event & Lomba
            </a>
            <a href="{{ route('home') }}#how-it-works"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-[#1E3A8A] hover:bg-[#F1F5FE] transition-colors"
               @click="close()">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Cara Kerja
            </a>
            <a href="{{ route('home') }}#faq"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-[#1E3A8A] hover:bg-[#F1F5FE] transition-colors"
               @click="close()">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                FAQ
            </a>

            <div class="pt-2 border-t border-[#E2E8F7] mt-2">
                <a href="{{ route('competitions.index') }}" class="btn-primary w-full justify-center" @click="close()">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</nav>
