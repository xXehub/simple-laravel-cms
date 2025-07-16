{{-- Menu Actions Component --}}
@props(['menu'])

<div class="btn-list flex-nowrap">
    @can('view-menus')
        <button type="button" class="btn btn-sm btn-primary"
            style="padding: 4px 8px; font-size: 12px;"onclick="openEditModal({{ json_encode($menu) }})" title="Edit Menu">
          <i class="fa-solid fa-expand"></i>
        </button>
    @endcan
    @can('update-menus')
        <button type="button" class="btn btn-sm btn-warning"
            style="padding: 4px 8px; font-size: 12px;"onclick="openEditModal({{ json_encode($menu) }})" title="Edit Menu">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    @endcan

    @can('delete-menus')
        <button type="button" class="btn btn-sm btn-danger"style="padding: 4px 8px; font-size: 12px;"
            onclick="openDeleteModal({{ json_encode($menu) }})" title="Delete Menu">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    @endcan
</div>
