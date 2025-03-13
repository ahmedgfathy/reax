<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Opportunities Management') }}</h2>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Total Value Card -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-dollar-sign text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Total Value') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('All opportunities') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">${{ number_format($stats['total_value']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pipeline Value Card -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-600/30 rounded-full p-3">
                            <i class="fas fa-chart-line text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Pipeline Value') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Active opportunities') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">${{ number_format($stats['pipeline_value']) }}</span>
                            <span class="text-sm text-blue-300 ml-2">({{ $stats['pending_opportunities'] + $stats['negotiation_opportunities'] }} {{ __('deals') }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Win Rate Card -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-trophy text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Won Deals') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Success rate') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['won_opportunities'] }}</span>
                            <span class="text-sm text-blue-300 ml-2">({{ $stats['conversion_rate'] }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Overview Card -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-600/30 rounded-full p-3">
                            <i class="fas fa-chart-pie text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Status Overview') }}</h3>
                        <div class="mt-3 space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-blue-300">{{ __('Pending') }}:</span>
                                <span class="text-white font-medium">{{ $stats['pending_opportunities'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-300">{{ __('Negotiating') }}:</span>
                                <span class="text-white font-medium">{{ $stats['negotiation_opportunities'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-300">{{ __('Lost') }}:</span>
                                <span class="text-white font-medium">{{ $stats['lost_opportunities'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <form action="{{ route('opportunities.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="{{ __('Search opportunities...') }}">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Stage Filter -->
                    <div class="relative">
                        <select name="stage" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Stages') }}</option>
                            @foreach(['initial', 'qualified', 'proposal', 'negotiation'] as $stage)
                                <option value="{{ $stage }}" {{ request('stage') == $stage ? 'selected' : '' }}>
                                    {{ __(ucfirst($stage)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative">
                        <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Status') }}</option>
                            @foreach(['pending', 'negotiation', 'won', 'lost'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ __(ucfirst($status)) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Sort Filter -->
                    <div class="relative">
                        <select name="sort" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                            <option value="value_high" {{ request('sort') == 'value_high' ? 'selected' : '' }}>{{ __('Value: High to Low') }}</option>
                            <option value="value_low" {{ request('sort') == 'value_low' ? 'selected' : '' }}>{{ __('Value: Low to High') }}</option>
                            <option value="closing_soon" {{ request('sort') == 'closing_soon' ? 'selected' : '' }}>{{ __('Closing Soon') }}</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-search"></i>
                            {{ __('Apply Filters') }}
                        </button>
                        @if(request()->anyFilled(['search', 'stage', 'status', 'sort']))
                            <a href="{{ route('opportunities.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('opportunities.create') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Opportunity') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Opportunities List -->
    <div class="bg-white rounded-lg shadow-sm mt-6">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Lead') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Stage') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Value') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Close Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Assigned To') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($opportunities as $opportunity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('opportunities.show', $opportunity) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        {{ $opportunity->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($opportunity->lead)
                                        <a href="{{ route('leads.show', $opportunity->lead) }}" class="text-gray-900 hover:text-blue-600">
                                            {{ $opportunity->lead->full_name }}
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $opportunity->stage == 'initial' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $opportunity->stage == 'qualified' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $opportunity->stage == 'proposal' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $opportunity->stage == 'negotiation' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ __(ucfirst($opportunity->stage)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">${{ number_format($opportunity->value) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $opportunity->expected_close_date?->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $opportunity->assignedTo?->name ?? __('Unassigned') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('opportunities.show', $opportunity) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('opportunities.edit', $opportunity) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('opportunities.destroy', $opportunity) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure you want to delete this opportunity?') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('No opportunities found matching your criteria.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $opportunities->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
