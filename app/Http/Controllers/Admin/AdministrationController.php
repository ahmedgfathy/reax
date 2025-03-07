<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agency;
use App\Models\Role;
use App\Models\ActivityLog;

class AdministrationController extends Controller
{
    public function index()
    {
        // Get user statistics - Now with proper error handling
        try {
            $userCount = User::count();
            $activeUserCount = User::where('is_active', true)->count();
            $recentUsers = User::latest()->take(5)->get();
            
            // Get agency statistics
            $agencyCount = 0;
            $activeAgencyCount = 0;
            $recentAgencies = collect();
            
            if (class_exists('\App\Models\Agency')) {
                $agencyCount = Agency::count();
                $activeAgencyCount = Agency::where('is_active', true)->count();
                $recentAgencies = Agency::latest()->take(5)->get();
            }
            
            // Get role statistics
            $roleCount = class_exists('\App\Models\Role') ? Role::count() : 0;
            
            // Get recent activity logs
            $activityLogs = class_exists('\App\Models\ActivityLog') 
                ? ActivityLog::with('user')->latest()->take(10)->get() 
                : collect();

            return view('administration.index', compact(
                'userCount',
                'activeUserCount',
                'recentUsers',
                'agencyCount',
                'activeAgencyCount',
                'recentAgencies',
                'roleCount',
                'activityLogs'
            ));

        } catch (\Exception $e) {
            \Log::error('Administration Dashboard Error: ' . $e->getMessage());
            return view('administration.index')->with('error', 'Error loading administration dashboard');
        }
    }
}
