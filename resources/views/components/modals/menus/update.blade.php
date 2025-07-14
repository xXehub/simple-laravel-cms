{{-- Edit Menu Modal --}}
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('panel.menus.update') }}" id="editMenuForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_menu_id">

                <div class="modal-body">
                    {{-- Basic Information --}}
                    <div class="row mb-3">
                        <x-form.input name="nama_menu" label="Menu Name" type="text" :required="true"
                            id="edit_nama_menu" class="col-md-6" />

                        <x-form.input name="slug" label="Slug" type="text" :required="true" id="edit_slug"
                            class="col-md-6" help="Example: panel/users" />
                    </div>

                    {{-- Technical Information --}}
                    <div class="row mb-3">
                        <x-form.input name="route_name" label="Route Name" type="text" id="edit_route_name"
                            class="col-md-6" help="Optional. Example: users.index" />

                        <x-form.input name="icon" label="Icon" type="text" id="edit_icon" class="col-md-6"
                            help="Font Awesome class. Example: fas fa-users" />
                    </div>

                    {{-- Menu Structure --}}
                    <div class="row mb-3">
                        <x-form.select name="parent_id" label="Parent Menu" :options="$parentMenus->pluck('nama_menu', 'id')" id="edit_parent_id"
                            class="col-md-6" placeholder="-- Root Menu --" />

                        <x-form.input name="urutan" label="Order" type="number" :required="true" id="edit_urutan"
                            class="col-md-6" />
                    </div>

                    {{-- Status & Roles --}}
                    <div class="mb-3">
                        <x-form.checkbox name="is_active" label="Active" id="edit_is_active" />
                    </div>

                    <div class="mb-3">
                        <x-form.checkbox-group name="roles" label="Assign to Roles" :options="$roles->pluck('name', 'id')"
                            prefix="edit_role_" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
