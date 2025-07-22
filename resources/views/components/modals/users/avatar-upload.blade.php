<!-- Avatar Upload Modal -->
<div class="modal modal-blur fade" id="uploadAvatarModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <form id="uploadAvatarForm" action="{{ route('panel.users.uploadAvatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="upload_user_id" name="id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Current Avatar Preview -->
                            <div class="mb-3 text-center">
                                <div class="avatar avatar-xl mx-auto mb-3" id="current-avatar-preview"
                                     style="background-image: url('https://ui-avatars.com/api/?name=U&color=ffffff&background=0ea5e9&size=128&rounded=false&bold=true');">
                                </div>
                                <small class="text-muted">Current Avatar</small>
                            </div>

                            <!-- File Upload -->
                            <div class="mb-3">
                                <label class="form-label">Choose Avatar Image</label>
                                <input type="file" class="form-control" id="avatar_file" name="avatar" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" required>
                                <div class="form-hint">
                                    Accepted formats: JPEG, PNG, GIF, WebP<br>
                                    Maximum size: 2MB<br>
                                    Recommended dimensions: 256x256 pixels
                                </div>
                            </div>

                            <!-- Preview New Avatar -->
                            <div class="mb-3 text-center" id="new-avatar-preview-container" style="display: none;">
                                <div class="avatar avatar-xl mx-auto mb-3" id="new-avatar-preview"></div>
                                <small class="text-muted">New Avatar Preview</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                    <button type="button" class="btn btn-danger me-2" id="delete-avatar-btn" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" 
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                             stroke-linejoin="round" class="icon me-1">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path>
                        </svg>
                        Delete Current
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" 
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                             stroke-linejoin="round" class="icon me-1">
                            <path d="M14,3v4a1,1,0,0,0,1,1h4"></path>
                            <path d="M17,21H7a2,2,0,0,1-2-2V5A2,2,0,0,1,7,3H14L21,10V19A2,2,0,0,1,19,21Z"></path>
                        </svg>
                        Upload Avatar
                    </button>
                </div>
            </form> --}}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarFileInput = document.getElementById('avatar_file');
    const newAvatarPreview = document.getElementById('new-avatar-preview');
    const newAvatarPreviewContainer = document.getElementById('new-avatar-preview-container');
    const uploadAvatarForm = document.getElementById('uploadAvatarForm');
    const deleteAvatarBtn = document.getElementById('delete-avatar-btn');

    // Preview selected image
    avatarFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                newAvatarPreview.style.backgroundImage = `url('${e.target.result}')`;
                newAvatarPreviewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            newAvatarPreviewContainer.style.display = 'none';
        }
    });

    // Handle form submission
    uploadAvatarForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
            Uploading...
        `;

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('uploadAvatarModal')).hide();
                
                // Refresh DataTable
                if (window.usersTable) {
                    usersTable.ajax.reload(null, false);
                }
                
                // Show success message
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Failed to upload avatar. Please try again.');
        })
        .finally(() => {
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Handle delete avatar
    deleteAvatarBtn.addEventListener('click', function() {
        if (!confirm('Are you sure you want to delete the current avatar?')) {
            return;
        }

        const userId = document.getElementById('upload_user_id').value;
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
            Deleting...
        `;

        fetch('{{ route('panel.users.deleteAvatar') }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('uploadAvatarModal')).hide();
                
                // Refresh DataTable
                if (window.usersTable) {
                    usersTable.ajax.reload(null, false);
                }
                
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Failed to delete avatar. Please try again.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });

    // Helper function to show alerts
    function showAlert(type, message) {
        // You can implement your own alert system here
        // For now, using browser alert
        alert(message);
    }
});

// Function to open avatar upload modal
window.openAvatarModal = function(userId, userName, currentAvatarUrl, hasCustomAvatar) {
    document.getElementById('upload_user_id').value = userId;
    document.getElementById('current-avatar-preview').style.backgroundImage = `url('${currentAvatarUrl}')`;
    document.getElementById('delete-avatar-btn').style.display = hasCustomAvatar ? 'inline-block' : 'none';
    
    // Reset form
    document.getElementById('uploadAvatarForm').reset();
    document.getElementById('new-avatar-preview-container').style.display = 'none';
    
    // Show modal
    new bootstrap.Modal(document.getElementById('uploadAvatarModal')).show();
};
</script>
