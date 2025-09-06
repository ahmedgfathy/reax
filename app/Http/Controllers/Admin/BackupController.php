<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can access backup management.');
        }

        // Get list of existing backups
        $backups = collect(Storage::disk('backups')->files())
            ->filter(function($file) {
                return in_array(pathinfo($file, PATHINFO_EXTENSION), ['sql', 'zip', 'gz']);
            })
            ->map(function($file) {
                return [
                    'file_name' => $file,
                    'file_size' => Storage::disk('backups')->size($file),
                    'last_modified' => Carbon::createFromTimestamp(
                        Storage::disk('backups')->lastModified($file)
                    ),
                    'age' => Carbon::createFromTimestamp(
                        Storage::disk('backups')->lastModified($file)
                    )->diffForHumans()
                ];
            })
            ->sortByDesc('last_modified');

        // Get backup disk usage
        $totalSize = $backups->sum('file_size');
        $diskSpace = [
            'used' => round($totalSize / 1024 / 1024, 2), // Convert to MB
            'total' => round(disk_free_space(storage_path()) / 1024 / 1024 / 1024, 2) // Convert to GB
        ];

        return view('administration.backup.index', compact('backups', 'diskSpace'));
    }

    public function create()
    {
        try {
            // Create database dump
            $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
            
            // Use mysqldump for MySQL or pg_dump for PostgreSQL
            if (config('database.default') === 'mysql') {
                $command = sprintf(
                    'mysqldump -u%s -p%s %s > %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.database'),
                    storage_path('app/backups/' . $filename)
                );
            } else {
                $command = sprintf(
                    'pg_dump -U %s %s > %s',
                    config('database.connections.pgsql.username'),
                    config('database.connections.pgsql.database'),
                    storage_path('app/backups/' . $filename)
                );
            }

            exec($command);

            return redirect()->route('administration.backup')
                ->with('success', __('Backup created successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('administration.backup')
                ->with('error', __('Failed to create backup: ') . $e->getMessage());
        }
    }

    public function download($filename)
    {
        try {
            $path = storage_path('app/backups/' . $filename);
            
            if (!file_exists($path)) {
                throw new \Exception('Backup file not found.');
            }

            return response()->download($path);
        } catch (\Exception $e) {
            return redirect()->route('administration.backup')
                ->with('error', __('Failed to download backup: ') . $e->getMessage());
        }
    }

    public function destroy($filename)
    {
        try {
            if (!Storage::disk('backups')->exists($filename)) {
                throw new \Exception('Backup file not found.');
            }

            Storage::disk('backups')->delete($filename);

            return redirect()->route('administration.backup')
                ->with('success', __('Backup deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('administration.backup')
                ->with('error', __('Failed to delete backup: ') . $e->getMessage());
        }
    }

    public function restore($filename)
    {
        try {
            $path = storage_path('app/backups/' . $filename);
            
            if (!file_exists($path)) {
                throw new \Exception('Backup file not found.');
            }

            // Restore database from dump
            if (config('database.default') === 'mysql') {
                $command = sprintf(
                    'mysql -u%s -p%s %s < %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.database'),
                    $path
                );
            } else {
                $command = sprintf(
                    'psql -U %s -d %s -f %s',
                    config('database.connections.pgsql.username'),
                    config('database.connections.pgsql.database'),
                    $path
                );
            }

            exec($command);

            return redirect()->route('administration.backup')
                ->with('success', __('Database restored successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('administration.backup')
                ->with('error', __('Failed to restore backup: ') . $e->getMessage());
        }
    }
}
