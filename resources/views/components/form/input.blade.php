@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'help' => null,
    'class' => '',
    'id' => null
])

<div class="{{ $class }}">
    <label for="{{ $id ?? $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input 
        type="{{ $type }}" 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $id ?? $name }}" 
        name="{{ $name }}" 
        value="{{ $value }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}>
    
    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

