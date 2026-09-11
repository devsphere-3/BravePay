@extends('layouts.admin')

@section('title', isset($competition) ? 'Edit Lomba' : 'Tambah Lomba')
@section('page_title', isset($competition) ? 'Edit Lomba' : 'Tambah Lomba')

@section('content')

<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.competitions.index') }}" class="p-2 rounded-xl hover:bg-white border border-slate-200 text-slate-500 hover:text-[#2563EB] transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        </a>
        <h1 class="text-xl font-extrabold text-[#0B1040]">
            {{ isset($competition) ? 'Edit: '.$competition->name : 'Tambah Lomba Baru' }}
        </h1>
    </div>

    <form
        action="{{ isset($competition) ? route('admin.competitions.update', $competition->id) : route('admin.competitions.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-5"
    >
        @csrf
        @if(isset($competition)) @method('PUT') @endif

        {{-- Basic info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Informasi Lomba</h2>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Nama Lomba <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $competition->name ?? '') }}" required class="form-input @error('name') border-red-400 @enderror" placeholder="Basket Competition">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Nama Event (opsional)</label>
                    <input type="text" name="event_name" value="{{ old('event_name', $competition->event_name ?? '') }}" class="form-input" placeholder="GEN FEST 2026">
                </div>
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="4" class="form-input resize-none" placeholder="Deskripsi lomba...">{{ old('description', $competition->description ?? '') }}</textarea>
            </div>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required class="form-select @error('category') border-red-400 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach(['Olahraga','Kreatif','Akademik','Seni & Musik','Teknologi','Fotografi','Teater & Drama','Komunitas'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $competition->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="form-select">
                        @foreach(['open'=>'Buka','closed'=>'Tutup','coming_soon'=>'Segera','full'=>'Penuh'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $competition->status ?? 'open') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Date, location, pricing --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Waktu, Lokasi & Harga</h2>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Tanggal Event <span class="text-red-500">*</span></label>
                    <input type="date" name="event_date" value="{{ old('event_date', isset($competition->event_date) ? \Carbon\Carbon::parse($competition->event_date)->format('Y-m-d') : '') }}" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location', $competition->location ?? '') }}" required class="form-input" placeholder="Batam">
                </div>
                <div>
                    <label class="form-label">Biaya Pendaftaran (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-[#64748B]">Rp</span>
                        <input type="number" name="price" value="{{ old('price', $competition->price ?? '') }}" required min="0" class="form-input pl-9 @error('price') border-red-400 @enderror" placeholder="50000">
                    </div>
                </div>
                <div>
                    <label class="form-label">Kuota (0 = tidak terbatas)</label>
                    <input type="number" name="quota" value="{{ old('quota', $competition->quota ?? 0) }}" min="0" class="form-input" placeholder="100">
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
            <h2 class="font-bold text-[#0B1040] text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Konten</h2>

            <div>
                <label class="form-label">Peraturan</label>
                <textarea name="rules" rows="5" class="form-input resize-none font-mono text-sm" placeholder="1. Peraturan pertama&#10;2. Peraturan kedua">{{ old('rules', $competition->rules ?? '') }}</textarea>
            </div>
            <div>
                <label class="form-label">Persyaratan</label>
                <textarea name="requirements" rows="4" class="form-input resize-none font-mono text-sm" placeholder="- Persyaratan 1&#10;- Persyaratan 2">{{ old('requirements', $competition->requirements ?? '') }}</textarea>
            </div>

            <div>
                <label class="form-label">Poster</label>
                @if(isset($competition) && $competition->poster)
                <div class="mb-3">
                    <img src="{{ asset('storage/'.$competition->poster) }}" class="w-32 h-32 object-cover rounded-xl border border-slate-200" alt="Poster">
                    <p class="text-xs text-slate-400 mt-1">Poster saat ini — upload baru untuk mengganti</p>
                </div>
                @endif
                <input type="file" name="poster" accept="image/*" class="form-input text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:text-xs file:font-semibold hover:file:bg-blue-100">
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('admin.competitions.index') }}" class="btn-secondary flex-1 justify-center">Batal</a>
            <button type="submit" class="btn-primary flex-1 justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ isset($competition) ? 'Simpan Perubahan' : 'Tambah Lomba' }}
            </button>
        </div>
    </form>
</div>

@endsection
