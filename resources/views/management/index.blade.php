    @extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Management Dashboard</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        @if($isSuperAdmin)
                            Global management overview across all companies
                        @else
                            Comprehensive management overview for {{ auth()->user()->company->name }}
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('management.territories.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-map-marker-alt mr-2"></i>Territories
                    </a>
                    <a href="{{ route('management.goals.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-bullseye mr-2"></i>Goals
                    </a>
                    <a href="{{ route('management.performance.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-chart-line mr-2"></i>Performance
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-8">
            <!-- Teams -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Teams</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['teams_count']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-user-tie text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active Staff</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['staff_count']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Territories -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-purple-100 rounded-full p-3">
                                <i class="fas fa-map-marker-alt text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Territories</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['territories_count']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Goals -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-yellow-100 rounded-full p-3">
                                <i class="fas fa-bullseye text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active Goals</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_goals']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($isSuperAdmin)
            <!-- Companies (Super Admin only) -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-red-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-red-100 rounded-full p-3">
                                <i class="fas fa-building text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Companies</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['companies_count']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Performance Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Current Month Performance -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Current Month Performance</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($performanceData['current']->total_leads ?? 0) }}</div>
                            <div class="text-sm text-gray-500">Leads Generated</div>
                            @if(isset($performanceData['growth']['leads_growth']))
                                <div class="text-xs {{ $performanceData['growth']['leads_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $performanceData['growth']['leads_growth'] >= 0 ? '+' : '' }}{{ $performanceData['growth']['leads_growth'] }}%
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($performanceData['current']->total_conversions ?? 0) }}</div>
                            <div class="text-sm text-gray-500">Conversions</div>
                            @if(isset($performanceData['growth']['conversion_growth']))
                                <div class="text-xs {{ $performanceData['growth']['conversion_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $performanceData['growth']['conversion_growth'] >= 0 ? '+' : '' }}{{ $performanceData['growth']['conversion_growth'] }}%
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">${{ number_format($performanceData['current']->total_revenue ?? 0, 0) }}</div>
                            <div class="text-sm text-gray-500">Revenue</div>
                            @if(isset($performanceData['growth']['revenue_growth']))
                                <div class="text-xs {{ $performanceData['growth']['revenue_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $performanceData['growth']['revenue_growth'] >= 0 ? '+' : '' }}{{ $performanceData['growth']['revenue_growth'] }}%
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ number_format($performanceData['current']->total_deals ?? 0) }}</div>
                            <div class="text-sm text-gray-500">Deals Closed</div>
                            @if(isset($performanceData['growth']['deals_growth']))
                                <div class="text-xs {{ $performanceData['growth']['deals_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $performanceData['growth']['deals_growth'] >= 0 ? '+' : '' }}{{ $performanceData['growth']['deals_growth'] }}%
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Analytics -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Revenue Analytics</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Current Month</span>
                            <span class="text-lg font-bold text-green-600">${{ number_format($revenueData['current_month_revenue'], 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Pipeline Value</span>
                            <span class="text-lg font-bold text-blue-600">${{ number_format($revenueData['pipeline_value'], 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Won Deals</span>
                            <span class="text-lg font-bold text-purple-600">{{ number_format($revenueData['won_deals_count']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Growth</span>
                            <span class="text-lg font-bold {{ $revenueData['revenue_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $revenueData['revenue_growth'] >= 0 ? '+' : '' }}{{ $revenueData['revenue_growth'] }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Goals Overview -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Goals Overview</h3>
                    <a href="{{ route('management.goals.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">{{ $goalData['total_goals'] }}</div>
                        <div class="text-sm text-gray-500">Total Goals</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $goalData['completed_goals'] }}</div>
                        <div class="text-sm text-gray-500">Completed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600">{{ $goalData['in_progress_goals'] }}</div>
                        <div class="text-sm text-gray-500">In Progress</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600">{{ $goalData['upcoming_deadlines'] }}</div>
                        <div class="text-sm text-gray-500">Due Soon</div>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Average Completion</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($goalData['avg_completion'], 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $goalData['avg_completion'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Individual Performers -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Top Individual Performers</h3>
                </div>
                <div class="p-6">
                    @if($topPerformers['top_individuals']->count() > 0)
                        <div class="space-y-4">
                            @foreach($topPerformers['top_individuals'] as $index => $performer)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium text-blue-600">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $performer->user->name ?? 'Unknown' }}</div>
                                            <div class="text-xs text-gray-500">{{ $performer->total_deals }} deals • {{ number_format($performer->avg_productivity, 1) }}% productivity</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">${{ number_format($performer->total_revenue, 0) }}</div>
                                        <div class="text-xs text-gray-500">{{ $performer->total_points }} points</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No performance data available for this period</p>
                    @endif
                </div>
            </div>

            <!-- Top Team Performers -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Top Team Performers</h3>
                </div>
                <div class="p-6">
                    @if($topPerformers['top_teams']->count() > 0)
                        <div class="space-y-4">
                            @foreach($topPerformers['top_teams'] as $index => $team)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium text-green-600">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $team->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $team->users->count() }} members</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">${{ number_format($team->performance_metrics_sum_revenue_generated ?? 0, 0) }}</div>
                                        <div class="text-xs text-gray-500">{{ number_format($team->performance_metrics_avg_achievement_percentage ?? 0, 1) }}% achievement</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No team performance data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Recent Team Activities</h3>
                    <a href="{{ route('management.activities.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                @if($activityData['recent_activities']->count() > 0)
                    <div class="space-y-4">
                        @foreach($activityData['recent_activities'] as $activity)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->user->name ?? 'Unknown User' }}</div>
                                    <div class="text-sm text-gray-500">{{ $activity->description }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($activity->activity_type) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No recent activities</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
