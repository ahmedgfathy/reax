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
        <!-- Tabs -->
        <div x-data="{ activeTab: 'properties' }" class="mb-6">
            <div class="border-b border-gray-200">
                <ul class="flex -mb-px">
                    <li @click="activeTab = 'properties'" :class="{'border-b-2 border-blue-500': activeTab === 'properties'}" class="mr-6 py-2 cursor-pointer">
                        <span :class="{'text-blue-600 font-medium': activeTab === 'properties', 'text-gray-500': activeTab !== 'properties'}" 
                              class="flex items-center">
                            <i class="fas fa-home mr-2"></i> {{ __('Properties') }}
                        </span>
                    </li>
                    <li @click="activeTab = 'leads'" :class="{'border-b-2 border-blue-500': activeTab === 'leads'}" class="mr-6 py-2 cursor-pointer">
                        <span :class="{'text-blue-600 font-medium': activeTab === 'leads', 'text-gray-500': activeTab !== 'leads'}" 
                              class="flex items-center">
                            <i class="fas fa-users mr-2"></i> {{ __('Leads') }}
                        </span>
                    </li>

                    <!-- Add Opportunities Tab -->
                    <li @click="activeTab = 'opportunities'" :class="{'border-b-2 border-blue-500': activeTab === 'opportunities'}" class="mr-6 py-2 cursor-pointer">
                        <span :class="{'text-blue-600 font-medium': activeTab === 'opportunities', 'text-gray-500': activeTab !== 'opportunities'}" 
                              class="flex items-center">
                            <i class="fas fa-handshake mr-2"></i> {{ __('Opportunities') }}
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Leads Stats -->
            <div x-show="activeTab === 'leads'">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
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
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
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

            <!-- Properties Stats -->
            <div x-show="activeTab === 'properties'" class="mt-6">
                <!-- Property Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">{{ __('Total Properties') }}</h3>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['properties_count'] }}</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full text-blue-500">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">{{ __('Total Sale Value') }}</h3>
                                <p class="text-3xl font-bold text-gray-800">{{ number_format($propertyStats['total_sale_value']) }}</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full text-green-500">
                                <i class="fas fa-tag text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">{{ __('Total Rent Value') }}</h3>
                                <p class="text-3xl font-bold text-gray-800">{{ number_format($propertyStats['total_rent_value']) }}</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full text-purple-500">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">{{ __('Featured Properties') }}</h3>
                                <p class="text-3xl font-bold text-gray-800">{{ $propertyStats['featured_properties'] }}</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full text-yellow-500">
                                <i class="fas fa-star text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Property Type Distribution -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Property Types') }}</h2>
                        <div>
                            <canvas id="propertyTypeChart" height="250"></canvas>
                        </div>
                    </div>
                    
                    <!-- Sale vs Rent Distribution -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Sale vs Rent Properties') }}</h2>
                        <div>
                            <canvas id="saleRentChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Price Range Distribution (Sale) -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Sale Price Ranges') }}</h2>
                        <div>
                            <canvas id="salePriceChart" height="250"></canvas>
                        </div>
                    </div>
                    
                    <!-- Price Range Distribution (Rent) -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Rent Price Ranges') }}</h2>
                        <div>
                            <canvas id="rentPriceChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 mt-6">
                    <!-- Monthly Listings -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Monthly Property Listings') }}</h2>
                        <div>
                            <canvas id="monthlyListingsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Properties -->
                <div class="mt-6 bg-white rounded-lg shadow-sm">
                    <div class="border-b px-6 py-3 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('Recently Added Properties') }}</h2>
                        <a href="{{ route('properties.index') }}" class="text-blue-600 text-sm hover:underline">{{ __('View All') }}</a>
                    </div>
                    
                    <div class="overflow-hidden">
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('For') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Location') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Added') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($recent_properties as $property)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('properties.show', $property->id) }}" class="text-blue-600 hover:underline font-medium">
                                                        {{ Str::limit($property->name, 30) }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-2 text-sm">{{ ucfirst($property->type ?? 'N/A') }}</td>
                                                <td class="px-4 py-2 text-sm">
                                                    <span class="px-2 py-1 text-xs rounded-full {{ $property->unit_for === 'sale' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                                        {{ ucfirst($property->unit_for ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 text-sm">{{ number_format($property->price ?? 0) }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $property->area ?? $property->location ?? 'N/A' }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $property->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                                    {{ __('No properties found.') }}
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

            <!-- Opportunities Stats Panel -->
            <div x-show="activeTab === 'opportunities'" class="mt-6">
                <!-- Opportunities Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Opportunities -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">{{ __('Total Opportunities') }}</h3>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['opportunities_count'] ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full text-blue-500">
                                <i class="fas fa-handshake text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Won Opportunities -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">{{ __('Won Opportunities') }}</h3>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['won_opportunities'] ?? 0 }}</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full text-green-500">
                                <i class="fas fa-trophy text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pipeline Value -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">{{ __('Pipeline Value') }}</h3>
                                <p class="text-3xl font-bold text-gray-800">${{ number_format($stats['pipeline_value'] ?? 0) }}</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full text-purple-500">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Conversion Rate -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">{{ __('Conversion Rate') }}</h3>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['conversion_rate'] ?? 0 }}%</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full text-yellow-500">
                                <i class="fas fa-percentage text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Opportunity Stage Distribution -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Opportunity Stages') }}</h2>
                        <div>
                            <canvas id="opportunityStageChart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Monthly Win Rate -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Monthly Win Rate') }}</h2>
                        <div>
                            <canvas id="monthlyWinRateChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Opportunities Table -->
                <div class="mt-6 bg-white rounded-lg shadow-sm">
                    <div class="border-b px-6 py-3 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('Recent Opportunities') }}</h2>
                        <a href="{{ route('opportunities.index') }}" class="text-blue-600 text-sm hover:underline">{{ __('View All') }}</a>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Stage') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Value') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Close Date') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Assigned To') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recent_opportunities ?? [] as $opportunity)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <a href="{{ route('opportunities.show', $opportunity->id) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $opportunity->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    {{ $opportunity->stage == 'initial' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $opportunity->stage == 'qualified' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $opportunity->stage == 'proposal' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $opportunity->stage == 'negotiation' ? 'bg-purple-100 text-purple-800' : '' }}
                                                    {{ $opportunity->stage == 'closed_won' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $opportunity->stage == 'closed_lost' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ __(ucfirst(str_replace('_', ' ', $opportunity->stage))) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($opportunity->value) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $opportunity->close_date?->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $opportunity->assignedTo?->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                {{ __('No recent opportunities') }}
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
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add this code to ensure the tabs are working correctly
            console.log("Dashboard script loaded");
            
            // Set up tab functionality manually if Alpine.js isn't working
            const propertyTab = document.querySelector('[x-data] li:first-child');
            const leadTab = document.querySelector('[x-data] li:nth-child(2)');
            const propertiesPanel = document.querySelector('[x-show="activeTab === \'properties\'"]');
            const leadsPanel = document.querySelector('[x-show="activeTab === \'leads\'"]');
            
            if (propertyTab && leadTab && propertiesPanel && leadsPanel) {
                console.log("Found tab elements");
                
                // Set properties tab as active by default
                propertyTab.classList.add('border-b-2', 'border-blue-500');
                propertyTab.querySelector('span').classList.add('text-blue-600', 'font-medium');
                propertyTab.querySelector('span').classList.remove('text-gray-500');
                
                leadTab.classList.remove('border-b-2', 'border-blue-500');
                leadTab.querySelector('span').classList.add('text-gray-500');
                leadTab.querySelector('span').classList.remove('text-blue-600', 'font-medium');
                
                propertiesPanel.style.display = 'block';
                leadsPanel.style.display = 'none';
                
                // Manually toggle tabs if needed
                propertyTab.addEventListener('click', function() {
                    console.log("Property tab clicked");
                    propertyTab.classList.add('border-b-2', 'border-blue-500');
                    leadTab.classList.remove('border-b-2', 'border-blue-500');
                    
                    propertyTab.querySelector('span').classList.add('text-blue-600', 'font-medium');
                    propertyTab.querySelector('span').classList.remove('text-gray-500');
                    leadTab.querySelector('span').classList.add('text-gray-500');
                    leadTab.querySelector('span').classList.remove('text-blue-600', 'font-medium');
                    
                    propertiesPanel.style.display = 'block';
                    leadsPanel.style.display = 'none';
                    
                    // Trigger resize to ensure charts render correctly
                    window.dispatchEvent(new Event('resize'));
                });
                
                leadTab.addEventListener('click', function() {
                    console.log("Lead tab clicked");
                    leadTab.classList.add('border-b-2', 'border-blue-500');
                    propertyTab.classList.remove('border-b-2', 'border-blue-500');
                    
                    leadTab.querySelector('span').classList.add('text-blue-600', 'font-medium');
                    leadTab.querySelector('span').classList.remove('text-gray-500');
                    propertyTab.querySelector('span').classList.add('text-gray-500');
                    propertyTab.querySelector('span').classList.remove('text-blue-600', 'font-medium');
                    
                    leadsPanel.style.display = 'block';
                    propertiesPanel.style.display = 'none';
                });
            }
            
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
                        }
                    }
                }
            });

            // PROPERTY CHARTS
            
            // Property Type Distribution
            const propertyTypeCtx = document.getElementById('propertyTypeChart').getContext('2d');
            const propertyTypeData = @json($propertyTypes);
            const propertyTypeLabels = Object.keys(propertyTypeData).map(type => type ? type.charAt(0).toUpperCase() + type.slice(1) : 'Other');
            const propertyTypeValues = Object.values(propertyTypeData);
            
            const propertyTypeColors = [
                '#3B82F6', // blue-500
                '#10B981', // emerald-500
                '#F59E0B', // amber-500
                '#8B5CF6', // violet-500
                '#EC4899', // pink-500
                '#F97316', // orange-500
            ];
            
            new Chart(propertyTypeCtx, {
                type: 'pie',
                data: {
                    labels: propertyTypeLabels,
                    datasets: [{
                        data: propertyTypeValues,
                        backgroundColor: propertyTypeColors.slice(0, propertyTypeLabels.length),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
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
            
            // Sale vs Rent Distribution
            const saleRentCtx = document.getElementById('saleRentChart').getContext('2d');
            const saleRentData = @json($propertyUnitFor);
            const saleRentLabels = Object.keys(saleRentData).map(type => type ? type.charAt(0).toUpperCase() + type.slice(1) : 'Other');
            const saleRentValues = Object.values(saleRentData);
            
            new Chart(saleRentCtx, {
                type: 'doughnut',
                data: {
                    labels: saleRentLabels,
                    datasets: [{
                        data: saleRentValues,
                        backgroundColor: ['#3B82F6', '#10B981'], // Blue for sale, green for rent
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
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
            
            // Price Range Charts
            const salePriceCtx = document.getElementById('salePriceChart').getContext('2d');
            const salePriceRanges = @json($salePriceRanges);
            
            new Chart(salePriceCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(salePriceRanges),
                    datasets: [{
                        label: '{{ __("Number of Properties") }}',
                        data: Object.values(salePriceRanges),
                        backgroundColor: '#3B82F6',
                        borderColor: '#2563EB',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            const rentPriceCtx = document.getElementById('rentPriceChart').getContext('2d');
            const rentPriceRanges = @json($rentPriceRanges);
            
            new Chart(rentPriceCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(rentPriceRanges),
                    datasets: [{
                        label: '{{ __("Number of Properties") }}',
                        data: Object.values(rentPriceRanges),
                        backgroundColor: '#10B981',
                        borderColor: '#059669',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Monthly Listings Chart
            const monthlyListingsCtx = document.getElementById('monthlyListingsChart').getContext('2d');
            const propertyTimeData = @json($propertyTimeData);
            
            new Chart(monthlyListingsCtx, {
                type: 'line',
                data: {
                    labels: propertyTimeData.labels,
                    datasets: [{
                        label: '{{ __("Properties Listed") }}',
                        data: propertyTimeData.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: '#3B82F6',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#3B82F6',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });

            // Opportunity Stage Distribution Chart
            const opportunityStageCtx = document.getElementById('opportunityStageChart').getContext('2d');
            const opportunityStageData = @json($opportunity_stages ?? []);
            
            new Chart(opportunityStageCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(opportunityStageData),
                    datasets: [{
                        data: Object.values(opportunityStageData),
                        backgroundColor: [
                            '#94A3B8', // Initial - slate
                            '#3B82F6', // Qualified - blue
                            '#F59E0B', // Proposal - amber
                            '#8B5CF6', // Negotiation - purple
                            '#22C55E', // Closed Won - green
                            '#EF4444', // Closed Lost - red
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });

            // Monthly Win Rate Chart
            const monthlyWinRateCtx = document.getElementById('monthlyWinRateChart').getContext('2d');
            const monthlyWinRateData = @json($monthly_win_rate ?? []);
            
            new Chart(monthlyWinRateCtx, {
                type: 'line',
                data: {
                    labels: monthlyWinRateData.labels,
                    datasets: [{
                        label: '{{ __("Win Rate") }}',
                        data: monthlyWinRateData.data,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + '%';
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
