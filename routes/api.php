<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('api.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('api.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

        Route::middleware('permission:users.view')->get('/users', [UserController::class, 'index']);
        Route::middleware('permission:users.create')->post('/users', [UserController::class, 'store']);
        Route::middleware('permission:users.view')->get('/users/{user}', [UserController::class, 'show']);
        Route::middleware('permission:users.update')->match(['put', 'patch'], '/users/{user}', [UserController::class, 'update']);
        Route::middleware('permission:users.delete')->delete('/users/{user}', [UserController::class, 'destroy']);

        Route::middleware('permission:roles.view')->get('/roles', [RoleController::class, 'index']);
        Route::middleware('permission:roles.create')->post('/roles', [RoleController::class, 'store']);
        Route::middleware('permission:roles.view')->get('/roles/{role}', [RoleController::class, 'show']);
        Route::middleware('permission:roles.update')->match(['put', 'patch'], '/roles/{role}', [RoleController::class, 'update']);
        Route::middleware('permission:roles.delete')->delete('/roles/{role}', [RoleController::class, 'destroy']);

        Route::middleware('permission:permissions.view')->get('/permissions', [PermissionController::class, 'index']);
        Route::middleware('permission:permissions.create')->post('/permissions', [PermissionController::class, 'store']);
        Route::middleware('permission:permissions.view')->get('/permissions/{permission}', [PermissionController::class, 'show']);
        Route::middleware('permission:permissions.update')->match(['put', 'patch'], '/permissions/{permission}', [PermissionController::class, 'update']);
        Route::middleware('permission:permissions.delete')->delete('/permissions/{permission}', [PermissionController::class, 'destroy']);

        Route::middleware('permission:activities.view')->get('/activity-logs', [ActivityLogController::class, 'index']);

        Route::get('/health-records', [\App\Http\Controllers\Api\HealthRecordController::class, 'index']);
        Route::post('/health-records', [\App\Http\Controllers\Api\HealthRecordController::class, 'store']);
        Route::get('/health-records/{healthRecord}', [\App\Http\Controllers\Api\HealthRecordController::class, 'show']);
        Route::match(['put', 'patch'], '/health-records/{healthRecord}', [\App\Http\Controllers\Api\HealthRecordController::class, 'update']);
        Route::delete('/health-records/{healthRecord}', [\App\Http\Controllers\Api\HealthRecordController::class, 'destroy']);

        Route::get('/companies', [\App\Http\Controllers\Api\CompanyController::class, 'index']);
        Route::get('/companies/{company}', [\App\Http\Controllers\Api\CompanyController::class, 'show']);
    });
});
