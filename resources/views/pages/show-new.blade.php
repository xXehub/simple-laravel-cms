<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    <!-- Minimal page header for authentication and edit links -->
    @auth
        @can('update-pages')
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">{{ $page->title }}</h2>
                            @if($page->meta_description)
                                <div class="page-subtitle">{{ $page->meta_description }}</div>
                            @endif
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="{{ route('panel.pages.builder', $page->id) }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="4" width="16" height="4" rx="1"/>
                                        <rect x="4" y="12" width="6" height="8" rx="1"/>
                                        <rect x="14" y="12" width="6" height="8" rx="1"/>
                                    </svg>
                                    Edit with Builder
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    @endauth

    <!-- Page content: Full control to page builder -->
    <div class="page-content">
        @if($page->content)
            {!! $page->content !!}
        @else
            <div class="page-body">
                <div class="container-xl">
                    <div class="empty">
                        <div class="empty-img">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTQgNFY0NEg0NFY0SDRaIiBzdHJva2U9IiNDQkQ1RTEiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik00IDEySDQ0IiBzdHJva2U9IiNDQkQ1RTEiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xMiA0VjEyIiBzdHJva2U9IiNDQkQ1RTEiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=" alt="Empty page">
                        </div>
                        <p class="empty-title">This page has no content</p>
                        <p class="empty-subtitle text-muted">
                            Use the page builder to create beautiful content for this page.
                        </p>
                        @auth
                            @can('update-pages')
                                <div class="empty-action">
                                    <a href="{{ route('panel.pages.builder', $page->id) }}" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
        @endif
    </div>

    <!-- SEO Meta Tags -->
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
