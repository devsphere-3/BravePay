@extends('layouts.admin')

@section('title', 'Pendaftaran')
@section('page_title', 'Pendaftaran')

@section('content')

@if(session('success'))
<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>
@endif

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-[#0B1040]">Manajemen Pendaftaran</h1>
        <p class="text-slate-500 text-sm mt-0.5">Kelola semua pendaftaran peserta</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.registrations.index') }}" class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 shadow-sm">
    <div class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari order ID, email..." class="form-input text-sm">
        </div>
        <select name="competition_id" class="form-select text-sm w-auto min-w-[160px]" onchange="this.form.submit()">
            <option value="">Semua Lomba</option>
            @foreach($competitions ?? [] as $c)
            <option value="{{ $c->id }}" {{ request('competition_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select text-sm w-auto min-w-[140px]" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="paid"     {{ request('status') === 'paid'     ? 'selected' : '' }}>Lunas</option>
            <option value="failed"   {{ request('status') === 'failed'   ? 'selected' : '' }}>Gagal</option>
            <option value="expired"  {{ request('status') === 'expired'  ? 'selected' : '' }}>Kadaluarsa</option>
        </select>
        <button type="submit" class="btn-primary btn-sm">Filter</button>
        @if(request()->hasAny(['q','competition_id','status']))
        <a href="{{ route('admin.registrations.index') }}" class="btn-secondary btn-sm">Reset</a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    <th class="px-5 py-3.5 text-left">Order ID</th>
                    <th class="px-5 py-3.5 text-left">Kontak</th>
                    <th class="px-5 py-3.5 text-left hidden md:table-cell">Lomba</th>
                    <th class="px-5 py-3.5 text-center">Peserta</th>
                    <th class="px-5 py-3.5 text-left">Pembayaran</th>
                    <th class="px-5 py-3.5 text-right">Total</th>
                    <th class="px-5 py-3.5 text-left hidden lg:table-cell">Tanggal</th>
                    <th class="px-5 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($registrations ?? [] as $reg)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.registrations.show', $reg->order_code) }}" class="font-mono text-xs font-bold text-[#2563EB] hover:underline">{{ $reg->order_code ?? '—' }}</a>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-[#0B1040] text-xs">{{ $reg->email ?? '—' }}</p>
                        <p class="text-xs text-slate-400">{{ $reg->phone ?? '—' }}</p>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="text-xs text-slate-600 truncate block max-w-[140px]">{{ optional($reg->competition)->name ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="badge badge-info">{{ $reg->participant_count ?? $reg->total_participants ?? count($reg->participants ?? []) ?? 0 }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <x-status-badge :status="optional($reg->payment)->status ?? ($reg->status ?? 'pending')"/>
                    </td>
                    <td class="px-5 py-4 text-right font-bold text-[#0B1040]">
                        Rp{{ number_format((int) ($reg->total_amount ?? 0), 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs text-slate-400">
                        {{ !empty($reg->created_at) ? \Carbon\Carbon::parse($reg->created_at)->format('d M Y') : '' }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('admin.registrations.show', $reg->order_code) }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold text-[#2563EB] hover:underline">
                            Detail
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <form action="{{ route('admin.registrations.destroy', $reg->order_code) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pendaftar ini? Data yang dihapus tidak dapat dikembalikan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-2 inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-800" title="Hapus pendaftar">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-sm font-semibold text-slate-400">Belum ada pendaftaran.</p>
                            <p class="text-xs text-slate-300">Data akan muncul setelah peserta mendaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(isset($registrations) && is_object($registrations) && method_exists($registrations, 'hasPages') && $registrations->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
        {{ $registrations->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection
