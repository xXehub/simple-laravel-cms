{{-- Button Component --}}
{{-- 
Interactive button component with Tabler.io styling
Supports variants, sizes, icons, and link functionality
--}}

@php
    // Ensure properties is an array and extract values with defaults
    $properties = $properties ?? [];
    $text = $properties['text'] ?? 'Click Me';
    $url = $properties['url'] ?? '#';
    $variant = $properties['variant'] ?? 'btn-primary';
    $size = $properties['size'] ?? '';
    $target = $properties['target'] ?? '_self';
    $icon = $properties['icon'] ?? '';
    $iconPosition = $properties['icon_position'] ?? 'left';
    $fullWidth = $properties['full_width'] ?? false;
    $disabled = $properties['disabled'] ?? false;
    $outline = $properties['outline'] ?? false;
    $pill = $properties['pill'] ?? false;
    $margin = $properties['margin'] ?? '';
    $cssClasses = $properties['css_classes'] ?? [];
    $customId = $properties['id'] ?? '';
    
    // Build button classes
    $buttonClasses = ['btn'];
    
    // Add variant
    if ($outline && str_contains($variant, 'btn-')) {
        $buttonClasses[] = str_replace('btn-', 'btn-outline-', $variant);
    } else {
        $buttonClasses[] = $variant;
    }
    
    // Add size
    if (!empty($size)) {
        $buttonClasses[] = $size;
    }
    
    // Add full width
    if ($fullWidth) {
        $buttonClasses[] = 'w-100';
    }
    
    // Add pill style
    if ($pill) {
        $buttonClasses[] = 'rounded-pill';
    }
    
    // Add margin
    if (!empty($margin)) {
        $buttonClasses[] = $margin;
    }
    
    // Add custom classes
    if (!empty($cssClasses)) {
        if (is_array($cssClasses)) {
            $buttonClasses = array_merge($buttonClasses, $cssClasses);
        } else {
            $buttonClasses[] = $cssClasses;
        }
    }
    
    $finalClasses = implode(' ', $buttonClasses);
    
    // Process icon
    $iconClass = '';
    if (!empty($icon)) {
        if (str_starts_with($icon, 'ti-')) {
            $iconClass = 'ti ' . $icon;
        } elseif (str_starts_with($icon, 'ti ti-')) {
            $iconClass = $icon;
        } else {
            $iconClass = 'ti ti-' . $icon;
        }
    }
    
    // Build attributes
    $attributes = [];
    if (!empty($customId)) {
        $attributes['id'] = $customId;
    }
    if ($disabled) {
        $attributes['disabled'] = 'disabled';
    }
    
    // Handle data attributes
    $dataAttributes = $properties['data_attributes'] ?? [];
    if (is_array($dataAttributes)) {
        foreach ($dataAttributes as $key => $value) {
            $attributes['data-' . $key] = htmlspecialchars($value);
        }
    }
@endphp

<a 
    href="{{ $url }}" 
    target="{{ $target }}"
    class="{{ $finalClasses }}"
    @if($disabled) aria-disabled="true" @endif
    @foreach($attributes as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach
>
    @if(!empty($icon) && $iconPosition === 'left')
        <i class="{{ $iconClass }}"></i>
        @if(!empty($text))
            <span class="ms-1">{{ $text }}</span>
        @endif
    @elseif(!empty($icon) && $iconPosition === 'right')
        @if(!empty($text))
            <span class="me-1">{{ $text }}</span>
        @endif
        <i class="{{ $iconClass }}"></i>
    @elseif(!empty($icon) && $iconPosition === 'only')
        <i class="{{ $iconClass }}"></i>
    @else
        {{ $text }}
    @endif
</a>
