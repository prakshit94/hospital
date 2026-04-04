<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Services\ActivityLogService;
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
            ->orderBy('group_name')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('permissions.partials.results', compact('permissions'));
        }

        return view('permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('permissions.create', [
            'permission' => new Permission(['group_name' => 'dashboard']),
        ]);
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $permission = Permission::create($request->validated());

        ActivityLogService::log(
            $request->user(),
            'permission.created',
            $permission,
            "Created permission {$permission->slug}.",
        );

        return redirect()
            ->route('permissions.index')
            ->with('status', 'Permission created successfully.');
    }

    public function show(Permission $permission): View
    {
        $permission->load('roles');

        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', $permission->getMorphClass())
            ->where('subject_id', $permission->getKey())
            ->latest()
            ->take(10)
            ->get();

        return view('permissions.show', compact('permission', 'activities'));
    }

    public function edit(Permission $permission): View
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update($request->validated());

        ActivityLogService::log(
            $request->user(),
            'permission.updated',
            $permission,
            "Updated permission {$permission->slug}.",
        );

        return redirect()
            ->route('permissions.show', $permission)
            ->with('status', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        ActivityLogService::log(
            auth()->user(),
            'permission.deleted',
            $permission,
            "Deleted permission {$permission->slug}.",
        );

        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('status', 'Permission deleted successfully.');
    }
}
