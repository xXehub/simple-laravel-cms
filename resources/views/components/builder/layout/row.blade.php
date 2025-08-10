{{--
    Row Component
    
    Horizontal container for organizing columns in a grid layout.
    Uses Tabler.io row classes with gutter and alignment utilities.
--}}

<div {!! $attributeString !!}>
    {{-- Row content will be injected here by the builder --}}
    @if(isset($slot))
        {!! $slot !!}
    @else
        {{-- Placeholder content for preview --}}
        <div class="col-12">
            <div class="text-muted text-center py-4 border border-dashed rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-rows mb-2" width="32" height="32" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <rect x="4" y="4" width="16" height="6" rx="2"/>
                    <rect x="4" y="14" width="16" height="6" rx="2"/>
                </svg>
                <h6 class="text-muted mb-1">Row</h6>
                <small class="text-muted">
                    @if($properties['gutter'])
                        Gutter: {{ $properties['gutter'] }}
                    @else
                        Default gutter spacing
                    @endif
                </small>
            </div>
        </div>
    @endif
</div>
