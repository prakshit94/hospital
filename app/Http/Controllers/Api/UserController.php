<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 5)));

        $users = User::query()
            ->with('roles.permissions')
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->safe()->except('roles'));
        $user->roles()->sync($request->validated('roles'));
        $user->load('roles.permissions');

        ActivityLogService::log($request->user(), 'user.created.api', $user, "Created user {$user->email} via API.");

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'data' => $user,
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        $user->load('roles.permissions');

        return response()->json([
            'status' => 'success',
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
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' => $user,
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        if (auth()->id() === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot delete the account you are currently using.',
            ], 422);
        }

        ActivityLogService::log(auth()->user(), 'user.deleted.api', $user, "Deleted user {$user->email} via API.");
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.',
        ]);
    }

    public function toggleStatus(User $user): JsonResponse
    {
        if (auth()->id() === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot deactivate your own account.',
            ], 403);
        }

        $oldStatus = $user->status;
        $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        ActivityLogService::log(
            auth()->user(),
            'user.status_toggled.api',
            $user,
            "API: Changed status for {$user->email} from {$oldStatus} to {$newStatus}.",
            ['old' => $oldStatus, 'new' => $newStatus]
        );

        return response()->json([
            'status' => 'success',
            'message' => "User {$user->name} is now {$newStatus}.",
            'data' => [
                'new_status' => $newStatus,
            ],
        ]);
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => ['required', 'string', 'in:active,inactive,delete,restore,force-delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $action = $request->input('action');
        $ids = array_filter($request->input('ids'), fn($id) => $id != auth()->id());

        if (empty($ids) && !empty($request->input('ids'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'No valid users selected (you cannot perform bulk actions on yourself).',
            ], 422);
        }

        if (empty($ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No users selected.',
            ], 422);
        }

        // Action-specific permission checks
        $user = auth()->user();
        if (in_array($action, ['delete', 'restore', 'force-delete']) && !$user->hasPermission('users.delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized for this destructive action.',
            ], 403);
        }

        if (in_array($action, ['active', 'inactive']) && !$user->hasPermission('users.update')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update user status.',
            ], 403);
        }

        try {
            $affectedUsers = User::withTrashed()->whereIn('id', $ids)->pluck('email')->all();

            \Illuminate\Support\Facades\DB::transaction(function () use ($action, $ids, $affectedUsers) {
                $query = User::withTrashed()->whereIn('id', $ids);

                switch ($action) {
                    case 'delete':
                        $query->delete();
                        $this->logBulkAction('user.bulk_deleted.api', $ids, "API: Soft-deleted " . count($ids) . " users.", ['affected_users' => $affectedUsers]);
                        break;
                    case 'restore':
                        $query->restore();
                        $this->logBulkAction('user.bulk_restored.api', $ids, "API: Restored " . count($ids) . " users.", ['affected_users' => $affectedUsers]);
                        break;
                    case 'force-delete':
                        $query->forceDelete();
                        $this->logBulkAction('user.bulk_permanently_deleted.api', $ids, "API: Permanently deleted " . count($ids) . " users.", ['affected_users' => $affectedUsers]);
                        break;
                    case 'active':
                    case 'inactive':
                        User::whereIn('id', $ids)->update(['status' => $action]);
                        $this->logBulkAction('user.bulk_status_updated.api', $ids, "API: Updated status to {$action} for " . count($ids) . " users.", ['affected_users' => $affectedUsers]);
                        break;
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => count($ids) . ' users processed successfully.',
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

    public function restore($id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        ActivityLogService::log(
            auth()->user(),
            'user.restored.api',
            $user,
            "API: Restored user {$user->email}.",
        );

        return response()->json([
            'status' => 'success',
            'message' => 'User restored successfully.',
            'data' => $user,
        ]);
    }

    public function forceDelete($id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        
        if (auth()->id() === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot permanently delete the account you are currently using.',
            ], 422);
        }

        ActivityLogService::log(
            auth()->user(),
            'user.permanently_deleted.api',
            $user,
            "API: Permanently deleted user {$user->email}.",
        );

        $user->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => 'User permanently deleted successfully.',
        ]);
    }
}
