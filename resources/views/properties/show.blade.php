<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">{{ $property->property_name }}</h2>
            <div class="space-x-2">
                <a href="{{ route('properties.edit', $property) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i>{{ __('Edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 space-y-6">
        <!-- Media Gallery -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Property Gallery') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($property->media as $media)
                    <div class="relative group cursor-pointer">
                        <img src="{{ $media->file_path }}" 
                             alt="{{ $property->property_name }}"
                             class="w-full h-48 object-cover rounded"
                             onclick="openGallery({{ $loop->index }})">
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        {{ __('No images available') }}
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Basic Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Property Name') }}</p>
                        <p class="font-medium">{{ $property->property_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Property Number') }}</p>
                        <p class="font-medium">{{ $property->property_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                        <p class="font-medium">{{ __(ucfirst($property->status)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Compound Name') }}</p>
                        <p class="font-medium">{{ $property->compound_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Location Type') }}</p>
                        <p class="font-medium">{{ __(ucfirst($property->location_type)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Building') }}</p>
                        <p class="font-medium">{{ $property->building }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Floor') }}</p>
                        <p class="font-medium">{{ $property->floor }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Unit No') }}</p>
                        <p class="font-medium">{{ $property->unit_no }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Phase') }}</p>
                        <p class="font-medium">{{ $property->phase }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Areas and Specifications -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Areas and Specifications') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Total Area') }}</p>
                        <p class="font-medium">{{ number_format($property->total_area) }} m²</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Unit Area') }}</p>
                        <p class="font-medium">{{ number_format($property->unit_area) }} m²</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Land Area') }}</p>
                        <p class="font-medium">{{ number_format($property->land_area) }} m²</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Garden Area') }}</p>
                        <p class="font-medium">{{ number_format($property->garden_area) }} m²</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Space Earth') }}</p>
                        <p class="font-medium">{{ number_format($property->space_earth) }} m²</p>
                    </div>
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

        <!-- Pricing Information -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Pricing Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Total Price') }}</p>
                        <p class="font-medium">{{ number_format($property->total_price) }} {{ $property->currency }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Price per Meter') }}</p>
                        <p class="font-medium">{{ number_format($property->price_per_meter) }} {{ $property->currency }}/m²</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('For') }}</p>
                        <p class="font-medium">{{ __(ucfirst($property->unit_for)) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Owner Information -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Owner Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Property Offered By') }}</p>
                        <p class="font-medium">{{ __(ucfirst($property->property_offered_by)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Owner Name') }}</p>
                        <p class="font-medium">{{ $property->owner_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Mobile') }}</p>
                        <p class="font-medium">{{ $property->owner_mobile }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Telephone') }}</p>
                        <p class="font-medium">{{ $property->owner_tel }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Contact Status') }}</p>
                        <p class="font-medium">{{ __(ucfirst($property->contact_status)) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Information -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Sales Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Handler') }}</p>
                        <p class="font-medium">{{ $property->handler ? $property->handler->name : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Sales Person') }}</p>
                        <p class="font-medium">{{ $property->salesPerson ? $property->salesPerson->name : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Sales Category') }}</p>
                        <p class="font-medium">{{ $property->sales_category ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Last Follow-up') }}</p>
                        <p class="font-medium">{{ $property->last_follow_up ? $property->last_follow_up->format('Y-m-d H:i') : 'N/A' }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-500 mb-2">{{ __('Sales Notes') }}</p>
                    <p class="text-gray-700">{{ $property->sales_notes }}</p>
                </div>
            </div>
        </div>

        <!-- Features and Description -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Features and Description') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($property->features)
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">{{ __('Features') }}</h4>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            @foreach(json_decode($property->features) ?? [] as $feature)
                                <li>{{ __($feature) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($property->amenities)
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">{{ __('Amenities') }}</h4>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            @foreach(json_decode($property->amenities) ?? [] as $amenity)
                                <li>{{ __($amenity) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                @if($property->description)
                <div class="mt-4 pt-4 border-t">
                    <h4 class="font-medium text-gray-700 mb-2">{{ __('Description') }}</h4>
                    <div class="prose max-w-none">
                        {!! nl2br(e($property->description)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>

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
