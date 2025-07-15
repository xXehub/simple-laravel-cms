@props(['user'])

<div class="btn-list flex-nowrap">
    @can('update-users')
        <button type="button" 
                class="btn btn-outline-primary btn-sm" 
                onclick="editUser({{ $user['id'] }})"
                data-bs-toggle="modal" 
                data-bs-target="#editUserModal"
                title="Edit User">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
        </button>
    @endcan
    
    @can('delete-users')
        <button type="button" 
                class="btn btn-outline-danger btn-sm" 
                onclick="deleteUser({{ $user['id'] }}, '{{ addslashes($user['name']) }}')"
                data-bs-toggle="modal" 
                data-bs-target="#deleteUserModal"
                title="Delete User">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c-1 0 2 1 2 2v2"></path>
            </svg>
        </button>
    @endcan
</div>
