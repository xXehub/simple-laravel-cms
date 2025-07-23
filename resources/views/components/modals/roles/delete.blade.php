<!-- Delete Role Modal -->
@can('delete-roles')
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleModalLabel">Delete Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.roles.destroy', ':id') }}" id="deleteRoleForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_role_id">
                    <div class="modal-body">
                        <p>Are you sure you want to delete role <strong id="delete_role_name"></strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Delete Role function
        function deleteRole(roleId, roleName) {
            // Update form action URL with the actual role ID
            const deleteForm = document.getElementById('deleteRoleForm');
            deleteForm.action = deleteForm.action.replace(':id', roleId);
            
            document.getElementById('delete_role_id').value = roleId;
            document.getElementById('delete_role_name').textContent = roleName;
        }

        // Reset delete modal when closed
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteRoleModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    const deleteForm = document.getElementById('deleteRoleForm');
                    deleteForm.reset();
                    // Reset form action URL to use placeholder
                    deleteForm.action = `{{ route('panel.roles.destroy', ':id') }}`;
                });
            }
        });
    </script>
@endcan
