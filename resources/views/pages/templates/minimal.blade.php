<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="text-center mb-4">
                            <h1 class="display-5">{{ $page->title }}</h1>
                            @if($page->meta_description)
                                <p class="text-muted">{{ $page->meta_description }}</p>
                            @endif
                        </div>
                        
                        <div class="card card-lg">
                            <div class="card-body">
                                <div class="prose max-w-none">
                                    {!! nl2br(e($page->content)) !!}
                                </div>
                                
                                @auth
                                    @can('update-pages')
                                        <div class="text-center mt-4 pt-4 border-top">
                                            <a href="{{ url('panel/pages') }}" class="btn btn-outline-primary btn-sm">
                                                Edit Page
                                            </a>
                                        </div>
                                    @endcan
                                @endauth
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                {{ $page->created_at->format('F d, Y') }}
                                @if($page->template)
                                    • {{ $page->template }}
                                @endif
                            </small>
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
