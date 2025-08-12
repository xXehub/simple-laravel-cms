<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h1 class="page-title">{{ $page->title }}</h1>
                        @if($page->meta_description)
                            <div class="page-subtitle">{{ $page->meta_description }}</div>
                        @endif
                    </div>
                    @auth
                        @can('update-pages')
                            <div class="col-auto ms-auto d-print-none">
                                <a href="{{ route('dynamic.page', ['slug' => 'panel/pages']) }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                        <path d="M16 5l3 3"/>
                                    </svg>
                                    Edit Page
                                </a>
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
                                <!-- Contact Header -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            {!! $page->content ?? '<h2>Contact Us</h2><p>Get in touch with our team. We are here to help you.</p>' !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Contact Information</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="datagrid">
                                                    <div class="datagrid-item">
                                                        <div class="datagrid-title">Address</div>
                                                        <div class="datagrid-content">
                                                            123 Business Street<br>
                                                            Business District<br>
                                                            City, State 12345
                                                        </div>
                                                    </div>
                                                    <div class="datagrid-item">
                                                        <div class="datagrid-title">Phone</div>
                                                        <div class="datagrid-content">
                                                            +1 (555) 123-4567
                                                        </div>
                                                    </div>
                                                    <div class="datagrid-item">
                                                        <div class="datagrid-title">Email</div>
                                                        <div class="datagrid-content">
                                                            contact@example.com
                                                        </div>
                                                    </div>
                                                    <div class="datagrid-item">
                                                        <div class="datagrid-title">Business Hours</div>
                                                        <div class="datagrid-content">
                                                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                                                            Saturday: 10:00 AM - 4:00 PM<br>
                                                            Sunday: Closed
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Form -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Send us a Message</h3>
                                            </div>
                                            <div class="card-body">
                                                <form action="#" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Name</label>
                                                                <input type="text" class="form-control" name="name" placeholder="Your name" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" class="form-control" name="email" placeholder="Your email" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Subject</label>
                                                        <input type="text" class="form-control" name="subject" placeholder="Message subject" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Message</label>
                                                        <textarea class="form-control" name="message" rows="6" placeholder="Your message" required></textarea>
                                                    </div>
                                                    <div class="form-footer">
                                                        <button type="submit" class="btn btn-primary">Send Message</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Location</h3>
                                            </div>
                                            <div class="card-body">
                                                <!-- You can add a map here -->
                                                <div class="ratio ratio-16x9">
                                                    <iframe 
                                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613!3d-6.2297465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta%2C%20Indonesia!5e0!3m2!1sen!2sus!4v1641234567890!5m2!1sen!2sus" 
                                                        style="border:0;" 
                                                        allowfullscreen="" 
                                                        loading="lazy" 
                                                        referrerpolicy="no-referrer-when-downgrade">
                                                    </iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
