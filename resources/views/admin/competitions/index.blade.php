@extends('layouts.admin')

@section('title', 'Lomba')
@section('page_title', 'Lomba')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-[#0B1040]">Manajemen Lomba</h1>
        <p class="text-slate-500 text-sm mt-0.5">Kelola semua event dan lomba</p>
    </div>
    <a href="{{ route('admin.competitions.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Lomba
    </a>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    <th class="px-5 py-3.5 text-left">Lomba</th>
                    <th class="px-5 py-3.5 text-left hidden md:table-cell">Kategori</th>
                    <th class="px-5 py-3.5 text-left hidden lg:table-cell">Tanggal</th>
                    <th class="px-5 py-3.5 text-right hidden sm:table-cell">Harga</th>
                    <th class="px-5 py-3.5 text-center hidden md:table-cell">Kuota</th>
                    <th class="px-5 py-3.5 text-left">Status</th>
                    <th class="px-5 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($competitions ?? [] as $c)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-[#0B1040]">{{ $c->name }}</p>
                        <p class="text-xs text-slate-400">{{ $c->slug }}</p>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell"><span class="badge badge-info">{{ $c->category }}</span></td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs text-slate-500">
                        {{ $c->event_date ? \Carbon\Carbon::parse($c->event_date)->format('d M Y') : '—' }}
                    </td>
                    <td class="px-5 py-4 text-right font-semibold text-[#0B1040] hidden sm:table-cell">Rp{{ number_format($c->price,0,',','.') }}</td>
                    <td class="px-5 py-4 text-center hidden md:table-cell">
                        <span class="text-xs font-bold text-[#0B1040]">{{ $c->registrations_count ?? 0 }}</span>
                        <span class="text-xs text-slate-400">/{{ $c->quota ?? '∞' }}</span>
                    </td>
                    <td class="px-5 py-4"><x-status-badge :status="$c->status"/></td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.competitions.edit', $c->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-[#2563EB] hover:bg-blue-50 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.competitions.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus lomba ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                @foreach([
                    ['GEN FEST 2026 — Basket Competition','basket-competition','Olahraga','20 Sep 2026',50000,100,62,'open'],
                    ['YOUTH SPORT — Futsal Championship','futsal-championship','Olahraga','5 Okt 2026',75000,80,30,'open'],
                    ['CREATIVE FEST — Desain Grafis','desain-grafis','Kreatif','12 Okt 2026',35000,50,0,'coming_soon'],
                ] as $d)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4"><p class="font-semibold text-[#0B1040] text-sm">{{ $d[0] }}</p><p class="text-xs text-slate-400">{{ $d[1] }}</p></td>
                    <td class="px-5 py-4 hidden md:table-cell"><span class="badge badge-info">{{ $d[2] }}</span></td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs text-slate-500">{{ $d[3] }}</td>
                    <td class="px-5 py-4 text-right font-semibold text-[#0B1040] hidden sm:table-cell text-xs">Rp{{ number_format($d[4],0,',','.') }}</td>
                    <td class="px-5 py-4 text-center hidden md:table-cell"><span class="text-xs font-bold text-[#0B1040]">{{ $d[5] }}</span><span class="text-xs text-slate-400">/{{ $d[6] }}</span></td>
                    <td class="px-5 py-4"><x-status-badge :status="$d[7]"/></td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#2563EB] hover:bg-blue-50 transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                            <button class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
