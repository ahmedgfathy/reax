<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Add New Lead') }}</title>
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
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Add New Lead') }}</h1>
                <a href="{{ route('leads.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Leads') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('leads.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Personal Information') }}</h3>
                        
                        <div class="mb-4">
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('First Name') }}</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="w-full p-2 border rounded-md @error('first_name') border-red-500 @enderror" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Last Name') }}</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="w-full p-2 border rounded-md @error('last_name') border-red-500 @enderror" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded-md @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full p-2 border rounded-md @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="source" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Source') }}</label>
                            <select id="source" name="source" class="w-full p-2 border rounded-md @error('source') border-red-500 @enderror">
                                <option value="">{{ __('Select Source') }}</option>
                                <option value="website" {{ old('source') == 'website' ? 'selected' : '' }}>{{ __('Website') }}</option>
                                <option value="referral" {{ old('source') == 'referral' ? 'selected' : '' }}>{{ __('Referral') }}</option>
                                <option value="social media" {{ old('source') == 'social media' ? 'selected' : '' }}>{{ __('Social Media') }}</option>
                                <option value="direct" {{ old('source') == 'direct' ? 'selected' : '' }}>{{ __('Direct') }}</option>
                                <option value="advertisement" {{ old('source') == 'advertisement' ? 'selected' : '' }}>{{ __('Advertisement') }}</option>
                                <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
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
                                <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                                <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>{{ __('Contacted') }}</option>
                                <option value="qualified" {{ old('status') == 'qualified' ? 'selected' : '' }}>{{ __('Qualified') }}</option>
                                <option value="proposal" {{ old('status') == 'proposal' ? 'selected' : '' }}>{{ __('Proposal') }}</option>
                                <option value="negotiation" {{ old('status') == 'negotiation' ? 'selected' : '' }}>{{ __('Negotiation') }}</option>
                                <option value="won" {{ old('status') == 'won' ? 'selected' : '' }}>{{ __('Won') }}</option>
                                <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>{{ __('Lost') }}</option>
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
                                    <option value="{{ $property->id }}" {{ old('property_interest') == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                                @endforeach
                            </select>
                            @error('property_interest')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Budget') }}</label>
                            <input type="number" id="budget" name="budget" value="{{ old('budget') }}" class="w-full p-2 border rounded-md @error('budget') border-red-500 @enderror">
                            @error('budget')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Assigned To') }}</label>
                            <select id="assigned_to" name="assigned_to" class="w-full p-2 border rounded-md @error('assigned_to') border-red-500 @enderror">
                                <option value="">{{ __('Not Assigned') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Notes') }}</label>
                            <textarea id="notes" name="notes" rows="4" class="w-full p-2 border rounded-md @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 border-t pt-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                        {{ __('Create Lead') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
