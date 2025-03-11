<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    @include('components.layouts.alert-scripts')
    
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
        <!-- Fixed Sidebar -->
        <div class="fixed left-0 top-28 bottom-0"> <!-- Positioned below fixed header -->
            @include('layouts.sidebar')
        </div>

        <!-- Scrollable Main Content -->
        <div class="flex-1 ml-64 p-6 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</body>
</html>
