{{-- Edit Menu Modal --}}
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                                    <i class="fas fa-shield-alt me-2"></i>
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
                                    <input type="text" class="form-control" id="edit_controller_class"
                                        name="controller_class">
                                    <small class="form-text text-muted">
                                        Full controller class name (e.g., App\Http\Controllers\Panel\UserController)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="edit_view_path" class="form-label">View Path</label>
                                    <input type="text" class="form-control" id="edit_view_path" name="view_path">
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
                                    <input type="text" class="form-control" id="edit_middleware_list"
                                        name="middleware_list">
                                    <small class="form-text text-muted">
                                        Comma-separated middleware (e.g., web,auth,permission:view-users)
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
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">SEO Preview</h4>
                                            <div class="text-primary" id="edit-seo-title-preview">Your page title will
                                                appear here</div>
                                            <div class="text-success small" id="edit-seo-url-preview">
                                                {{ url('/') }}/your-slug</div>
                                            <div class="text-muted small" id="edit-seo-desc-preview">Your meta
                                                description will appear here</div>
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
                        <button type="button" class="btn btn-outline-primary" id="edit-prev-tab" disabled>
                            <i class="fas fa-chevron-left me-2"></i>{{ $data['tombol_kembali'] }}
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="edit-next-tab">
                            {{ $data['tombol_lanjut'] }}<i class="fas fa-chevron-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-primary" id="edit-submit-btn" style="display: none;">
                            <i class="fas fa-save me-2"></i>Update Menu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation functionality for edit modal
        const editTabs = ['edit-basic-tab', 'edit-technical-tab', 'edit-permissions-tab', 'edit-seo-tab'];
        let editCurrentTab = 0;

        const editPrevBtn = document.getElementById('edit-prev-tab');
        const editNextBtn = document.getElementById('edit-next-tab');
        const editSubmitBtn = document.getElementById('edit-submit-btn');

        function updateEditTabNavigation() {
            editPrevBtn.disabled = editCurrentTab === 0;

            if (editCurrentTab === editTabs.length - 1) {
                editNextBtn.style.display = 'none';
                editSubmitBtn.style.display = 'inline-flex';
            } else {
                editNextBtn.style.display = 'inline-flex';
                editSubmitBtn.style.display = 'none';
            }
        }

        function showEditTab(index) {
            // Hide all tabs
            editTabs.forEach(tabId => {
                const tab = document.getElementById(tabId);
                const navLink = document.querySelector(`a[href="#${tabId}"]`);
                if (tab && navLink) {
                    tab.classList.remove('show', 'active');
                    navLink.classList.remove('active');
                    navLink.setAttribute('aria-selected', 'false');
                }
            });

            // Show current tab
            const currentTabEl = document.getElementById(editTabs[index]);
            const currentNavLink = document.querySelector(`a[href="#${editTabs[index]}"]`);
            if (currentTabEl && currentNavLink) {
                currentTabEl.classList.add('show', 'active');
                currentNavLink.classList.add('active');
                currentNavLink.setAttribute('aria-selected', 'true');
            }

            updateEditTabNavigation();
        }

        editPrevBtn.addEventListener('click', function() {
            if (editCurrentTab > 0) {
                editCurrentTab--;
                showEditTab(editCurrentTab);
            }
        });

        editNextBtn.addEventListener('click', function() {
            if (editCurrentTab < editTabs.length - 1) {
                editCurrentTab++;
                showEditTab(editCurrentTab);
            }
        });

        // Allow direct tab clicking for edit modal
        document.querySelectorAll('#editMenuModal .nav-tabs .nav-link').forEach((link, index) => {
            link.addEventListener('click', function() {
                editCurrentTab = index;
                updateEditTabNavigation();
            });
        });

        // SEO Preview functionality for edit modal
        function updateEditSEOPreview() {
            const title = document.getElementById('edit_meta_title').value ||
                document.getElementById('edit_nama_menu').value ||
                'Your page title will appear here';
            const slug = document.getElementById('edit_slug').value || 'your-slug';
            const description = document.getElementById('edit_meta_description').value ||
                'Your meta description will appear here';

            document.getElementById('edit-seo-title-preview').textContent = title;
            document.getElementById('edit-seo-url-preview').textContent = `{{ url('/') }}/${slug}`;
            document.getElementById('edit-seo-desc-preview').textContent = description;
        }

        // Update SEO preview on input changes for edit modal
        ['edit_nama_menu', 'edit_slug', 'edit_meta_title', 'edit_meta_description'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updateEditSEOPreview);
            }
        });

        // Reset edit form when modal closes
        document.getElementById('editMenuModal').addEventListener('hidden.bs.modal', function() {
            editCurrentTab = 0;
            showEditTab(0);
            updateEditSEOPreview();
        });

        // Initialize when edit modal opens
        document.getElementById('editMenuModal').addEventListener('shown.bs.modal', function() {
            updateEditTabNavigation();
            updateEditSEOPreview();
        });
    });
