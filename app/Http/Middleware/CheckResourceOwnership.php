<?php

namespace App\Http\Middleware;

use App\Models\Registration;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckResourceOwnership
{
    /**
     * Cegah IDOR (Insecure Direct Object Reference).
     * Memastikan customer hanya bisa mengakses resource miliknya sendiri.
     *
     * Penggunaan di route:
     *   ->middleware('owner:registration,order_code')
     *   ->middleware('owner:registration,id')
     *
     * Parameter:
     *   $resourceType : 'registration'
     *   $lookupField  : kolom yang digunakan untuk lookup ('order_code' atau 'id')
     */
    public function handle(
        Request $request,
        Closure $next,
        string $resourceType = 'registration',
        string $lookupField = 'order_code'
    ): Response {
        // SuperAdmin dan Admin boleh akses semua resource
        $user = Auth::user();
        if ($user && $user->hasAnyRole(['superadmin', 'admin', 'finance'])) {
            return $next($request);
        }

        $paramValue = $this->resolveParam($request, $lookupField);

        if (! $paramValue) {
            abort(404);
        }

        $owned = match ($resourceType) {
            'registration' => $this->checkRegistrationOwnership($paramValue, $lookupField),
            default        => false,
        };

        if (! $owned) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Resource bukan milik Anda.'], 403);
            }
            abort(403, 'Anda tidak diizinkan mengakses resource ini.');
        }

        return $next($request);
    }

    /**
     * Ambil nilai parameter dari route atau query string.
     */
    private function resolveParam(Request $request, string $field): mixed
    {
        // Coba dari route parameter terlebih dahulu
        $value = $request->route($field)
            ?? $request->route('code')
            ?? $request->route('id')
            ?? $request->query($field);

        return $value;
    }

    /**
     * Cek apakah registrasi milik user yang sedang login.
     */
    private function checkRegistrationOwnership(mixed $value, string $field): bool
    {
        $userId = Auth::id();

        if (! $userId) {
            return false;
        }

        $query = Registration::where('user_id', $userId);

        if ($field === 'order_code' || ! is_numeric($value)) {
            $query->where('order_code', $value);
        } else {
            $query->where('id', $value);
        }

        return $query->exists();
    }
}
