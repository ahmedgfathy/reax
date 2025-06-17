@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">{{ __('System Settings') }}</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- System Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                {{ __('System Information') }}
            </h2>
            <div class="space-y-4">
                @foreach($systemInfo as $key => $value)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">{{ __(ucwords(str_replace('_', ' ', $key))) }}</span>
                        <span class="font-medium text-gray-900">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Database Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-database text-green-500 mr-2"></i>
                {{ __('Database Information') }}
            </h2>
            <div class="space-y-4">
                @foreach($databaseStats as $key => $value)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">{{ __(ucwords($key)) }}</span>
                        <span class="font-medium text-gray-900">{{ $value }}</span>
                    </div>
                @endforeach
                <div class="mt-4">
                    <button onclick="window.location.href='{{ route('administration.backup') }}'" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        {{ __('Backup Database') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Cache Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                {{ __('Cache Information') }}
            </h2>
            <div class="space-y-4">
                @foreach($cacheStats as $key => $value)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">{{ __(ucwords($key)) }}</span>
                        <span class="font-medium text-gray-900">{{ $value }}</span>
                    </div>
                @endforeach
                <div class="mt-4 space-x-2">
                    <button onclick="window.location.href='{{ route('administration.cache.clear') }}'" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        {{ __('Clear Cache') }}
                    </button>
                    <button onclick="window.location.href='{{ route('administration.cache.optimize') }}'" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        {{ __('Optimize Cache') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Mail Configuration -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-envelope text-purple-500 mr-2"></i>
                {{ __('Mail Configuration') }}
            </h2>
            <div class="space-y-4">
                @foreach($mailConfig as $key => $value)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">{{ __(ucwords($key)) }}</span>
                        <span class="font-medium text-gray-900">{{ $value ?: 'Not Set' }}</span>
                    </div>
                @endforeach
                <div class="mt-4">
                    <button onclick="window.location.href='{{ route('administration.mail.test') }}'" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                        {{ __('Send Test Email') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
