@props(['user'])

<div class="btn-list flex-nowrap">
    @can('update-users')
        {{-- <button type="button" class="btn btn-outline-info btn-sm"
            onclick="openAvatarModal({{ $user['id'] }}, '{{ addslashes($user['name']) }}', '{{ $user['avatar_url'] ?? '' }}', {{ $user['has_custom_avatar'] ?? 'false' }})"
            title="Upload Avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21,15 16,10 5,21"></polyline>
            </svg>
        </button> --}}

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
                    // Create and submit form for deletion
                    const form = document.createElement('form');
                    form.method = 'POST';
                    
                    // Construct the action URL based on current route patterns
                    let actionUrl = '';
                    if (window.userDeleteRoute) {
                        actionUrl = window.userDeleteRoute.replace(':id', userId);
                    } else {
                        // Fallback to constructed URL
                        actionUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '') + '/users/' + userId;
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
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        };
    }
</script>
