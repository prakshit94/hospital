<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->with('roles.permissions')
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return response()->json($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->safe()->except('roles'));
        $user->roles()->sync($request->validated('roles'));
        $user->load('roles.permissions');

        ActivityLogService::log($request->user(), 'user.created.api', $user, "Created user {$user->email} via API.");

        return response()->json([
            'message' => 'User created successfully.',
            'data' => $user,
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        $user->load('roles.permissions');

        return response()->json([
            'data' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->safe()->except(['roles', 'password']);

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        $user->update($data);
        $user->roles()->sync($request->validated('roles'));
        $user->load('roles.permissions');

        ActivityLogService::log($request->user(), 'user.updated.api', $user, "Updated user {$user->email} via API.");

        return response()->json([
            'message' => 'User updated successfully.',
            'data' => $user,
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        if (auth()->id() === $user->id) {
            return response()->json([
                'message' => 'You cannot delete the account you are currently using.',
            ], 422);
        }

        ActivityLogService::log(auth()->user(), 'user.deleted.api', $user, "Deleted user {$user->email} via API.");
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
