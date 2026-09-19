@extends('layouts.admin')

@section('title', 'Riwayat Aktivitas')
@section('page_title', 'Riwayat Aktivitas')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-extrabold text-[#0B1040]">Riwayat Aktivitas Admin</h1>
    <p class="text-slate-500 text-sm mt-0.5">Daftar aktivitas yang terjadi di dashboard admin.</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    <th class="px-5 py-3.5 text-left">Waktu</th>
                    <th class="px-5 py-3.5 text-left">Admin</th>
                    <th class="px-5 py-3.5 text-left">Aktivitas</th>
                    <th class="px-5 py-3.5 text-left">Route</th>
                    <th class="px-5 py-3.5 text-left">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($activities as $activity)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4 text-xs text-slate-500 whitespace-nowrap">{{ $activity->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-5 py-4"><p class="font-semibold text-[#0B1040] text-xs">{{ $activity->user->email ?? '—' }}</p></td>
                    <td class="px-5 py-4"><span class="badge badge-info uppercase">{{ $activity->action }}</span><p class="text-xs text-slate-500 mt-1">{{ $activity->description }}</p></td>
                    <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $activity->route_name ?? '—' }}</td>
                    <td class="px-5 py-4 text-xs text-slate-400">{{ $activity->ip_address ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($activities->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $activities->links() }}</div>
    @endif
</div>
@endsection