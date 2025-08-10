{{--
    Text Component
    
    Creates text content with Tabler.io typography options.
    Supports rich formatting, colors, alignment, and sizing.
--}}

@php
    // Ensure properties is an array and has default values
    $properties = $properties ?? [];
    $textInstance = app(App\View\Components\Builder\Content\Text::class, ['properties' => $properties]);
    $formattedContent = $textInstance->getFormattedContent();
    $cssClasses = $textInstance->getCssClasses();
    $customId = $properties['id'] ?? '';
    
    // Build attributes
    $attributes = [];
    if (!empty($cssClasses)) {
        $attributes[] = 'class="' . $cssClasses . '"';
    }
    if (!empty($customId)) {
        $attributes[] = 'id="' . $customId . '"';
    }
    
    // Handle data attributes
    $dataAttributes = $properties['data_attributes'] ?? [];
    if (is_array($dataAttributes)) {
        foreach ($dataAttributes as $key => $value) {
            $attributes[] = 'data-' . $key . '="' . htmlspecialchars($value) . '"';
        }
    }
    
    $attributeString = implode(' ', $attributes);
@endphp

<div {!! $attributeString !!}>
    {!! $formattedContent !!}
</div>
