<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
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
            ->paginate(15)
            ->withQueryString();

        return response()->json($permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = Permission::create($request->validated());

        ActivityLogService::log($request->user(), 'permission.created.api', $permission, "Created permission {$permission->slug} via API.");

        return response()->json([
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
            'message' => 'Permission deleted successfully.',
        ]);
    }
}
