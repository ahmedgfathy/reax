<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $property->title }} | {{ __('Property Details') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center mb-2 sm:mb-0">
                    <a href="{{ route('properties.index') }}" class="text-gray-600 hover:text-blue-600 mr-2">
                        <i class="fas fa-chevron-left"></i> {{ __('Properties') }}
                    </a>
                    <span class="text-gray-600 mx-2">/</span>
                    <h1 class="text-xl font-bold text-gray-800">{{ $property->title }}</h1>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('properties.edit', $property->id) }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-edit mr-2"></i> {{ __('Edit') }}
                    </a>
                    
                    <form action="{{ route('properties.destroy', $property->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('{{ __('Are you sure you want to delete this property?') }}')" 
                                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                            <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Property Images -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="relative h-64 sm:h-96">
                        @if($property->featured_image)
                            <img src="{{ $property->featured_image }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-building text-gray-400 text-6xl"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-0 right-0 mt-4 mr-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium 
                                {{ $property->status == 'for_sale' ? 'bg-green-100 text-green-800' : 
                                  ($property->status == 'for_rent' ? 'bg-blue-100 text-blue-800' : 
                                  ($property->status == 'sold' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                {{ __($property->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Image Gallery Thumbnails (if available) -->
                    <div class="px-4 py-3 bg-gray-50 flex space-x-2 overflow-x-auto">
                        @if($property->images && count($property->images) > 0)
                            @foreach($property->images as $image)
                                <div class="h-16 w-16 rounded-md overflow-hidden flex-shrink-0 border-2 border-transparent hover:border-blue-500 cursor-pointer">
                                    <img src="{{ $image }}" alt="{{ $property->title }}" class="h-full w-full object-cover">
                                </div>
                            @endforeach
                        @else
                            <span class="text-sm text-gray-500">{{ __('No additional images available') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Property Details -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Property Details') }}</h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-gray-50 px-4 py-3 rounded-lg text-center">
                                <span class="block text-sm text-gray-500">{{ __('Price') }}</span>
                                <span class="block text-lg font-semibold text-blue-600">{{ number_format($property->price) }} {{ $property->currency }}</span>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 rounded-lg text-center">
                                <span class="block text-sm text-gray-500">{{ __('Type') }}</span>
                                <span class="block text-lg font-semibold">{{ __($property->type) }}</span>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 rounded-lg text-center">
                                <span class="block text-sm text-gray-500">{{ __('Area') }}</span>
                                <span class="block text-lg font-semibold">{{ $property->area }} m²</span>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 rounded-lg text-center">
                                <span class="block text-sm text-gray-500">{{ __('Reference') }}</span>
                                <span class="block text-lg font-semibold">{{ $property->reference_id }}</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="flex flex-col items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-bed text-gray-500 text-xl"></i>
                                <span class="mt-1 text-sm text-gray-500">{{ __('Bedrooms') }}</span>
                                <span class="font-semibold">{{ $property->bedrooms }}</span>
                            </div>
                            <div class="flex flex-col items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-bath text-gray-500 text-xl"></i>
                                <span class="mt-1 text-sm text-gray-500">{{ __('Bathrooms') }}</span>
                                <span class="font-semibold">{{ $property->bathrooms }}</span>
                            </div>
                            <div class="flex flex-col items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-calendar-alt text-gray-500 text-xl"></i>
                                <span class="mt-1 text-sm text-gray-500">{{ __('Year Built') }}</span>
                                <span class="font-semibold">{{ $property->year_built ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div class="prose max-w-none">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Description') }}</h3>
                            <div class="text-gray-700">
                                {!! $property->description !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Features -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Features & Amenities') }}</h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($property->features ?? [] as $feature)
                                <div class="flex items-center">
                                    <i class="fas fa-check text-blue-500 mr-2"></i>
                                    <span>{{ __($feature) }}</span>
                                </div>
                            @endforeach
                            
                            @if(empty($property->features) || count($property->features) === 0)
                                <div class="col-span-full text-gray-500">{{ __('No features specified') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Location Map -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Location') }}</h2>
                        
                        <div class="h-64 bg-gray-200 rounded-lg">
                            <!-- Map would be inserted here -->
                            <div class="h-full flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-gray-400 text-4xl mr-2"></i>
                                <span class="text-gray-500">{{ __('Map location for') }} {{ $property->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Agent Information -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Listed By') }}</h2>
                        
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-lg">
                                {{ substr($property->agent_name ?? 'Agent', 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <h3 class="text-md font-medium text-gray-900">{{ $property->agent_name ?? 'Agent Name' }}</h3>
                                <p class="text-sm text-gray-500">{{ $property->agent_phone ?? '+1 234 567 8900' }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex space-x-2">
                            <a href="tel:{{ $property->agent_phone ?? '+12345678900' }}" class="flex-1 justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none flex">
                                <i class="fas fa-phone-alt mr-2"></i>
                                {{ __('Call') }}
                            </a>
                            <a href="mailto:{{ $property->agent_email ?? 'agent@example.com' }}" class="flex-1 justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none flex">
                                <i class="fas fa-envelope mr-2 text-gray-500"></i>
                                {{ __('Email') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Property Details -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Property Details') }}</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">{{ __('ID') }}</span>
                                <span class="font-medium">{{ $property->id }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">{{ __('Reference') }}</span>
                                <span class="font-medium">{{ $property->reference_id ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">{{ __('Status') }}</span>
                                <span class="font-medium">{{ __($property->status) }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">{{ __('Type') }}</span>
                                <span class="font-medium">{{ __($property->type) }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">{{ __('Listed On') }}</span>
                                <span class="font-medium">{{ $property->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Last Updated') }}</span>
                                <span class="font-medium">{{ $property->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Similar Properties -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Similar Properties') }}</h2>
                        
                        <div class="space-y-4">
                            @foreach($similarProperties ?? [] as $similarProperty)
                            <div class="flex border-b pb-3 last:border-b-0 last:pb-0">
                                <div class="h-16 w-16 rounded-md overflow-hidden flex-shrink-0">
                                    @if($similarProperty->featured_image)
                                        <img src="{{ $similarProperty->featured_image }}" alt="{{ $similarProperty->title }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-building text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ $similarProperty->title }}</h3>
                                    <p class="text-xs text-gray-500">{{ $similarProperty->location }}</p>
                                    <p class="text-sm font-medium text-blue-600">{{ number_format($similarProperty->price) }} {{ $similarProperty->currency }}</p>
                                </div>
                            </div>
                            @endforeach
                            
                            @if(empty($similarProperties) || count($similarProperties) === 0)
                                <div class="text-gray-500">{{ __('No similar properties found') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
