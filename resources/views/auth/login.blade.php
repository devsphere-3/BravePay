<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — BravePay</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen bg-[#F0F4FF] flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-[#0B1040] flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-[#0B1040] leading-none">BravePay</h1>
                    <p class="text-[#64748B] text-sm mt-1">Masuk ke akun Anda</p>
                </div>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-7">

            {{-- Session / Rate limit errors --}}
            @if(session('error'))
                <div class="flex items-start gap-2.5 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm mb-5">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="flex items-start gap-2.5 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm mb-5">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-[#334155] mb-1.5" for="email">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="nama@bravepay.id"
                        class="w-full px-3.5 py-2.5 text-sm rounded-xl border transition-colors outline-none
                               {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-2 focus:ring-red-100' : 'border-slate-300 bg-white focus:border-[#2563EB] focus:ring-2 focus:ring-blue-100' }}"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div x-data="{ show: false }">
                    <label class="block text-sm font-semibold text-[#334155] mb-1.5" for="password">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full px-3.5 py-2.5 pr-10 text-sm rounded-xl border transition-colors outline-none
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-2 focus:ring-red-100' : 'border-slate-300 bg-white focus:border-[#2563EB] focus:ring-2 focus:ring-blue-100' }}"
                        >
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#2563EB] transition-colors"
                            tabindex="-1"
                        >
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="w-4 h-4 text-[#2563EB] rounded border-slate-300 cursor-pointer"
                        >
                        <span class="text-sm text-[#475569]">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white
                           bg-[#0B1040] hover:bg-[#1a2160] active:scale-[0.98] transition-all duration-150 mt-2
                           disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" x-cloak>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span x-text="loading ? 'Memproses...' : 'Masuk'">Masuk</span>
                </button>
            </form>
        </div>

        {{-- Role info hint --}}
        <div class="mt-4 text-center">
            <p class="text-xs text-[#94A3B8]">
                Login berlaku untuk semua role: Customer, Admin, Finance, Sponsor, SuperAdmin
            </p>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="text-xs text-[#94A3B8] hover:text-[#2563EB] transition-colors">
                ← Kembali ke BravePay
            </a>
        </div>
    </div>

</body>
</html>
