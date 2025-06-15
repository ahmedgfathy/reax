<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ManagementController;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

class TestManagementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:management';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test management functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Management Module...');
        
        // Create a test company if none exists
        $company = Company::first();
        if (!$company) {
            $company = Company::create([
                'name' => 'Test Company',
                'email' => 'test@company.com',
                'is_active' => true
            ]);
            $this->info('Created test company: ' . $company->name);
        }
        
        // Create a test user if none exists
        $user = User::where('email', 'admin@test.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
                'company_id' => $company->id,
                'is_admin' => true,
                'is_active' => true
            ]);
            $this->info('Created test user: ' . $user->email);
        }
        
        // Test Management Controller
        auth()->login($user);
        
        try {
            $controller = new ManagementController();
            $request = new Request();
            
            $response = $controller->index($request);
            $this->info('✅ Management index method works!');
            
            // Test territories method
            $response = $controller->territories();
            $this->info('✅ Management territories method works!');
            
            // Test goals method
            $response = $controller->goals();
            $this->info('✅ Management goals method works!');
            
            // Test performance method
            $response = $controller->performance();
            $this->info('✅ Management performance method works!');
            
            // Test activities method
            $response = $controller->activities();
            $this->info('✅ Management activities method works!');
            
            $this->info('🎉 All Management methods are working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }
        
        auth()->logout();
    }
}
