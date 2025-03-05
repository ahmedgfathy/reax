@extends('layouts.app')

@section('content')
<div class="bg-white shadow-sm border-b">
    <div class="container mx-auto py-4 px-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Reports') }}</h1>
            <a href="{{ route('reports.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                <i class="fas fa-plus mr-2"></i> {{ __('Create Report') }}
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto p-6">
    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-grow">
                <input type="text" name="search" placeholder="{{ __('Search reports...') }}" 
                       value="{{ request('search') }}"
                       class="w-full p-2 border rounded-md">
            </div>
            <div>
                <select name="source" class="p-2 border rounded-md" onchange="this.form.submit()">
                    <option value="">{{ __('All Sources') }}</option>
                    <option value="leads" {{ request('source') == 'leads' ? 'selected' : '' }}>{{ __('Leads') }}</option>
                    <option value="properties" {{ request('source') == 'properties' ? 'selected' : '' }}>{{ __('Properties') }}</option>
                    <option value="both" {{ request('source') == 'both' ? 'selected' : '' }}>{{ __('Combined') }}</option>
                </select>
            </div>
            <div>
                <select name="filter" class="p-2 border rounded-md" onchange="this.form.submit()">
                    <option value="">{{ __('All Reports') }}</option>
                    <option value="my-reports" {{ request('filter') == 'my-reports' ? 'selected' : '' }}>{{ __('My Reports') }}</option>
                    <option value="public" {{ request('filter') == 'public' ? 'selected' : '' }}>{{ __('Public Reports') }}</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white p-2 rounded-md">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search') || request('source') || request('filter'))
                    <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white p-2 rounded-md inline-block ml-2">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $report)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-bold text-gray-800">
                            <a href="{{ route('reports.show', $report->id) }}" class="hover:text-blue-600">
                                {{ $report->name }}
                            </a>
                        </h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $report->data_source == 'leads' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $report->data_source == 'properties' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $report->data_source == 'both' ? 'bg-purple-100 text-purple-700' : '' }}
                        ">
                            {{ __($report->data_source) }}
                        </span>
                    </div>
                    
                    @if($report->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $report->description }}</p>
                    @endif
                    
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            {{ $report->creator->name }}
                        </div>
                        <div>
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $report->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3 border-t flex justify-between">
                    <div class="flex space-x-2">
                        <a href="{{ route('reports.show', $report->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-chart-bar"></i>
                        </a>
                        @if($report->created_by === auth()->id())
                            <a href="{{ route('reports.edit', $report->id) }}" class="text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this report?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="flex space-x-3">
                        <span class="text-gray-500" title="{{ __('Access Level') }}">
                            <i class="fas fa-{{ $report->is_public ? 'globe' : ($report->access_level == 'team' ? 'users' : 'lock') }}"></i>
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-8 text-center text-gray-500">
                <i class="fas fa-chart-pie text-4xl mb-3 opacity-30"></i>
                <p>{{ __('No reports found matching your criteria.') }}</p>
                <a href="{{ route('reports.create') }}" class="inline-block mt-4 text-blue-600 hover:underline">
                    {{ __('Create your first report') }}
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-8">
        {{ $reports->links() }}
    </div>
</div>
@endsection
