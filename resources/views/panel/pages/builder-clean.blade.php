<x-layout.app :title="'Page Builder - ' . $page->title" :pakaiSidebar="false" :pakaiTopBar="false">
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
                                                        <div class="component-item" data-component="section" title="Section" onclick="addComponent('section')">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="4" y="4" width="16" height="4" rx="1"/>
                                                                    <rect x="4" y="12" width="16" height="8" rx="1"/>
                                                                </svg>
                                                            </div>
                                                            <small>Section</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="container" title="Container" onclick="addComponent('container')">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="6" y="6" width="12" height="12" rx="2"/>
                                                                </svg>
                                                            </div>
                                                            <small>Container</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="row" title="Row" onclick="addComponent('row')">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="4" y="8" width="6" height="8" rx="1"/>
                                                                    <rect x="14" y="8" width="6" height="8" rx="1"/>
                                                                </svg>
                                                            </div>
                                                            <small>Row</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="column" title="Column" onclick="addComponent('column')">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="9" y="4" width="6" height="16" rx="1"/>
                                                                </svg>
                                                            </div>
                                                            <small>Column</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Basic Components -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#basic-components">
                                                Basic
                                            </button>
                                        </h2>
                                        <div id="basic-components" class="accordion-collapse collapse" data-bs-parent="#componentsAccordion">
                                            <div class="accordion-body p-2">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="heading" title="Heading" onclick="addComponent('heading')">
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
                                                        <div class="component-item" data-component="text" title="Text" onclick="addComponent('text')">
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
                                                        <div class="component-item" data-component="image" title="Image" onclick="addComponent('image')">
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
                                                        <div class="component-item" data-component="button" title="Button" onclick="addComponent('button')">
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
                                                        <div class="component-item" data-component="card" title="Card" onclick="addComponent('card')">
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
                                                        <div class="component-item" data-component="list" title="List" onclick="addComponent('list')">
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
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="hero" title="Hero Section" onclick="addComponent('hero')">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                                                    <line x1="8" y1="21" x2="16" y2="21"/>
                                                                    <line x1="12" y1="17" x2="12" y2="21"/>
                                                                </svg>
                                                            </div>
                                                            <small>Hero</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="component-item" data-component="tabs" title="Tabs" onclick="addComponent('tabs')">
                                                            <div class="component-preview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                                                                    <line x1="4" y1="8" x2="20" y2="8"/>
                                                                    <line x1="8" y1="4" x2="8" y2="8"/>
                                                                </svg>
                                                            </div>
                                                            <small>Tabs</small>
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
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearCanvas()">
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
                                            <p class="text-muted">Click components from the sidebar to start building your page</p>
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

    @push('styles')
    <style>
        .component-item {
            cursor: pointer;
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
    </style>
    @endpush

    @push('scripts')
    <script>
        let pageData = @json($page);
        let selectedComponent = null;

        // Initialize page builder
        document.addEventListener('DOMContentLoaded', function() {
            loadExistingContent();
        });

        function addComponent(type) {
            const id = 'component-' + Date.now();
            let html = '';

            // Generate component HTML based on type
            switch (type) {
                case 'section':
                    html = `{!! addslashes(view('components.builder.section', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'container':
                    html = `{!! addslashes(view('components.builder.container', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'row':
                    html = `{!! addslashes(view('components.builder.row', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'column':
                    html = `{!! addslashes(view('components.builder.column', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'heading':
                    html = `{!! addslashes(view('components.builder.heading', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'text':
                    html = `{!! addslashes(view('components.builder.text', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'button':
                    html = `{!! addslashes(view('components.builder.button', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'card':
                    html = `{!! addslashes(view('components.builder.card', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'hero':
                    html = `{!! addslashes(view('components.builder.hero', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'tabs':
                    html = `{!! addslashes(view('components.builder.tabs', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'image':
                    html = `{!! addslashes(view('components.builder.image', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
                    break;
                case 'list':
                    html = `{!! addslashes(view('components.builder.list', ['id' => 'REPLACE_ID'])->render()) !!}`.replace(/REPLACE_ID/g, id);
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

            const canvas = document.getElementById('page-canvas');
            canvas.insertAdjacentHTML('beforeend', html);
            hideCanvasPlaceholder();
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
                    const currentHeading = component.querySelector('h1,h2,h3,h4,h5,h6');
                    html += `
                        <div class="mb-3">
                            <label class="form-label">Text</label>
                            <input type="text" class="form-control" id="prop-text" value="${currentHeading ? currentHeading.textContent : ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select class="form-control" id="prop-level">
                                <option value="h1" ${currentHeading && currentHeading.tagName === 'H1' ? 'selected' : ''}>H1</option>
                                <option value="h2" ${currentHeading && currentHeading.tagName === 'H2' ? 'selected' : ''}>H2</option>
                                <option value="h3" ${currentHeading && currentHeading.tagName === 'H3' ? 'selected' : ''}>H3</option>
                                <option value="h4" ${currentHeading && currentHeading.tagName === 'H4' ? 'selected' : ''}>H4</option>
                                <option value="h5" ${currentHeading && currentHeading.tagName === 'H5' ? 'selected' : ''}>H5</option>
                                <option value="h6" ${currentHeading && currentHeading.tagName === 'H6' ? 'selected' : ''}>H6</option>
                            </select>
                        </div>`;
                    break;
                    
                case 'text':
                    const currentText = component.querySelector('p');
                    html += `
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" id="prop-content" rows="4">${currentText ? currentText.textContent : ''}</textarea>
                        </div>`;
                    break;
                    
                case 'button':
                    const currentButton = component.querySelector('button');
                    html += `
                        <div class="mb-3">
                            <label class="form-label">Text</label>
                            <input type="text" class="form-control" id="prop-text" value="${currentButton ? currentButton.textContent : ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL</label>
                            <input type="url" class="form-control" id="prop-url" value="">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Style</label>
                            <select class="form-control" id="prop-style">
                                <option value="btn-primary">Primary</option>
                                <option value="btn-secondary">Secondary</option>
                                <option value="btn-success">Success</option>
                                <option value="btn-danger">Danger</option>
                                <option value="btn-outline-primary">Outline Primary</option>
                                <option value="btn-outline-secondary">Outline Secondary</option>
                            </select>
                        </div>`;
                    break;

                case 'image':
                    const currentImg = component.querySelector('img');
                    html += `
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="prop-src" value="${currentImg ? currentImg.src : ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alt Text</label>
                            <input type="text" class="form-control" id="prop-alt" value="${currentImg ? currentImg.alt : ''}">
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
                    heading.outerHTML = `<${level} class="mb-3">${text}</${level}>`;
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
                        // Convert button to link if URL provided
                        const newElement = document.createElement('a');
                        newElement.href = btnUrl;
                        newElement.className = button.className;
                        newElement.textContent = button.textContent;
                        button.parentNode.replaceChild(newElement, button);
                    }
                    break;

                case 'image':
                    const imgSrc = document.getElementById('prop-src').value;
                    const imgAlt = document.getElementById('prop-alt').value;
                    const img = component.querySelector('img');
                    img.src = imgSrc;
                    img.alt = imgAlt;
                    break;
            }
        }

        function loadExistingContent() {
            // If page has existing content, try to load it
            if (pageData.content && pageData.content.trim()) {
                const canvas = document.getElementById('page-canvas');
                canvas.innerHTML = pageData.content;
                hideCanvasPlaceholder();
            }
        }

        function generateHtmlContent() {
            const canvas = document.getElementById('page-canvas');
            let html = '';
            
            canvas.querySelectorAll('.builder-component').forEach(comp => {
                const type = comp.dataset.type;
                let cleanHtml = '';
                
                // Extract only the actual content based on component type
                switch (type) {
                    case 'section':
                        const sectionContent = comp.querySelector('section');
                        if (sectionContent) {
                            cleanHtml = sectionContent.outerHTML;
                        }
                        break;
                        
                    case 'container':
                        const containerContent = comp.querySelector('.container');
                        if (containerContent) {
                            cleanHtml = containerContent.outerHTML;
                        }
                        break;
                        
                    case 'heading':
                        const headingContent = comp.querySelector('h1,h2,h3,h4,h5,h6');
                        if (headingContent) {
                            cleanHtml = headingContent.outerHTML;
                        }
                        break;
                        
                    case 'text':
                        const textContent = comp.querySelector('p');
                        if (textContent) {
                            cleanHtml = textContent.outerHTML;
                        }
                        break;
                        
                    case 'button':
                        const buttonContent = comp.querySelector('button, a.btn');
                        if (buttonContent) {
                            cleanHtml = buttonContent.outerHTML;
                        }
                        break;
                        
                    case 'card':
                        const cardContent = comp.querySelector('.card');
                        if (cardContent) {
                            cleanHtml = cardContent.outerHTML;
                        }
                        break;
                        
                    case 'list':
                        const listContent = comp.querySelector('.list-group, ul, ol');
                        if (listContent) {
                            cleanHtml = listContent.outerHTML;
                        }
                        break;

                    case 'image':
                        const imageContent = comp.querySelector('img');
                        if (imageContent) {
                            cleanHtml = imageContent.outerHTML;
                        }
                        break;

                    case 'row':
                        const rowContent = comp.querySelector('.row');
                        if (rowContent) {
                            cleanHtml = rowContent.outerHTML;
                        }
                        break;

                    case 'column':
                        const columnContent = comp.querySelector('.col-md-12, [class*="col-"]');
                        if (columnContent) {
                            cleanHtml = columnContent.outerHTML;
                        }
                        break;

                    case 'hero':
                        const heroContent = comp.querySelector('.hero');
                        if (heroContent) {
                            cleanHtml = heroContent.outerHTML;
                        }
                        break;

                    case 'tabs':
                        const tabsContent = comp.querySelector('.card');
                        if (tabsContent) {
                            cleanHtml = tabsContent.outerHTML;
                        }
                        break;
                        
                    default:
                        // For custom components, try to extract meaningful content
                        const allContent = comp.innerHTML
                            .replace(/<div class="component-toolbar">.*?<\/div>/gs, '')
                            .trim();
                        cleanHtml = allContent;
                }
                
                if (cleanHtml) {
                    html += cleanHtml + '\n';
                }
            });
            
            return html;
        }

        function clearCanvas() {
            if (confirm('Are you sure you want to clear all components?')) {
                document.getElementById('page-canvas').innerHTML = '<div class="canvas-placeholder"><div class="text-center py-5"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="4" rx="1"/><rect x="4" y="12" width="6" height="8" rx="1"/><rect x="14" y="12" width="6" height="8" rx="1"/></svg><h4 class="text-muted">Start Building Your Page</h4><p class="text-muted">Click components from the sidebar to start building your page</p></div></div>';
                selectedComponent = null;
                clearPropertiesPanel();
            }
        }

        // Save page functionality
        document.getElementById('save-btn').addEventListener('click', function() {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('_method', 'PUT');
            
            // Include required fields from current page data
            formData.append('title', pageData.title);
            formData.append('slug', pageData.slug);
            formData.append('template', pageData.template || '');
            formData.append('sort_order', pageData.sort_order || '0');
            formData.append('meta_title', pageData.meta_title || '');
            formData.append('meta_description', pageData.meta_description || '');
            formData.append('is_published', pageData.is_published ? '1' : '0');
            
            // Include builder-specific data
            formData.append('content', generateHtmlContent());
            
            fetch(`{{ route('panel.pages.update', $page->id) }}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Page saved successfully!');
                } else {
                    alert('Error saving page: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Save error:', error);
                alert('Error saving page: ' + error.message);
            });
        });

        // Preview functionality
        document.getElementById('preview-btn').addEventListener('click', function() {
            window.open(`{{ route('dynamic.page', $page->slug) }}`, '_blank');
        });
    </script>
    @endpush
</x-layout.app>
