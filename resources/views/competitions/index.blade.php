@extends('layouts.app')

@section('title', 'Event & Lomba')
@section('meta_description', 'Temukan dan daftar event serta lomba terbaik di Indonesia. Filter berdasarkan kategori, tanggal, dan lokasi.')

@section('content')

{{-- Page Header --}}
<div class="relative overflow-hidden bg-gradient-to-br from-[#EFF6FF] via-[#DBEAFE] to-[#EFF6FF] py-14">
    <div class="absolute inset-0 batik-kawung pointer-events-none"></div>
    <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-blue-200/40 blur-3xl pointer-events-none"></div>
    <div class="section-container relative z-10">
        <div class="flex items-center gap-2 text-sm text-[#64748B] mb-4">
            <a href="{{ route('home') }}" class="hover:text-[#2563EB] transition-colors">Beranda</a>
            <svg class="w-4 h-4 text-[#CBD5E1]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-[#1E3A8A] font-semibold">Event & Lomba</span>
        </div>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-[#0B1040] mb-3">Event & Lomba</h1>
        <p class="text-[#475569] text-lg max-w-xl">
            Temukan event dan lomba yang sesuai untukmu. Daftar sekarang tanpa perlu akun.
        </p>
    </div>
</div>

{{-- Main Content --}}
<div class="section-container py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ── Sidebar Filters ─── --}}
        <aside class="lg:w-64 flex-shrink-0">
            <form method="GET" action="{{ route('competitions.index') }}" id="filter-form">
                {{-- Search --}}
                <div class="card p-5 mb-5">
                    <h3 class="font-bold text-[#0B1040] text-sm mb-3 uppercase tracking-wide">Cari</h3>
                    <div class="relative">
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Nama event..."
                            class="form-input pr-10 text-sm"
                        >
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Category filter --}}
                <div class="card p-5 mb-5">
                    <h3 class="font-bold text-[#0B1040] text-sm mb-3 uppercase tracking-wide">Kategori</h3>
                    <div class="space-y-2">
                        @foreach(['Semua','Olahraga','Kreatif','Akademik','Seni & Musik','Teknologi','Fotografi','Komunitas'] as $cat)
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input
                                type="radio"
                                name="category"
                                value="{{ $cat === 'Semua' ? '' : $cat }}"
                                {{ request('category', '') === ($cat === 'Semua' ? '' : $cat) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#2563EB] border-[#CBD5E1] rounded-full cursor-pointer focus:ring-[#BFDBFE]"
                                onchange="document.getElementById('filter-form').submit()"
                            >
                            <span class="text-sm text-[#475569] group-hover:text-[#1E3A8A] transition-colors">{{ $cat }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Status filter --}}
                <div class="card p-5 mb-5">
                    <h3 class="font-bold text-[#0B1040] text-sm mb-3 uppercase tracking-wide">Status</h3>
                    <div class="space-y-2">
                        @foreach([''=>'Semua','open'=>'Pendaftaran Buka','coming_soon'=>'Segera Buka','full'=>'Penuh','closed'=>'Ditutup'] as $val => $label)
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input
                                type="radio"
                                name="status"
                                value="{{ $val }}"
                                {{ request('status', '') === $val ? 'checked' : '' }}
                                class="w-4 h-4 text-[#2563EB] border-[#CBD5E1] cursor-pointer focus:ring-[#BFDBFE]"
                                onchange="document.getElementById('filter-form').submit()"
                            >
                            <span class="text-sm text-[#475569] group-hover:text-[#1E3A8A] transition-colors">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Sort --}}
                <div class="card p-5">
                    <h3 class="font-bold text-[#0B1040] text-sm mb-3 uppercase tracking-wide">Urutkan</h3>
                    <select
                        name="sort"
                        class="form-select text-sm"
                        onchange="document.getElementById('filter-form').submit()"
                    >
                        <option value="latest" {{ request('sort','latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga: Murah dulu</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga: Mahal dulu</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama A–Z</option>
                    </select>
                </div>

                {{-- Reset --}}
                @if(request()->hasAny(['q','category','status','sort']))
                <a href="{{ route('competitions.index') }}" class="btn-secondary w-full justify-center text-sm mt-4 block text-center">
                    Reset Filter
                </a>
                @endif
            </form>
        </aside>

        {{-- ── Results ─── --}}
        <div class="flex-1 min-w-0">
            {{-- Active filter chips + result count --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[#64748B] text-sm font-medium">
                        Menampilkan <strong class="text-[#0B1040]">{{ isset($competitions) ? $competitions->total() : 3 }}</strong> event
                    </span>
                    @if(request('q'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EFF6FF] border border-[#BFDBFE] text-xs font-semibold text-[#2563EB]">
                            "{{ request('q') }}"
                            <a href="{{ request()->fullUrlWithoutQuery('q') }}" class="hover:text-red-500">×</a>
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EFF6FF] border border-[#BFDBFE] text-xs font-semibold text-[#2563EB]">
                            {{ request('category') }}
                            <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="hover:text-red-500">×</a>
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EFF6FF] border border-[#BFDBFE] text-xs font-semibold text-[#2563EB]">
                            {{ ucfirst(str_replace('_',' ',request('status'))) }}
                            <a href="{{ request()->fullUrlWithoutQuery('status') }}" class="hover:text-red-500">×</a>
                        </span>
                    @endif
                </div>

                {{-- Mobile filter toggle --}}
                <button class="lg:hidden btn-secondary btn-sm" onclick="document.querySelector('aside').classList.toggle('hidden')">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
            </div>

            {{-- Grid --}}
            @if(isset($competitions) && $competitions->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                    @foreach($competitions as $c)
                    <x-competition-card
                        :id="$c->id"
                        :slug="$c->slug"
                        :name="$c->name"
                        :category="$c->category"
                        :date="$c->event_date ? \Carbon\Carbon::parse($c->event_date)->translatedFormat('d F Y') : 'TBA'"
                        :location="$c->location"
                        :price="$c->price"
                        :quota="$c->quota ?? 0"
                        :registered="$c->registrations_count ?? 0"
                        :status="$c->status"
                        :poster="$c->poster ? asset('storage/'.$c->poster) : null"
                        :event_name="$c->event_name ?? ''"
                    />
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($competitions->hasPages())
                <div class="flex justify-center">
                    {{ $competitions->appends(request()->query())->links() }}
                </div>
                @endif

            @elseif(!isset($competitions))
                {{-- Demo state --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach([
                        ['name'=>'Basket Competition','cat'=>'Olahraga','date'=>'20 Sep 2026','loc'=>'Batam','price'=>50000,'status'=>'open','event'=>'GEN FEST 2026','q'=>80],
                        ['name'=>'Futsal Championship','cat'=>'Olahraga','date'=>'5 Okt 2026','loc'=>'Batam','price'=>75000,'status'=>'open','event'=>'YOUTH SPORT FEST','q'=>45],
                        ['name'=>'Desain Grafis','cat'=>'Kreatif','date'=>'12 Okt 2026','loc'=>'Online','price'=>35000,'status'=>'coming_soon','event'=>'CREATIVE FEST','q'=>0],
                        ['name'=>'Tari Tradisional','cat'=>'Seni & Musik','date'=>'8 Okt 2026','loc'=>'Batam','price'=>40000,'status'=>'open','event'=>'BUDAYA FEST','q'=>30],
                        ['name'=>'Web Development','cat'=>'Teknologi','date'=>'15 Nov 2026','loc'=>'Online','price'=>45000,'status'=>'open','event'=>'TECH COMP','q'=>60],
                        ['name'=>'Fotografi Alam','cat'=>'Fotografi','date'=>'1 Nov 2026','loc'=>'Batam','price'=>60000,'status'=>'full','event'=>'PHOTO FEST','q'=>100],
                    ] as $i => $demo)
                    <x-competition-card
                        :name="$demo['name']"
                        :category="$demo['cat']"
                        :date="$demo['date']"
                        :location="$demo['loc']"
                        :price="$demo['price']"
                        :quota="100"
                        :registered="$demo['q']"
                        :status="$demo['status']"
                        :event_name="$demo['event']"
                    />
                    @endforeach
                </div>
            @else
                <x-empty-state
                    title="Tidak Ada Event"
                    message="Belum ada event yang sesuai dengan filter kamu. Coba ubah filter atau cari dengan kata kunci lain."
                    icon="search"
                    :action="true"
                    actionText="Lihat Semua Event"
                    :actionUrl="route('competitions.index')"
                />
            @endif
        </div>
    </div>
</div>

@endsection
