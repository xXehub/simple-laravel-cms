{{--
    Section Component
    
    Semantic section element for organizing page content.
    Uses HTML5 <section> element with Tabler.io styling utilities.
--}}

@php
    $inlineStyles = app(App\View\Components\Builder\Layout\Section::class, ['properties' => $properties])->getInlineStyles();
@endphp

<section {!! $attributeString !!} @if($inlineStyles) style="{{ $inlineStyles }}" @endif>
    {{-- Section content will be injected here by the builder --}}
    @if(isset($slot))
        {!! $slot !!}
    @else
        {{-- Placeholder content for preview --}}
        <div class="container-fluid h-100 d-flex align-items-center justify-content-center">
            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-section mb-3" width="48" height="48" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M20 20h.01"/>
                    <path d="M4 20h.01"/>
                    <path d="M8 20h.01"/>
                    <path d="M12 20h.01"/>
                    <path d="M16 20h.01"/>
                    <path d="M20 4h.01"/>
                    <path d="M4 4h.01"/>
                    <path d="M8 4h.01"/>
                    <path d="M12 4h.01"/>
                    <path d="M16 4h.01"/>
                    <rect x="4" y="6" width="16" height="12" rx="2"/>
                </svg>
                <h5 class="text-muted mb-2">Section</h5>
                <div class="text-muted">
                    @if($properties['background_image'])
                        <small class="badge bg-info">Background Image</small>
                    @endif
                    
                    @if($properties['background'])
                        <small class="badge bg-secondary">{{ ucfirst(str_replace('bg-', '', $properties['background'])) }} BG</small>
                    @endif
                    
                    @if($properties['min_height'])
                        <small class="badge bg-light text-dark">{{ ucfirst($properties['min_height']) }}</small>
                    @endif
                    
                    @if(!$properties['background_image'] && !$properties['background'] && !$properties['min_height'])
                        <small class="text-muted">Semantic content section</small>
                    @endif
                </div>
            </div>
        </div>
    @endif
</section>
