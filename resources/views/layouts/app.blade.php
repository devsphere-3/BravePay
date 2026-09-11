<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BravePay') — Event Registration & Payment</title>
    <meta name="description" content="@yield('meta_description', 'Daftar event dengan mudah, bayar dengan aman, dan dapatkan e-ticket secara instan. BravePay — Platform Registrasi & Pembayaran Event.')">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'BravePay')">
    <meta property="og:description" content="@yield('meta_description', 'Platform Registrasi & Pembayaran Event Indonesia')">
    <meta property="og:type" content="website">

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="antialiased" x-data="fadeObserver()" x-init="init()">

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Page Content --}}
    <main class="pt-[72px]">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Help Center Floating Widget --}}
    @include('components.help-center')

    @stack('scripts')
</body>
</html>
