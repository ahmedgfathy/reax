<div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-all duration-300">
    <!-- Top Section - Image and Primary Info -->
    <div class="relative h-48">
        <img src="{{ $property->featured_image_url }}" 
             class="w-full h-full object-cover"
             alt="{{ $property->property_name }}"
             loading="lazy"
             onerror="this.src='https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80'">
        
        <!-- Status Tags -->
        <div class="absolute top-4 left-4 flex gap-2">
            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                {{ $property->unit_for === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                {{ __(ucfirst($property->unit_for)) }}
            </span>
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $property->getStatusColor() }}-100 text-{{ $property->getStatusColor() }}-800">
                {{ __(ucfirst($property->status)) }}
            </span>
        </div>

        <!-- Price -->
        <div class="absolute bottom-4 right-4">
            <span class="bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-lg text-sm font-bold text-gray-900">
                {{ number_format($property->total_price) }} {{ $property->currency }}
            </span>
        </div>

        <!-- Action Buttons -->
        <div class="absolute top-4 right-4 flex space-x-2">
            <a href="{{ route('properties.show', $property) }}" 
               class="bg-blue-500/80 hover:bg-blue-600 text-white p-2 rounded-lg transition-all duration-200 backdrop-blur-sm">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('properties.edit', $property) }}" 
               class="bg-yellow-500/80 hover:bg-yellow-600 text-white p-2 rounded-lg transition-all duration-200 backdrop-blur-sm">
                <i class="fas fa-edit"></i>
            </a>
        </div>
    </div>

    <!-- Content Section -->
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $property->property_name ?? 'Property' }}</h3>
        <p class="text-gray-600 mb-4 flex items-center">
            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
            {{ $property->compound_name ?? $property->location ?? __('Location not specified') }}
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
                <span>{{ $property->total_area ?? $property->unit_area ?? 0 }} {{ __('m²') }}</span>
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
