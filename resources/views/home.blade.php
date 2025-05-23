<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta property="og:title" content="REAX - Premium Global Real Estate" />
    <meta property="og:description" content="Discover exceptional properties worldwide with REAX - Your trusted partner in luxury real estate" />
    <meta property="og:image" content="https://real.e-egar.com/images/og-image.webp" />
    <meta property="og:url" content="https://real.e-egar.com" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="REAX Real Estate" />
    <meta property="og:locale" content="en_US" />
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="REAX - Premium Global Real Estate">
    <meta name="twitter:description" content="Discover exceptional properties worldwide with REAX - Your trusted partner in luxury real estate">
    <meta name="twitter:image" content="https://real.e-egar.com/images/og-image.jpg">

    <!-- Standard meta -->
    <meta name="description" content="Discover exceptional properties worldwide with REAX - Your trusted partner in luxury real estate">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('REAX - Premium Global Real Estate') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Noto+Kufi+Arabic:wght@300;400;500;700&family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
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

        /* Custom styles for enhanced UI elements */
        .btn-primary {
            @apply inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-all duration-300;
        }

        .btn-secondary {
            @apply inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-blue-600 bg-white border border-blue-600 hover:bg-blue-50 transition-all duration-300;
        }

        .card {
            @apply bg-white rounded-2xl shadow-lg overflow-hidden transition-transform duration-300;
        }

        .card:hover {
            @apply transform scale-105;
        }

        .input-field {
            @apply w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200;
        }

        .input-field:focus {
            @apply ring-blue-500;
        }

        .section-title {
            @apply text-3xl font-bold mb-4;
        }

        .section-subtitle {
            @apply text-gray-600 mb-12;
        }

        .property-tag {
            @apply absolute top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium;
        }

        .premium-tag {
            @apply absolute top-4 left-4 bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-medium;
        }

        .location-info {
            @apply flex items-center space-x-2 mb-4;
        }

        .location-info i {
            @apply text-blue-600;
        }

        .property-title {
            @apply text-xl font-bold mb-4 transition-colors duration-300;
        }

        .property-title:hover {
            @apply text-blue-600;
        }

        .property-meta {
            @apply flex justify-between items-center mb-4;
        }

        .property-price {
            @apply text-2xl font-bold text-blue-600;
        }

        .view-details-btn {
            @apply px-6 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .section-title {
                @apply text-2xl;
            }

            .property-title {
                @apply text-lg;
            }

            .property-meta {
                @apply flex-col;
            }

            .property-price {
                @apply text-xl;
            }
        }
    </style>
