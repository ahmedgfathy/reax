<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Dashboard') }} - REAX CRM</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Modern Design System */
        * {
            font-family: 'Inter', 'Roboto', sans-serif !important;
        }
        
        [dir="rtl"] *, html[lang="ar"] *, 
        [dir="rtl"], html[lang="ar"] {
            font-family: 'Cairo', sans-serif !important;
        }
        
        /* Modern Background */
        .modern-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
            min-height: 100vh;
        }
        
        /* Glass Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            margin: 3rem 0.5rem 0.5rem;
            padding: 0.75rem 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-opacity="0.1" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            pointer-events: none;
        }
        
        /* Modern Stats Cards */
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
        
        .stat-card:hover::before {
            transform: scaleX(1);
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        /* Gradient Overlays for Stat Cards */
        .stat-blue::before { background: linear-gradient(90deg, #3b82f6, #1e40af); }
        .stat-green::before { background: linear-gradient(90deg, #10b981, #059669); }
        .stat-emerald::before { background: linear-gradient(90deg, #059669, #047857); }
        .stat-yellow::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .stat-red::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
        .stat-indigo::before { background: linear-gradient(90deg, #6366f1, #4f46e5); }
        
        /* Tab System */
        .modern-tabs {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.95) 0%, rgba(241, 245, 249, 0.95) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(203, 213, 225, 0.3);
            border-radius: 16px;
            padding: 6px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .tab-button {
            border-radius: 12px;
            padding: 12px 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            position: relative;
            overflow: hidden;
            font-size: 14px;
        }
        
        .tab-button.active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white !important;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.25);
            transform: translateY(-1px);
        }
        
        .tab-button.active span {
            color: white !important;
        }
        
        .tab-button.active i {
            color: white !important;
        }
        
        .tab-button:not(.active) {
            color: #374151;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(229, 231, 235, 0.6);
        }
        
        .tab-button:not(.active):hover {
            background: rgba(255, 255, 255, 1);
            color: #10b981;
            transform: translateY(-1px);
            border-color: rgba(16, 185, 129, 0.4);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }
        
        /* Content Cards */
        .content-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
        }
        
        /* Lead Cards */
        .lead-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .lead-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .lead-card:hover::before {
            opacity: 1;
        }
        
        .lead-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        @keyframes bounceIn {
            0% { 
                opacity: 0; 
                transform: scale(0.3); 
            }
            50% { 
                opacity: 1; 
                transform: scale(1.05); 
            }
            70% { 
                transform: scale(0.9); 
            }
            100% { 
                opacity: 1; 
                transform: scale(1); 
            }
        }
        
        .animate-fade-in { animation: fadeIn 0.5s ease-in-out; }
        .animate-slide-up { animation: slideUp 0.3s ease-out; }
        .animate-bounce-in { animation: bounceIn 0.6s ease-out; }
        
        /* Icon Containers */
        .icon-container {
            width: 4rem;
            height: 4rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .icon-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .stat-card:hover .icon-container::before {
            opacity: 1;
        }
        
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
            background: linear-gradient(135deg, #047857, #065f46);
        }
    </style>
</head>
<body class="modern-bg">
    <!-- Header Menu -->
    @include('components.header-menu')

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-4">
        <!-- Navigation Tabs -->
        <div x-data="{ activeTab: 'overview' }" class="mb-8">
            <div class="modern-tabs mb-6 animate-slide-up">
                <ul class="flex space-x-1">
                    <li @click="activeTab = 'overview'" 
                        :class="{'active': activeTab === 'overview'}" 
                        class="tab-button flex-1 cursor-pointer text-center">
                        <span class="flex items-center justify-center">
                            <i class="fas fa-chart-pie mr-2 text-sm"></i> 
                            {{ __('Overview') }}
                        </span>
                    </li>
                    <li @click="activeTab = 'properties'" 
                        :class="{'active': activeTab === 'properties'}" 
                        class="tab-button flex-1 cursor-pointer text-center">
                        <span class="flex items-center justify-center">
                            <i class="fas fa-building mr-2 text-sm"></i> 
                            {{ __('Properties') }}
                        </span>
                    </li>
                    <li @click="activeTab = 'leads'" 
                        :class="{'active': activeTab === 'leads'}" 
                        class="tab-button flex-1 cursor-pointer text-center">
                        <span class="flex items-center justify-center">
                            <i class="fas fa-users mr-2 text-sm"></i> 
                            {{ __('Leads') }}
                        </span>
                    </li>
                    <li @click="activeTab = 'opportunities'" 
                        :class="{'active': activeTab === 'opportunities'}" 
                        class="tab-button flex-1 cursor-pointer text-center">
                        <span class="flex items-center justify-center">
                            <i class="fas fa-handshake mr-2 text-sm"></i> 
                            {{ __('Opportunities') }}
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Overview Panel -->
            <div x-show="activeTab === 'overview'" class="animate-fade-in">
                <!-- Stats Overview Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card stat-blue animate-bounce-in" style="animation-delay: 0.1s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Total Properties') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">{{ $stats['properties_count'] }}</p>
                                <p class="text-sm text-green-600 font-medium">+12% from last month</p>
                            </div>
                            <div class="icon-container bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-green animate-bounce-in" style="animation-delay: 0.2s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Total Leads') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">{{ $stats['leads_count'] }}</p>
                                <p class="text-sm text-green-600 font-medium">+8% from last month</p>
                            </div>
                            <div class="icon-container bg-gradient-to-br from-green-500 to-green-600 text-white">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-emerald animate-bounce-in" style="animation-delay: 0.3s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Active Leads') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">{{ $stats['active_leads_count'] }}</p>
                                <p class="text-sm text-green-600 font-medium">+15% from last month</p>
                            </div>
                            <div class="icon-container bg-gradient-to-br from-emerald-500 to-emerald-600 text-white">
                                <i class="fas fa-user-tag"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-yellow animate-bounce-in" style="animation-delay: 0.4s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Revenue') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">${{ number_format($stats['revenue_potential']) }}</p>
                                <p class="text-sm text-green-600 font-medium">+23% from last month</p>
                            </div>
                            <div class="icon-container bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="content-card p-6 animate-slide-up" style="animation-delay: 0.5s;">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-chart-pie text-green-600"></i>
                            </div>
                            {{ __('Lead Status Distribution') }}
                        </h2>
                        <div class="h-64">
                            <canvas id="leadStatusChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="content-card p-6 animate-slide-up" style="animation-delay: 0.6s;">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-chart-line text-blue-600"></i>
                            </div>
                            {{ __('Monthly Performance') }}
                        </h2>
                        <div class="h-64">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leads Stats -->
            <div x-show="activeTab === 'leads'">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="glass-card card-hover rounded-2xl p-6 stat-card-blue text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-blue-100 text-sm font-medium uppercase tracking-wide">{{ __('Total Properties') }}</h3>
                                <p class="text-3xl font-bold">{{ $stats['properties_count'] }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <i class="fas fa-building text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card card-hover rounded-2xl p-6 stat-card-green text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-green-100 text-sm font-medium uppercase tracking-wide">{{ __('Total Leads') }}</h3>
                                <p class="text-3xl font-bold">{{ $stats['leads_count'] }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card card-hover rounded-2xl p-6 stat-card-emerald text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-emerald-100 text-sm font-medium uppercase tracking-wide">{{ __('Active Leads') }}</h3>
                                <p class="text-3xl font-bold">{{ $stats['active_leads_count'] }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <i class="fas fa-user-tag text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card card-hover rounded-2xl p-6 stat-card-yellow text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-yellow-100 text-sm font-medium uppercase tracking-wide">{{ __('Potential Revenue') }}</h3>
                                <p class="text-3xl font-bold">{{ number_format($stats['revenue_potential']) }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <i class="fas fa-dollar-sign text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Recent Leads - LEFT SECTION -->
                    <div class="lg:col-span-2">
                        <div class="glass-card rounded-2xl shadow-xl">
                            <div class="border-b border-violet-100 px-6 py-4 bg-gradient-to-r from-violet-50 to-purple-50 rounded-t-2xl">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                        <i class="fas fa-users mr-3 text-accent"></i>
                                        {{ __('Recent Leads') }}
                                    </h2>
                                    <a href="{{ route('leads.index') }}" class="text-accent hover:text-accent-dark font-medium transition-colors">
                                        {{ __('View All') }} <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @forelse($recent_leads as $lead)
                                        <div class="glass-effect hover:shadow-xl transition-all duration-300 rounded-xl p-5 border border-emerald-100">
                                            <!-- Status indicator -->
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                                    {{ strtoupper(substr($lead->first_name, 0, 1)) }}{{ strtoupper(substr($lead->last_name, 0, 1)) }}
                                                </div>
                                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                                    {{ $lead->status == 'new' ? 'bg-blue-100 text-blue-700' : '' }}
                                                    {{ $lead->status == 'contacted' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                                    {{ $lead->status == 'qualified' ? 'bg-purple-100 text-purple-700' : '' }}
                                                    {{ $lead->status == 'proposal' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                    {{ $lead->status == 'negotiation' ? 'bg-orange-100 text-orange-700' : '' }}
                                                    {{ $lead->status == 'won' ? 'bg-green-100 text-green-700' : '' }}
                                                    {{ $lead->status == 'lost' ? 'bg-red-100 text-red-700' : '' }}
                                                ">
                                                    {{ __(ucfirst($lead->status)) }}
                                                </span>
                                            </div>
                                            
                                            <div>
                                                <h3 class="font-bold text-gray-800 text-lg mb-3">
                                                    <a href="{{ route('leads.show', $lead->id) }}" class="hover:text-accent transition-colors">
                                                        {{ $lead->first_name }} {{ $lead->last_name }}
                                                    </a>
                                                </h3>
                                                
                                                <div class="space-y-2">
                                                    @if($lead->email)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-envelope text-emerald-600"></i>
                                                        </div>
                                                        <span class="truncate">{{ $lead->email }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($lead->phone)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-phone text-blue-600"></i>
                                                        </div>
                                                        <span>{{ $lead->phone }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($lead->interestedProperty)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-home text-purple-600"></i>
                                                        </div>
                                                        <span class="truncate">{{ $lead->interestedProperty->name }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($lead->budget)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-dollar-sign text-yellow-600"></i>
                                                        </div>
                                                        <span class="font-semibold">{{ number_format($lead->budget) }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="mt-4 flex items-center justify-between">
                                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                        {{ $lead->created_at->diffForHumans() }}
                                                    </span>
                                                    
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('leads.show', $lead->id) }}" class="w-8 h-8 bg-blue-500 text-white rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                                                            <i class="fas fa-eye text-sm"></i>
                                                        </a>
                                                        <a href="{{ route('leads.edit', $lead->id) }}" class="w-8 h-8 bg-amber-500 text-white rounded-lg flex items-center justify-center hover:bg-amber-600 transition-colors">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-2 p-8 text-center">
                                            <div class="text-gray-400 mb-4">
                                                <i class="fas fa-users text-4xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-lg">{{ __('No leads found.') }}</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- RIGHT SIDEBAR -->
                    <div class="space-y-6">
                        <!-- Upcoming Events -->
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="border-b px-6 py-3">
                                <h2 class="text-lg font-semibold text-gray-800">{{ __('Upcoming Events') }}</h2>
                            </div>
                            
                            <div class="p-4">
                                @forelse($upcoming_events as $event)
                                    <div class="mb-3 pb-3 border-b last:border-0 last:pb-0 last:mb-0">
                                        <div class="flex">
                                            <div class="mr-3">
                                                <div class="w-10 h-10 rounded-full 
                                                    {{ $event->event_type == 'meeting' ? 'bg-blue-100 text-blue-600' : '' }}
                                                    {{ $event->event_type == 'call' ? 'bg-green-100 text-green-600' : '' }}
                                                    {{ $event->event_type == 'email' ? 'bg-purple-100 text-purple-600' : '' }}
                                                    {{ $event->event_type == 'follow_up' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                                    {{ $event->event_type == 'other' ? 'bg-gray-100 text-gray-600' : '' }}
                                                    flex items-center justify-center">
                                                    <i class="fas 
                                                        {{ $event->event_type == 'meeting' ? 'fa-handshake' : '' }}
                                                        {{ $event->event_type == 'call' ? 'fa-phone' : '' }}
                                                        {{ $event->event_type == 'email' ? 'fa-envelope' : '' }}
                                                        {{ $event->event_type == 'follow_up' ? 'fa-reply' : '' }}
                                                        {{ $event->event_type == 'other' ? 'fa-calendar' : '' }}
                                                    "></i>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-grow">
                                                <h3 class="font-medium text-gray-800">{{ $event->title }}</h3>
                                                
                                                <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
                                                    <span>
                                                        <i class="far fa-clock mr-1"></i> 
                                                        {{ optional($event->start_date)->format('M d, Y g:i A') ?? 'N/A' }}
                                                    </span>
                                                    
                                                    @if($event->lead)
                                                    <a href="{{ route('leads.show', $event->lead->id) }}" class="text-blue-600 hover:underline">
                                                        {{ $event->lead->first_name }} {{ $event->lead->last_name }}
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">{{ __('No upcoming events') }}</p>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Lead Distribution by Status Chart -->
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Lead Status Distribution') }}</h2>
                            <div>
                                <canvas id="leadStatusChart" height="200"></canvas>
                            </div>
                        </div>
                        
                        <!-- Lead Distribution by Source Chart -->
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Lead Source Distribution') }}</h2>
                            <div>
                                <canvas id="leadSourceChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Properties Stats -->
            <div x-show="activeTab === 'properties'" class="mt-6">
                <!-- Property Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="stat-card stat-blue animate-bounce-in" style="animation-delay: 0.1s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Total Properties') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">{{ $stats['properties_count'] }}</p>
                                <span class="text-sm text-green-600 font-medium">+12% from last month</span>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-building text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-green animate-bounce-in" style="animation-delay: 0.2s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Total Sale Value') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">${{ number_format($propertyStats['total_sale_value']) }}</p>
                                <span class="text-sm text-green-600 font-medium">+8% from last month</span>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-tag text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-purple animate-bounce-in" style="animation-delay: 0.3s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Total Rent Value') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">${{ number_format($propertyStats['total_rent_value']) }}</p>
                                <span class="text-sm text-green-600 font-medium">+15% from last month</span>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-money-bill-wave text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-indigo animate-bounce-in" style="animation-delay: 0.4s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Featured Properties') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">{{ $propertyStats['featured_properties'] }}</p>
                                <span class="text-sm text-green-600 font-medium">+23% from last month</span>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-star text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Property Type Distribution -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Property Types') }}</h2>
                        <div>
                            <canvas id="propertyTypeChart" height="250"></canvas>
                        </div>
                    </div>
                    
                    <!-- Sale vs Rent Distribution -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Sale vs Rent Properties') }}</h2>
                        <div>
                            <canvas id="saleRentChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Price Range Distribution (Sale) -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Sale Price Ranges') }}</h2>
                        <div>
                            <canvas id="salePriceChart" height="250"></canvas>
                        </div>
                    </div>
                    
                    <!-- Price Range Distribution (Rent) -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Rent Price Ranges') }}</h2>
                        <div>
                            <canvas id="rentPriceChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 mt-6">
                    <!-- Monthly Listings -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Monthly Property Listings') }}</h2>
                        <div>
                            <canvas id="monthlyListingsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Properties -->
                <div class="mt-6 bg-white rounded-lg shadow-sm">
                    <div class="border-b px-6 py-3 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('Recently Added Properties') }}</h2>
                        <a href="{{ route('properties.index') }}" class="text-blue-600 text-sm hover:underline">{{ __('View All') }}</a>
                    </div>
                    
                    <div class="overflow-hidden">
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('For') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Location') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Added') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($recent_properties as $property)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('properties.show', $property->id) }}" class="text-blue-600 hover:underline font-medium">
                                                        {{ Str::limit($property->name, 30) }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-2 text-sm">{{ ucfirst($property->type ?? 'N/A') }}</td>
                                                <td class="px-4 py-2 text-sm">
                                                    <span class="px-2 py-1 text-xs rounded-full {{ $property->unit_for === 'sale' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                                        {{ ucfirst($property->unit_for ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 text-sm">{{ number_format($property->price ?? 0) }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $property->area ?? $property->location ?? 'N/A' }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $property->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                                    {{ __('No properties found.') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opportunities Stats Panel -->
            <div x-show="activeTab === 'opportunities'" class="mt-6">
                <!-- Opportunities Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Opportunities -->
                    <div class="stat-card stat-blue animate-bounce-in" style="animation-delay: 0.1s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Total Opportunities') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">{{ $stats['opportunities_count'] ?? 0 }}</p>
                                <span class="text-sm text-green-600 font-medium">+18% from last month</span>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-handshake text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Won Opportunities -->
                    <div class="stat-card stat-green animate-bounce-in" style="animation-delay: 0.2s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Won Opportunities') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">{{ $stats['won_opportunities'] ?? 0 }}</p>
                                <span class="text-sm text-green-600 font-medium">+25% from last month</span>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-trophy text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pipeline Value -->
                    <div class="stat-card stat-purple animate-bounce-in" style="animation-delay: 0.3s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Pipeline Value') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">${{ number_format($stats['pipeline_value'] ?? 0) }}</p>
                                <span class="text-sm text-green-600 font-medium">+12% from last month</span>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-chart-line text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Conversion Rate -->
                    <div class="stat-card stat-indigo animate-bounce-in" style="animation-delay: 0.4s;">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide mb-2">{{ __('Conversion Rate') }}</h3>
                                <p class="text-4xl font-bold text-gray-800 mb-1">{{ $stats['conversion_rate'] ?? 0 }}%</p>
                                <span class="text-sm text-green-600 font-medium">+8% from last month</span>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-percentage text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Opportunity Stage Distribution -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Opportunity Stages') }}</h2>
                        <div>
                            <canvas id="opportunityStageChart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Monthly Win Rate -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Monthly Win Rate') }}</h2>
                        <div>
                            <canvas id="monthlyWinRateChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Opportunities Table -->
                <div class="mt-6 bg-white rounded-lg shadow-sm">
                    <div class="border-b px-6 py-3 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('Recent Opportunities') }}</h2>
                        <a href="{{ route('opportunities.index') }}" class="text-blue-600 text-sm hover:underline">{{ __('View All') }}</a>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Stage') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Value') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Close Date') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Assigned To') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recent_opportunities ?? [] as $opportunity)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <a href="{{ route('opportunities.show', $opportunity->id) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $opportunity->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    {{ $opportunity->stage == 'initial' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $opportunity->stage == 'qualified' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $opportunity->stage == 'proposal' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $opportunity->stage == 'negotiation' ? 'bg-purple-100 text-purple-800' : '' }}
                                                    {{ $opportunity->stage == 'closed_won' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $opportunity->stage == 'closed_lost' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ __(ucfirst(str_replace('_', ' ', $opportunity->stage))) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($opportunity->value) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $opportunity->close_date?->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $opportunity->assignedTo?->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                {{ __('No recent opportunities') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add this code to ensure the tabs are working correctly
            console.log("Dashboard script loaded");
            
            // Set up tab functionality manually if Alpine.js isn't working
            const propertyTab = document.querySelector('[x-data] li:first-child');
            const leadTab = document.querySelector('[x-data] li:nth-child(2)');
            const propertiesPanel = document.querySelector('[x-show="activeTab === \'properties\'"]');
            const leadsPanel = document.querySelector('[x-show="activeTab === \'leads\'"]');
            
            if (propertyTab && leadTab && propertiesPanel && leadsPanel) {
                console.log("Found tab elements");
                
                // Set properties tab as active by default
                propertyTab.classList.add('border-b-2', 'border-blue-500');
                propertyTab.querySelector('span').classList.add('text-blue-600', 'font-medium');
                propertyTab.querySelector('span').classList.remove('text-gray-500');
                
                leadTab.classList.remove('border-b-2', 'border-blue-500');
                leadTab.querySelector('span').classList.add('text-gray-500');
                leadTab.querySelector('span').classList.remove('text-blue-600', 'font-medium');
                
                propertiesPanel.style.display = 'block';
                leadsPanel.style.display = 'none';
                
                // Manually toggle tabs if needed
                propertyTab.addEventListener('click', function() {
                    console.log("Property tab clicked");
                    propertyTab.classList.add('border-b-2', 'border-blue-500');
                    leadTab.classList.remove('border-b-2', 'border-blue-500');
                    
                    propertyTab.querySelector('span').classList.add('text-blue-600', 'font-medium');
                    propertyTab.querySelector('span').classList.remove('text-gray-500');
                    leadTab.querySelector('span').classList.add('text-gray-500');
                    leadTab.querySelector('span').classList.remove('text-blue-600', 'font-medium');
                    
                    propertiesPanel.style.display = 'block';
                    leadsPanel.style.display = 'none';
                    
                    // Trigger resize to ensure charts render correctly
                    window.dispatchEvent(new Event('resize'));
                });
                
                leadTab.addEventListener('click', function() {
                    console.log("Lead tab clicked");
                    leadTab.classList.add('border-b-2', 'border-blue-500');
                    propertyTab.classList.remove('border-b-2', 'border-blue-500');
                    
                    leadTab.querySelector('span').classList.add('text-blue-600', 'font-medium');
                    leadTab.querySelector('span').classList.remove('text-gray-500');
                    propertyTab.querySelector('span').classList.add('text-gray-500');
                    propertyTab.querySelector('span').classList.remove('text-blue-600', 'font-medium');
                    
                    leadsPanel.style.display = 'block';
                    propertiesPanel.style.display = 'none';
                });
            }
            
            // Set up the lead status chart
            const statusCtx = document.getElementById('leadStatusChart').getContext('2d');
            
            // Define status labels and color map
            const statusLabels = {
                'new': '{{ __("New") }}',
                'contacted': '{{ __("Contacted") }}',
                'qualified': '{{ __("Qualified") }}',
                'proposal': '{{ __("Proposal") }}',
                'negotiation': '{{ __("Negotiation") }}',
                'won': '{{ __("Won") }}',
                'lost': '{{ __("Lost") }}'
            };
            
            const statusColorMap = {
                'new': '#3B82F6', // blue-500
                'contacted': '#6366F1', // indigo-500
                'qualified': '#8B5CF6', // purple-500
                'proposal': '#F59E0B', // yellow-500
                'negotiation': '#F97316', // orange-500
                'won': '#22C55E', // green-500
                'lost': '#EF4444', // red-500
            };
            
            // Prepare data for status chart
            const leadStatusData = @json($lead_statuses);
            const statusLabelsArray = [];
            const statusDataArray = [];
            const statusBackgroundColors = [];
            
            Object.entries(statusLabels).forEach(([status, label]) => {
                statusLabelsArray.push(label);
                statusDataArray.push(leadStatusData[status] || 0);
                statusBackgroundColors.push(statusColorMap[status]);
            });
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabelsArray,
                    datasets: [{
                        data: statusDataArray,
                        backgroundColor: statusBackgroundColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
            
            // Set up the lead source chart
            const sourceCtx = document.getElementById('leadSourceChart').getContext('2d');
            
            // Predefined color palette for sources
            const sourceColors = [
                '#10B981', // emerald-500
                '#F59E0B', // amber-500
                '#3B82F6', // blue-500
                '#EC4899', // pink-500
                '#8B5CF6', // purple-500
                '#14B8A6', // teal-500
                '#F97316', // orange-500
                '#6366F1', // indigo-500
                '#EF4444', // red-500
                '#84CC16', // lime-500
                '#06B6D4', // cyan-500
                '#A855F7', // purple-500
                '#64748B', // slate-500
            ];
            
            // Prepare data for source chart
            const leadSourceData = @json($lead_sources);
            const sourceLabels = Object.keys(leadSourceData);
            const sourceData = Object.values(leadSourceData);
            
            // Generate colors based on number of sources
            const sourceBackgroundColors = sourceLabels.map((_, index) => 
                sourceColors[index % sourceColors.length]
            );
            
            new Chart(sourceCtx, {
                type: 'doughnut',
                data: {
                    labels: sourceLabels,
                    datasets: [{
                        data: sourceData,
                        backgroundColor: sourceBackgroundColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });

            // PROPERTY CHARTS
            
            // Property Type Distribution
            const propertyTypeCtx = document.getElementById('propertyTypeChart').getContext('2d');
            const propertyTypeData = @json($propertyTypes);
            const propertyTypeLabels = Object.keys(propertyTypeData).map(type => type ? type.charAt(0).toUpperCase() + type.slice(1) : 'Other');
            const propertyTypeValues = Object.values(propertyTypeData);
            
            const propertyTypeColors = [
                '#3B82F6', // blue-500
                '#10B981', // emerald-500
                '#F59E0B', // amber-500
                '#8B5CF6', // violet-500
                '#EC4899', // pink-500
                '#F97316', // orange-500
            ];
            
            new Chart(propertyTypeCtx, {
                type: 'pie',
                data: {
                    labels: propertyTypeLabels,
                    datasets: [{
                        data: propertyTypeValues,
                        backgroundColor: propertyTypeColors.slice(0, propertyTypeLabels.length),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            
            // Sale vs Rent Distribution
            const saleRentCtx = document.getElementById('saleRentChart').getContext('2d');
            const saleRentData = @json($propertyUnitFor);
            const saleRentLabels = Object.keys(saleRentData).map(type => type ? type.charAt(0).toUpperCase() + type.slice(1) : 'Other');
            const saleRentValues = Object.values(saleRentData);
            
            new Chart(saleRentCtx, {
                type: 'doughnut',
                data: {
                    labels: saleRentLabels,
                    datasets: [{
                        data: saleRentValues,
                        backgroundColor: ['#3B82F6', '#10B981'], // Blue for sale, green for rent
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            
            // Price Range Charts
            const salePriceCtx = document.getElementById('salePriceChart').getContext('2d');
            const salePriceRanges = @json($salePriceRanges);
            
            new Chart(salePriceCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(salePriceRanges),
                    datasets: [{
                        label: '{{ __("Number of Properties") }}',
                        data: Object.values(salePriceRanges),
                        backgroundColor: '#3B82F6',
                        borderColor: '#2563EB',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            const rentPriceCtx = document.getElementById('rentPriceChart').getContext('2d');
            const rentPriceRanges = @json($rentPriceRanges);
            
            new Chart(rentPriceCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(rentPriceRanges),
                    datasets: [{
                        label: '{{ __("Number of Properties") }}',
                        data: Object.values(rentPriceRanges),
                        backgroundColor: '#10B981',
                        borderColor: '#059669',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Monthly Listings Chart
            const monthlyListingsCtx = document.getElementById('monthlyListingsChart').getContext('2d');
            const propertyTimeData = @json($propertyTimeData);
            
            new Chart(monthlyListingsCtx, {
                type: 'line',
                data: {
                    labels: propertyTimeData.labels,
                    datasets: [{
                        label: '{{ __("Properties Listed") }}',
                        data: propertyTimeData.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: '#3B82F6',
                        borderWidth: 2,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Opportunity Stage Distribution Chart
            const opportunityStageCtx = document.getElementById('opportunityStageChart').getContext('2d');
            const opportunityStageData = @json($opportunity_stages ?? []);
            
            new Chart(opportunityStageCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(opportunityStageData),
                    datasets: [{
                        data: Object.values(opportunityStageData),
                        backgroundColor: [
                            '#94A3B8', // Initial - slate
                            '#3B82F6', // Qualified - blue
                            '#F59E0B', // Proposal - amber
                            '#8B5CF6', // Negotiation - purple
                            '#22C55E', // Closed Won - green
                            '#EF4444', // Closed Lost - red
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });

            // Monthly Win Rate Chart
            const monthlyWinRateCtx = document.getElementById('monthlyWinRateChart').getContext('2d');
            const monthlyWinRateData = @json($monthly_win_rate ?? []);
            
            new Chart(monthlyWinRateCtx, {
                type: 'line',
                data: {
                    labels: monthlyWinRateData.labels,
                    datasets: [{
                        label: '{{ __("Win Rate") }}',
                        data: monthlyWinRateData.data,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
