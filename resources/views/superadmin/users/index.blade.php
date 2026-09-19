@extends('layouts.admin')
@section('title', 'Manajemen User')
@section('page_title', 'Semua User')
@section('content')
@php use App\Models\User; $users = User::with('role')->latest()->paginate(20); @endphp
<div class="mb-6 flex items-center justify-between gap-3"><div><h1 class="text-2xl font-extrabold text-[#0B1040]">Manajemen User</h1><p class="text-slate-500 text-sm mt-1">Kelola akun dan role pengguna sistem.</p></div><a href="{{ route('superadmin.users.create') }}" class="btn-primary btn-sm">Tambah User</a></div>
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"><div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-6 py-3 text-left">Nama</th><th class="px-6 py-3 text-left">Email</th><th class="px-6 py-3 text-left">Role</th><th class="px-6 py-3 text-left">Status</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($users as $user)<tr><td class="px-6 py-4 font-semibold text-[#0B1040]"><a class="text-[#2563EB] hover:underline" href="{{ route('superadmin.users.show', $user->id) }}">{{ $user->name }}</a></td><td class="px-6 py-4 text-slate-600">{{ $user->email }}</td><td class="px-6 py-4 text-slate-600">{{ $user->role?->display_name ?? '—' }}</td><td class="px-6 py-4"><span class="text-xs font-semibold">{{ ucfirst($user->status ?? 'active') }}</span></td></tr>@empty<tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada user.</td></tr>@endforelse</tbody></table></div></div>
@endsection
