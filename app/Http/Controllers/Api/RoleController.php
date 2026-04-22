<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 5)));

        $roles = Role::query()
            ->with(['permissions', 'users'])
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'status' => 'success',
            'data' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->safe()->except('permissions'));
        $role->permissions()->sync($request->validated('permissions', []));
        $role->load('permissions');

        ActivityLogService::log($request->user(), 'role.created.api', $role, "Created role {$role->name} via API.");

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully.',
            'data' => $role,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        $role->load(['permissions', 'users']);

        return response()->json([
            'status' => 'success',
            'data' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update($request->safe()->except('permissions'));
        $role->permissions()->sync($request->validated('permissions', []));
        $role->load('permissions');

        ActivityLogService::log($request->user(), 'role.updated.api', $role, "Updated role {$role->name} via API.");

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully.',
            'data' => $role,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        if ($role->is_system) {
            return response()->json([
                'status' => 'error',
                'message' => 'System roles cannot be deleted.',
            ], 422);
        }

        if ($role->users()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Remove users from this role before deleting it.',
            ], 422);
        }

        ActivityLogService::log(auth()->user(), 'role.deleted.api', $role, "Deleted role {$role->name} via API.");
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully.',
        ]);
    }

    public function restore($id): JsonResponse
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();

        ActivityLogService::log(auth()->user(), 'role.restored.api', $role, "API: Restored role {$role->name}.");

        return response()->json([
            'status' => 'success',
            'message' => 'Role restored successfully.',
            'data' => $role,
        ]);
    }

    public function forceDelete($id): JsonResponse
    {
        $role = Role::withTrashed()->findOrFail($id);

        if ($role->is_system) {
            return response()->json([
                'status' => 'error',
                'message' => 'System roles cannot be permanently deleted.',
            ], 422);
        }

        ActivityLogService::log(auth()->user(), 'role.permanently_deleted.api', $role, "API: Permanently deleted role {$role->name}.");
        $role->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role permanently deleted successfully.',
        ]);
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => ['required', 'string', 'in:delete,restore,force-delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');

        $user = auth()->user();
        if (in_array($action, ['delete', 'restore', 'force-delete']) && !$user->hasPermission('roles.delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized for this destructive action.',
            ], 403);
        }

        try {
            $affectedRoles = Role::withTrashed()->whereIn('id', $ids)->pluck('name')->all();

            DB::transaction(function () use ($action, $ids, $affectedRoles) {
                $query = Role::withTrashed()->whereIn('id', $ids);

                switch ($action) {
                    case 'delete':
                        $query->get()->each(function($role) {
                            if (!$role->is_system) $role->delete();
                        });
                        $this->logBulkAction('role.bulk_deleted.api', $ids, "API: Soft-deleted " . count($ids) . " roles.", ['affected_roles' => $affectedRoles]);
                        break;
                    case 'restore':
                        $query->restore();
                        $this->logBulkAction('role.bulk_restored.api', $ids, "API: Restored " . count($ids) . " roles.", ['affected_roles' => $affectedRoles]);
                        break;
                    case 'force-delete':
                        $query->get()->each(function($role) {
                            if (!$role->is_system) $role->forceDelete();
                        });
                        $this->logBulkAction('role.bulk_permanently_deleted.api', $ids, "API: Permanently deleted " . count($ids) . " roles.", ['affected_roles' => $affectedRoles]);
                        break;
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => count($ids) . ' roles processed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during bulk operation: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function logBulkAction(string $event, array $ids, string $description, array $extra = []): void
    {
        ActivityLogService::log(
            auth()->user(),
            $event,
            null,
            $description,
            array_merge(['affected_ids' => $ids], $extra)
        );
    }
}
