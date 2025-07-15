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
                            <td>
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
    </div>
    {{-- TomSelect Initialization Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing TomSelect');

            // Initialize tomSelectInstances object
            if (!window.tomSelectInstances) {
                window.tomSelectInstances = {};
            }

            // Check if TomSelect is available
            if (typeof TomSelect === 'undefined') {
                console.error('TomSelect is not loaded. Please check if the library is properly included.');
                return;
            }

            // Initialize TomSelect after a short delay to ensure DOM is fully ready
            setTimeout(function() {
                initializeTomSelect();
            }, 100);

            // Modal event listeners
            const createModal = document.getElementById('createMenuModal');
            const editModal = document.getElementById('editMenuModal');

            if (createModal) {
                createModal.addEventListener('shown.bs.modal', function() {
                    console.log('Create Menu Modal Shown');
                });

                createModal.addEventListener('hidden.bs.modal', function() {
                    console.log('Create Menu Modal Hidden');
                });
            }

            if (editModal) {
                editModal.addEventListener('shown.bs.modal', function() {
                    console.log('Edit Menu Modal Shown');
                });

                editModal.addEventListener('hidden.bs.modal', function() {
                    console.log('Edit Menu Modal Hidden');
                });
            }

            // Add event listeners for modal close buttons (Tabler.io compatible)
            document.addEventListener('click', function(e) {
                if (e.target.matches('[data-bs-dismiss="modal"]') || e.target.closest('[data-bs-dismiss="modal"]')) {
                    const modal = e.target.closest('.modal');
                    if (modal) {
                        closeModal(modal);
                    }
                }
                
                // Handle backdrop clicks
                if (e.target.classList.contains('modal-backdrop')) {
                    const modals = document.querySelectorAll('.modal.show');
                    modals.forEach(modal => closeModal(modal));
                }
            });

            // Add form submission handlers
            const createForm = document.getElementById('createMenuForm');
            const editForm = document.getElementById('editMenuForm');

            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    console.log('Create form submitted');
                    console.log('Form action:', createForm.action);
                    console.log('Form method:', createForm.method);
                    
                    // Log form data for debugging
                    const formData = new FormData(createForm);
                    console.log('Form data:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }
                    // Let the form submit naturally
                });
            }

            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    console.log('Edit form submitted');
                    console.log('Form action:', editForm.action);
                    console.log('Form method:', editForm.method);
                    
                    // Log form data for debugging
                    const formData = new FormData(editForm);
                    console.log('Form data:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }
                    // Let the form submit naturally
                });
            }

            // Check for validation errors and reopen modal if needed
            @if ($errors->any())
                @if (old('_method') === 'PUT')
                    // Reopen edit modal if there were validation errors on update
                    console.log('Validation errors detected for edit form, attempting to reopen modal');
                    const editModal = document.getElementById('editMenuModal');
                    if (editModal) {
                        // Populate form with old values
                        document.getElementById('edit_menu_id').value = '{{ old('id') }}';
                        document.getElementById('edit_nama_menu').value = '{{ old('nama_menu') }}';
                        document.getElementById('edit_slug').value = '{{ old('slug') }}';
                        document.getElementById('edit_route_name').value = '{{ old('route_name') }}';
                        document.getElementById('edit_icon').value = '{{ old('icon') }}';
                        document.getElementById('edit_urutan').value = '{{ old('urutan') }}';
                        document.getElementById('edit_is_active').checked = {{ old('is_active') ? 'true' : 'false' }};

                        // Set parent using TomSelect if available
                        if (window.tomSelectInstances && window.tomSelectInstances['edit_parent_id']) {
                            setTimeout(() => {
                                window.tomSelectInstances['edit_parent_id'].setValue('{{ old('parent_id') }}');
                            }, 200);
                        } else {
                            document.getElementById('edit_parent_id').value = '{{ old('parent_id') }}';
                        }

                        // Set roles using TomSelect if available
                        if (window.tomSelectInstances && window.tomSelectInstances['edit_roles']) {
                            setTimeout(() => {
                                window.tomSelectInstances['edit_roles'].clear();
                                @if (old('roles'))
                                    @foreach (old('roles') as $roleId)
                                        window.tomSelectInstances['edit_roles'].addItem('{{ $roleId }}');
                                    @endforeach
                                @endif
                            }, 200);
                        }

                        // Try jQuery first (most common with Tabler.io)
                        if (typeof $ !== 'undefined' && $.fn.modal) {
                            $(editModal).modal('show');
                        } 
                        // Then try Bootstrap 5
                        else if (window.bootstrap && bootstrap.Modal) {
                            const modal = new bootstrap.Modal(editModal);
                            modal.show();
                        } 
                        // Fallback for Tabler.io
                        else {
                            editModal.classList.add('show');
                            editModal.style.display = 'block';
                            editModal.setAttribute('aria-hidden', 'false');
                            document.body.classList.add('modal-open');
                            
                            // Add backdrop
                            if (!document.querySelector('.modal-backdrop')) {
                                const backdrop = document.createElement('div');
                                backdrop.className = 'modal-backdrop fade show';
                                document.body.appendChild(backdrop);
                            }
                        }
                    }
                @else
                    // Reopen create modal if there were validation errors on store
                    console.log('Validation errors detected for create form, attempting to reopen modal');
                    const createModal = document.getElementById('createMenuModal');
                    if (createModal) {
                        // Try jQuery first (most common with Tabler.io)
                        if (typeof $ !== 'undefined' && $.fn.modal) {
                            $(createModal).modal('show');
                        } 
                        // Then try Bootstrap 5
                        else if (window.bootstrap && bootstrap.Modal) {
                            const modal = new bootstrap.Modal(createModal);
                            modal.show();
                        } 
                        // Fallback for Tabler.io
                        else {
                            createModal.classList.add('show');
                            createModal.style.display = 'block';
                            createModal.setAttribute('aria-hidden', 'false');
                            document.body.classList.add('modal-open');
                            
                            // Add backdrop
                            if (!document.querySelector('.modal-backdrop')) {
                                const backdrop = document.createElement('div');
                                backdrop.className = 'modal-backdrop fade show';
                                document.body.appendChild(backdrop);
                            }
                        }
                    }
                @endif
            @endif
        });

        // Helper function to close modal (Tabler.io compatible)
        function closeModal(modalElement) {
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalElement).modal('hide');
            } 
            else if (window.bootstrap && bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
            } 
            else {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                modalElement.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
                
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }
        }

        function initializeTomSelect() {
            try {
                console.log('Initializing TomSelect instances...');

                // Destroy existing instances first
                destroyTomSelects();

                // Initialize Create Roles Select
                const createRolesElement = document.getElementById('create_roles');
                if (createRolesElement && !createRolesElement.tomselect) {
                    window.tomSelectInstances['create_roles'] = new TomSelect(createRolesElement, {
                        plugins: [], // Removed problematic "remove_button" plugin
                    });
                    console.log('Create roles TomSelect initialized');
                }

                // Initialize Edit Roles Select
                const editRolesElement = document.getElementById('edit_roles');
                if (editRolesElement && !editRolesElement.tomselect) {
                    window.tomSelectInstances['edit_roles'] = new TomSelect(editRolesElement, {
                        plugins: [], // Removed problematic "remove_button" plugin
                    });
                    console.log('Edit roles TomSelect initialized');
                }

                // Initialize Create Parent Select
                const createParentElement = document.getElementById('create_parent_id');
                if (createParentElement && !createParentElement.tomselect) {
                    window.tomSelectInstances['create_parent_id'] = new TomSelect(createParentElement, {
                        plugins: [],
                    });
                    console.log('Create parent TomSelect initialized');
                }

                // Initialize Edit Parent Select
                const editParentElement = document.getElementById('edit_parent_id');
                if (editParentElement && !editParentElement.tomselect) {
                    window.tomSelectInstances['edit_parent_id'] = new TomSelect(editParentElement, {
                        plugins: [],
                    });
                    console.log('Edit parent TomSelect initialized');
                }

                console.log('All TomSelect instances initialized successfully');
                console.log('Current tomSelectInstances:', Object.keys(window.tomSelectInstances));
            } catch (error) {
                console.error('Error initializing TomSelect:', error);
            }
        }

        function destroyTomSelects() {
            console.log('Destroying Tom Select instances...');
            if (window.tomSelectInstances) {
                for (let key in window.tomSelectInstances) {
                    if (window.tomSelectInstances[key]) {
                        window.tomSelectInstances[key].destroy();
                        delete window.tomSelectInstances[key];
                    }
                }
            }
        }

        function openEditModal(menu) {
            console.log('Opening edit modal for menu:', menu);

            // Check if all required elements exist
            const requiredElements = [
                'edit_menu_id', 'edit_nama_menu', 'edit_slug',
                'edit_route_name', 'edit_icon', 'edit_parent_id',
                'edit_urutan', 'edit_is_active'
            ];

            const missingElements = requiredElements.filter(id => !document.getElementById(id));
            if (missingElements.length > 0) {
                console.error('Missing form elements:', missingElements);
                alert('Error: Some form elements are missing. Please check if edit modal is properly included.');
                return;
            }

            // Populate form fields
            document.getElementById('edit_menu_id').value = menu.id;
            document.getElementById('edit_nama_menu').value = menu.nama_menu;
            document.getElementById('edit_slug').value = menu.slug;
            document.getElementById('edit_route_name').value = menu.route_name || '';
            document.getElementById('edit_icon').value = menu.icon || '';
            document.getElementById('edit_urutan').value = menu.urutan;
            document.getElementById('edit_is_active').checked = menu.is_active == 1;

            // Set parent menu using Tom Select
            if (window.tomSelectInstances && window.tomSelectInstances['edit_parent_id']) {
                console.log('Setting parent value via TomSelect:', menu.parent_id || '');
                window.tomSelectInstances['edit_parent_id'].clear();
                if (menu.parent_id) {
                    window.tomSelectInstances['edit_parent_id'].setValue(menu.parent_id.toString());
                    console.log('Parent value set successfully');
                } else {
                    console.log('No parent_id to set');
                }
            } else {
                console.log('Tom Select edit_parent_id not found, using fallback');
                // Fallback to standard DOM manipulation
                const parentSelect = document.getElementById('edit_parent_id');
                if (parentSelect) {
                    parentSelect.value = menu.parent_id || '';
                    console.log('Parent value set via fallback:', parentSelect.value);
                }
            }

            // Set roles using Tom Select
            if (window.tomSelectInstances && window.tomSelectInstances['edit_roles']) {
                console.log('Setting roles:', menu.roles);
                // Clear current selection
                window.tomSelectInstances['edit_roles'].clear();

                // Set selected roles
                if (menu.roles && menu.roles.length > 0) {
                    menu.roles.forEach(role => {
                        console.log('Adding role:', role.id.toString());
                        window.tomSelectInstances['edit_roles'].addItem(role.id.toString());
                    });
                }
            } else {
                console.log('Tom Select edit_roles not found, using fallback');
                // Fallback to standard DOM manipulation
                const rolesSelect = document.getElementById('edit_roles');
                if (rolesSelect && menu.roles) {
                    // Clear all selections first
                    Array.from(rolesSelect.options).forEach(option => {
                        option.selected = false;
                    });

                    // Set selected roles
                    menu.roles.forEach(role => {
                        const option = rolesSelect.querySelector(`option[value="${role.id}"]`);
                        if (option) {
                            option.selected = true;
                        }
                    });
                }
            }

            // Sync all Tom Select instances to ensure data is properly displayed
            if (window.tomSelectInstances) {
                console.log('Syncing Tom Select instances:', Object.keys(window.tomSelectInstances));
                for (let key in window.tomSelectInstances) {
                    if (window.tomSelectInstances[key]) {
                        window.tomSelectInstances[key].sync();
                    }
                }
            }

            // Show modal
            const modalElement = document.getElementById('editMenuModal');
            if (modalElement) {
                // Try jQuery first (most common with Tabler.io)
                if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modalElement).modal('show');
                } 
                // Then try Bootstrap 5
                else if (window.bootstrap && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } 
                // Fallback for Tabler.io
                else {
                    modalElement.classList.add('show');
                    modalElement.style.display = 'block';
                    modalElement.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('modal-open');
                    
                    // Add backdrop
                    if (!document.querySelector('.modal-backdrop')) {
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                }
            } else {
                console.error('Edit modal element not found');
                alert('Error: Edit modal not found. Please check if it is properly included.');
            }
        }

        function openDeleteModal(menu) {
            console.log('Opening delete modal for menu:', menu);

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
            if (typeof $ !== 'undefined' && $.fn.modal) {
                // jQuery Bootstrap modal (most common in Tabler.io)
                $(modalElement).modal('show');
            } 
            else if (window.bootstrap && bootstrap.Modal) {
                // Bootstrap 5 modal
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            } 
            else {
                // Fallback for Tabler.io
                modalElement.classList.add('show');
                modalElement.style.display = 'block';
                modalElement.setAttribute('aria-hidden', 'false');
                document.body.classList.add('modal-open');
                
                // Add backdrop
                if (!document.querySelector('.modal-backdrop')) {
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                }
            }
        }
    </script>
</x-layout.app>
