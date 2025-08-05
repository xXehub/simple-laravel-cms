@can('update-pages')
    <div class="modal modal-blur fade" id="editPageModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPageModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Page
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="POST" id="editPageForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_page_id" name="id">
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
                                        <option value="kontak">Kontak</option>
                                        <option value="layanan">Layanan</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="edit_sort_order" name="sort_order" 
                                           min="0" value="0" placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_content" class="form-label">
                                Content <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="edit_content" name="content" rows="8" required
                                      placeholder="Enter page content..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="edit_meta_title" name="meta_title"
                                           placeholder="SEO title for search engines">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="edit_is_published" name="is_published" value="1">
                                        <span class="form-check-label">Published</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="edit_meta_description" name="meta_description" rows="3"
                                      placeholder="SEO description for search engines (max 500 characters)"></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
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
        // Auto-generate slug from title in edit form
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
@endcan
