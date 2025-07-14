@props([
    'name',
    'label',
    'options' => [],
    'checked' => [],
    'prefix' => ''
])

<div class="mb-3">
    <label class="form-label">{{ $label }}</label>
    <div class="row">
        @foreach($options as $key => $option)
            <div class="col-md-4 mb-2">
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox"
                        id="{{ $prefix }}{{ $key }}" 
                        name="{{ $name }}[]"
                        value="{{ $key }}"
                        {{ in_array($key, $checked) ? 'checked' : '' }}
                        {{ $attributes }}>
                    <label class="form-check-label" for="{{ $prefix }}{{ $key }}">
                        {{ ucfirst($option) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>

