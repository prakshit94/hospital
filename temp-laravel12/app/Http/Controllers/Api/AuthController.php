<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password) || $user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->load('roles.permissions');
        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken($credentials['device_name'] ?? 'api-token')->plainTextToken;

        ActivityLogService::log(
            $user,
            'auth.api.login',
            $user,
            "{$user->name} created an API token.",
        );

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $this->userPayload($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($request->user()?->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        ActivityLogService::log(
            $user,
            'auth.api.logout',
            $user,
            "{$user?->name} revoked an API token.",
        );

        return response()->json([
            'message' => 'Token revoked successfully.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->load('roles.permissions');

        return response()->json([
            'user' => $this->userPayload($user),
        ]);
    }

    protected function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'last_login_at' => $user->last_login_at,
            'roles' => $user->roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ])->values(),
            'permissions' => $user->permissionSlugs(),
        ];
    }
}
