<x-layout.app title="Permissions Management - Panel Admin" :pakai-sidebar="true">
    <div class="page-wrapper">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-key me-2"></i>Permissions Management
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                @can('create-permissions')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#createPermissionModal">
                        <i class="fas fa-plus me-2"></i>Add Permission
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
                        <th>Nama Permission</th>
                        <th>Group</th>
                        <th>Guard</th>
                        <th>Dibuat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>
                                <strong>{{ $permission->name }}</strong>
                            </td>
                            <td>
                                @if ($permission->group)
                                    <span class="badge bg-secondary">{{ $permission->group }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $permission->guard_name }}</td>
                            <td>{{ $permission->created_at->format('d M Y') }}</td>
                            <td>
                                @can('update-permissions')
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                        onclick="editPermission({{ $permission->id }})" data-bs-toggle="modal"
                                        data-bs-target="#editPermissionModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endcan
                                @can('delete-permissions')
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deletePermission({{ $permission->id }}, '{{ $permission->name }}')"
                                        data-bs-toggle="modal" data-bs-target="#deletePermissionModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada permissions</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $permissions->links() }}

        <!-- Create Permission Modal -->
        @can('create-permissions')
            <div class="modal fade" id="createPermissionModal" tabindex="-1" aria-labelledby="createPermissionModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createPermissionModalLabel">Create New Permission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('panel.permissions.store') }}" id="createPermissionForm">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="create_name" class="form-label">Permission Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="create_name" name="name" required
                                        placeholder="e.g., view-users, create-posts">
                                </div>

                                <div class="mb-3">
                                    <label for="create_group" class="form-label">Group</label>
                                    <input type="text" class="form-control" id="create_group" name="group"
                                        placeholder="e.g., users, posts, settings">
                                    <small class="form-text text-muted">Optional: Group permissions for better
                                        organization</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Permission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        <!-- Edit Permission Modal -->
        @can('update-permissions')
            <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPermissionModalLabel">Edit Permission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('panel.permissions.update', ':id') }}" id="editPermissionForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="edit_permission_id">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Permission Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_group" class="form-label">Group</label>
                                    <input type="text" class="form-control" id="edit_group" name="group">
                                    <small class="form-text text-muted">Optional: Group permissions for better
                                        organization</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Permission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        <!-- Delete Permission Modal -->
        @can('delete-permissions')
            <div class="modal fade" id="deletePermissionModal" tabindex="-1"
                aria-labelledby="deletePermissionModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deletePermissionModalLabel">Delete Permission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('panel.permissions.destroy', ':id') }}" id="deletePermissionForm">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" id="delete_permission_id">
                            <div class="modal-body">
                                <p>Are you sure you want to delete permission <strong
                                        id="delete_permission_name"></strong>?</p>
                                <p class="text-danger">This action cannot be undone and will remove this permission from
                                    all roles.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Permission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    </div>
    <script>
        // Edit Permission function
        function editPermission(permissionId) {
            // Update form action URL
            const editForm = document.getElementById('editPermissionForm');
            editForm.action = editForm.action.replace(':id', permissionId);
            
            // Fetch permission data via AJAX
            fetch(`{{ route('panel.permissions.edit', ':id') }}`.replace(':id', permissionId), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const permission = data.permission;

                    // Set values in modal
                    document.getElementById('edit_permission_id').value = permission.id;
                    document.getElementById('edit_name').value = permission.name;
                    document.getElementById('edit_group').value = permission.group || '';
                })
                .catch(error => {
                    console.error('Error loading permission data:', error);
                    alert('Error loading permission data');
                });
        }

        // Delete Permission function
        function deletePermission(permissionId, permissionName) {
            // Update form action URL
            const deleteForm = document.getElementById('deletePermissionForm');
            deleteForm.action = deleteForm.action.replace(':id', permissionId);
            
            document.getElementById('delete_permission_id').value = permissionId;
            document.getElementById('delete_permission_name').textContent = permissionName;
        }

        // Clear form when create modal is closed
        @can('create-permissions')
            document.getElementById('createPermissionModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('createPermissionForm').reset();
            });
        @endcan

        // Clear form when edit modal is closed
        @can('update-permissions')
            document.getElementById('editPermissionModal').addEventListener('hidden.bs.modal', function() {
                const editForm = document.getElementById('editPermissionForm');
                editForm.reset();
                // Reset form action URL
                editForm.action = `{{ route('panel.permissions.update', ':id') }}`;
            });
        @endcan

        // Clear form when delete modal is closed  
        @can('delete-permissions')
            document.getElementById('deletePermissionModal').addEventListener('hidden.bs.modal', function() {
                const deleteForm = document.getElementById('deletePermissionForm');
                deleteForm.reset();
                // Reset form action URL
                deleteForm.action = `{{ route('panel.permissions.destroy', ':id') }}`;
            });
        @endcan
    </script>
</x-layout.app>
