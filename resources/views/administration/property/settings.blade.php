@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Property Settings</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stats Cards -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-medium">Total Properties</h3>
                <div class="flex items-center mt-2">
                    <span class="text-2xl font-semibold text-gray-800">{{ $stats['total'] }}</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-medium">Published Properties</h3>
                <div class="flex items-center mt-2">
                    <span class="text-2xl font-semibold text-green-600">{{ $stats['published'] }}</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-medium">For Sale</h3>
                <div class="flex items-center mt-2">
                    <span class="text-2xl font-semibold text-blue-600">{{ $stats['for_sale'] }}</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-medium">For Rent</h3>
                <div class="flex items-center mt-2">
                    <span class="text-2xl font-semibold text-purple-600">{{ $stats['for_rent'] }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Property Types Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Property Types</h2>
                <div class="space-y-4">
                    @foreach($propertyTypes as $type)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-700">{{ $type }}</span>
                            <div class="flex items-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Add New Property Type
                    </button>
                </div>
            </div>

            <!-- Property Settings Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">General Settings</h2>
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">
                            Default Currency
                        </label>
                        <select name="default_currency" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="EGP">EGP</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">
                            Property Approval Required
                        </label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="require_approval" class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2 text-gray-700">Require admin approval for new properties</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">
                            Default Status
                        </label>
                        <select name="default_status" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="available">Available</option>
                            <option value="draft">Draft</option>
                            <option value="pending">Pending Review</option>
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
