@props(['name', 'label', 'options' => [], 'value' => '', 'placeholder' => null, 'class' => '', 'id' => null])

<div class="{{ $class }}">
    <label for="{{ $id ?? $name }}" class="form-label">{{ $label }}</label>
    <select class="form-select @error($name) is-invalid @enderror" id="{{ $id ?? $name }}" name="{{ $name }}"
        {{ $attributes }}>

        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $key => $option)
            <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

