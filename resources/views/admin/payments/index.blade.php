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
    <div class="grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_auto_auto_auto] gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari order ID, payment ID..." class="form-input text-sm min-w-0">
        <select name="status" class="form-select text-sm w-full sm:w-auto min-w-0 sm:min-w-[140px]" onchange="this.form.submit()">
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
                    <th class="px-5 py-3.5 text-left hidden md:table-cell">Email</th>
                    <th class="px-5 py-3.5 text-left hidden md:table-cell">Lomba</th>
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
                        <span class="text-xs text-slate-500">{{ $payment->email ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="text-xs text-slate-600">{{ $payment->competition_name ?? '—' }}</span>
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
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                            </svg>
                            <p class="text-sm font-semibold text-slate-400">Belum ada transaksi pembayaran.</p>
                            <p class="text-xs text-slate-300">Transaksi akan muncul setelah peserta menyelesaikan pembayaran.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($payments) && is_object($payments) && method_exists($payments, 'hasPages') && $payments->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $payments->appends(request()->query())->links() }}</div>
    @endif
</div>

@endsection
