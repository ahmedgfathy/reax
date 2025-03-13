<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Companies Management') }}</h2>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Total Companies -->
        <div class="bg-gradient-to-br from-indigo-900 to-indigo-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-indigo-700/30 rounded-full p-3">
                            <i class="fas fa-building text-indigo-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-indigo-100">{{ __('Total Companies') }}</h3>
                        <p class="text-sm text-indigo-300">{{ __('All companies') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['total_companies'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Companies -->
        <div class="bg-gradient-to-br from-indigo-800 to-indigo-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-indigo-600/30 rounded-full p-3">
                            <i class="fas fa-check-circle text-indigo-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-indigo-100">{{ __('Active Companies') }}</h3>
                        <p class="text-sm text-indigo-300">{{ __('Currently active') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['active_companies'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Contacts -->
        <div class="bg-gradient-to-br from-indigo-900 to-indigo-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-indigo-700/30 rounded-full p-3">
                            <i class="fas fa-users text-indigo-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-indigo-100">{{ __('Total Contacts') }}</h3>
                        <p class="text-sm text-indigo-300">{{ __('Associated contacts') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['total_contacts'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Companies -->
        <div class="bg-gradient-to-br from-indigo-800 to-indigo-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-indigo-600/30 rounded-full p-3">
                            <i class="fas fa-briefcase text-indigo-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-indigo-100">{{ __('Client Companies') }}</h3>
                        <p class="text-sm text-indigo-300">{{ __('With client contacts') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['client_companies'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <form action="{{ route('companies.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="{{ __('Search companies...') }}">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative">
                        <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Sort Filter -->
                    <div class="relative">
                        <select name="sort" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Oldest First') }}</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('Name A-Z') }}</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __('Name Z-A') }}</option>
                            <option value="contacts" {{ request('sort') == 'contacts' ? 'selected' : '' }}>{{ __('Most Contacts') }}</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-search"></i>
                            {{ __('Apply Filters') }}
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'sort']))
                            <a href="{{ route('companies.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('companies.create') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Company') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Companies List -->
    <div class="bg-white rounded-lg shadow-sm mt-6">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Company Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Contacts') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Phone') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($companies as $company)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($company->logo)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($company->logo) }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-indigo-600 font-medium text-lg">
                                                        {{ strtoupper(substr($company->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('companies.show', $company) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                                {{ $company->name }}
                                            </a>
                                            <p class="text-sm text-gray-500">{{ $company->address }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $company->contacts_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($company->email)
                                        <a href="mailto:{{ $company->email }}" class="text-sm text-gray-900 hover:text-indigo-600">
                                            {{ $company->email }}
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $company->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $company->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $company->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('companies.show', $company) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('companies.edit', $company) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure you want to delete this company?') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('No companies found matching your criteria.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
