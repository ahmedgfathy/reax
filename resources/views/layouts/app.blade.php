<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">
<head>
    @include('partials.head')
</head>
<body class="antialiased bg-gray-100 min-h-screen flex flex-col">
    @include('partials.header')
    
    <main class="flex-grow">
        {{ $slot }}
    </main>

    @include('partials.footer')
</body>
</html>
