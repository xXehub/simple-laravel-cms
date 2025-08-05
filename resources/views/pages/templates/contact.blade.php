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
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Get in Touch</h3>
                                <div class="prose prose-lg">
                                    {!! nl2br(e($page->content)) !!}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Form -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h3 class="card-title">Send us a message</h3>
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" placeholder="Your name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" placeholder="your@email.com">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <input type="text" class="form-control" placeholder="Message subject">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Message</label>
                                        <textarea class="form-control" rows="6" placeholder="Your message"></textarea>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <line x1="10" y1="14" x2="21" y2="3"/>
                                                <path d="M21 3L14.5 21A.55 .55 0 0 1 14 21.5A.55 .55 0 0 1 13.5 21L3 10.5A.55 .55 0 0 1 3 10A.55 .55 0 0 1 3.5 9.5L21 3Z"/>
                                            </svg>
                                            Send Message
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Contact Info -->
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Contact Information</h3>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar avatar-sm bg-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Phone</div>
                                                <div class="text-secondary">+62 123 456 7890</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar avatar-sm bg-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
                                                        <path d="M3 7l9 6l9 -6"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Email</div>
                                                <div class="text-secondary">contact@example.com</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar avatar-sm bg-warning">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M21 3L14.5 21A.55 .55 0 0 1 14 21.5A.55 .55 0 0 1 13.5 21L3 10.5A.55 .55 0 0 1 3 10A.55 .55 0 0 1 3.5 9.5L21 3Z"/>
                                                        <path d="M9 11L3 9L21 3L11 15"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Address</div>
                                                <div class="text-secondary">123 Street Name, City, Country</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Office Hours -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h3 class="card-title">Office Hours</h3>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Monday - Friday</span>
                                        <span class="text-secondary">9:00 AM - 5:00 PM</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Saturday</span>
                                        <span class="text-secondary">9:00 AM - 12:00 PM</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Sunday</span>
                                        <span class="text-secondary">Closed</span>
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
