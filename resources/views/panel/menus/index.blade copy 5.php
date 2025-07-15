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
        function showModal(modalElement) {
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalElement).modal('show');
                return;
            }
            if (window.bootstrap && bootstrap.Modal) {
                (new bootstrap.Modal(modalElement)).show();
                return;
            }
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            modalElement.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
            if (!document.querySelector('.modal-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
        }

        function hideModal(modalElement) {
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalElement).modal('hide');
                return;
            }
            if (window.bootstrap && bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
                return;
            }
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        }
        window.openEditModal = function(menu) {
            refreshParentMenuOptions().then(() => setTimeout(() => populateEditModal(menu), 300)).catch(() =>
                populateEditModal(menu));
        };
        window.openDeleteModal = function(menu) {
            const modalElement = document.getElementById('deleteMenuModal');
            if (!modalElement) return;
            document.getElementById('delete_menu_id').value = menu.id;
            document.getElementById('delete_menu_name').textContent = menu.nama_menu;
            document.getElementById('delete_menu_slug').textContent = menu.slug;
            document.getElementById('delete_menu_route').textContent = menu.route_name || '-';
            showModal(modalElement);
        };
        document.addEventListener('DOMContentLoaded', function() {
            if (!window.tomSelectInstances) window.tomSelectInstances = {};
            if (typeof TomSelect !== 'undefined') setTimeout(initializeTomSelect, 100);
            document.addEventListener('click', function(e) {
                if (e.target.matches('[data-bs-dismiss="modal"]') || e.target.closest(
                        '[data-bs-dismiss="modal"]')) {
                    const modal = e.target.closest('.modal');
                    if (modal) hideModal(modal);
                }
                if (e.target.classList.contains('modal-backdrop')) {
                    document.querySelectorAll('.modal.show').forEach(hideModal);
                }
            });
            // Validation error auto-reopen modal
            @if ($errors->any())
                @if (old('_method') === 'PUT')
                    const editModal = document.getElementById('editMenuModal');
                    if (editModal) {
                        document.getElementById('edit_menu_id').value = '{{ old('id') }}';
                        document.getElementById('edit_nama_menu').value = '{{ old('nama_menu') }}';
                        document.getElementById('edit_slug').value = '{{ old('slug') }}';
                        document.getElementById('edit_route_name').value = '{{ old('route_name') }}';
                        document.getElementById('edit_icon').value = '{{ old('icon') }}';
                        document.getElementById('edit_urutan').value = '{{ old('urutan') }}';
                        document.getElementById('edit_is_active').checked =
                            {{ old('is_active') ? 'true' : 'false' }};
                        if (window.tomSelectInstances && window.tomSelectInstances['edit_parent_id']) {
                            setTimeout(() => window.tomSelectInstances['edit_parent_id'].setValue(
                                '{{ old('parent_id') }}'), 200);
                        } else {
                            document.getElementById('edit_parent_id').value = '{{ old('parent_id') }}';
                        }
                        if (window.tomSelectInstances && window.tomSelectInstances['edit_roles']) {
                            setTimeout(() => {
                                window.tomSelectInstances['edit_roles'].clear();
                                @if (old('roles'))
                                    @foreach (old('roles') as $roleId)
                                        window.tomSelectInstances['edit_roles'].addItem(
                                            '{{ $roleId }}');
                                    @endforeach
                                @endif
                            }, 200);
                        }
                        showModal(editModal);
                    }
                @else
                    const createModal = document.getElementById('createMenuModal');
                    if (createModal) showModal(createModal);
                @endif
            @endif
        });

        function initializeTomSelect() {
            destroyTomSelects();
            [
                ['create_roles', {}],
                ['edit_roles', {}],
                ['create_parent_id', {}],
                ['edit_parent_id', {}]
            ].forEach(([id, opts]) => {
                const el = document.getElementById(id);
                if (el && !el.tomselect) window.tomSelectInstances[id] = new TomSelect(el, opts);
            });
        }

        function destroyTomSelects() {
            if (!window.tomSelectInstances) return;
            Object.values(window.tomSelectInstances).forEach(ts => ts && ts.destroy());
            window.tomSelectInstances = {};
        }

        function refreshParentMenuOptions() {
            return fetch('{{ route('panel.menus.index') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.parentMenus) {
                        ['create_parent_id', 'edit_parent_id'].forEach(id => {
                            const select = document.getElementById(id);
                            if (select) {
                                updateSelectOptions(select, data.parentMenus);
                                if (window.tomSelectInstances[id]) {
                                    window.tomSelectInstances[id].destroy();
                                    window.tomSelectInstances[id] = new TomSelect(select, {});
                                }
                            }
                        });
                    }
                    return data;
                });
        }

        function updateSelectOptions(selectElement, options) {
            while (selectElement.children.length > 1) selectElement.removeChild(selectElement.lastChild);
            Object.entries(options).forEach(([id, name]) => {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = name.replace(/└─\s/g, '').replace(/Parent\s-\s/g, '');
                selectElement.appendChild(option);
            });
        }

        function populateEditModal(menu) {
            const ids = [
                'edit_menu_id', 'edit_nama_menu', 'edit_slug',
                'edit_route_name', 'edit_icon', 'edit_parent_id',
                'edit_urutan', 'edit_is_active'
            ];
            if (ids.some(id => !document.getElementById(id))) return;
            document.getElementById('edit_menu_id').value = menu.id;
            document.getElementById('edit_nama_menu').value = menu.nama_menu;
            document.getElementById('edit_slug').value = menu.slug;
            document.getElementById('edit_route_name').value = menu.route_name || '';
            document.getElementById('edit_icon').value = menu.icon || '';
            document.getElementById('edit_urutan').value = menu.urutan;
            document.getElementById('edit_is_active').checked = menu.is_active == 1;
            setTimeout(() => {
                const ts = window.tomSelectInstances && window.tomSelectInstances['edit_parent_id'];
                if (ts) {
                    ts.clear();
                    if (menu.parent_id && menu.parent_id !== null) {
                        const option = document.querySelector(`#edit_parent_id option[value="${menu.parent_id}"]`);
                        if (option) ts.setValue(menu.parent_id.toString());
                        else {
                            ts.destroy();
                            const el = document.getElementById('edit_parent_id');
                            window.tomSelectInstances['edit_parent_id'] = new TomSelect(el, {});
                            setTimeout(() => window.tomSelectInstances['edit_parent_id'].setValue(menu.parent_id
                                .toString()), 100);
                        }
                    }
                } else {
                    const parentSelect = document.getElementById('edit_parent_id');
                    if (parentSelect) parentSelect.value = menu.parent_id || '';
                }
            }, 200);
            const tsRoles = window.tomSelectInstances && window.tomSelectInstances['edit_roles'];
            if (tsRoles) {
                tsRoles.clear();
                if (menu.roles && menu.roles.length > 0) menu.roles.forEach(role => tsRoles.addItem(role.id.toString()));
            } else {
                const rolesSelect = document.getElementById('edit_roles');
                if (rolesSelect && menu.roles) {
                    Array.from(rolesSelect.options).forEach(option => option.selected = false);
                    menu.roles.forEach(role => {
                        const option = rolesSelect.querySelector(`option[value="${role.id}"]`);
                        if (option) option.selected = true;
                    });
                }
            }
            if (window.tomSelectInstances) Object.values(window.tomSelectInstances).forEach(ts => ts && ts.sync());
            const modalElement = document.getElementById('editMenuModal');
            if (modalElement) showModal(modalElement);
        }
    </script>
</x-layout.app>
