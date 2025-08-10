{{-- Builder Template - Clean template for page builder output --}}
<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    {{-- Page Builder Content: Complete control over the entire page layout --}}
    <div class="page-wrapper">
        @auth
            @can('update-pages')
                {{-- Floating edit button for authenticated users --}}
                <div class="position-fixed" style="top: 20px; right: 20px; z-index: 1000;">
                    <a href="{{ route('panel.pages.builder', $page->id) }}" class="btn btn-primary btn-sm shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Edit Page
                    </a>
                </div>
            @endcan
        @endauth

        {{-- Builder content gets complete control - no restrictions or containers --}}
        @if(!empty($renderedContent))
            <div class="builder-content">
                {!! $renderedContent !!}
            </div>
        @elseif($page->content)
            <div class="builder-content">
                {!! $page->content !!}
            </div>
        @else
            {{-- Empty state when no content - only shown when page is completely empty --}}
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards justify-content-center">
                        <div class="col-md-8">
                            <div class="empty">
                                <div class="empty-img">
                                    <img src="{{ asset('static/illustrations/undraw_building_websites_i78t.svg') }}" height="128" alt="">
                                </div>
                                <p class="empty-title">{{ $page->title }}</p>
                                <p class="empty-subtitle text-muted">
                                    This page is still under construction. Use the page builder to create amazing content.
                                </p>
                                @auth
                                    @can('update-pages')
                                        <div class="empty-action">
                                            <a href="{{ route('panel.pages.builder', $page->id) }}" class="btn btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <rect x="4" y="4" width="16" height="4" rx="1"/>
                                                    <rect x="4" y="12" width="6" height="8" rx="1"/>
                                                    <rect x="14" y="12" width="6" height="8" rx="1"/>
                                                </svg>
                                                Start Building
                                            </a>
                                        </div>
                                    @endcan
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Custom CSS for builder content --}}
    <style>
        .builder-content {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        /* Ensure builder containers work properly */
        .builder-content .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        /* Remove any unwanted spacing from builder content */
        .builder-content > div:first-child {
            margin-top: 0;
        }
        
        .builder-content > div:last-child {
            margin-bottom: 0;
        }
        
        /* Ensure proper spacing for sections */
        .builder-content section {
            padding: 3rem 0;
        }
        
        /* Clean up any builder artifacts that might leak through */
        .builder-content .border-dashed {
            border: none !important;
        }
        
        .builder-content .bg-light {
            background: transparent !important;
        }
        
        .builder-content .drop-zone {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
        }
        
        .builder-content .text-muted {
            color: inherit !important;
        }
        
        /* Hide any remaining builder helper text */
        .builder-content .drop-zone small {
            display: none !important;
        }
        
        /* Responsive images */
        .builder-content img {
            max-width: 100%;
            height: auto;
        }
        
        /* Fix Bootstrap grid spacing */
        .builder-content .row {
            margin-left: 0;
            margin-right: 0;
        }
        
        .builder-content .row > * {
            padding-left: 15px;
            padding-right: 15px;
        }
    </style>

    {{-- SEO Meta Tags --}}
    @if($page->meta_title || $page->meta_description)
        @push('meta')
            @if($page->meta_title)
                <meta name="title" content="{{ $page->meta_title }}">
                <meta property="og:title" content="{{ $page->meta_title }}">
            @endif
            @if($page->meta_description)
                <meta name="description" content="{{ $page->meta_description }}">
                <meta property="og:description" content="{{ $page->meta_description }}">
            @endif
        @endpush
    @endif
</x-layout.app>
