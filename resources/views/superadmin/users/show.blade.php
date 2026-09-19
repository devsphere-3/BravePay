@extends('layouts.admin')
@section('title', 'Detail User')
@section('page_title', 'Detail User')
@section('content')
@php use App\Models\User; $user = User::with('role')->findOrFail($id); @endphp
<div class="max-w-2xl"><div class="mb-6"><h1 class="text-2xl font-extrabold text-[#0B1040]">{{ $user->name }}</h1><p class="text-slate-500 text-sm mt-1">Detail akun dan role pengguna.</p></div><div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4"><div><p class="text-xs text-slate-500">Email</p><p class="font-semibold text-[#0B1040]">{{ $user->email }}</p></div><div><p class="text-xs text-slate-500">Role</p><p class="font-semibold text-[#0B1040]">{{ $user->role?->display_name ?? '—' }}</p></div><div><p class="text-xs text-slate-500">Status</p><p class="font-semibold text-[#0B1040]">{{ ucfirst($user->status ?? 'active') }}</p></div></div></div>
@endsection
