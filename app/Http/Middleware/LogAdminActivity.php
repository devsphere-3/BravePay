<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    /**
     * Catat aktivitas ke tabel activity_logs untuk semua role staff.
     * Dijalankan setelah response agar tidak memblok request.
     *
     * Role yang dicatat: superadmin, admin, finance.
     * Customer dan sponsor tidak dicatat di sini.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = $request->user();

        if (! $user) {
            return $response;
        }

        // Hanya log role staff
        $user->loadMissing('role');
        $staffRoles = ['superadmin', 'admin', 'finance'];

        if (! in_array($user->role?->name, $staffRoles, true)) {
            return $response;
        }

        // Jangan log request yang tidak relevan
        $routeName = $request->route()?->getName();
        if (! $routeName) {
            return $response;
        }

        // Jangan log asset, API checkin GET (terlalu berisik), health check
        $skip = ['up', 'debugbar.*', 'horizon.*'];
        foreach ($skip as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return $response;
            }
        }

        $action = match ($request->method()) {
            'GET'            => 'view',
            'POST'           => 'create',
            'PUT', 'PATCH'   => 'update',
            'DELETE'         => 'delete',
            default          => Str::lower($request->method()),
        };

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => $action,
            'description' => sprintf('[%s] %s %s', strtoupper($user->role->name), $request->method(), $routeName ?? $request->path()),
            'route_name'  => $routeName,
            'method'      => $request->method(),
            'ip_address'  => $request->ip(),
            'metadata'    => [
                'role'       => $user->role?->name,
                'parameters' => $request->route()?->parameters() ?? [],
                'status'     => $response->getStatusCode(),
                'user_agent' => Str::limit($request->userAgent() ?? '', 150),
            ],
        ]);

        return $response;
    }
}
