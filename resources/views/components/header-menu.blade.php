<nav class="bg-gradient-to-r from-blue-700 to-blue-600 text-white shadow-lg">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center group">
                    <span class="text-2xl font-bold text-white group-hover:text-blue-200 transition">REAX 
                        <span class="text-sm font-normal opacity-80 bg-blue-800 px-2 py-0.5 rounded-md ml-1">CRM</span>
                    </span>
                </a>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-1">
                <!-- Home Link - Remove target="_blank" to open in same tab -->
                <a href="{{ url('/') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 hover:text-white transition-colors duration-200 text-blue-100">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>{{ __('Home') }}</span>
                    </div>
                </a>

                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-800 text-white' : 'text-blue-100' }}">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </div>
                </a>
                <a href="{{ route('properties.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('properties.*') ? 'bg-blue-800 text-white' : 'text-blue-100' }}">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>{{ __('Properties') }}</span>
                    </div>
                </a>
                <a href="{{ route('leads.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('leads.*') ? 'bg-blue-800 text-white' : 'text-blue-100' }}">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>{{ __('Leads') }}</span>
                    </div>
                </a>
                <!-- Reports Module - Update with the correct route -->
                <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.*') ? 'bg-blue-800 text-white' : 'text-blue-100' }}">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>{{ __('Reports') }}</span>
                    </div>
                </a>
                <!-- Administration Module -->
                <a href="{{ route('administration.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('administration.*') ? 'bg-blue-800 text-white' : 'text-blue-100' }}">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ __('Administration') }}</span>
                    </div>
                </a>

                <!-- Documentation Link - Icon Only -->
                <a href="{{ url('/documentation.html') }}" target="_blank" class="px-3 py-2 rounded-md hover:bg-blue-700 hover:text-white transition-colors duration-200 {{ request()->is('documentation*') ? 'bg-blue-800 text-white' : 'text-blue-100' }}" title="{{ __('Documentation') }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </a>
                
                <!-- User Menu Dropdown -->
                <div class="relative ml-3" x-data="{ isOpen: false }">
                    <button @click="isOpen = !isOpen" class="flex items-center space-x-2 bg-blue-800 rounded-full pl-2 pr-3 py-1.5 focus:outline-none hover:bg-blue-900 transition">
                        <div class="h-7 w-7 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': isOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
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
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 ring-1 ring-black ring-opacity-5">
                        
                        <!-- Language Selector -->
                        <form method="POST" action="{{ route('locale.switch') }}" class="px-4 py-2 border-b">
                            @csrf
                            <select name="locale" onchange="this.form.submit()" class="w-full text-gray-800 bg-transparent focus:outline-none cursor-pointer">
                                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية</option>
                            </select>
                            <noscript>
                                <button type="submit" class="mt-2 w-full bg-blue-600 text-white rounded-md px-2 py-1 text-xs">
                                    {{ __('Change Language') }}
                                </button>
                            </noscript>
                        </form>
                        
                        <!-- Profile Link -->
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Profile') }}
                        </a>
                        
                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Button -->
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
                        <!-- Remove target="_blank" from mobile Home link as well -->
                        <a href="{{ url('/') }}" class="px-3 py-2 rounded-md hover:bg-blue-800">
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span>{{ __('Home') }}</span>
                            </div>
                        </a>
                        
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
                        
                        <!-- Mobile Reports Module - Update with the correct route -->
                        <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('reports.*') ? 'bg-blue-800 text-white' : '' }}">
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>{{ __('Reports') }}</span>
                            </div>
                        </a>
                        
                        <!-- Mobile Administration Module -->
                        <a href="{{ route('administration.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-800 {{ request()->routeIs('administration.*') ? 'bg-blue-800 text-white' : '' }}">
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ __('Administration') }}</span>
                            </div>
                        </a>

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
                            
                            <!-- Language Selector -->
                            <form method="POST" action="{{ route('locale.switch') }}" class="px-3 py-2">
                                @csrf
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                    </svg>
                                    <select name="locale" onchange="this.form.submit()" class="w-full bg-blue-800 rounded text-white focus:outline-none p-1">
                                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية</option>
                                    </select>
                                    <noscript>
                                        <button type="submit" class="mt-2 w-full bg-blue-600 text-white rounded-md px-2 py-1 text-xs">
                                            {{ __('Change Language') }}
                                        </button>
                                    </noscript>
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
</nav>

<!-- Mobile Navigation Header -->
<div class="flex justify-between items-center p-4 md:hidden">
    <a href="/" class="text-xl font-bold text-gray-800">REAX</a>
    <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-800 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>
</div>

<!-- Desktop Navigation -->
<nav class="hidden md:flex space-x-4">
    <a href="/" class="text-gray-800 hover:text-blue-600 px-3 py-2">{{ __('Home') }}</a>
    <a href="{{ route('dashboard') }}" class="text-gray-800 hover:text-blue-600 px-3 py-2">{{ __('Dashboard') }}</a>
    <!-- Additional nav items -->
</nav>

<!-- Mobile Menu (hidden by default) -->
<div id="mobile-menu" class="hidden md:hidden mt-2 py-2 bg-white shadow-lg rounded-lg absolute left-0 right-0 z-20">
    <a href="/" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">{{ __('Home') }}</a>
    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">{{ __('Dashboard') }}</a>
    <!-- Additional mobile nav items -->
    
    <!-- Fix: Check if user is authenticated before accessing user properties -->
    @auth
        <div class="border-t border-gray-200 my-2"></div>
        <div class="px-4 py-2">
            <span class="block text-sm text-gray-600">{{ __('Signed in as:') }}</span>
            <span class="block font-medium">{{ Auth::user()->name }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="block px-4 py-2">
            @csrf
            <button type="submit" class="text-red-600 hover:text-red-800">{{ __('Sign out') }}</button>
        </form>
    @else
        <div class="border-t border-gray-200 my-2"></div>
        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">{{ __('Login') }}</a>
        <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">{{ __('Register') }}</a>
    @endauth
</div>

<!-- AlpineJS for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
