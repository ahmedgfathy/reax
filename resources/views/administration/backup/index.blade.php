@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">{{ __('Backup & Restore') }}</h1>
        <button onclick="document.getElementById('createBackupForm').submit()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>{{ __('Create Backup') }}
        </button>
        <form id="createBackupForm" action="{{ route('administration.backup.create') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <!-- Disk Space Usage -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-hdd text-blue-500 mr-2"></i>{{ __('Disk Space Usage') }}
        </h2>
        <div class="flex items-center">
            <div class="flex-1">
                <div class="h-4 bg-gray-200 rounded-full">
                    <div class="h-4 bg-blue-500 rounded-full" style="width: {{ ($diskSpace['used'] / ($diskSpace['total'] * 1024)) * 100 }}%"></div>
                </div>
            </div>
            <div class="ml-4">
                <span class="text-sm text-gray-600">{{ $diskSpace['used'] }} MB / {{ $diskSpace['total'] }} GB</span>
            </div>
        </div>
    </div>

    <!-- Backup List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-history text-blue-500 mr-2"></i>{{ __('Backup History') }}
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Backup File') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Size') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Created') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($backups as $backup)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $backup['file_name'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ number_format($backup['file_size'] / 1024 / 1024, 2) }} MB
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500" title="{{ $backup['last_modified'] }}">
                                    {{ $backup['age'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('administration.backup.download', $backup['file_name']) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('administration.backup.restore', $backup['file_name']) }}" 
                                   class="text-green-600 hover:text-green-900"
                                   onclick="return confirm('{{ __('Are you sure you want to restore this backup? This will overwrite your current database.') }}')">
                                    <i class="fas fa-undo"></i>
                                </a>
                                <form action="{{ route('administration.backup.destroy', $backup['file_name']) }}" 
                                      method="POST" 
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('{{ __('Are you sure you want to delete this backup?') }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ __('No backups found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
