<div class="w-64 bg-white text-gray-800 shadow-lg h-full overflow-y-auto">
    <!-- Close button for mobile -->
    <button onclick="toggleSidebar()" class="lg:hidden absolute right-4 top-4 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <div class="p-6 space-y-8"> <!-- Increased padding from p-4 to p-6 and space-y from 6 to 8 -->
        <div class="space-y-6">
            <!-- Administration & User Management (Only visible to Admin and Managers) -->
            @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <div class="pb-6 border-b border-gray-200">
                <h3 class="flex items-center text-sm font-medium mb-2 text-gray-700">
                    <i class="fas fa-cogs mr-3 text-red-600"></i>
                    {{ __('Administration') }}
                </h3>
                <div class="ml-6 space-y-2">
                    <a href="{{ route('administration.index') }}" class="flex items-center text-sm text-gray-600 hover:text-red-600 transition-colors {{ request()->routeIs('administration.index') ? 'text-red-600 font-medium' : '' }}">
                        <i class="fas fa-tachometer-alt w-4 mr-2"></i>
                        {{ __('Admin Dashboard') }}
                    </a>
                    <a href="{{ route('administration.users.index') }}" class="flex items-center text-sm text-gray-600 hover:text-red-600 transition-colors {{ request()->routeIs('administration.users.*') ? 'text-red-600 font-medium' : '' }}">
                        <i class="fas fa-users w-4 mr-2"></i>
                        {{ __('All Users') }}
                    </a>
                    <a href="{{ route('administration.profiles.index') }}" class="flex items-center text-sm text-gray-600 hover:text-red-600 transition-colors {{ request()->routeIs('administration.profiles.*') ? 'text-red-600 font-medium' : '' }}">
                        <i class="fas fa-user-shield w-4 mr-2"></i>
                        {{ __('User Profiles') }}
                    </a>
                    <a href="{{ route('administration.role-management.index') }}" class="flex items-center text-sm text-gray-600 hover:text-red-600 transition-colors {{ request()->routeIs('administration.role-management.*') ? 'text-red-600 font-medium' : '' }}">
                        <i class="fas fa-users-cog w-4 mr-2"></i>
                        {{ __('Role Management') }}
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('administration.employees.index') }}" class="flex items-center text-sm text-gray-600 hover:text-red-600 transition-colors {{ request()->routeIs('administration.employees.*') ? 'text-red-600 font-medium' : '' }}">
                        <i class="fas fa-user-plus w-4 mr-2"></i>
                        {{ __('Employee Management') }}
                    </a>
                    @endif
                </div>
            </div>
            @endif
            @endauth

            <!-- Clients & Contact Management -->
            <div class="pb-6 border-b border-gray-200"> <!-- Increased padding bottom from 4 to 6 -->
                <h3 class="flex items-center text-sm font-medium mb-2 text-gray-700">
                    <i class="fas fa-users mr-3 text-blue-600"></i>
                    {{ __('Clients & Contact Management') }}
                </h3>
                <div class="ml-6 space-y-2">
                    <a href="{{ route('contacts.index') }}" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors {{ request()->routeIs('contacts.*') ? 'text-blue-600 font-medium' : '' }}">
                        <i class="fas fa-address-card w-4 mr-2"></i>
                        {{ __('Contacts') }}
                    </a>
                    <a href="{{ route('companies.index') }}" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors {{ request()->routeIs('companies.*') ? 'text-blue-600 font-medium' : '' }}">
                        <i class="fas fa-building w-4 mr-2"></i>
                        {{ __('Companies') }}
                    </a>
                </div>
            </div>

            <!-- Task & Calendar Management -->
            <div class="pb-4 border-b border-gray-200">
                <h3 class="flex items-center text-sm font-medium mb-2 text-gray-700">
                    <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                    {{ __('Task & Calendar Management') }}
                </h3>
                <div class="ml-6 space-y-2">
                    <a href="#" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-check w-4 mr-2"></i>
                        {{ __('Calendar') }}
                    </a>
                    <a href="#" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-tasks w-4 mr-2"></i>
                        {{ __('Tasks') }}
                    </a>
                </div>
            </div>

            <!-- Marketing & Automation -->
            <div class="pb-4 border-b border-gray-200">
                <h3 class="flex items-center text-sm font-medium mb-2 text-gray-700">
                    <i class="fas fa-bullhorn mr-3 text-blue-600"></i>
                    {{ __('Marketing & Automation') }}
                </h3>
                <div class="ml-6 space-y-2">
                    <a href="#" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-ad w-4 mr-2"></i>
                        {{ __('Campaigns') }}
                    </a>
                    <a href="#" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-robot w-4 mr-2"></i>
                        {{ __('Automation') }}
                    </a>
                </div>
            </div>

            <!-- Legal & Document Management -->
            <div class="pb-4 border-b border-gray-200">
                <h3 class="flex items-center text-sm font-medium mb-2 text-gray-700">
                    <i class="fas fa-file-contract mr-3 text-blue-600"></i>
                    {{ __('Legal & Document Management') }}
                </h3>
                <div class="ml-6 space-y-2">
                    <a href="#" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-file-pdf w-4 mr-2"></i>
                        {{ __('Documents') }}
                    </a>
                    <a href="#" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-file-alt w-4 mr-2"></i>
                        {{ __('Templates') }}
                    </a>
                </div>
            </div>

            <!-- AI-powered Chatbots -->
            <div class="pb-4">
                <h3 class="flex items-center text-sm font-medium mb-2 text-gray-700">
                    <i class="fas fa-robot mr-3 text-blue-600"></i>
                    {{ __('AI-powered Chatbots') }}
                </h3>
                <div class="ml-6 space-y-2">
                    <a href="#" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-comments w-4 mr-2"></i>
                        {{ __('Chatbot Settings') }}
                    </a>
                    <a href="#" class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-chart-line w-4 mr-2"></i>
                        {{ __('Chat Analytics') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
