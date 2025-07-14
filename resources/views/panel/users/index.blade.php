<x-layout.app title="Users Management - Panel Admin" :use-sidebar="true">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-users me-2"></i>Users Management
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            @can('create-users')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-plus me-2"></i>Add User
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            @can('update-users')
                                <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                    onclick="editUser({{ $user->id }})" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endcan
                            @can('delete-users')
                                @if ($user->id !== auth()->id())
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                        data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada users</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}

    <!-- Create User Modal -->
    @can('create-users')
        <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('panel.users.store') }}" id="createUserForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="create_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="create_email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="create_email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="create_password" class="form-label">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="create_password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="create_password_confirmation" class="form-label">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="create_password_confirmation"
                                    name="password_confirmation" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Roles</label>
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="create_role_{{ $role->id }}" name="roles[]"
                                            value="{{ $role->name }}">
                                        <label class="form-check-label" for="create_role_{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <!-- Edit User Modal -->
    @can('update-users')
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('panel.users.update') }}" id="editUserForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_user_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_password" class="form-label">Password <small class="text-muted">(leave
                                        empty to keep current)</small></label>
                                <input type="password" class="form-control" id="edit_password" name="password">
                            </div>

                            <div class="mb-3">
                                <label for="edit_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="edit_password_confirmation"
                                    name="password_confirmation">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Roles</label>
                                <div id="edit_roles_container">
                                    @foreach ($roles as $role)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="edit_role_{{ $role->id }}" name="roles[]"
                                                value="{{ $role->name }}">
                                            <label class="form-check-label" for="edit_role_{{ $role->id }}">
                                                {{ ucfirst($role->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <!-- Delete User Modal -->
    @can('delete-users')
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('panel.users.delete') }}" id="deleteUserForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="delete_user_id">
                        <div class="modal-body">
                            <p>Are you sure you want to delete user <strong id="delete_user_name"></strong>?</p>
                            <p class="text-danger">This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <script>
        // Edit User function
        function editUser(userId) {
            // Fetch user data via AJAX
            fetch(`{{ route('panel.users.edit') }}?id=${userId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const user = data.user;

                    // Set values in modal
                    document.getElementById('edit_user_id').value = user.id;
                    document.getElementById('edit_name').value = user.name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_password').value = '';
                    document.getElementById('edit_password_confirmation').value = '';

                    // Set roles
                    const editRoleCheckboxes = document.querySelectorAll('#edit_roles_container input[name="roles[]"]');

                    // Uncheck all first
                    editRoleCheckboxes.forEach(checkbox => checkbox.checked = false);

                    // Check the appropriate roles
                    user.roles.forEach(role => {
                        const editCheckbox = document.querySelector(
                            `#edit_roles_container input[value="${role.name}"]`);
                        if (editCheckbox) {
                            editCheckbox.checked = true;
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading user data:', error);
                    alert('Error loading user data');
                });
        }

        // Delete User function
        function deleteUser(userId, userName) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('delete_user_name').textContent = userName;
        }

        // Clear form when create modal is closed
        @can('create-users')
            document.getElementById('createUserModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('createUserForm').reset();
            });
        @endcan

        // Clear form when edit modal is closed
        @can('update-users')
            document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('editUserForm').reset();
            });
        @endcan
    </script>
</x-layout.app>
