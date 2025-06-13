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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadImportExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TeamController;
use Illuminate\Http\Request; // Import Request at the top of the file
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\BranchController; // Import BranchController at the top with other use statements

// Public routes - place these before any auth routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sale', [PropertyController::class, 'sale'])->name('sale');
Route::get('/rent', [PropertyController::class, 'rent'])->name('rent');
Route::get('/featured-properties', [HomeController::class, 'featuredProperties'])->name('featured.properties');

// Move these routes BEFORE the auth middleware group
// Route::get('/compounds', [App\Http\Controllers\CompoundController::class, 'index'])->name('compounds.index');
// Route::get('/compounds/{compound}', [App\Http\Controllers\CompoundController::class, 'show'])->name('compounds.show');

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
    Route::post('/properties/export', [PropertyController::class, 'export'])->name('properties.export');
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

    // Contact Management Routes with full namespace
    Route::resource('contacts', \App\Http\Controllers\ContactController::class);
    Route::resource('companies', \App\Http\Controllers\CompanyController::class);

    Route::resource('branches', BranchController::class); // Add this line inside middleware auth group

    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);

    // Management Routes
    Route::get('/management', [\App\Http\Controllers\ManagementController::class, 'index'])->name('management.index');

    Route::resource('teams', \App\Http\Controllers\TeamController::class); // Add team routes

    Route::get('/teams/{team}/members/assign', [TeamController::class, 'assignMembersForm'])->name('teams.members.assign');
    Route::post('/teams/{team}/members/assign', [TeamController::class, 'assignMembers'])->name('teams.members.store');

    // Team Member Management
    Route::get('/teams/{team}/members/assign', [TeamMemberController::class, 'assignForm'])
        ->name('teams.members.assign-form');
    Route::post('/teams/{team}/members/store', [TeamMemberController::class, 'store'])
        ->name('teams.members.add');
});

// Remove or comment out the old locale route
// Route::post('/locale', function () { ... });

// Fix the language switch route
Route::post('/locale/switch', [LocaleController::class, 'switchLocale'])
    ->name('locale.switch')
    ->middleware('web');

Route::get('/locale-debug', function () {
    return [
        'locale' => App::getLocale(),
        'session_locale' => Session::get('locale'),
    ];
});

// Debug route to check if HomeController is working
Route::get('/home-debug', [HomeController::class, 'index']);

// Debug routes
Route::get('/debug-routes', function() {
    $routeCollection = Route::getRoutes();
    
    echo "<h1>Routes</h1>";
    echo "<table border='1'>";
    echo "<tr>";
    echo "<td><b>HTTP Method</b></td>";
    echo "<td><b>Route</b></td>";
    echo "<td><b>Name</b></td>";
    echo "<td><b>Controller</b></td>";
    echo "</tr>";
    
    foreach ($routeCollection as $value) {
        echo "<tr>";
        echo "<td>" . implode('|', $value->methods()) . "</td>";
        echo "<td>" . $value->uri() . "</td>";
        echo "<td>" . $value->getName() . "</td>";
        echo "<td>" . $value->getActionName() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
});

// Add a debug route to check if the bulk action route is properly registered
Route::get('/debug-bulk-action', function () {
    $routeName = 'leads.bulk-action';
    $routeExists = \Illuminate\Support\Facades\Route::has($routeName);
    $route = collect(\Illuminate\Support\Facades\Route::getRoutes())->first(function ($route) use ($routeName) {
        return $route->getName() === $routeName;
    });
    
    return [
        'route_exists' => $routeExists,
        'route_details' => $route ? [
            'uri' => $route->uri(),
            'methods' => $route->methods(),
            'action' => $route->getActionName(),
        ] : null,
        'all_routes' => collect(\Illuminate\Support\Facades\Route::getRoutes())
            ->map(function ($route) {
                return [
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'methods' => $route->methods(),
                ];
            })
            ->filter(function ($route) {
                return str_contains($route['uri'] ?? '', 'lead');
            })
            ->values()
            ->toArray()
    ];
});

// You can also add this debug route to test bulk actions directly
Route::get('/debug/bulk-action-test', function() {
    return view('debug.bulk-action-test', [
        'users' => \App\Models\User::all(),
        'leads' => \App\Models\Lead::take(5)->get()
    ]);
});

// Administration Routes
Route::prefix('administration')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdministrationController::class, 'index'])->name('administration.index');
});

// Systems Routes
Route::prefix('systems')->group(function () {
    Route::get('/', [\App\Http\Controllers\SystemController::class, 'index'])->name('systems.index');
});

// Add Management Routes
Route::prefix('management')->group(function () {
    Route::get('/', [\App\Http\Controllers\ManagementController::class, 'index'])->name('management.index');
});

// Employee Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);

    // Property Routes  
    Route::resource('properties', \App\Http\Controllers\PropertyController::class);
});

// Report Routes
Route::prefix('reports')->group(function () {
    Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::post('/preview', [\App\Http\Controllers\ReportController::class, 'preview'])->name('reports.preview');
    Route::get('/{report}/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::post('/{report}/share', [\App\Http\Controllers\ReportController::class, 'share'])->name('reports.share');
    Route::post('/{report}/schedule', [\App\Http\Controllers\ReportController::class, 'schedule'])->name('reports.schedule');
});

// REMOVE these duplicate route definitions:
// Route::prefix('opportunities')->group(function () {
//     Route::get('/', [\App\Http\Controllers\OpportunityController::class, 'index'])->name('opportunities.index');
// });

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('administration')->name('administration.')->group(function () {
    Route::get('/', [AdministrationController::class, 'index'])->name('index');
    Route::resource('employees', EmployeeController::class);
    Route::resource('teams', TeamController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('branches', BranchController::class);
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});

// Add AI Debug routes in admin middleware group
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/debug', [AIDebugController::class, 'index'])->name('debug');
        Route::post('/analyze', [AIDebugController::class, 'analyze'])->name('analyze');
    });
});

// Include admin routes
require __DIR__ . '/admin.php';
