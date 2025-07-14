<x-layout.app title="Roles Management - Panel Admin" :use-sidebar="true">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-user-tag me-2"></i>Roles Management
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            @can('create-roles')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="fas fa-plus me-2"></i>Add Role
                </button>
            @endcan
        </div>
    </div>

    <x-alert.flash-messages />

    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Role</th>
                    <th>Guard</th>
                    <th>Permissions</th>
                    <th>Dibuat</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>
                            <strong>{{ $role->name }}</strong>
                        </td>
                        <td>{{ $role->guard_name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                        </td>
                        <td>{{ $role->created_at->format('d M Y') }}</td>
                        <td>
                            @can('update-roles')
                                <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                        onclick="editRole({{ $role->id }})" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editRoleModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endcan
                            @can('delete-roles')
                                @if($role->name !== 'admin')
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteRoleModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada roles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $roles->links() }}

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
                                <input type="text" class="form-control" id="create_name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Permissions</label>
                                <div class="row">
                                    @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted">{{ ucfirst($group) }}</h6>
                                            @foreach($groupPermissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="create_permission_{{ $permission->id }}" 
                                                           name="permissions[]" value="{{ $permission->name }}">
                                                    <label class="form-check-label" for="create_permission_{{ $permission->id }}">
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
                                <i class="fas fa-save"></i> Create Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <!-- Edit Role Modal -->
    @can('update-roles')
        <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('panel.roles.update') }}" id="editRoleForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_role_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Permissions</label>
                                <div class="row" id="edit_permissions_container">
                                    @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted">{{ ucfirst($group) }}</h6>
                                            @foreach($groupPermissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="edit_permission_{{ $permission->id }}" 
                                                           name="permissions[]" value="{{ $permission->name }}">
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

    <!-- Delete Role Modal -->
    @can('delete-roles')
        <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRoleModalLabel">Delete Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('panel.roles.delete') }}" id="deleteRoleForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="delete_role_id">
                        <div class="modal-body">
                            <p>Are you sure you want to delete role <strong id="delete_role_name"></strong>?</p>
                            <p class="text-danger">This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <script>
        // Edit Role function
        function editRole(roleId) {
            // Fetch role data via AJAX
            fetch(`{{ route('panel.roles.edit') }}?id=${roleId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const role = data.role;
                
                // Set values in modal
                document.getElementById('edit_role_id').value = role.id;
                document.getElementById('edit_name').value = role.name;
                
                // Set permissions
                const editPermissionCheckboxes = document.querySelectorAll('#edit_permissions_container input[name="permissions[]"]');
                
                // Uncheck all first
                editPermissionCheckboxes.forEach(checkbox => checkbox.checked = false);
                
                // Check the appropriate permissions
                role.permissions.forEach(permission => {
                    const editCheckbox = document.querySelector(`#edit_permissions_container input[value="${permission.name}"]`);
                    if (editCheckbox) {
                        editCheckbox.checked = true;
                    }
                });
            })
            .catch(error => {
                console.error('Error loading role data:', error);
                alert('Error loading role data');
            });
        }

        // Delete Role function
        function deleteRole(roleId, roleName) {
            document.getElementById('delete_role_id').value = roleId;
            document.getElementById('delete_role_name').textContent = roleName;
        }

        // Clear form when create modal is closed
        @can('create-roles')
        document.getElementById('createRoleModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('createRoleForm').reset();
        });
        @endcan

        // Clear form when edit modal is closed
        @can('update-roles')
        document.getElementById('editRoleModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('editRoleForm').reset();
        });
        @endcan
    </script>
</x-layout.app>