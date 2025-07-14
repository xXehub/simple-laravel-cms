@props(['permissions'])

<!-- Create Role Modal -->
@can('create-roles')
    <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.roles.store') }}" id="createRoleForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="create_name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback" id="create_name_error"></div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="row">
                                @foreach ($permissions->groupBy('group') as $group => $groupPermissions)
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">{{ ucfirst($group) }}</h6>
                                        @foreach ($groupPermissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="create_permission_{{ $permission->id }}" name="permissions[]"
                                                    value="{{ $permission->name }}"
                                                    @if(old('permissions') && in_array($permission->name, old('permissions'))) checked @endif>
                                                <label class="form-check-label"
                                                    for="create_permission_{{ $permission->id }}">
                                                    {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Clear form when create modal is closed
        document.addEventListener('DOMContentLoaded', function() {
            const createModal = document.getElementById('createRoleModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createRoleForm').reset();
                    // Clear validation errors
                    const errorElements = createModal.querySelectorAll('.invalid-feedback');
                    errorElements.forEach(el => {
                        el.textContent = '';
                        el.previousElementSibling.classList.remove('is-invalid');
                    });
                });
            }
        });
    </script>
@endcan
