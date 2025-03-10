<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdministrationController;
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
    
    // Employee Management
    Route::resource('employees', EmployeeController::class);
});

