<x-app-layout>
    <x-slot name="header">
        {{ __('Properties') }}
    </x-slot>
    
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700">{{ __('Properties') }}</span>
        @if(request()->has('type'))
            <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700">{{ __(ucfirst(request('type'))) }}</span>
        @endif
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6">
        @foreach([
            ['label' => 'Total Properties', 'value' => $stats['total'], 'icon' => 'fa-building', 'gradient' => 'from-blue-900 to-blue-800', 'description' => 'Total property count'],
            ['label' => 'For Sale', 'value' => $stats['for_sale'], 'icon' => 'fa-tag', 'gradient' => 'from-green-900 to-green-800', 'description' => 'Properties for sale'],
            ['label' => 'For Rent', 'value' => $stats['for_rent'], 'icon' => 'fa-key', 'gradient' => 'from-yellow-900 to-yellow-800', 'description' => 'Properties for rent']
        ] as $stat)
            <div class="bg-gradient-to-br {{ $stat['gradient'] }} overflow-hidden shadow-lg rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white/20 rounded-full p-3">
                                <i class="fas {{ $stat['icon'] }} text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-white">{{ __($stat['label']) }}</h3>
                            <p class="text-sm text-white/70">{{ __($stat['description']) }}</p>
                            <div class="mt-3">
                                <span class="text-2xl font-bold text-white">{{ number_format($stat['value']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow mb-3">
        <div class="p-2 sm:p-3">            <form method="GET" action="{{ route('properties.index') }}" class="space-y-2">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-2">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full px-3 py-1.5 pl-8 pr-3 text-sm rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               placeholder="{{ __('Search properties...') }}">
                        <i class="fas fa-search absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>

                    <!-- Region/Area Filter -->
                    <div class="relative">
                        <select name="region" class="w-full px-3 py-1.5 text-sm rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Regions') }}</option>
                            @foreach($regions as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                    {{ __(ucfirst($region)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>

                    <!-- User Filter -->
                    <div class="relative">
                        <select name="user_id" class="w-full px-3 py-1.5 text-sm rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Users') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>

                    <!-- Property Type Filter -->
                    <div class="relative">
                        <select name="type" class="w-full px-3 py-1.5 text-sm rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($propertyTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ __(ucfirst($type)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>

                    <!-- More Filters Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" 
                                @click="open = !open"
                                class="w-full px-3 py-1.5 text-sm rounded-lg border focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white text-left flex justify-between items-center">
                            <span>{{ __('More Filters') }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute z-50 mt-1 w-full bg-white rounded-lg shadow-lg border p-2 space-y-2">
                            <!-- Price Range -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Price Range') }}</label>
                                <select name="price_range" class="w-full text-sm rounded-md border-gray-300 py-1">
                                    <option value="">{{ __('Any Price') }}</option>
                                    <option value="0-100000" {{ request('price_range') == '0-100000' ? 'selected' : '' }}>0 - 100,000</option>
                                    <option value="100000-500000" {{ request('price_range') == '100000-500000' ? 'selected' : '' }}>100,000 - 500,000</option>
                                    <option value="500000+" {{ request('price_range') == '500000+' ? 'selected' : '' }}>500,000+</option>
                                </select>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                                <select name="status" class="w-full text-sm rounded-md border-gray-300 py-1">
                                    <option value="">{{ __('All Status') }}</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ __(ucfirst($status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Actions & Buttons Row -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-1 sm:space-y-0 pt-2 border-t">
                    <!-- Filter Actions & Apply Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-1 sm:space-y-0 sm:space-x-1">
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center">
                            <i class="fas fa-filter mr-1 text-xs"></i>{{ __('Apply Filters') }}
                        </button>
                        @if(request()->hasAny(['search', 'type', 'status', 'price_range', 'region', 'user_id']))
                            <a href="{{ route('properties.index') }}" class="px-3 py-1.5 text-sm bg-gray-500 text-white rounded-lg hover:bg-gray-600 flex items-center justify-center">
                                <i class="fas fa-times mr-1 text-xs"></i>{{ __('Clear') }}
                            </a>
                        @endif
                        
                        <!-- Action Buttons (moved beside filter buttons) -->
                        <button type="button" onclick="exportProperties()" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center">
                            <i class="fas fa-download mr-1 text-xs"></i>{{ __('Export') }}
                        </button>
                        <button type="button" onclick="importProperties()" class="px-3 py-1.5 text-sm bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center justify-center">
                            <i class="fas fa-upload mr-1 text-xs"></i>{{ __('Import') }}
                        </button>
                        <a href="{{ route('properties.create') }}" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center">
                            <i class="fas fa-plus mr-1 text-xs"></i>{{ __('Add Property') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Properties Grid -->
    <div class="grid gap-3" style="grid-template-columns: repeat(auto-fill, minmax(min(100%, 280px), 1fr));">
        @forelse($properties as $property)
            @include('properties.partials.property-card', ['property' => $property])
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3">
                        <i class="fas fa-home text-gray-400 text-lg"></i>
                    </div>
                    <h3 class="text-base font-medium text-gray-900 mb-1">{{ __('No Properties Found') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Try adjusting your search or filter criteria') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <style>
        @media (min-width: 640px) {
            .grid { grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr)) !important; }
        }
        @media (min-width: 1024px) {
            .grid { grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr)) !important; }
        }
        @media (min-width: 1280px) {
            .grid { grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr)) !important; }
        }
    </style>

    <!-- Pagination -->
    @if($properties->hasPages())
        <div class="mt-3">
            {{ $properties->links() }}
        </div>
    @endif

    <!-- Import/Export Modals -->
    <!-- ...existing modal code... -->
</x-app-layout>
