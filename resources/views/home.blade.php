<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('REAX - Real Estate CRM') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Noto+Kufi+Arabic:wght@300;400;500;700&family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
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
        .swiper-slide {
            background-position: center;
            background-size: cover;
            height: 560px;
            width: 100%;
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

        /* Custom select styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        /* Custom scrollbar for dropdowns */
        select::-webkit-scrollbar {
            width: 8px;
        }

        select::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        select::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        /* Hover effect for filter inputs */
        .filter-hover-effect:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Updated pulsating login button animation with white circular shadow */
        @keyframes pulsate {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
            70% {
                transform: scale(1.1);
                box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }
        
        .login-button {
            font-size: 1.2rem;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white !important;
            transition: all 0.3s ease;
            animation: pulsate 2s infinite;
            box-shadow: 0 4px 6px -1px rgba(255, 255, 255, 0.2), 0 2px 4px -1px rgba(255, 255, 255, 0.1);
        }
        
        .login-button:hover {
            transform: translateY(-2px) scale(1.05);
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 8px 12px -1px rgba(255, 255, 255, 0.3), 0 4px 6px -1px rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="antialiased bg-gray-100">
    <header class="absolute top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-white text-2xl font-bold">{{ __('REAX') }}</a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center">
                <!-- Main Navigation Links -->
                <div class="flex space-x-6 items-center mr-6">
                    <a href="/" class="text-white hover:text-gray-200 font-medium">{{ __('Home') }}</a>
                    <a href="{{ route('sale') }}" class="text-white hover:text-gray-200 font-medium">{{ __('Sale') }}</a>
                    <a href="{{ route('rent') }}" class="text-white hover:text-gray-200 font-medium">{{ __('Rent') }}</a>
                    <a href="#" class="text-white hover:text-gray-200 font-medium">{{ __('Primary') }}</a>
                    <a href="#" class="text-white hover:text-gray-200 font-medium">{{ __('About Us') }}</a>
                    <a href="#" class="text-white hover:text-gray-200 font-medium">{{ __('Contact Us') }}</a>
                </div>

                <!-- Language Switcher -->
                <form method="POST" action="{{ route('locale.switch') }}" class="inline-flex items-center mr-6">
                    @csrf
                    <input type="hidden" name="locale" value="{{ app()->getLocale() == 'en' ? 'ar' : 'en' }}">
                    <button type="submit" class="text-white hover:text-gray-200 flex items-center">
                        <i class="fas fa-globe mr-2"></i>
                        <span>{{ app()->getLocale() == 'en' ? 'عربي' : 'ENG' }}</span>
                    </button>
                </form>

                <!-- Auth Buttons -->
                @auth
                    <div class="flex space-x-4 items-center">
                        <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-4 py-2 rounded-md font-medium transition-colors flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-white hover:text-blue-200 flex items-center">
                                <span class="h-8 w-8 bg-white rounded-full flex items-center justify-center text-blue-600 font-medium mr-2">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                {{ Auth::user()->name }}
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">{{ __('Profile') }}</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">{{ __('Logout') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex space-x-4 items-center">
                        <a href="{{ route('login') }}" class="login-button">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-4 py-2 rounded-md font-medium transition-colors">{{ __('Register') }}</a>
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
                                <a href="{{ route('login') }}" class="login-button">{{ __('Login') }}</a>
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-3 py-1 rounded-md">{{ __('Register') }}</a>
                            </div>
                        </div>
                    @endauth
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Hero Section with Swiper Slider -->
    <section class="relative h-[560px] bg-gray-900">
        <div class="swiper heroSwiper h-full">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80')">
                    <div class="absolute inset-0 bg-black/40"></div>
                    <div class="container mx-auto px-4 h-full flex items-center relative z-10">
                        <div class="text-white max-w-xl">
                            <h1 class="text-5xl font-bold mb-4">{{ __('Mountain View iCity') }}</h1>
                            <p class="text-xl mb-6">{{ __('Luxury Compounds in New Cairo') }}</p>
                            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg inline-flex items-center">
                                {{ __('Explore Now') }} <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80')">
                    <div class="absolute inset-0 bg-black/40"></div>
                    <div class="container mx-auto px-4 h-full flex items-center relative z-10">
                        <div class="text-white max-w-xl">
                            <h1 class="text-5xl font-bold mb-4">{{ __('Mountain View iCity') }}</h1>
                            <p class="text-xl mb-6">{{ __('Luxury Compounds in New Cairo') }}</p>
                            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg inline-flex items-center">
                                {{ __('Explore Now') }} <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80')">
                    <div class="absolute inset-0 bg-black/40"></div>
                    <div class="container mx-auto px-4 h-full flex items-center relative z-10">
                        <div class="text-white max-w-xl">
                            <h1 class="text-5xl font-bold mb-4">{{ __('Mountain View iCity') }}</h1>
                            <p class="text-xl mb-6">{{ __('Luxury Compounds in New Cairo') }}</p>
                            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg inline-flex items-center">
                                {{ __('Explore Now') }} <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Slide 4 -->
                <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1600573472550-8090b5e0745e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80')">
                    <div class="absolute inset-0 bg-black/40"></div>
                    <div class="container mx-auto px-4 h-full flex items-center relative z-10">
                        <div class="text-white max-w-xl">
                            <h1 class="text-5xl font-bold mb-4">{{ __('Mountain View iCity') }}</h1>
                            <p class="text-xl mb-6">{{ __('Luxury Compounds in New Cairo') }}</p>
                            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg inline-flex items-center">
                                {{ __('Explore Now') }} <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Slide 5 -->
                <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80')">
                    <div class="absolute inset-0 bg-black/40"></div>
                    <div class="container mx-auto px-4 h-full flex items-center relative z-10">
                        <div class="text-white max-w-xl">
                            <h1 class="text-5xl font-bold mb-4">{{ __('Mountain View iCity') }}</h1>
                            <p class="text-xl mb-6">{{ __('Luxury Compounds in New Cairo') }}</p>
                            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg inline-flex items-center">
                                {{ __('Explore Now') }} <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Search Filters Bar -->
        <div class="absolute bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md shadow-lg transform translate-y-1/2 z-20">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col lg:flex-row gap-4 items-center">
                    <!-- Search Input -->
                    <div class="w-full lg:w-1/3">
                        <div class="relative">
                            <input type="text" 
                                   placeholder="{{ __('Search by location, compound name...') }}" 
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Unit Type Dropdown -->
                    <div class="w-full lg:w-1/6">
                        <select class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('Unit Type') }}</option>
                            <option value="apartment">{{ __('Apartment') }}</option>
                            <option value="villa">{{ __('Villa') }}</option>
                            <option value="duplex">{{ __('Duplex') }}</option>
                            <option value="penthouse">{{ __('Penthouse') }}</option>
                            <option value="studio">{{ __('Studio') }}</option>
                        </select>
                    </div>

                    <!-- Region Dropdown -->
                    <div class="w-full lg:w-1/6">
                        <select class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('Region') }}</option>
                            <option value="new-cairo">{{ __('New Cairo') }}</option>
                            <option value="6th-october">{{ __('6th October') }}</option>
                            <option value="maadi">{{ __('Maadi') }}</option>
                            <option value="sheikh-zayed">{{ __('Sheikh Zayed') }}</option>
                            <option value="north-coast">{{ __('North Coast') }}</option>
                        </select>
                    </div>

                    <!-- Payment Method Dropdown -->
                    <div class="w-full lg:w-1/6">
                        <select class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('Payment Method') }}</option>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="installment">{{ __('Installment') }}</option>
                            <option value="financing">{{ __('Bank Financing') }}</option>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="w-full lg:w-auto">
                        <button class="w-full lg:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-search-location"></i>
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add margin top to next section to account for filter bar -->
    <section class="py-12 bg-gray-50 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Featured Properties -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Featured Properties') }}</h2>
                        <p class="text-sm text-gray-600 max-w-xl mx-auto">{{ __('Discover our hand-picked selection of featured properties') }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($featuredProperties as $property)
                            <!-- Property Card -->
                            <div class="bg-white rounded-lg border border-gray-100 hover:border-blue-200 transition-all duration-300 hover:shadow-md">
                                <div class="relative h-40">
                                    <!-- Same image handling logic -->
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
                                    <img src="{{ $imageUrl }}" alt="{{ $property->name }}" class="w-full h-40 object-cover rounded-t-lg">
                                    
                                    <!-- Heart Icon - Made Smaller -->
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-red-500 text-white p-1 rounded-full flex items-center justify-center text-xs">
                                            <i class="fas fa-heart"></i>
                                        </span>
                                    </div>
                                    
                                    <!-- Status Badge - Made Smaller -->
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-{{ $property->unit_for == 'sale' ? 'blue' : 'green' }}-600 text-white px-2 py-0.5 rounded-full text-xs">
                                            {{ $property->unit_for == 'sale' ? __('For Sale') : __('For Rent') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-sm font-bold text-gray-900 mb-1 truncate">{{ $property->name }}</h3>
                                    <p class="text-xs text-gray-600 mb-2 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                        {{ $property->area ?? $property->location ?? __('Location not specified') }}
                                    </p>
                                    
                                    <!-- Property Details - Compact Layout -->
                                    <div class="grid grid-cols-3 gap-2 mb-3 text-xs text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-bed mr-1"></i>
                                            <span>{{ $property->rooms ?? 0 }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-1"></i>
                                            <span>{{ $property->bathrooms ?? 0 }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-ruler-combined mr-1"></i>
                                            <span>{{ $property->unit_area ?? 0 }}m²</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Price Tag -->
                                    <div class="text-sm font-semibold text-blue-600 mb-2">
                                        {{ number_format($property->price) }} {{ $property->currency ?? 'EGP' }}
                                    </div>
                                    
                                    <!-- View Details Link -->
                                    <a href="{{ route('properties.show', $property->id) }}" 
                                       class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                        {{ __('View Details') }}
                                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <!-- Fallback Cards with Similar Styling -->
                            <div class="bg-white rounded-lg border border-gray-100 hover:border-blue-200 transition-all duration-300 hover:shadow-md">
                                <div class="relative h-40">
                                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Property" class="w-full h-40 object-cover rounded-t-lg">
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-blue-600 text-white px-2 py-0.5 rounded-full text-xs">{{ __('For Sale') }}</span>
                                    </div>
                                    <div class="absolute bottom-2 right-2">
                                        <span class="bg-white/80 backdrop-blur-sm text-gray-900 px-2 py-0.5 rounded-full text-xs font-semibold">$2,500,000</span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-sm font-bold text-gray-900 mb-1 truncate">{{ __('Modern Villa with Pool') }}</h3>
                                    <p class="text-xs text-gray-600 mb-2 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                        {{ __('Palm Hills, New Cairo') }}
                                    </p>
                                    <div class="grid grid-cols-3 gap-2 mb-3 text-xs text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-bed mr-1"></i>
                                            <span>4</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-1"></i>
                                            <span>3</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-ruler-combined mr-1"></i>
                                            <span>350m²</span>
                                        </div>
                                    </div>
                                    <div class="text-sm font-semibold text-blue-600 mb-2">
                                        $2,500,000
                                    </div>
                                    <a href="#" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                        {{ __('View Details') }}
                                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg border border-gray-100 hover:border-blue-200 transition-all duration-300 hover:shadow-md">
                                <div class="relative h-40">
                                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Property" class="w-full h-40 object-cover rounded-t-lg">
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-green-600 text-white px-2 py-0.5 rounded-full text-xs">{{ __('For Rent') }}</span>
                                    </div>
                                    <div class="absolute bottom-2 right-2">
                                        <span class="bg-white/80 backdrop-blur-sm text-gray-900 px-2 py-0.5 rounded-full text-xs font-semibold">$1,800/{{ __('month') }}</span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-sm font-bold text-gray-900 mb-1 truncate">{{ __('Luxury Apartment') }}</h3>
                                    <p class="text-xs text-gray-600 mb-2 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                        {{ __('Zamalek, Cairo') }}
                                    </p>
                                    <div class="grid grid-cols-3 gap-2 mb-3 text-xs text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-bed mr-1"></i>
                                            <span>3</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-1"></i>
                                            <span>2</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-ruler-combined mr-1"></i>
                                            <span>180m²</span>
                                        </div>
                                    </div>
                                    <div class="text-sm font-semibold text-blue-600 mb-2">
                                        $1,800/{{ __('month') }}
                                    </div>
                                    <a href="#" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                        {{ __('View Details') }}
                                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right Column: Top Compounds -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Top Compounds') }}</h2>
                        <p class="text-sm text-gray-600 max-w-xl mx-auto">{{ __('Explore the top compounds in New Cairo') }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Compound Card 1 -->
                        <div class="bg-white rounded-lg border border-gray-100 hover:border-blue-200 transition-all duration-300 hover:shadow-md">
                            <div class="relative h-40">
                                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Compound" 
                                     class="w-full h-40 object-cover rounded-t-lg">
                                
                                <div class="absolute top-2 left-2">
                                    <span class="bg-red-500 text-white p-1 rounded-full flex items-center justify-center text-xs">
                                        <i class="fas fa-heart"></i>
                                    </span>
                                </div>
                                
                                <div class="absolute top-2 right-2">
                                    <span class="bg-blue-600 text-white px-2 py-0.5 rounded-full text-xs">
                                        {{ __('Premium') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <h3 class="text-sm font-bold text-gray-900 mb-1 truncate">{{ __('Mountain View Hyde Park') }}</h3>
                                <p class="text-xs text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                    {{ __('New Cairo') }}
                                </p>
                                
                                <div class="grid grid-cols-3 gap-2 mb-3 text-xs text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-building mr-1"></i>
                                        <span>120 {{ __('Units') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-tree mr-1"></i>
                                        <span>75% {{ __('Green') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-ruler-combined mr-1"></i>
                                        <span>1.2M {{ __('m²') }}</span>
                                    </div>
                                </div>
                                
                                <div class="text-sm font-semibold text-blue-600 mb-2">
                                    {{ __('Starting from') }} 2.5M {{ __('EGP') }}
                                </div>
                                
                                <a href="#" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                    {{ __('View Details') }}
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Compound Card 2 -->
                        <div class="bg-white rounded-lg border border-gray-100 hover:border-blue-200 transition-all duration-300 hover:shadow-md">
                            <div class="relative h-40">
                                <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Compound" 
                                     class="w-full h-40 object-cover rounded-t-lg">
                                
                                <div class="absolute top-2 left-2">
                                    <span class="bg-red-500 text-white p-1 rounded-full flex items-center justify-center text-xs">
                                        <i class="fas fa-heart"></i>
                                    </span>
                                </div>
                                
                                <div class="absolute top-2 right-2">
                                    <span class="bg-blue-600 text-white px-2 py-0.5 rounded-full text-xs">
                                        {{ __('Premium') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <h3 class="text-sm font-bold text-gray-900 mb-1 truncate">{{ __('Madinaty') }}</h3>
                                <p class="text-xs text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                    {{ __('New Cairo') }}
                                </p>
                                
                                <div class="grid grid-cols-3 gap-2 mb-3 text-xs text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-building mr-1"></i>
                                        <span>200 {{ __('Units') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-tree mr-1"></i>
                                        <span>80% {{ __('Green') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-ruler-combined mr-1"></i>
                                        <span>2.1M {{ __('m²') }}</span>
                                    </div>
                                </div>
                                
                                <div class="text-sm font-semibold text-blue-600 mb-2">
                                    {{ __('Starting from') }} 1.8M {{ __('EGP') }}
                                </div>
                                
                                <a href="#" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                    {{ __('View Details') }}
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Additional Compound Cards... -->
                        <!-- Add more compound cards following the same pattern -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Super Deal Installments Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Super Deal Installments') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">{{ __('Get the best deals on real estate units with easy installments') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Deal Card 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Deal" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Deal Name 1') }}</h3>
                        <p class="text-gray-600">{{ __('Installment Plan: 5 years') }}</p>
                    </div>
                </div>
                
                <!-- Additional deal cards with different images -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Deal" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Deal Name 2') }}</h3>
                        <p class="text-gray-600">{{ __('Installment Plan: 7 years') }}</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Deal" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Deal Name 3') }}</h3>
                        <p class="text-gray-600">{{ __('Installment Plan: 10 years') }}</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Deal" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Deal Name 4') }}</h3>
                        <p class="text-gray-600">{{ __('Installment Plan: 15 years') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Top Sellers Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Top Sellers') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">{{ __('Meet our top real estate agents') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Seller Card 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Seller" class="w-full h-56 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Seller Name 1') }}</h3>
                        <p class="text-gray-600">{{ __('Top Agent') }}</p>
                    </div>
                </div>
                
                <!-- Additional seller cards with different images -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Seller" class="w-full h-56 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Seller Name 2') }}</h3>
                        <p class="text-gray-600">{{ __('Top Agent') }}</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Seller" class="w-full h-56 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Seller Name 3') }}</h3>
                        <p class="text-gray-600">{{ __('Top Agent') }}</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Seller" class="w-full h-56 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Seller Name 4') }}</h3>
                        <p class="text-gray-600">{{ __('Top Agent') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Top Companies and Agencies Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Top Companies and Agencies') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">{{ __('Our trusted partners in real estate') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Card 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Company" class="w-full h-56 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Company Name 1') }}</h3>
                    </div>
                </div>
                
                <!-- Additional company cards with different images -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Company" class="w-full h-56 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Company Name 2') }}</h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Company" class="w-full h-56 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Company Name 3') }}</h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Company" class="w-full h-56 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Company Name 4') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Google Maps Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Our Agencies Locations') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">{{ __('Find our trusted real estate agencies across Cairo') }}</p>
            </div>
            
            <div class="w-full h-[500px] rounded-lg overflow-hidden shadow-lg" id="map"></div>
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

    <!-- Add Footer Section before closing body tag -->
    <footer class="bg-gray-900 text-white">
        <!-- Main Footer -->
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ __('REAX') }}</h3>
                    <p class="text-gray-400 mb-4">{{ __('Your trusted partner in real estate. Find your perfect property with us.') }}</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ __('Quick Links') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('sale') }}" class="text-gray-400 hover:text-white">{{ __('Properties for Sale') }}</a></li>
                        <li><a href="{{ route('rent') }}" class="text-gray-400 hover:text-white">{{ __('Properties for Rent') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('New Projects') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('About Us') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('Contact Us') }}</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ __('Contact Us') }}</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt mt-1 text-gray-400"></i>
                            <span class="text-gray-400">123 {{ __('Street Name, City, Country') }}</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone-alt text-gray-400"></i>
                            <span class="text-gray-400">+20 123 456 7890</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                            <span class="text-gray-400">info@reax.com</span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ __('Newsletter') }}</h4>
                    <p class="text-gray-400 mb-4">{{ __('Subscribe to our newsletter for updates and exclusive offers.') }}</p>
                    <form class="flex">
                        <input type="email" 
                               placeholder="{{ __('Your email address') }}" 
                               class="bg-gray-800 text-white px-4 py-2 rounded-l-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-800">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-400 text-sm mb-4 md:mb-0">
                        © {{ date('Y') }} {{ __('REAX. All rights reserved.') }}
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white text-sm">{{ __('Privacy Policy') }}</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm">{{ __('Terms of Service') }}</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm">{{ __('Cookie Policy') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- AlpineJS for dropdowns -->
    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swiper
            const swiper = new Swiper(".heroSwiper", {
                loop: true,
                effect: "fade",
                speed: 1000,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                }
            });
            
            // Existing script for mobile menu toggle
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
    <script>
        // Sample agencies data
        const agencies = [
            { name: 'Main Office', lat: 30.0444, lng: 31.2357, info: 'REAX Headquarters' }, // Downtown Cairo
            { name: 'New Cairo Branch', lat: 30.0283, lng: 31.4454, info: 'New Cairo Office' },
            { name: '6th October Branch', lat: 29.9285, lng: 30.9188, info: '6th October Office' },
            { name: 'Maadi Branch', lat: 29.9602, lng: 31.2569, info: 'Maadi Office' }
        ];

        function initMap() {
            // Create map centered on Cairo
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 11,
                center: { lat: 30.0444, lng: 31.2357 }, // Cairo coordinates
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });

            // Add markers for each agency
            agencies.forEach(agency => {
                const marker = new google.maps.Marker({
                    position: { lat: agency.lat, lng: agency.lng },
                    map: map,
                    title: agency.name,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                    }
                });

                // Create info window for each marker
                const infowindow = new google.maps.InfoWindow({
                    content: `<div class="p-2">
                        <h3 class="font-bold text-gray-900">${agency.name}</h3>
                        <p class="text-gray-600">${agency.info}</p>
                    </div>`
                });

                marker.addListener('click', () => {
                    infowindow.open(map, marker);
                });
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
</body>
</html>
