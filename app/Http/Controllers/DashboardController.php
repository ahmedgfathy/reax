<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Lead;
use App\Models\Event;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

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
        
        // Property stats
        $propertyStats = [
            'total_sale_value' => Property::where('unit_for', 'sale')->sum('total_price'),
            'total_rent_value' => Property::where('unit_for', 'rent')->sum('total_price'),
            'available_properties' => Property::count(), // You may want to add a status field to filter
            'featured_properties' => Property::where('is_featured', true)->count(),
        ];
        
        // Get recent properties
        $recent_properties = Property::latest()->take(5)->get();
        
        // Get recent leads with more details
        $recent_leads = Lead::with(['assignedUser', 'interestedProperty'])
            ->latest()
            ->take(4)
            ->get();

        // Simplified upcoming events query using start_date
        $upcoming_events = Event::with('lead')
            ->where('start_date', '>=', Carbon::now())
            ->where('start_date', '<=', Carbon::now()->addHours(48))
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_date', 'asc')
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
        
        // Property data for charts
        
        // Property types distribution
        $propertyTypes = Property::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();
        
        // Property distribution by unit_for (sale/rent)
        $propertyUnitFor = Property::selectRaw('unit_for, count(*) as count')
            ->groupBy('unit_for')
            ->get()
            ->pluck('count', 'unit_for')
            ->toArray();
        
        // Properties by price range
        $salePriceRanges = [
            '0-100000' => Property::where('unit_for', 'sale')->where('total_price', '<=', 100000)->count(),
            '100001-250000' => Property::where('unit_for', 'sale')->whereBetween('total_price', [100001, 250000])->count(),
            '250001-500000' => Property::where('unit_for', 'sale')->whereBetween('total_price', [250001, 500000])->count(),
            '500001-1000000' => Property::where('unit_for', 'sale')->whereBetween('total_price', [500001, 1000000])->count(),
            '1000001+' => Property::where('unit_for', 'sale')->where('total_price', '>', 1000000)->count(),
        ];
        
        $rentPriceRanges = [
            '0-500' => Property::where('unit_for', 'rent')->where('total_price', '<=', 500)->count(),
            '501-1000' => Property::where('unit_for', 'rent')->whereBetween('total_price', [501, 1000])->count(),
            '1001-2500' => Property::where('unit_for', 'rent')->whereBetween('total_price', [1001, 2500])->count(),
            '2501-5000' => Property::where('unit_for', 'rent')->whereBetween('total_price', [2501, 5000])->count(),
            '5001+' => Property::where('unit_for', 'rent')->where('total_price', '>', 5000)->count(),
        ];
        
        // Properties by area size (sqm)
        $areaSizeRanges = [
            '0-50' => Property::where('unit_area', '<=', 50)->count(),
            '51-100' => Property::whereBetween('unit_area', [51, 100])->count(),
            '101-200' => Property::whereBetween('unit_area', [101, 200])->count(),
            '201-500' => Property::whereBetween('unit_area', [201, 500])->count(),
            '501+' => Property::where('unit_area', '>', 500)->count(),
        ];
        
        // Monthly property listings (for the past year)
        $monthlyListings = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Property::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $monthlyListings[] = $count;
            $labels[] = $month->format('M Y');
        }
        
        $propertyTimeData = [
            'labels' => $labels,
            'data' => $monthlyListings
        ];

        // Add opportunity stats
        $stats['opportunities_count'] = Opportunity::count();
        $stats['won_opportunities'] = Opportunity::where('status', 'won')->count();
        $stats['pipeline_value'] = Opportunity::whereIn('status', ['pending', 'negotiation'])->sum('value');
        $stats['conversion_rate'] = $stats['opportunities_count'] > 0 
            ? round(($stats['won_opportunities'] / $stats['opportunities_count']) * 100) 
            : 0;

        // Get opportunity stages for chart
        $opportunity_stages = Opportunity::selectRaw('stage, count(*) as count')
            ->groupBy('stage')
            ->pluck('count', 'stage')
            ->toArray();

        // Get recent opportunities
        $recent_opportunities = Opportunity::with(['assignedTo', 'lead', 'property'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard', compact(
            'stats', 
            'propertyStats',
            'recent_properties', 
            'recent_leads', 
            'upcoming_events', 
            'lead_statuses',
            'lead_sources',
            'propertyTypes',
            'propertyUnitFor',
            'salePriceRanges',
            'rentPriceRanges',
            'areaSizeRanges',
            'propertyTimeData',
            'opportunity_stages',
            'recent_opportunities'
        ));
    }
}
