<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
{
    if (!auth()->check()) {
        abort(401);
    }

    $user = $request->user();

    $userPermissions = $user->role
        ->permissions
        ->pluck('permission_name')
        ->toArray();

    foreach ($permissions as $permission) {
        if (in_array($permission, $userPermissions)) {
            return $next($request);
        }
    }

    abort(403);
}
}
