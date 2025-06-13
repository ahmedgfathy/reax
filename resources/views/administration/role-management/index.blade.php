@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Role & Permission Management') }}</h1>
        <p class="text-gray-600">{{ __('Manage roles, profiles, and user permissions for your organization') }}</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('profiles')" id="profiles-tab" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                    {{ __('Profiles Management') }}
                </button>
                <button onclick="showTab('users')" id="users-tab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    {{ __('User Management') }}
                </button>
                <button onclick="showTab('permissions')" id="permissions-tab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    {{ __('Permissions') }}
                </button>
                <button onclick="showTab('hierarchy')" id="hierarchy-tab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    {{ __('Organization Hierarchy') }}
                </button>
            </nav>
        </div>
    </div>

    <!-- Profiles Management Tab -->
    <div id="profiles-content" class="tab-content">
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Create New Profile') }}</h2>
            </div>
            
            <form action="{{ route('administration.profiles.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Profile Name') }}</label>
                        <input type="text" name="name" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Display Name') }}</label>
                        <input type="text" name="display_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Level') }}</label>
                        <select name="level" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('Select Level') }}</option>
                            <option value="administration">{{ __('Administration') }}</option>
                            <option value="manager">{{ __('Manager') }}</option>
                            <option value="team_leader">{{ __('Team Leader') }}</option>
                            <option value="employee">{{ __('Employee') }}</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
                
                <!-- Permissions Selection -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Assign Permissions') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $module => $modulePermissions)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3">{{ ucfirst($module) }}</h4>
                                @foreach($modulePermissions as $permission)
                                    <div class="flex items-center mb-2">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="perm_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ __('Create Profile') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Profiles -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Existing Profiles') }}</h2>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Profile') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Level') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Users') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Permissions') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($profiles as $profile)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $profile->display_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $profile->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($profile->level === 'administration') bg-red-100 text-red-800
                                            @elseif($profile->level === 'manager') bg-blue-100 text-blue-800
                                            @elseif($profile->level === 'team_leader') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $profile->level)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $profile->users->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $profile->permissions->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('administration.profiles.show', $profile) }}" class="text-blue-600 hover:text-blue-900 mr-3">{{ __('View') }}</a>
                                        <a href="{{ route('administration.profiles.edit', $profile) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Edit') }}</a>
                                        @if($profile->users->count() === 0)
                                            <form action="{{ route('administration.profiles.destroy', $profile) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management Tab -->
    <div id="users-content" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('User Role & Profile Assignment') }}</h2>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Current Role') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Profile') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Manager') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($user->role === 'admin') bg-red-100 text-red-800
                                            @elseif($user->role === 'manager') bg-blue-100 text-blue-800
                                            @elseif($user->role === 'team_leader') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->profile ? $user->profile->display_name : __('No Profile') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->manager ? $user->manager->name : __('No Manager') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="openUserModal({{ $user->id }})" class="text-blue-600 hover:text-blue-900">
                                            {{ __('Manage') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Tab -->
    <div id="permissions-content" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('System Permissions') }}</h2>
                <button onclick="openPermissionModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    {{ __('Add Permission') }}
                </button>
            </div>
            
            <div class="p-6">
                @foreach($permissions as $module => $modulePermissions)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">{{ ucfirst($module) }} {{ __('Module') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($modulePermissions as $permission)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $permission->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $permission->description }}</p>
                                    <div class="mt-2">
                                        <span class="text-xs text-gray-500">{{ __('Actions') }}: {{ implode(', ', $permission->actions) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Hierarchy Tab -->
    <div id="hierarchy-content" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Organization Hierarchy') }}</h2>
            </div>
            
            <div class="p-6">
                <a href="{{ route('administration.role-management.hierarchy') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    {{ __('View Full Hierarchy') }}
                </a>
                
                <div class="mt-6">
                    <p class="text-gray-600">{{ __('Click the button above to view the complete organizational hierarchy with all reporting relationships.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Management Modal -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Manage User') }}</h3>
            
            <form id="userForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Role') }}</label>
                    <select name="role" id="userRole" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="admin">{{ __('Administrator') }}</option>
                        <option value="manager">{{ __('Manager') }}</option>
                        <option value="team_leader">{{ __('Team Leader') }}</option>
                        <option value="employee">{{ __('Employee') }}</option>
                        <option value="agent">{{ __('Agent') }}</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Profile') }}</label>
                    <select name="profile_id" id="userProfile" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">{{ __('No Profile') }}</option>
                        @foreach($profiles as $profile)
                            <option value="{{ $profile->id }}">{{ $profile->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeUserModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ __('Update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

// User modal functionality
function openUserModal(userId) {
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('userForm').action = `/administration/role-management/users/${userId}/role`;
}

function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
}

// Permission modal functionality
function openPermissionModal() {
    // Implementation for permission modal
    alert('Permission creation modal - to be implemented');
}
</script>
@endsection
