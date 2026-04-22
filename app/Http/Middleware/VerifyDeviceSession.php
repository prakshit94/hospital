<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyDeviceSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $sessionId = $request->session()->getId();
            $ip = $request->ip();
            $ua = $request->userAgent();

            // Check if session metadata matches
            $storedIp = $request->session()->get('auth.session_ip');
            $storedUa = $request->session()->get('auth.session_ua');

            if (($storedIp && $storedIp !== $ip) || ($storedUa && $storedUa !== $ua)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Your session has been terminated due to a change in your network or device.');
            }

            // Update last activity on device
            $deviceId = md5($ua . $user->id);
            $device = $user->devices()->where('device_id', $deviceId)->first();

            if (!$device) {
                // New device detected
                $metadata = \App\Services\ActivityLogService::parseUserAgent($ua);
                $metadata['ip'] = $ip;

                $user->notify(new \App\Notifications\NewDeviceLoginNotification($metadata));

                $user->devices()->create([
                    'device_id' => $deviceId,
                    'ip_address' => $ip,
                    'user_agent' => $ua,
                    'browser' => $metadata['browser'],
                    'platform' => $metadata['platform'],
                    'last_active_at' => now(),
                    'is_trusted' => false,
                ]);
            } else {
                $device->update([
                    'ip_address' => $ip,
                    'last_active_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
