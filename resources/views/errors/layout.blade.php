@php
    $statusCode = $statusCode ?? (
        $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500
    );
    $defaults = config('error_pages.500');
    $page = array_merge($defaults, config("error_pages.{$statusCode}", []));
    $buttonAction = match ($page['action']) {
        'home' => url('/'),
        'login' => route('login'),
        'reload' => request()->fullUrl(),
        default => url()->previous(),
    };
    $buttonMethod = $page['action'] === 'reload' ? 'get' : 'get';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $statusCode }} — {{ $page['title'] }} | BravePay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --tiger-orange:#F59A3D; --tiger-dark:#0B1040; --tiger-brown:#1E3A8A; --cream:#F0F4FF; --cream-dark:#E0E9FF; --white:#FFFFFF; --muted:#64748B; --brand-blue:#2563EB; --brand-blue-dark:#1D4ED8; --brand-border:#BFDBFE; }
        html, body { width:100%; min-width:0; margin:0; overflow-x:hidden; }
        .error-page { min-height:100vh; overflow:hidden; background:var(--cream); color:var(--tiger-dark); position:relative; isolation:isolate; }
        .error-page::before,.error-page::after { content:""; position:absolute; border-radius:999px; z-index:-1; pointer-events:none; }
        .error-page::before { width:32rem; height:32rem; background:#DBEAFE; top:-15rem; right:-10rem; }
        .error-page::after { width:24rem; height:24rem; background:#E0E9FF; bottom:-12rem; left:-8rem; }
        .error-shell { width:min(1120px, calc(100% - 2rem)); min-height:100vh; margin:auto; padding:2rem 0; display:flex; flex-direction:column; justify-content:center; }
        .error-brand { display:inline-flex; align-items:center; gap:.7rem; color:var(--tiger-dark); font-weight:800; font-size:1.15rem; text-decoration:none; align-self:center; margin-bottom:2rem; }
        .error-brand-mark { width:2.7rem; height:2.7rem; display:grid; place-items:center; color:#fff; background:var(--brand-blue); border-radius:1rem; box-shadow:0 .6rem 1.4rem rgba(37,99,235,.25); }
        .error-grid { display:grid; grid-template-columns:minmax(0, .95fr) minmax(0, 1.05fr); align-items:center; gap:clamp(2rem, 7vw, 6rem); }
        .error-art { min-height:25rem; display:grid; place-items:center; position:relative; }
        .error-art::before { content:""; position:absolute; width:18rem; height:18rem; border-radius:50%; background:#DBEAFE; box-shadow:inset 0 -1rem 0 #BFDBFE; }
        .error-copy { background:rgba(255,255,255,.78); border:1px solid rgba(191,219,254,.9); border-radius:2rem; padding:clamp(1.5rem, 4vw, 3rem); box-shadow:0 1.5rem 4rem rgba(30,58,138,.1); backdrop-filter:blur(8px); }
        .error-code { color:var(--brand-blue); font-size:clamp(4.8rem, 12vw, 8.5rem); line-height:.8; letter-spacing:-.06em; font-weight:900; margin:0 0 1rem; }
        .error-title { color:var(--tiger-dark); font-size:clamp(1.65rem, 3vw, 2.7rem); line-height:1.1; font-weight:800; margin:0 0 1rem; }
        .error-message { color:var(--muted); font-size:1rem; line-height:1.75; max-width:30rem; margin:0 0 1.75rem; }
        .error-button { display:inline-flex; align-items:center; justify-content:center; gap:.55rem; min-height:3rem; padding:.75rem 1.35rem; border:0; border-radius:999px; background:var(--brand-blue); color:#fff; font:inherit; font-weight:800; text-decoration:none; box-shadow:0 .65rem 1.2rem rgba(37,99,235,.28); transition:transform .2s ease, background .2s ease, box-shadow .2s ease; }
        .error-button:hover { background:var(--brand-blue-dark); transform:translateY(-2px); box-shadow:0 .85rem 1.5rem rgba(37,99,235,.36); }
        .error-button:focus-visible { outline:3px solid #93C5FD; outline-offset:4px; }
        .error-doodle { position:absolute; color:#60A5FA; font-weight:800; opacity:.7; }
        .error-doodle.one { top:12%; left:8%; transform:rotate(-15deg); }
        .error-doodle.two { right:8%; bottom:15%; transform:rotate(18deg); }
        .error-paw { position:absolute; width:1.3rem; height:1rem; border-radius:60% 60% 50% 50%; background:#93C5FD; opacity:.6; transform:rotate(-25deg); }
        .error-paw::before,.error-paw::after { content:""; position:absolute; width:.45rem; height:.6rem; border-radius:50%; background:#93C5FD; top:-.35rem; }
        .error-paw::before { left:.1rem; transform:rotate(-20deg); }.error-paw::after { right:.1rem; transform:rotate(20deg); }
        .error-paw.one { left:20%; bottom:17%; }.error-paw.two { right:17%; top:17%; transform:rotate(25deg) scale(.7); }
        .tiger-mascot { width:14rem; height:14rem; position:relative; z-index:1; animation:tiger-float 4s ease-in-out infinite; }
        .tiger-head { position:absolute; inset:2rem 1.2rem 1.2rem; border-radius:48% 48% 45% 45%; background:var(--tiger-orange); border:.35rem solid var(--tiger-brown); box-shadow:inset 0 -.7rem 0 rgba(107,70,50,.12); }
        .tiger-ear { position:absolute; width:4rem; height:4rem; top:1rem; background:var(--tiger-orange); border:.35rem solid var(--tiger-brown); z-index:-1; }.tiger-ear-left { left:1.6rem; transform:rotate(-24deg); border-radius:70% 25% 20% 30%; }.tiger-ear-right { right:1.6rem; transform:rotate(24deg); border-radius:25% 70% 30% 20%; }
        .tiger-ear::after { content:""; position:absolute; inset:.7rem; background:#F7BE7A; border-radius:inherit; }
        .tiger-stripe { position:absolute; width:.55rem; height:2.4rem; background:var(--tiger-dark); border-radius:999px; top:-.2rem; }.tiger-stripe-one { left:36%; transform:rotate(-12deg); }.tiger-stripe-two { left:48%; height:2.7rem; }.tiger-stripe-three { right:36%; transform:rotate(12deg); }
        .tiger-eye { position:absolute; top:4.2rem; width:2rem; height:2.35rem; border-radius:50%; background:#fff; border:.18rem solid var(--tiger-dark); }.tiger-eye-left { left:2.35rem; }.tiger-eye-right { right:2.35rem; }.tiger-eye i { display:block; width:.8rem; height:1rem; margin:.48rem auto; border-radius:50%; background:var(--tiger-dark); }.tiger-eye i::after { content:""; display:block; width:.25rem; height:.25rem; margin:.12rem; border-radius:50%; background:#fff; }
        .tiger-muzzle { position:absolute; left:50%; top:6.3rem; transform:translateX(-50%); width:5.2rem; height:3.5rem; border-radius:50%; background:#FFE3BC; }.tiger-nose { position:absolute; left:50%; top:.65rem; transform:translateX(-50%); width:1.15rem; height:.8rem; background:var(--tiger-brown); border-radius:50% 50% 45% 45%; }.tiger-mouth { position:absolute; left:50%; top:1.35rem; width:1.7rem; height:.9rem; transform:translateX(-50%); border-bottom:.18rem solid var(--tiger-dark); border-radius:0 0 50% 50%; }
        .tiger-cheek { position:absolute; top:6.7rem; width:1.1rem; height:.7rem; border-radius:50%; background:#F28C73; opacity:.65; }.tiger-cheek-left { left:1.6rem; }.tiger-cheek-right { right:1.6rem; }.tiger-whisker { position:absolute; width:2rem; height:.08rem; background:var(--tiger-brown); top:7.7rem; }.tiger-whisker-left { left:-.7rem; transform:rotate(12deg); }.tiger-whisker-right { right:-.7rem; transform:rotate(-12deg); }.tiger-paw { position:absolute; width:3.1rem; height:2rem; bottom:.4rem; border:.3rem solid var(--tiger-brown); background:var(--tiger-orange); border-radius:55% 55% 45% 45%; }.tiger-paw-left { left:1.6rem; transform:rotate(10deg); }.tiger-paw-right { right:1.6rem; transform:rotate(-10deg); }
        .tiger-expression-sad .tiger-mouth { border-bottom:0; border-top:.18rem solid var(--tiger-dark); top:1.8rem; }.tiger-expression-sleepy .tiger-eye { height:.45rem; top:5rem; background:transparent; border-width:0 0 .2rem; border-radius:0; }.tiger-expression-shocked .tiger-mouth { width:1.2rem; height:1.2rem; border:.18rem solid var(--tiger-dark); border-radius:50%; }.tiger-expression-thinking .tiger-mouth { transform:translateX(-50%) rotate(-8deg); }.tiger-expression-embarrassed .tiger-cheek { opacity:1; width:1.5rem; }.tiger-expression-curious .tiger-eye { transform:scale(1.08); }
        @keyframes tiger-float { 0%,100% { transform:translateY(0) rotate(-1deg); } 50% { transform:translateY(-.6rem) rotate(1deg); } }
        @media (max-width: 700px) { .error-shell { padding:1.5rem 0; }.error-brand { margin-bottom:1rem; }.error-grid { grid-template-columns:1fr; gap:1rem; }.error-art { min-height:18rem; }.error-art::before { width:14rem; height:14rem; }.tiger-mascot { transform:scale(.86); }.error-copy { padding:1.5rem; text-align:center; }.error-message { margin-left:auto; margin-right:auto; }.error-button { width:100%; } }
        @media (prefers-reduced-motion:reduce) { *,*::before,*::after { animation-duration:.01ms !important; animation-iteration-count:1 !important; scroll-behavior:auto !important; transition-duration:.01ms !important; } }
    </style>
</head>
<body class="error-page">
    <main class="error-shell" aria-labelledby="error-title">
        <a class="error-brand" href="{{ url('/') }}" aria-label="Kembali ke BravePay">
            <span class="error-brand-mark" aria-hidden="true">🐾</span>
            <span>BravePay</span>
        </a>
        <span class="error-doodle one" aria-hidden="true">✦</span><span class="error-doodle two" aria-hidden="true">✧</span>
        <span class="error-paw one" aria-hidden="true"></span><span class="error-paw two" aria-hidden="true"></span>
        <div class="error-grid">
            <div class="error-art">@include('errors.components.tiger', ['expression' => $page['expression']])</div>
            <section class="error-copy">
                <p class="error-code" aria-label="Kode error {{ $statusCode }}">{{ $statusCode }}</p>
                <h1 id="error-title" class="error-title">{{ $page['title'] }}</h1>
                <p class="error-message">{{ $page['message'] }}</p>
                <a class="error-button" href="{{ $buttonAction }}" @if($page['action'] === 'reload') onclick="window.location.reload(); return false;" @endif>
                    <span aria-hidden="true">{{ in_array($page['action'], ['home', 'login']) ? '⌂' : '↻' }}</span>
                    {{ $page['button'] }}
                </a>
            </section>
        </div>
    </main>
</body>
</html>
