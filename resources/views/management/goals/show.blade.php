@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $goal->title }}</h1>
                    <p class="mt-2 text-sm text-gray-600">Goal Details and Progress</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('management.goals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Goals
                    </a>
                </div>
            </div>
        </div>

        <!-- Goal Info -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Goal Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $goal->title }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($goal->category) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($goal->status === 'active') bg-green-100 text-green-800
                            @elseif($goal->status === 'completed') bg-blue-100 text-blue-800
                            @elseif($goal->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($goal->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Priority</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($goal->priority === 'critical') bg-red-100 text-red-800
                            @elseif($goal->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($goal->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($goal->priority) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Target Value</label>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($goal->target_value) }} {{ $goal->unit }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Value</label>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($goal->current_value) }} {{ $goal->unit }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $goal->start_date ? $goal->start_date->format('M d, Y') : 'Not set' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $goal->end_date ? $goal->end_date->format('M d, Y') : 'Not set' }}</p>
                    </div>
                </div>
                @if($goal->description)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $goal->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Progress Section -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Progress Tracking</h3>
            </div>
            <div class="p-6">
                @php
                    $progressPercentage = $goal->target_value > 0 ? min(100, ($goal->current_value / $goal->target_value) * 100) : 0;
                @endphp
                
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Goal Progress</span>
                        <span class="text-sm text-gray-500">{{ number_format($progressPercentage, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($goal->target_value) }}</div>
                        <div class="text-sm text-gray-500">Target</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($goal->current_value) }}</div>
                        <div class="text-sm text-gray-500">Current</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($goal->target_value - $goal->current_value) }}</div>
                        <div class="text-sm text-gray-500">Remaining</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Milestones -->
        @if($goal->milestones && count(json_decode($goal->milestones, true) ?? []) > 0)
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Milestones</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach(json_decode($goal->milestones, true) as $milestone)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-flag text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $milestone['title'] ?? 'Milestone' }}</h4>
                            <p class="text-sm text-gray-600">{{ $milestone['description'] ?? 'No description' }}</p>
                        </div>
                        <div class="text-sm text-gray-500">
                            Target: {{ $milestone['target_date'] ?? 'No date set' }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
