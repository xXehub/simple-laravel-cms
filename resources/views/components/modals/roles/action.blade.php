<!-- Role Action Buttons Component -->
<div class="btn-list flex-nowrap">
    @can('update-roles')
        <button type="button" class="btn btn-warning btn-sm" style="padding: 4px 8px; font-size: 12px;"
            onclick="editRole({{ $role->id }})" data-bs-toggle="modal" data-bs-target="#editRoleModal">
            <i class="fas fa-edit"></i>
        </button>
    @endcan
    @can('delete-roles')
        @if ($role->name !== 'superadmin')
            <button type="button" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 12px;"
                onclick="confirmDeleteRole({{ $role->id }}, '{{ $role->name }}')">
                <i class="fas fa-trash"></i>
            </button>
        @endif
    @endcan
</div>

<script>
    // Global confirmation modal handler
    if (typeof window.showConfirmationModal === 'undefined') {
        window.showConfirmationModal = function(options) {
            const {
                title = 'Konfirmasi',
                message = 'Apakah Anda yakin?',
                itemName = null,
                confirmText = 'Ya, Hapus',
                confirmClass = 'btn-danger',
                onConfirm = null
            } = options;

            // Set modal content
            document.getElementById('confirmationTitle').textContent = title;
            document.getElementById('confirmationMessage').textContent = message;
            
            const detailsDiv = document.getElementById('confirmationDetails');
            const itemNameSpan = document.getElementById('confirmationItemName');
            
            if (itemName) {
                itemNameSpan.textContent = itemName;
                detailsDiv.style.display = 'block';
            } else {
                detailsDiv.style.display = 'none';
            }
            
            const confirmBtn = document.getElementById('confirmationYesBtn');
            confirmBtn.textContent = confirmText;
            confirmBtn.className = `btn w-100 ${confirmClass}`;
            
            // Remove previous event listeners
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            // Add new event listener
            if (onConfirm) {
                newConfirmBtn.addEventListener('click', function() {
                    onConfirm();
                    // Close modal
                    const modal = document.getElementById('confirmationModal');
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                });
            }
            
            // Show modal
            const modal = document.getElementById('confirmationModal');
            modal.classList.add('show');
            modal.style.display = 'block';
            document.body.classList.add('modal-open');
            
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
            
            // Handle close events
            modal.addEventListener('click', function(e) {
                if (e.target.matches('[data-bs-dismiss="modal"]') || e.target === modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
            });
        };
    }

    // Global function for delete role confirmation
    if (typeof window.confirmDeleteRole === 'undefined') {
        window.confirmDeleteRole = function(roleId, roleName) {
            showConfirmationModal({
                title: 'Hapus Role',
                message: 'Apakah Anda yakin ingin menghapus role ini?',
                itemName: roleName,
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                onConfirm: function() {
                    // Create and submit form for deletion
                    const form = document.createElement('form');
                    form.method = 'POST';
                    
                    // Construct the action URL
                    let actionUrl = '';
                    if (window.roleDeleteRoute) {
                        actionUrl = window.roleDeleteRoute.replace(':id', roleId);
                    } else {
                        // Fallback route construction
                        actionUrl = `/panel/roles/${roleId}`;
                    }
                    
                    form.action = actionUrl;
                    
                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    form.appendChild(csrfInput);
                    
                    // Add method override for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    // Append to body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        };
    }

    // Note: editRole function is handled by the edit modal component
</script>


