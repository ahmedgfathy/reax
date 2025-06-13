@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-gray-100">
    @include('layouts.sidebar')
    
    <div class="flex-1 md:ml-64">
        <div class="p-6">
            @if(isset($header))
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $header }}</h1>
                    @if(isset($breadcrumbs))
                        <!-- Breadcrumbs -->
                        <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                            {{ $breadcrumbs }}
                        </nav>
                    @endif
                </div>
            @endif
            
            <!-- Main Content -->
            {{ $slot }}
        </div>
    </div>
</div>
@endsection

@if(isset($scripts))
    @push('scripts')
        {{ $scripts }}
    @endpush
@endif

@if(isset($styles))
    @push('styles')
        {{ $styles }}
    @endpush
@endif