</script>
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Menu
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
                                    <i class="ti ti-info-circle me-2"></i>
                                    Umum
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#edit-technical-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                    <i class="ti ti-code me-2"></i>
                                    Routing
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#edit-permissions-tab" class="nav-link" data-bs-toggle="tab"
                                    aria-selected="false" role="tab">
                                    <i class="ti ti-shield-lock me-2"></i>
                                    Hak Akses
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#edit-seo-tab" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                    role="tab">
                                    <i class="ti ti-search me-2"></i>
                                    SEO & Meta
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
                                    <small class="form-text text-muted">Tampilan nama untuk menu</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_slug" class="form-label">
                                        Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_slug" name="slug"
                                        required>
                                    <small class="form-text text-muted">URL path (e.g., panel/users)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_icon" class="form-label">Icon</label>
                                    <input type="text" class="form-control" id="edit_icon" name="icon">
                                    <small class="form-text text-muted">Fontawesome icon (e.g., fas fa-users)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_urutan" class="form-label">
                                        Sort Order <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="edit_urutan" name="urutan"
                                        required min="1">
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
                                    <small class="form-text text-muted">Pilih parent untuk submenu</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" class="form-check-input" id="edit_is_active"
                                            name="is_active" value="1">
                                        <label class="form-check-label" for="edit_is_active">
                                            Menu Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Aktif/nonaktifkan menu</small>
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
                                    <input type="text" class="form-control" id="edit_controller_class"
                                        name="controller_class">
                                    <small class="form-text text-muted">
                                        Full controller class name (e.g., App\Http\Controllers\Panel\UserController)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="edit_view_path" class="form-label">View Path</label>
                                    <input type="text" class="form-control" id="edit_view_path" name="view_path">
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
                                    <input type="text" class="form-control" id="edit_middleware_list"
                                        name="middleware_list">
                                    <small class="form-text text-muted">
                                        Comma-separated middleware (e.g., web,auth,permission:view-users)
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Role Access</label>
                                    <select class="form-select" id="edit_roles" name="roles[]" multiple
                                        size="6">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                <span
                                                    class="badge bg-primary-lt me-2">{{ ucfirst($role->name) }}</span>
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
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">SEO Preview</h4>
                                            <div class="text-primary" id="edit-seo-title-preview">Your page title will
                                                appear here</div>
                                            <div class="text-success small" id="edit-seo-url-preview">
                                                {{ url('/') }}/your-slug</div>
                                            <div class="text-muted small" id="edit-seo-desc-preview">Your meta
                                                description will appear here</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batalkan
                    </button>
                    <div class="btn-group ms-auto">
                        <button type="button" class="btn btn-outline-primary" id="edit-prev-tab" disabled>
                            Sebelumnya
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="edit-next-tab">
                            Selanjutnya
                        </button>
                        <button type="submit" class="btn btn-primary" id="edit-submit-btn" style="display: none;">
                            <i class="ti ti-device-floppy me-2"></i>Update Menu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation functionality for edit modal
        const editTabs = ['edit-basic-tab', 'edit-technical-tab', 'edit-permissions-tab', 'edit-seo-tab'];
        let editCurrentTab = 0;

        const editPrevBtn = document.getElementById('edit-prev-tab');
        const editNextBtn = document.getElementById('edit-next-tab');
        const editSubmitBtn = document.getElementById('edit-submit-btn');

        function updateEditTabNavigation() {
            editPrevBtn.disabled = editCurrentTab === 0;

            if (editCurrentTab === editTabs.length - 1) {
                editNextBtn.style.display = 'none';
                editSubmitBtn.style.display = 'inline-flex';
            } else {
                editNextBtn.style.display = 'inline-flex';
                editSubmitBtn.style.display = 'none';
            }
        }

        function showEditTab(index) {
            // Hide all tabs
            editTabs.forEach(tabId => {
                const tab = document.getElementById(tabId);
                const navLink = document.querySelector(`a[href="#${tabId}"]`);
                if (tab && navLink) {
                    tab.classList.remove('show', 'active');
                    navLink.classList.remove('active');
                    navLink.setAttribute('aria-selected', 'false');
                }
            });

            // Show current tab
            const currentTabEl = document.getElementById(editTabs[index]);
            const currentNavLink = document.querySelector(`a[href="#${editTabs[index]}"]`);
            if (currentTabEl && currentNavLink) {
                currentTabEl.classList.add('show', 'active');
                currentNavLink.classList.add('active');
                currentNavLink.setAttribute('aria-selected', 'true');
            }

            updateEditTabNavigation();
        }

        editPrevBtn.addEventListener('click', function() {
            if (editCurrentTab > 0) {
                editCurrentTab--;
                showEditTab(editCurrentTab);
            }
        });

        editNextBtn.addEventListener('click', function() {
            if (editCurrentTab < editTabs.length - 1) {
                editCurrentTab++;
                showEditTab(editCurrentTab);
            }
        });

        // Allow direct tab clicking for edit modal
        document.querySelectorAll('#editMenuModal .nav-tabs .nav-link').forEach((link, index) => {
            link.addEventListener('click', function() {
                editCurrentTab = index;
                updateEditTabNavigation();
            });
        });

        // SEO Preview functionality for edit modal
        function updateEditSEOPreview() {
            const title = document.getElementById('edit_meta_title').value ||
                document.getElementById('edit_nama_menu').value ||
                'Your page title will appear here';
            const slug = document.getElementById('edit_slug').value || 'your-slug';
            const description = document.getElementById('edit_meta_description').value ||
                'Your meta description will appear here';

            document.getElementById('edit-seo-title-preview').textContent = title;
            document.getElementById('edit-seo-url-preview').textContent = `{{ url('/') }}/${slug}`;
            document.getElementById('edit-seo-desc-preview').textContent = description;
        }

        // Update SEO preview on input changes for edit modal
        ['edit_nama_menu', 'edit_slug', 'edit_meta_title', 'edit_meta_description'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updateEditSEOPreview);
            }
        });

        // Reset edit form when modal closes
        document.getElementById('editMenuModal').addEventListener('hidden.bs.modal', function() {
            editCurrentTab = 0;
            showEditTab(0);
            updateEditSEOPreview();
        });

        // Initialize when edit modal opens
        document.getElementById('editMenuModal').addEventListener('shown.bs.modal', function() {
            updateEditTabNavigation();
            updateEditSEOPreview();
        });
    });
</script>
