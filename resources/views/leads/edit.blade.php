<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Edit Lead') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Edit Lead') }}</h1>
                <a href="{{ route('leads.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Leads') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Personal Information') }}</h3>
                        
                        <div class="mb-4">
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('First Name') }}</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $lead->first_name) }}" class="w-full p-2 border rounded-md @error('first_name') border-red-500 @enderror" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Last Name') }}</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $lead->last_name) }}" class="w-full p-2 border rounded-md @error('last_name') border-red-500 @enderror" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full p-2 border rounded-md @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $lead->phone) }}" class="w-full p-2 border rounded-md @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="source" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Source') }}</label>
                            <select id="source" name="source" class="w-full p-2 border rounded-md @error('source') border-red-500 @enderror">
                                <option value="">{{ __('Select Source') }}</option>
                                <option value="website" {{ old('source', $lead->source) == 'website' ? 'selected' : '' }}>{{ __('Website') }}</option>
                                <option value="referral" {{ old('source', $lead->source) == 'referral' ? 'selected' : '' }}>{{ __('Referral') }}</option>
                                <option value="social media" {{ old('source', $lead->source) == 'social media' ? 'selected' : '' }}>{{ __('Social Media') }}</option>
                                <option value="direct" {{ old('source', $lead->source) == 'direct' ? 'selected' : '' }}>{{ __('Direct') }}</option>
                                <option value="advertisement" {{ old('source', $lead->source) == 'advertisement' ? 'selected' : '' }}>{{ __('Advertisement') }}</option>
                                <option value="other" {{ old('source', $lead->source) == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                            @error('source')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Lead Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Lead Information') }}</h3>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                            <select id="status" name="status" class="w-full p-2 border rounded-md @error('status') border-red-500 @enderror" required>
                                <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                                <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>{{ __('Contacted') }}</option>
                                <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>{{ __('Qualified') }}</option>
                                <option value="proposal" {{ old('status', $lead->status) == 'proposal' ? 'selected' : '' }}>{{ __('Proposal') }}</option>
                                <option value="negotiation" {{ old('status', $lead->status) == 'negotiation' ? 'selected' : '' }}>{{ __('Negotiation') }}</option>
                                <option value="won" {{ old('status', $lead->status) == 'won' ? 'selected' : '' }}>{{ __('Won') }}</option>
                                <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>{{ __('Lost') }}</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="property_interest" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Property Interest') }}</label>
                            <select id="property_interest" name="property_interest" class="w-full p-2 border rounded-md @error('property_interest') border-red-500 @enderror">
                                <option value="">{{ __('None') }}</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_interest', $lead->property_interest) == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                                @endforeach
                            </select>
                            @error('property_interest')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Budget') }}</label>
                            <input type="number" id="budget" name="budget" value="{{ old('budget', $lead->budget) }}" class="w-full p-2 border rounded-md @error('budget') border-red-500 @enderror">
                            @error('budget')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Assigned To') }}</label>
                            <select id="assigned_to" name="assigned_to" class="w-full p-2 border rounded-md @error('assigned_to') border-red-500 @enderror">
                                <option value="">{{ __('Not Assigned') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Notes') }}</label>
                            <textarea id="notes" name="notes" rows="4" class="w-full p-2 border rounded-md @error('notes') border-red-500 @enderror">{{ old('notes', $lead->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New fields -->
                        <div class="mb-4">
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mobile') }}</label>
                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $lead->mobile) }}" class="w-full p-2 border rounded-md">
                        </div>
                        
                        <div class="mb-4">
                            <label for="lead_source" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Lead Source Detail') }}</label>
                            <input type="text" name="lead_source" id="lead_source" value="{{ old('lead_source', $lead->lead_source) }}" class="w-full p-2 border rounded-md">
                        </div>
                        
                        <div class="mb-4">
                            <label for="lead_status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Lead Status Detail') }}</label>
                            <input type="text" name="lead_status" id="lead_status" value="{{ old('lead_status', $lead->lead_status) }}" class="w-full p-2 border rounded-md">
                        </div>
                        
                        <div class="mb-4">
                            <label for="last_follow_up" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Last Follow-up Date') }}</label>
                            <input type="date" name="last_follow_up" id="last_follow_up" value="{{ old('last_follow_up', $lead->last_follow_up ? $lead->last_follow_up->format('Y-m-d') : '') }}" class="w-full p-2 border rounded-md">
                        </div>
                        
                        <div class="mb-4">
                            <label for="agent_follow_up" class="flex items-center">
                                <input type="checkbox" name="agent_follow_up" id="agent_follow_up" value="1" {{ old('agent_follow_up', $lead->agent_follow_up) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ __('Requires Agent Follow-up') }}</span>
                            </label>
                        </div>
                        
                        <div class="mb-4">
                            <label for="lead_classification_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Lead Class') }}</label>
                            <select name="lead_classification_id" id="lead_classification_id" class="w-full p-2 border rounded-md">
                                <option value="">{{ __('Select a class') }}</option>
                                @foreach(\App\Models\LeadClassification::where('is_active', true)->orderBy('priority')->get() as $classification)
                                    <option value="{{ $classification->id }}" {{ old('lead_classification_id', $lead->lead_classification_id) == $classification->id ? 'selected' : '' }}>
                                        {{ $classification->code }} - {{ __($classification->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="type_of_request" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Type of Request') }}</label>
                            <select name="type_of_request" id="type_of_request" class="w-full p-2 border rounded-md">
                                <option value="">{{ __('Select request type') }}</option>
                                <option value="information" {{ old('type_of_request', $lead->type_of_request) == 'information' ? 'selected' : '' }}>{{ __('Information') }}</option>
                                <option value="viewing" {{ old('type_of_request', $lead->type_of_request) == 'viewing' ? 'selected' : '' }}>{{ __('Property Viewing') }}</option>
                                <option value="quotation" {{ old('type_of_request', $lead->type_of_request) == 'quotation' ? 'selected' : '' }}>{{ __('Quotation') }}</option>
                                <option value="contract" {{ old('type_of_request', $lead->type_of_request) == 'contract' ? 'selected' : '' }}>{{ __('Contract') }}</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                            <textarea name="description" id="description" rows="3" class="w-full p-2 border rounded-md">{{ old('description', $lead->description) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 border-t pt-4 flex justify-end space-x-3">
                    <a href="{{ route('leads.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                        {{ __('Update Lead') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