</head>
<body class="antialiased bg-white">
    <!-- Header -->
    <header class="absolute top-0 left-0 right-0 z-50">
        <nav class="container mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-white">REAX</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/sale" class="text-white hover:text-blue-100 transition">{{ __('Buy') }}</a>
                    <a href="/rent" class="text-white hover:text-blue-100 transition">{{ __('Rent') }}</a>
                    <a href="/sell" class="text-white hover:text-blue-100 transition">{{ __('Sell') }}</a>
                    <a href="/agents" class="text-white hover:text-blue-100 transition">{{ __('Agents') }}</a>
                    @guest
                        <a href="{{ route('login') }}" class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">{{ __('Sign In') }}</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">{{ __('Dashboard') }}</a>
                    @endguest
                </div>
                <button class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative h-screen">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-black/30"></div>
        <div class="absolute inset-0">
            <div class="swiper hero-swiper h-full">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3')] bg-cover bg-center"></div>
                    </div>
                    <div class="swiper-slide">
                        <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3')] bg-cover bg-center"></div>
                    </div>
                    <div class="swiper-slide">
                        <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1613490493576-7fde63acd811?ixlib=rb-4.0.3')] bg-cover bg-center"></div>
                    </div>
                    <div class="swiper-slide">
                        <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3')] bg-cover bg-center"></div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        
        <!-- Hero Content -->
        <div class="relative container mx-auto px-6 h-full flex flex-col items-center justify-center">
            <div class="text-center text-white max-w-4xl mx-auto mb-8">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">{{ __('Discover Your Perfect Home Worldwide') }}</h1>
                <p class="text-xl mb-12 text-white/90">{{ __('Premium properties in the world\'s most prestigious locations') }}</p>
            </div>
                
            <!-- Search Box -->
            <div class="bg-white/95 backdrop-blur-sm p-8 rounded-3xl shadow-2xl transform hover:scale-[1.02] transition-transform duration-300 w-full max-w-6xl mx-auto">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Main Search Input -->
                        <div class="flex-1 relative">
                            <input type="text" placeholder="{{ __('Enter a location or property type...') }}" 
                                   class="w-full px-6 py-4 pl-12 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 text-lg">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        </div>
                        
                        <!-- Filters Dropdown -->
                        <div class="flex gap-4">
                            <div class="relative">
                                <select class="appearance-none bg-gray-50 px-6 py-4 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 pr-10 text-lg">
                                    <option value="">{{ __('For Sale') }}</option>
                                    <option value="rent">{{ __('For Rent') }}</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                            
                            <div class="relative">
                                <select class="appearance-none bg-gray-50 px-6 py-4 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 pr-10 text-lg">
                                    <option value="">{{ __('Property Type') }}</option>
                                    <option value="apartment">{{ __('Apartment') }}</option>
                                    <option value="villa">{{ __('Villa') }}</option>
                                    <option value="house">{{ __('House') }}</option>
                                    <option value="penthouse">{{ __('Penthouse') }}</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                            
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 flex items-center gap-2 min-w-[140px]">
                                <i class="fas fa-search"></i>
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Advanced Filters -->
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                        <div class="flex gap-6">
                            <button class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors">
                                <i class="fas fa-sliders-h"></i>
                                {{ __('Price Range') }}
                            </button>
                            <button class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors">
                                <i class="fas fa-bed"></i>
                                {{ __('Bedrooms') }}
                            </button>
                            <button class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors">
                                <i class="fas fa-ruler-combined"></i>
                                {{ __('Area (m²)') }}
                            </button>
                        </div>
                        <button class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            {{ __('More Filters') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold mb-2">{{ __('Featured Properties') }}</h2>
                    <p class="text-gray-600">{{ __('Handpicked luxury properties for our distinguished clients') }}</p>
                </div>
                <a href="/featured" class="group flex items-center text-blue-600 hover:text-blue-700">
                    {{ __('View All') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
    $demoProperties = [
        [
            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'type' => 'Villa',
            'is_premium' => true,
            'title' => 'Luxury Villa with Ocean View',
            'location' => 'Beverly Hills, CA',
            'bedrooms' => 5,
            'bathrooms' => 4,
            'area' => 450,
            'price' => 2500000
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'type' => 'Penthouse',
            'is_premium' => true,
            'title' => 'Modern Downtown Penthouse',
            'location' => 'Dubai Marina',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'area' => 280,
            'price' => 1800000
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'type' => 'Apartment',
            'is_premium' => false,
            'title' => 'Elegant City Apartment',
            'location' => 'Manhattan, NY',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'area' => 150,
            'price' => 950000
        ],
    ];
@endphp

@foreach($demoProperties as $property)
    <div class="card group">
        <div class="relative overflow-hidden">
            <img src="{{ $property['image'] }}" alt="{{ $property['title'] }}" 
                 class="w-full h-72 object-cover transform group-hover:scale-110 transition duration-700">
            <div class="property-tag">
                {{ $property['type'] }}
            </div>
            @if($property['is_premium'])
                <div class="premium-tag">
                    {{ __('Premium') }}
                </div>
            @endif
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-25 transition-opacity duration-700"></div>
        </div>
        <div class="p-6">
            <div class="location-info">
                <i class="fas fa-map-marker-alt"></i>
                <span class="text-gray-600">{{ $property['location'] }}</span>
            </div>
            <h3 class="property-title">{{ $property['title'] }}</h3>
            <div class="property-meta">
                <div class="flex items-center space-x-4">
                    <span class="flex items-center text-gray-600">
                        <i class="fas fa-bed mr-2"></i> {{ $property['bedrooms'] }}
                    </span>
                    <span class="flex items-center text-gray-600">
                        <i class="fas fa-bath mr-2"></i> {{ $property['bathrooms'] }}
                    </span>
                    <span class="flex items-center text-gray-600">
                        <i class="fas fa-ruler-combined mr-2"></i> {{ $property['area'] }}m²
                    </span>
                </div>
            </div>
            <div class="flex justify-between items-center pt-4 border-t">
                <span class="property-price">${{ number_format($property['price']) }}</span>
                <a href="#" class="view-details-btn">
                    {{ __('View Details') }}
                </a>
            </div>
        </div>
    </div>
@endforeach
            </div>
        </div>
    </section>

    <!-- Popular Locations -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-4">{{ __('Popular Locations') }}</h2>
            <p class="text-gray-600 text-center mb-12">{{ __('Explore our most sought-after property locations') }}</p>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($popularLocations ?? [] as $location)
                    <a href="/properties/location/{{ $location->slug }}" 
                       class="relative rounded-2xl overflow-hidden group h-72">
                        <img src="{{ $location->image }}" alt="{{ $location->name }}" 
                             class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-6 left-6">
                            <h3 class="text-white font-bold text-xl mb-1">{{ $location->name }}</h3>
                            <p class="text-white/90">{{ $location->property_count }} {{ __('Properties') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold mb-4">{{ __('Why Choose REAX') }}</h2>
                <p class="text-gray-600">{{ __('Experience luxury real estate at its finest with our premium services') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center p-8 rounded-2xl bg-white shadow-lg hover:shadow-xl transition">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-globe text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">{{ __('Global Network') }}</h3>
                    <p class="text-gray-600">{{ __('Access premium properties from our worldwide network of trusted partners') }}</p>
                </div>
                
                <div class="text-center p-8 rounded-2xl bg-white shadow-lg hover:shadow-xl transition">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-tie text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">{{ __('Expert Agents') }}</h3>
                    <p class="text-gray-600">{{ __('Work with experienced professionals who understand your needs') }}</p>
                </div>
                
                <div class="text-center p-8 rounded-2xl bg-white shadow-lg hover:shadow-xl transition">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-lock text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">{{ __('Secure Transactions') }}</h3>
                    <p class="text-gray-600">{{ __('Every property transaction is handled with utmost security and transparency') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-blue-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-600 opacity-90"></div>
        <div class="relative container mx-auto px-6 text-center text-white">
            <h2 class="text-4xl font-bold mb-6">{{ __('Ready to Find Your Dream Home?') }}</h2>
            <p class="text-xl mb-8 text-white/90">{{ __('Let us help you discover the perfect property that matches your lifestyle') }}</p>
            <div class="flex justify-center space-x-4">
                <a href="/properties" class="px-8 py-4 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                    {{ __('Browse Properties') }}
                </a>
                <a href="/contact" class="px-8 py-4 border-2 border-white text-white rounded-lg hover:bg-white/10 transition">
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <h3 class="text-2xl font-bold mb-6">REAX</h3>
                    <p class="text-gray-400">{{ __('Your trusted partner in luxury real estate worldwide') }}</p>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('Quick Links') }}</h4>
                    <ul class="space-y-4">
                        <li><a href="/about" class="text-gray-400 hover:text-white transition">{{ __('About Us') }}</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white transition">{{ __('Contact') }}</a></li>
                        <li><a href="/careers" class="text-gray-400 hover:text-white transition">{{ __('Careers') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('Properties') }}</h4>
                    <ul class="space-y-4">
                        <li><a href="/sale" class="text-gray-400 hover:text-white transition">{{ __('For Sale') }}</a></li>
                        <li><a href="/rent" class="text-gray-400 hover:text-white transition">{{ __('For Rent') }}</a></li>
                        <li><a href="/new-developments" class="text-gray-400 hover:text-white transition">{{ __('New Developments') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('Connect With Us') }}</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-blue-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-blue-400 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-pink-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-blue-700 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8">
                <p class="text-gray-400 text-center">&copy; {{ date('Y') }} REAX. {{ __('All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        const heroSwiper = new Swiper('.hero-swiper', {
            loop: true,
            effect: 'fade',
            speed: 1000,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            }
        });

        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('button.md\\:hidden');
        const mobileMenu = document.querySelector('div.md\\:flex');
        
        mobileMenuButton?.addEventListener('click', () => {
            mobileMenu?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
