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
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && $request->routeIs('admin.*')) {
            $routeName = $request->route()?->getName();
            $action = match ($request->method()) {
                'GET' => 'view',
                'POST' => 'create',
                'PUT', 'PATCH' => 'update',
                'DELETE' => 'delete',
                default => Str::lower($request->method()),
            };

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => $action,
                'description' => sprintf('%s %s', $request->method(), $routeName ?? $request->path()),
                'route_name' => $routeName,
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'metadata' => [
                    'parameters' => $request->route()?->parameters() ?? [],
                    'status' => $response->getStatusCode(),
                ],
            ]);
        }

        return $response;
    }
}
