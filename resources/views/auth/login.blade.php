<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Login') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Cairo:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} font-{{ app()->getLocale() == 'ar' ? 'Cairo' : 'Roboto' }}">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-center mb-6">
                <div class="text-center mx-2">
                    <img src="https://img.icons8.com/color/48/000000/android-os.png" alt="Android" class="w-8 h-8 mx-auto">
                    <p class="text-xs text-gray-700 mt-1">{{ __('Install on Android') }}</p>
                </div>
                <div class="text-center mx-2">
                    <img src="https://img.icons8.com/color/48/000000/windows-10.png" alt="Windows" class="w-8 h-8 mx-auto">
                    <p class="text-xs text-gray-700 mt-1">{{ __('Install on Windows') }}</p>
                </div>
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Login') }}</h2>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">{{ __('Email') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded-md" required autofocus>
                    <label>{{ __('username it\'s : admin@example.com') }}</label>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">{{ __('Password') }}</label>
                    <input type="password" id="password" name="password" class="w-full p-2 border rounded-md" required>
                    <label>{{ __('password it\'s : password') }}</label>
                </div>
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <input type="checkbox" id="remember" name="remember" class="mr-2">
                        <label for="remember" class="text-gray-700">{{ __('Remember Me') }}</label>
                    </div>
                    <a href="#" class="text-blue-600 hover:underline">{{ __('Forgot Your Password?') }}</a>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">{{ __('Login') }}</button>
            </form>
            <p class="mt-4 text-center text-gray-700">{{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="text-blue-600 hover:underline">{{ __('Register') }}</a></p>
            <p class="mt-2 text-center text-gray-700">{{ __("Or") }} <a href="{{ url('/') }}" class="text-blue-600 hover:underline">{{ __('Go to Home') }}</a></p>
        </div>
    </div>
</body>
</html>
