{{-- Edit Menu Modal --}}
<div class="modal modal-blur fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">
                    <i class="fas fa-edit me-2"></i>{{ $data['edit_title'] ?? 'Edit Menu' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" id="editMenuForm"
                data-route-template="{{ route('panel.menus.update', ':id') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_menu_id">

                <div class="modal-body">
                    {{-- Display Validation Errors --}}
                    @if ($errors->any() && old('_method') === 'PUT')
                        <div class="alert alert-danger mb-3">
                            <h6 class="mb-2">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tab Navigation --}}
                    <div class="card-tabs mb-3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#edit-basic-tab" class="nav-link active" data-bs-toggle="tab"
                                    aria-selected="true" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ $data['tab_basic'] ?? 'Basic Info' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#edit-technical-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                    <i class="fas fa-code me-2"></i>
                                    {{ $data['tab_technical'] ?? 'Technical' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#edit-permissions-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                 <i class="fa-solid fa-address-card me-2"></i>
                                    {{ $data['tab_permissions'] ?? 'Permissions' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#edit-seo-tab" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                    role="tab">
                                    <i class="fas fa-search me-2"></i>
                                    {{ $data['tab_seo'] ?? 'SEO & Meta' }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Tab Content --}}
                    <div class="tab-content">
                        {{-- Basic Information Tab --}}
                        <div class="tab-pane fade show active" id="edit-basic-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="edit_nama_menu" class="form-label">
                                        Menu Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_nama_menu" name="nama_menu"
                                        required>
                                    <small class="form-text text-muted">Display name for the menu item</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_slug" class="form-label">
                                        Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_slug" name="slug" required>
                                    <small class="form-text text-muted">URL path (e.g., panel/users)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_icon" class="form-label">Icon</label>
                                    <input type="text" class="form-control" id="edit_icon" name="icon">
                                    <small class="form-text text-muted">FontAwesome class (e.g., fas fa-users)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_urutan" class="form-label">
                                        Sort Order <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="edit_urutan" name="urutan" required
                                        min="1">
                                    <small class="form-text text-muted">Display order in menu</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parent Menu</label>
                                    <select class="form-select" id="edit_parent_id" name="parent_id">
                                        <option value="">-- Root Menu --</option>
                                        @foreach ($parentMenus as $id => $name)
                                            <option value="{{ $id }}">
                                                {{ str_replace(['└─ ', 'Parent - '], '', $name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Select parent for submenu</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" class="form-check-input" id="edit_is_active"
                                            name="is_active" value="1">
                                        <label class="form-check-label" for="edit_is_active">
                                            Active Menu
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enable/disable this menu</small>
                                </div>
                            </div>
                        </div>

                        {{-- Technical Configuration Tab --}}
                        <div class="tab-pane fade" id="edit-technical-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="edit_route_name" class="form-label">Route Name</label>
                                    <input type="text" class="form-control" id="edit_route_name"
                                        name="route_name">
                                    <small class="form-text text-muted">Laravel route name (e.g., users.index)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_route_type" class="form-label">Route Type</label>
                                    <select class="form-select" id="edit_route_type" name="route_type">
                                        <option value="public">Public</option>
                                        <option value="admin">Admin</option>
                                        <option value="api">API</option>
                                    </select>
                                    <small class="form-text text-muted">Access level classification</small>
                                </div>
                                <div class="col-md-12">
                                    <label for="edit_controller_class" class="form-label">Controller Class</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="edit_controller_class"
                                            name="controller_class"
                                            placeholder="App\Http\Controllers\">
                                        <button class="btn
                                            btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-magic"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <h6 class="dropdown-header">Quick Templates</h6>
                                            </li>
                                            <li><a class="dropdown-item edit-controller-template" href="#"
                                                    data-template="App\Http\Controllers\Panel\">Panel Controller</a></li>
                                            <li><a class="dropdown-item
                                                    edit-controller-template" href="#"
                                                    data-template="App\Http\Controllers\Admin\">Admin Controller</a></li>
                                            <li><a class="dropdown-item
                                                    edit-controller-template" href="#"
                                                    data-template="App\Http\Controllers\API\">API Controller</a></li>
                                            <li><a class="dropdown-item
                                                    edit-controller-template" href="#"
                                                    data-template="App\Http\Controllers\">Base Controller</a></li>
                                        </ul>
                                    </div>
                                    <small class="form-text
                                                    text-muted">
                                                    <i class="fas fa-lightbulb text-warning"></i> Use dropdown for
                                                    quick templates, or type manually
                                                    </small>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="edit_view_path" class="form-label">View Path</label>
                                        <input type="text" class="form-control" id="edit_view_path"
                                            name="view_path">
                                        <small class="form-text text-muted">
                                            Blade view path (e.g., panel.users.index)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Permissions & Security Tab --}}
                            <div class="tab-pane fade" id="edit-permissions-tab" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="edit_middleware_list" class="form-label">Middleware</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="edit_middleware_list"
                                                name="middleware_list" placeholder="web,auth">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-shield-alt"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <h6 class="dropdown-header">Common Middleware</h6>
                                                </li>
                                                <li><a class="dropdown-item edit-middleware-template" href="#"
                                                        data-template="web,auth">Web + Auth</a></li>
                                                <li><a class="dropdown-item edit-middleware-template" href="#"
                                                        data-template="web,auth,permission:">Permission Based</a></li>
                                                <li><a class="dropdown-item edit-middleware-template" href="#"
                                                        data-template="api,auth:sanctum">API + Sanctum</a></li>
                                                <li><a class="dropdown-item edit-middleware-template" href="#"
                                                        data-template="web,guest">Guest Only</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <h6 class="dropdown-header">Quick Add</h6>
                                                </li>
                                                <li><a class="dropdown-item edit-middleware-add" href="#"
                                                        data-middleware="web">+ web</a></li>
                                                <li><a class="dropdown-item edit-middleware-add" href="#"
                                                        data-middleware="auth">+ auth</a></li>
                                                <li><a class="dropdown-item edit-middleware-add" href="#"
                                                        data-middleware="permission:">+ permission:</a></li>
                                                <li><a class="dropdown-item edit-middleware-add" href="#"
                                                        data-middleware="throttle:60,1">+ throttle</a></li>
                                            </ul>
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle text-info"></i> Auto-formats with commas. Use
                                            dropdown for quick templates.
                                        </small>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Role Access</label>
                                        <select class="form-select" id="edit_roles" name="roles[]" multiple
                                            size="6">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            Hold Ctrl/Cmd to select multiple roles. Users with these roles will see this
                                            menu.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- SEO & Meta Information Tab --}}
                            <div class="tab-pane fade" id="edit-seo-tab" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="edit_meta_title" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" id="edit_meta_title"
                                            name="meta_title">
                                        <small class="form-text text-muted">
                                            SEO title for browser tab and search engines
                                        </small>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="edit_meta_description" class="form-label">Meta Description</label>
                                        <textarea class="form-control" id="edit_meta_description" name="meta_description" rows="4"></textarea>
                                        <small class="form-text text-muted">
                                            SEO description for search engines (recommended: 150-160 characters)
                                        </small>
                                    </div>
                                    {{-- <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">SEO Preview</h4>
                                                <div class="text-primary" id="edit-seo-title-preview">Your page title
                                                    will
                                                    appear here</div>
                                                <div class="text-success small" id="edit-seo-url-preview">
                                                    {{ url('/') }}/your-slug</div>
                                                <div class="text-muted small" id="edit-seo-desc-preview">Your meta
                                                    description will appear here</div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>{{ $data['tombol_batal'] }}
                        </button>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="fas fa-save me-2"></i>Update Menu
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SEO Preview functionality for edit modal
        function updateEditSEOPreview() {
            const title = document.getElementById('edit_meta_title').value ||
                document.getElementById('edit_nama_menu').value ||
                'Your page title will appear here';
            const slug = document.getElementById('edit_slug').value || 'your-slug';
            const description = document.getElementById('edit_meta_description').value ||
                'Your meta description will appear here';

            const titlePreview = document.getElementById('edit-seo-title-preview');
            const urlPreview = document.getElementById('edit-seo-url-preview');
            const descPreview = document.getElementById('edit-seo-desc-preview');

            if (titlePreview) titlePreview.textContent = title;
            if (urlPreview) urlPreview.textContent = `{{ url('/') }}/${slug}`;
            if (descPreview) descPreview.textContent = description;
        }

        // Update SEO preview on input changes for edit modal
        ['edit_nama_menu', 'edit_slug', 'edit_meta_title', 'edit_meta_description'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updateEditSEOPreview);
            }
        });

        // Initialize when edit modal opens
        document.getElementById('editMenuModal').addEventListener('shown.bs.modal', function() {
            updateEditSEOPreview();
        });
    });
