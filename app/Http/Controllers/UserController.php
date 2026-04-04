<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 5)));

        $users = User::query()
            ->with('roles')
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->string('status')->toString(), fn ($query, string $status) => $query->where('status', $status))
            ->when($request->integer('role'), fn ($query, int $roleId) => $query->whereHas('roles', fn ($inner) => $inner->whereKey($roleId)))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        if ($request->ajax()) {
            return view('users.partials.results', compact('users'));
        }

        return view('users.index', compact('users', 'roles'));
    }

    public function create(Request $request): View
    {
        $roles = Role::query()->with('permissions')->orderBy('name')->get();

        $payload = [
            'user' => new User(['status' => 'active']),
            'roles' => $roles,
        ];

        if ($request->ajax()) {
            return view('users.modal-form', $payload + [
                'pageTitle' => 'Create User',
                'pageDescription' => 'Add a new account and assign one or more roles.',
                'formAction' => route('users.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Save User',
            ]);
        }

        return view('users.create', $payload);
    }

    public function store(StoreUserRequest $request): RedirectResponse|JsonResponse
    {
        $user = User::create($request->safe()->except('roles'));
        $user->roles()->sync($request->validated('roles'));

        ActivityLogService::log(
            $request->user(),
            'user.created',
            $user,
            "Created user {$user->email}.",
            ['roles' => $user->roles()->pluck('slug')->all()],
        );

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully.',
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('status', 'User created successfully.');
    }

    public function show(Request $request, User $user): View
    {
        $user->load('roles.permissions');

        $activities = ActivityLog::query()
            ->with('causer')
            ->where(function ($query) use ($user) {
                $query->where('subject_type', $user->getMorphClass())
                    ->where('subject_id', $user->getKey());
            })
            ->orWhere('causer_id', $user->getKey())
            ->latest()
            ->take(10)
            ->get();

        if ($request->ajax()) {
            return view('users.modal-show', compact('user', 'activities'));
        }

        return view('users.show', compact('user', 'activities'));
    }

    public function edit(Request $request, User $user): View
    {
        $user->load('roles');
        $roles = Role::query()->with('permissions')->orderBy('name')->get();

        if ($request->ajax()) {
            return view('users.modal-form', [
                'user' => $user,
                'roles' => $roles,
                'pageTitle' => 'Edit User',
                'pageDescription' => "Update account details, status, and assigned roles for {$user->email}.",
                'formAction' => route('users.update', $user),
                'formMethod' => 'PUT',
                'submitLabel' => 'Update User',
            ]);
        }

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse|JsonResponse
    {
        $data = $request->safe()->except(['roles', 'password']);

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        $user->update($data);
        $user->roles()->sync($request->validated('roles'));

        ActivityLogService::log(
            $request->user(),
            'user.updated',
            $user,
            "Updated user {$user->email}.",
            ['roles' => $user->roles()->pluck('slug')->all()],
        );

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully.',
            ]);
        }

        return redirect()
            ->route('users.show', $user)
            ->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'user' => 'You cannot delete the account you are currently using.',
            ]);
        }

        ActivityLogService::log(
            auth()->user(),
            'user.deleted',
            $user,
            "Deleted user {$user->email}.",
        );

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('status', 'User deleted successfully.');
    }
}
