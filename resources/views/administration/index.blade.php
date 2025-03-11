<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Administration') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    @include('components.layouts.alert-scripts')
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Admin Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Administration Dashboard') }}</h1>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <!-- Total Properties -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-building text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Properties') }}</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['properties']['total'] ?? 0 }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <i class="fas fa-arrow-up"></i>
                                        <span class="ml-1">12%</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Employees -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-indigo-100 rounded-full p-3">
                                <i class="fas fa-users text-indigo-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Employees') }}</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['employees']['total'] ?? 0 }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <i class="fas fa-arrow-up"></i>
                                        <span class="ml-1">4%</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Leads -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-user-check text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Active Leads') }}</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['leads']['active'] ?? 0 }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <i class="fas fa-arrow-up"></i>
                                        <span class="ml-1">8%</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

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
                        <a href="{{ route('employees.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-user-plus text-blue-600 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">{{ __('Add Employee') }}</span>
                        </a>
                        <a href="{{ route('properties.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-building text-indigo-600 text-xl mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">{{ __('Add Property') }}</span>
                        </a>
                        <a href="{{ route('leads.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-user-check text-green-600 text-xl mr-3"></i>
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
