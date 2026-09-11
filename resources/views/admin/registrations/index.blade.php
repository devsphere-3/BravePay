@extends('layouts.admin')

@section('title', 'Pendaftaran')
@section('page_title', 'Pendaftaran')

@section('content')

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
                        <a href="{{ route('admin.registrations.show', $reg->id) }}" class="font-mono text-xs font-bold text-[#2563EB] hover:underline">{{ $reg->order_code }}</a>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-[#0B1040] text-xs">{{ $reg->email }}</p>
                        <p class="text-xs text-slate-400">{{ $reg->phone }}</p>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="text-xs text-slate-600 truncate block max-w-[140px]">{{ $reg->competition->name ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="badge badge-info">{{ $reg->total_participants }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <x-status-badge :status="$reg->payment ? $reg->payment->status : 'pending'"/>
                    </td>
                    <td class="px-5 py-4 text-right font-bold text-[#0B1040]">
                        Rp{{ number_format($reg->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs text-slate-400">
                        {{ $reg->created_at ? $reg->created_at->format('d M Y') : '' }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('admin.registrations.show', $reg->id) }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold text-[#2563EB] hover:underline">
                            Detail
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                {{-- Demo rows --}}
                @foreach([
                    ['BRV-20260911-0001','wahyu@email.com','+62812','Basket Competition',3,'paid',150000,'11 Sep 2026'],
                    ['BRV-20260911-0002','budi@email.com','+62813','Futsal Championship',1,'pending',75000,'11 Sep 2026'],
                    ['BRV-20260911-0003','sari@email.com','+62814','Desain Grafis',1,'paid',35000,'10 Sep 2026'],
                    ['BRV-20260911-0004','eko@email.com','+62815','Basket Competition',2,'failed',100000,'10 Sep 2026'],
                    ['BRV-20260911-0005','dina@email.com','+62816','Futsal Championship',2,'paid',150000,'9 Sep 2026'],
                ] as $d)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4"><span class="font-mono text-xs font-bold text-[#2563EB]">{{ $d[0] }}</span></td>
                    <td class="px-5 py-4"><p class="font-semibold text-[#0B1040] text-xs">{{ $d[1] }}</p><p class="text-xs text-slate-400">{{ $d[2] }}</p></td>
                    <td class="px-5 py-4 hidden md:table-cell"><span class="text-xs text-slate-600">{{ $d[3] }}</span></td>
                    <td class="px-5 py-4 text-center"><span class="badge badge-info">{{ $d[4] }}</span></td>
                    <td class="px-5 py-4"><x-status-badge :status="$d[5]"/></td>
                    <td class="px-5 py-4 text-right font-bold text-[#0B1040] text-xs">Rp{{ number_format($d[6],0,',','.') }}</td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs text-slate-400">{{ $d[7] }}</td>
                    <td class="px-5 py-4 text-center"><span class="inline-flex items-center gap-1 text-xs font-semibold text-[#2563EB]">Detail <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></span></td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(isset($registrations) && $registrations->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
        {{ $registrations->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection
