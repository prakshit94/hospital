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
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
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
    Route::post('/notifications/mark-as-read', function () {
        auth()->user()->update(['notifications_read_at' => now()]);
        return response()->json(['status' => 'success']);
    })->name('notifications.mark-as-read');

    Route::middleware('permission:dashboard.view')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('permission:activities.view')->get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::middleware('permission:reports.view')->get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::middleware('permission:reports.export')->get('/reports/export/{dataset}', [ReportController::class, 'export'])->name('reports.export');

    Route::middleware('permission:users.view')->get('/users', [UserController::class, 'index'])->name('users.index');
    Route::middleware('permission:users.create')->get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::middleware('permission:users.create')->post('/users', [UserController::class, 'store'])->name('users.store');
    Route::middleware('permission:users.view')->get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::middleware('permission:users.update')->get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::middleware('permission:users.update')->match(['put', 'patch'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::middleware('permission:users.update')->post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::middleware('permission:users.update')->post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::middleware('permission:users.delete')->delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::middleware('permission:users.delete')->post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    Route::middleware('permission:roles.view')->get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::middleware('permission:roles.create')->get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::middleware('permission:roles.create')->post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::middleware('permission:roles.view')->get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::middleware('permission:roles.update')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::middleware('permission:roles.update')->match(['put', 'patch'], '/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::middleware('permission:roles.update')->post('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
    Route::middleware('permission:roles.delete')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::middleware('permission:permissions.view')->get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::middleware('permission:permissions.create')->get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::middleware('permission:permissions.create')->post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::middleware('permission:permissions.view')->get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::middleware('permission:permissions.update')->get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::middleware('permission:permissions.update')->match(['put', 'patch'], '/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::middleware('permission:permissions.update')->post('/permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
    Route::middleware('permission:permissions.delete')->delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});
