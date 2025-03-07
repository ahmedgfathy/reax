@extends('layouts.app')

@section('content')
<!-- Administration Header -->
<div class="bg-white shadow-sm border-b">
    <div class="container mx-auto py-4 px-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Administration') }}</h1>
            <div class="flex space-x-2">
                <!-- Add buttons for Agencies and Employees -->
                <a href="{{ route('administration.agencies.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-building mr-2"></i> {{ __('Agencies') }}
                </a>
                <a href="{{ route('administration.employees.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                    <i class="fas fa-users mr-2"></i> {{ __('Employees') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto p-6">
    <!-- Add content for Administration page here -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800">{{ __('Administration Dashboard') }}</h2>
        <p class="text-gray-600">{{ __('Manage agencies and employees from this dashboard.') }}</p>
    </div>
    <a href="{{ route('administration.employees.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
        <h3 class="text-xl font-semibold mb-4">{{ __('Employee Management') }}</h3>
        <p class="text-gray-600">{{ __('Manage your team members and their access levels') }}</p>
    </a>
</div>
@endsection
