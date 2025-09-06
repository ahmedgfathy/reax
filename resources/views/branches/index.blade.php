<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-2">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Branch Offices') }} - {{ $company->name }}
                </h2>
                <!-- Breadcrumbs -->
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                    <span class="px-2">/</span>
                    <a href="{{ route('management.index') }}" class="hover:text-gray-700">{{ __('Management') }}</a>
                    <span class="px-2">/</span>
                    <span class="text-gray-700">{{ __('Branch Offices') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Reduced py-4 to py-2 and mb-3 to mb-2 -->
    <div class="py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Add Branch Button - Reduced margin bottom -->
            <div class="mb-2 flex justify-end">
                <a href="{{ route('branches.create') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                    <i class="fas fa-plus text-xs mr-2"></i>
                    {{ __('Add Branch') }}
                </a>
            </div>

            <!-- Search and Filters - Reduced padding -->
            <div class="bg-white rounded-lg shadow-sm mb-4">
                <div class="p-3 border-b border-gray-200">
                    <form action="{{ route('branches.index') }}" method="GET">
                        <div class="flex items-center gap-4">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-md leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="{{ __('Search by branch name or code...') }}">
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('Search') }}
                                </button>
                                @if(request()->filled('search'))
                                    <a href="{{ route('branches.index') }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-times mr-2"></i>
                                        {{ __('Clear') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Branches List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Branch Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Code') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Location') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Contact') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Manager') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($branches) && $branches->count() > 0)
                                @foreach($branches as $branch)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100">
                                                    <i class="fas fa-building text-blue-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $branch->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $branch->created_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $branch->code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $branch->city }}</div>
                                            <div class="text-sm text-gray-500">{{ $branch->country }}</div>
                                            <div class="text-xs text-gray-400">{{ $branch->address }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $branch->phone }}</div>
                                            <div class="text-sm text-gray-500">{{ $branch->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($branch->manager_name)
                                                <div class="text-sm text-gray-900">{{ $branch->manager_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $branch->manager_phone }}</div>
                                                <div class="text-xs text-gray-400">{{ $branch->manager_email }}</div>
                                            @else
                                                <span class="text-sm text-gray-500">{{ __('No manager assigned') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $branch->is_active ? __('Active') : __('Inactive') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('branches.show', $branch) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('branches.edit', $branch) }}" class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('{{ __('Are you sure you want to delete this branch?') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="px-6 py-4">
                                        <div class="text-center text-gray-500">
                                            <p>{{ __('No branches found') }}</p>
                                            @if(app()->environment('local'))
                                                <div class="mt-2 text-xs text-left text-gray-400">
                                                    <p>Debug Info:</p>
                                                    <p>Company ID: {{ optional(auth()->user()->company)->id ?? 'None' }}</p>
                                                    <p>Total Branches: {{ \App\Models\Branch::count() }}</p>
                                                    <p>Company Branches: {{ \App\Models\Branch::where('company_id', optional(auth()->user()->company)->id)->count() }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($branches->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $branches->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
