<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Team;
use App\Models\Department;
use App\Models\User;
use App\Models\TeamPerformanceMetric;
use App\Models\Territory;
use App\Models\Goal;
use App\Models\TeamActivity;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;
        
        // Check if user is super admin (sees all data) or company-specific
        $isSuperAdmin = $user->isSuperAdmin();
        
        // Base query builder
        $companiesQuery = $isSuperAdmin ? [] : ['company_id' => $company->id];
        
        // Basic Statistics
        $stats = $this->getBasicStats($companiesQuery, $isSuperAdmin);
        
        // Performance Analytics
        $performanceData = $this->getPerformanceAnalytics($companiesQuery, $isSuperAdmin);
        
        // Territory Management
        $territoryData = $this->getTerritoryAnalytics($companiesQuery, $isSuperAdmin);
        
        // Goal Tracking
        $goalData = $this->getGoalAnalytics($companiesQuery, $isSuperAdmin);
        
        // Team Activities & Collaboration
        $activityData = $this->getActivityAnalytics($companiesQuery, $isSuperAdmin);
        
        // Revenue & Sales Analytics
        $revenueData = $this->getRevenueAnalytics($companiesQuery, $isSuperAdmin);
        
        // Top Performers
        $topPerformers = $this->getTopPerformers($companiesQuery, $isSuperAdmin);
        
        return view('management.index', compact(
            'stats', 'performanceData', 'territoryData', 'goalData', 
            'activityData', 'revenueData', 'topPerformers', 'isSuperAdmin'
        ));
    }
    
    private function getBasicStats($companiesQuery, $isSuperAdmin)
    {
        if ($isSuperAdmin) {
            return [
                'teams_count' => Team::count(),
                'departments_count' => Department::count(),
                'branches_count' => Branch::where('is_active', true)->count(),
                'staff_count' => User::where('is_active', true)->count(),
                'companies_count' => DB::table('companies')->count(),
                'territories_count' => Territory::count(),
                'active_goals' => Goal::where('status', 'active')->count(),
                'total_leads' => Lead::count(),
                'total_opportunities' => Opportunity::count(),
                'total_properties' => Property::count(),
            ];
        } else {
            return [
                'teams_count' => Team::where('company_id', $companiesQuery['company_id'])->count(),
                'departments_count' => Department::where('company_id', $companiesQuery['company_id'])->count(),
                'branches_count' => Branch::where('company_id', $companiesQuery['company_id'])->where('is_active', true)->count(),
                'staff_count' => User::where('company_id', $companiesQuery['company_id'])->where('is_active', true)->count(),
                'territories_count' => Territory::where('company_id', $companiesQuery['company_id'])->count(),
                'active_goals' => Goal::where('company_id', $companiesQuery['company_id'])->where('status', 'active')->count(),
                'total_leads' => Lead::where('company_id', $companiesQuery['company_id'])->count(),
                'total_opportunities' => Opportunity::where('company_id', $companiesQuery['company_id'])->count(),
                'total_properties' => Property::where('company_id', $companiesQuery['company_id'])->count(),
            ];
        }
    }
    
    private function getPerformanceAnalytics($companiesQuery, $isSuperAdmin)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $query = TeamPerformanceMetric::query();
        if (!$isSuperAdmin) {
            $query->where('company_id', $companiesQuery['company_id']);
        }
        
        $currentMetrics = $query->clone()
            ->where('period_start', '>=', $currentMonth)
            ->selectRaw('
                SUM(leads_generated) as total_leads,
                SUM(leads_converted) as total_conversions,
                AVG(conversion_rate) as avg_conversion_rate,
                SUM(revenue_generated) as total_revenue,
                SUM(deals_closed) as total_deals,
                AVG(productivity_score) as avg_productivity,
                SUM(calls_made) as total_calls,
                SUM(meetings_held) as total_meetings
            ')
            ->first();
            
        $lastMonthMetrics = $query->clone()
            ->whereBetween('period_start', [$lastMonth, $currentMonth])
            ->selectRaw('
                SUM(leads_generated) as total_leads,
                SUM(leads_converted) as total_conversions,
                AVG(conversion_rate) as avg_conversion_rate,
                SUM(revenue_generated) as total_revenue,
                SUM(deals_closed) as total_deals,
                AVG(productivity_score) as avg_productivity
            ')
            ->first();
            
        // Calculate growth percentages
        $growthData = [
            'leads_growth' => $this->calculateGrowth($currentMetrics->total_leads, $lastMonthMetrics->total_leads),
            'conversion_growth' => $this->calculateGrowth($currentMetrics->total_conversions, $lastMonthMetrics->total_conversions),
            'revenue_growth' => $this->calculateGrowth($currentMetrics->total_revenue, $lastMonthMetrics->total_revenue),
            'deals_growth' => $this->calculateGrowth($currentMetrics->total_deals, $lastMonthMetrics->total_deals),
        ];
        
        return [
            'current' => $currentMetrics,
            'growth' => $growthData,
            'chart_data' => $this->getPerformanceChartData($query)
        ];
    }
    
    private function getTerritoryAnalytics($companiesQuery, $isSuperAdmin)
    {
        $query = Territory::query();
        if (!$isSuperAdmin) {
            $query->where('company_id', $companiesQuery['company_id']);
        }
        
        $territories = $query->withCount(['assignedUsers', 'leads', 'opportunities'])
            ->selectRaw('(SELECT COALESCE(SUM(value), 0) FROM opportunities WHERE territory_id = territories.id AND status = "won") as territory_revenue')
            ->get();
            
        return [
            'territories' => $territories,
            'total_territories' => $territories->count(),
            'assigned_territories' => $territories->where('assigned_users_count', '>', 0)->count(),
            'unassigned_territories' => $territories->where('assigned_users_count', 0)->count(),
            'top_performing_territories' => $territories->sortByDesc('territory_revenue')->take(5)
        ];
    }
    
    private function getGoalAnalytics($companiesQuery, $isSuperAdmin)
    {
        $query = Goal::query();
        if (!$isSuperAdmin) {
            $query->where('company_id', $companiesQuery['company_id']);
        }
        
        $goals = $query->with(['user', 'team'])
            ->where('status', 'active')
            ->get()
            ->map(function ($goal) {
                $goal->completion_percentage = $goal->current_value > 0 && $goal->target_value > 0 
                    ? min(100, ($goal->current_value / $goal->target_value) * 100)
                    : 0;
                return $goal;
            });
            
        $upcomingDeadlines = $goals->filter(function ($goal) {
            return Carbon::parse($goal->deadline)->diffInDays(Carbon::now()) <= 7;
        });
        
        return [
            'total_goals' => $goals->count(),
            'completed_goals' => $goals->where('completion_percentage', '>=', 100)->count(),
            'in_progress_goals' => $goals->where('completion_percentage', '<', 100)->where('completion_percentage', '>', 0)->count(),
            'not_started_goals' => $goals->where('completion_percentage', 0)->count(),
            'upcoming_deadlines' => $upcomingDeadlines->count(),
            'avg_completion' => $goals->avg('completion_percentage'),
            'goals_by_type' => $goals->groupBy('type')->map->count(),
            'recent_goals' => $goals->sortByDesc('created_at')->take(5)
        ];
    }
    
    private function getActivityAnalytics($companiesQuery, $isSuperAdmin)
    {
        $query = TeamActivity::query();
        if (!$isSuperAdmin) {
            $query->where('company_id', $companiesQuery['company_id']);
        }
        
        $recentActivities = $query->clone()
            ->with(['user', 'team'])
            ->latest()
            ->take(10)
            ->get();
            
        $activityStats = $query->clone()
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->selectRaw('
                type,
                COUNT(*) as count,
                COUNT(DISTINCT user_id) as unique_users
            ')
            ->groupBy('type')
            ->get();
            
        return [
            'recent_activities' => $recentActivities,
            'activity_stats' => $activityStats,
            'total_activities_month' => $activityStats->sum('count'),
            'active_users_month' => $query->distinct('user_id')->where('created_at', '>=', Carbon::now()->startOfMonth())->count()
        ];
    }
    
    private function getRevenueAnalytics($companiesQuery, $isSuperAdmin)
    {
        $opportunityQuery = Opportunity::query();
        if (!$isSuperAdmin) {
            $opportunityQuery->where('company_id', $companiesQuery['company_id']);
        }
        
        $currentMonth = Carbon::now()->startOfMonth();
        $currentRevenue = $opportunityQuery->clone()
            ->where('status', 'won')
            ->where('updated_at', '>=', $currentMonth)
            ->sum('value');
            
        $lastMonthRevenue = $opportunityQuery->clone()
            ->where('status', 'won')
            ->whereBetween('updated_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->sum('value');
            
        $pipeline = $opportunityQuery->clone()
            ->whereIn('status', ['prospecting', 'qualification', 'proposal', 'negotiation'])
            ->sum('value');
            
        return [
            'current_month_revenue' => $currentRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'revenue_growth' => $this->calculateGrowth($currentRevenue, $lastMonthRevenue),
            'pipeline_value' => $pipeline,
            'won_deals_count' => $opportunityQuery->clone()->where('status', 'closed_won')->count(),
            'active_pipeline_count' => $opportunityQuery->clone()->whereIn('status', ['prospecting', 'qualification', 'proposal', 'negotiation'])->count()
        ];
    }
    
    private function getTopPerformers($companiesQuery, $isSuperAdmin)
    {
        $query = TeamPerformanceMetric::query();
        if (!$isSuperAdmin) {
            $query->where('company_id', $companiesQuery['company_id']);
        }
        
        $currentMonth = Carbon::now()->startOfMonth();
        
        $topPerformers = $query->with('user')
            ->where('period_start', '>=', $currentMonth)
            ->where('user_id', '!=', null)
            ->selectRaw('
                user_id,
                SUM(revenue_generated) as total_revenue,
                SUM(deals_closed) as total_deals,
                SUM(leads_converted) as total_conversions,
                AVG(productivity_score) as avg_productivity,
                SUM(points_earned) as total_points
            ')
            ->groupBy('user_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();
            
        $topTeams = Team::query();
        if (!$isSuperAdmin) {
            $topTeams->where('company_id', $companiesQuery['company_id']);
        }
        
        $topTeams = $topTeams->withAvg('performanceMetrics', 'achievement_percentage')
            ->withSum('performanceMetrics', 'revenue_generated')
            ->orderByDesc('performance_metrics_sum_revenue_generated')
            ->take(5)
            ->get();
            
        return [
            'top_individuals' => $topPerformers,
            'top_teams' => $topTeams
        ];
    }
    
    private function getPerformanceChartData($query)
    {
        $data = $query->selectRaw('
                DATE(period_start) as date,
                SUM(revenue_generated) as revenue,
                SUM(leads_generated) as leads,
                SUM(deals_closed) as deals
            ')
            ->where('period_start', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return [
            'labels' => $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d')),
            'revenue' => $data->pluck('revenue'),
            'leads' => $data->pluck('leads'),
            'deals' => $data->pluck('deals')
        ];
    }
    
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
    
    // Territory Management Methods
    public function territories()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Territory::with(['company', 'assignedUsers']);
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $territories = $query->paginate(15);
        
        // Get managers for the dropdown
        $managersQuery = User::where('role', 'manager')
            ->orWhere('role', 'admin')
            ->orWhere('role', 'team_leader');
        
        if (!$isSuperAdmin) {
            $managersQuery->where('company_id', $user->company_id);
        }
        
        $managers = $managersQuery->orderBy('name')->get();
        
        return view('management.territories.index', compact('territories', 'managers', 'isSuperAdmin'));
    }
    
    public function createTerritory()
    {
        $user = auth()->user();
        $companies = $user->isSuperAdmin() ? DB::table('companies')->get() : [$user->company];
        $users = $user->isSuperAdmin() ? User::with('company')->get() : User::where('company_id', $user->company_id)->get();
        
        return view('management.territories.create', compact('companies', 'users'));
    }
    
    public function storeTerritory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_id' => 'required|exists:companies,id',
            'type' => 'required|in:geographic,demographic,product,account',
            'boundaries' => 'nullable|json',
            'is_active' => 'boolean',
            'assigned_users' => 'array',
            'assigned_users.*' => 'exists:users,id'
        ]);
        
        $territory = Territory::create($validated);
        
        if (!empty($validated['assigned_users'])) {
            $territory->assignedUsers()->sync($validated['assigned_users']);
        }
        
        return redirect()->route('management.territories.index')
            ->with('success', 'Territory created successfully');
    }
    
    // Territory Edit and Update Methods
    public function editTerritory($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Territory::with(['assignedUsers', 'company']);
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $territory = $query->findOrFail($id);
        $companies = $isSuperAdmin ? DB::table('companies')->get() : [$user->company];
        $users = $isSuperAdmin ? User::with('company')->get() : User::where('company_id', $user->company_id)->get();
        
        return view('management.territories.edit', compact('territory', 'companies', 'users', 'isSuperAdmin'));
    }
    
    public function updateTerritory(Request $request, $id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Territory::query();
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $territory = $query->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:geographic,demographic,product,account',
            'boundaries' => 'nullable|json',
            'is_active' => 'boolean',
            'assigned_users' => 'array',
            'assigned_users.*' => 'exists:users,id'
        ]);
        
        $territory->update($validated);
        
        if (isset($validated['assigned_users'])) {
            $territory->assignedUsers()->sync($validated['assigned_users']);
        }
        
        return redirect()->route('management.territories.index')
            ->with('success', 'Territory updated successfully');
    }
    
    public function destroyTerritory($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Territory::query();
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $territory = $query->findOrFail($id);
        
        // Check if territory has associated data that might prevent deletion
        $hasLeads = $territory->leads()->count() > 0;
        $hasOpportunities = $territory->opportunities()->count() > 0;
        $hasProperties = $territory->properties()->count() > 0;
        
        if ($hasLeads || $hasOpportunities || $hasProperties) {
            return redirect()->route('management.territories.index')
                ->with('error', 'Cannot delete territory. It has associated leads, opportunities, or properties. Please reassign them first.');
        }
        
        // Detach users before deletion
        $territory->assignedUsers()->detach();
        
        // Delete the territory
        $territory->delete();
        
        return redirect()->route('management.territories.index')
            ->with('success', 'Territory deleted successfully');
    }
    
    // Goal Management Methods
    public function goals()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Goal::with(['user', 'team', 'company']);
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $goals = $query->latest()->paginate(15);
        
        return view('management.goals.index', compact('goals', 'isSuperAdmin'));
    }
    
    public function createGoal()
    {
        $user = auth()->user();
        $companies = $user->isSuperAdmin() ? DB::table('companies')->get() : [$user->company];
        $users = $user->isSuperAdmin() ? User::with('company')->get() : User::where('company_id', $user->company_id)->get();
        $teams = $user->isSuperAdmin() ? Team::with('company')->get() : Team::where('company_id', $user->company_id)->get();
        
        return view('management.goals.create', compact('companies', 'users', 'teams'));
    }
    
    public function storeGoal(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'nullable|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'type' => 'required|in:sales,revenue,leads,calls,meetings,individual,team,company',
            'target_value' => 'required|numeric|min:0',
            'current_value' => 'numeric|min:0',
            'measurement_unit' => 'required|string|max:50',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:draft,active,completed,cancelled',
            'milestones' => 'nullable|json'
        ]);
        
        Goal::create($validated);
        
        return redirect()->route('management.goals.index')
            ->with('success', 'Goal created successfully');
    }
    
    // Goal Edit and Update Methods
    public function editGoal($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Goal::with(['user', 'team', 'company']);
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $goal = $query->findOrFail($id);
        $companies = $isSuperAdmin ? DB::table('companies')->get() : [$user->company];
        $users = $isSuperAdmin ? User::with('company')->get() : User::where('company_id', $user->company_id)->get();
        $teams = $isSuperAdmin ? Team::with('company')->get() : Team::where('company_id', $user->company_id)->get();
        
        return view('management.goals.edit', compact('goal', 'companies', 'users', 'teams', 'isSuperAdmin'));
    }
    
    public function updateGoal(Request $request, $id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Goal::query();
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $goal = $query->findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:sales,revenue,leads,calls,meetings,individual,team,company',
            'target_value' => 'required|numeric|min:0',
            'current_value' => 'numeric|min:0',
            'measurement_unit' => 'required|string|max:50',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:draft,active,completed,cancelled',
            'milestones' => 'nullable|json'
        ]);
        
        $goal->update($validated);
        
        return redirect()->route('management.goals.index')
            ->with('success', 'Goal updated successfully');
    }
    
    // Team Performance Methods
    public function performance()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = TeamPerformanceMetric::with(['user', 'team', 'company']);
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $metrics = $query->latest()->paginate(15);
        $teams = $isSuperAdmin ? Team::all() : Team::where('company_id', $user->company_id)->get();
        
        // Calculate summary statistics
        $metricsQuery = TeamPerformanceMetric::query();
        if (!$isSuperAdmin) {
            $metricsQuery->where('company_id', $user->company_id);
        }
        
        $totalRevenue = $metricsQuery->sum('revenue_generated') ?? 0;
        $totalDeals = $metricsQuery->sum('deals_closed') ?? 0;
        $totalLeads = $metricsQuery->sum('leads_generated') ?? 0;
        $avgProductivity = $metricsQuery->avg('productivity_score') ?? 0;
        
        return view('management.performance.index', compact(
            'metrics', 'teams', 'isSuperAdmin', 'totalRevenue', 
            'totalDeals', 'totalLeads', 'avgProductivity'
        ));
    }
    
    // Activity Feed
    public function activities()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = TeamActivity::with(['user', 'team', 'company']);
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $activities = $query->latest()->paginate(20);
        
        return view('management.activities.index', compact('activities', 'isSuperAdmin'));
    }
    
    // Territory Detail View
    public function showTerritory($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Territory::with(['assignedUsers', 'leads', 'properties']);
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $territory = $query->findOrFail($id);
        
        return view('management.territories.show', compact('territory', 'isSuperAdmin'));
    }
    
    // Goal Detail View
    public function showGoal($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        $query = Goal::with(['user', 'team', 'company']);
        if (!$isSuperAdmin) {
            $query->where('company_id', $user->company_id);
        }
        
        $goal = $query->findOrFail($id);
        
        return view('management.goals.show', compact('goal', 'isSuperAdmin'));
    }
}
