<x-layout.app title="Menus Management - Panel Admin" :use-sidebar="true">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
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
                        <td>
                            @if ($menu->parent_id)
                                <span class="text-muted ms-3">└─</span>
                            @endif
                            <strong>{{ $menu->nama_menu }}</strong>
                        </td>
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

    {{-- Include Modal Components --}}
    @include('components.modals.menus.create')
    @include('components.modals.menus.update')
    @include('components.modals.menus.delete')

    {{-- Debug Script --}}
    <script>
        console.log('Menu management page loaded');

        // Check if Bootstrap is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
            console.log('Bootstrap Modal available:', typeof bootstrap !== 'undefined' && bootstrap.Modal);

            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap is not loaded!');
            }
        });

        function openEditModal(menu) {
            console.log('Edit modal called with menu:', menu);

            // Check if all required elements exist
            const requiredElements = [
                'edit_menu_id', 'edit_nama_menu', 'edit_slug',
                'edit_route_name', 'edit_icon', 'edit_parent_id',
                'edit_urutan', 'edit_is_active'
            ];

            const missingElements = requiredElements.filter(id => !document.getElementById(id));
            if (missingElements.length > 0) {
                console.error('Missing form elements:', missingElements);
                alert('Error: Some form elements are missing. Please check the console.');
                return;
            }

            // Populate form fields
            document.getElementById('edit_menu_id').value = menu.id;
            document.getElementById('edit_nama_menu').value = menu.nama_menu;
            document.getElementById('edit_slug').value = menu.slug;
            document.getElementById('edit_route_name').value = menu.route_name || '';
            document.getElementById('edit_icon').value = menu.icon || '';
            document.getElementById('edit_parent_id').value = menu.parent_id || '';
            document.getElementById('edit_urutan').value = menu.urutan;
            document.getElementById('edit_is_active').checked = menu.is_active == 1;

            // Clear and set roles
            const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
            roleCheckboxes.forEach(checkbox => {
                if (checkbox.id.startsWith('edit_role_')) {
                    checkbox.checked = false;
                }
            });

            if (menu.roles && menu.roles.length > 0) {
                menu.roles.forEach(role => {
                    const checkbox = document.getElementById(`edit_role_${role.id}`);
                    if (checkbox) {
                        checkbox.checked = true;
                    } else {
                        console.warn(`Role checkbox not found: edit_role_${role.id}`);
                    }
                });
            }

            // Show modal
            const modalElement = document.getElementById('editMenuModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else {
                console.error('Edit modal element not found');
                alert('Error: Edit modal not found. Please check permissions.');
            }
        }

        function openDeleteModal(menu) {
            console.log('Delete modal called with menu:', menu);

            // Check if modal exists
            const modalElement = document.getElementById('deleteMenuModal');
            if (!modalElement) {
                console.error('Delete modal element not found');
                alert('Error: Delete modal not found. Please check permissions.');
                return;
            }

            // Populate form fields
            const deleteMenuId = document.getElementById('delete_menu_id');
            const deleteMenuName = document.getElementById('delete_menu_name');
            const deleteMenuSlug = document.getElementById('delete_menu_slug');
            const deleteMenuRoute = document.getElementById('delete_menu_route');

            if (deleteMenuId) deleteMenuId.value = menu.id;
            if (deleteMenuName) deleteMenuName.textContent = menu.nama_menu;
            if (deleteMenuSlug) deleteMenuSlug.textContent = menu.slug;
            if (deleteMenuRoute) deleteMenuRoute.textContent = menu.route_name || '-';

            // Show modal
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    </script>
</x-layout.app>
