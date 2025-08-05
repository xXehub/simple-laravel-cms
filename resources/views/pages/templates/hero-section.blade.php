<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    <div class="page-wrapper">
        <!-- Hero Section with Background -->
        <div class="page-header d-print-none bg-primary text-white">
            <div class="container-xl">
                <div class="row justify-content-center text-center py-5">
                    <div class="col-lg-8">
                        <h1 class="display-4 fw-bold mb-3">{{ $page->title }}</h1>
                        @if($page->meta_description)
                            <p class="lead opacity-75">{{ $page->meta_description }}</p>
                        @endif
                        
                        @auth
                            @can('update-pages')
                                <div class="mt-4">
                                    <a href="{{ url('panel/pages') }}" class="btn btn-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Edit Page
                                    </a>
                                </div>
                            @endcan
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card mt-n4 shadow-lg">
                            <div class="card-body p-5">
                                <div class="row">
                                    <div class="col-lg-8 mx-auto">
                                        <div class="prose prose-lg max-w-none">
                                            {!! nl2br(e($page->content)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Call to Action Section -->
                        <div class="text-center mt-5 mb-5">
                            <div class="card bg-azure-lt">
                                <div class="card-body py-5">
                                    <h3 class="card-title">Ready to get started?</h3>
                                    <p class="text-muted">Contact us to learn more about our services.</p>
                                    <a href="{{ url('kontak') }}" class="btn btn-primary">Get in Touch</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="page-footer bg-light">
            <div class="container-xl">
                <div class="row">
                    <div class="col text-center py-3">
                        <div class="text-muted small">
                            Published {{ $page->created_at->format('F d, Y') }}
                            @if($page->updated_at->ne($page->created_at))
                                • Updated {{ $page->updated_at->format('F d, Y') }}
                            @endif
                            @if($page->template)
                                • Template: {{ $page->template }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
