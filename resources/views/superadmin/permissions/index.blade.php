@extends('layouts.admin')
@section('title', 'Permission Sistem')
@section('page_title', 'Permission Sistem')
@section('content')
@php use App\Models\Permission; $permissions = Permission::orderBy('group_name')->orderBy('name')->get()->groupBy('group_name'); @endphp
<div class="mb-6"><h1 class="text-2xl font-extrabold text-[#0B1040]">Permission Sistem</h1><p class="text-slate-500 text-sm mt-1">Daftar permission berdasarkan kelompok akses.</p></div>
<div class="space-y-5">@forelse($permissions as $group => $items)<section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6"><h2 class="font-bold text-[#0B1040] capitalize mb-4">{{ $group }}</h2><div class="grid md:grid-cols-2 gap-3">@foreach($items as $permission)<div class="rounded-xl bg-slate-50 p-3"><p class="text-sm font-semibold text-[#1E3A8A]">{{ $permission->display_name }}</p><p class="text-xs text-slate-400 mt-1">{{ $permission->name }}</p></div>@endforeach</div></section>@empty<div class="text-sm text-slate-400">Belum ada permission.</div>@endforelse</div>
@endsection
