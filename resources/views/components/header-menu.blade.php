<style>
    /* Enhanced Font Configuration */
    * {
        font-family: 'Roboto', sans-serif !important;
    }
    
    [dir="rtl"] *, html[lang="ar"] *, 
    [dir="rtl"], html[lang="ar"] {
        font-family: 'Cairo', sans-serif !important;
    }
    
    /* RTL Icon and Text Spacing */
    .nav-item-ltr {
        flex-direction: row;
    }
    
    .nav-item-rtl {
        flex-direction: row-reverse;
    }
    
    [dir="rtl"] .nav-icon {
        margin-right: 0 !important;
        margin-left: 0.5rem !important;
    }
    
    [dir="ltr"] .nav-icon {
        margin-right: 0.5rem !important;
        margin-left: 0 !important;
    }
    
    /* Custom Gradient - Emerald Ocean Theme */
    .bg-gradient-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
    }
    
    /* Enhanced Glass Effect */
    .glass-effect {
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .glass-nav-item {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .glass-nav-item:hover {
        background: rgba(255, 255, 255, 0.25) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    /* Navigation spacing for RTL */
    [dir="rtl"] .nav-container {
        flex-direction: row-reverse;
    }
    
    [dir="rtl"] .nav-links {
        flex-direction: row-reverse;
        gap: 0.25rem;
    }
    
    [dir="ltr"] .nav-links {
        gap: 0.25rem;
    }
    
    /* User dropdown adjustments */
    [dir="rtl"] .user-dropdown {
        left: 0;
        right: auto;
    }
    
    [dir="ltr"] .user-dropdown {
        right: 0;
        left: auto;
    }
    
    /* Language switcher */
    .lang-select {
        min-width: 80px;
        text-align: center;
    }
    
    [x-cloak] { display: none !important; }
</style>

<nav class="bg-gradient-header shadow-xl border-b border-emerald-600/20">
    <!-- Add Alpine.js if not already loaded -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <div class="max-w-full mx-4 xl:max-w-[1920px] xl:mx-auto px-4 md:px-6 w-full">
        <div class="flex items-center h-16">
            <!-- Logo -->
            <div class="flex items-center mr-8">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-building text-xl text-white"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-white">REAX</span>
                        <div class="text-xs font-medium text-emerald-100 opacity-90 -mt-1">CRM SYSTEM</div>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-1 flex-1">
                <!-- Properties -->
                <a href="{{ route('properties.index') }}" class="glass-nav-item px-4 py-2 rounded-xl hover:bg-white/20 text-sm transition-all duration-200 {{ request()->routeIs('properties.*') ? 'bg-white/30 text-white shadow-lg' : 'text-emerald-100 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-building mr-2"></i>
                        <span class="font-medium">{{ __('Properties') }}</span>
                    </div>
                </a>

                <!-- Leads -->
                <a href="{{ route('leads.index') }}" class="glass-nav-item px-4 py-2 rounded-xl hover:bg-white/20 text-sm transition-all duration-200 {{ request()->routeIs('leads.*') ? 'bg-white/30 text-white shadow-lg' : 'text-emerald-100 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        <span class="font-medium">{{ __('Leads') }}</span>
                    </div>
                </a>

                <!-- Opportunities -->
                <a href="{{ route('opportunities.index') }}" class="glass-nav-item px-4 py-2 rounded-xl hover:bg-white/20 text-sm transition-all duration-200 {{ request()->routeIs('opportunities.*') ? 'bg-white/30 text-white shadow-lg' : 'text-emerald-100 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-handshake mr-2"></i>
                        <span class="font-medium">{{ __('Opportunities') }}</span>
                    </div>
                </a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" class="glass-nav-item px-4 py-2 rounded-xl hover:bg-white/20 text-sm transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-white/30 text-white shadow-lg' : 'text-emerald-100 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        <span class="font-medium">{{ __('Reports') }}</span>
                    </div>
                </a>

                <!-- Management -->
                <a href="{{ route('management.index') }}" class="glass-nav-item px-4 py-2 rounded-xl hover:bg-white/20 text-sm transition-all duration-200 {{ request()->routeIs('management.*') ? 'bg-white/30 text-white shadow-lg' : 'text-emerald-100 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-cogs mr-2"></i>
                        <span class="font-medium">{{ __('Management') }}</span>
                    </div>
                </a>

                <!-- Administration -->
                @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('administration.index') }}" class="glass-nav-item px-4 py-2 rounded-xl hover:bg-white/20 text-sm transition-all duration-200 {{ request()->routeIs('administration.*') ? 'bg-white/30 text-white shadow-lg' : 'text-emerald-100 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span class="font-medium">{{ __('Administration') }}</span>
                    </div>
                </a>
                @endif

                <!-- Systems -->
                <a href="{{ route('systems.index') }}" class="glass-nav-item px-4 py-2 rounded-xl hover:bg-white/20 text-sm transition-all duration-200 {{ request()->routeIs('systems.*') ? 'bg-white/30 text-white shadow-lg' : 'text-emerald-100 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-network-wired mr-2"></i>
                        <span class="font-medium">{{ __('Systems') }}</span>
                    </div>
                </a>
            </div>

            <!-- Right Side Items -->
            <div class="flex items-center space-x-3">
                <!-- Language Switcher -->
                <div class="hidden md:block">
                    <form method="POST" action="{{ route('locale.switch') }}" class="inline-flex items-center" id="languageForm">
                        @csrf
                        <select name="locale" onchange="document.getElementById('languageForm').submit()" 
                                class="bg-white/20 border border-white/30 rounded-xl px-3 py-2 text-white text-sm backdrop-blur-sm hover:bg-white/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/50">
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }} class="text-gray-800">🇺🇸 EN</option>
                            <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }} class="text-gray-800">🇸🇦 عربي</option>
                        </select>
                    </form>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ isOpen: false }">
                    <button @click="isOpen = !isOpen" class="flex items-center space-x-3 bg-white/20 backdrop-blur-sm rounded-xl pl-3 pr-4 py-2 hover:bg-white/30 transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="text-left hidden lg:block">
                            <div class="text-white text-sm font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-emerald-100 text-xs">{{ Auth::user()->email }}</div>
                        </div>
                        <i class="fas fa-chevron-down text-emerald-100 transition-transform duration-200" :class="{'rotate-180': isOpen}"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="isOpen" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="transform opacity-0 scale-95" 
                         x-transition:enter-end="transform opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="transform opacity-100 scale-100" 
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="isOpen = false" 
                         class="absolute right-0 mt-2 w-64 glass-effect rounded-xl shadow-xl py-2 z-20 border border-white/20">
                        
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-emerald-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold shadow-lg">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-gray-800 font-semibold">{{ Auth::user()->name }}</div>
                                    <div class="text-gray-600 text-sm">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Menu Items -->
                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="fas fa-user w-5 text-emerald-600 mr-3"></i>
                                {{ __('Profile') }}
                            </a>
                            
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="fas fa-tachometer-alt w-5 text-emerald-600 mr-3"></i>
                                {{ __('Dashboard') }}
                            </a>
                            
                            <div class="border-t border-emerald-100 my-1"></div>
                            
                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Mobile Menu -->
                <div class="md:hidden" x-data="{ isOpen: false }">
                    <button @click="isOpen = !isOpen" class="p-2 rounded-md bg-blue-800 focus:outline-none hover:bg-blue-900 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <!-- Mobile Navigation Menu -->
                    <div x-show="isOpen" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="transform opacity-0 scale-95" 
                         x-transition:enter-end="transform opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="transform opacity-100 scale-100" 
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="isOpen = false" 
                         class="absolute top-16 left-0 right-0 bg-blue-700 shadow-lg z-20 mt-2">
                        <div class="flex flex-col py-2 px-4 space-y-1">
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('dashboard') ? 'bg-blue-800 text-white' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span>{{ __('Dashboard') }}</span>
                                </div>
                            </a>
                            <a href="{{ route('properties.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('properties.*') ? 'bg-blue-800 text-white' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span>{{ __('Properties') }}</span>
                                </div>
                            </a>
                            <a href="{{ route('leads.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('leads.*') ? 'bg-blue-800 text-white' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>{{ __('Leads') }}</span>
                                </div>
                            </a>
                            <a href="{{ route('opportunities.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('opportunities.*') ? 'bg-blue-800 text-white' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </div>
                            </a>
                            
                            <!-- Mobile Reports Module -->
                            <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('reports.*') ? 'bg-blue-800 text-white' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span>{{ __('Reports') }}</span>
                                </div>
                            </a>

                            <!-- Mobile Management Module - Available to all authenticated users -->
                            <a href="{{ route('management.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('management.*') ? 'bg-blue-800 text-white' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    <span>{{ __('Management') }}</span>
                                </div>
                            </a>
                            
                            <!-- Only show Administration and Systems in mobile menu for super admin -->
                            @if(Auth::user()->isAdmin())
                            <!-- Mobile Administration Module -->
                            <a href="{{ route('administration.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('administration.*') ? 'bg-blue-800 text-white' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ __('Administration') }}</span>
                                </div>
                            </a>

                            <!-- Mobile Systems Module -->
                            <a href="{{ route('systems.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('systems.*') ? 'bg-blue-800 text-white' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ __('Systems') }}</span>
                                </div>
                            </a>
                            @endif

                            <!-- Mobile Documentation Link - Keep text for mobile -->
                            <a href="{{ url('/documentation.html') }}" target="_blank" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->is('documentation*') ? 'bg-blue-800 text-white' : '' }}" title="{{ __('Documentation') }}">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span class="md:hidden">{{ __('Documentation') }}</span>
                                </div>
                            </a>

                            <div class="border-t border-blue-600 pt-2 mt-2">
                                <!-- User Info -->
                                <div class="flex items-center px-3 py-2 mb-2 text-blue-200">
                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold mr-3">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium">{{ Auth::user()->name }}</span>
                                </div>
                                
                                <!-- Mobile Language Selector -->
                                <form method="POST" 
                                      action="{{ route('locale.switch') }}" 
                                      class="lang-switcher px-3 py-2">
                                    @csrf
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                        </svg>
                                        <select name="locale" class="w-full bg-blue-800 rounded text-white focus:outline-none p-1 cursor-pointer">
                                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                            <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية</option>
                                        </select>
                                    </div>
                                </form>
                                
                                <!-- Profile Link in mobile menu -->
                                <a href="{{ route('profile.show') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ __('Profile') }}
                                </a>
                                
                                <!-- Logout Button -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-3 py-2 hover:bg-blue-800 rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- AlpineJS for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>

<!-- Unified Language Switcher JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const langSwitchers = document.querySelectorAll('.lang-switcher');
    
    langSwitchers.forEach(form => {
        const select = form.querySelector('select');
        if (!select) return;
        
        select.addEventListener('change', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            
            // Get CSRF token from meta tag
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin' // Important: send cookies with request
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then data => {
                if (data.success) {
                    // Set HTML direction
                    document.documentElement.dir = data.direction;
                    document.documentElement.lang = data.locale;
                    document.documentElement.classList.toggle('rtl', data.isRtl);
                    
                    // Force page reload with cache busting
                    const url = new URL(window.location.href);
                    url.searchParams.set('_locale', data.locale);
                    url.searchParams.set('_', Date.now());
                    window.location.href = url.toString();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // On error, submit form normally
                form.submit();
            });
        });
    });
});
</script>
