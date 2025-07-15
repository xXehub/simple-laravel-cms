<x-layout.app title="Users Management - Panel Admin" :pakai-sidebar="true">
    <div class="container-xl">
        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Users Management</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards">
            <x-alert.flash-messages />

            <!-- Main Table Card -->
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon me-1">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <line x1="5" y1="12" x2="19" y2="12">
                                                    </line>
                                                </svg>
                                                Add User
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
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Registered</th>
                                            <th class="w-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-tbody">
                                        <!-- Data will be filled by DataTable AJAX -->
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
                                    <!-- Pagination will be filled by DataTables -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Modals -->
            <x-modals.users.create :roles="$roles" />
            <x-modals.users.update :roles="$roles" />
        </div>
    </div>

    @push('scripts')
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script>
            // --- DataTable Initialization ---
            let dataTable;
            $(document).ready(function() {
                dataTable = $('#datatable-users').DataTable({
                    processing: true,
                    autoWidth: false,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('panel.users.datatable') }}',
                        type: 'GET',
                    },
                    columns: [{
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: (data, type, row) =>
                                `<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select user" value="${row.id}"/>`
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: (data) =>
                                `<span class="avatar avatar-xs me-2" style="background-image: url(./static/avatars/000m.jpg);"></span>${data}`
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
                            render: (data) => {
                                if (data && data.length > 0) {
                                    return data.map(role =>
                                            `<span class="badge bg-primary-lt me-1">${role}</span>`)
                                        .join('');
                                }
                                return '<span class="badge bg-secondary-lt">No Role</span>';
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: (data) => new Date(data).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-end'
                        },
                    ],
                    dom: 'rt',
                    order: [
                        [1, 'asc']
                    ],
                    columnDefs: [{
                        targets: [0, 3, 5],
                        orderable: false
                    }],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    language: {
                        processing: "Memuat...",
                        zeroRecords: "Tidak ada pengguna ditemukan",
                        info: "",
                        infoEmpty: "",
                        infoFiltered: "",
                    },
                    drawCallback: function(settings) {
                        updatePagination(settings);
                        updateRecordInfo(settings);
                    }
                });
                // --- Event Listeners ---
                $('#advanced-table-search').on('keyup', function() {
                    dataTable.search(this.value).draw();
                });
                $('#select-all').on('change', function() {
                    $('.table-selectable-check').prop('checked', this.checked);
                });
                $(document).on('keydown', function(e) {
                    if (e.ctrlKey && e.key === 'k') {
                        e.preventDefault();
                        $('#advanced-table-search').focus();
                    }
                });
                // Reset forms on modal close
                $('#createUserModal').on('hidden.bs.modal', function() {
                    document.getElementById('createUserForm').reset();
                });
                $('#editUserModal').on('hidden.bs.modal', function() {
                    document.getElementById('editUserForm').reset();
                });
            });
            // --- Helper Functions ---
            function setPageListItems(event) {
                event.preventDefault();
                const value = parseInt(event.target.getAttribute('data-value'));
                dataTable.page.len(value).draw();
                $('#page-count').text(value);
            }

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
                            sebelumnya
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
                            selanjutnya
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M9 6l6 6l-6 6"/>
                            </svg>
                        </a>
                    </li>
                `);
            }

            function changePage(pageNumber) {
                if (pageNumber >= 0) {
                    dataTable.page(pageNumber).draw(false);
                }
            }

            function updateRecordInfo(settings) {
                // You can update record count displays here if needed
            }
            // --- User Actions ---
            function editUser(userId) {
                fetch(`{{ route('panel.users.edit') }}?id=${userId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const user = data.user;
                        document.getElementById('edit_user_id').value = user.id;
                        document.getElementById('edit_name').value = user.name;
                        document.getElementById('edit_email').value = user.email;
                        document.getElementById('edit_password').value = '';
                        document.getElementById('edit_password_confirmation').value = '';
                        const editRoleCheckboxes = document.querySelectorAll('#edit_roles_container input[name="roles[]"]');
                        editRoleCheckboxes.forEach(checkbox => checkbox.checked = false);
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

            function deleteUser(userId, userName) {
                document.getElementById('delete_user_id').value = userId;
                document.getElementById('delete_user_name').textContent = userName;
            }
        </script>
    @endpush
</x-layout.app>
