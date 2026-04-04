<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $permissionList = array_filter(array_map('trim', explode('|', $permissions)));

        if (! $user->hasAnyPermission($permissionList)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
