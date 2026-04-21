<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\EmployeeHealthRecord;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            ['label' => 'Companies', 'value' => number_format(Company::count()), 'change' => 'Active partners', 'trend' => 'up'],
            ['label' => 'Health Records', 'value' => number_format(EmployeeHealthRecord::count()), 'change' => 'Total examinations', 'trend' => 'up'],
            ['label' => 'Users', 'value' => number_format(User::count()), 'change' => 'System accounts', 'trend' => 'up'],
            ['label' => 'Activities', 'value' => number_format(ActivityLog::count()), 'change' => 'Audit entries', 'trend' => 'up'],
        ];

        $activities = ActivityLog::query()
            ->with('causer')
            ->latest()
            ->take(8)
            ->get();

        $recentRecords = EmployeeHealthRecord::query()
            ->with('creator')
            ->latest()
            ->take(5)
            ->get();

        $companies = Company::query()
            ->withCount('healthRecords')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'activities', 'recentRecords', 'companies'));
    }
}
