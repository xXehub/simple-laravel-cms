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
                            <div class="card-header">
                                <div class="row w-full">
                                    <div class="col">
                                        <div class="dropdown">
                                            <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                                <span id="page-count" class="me-1">15</span>
                                                <span>records</span>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="10">10 records</a>
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="15">15 records</a>
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="25">25 records</a>
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="50">50 records</a>
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="100">100 records</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto col-sm-12">
                                        <div class="ms-auto d-flex flex-wrap btn-list">
                                            <div class="input-group input-group-flat w-auto">
                                                <span class="input-group-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="icon icon-1">
                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                        <path d="M21 21l-6 -6" />
                                                    </svg>
                                                </span>
                                                <input id="advanced-table-search" type="text" class="form-control"
                                                    placeholder="Search permissions..." autocomplete="off" />
                                                <span class="input-group-text">
                                                    <kbd>ctrl + F</kbd>
                                                </span>
                                            </div>
                                            @can('delete-permissions')
                                                <button type="button" id="delete-selected-btn" class="btn btn-danger"
                                                    data-bs-toggle="modal" data-bs-target="#deleteSelectedModal" disabled>
                                                    Delete Selected (<span id="selected-count">0</span>)
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="advanced-table">
                                <div class="table-responsive">
                                    <table id="datatable-permissions" class="table table-vcenter table-selectable">
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
                <x-modals.permissions.delete />
                <x-modals.permissions.delete-selected />
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
