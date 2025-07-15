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
            <table class="table table-striped table-sm">
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
                    @forelse($menus as $menu)
                        <tr>
                            <td>{{ $menu->id }}</td>
                            <td><strong>{{ $menu->nama_menu }}</strong></td>
                            <td><code>{{ $menu->slug }}</code></td>
                            <td>
                                @if ($menu->parent)
                                    <span class="badge bg-secondary">{{ $menu->parent->nama_menu }}</span>
                                @else
                                    <span class="text-muted">Root</span>
                                @endif
                            </td>
                            <td>
                                @if ($menu->route_name)
                                    <code>{{ $menu->route_name }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($menu->icon)
                                    <i class="{{ $menu->icon }}"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $menu->urutan }}</td>
                            <td>
                                @if ($menu->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @foreach ($menu->roles as $role)
                                    <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('update-menus')
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="openEditModal({{ $menu->toJson() }})" title="Edit Menu">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endcan
                                    @can('delete-menus')
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="openDeleteModal({{ $menu->toJson() }})" title="Delete Menu">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No menus found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $menus->links() }}

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
        // --- DOM Ready ---
        document.addEventListener('DOMContentLoaded', () => {
            window.tomSelectInstances ??= {};
            if (window.TomSelect) setTimeout(initTomSelect, 100);
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
