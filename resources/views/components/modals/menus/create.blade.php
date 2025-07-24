{{-- Create Menu Modal --}}
<div class="modal fade" id="createMenuModal" tabindex="-1" aria-labelledby="createMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMenuModalLabel">
                    <i class="fas fa-plus me-2"></i>Create New Menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('panel.menus.store') }}" id="createMenuForm">
                @csrf
                <div class="modal-body">
                    {{-- Display Validation Errors --}}
                    @if ($errors->any() && !old('_method'))
                        <div class="alert alert-danger">
                            <h6>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Basic Information --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="create_nama_menu" class="form-label">Menu Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_nama_menu" name="nama_menu"
                                value="{{ old('nama_menu') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="create_slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_slug" name="slug"
                                value="{{ old('slug') }}" required>
                            <small class="form-text text-muted">Example: panel/users</small>
                        </div>
                    </div>

                    {{-- Technical Information --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="create_route_name" class="form-label">Route Name</label>
                            <input type="text" class="form-control" id="create_route_name" name="route_name"
                                value="{{ old('route_name') }}">
                            <small class="form-text text-muted">Optional. Example: users.index</small>
                        </div>
                        <div class="col-md-6">
                            <label for="create_route_type" class="form-label">Route Type</label>
                            <select class="form-select" id="create_route_type" name="route_type">
                                <option value="public" {{ old('route_type', 'public') == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="admin" {{ old('route_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="api" {{ old('route_type') == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                            <small class="form-text text-muted">Access level for this menu</small>
                        </div>
                    </div>

                    {{-- Controller & View Configuration --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="create_controller_class" class="form-label">Controller Class</label>
                            <input type="text" class="form-control" id="create_controller_class" name="controller_class"
                                value="{{ old('controller_class') }}">
                            <small class="form-text text-muted">Example: Panel\UserController</small>
                        </div>
                        <div class="col-md-6">
                            <label for="create_view_path" class="form-label">View Path</label>
                            <input type="text" class="form-control" id="create_view_path" name="view_path"
                                value="{{ old('view_path') }}">
                            <small class="form-text text-muted">Example: panel.users.index</small>
                        </div>
                    </div>

                    {{-- Icon & Middleware --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="create_icon" class="form-label">Icon</label>
                            <input type="text" class="form-control" id="create_icon" name="icon"
                                value="{{ old('icon') }}">
                            <small class="form-text text-muted">Font Awesome class. Example: fas fa-users</small>
                        </div>
                        <div class="col-md-6">
                            <label for="create_middleware_list" class="form-label">Middleware</label>
                            <input type="text" class="form-control" id="create_middleware_list" name="middleware_list"
                                value="{{ old('middleware_list') }}">
                            <small class="form-text text-muted">Comma separated. Example: auth,verified</small>
                        </div>
                    </div>

                    {{-- SEO Fields --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="create_meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="create_meta_title" name="meta_title"
                                value="{{ old('meta_title') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="create_meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="create_meta_description" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>

                    {{-- Menu Structure --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Parent Menu</label>
                                <select class="form-select" id="create_parent_id" name="parent_id">
                                    <option value="">-- Root Menu --</option>
                                    @foreach ($parentMenus as $id => $name)
                                        <option value="{{ $id }}"
                                            data-custom-properties="{{ str_contains($name, 'Parent -') ? '&lt;span class=&#34;badge bg-success-lt&#34;&gt;Parent&lt;/span&gt;' : '' }}"
                                            {{ old('parent_id') == $id ? 'selected' : '' }}>
                                            {{ str_replace(['└─ ', 'Parent - '], '', $name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- urutan --}}
                        <div class="col-md-6">
                            <label for="create_urutan" class="form-label">Urutan <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="create_urutan" name="urutan"
                                value="{{ old('urutan', 1) }}" required>
                        </div>
                    </div>

                    {{-- Order & Status --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Assign to Roles</label>
                                <select class="form-select" id="create_roles" name="roles[]" multiple>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            data-custom-properties="&lt;span class=&#34;badge bg-primary-lt&#34;&gt;Role&lt;/span&gt;"
                                            {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Bisa memilih lebih dari satu role</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <!-- Hidden input untuk memastikan nilai 0 dikirim jika checkbox tidak dicentang -->
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="form-check-input" id="create_is_active" name="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="create_is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
