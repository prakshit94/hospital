<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            ['label' => 'Users', 'value' => number_format(User::count()), 'change' => 'Managed accounts', 'trend' => 'up'],
            ['label' => 'Roles', 'value' => number_format(Role::count()), 'change' => 'RBAC roles', 'trend' => 'up'],
            ['label' => 'Permissions', 'value' => number_format(Permission::count()), 'change' => 'System abilities', 'trend' => 'up'],
            ['label' => 'Activities', 'value' => number_format(ActivityLog::count()), 'change' => 'Audit entries', 'trend' => 'up'],
        ];

        $activities = ActivityLog::query()
            ->with('causer')
            ->latest()
            ->take(6)
            ->get();

        $users = User::query()
            ->with('roles')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'activities', 'users'));
    }
}
