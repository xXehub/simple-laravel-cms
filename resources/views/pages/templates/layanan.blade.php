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
                <!-- Main Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="prose prose-lg">
                                    {!! nl2br(e($page->content)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Services Grid -->
                <div class="row mt-5">
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-primary text-white mb-3 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9-9 9s-9-1.8-9-9s1.8-9 9-9z"/>
                                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/>
                                    </svg>
                                </div>
                                <h4 class="card-title">Web Development</h4>
                                <p class="text-secondary">Modern and responsive websites built with the latest technologies.</p>
                                <a href="#" class="btn btn-outline-primary">Learn More</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-success text-white mb-3 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 4v16l13 -8z"/>
                                    </svg>
                                </div>
                                <h4 class="card-title">Mobile Apps</h4>
                                <p class="text-secondary">Native and cross-platform mobile applications for iOS and Android.</p>
                                <a href="#" class="btn btn-outline-success">Learn More</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-warning text-white mb-3 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M2 12l1.5 -3l1.5 3l-1.5 3z"/>
                                        <path d="M22 12l-1.5 -3l-1.5 3l1.5 3z"/>
                                        <path d="M12 5l4 14l-8 0l4 -14z"/>
                                    </svg>
                                </div>
                                <h4 class="card-title">Cloud Solutions</h4>
                                <p class="text-secondary">Scalable cloud infrastructure and deployment solutions.</p>
                                <a href="#" class="btn btn-outline-warning">Learn More</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-info text-white mb-3 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"/>
                                        <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3"/>
                                        <path d="M9.7 17l4.6 0"/>
                                    </svg>
                                </div>
                                <h4 class="card-title">Consulting</h4>
                                <p class="text-secondary">Technical consulting and strategy for your digital transformation.</p>
                                <a href="#" class="btn btn-outline-info">Learn More</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-danger text-white mb-3 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"/>
                                        <path d="M9 12l2 2l4 -4"/>
                                    </svg>
                                </div>
                                <h4 class="card-title">Security</h4>
                                <p class="text-secondary">Comprehensive security solutions to protect your digital assets.</p>
                                <a href="#" class="btn btn-outline-danger">Learn More</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg bg-purple text-white mb-3 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z"/>
                                        <path d="M12 9v4"/>
                                        <path d="M12 16h.01"/>
                                    </svg>
                                </div>
                                <h4 class="card-title">Support</h4>
                                <p class="text-secondary">24/7 technical support and maintenance services.</p>
                                <a href="#" class="btn btn-outline-purple">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Call to Action -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center py-5">
                                <h3 class="text-white mb-3">Ready to get started?</h3>
                                <p class="text-white-50 mb-4">Contact us today to discuss your project requirements and get a free consultation.</p>
                                <a href="#" class="btn btn-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"/>
                                    </svg>
                                    Contact Us
                                </a>
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
