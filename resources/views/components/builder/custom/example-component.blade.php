{{--
    Example Custom Component Template
    
    This Blade template demonstrates how to create custom component views.
    Use Tabler.io CSS classes for styling.
--}}

<div {!! $attributeString !!}>
    {{-- Header section --}}
    @if($properties['title'])
        <div class="mb-2">
            <h5 class="card-title text-{{ $properties['color_scheme'] }} mb-0">
                {{ $properties['title'] }}
            </h5>
        </div>
    @endif
    
    {{-- Content section --}}
    @if($properties['content'])
        <div class="text-secondary">
            {{ $properties['content'] }}
        </div>
    @endif
    
    {{-- Optional footer/actions --}}
    <div class="mt-3">
        <span class="badge bg-{{ $properties['color_scheme'] }}">Custom Component</span>
    </div>
</div>
