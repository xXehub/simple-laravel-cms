<x-layout.app title="Menus Management - Panel Admin" :pakaiSidebar="true" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-header d-print-none" aria-label="Page header">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">Panel/Users</div>
                        <h2 class="page-title">User Management</h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            @can('create-users')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createUserModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                        <line x1="12" y1="5" x2="12" y2="19">
                                        </line>
                                        <line x1="5" y1="12" x2="19" y2="12">
                                        </line>
                                    </svg>
                                    Tambah Data
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row row-cards">
                    <x-alert.modal-alert />

                    <!-- Main Table Card -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-users" class="nav-link active" data-bs-toggle="tab"
                                            aria-selected="true" role="tab">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon me-2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                            Users
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-trashed" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" role="tab" tabindex="-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon me-2">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c0 1 1 2 2V6"></path>
                                            </svg>
                                            Trashed Users
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- Active Users Tab -->
                                <div class="tab-pane active show" id="tabs-users" role="tabpanel">
                                    <div class="card-table" id="main-table-container">
                                        <x-form.datatable-header searchPlaceholder="Cari users..." :showBulkDelete="true"
                                            bulkDeletePermission="delete-users">
                                        </x-form.datatable-header>
                                        <div id="advanced-table">
                                            <div class="table-responsive">
                                                <table id="datatable-users">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">
                                                                <input class="form-check-input m-0 align-middle"
                                                                    type="checkbox" aria-label="Select all users"
                                                                    id="select-all" />
                                                            </th>
                                                            <th>Name</th>
                                                            <th>Username</th>
                                                            <th>Email</th>
                                                            <th>Role</th>
                                                            <th>Created At</th>
                                                            <th class="w-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-tbody">
                                                        <!-- Data will be loaded via DataTables AJAX -->
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card-footer d-flex align-items-center">
                                                <div class="col-auto d-flex align-items-center">
                                                    <p class="m-0 text-secondary" id="record-info">Showing <strong>0 to
                                                            0</strong>
                                                        of <strong>0 entries</strong></p>
                                                </div>
                                                <ul class="pagination m-0 ms-auto" id="datatable-pagination">
                                                    <!-- Pagination will be filled by DataTables -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trashed Users Tab -->
                                <div class="tab-pane" id="tabs-trashed" role="tabpanel">
                                    <div class="card-table">
                                        <div id="trashed-table-placeholder"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- User Modals -->

                </div>
            </div>
        </div>
    </div>
    <x-modals.users.create :roles="$roles" />
    <x-modals.users.update :roles="$roles" />
    @include('components.modals.users.avatar-upload')
    @push('scripts')
        <!-- Users DataTable Module -->
        <script src="{{ asset('js/datatable/users.js') }}"></script>
        <script>
            let usersTable;

            // Set global routes for users
            window.userEditRoute = '{{ route('panel.users.edit', ':id') }}';
            window.userDeleteRoute = '{{ route('panel.users.destroy', ':id') }}';
            window.userRestoreRoute = '{{ route('panel.users.restore', ':id') }}';
            window.userForceDeleteRoute = '{{ route('panel.users.forceDestroy', ':id') }}';

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Active Users DataTable
                UsersDataTable.initialize('{{ route('panel.users') }}')
                    .then(table => {
                        usersTable = table;
                        UsersDataTable.setupModalHandlers();
                        UsersDataTable.setBulkDeleteRoute('{{ route('panel.users.bulkDestroy') }}');

                        // Setup tab filtering
                        setupTabFiltering(table);

                        console.log('Users DataTable initialized successfully');
                    })
                    .catch(error => {
                        console.error('Failed to initialize Users DataTable:', error);
                    });
            });

            // Setup tab filtering function
            function setupTabFiltering(table) {
                const mainTableContainer = document.getElementById('main-table-container');

                // Handle tab clicks
                document.querySelectorAll('.nav-tabs a[data-bs-toggle="tab"]').forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function(e) {
                        const target = e.target.getAttribute('href');
                        const tabType = target.replace('#tabs-', '');

                        // Move table to active tab
                        if (tabType === 'users') {
                            // Move table back to users tab
                            const usersTabPane = document.getElementById('tabs-users');
                            if (usersTabPane && mainTableContainer && !usersTabPane.contains(
                                    mainTableContainer)) {
                                usersTabPane.appendChild(mainTableContainer);
                            }
                            // Show active users (no trashed filter)
                            if (table.ajax) {
                                table.ajax.url('{{ route('panel.users') }}').load();
                            }
                        } else if (tabType === 'trashed') {
                            // Move table to trashed tab placeholder
                            const targetPlaceholder = document.getElementById('trashed-table-placeholder');
                            if (targetPlaceholder && mainTableContainer) {
                                targetPlaceholder.appendChild(mainTableContainer);
                            }
                            // Show trashed users
                            if (table.ajax) {
                                table.ajax.url('{{ route('panel.users') }}?show_trashed=true').load();
                            }
                        }

                        // Ensure table is visible and properly sized
                        setTimeout(() => {
                            table.columns.adjust().responsive.recalc();
                        }, 100);
                    });
                });
            }

            // Functions for trashed users actions
            function restoreUser(userId, userName) {
                Swal.fire({
                    title: 'Restore User?',
                    text: `Are you sure you want to restore user "${userName}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, restore it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window.userRestoreRoute.replace(':id', userId);

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'PATCH';

                        form.appendChild(csrfToken);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function forceDeleteUser(userId, userName) {
                Swal.fire({
                    title: 'Permanently Delete User?',
                    text: `Are you sure you want to permanently delete user "${userName}"? This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete permanently!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window.userForceDeleteRoute.replace(':id', userId);

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';

                        form.appendChild(csrfToken);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        if (usersTable) {
                            UsersDataTable.refreshDataTable();
                        }
                    }, 1000);
                });
            @endif

            // Backward compatibility
            window.editUser = (userId) => UsersDataTable.editUser(userId, '{{ route('panel.users.edit', ':id') }}');
            window.deleteUser = (userId, userName) => confirmDeleteUser(userId, userName);
            window.restoreUser = restoreUser;
            window.forceDeleteUser = forceDeleteUser;
        </script>
    @endpush
</x-layout.app>
