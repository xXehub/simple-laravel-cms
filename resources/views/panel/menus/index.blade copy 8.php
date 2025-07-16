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
                                            <button class="btn btn-outline-secondary dropdown-toggle" 
                                                    type="button" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon me-1">
                                                    <path d="M6 9l6 6 6-6"/>
                                                </svg>
                                                Filter
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" onclick="filterTable('all')">All Menus</a>
                                                <a class="dropdown-item" href="#" onclick="filterTable('active')">Active Only</a>
                                                <a class="dropdown-item" href="#" onclick="filterTable('inactive')">Inactive Only</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" onclick="filterTable('parent')">Parent Menus</a>
                                                <a class="dropdown-item" href="#" onclick="filterTable('child')">Child Menus</a>
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

            @include('components.modals.menus.create')
            @include('components.modals.menus.update')
            @include('components.modals.menus.delete')
        </div>
    </div>


    <script>
        // --- DataTable Variable ---
        let menusTable;

        // --- Modal Helpers ---
        function showModal(el) {
            if (window.$?.fn?.modal) return $(el).modal('show');
            if (window.bootstrap?.Modal) return (new bootstrap.Modal(el)).show();
            el.classList.add('show');
            el.style.display = 'block';
            el.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
            if (!document.querySelector('.modal-backdrop')) {
                const b = document.createElement('div');
                b.className = 'modal-backdrop fade show';
                document.body.appendChild(b);
            }
        }

        function hideModal(el) {
            if (window.$?.fn?.modal) return $(el).modal('hide');
            if (window.bootstrap?.Modal) return bootstrap.Modal.getInstance(el)?.hide();
            el.classList.remove('show');
            el.style.display = 'none';
            el.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            document.querySelector('.modal-backdrop')?.remove();
        }

        // --- Modal Openers ---
        window.openEditModal = menu => {
            fillEditModal(menu); // Open modal instantly
            // After parent menu options refresh, pastikan parent_id tetap sesuai
            refreshParentMenuOptions().then(() => {
                setSelect('edit_parent_id', menu.parent_id, 'edit_parent_id');
            }).catch(() => {});
        };
        
        window.openDeleteModal = menu => {
            const m = document.getElementById('deleteMenuModal');
            if (!m) return;
            setValue('delete_menu_id', menu.id);
            setText('delete_menu_name', menu.nama_menu);
            setText('delete_menu_slug', menu.slug);
            setText('delete_menu_route', menu.route_name || '-');
            showModal(m);
        };

        // --- Pagination Helper ---
        window.setPageListItems = function(event) {
            event.preventDefault();
            const value = parseInt(event.target.getAttribute('data-value'));
            menusTable.page.len(value).draw();
            document.getElementById('page-count').textContent = value;
        };

        // --- Filter Functionality ---
        window.filterTable = function(type) {
            let searchTerm = '';
            switch(type) {
                case 'active':
                    searchTerm = 'Active';
                    menusTable.column(7).search(searchTerm).draw();
                    break;
                case 'inactive':
                    searchTerm = 'Inactive';
                    menusTable.column(7).search(searchTerm).draw();
                    break;
                case 'parent':
                    searchTerm = '^-$'; // Match only "-" (no parent)
                    menusTable.column(3).search(searchTerm, true, false).draw();
                    break;
                case 'child':
                    searchTerm = '^(?!-).*'; // Match anything except "-"
                    menusTable.column(3).search(searchTerm, true, false).draw();
                    break;
                case 'all':
                default:
                    menusTable.columns().search('').draw();
                    break;
            }
        };

        // Check if operation was successful and refresh table
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    refreshDataTable();
                    refreshParentMenuOptions();
                }, 1000);
            });
        @endif

        // --- DOM Ready ---
        function waitForLibraries() {
            // Check if all required libraries are loaded
            if (typeof $ === 'undefined' || 
                typeof $.fn.DataTable === 'undefined' || 
                typeof window.TomSelect === 'undefined') {
                setTimeout(waitForLibraries, 100);
                return;
            }
            
            // Initialize everything once libraries are ready
            initializeApp();
        }

        function initializeApp() {
            window.tomSelectInstances ??= {};
            initTomSelect();

            // Initialize DataTables with Tabler styling
            menusTable = $('#menusTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('panel.menus.index') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 0,
                        name: 'id',
                        className: 'text-secondary'
                    },
                    {
                        data: 1,
                        name: 'nama_menu',
                        className: 'fw-medium'
                    },
                    {
                        data: 2,
                        name: 'slug',
                        className: 'text-secondary'
                    },
                    {
                        data: 3,
                        name: 'parent_id',
                        orderable: false,
                        className: 'text-secondary'
                    },
                    {
                        data: 4,
                        name: 'route_name'
                    },
                    {
                        data: 5,
                        name: 'icon',
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 6,
                        name: 'urutan',
                        className: 'text-center'
                    },
                    {
                        data: 7,
                        name: 'is_active',
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 8,
                        name: 'roles',
                        orderable: false
                    },
                    {
                        data: 9,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-end'
                    }
                ],
                dom: 'rt',
                order: [
                    [6, 'asc'],
                    [0, 'asc']
                ],
                pageLength: 15,
                lengthMenu: [
                    [10, 15, 25, 50, 100],
                    [10, 15, 25, 50, 100]
                ],
                language: {
                    processing: "Loading...",
                    zeroRecords: "No menus found",
                    info: "",
                    infoEmpty: "",
                    infoFiltered: "",
                    emptyTable: "No menu data available",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                drawCallback: function(settings) {
                    // Update custom pagination
                    const api = this.api();
                    const pageInfo = api.page.info();
                    
                    // Build custom pagination
                    let paginationHtml = '';
                    const totalPages = pageInfo.pages;
                    const currentPage = pageInfo.page + 1;
                    
                    // Previous button
                    if (currentPage > 1) {
                        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="menusTable.page('previous').draw(); return false;">‹ Prev</a></li>`;
                    }
                    
                    // Page numbers
                    const startPage = Math.max(1, currentPage - 2);
                    const endPage = Math.min(totalPages, currentPage + 2);
                    
                    if (startPage > 1) {
                        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="menusTable.page(0).draw(); return false;">1</a></li>`;
                        if (startPage > 2) {
                            paginationHtml += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
                        }
                    }
                    
                    for (let i = startPage; i <= endPage; i++) {
                        const isActive = i === currentPage ? 'active' : '';
                        const pageIndex = i - 1;
                        paginationHtml += `<li class="page-item ${isActive}"><a class="page-link" href="#" onclick="menusTable.page(${pageIndex}).draw(); return false;">${i}</a></li>`;
                    }
                    
                    if (endPage < totalPages) {
                        if (endPage < totalPages - 1) {
                            paginationHtml += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
                        }
                        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="menusTable.page(${totalPages - 1}).draw(); return false;">${totalPages}</a></li>`;
                    }
                    
                    // Next button
                    if (currentPage < totalPages) {
                        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="menusTable.page('next').draw(); return false;">Next ›</a></li>`;
                    }
                    
                    $('#datatable-pagination').html(paginationHtml);
                }                });

            // Custom search functionality
            $('#advanced-table-search').on('keyup', function() {
                menusTable.search(this.value).draw();
            });

            // Keyboard shortcut for search
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.key === 'k') {
                    e.preventDefault();
                    $('#advanced-table-search').focus();
                }
            });

            // Modal click handlers
            document.addEventListener('click', e => {
                if (e.target.closest('[data-bs-dismiss="modal"]')) hideModal(e.target.closest('.modal'));
                if (e.target.classList.contains('modal-backdrop')) document.querySelectorAll('.modal.show')
                    .forEach(hideModal);
            });

            // Validation error auto-reopen modal
            @if ($errors->any())
                @if (old('_method') === 'PUT')
                    const m = document.getElementById('editMenuModal');
                    if (m) {
                        setValue('edit_menu_id', '{{ old('id') }}');
                        setValue('edit_nama_menu', '{{ old('nama_menu') }}');
                        setValue('edit_slug', '{{ old('slug') }}');
                        setValue('edit_route_name', '{{ old('route_name') }}');
                        setValue('edit_icon', '{{ old('icon') }}');
                        setValue('edit_urutan', '{{ old('urutan') }}');
                        setChecked('edit_is_active', {{ old('is_active') ? 'true' : 'false' }});
                        setSelect('edit_parent_id', '{{ old('parent_id') }}', 'edit_parent_id');
                        setRoles('edit_roles', @json(old('roles', [])));
                        showModal(m);
                    }
                @else
                    const m = document.getElementById('createMenuModal');
                    if (m) showModal(m);
                @endif
            @endif
        }

        // Start when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', waitForLibraries);
        } else {
            waitForLibraries();
        }
        // --- TomSelect ---
        function initTomSelect() {
            destroyTomSelects();
            ['create_roles', 'edit_roles', 'create_parent_id', 'edit_parent_id'].forEach(id => {
                const el = document.getElementById(id);
                if (el && !el.tomselect) window.tomSelectInstances[id] = new TomSelect(el, {});
            });
        }

        function destroyTomSelects() {
            Object.values(window.tomSelectInstances || {}).forEach(ts => ts?.destroy());
            window.tomSelectInstances = {};
        }

        // --- Parent Menu Refresh ---
        function refreshParentMenuOptions() {
            return fetch('{{ route('panel.menus.index') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(r => {
                    if (!r.ok) throw new Error('Network error');
                    return r.json();
                })
                .then(data => {
                    if (data.parentMenus)['create_parent_id', 'edit_parent_id'].forEach(id => {
                        const sel = document.getElementById(id);
                        if (sel) {
                            updateSelectOptions(sel, data.parentMenus);
                            if (window.tomSelectInstances[id]) {
                                window.tomSelectInstances[id].destroy();
                                window.tomSelectInstances[id] = new TomSelect(sel, {});
                            }
                        }
                    });
                    return data;
                });
        }

        // Refresh DataTable after CRUD operations
        function refreshDataTable() {
            if (menusTable) {
                menusTable.ajax.reload(null, false); // Keep current page
            }
        }

        function updateSelectOptions(sel, opts) {
            while (sel.children.length > 1) sel.removeChild(sel.lastChild);
            Object.entries(opts).forEach(([id, name]) => {
                const o = document.createElement('option');
                o.value = id;
                o.textContent = name.replace(/└─\s/g, '').replace(/Parent\s-\s/g, '');
                sel.appendChild(o);
            });
        }

        // --- Edit Modal Filler ---
        function fillEditModal(menu) {
            const ids = ['edit_menu_id', 'edit_nama_menu', 'edit_slug', 'edit_route_name', 'edit_icon', 'edit_parent_id',
                'edit_urutan', 'edit_is_active'
            ];
            if (ids.some(id => !document.getElementById(id))) return;
            setValue('edit_menu_id', menu.id);
            setValue('edit_nama_menu', menu.nama_menu);
            setValue('edit_slug', menu.slug);
            setValue('edit_route_name', menu.route_name || '');
            setValue('edit_icon', menu.icon || '');
            setValue('edit_urutan', menu.urutan);
            setChecked('edit_is_active', menu.is_active == 1);
            setSelect('edit_parent_id', menu.parent_id, 'edit_parent_id');
            setRoles('edit_roles', (menu.roles || []).map(r => r.id));
            if (window.tomSelectInstances) Object.values(window.tomSelectInstances).forEach(ts => ts?.sync());
            showModal(document.getElementById('editMenuModal'));
        }

        // --- Utility Setters ---
        function setValue(id, val) {
            const el = document.getElementById(id);
            if (el) el.value = val;
        }

        function setText(id, val) {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        }

        function setChecked(id, val) {
            const el = document.getElementById(id);
            if (el) el.checked = !!val;
        }

        function setSelect(id, val, tsKey) {
            const ts = window.tomSelectInstances && window.tomSelectInstances[tsKey];
            if (ts) {
                ts.clear();
                if (val) ts.setValue(val.toString());
            } else {
                const el = document.getElementById(id);
                if (el) el.value = val || '';
            }
        }

        function setRoles(id, arr) {
            const ts = window.tomSelectInstances && window.tomSelectInstances[id];
            if (ts) {
                ts.clear();
                (arr || []).forEach(v => ts.addItem(v.toString()));
            } else {
                const el = document.getElementById(id);
                if (el) {
                    Array.from(el.options).forEach(o => o.selected = false);
                    (arr || []).forEach(v => {
                        const o = el.querySelector(`option[value="${v}"]`);
                        if (o) o.selected = true;
                    });
                }
            }
        }
    </script>
</x-layout.app>
