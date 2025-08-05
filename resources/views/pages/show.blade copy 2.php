<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $page->title }}</h2>
                @if($page->meta_description)
                    <div class="page-subtitle">{{ $page->meta_description }}</div>
                @endif
            </div>
            @auth
                @can('update-pages')
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('dynamic.page', ['slug' => 'panel/pages']) }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                    <path d="M16 5l3 3"/>
                                </svg>
                                Manage Pages
                            </a>
                        </div>
                    </div>
                @endcan
            @endauth
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                @if($page->meta_title && $page->meta_title !== $page->title)
                    <div class="alert alert-info mb-3">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                    <polyline points="11,12 12,12 12,16 13,16"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="alert-title">SEO Information</h4>
                                <div class="text-muted">
                                    <strong>SEO Title:</strong> {{ $page->meta_title }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="content">
                            {!! nl2br(e($page->content)) !!}
                        </div>
                    </div>

                    <div class="card-footer text-muted">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                                        <line x1="16" y1="3" x2="16" y2="7"/>
                                        <line x1="8" y1="3" x2="8" y2="7"/>
                                        <line x1="4" y1="11" x2="20" y2="11"/>
                                    </svg>
                                    <small>Created: {{ $page->created_at->format('F d, Y') }}</small>
                                    @if($page->updated_at->ne($page->created_at))
                                        <span class="mx-2">•</span>
                                        <small>Updated: {{ $page->updated_at->format('F d, Y') }}</small>
                                    @endif
                                </div>
                            </div>
                            @if($page->template)
                                <div class="col-auto">
                                    <div class="d-flex align-items-center text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18z"/>
                                            <path d="M12 7l0 5l3 3"/>
                                        </svg>
                                        <small>Template: {{ $page->template }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

