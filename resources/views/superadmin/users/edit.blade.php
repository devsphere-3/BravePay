@extends('layouts.admin')
@section('title', 'Edit User')
@section('page_title', 'Edit User')
@section('content')
@php use App\Models\User; $user = User::with('role')->findOrFail($id); @endphp
<div class="max-w-2xl"><div class="mb-6"><h1 class="text-2xl font-extrabold text-[#0B1040]">Edit User</h1><p class="text-slate-500 text-sm mt-1">Pengaturan akun {{ $user->name }}.</p></div><div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm"><p class="text-sm text-slate-500">Form edit user untuk akun <strong>{{ $user->email }}</strong> siap dilanjutkan ke endpoint update.</p></div></div>
@endsection
