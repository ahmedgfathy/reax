<!-- FontAwesome CDN with multiple fallbacks for reliability -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" crossorigin="anonymous" />

<!-- Fallback for broken FontAwesome - Pure CSS Icons -->
<style>
    .fa-fallback {
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
    }
    
    /* Fallback icons using Unicode */
    .fas.fa-building:before { content: "🏢"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-users:before { content: "👥"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-handshake:before { content: "🤝"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-chart-bar:before { content: "📊"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-cogs:before { content: "⚙️"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-globe:before { content: "🌐"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-user:before { content: "👤"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-bars:before { content: "☰"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-user-edit:before { content: "✏️"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-tachometer-alt:before { content: "📊"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-sign-out-alt:before { content: "🚪"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-check:before { content: "✓"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
    .fas.fa-chevron-down:before { content: "▼"; font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
</style>

<style>
    /* Modern Font Configuration */
    * {
        font-family: 'Inter', 'Roboto', sans-serif !important;
    }
    
    [dir="rtl"] *, html[lang="ar"] *, 
    [dir="rtl"], html[lang="ar"] {
        font-family: 'Cairo', sans-serif !important;
    }
    
    /* Modern Header Design */
    .modern-header {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 50%, rgba(4, 120, 87, 0.95) 100%) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Navigation Item Styling */
    .modern-nav-item {
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        padding: 8px 12px !important;
        border-radius: 12px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        color: rgba(255, 255, 255, 0.9) !important;
        text-decoration: none !important;
        white-space: nowrap !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        backdrop-filter: blur(10px) !important;
        overflow: hidden !important;
        margin: 0 2px !important;
    }
    
    .modern-nav-item::before {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05)) !important;
        border-radius: 16px !important;
        opacity: 0 !important;
        transition: opacity 0.3s ease !important;
        z-index: -1 !important;
    }
    
    .modern-nav-item:hover::before {
        opacity: 1 !important;
    }
    
    .modern-nav-item:hover {
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .modern-nav-item.active {
        background: rgba(255, 255, 255, 0.25) !important;
        color: white !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
    }
    
    /* Icon Styling */
    .modern-nav-icon {
        font-size: 14px !important;
        margin-right: 6px !important;
        display: inline-flex !important;
        width: 16px !important;
        height: 16px !important;
        align-items: center !important;
        justify-content: center !important;
        background: rgba(255, 255, 255, 0.2) !important;
        border-radius: 6px !important;
        transition: all 0.3s ease !important;
    }
    
    .modern-nav-item:hover .modern-nav-icon {
        background: rgba(255, 255, 255, 0.3) !important;
        transform: scale(1.1) !important;
    }
    
    /* RTL Icon positioning */
    [dir="rtl"] .modern-nav-icon {
        margin-right: 0 !important;
        margin-left: 6px !important;
    }
    
    /* Logo Design */
    .modern-logo {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 16px !important;
        transition: all 0.3s ease !important;
    }
    
    .modern-logo:hover {
        background: rgba(255, 255, 255, 0.25) !important;
        transform: scale(1.02) !important;
    }
    
    /* User Menu & Language Switcher */
    .modern-button {
        padding: 8px 12px !important;
        border-radius: 12px !important;
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        transition: all 0.3s ease !important;
        font-weight: 500 !important;
        font-size: 13px !important;
    }
    
    .modern-button:hover {
        background: rgba(255, 255, 255, 0.25) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Dropdown Menus */
    .modern-dropdown {
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden !important;
    }
    
    .modern-dropdown-item {
        padding: 12px 16px !important;
        transition: all 0.2s ease !important;
        color: #374151 !important;
        display: flex !important;
        align-items: center !important;
    }
    
    .modern-dropdown-item:hover {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)) !important;
        color: #047857 !important;
    }
    
    /* Mobile Menu */
    .modern-mobile-menu {
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(20px) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.1) !important;
    }
    
    .modern-mobile-item {
        padding: 16px !important;
        border-radius: 16px !important;
        margin: 8px !important;
        background: rgba(255, 255, 255, 0.7) !important;
        border: 1px solid rgba(16, 185, 129, 0.1) !important;
        transition: all 0.3s ease !important;
    }
    
    .modern-mobile-item:hover {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)) !important;
        transform: translateX(4px) !important;
    }
    
    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }
    
    /* Enhanced Focus States */
    .modern-nav-item:focus,
    .modern-button:focus {
        outline: 2px solid rgba(255, 255, 255, 0.5) !important;
        outline-offset: 2px !important;
    }
</style>

<nav class="modern-header shadow-2xl" x-data="{ mobileOpen: false }" style="min-height: 56px; position: sticky; top: 0; z-index: 50;">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-14">
            
            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center modern-logo p-2">
                    <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-building text-lg text-white"></i>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xl font-bold text-white tracking-wide">REAX</span>
                        <span class="text-sm font-medium text-white/90 tracking-wider">
                            {{ app()->getLocale() == 'ar' ? 'نظام إدارة العقارات' : 'CRM SYSTEM' }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- Center Navigation Links -->
            <div class="hidden lg:flex items-center space-x-0">
                
                <!-- Properties -->
                <a href="{{ route('properties.index') }}" class="modern-nav-item {{ request()->routeIs('properties.*') ? 'active' : '' }}">
                    <div class="modern-nav-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <span>{{ __('Properties') }}</span>
                </a>

                <!-- Leads -->
                <a href="{{ route('leads.index') }}" class="modern-nav-item {{ request()->routeIs('leads.*') ? 'active' : '' }}">
                    <div class="modern-nav-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span>{{ __('Leads') }}</span>
                </a>

                <!-- Opportunities -->
                <a href="{{ route('opportunities.index') }}" class="modern-nav-item {{ request()->routeIs('opportunities.*') ? 'active' : '' }}">
                    <div class="modern-nav-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <span>{{ __('Opportunities') }}</span>
                </a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" class="modern-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <div class="modern-nav-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <span>{{ __('Reports') }}</span>
                </a>

                <!-- Management -->
                <a href="{{ route('management.index') }}" class="modern-nav-item {{ request()->routeIs('management.*') ? 'active' : '' }}">
                    <div class="modern-nav-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <span>{{ __('Management') }}</span>
                </a>
            </div>

            <!-- Right Side Menu -->
            <div class="flex items-center space-x-1">
                
                <!-- Language Switcher -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="modern-button flex items-center space-x-1">
                        <div class="w-4 h-4 rounded-full bg-white/20 flex items-center justify-content">
                            <i class="fas fa-globe text-xs"></i>
                        </div>
                        <span class="font-semibold text-xs uppercase">{{ app()->getLocale() }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-52 modern-dropdown z-50 animate-slide-in">
                        <div class="py-2">
                            <form method="POST" action="{{ route('locale.switch') }}">
                                @csrf
                                <input type="hidden" name="locale" value="en">
                                <button type="submit" class="modern-dropdown-item w-full {{ app()->getLocale() == 'en' ? 'bg-emerald-50 text-emerald-800 font-semibold' : '' }}">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                        <span class="text-sm">🇺🇸</span>
                                    </div>
                                    <span class="font-medium">English</span>
                                    @if(app()->getLocale() == 'en')
                                        <i class="fas fa-check ml-auto text-emerald-600"></i>
                                    @endif
                                </button>
                            </form>
                            <form method="POST" action="{{ route('locale.switch') }}">
                                @csrf
                                <input type="hidden" name="locale" value="ar">
                                <button type="submit" class="modern-dropdown-item w-full {{ app()->getLocale() == 'ar' ? 'bg-emerald-50 text-emerald-800 font-semibold' : '' }}">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                                        <span class="text-sm">🇸🇦</span>
                                    </div>
                                    <span class="font-medium">العربية</span>
                                    @if(app()->getLocale() == 'ar')
                                        <i class="fas fa-check ml-auto text-emerald-600"></i>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="modern-button flex items-center space-x-2">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="hidden sm:block text-left">
                            <div class="text-sm font-semibold text-white leading-tight">{{ auth()->user()->name }}</div>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-72 modern-dropdown z-50 animate-slide-in">
                        
                        <!-- User Info Header -->
                        <div class="px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                    @if(auth()->user()->role)
                                        <div class="text-xs text-emerald-600 font-medium">{{ ucfirst(auth()->user()->role) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}" class="modern-dropdown-item">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-user-edit text-blue-600"></i>
                                </div>
                                <span class="font-medium">{{ __('Edit Profile') }}</span>
                            </a>
                            
                            <a href="{{ route('dashboard') }}" class="modern-dropdown-item">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-tachometer-alt text-purple-600"></i>
                                </div>
                                <span class="font-medium">{{ __('Dashboard') }}</span>
                            </a>
                            
                            <div class="border-t border-gray-100 my-2"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="modern-dropdown-item w-full text-red-600 hover:bg-red-50">
                                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-sign-out-alt text-red-600"></i>
                                    </div>
                                    <span class="font-medium">{{ __('Log Out') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden modern-button p-2">
                    <i class="fas fa-bars text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 transform -translate-y-4" 
         x-transition:enter-end="opacity-100 transform translate-y-0" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 transform translate-y-0" 
         x-transition:leave-end="opacity-0 transform -translate-y-4" 
         @click.away="mobileOpen = false" 
         class="lg:hidden modern-mobile-menu">
        
        <div class="px-4 py-6 space-y-3">
            <!-- Mobile Navigation Links -->
            <div class="space-y-2">
                <a href="{{ route('properties.index') }}" class="modern-mobile-item {{ request()->routeIs('properties.*') ? 'bg-emerald-100 text-emerald-800' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center mr-4">
                            <i class="fas fa-building text-blue-600"></i>
                        </div>
                        <span class="font-medium">{{ __('Properties') }}</span>
                    </div>
                </a>
                
                <a href="{{ route('leads.index') }}" class="modern-mobile-item {{ request()->routeIs('leads.*') ? 'bg-emerald-100 text-emerald-800' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center mr-4">
                            <i class="fas fa-users text-green-600"></i>
                        </div>
                        <span class="font-medium">{{ __('Leads') }}</span>
                    </div>
                </a>
                
                <a href="{{ route('opportunities.index') }}" class="modern-mobile-item {{ request()->routeIs('opportunities.*') ? 'bg-emerald-100 text-emerald-800' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center mr-4">
                            <i class="fas fa-handshake text-purple-600"></i>
                        </div>
                        <span class="font-medium">{{ __('Opportunities') }}</span>
                    </div>
                </a>
                
                <a href="{{ route('reports.index') }}" class="modern-mobile-item {{ request()->routeIs('reports.*') ? 'bg-emerald-100 text-emerald-800' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center mr-4">
                            <i class="fas fa-chart-bar text-yellow-600"></i>
                        </div>
                        <span class="font-medium">{{ __('Reports') }}</span>
                    </div>
                </a>
                
                <a href="{{ route('management.index') }}" class="modern-mobile-item {{ request()->routeIs('management.*') ? 'bg-emerald-100 text-emerald-800' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center mr-4">
                            <i class="fas fa-cogs text-indigo-600"></i>
                        </div>
                        <span class="font-medium">{{ __('Management') }}</span>
                    </div>
                </a>
            </div>
            
            <div class="border-t border-gray-200 pt-6">
                <!-- Mobile Language Switcher -->
                <div class="mb-4">
                    <form method="POST" action="{{ route('locale.switch') }}" id="mobileLangForm">
                        @csrf
                        <select name="locale" onchange="document.getElementById('mobileLangForm').submit()" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                            <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>🇸🇦 العربية</option>
                        </select>
                    </form>
                </div>
                
                <!-- Mobile User Actions -->
                <div class="space-y-2">
                    <a href="{{ route('profile.edit') }}" class="modern-mobile-item">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center mr-4">
                                <i class="fas fa-user-edit text-gray-600"></i>
                            </div>
                            <span class="font-medium">{{ __('Edit Profile') }}</span>
                        </div>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="modern-mobile-item w-full text-red-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-sign-out-alt text-red-600"></i>
                                </div>
                                <span class="font-medium">{{ __('Log Out') }}</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Alpine.js -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
