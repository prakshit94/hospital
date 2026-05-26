<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Company;
use App\Models\HealthCheckup;
use App\Models\Employee;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            ['label' => 'Companies', 'value' => number_format(Company::count()), 'change' => 'Active partners', 'trend' => 'up'],
            ['label' => 'Total Checkups', 'value' => number_format(HealthCheckup::count()), 'change' => 'Medical examinations', 'trend' => 'up'],
            ['label' => 'Employees', 'value' => number_format(Employee::count()), 'change' => 'Total registered', 'trend' => 'up'],
            ['label' => 'Users', 'value' => number_format(User::count()), 'change' => 'System accounts', 'trend' => 'up'],
        ];

        $activities = ActivityLog::query()
            ->with('causer')
            ->latest()
            ->take(8)
            ->get();

        $recentRecords = HealthCheckup::query()
            ->with(['employee', 'creator'])
            ->latest()
            ->take(5)
            ->get();

        $companies = Company::query()
            ->withCount('healthCheckups')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'activities', 'recentRecords', 'companies'));
    }
}
