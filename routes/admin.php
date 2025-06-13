<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleManagementController;

/*
|--------------------------------------------------------------------------
| Administration Routes
|--------------------------------------------------------------------------
|
| These routes are used for administration functionality in the REAX CRM
| system. They're all prefixed with "administration" and require authentication.
|
*/

Route::middleware(['auth'])->prefix('administration')->name('administration.')->group(function () {
    // Main Administration Dashboard
    Route::get('/', [AdministrationController::class, 'index'])->name('index');
    
    // Employee Management
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

