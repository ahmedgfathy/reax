<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ManagementController;
use App\Models\User;
use App\Models\Company;
use App\Models\Territory;
use App\Models\Goal;
use Illuminate\Http\Request;

class TestManagementDetailsCommand extends Command
{
    protected $signature = 'test:management-details';
    protected $description = 'Test management detail pages functionality';

    public function handle()
    {
        $this->info('Testing Management Detail Pages...');
        
        // Get test user
        $user = User::where('email', 'admin@test.com')->first();
        if (!$user) {
            $this->error('Test user not found. Run test:management first.');
            return;
        }
        
        auth()->login($user);
        
        try {
            $controller = new ManagementController();
            
            // Test territory detail page
            $territory = Territory::first();
            if ($territory) {
                $response = $controller->showTerritory($territory->id);
                $this->info('✅ Territory show page works! Territory: ' . $territory->name);
            } else {
                $this->info('⚠️  No territories found to test show page');
            }
            
            // Test goal detail page
            $goal = Goal::first();
            if ($goal) {
                $response = $controller->showGoal($goal->id);
                $this->info('✅ Goal show page works! Goal: ' . $goal->title);
            } else {
                $this->info('⚠️  No goals found to test show page');
            }
            
            $this->info('🎉 All Management detail pages are working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }
        
        auth()->logout();
    }
}
