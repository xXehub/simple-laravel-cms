{{-- Delete Menu Modal --}}
<div class="modal modal-blur fade" id="deleteMenuModal" tabindex="-1" aria-labelledby="deleteMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMenuModalLabel">
                    <i class="fas fa-trash me-2 text-danger"></i>Delete Menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" id="deleteMenuForm" data-route-template="{{ route('panel.menus.destroy', ':id') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="delete_menu_id">

                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>

                    <p>Are you sure you want to delete this menu?</p>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-2">Menu Details:</h6>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Name:</strong> <span id="delete_menu_name"></span></li>
                                <li><strong>Slug:</strong> <code id="delete_menu_slug"></code></li>
                                <li><strong>Route:</strong> <code id="delete_menu_route"></code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            If this menu has child menus, they will also be affected. Please review the menu structure
                            before proceeding.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for Delete Modal --}}
<script>
    function openDeleteModal(menu) {
        // Populate form fields
        document.getElementById('delete_menu_id').value = menu.id;
        document.getElementById('delete_menu_name').textContent = menu.nama_menu;
        document.getElementById('delete_menu_slug').textContent = menu.slug;
        document.getElementById('delete_menu_route').textContent = menu.route_name || '-';

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('deleteMenuModal'));
        modal.show();
    }
</script>
