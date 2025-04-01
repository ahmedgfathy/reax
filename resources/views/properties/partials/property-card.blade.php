<div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
    <div class="relative">
        <img src="{{ $property->image_url }}" 
             alt="{{ $property->property_name }}" 
             class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
        
        <!-- Badges -->
        <div class="absolute top-4 left-4 flex flex-col gap-2">
            @if($property->has_installments)
                <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                    {{ __('Installment') }}
                </span>
            @endif
            @if($property->is_featured)
                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                    {{ __('Featured') }}
                </span>
            @endif
        </div>
        
        <!-- Price Badge -->
        <div class="absolute bottom-4 right-4">
            <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-3 py-1.5 rounded-full text-sm font-semibold">
                {{ number_format($property->total_price) }} {{ $property->currency }}
            </span>
        </div>
    </div>

    <div class="p-4">
        <!-- Content -->
        <div class="mb-3">
            <h3 class="font-semibold text-gray-900 mb-1">{{ $property->property_name }}</h3>
            <p class="text-sm text-gray-600 flex items-center">
                <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>
                {{ $property->location ?? $property->area ?? __('Location not specified') }}
            </p>
        </div>

        <!-- Details -->
        <div class="grid grid-cols-3 gap-2 mb-4 text-xs text-gray-600">
            <div class="flex items-center">
                <i class="fas fa-bed mr-1.5 text-blue-500"></i>
                <span>{{ $property->rooms }} {{ __('Beds') }}</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-bath mr-1.5 text-blue-500"></i>
                <span>{{ $property->bathrooms }} {{ __('Baths') }}</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-ruler-combined mr-1.5 text-blue-500"></i>
                <span>{{ $property->total_area }}m²</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div class="text-sm">
                @if($property->has_installments)
                    <span class="text-gray-500">{{ __('Starting from') }}</span>
                    <span class="font-semibold text-blue-600 ml-1">
                        {{ number_format($property->monthly_installment) }}/{{ __('mo') }}
                    </span>
                @endif
            </div>
            <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                {{ __('Details') }}
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</div>
