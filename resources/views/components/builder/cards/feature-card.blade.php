{{-- Feature Card Component --}}
@php
    // Use the passed cardInstance or create a fallback
    $cardInstance = $cardInstance ?? app(App\View\Components\Builder\Cards\FeatureCard::class, ['properties' => $properties ?? []]);
    $cssClasses = $cardInstance->getCssClasses();
@endphp

<div class="{{ $cssClasses }}">
    {{-- Badge --}}
    @if($cardInstance->getProperty('badge_text'))
        <div class="position-relative">
            <span class="badge {{ $cardInstance->getProperty('badge_variant', 'bg-success') }} position-absolute {{ $cardInstance->getProperty('badge_position', 'top-right') === 'top-right' ? 'top-0 end-0' : '' }}{{ $cardInstance->getProperty('badge_position') === 'top-left' ? 'top-0 start-0' : '' }}{{ $cardInstance->getProperty('badge_position') === 'bottom-right' ? 'bottom-0 end-0' : '' }}{{ $cardInstance->getProperty('badge_position') === 'bottom-left' ? 'bottom-0 start-0' : '' }}" style="transform: translate(25%, -25%);">
                {{ $cardInstance->getProperty('badge_text') }}
            </span>
        </div>
    @endif
    
    <div class="card-body">
        {{-- Icon and Title Layout --}}
        @if($cardInstance->getProperty('icon_position', 'top') === 'top')
            {{-- Icon on top --}}
            @if($cardInstance->getProperty('icon'))
                <div class="mb-3">
                    <i class="{{ $cardInstance->getProperty('icon') }} {{ $cardInstance->getProperty('icon_color', 'text-primary') }}" 
                       style="font-size: {{ $cardInstance->getProperty('icon_size', '48') }}px;"></i>
                </div>
            @endif
            
            @if($cardInstance->getProperty('title'))
                <h5 class="card-title">{{ $cardInstance->getProperty('title') }}</h5>
            @endif
        @else
            {{-- Icon on left or right --}}
            <div class="d-flex {{ $cardInstance->getProperty('icon_position') === 'right' ? 'flex-row-reverse' : '' }} align-items-start">
                @if($cardInstance->getProperty('icon'))
                    <div class="{{ $cardInstance->getProperty('icon_position') === 'right' ? 'ms-3' : 'me-3' }}">
                        <i class="{{ $cardInstance->getProperty('icon') }} {{ $cardInstance->getProperty('icon_color', 'text-primary') }}" 
                           style="font-size: {{ $cardInstance->getProperty('icon_size', '48') }}px;"></i>
                    </div>
                @endif
                
                <div class="flex-grow-1">
                    @if($cardInstance->getProperty('title'))
                        <h5 class="card-title mb-0">{{ $cardInstance->getProperty('title') }}</h5>
                    @endif
                </div>
            </div>
        @endif
        
        {{-- Description --}}
        @if($cardInstance->getProperty('description'))
            <p class="card-text {{ $cardInstance->getProperty('icon_position', 'top') !== 'top' ? 'mt-2' : '' }}">
                {{ $cardInstance->getProperty('description') }}
            </p>
        @endif
        
        {{-- Button --}}
        @if($cardInstance->getProperty('button_text') && $cardInstance->getProperty('button_url'))
            <div class="mt-3">
                <a href="{{ $cardInstance->getProperty('button_url') }}" 
                   class="btn {{ $cardInstance->getProperty('button_variant', 'btn-primary') }} {{ $cardInstance->getProperty('button_size', '') }}"
                   @if($cardInstance->getProperty('button_target') === '_blank') target="_blank" @endif>
                    {{ $cardInstance->getProperty('button_text') }}
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Add hover effect styles --}}
@if($cardInstance->getProperty('hover_effect', true))
<style>
.feature-card-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.feature-card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
}
</style>
@endif
