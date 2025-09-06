@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="glass-card rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ __('Properties') }}
                        </h1>
                        <p class="text-gray-600">{{ __('Manage all properties in the system') }}</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('properties.create') }}" class="inline-flex items-center px-6 py-3 bg-accent-600 text-white font-semibold rounded-lg shadow-lg hover:bg-accent-700 hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>
                            {{ __('Add New Property') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Properties -->
            <div class="stat-card stat-blue">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Total Properties') }}</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- For Sale -->
            <div class="group backdrop-blur-xl bg-white/85 rounded-3xl p-8 hover:bg-white/90 hover:shadow-2xl hover:scale-105 transition-all duration-500 border border-white/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 via-green-600 to-emerald-700 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <span class="text-3xl">💰</span>
                        </div>
                    </div>
                    <div class="ml-6 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('For Sale') }}</dt>
                            <dd class="text-4xl font-black text-gray-900 mt-1">{{ $stats['for_sale'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            <!-- For Rent -->
            <div class="group backdrop-blur-xl bg-white/85 rounded-3xl p-8 hover:bg-white/90 hover:shadow-2xl hover:scale-105 transition-all duration-500 border border-white/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 via-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <span class="text-3xl">🔑</span>
                        </div>
                    </div>
                    <div class="ml-6 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('For Rent') }}</dt>
                            <dd class="text-4xl font-black text-gray-900 mt-1">{{ $stats['for_rent'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            <!-- Sold -->
            <div class="group backdrop-blur-xl bg-white/85 rounded-3xl p-8 hover:bg-white/90 hover:shadow-2xl hover:scale-105 transition-all duration-500 border border-white/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <span class="text-3xl">🎉</span>
                        </div>
                    </div>
                    <div class="ml-6 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('Sold') }}</dt>
                            <dd class="text-4xl font-black text-gray-900 mt-1">{{ $stats['sold'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-8">
            <div class="glass-effect rounded-2xl p-6">
                <form method="GET" action="{{ route('properties.index') }}" class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Filters') }}</h3>
                        <button type="button" class="text-gray-500 hover:text-gray-700">
                            <span class="text-2xl">🔍</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Search') }}</label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('Property name or number...') }}"
                                   class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Property Type') }}</label>
                            <select id="type" 
                                    name="type" 
                                    class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="apartment" {{ request('type') === 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                                <option value="villa" {{ request('type') === 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                                <option value="duplex" {{ request('type') === 'duplex' ? 'selected' : '' }}>{{ __('Duplex') }}</option>
                                <option value="studio" {{ request('type') === 'studio' ? 'selected' : '' }}>{{ __('Studio') }}</option>
                                <option value="townhouse" {{ request('type') === 'townhouse' ? 'selected' : '' }}>{{ __('Townhouse') }}</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                            <select id="status" 
                                    name="status" 
                                    class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>{{ __('Available') }}</option>
                                <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>{{ __('Reserved') }}</option>
                                <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>{{ __('Sold') }}</option>
                                <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>{{ __('Rented') }}</option>
                                <option value="under_contract" {{ request('status') === 'under_contract' ? 'selected' : '' }}>{{ __('Under Contract') }}</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Price Range') }}</label>
                            <select id="price_range" 
                                    name="price_range" 
                                    class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">{{ __('Any Price') }}</option>
                                <option value="0-500000" {{ request('price_range') === '0-500000' ? 'selected' : '' }}>{{ __('Under 500K') }}</option>
                                <option value="500000-1000000" {{ request('price_range') === '500000-1000000' ? 'selected' : '' }}>{{ __('500K - 1M') }}</option>
                                <option value="1000000-2000000" {{ request('price_range') === '1000000-2000000' ? 'selected' : '' }}>{{ __('1M - 2M') }}</option>
                                <option value="2000000-5000000" {{ request('price_range') === '2000000-5000000' ? 'selected' : '' }}>{{ __('2M - 5M') }}</option>
                                <option value="5000000-" {{ request('price_range') === '5000000-' ? 'selected' : '' }}>{{ __('Above 5M') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                            <span class="mr-2">🔍</span>{{ __('Apply Filters') }}
                        </button>
                        
                        @if(request()->hasAny(['search', 'type', 'status', 'price_range']))
                            <a href="{{ route('properties.index') }}" class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                <span class="mr-2">✖️</span>{{ __('Clear Filters') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @forelse($properties as $property)
                <div class="group glass-card rounded-2xl overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <!-- Property Image -->
                    <div class="relative h-56 overflow-hidden bg-gray-100">
                        @if($property->featured_image_url ?? false)
                            <img src="{{ $property->featured_image_url }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 alt="{{ $property->property_name ?? 'Property' }}"
                                 loading="lazy"
                                 onerror="this.src='https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80'">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                <span class="text-4xl">🏠</span>
                            </div>
                        @endif
                        
                        <!-- Status Badges -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-500 text-white">
                                {{ __(ucfirst($property->unit_for ?? 'sale')) }}
                            </span>
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-500 text-white">
                                {{ __(ucfirst($property->status ?? 'available')) }}
                            </span>
                        </div>

                        <!-- Price Badge -->
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-white px-4 py-2 rounded-xl text-lg font-bold text-gray-900 shadow-lg">
                                {{ number_format((float)($property->total_price ?? 0)) }} {{ $property->currency ?? 'EGP' }}
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            @if(Route::has('properties.show'))
                                <a href="{{ route('properties.show', $property) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-xl transition-all duration-200 shadow-lg">
                                    <span class="text-lg">👁️</span>
                                </a>
                            @endif
                            @if(Route::has('properties.edit'))
                                <a href="{{ route('properties.edit', $property) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white p-3 rounded-xl transition-all duration-200 shadow-lg">
                                    <span class="text-lg">✏️</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="p-6">
                        <!-- Property Name -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1">
                            {{ $property->property_name ?? 'Property' }}
                        </h3>
                        
                        <!-- Location -->
                        <p class="text-gray-600 mb-4 flex items-center">
                            <span class="mr-2 text-lg">📍</span>
                            <span class="line-clamp-1">{{ $property->compound_name ?? $property->location ?? __('Location not specified') }}</span>
                        </p>
                        
                        <!-- Property Features -->
                        <div class="grid grid-cols-3 gap-4 py-4 border-t border-gray-200">
                            <div class="text-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl mx-auto mb-2 flex items-center justify-center">
                                    <span class="text-lg">🛏️</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $property->rooms ?? $property->bedrooms ?? 0 }}</span>
                                <p class="text-xs text-gray-500">{{ __('Beds') }}</p>
                            </div>
                            <div class="text-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl mx-auto mb-2 flex items-center justify-center">
                                    <span class="text-lg">🚿</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $property->bathrooms ?? 0 }}</span>
                                <p class="text-xs text-gray-500">{{ __('Baths') }}</p>
                            </div>
                            <div class="text-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl mx-auto mb-2 flex items-center justify-center">
                                    <span class="text-lg">📐</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $property->total_area ?? $property->unit_area ?? 0 }}</span>
                                <p class="text-xs text-gray-500">{{ __('m²') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="glass-effect rounded-2xl p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-gray-400 to-gray-500 mb-6">
                            <span class="text-3xl">🏠</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('No Properties Found') }}</h3>
                        <p class="text-gray-600 text-lg mb-6">{{ __('Try adjusting your search or filter criteria') }}</p>
                        @if(Route::has('properties.create'))
                            <a href="{{ route('properties.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                <span class="mr-2">➕</span>{{ __('Add Your First Property') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="glass-effect rounded-2xl p-6">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// Simple JavaScript functions
function exportProperties() {
    alert('{{ __("Export functionality coming soon!") }}');
}

function importProperties() {
    alert('{{ __("Import functionality coming soon!") }}');
}
</script>
@endsection
