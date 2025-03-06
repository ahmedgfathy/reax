@extends('layouts.public')

@section('title', __('Properties for Sale - REAX'))

@section('content')
<!-- Hero Section -->
<div class="bg-blue-600 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">{{ __('Properties for Sale') }}</h1>
            <p class="text-xl text-white/80">{{ __('Find your dream property from our extensive collection') }}</p>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<section class="py-8 bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <form action="{{ route('properties.sale') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div>
                <input type="text" name="search" placeholder="{{ __('Search properties...') }}" 
                       class="w-full p-3 border rounded-lg" value="{{ request('search') }}">
            </div>

            <!-- Price Range -->
            <div class="flex gap-2">
                <input type="number" name="min_price" placeholder="{{ __('Min Price') }}" 
                       class="w-1/2 p-3 border rounded-lg" value="{{ request('min_price') }}">
                <input type="number" name="max_price" placeholder="{{ __('Max Price') }}" 
                       class="w-1/2 p-3 border rounded-lg" value="{{ request('max_price') }}">
            </div>

            <!-- Property Type -->
            <div>
                <select name="type" class="w-full p-3 border rounded-lg">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                    <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                    <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>{{ __('Office') }}</option>
                </select>
            </div>

            <!-- Sort By -->
            <div class="flex gap-2">
                <select name="sort" class="w-3/4 p-3 border rounded-lg">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                </select>
                <button type="submit" class="w-1/4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Properties Grid -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <!-- Results Count -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">
                {{ __(':count Properties Found', ['count' => $properties->total()]) }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($properties as $property)
                @include('properties.partials.property-card')
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-home text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No properties found') }}</h3>
                    <p class="text-gray-600">{{ __('Try adjusting your search criteria') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    </div>
</section>
@endsection
