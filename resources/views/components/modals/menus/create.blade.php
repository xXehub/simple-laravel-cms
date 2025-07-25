{{-- Create Menu Modal --}}
<div class="modal fade" id="createMenuModal" tabindex="-1" aria-labelledby="createMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMenuModalLabel">
                    <i class="fas fa-plus me-2"></i>{{ $data['create_title'] ?? 'Create New Menu' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('panel.menus.store') }}" id="createMenuForm">
                @csrf
                <div class="modal-body">
                    {{-- Display Validation Errors --}}
                    @if ($errors->any() && !old('_method'))
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
                                <a href="#create-basic-tab" class="nav-link active" data-bs-toggle="tab"
                                    aria-selected="true" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ $data['tab_basic'] ?? 'Basic Info' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#create-technical-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                    <i class="fas fa-code me-2"></i>
                                    {{ $data['tab_technical'] ?? 'Technical' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#create-permissions-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    {{ $data['tab_permissions'] ?? 'Permissions' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#create-seo-tab" class="nav-link" data-bs-toggle="tab" aria-selected="false"
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
                        <div class="tab-pane fade show active" id="create-basic-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="create_nama_menu" class="form-label">
                                        Menu Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="create_nama_menu" name="nama_menu"
                                        value="{{ old('nama_menu') }}" required>
                                    <small class="form-text text-muted">Display name for the menu item</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_slug" class="form-label">
                                        Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="create_slug" name="slug"
                                        value="{{ old('slug') }}" required>
                                    <small class="form-text text-muted">URL path (e.g., panel/users)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_icon" class="form-label">Icon</label>
                                    <input type="text" class="form-control" id="create_icon" name="icon"
                                        value="{{ old('icon') }}">
                                    <small class="form-text text-muted">FontAwesome class (e.g., fas fa-users)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_urutan" class="form-label">
                                        Sort Order <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="create_urutan" name="urutan"
                                        value="{{ old('urutan', 1) }}" required min="1">
                                    <small class="form-text text-muted">Display order in menu</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parent Menu</label>
                                    <select class="form-select" id="create_parent_id" name="parent_id">
                                        <option value="">-- Root Menu --</option>
                                        @foreach ($parentMenus as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('parent_id') == $id ? 'selected' : '' }}>
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
                                        <input type="checkbox" class="form-check-input" id="create_is_active"
                                            name="is_active" value="1"
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="create_is_active">
                                            Active Menu
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enable/disable this menu</small>
                                </div>
                            </div>
                        </div>

                        {{-- Technical Configuration Tab --}}
                        <div class="tab-pane fade" id="create-technical-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="create_route_name" class="form-label">Route Name</label>
                                    <input type="text" class="form-control" id="create_route_name"
                                        name="route_name" value="{{ old('route_name') }}">
                                    <small class="form-text text-muted">Laravel route name (e.g., users.index)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_route_type" class="form-label">Route Type</label>
                                    <select class="form-select" id="create_route_type" name="route_type">
                                        <option value="public"
                                            {{ old('route_type', 'public') == 'public' ? 'selected' : '' }}>
                                            Public
                                        </option>
                                        <option value="admin" {{ old('route_type') == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                        <option value="api" {{ old('route_type') == 'api' ? 'selected' : '' }}>
                                            API
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">Access level classification</small>
                                </div>
                                <div class="col-md-12">
                                    <label for="create_controller_class" class="form-label">Controller Class</label>
                                    <input type="text" class="form-control" id="create_controller_class"
                                        name="controller_class" value="{{ old('controller_class') }}">
                                    <small class="form-text text-muted">
                                        Full controller class name (e.g., App\Http\Controllers\Panel\UserController)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="create_view_path" class="form-label">View Path</label>
                                    <input type="text" class="form-control" id="create_view_path"
                                        name="view_path" value="{{ old('view_path') }}">
                                    <small class="form-text text-muted">
                                        Blade view path (e.g., panel.users.index)
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Permissions & Security Tab --}}
                        <div class="tab-pane fade" id="create-permissions-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="create_middleware_list" class="form-label">Middleware</label>
                                    <input type="text" class="form-control" id="create_middleware_list"
                                        name="middleware_list" value="{{ old('middleware_list') }}">
                                    <small class="form-text text-muted">
                                        Comma-separated middleware (e.g., web,auth,permission:view-users)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Role Access</label>
                                    <select class="form-select" id="create_roles" name="roles[]" multiple
                                        size="6">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
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
                        <div class="tab-pane fade" id="create-seo-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="create_meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="create_meta_title"
                                        name="meta_title" value="{{ old('meta_title') }}">
                                    <small class="form-text text-muted">
                                        SEO title for browser tab and search engines
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="create_meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="create_meta_description" name="meta_description" rows="4">{{ old('meta_description') }}</textarea>
                                    <small class="form-text text-muted">
                                        SEO description for search engines (recommended: 150-160 characters)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">SEO Preview</h4>
                                            <div class="text-primary" id="seo-title-preview">Your page title will
                                                appear here</div>
                                            <div class="text-success small" id="seo-url-preview">
                                                {{ url('/') }}/your-slug</div>
                                            <div class="text-muted small" id="seo-desc-preview">Your meta description
                                                will appear here</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>{{ $data['tombol_batal'] }}
                    </button>
                    <div class="btn-group ms-auto">
                        <button type="button" class="btn btn-outline-primary" id="create-prev-tab" disabled>
                            <i class="fas fa-chevron-left me-2"></i>{{ $data['tombol_kembali'] }}
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="create-next-tab">
                            {{ $data['tombol_lanjut'] }}<i class="fas fa-chevron-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-primary" id="create-submit-btn"
                            style="display: none;">
                            <i class="fas fa-save me-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation functionality
        const tabs = ['create-basic-tab', 'create-technical-tab', 'create-permissions-tab', 'create-seo-tab'];
        let currentTab = 0;

        const prevBtn = document.getElementById('create-prev-tab');
        const nextBtn = document.getElementById('create-next-tab');
        const submitBtn = document.getElementById('create-submit-btn');

        function updateTabNavigation() {
            prevBtn.disabled = currentTab === 0;

            if (currentTab === tabs.length - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-flex';
            } else {
                nextBtn.style.display = 'inline-flex';
                submitBtn.style.display = 'none';
            }
        }

        function showTab(index) {
            // Hide all tabs
            tabs.forEach(tabId => {
                const tab = document.getElementById(tabId);
                const navLink = document.querySelector(`a[href="#${tabId}"]`);
                if (tab && navLink) {
                    tab.classList.remove('show', 'active');
                    navLink.classList.remove('active');
                    navLink.setAttribute('aria-selected', 'false');
                }
            });

            // Show current tab
            const currentTabEl = document.getElementById(tabs[index]);
            const currentNavLink = document.querySelector(`a[href="#${tabs[index]}"]`);
            if (currentTabEl && currentNavLink) {
                currentTabEl.classList.add('show', 'active');
                currentNavLink.classList.add('active');
                currentNavLink.setAttribute('aria-selected', 'true');
            }

            updateTabNavigation();
        }

        prevBtn.addEventListener('click', function() {
            if (currentTab > 0) {
                currentTab--;
                showTab(currentTab);
            }
        });

        nextBtn.addEventListener('click', function() {
            if (currentTab < tabs.length - 1) {
                currentTab++;
                showTab(currentTab);
            }
        });

        // Allow direct tab clicking
        document.querySelectorAll('.nav-tabs .nav-link').forEach((link, index) => {
            link.addEventListener('click', function() {
                currentTab = index;
                updateTabNavigation();
            });
        });

        // SEO Preview functionality
        function updateSEOPreview() {
            const title = document.getElementById('create_meta_title').value ||
                document.getElementById('create_nama_menu').value ||
                'Your page title will appear here';
            const slug = document.getElementById('create_slug').value || 'your-slug';
            const description = document.getElementById('create_meta_description').value ||
                'Your meta description will appear here';

            document.getElementById('seo-title-preview').textContent = title;
            document.getElementById('seo-url-preview').textContent = `{{ url('/') }}/${slug}`;
            document.getElementById('seo-desc-preview').textContent = description;
        }

        // Update SEO preview on input changes
        ['create_nama_menu', 'create_slug', 'create_meta_title', 'create_meta_description'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updateSEOPreview);
            }
        });

        // Auto-generate slug from menu name
        document.getElementById('create_nama_menu').addEventListener('input', function() {
            const slugField = document.getElementById('create_slug');
            if (!slugField.value) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugField.value = slug;
                updateSEOPreview();
            }
        });

        // Reset form when modal closes
        document.getElementById('createMenuModal').addEventListener('hidden.bs.modal', function() {
            currentTab = 0;
            showTab(0);
            document.getElementById('createMenuForm').reset();
            updateSEOPreview();
        });

        // Initialize
        updateTabNavigation();
        updateSEOPreview();
    });
