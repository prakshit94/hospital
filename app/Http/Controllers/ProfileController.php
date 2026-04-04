<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user()->load('roles.permissions'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        ActivityLogService::log(
            $user,
            'profile.updated',
            $user,
            "{$user->name} updated their profile details.",
        );

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        ActivityLogService::log(
            $user,
            'profile.password.updated',
            $user,
            "{$user->name} changed their account password.",
        );

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Password changed successfully.');
    }
}
