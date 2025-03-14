<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
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
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <!-- Mobile Sidebar Toggle Button -->
    <button id="sidebarToggle" class="fixed bottom-4 left-4 z-50 lg:hidden bg-blue-600 text-white rounded-full p-3 shadow-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-bars"></i>
    </button>

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
            <div class="container mx-auto py-4 px-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $header ?? __('Dashboard') }}</h1>
                    <div class="text-gray-600">{{ __('Welcome back, System Admin!') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content with Sidebar -->
    <div class="flex pt-28"> <!-- Added padding top to account for fixed header -->
        <!-- Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" 
             onclick="toggleSidebar()"></div>

        <!-- Fixed Sidebar -->
        <div id="sidebar" class="fixed left-0 top-28 bottom-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40 lg:z-30">
            @include('layouts.sidebar')
        </div>

        <!-- Scrollable Main Content -->
        <div class="flex-1 lg:ml-64 p-6 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    
    <!-- PWA Scripts -->
    <script src="{{ asset('js/pwa.js') }}" defer></script>

    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggle');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            toggleBtn.classList.toggle('rotate-180');
        }

        // Close sidebar on window resize if screen becomes large
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) { // lg breakpoint
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const toggleBtn = document.getElementById('sidebarToggle');

                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                toggleBtn.classList.remove('rotate-180');
            }
        });
    </script>
</body>
</html>
