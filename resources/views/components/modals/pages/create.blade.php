@can('create-pages')
    <div class="modal modal-blur fade" id="createPageModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPageModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Create New Page
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="POST" action="{{ route('panel.pages.store') }}" id="createPageForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="create_title" class="form-label">
                                        Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="create_title" name="title" required 
                                           placeholder="Enter page title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="create_slug" class="form-label">
                                        Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="create_slug" name="slug" required
                                           placeholder="page-url-slug">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="create_template" class="form-label">Template</label>
                                    <select class="form-control" id="create_template" name="template">
                                        <option value="">Default</option>
                                        <option value="about">About</option>
                                        <option value="contact">Contact</option>
                                        <option value="landing">Landing</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="create_sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="create_sort_order" name="sort_order" 
                                           value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="create_content" class="form-label">
                                Content <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="create_content" name="content" rows="5" required
                                      placeholder="Enter page content..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="create_meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="create_meta_title" name="meta_title"
                                           placeholder="SEO meta title">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="create_meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="create_meta_description" name="meta_description" rows="2"
                                      placeholder="SEO meta description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" id="create_is_published" name="is_published" checked>
                                <span class="form-check-label">Published</span>
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                <polyline points="20,6 9,17 4,12"></polyline>
                            </svg>
                            Create Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from title
        document.getElementById('create_title').addEventListener('input', function() {
            const title = this.value;
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
                .replace(/\s+/g, '-')        // Replace spaces with -
                .replace(/-+/g, '-')         // Replace multiple - with single -
                .trim('-');                  // Trim - from start and end
            document.getElementById('create_slug').value = slug;
        });
    </script>
@endcan
