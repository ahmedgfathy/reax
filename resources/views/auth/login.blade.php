<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Login') }} - Glomart CRM</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Roboto', 'sans-serif'],
                        'arabic': ['Cairo', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts with DNS prefetch -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="all">
    
    <!-- Immediate Font Loading Script -->
    <script>
        // Force font application immediately 
        const applyFonts = () => {
            const lang = document.documentElement.lang || 'en';
            const dir = document.documentElement.dir || 'ltr';
            
            const fontFamily = (lang === 'ar' || dir === 'rtl') ? 
                "'Cairo', 'Tahoma', 'Arial Unicode MS', sans-serif" : 
                "'Roboto', 'Arial', 'Helvetica', sans-serif";
            
            document.body.style.setProperty('font-family', fontFamily, 'important');
            document.documentElement.style.setProperty('font-family', fontFamily, 'important');
            
            // Apply to all existing elements
            const allElements = document.querySelectorAll('*');
            allElements.forEach(el => {
                el.style.setProperty('font-family', fontFamily, 'important');
            });
        };
        
        // Apply fonts immediately and on DOM ready
        applyFonts();
        document.addEventListener('DOMContentLoaded', applyFonts);
        window.addEventListener('load', applyFonts);
    </script>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Font Configuration with higher specificity */
        * {
            font-family: 'Roboto', sans-serif !important;
        }
        
        [dir="rtl"] *, html[lang="ar"] *, 
        [dir="rtl"], html[lang="ar"] {
            font-family: 'Cairo', sans-serif !important;
        }
        
        /* Tailwind CSS font override */
        .font-sans {
            font-family: 'Roboto', sans-serif !important;
        }
        
        [dir="rtl"] .font-sans, 
        html[lang="ar"] .font-sans {
            font-family: 'Cairo', sans-serif !important;
        }
        
        /* Force font loading on all elements */
        input, button, select, textarea, label, span, p, h1, h2, h3, h4, h5, h6, div, a {
            font-family: inherit !important;
        }
        
        /* Additional CSS to ensure font visibility */
        @font-face {
            font-family: 'Roboto-Fallback';
            src: local('Roboto'), local('Arial'), local('sans-serif');
        }
        
        @font-face {
            font-family: 'Cairo-Fallback';
            src: local('Cairo'), local('Tahoma'), local('Arial Unicode MS');
        }
        
        /* Custom Gradient - Glomart Purple Theme */
        .bg-gradient-custom {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #7c3aed 50%, #ec4899 75%, #06b6d4 100%);
        }
        
        /* Glass Effect */
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        }
        
        /* Hover Effects */
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(124, 58, 237, 0.4);
        }
        
        /* Input Focus */
        .input-focus:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.3);
            border-color: #7c3aed;
        }
        
        /* Logo Glow Effect */
        .logo-glow {
            box-shadow: 0 0 30px rgba(124, 58, 237, 0.3);
        }
        
        /* Button Gradient */
        .btn-gradient {
            background: linear-gradient(135deg, #7c3aed 0%, #ec4899 50%, #06b6d4 100%);
        }
        
        /* Accent Colors */
        .text-accent { color: #a855f7; }
        .text-accent-light { color: #c084fc; }
        .bg-accent-glow { 
            background: rgba(124, 58, 237, 0.15);
            border: 1px solid rgba(124, 58, 237, 0.3);
        }
        
        /* Language Switcher */
        .lang-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        
        .lang-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .lang-btn:hover, .lang-btn.active {
            background: rgba(124, 58, 237, 0.3);
            border-color: #7c3aed;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-custom flex items-center justify-center p-4">
    <!-- Language Switcher -->
    <div class="lang-switcher">
        <div class="flex space-x-2">
            <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit" class="lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    <i class="fas fa-globe mr-1"></i>EN
                </button>
            </form>
            <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                @csrf
                <input type="hidden" name="locale" value="ar">
                <button type="submit" class="lang-btn {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    <i class="fas fa-globe mr-1"></i>العربية
                </button>
            </form>
        </div>
    </div>

    <div class="w-full max-w-md">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="logo-glow inline-flex items-center justify-center w-20 h-20 bg-accent-glow rounded-2xl mb-4">
                <i class="fas fa-gem text-3xl text-accent"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">Glomart CRM</h1>
            <p class="text-accent-light text-lg">{{ __('Real Estate Management System') }}</p>
        </div>

        <!-- Login Form Card -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white mb-2">{{ __('Welcome Back') }}</h2>
                <p class="text-accent-light">{{ __('Sign in to your account') }}</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-500 bg-opacity-20 border border-red-400 text-red-100 px-4 py-3 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-accent-light mb-2">
                        <i class="fas fa-envelope mr-2"></i>{{ __('Email Address') }}
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="input-focus w-full px-4 py-3 bg-white bg-opacity-15 border border-white border-opacity-30 rounded-xl text-white placeholder-gray-300 transition-all duration-200" 
                        placeholder="{{ __('Enter your email') }}"
                        required 
                        autofocus
                    >
                </div>
                
                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-accent-light mb-2">
                        <i class="fas fa-lock mr-2"></i>{{ __('Password') }}
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-focus w-full px-4 py-3 bg-white bg-opacity-15 border border-white border-opacity-30 rounded-xl text-white placeholder-gray-300 transition-all duration-200" 
                        placeholder="{{ __('Enter your password') }}"
                        required
                    >
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-accent-light">
                            {{ __('Remember me') }}
                        </label>
                    </div>
                    <a href="#" class="text-sm text-accent hover:text-white transition-colors">
                        {{ __('Forgot password?') }}
                    </a>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn-hover btn-gradient w-full text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 flex items-center justify-center shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-3"></i>
                    {{ __('Sign In to Dashboard') }}
                </button>
            </form>
            
            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-accent-light">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-accent hover:text-white font-medium transition-colors">
                        {{ __('Create Account') }}
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-accent-light">
                &copy; {{ date('Y') }} Glomart CRM. {{ __('All rights reserved.') }}
            </p>
        </div>
    </div>
</body>
</html>
