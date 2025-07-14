@props([
    'name',
    'label',
    'checked' => false,
    'value' => '1',
    'id' => null
])

<div class="form-check">
    <input 
        class="form-check-input" 
        type="checkbox" 
        id="{{ $id ?? $name }}" 
        name="{{ $name }}" 
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        {{ $attributes }}>
    <label class="form-check-label" for="{{ $id ?? $name }}">
        {{ $label }}
    </label>
</div>

