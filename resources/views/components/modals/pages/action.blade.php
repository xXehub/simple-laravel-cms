@props(['page'])

<div class="btn-list flex-nowrap">
    {{-- Edit/Builder Button - Show builder for builder pages, edit for template pages --}}
    @can('update-pages')
        @php
            $isBuilder = !empty($page['builder_data']) && $page['builder_data'] !== '[]';
        @endphp
        
        @if($isBuilder)
            <a href="{{ url('panel/pages/' . $page['id'] . '/builder') }}" class="btn btn-primary btn-sm" style="padding: 4px 8px; font-size: 12px;" title="Open Page Builder">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="4" width="16" height="4" rx="1"/>
                    <rect x="4" y="12" width="6" height="8" rx="1"/>
                    <rect x="14" y="12" width="6" height="8" rx="1"/>
                </svg>
            </a>
        @else
            <button type="button" class="btn btn-warning btn-sm" style="padding: 4px 8px; font-size: 12px;" 
                onclick="editPage({{ $page['id'] }})" title="Edit Page">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                    <path d="M16 5l3 3"/>
                </svg>
            </button>
        @endif
    @endcan

    {{-- View Button --}}
    <a href="{{ route('dynamic.page', $page['slug']) }}" target="_blank" class="btn btn-info btn-sm" style="padding: 4px 8px; font-size: 12px;" title="Preview Page">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
        </svg>
    </a>

    {{-- Duplicate Button --}}
    @can('update-pages')
        <button type="button" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 12px;"
            onclick="duplicatePage({{ $page['id'] }})" title="Duplicate Page">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="8" y="8" width="12" height="12" rx="2"/>
                <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"/>
            </svg>
        </button>
    @endcan

    {{-- Delete Button --}}
    @can('delete-pages')
        <button type="button" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 12px;"
            onclick="confirmDeletePage({{ $page['id'] }}, '{{ addslashes($page['title']) }}')" title="Delete Page">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <line x1="4" y1="7" x2="20" y2="7"/>
                <line x1="10" y1="11" x2="10" y2="17"/>
                <line x1="14" y1="11" x2="14" y2="17"/>
                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
            </svg>
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
    if (typeof window.confirmDeletePage === 'undefined') {
        window.confirmDeletePage = function(pageId, pageTitle) {
            showConfirmationModal({
                title: 'Hapus Page',
                message: 'Apakah Anda yakin ingin menghapus page ini?',
                itemName: pageTitle,
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                onConfirm: function() {
                    // Create and submit form for deletion
                    const form = document.createElement('form');
                    form.method = 'POST';
                    
                    // Construct the action URL based on current route patterns
                    let actionUrl = '';
                    if (window.pageDeleteRoute) {
                        actionUrl = window.pageDeleteRoute.replace(':id', pageId);
                    } else {
                        // Fallback to constructed URL
                        actionUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '') + '/pages/' + pageId;
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
