<x-layout.app title="{{ setting('welcome_title', 'Laravel Superapp CMS') }}" :pakai-sidebar="false" :use-fluid="false">
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                
                <!-- Hero Section -->
                <div class="col-12">
                    <div class="card">
                        <div class="row row-0">
                            <div class="col-3 order-md-last">
                                <!-- Hero Image -->
                                <div class="card-body">
                                    <img src="{{ asset('static/sby-hebat-2024.png') }}"
                                        class="w-100 h-100 object-cover card-img-end">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card-body">
                                    <h1 class="display-4">{{ setting('welcome_title', 'Laravel Superapp CMS') }}</h1>
                                    <p class="lead text-muted">
                                        {{ setting('welcome_subtitle', 'A powerful content management system built with Laravel 12, featuring role-based permissions, dynamic menus, and clean architecture.') }}
                                    </p>

                                    <div class="mt-4">
                                        @guest
                                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">
                                                <i class="fas fa-sign-in-alt"></i> Login untuk Akses Penuh
                                            </a>
                                            @if(Route::has('register'))
                                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                                                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                                                </a>
                                            @endif
                                        @else
                                            @php
                                                // Check if user has access to panel/dashboard menu dynamically
                                                $panelMenu = \App\Models\MasterMenu::active()
                                                    ->where('slug', 'panel/dashboard')
                                                    ->whereHas('roles', function ($q) {
                                                        $q->whereIn('role_id', auth()->user()->roles->pluck('id'));
                                                    })
                                                    ->first();
                                            @endphp

                                            @if ($panelMenu)
                                                <a class="btn btn-primary btn-lg me-2" href="{{ $panelMenu->getUrl() }}">
                                                    <i class="fas fa-tachometer-alt"></i> Go to Panel
                                                </a>
                                            @endif

                                            <a class="btn btn-outline-primary btn-lg" href="{{ route('profile') }}">
                                                <i class="fas fa-user"></i> My Profile
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="col-12">
                    <h2 class="mb-4">{{ setting('site_title', 'Our Features') }}</h2>
                    <div class="row">
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-3">
                                        <i class="{{ setting('feature_1_icon', 'fas fa-shield-alt') }} fa-3x"></i>
                                    </div>
                                    <h5 class="card-title">{{ setting('feature_1_title', 'Role-Based Access') }}</h5>
                                    <p class="card-text">
                                        {{ setting('feature_1_description', 'Spatie Laravel Permission for granular access control with roles and permissions.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-success mb-3">
                                        <i class="{{ setting('feature_2_icon', 'fas fa-bars') }} fa-3x"></i>
                                    </div>
                                    <h5 class="card-title">{{ setting('feature_2_title', 'Dynamic Menus') }}</h5>
                                    <p class="card-text">
                                        {{ setting('feature_2_description', 'Hierarchical menu system with role-based visibility using clean relationship queries.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-info mb-3">
                                        <i class="{{ setting('feature_3_icon', 'fas fa-file-alt') }} fa-3x"></i>
                                    </div>
                                    <h5 class="card-title">{{ setting('feature_3_title', 'Dynamic Pages') }}</h5>
                                    <p class="card-text">
                                        {{ setting('feature_3_description', 'Slug-based routing with custom templates and SEO-friendly URL management.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-3">
                                        <i class="{{ setting('feature_4_icon', 'fas fa-code') }} fa-3x"></i>
                                    </div>
                                    <h5 class="card-title">{{ setting('feature_4_title', 'Clean Code') }}</h5>
                                    <p class="card-text">
                                        {{ setting('feature_4_description', 'Modern Laravel practices with minimal conditionals, Blade components, and policies.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Pages Section -->
                <div class="col-12">
                    <h2 class="mb-4">{{ setting('sample_pages_title', 'Explore Sample Pages') }}</h2>
                    <div class="row">
                        @php
                            $samplePages = \App\Models\Page::published()->orderBy('sort_order')->take(4)->get();
                        @endphp

                        @forelse($samplePages as $page)
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4 text-center">
                                        <span class="avatar avatar-xl mb-3 bg-primary text-white">
                                            {{ strtoupper(substr($page->title, 0, 1)) }}
                                        </span>
                                        <h3 class="m-0 mb-2">
                                            <a href="{{ url($page->slug) }}" class="text-decoration-none">
                                                {{ $page->title }}
                                            </a>
                                        </h3>
                                        <div class="text-secondary">
                                            {{ Str::limit(strip_tags($page->content), 100) }}
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 pt-0">
                                        <a href="{{ url($page->slug) }}" class="btn btn-outline-primary w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                            </svg>
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <div class="empty">
                                        <div class="empty-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                            </svg>
                                        </div>
                                        <p class="empty-title">No pages available yet</p>
                                        <p class="empty-subtitle text-muted">
                                            Login as admin to create some pages!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Test Accounts Section (Only for guests) -->
                @guest
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-users text-primary"></i> Test Accounts
                                </h3>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">Use these accounts to explore different role-based features:</p>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-danger">
                                            <div class="card-body">
                                                <h6 class="card-title text-danger">
                                                    <i class="fas fa-crown"></i> Admin Account
                                                </h6>
                                                <p class="mb-1"><strong>Email:</strong> admin@example.com</p>
                                                <p class="mb-1"><strong>Password:</strong> password</p>
                                                <small class="text-muted">Full system access</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-warning">
                                            <div class="card-body">
                                                <h6 class="card-title text-warning">
                                                    <i class="fas fa-edit"></i> Editor Account
                                                </h6>
                                                <p class="mb-1"><strong>Email:</strong> editor@example.com</p>
                                                <p class="mb-1"><strong>Password:</strong> password</p>
                                                <small class="text-muted">Posts & Pages management</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-info">
                                            <div class="card-body">
                                                <h6 class="card-title text-info">
                                                    <i class="fas fa-eye"></i> Viewer Account
                                                </h6>
                                                <p class="mb-1"><strong>Email:</strong> viewer@example.com</p>
                                                <p class="mb-1"><strong>Password:</strong> password</p>
                                                <small class="text-muted">Read-only access</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest

            </div>
        </div>
    </div>
</x-layout.app>
