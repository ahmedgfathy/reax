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

// Test route to verify file is being loaded
Route::get('/test-admin-routes', function () {
    return 'Admin routes file is being loaded!';
});

Route::middleware(['auth', 'admin'])
    ->prefix('administration')
    ->name('administration.')
    ->group(function () {
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
        Route::resource('roles', RoleManagementController::class);
    });

