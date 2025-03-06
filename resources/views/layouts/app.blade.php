<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'REAX CRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome - Added with defer=false to ensure icons load before page renders -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS via CDN as fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    
    <!-- Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Font settings based on language */
        body {
            font-family: 'Roboto', sans-serif;
        }
        
        html[dir="rtl"] body,
        html.rtl body {
            font-family: 'Cairo', sans-serif !important;
        }
        
        /* RTL spacing fixes */
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
    
    <!-- Include global font CSS -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50" lang="{{ app()->getLocale() }}">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <!-- Header -->
        <x-header-menu />

        <!-- Page Heading -->
        @yield('header')

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} REAX CRM. {{ __('All rights reserved.') }}
                </div>
            </div>
        </footer>
    </div>
    
    @stack('scripts')
    @include('components.layouts.alert-scripts')
</body>
</html>
