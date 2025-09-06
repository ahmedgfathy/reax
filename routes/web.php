<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController; // Add this
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadImportExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TeamController;
use Illuminate\Http\Request; // Import Request at the top of the file
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\BranchController; // Import BranchController at the top with other use statements

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
// Update the logout route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Property routes
    Route::post('/properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
    Route::post('/properties/import', [PropertyController::class, 'import'])->name('properties.import');
    Route::get('/properties/export', [PropertyController::class, 'export'])->name('properties.export');
    Route::resource('properties', PropertyController::class);
    
    Route::post('/properties/{property}/toggle-published', [PropertyController::class, 'togglePublished'])
        ->name('properties.toggle-published');
    
    // Lead routes - FIXED ORDER
    // The bulk action route must be defined BEFORE the resource route
    Route::post('/leads/bulk-action', [LeadController::class, 'bulkAction'])->name('leads.bulk-action');
    Route::post('/leads/import', [LeadImportExportController::class, 'import'])->name('leads.import');
    Route::post('/leads/process-import', [LeadController::class, 'processImport'])->name('leads.process-import');
    Route::post('/leads/export', [LeadImportExportController::class, 'export'])->name('leads.export');
    Route::get('/leads/{lead}/print', [LeadController::class, 'print'])->name('leads.print');
    Route::post('/leads/{lead}/note', [LeadController::class, 'addNote'])->name('leads.add-note');
    Route::resource('leads', LeadController::class);
    
    // Event routes
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}/complete', [EventController::class, 'complete'])->name('events.complete');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // Report routes
    Route::resource('reports', \App\Http\Controllers\ReportController::class);
    Route::post('/reports/preview', [\App\Http\Controllers\ReportController::class, 'preview'])->name('reports.preview');
    Route::get('/reports/{report}/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::post('/reports/{report}/share', [\App\Http\Controllers\ReportController::class, 'share'])->name('reports.share');
    Route::post('/reports/{report}/schedule', [\App\Http\Controllers\ReportController::class, 'schedule'])->name('reports.schedule');

    // Profile routes with unique names - this fixes the conflict
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::patch('/profile/update', 'update')->name('custom.profile.update');  // Changed from profile.update
        Route::put('/profile/password', 'changePassword')->name('profile.password.update');
        Route::post('/profile/avatar', 'updateAvatar')->name('profile.avatar.update');
        Route::delete('/profile/avatar', 'removeAvatar')->name('profile.avatar.remove');
    });

    // Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');

    // Opportunities Routes - SINGLE DEFINITION
    Route::resource('opportunities', \App\Http\Controllers\OpportunityController::class);
    Route::post('opportunities/bulk-action', [\App\Http\Controllers\OpportunityController::class, 'bulkAction'])
        ->name('opportunities.bulk-action');

    // Company Management (needed for multi-tenancy)
    Route::resource('companies', \App\Http\Controllers\CompanyController::class);

    Route::resource('branches', BranchController::class);
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
    Route::resource('teams', \App\Http\Controllers\TeamController::class);

    // Team Member Management
    Route::get('/teams/{team}/members/assign', [TeamMemberController::class, 'assignForm'])
        ->name('teams.members.assign-form');
    Route::post('/teams/{team}/members/store', [TeamMemberController::class, 'store'])
        ->name('teams.members.add');
});

// Language and locale routes
Route::post('/locale/switch', [LocaleController::class, 'switchLocale'])
    ->name('locale.switch')
    ->middleware('web');

// Simple GET routes for language switching
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Management Routes
Route::middleware(['auth'])->prefix('management')->name('management.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ManagementController::class, 'index'])->name('index');
    
    // Territory Management
    Route::get('/territories', [\App\Http\Controllers\ManagementController::class, 'territories'])->name('territories.index');
    Route::get('/territories/create', [\App\Http\Controllers\ManagementController::class, 'createTerritory'])->name('territories.create');
    Route::post('/territories', [\App\Http\Controllers\ManagementController::class, 'storeTerritory'])->name('territories.store');
    Route::get('/territories/{territory}', [\App\Http\Controllers\ManagementController::class, 'showTerritory'])->name('territories.show');
    Route::get('/territories/{territory}/edit', [\App\Http\Controllers\ManagementController::class, 'editTerritory'])->name('territories.edit');
    Route::put('/territories/{territory}', [\App\Http\Controllers\ManagementController::class, 'updateTerritory'])->name('territories.update');
    Route::delete('/territories/{territory}', [\App\Http\Controllers\ManagementController::class, 'destroyTerritory'])->name('territories.destroy');
    
    // Goal Management
    Route::get('/goals', [\App\Http\Controllers\ManagementController::class, 'goals'])->name('goals.index');
    Route::get('/goals/create', [\App\Http\Controllers\ManagementController::class, 'createGoal'])->name('goals.create');
    Route::post('/goals', [\App\Http\Controllers\ManagementController::class, 'storeGoal'])->name('goals.store');
    Route::get('/goals/{goal}', [\App\Http\Controllers\ManagementController::class, 'showGoal'])->name('goals.show');
    Route::get('/goals/{goal}/edit', [\App\Http\Controllers\ManagementController::class, 'editGoal'])->name('goals.edit');
    Route::put('/goals/{goal}', [\App\Http\Controllers\ManagementController::class, 'updateGoal'])->name('goals.update');
    
    // Performance Analytics
    Route::get('/performance', [\App\Http\Controllers\ManagementController::class, 'performance'])->name('performance.index');
    

    // Team Activities
    Route::get('/activities', [\App\Http\Controllers\ManagementController::class, 'activities'])->name('activities.index');
});
