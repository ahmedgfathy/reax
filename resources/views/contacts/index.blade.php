<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Contacts Management') }}</h2>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Total Contacts -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-address-book text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Total Contacts') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('All contacts') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['total_contacts'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Contacts -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-600/30 rounded-full p-3">
                            <i class="fas fa-user-check text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Active Contacts') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Recently active') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['active_contacts'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Companies -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-700/30 rounded-full p-3">
                            <i class="fas fa-building text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Associated Companies') }}</h3>
                        <p class="text-sm text-blue-300">{{ __('Linked companies') }}</p>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-white">{{ $stats['companies_count'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Types -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-600/30 rounded-full p-3">
                            <i class="fas fa-tags text-blue-200 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <h3 class="text-lg font-medium text-blue-100">{{ __('Contact Types') }}</h3>
                        <div class="mt-3 space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-blue-300">{{ __('Clients') }}:</span>
                                <span class="text-white font-medium">{{ $stats['client_contacts'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-300">{{ __('Prospects') }}:</span>
                                <span class="text-white font-medium">{{ $stats['prospect_contacts'] }}</span>
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
            <form action="{{ route('contacts.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="{{ __('Search contacts...') }}">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Company Filter -->
                    <div class="relative">
                        <select name="company_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Companies') }}</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Type Filter -->
                    <div class="relative">
                        <select name="type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="client" {{ request('type') == 'client' ? 'selected' : '' }}>{{ __('Client') }}</option>
                            <option value="prospect" {{ request('type') == 'prospect' ? 'selected' : '' }}>{{ __('Prospect') }}</option>
                            <option value="partner" {{ request('type') == 'partner' ? 'selected' : '' }}>{{ __('Partner') }}</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Sort Filter -->
                    <div class="relative">
                        <select name="sort" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Oldest First') }}</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('Name A-Z') }}</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __('Name Z-A') }}</option>
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
                        @if(request()->anyFilled(['search', 'company_id', 'type', 'sort']))
                            <a href="{{ route('contacts.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('contacts.create') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Contact') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Contacts List -->
    <div class="bg-white rounded-lg shadow-sm mt-6">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Company') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Phone') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($contacts as $contact)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($contact->avatar)
                                                <img class="h-10 w-10 rounded-full" src="{{ $contact->avatar_url }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-blue-600 font-medium text-sm">
                                                        {{ strtoupper(substr($contact->first_name, 0, 1)) }}{{ strtoupper(substr($contact->last_name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('contacts.show', $contact) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                                {{ $contact->first_name }} {{ $contact->last_name }}
                                            </a>
                                            <p class="text-sm text-gray-500">{{ $contact->title }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($contact->company)
                                        <a href="{{ route('companies.show', $contact->company) }}" class="text-sm text-gray-900 hover:text-blue-600">
                                            {{ $contact->company->name }}
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $contact->type == 'client' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $contact->type == 'prospect' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $contact->type == 'partner' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ __(ucfirst($contact->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="mailto:{{ $contact->email }}" class="text-sm text-gray-900 hover:text-blue-600">
                                        {{ $contact->email }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $contact->phone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('contacts.show', $contact) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('contacts.edit', $contact) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure you want to delete this contact?') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('No contacts found matching your criteria.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
