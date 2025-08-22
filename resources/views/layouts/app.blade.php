<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="Real Estate CRM - REAX" />
    <meta property="og:description" content="REAX Discover the best real estate deals with our powerful CRM!" />
    <meta property="og:image" content="https://real.e-egar.com/images/og-image.jpg" />
    <meta property="og:url" content="https://real.e-egar.com" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="REAX Real Estate" />
    <meta property="og:locale" content="en_US" />
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="REAX Real Estate CRM">
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
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Font Configuration */
        body {
            font-family: 'Roboto', sans-serif;
        }
        
        [dir="rtl"], html[lang="ar"] {
            font-family: 'Cairo', sans-serif !important;
        }
        
        /* Custom Gradient - Emerald Ocean Theme */
        .bg-gradient-main {
            background: linear-gradient(135deg, #0c4a6e 0%, #065f46 50%, #1e40af 100%);
        }
        
        .bg-gradient-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
        }
        
        /* Glass Effect */
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .glass-card {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(16, 185, 129, 0.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }
        
        /* Hover Effects */
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.15);
            transition: all 0.3s ease;
        }
        
        /* Accent Colors */
        .text-accent { color: #10b981; }
        .text-accent-dark { color: #047857; }
        .text-accent-light { color: #6ee7b7; }
        .bg-accent { background-color: #10b981; }
        .border-accent { border-color: #10b981; }
        .bg-accent-glow { 
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        /* Button Gradient */
        .btn-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        }
        
        /* Input Focus */
        .input-focus:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
            border-color: #10b981;
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #047857;
        }
        
        /* Table Styling */
        .emerald-table th {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .emerald-table tbody tr:hover {
            background-color: rgba(16, 185, 129, 0.05);
        }
        
        /* Form Styling */
        .emerald-form-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(16, 185, 129, 0.2);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .emerald-form-input:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        /* Gradient Stats Cards */
        .stat-card-blue { background: linear-gradient(135deg, #3b82f6, #1e40af); }
        .stat-card-green { background: linear-gradient(135deg, #10b981, #047857); }
        .stat-card-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
        .stat-card-yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-card-red { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stat-card-indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    </style>
    
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
<body class="min-h-screen bg-gradient-main" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
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
