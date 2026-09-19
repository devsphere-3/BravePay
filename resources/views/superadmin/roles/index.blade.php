@extends('layouts.admin')
@section('title', 'Role dan Permission')
@section('page_title', 'Role & Permission')
@section('content')
@php use App\Models\Role; $roles = Role::withCount('users')->orderByDesc('level')->get(); @endphp
<div class="mb-6"><h1 class="text-2xl font-extrabold text-[#0B1040]">Role & Permission</h1><p class="text-slate-500 text-sm mt-1">Daftar role dan jumlah user yang menggunakannya.</p></div>
<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">@forelse($roles as $role)<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6"><div class="flex items-center justify-between gap-3"><h2 class="font-bold text-[#0B1040]">{{ $role->display_name }}</h2><span class="text-xs font-bold text-violet-600">Level {{ $role->level }}</span></div><p class="text-sm text-slate-500 mt-3">{{ $role->description }}</p><p class="text-xs font-semibold text-slate-500 mt-5">{{ number_format($role->users_count) }} user</p></div>@empty<div class="text-sm text-slate-400">Belum ada role.</div>@endforelse</div>
@endsection
