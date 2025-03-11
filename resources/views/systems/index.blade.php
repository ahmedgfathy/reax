<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Systems Management') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    @include('components.layouts.alert-scripts')
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Systems Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Systems Management') }}</h1>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-6">
        <!-- Systems Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6">
            <!-- CRM System -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-users-gear text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('CRM System') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Manage customer relationships') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('leads.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            {{ __('Access CRM') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Property Management -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-building text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Property Management') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Manage real estate properties') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('properties.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                            {{ __('Access Properties') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reports & Analytics -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-purple-100 rounded-full p-3">
                                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Reports & Analytics') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('View insights and reports') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('reports.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                            {{ __('Access Reports') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('System Status') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">{{ __('Database') }}</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ __('Active') }}</span>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">{{ __('Storage') }}</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ __('Connected') }}</span>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">{{ __('Cache') }}</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ __('Optimized') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
