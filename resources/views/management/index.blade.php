<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Management') }}</h2>
    </x-slot>

    <div class="container mx-auto p-6">
        <!-- Management Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6">
            <!-- Team Management -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-indigo-100 rounded-full p-3">
                                <i class="fas fa-users text-indigo-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Team Management') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Manage team members and roles') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            {{ __('Manage Teams') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Department Management -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-building-user text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Departments') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Manage departments and structure') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            {{ __('Manage Departments') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Branch Management -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-code-branch text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Branch Offices') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Manage branch locations') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium">
                            {{ __('Manage Branches') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
