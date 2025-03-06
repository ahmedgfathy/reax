@extends('layouts.app')

@section('content')
<!-- Profile Edit Header -->
<div class="bg-white shadow-sm border-b">
    <div class="container mx-auto py-4 px-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('Edit Profile') }}</h1>
            <a href="{{ route('profile.show') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Profile') }}
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    {{ __('Profile Information') }}
                </h2>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full p-2 border rounded-md @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full p-2 border rounded-md @error('email') border-red-500 @enderror" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone') }} ({{ __('Optional') }})</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full p-2 border rounded-md @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Address') }} ({{ __('Optional') }})</label>
                            <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}" class="w-full p-2 border rounded-md @error('address') border-red-500 @enderror">
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('Update Profile') }}
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Update Password Form -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    {{ __('Update Password') }}
                </h2>
                
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Current Password') }}
                        </label>
                        <input type="password" id="current_password" name="current_password" 
                            class="w-full p-2 border rounded-md @error('current_password') border-red-500 @enderror" required>
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('New Password') }}
                        </label>
                        <input type="password" id="password" name="password" 
                            class="w-full p-2 border rounded-md @error('password') border-red-500 @enderror" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Confirm New Password') }}
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                            class="w-full p-2 border rounded-md" required>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('Update Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Profile Picture Update -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    {{ __('Profile Picture') }}
                </h2>
                
                <div class="text-center mb-4">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                        alt="{{ auth()->user()->name }}" 
                        class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-blue-100">
                </div>
                
                <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Upload New Picture') }}
                        </label>
                        <input type="file" id="avatar" name="avatar" 
                            class="w-full p-2 border rounded-md @error('avatar') border-red-500 @enderror" 
                            accept="image/*" required>
                        @error('avatar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ __('Max 1MB. JPEG, PNG, or GIF.') }}</p>
                    </div>
                    
                    <div class="flex justify-center">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('Upload Photo') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
