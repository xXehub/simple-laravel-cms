<x-layout.app title="Users Management - Panel Admin" :pakai-sidebar="true">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="page-header d-print-none" aria-label="Page header">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">Tables</h2>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-users me-2"></i>Users Management
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    @can('create-users')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createUserModal">
                            <i class="fas fa-plus me-2"></i>Add User
                        </button>
                    @endcan
                </div>
            </div> --}}

            <x-alert.flash-messages />
            {{-- gawe body tabel --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-table">
                        <div class="card-header">
                            <div class="row w-full">
                                <div class="col">
                                    <h3 class="card-title mb-0">Users Management</h3>
                                    <p class="text-secondary m-0">Manage user accounts and their roles.</p>
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
                                                <i class="fas fa-plus me-2"></i>Add User
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="advanced-table">
                            <div class="table-responsive">
                                <table id="datatable-users" class="table table-vcenter table-selectable">
                                    <thead>
                                        <tr>
                                            <th class="w-1">
                                                <input class="form-check-input m-0 align-middle" type="checkbox"
                                                    aria-label="Select all users" id="select-all" />
                                            </th>
                                            <th>
                                                <button class="table-sort d-flex justify-content-between"
                                                    data-sort="sort-name">
                                                    Name
                                                </button>
                                            </th>
                                            <th>
                                                <button class="table-sort d-flex justify-content-between"
                                                    data-sort="sort-email">
                                                    Email
                                                </button>
                                            </th>
                                            <th>
                                                <button class="table-sort d-flex justify-content-between"
                                                    data-sort="sort-role">
                                                    Role
                                                </button>
                                            </th>
                                            <th>
                                                <button class="table-sort d-flex justify-content-between"
                                                    data-sort="sort-date">
                                                    Registered
                                                </button>
                                            </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-tbody">
                                        <!-- Data akan diisi oleh datatable AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer d-flex align-items-center">
                                <div class="dropdown">
                                    <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                        <span id="page-count" class="me-1">10</span>
                                        <span>records</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="10">10
                                            records</a>
                                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="25">25
                                            records</a>
                                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="50">50
                                            records</a>
                                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="100">100
                                            records</a>
                                    </div>
                                </div>
                                <ul class="pagination m-0 ms-auto" id="datatable-pagination">
                                    <!-- Pagination akan diisi oleh DataTables -->
                                </ul>
                            </div>
                        </div>
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('panel.users.store') }}" id="createUserForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="create_name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="create_name" name="name"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="create_email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="create_email" name="email"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="create_password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="create_password" name="password"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="create_password_confirmation" class="form-label">Confirm Password
                                            <span class="text-danger">*</span></label>
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
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
                <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('panel.users.update') }}" id="editUserForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="edit_user_id">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_name" name="name"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="edit_email" name="email"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_password" class="form-label">Password <small
                                                class="text-muted">(leave
                                                empty to keep current)</small></label>
                                        <input type="password" class="form-control" id="edit_password" name="password">
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_password_confirmation" class="form-label">Confirm
                                            Password</label>
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>

    </div>
    @push('scripts')
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        <script>
            let dataTable;

            $(document).ready(function() {
                // Initialize DataTable
                dataTable = $('#datatable-users').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('panel.users.datatable') }}',
                        type: 'GET',
                    },
                    columns: [{
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return '<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select user" value="' +
                                    row.id + '"/>';
                            }
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function(data, type, row) {
                                return '<span class="avatar avatar-xs me-2" style="background-image: url(./static/avatars/000m.jpg);"></span>' +
                                    data;
                            }
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'roles',
                            name: 'roles',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                if (data && data.length > 0) {
                                    let badges = '';
                                    data.forEach(role => {
                                        badges += '<span class="badge bg-primary-lt me-1">' +
                                            role + '</span>';
                                    });
                                    return badges;
                                }
                                return '<span class="badge bg-secondary-lt">No Role</span>';
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data, type, row) {
                                return new Date(data).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                let actions = '<div class="d-flex justify-content-end">';
                                @can('update-users')
                                    actions +=
                                        '<button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(' +
                                        row.id +
                                        ')" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="fas fa-edit"></i></button>';
                                @endcan
                                @can('delete-users')
                                    actions +=
                                        '<button class="btn btn-sm btn-outline-danger" onclick="deleteUser(' +
                                        row.id + ', \'' + row.name +
                                        '\')" data-bs-toggle="modal" data-bs-target="#deleteUserModal"><i class="fas fa-trash"></i></button>';
                                @endcan
                                actions += '</div>';
                                return actions;
                            }
                        },
                    ],
                    dom: 'rt', // Remove default search and pagination
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    language: {
                        processing: "Loading...",
                        zeroRecords: "No users found",
                        info: "",
                        infoEmpty: "",
                        infoFiltered: "",
                    },
                    drawCallback: function(settings) {
                        updatePagination(settings);
                        updateRecordInfo(settings);
                    }
                });

                // Custom search functionality
                $('#advanced-table-search').on('keyup', function() {
                    dataTable.search(this.value).draw();
                });

                // Select all functionality
                $('#select-all').on('change', function() {
                    $('.table-selectable-check').prop('checked', this.checked);
                });

                // Keyboard shortcut for search
                $(document).on('keydown', function(e) {
                    if (e.ctrlKey && e.key === 'k') {
                        e.preventDefault();
                        $('#advanced-table-search').focus();
                    }
                });
            });

            // Function to set page length
            function setPageListItems(event) {
                event.preventDefault();
                const value = parseInt(event.target.getAttribute('data-value'));
                dataTable.page.len(value).draw();
                $('#page-count').text(value);
            }

            // Function to update pagination
            function updatePagination(settings) {
                const api = new $.fn.dataTable.Api(settings);
                const pageInfo = api.page.info();
                const pagination = $('#datatable-pagination');

                pagination.empty();

                // Previous button
                const prevDisabled = pageInfo.page === 0 ? 'disabled' : '';
                pagination.append(`
                <li class="page-item ${prevDisabled}">
                    <a class="page-link" href="#" onclick="changePage(${pageInfo.page - 1})" ${prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M15 6l-6 6l6 6"/>
                        </svg>
                        prev
                    </a>
                </li>
            `);

                // Page numbers
                for (let i = 0; i < pageInfo.pages; i++) {
                    const active = i === pageInfo.page ? 'active' : '';
                    pagination.append(`
                    <li class="page-item ${active}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i + 1}</a>
                    </li>
                `);
                }

                // Next button
                const nextDisabled = pageInfo.page === pageInfo.pages - 1 ? 'disabled' : '';
                pagination.append(`
                <li class="page-item ${nextDisabled}">
                    <a class="page-link" href="#" onclick="changePage(${pageInfo.page + 1})" ${nextDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                        next
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M9 6l6 6l-6 6"/>
                        </svg>
                    </a>
                </li>
            `);
            }

            // Function to change page
            function changePage(pageNumber) {
                if (pageNumber >= 0) {
                    dataTable.page(pageNumber).draw(false);
                }
            }

            // Function to update record info
            function updateRecordInfo(settings) {
                const api = new $.fn.dataTable.Api(settings);
                const pageInfo = api.page.info();
                // Update any record count displays if needed
            }

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
    @endpush
</x-layout.app>
