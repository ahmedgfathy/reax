<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Opportunity Details') }}
        </h2>
    </x-slot>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-gray-700">{{ $opportunity->title }}</h3>
                            <a href="{{ route('opportunities.edit', $opportunity) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                {{ __('Edit') }}
                            </a>
                        </div>
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-600">{{ __('Basic Information') }}</h4>
                                <dl class="mt-2 space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                                        <dd class="mt-1">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $opportunity->status === 'won' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $opportunity->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $opportunity->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $opportunity->status === 'negotiation' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ ucfirst($opportunity->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Value') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($opportunity->value, 2) }} EGP</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Probability') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $opportunity->probability }}%</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Expected Close Date') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $opportunity->expected_close_date?->format('Y-m-d') }}</dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-600">{{ __('Related Information') }}</h4>
                                <dl class="mt-2 space-y-2">
                                    @if($opportunity->lead)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Lead') }}</dt>
                                        <dd class="mt-1">
                                            <a href="{{ route('leads.show', $opportunity->lead) }}" class="text-blue-600 hover:text-blue-800">
                                                {{ $opportunity->lead->first_name }} {{ $opportunity->lead->last_name }}
                                            </a>
                                        </dd>
                                    </div>
                                    @endif
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Property') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            @if($opportunity->property)
                                                <a href="{{ route('properties.show', $opportunity->property) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $opportunity->property->property_name }}
                                                </a>
                                            @else
                                                {{ __('No property linked') }}
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Assigned To') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $opportunity->assignedUser?->name ?? __('Unassigned') }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Description -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Description') }}</h2>
                            <div class="text-sm text-gray-700">
                                {{ $opportunity->description ?? __('No description provided') }}
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Notes') }}</h2>
                            <div class="text-sm text-gray-700">
                                {{ $opportunity->notes ?? __('No notes added') }}
                            </div>
                        </div>

                        <!-- Activity Log -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Activity History') }}</h2>
                            <div class="space-y-4">
                                @forelse($opportunity->activities->sortByDesc('created_at') as $activity)
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-history text-blue-600"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                {{ $activity->description }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $activity->created_at->diffForHumans() }} 
                                                {{ __('by') }} {{ $activity->user->name }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">{{ __('No activity recorded yet.') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
