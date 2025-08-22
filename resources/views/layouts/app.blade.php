<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="Real Estate CRM - E-Gar" />
    <meta property="og:description" content="REAX Discover the best real estate deals with our powerful CRM!" />
    <meta property="og:image" content="https://real.e-egar.com/images/og-image.jpg" />
    <meta property="og:url" content="https://real.e-egar.com" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="REAX E-Gar Real Estate" />
    <meta property="og:locale" content="en_US" />
    
    <!-- Twitter Card (Optional) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="REAX Real Estate CRM from E-Gar">
    <meta name="twitter:description" content="REAX Discover the best real estate deals with our powerful CRM!">
    <meta name="twitter:image" content="https://real.e-egar.com/images/og-image.jpg">

    <!-- Standard meta -->
    <meta name="description" content="Discover the best real estate deals with our powerful CRM!">

    <title>{{ $title ?? config('app.name') }}</title>
    
    <script>
        // Set up CSRF token for all AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            window.csrf_token = token;
            
            // Set up axios defaults if using axios
            if (window.axios) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
            }
        });
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brand/logo.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/brand/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/brand/favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/brand/apple-touch-icon.png') }}">
    
    <!-- Add PWA meta tags -->
    <meta name="theme-color" content="#2563EB">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Apple PWA Meta Tags -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/brand/apple-touch-icon.png') }}">
    <meta name="apple-mobile-web-app-title" content="REAX">
    
    <!-- Microsoft PWA Meta Tags -->
    <meta name="msapplication-TileColor" content="#2563EB">
    <meta name="msapplication-TileImage" content="{{ asset('images/brand/icon-144.png') }}">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    @include('components.layouts.alert-scripts')
    
    <!-- Add PWA Install Button -->
    <div id="pwa-install-button" class="hidden fixed bottom-4 right-4 z-50">
        <button class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            {{ __('Install App') }}
        </button>
    </div>
    
    <!-- Fixed Header -->
    <div class="fixed top-0 left-0 right-0 z-50">
        <!-- Main Navigation -->
        @include('components.header-menu')
        
        <!-- Module Header (Single white bar) -->
        <div class="bg-white shadow-sm border-b">
            <div class="container mx-auto py-2 px-3 sm:py-2 sm:px-4">
                <div class="flex justify-between items-center">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-base sm:text-xl font-bold text-gray-800 truncate">{{ $header ?? __('Dashboard') }}</h1>
                        @isset($breadcrumbs)
                            <div class="hidden sm:flex items-center text-xs text-gray-500">
                                {{ $breadcrumbs }}
                            </div>
                        @endisset
                    </div>
                    @auth
                        <div class="hidden sm:block text-gray-600 text-xs ml-2">
                            {{ Auth::user()->name ?? 'Guest' }} 
                            @if(Auth::user()->role)
                                <span class="hidden md:inline">- {{ Auth::user()->role_name }}</span>
                            @endif
                            @if(Auth::user()->company)
                                <span class="hidden lg:inline">{{ __('at') }} {{ Auth::user()->company->name }}</span>
                            @endif
                        </div>
                    @else
                        <div class="hidden sm:block text-gray-600 text-xs">
                            {{ __('Welcome Guest') }}
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content without Sidebar -->
    <div class="pt-28"> <!-- Reduced from 36 to 28 -->
        <!-- Scrollable Main Content -->
        <div class="flex-1 p-2 overflow-y-auto">
            @yield('content')
        </div>
    </div>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    
    <!-- PWA Scripts -->
    <script src="{{ asset('js/pwa.js') }}" defer></script>
</body>
</html>
