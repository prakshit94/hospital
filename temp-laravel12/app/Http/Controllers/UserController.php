<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
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
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::query()->with('permissions')->orderBy('name')->get();

        return view('users.create', [
            'user' => new User(['status' => 'active']),
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
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

        return redirect()
            ->route('users.index')
            ->with('status', 'User created successfully.');
    }

    public function show(User $user): View
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

        return view('users.show', compact('user', 'activities'));
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $roles = Role::query()->with('permissions')->orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
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
