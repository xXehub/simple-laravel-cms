<x-layout.app title="Users Management - Panel Admin" :pakai-sidebar="true">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-table">
                        <div class="card-header">
                            <div class="row w-full">
                                <div class="col">
                                    <h3 class="card-title mb-0">
                                        <i class="ti ti-users me-2"></i>Users Management
                                    </h3>
                                    <p class="text-secondary m-0">Manage system users and their permissions</p>
                                </div>
                                <div class="col-md-auto col-sm-12">
                                    <div class="ms-auto d-flex flex-wrap btn-list">
                                        <div class="input-group input-group-flat w-auto">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-1">
                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                    <path d="M21 21l-6 -6" />
                                                </svg>
                                            </span>
                                            <input id="advanced-table-search" type="text" class="form-control"
                                                placeholder="Search users..." autocomplete="off" />
                                            <span class="input-group-text">
                                                <kbd>ctrl + K</kbd>
                                            </span>
                                        </div>
                                        @can('create-users')
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#createUserModal">
                                            <i class="ti ti-plus me-2"></i>Add User
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-alert.flash-messages />
                        <div id="advanced-table">
                            <div class="table-responsive">
                                <table class="table table-vcenter table-selectable">
                                    <thead>
                                        <tr>
                                            <th class="w-1">
                                                <input class="form-check-input m-0 align-middle" type="checkbox" 
                                                    aria-label="Select all users">
                                            </th>
                                            <th>
                                                <button class="table-sort d-flex justify-content-between"
                                                    data-sort="sort-name">Name</button>
                                            </th>
                                            <th>
                                                <button class="table-sort d-flex justify-content-between"
                                                    data-sort="sort-email">Email</button>
                                            </th>
                                            <th>
                                                <button class="table-sort d-flex justify-content-between"
                                                    data-sort="sort-role">Role</button>
                                            </th>
                                            <th>
                                                <button class="table-sort d-flex justify-content-between"
                                                    data-sort="sort-date">Registered</button>
                                            </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-tbody">
                                        @forelse($users as $user)
                                        <tr>
                                            <td>
                                                <input class="form-check-input m-0 align-middle table-selectable-check"
                                                    type="checkbox" aria-label="Select user" value="{{ $user->id }}" />
                                            </td>
                                            <td class="sort-name">
                                                @if(isset($user->avatar) && $user->avatar)
                                                    <span class="avatar avatar-xs me-2"
                                                        style="background-image: url({{ asset('storage/' . $user->avatar) }})">
                                                    </span>
                                                @else
                                                    <span class="avatar avatar-xs me-2 placeholder" 
                                                        style="background-color: hsl({{ (ord(substr($user->name, 0, 1)) * 137.5) % 360 }}, 70%, 50%); color: white;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                                {{ $user->name }}
                                            </td>
                                            <td class="sort-email">{{ $user->email }}</td>
                                            <td class="sort-role">
                                                @foreach ($user->roles as $role)
                                                <span class="badge bg-primary-lt me-1">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="sort-date">{{ $user->created_at->format('M d, Y') }}</td>
                                            <td class="py-0">
                                                <div class="d-flex justify-content-end">
                                                    @can('update-users')
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                                        onclick="editUser({{ $user->id }})" data-bs-toggle="modal" 
                                                        data-bs-target="#editUserModal" title="Edit User">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    @endcan
                                                    @can('delete-users')
                                                    @if ($user->id !== auth()->id())
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteUserModal" 
                                                        title="Delete User">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                    @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <div class="empty">
                                                    <div class="empty-icon">
                                                        <i class="ti ti-users icon-lg"></i>
                                                    </div>
                                                    <p class="empty-title">No users found</p>
                                                    <p class="empty-subtitle text-secondary">
                                                        There are no users to display at the moment.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($users->hasPages())
                            <div class="card-footer d-flex align-items-center">
                                <p class="m-0 text-secondary">
                                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                                </p>
                                <ul class="pagination m-0 ms-auto">
                                    {{-- Previous Page Link --}}
                                    @if ($users->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="ti ti-chevron-left"></i>
                                                prev
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->previousPageUrl() }}">
                                                <i class="ti ti-chevron-left"></i>
                                                prev
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                        @if ($page == $users->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($users->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->nextPageUrl() }}">
                                                next
                                                <i class="ti ti-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                next
                                                <i class="ti ti-chevron-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>

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
                                            <input class="form-check-input" type="checkbox" id="create_role_{{ $role->id }}"
                                                name="roles[]" value="{{ $role->name }}">
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
                                        <i class="ti ti-device-floppy"></i> Create User
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
                                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
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
                                                <input class="form-check-input" type="checkbox" id="edit_role_{{ $role->id }}"
                                                    name="roles[]" value="{{ $role->name }}">
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
                                        <i class="ti ti-device-floppy"></i> Update User
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
                                        <i class="ti ti-trash"></i> Delete User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endcan

                <script>
                    // Enhanced search functionality
                    const advancedTable = {
                        headers: [
                            { "data-sort": "sort-name", name: "Name" },
                            { "data-sort": "sort-email", name: "Email" },
                            { "data-sort": "sort-role", name: "Role" },
                            { "data-sort": "sort-date", name: "Registered" },
                        ],
                    };

                    window.tabler_list = window.tabler_list || {};
                    document.addEventListener("DOMContentLoaded", function() {
                        // Initialize List.js for search and sort
                        const list = (window.tabler_list["advanced-table"] = new List("advanced-table", {
                            sortClass: "table-sort",
                            listClass: "table-tbody",
                            valueNames: advancedTable.headers.map((header) => header["data-sort"]),
                        }));

                        // Search functionality
                        const searchInput = document.querySelector("#advanced-table-search");
                        if (searchInput) {
                            searchInput.addEventListener("input", () => {
                                list.search(searchInput.value);
                            });

                            // Keyboard shortcut for search (Ctrl+K)
                            document.addEventListener('keydown', function(e) {
                                if (e.ctrlKey && e.key === 'k') {
                                    e.preventDefault();
                                    searchInput.focus();
                                }
                            });
                        }

                        // Select all functionality
                        const selectAllCheckbox = document.querySelector('input[aria-label="Select all users"]');
                        const userCheckboxes = document.querySelectorAll('.table-selectable-check');
                        
                        if (selectAllCheckbox) {
                            selectAllCheckbox.addEventListener('change', function() {
                                userCheckboxes.forEach(checkbox => {
                                    checkbox.checked = this.checked;
                                });
                            });
                        }

                        // Update select all when individual checkboxes change
                        userCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                const checkedCount = document.querySelectorAll('.table-selectable-check:checked').length;
                                selectAllCheckbox.checked = checkedCount === userCheckboxes.length;
                                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < userCheckboxes.length;
                            });
                        });
                    });

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
                    document.getElementById('createUserModal').addEventListener('hidden.bs.modal', function () {
                        document.getElementById('createUserForm').reset();
                    });
                    @endcan

                    // Clear form when edit modal is closed
                    @can('update-users')
                    document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function () {
                        document.getElementById('editUserForm').reset();
                    });
                    @endcan
                </script>
            </div>
        </div>
    </div>
</x-layout.app>
