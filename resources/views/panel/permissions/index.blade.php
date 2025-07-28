<x-layout.app title="Permissions Management - Panel Admin" :pakai-sidebar="true" :pakaiTopBar="false">
    <div class="page-header d-print-none" aria-label="Page header">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">Panel/Permissions</div>
                    <h2 class="page-title">
                        <i class="fas fa-key me-2"></i>Permissions Management
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @can('create-permissions')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#createPermissionModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon me-1">
                                    <line x1="12" y1="5" x2="12" y2="19">
                                    </line>
                                    <line x1="5" y1="12" x2="19" y2="12">
                                    </line>
                                </svg>
                                Add Permission
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
                searchPlaceholder="Search permissions..." 
                :showBulkDelete="true"
                bulkDeletePermission="delete-permissions"
                bulkDeleteText="Delete Selected"
                :recordOptions="[10, 15, 25, 50, 100]"
                :defaultRecords="15" />
                            <div id="advanced-table">
                                <div class="table-responsive">
                                    <table id="datatable-permissions">
                                        <thead>
                                            <tr>
                                                <th class="w-1">
                                                    <input class="form-check-input m-0 align-middle" type="checkbox"
                                                        aria-label="Select all permissions" id="select-all" />
                                                </th>
                                                <th>No</th>
                                                <th>Permission Name</th>
                                                <th>Group</th>
                                                <th>Guard</th>
                                                <th>Created</th>
                                                <th class="w-3">Actions</th>
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
                                            of <strong>0 entries</strong>
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

                <!-- Permission Modals -->
                <x-modals.permissions.create />
                <x-modals.permissions.edit />
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Permissions DataTable Module -->
        <script src="{{ asset('js/datatable/permissions.js') }}"></script>
        <script>
            let permissionsTable;

            // Set the edit route for backward compatibility
            window.permissionEditRoute = '{{ route('panel.permissions.edit', ':id') }}';
            window.permissionDeleteRoute = '{{ route('panel.permissions.destroy', ':id') }}';

            // Initialize Permissions DataTable
            PermissionsDataTable.initialize('{{ route('panel.permissions.datatable') }}')
                .then(table => {
                    permissionsTable = table;
                    PermissionsDataTable.setupModalHandlers();
                    PermissionsDataTable.setBulkDeleteRoute('{{ route('panel.permissions.bulkDestroy') }}');
                })
                .catch(error => {
                    console.error('Failed to initialize Permissions DataTable:', error);
                });

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        PermissionsDataTable.refreshDataTable();
                    }, 1000);
                });
            @endif
        </script>
    @endpush
</x-layout.app>
