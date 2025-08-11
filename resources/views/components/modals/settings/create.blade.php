<!-- Create Setting Modal -->
@can('create-settings')
    <div class="modal modal-blur fade" id="createSettingModal"  tabindex="-1" aria-labelledby="createSettingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSettingModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M12 1v6m0 6v6"/>
                            <path d="m21 12l-6 0m-6 0l-6 0"/>
                            <path d="m20.2 7.8l-4.2 4.2m-4.2 -4.2l-4.2 4.2"/>
                            <path d="m20.2 16.2l-4.2 -4.2m-4.2 4.2l-4.2 -4.2"/>
                        </svg>
                        Create New Setting
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panel.settings.store') }}" id="createSettingForm">
                    @csrf
                    <div class="modal-body">
                        <!-- Row 1: Setting Key (Full Width) -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="create_key" class="form-label fw-medium">Setting Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_key" name="key" required
                                    placeholder="e.g., app_name, site_logo, maintenance_mode">
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
                                <label for="create_type" class="form-label fw-medium">Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="create_type" name="type" required>
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
                                <label for="create_group" class="form-label fw-medium">Group</label>
                                <select class="form-select" id="create_group" name="group">
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
                                <label for="create_description" class="form-label fw-medium">Description</label>
                                <textarea class="form-control" id="create_description" name="description" rows="2"
                                    placeholder="Brief description of what this setting controls"></textarea>
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
                                <label for="create_value" class="form-label fw-medium">Value <span class="text-danger">*</span></label>
                                <div id="create_value_container">
                                    <!-- Default text input -->
                                    <input type="text" class="form-control" id="create_value" name="value" required>
                                </div>
                                <small class="form-text text-muted" id="create_value_help">
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
                            Create Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createTypeSelect = document.getElementById('create_type');
            const createGroupSelect = document.getElementById('create_group');
            const createValueContainer = document.getElementById('create_value_container');
            const createValueHelp = document.getElementById('create_value_help');

            // Initialize TomSelect for Type
            let typeTomSelect = null;
            if (createTypeSelect && typeof TomSelect !== 'undefined') {
                typeTomSelect = new TomSelect(createTypeSelect, {
                    placeholder: 'Select setting type...',
                    create: false,
                    maxItems: 1,
                    searchField: ['text', 'value']
                });
            }

            // Initialize TomSelect for Group  
            let groupTomSelect = null;
            if (createGroupSelect && typeof TomSelect !== 'undefined') {
                groupTomSelect = new TomSelect(createGroupSelect, {
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

            function updateCreateValueField(type) {
                let inputHtml = '';
                let helpText = '';

                switch(type) {
                    case 'text':
                        inputHtml = '<input type="text" class="form-control" id="create_value" name="value" required placeholder="Enter text value">';
                        helpText = 'Enter a text value';
                        break;
                    case 'textarea':
                        inputHtml = '<textarea class="form-control" id="create_value" name="value" rows="3" required placeholder="Enter long text"></textarea>';
                        helpText = 'Enter multiline text content';
                        break;
                    case 'number':
                        inputHtml = '<input type="number" class="form-control" id="create_value" name="value" required placeholder="0">';
                        helpText = 'Enter a numeric value';
                        break;
                    case 'boolean':
                        inputHtml = `
                            <select class="form-select" id="create_value" name="value" required>
                                <option value="">Select Value</option>
                                <option value="1">True (Enabled)</option>
                                <option value="0">False (Disabled)</option>
                            </select>
                        `;
                        helpText = 'Select true or false';
                        break;
                    case 'email':
                        inputHtml = '<input type="email" class="form-control" id="create_value" name="value" required placeholder="user@example.com">';
                        helpText = 'Enter a valid email address';
                        break;
                    case 'url':
                        inputHtml = '<input type="url" class="form-control" id="create_value" name="value" required placeholder="https://example.com">';
                        helpText = 'Enter a valid URL';
                        break;
                    case 'image':
                        inputHtml = '<input type="file" class="form-control" id="create_value_file" accept="image/*"><input type="hidden" id="create_value" name="value">';
                        helpText = 'Upload an image file';
                        break;
                    case 'color':
                        inputHtml = '<input type="color" class="form-control form-control-color" id="create_value" name="value" required value="#206bc4">';
                        helpText = 'Select a color value';
                        break;
                    case 'json':
                        inputHtml = '<textarea class="form-control" id="create_value" name="value" rows="4" required placeholder=\'{"key": "value"}\'></textarea>';
                        helpText = 'Enter valid JSON format (e.g., ["item1", "item2"] or {"key": "value"})';
                        break;
                    default:
                        inputHtml = '<input type="text" class="form-control" id="create_value" name="value" required>';
                        helpText = 'Enter the setting value';
                }

                createValueContainer.innerHTML = inputHtml;
                createValueHelp.textContent = helpText;

                // Handle file inputs
                const fileInput = document.getElementById('create_value_file');
                const hiddenInput = document.getElementById('create_value');
                if (fileInput && hiddenInput) {
                    fileInput.addEventListener('change', function() {
                        if (this.files[0]) {
                            hiddenInput.value = this.files[0].name; // Temporary, should be handled by backend
                        }
                    });
                }

                // Initialize TomSelect for boolean values
                if (type === 'boolean') {
                    const boolSelect = document.getElementById('create_value');
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
            if (typeTomSelect) {
                typeTomSelect.on('change', function(value) {
                    updateCreateValueField(value);
                });
            } else {
                createTypeSelect.addEventListener('change', function() {
                    updateCreateValueField(this.value);
                });
            }
        });
    </script>
@endcan
                                       