</script>

{{-- Additional JavaScript for Edit Modal Controller and Middleware Helpers --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Controller Template Helper for Edit Modal
        function setupEditControllerHelpers() {
            // Controller template dropdown for edit modal
            document.querySelectorAll('.edit-controller-template').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const template = this.getAttribute('data-template');
                    const input = document.getElementById('edit_controller_class');

                    // Auto-complete controller name based on menu name
                    const menuName = document.getElementById('edit_nama_menu').value;
                    if (menuName) {
                        const controllerName = menuName.replace(/\s+/g, '') + 'Controller';
                        input.value = template + controllerName;
                    } else {
                        input.value = template;
                        input.focus();
                    }
                });
            });

            // Auto-format controller class on input for edit modal
            document.getElementById('edit_controller_class').addEventListener('input', function() {
                const value = this.value;
                // Ensure proper namespace format
                if (value && !value.startsWith('App\\') && !value.includes('\\')) {
                    this.value = 'App\\Http\\Controllers\\' + value;
                }
            });

            // Auto-generate view path from controller class for edit modal
            document.getElementById('edit_controller_class').addEventListener('input', function() {
                const viewPathField = document.getElementById('edit_view_path');
                if (!viewPathField.value && this.value) {
                    // Extract controller info and generate view path
                    const controllerValue = this.value;

                    // Extract namespace parts
                    const parts = controllerValue.split('\\');
                    const controllerName = parts[parts.length - 1];

                    if (controllerName && controllerName.endsWith('Controller')) {
                        const resourceName = controllerName.replace('Controller', '').toLowerCase();

                        // Determine view path based on namespace
                        if (controllerValue.includes('Panel\\')) {
                            viewPathField.value = `panel.${resourceName}.index`;
                        } else if (controllerValue.includes('Admin\\')) {
                            viewPathField.value = `admin.${resourceName}.index`;
                        } else if (controllerValue.includes('API\\')) {
                            viewPathField.value = `api.${resourceName}.index`;
                        } else {
                            viewPathField.value = `${resourceName}.index`;
                        }
                    }
                }
            });

            // Auto-generate route name from controller for edit modal
            document.getElementById('edit_controller_class').addEventListener('blur', function() {
                const routeNameField = document.getElementById('edit_route_name');
                if (!routeNameField.value && this.value) {
                    const controllerValue = this.value;
                    const parts = controllerValue.split('\\');
                    const controllerName = parts[parts.length - 1];

                    if (controllerName && controllerName.endsWith('Controller')) {
                        const resourceName = controllerName.replace('Controller', '').toLowerCase();

                        // Generate route name
                        if (controllerValue.includes('Panel\\')) {
                            routeNameField.value = `panel.${resourceName}.index`;
                        } else if (controllerValue.includes('Admin\\')) {
                            routeNameField.value = `admin.${resourceName}.index`;
                        } else {
                            routeNameField.value = `${resourceName}.index`;
                        }
                    }
                }
            });
        }

        // Middleware Template Helper for Edit Modal
        function setupEditMiddlewareHelpers() {
            // Middleware template dropdown for edit modal
            document.querySelectorAll('.edit-middleware-template').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const template = this.getAttribute('data-template');
                    const input = document.getElementById('edit_middleware_list');
                    input.value = template;

                    // Focus for further editing if template ends with ':'
                    if (template.endsWith(':')) {
                        input.focus();
                        input.setSelectionRange(input.value.length, input.value.length);
                    }
                });
            });

            // Middleware add dropdown for edit modal
            document.querySelectorAll('.edit-middleware-add').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const middleware = this.getAttribute('data-middleware');
                    const input = document.getElementById('edit_middleware_list');

                    // Add to existing middleware
                    if (input.value) {
                        if (!input.value.endsWith(',')) {
                            input.value += ',';
                        }
                        input.value += middleware;
                    } else {
                        input.value = middleware;
                    }

                    // Focus for further editing if middleware ends with ':'
                    if (middleware.endsWith(':')) {
                        input.focus();
                        input.setSelectionRange(input.value.length, input.value.length);
                    }
                });
            });

            // Auto-format middleware on input for edit modal
            document.getElementById('edit_middleware_list').addEventListener('input', function() {
                let value = this.value;

                // Remove extra spaces and format commas
                value = value.replace(/\s*,\s*/g, ',');
                value = value.replace(/,+/g, ','); // Remove multiple commas
                value = value.replace(/^,+|,+$/g, ''); // Remove leading/trailing commas

                this.value = value;
            });

            // Format on blur to clean up any remaining issues for edit modal
            document.getElementById('edit_middleware_list').addEventListener('blur', function() {
                let value = this.value.trim();

                // Final cleanup
                value = value.replace(/\s*,\s*/g, ',');
                value = value.replace(/,+/g, ',');
                value = value.replace(/^,+|,+$/g, '');

                this.value = value;
            });
        }

        // Initialize helpers for edit modal
        setupEditControllerHelpers();
        setupEditMiddlewareHelpers();
    });
</script>
