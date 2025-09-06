<div class="group bg-white/70 backdrop-blur-lg rounded-2xl border border-white/30 shadow-xl overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-300">
    <!-- Property Image -->
    <div class="relative h-56 overflow-hidden">
        <img src="{{ $property->featured_image_url }}" 
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
             alt="{{ $property->property_name }}"
             loading="lazy"
             onerror="this.src='https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80'">
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        
        <!-- Status Badges -->
        <div class="absolute top-4 left-4 flex gap-2">
            <span class="px-3 py-1 text-xs font-bold rounded-full backdrop-blur-sm
                {{ $property->unit_for === 'sale' ? 'bg-blue-500/80 text-white' : 'bg-purple-500/80 text-white' }}">
                {{ __(ucfirst($property->unit_for)) }}
            </span>
            <span class="px-3 py-1 text-xs font-bold rounded-full backdrop-blur-sm bg-green-500/80 text-white">
                {{ __(ucfirst($property->status)) }}
            </span>
        </div>

        <!-- Price Badge -->
        <div class="absolute bottom-4 left-4">
            <span class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-xl text-lg font-bold text-gray-900 shadow-lg">
                {{ number_format((float)($property->total_price ?? 0)) }} {{ $property->currency ?? 'EGP' }}
            </span>
        </div>

        <!-- Action Buttons -->
        <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <a href="{{ route('properties.show', $property) }}" 
               class="bg-blue-500/80 hover:bg-blue-600 text-white p-3 rounded-xl transition-all duration-200 backdrop-blur-sm shadow-lg">
                <span class="text-lg">👁️</span>
            </a>
            <a href="{{ route('properties.edit', $property) }}" 
               class="bg-yellow-500/80 hover:bg-yellow-600 text-white p-3 rounded-xl transition-all duration-200 backdrop-blur-sm shadow-lg">
                <span class="text-lg">✏️</span>
            </a>
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
