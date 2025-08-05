<div class="modal modal-blur fade" id="uploadTemplateModal" tabindex="-1" aria-labelledby="uploadTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadTemplateModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="12" y1="11" x2="12" y2="17"></line>
                        <line x1="9" y1="14" x2="15" y2="14"></line>
                    </svg>
                    Upload Page Template
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="POST" action="{{ route('panel.pages.uploadTemplate') }}" enctype="multipart/form-data" id="uploadTemplateForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                <strong>Upload Requirements:</strong><br>
                                • File must be a .blade.php template<br>
                                • Template should use $page variable for content<br>
                                • Maximum file size: 2MB
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="template_file" class="form-label">
                                Template File <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control" id="template_file" name="template_file" accept=".blade.php,.php" required>
                            <small class="form-text text-muted">Select .blade.php file to upload</small>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="overwrite_existing" name="overwrite_existing" value="1">
                                <label class="form-check-label" for="overwrite_existing">
                                    Overwrite existing template if it exists
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/>
                            <path d="m6 6 12 12"/>
                        </svg>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="12" y1="11" x2="12" y2="17"></line>
                            <line x1="9" y1="14" x2="15" y2="14"></line>
                        </svg>
                        Upload Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reset form when modal closes
        const uploadModal = document.getElementById('uploadTemplateModal');
        if (uploadModal) {
            uploadModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('uploadTemplateForm').reset();
            });
        }

        // File validation
        const templateFileInput = document.getElementById('template_file');
        if (templateFileInput) {
            templateFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const fileName = file.name;
                    const isValidExtension = fileName.endsWith('.blade.php') || fileName.endsWith('.php');
                    
                    if (!isValidExtension) {
                        alert('Please select a .blade.php or .php file');
                        this.value = '';
                        return;
                    }

                    // Show selected file name
                    console.log('Selected file:', fileName);
                }
            });
        }

        // Form submit handler
        const uploadForm = document.getElementById('uploadTemplateForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                const fileInput = document.getElementById('template_file');
                if (!fileInput.files || !fileInput.files[0]) {
                    alert('Please select a template file to upload');
                    return;
                }

                // Create FormData for file upload
                const formData = new FormData(this);
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v6l3-3-3-3z"/><path d="M21 12h-6l3 3 3-3z"/><path d="M12 22v-6l-3 3 3 3z"/><path d="M3 12h6l-3-3-3 3z"/></svg>Uploading...';
                submitBtn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Close modal using Tabler.io method
                        const modal = document.getElementById('uploadTemplateModal');
                        if (modal) {
                            modal.style.display = 'none';
                            modal.classList.remove('show');
                            document.body.classList.remove('modal-open');
                            const backdrop = document.querySelector('.modal-backdrop');
                            if (backdrop) backdrop.remove();
                        }
                        // Refresh page to show new template
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Error uploading template: ' + error.message);
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });
</script>
