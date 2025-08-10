{{--
    Heading Component
    
    Creates headings (H1-H6) with Tabler.io typography and styling options.
    Supports all heading levels with color, alignment, and weight controls.
--}}

@php
    // Ensure properties is an array and has default values
    $properties = $properties ?? [];
    $headingInstance = app(App\View\Components\Builder\Content\Heading::class, ['properties' => $properties]);
    $headingTag = $headingInstance->getHeadingTag();
    $headingText = $properties['text'] ?? $headingInstance->getProperty('text', 'Default Heading');
    $cssClasses = $headingInstance->getCssClasses();
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

<{{ $headingTag }} {!! $attributeString !!}>
    {{ $headingText }}
</{{ $headingTag }}>
