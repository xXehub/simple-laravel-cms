@props(['user'])

<div class="btn-list flex-nowrap">
    @can('update-users')
        {{-- <button type="button" class="btn btn-outline-info btn-sm"
            onclick="openAvatarModal({{ $user['id'] }}, '{{ addslashes($user['name']) }}', '{{ $user['avatar_url'] ?? '' }}', {{ $user['has_custom_avatar'] ?? 'false' }})"
            title="Upload Avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21,15 16,10 5,21"></polyline>
            </svg>
        </button> --}}

        <button type="button" class="btn btn-warning btn-sm" style="padding: 4px 8px; font-size: 12px;" onclick="editUser({{ $user['id'] }})"
            data-bs-toggle="modal" data-bs-target="#editUserModal" title="Edit User">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    @endcan

    @can('delete-users')
        <button type="button" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 12px;"
            onclick="deleteUser({{ $user['id'] }}, '{{ addslashes($user['name']) }}')" data-bs-toggle="modal"
            data-bs-target="#deleteUserModal" title="Delete User">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    @endcan
</div>
