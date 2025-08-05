<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    <div class="page-wrapper">
        <!-- Hero Section -->
        <div class="page-header page-header-dark d-print-none" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="container-xl">
                <div class="row justify-content-center text-center py-5">
                    <div class="col-lg-10">
                        <h1 class="display-3 fw-bold text-white mb-4">{{ $page->title }}</h1>
                        @if($page->meta_description)
                            <p class="lead text-white-75 mb-4">{{ $page->meta_description }}</p>
                        @endif
                        
                        <div class="mt-4">
                            <a href="#content" class="btn btn-white btn-lg me-3">
                                Learn More
                            </a>
                            <a href="{{ url('kontak') }}" class="btn btn-outline-white btn-lg">
                                Get Started
                            </a>
                        </div>
                        
                        @auth
                            @can('update-pages')
                                <div class="mt-4">
                                    <a href="{{ url('panel/pages') }}" class="btn btn-outline-white btn-sm">
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
        <div class="page-body" id="content">
            <div class="container-xl">
                <!-- Content Section -->
                <div class="row justify-content-center py-5">
                    <div class="col-lg-8">
                        <div class="prose prose-lg max-w-none">
                            {!! nl2br(e($page->content)) !!}
                        </div>
                    </div>
                </div>
                
                <!-- Features Grid -->
                <div class="row g-4 py-5">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-primary text-white rounded-circle mx-auto mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <h4>Quality Service</h4>
                                <p class="text-muted">We provide high-quality services that exceed expectations and deliver exceptional results.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-success text-white rounded-circle mx-auto mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <h4>Fast Delivery</h4>
                                <p class="text-muted">Quick turnaround times without compromising on quality or attention to detail.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-warning text-white rounded-circle mx-auto mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <h4>24/7 Support</h4>
                                <p class="text-muted">Round-the-clock support to ensure your success and satisfaction with our services.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Call to Action -->
        <div class="page-footer bg-dark text-white">
            <div class="container-xl">
                <div class="row justify-content-center text-center py-5">
                    <div class="col-lg-6">
                        <h3 class="mb-3">Ready to Get Started?</h3>
                        <p class="text-white-75 mb-4">Join thousands of satisfied customers who trust our services.</p>
                        <a href="{{ url('kontak') }}" class="btn btn-primary btn-lg">Contact Us Today</a>
                    </div>
                </div>
                
                <div class="row mt-4 pt-4 border-top border-white-10">
                    <div class="col text-center">
                        <small class="text-white-50">
                            {{ $page->created_at->format('F d, Y') }}
                            @if($page->template)
                                • Template: {{ $page->template }}
                            @endif
                        </small>
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
