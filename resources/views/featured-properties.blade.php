@extends('layouts.app')

@section('title', __('Featured Properties - REAX'))

@section('content')
<div class="bg-blue-600 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">{{ __('Featured Properties') }}</h1>
            <p class="text-xl text-white/80">{{ __('Our hand-picked selection of premium properties') }}</p>
        </div>
    </div>
</div>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredProperties as $property)
                @include('properties.partials.property-card')
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-home text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No featured properties found') }}</h3>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $featuredProperties->links() }}
        </div>
    </div>
</section>
@endsection
