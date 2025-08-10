{{--
    Vanilla Component Blade Template
    
    Copy this file to create new component templates.
    Replace "vanilla-component" with your component name.
    
    Use only Tabler.io CSS classes for styling.
    Variables available: $properties, $attributes, $attributeString
--}}

<div {!! $attributeString !!}>
    {{-- CUSTOMIZE THE CONTENT BELOW --}}
    
    {{-- Example: Title section --}}
    @if(!empty($properties['title']))
        <div class="mb-2">
            <h5 class="card-title mb-0">
                {{ $properties['title'] }}
            </h5>
        </div>
    @endif
    
    {{-- Example: Content section --}}
    @if(!empty($properties['content']))
        <div class="text-secondary">
            {!! nl2br(e($properties['content'])) !!}
        </div>
    @endif
    
    {{-- Add your custom HTML here --}}
    
</div>
