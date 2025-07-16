<x-layout.app title="Menus Management - Panel Admin" :pakai-sidebar="true">
    <div class="container-xl">
        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Menus Management</h2>
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
                                    <h3 class="card-title mb-0">Menus Management</h3>
                                    <p class="text-secondary m-0">Manage navigation menus and their hierarchy.</p>
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
                                                placeholder="Search menus..." autocomplete="off" />
                                            <span class="input-group-text">
                                                <kbd>ctrl + K</kbd>
                                            </span>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon me-1">
                                                    <path d="M6 9l6 6 6-6" />
                                                </svg>
                                                Filter
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" onclick="filterTable('all')">All
                                                    Menus</a>
                                                <a class="dropdown-item" href="#"
                                                    onclick="filterTable('active')">Active Only</a>
                                                <a class="dropdown-item" href="#"
                                                    onclick="filterTable('inactive')">Inactive Only</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#"
                                                    onclick="filterTable('parent')">Parent Menus</a>
                                                <a class="dropdown-item" href="#"
                                                    onclick="filterTable('child')">Child Menus</a>
                                            </div>
                                        </div>
                                        @can('create-menus')
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#createMenuModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon me-1">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <line x1="5" y1="12" x2="19" y2="12">
                                                    </line>
                                                </svg>
                                                Add Menu
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="advanced-table">
                            <div class="table-responsive">
                                <table id="menusTable" class="table table-vcenter table-selectable">
                                    <thead>
                                        <tr>
                                            <th class="w-1">
                                                <input class="form-check-input m-0 align-middle" type="checkbox"
                                                    aria-label="Select all menus" id="select-all" />
                                            </th>
                                            <th class="w-1">ID</th>
                                            <th>Menu Name</th>
                                            <th>Slug</th>
                                            <th>Parent</th>
                                            <th>Route</th>
                                            <th>Icon</th>
                                            <th class="w-1">Order</th>
                                            <th>Status</th>
                                            <th>Roles</th>
                                            <th class="w-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-tbody">
                                        <!-- Data will be loaded via DataTables AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer d-flex align-items-center">
                                <div class="dropdown">
                                    <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                        <span id="page-count" class="me-1">15</span>
                                        <span>records</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="10">10
                                            records</a>
                                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="15">15
                                            records</a>
                                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="25">25
                                            records</a>
                                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="50">50
                                            records</a>
                                        <a class="dropdown-item" onclick="setPageListItems(event)"
                                            data-value="100">100
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

            @include('components.modals.menus.create')
            @include('components.modals.menus.update')
            @include('components.modals.menus.delete')
        </div>
    </div>

    @push('scripts')
        <script>
            // --- DataTable Variable ---
            let menusTable;
        </script>
        <!-- Menus DataTable Module -->
        <script src="{{ asset('js/datatable/menus.js') }}"></script>
        <script>
            // Initialize Menus DataTable
            MenusDataTable.initialize('{{ route('panel.menus.index') }}').then(table => {
                // Setup modal handlers
                MenusDataTable.setupModalHandlers();

                // Handle validation errors and auto-reopen modals
                @if ($errors->any())
                    @if (old('_method') === 'PUT')
                        const m = document.getElementById('editMenuModal');
                        if (m) {
                            // Fill form with old values and show modal
                            // Implementation would go here for error handling
                        }
                    @else
                        const m = document.getElementById('createMenuModal');
                        if (m) {
                            // Show create modal for validation errors
                        }
                    @endif
                @endif
            });

            // Check if operation was successful and refresh table
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        MenusDataTable.refreshDataTable();
                        MenusDataTable.refreshParentMenuOptions('{{ route('panel.menus.index') }}');
                    }, 1000);
                });
            @endif

            // Global functions for backward compatibility
            function openEditModal(menu) {
                MenusDataTable.openEditModal(menu, '{{ route('panel.menus.index') }}');
            }

            function openDeleteModal(menu) {
                MenusDataTable.openDeleteModal(menu);
            }

            function filterTable(type) {
                MenusDataTable.filterTable(type);
            }
        </script>
    @endpush
</x-layout.app>
