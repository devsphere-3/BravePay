<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    // ── Show Login ────────────────────────────────────────────────

    public function showLogin(): View|RedirectResponse
    {
        // Sudah login → langsung ke dashboard
        if (Auth::check()) {
            return redirect(Auth::user()->dashboardUrl());
        }

        return view('auth.login');
    }

    // ── Login ─────────────────────────────────────────────────────

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Rate limiting: 5 percobaan per menit per IP+email
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, maxAttempts: 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        // Attempt login
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, decay: 60);

            return back()->withErrors([
                // Pesan generik — tidak membedakan email tidak ada vs password salah
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        // Login berhasil → reset rate limiter
        RateLimiter::clear($throttleKey);

        $user = Auth::user();
        $user->loadMissing('role');

        // Cek status akun
        if (! $user->isActive()) {
            Auth::logout();
            $message = match ($user->status) {
                'suspended' => 'Akun Anda telah disuspend. Hubungi administrator.',
                'inactive'  => 'Akun Anda tidak aktif. Hubungi administrator.',
                default     => 'Akun Anda tidak dapat digunakan.',
            };
            return back()->withErrors(['email' => $message])->onlyInput('email');
        }

        // Cek role valid
        if (! $user->role) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda tidak memiliki role. Hubungi administrator.',
            ])->onlyInput('email');
        }

        // Regenerate session
        $request->session()->regenerate();

        // Catat last_login_at
        $user->updateQuietly(['last_login_at' => now()]);

        // Audit log
        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'login',
            'description' => "[{$user->role->name}] Login berhasil",
            'route_name'  => 'login',
            'method'      => 'POST',
            'ip_address'  => $request->ip(),
            'metadata'    => [
                'role'       => $user->role->name,
                'user_agent' => Str::limit($request->userAgent() ?? '', 150),
            ],
        ]);

        // Redirect ke intended URL hanya jika sesuai role, fallback ke dashboard
        $intended = session()->pull('url.intended');
        if ($intended && $this->intendedMatchesRole($intended, $user->role->name)) {
            return redirect($intended);
        }

        return redirect($user->dashboardUrl());
    }

    // ── Logout ────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->loadMissing('role');

            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => 'logout',
                'description' => "[{$user->role?->name}] Logout",
                'route_name'  => 'logout',
                'method'      => 'POST',
                'ip_address'  => $request->ip(),
                'metadata'    => ['role' => $user->role?->name],
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ── Helpers ───────────────────────────────────────────────────

    /**
     * Cek apakah URL intended sesuai dengan role user.
     * Mencegah customer login lalu diarahkan ke /admin.
     */
    private function intendedMatchesRole(string $url, string $role): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        return match ($role) {
            'superadmin' => str_starts_with($path, '/superadmin'),
            'admin'      => str_starts_with($path, '/admin'),
            'finance'    => str_starts_with($path, '/finance'),
            'sponsor'    => str_starts_with($path, '/sponsor'),
            'customer'   => str_starts_with($path, '/customer'),
            default      => false,
        };
    }
}
