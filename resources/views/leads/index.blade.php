<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Leads Management') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Leads Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Leads Management') }}</h1>
                <div class="flex space-x-2">
                    <!-- Import Button -->
                    <button onclick="document.getElementById('import-modal').classList.remove('hidden')" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-file-import mr-2"></i> {{ __('Import') }}
                    </button>
                    
                    <!-- Export Button -->
                    <button onclick="document.getElementById('export-modal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-file-export mr-2"></i> {{ __('Export') }}
                    </button>
                    
                    <!-- Add Lead Button -->
                    <a href="{{ route('leads.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <i class="fas fa-plus mr-2"></i> {{ __('Add Lead') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Lead Filters -->
    <div class="container mx-auto p-6">
        <form action="{{ route('leads.index') }}" method="GET" class="mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm flex flex-wrap items-center gap-4">
                <div class="flex-grow">
                    <input type="text" name="search" placeholder="{{ __('Search leads...') }}" 
                           value="{{ request('search') }}"
                           class="w-full p-2 border rounded-md">
                </div>
                <div>
                    <select name="status" class="p-2 border rounded-md" onchange="this.form.submit()">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>{{ __('Contacted') }}</option>
                        <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>{{ __('Qualified') }}</option>
                        <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>{{ __('Proposal') }}</option>
                        <option value="negotiation" {{ request('status') == 'negotiation' ? 'selected' : '' }}>{{ __('Negotiation') }}</option>
                        <option value="won" {{ request('status') == 'won' ? 'selected' : '' }}>{{ __('Won') }}</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>{{ __('Lost') }}</option>
                    </select>
                </div>
                <div>
                    <select name="source" class="p-2 border rounded-md" onchange="this.form.submit()">
                        <option value="">{{ __('All Sources') }}</option>
                        @foreach($sources as $source)
                            <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ __(ucfirst($source)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="order_by" class="p-2 border rounded-md" onchange="this.form.submit()">
                        <option value="created_at" {{ request('order_by') == 'created_at' ? 'selected' : '' }}>{{ __('Date Created') }}</option>
                        <option value="first_name" {{ request('order_by') == 'first_name' ? 'selected' : '' }}>{{ __('Name') }}</option>
                        <option value="status" {{ request('order_by') == 'status' ? 'selected' : '' }}>{{ __('Status') }}</option>
                        <option value="budget" {{ request('order_by') == 'budget' ? 'selected' : '' }}>{{ __('Budget') }}</option>
                    </select>
                </div>
                <div>
                    <select name="order_direction" class="p-2 border rounded-md" onchange="this.form.submit()">
                        <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}</option>
                        <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}</option>
                    </select>
                </div>
                <!-- New per_page selector -->
                <div>
                    <select name="per_page" class="p-2 border rounded-md" onchange="this.form.submit()">
                        <option value="25" {{ request('per_page') == '25' || !request('per_page') ? 'selected' : '' }}>{{ __('25 per page') }}</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>{{ __('50 per page') }}</option>
                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>{{ __('100 per page') }}</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 text-white p-2 rounded-md">
                        <i class="fas fa-search mr-1"></i> {{ __('Search') }}
                    </button>
                    @if(request('search') || request('status') || request('source') || request('order_by') || request('order_direction'))
                        <a href="{{ route('leads.index') }}" class="bg-gray-500 text-white p-2 rounded-md inline-block ml-2">
                            <i class="fas fa-times mr-1"></i> {{ __('Reset') }}
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Bulk Actions Form -->
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
                            <th class="text-left py-3 px-4 font-semibold text-sm text-gray-600">{{ __('Property Interest') }}</th>
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
                                        <div class="font-medium text-gray-800">{{ $lead->first_name }} {{ $lead->last_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $lead->source }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm">{{ $lead->email }}</div>
                                        <div class="text-xs text-gray-500">{{ $lead->phone }}</div>
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
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        {{ $lead->interestedProperty ? $lead->interestedProperty->name : 'N/A' }}
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

            // Checkbox selection handling
            const selectAll = document.getElementById('select-all');
            const leadCheckboxes = document.querySelectorAll('.lead-checkbox');
            const bulkActionsToolbar = document.getElementById('bulk-actions-toolbar');
            const selectedCountElement = document.getElementById('selected-count');

            // Select all checkbox
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    leadCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            // Individual lead checkboxes
            leadCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedCount();
                    
                    // Update the "select all" checkbox state
                    if (!this.checked) {
                        selectAll.checked = false;
                    } else if (areAllChecked()) {
                        selectAll.checked = true;
                    }
                });
            });

            // Helper function to check if all checkboxes are checked
            function areAllChecked() {
                return Array.from(leadCheckboxes).every(checkbox => checkbox.checked);
            }

            // Update selected count and show/hide toolbar
            function updateSelectedCount() {
                const checkedCount = document.querySelectorAll('.lead-checkbox:checked').length;
                selectedCountElement.textContent = checkedCount + ' ' + (checkedCount === 1 ? '{{ __("selected") }}' : '{{ __("selected") }}');
                
                if (checkedCount > 0) {
                    bulkActionsToolbar.classList.remove('hidden');
                } else {
                    bulkActionsToolbar.classList.add('hidden');
                }
            }

            // Deselect all leads
            function deselectAll() {
                document.getElementById('select-all').checked = false;
                document.querySelectorAll('.lead-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                document.getElementById('bulk-actions-toolbar').classList.add('hidden');
            }

            // Show transfer modal
            function showTransferModal() {
                document.getElementById('transfer-modal').classList.remove('hidden');
            }

            // Process transfer action
            function transferLeads() {
                const userId = document.getElementById('transfer-to-user').value;
                if (!userId) {
                    alert('{{ __("Please select a user to transfer leads to.") }}');
                    return;
                }
                document.getElementById('bulk-action-input').value = 'transfer';
                document.getElementById('bulk-assign-input').value = userId;
                document.getElementById('bulk-action-form').submit();
            }

            // Confirm and process delete action
            function confirmDelete() {
                if (confirm('{{ __("Are you sure you want to delete the selected leads?") }}')) {
                    document.getElementById('bulk-action-input').value = 'delete';
                    document.getElementById('bulk-action-form').submit();
                }
            }

            // Additional JavaScript for import/export functionality
            const fileInput = document.getElementById('file-upload');
            const fileNameDisplay = document.getElementById('file-name');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        fileNameDisplay.textContent = this.files[0].name;
                        fileNameDisplay.classList.remove('hidden');
                    } else {
                        fileNameDisplay.classList.add('hidden');
                    }
                });
            }

            // Handle format selection in export modal
            const formatRadios = document.querySelectorAll('input[name="format"]');
            if (formatRadios.length) {
                formatRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Remove highlight from all labels
                        document.querySelectorAll('input[name="format"]').forEach(r => {
                            r.closest('label').querySelector('div').classList.remove('bg-blue-600');
                        });
                        
                        // Add highlight to selected label
                        if (this.checked) {
                            this.closest('label').querySelector('div').classList.add('bg-blue-600');
                        }
                    });
                });
            }

            // Update selected count in export modal
            function updateSelectedExportCount() {
                const selectedLeads = Array.from(document.querySelectorAll('.lead-checkbox:checked')).map(cb => cb.value);
                document.getElementById('selected-count-number').textContent = selectedLeads.length;
                document.getElementById('selected_leads_export').value = selectedLeads.join(',');
                
                // Disable selected option if no leads are selected
                const exportSelectedRadio = document.getElementById('export_selected');
                if (selectedLeads.length === 0) {
                    exportSelectedRadio.disabled = true;
                    exportSelectedRadio.checked = false;
                    document.getElementById('export_all').checked = true;
                } else {
                    exportSelectedRadio.disabled = false;
                }
            }

            // Update export count when checkboxes change
            document.querySelectorAll('.lead-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedExportCount);
            });

            // Update on "select all" changes too
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', updateSelectedExportCount);
            }

            // Initial update
            updateSelectedExportCount();
        });
    </script>
</body>
</html>
