@props(['permission'])

<div class="btn-list flex-nowrap">
    @can('update-permissions')
        <button type="button" class="btn btn-warning btn-sm" style="padding: 4px 8px; font-size: 12px;" 
            onclick="editPermission({{ $permission['id'] }})"
            data-bs-toggle="modal" data-bs-target="#editPermissionModal" title="Edit Permission">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    @endcan

    @can('delete-permissions')
        <button type="button" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 12px;"
            onclick="deletePermission({{ $permission['id'] }}, '{{ addslashes($permission['name']) }}')" 
            data-bs-toggle="modal" data-bs-target="#deletePermissionModal" title="Delete Permission">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    @endcan
</div>
