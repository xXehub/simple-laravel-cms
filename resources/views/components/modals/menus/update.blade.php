{{-- Edit Menu Modal --}}
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('panel.menus.update') }}" id="editMenuForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_menu_id">

                <div class="modal-body">
                    {{-- Basic Information --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_nama_menu" class="form-label">Menu Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_menu" name="nama_menu" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_slug" name="slug" required>
                            <small class="form-text text-muted">Example: panel/users</small>
                        </div>
                    </div>

                    {{-- Technical Information --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_route_name" class="form-label">Route Name</label>
                            <input type="text" class="form-control" id="edit_route_name" name="route_name">
                            <small class="form-text text-muted">Optional. Example: users.index</small>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_icon" class="form-label">Icon</label>
                            <input type="text" class="form-control" id="edit_icon" name="icon">
                            <small class="form-text text-muted">Font Awesome class. Example: fas fa-users</small>
                        </div>
                    </div>

                    {{-- Menu Structure --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_parent_id" class="form-label">Parent Menu</label>
                            <select class="form-select" id="edit_parent_id" name="parent_id">
                                <option value="">-- Root Menu --</option>
                                @foreach($parentMenus as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_urutan" class="form-label">Order <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_urutan" name="urutan" required>
                        </div>
                    </div>

                    {{-- Status & Roles --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_roles" class="form-label">Assign to Roles</label>
                            <select name="roles[]" id="edit_roles" class="form-select" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Select multiple roles</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
