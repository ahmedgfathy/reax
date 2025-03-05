<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Dashboard') }} - REAX CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ __('Dashboard') }}</h1>
                    <p class="text-gray-500">{{ __('Welcome back') }}, {{ auth()->user()->name }}!</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ now()->format('l, F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-gray-500 text-sm">{{ __('Total Properties') }}</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['properties_count'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full text-blue-500">
                        <i class="fas fa-home text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-gray-500 text-sm">{{ __('Total Leads') }}</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['leads_count'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full text-green-500">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-gray-500 text-sm">{{ __('Active Leads') }}</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['active_leads_count'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full text-purple-500">
                        <i class="fas fa-user-tag text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-gray-500 text-sm">{{ __('Potential Revenue') }}</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['revenue_potential']) }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full text-yellow-500">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Leads - LEFT SECTION -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="border-b px-6 py-3 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('Recent Leads') }}</h2>
                        <a href="{{ route('leads.index') }}" class="text-blue-600 text-sm hover:underline">{{ __('View All') }}</a>
                    </div>
                    
                    <div class="overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                            @forelse($recent_leads as $lead)
                                <div class="bg-gray-50 hover:bg-gray-100 transition-colors rounded-lg p-4 relative">
                                    <!-- Status indicator -->
                                    <div class="absolute top-4 right-4">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $lead->status == 'new' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $lead->status == 'contacted' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                            {{ $lead->status == 'qualified' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $lead->status == 'proposal' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $lead->status == 'negotiation' ? 'bg-orange-100 text-orange-700' : '' }}
                                            {{ $lead->status == 'won' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $lead->status == 'lost' ? 'bg-red-100 text-red-700' : '' }}
                                        ">
                                            {{ __(ucfirst($lead->status)) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-start space-x-3">
                                        <!-- Avatar placeholder -->
                                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-lg">
                                            {{ strtoupper(substr($lead->first_name, 0, 1)) }}{{ strtoupper(substr($lead->last_name, 0, 1)) }}
                                        </div>
                                        
                                        <div class="flex-grow">
                                            <h3 class="font-semibold text-gray-800">
                                                <a href="{{ route('leads.show', $lead->id) }}" class="hover:text-blue-600">
                                                    {{ $lead->first_name }} {{ $lead->last_name }}
                                                </a>
                                            </h3>
                                            
                                            <div class="grid grid-cols-1 gap-1 mt-1">
                                                @if($lead->email)
                                                <div class="flex items-center text-xs">
                                                    <span class="text-gray-500 w-5"><i class="fas fa-envelope"></i></span>
                                                    <span class="ml-2 text-gray-700 truncate">{{ $lead->email }}</span>
                                                </div>
                                                @endif
                                                
                                                @if($lead->phone)
                                                <div class="flex items-center text-xs">
                                                    <span class="text-gray-500 w-5"><i class="fas fa-phone"></i></span>
                                                    <span class="ml-2 text-gray-700">{{ $lead->phone }}</span>
                                                </div>
                                                @endif
                                                
                                                @if($lead->interestedProperty)
                                                <div class="flex items-center text-xs">
                                                    <span class="text-gray-500 w-5"><i class="fas fa-home"></i></span>
                                                    <span class="ml-2 text-gray-700 truncate">{{ $lead->interestedProperty->name }}</span>
                                                </div>
                                                @endif
                                                
                                                @if($lead->budget)
                                                <div class="flex items-center text-xs">
                                                    <span class="text-gray-500 w-5"><i class="fas fa-dollar-sign"></i></span>
                                                    <span class="ml-2 text-gray-700">{{ number_format($lead->budget) }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-2 flex items-center justify-between">
                                                <span class="text-xs text-gray-500">{{ $lead->created_at->diffForHumans() }}</span>
                                                
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('leads.show', $lead->id) }}" class="text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('leads.edit', $lead->id) }}" class="text-yellow-600 hover:text-yellow-800">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 p-4 text-center text-gray-500">
                                    {{ __('No leads found.') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- RIGHT SIDEBAR -->
            <div class="space-y-6">
                <!-- Upcoming Events -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="border-b px-6 py-3">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('Upcoming Events') }}</h2>
                    </div>
                    
                    <div class="p-4">
                        @forelse($upcoming_events as $event)
                            <div class="mb-3 pb-3 border-b last:border-0 last:pb-0 last:mb-0">
                                <div class="flex">
                                    <div class="mr-3">
                                        <div class="w-10 h-10 rounded-full 
                                            {{ $event->event_type == 'meeting' ? 'bg-blue-100 text-blue-600' : '' }}
                                            {{ $event->event_type == 'call' ? 'bg-green-100 text-green-600' : '' }}
                                            {{ $event->event_type == 'email' ? 'bg-purple-100 text-purple-600' : '' }}
                                            {{ $event->event_type == 'follow_up' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                            {{ $event->event_type == 'other' ? 'bg-gray-100 text-gray-600' : '' }}
                                            flex items-center justify-center">
                                            <i class="fas 
                                                {{ $event->event_type == 'meeting' ? 'fa-handshake' : '' }}
                                                {{ $event->event_type == 'call' ? 'fa-phone' : '' }}
                                                {{ $event->event_type == 'email' ? 'fa-envelope' : '' }}
                                                {{ $event->event_type == 'follow_up' ? 'fa-reply' : '' }}
                                                {{ $event->event_type == 'other' ? 'fa-calendar' : '' }}
                                            "></i>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-grow">
                                        <h3 class="font-medium text-gray-800">{{ $event->title }}</h3>
                                        
                                        <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
                                            <span>
                                                <i class="far fa-clock mr-1"></i> 
                                                {{ $event->event_date->format('M d, Y g:i A') }}
                                            </span>
                                            
                                            @if($event->lead)
                                            <a href="{{ route('leads.show', $event->lead->id) }}" class="text-blue-600 hover:underline">
                                                {{ $event->lead->first_name }} {{ $event->lead->last_name }}
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">{{ __('No upcoming events') }}</p>
                        @endforelse
                    </div>
                </div>
                
                <!-- Lead Distribution by Status Chart -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Lead Status Distribution') }}</h2>
                    <div>
                        <canvas id="leadStatusChart" height="200"></canvas>
                    </div>
                </div>
                
                <!-- Lead Distribution by Source Chart -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Lead Source Distribution') }}</h2>
                    <div>
                        <canvas id="leadSourceChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set up the lead status chart
            const statusCtx = document.getElementById('leadStatusChart').getContext('2d');
            
            // Define status labels and color map
            const statusLabels = {
                'new': '{{ __("New") }}',
                'contacted': '{{ __("Contacted") }}',
                'qualified': '{{ __("Qualified") }}',
                'proposal': '{{ __("Proposal") }}',
                'negotiation': '{{ __("Negotiation") }}',
                'won': '{{ __("Won") }}',
                'lost': '{{ __("Lost") }}'
            };
            
            const statusColorMap = {
                'new': '#3B82F6', // blue-500
                'contacted': '#6366F1', // indigo-500
                'qualified': '#8B5CF6', // purple-500
                'proposal': '#F59E0B', // yellow-500
                'negotiation': '#F97316', // orange-500
                'won': '#22C55E', // green-500
                'lost': '#EF4444', // red-500
            };
            
            // Prepare data for status chart
            const leadStatusData = @json($lead_statuses);
            const statusLabelsArray = [];
            const statusDataArray = [];
            const statusBackgroundColors = [];
            
            Object.entries(statusLabels).forEach(([status, label]) => {
                statusLabelsArray.push(label);
                statusDataArray.push(leadStatusData[status] || 0);
                statusBackgroundColors.push(statusColorMap[status]);
            });
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabelsArray,
                    datasets: [{
                        data: statusDataArray,
                        backgroundColor: statusBackgroundColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
            
            // Set up the lead source chart
            const sourceCtx = document.getElementById('leadSourceChart').getContext('2d');
            
            // Predefined color palette for sources
            const sourceColors = [
                '#10B981', // emerald-500
                '#F59E0B', // amber-500
                '#3B82F6', // blue-500
                '#EC4899', // pink-500
                '#8B5CF6', // purple-500
                '#14B8A6', // teal-500
                '#F97316', // orange-500
                '#6366F1', // indigo-500
                '#EF4444', // red-500
                '#84CC16', // lime-500
                '#06B6D4', // cyan-500
                '#A855F7', // purple-500
                '#64748B', // slate-500
            ];
            
            // Prepare data for source chart
            const leadSourceData = @json($lead_sources);
            const sourceLabels = Object.keys(leadSourceData);
            const sourceData = Object.values(leadSourceData);
            
            // Generate colors based on number of sources
            const sourceBackgroundColors = sourceLabels.map((_, index) => 
                sourceColors[index % sourceColors.length]
            );
            
            new Chart(sourceCtx, {
                type: 'doughnut',
                data: {
                    labels: sourceLabels,
                    datasets: [{
                        data: sourceData,
                        backgroundColor: sourceBackgroundColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
