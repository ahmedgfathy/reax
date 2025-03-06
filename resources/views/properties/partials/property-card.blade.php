<div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
    <div class="relative">
        <img src="{{ $property->getImageUrlAttribute() }}" alt="{{ $property->name }}" 
             class="w-full h-56 object-cover">
        <div class="absolute top-4 right-4">
            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">
                {{ __('For Sale') }}
            </span>
        </div>
        <div class="absolute bottom-4 right-4">
            <span class="bg-white/80 backdrop-blur-sm text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">
                {{ number_format($property->price) }} {{ $property->currency ?? 'USD' }}
            </span>
        </div>
    </div>
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $property->name }}</h3>
        <p class="text-gray-600 mb-4 flex items-center">
            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
            {{ $property->area ?? $property->location ?? __('Location not specified') }}
        </p>
        <div class="flex justify-between text-gray-600 border-t pt-4">
            <div class="flex items-center">
                <i class="fas fa-bed mr-1"></i>
                <span>{{ $property->rooms ?? $property->bedrooms ?? 0 }} {{ __('Beds') }}</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-bath mr-1"></i>
                <span>{{ $property->bathrooms ?? 0 }} {{ __('Baths') }}</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-ruler-combined mr-1"></i>
                <span>{{ $property->unit_area ?? 0 }} {{ __('m²') }}</span>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('properties.show', $property->id) }}" 
               class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                {{ __('View Details') }}
            </a>
        </div>
    </div>
</div>
