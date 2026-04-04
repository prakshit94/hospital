<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $summary = [
            'users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'activities' => ActivityLog::count(),
        ];

        $recentUsers = User::with('roles')->latest()->take(10)->get();
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->get();
        $permissionGroups = Permission::selectRaw('group_name, count(*) as total')
            ->groupBy('group_name')
            ->orderBy('group_name')
            ->get();
        $recentActivities = ActivityLog::with('causer')->latest()->take(12)->get();

        return view('reports.index', compact('summary', 'recentUsers', 'roles', 'permissionGroups', 'recentActivities'));
    }

    public function export(Request $request, string $dataset): StreamedResponse
    {
        $map = [
            'users' => [
                'filename' => 'users-report.csv',
                'headers' => ['ID', 'Name', 'Email', 'Status', 'Roles', 'Last Login', 'Created At'],
                'rows' => User::with('roles')->orderBy('id')->get()->map(fn (User $user) => [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->status,
                    $user->roles->pluck('name')->implode(', '),
                    optional($user->last_login_at)?->toDateTimeString(),
                    optional($user->created_at)?->toDateTimeString(),
                ])->all(),
            ],
            'roles' => [
                'filename' => 'roles-report.csv',
                'headers' => ['ID', 'Name', 'Slug', 'System Role', 'Users', 'Permissions'],
                'rows' => Role::withCount(['users', 'permissions'])->orderBy('id')->get()->map(fn (Role $role) => [
                    $role->id,
                    $role->name,
                    $role->slug,
                    $role->is_system ? 'Yes' : 'No',
                    $role->users_count,
                    $role->permissions_count,
                ])->all(),
            ],
            'permissions' => [
                'filename' => 'permissions-report.csv',
                'headers' => ['ID', 'Name', 'Slug', 'Group', 'Description'],
                'rows' => Permission::orderBy('id')->get()->map(fn (Permission $permission) => [
                    $permission->id,
                    $permission->name,
                    $permission->slug,
                    $permission->group_name,
                    $permission->description,
                ])->all(),
            ],
            'activities' => [
                'filename' => 'activities-report.csv',
                'headers' => ['ID', 'Action', 'Description', 'Actor', 'IP Address', 'Created At'],
                'rows' => ActivityLog::with('causer')->latest('id')->get()->map(fn (ActivityLog $activity) => [
                    $activity->id,
                    $activity->action,
                    $activity->description,
                    $activity->causer?->email ?? 'System',
                    $activity->ip_address,
                    optional($activity->created_at)?->toDateTimeString(),
                ])->all(),
            ],
        ];

        abort_unless(isset($map[$dataset]), 404);

        $export = $map[$dataset];

        return response()->streamDownload(function () use ($export) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $export['headers']);

            foreach ($export['rows'] as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $export['filename'], [
            'Content-Type' => 'text/csv',
        ]);
    }
}
