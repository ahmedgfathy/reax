<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* Modern Header Design - Simplified */
    .modern-header {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 50%, rgba(4, 120, 87, 0.95) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    /* Navigation Item Styling */
    .modern-nav-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 12px;
        transition: all 0.3s ease;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-weight: 500;
        font-size: 13px;
        margin: 0 2px;
        position: relative;
    }
    
    .modern-nav-item:hover {
        color: white;
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }
    
    .modern-nav-item.active {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* Icon Styling */
    .modern-nav-icon {
        font-size: 14px;
        margin-right: 6px;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .modern-nav-item:hover .modern-nav-icon {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }
    
    /* RTL Support */
    [dir="rtl"] .modern-nav-icon {
        margin-right: 0;
        margin-left: 6px;
    }
    
    /* Logo Design */
    .modern-logo {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
        padding: 8px;
    }
    
    .modern-logo:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.02);
    }
    
    /* User Menu & Language Switcher */
    .modern-button {
        padding: 8px 12px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 13px;
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    
    .modern-button:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    /* Dropdown Menus */
    .modern-dropdown {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .modern-dropdown-item {
        padding: 12px 16px;
        transition: all 0.2s ease;
        color: #374151;
        display: flex;
        align-items: center;
        text-decoration: none;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }
    
    .modern-dropdown-item:hover {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
        color: #047857;
    }
    
    /* Mobile Menu */
    .modern-mobile-menu {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .modern-mobile-item {
        padding: 16px;
        border-radius: 16px;
        margin: 8px;
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(16, 185, 129, 0.1);
        transition: all 0.3s ease;
        display: block;
        text-decoration: none;
        color: #374151;
    }
    
    .modern-mobile-item:hover {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
        transform: translateX(4px);
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
