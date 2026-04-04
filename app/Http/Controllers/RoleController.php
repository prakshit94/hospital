<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 5)));

        $roles = Role::query()
            ->withCount(['permissions', 'users'])
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('roles.partials.results', compact('roles'));
        }

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissionGroups = Permission::orderBy('group_name')->orderBy('name')->get()->groupBy('group_name');

        return view('roles.create', [
            'role' => new Role(),
            'permissionGroups' => $permissionGroups,
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create($request->safe()->except('permissions'));
        $role->permissions()->sync($request->validated('permissions', []));

        ActivityLogService::log(
            $request->user(),
            'role.created',
            $role,
            "Created role {$role->name}.",
            ['permissions' => $role->permissions()->pluck('slug')->all()],
        );

        return redirect()
            ->route('roles.index')
            ->with('status', 'Role created successfully.');
    }

    public function show(Role $role): View
    {
        $role->load(['permissions', 'users']);

        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', $role->getMorphClass())
            ->where('subject_id', $role->getKey())
            ->latest()
            ->take(10)
            ->get();

        return view('roles.show', compact('role', 'activities'));
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');
        $permissionGroups = Permission::orderBy('group_name')->orderBy('name')->get()->groupBy('group_name');

        return view('roles.edit', compact('role', 'permissionGroups'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->safe()->except('permissions'));
        $role->permissions()->sync($request->validated('permissions', []));

        ActivityLogService::log(
            $request->user(),
            'role.updated',
            $role,
            "Updated role {$role->name}.",
            ['permissions' => $role->permissions()->pluck('slug')->all()],
        );

        return redirect()
            ->route('roles.show', $role)
            ->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return back()->withErrors([
                'role' => 'System roles cannot be deleted.',
            ]);
        }

        if ($role->users()->exists()) {
            return back()->withErrors([
                'role' => 'Remove users from this role before deleting it.',
            ]);
        }

        ActivityLogService::log(
            auth()->user(),
            'role.deleted',
            $role,
            "Deleted role {$role->name}.",
        );

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('status', 'Role deleted successfully.');
    }
}
