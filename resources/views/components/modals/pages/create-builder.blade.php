@can('create-pages')
    <div class="modal modal-blur fade" id="createBuilderPageModal" tabindex="-1" aria-labelledby="createBuilderPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBuilderPageModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="4" rx="1"/>
                            <rect x="4" y="12" width="6" height="8" rx="1"/>
                            <rect x="14" y="12" width="6" height="8" rx="1"/>
                        </svg>
                        Create Page with Builder
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('panel.pages.createBuilder') }}" id="createBuilderPageForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="builder_title" class="form-label">
                                        Page Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="builder_title" name="title" required placeholder="Enter page title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="builder_slug" class="form-label">
                                        URL Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="builder_slug" name="slug" required placeholder="page-url-slug">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="builder_meta_title" class="form-label">Meta Title (SEO)</label>
                                    <input type="text" class="form-control" id="builder_meta_title" name="meta_title" placeholder="SEO meta title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="builder_sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="builder_sort_order" name="sort_order" value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="builder_meta_description" class="form-label">Meta Description (SEO)</label>
                            <textarea class="form-control" id="builder_meta_description" name="meta_description" rows="2" placeholder="SEO meta description"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="builder_is_published" name="is_published" value="1">
                                    <label class="form-check-label" for="builder_is_published">
                                        Publish immediately
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="builder_open_after_create" name="open_builder" value="1" checked>
                                    <label class="form-check-label" for="builder_open_after_create">
                                        Open in builder after creating
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            This will create a new page and open it in the drag & drop page builder where you can design your content visually.
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
                                <rect x="4" y="4" width="16" height="4" rx="1"/>
                                <rect x="4" y="12" width="6" height="8" rx="1"/>
                                <rect x="14" y="12" width="6" height="8" rx="1"/>
                            </svg>
                            Create & Open Builder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-generate slug from title for builder pages
            const builderTitleInput = document.getElementById('builder_title');
            const builderSlugInput = document.getElementById('builder_slug');
            
            if (builderTitleInput && builderSlugInput) {
                builderTitleInput.addEventListener('input', function() {
                    const title = this.value;
                    const slug = title.toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    builderSlugInput.value = slug;
                });
            }

            // Handle builder page form submission
            const builderForm = document.getElementById('createBuilderPageForm');
            if (builderForm) {
                builderForm.addEventListener('submit', function(e) {
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
                            if (data.redirect_url) {
                                // Redirect to builder
                                window.location.href = data.redirect_url;
                            } else {
                                // Close modal using Tabler.io method and refresh
                                const modal = document.getElementById('createBuilderPageModal');
                                if (modal) {
                                    modal.style.display = 'none';
                                    modal.classList.remove('show');
                                    document.body.classList.remove('modal-open');
                                    const backdrop = document.querySelector('.modal-backdrop');
                                    if (backdrop) backdrop.remove();
                                }
                                
                                alert('Builder page created successfully!');
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
                            alert('Error: ' + (error.message || 'Error creating builder page'));
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
    </script>
@endcan
