@props(['name', 'label', 'type' => 'text'])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($attributes->has('required'))
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="relative">
        <input type="{{ $type }}" 
               id="{{ $name }}" 
               name="{{ $name }}" 
               value="{{ old($name) }}"
               {{ $attributes->merge([
                   'class' => 'form-input w-full rounded-lg border-gray-300 bg-gray-50 
                              shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500
                              hover:bg-gray-50 transition-colors duration-200'
               ]) }}>
        @if($type === 'search')
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
