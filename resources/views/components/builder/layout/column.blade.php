{{--
    Column Component
    
    Responsive column for grid layouts with breakpoint-specific sizing.
    Uses Tabler.io column classes with responsive breakpoints.
--}}

<div {!! $attributeString !!}>
    {{-- Column content will be injected here by the builder --}}
    @if(isset($slot))
        {!! $slot !!}
    @else
        {{-- Placeholder content for preview --}}
        <div class="text-muted text-center py-4 border border-dashed rounded bg-light">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-columns mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <rect x="4" y="4" width="6" height="16" rx="2"/>
                <rect x="14" y="4" width="6" height="16" rx="2"/>
            </svg>
            <h6 class="text-muted mb-1">Column</h6>
            <small class="text-muted">
                @php
                    $sizes = [];
                    if ($properties['xs_size'] && $properties['xs_size'] !== 'auto') $sizes[] = 'xs:' . $properties['xs_size'];
                    if ($properties['sm_size']) $sizes[] = 'sm:' . $properties['sm_size'];
                    if ($properties['md_size']) $sizes[] = 'md:' . $properties['md_size'];
                    if ($properties['lg_size']) $sizes[] = 'lg:' . $properties['lg_size'];
                    if ($properties['xl_size']) $sizes[] = 'xl:' . $properties['xl_size'];
                    if ($properties['xxl_size']) $sizes[] = 'xxl:' . $properties['xxl_size'];
                @endphp
                
                @if(count($sizes) > 0)
                    {{ implode(' | ', $sizes) }}
                @else
                    Auto-sized column
                @endif
            </small>
        </div>
    @endif
</div>
