<x-layout.app title="Page Builder - {{ $page->title }}" :pakaiSidebar="false" :pakaiTopBar="false">
    <div class="page-wrapper">
        <!-- Builder Header -->
        <div class="page-header d-print-none sticky-top bg-white border-bottom">
            <div class="container-fluid">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <a href="{{ url('panel/pages') }}" class="btn btn-ghost-secondary btn-icon me-3" title="Back to Pages">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="15,6 9,12 15,18"/>
                                </svg>
                            </a>
                            <div>
                                <h2 class="page-title mb-0">{{ $page->title }}</h2>
                                <div class="page-subtitle">Page Builder</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="btn-list">
                            <button type="button" class="btn btn-outline-primary" id="preview-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                </svg>
                                Preview
                            </button>
                            <button type="button" class="btn btn-success" id="save-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-16a2 2 0 0 1 2 -2"/>
                                    <circle cx="12" cy="14" r="2"/>
                                    <polyline points="14,4 14,8 8,8 8,4"/>
                                </svg>
                                Save Page
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1"/>
                                        <circle cx="19" cy="12" r="1"/>
                                        <circle cx="5" cy="12" r="1"/>
                                    </svg>
                                    More
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#" onclick="togglePublishStatus()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="9,11 12,14 20,4"/>
                                            <path d="M21 12v7a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h11"/>
                                        </svg>
                                        {{ $page->is_published ? 'Unpublish' : 'Publish' }}
                                    </a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#pageSettingsModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Page Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Builder Content -->
        <div class="page-body">
            <div class="container-fluid">
                <div class="row">
                    <!-- Components Sidebar -->
                    <div class="col-md-3 col-lg-2">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="card-title">Components</h4>
                            </div>
                            <div class="card-body p-2">
                                <div class="accordion" id="componentsAccordion">
                                    <!-- Layout Components -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#layout-components">
                                                Layout
                                            </button>
                                        </h2>
                                        <div id="layout-components" class="accordion-collapse collapse show" data-bs-parent="#componentsAccordion">
                                            <div class="accordion-body p-2">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="section" title="Section">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                                </svg>
                                                            </div>
                                                            <small>Section</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="container" title="Container">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="5" y="5" width="14" height="14" rx="1" ry="1"/>
                                                                </svg>
                                                            </div>
                                                            <small>Container</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="row" title="Row">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="4" y="6" width="16" height="4" rx="1"/>
                                                                    <rect x="4" y="14" width="16" height="4" rx="1"/>
                                                                </svg>
                                                            </div>
                                                            <small>Row</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="column" title="Column">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="6" y="4" width="4" height="16" rx="1"/>
                                                                    <rect x="14" y="4" width="4" height="16" rx="1"/>
                                                                </svg>
                                                            </div>
                                                            <small>Column</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content Components -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#content-components">
                                                Content
                                            </button>
                                        </h2>
                                        <div id="content-components" class="accordion-collapse collapse" data-bs-parent="#componentsAccordion">
                                            <div class="accordion-body p-2">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="heading" title="Heading">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M7 12h10"/>
                                                                    <path d="M7 4v16"/>
                                                                    <path d="M17 4v16"/>
                                                                    <path d="M15 20h4"/>
                                                                    <path d="M15 4h4"/>
                                                                    <path d="M5 20h4"/>
                                                                    <path d="M5 4h4"/>
                                                                </svg>
                                                            </div>
                                                            <small>Heading</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="text" title="Text">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <polyline points="4,7 4,4 20,4 20,7"/>
                                                                    <line x1="9" y1="20" x2="15" y2="20"/>
                                                                    <line x1="12" y1="4" x2="12" y2="20"/>
                                                                </svg>
                                                            </div>
                                                            <small>Text</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="image" title="Image">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                                    <circle cx="9" cy="9" r="2"/>
                                                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                                                </svg>
                                                            </div>
                                                            <small>Image</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="button" title="Button">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="3" y="8" width="18" height="8" rx="2" ry="2"/>
                                                                    <path d="M7 12h10"/>
                                                                </svg>
                                                            </div>
                                                            <small>Button</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Advanced Components -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#advanced-components">
                                                Advanced
                                            </button>
                                        </h2>
                                        <div id="advanced-components" class="accordion-collapse collapse" data-bs-parent="#componentsAccordion">
                                            <div class="accordion-body p-2">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="card" title="Card">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                                                                    <rect x="4" y="4" width="16" height="6" rx="2"/>
                                                                </svg>
                                                            </div>
                                                            <small>Card</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="list" title="List">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <line x1="8" y1="6" x2="21" y2="6"/>
                                                                    <line x1="8" y1="12" x2="21" y2="12"/>
                                                                    <line x1="8" y1="18" x2="21" y2="18"/>
                                                                    <line x1="3" y1="6" x2="3.01" y2="6"/>
                                                                    <line x1="3" y1="12" x2="3.01" y2="12"/>
                                                                    <line x1="3" y1="18" x2="3.01" y2="18"/>
                                                                </svg>
                                                            </div>
                                                            <small>List</small>
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

                    <!-- Builder Canvas -->
                    <div class="col-md-6 col-lg-8">
                        <div class="card h-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h4 class="card-title mb-0">Page Canvas</h4>
                                <div class="btn-list">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-canvas">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <line x1="4" y1="7" x2="20" y2="7"/>
                                            <line x1="10" y1="11" x2="10" y2="17"/>
                                            <line x1="14" y1="11" x2="14" y2="17"/>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                        </svg>
                                        Clear All
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="page-canvas" class="page-canvas">
                                    <div class="canvas-placeholder">
                                        <div class="text-center py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="4" y="4" width="16" height="4" rx="1"/>
                                                <rect x="4" y="12" width="6" height="8" rx="1"/>
                                                <rect x="14" y="12" width="6" height="8" rx="1"/>
                                            </svg>
                                            <h4 class="text-muted">Start Building Your Page</h4>
                                            <p class="text-muted">Drag components from the sidebar to start building your page</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Properties Panel -->
                    <div class="col-md-3 col-lg-2">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="card-title">Properties</h4>
                            </div>
                            <div class="card-body">
                                <div id="properties-panel">
                                    <div class="text-center text-muted py-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-3" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <p>Select a component to edit its properties</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Settings Modal -->
    <div class="modal modal-blur fade" id="pageSettingsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Page Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="pageSettingsForm">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Page Title</label>
                                    <input type="text" class="form-control" id="page-title" value="{{ $page->title }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">URL Slug</label>
                                    <input type="text" class="form-control" id="page-slug" value="{{ $page->slug }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Template</label>
                                    <select class="form-control" id="page-template">
                                        <option value="">Default Template</option>
                                        @if(isset($templates) && count($templates) > 0)
                                            @foreach($templates as $template)
                                                <option value="{{ $template['value'] }}" {{ $page->template == $template['value'] ? 'selected' : '' }}>{{ $template['label'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="page-sort-order" value="{{ $page->sort_order }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Title (SEO)</label>
                            <input type="text" class="form-control" id="page-meta-title" value="{{ $page->meta_title }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Description (SEO)</label>
                            <textarea class="form-control" id="page-meta-description" rows="3">{{ $page->meta_description }}</textarea>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="page-is-published" {{ $page->is_published ? 'checked' : '' }}>
                            <label class="form-check-label">Published</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePageSettings()">Save Settings</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .component-item {
            cursor: grab;
            border: 1px solid #e6ebf1;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
            transition: all 0.2s;
            background: white;
        }
        .component-item:hover {
            border-color: #206bc4;
            background: #f8f9fa;
        }
        .component-item:active {
            cursor: grabbing;
        }
        .component-preview {
            display: flex;
            justify-content: center;
            margin-bottom: 4px;
        }
        .page-canvas {
            min-height: 500px;
            border: 2px dashed #e6ebf1;
            border-radius: 8px;
            position: relative;
        }
        .page-canvas.drag-over {
            border-color: #206bc4;
            background: #f8f9fa;
        }
        .canvas-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        .canvas-placeholder.hidden {
            display: none;
        }
        .builder-component {
            position: relative;
            margin: 10px 0;
            padding: 15px;
            border: 1px solid transparent;
            border-radius: 4px;
            min-height: 40px;
        }
        .builder-component:hover {
            border-color: #206bc4;
        }
        .builder-component.selected {
            border-color: #206bc4;
            box-shadow: 0 0 0 2px rgba(32, 107, 196, 0.1);
        }
        .component-toolbar {
            position: absolute;
            top: -30px;
            right: 0;
            background: #206bc4;
            border-radius: 4px;
            padding: 2px;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .builder-component:hover .component-toolbar,
        .builder-component.selected .component-toolbar {
            opacity: 1;
        }
        .component-toolbar .btn {
            padding: 2px 6px;
            font-size: 12px;
            line-height: 1;
        }
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .sortable-ghost {
            opacity: 0.5;
        }
    </style>
    @endpush

    @push('scripts')
    <!-- Include Sortable.js for drag & drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        let pageData = @json($page);
        let builderData = [];
        let selectedComponent = null;

        // Initialize page builder
        document.addEventListener('DOMContentLoaded', function() {
            initializeBuilder();
            loadExistingContent();
        });

        function initializeBuilder() {
            // Make canvas sortable for drag & drop
            const canvas = document.getElementById('page-canvas');
            Sortable.create(canvas, {
                group: 'builder',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onAdd: function(evt) {
                    const componentType = evt.item.dataset.component;
                    if (componentType) {
                        const newComponent = createComponent(componentType);
                        evt.item.outerHTML = newComponent;
                        hideCanvasPlaceholder();
                    }
                },
                onSort: function(evt) {
                    updateBuilderData();
                }
            });

            // Make components draggable
            document.querySelectorAll('.component-item').forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('component', this.dataset.component);
                });
                item.draggable = true;
            });

            // Canvas drop handlers
            canvas.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });

            canvas.addEventListener('dragleave', function(e) {
                this.classList.remove('drag-over');
            });

            canvas.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');
                
                const componentType = e.dataTransfer.getData('component');
                if (componentType) {
                    const componentHtml = createComponent(componentType);
                    this.insertAdjacentHTML('beforeend', componentHtml);
                    hideCanvasPlaceholder();
                    updateBuilderData();
                }
            });
        }

        function createComponent(type) {
            const id = 'component-' + Date.now();
            let html = '';

            switch (type) {
                case 'section':
                    html = `<div class="builder-component" data-type="section" data-id="${id}">
                        <div class="component-toolbar">
                            <button class="btn btn-sm text-white" onclick="editComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button class="btn btn-sm text-white" onclick="deleteComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <section class="py-4">
                            <p class="text-muted mb-0">Section content goes here</p>
                        </section>
                    </div>`;
                    break;
                    
                case 'container':
                    html = `<div class="builder-component" data-type="container" data-id="${id}">
                        <div class="component-toolbar">
                            <button class="btn btn-sm text-white" onclick="editComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button class="btn btn-sm text-white" onclick="deleteComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <div class="container">
                            <p class="text-muted mb-0">Container content</p>
                        </div>
                    </div>`;
                    break;

                case 'heading':
                    html = `<div class="builder-component" data-type="heading" data-id="${id}">
                        <div class="component-toolbar">
                            <button class="btn btn-sm text-white" onclick="editComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button class="btn btn-sm text-white" onclick="deleteComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <h2>Heading Text</h2>
                    </div>`;
                    break;

                case 'text':
                    html = `<div class="builder-component" data-type="text" data-id="${id}">
                        <div class="component-toolbar">
                            <button class="btn btn-sm text-white" onclick="editComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button class="btn btn-sm text-white" onclick="deleteComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>`;
                    break;

                case 'button':
                    html = `<div class="builder-component" data-type="button" data-id="${id}">
                        <div class="component-toolbar">
                            <button class="btn btn-sm text-white" onclick="editComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button class="btn btn-sm text-white" onclick="deleteComponent('${id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <button class="btn btn-primary">Click Me</button>
                    </div>`;
                    break;

                default:
                    html = `<div class="builder-component" data-type="${type}" data-id="${id}">
                        <div class="component-toolbar">
                            <button class="btn btn-sm text-white" onclick="editComponent('${id}')">Edit</button>
                            <button class="btn btn-sm text-white" onclick="deleteComponent('${id}')">Delete</button>
                        </div>
                        <p>Component: ${type}</p>
                    </div>`;
            }

            return html;
        }

        function hideCanvasPlaceholder() {
            document.querySelector('.canvas-placeholder').classList.add('hidden');
        }

        function showCanvasPlaceholder() {
            const canvas = document.getElementById('page-canvas');
            if (canvas.children.length === 1) { // Only placeholder remains
                document.querySelector('.canvas-placeholder').classList.remove('hidden');
            }
        }

        function editComponent(id) {
            const component = document.querySelector(`[data-id="${id}"]`);
            if (component) {
                // Remove previous selection
                document.querySelectorAll('.builder-component.selected').forEach(el => {
                    el.classList.remove('selected');
                });
                
                // Select this component
                component.classList.add('selected');
                selectedComponent = id;
                
                // Show properties panel for this component
                showPropertiesPanel(component);
            }
        }

        function deleteComponent(id) {
            const component = document.querySelector(`[data-id="${id}"]`);
            if (component && confirm('Delete this component?')) {
                component.remove();
                updateBuilderData();
                showCanvasPlaceholder();
                
                // Clear properties panel if this component was selected
                if (selectedComponent === id) {
                    selectedComponent = null;
                    clearPropertiesPanel();
                }
            }
        }

        function showPropertiesPanel(component) {
            const panel = document.getElementById('properties-panel');
            const type = component.dataset.type;
            
            let html = `<h6 class="mb-3">${type.charAt(0).toUpperCase() + type.slice(1)} Properties</h6>`;
            
            // Add common properties based on component type
            switch (type) {
                case 'heading':
                    html += `
                        <div class="mb-3">
                            <label class="form-label">Text</label>
                            <input type="text" class="form-control" id="prop-text" value="${component.querySelector('h1,h2,h3,h4,h5,h6').textContent}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select class="form-control" id="prop-level">
                                <option value="h1">H1</option>
                                <option value="h2" selected>H2</option>
                                <option value="h3">H3</option>
                                <option value="h4">H4</option>
                                <option value="h5">H5</option>
                                <option value="h6">H6</option>
                            </select>
                        </div>`;
                    break;
                    
                case 'text':
                    html += `
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" id="prop-content" rows="4">${component.querySelector('p').textContent}</textarea>
                        </div>`;
                    break;
                    
                case 'button':
                    html += `
                        <div class="mb-3">
                            <label class="form-label">Text</label>
                            <input type="text" class="form-control" id="prop-text" value="${component.querySelector('button').textContent}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL</label>
                            <input type="url" class="form-control" id="prop-url" placeholder="https://">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Style</label>
                            <select class="form-control" id="prop-style">
                                <option value="btn-primary">Primary</option>
                                <option value="btn-secondary">Secondary</option>
                                <option value="btn-success">Success</option>
                                <option value="btn-danger">Danger</option>
                                <option value="btn-outline-primary">Outline Primary</option>
                            </select>
                        </div>`;
                    break;
            }
            
            html += `
                <button type="button" class="btn btn-primary btn-sm w-100" onclick="applyProperties('${component.dataset.id}')">
                    Apply Changes
                </button>`;
            
            panel.innerHTML = html;
        }

        function clearPropertiesPanel() {
            document.getElementById('properties-panel').innerHTML = `
                <div class="text-center text-muted py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-3" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <p>Select a component to edit its properties</p>
                </div>`;
        }

        function applyProperties(id) {
            const component = document.querySelector(`[data-id="${id}"]`);
            const type = component.dataset.type;
            
            switch (type) {
                case 'heading':
                    const text = document.getElementById('prop-text').value;
                    const level = document.getElementById('prop-level').value;
                    const heading = component.querySelector('h1,h2,h3,h4,h5,h6');
                    heading.outerHTML = `<${level}>${text}</${level}>`;
                    break;
                    
                case 'text':
                    const content = document.getElementById('prop-content').value;
                    component.querySelector('p').textContent = content;
                    break;
                    
                case 'button':
                    const btnText = document.getElementById('prop-text').value;
                    const btnUrl = document.getElementById('prop-url').value;
                    const btnStyle = document.getElementById('prop-style').value;
                    const button = component.querySelector('button');
                    button.textContent = btnText;
                    button.className = `btn ${btnStyle}`;
                    if (btnUrl) {
                        button.setAttribute('onclick', `window.open('${btnUrl}', '_blank')`);
                    }
                    break;
            }
            
            updateBuilderData();
        }

        function updateBuilderData() {
            const canvas = document.getElementById('page-canvas');
            const components = Array.from(canvas.querySelectorAll('.builder-component'));
            
            builderData = components.map(comp => ({
                id: comp.dataset.id,
                type: comp.dataset.type,
                html: comp.innerHTML,
                order: Array.from(comp.parentNode.children).indexOf(comp)
            }));
        }

        function loadExistingContent() {
            // If page has existing builder data, load it
            try {
                const existingData = JSON.parse(pageData.builder_data || '[]');
                if (existingData.length > 0) {
                    const canvas = document.getElementById('page-canvas');
                    existingData.forEach(comp => {
                        canvas.insertAdjacentHTML('beforeend', `<div class="builder-component" data-type="${comp.type}" data-id="${comp.id}">${comp.html}</div>`);
                    });
                    hideCanvasPlaceholder();
                }
            } catch (e) {
                console.log('No existing builder data found');
            }
        }

        // Save page functionality
        document.getElementById('save-btn').addEventListener('click', function() {
            updateBuilderData();
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('builder_data', JSON.stringify(builderData));
            formData.append('content', generateHtmlContent());
            
            fetch(`{{ url('panel/pages') }}/${pageData.id}`, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Page saved successfully!');
                } else {
                    alert('Error saving page: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving page');
            });
        });

        // Preview functionality
        document.getElementById('preview-btn').addEventListener('click', function() {
            window.open(`{{ route('dynamic.page', $page->slug) }}`, '_blank');
        });

        // Clear canvas
        document.getElementById('clear-canvas').addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all components?')) {
                document.getElementById('page-canvas').innerHTML = '<div class="canvas-placeholder"><div class="text-center py-5"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="4" rx="1"/><rect x="4" y="12" width="6" height="8" rx="1"/><rect x="14" y="12" width="6" height="8" rx="1"/></svg><h4 class="text-muted">Start Building Your Page</h4><p class="text-muted">Drag components from the sidebar to start building your page</p></div></div>';
                builderData = [];
                selectedComponent = null;
                clearPropertiesPanel();
            }
        });

        function generateHtmlContent() {
            const canvas = document.getElementById('page-canvas');
            let html = '';
            
            canvas.querySelectorAll('.builder-component').forEach(comp => {
                // Remove builder-specific elements and get clean HTML
                const cleanHtml = comp.innerHTML
                    .replace(/<div class="component-toolbar">.*?<\/div>/gs, '')
                    .trim();
                html += cleanHtml + '\n';
            });
            
            return html;
        }

        function savePageSettings() {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('title', document.getElementById('page-title').value);
            formData.append('slug', document.getElementById('page-slug').value);
            formData.append('template', document.getElementById('page-template').value);
            formData.append('sort_order', document.getElementById('page-sort-order').value);
            formData.append('meta_title', document.getElementById('page-meta-title').value);
            formData.append('meta_description', document.getElementById('page-meta-description').value);
            formData.append('is_published', document.getElementById('page-is-published').checked ? '1' : '0');
            
            fetch(`{{ url('panel/pages') }}/${pageData.id}`, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update page data
                    pageData.title = document.getElementById('page-title').value;
                    pageData.slug = document.getElementById('page-slug').value;
                    pageData.is_published = document.getElementById('page-is-published').checked;
                    
                    // Update page title in header
                    document.querySelector('.page-title').textContent = pageData.title;
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('pageSettingsModal')).hide();
                    
                    alert('Page settings saved successfully!');
                } else {
                    alert('Error saving settings: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving settings');
            });
        }

        function togglePublishStatus() {
            const newStatus = !pageData.is_published;
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('is_published', newStatus ? '1' : '0');
            
            fetch(`{{ url('panel/pages') }}/${pageData.id}`, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    pageData.is_published = newStatus;
                    location.reload(); // Reload to update UI
                } else {
                    alert('Error updating status: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating status');
            });
        }
    </script>
    @endpush
</x-layout.app>
