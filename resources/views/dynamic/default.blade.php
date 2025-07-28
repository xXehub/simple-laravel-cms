{{-- Dynamic Default Template --}}
@php
    // Determine layout based on route type and user permissions
    $isPanel = isset($menu) && str_starts_with($menu->slug, 'panel/');
    $hasPanel = Auth::check() && Auth::user()->can('access-panel');
    
    $useSidebar = $isPanel && $hasPanel;
    $useTopBar = !$useSidebar; // Use top-bar for public pages or users without panel access
@endphp

<x-layout.app title="{{ $title }}" :pakai-sidebar="$useSidebar" :pakaiTopBar="$useTopBar" pakaiFluid="false">
    
    {{-- Page Header - No Breadcrumbs --}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    {{-- Page Title --}}
                    <h2 class="page-title">
                        @if(isset($menu) && $menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endif
                        {{ $title }}
                    </h2>
                    
                    {{-- Page Description --}}
                    @if(isset($description) && $description)
                        <div class="page-subtitle">{{ $description }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Page Body --}}
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @if(isset($content))
                        {!! $content !!}
                    @else
                        {{-- Default content for pages without specific content --}}
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="empty">
                                    <div class="empty-icon">
                                        @if(isset($menu) && $menu->icon)
                                            <i class="{{ $menu->icon }}" style="font-size: 3rem; color: #6c757d;"></i>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="font-size: 3rem; color: #6c757d;">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="empty-title">{{ $title }}</p>
                                    <p class="empty-subtitle text-muted">
                                        {{ $description ?? 'This page is under development.' }}
                                    </p>
                                    @if(isset($menu) && $menu->children()->exists())
                                        <div class="empty-action">
                                            <p class="text-muted">Available sections:</p>
                                            <div class="btn-list">
                                                @foreach($menu->children()->where('is_active', true)->orderBy('urutan')->get() as $child)
                                                    @if($child->isAccessible())
                                                        <a href="{{ url($child->slug) }}" class="btn btn-outline-primary">
                                                            @if($child->icon)
                                                                <i class="{{ $child->icon }}"></i>
                                                            @endif
                                                            {{ $child->nama_menu }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Optional: Add metadata for SEO --}}
    @if(isset($menu))
        @push('meta')
            @if($menu->meta_title)
                <meta name="title" content="{{ $menu->meta_title }}">
                <meta property="og:title" content="{{ $menu->meta_title }}">
            @endif
            @if($menu->meta_description)
                <meta name="description" content="{{ $menu->meta_description }}">
                <meta property="og:description" content="{{ $menu->meta_description }}">
            @endif
        @endpush
    @endif

</x-layout.app>
