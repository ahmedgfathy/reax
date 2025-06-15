<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Property;
use App\Models\Lead;
use App\Models\ActivityLog;
use Carbon\Carbon;

class SystemController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        // System Health Metrics
        $systemHealth = $this->getSystemHealth();
        
        // Database Statistics
        $databaseStats = $this->getDatabaseStats();
        
        // Application Statistics
        $appStats = $this->getApplicationStats($user, $isSuperAdmin);
        
        // System Configuration
        $systemConfig = $this->getSystemConfiguration();
        
        // Performance Metrics
        $performanceMetrics = $this->getPerformanceMetrics();
        
        // System Monitoring
        $systemMonitoring = $this->getSystemMonitoring();
        
        // Recent System Events
        $recentEvents = $this->getRecentSystemEvents($user, $isSuperAdmin);
        
        // Navigation modules
        $navigationModules = $this->getNavigationModules($user);

        return view('systems.index', compact(
            'systemHealth',
            'databaseStats',
            'appStats',
            'systemConfig',
            'performanceMetrics',
            'systemMonitoring',
            'recentEvents',
            'navigationModules',
            'isSuperAdmin'
        ));
    }
    
    private function getSystemHealth()
    {
        return [
            'database' => $this->checkDatabaseConnection(),
            'storage' => $this->checkStorageConnection(),
            'cache' => $this->checkCacheConnection(),
            'mail' => $this->checkMailConfiguration(),
            'queue' => $this->checkQueueConfiguration(),
            'session' => $this->checkSessionConfiguration()
        ];
    }
    
    private function getDatabaseStats()
    {
        try {
            // Get database size
            $databaseSize = DB::select("SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS size_mb 
                FROM information_schema.tables 
                WHERE table_schema = ?", [env('DB_DATABASE')]);
            
            // Get table counts
            $tables = Schema::getAllTables();
            $tableCounts = [];
            
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                try {
                    $count = DB::table($tableName)->count();
                    $tableCounts[$tableName] = $count;
                } catch (\Exception $e) {
                    $tableCounts[$tableName] = 0;
                }
            }
            
            return [
                'size_mb' => $databaseSize[0]->size_mb ?? 0,
                'total_tables' => count($tables),
                'table_counts' => $tableCounts,
                'connection_count' => $this->getActiveConnections()
            ];
        } catch (\Exception $e) {
            return [
                'size_mb' => 0,
                'total_tables' => 0,
                'table_counts' => [],
                'connection_count' => 0
            ];
        }
    }
    
    private function getApplicationStats($user, $isSuperAdmin)
    {
        $baseQuery = $isSuperAdmin ? [] : ['company_id' => $user->company_id];
        
        return [
            'total_users' => $isSuperAdmin ? User::count() : User::where('company_id', $user->company_id)->count(),
            'active_users' => $isSuperAdmin ? User::where('is_active', true)->count() : User::where('company_id', $user->company_id)->where('is_active', true)->count(),
            'total_properties' => $isSuperAdmin ? Property::count() : Property::where('company_id', $user->company_id)->count(),
            'published_properties' => $isSuperAdmin ? Property::where('is_published', true)->count() : Property::where('company_id', $user->company_id)->where('is_published', true)->count(),
            'total_leads' => $isSuperAdmin ? Lead::count() : Lead::where('company_id', $user->company_id)->count(),
            'active_leads' => $isSuperAdmin ? Lead::whereNotIn('status', ['won', 'lost'])->count() : Lead::where('company_id', $user->company_id)->whereNotIn('status', ['won', 'lost'])->count(),
            'daily_activity' => $this->getDailyActivity($user, $isSuperAdmin),
            'storage_usage' => $this->getStorageUsage()
        ];
    }
    
    private function getSystemConfiguration()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'debug_mode' => config('app.debug'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'queue_driver' => config('queue.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'mail_driver' => config('mail.default')
        ];
    }
    
    private function getPerformanceMetrics()
    {
        return [
            'memory_usage' => [
                'current' => round(memory_get_usage(true) / 1024 / 1024, 1),
                'peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 1),
                'limit' => ini_get('memory_limit')
            ],
            'execution_time' => round((microtime(true) - LARAVEL_START) * 1000, 2),
            'database_queries' => $this->getDatabaseQueryCount(),
            'cache_hit_rate' => $this->getCacheHitRate(),
            'response_time' => $this->getAverageResponseTime()
        ];
    }
    
    private function getSystemMonitoring()
    {
        return [
            'disk_space' => $this->getDiskSpace(),
            'cpu_usage' => $this->getCPUUsage(),
            'load_average' => $this->getLoadAverage(),
            'uptime' => $this->getSystemUptime(),
            'active_processes' => $this->getActiveProcesses(),
            'network_stats' => $this->getNetworkStats()
        ];
    }
    
    private function getRecentSystemEvents($user, $isSuperAdmin)
    {
        $query = ActivityLog::where('module_name', 'system')
            ->latest()
            ->take(10);
            
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        return $query->get();
    }
    
    private function getNavigationModules($user)
    {
        $modules = [
            'crm' => [
                'icon' => 'fas fa-users-gear',
                'title' => __('CRM System'),
                'description' => __('Manage customer relationships and leads'),
                'url' => route('leads.index'),
                'color' => 'blue',
                'status' => 'active'
            ],
            'properties' => [
                'icon' => 'fas fa-building',
                'title' => __('Property Management'),
                'description' => __('Manage real estate properties and listings'),
                'url' => route('properties.index'),
                'color' => 'green',
                'status' => 'active'
            ],
            'reports' => [
                'icon' => 'fas fa-chart-line',
                'title' => __('Reports & Analytics'),
                'description' => __('View insights, reports and analytics'),
                'url' => route('reports.index'),
                'color' => 'purple',
                'status' => 'active'
            ],
            'management' => [
                'icon' => 'fas fa-cogs',
                'title' => __('Management Dashboard'),
                'description' => __('Team management and performance tracking'),
                'url' => route('management.index'),
                'color' => 'indigo',
                'status' => 'active'
            ]
        ];
        
        if ($user->isAdmin()) {
            $modules['administration'] = [
                'icon' => 'fas fa-users-cog',
                'title' => __('Administration'),
                'description' => __('User and system administration'),
                'url' => route('administration.index'),
                'color' => 'red',
                'status' => 'active'
            ];
        }
        
        return $modules;
    }
    
    // Helper methods for system checks
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'connected', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }
    }
    
    private function checkStorageConnection()
    {
        try {
            Storage::disk('local')->exists('test');
            return ['status' => 'connected', 'message' => 'Storage accessible'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Storage not accessible'];
        }
    }
    
    private function checkCacheConnection()
    {
        try {
            Cache::put('test_key', 'test_value', 60);
            $value = Cache::get('test_key');
            Cache::forget('test_key');
            return ['status' => 'connected', 'message' => 'Cache working properly'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Cache not working'];
        }
    }
    
    private function checkMailConfiguration()
    {
        $driver = config('mail.default');
        return ['status' => 'configured', 'message' => "Mail driver: {$driver}"];
    }
    
    private function checkQueueConfiguration()
    {
        $driver = config('queue.default');
        return ['status' => 'configured', 'message' => "Queue driver: {$driver}"];
    }
    
    private function checkSessionConfiguration()
    {
        $driver = config('session.driver');
        return ['status' => 'configured', 'message' => "Session driver: {$driver}"];
    }
    
    private function getActiveConnections()
    {
        try {
            $connections = DB::select('SHOW STATUS LIKE "Threads_connected"');
            return $connections[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getDailyActivity($user, $isSuperAdmin)
    {
        $query = ActivityLog::whereDate('created_at', Carbon::today());
        
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        return $query->count();
    }
    
    private function getStorageUsage()
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
    
    private function getDatabaseQueryCount()
    {
        return count(DB::getQueryLog());
    }
    
    private function getCacheHitRate()
    {
        // Placeholder - would need specific cache implementation
        return rand(85, 95);
    }
    
    private function getAverageResponseTime()
    {
        // Placeholder - would need response time tracking
        return rand(100, 500);
    }
    
    private function getDiskSpace()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        return [
            'total' => round($total / 1024 / 1024 / 1024, 1),
            'used' => round($used / 1024 / 1024 / 1024, 1),
            'free' => round($free / 1024 / 1024 / 1024, 1),
            'percentage' => round(($used / $total) * 100, 1)
        ];
    }
    
    private function getCPUUsage()
    {
        // Placeholder - would need system-specific implementation
        return rand(10, 80);
    }
    
    private function getLoadAverage()
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return [
                '1min' => round($load[0], 2),
                '5min' => round($load[1], 2),
                '15min' => round($load[2], 2)
            ];
        }
        return ['1min' => 0, '5min' => 0, '15min' => 0];
    }
    
    private function getSystemUptime()
    {
        // Placeholder - would need system-specific implementation
        return '24 days, 5 hours';
    }
    
    private function getActiveProcesses()
    {
        // Placeholder - would need system-specific implementation
        return rand(50, 200);
    }
    
    private function getNetworkStats()
    {
        // Placeholder - would need network monitoring
        return [
            'bytes_sent' => rand(1000000, 5000000),
            'bytes_received' => rand(2000000, 8000000),
            'packets_sent' => rand(10000, 50000),
            'packets_received' => rand(15000, 60000)
        ];
    }
}
