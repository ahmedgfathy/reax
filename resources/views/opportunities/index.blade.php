<x-app-layout>
    <x-slot name="title">
        {{ __('Opportunities Management') }}
    </x-slot>

    <x-slot name="header">
        {{ __('Opportunities Management') }}
    </x-slot>

    <!-- Page Content -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6">
        <!-- Active Opportunities -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-handshake text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Active Opportunities') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Current active deals') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">24</span>
                            <span class="text-blue-300 text-sm ml-2">{{ __('Active deals') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Won Opportunities -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-600/30 rounded-full p-3">
                            <i class="fas fa-trophy text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Won Opportunities') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Successfully closed deals') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">156</span>
                            <span class="text-blue-300 text-sm ml-2">{{ __('Closed deals') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pipeline Value -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-chart-line text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Pipeline Value') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Total opportunity value') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">$2.4M</span>
                            <span class="text-blue-300 text-sm ml-2">{{ __('Total value') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Opportunity List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">{{ __('Recent Opportunities') }}</h2>
                <a href="#" class="bg-blue-900 text-blue-100 px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    {{ __('New Opportunity') }}
                </a>
            </div>
            
            <!-- Add your opportunity list table or grid here -->
        </div>
    </div>
</x-app-layout>
