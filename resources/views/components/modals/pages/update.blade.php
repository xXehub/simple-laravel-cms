@can('update-pages')
    <div class="modal modal-blur fade" id="editPageModal" tabindex="-1" aria-labelledby="editPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPageModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="m18.5 2.5 a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Page
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_title" name="title" required 
                                           placeholder="Enter page title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_slug" class="form-label">
                                        Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_slug" name="slug" required
                                           placeholder="page-url-slug">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_template" class="form-label">Template</label>
                                    <select class="form-control" id="edit_template" name="template">
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
                                    <label for="edit_sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="edit_sort_order" name="sort_order" 
                                           value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_content" class="form-label">
                                Content <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="edit_content" name="content" rows="5" required
                                      placeholder="Enter page content..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="edit_meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="edit_meta_title" name="meta_title"
                                           placeholder="SEO meta title">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="edit_meta_description" name="meta_description" rows="2"
                                      placeholder="SEO meta description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_is_published" name="is_published">
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
                            Update Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from title in edit modal
        document.getElementById('edit_title').addEventListener('input', function() {
            const title = this.value;
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
                .replace(/\s+/g, '-')        // Replace spaces with -
                .replace(/-+/g, '-')         // Replace multiple - with single -
                .trim('-');                  // Trim - from start and end
            document.getElementById('edit_slug').value = slug;
        });
    </script>
@else
    {{-- Fallback for users without permission --}}
    <div class="modal modal-blur fade" id="editPageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2 text-warning icon-lg">
                        <path d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"></path>
                    </svg>
                    <h3>Access Denied</h3>
                    <div class="text-secondary">You don't have permission to edit pages.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endcan
