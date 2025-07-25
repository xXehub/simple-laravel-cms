<!-- Delete Permission Modal -->
@can('delete-permissions')
    <div class="modal modal-blur fade" id="deletePermissionModal" tabindex="-1"
        aria-labelledby="deletePermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePermissionModalLabel">Delete Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.permissions.destroy', ':id') }}" id="deletePermissionForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_permission_id">
                    <div class="modal-body">
                        <p>Are you sure you want to delete permission <strong
                                id="delete_permission_name"></strong>?</p>
                        <p class="text-danger">This action cannot be undone and will remove this permission from
                            all roles.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
