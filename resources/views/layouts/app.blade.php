<!DOCTYPE htm    <meta p    <meta property="og:title" content="Real Estate CRM - REAX" />
    <meta property="og:description" content="REAX - Your complete real estate CRM solution for property management and lead tracking!" />
    <meta property="og:image" content="https://real.e-egar.com/images/og-image.jpg" />
    <meta property="og:url" content="https://real.e-egar.com" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="REAX Real Estate" />
    <meta property="og:locale" content="en_US" />
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="REAX Real Estate CRM">
    <meta name="twitter:description" content="REAX Discover the best real estate deals with our powerful CRM!">title" content="Real Estate CRM - Glomart" />
    <meta property="og:description" content="Glomart - Your complete real estate CRM solution for property management and lead tracking!" />
    <meta property="og:image" content="https://real.e-egar.com/images/og-image.jpg" />
    <meta property="og:url" content="https://real.e-egar.com" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Glomart Real Estate" />
    <meta property="og:locale" content="en_US" />
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Glomart Real Estate CRM">
    <meta name="twitter:description" content="Glomart - Discover the best real estate deals with our powerful CRM!">
    <meta name="twitter:image" content="https://real.e-egar.com/images/og-image.jpg">="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Preload critical fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SEO Meta Tags -->
    <meta property="og:title" content="Real Estate CRM - REAX" />
    <meta property="og:description" content="Glomart - Discover the best real estate deals with our powerful CRM!" />
    <meta property="og:image" content="https://real.e-egar.com/images/og-image.jpg" />
    <meta property="og:url" content="https://real.e-egar.com" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Glomart Real Estate" />
    <meta property="og:locale" content="en_US" />
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Glomart Real Estate CRM">
    <meta name="twitter:description" content="Glomart - Discover the best real estate deals with our powerful CRM!">
    <meta name="twitter:image" content="https://real.e-egar.com/images/og-image.jpg">

    <!-- Standard meta -->
    <meta name="description" content="Discover the best real estate deals with our powerful CRM!">

    <title>{{ $title ?? config('app.name') }}</title>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#10b981">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        /* Tailwind Config */
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': {
                            DEFAULT: '#10b981',
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
        
        /* Font Configuration */
        * {
            font-family: 'Inter', 'Roboto', sans-serif !important;
        }
        
        [dir="rtl"] *, html[lang="ar"] *, 
        [dir="rtl"], html[lang="ar"] {
            font-family: 'Cairo', sans-serif !important;
        }
        
        /* Background Gradients */
        .bg-gradient-main {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        }
        
        .bg-gradient-header {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.95) 0%, rgba(91, 33, 182, 0.95) 50%, rgba(76, 29, 149, 0.95) 100%);
        }
        
        /* Glass Effects */
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .glass-card {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(16, 185, 129, 0.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }
        
        /* Hover Effects */
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.15);
            transition: all 0.3s ease;
        }
        
        /* Accent Colors */
        .text-accent { color: #10b981; }
        .text-accent-dark { color: #047857; }
        .bg-accent { background-color: #10b981; }
        .bg-accent-500 { background-color: #10b981; }
        .bg-accent-600 { background-color: #059669; }
        .bg-accent-700 { background-color: #047857; }
        .hover\\:bg-accent-600:hover { background-color: #059669; }
        .hover\\:bg-accent-700:hover { background-color: #047857; }
        .border-accent { border-color: #10b981; }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.3); }
            50% { opacity: 1; transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        .animate-fade-in { animation: fadeIn 0.5s ease-in-out; }
        .animate-slide-up { animation: slideUp 0.3s ease-out; }
        .animate-bounce-in { animation: bounceIn 0.6s ease-out; }
        
        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #1e40af);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover::before { transform: scaleX(1); }
        .stat-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15); }
        
        .stat-violet::before { background: linear-gradient(90deg, #7c3aed, #5b21b6); }
        .stat-purple::before { background: linear-gradient(90deg, #a855f7, #7c3aed); }
        .stat-pink::before { background: linear-gradient(90deg, #ec4899, #db2777); }
        .stat-cyan::before { background: linear-gradient(90deg, #06b6d4, #0891b2); }
        .stat-emerald::before { background: linear-gradient(90deg, #10b981, #047857); }
        .stat-indigo::before { background: linear-gradient(90deg, #6366f1, #4f46e5); }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5b21b6, #4c1d95);
        }
        
        /* RTL Support */
        [dir="rtl"] .ml-2 { margin-left: 0; margin-right: 0.5rem; }
        [dir="rtl"] .mr-2 { margin-right: 0; margin-left: 0.5rem; }
        [dir="rtl"] .ml-3 { margin-left: 0; margin-right: 0.75rem; }
        [dir="rtl"] .mr-3 { margin-right: 0; margin-left: 0.75rem; }
        [dir="rtl"] .ml-4 { margin-left: 0; margin-right: 1rem; }
        [dir="rtl"] .mr-4 { margin-right: 0; margin-left: 1rem; }
        
        /* Ensure consistent heights */
        .min-h-screen { min-height: 100vh; }
        .h-full { height: 100%; }
        
        /* Button styles */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }
        
        /* Form elements */
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-main" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    @include('components.layouts.alert-scripts')
    
    <!-- PWA Install Button -->
    <div id="pwa-install-button" class="hidden fixed bottom-4 right-4 z-50">
        <button class="flex items-center px-4 py-2 bg-accent-600 text-white rounded-lg shadow-lg hover:bg-accent-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            {{ __('Install App') }}
        </button>
    </div>
    
    <!-- Fixed Header -->
    <div class="fixed top-0 left-0 right-0 z-50">
        @include('components.header-menu')
    </div>

    <!-- Main Content -->
    <div class="pt-20">
        <!-- Scrollable Main Content -->
        <div class="flex-1 p-2 overflow-y-auto">
            @yield('content')
        </div>
    </div>
</body>
</html>
