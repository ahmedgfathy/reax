@extends('layouts.app')

@section('content')
<!-- Leads content -->
<div class="bg-gray-100 min-h-screen">
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Leads Management') }}@if($company) - {{ $company->name }}@endif</h1>
            <!-- Breadcrumbs -->
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-700">{{ __('Leads') }}</span>
            </nav>
        </div>
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6">
            <!-- Active Leads Card -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-blue-700/30 rounded-full p-3">
                                <i class="fas fa-user-check text-blue-200 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-blue-100">{{ __('Active Leads') }}</h3>
                            <p class="text-sm text-blue-300">{{ __('Current active leads') }}</p>
                            <div class="mt-3">
                                <span class="text-2xl font-bold text-white">{{ $stats['active'] }}</span>
                                <span class="text-blue-300 text-sm ml-2">{{ __('Active') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Converted/Won Leads Card -->
            <div class="bg-gradient-to-br from-blue-800 to-blue-700 overflow-hidden shadow-lg rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-blue-600/30 rounded-full p-3">
                                <i class="fas fa-exchange-alt text-blue-200 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-blue-100">{{ __('Won Leads') }}</h3>
                            <p class="text-sm text-blue-300">{{ __('Successfully converted') }}</p>
                            <div class="mt-3">
                                <span class="text-2xl font-bold text-white">{{ $stats['won'] }}</span>
                                <span class="text-blue-300 text-sm ml-2">{{ __('Won') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pipeline Value Card -->
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
                            <p class="text-sm text-blue-300">{{ __('Total budget value') }}</p>
                            <div class="mt-3">
                                <span class="text-2xl font-bold text-white">{{ number_format($stats['pipeline_value']) }}</span>
                                <span class="text-blue-300 text-sm ml-2">{{ __('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lead Filters -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('leads.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div>
                            <div class="relative">
                                <input type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="{{ __('Search leads...') }}">
                                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="relative">
                            <select name="status"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                                <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>{{ __('Contacted') }}</option>
                                <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>{{ __('Qualified') }}</option>
                                <option value="unqualified" {{ request('status') == 'unqualified' ? 'selected' : '' }}>{{ __('Unqualified') }}</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>

                        <!-- Source Filter -->
                        <div class="relative">
                            <select name="source"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">{{ __('All Sources') }}</option>
                                <option value="website" {{ request('source') == 'website' ? 'selected' : '' }}>{{ __('Website') }}</option>
                                <option value="referral" {{ request('source') == 'referral' ? 'selected' : '' }}>{{ __('Referral') }}</option>
                                <option value="social" {{ request('source') == 'social' ? 'selected' : '' }}>{{ __('Social Media') }}</option>
                                <option value="other" {{ request('source') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>

                        <!-- Sort By Filter -->
                        <div class="relative">
                            <select name="sort"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Oldest First') }}</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('Name A-Z') }}</option>
                                <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>{{ __('Priority') }}</option>
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
                            @if(request()->anyFilled(['search', 'status', 'source', 'sort']))
                                <a href="{{ route('leads.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors flex items-center gap-2">
                                    <i class="fas fa-times"></i>
                                    {{ __('Clear') }}
                                </a>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <!-- Import Button -->
                            <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')"
                                    class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="fas fa-file-import"></i>
                                {{ __('Import') }}
                            </button>

                            <!-- Export Button -->
                            <button type="button" onclick="document.getElementById('export-modal').classList.remove('hidden')"
                                    class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="fas fa-file-export"></i>
                                {{ __('Export') }}
                            </button>

                            <!-- Add Lead Button -->
                            <a href="{{ route('leads.create') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                {{ __('Add Lead') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ __('Leads List') }}</h2>
                </div>

                <div class="overflow-x-auto">
                    <form id="bulk-action-form" action="{{ route('leads.bulk-action') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="action" id="bulk-action-input">
                        <input type="hidden" name="assigned_to" id="bulk-assign-input">

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        @if(session('warning'))
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                                <p>{{ session('warning') }}</p>

                                @if(session('import_errors'))
                                    <div class="mt-2">
                                        <button type="button" class="text-yellow-800 underline"
                                                onclick="document.getElementById('import-errors').classList.toggle('hidden')">
                                            {{ __('Show/Hide Errors') }}
                                        </button>

                                        <ul id="import-errors" class="list-disc list-inside mt-2 text-sm text-yellow-800 hidden">
                                            @foreach(session('import_errors') as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Bulk Action Controls -->
                        <div id="bulk-actions-toolbar" class="bg-gray-100 p-3 mb-4 rounded-md hidden">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-700">{{ __('With selected:') }}</span>
                                <div class="flex-grow flex gap-2">
                                    <button type="button" onclick="showTransferModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-user-plus mr-1"></i> {{ __('Transfer') }}
                                    </button>
                                    <button type="button" onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                                    </button>
                                    <button type="button" onclick="deselectAll()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-times mr-1"></i> {{ __('Deselect All') }}
                                    </button>
                                </div>
                                <span id="selected-count" class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                    0 {{ __('selected') }}
                                </span>
                            </div>
                        </div>

                        <!-- Leads Table -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 border-b">
                                        <th class="w-12 px-4 py-3">
                                            <input type="checkbox" id="select-all" class="rounded text-blue-600 focus:ring-blue-500">
                                        </th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Name') }}</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Contact') }}</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Status') }}</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Class') }}</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Last Follow-up') }}</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Budget') }}</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Assigned To') }}</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($leads->count() > 0)
                                        @foreach ($leads as $lead)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" name="selected_leads[]" value="{{ $lead->id }}" class="lead-checkbox rounded text-blue-600 focus:ring-blue-500">
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="font-medium text-gray-800">
                                                        <a href="{{ route('leads.show', $lead->id) }}" class="hover:text-blue-600">
                                                            {{ $lead->first_name }} {{ $lead->last_name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $lead->source }} {{ $lead->lead_source ? "({$lead->lead_source})" : '' }}</div>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="text-sm">{{ $lead->email }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $lead->phone }}
                                                        @if($lead->mobile)
                                                            <span class="text-xs text-blue-600 ml-1">(M: {{ $lead->mobile }})</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 text-xs rounded-full
                                                        {{ $lead->status == 'new' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $lead->status == 'contacted' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                                        {{ $lead->status == 'qualified' ? 'bg-purple-100 text-purple-700' : '' }}
                                                        {{ $lead->status == 'proposal' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                        {{ $lead->status == 'negotiation' ? 'bg-orange-100 text-orange-700' : '' }}
                                                        {{ $lead->status == 'won' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ $lead->status == 'lost' ? 'bg-red-100 text-red-700' : '' }}
                                                    ">
                                                        {{ ucfirst($lead->status) }}
                                                    </span>
                                                    @if($lead->lead_status)
                                                        <div class="text-xs text-gray-500 mt-1">{{ $lead->lead_status }}</div>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if($lead->lead_class)
                                                        @if($lead->lead_class === 'A')
                                                            <span class="text-green-600 font-medium">A</span>
                                                        @elseif($lead->lead_class === 'B')
                                                            <span class="text-yellow-600 font-medium">B</span>
                                                        @elseif($lead->lead_class === 'C')
                                                            <span class="text-red-600 font-medium">C</span>
                                                        @else
                                                            {{ $lead->lead_class }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                    @if($lead->agent_follow_up)
                                                        <span class="bg-red-100 text-red-800 text-xs px-1 rounded ml-1">{{ __('Follow Up') }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-sm">
                                                    {{ $lead->last_follow_up ? $lead->last_follow_up->format('Y-m-d') : '-' }}
                                                </td>
                                                <td class="py-3 px-4 text-sm">
                                                    {{ $lead->budget ? number_format($lead->budget) : 'N/A' }}
                                                </td>
                                                <td class="py-3 px-4 text-sm">
                                                    {{ $lead->assignedUser ? $lead->assignedUser->name : 'Unassigned' }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center space-x-2">
                                                        <a href="{{ route('leads.show', $lead->id) }}" class="text-blue-600 hover:text-blue-900">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('leads.edit', $lead->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this lead?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="py-6 px-4 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-search text-4xl mb-3"></i>
                                                    <p>{{ __('No leads found matching your criteria.') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <!-- Fix the pagination display showing 0 -->
                            <!-- Pagination -->
                            <div class="px-6 py-4 border-t">
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-600">
                                        @if($leads->total() > 0)
                                            {{ __('Showing') }} {{ $leads->firstItem() }} {{ __('to') }} {{ $leads->lastItem() }} {{ __('of') }} {{ $leads->total() }} {{ __('leads') }}
                                        @else
                                            {{ __('No leads found') }}
                                        @endif
                                    </div>
                                    <div>
                                        {{ $leads->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transfer Modal -->
        <div id="transfer-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">{{ __('Transfer Leads') }}</h3>
                    <button onclick="document.getElementById('transfer-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4">
                    <p class="mb-4 text-gray-700">{{ __('Select a user to transfer selected leads to:') }}</p>
                    <div class="mb-4">
                        <label for="transfer-to-user" class="block text-sm font-medium text-gray-700">{{ __('Transfer to') }}</label>
                        <select id="transfer-to-user" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">{{ __('Select a user') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="button" onclick="document.getElementById('transfer-modal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md mr-2">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" onclick="transferLeads()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('Transfer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Modal -->
        <div id="import-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">{{ __('Import Leads') }}</h3>
                    <button onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data" class="p-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Upload CSV or Excel file') }}</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-file-excel text-gray-400 text-3xl mb-3"></i>
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>{{ __('Upload a file') }}</span>
                                    <input id="file-upload" name="file" type="file" accept=".csv, .xls, .xlsx" class="sr-only" required>
                                </label>
                                <p class="pl-1">{{ __('or drag and drop') }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ __('CSV, Excel files up to 10MB') }}
                                </p>
                                <div id="file-name" class="text-sm text-gray-800 font-medium mt-2 hidden"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Options') }}</label>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="header_row" name="header_row" value="1" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="header_row" class="ml-2 block text-sm text-gray-700">
                                    {{ __('File contains header row') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-6">
                        <a href="{{ asset('templates/leads_import_template.xlsx') }}" download class="text-blue-600 hover:underline text-sm mr-4">
                            <i class="fas fa-download mr-1"></i> {{ __('Download template') }}
                        </a>
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md mr-2">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md">
                            <i class="fas fa-file-import mr-2"></i> {{ __('Import') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export Modal -->
        <div id="export-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">{{ __('Export Leads') }}</h3>
                    <button onclick="document.getElementById('export-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('leads.export') }}" method="POST" class="p-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Select export format') }}</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="format" value="csv" class="sr-only" checked>
                                <i class="fas fa-file-csv text-gray-700 text-2xl"></i>
                                <span class="text-gray-900 font-medium">CSV</span>
                                <span class="text-xs text-gray-500">{{ __('Comma separated') }}</span>
                                <div class="w-full h-1 bg-blue-600 rounded-full mt-1"></div>
                            </label>

                            <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="format" value="xlsx" class="sr-only">
                                <i class="fas fa-file-excel text-gray-700 text-2xl"></i>
                                <span class="text-gray-900 font-medium">Excel</span>
                                <span class="text-xs text-gray-500">{{ __('XLSX format') }}</span>
                                <div class="w-full h-1 bg-blue-600 rounded-full mt-1"></div>
                            </label>

                            <label class="border rounded-md p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="format" value="pdf" class="sr-only">
                                <i class="fas fa-file-pdf text-gray-700 text-2xl"></i>
                                <span class="text-gray-900 font-medium">PDF</span>
                                <span class="text-xs text-gray-500">{{ __('Portable format') }}</span>
                                <div class="w-full h-1 bg-blue-600 rounded-full mt-1"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('What to export') }}</label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="export_all" name="export_scope" value="all" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="export_all" class="ml-2 block text-sm text-gray-700">
                                    {{ __('All leads') }}
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="export_filtered" name="export_scope" value="filtered" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="export_filtered" class="ml-2 block text-sm text-gray-700">
                                    {{ __('Current filtered results') }}
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="export_selected" name="export_scope" value="selected" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="export_selected" class="ml-2 block text-sm text-gray-700">
                                    {{ __('Only selected leads') }}
                                    <span id="selected-count-export" class="text-blue-600">(<span id="selected-count-number">0</span>)</span>
                                </label>
                            </div>
                            <input type="hidden" id="selected_leads_export" name="selected_leads">
                        </div>
                    </div>
                    <div class="text-right mt-6">
                        <button type="button" onclick="document.getElementById('export-modal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md mr-2">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">
                            <i class="fas fa-file-export mr-2"></i> {{ __('Export') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Live search functionality
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) {
                    searchInput.addEventListener('keyup', function(e) {
                        // Auto-submit form when typing after a small delay
                        clearTimeout(this.timer);
                        this.timer = setTimeout(() => {
                            this.form.submit();
                        }, 500);
                    });
                }

                // Checkbox handling for bulk actions
                const selectAll = document.getElementById('select-all');
                const leadCheckboxes = document.querySelectorAll('.lead-checkbox');
                const bulkActionsToolbar = document.getElementById('bulk-actions-toolbar');
                const selectedCountElement = document.getElementById('selected-count');

                // Select all checkbox functionality
                if (selectAll) {
                    selectAll.addEventListener('click', function() {
                        const isChecked = this.checked;

                        leadCheckboxes.forEach(checkbox => {
                            checkbox.checked = isChecked;
                        });

                        updateBulkActionToolbar();
                    });
                }

                // Individual checkboxes
                leadCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('click', function() {
                        updateSelectAllCheckbox();
                        updateBulkActionToolbar();
                    });
                });

                // Function to update select all checkbox
                function updateSelectAllCheckbox() {
                    if (leadCheckboxes.length > 0) {
                        const allChecked = Array.from(leadCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(leadCheckboxes).some(cb => cb.checked);

                        selectAll.checked = allChecked;
                        selectAll.indeterminate = someChecked && !allChecked;
                    }
                }

                // Function to update the bulk actions toolbar
                function updateBulkActionToolbar() {
                    const checkedCount = document.querySelectorAll('.lead-checkbox:checked').length;

                    // Update counter
                    selectedCountElement.textContent = checkedCount + ' ' +
                        (checkedCount === 1 ? '{{ __("selected") }}' : '{{ __("selected") }}');

                    // Show/hide toolbar
                    if (checkedCount > 0) {
                        bulkActionsToolbar.classList.remove('hidden');
                    } else {
                        bulkActionsToolbar.classList.add('hidden');
                    }

                    // Also update the count in the export modal
                    if (document.getElementById('selected-count-number')) {
                        document.getElementById('selected-count-number').textContent = checkedCount;
                    }

                    // Update hidden field with selected IDs for export
                    if (document.getElementById('selected_leads_export')) {
                        const selectedIds = Array.from(document.querySelectorAll('.lead-checkbox:checked'))
                            .map(cb => cb.value).join(',');
                        document.getElementById('selected_leads_export').value = selectedIds;
                    }
                }

                // Make functions accessible globally
                window.deselectAll = function() {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;

                    leadCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    updateBulkActionToolbar();
                };

                // Confirm delete function - Make sure this is being called correctly
                window.confirmDelete = function() {
                    const checkboxes = document.querySelectorAll('.lead-checkbox:checked');
                    const count = checkboxes.length;

                    if (count === 0) {
                        // Use a nicer alert for the warning
                        Swal.fire({
                            title: '{{ __("No Leads Selected") }}',
                            text: '{{ __("Please select at least one lead to delete.") }}',
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: '{{ __("Ok") }}'
                        });
                        return;
                    }

                    // Use our sweet alert confirmation
                    window.confirmBulkDelete(count, {
                        text: '{{ __("Are you sure you want to delete") }} ' + count + ' {{ __("selected leads? This action cannot be undone.") }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create a temporary form for submission
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("leads.bulk-action") }}';
                            form.style.display = 'none';

                            // Add CSRF token
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);

                            // Add action type
                            const actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action';
                            actionInput.value = 'delete';
                            form.appendChild(actionInput);

                            // Add each selected lead ID
                            checkboxes.forEach(function(checkbox) {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'selected_leads[]';
                                input.value = checkbox.value;
                                form.appendChild(input);
                            });

                            // Show loading state
                            Swal.fire({
                                title: '{{ __("Deleting...") }}',
                                html: '{{ __("Please wait while we delete the selected leads.") }}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Append form to document and submit
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                };

                // Transfer leads functions
                window.showTransferModal = function() {
                    const count = document.querySelectorAll('.lead-checkbox:checked').length;

                    if (count === 0) {
                        Swal.fire({
                            title: '{{ __("No Leads Selected") }}',
                            text: '{{ __("Please select at least one lead to transfer.") }}',
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: '{{ __("Ok") }}'
                        });
                        return;
                    }

                    document.getElementById('transfer-modal').classList.remove('hidden');
                };

                window.transferLeads = function() {
                    const checkboxes = document.querySelectorAll('.lead-checkbox:checked');
                    const count = checkboxes.length;
                    const userId = document.getElementById('transfer-to-user').value;

                    if (count === 0) {
                        Swal.fire({
                            title: '{{ __("No Leads Selected") }}',
                            text: '{{ __("Please select at least one lead to transfer.") }}',
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: '{{ __("Ok") }}'
                        });
                        return;
                    }

                    if (!userId) {
                        Swal.fire({
                            title: '{{ __("No User Selected") }}',
                            text: '{{ __("Please select a user to transfer leads to.") }}',
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: '{{ __("Ok") }}'
                        });
                        return;
                    }

                    // Get user name from the select element
                    const userSelect = document.getElementById('transfer-to-user');
                    const userName = userSelect.options[userSelect.selectedIndex].text;

                    // Confirm transfer
                    window.confirmDialog({
                        title: '{{ __("Confirm Transfer") }}',
                        text: `{{ __("Are you sure you want to transfer") }} ${count} {{ __("leads to") }} ${userName}?`,
                        icon: 'question',
                        confirmButtonText: '{{ __("Yes, transfer!") }}',
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create a temporary form for submission
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("leads.bulk-action") }}';
                            form.style.display = 'none';

                            // Add CSRF token
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);

                            // Add action type
                            const actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action';
                            actionInput.value = 'transfer';
                            form.appendChild(actionInput);

                            // Add user ID to transfer to
                            const userInput = document.createElement('input');
                            userInput.type = 'hidden';
                            userInput.name = 'assigned_to';
                            userInput.value = userId;
                            form.appendChild(userInput);

                            // Add each selected lead ID
                            checkboxes.forEach(function(checkbox) {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'selected_leads[]';
                                input.value = checkbox.value;
                                form.appendChild(input);
                            });

                            // Show loading state
                            Swal.fire({
                                title: '{{ __("Transferring...") }}',
                                html: '{{ __("Please wait while we transfer the selected leads.") }}',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Append form to document and submit
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            // Close the transfer modal if the user cancels
                            document.getElementById('transfer-modal').classList.add('hidden');
                        }
                    });
                };

                // Initialize on page load
                updateSelectAllCheckbox();
                updateBulkActionToolbar();
            });
        </script>

        <!-- Also update the delete buttons in the table rows -->
        <script>
            // Add this to your existing script section
            document.addEventListener('DOMContentLoaded', function() {
                // Replace all delete form submissions with SweetAlert2
                document.querySelectorAll('form[action^="{{ route("leads.destroy", "") }}"]').forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();

                        const leadName = this.closest('tr').querySelector('a[href^="{{ route("leads.show", "") }}"]').textContent.trim();

                        window.confirmDelete({
                            title: '{{ __("Delete Lead") }}',
                            text: `{{ __("Are you sure you want to delete") }} ${leadName}? {{ __("This action cannot be undone.") }}`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Show loading state
                                Swal.fire({
                                    title: '{{ __("Deleting...") }}',
                                    html: '{{ __("Please wait") }}',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                this.submit();
                            }
                        });
                    });
                });
            });
        </script>
    </div>
</div>
@endsection
