{{--
    Container Component
    
    A responsive container that centers and constrains content width.
    Uses Tabler.io container classes (.container or .container-fluid).
--}}

<div {!! $attributeString !!}>
    {{-- Container content will be injected here by the builder --}}
    @if(isset($slot))
        {!! $slot !!}
    @else
        {{-- Placeholder content for preview --}}
        <div class="text-muted text-center py-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-grid mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <rect x="4" y="4" width="6" height="6" rx="1"/>
                <rect x="14" y="4" width="6" height="6" rx="1"/>
                <rect x="4" y="14" width="6" height="6" rx="1"/>
                <rect x="14" y="14" width="6" height="6" rx="1"/>
            </svg>
            <h5 class="text-muted">Container</h5>
            <p class="text-muted mb-0">
                {{ $properties['fluid'] ? 'Fluid container (full width)' : 'Fixed container (responsive width)' }}
            </p>
        </div>
    @endif
</div>
