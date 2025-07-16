<x-layout.app title="Menus Management - Panel Admin" :pakai-sidebar="true">
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
                        {{-- <span class="d-none d-sm-inline">
                            <a href="#" class="btn btn-1"> New view </a>
                        </span> --}}
                        @can('create-menus')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#createMenuModal">
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
                        <a href="#" class="btn btn-primary btn-6 d-sm-none btn-icon" data-bs-toggle="modal"
                            data-bs-target="#modal-report" aria-label="Create new report">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-2">
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                        </a>
                    </div>
                    <!-- BEGIN MODAL -->
                    <!-- END MODAL -->
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <x-alert.flash-messages />
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
                                                <span>data ditampilkan</span>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="10">10
                                                    data</a>
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="15">15
                                                    data</a>
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="25">25
                                                    data</a>
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="50">50
                                                    data</a>
                                                <a class="dropdown-item" onclick="setPageListItems(event)"
                                                    data-value="100">100
                                                    data</a>
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
                                                    placeholder="Search menus..." autocomplete="off" />
                                                <span class="input-group-text">
                                                    <kbd>ctrl + K</kbd>
                                                </span>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon me-1">
                                                    <path d="M6 9l6 6 6-6" />
                                                </svg> --}}
                                                    Filter
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#"
                                                        onclick="filterTable('all')">All
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

                                                {{-- gawe delete selected --}}
                                                @can('delete-menus')
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#createMenuModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon me-1">
                                                            <line x1="12" y1="5" x2="12"
                                                                y2="19">
                                                            </line>
                                                            <line x1="5" y1="12" x2="19"
                                                                y2="12">
                                                            </line>
                                                        </svg>
                                                        Hapus Terpilih
                                                    </button>
                                                @endcan
                                            </div>
                                            {{-- gawe button delete all --}}

                                            {{-- end delete all --}}
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
                                                <th>Icon</th>
                                                <th class="w-1">ID</th>
                                                <th style="width: 10%">Nama Menu</th>
                                                <th style="width: 10%">Slug</th>
                                                <th style="width: 20%">Parent</th>
                                                <th style="width: 10%">Route</th>

                                                <th class="w-1">Order</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 30%">Roles</th>
                                                {{-- <th class="w-3">Actions</th> --}}
                                                <th class="w-3">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-tbody">
                                            <!-- Data will be loaded via DataTables AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer d-flex align-items-center">
                                    {{-- gawe showndekene --}}
                                    <div class="col-auto d-flex align-items-center">
                                        <p class="m-0 text-secondary">Showing <strong>1 to 8</strong> of <strong>16
                                                entries</strong></p>
                                    </div>
                                    {{-- end show --}}
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
    </div>

    @push('scripts')
        <!-- Menus DataTable Module -->
        <script src="{{ asset('js/datatable/menus.js') }}"></script>
        <script>
            // Initialize Menus DataTable
            MenusDataTable.initialize('{{ route('panel.menus.index') }}')
                .then(table => {
                    MenusDataTable.setupModalHandlers();
                })
                .catch(error => {
                    console.error('Failed to initialize Menus DataTable:', error);
                });

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        MenusDataTable.refreshDataTable();
                        MenusDataTable.refreshParentMenuOptions('{{ route('panel.menus.index') }}');
                    }, 1000);
                });
            @endif

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.openEditModal = (menu) => MenusDataTable.openEditModal(menu, '{{ route('panel.menus.index') }}');
            window.openDeleteModal = (menu) => MenusDataTable.openDeleteModal(menu);
            window.filterTable = (type) => MenusDataTable.filterTable(type);
        </script>
    @endpush
</x-layout.app>
