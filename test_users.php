<?php

// Simple test script to check user access
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Test basic user query
echo "Testing user access..." . PHP_EOL;

try {
    $admin = User::where('email', 'admin@reax.com')->first();
    echo "Admin found: " . $admin->name . " (ID: " . $admin->id . ")" . PHP_EOL;
    echo "Is admin: " . ($admin->is_admin ? 'Yes' : 'No') . PHP_EOL;
    echo "Role: " . $admin->role . PHP_EOL;
    
    // Test isSuperAdmin method
    echo "isSuperAdmin(): " . ($admin->isSuperAdmin() ? 'Yes' : 'No') . PHP_EOL;
    
    // Test user query like in controller
    $query = User::query();
    if (!$admin->isSuperAdmin()) {
        $query->where('company_id', $admin->company_id);
        echo "Applied company filter for company_id: " . $admin->company_id . PHP_EOL;
    } else {
        echo "Super admin - no filter applied" . PHP_EOL;
    }
    
    $users = $query->get();
    echo "Total users found: " . $users->count() . PHP_EOL;
    
    echo "Sample users:" . PHP_EOL;
    foreach ($users->take(5) as $user) {
        echo "- " . $user->name . " (" . $user->email . ") - Role: " . ($user->role ?? 'None') . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
