@props(['permissions', 'role'])

<!-- Edit Role Modal -->
@can('update-roles')
    <div class="modal modal-blur fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.roles.update', $role->id ?? ':id') }}" id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_role_id" value="{{ $role->id ?? '' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback" id="edit_name_error"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="row" id="edit_permissions_container">
                                @foreach ($permissions->groupBy('group') as $group => $groupPermissions)
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">{{ ucfirst($group) }}</h6>
                                        @foreach ($groupPermissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="edit_permission_{{ $permission->id }}" name="permissions[]"
                                                    value="{{ $permission->name }}">
                                                <label class="form-check-label" for="edit_permission_{{ $permission->id }}">
                                                    {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
