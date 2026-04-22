<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validateCredentials();

        if ($request->boolean('remember')) {
            cookie()->queue('remembered_email', $request->email, 60 * 24 * 30);
        } else {
            cookie()->queue(cookie()->forget('remembered_email'));
        }

        /** @var \App\Models\User $user */
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user->two_factor_confirmed_at) {
            $request->session()->put([
                'auth.2fa.user_id' => $user->id,
                'auth.2fa.remember' => (bool) $request->remember,
            ]);

            return redirect()->route('two-factor.login');
        }

        $request->authenticate();
        $request->session()->regenerate();
        $request->session()->put([
            'auth.session_ip' => $request->ip(),
            'auth.session_ua' => $request->userAgent(),
        ]);

        $user->forceFill([
            'last_login_at' => now(),
        ])->save();

        ActivityLogService::log(
            $user,
            'auth.web.login',
            $user,
            "{$user->name} signed in to the web dashboard.",
        );

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            ActivityLogService::log(
                $user,
                'auth.web.logout',
                $user,
                "{$user->name} signed out of the web dashboard.",
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
