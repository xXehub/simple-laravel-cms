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

    {{-- Tom Select CSS & JS --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    {{-- Main Script --}}
    <script>
        console.log('Menu management page loaded');
        
        let editRolesSelect;
        let createRolesSelect;

        // Check if Bootstrap is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
            console.log('Bootstrap Modal available:', typeof bootstrap !== 'undefined' && bootstrap.Modal);

            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap is not loaded!');
            }
            
            // Initialize Tom Select for role selects
            setTimeout(function() {
                // Initialize create roles select
                const createRolesElement = document.getElementById('create_roles');
                if (createRolesElement) {
                    createRolesSelect = new TomSelect('#create_roles', {
                        plugins: ['remove_button'],
                        placeholder: 'Select roles...',
                        maxOptions: null,
                        create: false,
                        sortField: 'text'
                    });
                    console.log('Create roles Tom Select initialized');
                }
                
                // Initialize edit roles select
                const editRolesElement = document.getElementById('edit_roles');
                if (editRolesElement) {
                    editRolesSelect = new TomSelect('#edit_roles', {
                        plugins: ['remove_button'],
                        placeholder: 'Select roles...',
                        maxOptions: null,
                        create: false,
                        sortField: 'text'
                    });
                    console.log('Edit roles Tom Select initialized');
                }
            }, 100); // Small delay to ensure DOM is fully ready
        });

        function openEditModal(menu) {
            console.log('Edit modal called with menu:', menu);

            // Check if all required elements exist
            const requiredElements = [
                'edit_menu_id', 'edit_nama_menu', 'edit_slug',
                'edit_route_name', 'edit_icon', 'edit_parent_id',
                'edit_urutan', 'edit_is_active'
            ];

            // Debug: Check each element individually
            console.log('Checking form elements:');
            requiredElements.forEach(id => {
                const element = document.getElementById(id);
                console.log(`${id}:`, element ? 'FOUND' : 'MISSING');
            });

            const missingElements = requiredElements.filter(id => !document.getElementById(id));
            if (missingElements.length > 0) {
                console.error('Missing form elements:', missingElements);
                
                // Debug: Check if modal exists
                const modal = document.getElementById('editMenuModal');
                console.log('Edit modal exists:', modal ? 'YES' : 'NO');
                
                if (modal) {
                    console.log('Modal HTML:', modal.innerHTML.substring(0, 200) + '...');
                }
                
                alert('Error: Some form elements are missing. Please check console.');
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

            // Set roles using Tom Select
            if (typeof editRolesSelect !== 'undefined' && editRolesSelect) {
                // Clear current selection
                editRolesSelect.clear();

                // Set selected roles
                if (menu.roles && menu.roles.length > 0) {
                    menu.roles.forEach(role => {
                        editRolesSelect.addItem(role.id.toString());
                    });
                }
            }

            // Show modal
            const modalElement = document.getElementById('editMenuModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
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
