<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 5)));

        $permissions = Permission::query()
            ->with('roles')
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('group_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('group_name')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'status' => 'success',
            'data' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = Permission::create($request->validated());

        ActivityLogService::log($request->user(), 'permission.created.api', $permission, "Created permission {$permission->slug} via API.");

        return response()->json([
            'status' => 'success',
            'message' => 'Permission created successfully.',
            'data' => $permission,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): JsonResponse
    {
        $permission->load('roles');

        return response()->json([
            'status' => 'success',
            'data' => $permission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $permission->update($request->validated());

        ActivityLogService::log($request->user(), 'permission.updated.api', $permission, "Updated permission {$permission->slug} via API.");

        return response()->json([
            'status' => 'success',
            'message' => 'Permission updated successfully.',
            'data' => $permission,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): JsonResponse
    {
        ActivityLogService::log(auth()->user(), 'permission.deleted.api', $permission, "Deleted permission {$permission->slug} via API.");
        $permission->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission deleted successfully.',
        ]);
    }

    public function restore($id): JsonResponse
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->restore();

        ActivityLogService::log(auth()->user(), 'permission.restored.api', $permission, "API: Restored permission {$permission->slug}.");

        return response()->json([
            'status' => 'success',
            'message' => 'Permission restored successfully.',
            'data' => $permission,
        ]);
    }

    public function forceDelete($id): JsonResponse
    {
        $permission = Permission::withTrashed()->findOrFail($id);

        ActivityLogService::log(auth()->user(), 'permission.permanently_deleted.api', $permission, "API: Permanently deleted permission {$permission->slug}.");
        $permission->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission permanently deleted successfully.',
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
        if (in_array($action, ['delete', 'restore', 'force-delete']) && !$user->hasPermission('permissions.delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized for this destructive action.',
            ], 403);
        }

        try {
            $affectedPermissions = Permission::withTrashed()->whereIn('id', $ids)->pluck('slug')->all();

            DB::transaction(function () use ($action, $ids, $affectedPermissions) {
                $query = Permission::withTrashed()->whereIn('id', $ids);

                switch ($action) {
                    case 'delete':
                        $query->delete();
                        $this->logBulkAction('permission.bulk_deleted.api', $ids, "API: Soft-deleted " . count($ids) . " permissions.", ['affected_permissions' => $affectedPermissions]);
                        break;
                    case 'restore':
                        $query->restore();
                        $this->logBulkAction('permission.bulk_restored.api', $ids, "API: Restored " . count($ids) . " permissions.", ['affected_permissions' => $affectedPermissions]);
                        break;
                    case 'force-delete':
                        $query->forceDelete();
                        $this->logBulkAction('permission.bulk_permanently_deleted.api', $ids, "API: Permanently deleted " . count($ids) . " permissions.", ['affected_permissions' => $affectedPermissions]);
                        break;
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => count($ids) . ' permissions processed successfully.',
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
