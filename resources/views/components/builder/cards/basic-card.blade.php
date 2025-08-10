{{-- Basic Card Component --}}
@php
    // Ensure properties is an array and has default values
    $properties = $properties ?? [];
    $cardInstance = app(App\View\Components\Builder\Cards\BasicCard::class, ['properties' => $properties]);
    $cssClasses = $cardInstance->getCssClasses();
@endphp

<div class="{{ $cssClasses }}">
    @if($cardInstance->getProperty('show_header') && ($cardInstance->getProperty('header_title') || $cardInstance->getProperty('header_subtitle')))
        <div class="card-header {{ $cardInstance->getProperty('header_background') }}">
            @if($cardInstance->getProperty('header_title'))
                <h5 class="card-title mb-0">{{ $cardInstance->getProperty('header_title') }}</h5>
            @endif
            @if($cardInstance->getProperty('header_subtitle'))
                <small class="text-muted">{{ $cardInstance->getProperty('header_subtitle') }}</small>
            @endif
        </div>
    @endif
    
    <div class="card-body {{ $cardInstance->getProperty('padding') }}">
        @if($cardInstance->getProperty('body_content'))
            <div class="card-text">{!! $cardInstance->getProperty('body_content') !!}</div>
        @endif
    </div>
    
    @if($cardInstance->getProperty('show_footer') && $cardInstance->getProperty('footer_content'))
        <div class="card-footer">
            <div class="text-muted">{!! $cardInstance->getProperty('footer_content') !!}</div>
        </div>
    @endif
</div>
