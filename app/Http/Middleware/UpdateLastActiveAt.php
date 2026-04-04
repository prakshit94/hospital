<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActiveAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            // Don't update on every single request to save DB load, maybe only if older than 1 minute
            if (!$user->last_active_at || $user->last_active_at->diffInMinutes(now()) >= 1) {
                // Avoid using `update()` directly to prevent firing model events unnecessarily, which might cause loops
                // or unwanted side effects on observing things. Wait, `timestamps` set to false.
                $user->timestamps = false;
                $user->forceFill(['last_active_at' => now()])->save();
                $user->timestamps = true;
            }
        }

        return $next($request);
    }
}
