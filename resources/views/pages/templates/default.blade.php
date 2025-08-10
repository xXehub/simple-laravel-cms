<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    <div class="page-wrapper">
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
                                    <a href="{{ route('dynamic.page', ['slug' => 'panel/pages']) }}" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 11l3 3l8 -8"/>
                                            <path d="M21 12c-.552 0-1 .436-1 .937v6.063a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h6"/>
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
                        <div class="card">
                            <div class="card-body">
                                <div class="prose prose-lg max-w-none">
                                    {!! $page->content !!}
                                </div>
                            </div>
                            
                            <div class="card-footer text-muted">
                                <div class="row">
                                    <div class="col">
                                        <div class="d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"/>
                                                <path d="M16 3v4"/>
                                                <path d="M8 3v4"/>
                                                <path d="M4 11h16"/>
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
