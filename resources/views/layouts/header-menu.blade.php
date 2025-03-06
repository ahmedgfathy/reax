<header class="bg-blue-600 shadow-md">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-white">{{ config('app.name') }}</a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-6">
                <a href="/" class="text-white hover:text-blue-200 font-medium">{{ __('Home') }}</a>
                <a href="{{ route('sale') }}" class="text-white hover:text-blue-200 font-medium">{{ __('Sale') }}</a>
                <a href="{{ route('rent') }}" class="text-white hover:text-blue-200 font-medium">{{ __('Rent') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('Primary') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('About Us') }}</a>
                <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('Contact Us') }}</a>

                <!-- Language Switcher -->
                <form method="POST" action="{{ route('locale.switch') }}" class="inline-flex items-center">
                    @csrf
                    <select name="locale" onchange="this.form.submit()" 
                            class="bg-white/20 border border-white/30 rounded px-2 py-1 text-white">
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                        <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>عربي</option>
                    </select>
                </form>

                <!-- Auth Links -->
                @auth 
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg transition-colors">
                            {{ __('Dashboard') }}
                        </a>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-200">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg transition-colors">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </nav>

            <!-- Mobile menu button -->
            <button class="md:hidden text-white" id="mobile-menu-button">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden mt-4">
            <nav class="flex flex-col space-y-3">
                <a href="/" class="text-white hover:text-blue-200">{{ __('Home') }}</a>
                <a href="{{ route('sale') }}" class="text-white hover:text-blue-200">{{ __('Sale') }}</a>
                <a href="{{ route('rent') }}" class="text-white hover:text-blue-200">{{ __('Rent') }}</a>
                <a href="#" class="text-white hover:text-blue-200">{{ __('Primary') }}</a>
                <a href="#" class="text-white hover:text-blue-200">{{ __('About Us') }}</a>
                <a href="#" class="text-white hover:text-blue-200">{{ __('Contact Us') }}</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-blue-200">{{ __('Dashboard') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="text-white hover:text-blue-200 w-full text-left">
                            {{ __('Logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-blue-200">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="text-white hover:text-blue-200">{{ __('Register') }}</a>
                @endauth
            </nav>
        </div>
    </div>
</header>

@push('scripts')
<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
@endpush