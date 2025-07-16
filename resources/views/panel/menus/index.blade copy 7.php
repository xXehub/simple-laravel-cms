<x-layout.app title="Menus Management - Panel Admin" :pakai-sidebar="true">
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            Panel Admin
                        </div>
                        <h2 class="page-title">
                            <i class="fas fa-bars me-2"></i>Menus Management
                        </h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        @can('create-menus')
                            <div class="btn-list">
                                <button type="button" class="btn btn-primary d-none d-sm-inline-block" 
                                        data-bs-toggle="modal" data-bs-target="#createMenuModal">
                                    <i class="fas fa-plus me-2"></i>Add Menu
                                </button>
                                <button type="button" class="btn btn-primary d-sm-none btn-icon" 
                                        data-bs-toggle="modal" data-bs-target="#createMenuModal" aria-label="Add Menu">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <x-alert.flash-messages />
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Menu List</h3>
                        <div class="card-actions">
                            <div class="d-flex">
                                <div class="me-2">
                                    <input type="search" class="form-control form-control-sm" 
                                           placeholder="Search menus..." id="customSearch">
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                            type="button" data-bs-toggle="dropdown">
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
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="menusTable" class="table table-vcenter card-table">
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
                                    <th class="w-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('components.modals.menus.create')
        @include('components.modals.menus.update')
        @include('components.modals.menus.delete')
    </div>


    <script>
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
        document.addEventListener('DOMContentLoaded', () => {
            window.tomSelectInstances ??= {};
            if (window.TomSelect) setTimeout(initTomSelect, 100);

            // Initialize DataTables with Tabler styling
            window.menusTable = $('#menusTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: 'rt<"d-flex justify-content-between align-items-center"<"d-flex align-items-center"l><"d-flex align-items-center"p>>',
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
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-muted" role="status"></div></div>',
                    search: "",
                    searchPlaceholder: "Search menus...",
                    lengthMenu: "Show _MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    emptyTable: "No menu data available",
                    zeroRecords: "No matching records found"
                }
            });

            // Custom search functionality
            $('#customSearch').on('keyup', function() {
                window.menusTable.search(this.value).draw();
            });

            // Filter functionality
            window.filterTable = function(type) {
                let searchTerm = '';
                switch(type) {
                    case 'active':
                        searchTerm = 'Active';
                        window.menusTable.column(7).search(searchTerm).draw();
                        break;
                    case 'inactive':
                        searchTerm = 'Inactive';
                        window.menusTable.column(7).search(searchTerm).draw();
                        break;
                    case 'parent':
                        searchTerm = '^-$'; // Match only "-" (no parent)
                        window.menusTable.column(3).search(searchTerm, true, false).draw();
                        break;
                    case 'child':
                        searchTerm = '^(?!-).*'; // Match anything except "-"
                        window.menusTable.column(3).search(searchTerm, true, false).draw();
                        break;
                    case 'all':
                    default:
                        window.menusTable.columns().search('').draw();
                        break;
                }
            };

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
        });
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
            if (window.menusTable) {
                window.menusTable.ajax.reload(null, false); // Keep current page
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
