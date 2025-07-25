<!-- Delete Selected Permissions Modal -->
@can('delete-permissions')
    <div class="modal modal-blur fade" id="deleteSelectedModal" tabindex="-1" aria-labelledby="deleteSelectedModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSelectedModalLabel">Delete Selected Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the selected permissions?</p>
                    <p class="text-danger">This action cannot be undone and will remove these permissions from all roles.</p>
                    <p>Selected permissions: <span id="selected-permissions-count" class="fw-bold">0</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="PermissionsDataTable.handleBulkDelete()" data-bs-dismiss="modal">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update selected count when modal is shown
        document.addEventListener('DOMContentLoaded', function() {
            const deleteSelectedModal = document.getElementById('deleteSelectedModal');
            if (deleteSelectedModal) {
                deleteSelectedModal.addEventListener('show.bs.modal', function() {
                    const selectedCount = document.getElementById('selected-count').textContent;
                    document.getElementById('selected-permissions-count').textContent = selectedCount;
                });
            }
        });
    </script>
@endcan
