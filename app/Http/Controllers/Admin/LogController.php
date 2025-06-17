<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = ActivityLog::with(['user', 'company', 'loggable'])
            ->latest();
            
        // Filter by company for non-super admins
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        }
        
        // Module filter
        if ($request->filled('module')) {
            $query->where('module_name', $request->module);
        }
        
        // Action type filter
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }
        
        // User filter
        if ($request->filled('user_id') && $user->isAdmin()) {
            $query->where('user_id', $request->user_id);
        }
        
        $logs = $query->paginate(20);
        
        // Get unique values for filters
        $modules = ActivityLog::distinct('module_name')->pluck('module_name');
        $actionTypes = ActivityLog::distinct('action_type')->pluck('action_type');
        
        return view('administration.logs.index', compact(
            'logs', 
            'modules', 
            'actionTypes', 
            'isSuperAdmin'
        ));
    }
}
