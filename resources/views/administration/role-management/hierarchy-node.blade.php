{{-- Recursive component for displaying hierarchy nodes --}}
<div class="user-card {{ $user->role }}" data-user-id="{{ $user->id }}">
    <div class="user-info">
        <div class="user-name">{{ $user->name }}</div>
        <div class="user-email">{{ $user->email }}</div>
    </div>
    
    <div class="user-meta">
        <span class="role-badge {{ $user->role }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
        @if($user->subordinates->count() > 0)
            <span class="subordinate-count">{{ $user->subordinates->count() }} {{ __('subordinate(s)') }}</span>
        @endif
    </div>
    
    @if($user->profile)
        <div class="mt-2 text-xs text-gray-600">
            {{ __('Profile') }}: {{ $user->profile->display_name }}
        </div>
    @endif
</div>

@if($user->subordinates->count() > 0)
    <div class="subordinates">
        @foreach($user->subordinates as $subordinate)
            @include('administration.role-management.hierarchy-node', ['user' => $subordinate, 'level' => $level + 1])
        @endforeach
    </div>
@endif
