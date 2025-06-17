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
            <!-- User Administration -->
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-red-100 rounded-full p-3">
                                <i class="fas fa-users-cog text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('User Administration') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Manage users, roles and permissions') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('administration.index') }}" class="block text-red-600 hover:text-red-800 text-sm font-medium">
                            {{ __('Admin Dashboard') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('administration.users.index') }}" class="block text-red-600 hover:text-red-800 text-sm font-medium">
                            {{ __('All Users') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('administration.profiles.index') }}" class="block text-red-600 hover:text-red-800 text-sm font-medium">
                            {{ __('User Profiles') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('administration.role-management.index') }}" class="block text-red-600 hover:text-red-800 text-sm font-medium">
                            {{ __('Role Management') }} <i class="fas fa-arrow-right ml-1"></i>
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
                            <p class="text-sm text-gray-500">{{ __('Manage properties and listings') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('properties.index') }}" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            {{ __('All Properties') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('properties.create') }}" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            {{ __('Add New Property') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('administration.property.settings') }}" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            {{ __('Property Settings') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Configuration -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-cogs text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('System Configuration') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Configure global settings and preferences') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('administration.settings') }}" class="block text-blue-600 hover:text-blue-800 text-sm font-medium">
                            {{ __('Global Settings') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('administration.backup') }}" class="block text-blue-600 hover:text-blue-800 text-sm font-medium">
                            {{ __('Backup & Restore') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('administration.logs') }}" class="block text-blue-600 hover:text-blue-800 text-sm font-medium">
                            {{ __('System Logs') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

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
                            <p class="text-sm text-gray-500">{{ __('View and generate reports') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('reports.property') }}" class="block text-purple-600 hover:text-purple-800 text-sm font-medium">
                            {{ __('Property Reports') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('reports.sales') }}" class="block text-purple-600 hover:text-purple-800 text-sm font-medium">
                            {{ __('Sales Reports') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('reports.custom') }}" class="block text-purple-600 hover:text-purple-800 text-sm font-medium">
                            {{ __('Custom Reports') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('System Status') }}</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">{{ __('Database') }}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ __('Connected') }}</span>
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
    </div>
</body>
</html>
