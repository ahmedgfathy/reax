<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can access system settings.');
        }

        // System Information
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'timezone' => config('app.timezone'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
        ];

        // Database Information
        $databaseStats = [
            'connection' => config('database.default'),
            'size' => $this->getDatabaseSize(),
            'tables' => $this->getTableCount(),
        ];

        // Cache Information
        $cacheStats = [
            'driver' => config('cache.default'),
            'prefix' => config('cache.prefix', ''),
            'status' => Cache::get('cache_test_key', false) ? 'Working' : 'Not Working',
        ];

        // Mail Configuration
        $mailConfig = [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
        ];

        return view('administration.settings.index', compact(
            'systemInfo',
            'databaseStats',
            'cacheStats',
            'mailConfig'
        ));
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
            return redirect()->route('administration.settings')
                ->with('success', __('Cache cleared successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('administration.settings')
                ->with('error', __('Failed to clear cache: ') . $e->getMessage());
        }
    }

    public function optimizeCache()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return redirect()->route('administration.settings')
                ->with('success', __('Cache optimized successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('administration.settings')
                ->with('error', __('Failed to optimize cache: ') . $e->getMessage());
        }
    }

    public function testMail()
    {
        try {
            Mail::raw('Test email from ' . config('app.name'), function($message) {
                $message->to(Auth::user()->email)
                    ->subject('Test Email');
            });
            
            return redirect()->route('administration.settings')
                ->with('success', __('Test email sent successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('administration.settings')
                ->with('error', __('Failed to send test email: ') . $e->getMessage());
        }
    }

    private function getDatabaseSize()
    {
        try {
            $size = DB::select("SELECT pg_size_pretty(pg_database_size(current_database())) as size");
            return $size[0]->size ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getTableCount()
    {
        return count(DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'"));
    }
}
