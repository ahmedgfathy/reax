<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'REAX - Real Estate Platform')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="flex items-center">
                <span class="text-2xl font-bold text-blue-600">REAX</span>
                <span class="ml-2 text-gray-600">CRM</span>
            </a>
            
            <!-- Main Navigation -->
            <nav class="hidden md:flex space-x-6">
                <a href="/" class="text-gray-800 hover:text-blue-600">{{ __('Home') }}</a>
                <a href="{{ route('sale') }}" class="text-gray-800 hover:text-blue-600">{{ __('Sale') }}</a>
                <a href="{{ route('rent') }}" class="text-gray-800 hover:text-blue-600">{{ __('Rent') }}</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-800 hover:text-blue-600">{{ __('Dashboard') }}</a>
                @endauth
            </nav>
            
            <!-- User Menu -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700 focus:outline-none">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Profile') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Log Out') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-800 hover:text-blue-600">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">{{ __('Register') }}</a>
                @endauth
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-2">
                <a href="/" class="block py-2">{{ __('Home') }}</a>
                <a href="{{ route('sale') }}" class="block py-2">{{ __('Sale') }}</a>
                <a href="{{ route('rent') }}" class="block py-2">{{ __('Rent') }}</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="block py-2">{{ __('Dashboard') }}</a>
                    <a href="{{ route('profile.show') }}" class="block py-2">{{ __('Profile') }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-red-600">{{ __('Log Out') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="block py-2">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </header>
    
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <!-- Page Content -->
    <main>
        @yield('content')
    </main>
    
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
    
    <!-- Alpine.js for dropdowns -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
