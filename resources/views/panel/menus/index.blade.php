<x-layout.app title="Menus Management - Panel Admin" :pakaiSidebar="true" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-header d-print-none" aria-label="Page header">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">Panel/Menus</div>
                        <h2 class="page-title">Manajemen Menu</h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            @can('create-menus')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createMenuModal">
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
                            <div class="card-table">
                                <x-form.datatable-header searchPlaceholder="Cari menus..." :showBulkDelete="true"
                                    bulkDeletePermission="delete-menus">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
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
                                            <a class="dropdown-item" href="#" onclick="filterTable('child')">Child
                                                Menus</a>
                                        </div>
                                    </div>
                                </x-form.datatable-header>
                                <div id="advanced-table">
                                    <div class="table-responsive">
                                        <table id="menusTable">
                                            <thead>
                                                <tr>
                                                    <th class="w-1">
                                                        <input class="form-check-input m-0 align-middle" type="checkbox"
                                                            aria-label="Select all menus" id="select-all" />
                                                    </th>
                                                    <th></th>
                                                    <th>ICON</th>
                                                    <th style="width: 20%">Nama Menu</th>
                                                    <th style="width: 10%">Slug</th>
                                                    <th style="width: 20%">Parent</th>
                                                    <th style="width: 10%">Route</th>
                                                    <th class="w-1">Urutan</th>
                                                    <th style="width: 10%">Status</th>
                                                    <th style="width: 30%">Roles</th>
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
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- load komponen modal disini -->
    @can('create-menus')
        <x-modals.menus.create :parentMenus="$parentMenus" :roles="$roles" :data="$data" />
    @endcan
    @can('update-menus')
        <x-modals.menus.update :parentMenus="$parentMenus" :roles="$roles" :data="$data" />
    @endcan
    @push('scripts')
        <!-- Toast Notification System -->
        <script src="{{ asset('js/toast.js') }}"></script>
        <!-- Menus DataTable Module -->
        <script src="{{ asset('js/datatable/menus.js') }}"></script>
        <script>
            let menusTable;

            // Set global routes for menu operations
            window.menuDeleteRoute = '{{ route('panel.menus.destroy', ':id') }}';

            // Check if required dependencies are loaded
            console.log('DataTableGlobal available:', typeof DataTableGlobal !== 'undefined');
            console.log('MenusDataTable available:', typeof MenusDataTable !== 'undefined');
            console.log('jQuery available:', typeof $ !== 'undefined');
            console.log('DataTables available:', typeof $.fn.DataTable !== 'undefined');

            // Wait for dependencies to load
            function waitForDependencies() {
                return new Promise((resolve, reject) => {
                    let attempts = 0;
                    const maxAttempts = 50; // 5 seconds max wait

                    function check() {
                        attempts++;

                        if (typeof DataTableGlobal !== 'undefined' &&
                            typeof MenusDataTable !== 'undefined' &&
                            typeof $ !== 'undefined' &&
                            typeof $.fn.DataTable !== 'undefined') {
                            resolve();
                        } else if (attempts >= maxAttempts) {
                            reject(new Error('Dependencies failed to load within timeout'));
                        } else {
                            setTimeout(check, 100);
                        }
                    }

                    check();
                });
            }

            // Initialize Menus DataTable
            waitForDependencies()
                .then(() => {
                    console.log('All dependencies loaded, initializing...');
                    return MenusDataTable.initialize('{{ route('panel.menus') }}');
                })
                .then(table => {
                    menusTable = table;

                    // Setup all handlers
                    MenusDataTable.setupModalHandlers();
                    MenusDataTable.setupMenuOrderHandlers('{{ route('panel.menus.moveOrder') }}');
                    MenusDataTable.initializeAllHandlers(
                        '{{ route('panel.menus.bulkDestroy') }}',
                        '{{ csrf_token() }}'
                    );
                })
                .catch(error => {
                    console.error('Failed to initialize Menus DataTable:', error);
                    alert('Failed to load menu table. Please refresh the page.');
                });

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        if (typeof MenusDataTable !== 'undefined') {
                            MenusDataTable.refreshDataTable();
                            MenusDataTable.refreshParentMenuOptions('{{ route('panel.menus') }}');
                        }
                    }, 1000);
                });
            @endif

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.openEditModal = (menu) => {
                if (typeof MenusDataTable !== 'undefined') {
                    MenusDataTable.openEditModal(menu, '{{ route('panel.menus') }}');
                } else {
                    console.error('MenusDataTable not available');
                }
            };
            window.openDeleteModal = (menu) => {
                if (typeof confirmDeleteMenu === 'function') {
                    confirmDeleteMenu(menu.id, menu.label || menu.nama_menu);
                } else if (typeof MenusDataTable !== 'undefined') {
                    MenusDataTable.openDeleteModal(menu);
                } else {
                    console.error('MenusDataTable not available');
                }
            };
            window.filterTable = (type) => {
                if (typeof MenusDataTable !== 'undefined') {
                    MenusDataTable.filterTable(type);
                } else {
                    console.error('MenusDataTable not available');
                }
            };
        </script>
    @endpush
</x-layout.app>
