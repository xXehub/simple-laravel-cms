{{-- Create Menu Modal --}}
<div class="modal fade" id="createMenuModal" tabindex="-1" aria-labelledby="createMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMenuModalLabel">
                    <i class="fas fa-plus me-2"></i>Create New Menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('panel.menus.store') }}" id="createMenuForm">
                @csrf
                <div class="modal-body">
                    {{-- Basic Information --}}
                    <div class="row mb-3">
                        <x-form.input name="nama_menu" label="Menu Name" type="text" :required="true"
                            :value="old('nama_menu')" id="create_nama_menu" class="col-md-6" />

                        <x-form.input name="slug" label="Slug" type="text" :required="true" :value="old('slug')"
                            id="create_slug" class="col-md-6" help="Example: panel/users" />
                    </div>

                    {{-- Technical Information --}}
                    <div class="row mb-3">
                        <x-form.input name="route_name" label="Route Name" type="text" :value="old('route_name')"
                            id="create_route_name" class="col-md-6" help="Optional. Example: users.index" />

                        <x-form.input name="icon" label="Icon" type="text" :value="old('icon')" id="create_icon"
                            class="col-md-6" help="Font Awesome class. Example: fas fa-users" />
                    </div>

                    {{-- Menu Structure --}}
                    <div class="row mb-3">
                        <x-form.select name="parent_id" label="Parent Menu" :options="$parentMenus->pluck('nama_menu', 'id')" :value="old('parent_id')"
                            id="create_parent_id" class="col-md-6" placeholder="-- Root Menu --" />

                        <x-form.input name="urutan" label="Order" type="number" :required="true" :value="old('urutan', 1)"
                            id="create_urutan" class="col-md-6" />
                    </div>

                    {{-- Status & Roles --}}
                    <div class="mb-3">
                        <x-form.checkbox name="is_active" label="Active" :checked="old('is_active', true)" id="create_is_active" />
                    </div>

                    <div class="mb-3">
                        <x-form.checkbox-group name="roles" label="Assign to Roles" :options="$roles->pluck('name', 'id')"
                            :checked="old('roles', [])" prefix="create_role_" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
