@props(['roles', 'user'])

@can('update-users')
    <div class="modal modal-blur fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="POST" action="{{ route('panel.users.update', $user->id ?? ':id') }}" id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_user_id" value="{{ $user->id ?? '' }}">
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">
                                        Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required 
                                           placeholder="Enter full name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_username" class="form-label">
                                        Username <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_username" name="username" required
                                           placeholder="Enter username">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required
                                           placeholder="Enter email address">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_password" class="form-label">
                                        Password 
                                        <small class="text-muted">(leave empty to keep current)</small>
                                    </label>
                                    <input type="password" class="form-control" id="edit_password" name="password"
                                           placeholder="Enter new password">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="edit_password_confirmation" class="form-label">
                                        Confirm Password
                                    </label>
                                    <input type="password" class="form-control" id="edit_password_confirmation" 
                                           name="password_confirmation" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column" id="edit_roles_container">
                                @foreach ($roles as $role)
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                               class="form-selectgroup-input" id="edit_role_{{ $role->id }}">
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div>
                                                <strong>{{ ucfirst($role->name) }}</strong>
                                                <div class="text-secondary">{{ $role->description ?? 'Role permissions' }}</div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17,21 17,13 7,13 7,21"></polyline>
                                <polyline points="7,3 7,8 15,8"></polyline>
                            </svg>
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    @can('delete-users')
        <div class="modal modal-blur fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2 text-danger">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c-1 0 2 1 2 2v2"></path>
                            </svg>
                            Delete User
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form method="POST" action="{{ route('panel.users.destroy', $user->id ?? ':id') }}" id="deleteUserForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="delete_user_id" value="{{ $user->id ?? '' }}">
                        
                        <div class="modal-body">
                            <div class="text-center">
                                <div class="avatar avatar-lg bg-danger-lt mb-3 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-danger">
                                        <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"></path>
                                    </svg>
                                </div>
                                <h3>Are you sure?</h3>
                                <p class="text-secondary">Do you really want to delete user <strong id="delete_user_name"></strong>?</p>
                                <p class="text-danger small">This action cannot be undone.</p>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c-1 0 2 1 2 2v2"></path>
                                </svg>
                                Delete User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endcan
