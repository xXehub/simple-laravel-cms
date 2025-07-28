<x-layout.app title="Roles Management - Panel Admin" :pakai-sidebar="true" :pakaiTopBar="false">
    <div class="page-header d-print-none" aria-label="Page header">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">Panel/Roles</div>
                    <h2 class="page-title">Roles Management</h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @can('create-roles')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#createRoleModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon me-1">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Role
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
                searchPlaceholder="Cari roles..."
                :showBulkDelete="true"
                bulkDeletePermission="delete-roles" />

                            <div id="advanced-table">
                                <div class="table-responsive">
                                    <table id="rolesTable">
                                        <thead>
                                            <tr>
                                                <th class="w-1">
                                                    <input class="form-check-input m-0 align-middle" type="checkbox"
                                                        aria-label="Select all roles" id="select-all" />
                                                </th>
                                                <th>ID</th>
                                                <th>Nama Role</th>
                                                <th>Guard</th>
                                                <th>Permissions</th>
                                                <th>Dibuat</th>
                                                <th class="w-3"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-tbody">
                                            <!-- Data will be loaded via DataTables AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer d-flex align-items-center">
                                    <div class="col-auto d-flex align-items-center">
                                        <p class="m-0 text-secondary" id="record-info">Menampilkan<strong>0 to 0</strong>
                                            dari <strong>0 data</strong></p>
                                    </div>
                                    <ul class="pagination m-0 ms-auto" id="datatable-pagination">
                                        <!-- Pagination will be filled by DataTables -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Include Modal Components -->
                <x-modals.roles.create :permissions="$permissions" />
                <x-modals.roles.edit :permissions="$permissions" />
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Roles DataTable Module -->
        <script src="{{ asset('js/datatable/roles.js') }}"></script>
        <script>
            let rolesTable;

            // Set global edit route for fallback
            window.roleEditRoute = '{{ route('panel.roles.edit', ':id') }}';
            window.roleDeleteRoute = '{{ route('panel.roles.destroy', ':id') }}';
            window.roleUpdateRoute = '{{ route('panel.roles.update', ':id') }}';

            // Initialize Roles DataTable
            RolesDataTable.initialize('{{ route('panel.roles') }}')
                .then(table => {
                    rolesTable = table;
                    RolesDataTable.setupModalHandlers();
                    RolesDataTable.setBulkDeleteRoute('{{ route('panel.roles.bulkDestroy') }}');
                })
                .catch(error => {
                    console.error('Failed to initialize Roles DataTable:', error);
                });

            // Auto-refresh on success
            @if(session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        RolesDataTable.refreshDataTable();
                    }, 1000);
                });
            @endif

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.editRole = (roleId) => RolesDataTable.editRole(roleId, '{{ route('panel.roles.edit', ':id') }}');
            window.deleteRole = (roleId, roleName) => confirmDeleteRole(roleId, roleName);
        </script>
    @endpush
</x-layout.app>
