<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Edit Employee') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    @include('components.layouts.alert-scripts')
    @include('components.header-menu')

    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto py-4 px-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">{{ __('Edit Employee') }}</h1>
                <a href="{{ route('administration.employees.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('administration.employees.update', $employee) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Full Name') }} *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }} *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2">{{ __('Active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <button type="button" onclick="history.back()" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md mr-2">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ __('Update Employee') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
