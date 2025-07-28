{{-- Menu Actions Component --}}
@props(['menu'])

<div class="btn-list flex-nowrap">
    @can('view-menus')
        <button type="button" class="btn btn-sm btn-primary"
            style="padding: 4px 8px; font-size: 12px;" onclick="openEditModal({{ json_encode($menu) }})" title="Edit Menu">
            <i class="fa-solid fa-expand"></i>
        </button>
    @endcan
    @can('update-menus')
        <button type="button" class="btn btn-sm btn-warning"
            style="padding: 4px 8px; font-size: 12px;" onclick="openEditModal({{ json_encode($menu) }})" title="Edit Menu">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    @endcan

    @can('delete-menus')
        <button type="button" class="btn btn-sm btn-danger" style="padding: 4px 8px; font-size: 12px;"
            onclick="confirmDeleteMenu({{ $menu['id'] }}, '{{ addslashes($menu['label']) }}')" title="Delete Menu">
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

    // Global function for menu delete confirmation
    if (typeof window.confirmDeleteMenu === 'undefined') {
        window.confirmDeleteMenu = function(menuId, menuLabel) {
            showConfirmationModal({
                title: 'Hapus Menu',
                message: 'Apakah Anda yakin ingin menghapus menu ini?',
                itemName: menuLabel,
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                onConfirm: function() {
                    // Create and submit form for deletion
                    const form = document.createElement('form');
                    form.method = 'POST';
                    
                    // Construct the action URL
                    let actionUrl = '';
                    if (window.menuDeleteRoute) {
                        actionUrl = window.menuDeleteRoute.replace(':id', menuId);
                    } else {
                        // Fallback to constructed URL
                        actionUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '') + '/menus/' + menuId;
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
