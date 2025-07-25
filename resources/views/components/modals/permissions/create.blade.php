<!-- Create Permission Modal -->
@can('create-permissions')
    <div class="modal modal-blur fade" id="createPermissionModal" tabindex="-1" aria-labelledby="createPermissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPermissionModalLabel">Create New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.permissions.store') }}" id="createPermissionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_name" class="form-label">Permission Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_name" name="name" required
                                placeholder="e.g., view-users, create-posts">
                        </div>

                        <div class="mb-3">
                            <label for="create_group" class="form-label">Group</label>
                            <input type="text" class="form-control" id="create_group" name="group"
                                placeholder="e.g., users, posts, settings">
                            <small class="form-text text-muted">Optional: Group permissions for better
                                organization</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
