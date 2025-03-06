<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('REAX - Real Estate CRM') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <style>
        body {
            font-family: {{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}, sans-serif;
        }
        [dir="rtl"] .reverse-on-rtl {
            flex-direction: row-reverse;
        }
        [dir="rtl"] .space-x-6 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }
        [dir="rtl"] .space-x-4 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }
        [dir="rtl"] .space-x-2 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }
    </style>
</head>
<body class="bg-gray-50" lang="{{ app()->getLocale() }}">
    <!-- Header with transparent background that becomes solid on scroll -->
    <header id="main-header" class="fixed w-full z-10 transition-all duration-300">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="flex items-center">
                <span class="text-2xl font-bold text-white">REAX <span class="text-sm font-normal opacity-80">CRM</span></span>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-6 items-center">
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('Sale') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('Rent') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('Primary') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('About Us') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('Contact Us') }}</a>
                
                <!-- Language Switcher -->
                <form method="POST" action="{{ route('locale.switch') }}">
                    @csrf
                    <select name="locale" onchange="this.form.submit()" class="bg-white/20 backdrop-blur-sm border border-white/30 rounded-md p-2 text-white">
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                        <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>عربي</option>
                    </select>
                    <noscript>
                        <button type="submit" class="ml-2 bg-white text-blue-600 px-2 py-1 rounded-md text-xs">
                            {{ __('Change') }}
                        </button>
                    </noscript>
                </form>
                
                <!-- Conditional Login/Register or Dashboard link based on authentication -->
                @auth
                    <div class="flex space-x-4 items-center">
                        <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md font-medium transition-colors flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-white hover:text-blue-200 flex items-center">
                                <span class="h-8 w-8 bg-white rounded-full flex items-center justify-center text-blue-600 font-medium mr-2">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                {{ Auth::user()->name }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                    {{ __('Profile') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Login/Register Buttons -->
                    <div class="flex space-x-4 items-center">
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-200">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md font-medium transition-colors">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </nav>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden flex items-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden bg-white shadow-lg hidden absolute w-full">
            <div class="container mx-auto px-4 py-3">
                <nav class="flex flex-col space-y-3">
                    <a href="#" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Sale') }}</a>
                    <a href="#" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Rent') }}</a>
                    <a href="#" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Primary') }}</a>
                    <a href="#" class="text-gray-800 hover:text-blue-600 py-2">{{ __('About Us') }}</a>
                    <a href="#" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Contact Us') }}</a>
                    
                    <!-- Add Dashboard link for mobile if authenticated -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-800 hover:text-blue-600 py-2 flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                        </a>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-800">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-800">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex justify-between py-2">
                            <!-- Language Switcher -->
                            <form method="POST" action="{{ route('locale.switch') }}">
                                @csrf
                                <select name="locale" onchange="this.form.submit()" class="border rounded-md p-2 text-gray-800">
                                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                                    <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>عربي</option>
                                </select>
                                <noscript>
                                    <button type="submit" class="ml-2 bg-blue-600 text-white px-2 py-1 rounded-md text-xs">
                                        {{ __('Change') }}
                                    </button>
                                </noscript>
                            </form>
                            
                            <!-- Login/Register -->
                            <div class="flex space-x-4">
                                <a href="{{ route('login') }}" class="text-gray-800 hover:text-blue-600">{{ __('Login') }}</a>
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-3 py-1 rounded-md">{{ __('Register') }}</a>
                            </div>
                        </div>
                    @endauth
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Hero Section with Search -->
    <section class="relative h-[600px] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 to-black/30"></div>
        <div class="container mx-auto px-4 h-full flex flex-col justify-center items-center relative z-1">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white text-center mb-6">{{ __('Find Your Dream Home') }}</h1>
            <p class="text-xl text-white opacity-90 text-center max-w-2xl mb-8">{{ __('Browse thousands of properties for sale and rent across the country') }}</p>
            
            <!-- Property Search Form -->
            <div class="w-full max-w-4xl bg-white rounded-lg shadow-xl p-4 md:p-6">
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <div class="flex-1">
                        <label class="block text-gray-700 text-sm font-medium mb-1">{{ __('Property Type') }}</label>
                        <select class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="apartment">{{ __('Apartment') }}</option>
                            <option value="villa">{{ __('Villa') }}</option>
                            <option value="house">{{ __('House') }}</option>
                            <option value="office">{{ __('Office') }}</option>
                            <option value="land">{{ __('Land') }}</option>
                        </select>
                    </div>
                    
                    <div class="flex-1">
                        <label class="block text-gray-700 text-sm font-medium mb-1">{{ __('Location') }}</label>
                        <select class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('All Locations') }}</option>
                            <option value="cairo">{{ __('Cairo') }}</option>
                            <option value="alexandria">{{ __('Alexandria') }}</option>
                            <option value="giza">{{ __('Giza') }}</option>
                            <option value="sharm">{{ __('Sharm El Sheikh') }}</option>
                        </select>
                    </div>
                    
                    <div class="flex-1">
                        <label class="block text-gray-700 text-sm font-medium mb-1">{{ __('Price Range') }}</label>
                        <select class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('Any Price') }}</option>
                            <option value="0-1000000">{{ __('Up to 1,000,000') }}</option>
                            <option value="1000000-3000000">{{ __('1M to 3M') }}</option>
                            <option value="3000000-5000000">{{ __('3M to 5M') }}</option>
                            <option value="5000000-10000000">{{ __('5M to 10M') }}</option>
                            <option value="10000000+">{{ __('10M+') }}</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-6 py-2 transition-colors w-full md:w-auto">
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Properties Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Featured Properties') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">{{ __('Discover our hand-picked selection of featured properties') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredProperties as $property)
                <!-- Property Card -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <div class="relative">
                        @php
                            $imageUrl = null;
                            // Try to find a featured media file
                            if ($property->mediaFiles && $property->mediaFiles->count() > 0) {
                                $featuredMedia = $property->mediaFiles->where('is_featured', true)->first();
                                if ($featuredMedia) {
                                    $imageUrl = asset('storage/' . $featuredMedia->file_path);
                                } else {
                                    // If no featured image, use the first one
                                    $imageUrl = asset('storage/' . $property->mediaFiles->first()->file_path);
                                }
                            }

                            // If no media files, use a default image based on property type
                            if (!$imageUrl) {
                                $type = strtolower($property->type ?? 'default');
                                if ($type == 'apartment') {
                                    $imageUrl = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                                } elseif ($type == 'villa') {
                                    $imageUrl = 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                                } elseif ($type == 'office') {
                                    $imageUrl = 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                                } else {
                                    // Default fallback
                                    $imageUrl = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                                }
                            }
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $property->name }}" class="w-full h-56 object-cover">
                        
                        <!-- Featured Heart Icon indication -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-500 text-white p-1.5 rounded-full flex items-center justify-center">
                                <i class="fas fa-heart"></i>
                            </span>
                        </div>
                        
                        <div class="absolute top-4 right-4">
                            <span class="bg-{{ $property->unit_for == 'sale' ? 'blue' : 'green' }}-600 text-white px-3 py-1 rounded-full text-sm">
                                {{ $property->unit_for == 'sale' ? __('For Sale') : __('For Rent') }}
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
                                <span>{{ $property->unit_area ?? $property->area_size ?? 0 }} {{ __('m²') }}</span>
                            </div>
                        </div>
                        <!-- View Details Button -->
                        <div class="mt-4">
                            <a href="{{ route('properties.show', $property->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                {{ __('View Details') }}
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Fallback content for when there are no featured properties -->
                <!-- Property Card 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Property" class="w-full h-56 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">{{ __('For Sale') }}</span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <span class="bg-white/80 backdrop-blur-sm text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">$2,500,000</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Modern Villa with Pool') }}</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                            {{ __('Palm Hills, New Cairo') }}
                        </p>
                        <div class="flex justify-between text-gray-600 border-t pt-4">
                            <div class="flex items-center">
                                <i class="fas fa-bed mr-1"></i>
                                <span>4 {{ __('Beds') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-bath mr-1"></i>
                                <span>3 {{ __('Baths') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-ruler-combined mr-1"></i>
                                <span>350 {{ __('m²') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Property Card 2 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Property" class="w-full h-56 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">{{ __('For Rent') }}</span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <span class="bg-white/80 backdrop-blur-sm text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">$1,800/{{ __('month') }}</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Luxury Apartment') }}</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                            {{ __('Zamalek, Cairo') }}
                        </p>
                        <div class="flex justify-between text-gray-600 border-t pt-4">
                            <div class="flex items-center">
                                <i class="fas fa-bed mr-1"></i>
                                <span>3 {{ __('Beds') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-bath mr-1"></i>
                                <span>2 {{ __('Baths') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-ruler-combined mr-1"></i>
                                <span>180 {{ __('m²') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Property Card 3 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Property" class="w-full h-56 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">{{ __('For Sale') }}</span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <span class="bg-white/80 backdrop-blur-sm text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">$850,000</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Modern Office Space') }}</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                            {{ __('Downtown, Alexandria') }}
                        </p>
                        <div class="flex justify-between text-gray-600 border-t pt-4">
                            <div class="flex items-center">
                                <i class="fas fa-door-open mr-1"></i>
                                <span>8 {{ __('Rooms') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-bath mr-1"></i>
                                <span>2 {{ __('Baths') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-ruler-combined mr-1"></i>
                                <span>220 {{ __('m²') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-10">
                <a href="{{ route('featured.properties') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-6 py-3 transition-colors">
                    {{ __('View All Properties') }} <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Why Choose Us') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">{{ __('We provide comprehensive real estate services tailored to your needs') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Wide Range of Properties') }}</h3>
                    <p class="text-gray-600">{{ __('Explore thousands of properties matching your specific requirements and preferences.') }}</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-dollar-sign text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Best Price Guarantee') }}</h3>
                    <p class="text-gray-600">{{ __('We ensure you get the best value for your investment with our price match promise.') }}</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('24/7 Support') }}</h3>
                    <p class="text-gray-600">{{ __('Our dedicated team is available around the clock to address your questions and concerns.') }}</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lock text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Secure Transactions') }}</h3>
                    <p class="text-gray-600">{{ __('Your investments are safe with our secure transaction process and legal protection.') }}</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('What Our Clients Say') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">{{ __('Hear from our satisfied clients about their experience with us') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">{{ __('The team was incredibly helpful throughout the entire buying process. They found us exactly what we were looking for and made the transaction smooth and stress-free.') }}</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">AH</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ __('Ahmed Hassan') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('Property Buyer') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">{{ __('The team was incredibly helpful throughout the entire buying process. They found us exactly what we were looking for and made the transaction smooth and stress-free.') }}</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">AH</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ __('Ahmed Hassan') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('Property Buyer') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">{{ __('The team was incredibly helpful throughout the entire buying process. They found us exactly what we were looking for and made the transaction smooth and stress-free.') }}</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">AH</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ __('Ahmed Hassan') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('Property Buyer') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AlpineJS for dropdowns -->
    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"></script>
    <script>
        // Existing script for mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Header background change on scroll
            const header = document.getElementById('main-header');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('bg-blue-600', 'shadow-md');
                } else {
                    header.classList.remove('bg-blue-600', 'shadow-md');
                    header.classList.add('bg-transparent');
                }
            });

            // Trigger scroll event on page load
            window.dispatchEvent(new Event('scroll'));
        });
    </script>
</body>
</html>
