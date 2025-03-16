<x-app-layout>
    <x-slot name="header">
        // ...similar header and breadcrumbs as branches...
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Add Department Button -->
            <div class="mb-2 flex justify-end">
            // ...similar button code as branches...
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow-sm mb-4">
                // ...similar search form as branches...
            </div>

            <!-- Departments List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th>{{ __('Department Name') }}</th>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Parent Department') }}</th>
                                <th>{{ __('Manager') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            // ...similar row structure as branches...
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
