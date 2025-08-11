<!-- Edit Setting Modal -->
@can('update-settings')
    <div class="modal modal-blur fade" id="editSettingModal" tabindex="-1" aria-labelledby="editSettingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSettingModalLabel">Edit Setting</h5>
                    <button type="button" class="btn-close" onclick="$('#editSettingModal').modal('hide')" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.settings.update', ':id') }}" id="editSettingForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_setting_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_key" class="form-label">Setting Key <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_key" name="key" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_type" name="type" required>
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
                            <label for="edit_group" class="form-label">Group</label>
                            <input type="text" class="form-control" id="edit_group" name="group">
                            <small class="form-text text-muted">Optional: Group settings for better
                                organization</small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_description" name="description">
                        </div>

                        <div class="mb-3">
                            <label for="edit_value" class="form-label">Value <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_value" name="value" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
