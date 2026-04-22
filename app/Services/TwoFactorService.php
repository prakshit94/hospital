<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Str;

class TwoFactorService
{
    public function generateSecretKey(): string
    {
        return Google2FA::generateSecretKey();
    }

    public function getQRCodeUrl(User $user, string $secret): string
    {
        return Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );
    }

    public function verifyCode(string $secret, string $code): bool
    {
        return Google2FA::verifyKey($secret, $code);
    }

    public function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))->map(function () {
            return Str::random(10) . '-' . Str::random(10);
        })->toArray();
    }
}
