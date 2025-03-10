<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'REAX Admin - Real Estate Platform')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Top Navigation Bar -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <span class="text-2xl font-bold">REAX</span>
                <span class="ml-2 text-sm bg-blue-700 px-2 py-0.5 rounded">ADMIN</span>
            </a>
            
            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center focus:outline-none">
                            <span class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="ml-2">{{ Auth::user()->name }}</span>
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                @endauth
            </div>
        </div>
    </header>
    
    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-white w-64 min-h-screen shadow-lg hidden md:block">
            <div class="py-4 px-3">
                <nav>
                    <a href="{{ route('dashboard') }}" class="block py-3 px-4 rounded-lg mb-1 {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('properties.index') }}" class="block py-3 px-4 rounded-lg mb-1 {{ request()->routeIs('properties.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-home mr-2"></i> {{ __('Properties') }}
                    </a>
                    <a href="{{ route('leads.index') }}" class="block py-3 px-4 rounded-lg mb-1 {{ request()->routeIs('leads.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-user-tag mr-2"></i> {{ __('Leads') }}
                    </a>
                    <a href="{{ route('reports.index') }}" class="block py-3 px-4 rounded-lg mb-1 {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-chart-bar mr-2"></i> {{ __('Reports') }}
                    </a>
                    <a href="{{ url('/') }}" class="block py-3 px-4 rounded-lg mb-1 text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-globe mr-2"></i> {{ __('View Site') }}
                    </a>
                </nav>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-grow p-6">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
        </main>
    </div>
    
    <!-- Mobile navigation overlay -->
    <div id="mobile-nav" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden">
        <div class="bg-white w-64 min-h-screen p-4">
            <div class="flex justify-between items-center mb-4">
                <span class="text-xl font-bold">REAX Admin</span>
                <button id="close-nav" class="text-gray-500 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav>
                <a href="{{ route('dashboard') }}" class="block py-3 px-4 rounded-lg mb-1 {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                </a>
                <a href="{{ route('properties.index') }}" class="block py-3 px-4 rounded-lg mb-1 {{ request()->routeIs('properties.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-home mr-2"></i> {{ __('Properties') }}
                </a>
                <a href="{{ route('leads.index') }}" class="block py-3 px-4 rounded-lg mb-1 {{ request()->routeIs('leads.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-user-tag mr-2"></i> {{ __('Leads') }}
                </a>
                <a href="{{ route('reports.index') }}" class="block py-3 px-4 rounded-lg mb-1 {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-chart-bar mr-2"></i> {{ __('Reports') }}
                </a>
                <a href="{{ url('/') }}" class="block py-3 px-4 rounded-lg mb-1 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-globe mr-2"></i> {{ __('View Site') }}
                </a>
            </nav>
        </div>
    </div>
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileNav = document.getElementById('mobile-nav');
            const closeNavButton = document.getElementById('close-nav');
            
            if (mobileMenuButton && mobileNav && closeNavButton) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileNav.classList.remove('hidden');
                });
                
                closeNavButton.addEventListener('click', function() {
                    mobileNav.classList.add('hidden');
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
