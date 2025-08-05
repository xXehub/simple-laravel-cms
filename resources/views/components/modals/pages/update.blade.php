@can('update-pages')
    <div class="modal modal-blur fade" id="updatePageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePageModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Edit Page
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('panel.pages.update', ':id') }}" id="updatePageForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="update_page_id" value="">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="update_title" class="form-label">
                                        Page Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="update_title" name="title" required
                                        placeholder="Enter page title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="update_slug" class="form-label">
                                        URL Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="update_slug" name="slug" required
                                        placeholder="page-url-slug">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="update_template" class="form-label">Page Template</label>
                            <select class="form-control" id="update_template" name="template">
                                <option value="">Select Template</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="update_content" class="form-label">Content</label>
                            <textarea class="form-control" id="update_content" name="content" rows="6" placeholder="Enter page content"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="update_meta_title" class="form-label">Meta Title (SEO)</label>
                                    <input type="text" class="form-control" id="update_meta_title" name="meta_title"
                                        placeholder="SEO meta title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="update_sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="update_sort_order" name="sort_order"
                                        value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="update_meta_description" class="form-label">Meta Description (SEO)</label>
                            <textarea class="form-control" id="update_meta_description" name="meta_description" rows="3"
                                placeholder="SEO meta description"></textarea>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="update_is_published" name="is_published" value="1">
                            <label class="form-check-label" for="update_is_published">
                                Published
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon me-1">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon me-1">
                                <path d="M19 21H5a2 2 0 0 1-2 2v16a2 2 0 0 1 2 2h12a2 2 0 0 1 2-2V8z"/>
                                <polyline points="17,21 17,13 7,13 7,21"/>
                                <polyline points="7,3 7,8 15,8"/>
                            </svg>
                            Update Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
