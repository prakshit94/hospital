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
            ->when($request->string('status')->toString(), function ($query, string $status) {
                if ($status === 'deleted') {
                    $query->onlyTrashed();
                } else {
                    $query->where('status', $status);
                }
            })
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
        $data = $request->safe()->except('roles', 'profile_image');

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('users', 'public');
            $data['profile_image'] = '/storage/' . $path;
        }

        $user = User::create($data);
        $user->roles()->sync($request->validated('roles'));

        ActivityLogService::logWithChanges(
            $request->user(),
            $user,
            'user.created',
            "Created user {$user->email}.",
            ['roles' => $user->roles()->pluck('name')->all()],
        );

        if ($request->ajax()) {
            session()->flash('status', 'User created successfully.');
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
        $data = $request->safe()->except(['roles', 'password', 'profile_image']);

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                $oldPath = str_replace('/storage/', '', $user->profile_image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('profile_image')->store('users', 'public');
            $data['profile_image'] = '/storage/' . $path;
        }

        $oldRoles = $user->roles()->pluck('name')->all();
        
        $user->fill($data);
        $modelChanges = ActivityLogService::getModelChanges($user);
        $user->save();
        
        $user->roles()->sync($request->validated('roles'));
        $user->load('roles');
        $newRoles = $user->roles()->pluck('name')->all();

        $extraProperties = array_merge($modelChanges, []);
        if ($oldRoles !== $newRoles) {
            $extraProperties['old_roles'] = $oldRoles;
            $extraProperties['new_roles'] = $newRoles;
        }

        ActivityLogService::log(
            $request->user(),
            'user.updated',
            $user,
            "Updated user {$user->email}.",
            $extraProperties,
        );

        if ($request->ajax()) {
            session()->flash('status', 'User updated successfully.');
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
            'user.status_toggled',
            $user,
            "Changed status for {$user->email} from {$oldStatus} to {$newStatus}.",
            ['old' => $oldStatus, 'new' => $newStatus],
        );

        session()->flash('status', "User {$user->name} is now {$newStatus}.");

        return response()->json([
            'status' => 'success',
            'message' => "User {$user->name} is now {$newStatus}.",
            'new_status' => $newStatus,
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
            return response()->json(['status' => 'error', 'message' => 'No users selected.'], 422);
        }

        // Action-specific permission checks
        $user = auth()->user();
        if (in_array($action, ['delete', 'restore', 'force-delete']) && !$user->hasPermission('users.delete')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized for this destructive action.'], 403);
        }
        
        if (in_array($action, ['active', 'inactive']) && !$user->hasPermission('users.update')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized to update user status.'], 403);
        }

        try {
            $affectedUsers = User::withTrashed()->whereIn('id', $ids)->pluck('email')->all();

            \Illuminate\Support\Facades\DB::transaction(function () use ($action, $ids, $affectedUsers) {
                $query = User::withTrashed()->whereIn('id', $ids);
                
                switch ($action) {
                    case 'delete':
                        $query->delete();
                        $this->logBulkAction('user.bulk_deleted', $ids, "Soft-deleted " . count($ids) . " users.", ['affected_users' => $affectedUsers]);
                        break;
                    case 'restore':
                        $query->restore();
                        $this->logBulkAction('user.bulk_restored', $ids, "Restored " . count($ids) . " users.", ['affected_users' => $affectedUsers]);
                        break;
                    case 'force-delete':
                        $query->forceDelete();
                        $this->logBulkAction('user.bulk_permanently_deleted', $ids, "Permanently deleted " . count($ids) . " users.", ['affected_users' => $affectedUsers]);
                        break;
                    case 'active':
                    case 'inactive':
                        User::whereIn('id', $ids)->update(['status' => $action]);
                        $this->logBulkAction('user.bulk_status_updated', $ids, "Updated status to {$action} for " . count($ids) . " users.", ['affected_users' => $affectedUsers]);
                        break;
                }
            });

            $actionLabel = [
                'active' => 'activated',
                'inactive' => 'deactivated',
                'delete' => 'deleted',
                'restore' => 'restored',
                'force-delete' => 'permanently deleted'
            ][$action] ?? 'processed';

            $message = count($ids) . " users {$actionLabel} successfully.";
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

    public function restore($id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        ActivityLogService::log(
            auth()->user(),
            'user.restored',
            $user,
            "Restored user {$user->email}.",
        );

        return redirect()
            ->route('users.index', ['status' => 'deleted'])
            ->with('status', 'User restored successfully.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'user' => 'You cannot permanently delete the account you are currently using.',
            ]);
        }

        ActivityLogService::log(
            auth()->user(),
            'user.permanently_deleted',
            $user,
            "Permanently deleted user {$user->email}.",
        );

        $user->forceDelete();

        return redirect()
            ->route('users.index', ['status' => 'deleted'])
            ->with('status', 'User permanently deleted successfully.');
    }
}
