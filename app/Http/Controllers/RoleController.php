<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
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

    public function create(Request $request): View
    {
        $permissionGroups = Permission::orderBy('group_name')->orderBy('name')->get()->groupBy('group_name');

        $payload = [
            'role' => new Role(),
            'permissionGroups' => $permissionGroups,
        ];

        if ($request->ajax()) {
            return view('roles.modal-form', $payload + [
                'pageTitle' => 'Create Role',
                'pageDescription' => 'Bundle permissions into a reusable access profile.',
                'formAction' => route('roles.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Save Role',
            ]);
        }

        return view('roles.create', $payload);
    }

    public function store(StoreRoleRequest $request): RedirectResponse|JsonResponse
    {
        $role = Role::create($request->safe()->except('permissions'));
        $role->permissions()->sync($request->validated('permissions', []));

        ActivityLogService::logWithChanges(
            $request->user(),
            $role,
            'role.created',
            "Created role {$role->name}.",
            ['permissions' => $role->permissions()->pluck('name')->all()],
        );

        if ($request->ajax()) {
            session()->flash('status', 'Role created successfully.');
            return response()->json([
                'status' => 'success',
                'message' => 'Role created successfully.',
            ]);
        }

        return redirect()
            ->route('roles.index')
            ->with('status', 'Role created successfully.');
    }

    public function show(Request $request, Role $role): View
    {
        $role->load(['permissions', 'users']);

        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', $role->getMorphClass())
            ->where('subject_id', $role->getKey())
            ->latest()
            ->take(10)
            ->get();

        if ($request->ajax()) {
            return view('roles.modal-show', compact('role', 'activities'));
        }

        return view('roles.show', compact('role', 'activities'));
    }

    public function edit(Request $request, Role $role): View
    {
        $role->load('permissions');
        $permissionGroups = Permission::orderBy('group_name')->orderBy('name')->get()->groupBy('group_name');

        if ($request->ajax()) {
            return view('roles.modal-form', [
                'role' => $role,
                'permissionGroups' => $permissionGroups,
                'pageTitle' => 'Edit Role',
                'pageDescription' => "Update permissions and metadata for {$role->name}.",
                'formAction' => route('roles.update', $role),
                'formMethod' => 'PUT',
                'submitLabel' => 'Update Role',
            ]);
        }

        return view('roles.edit', compact('role', 'permissionGroups'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse|JsonResponse
    {
        $oldPermissions = $role->permissions()->pluck('name')->all();
        
        $role->fill($request->safe()->except('permissions'));
        $modelChanges = ActivityLogService::getModelChanges($role);
        $role->save();
        
        $role->permissions()->sync($request->validated('permissions', []));
        $role->load('permissions');
        $newPermissions = $role->permissions()->pluck('name')->all();

        $extraProperties = array_merge($modelChanges, []);
        if ($oldPermissions !== $newPermissions) {
            $extraProperties['old_permissions'] = $oldPermissions;
            $extraProperties['new_permissions'] = $newPermissions;
        }

        ActivityLogService::log(
            $request->user(),
            'role.updated',
            $role,
            "Updated role {$role->name}.",
            $extraProperties,
        );

        if ($request->ajax()) {
            session()->flash('status', 'Role updated successfully.');
            return response()->json([
                'status' => 'success',
                'message' => 'Role updated successfully.',
            ]);
        }

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

    public function toggleStatus(Role $role): JsonResponse
    {
        if ($role->is_system) {
            return response()->json([
                'status' => 'error',
                'message' => 'System roles cannot be deactivated.',
            ], 403);
        }

        $oldStatus = $role->status;
        $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';
        $role->update(['status' => $newStatus]);

        ActivityLogService::log(
            auth()->user(),
            'role.status_toggled',
            $role,
            "Changed status for {$role->name} from {$oldStatus} to {$newStatus}.",
            ['old' => $oldStatus, 'new' => $newStatus],
        );

        session()->flash('status', "Role {$role->name} is now {$newStatus}.");

        return response()->json([
            'status' => 'success',
            'message' => "Role {$role->name} is now {$newStatus}.",
            'new_status' => $newStatus,
        ]);
    }
}
