<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PropertySettingsController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\LogController;

/*
|--------------------------------------------------------------------------
| Administration Routes
|--------------------------------------------------------------------------
|
| These routes are used for administration functionality in the REAX CRM
| system. They're all prefixed with "administration" and require authentication.
|
*/

Route::middleware(['auth', 'admin'])->prefix('administration')->name('administration.')->group(function () {
    // Main Administration Dashboard
    Route::get('/', [AdministrationController::class, 'index'])->name('index');
    
    // Backup Routes
    Route::get('/backup', [BackupController::class, 'index'])->name('backup');
    Route::post('/backup', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/{filename}/download', [BackupController::class, 'download'])->name('backup.download');
    Route::get('/backup/{filename}/restore', [BackupController::class, 'restore'])->name('backup.restore');
    Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
    
    // System Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    
    // System Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/cache/clear', [SettingsController::class, 'clearCache'])->name('cache.clear');
    Route::get('/settings/cache/optimize', [SettingsController::class, 'optimizeCache'])->name('cache.optimize');
    Route::get('/settings/mail/test', [SettingsController::class, 'testMail'])->name('mail.test');
    
    // Property Settings
    Route::get('/property/settings', [PropertySettingsController::class, 'index'])->name('property.settings');
    
    // User Management (Admin only - includes ALL users including admins)
    Route::resource('users', UserManagementController::class);
    
    // Employee Management (Non-admin users only)
    Route::resource('employees', EmployeeController::class);
    
    // Profile Management (Admin and Managers only)
    Route::resource('profiles', ProfileController::class);
    Route::post('profiles/{profile}/assign-user', [ProfileController::class, 'assignToUser'])->name('profiles.assign-user');
    
    // Role and Permission Management (Admin only)
    Route::prefix('role-management')->name('role-management.')->group(function () {
        Route::get('/', [RoleManagementController::class, 'index'])->name('index');
        Route::post('permissions', [RoleManagementController::class, 'storePermission'])->name('permissions.store');
        Route::post('assign-manager', [RoleManagementController::class, 'assignToManager'])->name('assign-manager');
        Route::put('users/{user}/role', [RoleManagementController::class, 'updateUserRole'])->name('users.update-role');
        Route::get('manageable-users', [RoleManagementController::class, 'getManageableUsers'])->name('manageable-users');
        Route::post('bulk-assign-profiles', [RoleManagementController::class, 'bulkAssignProfiles'])->name('bulk-assign-profiles');
        Route::get('hierarchy', [RoleManagementController::class, 'showHierarchy'])->name('hierarchy');
    });
});