</script>
<div class="modal fade" id="createMenuModal" tabindex="-1" aria-labelledby="createMenuModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMenuModalLabel">
                    <i class="fas fa-plus me-2"></i>{{ $data['create_title'] ?? 'Create New Menu' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('panel.menus.store') }}" id="createMenuForm">
                @csrf
                <div class="modal-body">
                    {{-- Display Validation Errors --}}
                    @if ($errors->any() && !old('_method'))
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
                                <a href="#create-basic-tab" class="nav-link active" data-bs-toggle="tab"
                                    aria-selected="true" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ $data['tab_basic'] ?? 'Basic Info' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#create-technical-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                    <i class="fas fa-code me-2"></i>
                                    {{ $data['tab_technical'] ?? 'Technical' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#create-permissions-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    {{ $data['tab_permissions'] ?? 'Permissions' }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#create-seo-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                    <i class="fas fa-search me-2"></i>
                                    {{ $data['tab_seo'] ?? 'SEO & Meta' }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Tab Content --}}
                    <div class="tab-content">
                        {{-- Basic Information Tab --}}
                        <div class="tab-pane fade show active" id="create-basic-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="create_nama_menu" class="form-label">
                                        Menu Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="create_nama_menu"
                                        name="nama_menu" value="{{ old('nama_menu') }}" required>
                                    <small class="form-text text-muted">Display name for the menu item</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_slug" class="form-label">
                                        Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="create_slug" name="slug"
                                        value="{{ old('slug') }}" required>
                                    <small class="form-text text-muted">URL path (e.g., panel/users)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_icon" class="form-label">Icon</label>
                                    <input type="text" class="form-control" id="create_icon" name="icon"
                                        value="{{ old('icon') }}">
                                    <small class="form-text text-muted">FontAwesome class (e.g., fas fa-users)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_urutan" class="form-label">
                                        Sort Order <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="create_urutan" name="urutan"
                                        value="{{ old('urutan', 1) }}" required min="1">
                                    <small class="form-text text-muted">Display order in menu</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parent Menu</label>
                                    <select class="form-select" id="create_parent_id" name="parent_id">
                                        <option value="">-- Root Menu --</option>
                                        @foreach ($parentMenus as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('parent_id') == $id ? 'selected' : '' }}>
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
                                        <input type="checkbox" class="form-check-input" id="create_is_active"
                                            name="is_active" value="1"
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="create_is_active">
                                            Active Menu
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enable/disable this menu</small>
                                </div>
                            </div>
                        </div>

                        {{-- Technical Configuration Tab --}}
                        <div class="tab-pane fade" id="create-technical-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="create_route_name" class="form-label">Route Name</label>
                                    <input type="text" class="form-control" id="create_route_name"
                                        name="route_name" value="{{ old('route_name') }}">
                                    <small class="form-text text-muted">Laravel route name (e.g., users.index)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_route_type" class="form-label">Route Type</label>
                                    <select class="form-select" id="create_route_type" name="route_type">
                                        <option value="public"
                                            {{ old('route_type', 'public') == 'public' ? 'selected' : '' }}>
                                            Public
                                        </option>
                                        <option value="admin" {{ old('route_type') == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                        <option value="api" {{ old('route_type') == 'api' ? 'selected' : '' }}>
                                            API
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">Access level classification</small>
                                </div>
                                <div class="col-md-12">
                                    <label for="create_controller_class" class="form-label">Controller Class</label>
                                    <input type="text" class="form-control" id="create_controller_class"
                                        name="controller_class" value="{{ old('controller_class') }}">
                                    <small class="form-text text-muted">
                                        Full controller class name (e.g., App\Http\Controllers\Panel\UserController)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="create_view_path" class="form-label">View Path</label>
                                    <input type="text" class="form-control" id="create_view_path"
                                        name="view_path" value="{{ old('view_path') }}">
                                    <small class="form-text text-muted">
                                        Blade view path (e.g., panel.users.index)
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Permissions & Security Tab --}}
                        <div class="tab-pane fade" id="create-permissions-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="create_middleware_list" class="form-label">Middleware</label>
                                    <input type="text" class="form-control" id="create_middleware_list"
                                        name="middleware_list" value="{{ old('middleware_list') }}">
                                    <small class="form-text text-muted">
                                        Comma-separated middleware (e.g., web,auth,permission:view-users)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Role Access</label>
                                    <select class="form-select" id="create_roles" name="roles[]" multiple
                                        size="6">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
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
                        <div class="tab-pane fade" id="create-seo-tab" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="create_meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="create_meta_title"
                                        name="meta_title" value="{{ old('meta_title') }}">
                                    <small class="form-text text-muted">
                                        SEO title for browser tab and search engines
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="create_meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="create_meta_description" name="meta_description" rows="4">{{ old('meta_description') }}</textarea>
                                    <small class="form-text text-muted">
                                        SEO description for search engines (recommended: 150-160 characters)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                SEO Preview</h4>
                                            <div class="text-primary" id="seo-title-preview">
                                                Your page title will appear here
                                            </div>
                                            <div class="text-success small" id="seo-url-preview">
                                                {{ url('/') }}/your-slug</div>
                                            <div class="text-muted small" id="seo-desc-preview">
                                                Your meta description will appear here
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-2"></i>Cancel
                    </button>
                    <div class="btn-group ms-auto">
                        <button type="button" class="btn btn-outline-primary" id="create-prev-tab" disabled>
                            <i class="ti ti-chevron-left me-2"></i>Previous
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="create-next-tab">
                            Next<i class="ti ti-chevron-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-primary" id="create-submit-btn"
                            style="display: none;">
                            <i class="ti ti-device-floppy me-2"></i>Create Menu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation functionality
        const tabs = ['create-basic-tab', 'create-technical-tab', 'create-permissions-tab', 'create-seo-tab'];
        let currentTab = 0;

        const prevBtn = document.getElementById('create-prev-tab');
        const nextBtn = document.getElementById('create-next-tab');
        const submitBtn = document.getElementById('create-submit-btn');

        function updateTabNavigation() {
            prevBtn.disabled = currentTab === 0;

            if (currentTab === tabs.length - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-flex';
            } else {
                nextBtn.style.display = 'inline-flex';
                submitBtn.style.display = 'none';
            }
        }

        function showTab(index) {
            // Hide all tabs
            tabs.forEach(tabId => {
                const tab = document.getElementById(tabId);
                const navLink = document.querySelector(`a[href="#${tabId}"]`);
                if (tab && navLink) {
                    tab.classList.remove('show', 'active');
                    navLink.classList.remove('active');
                    navLink.setAttribute('aria-selected', 'false');
                }
            });

            // Show current tab
            const currentTabEl = document.getElementById(tabs[index]);
            const currentNavLink = document.querySelector(`a[href="#${tabs[index]}"]`);
            if (currentTabEl && currentNavLink) {
                currentTabEl.classList.add('show', 'active');
                currentNavLink.classList.add('active');
                currentNavLink.setAttribute('aria-selected', 'true');
            }

            updateTabNavigation();
        }

        prevBtn.addEventListener('click', function() {
            if (currentTab > 0) {
                currentTab--;
                showTab(currentTab);
            }
        });

        nextBtn.addEventListener('click', function() {
            if (currentTab < tabs.length - 1) {
                currentTab++;
                showTab(currentTab);
            }
        });

        // Allow direct tab clicking
        document.querySelectorAll('.nav-tabs .nav-link').forEach((link, index) => {
            link.addEventListener('click', function() {
                currentTab = index;
                updateTabNavigation();
            });
        });

        // SEO Preview functionality
        function updateSEOPreview() {
            const title = document.getElementById('create_meta_title').value ||
                document.getElementById('create_nama_menu').value ||
                'Your page title will appear here';
            const slug = document.getElementById('create_slug').value || 'your-slug';
            const description = document.getElementById('create_meta_description').value ||
                'Your meta description will appear here';

            document.getElementById('seo-title-preview').textContent = title;
            document.getElementById('seo-url-preview').textContent = `{{ url('/') }}/${slug}`;
            document.getElementById('seo-desc-preview').textContent = description;
        }

        // Update SEO preview on input changes
        ['create_nama_menu', 'create_slug', 'create_meta_title', 'create_meta_description'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updateSEOPreview);
            }
        });

        // Auto-generate slug from menu name
        document.getElementById('create_nama_menu').addEventListener('input', function() {
            const slugField = document.getElementById('create_slug');
            if (!slugField.value) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugField.value = slug;
                updateSEOPreview();
            }
        });

        // Reset form when modal closes
        document.getElementById('createMenuModal').addEventListener('hidden.bs.modal', function() {
            currentTab = 0;
            showTab(0);
            document.getElementById('createMenuForm').reset();
            updateSEOPreview();
        });

        // Initialize
        updateTabNavigation();
        updateSEOPreview();
    });
</script>
