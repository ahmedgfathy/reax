<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $property->property_name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('properties.edit', $property) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i>{{ __('Edit') }}
                </a>
                <form action="{{ route('properties.destroy', $property) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700"
                            onclick="return confirm('{{ __('Are you sure you want to delete this property?') }}')">
                        <i class="fas fa-trash mr-2"></i>{{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Property Media Gallery -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Property Gallery') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @forelse($property->media as $media)
                        <div class="relative group cursor-pointer overflow-hidden rounded-lg" onclick="openGallery({{ $loop->index }})">
                            <img src="{{ $media->file_path }}" 
                                 alt="{{ $property->property_name }}"
                                 class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="fas fa-search text-white text-2xl"></i>
                            </div>
                            @if($media->is_featured)
                                <div class="absolute top-2 right-2">
                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                        {{ __('Featured') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-8 text-gray-500">
                            {{ __('No images available') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Property Overview Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Base Details -->
                    <div class="col-span-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800">{{ $property->property_name }}</h3>
                                <p class="text-gray-600 mt-1 flex items-center">
                                    <i class="fas fa-tag mr-2 text-blue-600"></i>
                                    {{ __('Property #') }}{{ $property->property_number }}
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $property->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ __(ucfirst($property->status)) }}
                            </span>
                        </div>

                        <!-- Location Details -->
                        <div class="mt-6 space-y-3">
                            @if($property->compound_name)
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-building mr-2 text-blue-600 w-5"></i>
                                {{ $property->compound_name }}
                            </p>
                            @endif
                            <p class="text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ __(ucfirst($property->location_type)) }} Location
                                @if($property->building)
                                    • {{ __('Building') }} {{ $property->building }}
                                @endif
                                @if($property->floor)
                                    • {{ __('Floor') }} {{ $property->floor }}
                                @endif
                                @if($property->unit_no)
                                    • {{ __('Unit') }} {{ $property->unit_no }}
                                @endif
                            </p>
                            @if($property->phase)
                            <p class="text-gray-600">
                                <i class="fas fa-project-diagram mr-2"></i>{{ __('Phase') }} {{ $property->phase }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <!-- Price Information -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-1">{{ __('Price') }}</p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ number_format($property->total_price) }}
                                <span class="text-sm font-normal text-gray-600">{{ $property->currency }}</span>
                            </p>
                            @if($property->price_per_meter)
                            <p class="text-sm text-gray-600 mt-1">
                                {{ number_format($property->price_per_meter) }} {{ $property->currency }}/m²
                            </p>
                            @endif
                            <div class="mt-3 pt-3 border-t">
                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                                    {{ __('For') }} {{ __(ucfirst($property->unit_for)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Property Specifications -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Property Specifications') }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Type') }}</p>
                                <p class="font-medium">{{ __(ucfirst($property->type)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Category') }}</p>
                                <p class="font-medium">{{ __(ucfirst($property->category)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Finishing Status') }}</p>
                                <p class="font-medium">{{ $property->finished ? __('Finished') : __('Unfinished') }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Rooms') }}</p>
                                <p class="font-medium">{{ $property->rooms ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Bathrooms') }}</p>
                                <p class="font-medium">{{ $property->bathrooms ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Details -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Area Details') }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @if($property->total_area)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Total Area') }}</p>
                            <p class="font-medium">{{ number_format($property->total_area) }} m²</p>
                        </div>
                        @endif
                        @if($property->unit_area)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Unit Area') }}</p>
                            <p class="font-medium">{{ number_format($property->unit_area) }} m²</p>
                        </div>
                        @endif
                        @if($property->land_area)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Land Area') }}</p>
                            <p class="font-medium">{{ number_format($property->land_area) }} m²</p>
                        </div>
                        @endif
                        @if($property->garden_area)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Garden Area') }}</p>
                            <p class="font-medium">{{ number_format($property->garden_area) }} m²</p>
                        </div>
                        @endif
                        @if($property->space_earth)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Space Earth') }}</p>
                            <p class="font-medium">{{ number_format($property->space_earth) }} m²</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Owner/Seller Information -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Owner Information') }}</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Property Offered By') }}</p>
                            <p class="font-medium">{{ __(ucfirst($property->property_offered_by)) }}</p>
                        </div>
                        @if($property->owner_name)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Owner Name') }}</p>
                            <p class="font-medium">{{ $property->owner_name }}</p>
                        </div>
                        @endif
                        <div class="grid grid-cols-2 gap-4">
                            @if($property->owner_mobile)
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Mobile') }}</p>
                                <p class="font-medium">{{ $property->owner_mobile }}</p>
                            </div>
                            @endif
                            @if($property->owner_tel)
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Telephone') }}</p>
                                <p class="font-medium">{{ $property->owner_tel }}</p>
                            </div>
                            @endif
                        </div>
                        @if($property->contact_status)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Contact Status') }}</p>
                            <span class="px-2 py-1 text-sm rounded-full 
                                {{ $property->contact_status === 'contacted' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $property->contact_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $property->contact_status === 'no_answer' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ __(ucfirst($property->contact_status)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sales Information -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Sales Information') }}</h3>
                    <div class="space-y-4">
                        @if($property->handler_id)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Handler') }}</p>
                            <p class="font-medium">{{ $property->handler->name }}</p>
                        </div>
                        @endif
                        @if($property->sales_person_id)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Sales Person') }}</p>
                            <p class="font-medium">{{ $property->salesPerson->name }}</p>
                        </div>
                        @endif
                        @if($property->sales_category)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Sales Category') }}</p>
                            <p class="font-medium">{{ $property->sales_category }}</p>
                        </div>
                        @endif
                        @if($property->last_follow_up)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Last Follow-up') }}</p>
                            <p class="font-medium">{{ $property->last_follow_up->format('Y-m-d H:i') }}</p>
                        </div>
                        @endif
                    </div>
                    @if($property->sales_notes)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-2">{{ __('Sales Notes') }}</p>
                        <p class="text-gray-700">{{ $property->sales_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Property Features -->
        @if($property->features || $property->amenities)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Features & Amenities') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($property->features)
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2">{{ __('Features') }}</h4>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    @foreach(json_decode($property->features) as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($property->amenities)
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2">{{ __('Amenities') }}</h4>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    @foreach(json_decode($property->amenities) as $amenity)
                                        <li>{{ $amenity }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Description -->
        @if($property->description)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Description') }}</h3>
                    <div class="prose max-w-none">
                        {!! nl2br(e($property->description)) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Image Gallery Modal -->
    <div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50">
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <button onclick="closeGallery()" class="absolute top-4 right-4 text-white text-xl">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="relative w-full max-w-4xl">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($property->media as $media)
                            <div class="swiper-slide">
                                <img src="{{ $media->file_path }}" 
                                     alt="{{ $property->property_name }}"
                                     class="max-h-[80vh] mx-auto">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            let swiper;
            
            function openGallery(index) {
                document.getElementById('galleryModal').classList.remove('hidden');
                if (!swiper) {
                    swiper = new Swiper('.swiper-container', {
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    });
                }
                swiper.slideTo(index);
            }
            
            function closeGallery() {
                document.getElementById('galleryModal').classList.add('hidden');
            }
        </script>
    @endpush
</x-app-layout>
