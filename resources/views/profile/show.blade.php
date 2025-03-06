@extends('layouts.app')

@section('content')
<!-- Profile Header -->
<div class="bg-white shadow-sm border-b">
    <div class="container mx-auto py-4 px-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Profile') }}</h1>
            <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                <i class="fas fa-edit mr-2"></i> {{ __('Edit Profile') }}
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-center">
                    <div class="relative inline-block">
                        <img src="{{ auth()->user()->avatar_url }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-blue-100">
                    </div>
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                </div>
                
                <div class="mt-6 border-t pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-600">{{ __('Member Since') }}</span>
                        <span class="font-medium">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                    </div>
                    @if(auth()->user()->phone)
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-600">{{ __('Phone') }}</span>
                        <span class="font-medium">{{ auth()->user()->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activity & Stats -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Activity Overview') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Leads Count -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600">{{ __('Total Leads') }}</p>
                                <p class="text-2xl font-bold text-blue-700">{{ auth()->user()->leads()->count() }}</p>
                            </div>
                            <i class="fas fa-users text-2xl text-blue-400"></i>
                        </div>
                    </div>

                    <!-- Properties Count -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600">{{ __('Properties') }}</p>
                                <p class="text-2xl font-bold text-green-700">{{ auth()->user()->properties()->count() }}</p>
                            </div>
                            <i class="fas fa-home text-2xl text-green-400"></i>
                        </div>
                    </div>

                    <!-- Reports Count -->
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-600">{{ __('Reports') }}</p>
                                <p class="text-2xl font-bold text-purple-700">{{ auth()->user()->reports()->count() }}</p>
                            </div>
                            <i class="fas fa-chart-bar text-2xl text-purple-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Recent Activity') }}</h3>
                <div class="space-y-4">
                    @forelse(auth()->user()->recentActivity() as $activity)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="mr-4">
                                <i class="fas fa-{{ $activity->icon }} text-{{ $activity->color }}-500"></i>
                            </div>
                            <div class="flex-grow">
                                <p class="text-gray-800">{{ $activity->description }}</p>
                                <p class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">{{ __('No recent activity') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
