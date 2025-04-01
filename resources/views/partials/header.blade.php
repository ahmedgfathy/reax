<header class="absolute top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="/" class="text-white text-2xl font-bold">{{ __('REAX') }}</a>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center">
            <!-- Main Navigation Links -->
            <div class="flex space-x-6 items-center mr-6">
                <a href="/" class="text-white hover:text-gray-200 font-medium">{{ __('Home') }}</a>
                <a href="{{ route('sale') }}" class="text-white hover:text-gray-200 font-medium">{{ __('Sale') }}</a>
                <a href="{{ route('rent') }}" class="text-white hover:text-gray-200 font-medium">{{ __('Rent') }}</a>
                <a href="{{ route('primary') }}" class="text-white hover:text-gray-200 font-medium">{{ __('Primary') }}</a>
                <a href="#" class="text-white hover:text-gray-200 font-medium">{{ __('About Us') }}</a>
                <a href="#" class="text-white hover:text-gray-200 font-medium">{{ __('Contact Us') }}</a>
            </div>

            <!-- Language Switcher -->
            <form method="POST" action="{{ route('locale.switch') }}" class="inline-flex items-center mr-6">
                @csrf
                <input type="hidden" name="locale" value="{{ app()->getLocale() == 'en' ? 'ar' : 'en' }}">
                <button type="submit" class="text-white hover:text-gray-200 flex items-center">
                    <i class="fas fa-globe mr-2"></i>
                    <span>{{ app()->getLocale() == 'en' ? 'عربي' : 'ENG' }}</span>
                </button>
            </form>

            <!-- Auth Buttons -->
            @auth
                <div class="flex space-x-4 items-center">
                    <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-4 py-2 rounded-md font-medium transition-colors flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                    </a>
                    <!-- User Menu Dropdown -->   
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-white hover:text-blue-200 flex items-center">
                            <span class="h-8 w-8 bg-white rounded-full flex items-center justify-center text-blue-600 font-medium mr-2">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            {{ Auth::user()->name }}
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">{{ __('Profile') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">{{ __('Logout') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex space-x-4 items-center">
                    <a href="{{ route('login') }}" class="login-button">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-4 py-2 rounded-md font-medium transition-colors">{{ __('Register') }}</a>
                </div>
            @endauth
        </nav>
        
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="md:hidden flex items-center text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden bg-white shadow-lg hidden absolute w-full">
        <div class="container mx-auto px-4 py-3">
            <nav class="flex flex-col space-y-3">
                <a href="/" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Home') }}</a>
                <a href="{{ route('sale') }}" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Sale') }}</a>
                <a href="{{ route('rent') }}" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Rent') }}</a>
                <a href="{{ route('primary') }}" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Primary') }}</a>
                <a href="#" class="text-gray-800 hover:text-blue-600 py-2">{{ __('About Us') }}</a>
                <a href="#" class="text-gray-800 hover:text-blue-600 py-2">{{ __('Contact Us') }}</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-800 hover:text-blue-600 py-2 flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                    </a>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-800">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex justify-between py-2 border-t border-gray-200">
                        <form method="POST" action="{{ route('locale.switch') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="locale" value="{{ app()->getLocale() == 'en' ? 'ar' : 'en' }}">
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:text-blue-600 flex items-center">
                                <i class="fas fa-globe mr-2"></i>
                                <span>{{ app()->getLocale() == 'en' ? 'عربي' : 'ENG' }}</span>
                            </button>
                        </form>
                        
                        <div class="flex space-x-4">
                            <a href="{{ route('login') }}" class="login-button">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-3 py-1 rounded-md">{{ __('Register') }}</a>
                        </div>
                    </div>
                @endauth
            </nav>
        </div>
    </div>
</header>

@push('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
@endpush
