<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\HealthRecordController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('api.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('api.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

        // User Management
        Route::prefix('users')->group(function () {
            Route::middleware('permission:users.view')->get('/', [UserController::class, 'index']);
            Route::middleware('permission:users.create')->post('/', [UserController::class, 'store']);
            Route::middleware('permission:users.view')->get('/{user}', [UserController::class, 'show']);
            Route::middleware('permission:users.update')->match(['put', 'patch'], '/{user}', [UserController::class, 'update']);
            Route::middleware('permission:users.delete')->delete('/{user}', [UserController::class, 'destroy']);
            
            Route::middleware('permission:users.update')->post('/{user}/toggle-status', [UserController::class, 'toggleStatus']);
            Route::middleware('permission:users.update')->post('/bulk-action', [UserController::class, 'bulkAction']);
            Route::middleware('permission:users.delete')->post('/{id}/restore', [UserController::class, 'restore']);
            Route::middleware('permission:users.delete')->delete('/{id}/force', [UserController::class, 'forceDelete']);
        });

        // Role Management
        Route::prefix('roles')->group(function () {
            Route::middleware('permission:roles.view')->get('/', [RoleController::class, 'index']);
            Route::middleware('permission:roles.create')->post('/', [RoleController::class, 'store']);
            Route::middleware('permission:roles.view')->get('/{role}', [RoleController::class, 'show']);
            Route::middleware('permission:roles.update')->match(['put', 'patch'], '/{role}', [RoleController::class, 'update']);
            Route::middleware('permission:roles.delete')->delete('/{role}', [RoleController::class, 'destroy']);
            
            Route::middleware('permission:roles.update')->post('/bulk-action', [RoleController::class, 'bulkAction']);
            Route::middleware('permission:roles.delete')->post('/{id}/restore', [RoleController::class, 'restore']);
            Route::middleware('permission:roles.delete')->delete('/{id}/force', [RoleController::class, 'forceDelete']);
        });

        // Permission Management
        Route::prefix('permissions')->group(function () {
            Route::middleware('permission:permissions.view')->get('/', [PermissionController::class, 'index']);
            Route::middleware('permission:permissions.create')->post('/', [PermissionController::class, 'store']);
            Route::middleware('permission:permissions.view')->get('/{permission}', [PermissionController::class, 'show']);
            Route::middleware('permission:permissions.update')->match(['put', 'patch'], '/{permission}', [PermissionController::class, 'update']);
            Route::middleware('permission:permissions.delete')->delete('/{permission}', [PermissionController::class, 'destroy']);
            
            Route::middleware('permission:permissions.update')->post('/bulk-action', [PermissionController::class, 'bulkAction']);
            Route::middleware('permission:permissions.delete')->post('/{id}/restore', [PermissionController::class, 'restore']);
            Route::middleware('permission:permissions.delete')->delete('/{id}/force', [PermissionController::class, 'forceDelete']);
        });

        // Activity Logs
        Route::middleware('permission:activities.view')->get('/activity-logs', [ActivityLogController::class, 'index']);

        // Health Records
        Route::prefix('health-records')->group(function () {
            Route::middleware('permission:health_records.view')->get('/', [HealthRecordController::class, 'index']);
            Route::middleware('permission:health_records.create')->post('/', [HealthRecordController::class, 'store']);
            Route::middleware('permission:health_records.view')->get('/{healthRecord}', [HealthRecordController::class, 'show']);
            Route::middleware('permission:health_records.update')->match(['put', 'patch'], '/{healthRecord}', [HealthRecordController::class, 'update']);
            Route::middleware('permission:health_records.delete')->delete('/{healthRecord}', [HealthRecordController::class, 'destroy']);
            
            Route::middleware('permission:health_records.update')->post('/bulk-action', [HealthRecordController::class, 'bulkAction']);
            Route::middleware('permission:health_records.update')->post('/{id}/restore', [HealthRecordController::class, 'restore']);
            Route::middleware('permission:health_records.delete')->delete('/{id}/force', [HealthRecordController::class, 'forceDelete']);
        });

        // Company Management
        Route::prefix('companies')->group(function () {
            Route::middleware('permission:companies.view')->get('/', [CompanyController::class, 'index']);
            Route::middleware('permission:companies.create')->post('/', [CompanyController::class, 'store']);
            Route::middleware('permission:companies.view')->get('/{company}', [CompanyController::class, 'show']);
            Route::middleware('permission:companies.update')->match(['put', 'patch'], '/{company}', [CompanyController::class, 'update']);
            Route::middleware('permission:companies.delete')->delete('/{company}', [CompanyController::class, 'destroy']);
            
            Route::middleware('permission:companies.update')->post('/{company}/toggle-status', [CompanyController::class, 'toggleStatus']);
            Route::middleware('permission:companies.update')->post('/bulk-action', [CompanyController::class, 'bulkAction']);
            Route::middleware('permission:companies.update')->post('/{id}/restore', [CompanyController::class, 'restore']);
            Route::middleware('permission:companies.delete')->delete('/{id}/force', [CompanyController::class, 'forceDelete']);
        });

        // Notifications
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
    });
});
