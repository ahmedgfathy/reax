<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Featured Properties') }} - {{ config('app.name', 'REAX CRM') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    <header id="main-header" class="fixed w-full z-10 bg-blue-600">
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
                
                <!-- Login/Register Buttons -->
                <div class="flex space-x-4 items-center">
                    <a href="{{ route('login') }}" class="text-white hover:text-blue-200">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md font-medium transition-colors">
                        {{ __('Register') }}
                    </a>
                </div>
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
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Page Header with Title -->
    <div class="bg-gray-100 pt-24 pb-10">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Featured Properties') }}</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ __('Explore our selection of handpicked premium properties') }}
                </p>
            </div>
        </div>
    </div>
    
    <!-- Featured Properties Grid -->
    <div class="container mx-auto px-4 py-12">
        <!-- Property Filters (simplified) -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-10">
            <form action="{{ route('featured.properties') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <select name="type" class="w-full p-3 border rounded-lg" onchange="this.form.submit()">
                        <option value="">{{ __('All Property Types') }}</option>
                        <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                        <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                        <option value="house" {{ request('type') == 'house' ? 'selected' : '' }}>{{ __('House') }}</option>
                        <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>{{ __('Office') }}</option>
                    </select>
                </div>
                
                <div class="flex-1 min-w-[200px]">
                    <select name="for" class="w-full p-3 border rounded-lg" onchange="this.form.submit()">
                        <option value="">{{ __('Buy or Rent') }}</option>
                        <option value="sale" {{ request('for') == 'sale' ? 'selected' : '' }}>{{ __('For Sale') }}</option>
                        <option value="rent" {{ request('for') == 'rent' ? 'selected' : '' }}>{{ __('For Rent') }}</option>
                    </select>
                </div>
                
                <div class="flex-1 min-w-[200px]">
                    <select name="sort" class="w-full p-3 border rounded-lg" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredProperties as $property)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow">
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
                    <img src="{{ $imageUrl }}" alt="{{ $property->name }}" class="w-full h-64 object-cover">
                    
                    <div class="absolute top-4 left-4">
                        <span class="bg-{{ $property->unit_for == 'sale' ? 'blue' : 'green' }}-600 text-white px-3 py-1 rounded-full text-sm">
                            {{ $property->unit_for == 'sale' ? __('For Sale') : __('For Rent') }}
                        </span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-red-500 text-white p-1 rounded-full">
                            <i class="fas fa-heart"></i>
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
                        {{ $property->area ?? $property->location ?? 'Location not specified' }}
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
                    <div class="mt-6">
                        <a href="{{ route('properties.show', $property->id) }}" class="block bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            {{ __('View Details') }}
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 py-20 text-center">
                <i class="fas fa-home text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-500 mb-2">{{ __('No Featured Properties Found') }}</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    {{ __('We currently don\'t have any featured properties. Please check back later or browse our other listings.') }}
                </p>
                <div class="mt-6">
                    <a href="/" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        {{ __('Back to Home') }}
                    </a>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            {{ $featuredProperties->links() }}
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">REAX <span class="text-sm font-normal opacity-80">CRM</span></h3>
                    <p class="text-gray-400">
                        {{ __('Your trusted partner for finding the perfect property. We connect buyers, sellers, and renters with their dream properties.') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white">{{ __('Home') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('Properties') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('Services') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('About Us') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('Contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ __('Contact Info') }}</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                            <span>123 Property St., Real Estate City</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            <span>+1 234 567 890</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>info@reaxcrm.com</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ __('Newsletter') }}</h3>
                    <p class="text-gray-400 mb-4">{{ __('Subscribe to our newsletter for latest updates') }}</p>
                    <form class="flex">
                        <input type="email" placeholder="{{ __('Your Email') }}" class="flex-1 py-2 px-3 rounded-l-md focus:outline-none text-gray-900">
                        <button type="submit" class="bg-blue-600 py-2 px-4 rounded-r-md hover:bg-blue-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© {{ date('Y') }} REAX CRM. {{ __('All rights reserved.') }}</p>
            </div>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Header background change on scroll
            const header = document.getElementById('main-header');
            if (window.location.pathname === '/') {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        header.classList.add('bg-blue-600', 'shadow-md');
                    } else {
                        header.classList.remove('bg-blue-600', 'shadow-md');
                    }
                });
            }
        });
    </script>
</body>
</html>
