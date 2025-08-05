<x-layout.app title="Pages Management - Panel Admin" :pakaiSidebar="true" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-header d-print-none" aria-label="Page header">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">Panel/Pages</div>
                        <h2 class="page-title">Pages Management</h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            @can('create-pages')
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#uploadTemplateModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon me-1">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    Add Template
                                </button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createPageModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon me-1">
                                        <line x1="12" y1="5" x2="12" y2="19">
                                        </line>
                                        <line x1="5" y1="12" x2="19" y2="12">
                                        </line>
                                    </svg>
                                    Add Page
                                </button>
                            @else
                                <a href="{{ route('panel.pages.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon me-1">
                                        <line x1="12" y1="5" x2="12" y2="19">
                                        </line>
                                        <line x1="5" y1="12" x2="19" y2="12">
                                        </line>
                                    </svg>
                                    Add Page
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row row-cards">
                    <x-alert.modal-alert />

                    <!-- Main Card with Tabs -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#pages-tab" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                            </svg>
                                            Pages
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#templates-tab" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <rect x="4" y="4" width="16" height="4" rx="1"/>
                                                <rect x="4" y="12" width="6" height="8" rx="1"/>
                                                <rect x="14" y="12" width="6" height="8" rx="1"/>
                                            </svg>
                                            Templates
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <!-- Pages Tab -->
                                <div class="tab-pane fade show active" id="pages-tab" role="tabpanel">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <div>
                                                <h3 class="m-0">Page Builder</h3>
                                                <p class="text-muted mb-0">Create and manage pages with drag & drop components</p>
                                            </div>
                                            <div class="btn-list">
                                                @can('create-pages')
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#createPageBuilderModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon me-1">
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        </svg>
                                                        Create New Page
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>

                                        <!-- Pages Grid -->
                                        <div class="row g-3" id="pages-grid">
                                            @if(isset($pages) && count($pages) > 0)
                                                @foreach($pages as $page)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="card card-link">
                                                            <div class="card-status-start bg-{{ $page->is_published ? 'green' : 'orange' }}"></div>
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <div class="me-auto">
                                                                        <h3 class="card-title mb-1">{{ $page->title }}</h3>
                                                                        <div class="text-muted small">{{ $page->slug }}</div>
                                                                    </div>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-ghost-secondary btn-icon" data-bs-toggle="dropdown">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                                <circle cx="12" cy="12" r="1"/>
                                                                                <circle cx="19" cy="12" r="1"/>
                                                                                <circle cx="5" cy="12" r="1"/>
                                                                            </svg>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <a class="dropdown-item" href="{{ url('panel/pages/' . $page->id . '/builder') }}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                                    <rect x="4" y="4" width="16" height="4" rx="1"/>
                                                                                    <rect x="4" y="12" width="6" height="8" rx="1"/>
                                                                                    <rect x="14" y="12" width="6" height="8" rx="1"/>
                                                                                </svg>
                                                                                Edit with Builder
                                                                            </a>
                                                                            <a class="dropdown-item" href="{{ route('dynamic.page', $page->slug) }}" target="_blank">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                                    <path d="M9 12l2 2l4 -4"/>
                                                                                    <path d="M21 12c-.552 0-1 .436-1 .937v6.063a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h6"/>
                                                                                </svg>
                                                                                Preview
                                                                            </a>
                                                                            <div class="dropdown-divider"></div>
                                                                            @can('update-pages')
                                                                                <a class="dropdown-item" href="#" onclick="duplicatePage({{ $page->id }})">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                                        <rect x="8" y="8" width="12" height="12" rx="2"/>
                                                                                        <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"/>
                                                                                    </svg>
                                                                                    Duplicate
                                                                                </a>
                                                                            @endcan
                                                                            @can('delete-pages')
                                                                                <a class="dropdown-item text-danger" href="#" onclick="deletePage({{ $page->id }}, '{{ $page->title }}')">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                                        <line x1="4" y1="7" x2="20" y2="7"/>
                                                                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                                                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                                                    </svg>
                                                                                    Delete
                                                                                </a>
                                                                            @endcan
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="mb-3">
                                                                    <div class="text-muted small mb-1">Content Preview</div>
                                                                    <div class="text-truncate" style="max-height: 40px; overflow: hidden;">
                                                                        {{ Str::limit(strip_tags($page->content), 80) }}
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="d-flex align-items-center text-muted">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                                                        </svg>
                                                                        <small>{{ $page->created_at->format('M d, Y') }}</small>
                                                                    </div>
                                                                    <div class="badge bg-{{ $page->is_published ? 'green' : 'orange' }}">
                                                                        {{ $page->is_published ? 'Published' : 'Draft' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-12">
                                                    <div class="empty">
                                                        <div class="empty-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <rect x="4" y="4" width="16" height="4" rx="1"/>
                                                                <rect x="4" y="12" width="6" height="8" rx="1"/>
                                                                <rect x="14" y="12" width="6" height="8" rx="1"/>
                                                            </svg>
                                                        </div>
                                                        <p class="empty-title">No pages yet</p>
                                                        <p class="empty-subtitle text-muted">
                                                            Create your first page with our drag & drop page builder
                                                        </p>
                                                        <div class="empty-action">
                                                            @can('create-pages')
                                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPageBuilderModal">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                                    </svg>
                                                                    Create Your First Page
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Templates Tab -->
                                <div class="tab-pane fade" id="templates-tab" role="tabpanel">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h3 class="m-0">Template Manager</h3>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14,2 14,8 20,8"></polyline>
                                                    <line x1="12" y1="11" x2="12" y2="17"></line>
                                                    <line x1="9" y1="14" x2="15" y2="14"></line>
                                                </svg>
                                                Upload Template
                                            </button>
                                        </div>

                                        <div class="row g-3" id="templates-list">
                                            @if(isset($templates) && count($templates) > 0)
                                                @foreach($templates as $template)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div>
                                                                        <h4 class="card-title mb-1">{{ $template['label'] }}</h4>
                                                                        <div class="text-muted">{{ $template['value'] }}.blade.php</div>
                                                                    </div>
                                                                    <div class="btn-list">
                                                                        @if(!in_array($template['value'], ['default', 'about', 'kontak', 'layanan']))
                                                                            <button type="button" class="btn btn-sm btn-outline-danger delete-template" data-template="{{ $template['value'] }}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                                    <line x1="4" y1="7" x2="20" y2="7"/>
                                                                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                                                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                                                </svg>
                                                                            </button>
                                                                        @else
                                                                            <span class="badge bg-blue">Core</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @if(file_exists($template['file']))
                                                                    <div class="mt-2">
                                                                        <small class="text-muted">
                                                                            Size: {{ round(filesize($template['file']) / 1024, 2) }} KB
                                                                        </small>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-12">
                                                    <div class="empty">
                                                        <div class="empty-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <rect x="4" y="4" width="16" height="4" rx="1"/>
                                                                <rect x="4" y="12" width="6" height="8" rx="1"/>
                                                                <rect x="14" y="12" width="6" height="8" rx="1"/>
                                                            </svg>
                                                        </div>
                                                        <p class="empty-title">No templates found</p>
                                                        <p class="empty-subtitle text-muted">
                                                            Upload your first template to get started
                                                        </p>
                                                        <div class="empty-action">
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                                </svg>
                                                                Upload Template
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                                        <ul class="pagination m-0 ms-auto" id="datatable-pagination">
                                            <!-- Pagination will be filled by DataTables -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Modals --}}
    @can('create-pages')
        <!-- Create Page Builder Modal -->
        <div class="modal modal-blur fade" id="createPageBuilderModal" tabindex="-1" aria-labelledby="createPageBuilderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createPageBuilderModalLabel">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="4" width="16" height="4" rx="1"/>
                                <rect x="4" y="12" width="6" height="8" rx="1"/>
                                <rect x="14" y="12" width="6" height="8" rx="1"/>
                            </svg>
                            Create New Page
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form method="POST" action="{{ url('/panel/pages') }}" id="createPageBuilderForm">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="builder_title" class="form-label">
                                            Page Title <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="builder_title" name="title" required 
                                               placeholder="Enter page title">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="builder_slug" class="form-label">
                                            URL Slug <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="builder_slug" name="slug" required
                                               placeholder="page-url-slug">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="builder_template" class="form-label">Page Template</label>
                                        <select class="form-control" id="builder_template" name="template">
                                            <option value="">Default Template</option>
                                            @if(isset($templates) && count($templates) > 0)
                                                @foreach($templates as $template)
                                                    <option value="{{ $template['value'] }}">{{ $template['label'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="builder_sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control" id="builder_sort_order" name="sort_order" 
                                               value="0" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="builder_meta_title" class="form-label">Meta Title (SEO)</label>
                                <input type="text" class="form-control" id="builder_meta_title" name="meta_title"
                                       placeholder="SEO meta title">
                            </div>

                            <div class="mb-3">
                                <label for="builder_meta_description" class="form-label">Meta Description (SEO)</label>
                                <textarea class="form-control" id="builder_meta_description" name="meta_description" rows="2"
                                          placeholder="SEO meta description"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="builder_is_published" name="is_published" value="1">
                                        <label class="form-check-label" for="builder_is_published">
                                            Publish immediately
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="builder_open_after_create" name="open_after_create" value="1" checked>
                                        <label class="form-check-label" for="builder_open_after_create">
                                            Open in builder after creating
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden field for builder content -->
                            <input type="hidden" name="content" value="[]" id="builder_content">
                            <input type="hidden" name="builder_data" value="[]" id="builder_data">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"/>
                                    <path d="m6 6 12 12"/>
                                </svg>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="4" width="16" height="4" rx="1"/>
                                    <rect x="4" y="12" width="6" height="8" rx="1"/>
                                    <rect x="14" y="12" width="6" height="8" rx="1"/>
                                </svg>
                                Create Page
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @push('scripts')
        <!-- Pages DataTable Module -->
        <script src="{{ asset('js/datatable/pages.js') }}"></script>
        <script>
            let pagesTable;

            // Set global routes
            window.pageEditRoute = '{{ route('panel.pages.edit', ':id') }}';
            window.pageDeleteRoute = '{{ route('panel.pages.destroy', ':id') }}';
            window.pageViewRoute = '{{ route('dynamic.page', ':slug') }}'; // Use dynamic route for page viewing

            // Initialize Pages DataTable
            PagesDataTable.initialize('{{ route('panel.pages') }}')
                .then(table => {
                    pagesTable = table;
                    PagesDataTable.setupModalHandlers();
                    PagesDataTable.setBulkDeleteRoute('{{ route('panel.pages.bulkDestroy') }}');
                })
                .catch(error => {
                    console.error('Failed to initialize Pages DataTable:', error);
                });

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        PagesDataTable.refreshDataTable();
                    }, 1000);
                });
            @endif

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.editPage = (pageId) => PagesDataTable.editPage(pageId, '{{ route('panel.pages.edit', ':id') }}');
            window.deletePage = (pageId, pageTitle) => PagesDataTable.deletePage(pageId, pageTitle);
            window.viewPage = (pageSlug) => PagesDataTable.viewPage(pageSlug);

            // Handle page builder form submission
            document.getElementById('createPageBuilderForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.redirect_url) {
                            // Open builder if requested
                            window.location.href = data.redirect_url;
                        } else {
                            // Just refresh the page list
                            window.location.reload();
                        }
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error creating page');
                });
            });

            // Auto-generate slug from title in page builder modal
            document.getElementById('builder_title').addEventListener('input', function() {
                const title = this.value;
                const slug = title.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                document.getElementById('builder_slug').value = slug;
            });

            // Global functions for page actions
            window.duplicatePage = function(pageId) {
                if (confirm('Duplicate this page?')) {
                    fetch(`{{ url('panel/pages') }}/${pageId}/duplicate`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error duplicating page');
                    });
                }
            };

            window.deletePage = function(pageId, pageTitle) {
                if (confirm(`Delete page "${pageTitle}"?`)) {
                    fetch(`{{ url('panel/pages') }}/${pageId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting page');
                    });
                }
            };
        </script>
    @endpush

    {{-- Upload Template Modal --}}
    <div class="modal modal-blur fade" id="uploadTemplateModal" tabindex="-1" aria-labelledby="uploadTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadTemplateModalLabel">
                        <i class="fas fa-upload me-2"></i>Upload Page Template
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ url('panel/pages/upload-template') }}" enctype="multipart/form-data" id="uploadTemplateForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Template Upload</h6>
                                    <ul class="mb-0">
                                        <li>Upload <code>.blade.php</code> files only</li>
                                        <li>Template will be saved to <code>resources/views/pages/templates/</code></li>
                                        <li>File name will be used as template name</li>
                                        <li>Template must use <code>$page</code> variable</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="template_file" class="form-label">
                                    Template File <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" id="template_file" name="template_file" accept=".blade.php,.php" required>
                                <small class="form-text text-muted">Select .blade.php file to upload</small>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="overwrite_existing" name="overwrite_existing">
                                    <label class="form-check-label" for="overwrite_existing">
                                        Overwrite if template already exists
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reset form when modal closes
            document.getElementById('uploadTemplateModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('uploadTemplateForm').reset();
            });

            // File validation
            document.getElementById('template_file').addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const fileName = file.name;
                    const isValidExtension = fileName.endsWith('.blade.php') || fileName.endsWith('.php');
                    
                    if (!isValidExtension) {
                        alert('Please select a .blade.php or .php file');
                        this.value = '';
                        return;
                    }

                    // Show selected file name
                    console.log('Selected file:', fileName);
                }
            });

            // Form submit handler
            document.getElementById('uploadTemplateForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                const fileInput = document.getElementById('template_file');
                if (!fileInput.files || !fileInput.files[0]) {
                    alert('Please select a template file to upload');
                    return;
                }

                // Create FormData for file upload
                const formData = new FormData(this);
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
                submitBtn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Close modal and refresh page to show new template
                        bootstrap.Modal.getInstance(document.getElementById('uploadTemplateModal')).hide();
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Upload failed'));
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Error uploading template');
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        });
    </script>

    <script>
        // Template delete functionality
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-template').forEach(button => {
                button.addEventListener('click', function() {
                    const templateSlug = this.getAttribute('data-template');
                    const templateName = this.closest('.card').querySelector('.card-title').textContent;
                    
                    if (confirm(`Are you sure you want to delete template "${templateName}"?`)) {
                        fetch('{{ url("/panel/pages/deleteTemplate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                template_slug: templateSlug
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload(); // Refresh to update template list
                            } else {
                                alert(data.message || 'Failed to delete template');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to delete template');
                        });
                    }
                });
            });
        });
    </script>
</x-layout.app>

