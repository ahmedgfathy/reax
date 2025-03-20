<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Register') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Register') }}</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Company Information -->
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Company Information') }}</h3>
                    <div class="mb-4">
                        <label for="company_name" class="block text-gray-700">{{ __('Company Name') }}</label>
                        <input type="text" id="company_name" name="company_name" class="w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="company_email" class="block text-gray-700">{{ __('Company Email') }}</label>
                        <input type="email" id="company_email" name="company_email" class="w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="company_phone" class="block text-gray-700">{{ __('Company Phone') }}</label>
                        <input type="text" id="company_phone" name="company_phone" class="w-full p-2 border rounded-md">
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Personal Information') }}</h3>
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">{{ __('Name') }}</label>
                        <input type="text" id="name" name="name" class="w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email" class="w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700">{{ __('Password') }}</label>
                        <input type="password" id="password" name="password" class="w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-gray-700">{{ __('Confirm Password') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border rounded-md" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">{{ __('Register') }}</button>
            </form>
            <p class="mt-4 text-center text-gray-700">{{ __('Already have an account?') }} <a href="{{ route('login') }}" class="text-blue-600 hover:underline">{{ __('Login') }}</a></p>
        </div>
    </div>
</body>
</html>
