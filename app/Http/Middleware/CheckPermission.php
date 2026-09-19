<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Cek apakah user memiliki permission tertentu.
     * Mempertimbangkan role_permissions + user_permissions override.
     *
     * Penggunaan di route:
     *   ->middleware('permission:admin.scan_ticket')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        $user->loadMissing('role');

        // Cek status aktif
        if (! $user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda tidak aktif.']);
        }

        // Cek permission (via cache)
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Akses ditolak. Permission [{$permission}] diperlukan.",
            ], 403);
        }

        abort(403, "Anda tidak memiliki permission untuk melakukan tindakan ini.");
    }
}
