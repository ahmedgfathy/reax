<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Team;
use App\Models\Department;
use App\Models\Branch;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Profile;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdministrationController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            $isSuperAdmin = $user->isSuperAdmin();
            
            // Basic Statistics
            $stats = $this->getComprehensiveStats($user, $isSuperAdmin);
            
            // System Health Metrics
            $systemHealth = $this->getSystemHealthMetrics();
            
            // Recent Activity Feed
            $recentActivity = $this->getRecentActivity($user, $isSuperAdmin);
            
            // Performance Analytics
            $performanceMetrics = $this->getPerformanceMetrics($user, $isSuperAdmin);
            
            // User Analytics
            $userAnalytics = $this->getUserAnalytics($user, $isSuperAdmin);
            
            // Security Overview
            $securityOverview = $this->getSecurityOverview($user, $isSuperAdmin);
            
            // Quick Actions based on role
            $quickActions = $this->getQuickActions($user);

            return view('administration.index', compact(
                'stats',
                'systemHealth',
                'recentActivity',
                'performanceMetrics',
                'userAnalytics',
                'securityOverview',
                'quickActions',
                'isSuperAdmin'
            ));

        } catch (\Exception $e) {
            \Log::error('Administration Dashboard Error: ' . $e->getMessage());
            return back()->with('error', __('Error loading dashboard data'));
        }
    }
    
    private function getComprehensiveStats($user, $isSuperAdmin)
    {
        $baseQuery = $isSuperAdmin ? [] : ['company_id' => $user->company_id];
        
        return [
            'users' => [
                'total' => $isSuperAdmin ? User::count() : User::where('company_id', $user->company_id)->count(),
                'active' => $isSuperAdmin ? User::where('is_active', true)->count() : User::where('company_id', $user->company_id)->where('is_active', true)->count(),
                'admins' => $isSuperAdmin ? User::where('is_admin', true)->count() : User::where('company_id', $user->company_id)->where('is_admin', true)->count(),
                'recent' => $isSuperAdmin ? User::latest()->take(5)->get() : User::where('company_id', $user->company_id)->latest()->take(5)->get(),
                'new_this_month' => $isSuperAdmin ? 
                    User::whereMonth('created_at', Carbon::now()->month)->count() :
                    User::where('company_id', $user->company_id)->whereMonth('created_at', Carbon::now()->month)->count()
            ],
            'companies' => [
                'total' => $isSuperAdmin ? Company::count() : 1,
                'active' => $isSuperAdmin ? Company::where('is_active', true)->count() : ($user->company->is_active ? 1 : 0),
                'new_this_month' => $isSuperAdmin ? Company::whereMonth('created_at', Carbon::now()->month)->count() : 0
            ],
            'properties' => [
                'total' => $isSuperAdmin ? Property::count() : Property::where('company_id', $user->company_id)->count(),
                'featured' => $isSuperAdmin ? Property::where('is_featured', true)->count() : Property::where('company_id', $user->company_id)->where('is_featured', true)->count(),
                'published' => $isSuperAdmin ? Property::where('is_published', true)->count() : Property::where('company_id', $user->company_id)->where('is_published', true)->count()
            ],
            'leads' => [
                'total' => $isSuperAdmin ? Lead::count() : Lead::where('company_id', $user->company_id)->count(),
                'active' => $isSuperAdmin ? Lead::whereNotIn('status', ['won', 'lost'])->count() : Lead::where('company_id', $user->company_id)->whereNotIn('status', ['won', 'lost'])->count(),
                'won_this_month' => $isSuperAdmin ? 
                    Lead::where('status', 'won')->whereMonth('updated_at', Carbon::now()->month)->count() :
                    Lead::where('company_id', $user->company_id)->where('status', 'won')->whereMonth('updated_at', Carbon::now()->month)->count()
            ],
            'teams' => [
                'total' => $isSuperAdmin ? Team::count() : Team::where('company_id', $user->company_id)->count(),
                'active' => $isSuperAdmin ? Team::count() : Team::where('company_id', $user->company_id)->count()
            ],
            'departments' => [
                'total' => $isSuperAdmin ? Department::count() : Department::where('company_id', $user->company_id)->count(),
                'active' => $isSuperAdmin ? Department::where('is_active', true)->count() : Department::where('company_id', $user->company_id)->where('is_active', true)->count()
            ],
            'branches' => [
                'total' => $isSuperAdmin ? Branch::count() : Branch::where('company_id', $user->company_id)->count(),
                'active' => $isSuperAdmin ? Branch::where('is_active', true)->count() : Branch::where('company_id', $user->company_id)->where('is_active', true)->count()
            ]
        ];
    }
    
    private function getSystemHealthMetrics()
    {
        return [
            'database_size' => $this->getDatabaseSize(),
            'storage_used' => $this->getStorageUsed(),
            'active_sessions' => $this->getActiveSessions(),
            'system_uptime' => $this->getSystemUptime(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_space' => $this->getDiskSpace()
        ];
    }
    
    private function getRecentActivity($user, $isSuperAdmin)
    {
        $query = ActivityLog::with(['user', 'company'])
            ->latest()
            ->take(10);
            
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        return $query->get();
    }
    
    private function getPerformanceMetrics($user, $isSuperAdmin)
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        return [
            'conversion_rate' => $this->calculateConversionRate($user, $isSuperAdmin, $startDate, $endDate),
            'average_deal_size' => $this->calculateAverageDealSize($user, $isSuperAdmin, $startDate, $endDate),
            'sales_velocity' => $this->calculateSalesVelocity($user, $isSuperAdmin, $startDate, $endDate),
            'team_performance' => $this->getTeamPerformance($user, $isSuperAdmin, $startDate, $endDate)
        ];
    }
    
    private function getUserAnalytics($user, $isSuperAdmin)
    {
        $query = $isSuperAdmin ? User::query() : User::where('company_id', $user->company_id);
        
        return [
            'by_role' => $query->selectRaw('role, COUNT(*) as count')->groupBy('role')->pluck('count', 'role'),
            'by_status' => $query->selectRaw('is_active, COUNT(*) as count')->groupBy('is_active')->pluck('count', 'is_active'),
            'login_activity' => $this->getLoginActivity($user, $isSuperAdmin),
            'registration_trends' => $this->getRegistrationTrends($user, $isSuperAdmin)
        ];
    }
    
    private function getSecurityOverview($user, $isSuperAdmin)
    {
        return [
            'failed_logins' => $this->getFailedLogins($user, $isSuperAdmin),
            'suspicious_activity' => $this->getSuspiciousActivity($user, $isSuperAdmin),
            'permission_changes' => $this->getPermissionChanges($user, $isSuperAdmin),
            'security_score' => $this->calculateSecurityScore($user, $isSuperAdmin)
        ];
    }
    
    private function getQuickActions($user)
    {
        $actions = [
            'user_management' => [
                'icon' => 'fas fa-users',
                'title' => __('User Management'),
                'url' => route('administration.users.index'),
                'color' => 'blue'
            ],
            'role_management' => [
                'icon' => 'fas fa-users-cog',
                'title' => __('Role Management'),
                'url' => route('administration.role-management.index'),
                'color' => 'purple'
            ],
            'profile_management' => [
                'icon' => 'fas fa-id-card',
                'title' => __('Profile Management'),
                'url' => route('administration.profiles.index'),
                'color' => 'indigo'
            ]
        ];
        
        if ($user->isSuperAdmin()) {
            $actions['company_management'] = [
                'icon' => 'fas fa-building',
                'title' => __('Company Management'),
                'url' => route('companies.index'),
                'color' => 'green'
            ];
            $actions['system_settings'] = [
                'icon' => 'fas fa-cogs',
                'title' => __('System Settings'),
                'url' => route('systems.index'),
                'color' => 'gray'
            ];
        }
        
        return $actions;
    }
    
    // Helper methods for metrics calculation
    private function getDatabaseSize()
    {
        try {
            $size = DB::select("SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS size_mb 
                FROM information_schema.tables 
                WHERE table_schema = ?", [env('DB_DATABASE')]);
            return $size[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getStorageUsed()
    {
        try {
            $storageSize = 0;
            $storagePath = storage_path('app');
            if (is_dir($storagePath)) {
                $storageSize = $this->folderSize($storagePath) / 1024 / 1024; // Convert to MB
            }
            return round($storageSize, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function folderSize($dir)
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : $this->folderSize($each);
        }
        return $size;
    }
    
    private function getActiveSessions()
    {
        // This would depend on your session configuration
        return rand(10, 50); // Placeholder
    }
    
    private function getSystemUptime()
    {
        return 'Available'; // Placeholder - would need system-specific implementation
    }
    
    private function getMemoryUsage()
    {
        return round(memory_get_usage(true) / 1024 / 1024, 1); // Memory usage in MB
    }
    
    private function getDiskSpace()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        return [
            'total' => round($total / 1024 / 1024 / 1024, 1), // GB
            'used' => round($used / 1024 / 1024 / 1024, 1), // GB
            'free' => round($free / 1024 / 1024 / 1024, 1), // GB
            'percentage' => round(($used / $total) * 100, 1)
        ];
    }
    
    private function calculateConversionRate($user, $isSuperAdmin, $startDate, $endDate)
    {
        $query = $isSuperAdmin ? Lead::query() : Lead::where('company_id', $user->company_id);
        $totalLeads = $query->whereBetween('created_at', [$startDate, $endDate])->count();
        $wonLeads = $query->whereBetween('created_at', [$startDate, $endDate])->where('status', 'won')->count();
        
        return $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 1) : 0;
    }
    
    private function calculateAverageDealSize($user, $isSuperAdmin, $startDate, $endDate)
    {
        $query = $isSuperAdmin ? Lead::query() : Lead::where('company_id', $user->company_id);
        return $query->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'won')
            ->avg('estimated_value') ?? 0;
    }
    
    private function calculateSalesVelocity($user, $isSuperAdmin, $startDate, $endDate)
    {
        // Sales velocity = (Number of deals × Average deal size × Win rate) / Sales cycle length
        // Simplified calculation
        return rand(5, 15); // Placeholder
    }
    
    private function getTeamPerformance($user, $isSuperAdmin, $startDate, $endDate)
    {
        $query = $isSuperAdmin ? Team::query() : Team::where('company_id', $user->company_id);
        return $query->with(['users'])->take(5)->get();
    }
    
    private function getLoginActivity($user, $isSuperAdmin)
    {
        // This would track login activities - placeholder for now
        return [
            'today' => rand(10, 50),
            'this_week' => rand(100, 300),
            'this_month' => rand(400, 1000)
        ];
    }
    
    private function getRegistrationTrends($user, $isSuperAdmin)
    {
        $query = $isSuperAdmin ? User::query() : User::where('company_id', $user->company_id);
        
        return [
            'last_7_days' => $query->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'last_30_days' => $query->where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            'last_90_days' => $query->where('created_at', '>=', Carbon::now()->subDays(90))->count()
        ];
    }
    
    private function getFailedLogins($user, $isSuperAdmin)
    {
        // Placeholder - would track failed login attempts
        return rand(0, 10);
    }
    
    private function getSuspiciousActivity($user, $isSuperAdmin)
    {
        // Placeholder - would track suspicious activities
        return rand(0, 5);
    }
    
    private function getPermissionChanges($user, $isSuperAdmin)
    {
        $query = ActivityLog::where('module_name', 'permissions')
            ->where('created_at', '>=', Carbon::now()->subDays(7));
            
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        return $query->count();
    }
    
    private function calculateSecurityScore($user, $isSuperAdmin)
    {
        // Simplified security score calculation
        $score = 100;
        
        // Deduct points for security issues
        $failedLogins = $this->getFailedLogins($user, $isSuperAdmin);
        $suspiciousActivity = $this->getSuspiciousActivity($user, $isSuperAdmin);
        
        $score -= ($failedLogins * 2);
        $score -= ($suspiciousActivity * 5);
        
        return max(0, min(100, $score));
    }
}
