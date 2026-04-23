<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeHealthRecordController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/two-factor-login', [\App\Http\Controllers\Auth\TwoFactorAuthenticatedSessionController::class, 'create'])->name('two-factor.login');
    Route::post('/two-factor-login', [\App\Http\Controllers\Auth\TwoFactorAuthenticatedSessionController::class, 'store'])->name('two-factor.login.store');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/user/two-factor-authentication', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::get('/user/two-factor-qr-code', [\App\Http\Controllers\TwoFactorController::class, 'showQrCode'])->name('two-factor.qr-code');
    Route::post('/user/two-factor-confirm', [\App\Http\Controllers\TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::delete('/user/two-factor-authentication', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('two-factor.disable');

    Route::get('/devices', [\App\Http\Controllers\DeviceController::class, 'index'])->name('devices.index');
    Route::delete('/devices/{device}', [\App\Http\Controllers\DeviceController::class, 'destroy'])->name('devices.destroy');
    Route::post('/notifications/mark-as-read', function () {
        auth()->user()->update(['notifications_read_at' => now()]);
        return response()->json(['status' => 'success']);
    })->name('notifications.mark-as-read');

    Route::middleware('permission:dashboard.view')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('permission:activities.view')->get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::middleware('permission:reports.view')->get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::middleware('permission:reports.export')->get('/reports/export/{dataset}', [ReportController::class, 'export'])->name('reports.export');

    Route::middleware('permission:users.view')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    });

    Route::middleware('permission:users.create')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware('permission:users.view')->get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::middleware('permission:users.update')->get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::middleware('permission:users.update')->match(['put', 'patch'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::middleware('permission:users.update')->post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::middleware('permission:users.delete')->delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::middleware('permission:users.delete')->post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::middleware('permission:users.delete')->delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('users.force-delete');

    Route::middleware('permission:roles.view')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles/bulk-action', [RoleController::class, 'bulkAction'])->name('roles.bulk-action');
    });

    Route::middleware('permission:roles.create')->group(function () {
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    });

    Route::middleware('permission:roles.view')->get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::middleware('permission:roles.update')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::middleware('permission:roles.update')->match(['put', 'patch'], '/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::middleware('permission:roles.update')->post('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
    Route::middleware('permission:roles.delete')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::middleware('permission:roles.delete')->post('/roles/{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');
    Route::middleware('permission:roles.delete')->delete('/roles/{id}/force', [RoleController::class, 'forceDelete'])->name('roles.force-delete');

    Route::middleware('permission:permissions.view')->get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::middleware('permission:permissions.create')->get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::middleware('permission:permissions.create')->post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::middleware('permission:permissions.view')->get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::middleware('permission:permissions.update')->get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::middleware('permission:permissions.update')->match(['put', 'patch'], '/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::middleware('permission:permissions.update')->post('/permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
    Route::middleware('permission:permissions.update')->post('/permissions/bulk-action', [PermissionController::class, 'bulkAction'])->name('permissions.bulk-action');
    Route::middleware('permission:permissions.delete')->delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::middleware('permission:permissions.delete')->post('/permissions/{id}/restore', [PermissionController::class, 'restore'])->name('permissions.restore');
    Route::middleware('permission:permissions.delete')->delete('/permissions/{id}/force', [PermissionController::class, 'forceDelete'])->name('permissions.force-delete');


    // Health Records Module
    Route::middleware('permission:health_records.create')->group(function () {
        Route::get('health-records/next-employee-id', [EmployeeHealthRecordController::class, 'getNextEmployeeId'])->name('health-records.next-id');
        Route::get('health-records/create', [EmployeeHealthRecordController::class, 'create'])->name('health-records.create');
        Route::post('health-records', [EmployeeHealthRecordController::class, 'store'])->name('health-records.store');
    });

    Route::middleware('permission:health_records.view')->group(function () {
        Route::get('health-records/{record}/print', [EmployeeHealthRecordController::class, 'print'])->name('health-records.print');
        Route::get('health-records/{record}/print-form32', [EmployeeHealthRecordController::class, 'printForm32'])->name('health-records.print-form32');
        Route::get('health-records/{record}/print-form33', [EmployeeHealthRecordController::class, 'printForm33'])->name('health-records.print-form33');
        Route::get('health-records/{record}/print-all', [EmployeeHealthRecordController::class, 'printAll'])->name('health-records.print-all');
        Route::get('health-records', [EmployeeHealthRecordController::class, 'index'])->name('health-records.index');
        Route::get('health-records/{record}', [EmployeeHealthRecordController::class, 'show'])->name('health-records.show');
        Route::post('health-records/bulk-action', [EmployeeHealthRecordController::class, 'bulkAction'])->name('health-records.bulk-action');
    });

    Route::middleware('permission:health_records.update')->group(function () {
        Route::get('health-records/{record}/edit', [EmployeeHealthRecordController::class, 'edit'])->name('health-records.edit');
        Route::match(['put', 'patch'], 'health-records/{record}', [EmployeeHealthRecordController::class, 'update'])->name('health-records.update');
    });

    Route::middleware('permission:health_records.delete')->group(function () {
        Route::delete('health-records/{record}', [EmployeeHealthRecordController::class, 'destroy'])->name('health-records.destroy');
        Route::post('health-records/{uuid}/restore', [EmployeeHealthRecordController::class, 'restore'])->name('health-records.restore');
        Route::delete('health-records/{uuid}/force', [EmployeeHealthRecordController::class, 'forceDelete'])->name('health-records.force-delete');
    });

    // Company Management
    Route::middleware('permission:companies.create')->group(function () {
        Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
    });

    Route::middleware('permission:companies.view')->group(function () {
        Route::post('companies/switch', [CompanyController::class, 'switch'])->name('companies.switch');
        Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
        Route::post('companies/bulk-action', [CompanyController::class, 'bulkAction'])->name('companies.bulk-action');
    });

    Route::middleware('permission:companies.update')->group(function () {
        Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::match(['put', 'patch'], 'companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::post('companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');
    });

    Route::middleware('permission:companies.delete')->group(function () {
        Route::delete('companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::post('companies/{id}/restore', [CompanyController::class, 'restore'])->name('companies.restore');
        Route::delete('companies/{id}/force', [CompanyController::class, 'forceDelete'])->name('companies.force-delete');
    });
});

