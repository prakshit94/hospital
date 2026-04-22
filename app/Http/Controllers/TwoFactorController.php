<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    protected $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function enable(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->two_factor_secret) {
            return back()->with('error', 'Two-factor authentication is already enabled.');
        }

        $secret = $this->twoFactorService->generateSecretKey();
        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($this->twoFactorService->generateRecoveryCodes())),
        ])->save();

        ActivityLogService::log($user, 'auth.2fa.enabled', $user, "{$user->name} enabled 2FA.");

        return back()->with('status', 'two-factor-authentication-enabled');
    }

    public function showQrCode(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (!$user->two_factor_secret || $user->two_factor_confirmed_at) {
            return response()->json(['error' => 'Action not allowed.'], 403);
        }

        $qrCode = $this->twoFactorService->getQRCodeUrl($user, decrypt($user->two_factor_secret));

        return response()->json([
            'svg' => $qrCode,
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        if (!$this->twoFactorService->verifyCode(decrypt($user->two_factor_secret), $request->code)) {
            throw ValidationException::withMessages([
                'code' => ['The provided two-factor authentication code was invalid.'],
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        ActivityLogService::log($user, 'auth.2fa.confirmed', $user, "{$user->name} confirmed 2FA.");

        return back()->with('status', 'two-factor-authentication-confirmed');
    }

    public function disable(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        ActivityLogService::log($user, 'auth.2fa.disabled', $user, "{$user->name} disabled 2FA.");

        return back()->with('status', 'two-factor-authentication-disabled');
    }
}
