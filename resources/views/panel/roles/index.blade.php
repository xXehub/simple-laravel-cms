<x-layout.app title="Roles Management - Panel Admin" :pakai-sidebar="true">
   <div class="page-wrapper">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-user-tag me-2"></i>Roles Management
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            @can('create-roles')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="fas fa-plus me-2"></i>Add Role
                </button>
            @endcan
        </div>
    </div>

    <x-alert.flash-messages />

    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Role</th>
                    <th>Guard</th>
                    <th>Permissions</th>
                    <th>Dibuat</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>
                            <strong>{{ $role->name }}</strong>
                        </td>
                        <td>{{ $role->guard_name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                        </td>
                        <td>{{ $role->created_at->format('d M Y') }}</td>
                        <td>
                            @can('update-roles')
                                <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                    onclick="editRole({{ $role->id }})" data-bs-toggle="modal"
                                    data-bs-target="#editRoleModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endcan
                            @can('delete-roles')
                                @if ($role->name !== 'admin')
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')"
                                        data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada roles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $roles->links() }}

    <!-- Include Modal Components -->
    <x-modals.roles.create :permissions="$permissions" />
    <x-modals.roles.edit :permissions="$permissions" />
    <x-modals.roles.delete />
   </div>
</x-layout.app>
