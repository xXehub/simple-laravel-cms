@props(['permissions', 'role'])

<!-- Edit Role Modal -->
@can('update-roles')
    <div class="modal modal-blur fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.roles.update', $role->id ?? ':id') }}" id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_role_id" value="{{ $role->id ?? '' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback" id="edit_name_error"></div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">Permissions</label>
                                <div class="btn-list">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        id="edit_select_all_permissions">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check me-1"
                                            width="16" height="16" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                        Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="edit_deselect_all_permissions">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x me-1"
                                            width="16" height="16" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M18 6l-12 12" />
                                            <path d="M6 6l12 12" />
                                        </svg>
                                        Deselect All
                                    </button>
                                </div>
                            </div>
                            
                            <div class="accordion accordion-inverted accordion-plus" id="edit_permissions_accordion">
                                @foreach ($permissions->groupBy('group') as $group => $groupPermissions)
                                    <div class="accordion-item">
                                        <div class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#edit_collapse_{{ Str::slug($group) }}"
                                                aria-expanded="false">
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <span class="fw-medium">{{ ucfirst($group) }} Permissions</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success edit-active-count-{{ Str::slug($group) }}" style="display: none;">
                                                            <span class="edit-active-{{ Str::slug($group) }}">0</span> active
                                                        </span>
                                                        <span class="badge bg-default text-default-fg">
                                                            {{ $groupPermissions->count() }} {{ Str::plural('permission', $groupPermissions->count()) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="accordion-button-toggle accordion-button-toggle-plus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-1">
                                                        <path d="M12 5l0 14"></path>
                                                        <path d="M5 12l14 0"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                        <div id="edit_collapse_{{ Str::slug($group) }}"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#edit_permissions_accordion">
                                            <div class="accordion-body pt-2">
                                                <div class="d-flex justify-content-end mb-3">
                                                    <div class="btn-list">
                                                        <button type="button"
                                                            class="btn btn-sm btn-ghost-primary edit-select-group"
                                                            data-group="{{ Str::slug($group) }}"
                                                            title="Select all {{ $group }} permissions">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon me-1" width="16" height="16" viewBox="0 0 24 24" 
                                                                stroke-width="2" stroke="currentColor" fill="none" 
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M5 12l5 5l10 -10" />
                                                            </svg>
                                                            Select All
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-ghost-secondary edit-deselect-group"
                                                            data-group="{{ Str::slug($group) }}"
                                                            title="Deselect all {{ $group }} permissions">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon me-1" width="16" height="16" viewBox="0 0 24 24" 
                                                                stroke-width="2" stroke="currentColor" fill="none" 
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M18 6l-12 12" />
                                                                <path d="M6 6l12 12" />
                                                            </svg>
                                                            Deselect All
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    @foreach ($groupPermissions as $permission)
                                                        <div class="col-md-6">
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" 
                                                                    class="form-check-input edit-permission-checkbox edit-group-{{ Str::slug($group) }}" 
                                                                    id="edit_permission_{{ $permission->id }}" 
                                                                    name="permissions[]" 
                                                                    value="{{ $permission->name }}">
                                                                <label class="form-check-label" for="edit_permission_{{ $permission->id }}">
                                                                    {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" 
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                                <circle cx="12" cy="14" r="2"/>
                                <polyline points="14,4 14,8 8,8 8,4"/>
                            </svg>
                            Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update active permission count for each group
            function updateActiveCount(group) {
                const activeCheckboxes = document.querySelectorAll(`.edit-group-${group}:checked`);
                const countElement = document.querySelector(`.edit-active-${group}`);
                const badgeElement = document.querySelector(`.edit-active-count-${group}`);
                
                if (countElement && badgeElement) {
                    const count = activeCheckboxes.length;
                    countElement.textContent = count;
                    
                    if (count > 0) {
                        badgeElement.style.display = 'inline-flex';
                        badgeElement.className = count === document.querySelectorAll(`.edit-group-${group}`).length 
                            ? 'badge bg-green-lt edit-active-count-' + group 
                            : 'badge bg-azure-lt edit-active-count-' + group;
                    } else {
                        badgeElement.style.display = 'none';
                    }
                }
            }

            // Function to update all group counts
            function updateAllCounts() {
                const groups = [];
                document.querySelectorAll('[data-group]').forEach(btn => {
                    const group = btn.getAttribute('data-group');
                    if (!groups.includes(group)) {
                        groups.push(group);
                        updateActiveCount(group);
                    }
                });
            }

            // Listen to checkbox changes
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('edit-permission-checkbox')) {
                    const classes = Array.from(e.target.classList);
                    const groupClass = classes.find(cls => cls.startsWith('edit-group-'));
                    if (groupClass) {
                        const group = groupClass.replace('edit-group-', '');
                        updateActiveCount(group);
                    }
                }
            });

            // Clear form when edit modal is closed
            const editModal = document.getElementById('editRoleModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('editRoleForm').reset();
                    // Clear validation errors
                    const errorElements = editModal.querySelectorAll('.invalid-feedback');
                    errorElements.forEach(el => {
                        el.textContent = '';
                        el.previousElementSibling.classList.remove('is-invalid');
                    });
                    // Hide all badges
                    document.querySelectorAll('[class*="edit-active-count-"]').forEach(badge => {
                        badge.style.display = 'none';
                    });
                });

                // Update counts when modal is shown (for edit mode)
                editModal.addEventListener('shown.bs.modal', function() {
                    setTimeout(updateAllCounts, 100);
                });
            }

            // Global select all permissions for edit modal
            document.getElementById('edit_select_all_permissions')?.addEventListener('click', function() {
                document.querySelectorAll('.edit-permission-checkbox').forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateAllCounts();
            });

            // Global deselect all permissions for edit modal
            document.getElementById('edit_deselect_all_permissions')?.addEventListener('click', function() {
                document.querySelectorAll('.edit-permission-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateAllCounts();
            });

            // Group-specific select all for edit modal
            document.querySelectorAll('.edit-select-group').forEach(button => {
                button.addEventListener('click', function() {
                    const group = this.getAttribute('data-group');
                    document.querySelectorAll(`.edit-group-${group}`).forEach(checkbox => {
                        checkbox.checked = true;
                    });
                    updateActiveCount(group);
                });
            });

            // Group-specific deselect all for edit modal
            document.querySelectorAll('.edit-deselect-group').forEach(button => {
                button.addEventListener('click', function() {
                    const group = this.getAttribute('data-group');
                    document.querySelectorAll(`.edit-group-${group}`).forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    updateActiveCount(group);
                });
            });
        });
    </script>
@endcan