<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Lead;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $stats = [
            'properties_count' => Property::count(),
            'leads_count' => Lead::count(),
            'active_leads_count' => Lead::whereNotIn('status', ['won', 'lost'])->count(),
            'revenue_potential' => Lead::whereNotIn('status', ['lost'])->sum('budget'),
        ];
        
        // Get recent properties
        $recent_properties = Property::latest()->take(5)->get();
        
        // Get recent leads with more details
        $recent_leads = Lead::with(['assignedUser', 'interestedProperty'])
            ->latest()
            ->take(4)
            ->get();

        // Get upcoming events for today and tomorrow
        $upcoming_events = Event::with('lead')
            ->where('is_completed', false)
            ->where('event_date', '>=', Carbon::now())
            ->where('event_date', '<=', Carbon::now()->addDays(2))
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();
        
        // Get lead distribution by status for chart
        $lead_statuses = Lead::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
            
        // Get lead distribution by source for chart
        $lead_sources = Lead::whereNotNull('source')
            ->selectRaw('source, count(*) as count')
            ->groupBy('source')
            ->get()
            ->pluck('count', 'source')
            ->toArray();
        
        // Add "Unknown" or "Not specified" category for leads without source
        $not_specified_count = Lead::whereNull('source')->count();
        if ($not_specified_count > 0) {
            $lead_sources['Not specified'] = $not_specified_count;
        }
            
        return view('dashboard', compact(
            'stats', 
            'recent_properties', 
            'recent_leads', 
            'upcoming_events', 
            'lead_statuses',
            'lead_sources'
        ));
    }
}
