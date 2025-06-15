<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Property;
use App\Models\Lead;

echo "=== Admin User Check ===" . PHP_EOL;

$admin = User::where('email', 'admin@reax.com')->first();
if ($admin) {
    echo "User: " . $admin->name . PHP_EOL;
    echo "Role: " . $admin->role . PHP_EOL;
    echo "is_admin: " . $admin->is_admin . PHP_EOL;
    echo "company_id: " . $admin->company_id . PHP_EOL;
    echo "isAdmin(): " . ($admin->isAdmin() ? 'true' : 'false') . PHP_EOL;
    echo "isSuperAdmin(): " . ($admin->isSuperAdmin() ? 'true' : 'false') . PHP_EOL;
    
    echo PHP_EOL . "=== Data Access Check ===" . PHP_EOL;
    echo "Total Properties in System: " . Property::count() . PHP_EOL;
    echo "Properties for admin's company: " . Property::where('company_id', $admin->company_id)->count() . PHP_EOL;
    echo "Total Leads in System: " . Lead::count() . PHP_EOL;
    echo "Leads for admin's company: " . Lead::where('company_id', $admin->company_id)->count() . PHP_EOL;
} else {
    echo "Admin user not found!" . PHP_EOL;
}
