@extends('layouts.admin')

@section('title', 'Pembayaran')
@section('page_title', 'Pembayaran')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-[#0B1040]">Manajemen Pembayaran</h1>
        <p class="text-slate-500 text-sm mt-0.5">Monitor dan kelola semua transaksi pembayaran</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.payments.index') }}" class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 shadow-sm">
    <div class="flex flex-wrap gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari order ID, payment ID..." class="form-input text-sm flex-1 min-w-[180px]">
        <select name="status" class="form-select text-sm w-auto min-w-[140px]" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['pending'=>'Pending','paid'=>'Lunas','failed'=>'Gagal','expired'=>'Kadaluarsa','refunded'=>'Refund'] as $val => $label)
            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary btn-sm">Filter</button>
        @if(request()->hasAny(['q','status']))
        <a href="{{ route('admin.payments.index') }}" class="btn-secondary btn-sm">Reset</a>
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
                    <th class="px-5 py-3.5 text-left hidden md:table-cell">Payment ID</th>
                    <th class="px-5 py-3.5 text-left hidden lg:table-cell">Metode</th>
                    <th class="px-5 py-3.5 text-right">Jumlah</th>
                    <th class="px-5 py-3.5 text-left">Status</th>
                    <th class="px-5 py-3.5 text-left hidden lg:table-cell">Dibayar Pada</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($payments ?? [] as $payment)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.registrations.show', $payment->registration_id) }}" class="font-mono text-xs font-bold text-[#2563EB] hover:underline">
                            {{ $payment->registration->order_code ?? '—' }}
                        </a>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="font-mono text-xs text-slate-500">{{ $payment->payment_reference ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        <span class="badge badge-info text-xs">{{ $payment->payment_method ?? 'QRIS' }}</span>
                    </td>
                    <td class="px-5 py-4 text-right font-bold text-[#0B1040]">
                        Rp{{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4"><x-status-badge :status="$payment->status"/></td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs text-slate-400">
                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') : '—' }}
                    </td>
                </tr>
                @empty
                {{-- Demo rows --}}
                @foreach([
                    ['BRV-20260911-0001','PG-001','QRIS',150000,'paid','11 Sep 2026 09:14'],
                    ['BRV-20260911-0002','PG-002','QRIS',75000,'pending','—'],
                    ['BRV-20260911-0003','PG-003','QRIS',35000,'paid','10 Sep 2026 14:22'],
                    ['BRV-20260911-0004','PG-004','QRIS',100000,'failed','—'],
                    ['BRV-20260911-0005','PG-005','QRIS',150000,'paid','9 Sep 2026 16:05'],
                    ['BRV-20260911-0006','PG-006','QRIS',50000,'expired','—'],
                ] as $d)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4"><span class="font-mono text-xs font-bold text-[#2563EB]">{{ $d[0] }}</span></td>
                    <td class="px-5 py-4 hidden md:table-cell"><span class="font-mono text-xs text-slate-500">{{ $d[1] }}</span></td>
                    <td class="px-5 py-4 hidden lg:table-cell"><span class="badge badge-info text-xs">{{ $d[2] }}</span></td>
                    <td class="px-5 py-4 text-right font-bold text-[#0B1040] text-xs">Rp{{ number_format($d[3],0,',','.') }}</td>
                    <td class="px-5 py-4"><x-status-badge :status="$d[4]"/></td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs text-slate-400">{{ $d[5] }}</td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($payments) && $payments->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $payments->appends(request()->query())->links() }}</div>
    @endif
</div>

@endsection
