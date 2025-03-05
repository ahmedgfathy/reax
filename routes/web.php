<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadImportExportController;

// Home route using HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');

// Featured Properties route
Route::get('/featured-properties', [HomeController::class, 'featuredProperties'])->name('featured.properties');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
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
});

// Locale route
Route::post('/locale', function () {
    $locale = request('locale');
    Session::put('locale', $locale);
    App::setLocale($locale);
    return Redirect::back();
})->name('locale.switch');

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
