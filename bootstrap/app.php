<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckResourceOwnership;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\LogAdminActivity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Guest redirect: semua unauthenticated request → /login (bukan /admin/login)
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Middleware aliases
        $middleware->alias([
            'role'           => CheckRole::class,
            'permission'     => CheckPermission::class,
            'owner'          => CheckResourceOwnership::class,
            'admin.activity' => LogAdminActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })
    ->create();
