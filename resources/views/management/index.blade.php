<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">
                        {{ __('Dashboard') }}
                    </a>
                    <span class="px-2">/</span>
                    <span class="text-gray-700">{{ __('Management') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Total Teams -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-users text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Total Teams') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Active teams') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">
                                {{ Auth::user()->company->teams()->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Departments -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-600/30 rounded-full p-3">
                            <i class="fas fa-building text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Departments') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Active departments') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">
                                {{ App\Models\Department::where('company_id', Auth::user()->company_id)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch Offices -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-code-branch text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Branch Offices') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Active locations') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">
                                {{ App\Models\Branch::where('company_id', Auth::user()->company_id)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Staff -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-600/30 rounded-full p-3">
                            <i class="fas fa-user-tie text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Total Staff') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Active employees') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">
                                {{ App\Models\User::where('company_id', Auth::user()->company_id)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Modules -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Team Management Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-indigo-100 rounded-full p-3">
                            <i class="fas fa-users text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Team Management') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Manage team members and roles') }}</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <a href="#" class="block px-4 py-2 bg-gray-50 text-sm text-gray-700 rounded-md hover:bg-gray-100">
                        <i class="fas fa-users-cog mr-2 text-indigo-500"></i>
                        {{ __('Team Settings') }}
                    </a>
                    <a href="#" class="block px-4 py-2 bg-gray-50 text-sm text-gray-700 rounded-md hover:bg-gray-100">
                        <i class="fas fa-user-plus mr-2 text-indigo-500"></i>
                        {{ __('Add Member') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Department Management Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-building text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Departments') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Manage departments and structure') }}</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <a href="#" class="block px-4 py-2 bg-gray-50 text-sm text-gray-700 rounded-md hover:bg-gray-100">
                        <i class="fas fa-sitemap mr-2 text-blue-500"></i>
                        {{ __('Department List') }}
                    </a>
                    <a href="#" class="block px-4 py-2 bg-gray-50 text-sm text-gray-700 rounded-md hover:bg-gray-100">
                        <i class="fas fa-plus mr-2 text-blue-500"></i>
                        {{ __('Add Department') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Branch Management Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-code-branch text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Branch Offices') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Manage branch locations') }}</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('branches.index') }}" class="block px-4 py-2 bg-gray-50 text-sm text-gray-700 rounded-md hover:bg-gray-100">
                        <i class="fas fa-list mr-2 text-green-500"></i>
                        {{ __('Branch List') }}
                    </a>
                    <a href="{{ route('branches.create') }}" class="block px-4 py-2 bg-gray-50 text-sm text-gray-700 rounded-md hover:bg-gray-100">
                        <i class="fas fa-plus mr-2 text-green-500"></i>
                        {{ __('Add Branch') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
