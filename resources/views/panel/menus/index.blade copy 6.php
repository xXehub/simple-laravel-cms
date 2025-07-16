<x-layout.app title="Menus Management - Panel Admin" :pakai-sidebar="true">
    <div class="page-wrapper">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-bars me-2"></i>Menus Management
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                @can('create-menus')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuModal">
                        <i class="fas fa-plus me-2"></i>Add Menu
                    </button>
                @endcan
            </div>
        </div>

        <x-alert.flash-messages />

        <div class="table-responsive">
            <table id="menusTable" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Menu</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Route</th>
                        <th>Icon</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via DataTables AJAX -->
                </tbody>
            </table>
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
        @if(session('success'))
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
            
            // Initialize DataTables
            window.menusTable = $('#menusTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('panel.menus.index') }}',
                    type: 'GET'
                },
                columns: [
                    { data: 0, name: 'id', width: '5%' },
                    { data: 1, name: 'nama_menu', width: '15%' },
                    { data: 2, name: 'slug', width: '15%' },
                    { data: 3, name: 'parent_id', width: '10%', orderable: false },
                    { data: 4, name: 'route_name', width: '12%' },
                    { data: 5, name: 'icon', width: '8%', orderable: false },
                    { data: 6, name: 'urutan', width: '8%' },
                    { data: 7, name: 'is_active', width: '8%', orderable: false },
                    { data: 8, name: 'roles', width: '12%', orderable: false },
                    { data: 9, name: 'actions', width: '7%', orderable: false, searchable: false }
                ],
                order: [[6, 'asc'], [0, 'asc']],
                pageLength: 15,
                lengthMenu: [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
                language: {
                    processing: "Memuat data...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    emptyTable: "Tidak ada data menu",
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                }
            });

            document.addEventListener('click', e => {
                if (e.target.closest('[data-bs-dismiss="modal"]')) hideModal(e.target.closest('.modal'));
                if (e.target.classList.contains('modal-backdrop')) document.querySelectorAll('.modal.show').forEach(hideModal);
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
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
            .then(data => {
                if (data.parentMenus) ['create_parent_id', 'edit_parent_id'].forEach(id => {
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
