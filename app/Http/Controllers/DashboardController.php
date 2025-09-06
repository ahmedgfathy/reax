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
        $stats = $this->getBasicStats();
        
        // Property specific stats
        $propertyStats = $this->getPropertyStats();
        
        // Time-based data for charts
        $propertyTimeData = $this->getPropertyTimeData();
        
        // Recent records and events - Super Admin sees all, others see company data only
        if (auth()->user()->isSuperAdmin()) {
            $recent_leads = Lead::with('assignedUser')
                ->latest()
                ->take(4)
                ->get();
                
            $recent_properties = Property::latest()
                ->take(5)
                ->get();
                
            $upcoming_events = Event::whereBetween('start_date', [
                now(),
                now()->addDays(2)
            ])
                ->where('status', '!=', 'cancelled')
                ->orderBy('start_date')
                ->take(5)
                ->get();
                
            // Statistics for charts - ALL data for Super Admin
            $lead_statuses = Lead::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
                
            $propertyTypes = Property::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray();
                
            $propertyUnitFor = Property::selectRaw('unit_for, count(*) as count')
                ->groupBy('unit_for')
                ->pluck('count', 'unit_for')
                ->toArray();
        } else {
            $recent_leads = Lead::with('assignedUser')
                ->where('company_id', auth()->user()->company_id)
                ->latest()
                ->take(4)
                ->get();
                
            $recent_properties = Property::where('company_id', auth()->user()->company_id)
                ->latest()
                ->take(5)
                ->get();
                
            $upcoming_events = Event::whereBetween('start_date', [
                now(),
                now()->addDays(2)
            ])
                ->where('company_id', auth()->user()->company_id)
                ->where('status', '!=', 'cancelled')
                ->orderBy('start_date')
                ->take(5)
                ->get();
                
            // Statistics for charts - ONLY company data
            $lead_statuses = Lead::selectRaw('status, count(*) as count')
                ->where('company_id', auth()->user()->company_id)
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
                
            $propertyTypes = Property::selectRaw('type, count(*) as count')
                ->where('company_id', auth()->user()->company_id)
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray();
                
            $propertyUnitFor = Property::selectRaw('unit_for, count(*) as count')
                ->where('company_id', auth()->user()->company_id)
                ->groupBy('unit_for')
                ->pluck('count', 'unit_for')
                ->toArray();
        }

        $lead_sources = $this->getLeadSources();

        // Get price ranges
        $priceRanges = $this->getPriceRanges();
        $salePriceRanges = $priceRanges['salePriceRanges'];
        $rentPriceRanges = $priceRanges['rentPriceRanges'];

        return view('dashboard', compact(
            'stats',
            'propertyStats',
            'propertyTimeData',
            'recent_leads',
            'recent_properties',
            'upcoming_events',
            'lead_statuses',
            'propertyTypes',
            'propertyUnitFor',
            'lead_sources',
            'salePriceRanges',
            'rentPriceRanges'
        ));
    }

    protected function getAccessibleModules($user)
    {
        $modules = [
            'properties' => true,
            'leads' => true,
            'reports' => true
        ];

        if ($user->isAdmin()) {
            $modules = array_merge($modules, [
                'administration' => true,
                'employees' => true,
                'teams' => true,
                'roles' => true,
                'departments' => true,
                'branches' => true,
                'settings' => true
            ]);
        }

        return $modules;
    }

    protected function getBasicStats()
    {
        if (auth()->user()->isSuperAdmin()) {
            return [
                'properties_count' => Property::count(),
                'leads_count' => Lead::count(),
                'active_leads_count' => Lead::whereNotIn('status', ['won', 'lost'])->count(),
                'revenue_potential' => $this->calculateRevenuePotential(),
            ];
        } else {
            return [
                'properties_count' => Property::where('company_id', auth()->user()->company_id)->count(),
                'leads_count' => Lead::where('company_id', auth()->user()->company_id)->count(),
                'active_leads_count' => Lead::where('company_id', auth()->user()->company_id)->whereNotIn('status', ['won', 'lost'])->count(),
                'revenue_potential' => $this->calculateRevenuePotential(),
            ];
        }
    }

    protected function getPropertyStats()
    {
        if (auth()->user()->isSuperAdmin()) {
            return [
                'total_sale_value' => Property::where('unit_for', 'sale')->sum('total_price') ?? 0,
                'total_rent_value' => Property::where('unit_for', 'rent')->sum('total_price') ?? 0,
                'total_properties' => Property::count(),
                'featured_properties' => Property::where('is_featured', true)->count(),
            ];
        } else {
            return [
                'total_sale_value' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'sale')->sum('total_price') ?? 0,
                'total_rent_value' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'rent')->sum('total_price') ?? 0,
                'total_properties' => Property::where('company_id', auth()->user()->company_id)->count(),
                'featured_properties' => Property::where('company_id', auth()->user()->company_id)->where('is_featured', true)->count(),
            ];
        }
    }

    protected function calculateRevenuePotential()
    {
        if (auth()->user()->isSuperAdmin()) {
            $activeLeadsBudget = Lead::whereNotIn('status', ['won', 'lost'])
                ->whereNotNull('budget')
                ->sum('budget') ?? 0;
                
            $pendingOpportunities = Opportunity::whereIn('status', ['pending', 'negotiation'])
                ->sum('value') ?? 0;
        } else {
            $activeLeadsBudget = Lead::where('company_id', auth()->user()->company_id)->whereNotIn('status', ['won', 'lost'])
                ->whereNotNull('budget')
                ->sum('budget') ?? 0;
                
            $pendingOpportunities = Opportunity::where('company_id', auth()->user()->company_id)->whereIn('status', ['pending', 'negotiation'])
                ->sum('value') ?? 0;
        }
            
        return $activeLeadsBudget + $pendingOpportunities;
    }

    protected function getPropertyTimeData()
    {
        $labels = [];
        $data = [];
        $lastYear = now()->subMonths(11);
        
        for ($i = 0; $i < 12; $i++) {
            $date = $lastYear->copy()->addMonths($i);
            $labels[] = $date->format('M Y');
            
            if (auth()->user()->isSuperAdmin()) {
                $count = Property::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            } else {
                $count = Property::where('company_id', auth()->user()->company_id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
                
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    protected function getLeadSources()
    {
        if (auth()->user()->isSuperAdmin()) {
            $sources = Lead::whereNotNull('lead_source')
                ->selectRaw('lead_source, count(*) as count')
                ->groupBy('lead_source')
                ->pluck('count', 'lead_source')
                ->toArray();

            // Add unknown sources
            $unknown = Lead::whereNull('lead_source')->count();
        } else {
            $sources = Lead::where('company_id', auth()->user()->company_id)
                ->whereNotNull('lead_source')
                ->selectRaw('lead_source, count(*) as count')
                ->groupBy('lead_source')
                ->pluck('count', 'lead_source')
                ->toArray();

            // Add unknown sources
            $unknown = Lead::where('company_id', auth()->user()->company_id)->whereNull('lead_source')->count();
        }
        
        if ($unknown > 0) {
            $sources['Unknown'] = $unknown;
        }

        return $sources;
    }

    protected function getPriceRanges()
    {
        if (auth()->user()->isSuperAdmin()) {
            return [
                'salePriceRanges' => [
                    '< 100K' => Property::where('unit_for', 'sale')
                        ->where('total_price', '<=', 100000)
                        ->count(),
                    '100K - 250K' => Property::where('unit_for', 'sale')
                        ->whereBetween('total_price', [100001, 250000])
                        ->count(),
                    '250K - 500K' => Property::where('unit_for', 'sale')
                        ->whereBetween('total_price', [250001, 500000])
                        ->count(),
                    '500K - 1M' => Property::where('unit_for', 'sale')
                        ->whereBetween('total_price', [500001, 1000000])
                        ->count(),
                    '> 1M' => Property::where('unit_for', 'sale')
                        ->where('total_price', '>', 1000000)
                        ->count(),
                ],
                'rentPriceRanges' => [
                    '< 500' => Property::where('unit_for', 'rent')
                        ->where('total_price', '<=', 500)
                        ->count(),
                    '500 - 1K' => Property::where('unit_for', 'rent')
                        ->whereBetween('total_price', [501, 1000])
                        ->count(),
                    '1K - 2.5K' => Property::where('unit_for', 'rent')
                        ->whereBetween('total_price', [1001, 2500])
                        ->count(),
                    '2.5K - 5K' => Property::where('unit_for', 'rent')
                        ->whereBetween('total_price', [2501, 5000])
                        ->count(),
                    '> 5K' => Property::where('unit_for', 'rent')
                        ->where('total_price', '>', 5000)
                        ->count(),
                ]
            ];
        } else {
            return [
                'salePriceRanges' => [
                    '< 100K' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'sale')
                        ->where('total_price', '<=', 100000)
                        ->count(),
                    '100K - 250K' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'sale')
                        ->whereBetween('total_price', [100001, 250000])
                        ->count(),
                    '250K - 500K' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'sale')
                        ->whereBetween('total_price', [250001, 500000])
                        ->count(),
                    '500K - 1M' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'sale')
                        ->whereBetween('total_price', [500001, 1000000])
                        ->count(),
                    '> 1M' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'sale')
                        ->where('total_price', '>', 1000000)
                        ->count(),
                ],
                'rentPriceRanges' => [
                    '< 500' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'rent')
                        ->where('total_price', '<=', 500)
                        ->count(),
                    '500 - 1K' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'rent')
                        ->whereBetween('total_price', [501, 1000])
                        ->count(),
                    '1K - 2.5K' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'rent')
                        ->whereBetween('total_price', [1001, 2500])
                        ->count(),
                    '2.5K - 5K' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'rent')
                        ->whereBetween('total_price', [2501, 5000])
                        ->count(),
                    '> 5K' => Property::where('company_id', auth()->user()->company_id)->where('unit_for', 'rent')
                        ->where('total_price', '>', 5000)
                        ->count(),
                ]
            ];
        }
    }
}
