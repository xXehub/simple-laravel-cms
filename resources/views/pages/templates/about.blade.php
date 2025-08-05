<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-header d-print-none text-center">
            <div class="container-xl">
                <div class="row g-2 align-items-center justify-content-center">
                    <div class="col-lg-8">
                        <h1 class="page-title mb-2">{{ $page->title }}</h1>
                        @if($page->meta_description)
                            <p class="text-secondary fs-4">{{ $page->meta_description }}</p>
                        @endif
                        @auth
                            @can('update-pages')
                                <div class="mt-3">
                                    <a href="{{ route('dynamic.page', ['slug' => 'panel/pages']) }}" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 11l3 3l8 -8"/>
                                            <path d="M21 12c-.552 0-1 .436-1 .937v6.063a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h6"/>
                                        </svg>
                                        Manage Pages
                                    </a>
                                </div>
                            @endcan
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <div class="page-body">
            <div class="container-xl">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-5">
                                <div class="row">
                                    <div class="col-lg-8 mx-auto">
                                        <div class="prose prose-lg">
                                            {!! nl2br(e($page->content)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- About Stats Section -->
                        <div class="row mt-5">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <div class="text-h2 text-primary">100+</div>
                                        <div class="text-muted">Projects</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <div class="text-h2 text-success">24/7</div>
                                        <div class="text-muted">Support</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <div class="text-h2 text-warning">5+</div>
                                        <div class="text-muted">Years</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <div class="text-h2 text-info">1000+</div>
                                        <div class="text-muted">Users</div>
                                    </div>
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
