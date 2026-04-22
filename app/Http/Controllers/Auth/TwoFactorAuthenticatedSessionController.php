<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticatedSessionController extends Controller
{
    protected $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function create(Request $request)
    {
        if (!$request->session()->has('auth.2fa.user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    public function store(Request $request)
    {
        $userId = $request->session()->get('auth.2fa.user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = \App\Models\User::findOrFail($userId);

        if (!$this->twoFactorService->verifyCode(decrypt($user->two_factor_secret), $request->code)) {
            ActivityLogService::log($user, 'auth.2fa.failed', $user, "Failed 2FA attempt for {$user->name}");
            
            throw ValidationException::withMessages([
                'code' => ['The provided two-factor authentication code was invalid.'],
            ]);
        }

        $request->session()->regenerate();
        Auth::login($user, (bool) $request->session()->get('auth.2fa.remember', false));
        $request->session()->forget(['auth.2fa.user_id', 'auth.2fa.remember']);
        $request->session()->put([
            'auth.session_ip' => $request->ip(),
            'auth.session_ua' => $request->userAgent(),
        ]);

        $user->forceFill(['last_login_at' => now()])->save();

        ActivityLogService::log($user, 'auth.web.login', $user, "{$user->name} signed in (2FA verified).");

        return redirect()->intended(route('dashboard'));
    }
}
