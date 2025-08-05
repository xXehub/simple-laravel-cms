@can('update-pages')
    <div class="modal modal-blur fade" id="editPageModal" tabindex="-1" aria-labelledby="editPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPageModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Edit Page
                    </h5>
                    <button type="button" class="btn-close" onclick="closeEditPageModal()" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('panel.pages.update', ':id') }}" id="editPageForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_page_id" value="">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_title" class="form-label">
                                        Page Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_title" name="title" required
                                        placeholder="Enter page title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_slug" class="form-label">
                                        URL Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_slug" name="slug" required
                                        placeholder="page-url-slug">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_template" class="form-label">Page Template</label>
                            <select class="form-control" id="edit_template" name="template">
                                <option value="">Select Template</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_content" class="form-label">Content</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="6" placeholder="Enter page content"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_meta_title" class="form-label">Meta Title (SEO)</label>
                                    <input type="text" class="form-control" id="edit_meta_title" name="meta_title"
                                        placeholder="SEO meta title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="edit_sort_order" name="sort_order"
                                        value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_meta_description" class="form-label">Meta Description (SEO)</label>
                            <textarea class="form-control" id="edit_meta_description" name="meta_description" rows="3"
                                placeholder="SEO meta description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_is_published" name="is_published"
                                    value="1">
                                <span class="form-check-label">Published</span>
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeEditPageModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon me-1">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon me-1">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17,21 17,13 7,13 7,21"></polyline>
                                <polyline points="7,3 7,8 15,8"></polyline>
                            </svg>
                            Update Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize TomSelect for template selection
        let editPageTemplateTomSelect = null;
        let availableTemplates = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Load templates first
            fetch('{{ route('panel.pages.getAvailableTemplates') }}')
                .then(response => response.json())
                .then(data => {
                    availableTemplates = data;
                    initializeEditPageTomSelect();
                })
                .catch(error => {
                    console.error('Error loading templates:', error);
                    availableTemplates = [];
                    initializeEditPageTomSelect();
                });

            function initializeEditPageTomSelect() {
                const editTemplateSelect = document.getElementById('edit_template');
                if (editTemplateSelect && typeof TomSelect !== 'undefined') {
                    editPageTemplateTomSelect = new TomSelect(editTemplateSelect, {
                        placeholder: 'Select a template...',
                        create: false,
                        maxItems: 1,
                        options: availableTemplates.map(template => ({
                            value: template.value,
                            text: template.label
                        })),
                        searchField: ['text'],
                        valueField: 'value',
                        labelField: 'text'
                    });
                }
            }

            // Close modal function - same pattern as create template modal
            window.closeEditPageModal = function() {
                const modal = document.getElementById('editPageModal');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
            };

            // Auto-generate slug from title in edit form
            const editTitleInput = document.getElementById('edit_title');
            const editSlugInput = document.getElementById('edit_slug');

            if (editTitleInput && editSlugInput) {
                editTitleInput.addEventListener('input', function() {
                    const title = this.value;
                    const slug = title.toLowerCase()
                        .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
                        .replace(/\s+/g, '-') // Replace spaces with -
                        .replace(/-+/g, '-') // Replace multiple - with single -
                        .trim('-'); // Trim - from start and end
                    editSlugInput.value = slug;
                });
            }

            // Handle edit page form submission
            const editPageForm = document.getElementById('editPageForm');
            if (editPageForm) {
                editPageForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const pageId = document.getElementById('edit_page_id').value;
                    if (!pageId) {
                        alert('Page ID is missing');
                        return;
                    }

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    // Show loading state
                    submitBtn.innerHTML =
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v6l3-3-3-3z"/><path d="M21 12h-6l3 3 3-3z"/><path d="M12 22v-6l-3 3 3 3z"/><path d="M3 12h6l-3-3-3 3z"/></svg>Updating...';
                    submitBtn.disabled = true;

                    const formData = new FormData(this);
                    const actionUrl = this.action.replace(':id', pageId);

                    fetch(actionUrl, {
                            method: 'POST', // Laravel handles PUT via _method
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'X-Requested-With': 'XMLHttpRequest'
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
                                // Close modal using Tabler.io method - same pattern as create template modal
                                const modal = document.getElementById('editPageModal');
                                if (modal) {
                                    modal.style.display = 'none';
                                    modal.classList.remove('show');
                                    document.body.classList.remove('modal-open');
                                    const backdrop = document.querySelector('.modal-backdrop');
                                    if (backdrop) backdrop.remove();
                                }
                                // Show success message and refresh DataTable using pages.js
                                if (typeof window.handlePageUpdateSuccess === 'function') {
                                    window.handlePageUpdateSuccess(data.page);
                                } else if (typeof PagesDataTable !== 'undefined') {
                                    PagesDataTable.refreshDataTable();
                                } else {
                                    window.location.reload();
                                }
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
                                alert('Error: ' + (error.message || 'Error updating page'));
                            }
                        })
                        .finally(() => {
                            // Restore button state
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        });
                });
            }

            // Global editPage function
            window.editPage = function(pageId) {
                // Load page data
                fetch('{{ route('panel.pages.edit', ':id') }}'.replace(':id', pageId), {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.page) {
                            const page = data.page;

                            // Populate form fields
                            document.getElementById('edit_page_id').value = page.id;
                            document.getElementById('edit_title').value = page.title || '';
                            document.getElementById('edit_slug').value = page.slug || '';
                            document.getElementById('edit_content').value = page.content || '';
                            document.getElementById('edit_meta_title').value = page.meta_title || '';
                            document.getElementById('edit_meta_description').value = page
                                .meta_description || '';
                            document.getElementById('edit_sort_order').value = page.sort_order || 0;
                            document.getElementById('edit_is_published').checked = !!page.is_published;

                            // Set template selection if TomSelect is available
                            if (editPageTemplateTomSelect) {
                                if (page.template) {
                                    editPageTemplateTomSelect.setValue(page.template, true);
                                } else {
                                    editPageTemplateTomSelect.clear(true);
                                }
                            }

                            // Update form action URL
                            const form = document.getElementById('editPageForm');
                            if (form) {
                                form.action = form.action.replace(':id', page.id);
                            }

                            // Show modal using Tabler.io method - same pattern as create template modal
                            const editModal = document.getElementById('editPageModal');
                            if (editModal) {
                                editModal.style.display = 'block';
                                editModal.classList.add('show');
                                document.body.classList.add('modal-open');

                                // Add backdrop
                                const backdrop = document.createElement('div');
                                backdrop.className = 'modal-backdrop fade show';
                                document.body.appendChild(backdrop);
                            }
                        } else {
                            alert('Error loading page data: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error loading page:', error);
                        alert('Error loading page data');
                    });
            };
        });
    </script>
@endcan
