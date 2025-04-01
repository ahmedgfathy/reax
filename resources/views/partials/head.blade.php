<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name') }}</title>

<!-- SEO Meta Tags -->
<meta property="og:title" content="{{ $title ?? config('app.name') }}" />
<meta property="og:description" content="Discover the best real estate deals with our powerful CRM!" />
<meta property="og:type" content="website" />

<!-- Styles -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">

<style>
    :root {
        --font-arabic: 'Cairo', sans-serif;
        --font-english: 'Roboto', sans-serif;
    }
    
    body {
        font-family: {{ app()->getLocale() == 'ar' ? 'var(--font-arabic)' : 'var(--font-english)' }};
    }

    [dir="rtl"] {
        /* RTL specific styles */
        text-align: right;
    }

    .login-button {
        /* Login button styles from home page */
        @apply bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md font-medium transition-colors;
    }
</style>

@stack('styles')
