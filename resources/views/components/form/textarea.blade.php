@props(['name', 'label', 'rows' => 3])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($attributes->has('required'))
            <span class="text-red-500">*</span>
        @endif
    </label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => 'form-textarea w-full rounded-lg border-gray-300 bg-gray-50 
                       shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500
                       hover:bg-gray-50 transition-colors duration-200'
        ]) }}
    >{{ old($name) }}</textarea>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
