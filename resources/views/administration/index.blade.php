@extends('layouts.admin')

@section('title', __('Administration'))

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Administration') }}</h1>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Employee Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">{{ __('Total Employees') }}</h3>
                    <p class="text-2xl font-semibold">{{ $stats['employees']['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Active Employee Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">{{ __('Active Employees') }}</h3>
                    <p class="text-2xl font-semibold">{{ $stats['employees']['active'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('Recent Employees') }}</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Joined') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($stats['employees']['recent'] as $employee)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $employee->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $employee->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $employee->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('No recent employees') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
