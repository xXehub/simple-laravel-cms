<!-- Edit Setting Modal -->
@can('update-settings')
    <div class="modal modal-blur fade" id="editSettingModal" tabindex="-1" aria-labelledby="editSettingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSettingModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Edit Setting
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.settings.update', ':id') }}" id="editSettingForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_setting_id">
                    <div class="modal-body">
                        <!-- Row 1: Setting Key (Full Width) -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="edit_key" class="form-label fw-medium">Setting Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_key" name="key" required>
                                <small class="form-text text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="4"/>
                                        <path d="M12 2v2"/>
                                        <path d="M12 20v2"/>
                                        <path d="m4.93 4.93l1.41 1.41"/>
                                        <path d="m17.66 17.66l1.41 1.41"/>
                                        <path d="M2 12h2"/>
                                        <path d="M20 12h2"/>
                                        <path d="m6.34 17.66l-1.41 1.41"/>
                                        <path d="m19.07 4.93l-1.41 1.41"/>
                                    </svg>
                                    Unique identifier for this setting (snake_case recommended)
                                </small>
                            </div>
                        </div>

                        <!-- Row 2: Type & Group (2 Columns) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_type" class="form-label fw-medium">Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="text">Text</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="number">Number</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="email">Email</option>
                                    <option value="url">URL</option>
                                    <option value="image">Image</option>
                                    <option value="color">Color</option>
                                    <option value="json">JSON</option>
                                </select>
                                <small class="form-text text-muted">Data type for this setting</small>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_group" class="form-label fw-medium">Group</label>
                                <select class="form-select" id="edit_group" name="group">
                                    <option value="">Select or type group...</option>
                                    <option value="general">General</option>
                                    <option value="branding">Branding</option>
                                    <option value="seo">SEO</option>
                                    <option value="contact">Contact</option>
                                    <option value="social">Social</option>
                                    <option value="feature">Feature</option>
                                    <option value="landing">Landing</option>
                                </select>
                                <small class="form-text text-muted">Optional: Organize settings by category</small>
                            </div>
                        </div>

                        <!-- Row 3: Description (Full Width) -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="edit_description" class="form-label fw-medium">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                                <small class="form-text text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="4"/>
                                        <path d="M12 2v2"/>
                                        <path d="M12 20v2"/>
                                        <path d="m4.93 4.93l1.41 1.41"/>
                                        <path d="m17.66 17.66l1.41 1.41"/>
                                        <path d="M2 12h2"/>
                                        <path d="M20 12h2"/>
                                        <path d="m6.34 17.66l-1.41 1.41"/>
                                        <path d="m19.07 4.93l-1.41 1.41"/>
                                    </svg>
                                    Helpful description for other administrators
                                </small>
                            </div>
                        </div>

                        <!-- Row 4: Value (Full Width) -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="edit_value" class="form-label fw-medium">Value <span class="text-danger">*</span></label>
                                <div id="edit_value_container">
                                    <!-- Default text input -->
                                    <input type="text" class="form-control" id="edit_value" name="value" required>
                                </div>
                                <small class="form-text text-muted" id="edit_value_help">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/>
                                        <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/>
                                        <line x1="16" y1="5" x2="19" y2="8"/>
                                    </svg>
                                    Enter the setting value
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" 
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                                <circle cx="12" cy="14" r="2"/>
                                <polyline points="14,4 14,8 8,8 8,4"/>
                            </svg>
                            Update Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editTypeSelect = document.getElementById('edit_type');
            const editGroupSelect = document.getElementById('edit_group');
            const editValueContainer = document.getElementById('edit_value_container');
            const editValueHelp = document.getElementById('edit_value_help');

            // Initialize TomSelect for Type
            let editTypeTomSelect = null;
            if (editTypeSelect && typeof TomSelect !== 'undefined') {
                editTypeTomSelect = new TomSelect(editTypeSelect, {
                    placeholder: 'Select setting type...',
                    create: false,
                    maxItems: 1,
                    searchField: ['text', 'value']
                });
            }

            // Initialize TomSelect for Group  
            let editGroupTomSelect = null;
            if (editGroupSelect && typeof TomSelect !== 'undefined') {
                editGroupTomSelect = new TomSelect(editGroupSelect, {
                    placeholder: 'Select or create group...',
                    create: true,
                    maxItems: 1,
                    createOnBlur: true,
                    searchField: ['text', 'value'],
                    render: {
                        option_create: function(data, escape) {
                            return '<div class="create">Add group: <strong>' + escape(data.input) + '</strong>&hellip;</div>';
                        }
                    }
                });
            }

            function updateEditValueField(type, currentValue = '') {
                let inputHtml = '';
                let helpText = '';

                switch(type) {
                    case 'text':
                        inputHtml = `<input type="text" class="form-control" id="edit_value" name="value" required placeholder="Enter text value" value="${currentValue}">`;
                        helpText = 'Enter a text value';
                        break;
                    case 'textarea':
                        inputHtml = `<textarea class="form-control" id="edit_value" name="value" rows="3" required placeholder="Enter long text">${currentValue}</textarea>`;
                        helpText = 'Enter multiline text content';
                        break;
                    case 'number':
                        inputHtml = `<input type="number" class="form-control" id="edit_value" name="value" required placeholder="0" value="${currentValue}">`;
                        helpText = 'Enter a numeric value';
                        break;
                    case 'boolean':
                        const isTrue = currentValue === '1' || currentValue === 'true' || currentValue === true;
                        inputHtml = `
                            <select class="form-select" id="edit_value" name="value" required>
                                <option value="">Select Value</option>
                                <option value="1" ${isTrue ? 'selected' : ''}>True (Enabled)</option>
                                <option value="0" ${!isTrue && currentValue !== '' ? 'selected' : ''}>False (Disabled)</option>
                            </select>
                        `;
                        helpText = 'Select true or false';
                        break;
                    case 'email':
                        inputHtml = `<input type="email" class="form-control" id="edit_value" name="value" required placeholder="user@example.com" value="${currentValue}">`;
                        helpText = 'Enter a valid email address';
                        break;
                    case 'url':
                        inputHtml = `<input type="url" class="form-control" id="edit_value" name="value" required placeholder="https://example.com" value="${currentValue}">`;
                        helpText = 'Enter a valid URL';
                        break;
                    case 'image':
                        const imagePreview = currentValue ? `<div class="mb-2"><img src="/storage/${currentValue}" alt="Current image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;"></div>` : '';
                        inputHtml = `
                            ${imagePreview}
                            <input type="file" class="form-control mb-2" id="edit_value_file" name="image_file" accept="image/*">
                            <input type="hidden" id="edit_value" name="value" value="${currentValue}">
                            <small class="text-muted">Upload new image to replace current one</small>
                        `;
                        helpText = 'Upload an image file';
                        break;
                    case 'color':
                        inputHtml = `<input type="color" class="form-control form-control-color" id="edit_value" name="value" required value="${currentValue || '#206bc4'}">`;
                        helpText = 'Select a color value';
                        break;
                    case 'json':
                        inputHtml = `<textarea class="form-control" id="edit_value" name="value" rows="4" required placeholder='{"key": "value"}'>${currentValue}</textarea>`;
                        helpText = 'Enter valid JSON format (e.g., ["item1", "item2"] or {"key": "value"})';
                        break;
                    default:
                        inputHtml = `<input type="text" class="form-control" id="edit_value" name="value" required value="${currentValue}">`;
                        helpText = 'Enter the setting value';
                }

                editValueContainer.innerHTML = inputHtml;
                editValueHelp.textContent = helpText;

                // Handle file inputs
                const fileInput = document.getElementById('edit_value_file');
                const hiddenInput = document.getElementById('edit_value');
                if (fileInput && hiddenInput) {
                    fileInput.addEventListener('change', function() {
                        if (this.files[0]) {
                            hiddenInput.value = this.files[0].name; // Temporary, should be handled by backend
                        }
                    });
                }

                // Initialize TomSelect for boolean values
                if (type === 'boolean') {
                    const boolSelect = document.getElementById('edit_value');
                    if (boolSelect && typeof TomSelect !== 'undefined') {
                        new TomSelect(boolSelect, {
                            placeholder: 'Select true or false...',
                            create: false,
                            maxItems: 1
                        });
                    }
                }
            }

            // Listen for type changes (works with TomSelect)
            if (editTypeTomSelect) {
                editTypeTomSelect.on('change', function(value) {
                    const currentValue = document.getElementById('edit_value')?.value || '';
                    updateEditValueField(value, currentValue);
                });
            } else {
                editTypeSelect.addEventListener('change', function() {
                    const currentValue = document.getElementById('edit_value')?.value || '';
                    updateEditValueField(this.value, currentValue);
                });
            }

            // Store the function globally so it can be called from settings.js
            window.updateEditValueField = updateEditValueField;
            window.editTypeTomSelect = editTypeTomSelect;
            window.editGroupTomSelect = editGroupTomSelect;
        });
    </script>
@endcan
