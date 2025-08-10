{{-- Image Component --}}
{{-- 
Responsive image component with Tabler.io styling
Supports various sizes, alignments, linking, and caption options
--}}

@props([
    'src' => 'https://via.placeholder.com/400x300/667684/ffffff?text=Image',
    'alt' => 'Image',
    'width' => '',
    'height' => '',
    'alignment' => 'left',
    'rounded' => false,
    'roundedCircle' => false,
    'thumbnail' => false,
    'responsive' => true,
    'lazyLoading' => true,
    'linkUrl' => '',
    'linkTarget' => '_self',
    'caption' => '',
    'captionPosition' => 'below',
    'margin' => '',
    'cssClasses' => [],
    'id' => ''
])

@php
    // Build image classes
    $imageClasses = [];
    
    // Add responsive class if enabled
    if ($responsive) {
        $imageClasses[] = 'img-fluid';
    }
    
    // Add rounded styles
    if ($roundedCircle) {
        $imageClasses[] = 'rounded-circle';
    } elseif ($rounded) {
        $imageClasses[] = 'rounded';
    }
    
    // Add thumbnail style
    if ($thumbnail) {
        $imageClasses[] = 'img-thumbnail';
    }
    
    // Add margin
    if (!empty($margin)) {
        $imageClasses[] = $margin;
    }
    
    // Add custom classes
    if (!empty($cssClasses)) {
        if (is_array($cssClasses)) {
            $imageClasses = array_merge($imageClasses, $cssClasses);
        } else {
            $imageClasses[] = $cssClasses;
        }
    }
    
    $finalImageClasses = implode(' ', array_filter($imageClasses));
    
    // Container classes for alignment
    $containerClasses = '';
    switch ($alignment) {
        case 'center':
            $containerClasses = 'text-center';
            break;
        case 'right':
            $containerClasses = 'text-end';
            break;
        default:
            $containerClasses = 'text-start';
            break;
    }
    
    // Build image style for fixed dimensions
    $imageStyle = '';
    $styleArray = [];
    if (!empty($width)) {
        $styleArray[] = "width: {$width}px";
    }
    if (!empty($height)) {
        $styleArray[] = "height: {$height}px";
        $styleArray[] = "object-fit: cover";
    }
    if (!empty($styleArray)) {
        $imageStyle = implode('; ', $styleArray);
    }
    
    // Image attributes
    $imageAttributes = [];
    if (!empty($id)) {
        $imageAttributes['id'] = $id;
    }
    if ($lazyLoading) {
        $imageAttributes['loading'] = 'lazy';
    }
    if (!empty($imageStyle)) {
        $imageAttributes['style'] = $imageStyle;
    }
    
    // Check for link and caption
    $hasLink = !empty($linkUrl);
    $hasCaption = !empty($caption);
@endphp

<div class="{{ $containerClasses }}">
    {{-- Caption Above Image --}}
    @if($hasCaption && $captionPosition === 'above')
        <div class="text-muted small mb-2">{{ $caption }}</div>
    @endif
    
    {{-- Image Container with Optional Link --}}
    @if($hasLink)
        <a href="{{ $linkUrl }}" target="{{ $linkTarget }}" class="d-inline-block position-relative">
    @elseif($hasCaption && $captionPosition === 'overlay')
        <div class="position-relative d-inline-block">
    @endif
    
    {{-- The Image Element --}}
    <img 
        src="{{ $src }}" 
        alt="{{ $alt }}"
        class="{{ $finalImageClasses }}"
        @foreach($imageAttributes as $key => $value)
            {{ $key }}="{{ $value }}"
        @endforeach
    >
    
    {{-- Overlay Caption --}}
    @if($hasCaption && $captionPosition === 'overlay')
        <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2 small">
            {{ $caption }}
        </div>
    @endif
    
    {{-- Close Link/Overlay Container --}}
    @if($hasLink)
        </a>
    @elseif($hasCaption && $captionPosition === 'overlay')
        </div>
    @endif
    
    {{-- Caption Below Image --}}
    @if($hasCaption && $captionPosition === 'below')
        <div class="text-muted small mt-2">{{ $caption }}</div>
    @endif
</div>
