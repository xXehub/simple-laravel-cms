<x-layout.app title="Menus Management - Panel Admin" :pakaiSidebar="true" :pakaiTopBar="false">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon me-1">
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
                        <div class="card-table">
                <x-form.datatable-header
                searchPlaceholder="Cari users..."
                :showBulkDelete="true"
                bulkDeletePermission="delete-users" />
                            <div id="advanced-table">
                                <div class="table-responsive">
                                    <table id="datatable-users">
                                        <thead>
                                            <tr>
                                                <th class="w-1">
                                                    <input class="form-check-input m-0 align-middle" type="checkbox"
                                                        aria-label="Select all users" id="select-all" />
                                                </th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Dibuat</th>
                                                <th class="w-3"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-tbody">
                                            <!-- Data will be filled by DataTable AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer d-flex align-items-center">
                                    <div class="col-auto d-flex align-items-center">
                                        <p class="m-0 text-secondary" id="record-info">Showing <strong>0 to 0</strong>
                                            of
                                            <strong>0 entries</strong>
                                        </p>
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
                @include('components.modals.users.avatar-upload')
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Users DataTable Module -->
        <script src="{{ asset('js/datatable/users.js') }}"></script>
        <script>
            let usersTable;

            // Set global edit route for fallback
            window.userEditRoute = '{{ route('panel.users.edit', ':id') }}';
            window.userDeleteRoute = '{{ route('panel.users.destroy', ':id') }}';

            // Initialize Users DataTable
            UsersDataTable.initialize('{{ route('panel.users') }}')
                .then(table => {
                    usersTable = table;
                    UsersDataTable.setupModalHandlers();
                    UsersDataTable.setBulkDeleteRoute('{{ route('panel.users.bulkDestroy') }}');
                })
                .catch(error => {
                    console.error('Failed to initialize Users DataTable:', error);
                });

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        UsersDataTable.refreshDataTable();
                    }, 1000);
                });
            @endif

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.editUser = (userId) => UsersDataTable.editUser(userId, '{{ route('panel.users.edit', ':id') }}');
            window.deleteUser = (userId, userName) => confirmDeleteUser(userId, userName);
        </script>
    @endpush
</x-layout.app>
