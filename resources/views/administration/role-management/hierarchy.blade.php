@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Organization Hierarchy') }}</h1>
            <p class="text-gray-600">{{ __('View the complete organizational structure and reporting relationships') }}</p>
        </div>
        <a href="{{ route('administration.role-management.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            {{ __('Back to Role Management') }}
        </a>
    </div>

    <!-- Hierarchy Tree -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="hierarchy-tree">
            @foreach($admins as $admin)
                <div class="hierarchy-node">
                    @include('administration.role-management.hierarchy-node', ['user' => $admin, 'level' => 0])
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.hierarchy-tree {
    font-family: 'Arial', sans-serif;
}

.hierarchy-node {
    margin-left: 0;
}

.user-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px;
    margin: 8px 0;
    display: inline-block;
    min-width: 200px;
    position: relative;
    transition: all 0.3s ease;
}

.user-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.user-card.admin {
    border-color: #dc2626;
    background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
}

.user-card.manager {
    border-color: #2563eb;
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
}

.user-card.team_leader {
    border-color: #059669;
    background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
}

.user-card.employee {
    border-color: #6b7280;
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
}

.subordinates {
    margin-left: 40px;
    position: relative;
}

.subordinates::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #d1d5db;
}

.subordinates .user-card::before {
    content: '';
    position: absolute;
    left: -32px;
    top: 50%;
    width: 20px;
    height: 2px;
    background: #d1d5db;
}

.role-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 12px;
    text-transform: uppercase;
}

.role-badge.admin {
    background: #fecaca;
    color: #991b1b;
}

.role-badge.manager {
    background: #bfdbfe;
    color: #1e40af;
}

.role-badge.team_leader {
    background: #bbf7d0;
    color: #065f46;
}

.role-badge.employee {
    background: #e5e7eb;
    color: #374151;
}

.user-info {
    margin-bottom: 8px;
}

.user-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 2px;
}

.user-email {
    font-size: 0.875rem;
    color: #6b7280;
}

.user-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
}

.subordinate-count {
    font-size: 0.75rem;
    color: #6b7280;
}
</style>
@endsection
