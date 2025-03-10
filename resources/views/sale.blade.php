<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('REAX - Properties for Sale') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Noto+Kufi+Arabic:wght@300;400;500;700&family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <style>
        :root {
            --font-arabic: 'Noto Kufi Arabic', 'Cairo', sans-serif;
            --font-english: 'Roboto', sans-serif;
        }
        
        body {
            font-family: {{ app()->getLocale() == 'ar' ? 'var(--font-arabic)' : 'var(--font-english)' }} !important;
        }
        
        [lang="ar"] {
            font-family: var(--font-arabic) !important;
        }
        
        [lang="en"] {
            font-family: var(--font-english) !important;
        }
        [dir="rtl"] .reverse-on-rtl {
            flex-direction: row-reverse;
        }
        [dir="rtl"] .space-x-6 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1 !important;
        }
        [dir="rtl"] .space-x-4 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1 !important;
        }
        [dir="rtl"] .space-x-2 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1 !important;
        }
        
        /* RTL Support */
        html[dir="rtl"] {
            text-align: right;
        }
        
        html[dir="rtl"] .space-x-4 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }
        
        html[dir="rtl"] .space-x-6 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        html[dir="rtl"] .ml-2 {
            margin-left: 0;
            margin-right: 0.5rem;
        }

        html[dir="rtl"] .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }
        
        html[dir="rtl"] .left-4 {
            left: auto;
            right: 1rem;
        }
        
        html[dir="rtl"] .right-4 {
            right: auto;
            left: 1rem;
        }
        
        [dir="rtl"] .me-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }
        
        [dir="rtl"] .ms-2 {
            margin-right: 0.5rem !important;
            margin-left: 0 !important;
        }
    </style>
