@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Administration Dashboard</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        @if($isSuperAdmin)
                            Global administration overview across all companies and systems
                        @else
                            Administration overview for {{ auth()->user()->company->name }}
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('administration.users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-users mr-2"></i>Manage Users
                    </a>
                    <a href="{{ route('administration.role-management.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-users-cog mr-2"></i>Roles & Permissions
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Users Statistics -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['users']['total']) }}</p>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            {{ $stats['users']['new_this_month'] }} new this month
                        </p>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Active:</span>
                        <span class="font-medium">{{ $stats['users']['active'] }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Admins:</span>
                        <span class="font-medium">{{ $stats['users']['admins'] }}</span>
                    </div>
                </div>
            </div>

            @if($isSuperAdmin)
            <!-- Companies Statistics -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-building text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Companies</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['companies']['total']) }}</p>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            {{ $stats['companies']['active'] }} active
                        </p>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">New this month:</span>
                    <span class="font-medium">{{ $stats['companies']['new_this_month'] }}</span>
                </div>
            </div>
            @endif

            <!-- Properties Statistics -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <i class="fas fa-home text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Properties</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['properties']['total']) }}</p>
                        <p class="text-xs text-blue-600">
                            <i class="fas fa-star mr-1"></i>
                            {{ $stats['properties']['featured'] }} featured
                        </p>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">Published:</span>
                    <span class="font-medium">{{ $stats['properties']['published'] }}</span>
                </div>
            </div>

            <!-- Leads Statistics -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100">
                        <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Leads</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['leads']['total']) }}</p>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-trophy mr-1"></i>
                            {{ $stats['leads']['won_this_month'] }} won this month
                        </p>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">Active:</span>
                    <span class="font-medium">{{ $stats['leads']['active'] }}</span>
                </div>
            </div>
        </div>

        <!-- System Health & Performance Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- System Health -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">System Health</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Database Size -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Database Size</span>
                            <span class="text-sm font-medium">{{ $systemHealth['database_size'] }} MB</span>
                        </div>
                        <!-- Storage Used -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Storage Used</span>
                            <span class="text-sm font-medium">{{ $systemHealth['storage_used'] }} MB</span>
                        </div>
                        <!-- Active Sessions -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Active Sessions</span>
                            <span class="text-sm font-medium">{{ $systemHealth['active_sessions'] }}</span>
                        </div>
                        <!-- Memory Usage -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Memory Usage</span>
                            <span class="text-sm font-medium">{{ $systemHealth['memory_usage'] }} MB</span>
                        </div>
                        <!-- Disk Space -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Disk Space</span>
                                <span class="text-sm font-medium">{{ $systemHealth['disk_space']['percentage'] }}% used</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $systemHealth['disk_space']['percentage'] }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ $systemHealth['disk_space']['used'] }} GB used</span>
                                <span>{{ $systemHealth['disk_space']['free'] }} GB free</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Performance Metrics</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Conversion Rate -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Conversion Rate</span>
                                <span class="text-sm font-medium">{{ $performanceMetrics['conversion_rate'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $performanceMetrics['conversion_rate'] }}%"></div>
                            </div>
                        </div>
                        <!-- Average Deal Size -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Avg Deal Size</span>
                            <span class="text-sm font-medium">${{ number_format($performanceMetrics['average_deal_size'], 2) }}</span>
                        </div>
                        <!-- Sales Velocity -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Sales Velocity</span>
                            <span class="text-sm font-medium">{{ $performanceMetrics['sales_velocity'] }} days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Analytics & Security Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- User Analytics -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">User Analytics</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Users by Role -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Users by Role</h4>
                            @foreach($userAnalytics['by_role'] as $role => $count)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                                <span class="text-sm font-medium">{{ $count }}</span>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Login Activity -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Login Activity</h4>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-lg font-bold text-blue-600">{{ $userAnalytics['login_activity']['today'] }}</div>
                                    <div class="text-xs text-gray-500">Today</div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-blue-600">{{ $userAnalytics['login_activity']['this_week'] }}</div>
                                    <div class="text-xs text-gray-500">This Week</div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-blue-600">{{ $userAnalytics['login_activity']['this_month'] }}</div>
                                    <div class="text-xs text-gray-500">This Month</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Overview -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Security Overview</h3>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">{{ $securityOverview['security_score'] }}% Secure</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Failed Logins -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Failed Logins (24h)</span>
                            <span class="text-sm font-medium {{ $securityOverview['failed_logins'] > 10 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $securityOverview['failed_logins'] }}
                            </span>
                        </div>
                        <!-- Suspicious Activity -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Suspicious Activity</span>
                            <span class="text-sm font-medium {{ $securityOverview['suspicious_activity'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $securityOverview['suspicious_activity'] }}
                            </span>
                        </div>
                        <!-- Permission Changes -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Permission Changes (7d)</span>
                            <span class="text-sm font-medium">{{ $securityOverview['permission_changes'] }}</span>
                        </div>
                        
                        <!-- Security Score -->
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $securityOverview['security_score'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @forelse($recentActivity as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <span>{{ $activity->user->name ?? 'System' }}</span>
                                    <span class="mx-1">•</span>
                                    <span>{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($quickActions as $action)
                        <a href="{{ $action['url'] }}" class="flex items-center p-4 bg-{{ $action['color'] }}-50 hover:bg-{{ $action['color'] }}-100 rounded-lg transition-colors duration-200">
                            <div class="flex-shrink-0">
                                <i class="{{ $action['icon'] }} text-{{ $action['color'] }}-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $action['title'] }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Organizational Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Teams Overview -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Teams</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">{{ $stats['teams']['total'] }}</div>
                        <div class="text-sm text-gray-500">Total Teams</div>
                        <div class="mt-2">
                            <span class="text-sm text-green-600">{{ $stats['teams']['active'] }} active</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('teams.index') }}" class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 text-center py-2 rounded-md text-sm font-medium transition-colors">
                            Manage Teams
                        </a>
                    </div>
                </div>
            </div>

            <!-- Departments Overview -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Departments</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">{{ $stats['departments']['total'] }}</div>
                        <div class="text-sm text-gray-500">Total Departments</div>
                        <div class="mt-2">
                            <span class="text-sm text-green-600">{{ $stats['departments']['active'] }} active</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('departments.index') }}" class="block w-full bg-purple-50 hover:bg-purple-100 text-purple-700 text-center py-2 rounded-md text-sm font-medium transition-colors">
                            Manage Departments
                        </a>
                    </div>
                </div>
            </div>

            <!-- Branches Overview -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Branches</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">{{ $stats['branches']['total'] }}</div>
                        <div class="text-sm text-gray-500">Total Branches</div>
                        <div class="mt-2">
                            <span class="text-sm text-green-600">{{ $stats['branches']['active'] }} active</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('branches.index') }}" class="block w-full bg-green-50 hover:bg-green-100 text-green-700 text-center py-2 rounded-md text-sm font-medium transition-colors">
                            Manage Branches
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

            <!-- Total Revenue -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-yellow-100 rounded-full p-3">
                                <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Revenue') }}</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">${{ number_format($stats['revenue']['total'] ?? 0) }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <i class="fas fa-arrow-up"></i>
                                        <span class="ml-1">15%</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity & Performance Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Activity -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent Activity') }}</h3>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($activities ?? [] as $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <i class="fas fa-user-circle text-white"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $activity->created_at }}">{{ $activity->created_at->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="text-gray-500 text-center py-4">{{ __('No recent activity') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Performance Metrics') }}</h3>
                    <div class="space-y-4">
                        <!-- Leads Conversion Rate -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-600">{{ __('Leads Conversion') }}</span>
                                <span class="text-sm font-medium text-gray-900">65%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>

                        <!-- Property Sales -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-600">{{ __('Property Sales') }}</span>
                                <span class="text-sm font-medium text-gray-900">78%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>

                        <!-- Team Performance -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-600">{{ __('Team Performance') }}</span>
                                <span class="text-sm font-medium text-gray-900">92%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Employees -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Quick Actions') }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Role Management -->
                        <a href="{{ route('administration.role-management.index') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-colors">
                            <i class="fas fa-users-cog text-purple-600 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">{{ __('Role Management') }}</span>
                        </a>
                        
                        <!-- Profile Management -->
                        <a href="{{ route('administration.profiles.index') }}" class="flex items-center p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg hover:from-indigo-100 hover:to-indigo-200 transition-colors">
                            <i class="fas fa-id-card text-indigo-600 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">{{ __('User Profiles') }}</span>
                        </a>
                        
                        <a href="{{ route('administration.employees.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-user-plus text-blue-600 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">{{ __('Add Employee') }}</span>
                        </a>
                        <a href="{{ route('properties.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-building text-green-600 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">{{ __('Add Property') }}</span>
                        </a>
                        <a href="{{ route('leads.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-user-check text-orange-600 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">{{ __('Add Lead') }}</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-chart-bar text-yellow-600 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">{{ __('View Reports') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Employees Section from original file -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('Recent Employees') }}</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Joined') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($stats['employees']['recent'] as $employee)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $employee->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $employee->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $employee->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('No recent employees') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ...existing JavaScript code...
    </script>
</body>
</html>
