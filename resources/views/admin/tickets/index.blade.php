@extends('layouts.admin')

@section('title', 'E-Ticket')
@section('page_title', 'Manajemen E-Ticket')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-[#0B1040]">Manajemen E-Ticket</h1>
        <p class="text-slate-500 text-sm mt-0.5">Kelola semua e-ticket peserta</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.tickets.index') }}" class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 shadow-sm">
    <div class="flex flex-wrap gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kode tiket, nama peserta..." class="form-input text-sm flex-1 min-w-[180px]">
        <select name="status" class="form-select text-sm w-auto min-w-[130px]" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Aktif</option>
            <option value="used"      {{ request('status') === 'used'      ? 'selected' : '' }}>Digunakan</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="btn-primary btn-sm">Filter</button>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    <th class="px-5 py-3.5 text-left">Kode Tiket</th>
                    <th class="px-5 py-3.5 text-left">Peserta</th>
                    <th class="px-5 py-3.5 text-left hidden md:table-cell">Lomba</th>
                    <th class="px-5 py-3.5 text-left">Status</th>
                    <th class="px-5 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tickets ?? [] as $t)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <span class="font-mono text-xs font-bold text-[#1D4ED8]">{{ $t->ticket_code }}</span>
                    </td>
                    <td class="px-5 py-4 font-semibold text-[#0B1040] text-sm">{{ $t->participant->name ?? '—' }}</td>
                    <td class="px-5 py-4 hidden md:table-cell text-xs text-slate-600">{{ $t->registration->competition->name ?? '—' }}</td>
                    <td class="px-5 py-4"><x-status-badge :status="$t->status"/></td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('ticket.show', $t->ticket_code) }}" target="_blank"
                               class="p-1.5 rounded-lg text-slate-400 hover:text-[#2563EB] hover:bg-blue-50 transition-colors" title="Lihat">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('ticket.download', $t->ticket_code) }}"
                               class="p-1.5 rounded-lg text-slate-400 hover:text-green-600 hover:bg-green-50 transition-colors" title="Download">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            <form action="{{ route('admin.tickets.resend', $t->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Kirim ulang">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                @foreach([
                    ['BRV-TKT-A8F92K','Wahyu Perwira','Basket Competition','active'],
                    ['BRV-TKT-X92KD1','Budi Santoso','Basket Competition','used'],
                    ['BRV-TKT-P7A21M','Sari Dewi','Futsal Championship','active'],
                    ['BRV-TKT-K31ZP9','Eko Prasetyo','Basket Competition','active'],
                ] as $d)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4"><span class="font-mono text-xs font-bold text-[#1D4ED8]">{{ $d[0] }}</span></td>
                    <td class="px-5 py-4 font-semibold text-[#0B1040] text-sm">{{ $d[1] }}</td>
                    <td class="px-5 py-4 hidden md:table-cell text-xs text-slate-600">{{ $d[2] }}</td>
                    <td class="px-5 py-4"><x-status-badge :status="$d[3]"/></td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#2563EB] hover:bg-blue-50 transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                            <button class="p-1.5 rounded-lg text-slate-400 hover:text-green-600 hover:bg-green-50 transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></button>
                            <button class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($tickets) && $tickets->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $tickets->appends(request()->query())->links() }}</div>
    @endif
</div>

@endsection
