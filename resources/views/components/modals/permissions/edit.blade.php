<!-- Edit Permission Modal -->
@can('update-permissions')
    <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPermissionModalLabel">Edit Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.permissions.update', ':id') }}" id="editPermissionForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_permission_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Permission Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_group" class="form-label">Group</label>
                            <input type="text" class="form-control" id="edit_group" name="group">
                            <small class="form-text text-muted">Optional: Group permissions for better
                                organization</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
