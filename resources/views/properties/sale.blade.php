<x-app-layout>
    <x-slot name="title">{{ __('Properties for Sale') }} - {{ config('app.name') }}</x-slot>
    
    <!-- Hero Section -->
    <section class="relative h-[560px] bg-gray-900"> <!-- Increased height to match home -->
        <div class="absolute inset-0 bg-black/60"> <!-- Added darker overlay -->
            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750" 
                 class="w-full h-full object-cover"
                 alt="Properties for Sale">
        </div>
        <div class="relative container mx-auto px-4 h-full flex items-center">
            <div class="text-white max-w-2xl">
                <h1 class="text-5xl font-bold mb-4">{{ __('Properties for Sale') }}</h1>
                <p class="text-xl">{{ __('Find your dream property from our exclusive collection of properties for sale') }}</p>
            </div>
        </div>
    </section>

    <!-- Properties Grid -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <!-- Properties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($properties as $property)
                    @include('properties.partials.property-card', ['property' => $property])
                @empty
                    <div class="col-span-4 text-center py-12">
                        <h3 class="text-xl text-gray-600">{{ __('No properties found') }}</h3>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $properties->links() }}
            </div>
        </div>
    </section>
</x-app-layout>
