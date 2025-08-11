<!-- Create Setting Modal -->
@can('create-settings')
    <div class="modal modal-blur fade" id="createSettingModal"  tabindex="-1" aria-labelledby="createSettingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSettingModalLabel">Create New Setting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.settings.store') }}" id="createSettingForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_key" class="form-label">Setting Key <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_key" name="key" required
                                placeholder="e.g., app_name, site_logo, maintenance_mode">
                        </div>

                        <div class="mb-3">
                            <label for="create_type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="create_type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="number">Number</option>
                                <option value="boolean">Boolean</option>
                                <option value="json">JSON</option>
                                <option value="file">File</option>
                                <option value="image">Image</option>
                                <option value="url">URL</option>
                                <option value="email">Email</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="create_group" class="form-label">Group</label>
                            <input type="text" class="form-control" id="create_group" name="group"
                                placeholder="e.g., general, appearance, system">
                            <small class="form-text text-muted">Optional: Group settings for better
                                organization</small>
                        </div>

                        <div class="mb-3">
                            <label for="create_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="create_description" name="description"
                                placeholder="Brief description of this setting">
                        </div>

                        <div class="mb-3">
                            <label for="create_value" class="form-label">Value <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_value" name="value" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" 
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                                <circle cx="12" cy="14" r="2"/>
                                <polyline points="14,4 14,8 8,8 8,4"/>
                            </svg>
                            Create Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
                                       