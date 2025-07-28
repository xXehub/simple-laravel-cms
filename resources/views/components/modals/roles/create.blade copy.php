@props(['permissions'])

<!-- Create Role Modal -->
@can('create-roles')
    <div class="modal modal-blur fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.roles.store') }}" id="createRoleForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_name" name="name" required>
                            <div class="invalid-feedback" id="create_name_error"></div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">Permissions</label>
                                <div class="btn-list">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        id="create_select_all_permissions">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check me-1"
                                            width="16" height="16" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                        Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="create_deselect_all_permissions">
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
                            
                            <div class="accordion accordion-flush" id="create_permissions_accordion">
                                @foreach ($permissions->groupBy('group') as $group => $groupPermissions)
                                    <div class="accordion-item">
                                        <div class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#create_collapse_{{ Str::slug($group) }}"
                                                aria-expanded="false">
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <span class="fw-medium">{{ ucfirst($group) }} Permissions</span>
                                                    <small class="text-muted">
                                                        ({{ $groupPermissions->count() }} {{ Str::plural('permission', $groupPermissions->count()) }})
                                                    </small>
                                                </div>
                                            </button>
                                        </div>
                                        <div id="create_collapse_{{ Str::slug($group) }}"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#create_permissions_accordion">
                                            <div class="accordion-body pt-2">
                                                <div class="d-flex justify-content-end mb-3">
                                                    <div class="btn-list">
                                                        <button type="button"
                                                            class="btn btn-sm btn-ghost-primary create-select-group"
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
                                                            class="btn btn-sm btn-ghost-secondary create-deselect-group"
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
                                                            <label class="form-check">
                                                                <input
                                                                    class="form-check-input create-permission-checkbox create-group-{{ Str::slug($group) }}"
                                                                    type="checkbox"
                                                                    id="create_permission_{{ $permission->id }}"
                                                                    name="permissions[]" value="{{ $permission->name }}">
                                                                <span class="form-check-label">
                                                                    {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                                </span>
                                                            </label>
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
                            Create Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Clear form when create modal is closed
        document.addEventListener('DOMContentLoaded', function() {
            const createModal = document.getElementById('createRoleModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createRoleForm').reset();
                    // Clear validation errors
                    const errorElements = createModal.querySelectorAll('.invalid-feedback');
                    errorElements.forEach(el => {
                        el.textContent = '';
                        el.previousElementSibling.classList.remove('is-invalid');
                    });
                });
            }

            // Global select all permissions for create modal
            document.getElementById('create_select_all_permissions')?.addEventListener('click', function() {
                document.querySelectorAll('.create-permission-checkbox').forEach(checkbox => {
                    checkbox.checked = true;
                });
            });

            // Global deselect all permissions for create modal
            document.getElementById('create_deselect_all_permissions')?.addEventListener('click', function() {
                document.querySelectorAll('.create-permission-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
            });

            // Group-specific select all for create modal
            document.querySelectorAll('.create-select-group').forEach(button => {
                button.addEventListener('click', function() {
                    const group = this.getAttribute('data-group');
                    document.querySelectorAll(`.create-group-${group}`).forEach(checkbox => {
                        checkbox.checked = true;
                    });
                });
            });

            // Group-specific deselect all for create modal
            document.querySelectorAll('.create-deselect-group').forEach(button => {
                button.addEventListener('click', function() {
                    const group = this.getAttribute('data-group');
                    document.querySelectorAll(`.create-group-${group}`).forEach(checkbox => {
                        checkbox.checked = false;
                    });
                });
            });
        });
    </script>
@endcan