@props([
    'name',
    'label',
    'options' => [],
    'selected' => null,
    'multiple' => false
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($attributes->has('required'))
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="relative">
        <select id="{{ $name }}" 
                name="{{ $multiple ? $name.'[]' : $name }}"
                {{ $multiple ? 'multiple' : '' }}
                {{ $attributes->merge([
                    'class' => 'form-select w-full rounded-lg border-gray-300 bg-gray-50 
                               shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500
                               hover:bg-gray-50 transition-colors duration-200
                               appearance-none'
                ]) }}>
            @unless($multiple)
                <option value="">{{ __('Select...') }}</option>
            @endunless
            @foreach($options as $value => $label)
                <option value="{{ $value }}" 
                        {{ $multiple 
                            ? (in_array($value, (array)$selected) ? 'selected' : '')
                            : ($selected == $value ? 'selected' : '') }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @if($multiple)
        <p class="mt-1 text-xs text-gray-500">{{ __('Hold Ctrl/Cmd to select multiple options') }}</p>
    @endif
</div>
