<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 5)));

        $permissions = Permission::query()
            ->withCount('roles')
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('group_name', 'like', "%{$search}%");
                });
            })
            ->when($request->string('status')->toString(), function ($query, string $status) {
                if ($status === 'deleted') {
                    $query->onlyTrashed();
                } else {
                    $query->where('status', $status);
                }
            })
            ->orderBy('group_name')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('permissions.partials.results', compact('permissions'));
        }

        return view('permissions.index', compact('permissions'));
    }

    public function create(Request $request): View
    {
        $payload = [
            'permission' => new Permission(['group_name' => 'dashboard']),
        ];

        if ($request->ajax()) {
            return view('permissions.modal-form', $payload + [
                'pageTitle' => 'Create Permission',
                'pageDescription' => 'Add a reusable capability that roles can inherit.',
                'formAction' => route('permissions.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Save Permission',
            ]);
        }

        return view('permissions.create', $payload);
    }

    public function store(StorePermissionRequest $request): RedirectResponse|JsonResponse
    {
        $permission = Permission::create($request->validated());

        ActivityLogService::logWithChanges(
            $request->user(),
            $permission,
            'permission.created',
            "Created permission {$permission->slug}.",
        );

        if ($request->ajax()) {
            session()->flash('status', 'Permission created successfully.');
            return response()->json([
                'status' => 'success',
                'message' => 'Permission created successfully.',
            ]);
        }

        return redirect()
            ->route('permissions.index')
            ->with('status', 'Permission created successfully.');
    }

    public function show(Request $request, Permission $permission): View
    {
        $permission->load('roles');

        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', $permission->getMorphClass())
            ->where('subject_id', $permission->getKey())
            ->latest()
            ->take(10)
            ->get();

        if ($request->ajax()) {
            return view('permissions.modal-show', compact('permission', 'activities'));
        }

        return view('permissions.show', compact('permission', 'activities'));
    }

    public function edit(Request $request, Permission $permission): View
    {
        if ($request->ajax()) {
            return view('permissions.modal-form', [
                'permission' => $permission,
                'pageTitle' => 'Edit Permission',
                'pageDescription' => "Update labels and grouping for {$permission->slug}.",
                'formAction' => route('permissions.update', $permission),
                'formMethod' => 'PUT',
                'submitLabel' => 'Update Permission',
            ]);
        }

        return view('permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse|JsonResponse
    {
        $permission->update($request->validated());

        ActivityLogService::logWithChanges(
            $request->user(),
            $permission,
            'permission.updated',
            "Updated permission {$permission->slug}.",
        );

        if ($request->ajax()) {
            session()->flash('status', 'Permission updated successfully.');
            return response()->json([
                'status' => 'success',
                'message' => 'Permission updated successfully.',
            ]);
        }

        return redirect()
            ->route('permissions.show', $permission)
            ->with('status', 'Permission updated successfully.');
    }

    public function destroy(Request $request, Permission $permission): RedirectResponse
    {
        ActivityLogService::log(
            $request->user(), // ✅ FIXED
            'permission.deleted',
            $permission,
            "Deleted permission {$permission->slug}.",
        );

        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('status', 'Permission deleted successfully.');
    }

    public function toggleStatus(Request $request, Permission $permission): JsonResponse
    {
        $oldStatus = $permission->status;

        // ✅ safer toggle
        $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';

        $permission->update(['status' => $newStatus]);

        ActivityLogService::log(
            $request->user(), // ✅ FIXED
            'permission.status_toggled',
            $permission,
            "Changed status for {$permission->slug} from {$oldStatus} to {$newStatus}.",
            ['old' => $oldStatus, 'new' => $newStatus],
        );

        session()->flash('status', "Permission {$permission->slug} is now {$newStatus}.");

        return response()->json([
            'status' => 'success',
            'message' => "Permission {$permission->slug} is now {$newStatus}.",
            'new_status' => $newStatus,
        ]);
    }

    public function restore($id): RedirectResponse
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->restore();

        ActivityLogService::log(
            auth()->user(),
            'permission.restored',
            $permission,
            "Restored permission {$permission->slug}.",
        );

        return redirect()
            ->route('permissions.index', ['status' => 'deleted'])
            ->with('status', 'Permission restored successfully.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $permission = Permission::withTrashed()->findOrFail($id);

        ActivityLogService::log(
            auth()->user(),
            'permission.permanently_deleted',
            $permission,
            "Permanently deleted permission {$permission->slug}.",
        );

        $permission->forceDelete();

        return redirect()
            ->route('permissions.index', ['status' => 'deleted'])
            ->with('status', 'Permission permanently deleted successfully.');
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
            return response()->json(['status' => 'error', 'message' => 'Unauthorized for this destructive action.'], 403);
        }

        try {
            $affectedPermissions = Permission::withTrashed()->whereIn('id', $ids)->pluck('slug')->all();

            \Illuminate\Support\Facades\DB::transaction(function () use ($action, $ids, $affectedPermissions) {
                $query = Permission::withTrashed()->whereIn('id', $ids);
                
                switch ($action) {
                    case 'delete':
                        $query->delete();
                        $this->logBulkAction('permission.bulk_deleted', $ids, "Soft-deleted " . count($ids) . " permissions.", ['affected_permissions' => $affectedPermissions]);
                        break;
                    case 'restore':
                        $query->restore();
                        $this->logBulkAction('permission.bulk_restored', $ids, "Restored " . count($ids) . " permissions.", ['affected_permissions' => $affectedPermissions]);
                        break;
                    case 'force-delete':
                        $query->forceDelete();
                        $this->logBulkAction('permission.bulk_permanently_deleted', $ids, "Permanently deleted " . count($ids) . " permissions.", ['affected_permissions' => $affectedPermissions]);
                        break;
                }
            });

            $message = count($ids) . ' permissions processed successfully.';
            session()->flash('status', $message);

            return response()->json([
                'status' => 'success',
                'message' => $message,
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