</head>
<body class="bg-gray-50" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">
    <!-- Header with solid background -->
    <header id="main-header" class="w-full z-50 bg-blue-600 shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="flex items-center">
                <span class="text-2xl font-bold text-white">REAX <span class="text-sm font-normal opacity-80">CRM</span></span>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-6 items-center">
                <a href="/" class="text-white hover:text-blue-200 font-medium">{{ __('Home') }}</a>
                <a href="{{ route('sale') }}" class="text-white hover:text-blue-200 font-medium">{{ __('Sale') }}</a>
                <a href="{{ route('rent') }}" class="text-white hover:text-blue-200 font-medium">{{ __('Rent') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('Primary') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('About Us') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('Contact Us') }}</a>
                
                <!-- Replace Language Dropdown with Icon Button -->
                <form method="POST" action="{{ route('locale.switch') }}" class="inline-flex items-center">
                    @csrf
                    <input type="hidden" name="locale" value="{{ app()->getLocale() == 'en' ? 'ar' : 'en' }}">
                    <button type="submit" class="text-white hover:text-blue-200 flex items-center">
                        <i class="fas fa-globe mr-2"></i>
                        <span>{{ app()->getLocale() == 'en' ? 'عربي' : 'ENG' }}</span>
                    </button>
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
                    <a href="/" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Home') }}</a>
                    <a href="{{ route('sale') }}" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Sale') }}</a>
                    <a href="{{ route('rent') }}" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Rent') }}</a>
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
                        <div class="flex justify-between py-2 border-t border-gray-200">
                            <!-- Replace Language Dropdown with Icon Button in Mobile Menu -->
                            <form method="POST" action="{{ route('locale.switch') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="locale" value="{{ app()->getLocale() == 'en' ? 'ar' : 'en' }}">
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:text-blue-600 flex items-center">
                                    <i class="fas fa-globe mr-2"></i>
                                    <span>{{ app()->getLocale() == 'en' ? 'عربي' : 'ENG' }}</span>
                                </button>
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

    <!-- Hero Section -->
    <section class="relative py-24 bg-blue-600">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Properties for Sale') }}</h1>
                <p class="text-xl opacity-90 max-w-lg mx-auto">{{ __('Find your dream property with our exclusive listings') }}</p>
            </div>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-16 bg-white" style="clip-path: polygon(0 100%, 100% 100%, 100% 0);"></div>
    </section>

    <!-- Search and Filter Section -->
    <section class="py-8 bg-white shadow-sm">
        <div class="container mx-auto px-4">
            <form action="{{ route('sale') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div>
                    <input type="text" name="search" placeholder="{{ __('Search properties...') }}" 
                           class="w-full p-3 border rounded-lg" value="{{ request('search') }}">
                </div>

                <!-- Price Range -->
                <div class="flex gap-2">
                    <input type="number" name="min_price" placeholder="{{ __('Min Price') }}" 
                           class="w-1/2 p-3 border rounded-lg" value="{{ request('min_price') }}">
                    <input type="number" name="max_price" placeholder="{{ __('Max Price') }}" 
                           class="w-1/2 p-3 border rounded-lg" value="{{ request('max_price') }}">
                </div>

                <!-- Property Type -->
                <div>
                    <select name="type" class="w-full p-3 border rounded-lg">
                        <option value="">{{ __('All Types') }}</option>
                        <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                        <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                        <option value="duplex" {{ request('type') == 'duplex' ? 'selected' : '' }}>{{ __('Duplex') }}</option>
                        <option value="penthouse" {{ request('type') == 'penthouse' ? 'selected' : '' }}>{{ __('Penthouse') }}</option>
                        <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>{{ __('Office') }}</option>
                        <option value="shop" {{ request('type') == 'shop' ? 'selected' : '' }}>{{ __('Shop') }}</option>
                        <option value="land" {{ request('type') == 'land' ? 'selected' : '' }}>{{ __('Land') }}</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div class="flex gap-2">
                    <select name="sort" class="w-3/4 p-3 border rounded-lg">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                    </select>
                    <button type="submit" class="w-1/4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Properties Grid -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <!-- Results Count -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('Properties For Sale') }}</h2>
                <p class="text-gray-600">{{ __(':count Properties Found', ['count' => $properties->total()]) }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($properties as $property)
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="{{ $property->getImageUrlAttribute() }}" alt="{{ $property->name }}" 
                             class="w-full h-56 object-cover">
                        <div class="absolute top-4 right-4">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">
                                {{ __('For Sale') }}
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
                                <span>{{ $property->unit_area ?? 0 }} {{ __('m²') }}</span>
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
                @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-home text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No properties found') }}</h3>
                    <p class="text-gray-600">{{ __('Try adjusting your search criteria') }}</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $properties->links() }}
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/3 mb-6 md:mb-0">
                    <h3 class="text-xl font-bold mb-4">{{ __('REAX CRM') }}</h3>
                    <p class="text-gray-600">{{ __('A comprehensive real estate management platform.') }}</p>
                </div>
                <div class="w-full md:w-1/3 mb-6 md:mb-0">
                    <h3 class="text-xl font-bold mb-4">{{ __('Links') }}</h3>
                    <ul class="text-gray-600">
                        <li class="mb-2"><a href="/" class="hover:text-blue-600">{{ __('Home') }}</a></li>
                        <li class="mb-2"><a href="{{ route('sale') }}" class="hover:text-blue-600">{{ __('Sale') }}</a></li>
                        <li class="mb-2"><a href="{{ route('rent') }}" class="hover:text-blue-600">{{ __('Rent') }}</a></li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3">
                    <h3 class="text-xl font-bold mb-4">{{ __('Contact Us') }}</h3>
                    <p class="text-gray-600">{{ __('Email: info@example.com') }}</p>
                    <p class="text-gray-600">{{ __('Phone: +1 234 567 8900') }}</p>
                </div>
            </div>
            <div class="border-t mt-8 pt-6 text-center text-gray-600">
                <p>© {{ date('Y') }} REAX CRM. {{ __('All rights reserved.') }}</p>
            </div>
        </div>
    </footer>
    
    <!-- AlpineJS for dropdowns -->
    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
