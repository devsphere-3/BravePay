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
    <div class="w-full">
        <div class="mb-6">
            <span class="text-[#64748B] text-sm font-medium">
                Menampilkan <strong class="text-[#0B1040]">{{ isset($competitions) ? $competitions->total() : 3 }}</strong> event
            </span>
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
                        :unit="$c->unit ?? 'peserta'"
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
                        ['name'=>'Basket Competition','cat'=>'Olahraga','date'=>'20 Sep 2026','loc'=>'Harbour Bay, Jodoh River, Batu Ampar, Batam City, Riau Islands','price'=>50000,'status'=>'open','event'=>'GEN FEST 2026','q'=>80],
                        ['name'=>'Futsal Championship','cat'=>'Olahraga','date'=>'5 Okt 2026','loc'=>'Harbour Bay, Jodoh River, Batu Ampar, Batam City, Riau Islands','price'=>75000,'status'=>'open','event'=>'YOUTH SPORT FEST','q'=>45],
                        ['name'=>'Desain Grafis','cat'=>'Kreatif','date'=>'12 Okt 2026','loc'=>'Online','price'=>35000,'status'=>'coming_soon','event'=>'CREATIVE FEST','q'=>0],
                        ['name'=>'Tari Tradisional','cat'=>'Seni & Musik','date'=>'8 Okt 2026','loc'=>'Harbour Bay, Jodoh River, Batu Ampar, Batam City, Riau Islands','price'=>40000,'status'=>'open','event'=>'BUDAYA FEST','q'=>30],
                        ['name'=>'Web Development','cat'=>'Teknologi','date'=>'15 Nov 2026','loc'=>'Online','price'=>45000,'status'=>'open','event'=>'TECH COMP','q'=>60],
                        ['name'=>'Fotografi Alam','cat'=>'Fotografi','date'=>'1 Nov 2026','loc'=>'Harbour Bay, Jodoh River, Batu Ampar, Batam City, Riau Islands','price'=>60000,'status'=>'full','event'=>'PHOTO FEST','q'=>100],
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
