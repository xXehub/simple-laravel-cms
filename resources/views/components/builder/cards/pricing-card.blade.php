{{-- Pricing Card Component --}}
@php
    // Use the passed cardInstance or create a fallback
    $cardInstance = $cardInstance ?? app(App\View\Components\Builder\Cards\PricingCard::class, ['properties' => $properties ?? []]);
    $cssClasses = $cardInstance->getCssClasses();
    $featuresArray = $cardInstance->getFeaturesArray();
@endphp

<div class="{{ $cssClasses }}">
    {{-- Popular Badge --}}
    @if($cardInstance->getProperty('is_popular', false) && $cardInstance->getProperty('badge_text'))
        <div class="position-relative">
            <div class="badge {{ $cardInstance->getProperty('badge_variant', 'bg-success') }} position-absolute top-0 start-50 translate-middle">
                {{ $cardInstance->getProperty('badge_text') }}
            </div>
        </div>
    @endif
    
    {{-- Card Header --}}
    <div class="card-header text-center py-4">
        <h5 class="card-title mb-1">{{ $cardInstance->getProperty('plan_name', 'Basic Plan') }}</h5>
        
        @if($cardInstance->getProperty('plan_description'))
            <p class="text-muted mb-3">{{ $cardInstance->getProperty('plan_description') }}</p>
        @endif
        
        {{-- Pricing --}}
        <div class="display-4 fw-bold">
            <span class="currency">{{ $cardInstance->getProperty('currency', '$') }}</span>{{ $cardInstance->getProperty('price', '29') }}
            @if($cardInstance->getProperty('original_price'))
                <small class="text-muted text-decoration-line-through ms-2">
                    {{ $cardInstance->getProperty('currency', '$') }}{{ $cardInstance->getProperty('original_price') }}
                </small>
            @endif
        </div>
        
        @if($cardInstance->getProperty('price_period') && $cardInstance->getProperty('price_period') !== 'one-time')
            <small class="text-muted">/ {{ $cardInstance->getProperty('price_period', 'month') }}</small>
        @endif
    </div>
    
    {{-- Card Body - Features --}}
    <div class="card-body">
        @if($featuresArray)
            <ul class="list-unstyled">
                @foreach($featuresArray as $feature)
                    <li class="d-flex align-items-center mb-2">
                        @if($cardInstance->getProperty('features_icon'))
                            <i class="{{ $cardInstance->getProperty('features_icon', 'ti ti-check') }} text-success me-2"></i>
                        @endif
                        <span>{{ trim($feature) }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    
    {{-- Card Footer - Button --}}
    @if($cardInstance->getProperty('button_text'))
        <div class="card-footer border-0 pt-0">
            <a href="{{ $cardInstance->getProperty('button_url', '#') }}" 
               class="btn {{ $cardInstance->getProperty('button_variant', 'btn-primary') }} {{ $cardInstance->getProperty('button_size', '') }} {{ $cardInstance->getProperty('button_block', true) ? 'w-100' : '' }}"
               @if($cardInstance->getProperty('button_target') === '_blank') target="_blank" @endif>
                {{ $cardInstance->getProperty('button_text') }}
            </a>
        </div>
    @endif
</div>


