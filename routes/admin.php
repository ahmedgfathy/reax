<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\EmployeeController;

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
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Agency Management
    Route::resource('agencies', AgencyController::class);
    
    // Role & Permission Management
    Route::resource('roles', RoleController::class);
    
    // Employee Management Routes
    Route::resource('employees', EmployeeController::class);
    
    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('general', [SettingController::class, 'general'])->name('general');
        Route::post('general', [SettingController::class, 'saveGeneral']);
        
        Route::get('email', [SettingController::class, 'email'])->name('email');
        Route::post('email', [SettingController::class, 'saveEmail']);
        
        Route::get('security', [SettingController::class, 'security'])->name('security');
        Route::post('security', [SettingController::class, 'saveSecurity']);
    });
});
