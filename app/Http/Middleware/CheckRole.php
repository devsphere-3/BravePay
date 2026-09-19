<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Cek apakah user yang login memiliki role yang diizinkan.
     *
     * Penggunaan di route:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,superadmin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Harus sudah login
        if (! Auth::check()) {
            return $this->redirectToLogin($request);
        }

        $user = Auth::user();

        // 2. Load role (eager load jika belum ada)
        $user->loadMissing('role');

        // 3. Cek status akun
        if (! $user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($user->status) {
                'suspended' => 'Akun Anda telah disuspend. Hubungi administrator.',
                'inactive'  => 'Akun Anda tidak aktif. Hubungi administrator.',
                default     => 'Akun Anda tidak dapat digunakan.',
            };

            return redirect()->route('login')
                ->withErrors(['email' => $message]);
        }

        // 4. Cek role
        if (empty($roles) || in_array($user->role?->name, $roles, true)) {
            return $next($request);
        }

        // 5. Role tidak cocok → 403
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized. Akses ditolak.'], 403);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    private function redirectToLogin(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->route('login')->withErrors([
            'email' => 'Silakan login terlebih dahulu.',
        ]);
    }
}
