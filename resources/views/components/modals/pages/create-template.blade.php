@can('create-pages')
    <div class="modal modal-blur fade" id="createTemplatePageModal" tabindex="-1" aria-labelledby="createTemplatePageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTemplatePageModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10,9 9,9 8,9"/>
                        </svg>
                        Create Template-based Page
                    </h5>
                    <button type="button" class="btn-close" onclick="closeCreateTemplateModal()" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('panel.pages.createTemplate') }}" id="createTemplatePageForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="template_title" class="form-label">
                                        Page Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="template_title" name="title" required placeholder="Enter page title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="template_slug" class="form-label">
                                        URL Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="template_slug" name="slug" required placeholder="page-url-slug">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="template_template" class="form-label">
                                Page Template <span class="text-danger">*</span>
                            </label>
                            <select class="form-control tomselect-template" id="template_template" name="template" required>
                                <option value="">Select Template</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="template_content" class="form-label">Initial Content</label>
                            <textarea class="form-control" id="template_content" name="content" rows="4" placeholder="Enter initial content for the page (optional)"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="template_meta_title" class="form-label">Meta Title (SEO)</label>
                                    <input type="text" class="form-control" id="template_meta_title" name="meta_title" placeholder="SEO meta title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="template_sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="template_sort_order" name="sort_order" value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="template_meta_description" class="form-label">Meta Description (SEO)</label>
                            <textarea class="form-control" id="template_meta_description" name="meta_description" rows="2" placeholder="SEO meta description"></textarea>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="template_is_published" name="is_published" value="1">
                            <label class="form-check-label" for="template_is_published">
                                Publish immediately
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeCreateTemplateModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10,9 9,9 8,9"/>
                            </svg>
                            Create Template Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-generate slug from title for template pages
            const templateTitleInput = document.getElementById('template_title');
            const templateSlugInput = document.getElementById('template_slug');
            
            if (templateTitleInput && templateSlugInput) {
                templateTitleInput.addEventListener('input', function() {
                    const title = this.value;
                    const slug = title.toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    templateSlugInput.value = slug;
                });
            }

            // Handle template-based page form submission
            const templateForm = document.getElementById('createTemplatePageForm');
            if (templateForm) {
                templateForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    // Show loading state
                    submitBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v6l3-3-3-3z"/><path d="M21 12h-6l3 3 3-3z"/><path d="M12 22v-6l-3 3 3 3z"/><path d="M3 12h6l-3-3-3 3z"/></svg>Creating...';
                    submitBtn.disabled = true;

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Close modal using Tabler.io method
                            const modal = document.getElementById('createTemplatePageModal');
                            if (modal) {
                                modal.style.display = 'none';
                                modal.classList.remove('show');
                                document.body.classList.remove('modal-open');
                                const backdrop = document.querySelector('.modal-backdrop');
                                if (backdrop) backdrop.remove();
                            }
                            
                            // Show success message and refresh page
                            alert('Template page created successfully!');
                            window.location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (error.errors) {
                            // Handle validation errors
                            let errorMessage = 'Validation errors:\n';
                            for (const [field, messages] of Object.entries(error.errors)) {
                                errorMessage += `${field}: ${messages.join(', ')}\n`;
                            }
                            alert(errorMessage);
                        } else {
                            alert('Error: ' + (error.message || 'Error creating template page'));
                        }
                    })
                    .finally(() => {
                        // Restore button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
        });

        // Close modal function - same pattern as edit modal
        window.closeCreateTemplateModal = function() {
            const modal = document.getElementById('createTemplatePageModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }
        };

        // Initialize TomSelect for template selection
        let templateTomSelect = null;
        document.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.getElementById('template_template');
            if (templateSelect && typeof TomSelect !== 'undefined') {
                templateTomSelect = new TomSelect(templateSelect, {
                    placeholder: 'Select a template...',
                    create: false,
                    maxItems: 1,
                    load: function(query, callback) {
                        // Always load templates when dropdown opens (query is empty on first open)
                        fetch('{{ route('panel.pages.getAvailableTemplates') }}')
                            .then(response => response.json())
                            .then(data => {
                                callback(data.map(template => ({
                                    value: template.value,
                                    text: template.label
                                })));
                            })
                            .catch(error => {
                                console.error('Error loading templates:', error);
                                callback();
                            });
                    },
                    loadThrottle: 300,
                    searchField: ['text'],
                    valueField: 'value',
                    labelField: 'text'
                });
            }
        });

        // Reset TomSelect when modal is shown
        document.getElementById('createTemplatePageModal').addEventListener('show.bs.modal', function() {
            if (templateTomSelect) {
                templateTomSelect.clear();
                // Trigger load to refresh templates
                templateTomSelect.load('');
            }
        });
    </script>
@endcan
