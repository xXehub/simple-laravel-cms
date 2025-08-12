@props(['user'])

<div class="btn-list flex-nowrap">
    @if(!isset($user['is_trashed']) || !$user['is_trashed'])
        {{-- Active User Actions --}}
        @can('update-users')
            <button type="button" class="btn btn-warning btn-sm" style="padding: 4px 8px; font-size: 12px;" onclick="editUser({{ $user['id'] }})"
                data-bs-toggle="modal" data-bs-target="#editUserModal" title="Edit User">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        @endcan

        @can('delete-users')
            <button type="button" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 12px;"
                onclick="confirmDeleteUser({{ $user['id'] }}, '{{ addslashes($user['name']) }}')" title="Delete User">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        @endcan
    @else
        {{-- Trashed User Actions --}}
        @can('delete-users')
            <button type="button" class="btn btn-success btn-sm" style="padding: 4px 8px; font-size: 12px;"
                onclick="confirmRestoreUser({{ $user['id'] }}, '{{ addslashes($user['name']) }}')" title="Restore User">
                <i class="fa-solid fa-undo"></i>
            </button>
            
            <button type="button" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 12px;"
                onclick="confirmForceDeleteUser({{ $user['id'] }}, '{{ addslashes($user['name']) }}')" title="Delete Permanently">
                <i class="fa-solid fa-trash"></i>
            </button>
        @endcan
    @endif
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

    // Global function for delete confirmation
    if (typeof window.confirmDeleteUser === 'undefined') {
        window.confirmDeleteUser = function(userId, userName) {
            showConfirmationModal({
                title: 'Hapus User',
                message: 'Apakah Anda yakin ingin menghapus user ini?',
                itemName: userName,
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                onConfirm: function() {
                    submitUserAction(userId, 'DELETE');
                }
            });
        };
    }

    // Global function for restore confirmation
    if (typeof window.confirmRestoreUser === 'undefined') {
        window.confirmRestoreUser = function(userId, userName) {
            showRestoreConfirmationModal({
                title: 'Pulihkan User?',
                message: 'Apakah Anda yakin ingin memulihkan user ini?',
                itemName: userName,
                buttonText: 'Ya, Pulihkan',
                onConfirm: function() {
                    submitUserAction(userId, 'PATCH', 'restore');
                }
            });
        };
    }

    // Global function for force delete confirmation
    if (typeof window.confirmForceDeleteUser === 'undefined') {
        window.confirmForceDeleteUser = function(userId, userName) {
            showConfirmationModal({
                title: 'Hapus Permanen',
                message: 'Apakah Anda yakin ingin menghapus user ini secara permanen? Tindakan ini tidak dapat dibatalkan!',
                itemName: userName,
                confirmText: 'Ya, Hapus Permanen',
                confirmClass: 'btn-danger',
                onConfirm: function() {
                    submitUserAction(userId, 'DELETE', 'force');
                }
            });
        };
    }

    // Helper function to submit user actions
    if (typeof window.submitUserAction === 'undefined') {
        window.submitUserAction = function(userId, method, action = 'destroy') {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            
            // Construct the action URL based on the action
            let actionUrl = '';
            if (action === 'restore') {
                actionUrl = '{{ route('panel.users.restore', ':id') }}'.replace(':id', userId);
            } else if (action === 'force') {
                actionUrl = '{{ route('panel.users.forceDestroy', ':id') }}'.replace(':id', userId);
            } else {
                // Default destroy action
                actionUrl = '{{ route('panel.users.destroy', ':id') }}'.replace(':id', userId);
            }
            
            form.action = actionUrl;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            form.appendChild(csrfInput);
            
            // Add method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = method;
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        };
    }
</script>
