<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        return response()->json($roles);
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
                'message' => 'System roles cannot be deleted.',
            ], 422);
        }

        if ($role->users()->exists()) {
            return response()->json([
                'message' => 'Remove users from this role before deleting it.',
            ], 422);
        }

        ActivityLogService::log(auth()->user(), 'role.deleted.api', $role, "Deleted role {$role->name} via API.");
        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully.',
        ]);
    }
}
