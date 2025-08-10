{{-- Universal Property Editor for Page Builder --}}
{{-- 
Dynamic property editor that adapts to any component's property definitions
Uses Tabler.io form components for consistent UI
--}}

<div class="property-editor" id="property-editor" style="width: 350px; height: 100%; display: none;">
    <!-- Editor Header -->
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0">
            <i class="ti ti-settings me-2"></i>
            <span id="editor-title">Properties</span>
        </h3>
        <button class="btn btn-ghost-secondary btn-sm" id="close-editor" title="Close Editor">
            <i class="ti ti-x"></i>
        </button>
    </div>

    <!-- Editor Content -->
    <div class="card-body" id="editor-content">
        <div class="d-flex align-items-center justify-content-center py-5">
            <div class="text-center">
                <i class="ti ti-click text-muted" style="font-size: 3rem;"></i>
                <h3 class="mt-3 text-muted">Select a Component</h3>
                <p class="text-muted">Click on a component in the canvas to edit its properties</p>
            </div>
        </div>
    </div>

    <!-- Editor Actions -->
    <div class="card-footer" id="editor-actions" style="display: none;">
        <div class="d-flex justify-content-between">
            <button class="btn btn-outline-secondary" id="reset-properties">
                <i class="ti ti-refresh me-1"></i>
                Reset
            </button>
            <div class="btn-group">
                <button class="btn btn-outline-primary" id="apply-properties">
                    <i class="ti ti-check me-1"></i>
                    Apply
                </button>
                <button class="btn btn-primary" id="apply-and-close">
                    <i class="ti ti-check me-1"></i>
                    Apply & Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Property Field Templates -->

<!-- Text Input Template -->
<template id="property-text-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <input type="text" class="form-control property-input" />
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Textarea Template -->
<template id="property-textarea-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <textarea class="form-control property-input" rows="3"></textarea>
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Select Template -->
<template id="property-select-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <select class="form-select property-input">
        </select>
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Number Input Template -->
<template id="property-number-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <input type="number" class="form-control property-input" />
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Boolean/Switch Template -->
<template id="property-boolean-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <div>
            <label class="form-check form-switch">
                <input class="form-check-input property-input" type="checkbox" />
                <span class="form-check-label property-switch-label">Enable</span>
            </label>
        </div>
        <div class="form-hint property-description"></div>
    </div>
</template>

<!-- URL Input Template -->
<template id="property-url-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <div class="input-group">
            <input type="url" class="form-control property-input" />
            <button class="btn btn-outline-secondary" type="button" title="Test URL">
                <i class="ti ti-external-link"></i>
            </button>
        </div>
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Color Picker Template -->
<template id="property-color-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <div class="input-group">
            <input type="text" class="form-control property-input" />
            <div class="input-group-text p-1">
                <input type="color" class="form-control form-control-color border-0 p-0" style="width: 2rem; height: 1.8rem;" />
            </div>
        </div>
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Icon Picker Template -->
<template id="property-icon-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="ti ti-square icon-preview"></i>
            </span>
            <input type="text" class="form-control property-input" placeholder="e.g., heart, star, download" />
            <button class="btn btn-outline-secondary" type="button" title="Browse Icons">
                <i class="ti ti-search"></i>
            </button>
        </div>
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Image Upload Template -->
<template id="property-image-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <div class="image-upload-area">
            <input type="text" class="form-control property-input mb-2" placeholder="Image URL" />
            <div class="image-preview" style="display: none;">
                <img class="img-thumbnail" style="max-width: 200px; max-height: 150px;" />
                <button type="button" class="btn btn-sm btn-outline-danger mt-1 remove-image">
                    <i class="ti ti-trash"></i> Remove
                </button>
            </div>
            <div class="upload-placeholder text-center p-3 border border-dashed rounded">
                <i class="ti ti-upload text-muted"></i>
                <p class="text-muted mb-0">Drag image here or <a href="#" class="browse-image">browse</a></p>
            </div>
        </div>
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Checkbox Template -->
<template id="property-checkbox-template">
    <div class="mb-3 property-field" data-property="">
        <div class="form-check form-switch">
            <input class="form-check-input property-input" type="checkbox" />
            <label class="form-check-label property-label"></label>
        </div>
        <div class="form-hint property-description"></div>
    </div>
</template>

<!-- Multi-select Template -->
<template id="property-multiselect-template">
    <div class="mb-3 property-field" data-property="">
        <label class="form-label property-label"></label>
        <select class="form-select property-input" multiple size="4">
        </select>
        <div class="form-hint property-description"></div>
        <div class="invalid-feedback property-error"></div>
    </div>
</template>

<!-- Property Group Template -->
<template id="property-group-template">
    <div class="property-group mb-4">
        <div class="property-group-header d-flex align-items-center justify-content-between p-2 bg-light rounded-top border">
            <h5 class="mb-0 property-group-title"></h5>
            <button class="btn btn-ghost-secondary btn-sm group-toggle" type="button">
                <i class="ti ti-chevron-down"></i>
            </button>
        </div>
        <div class="property-group-content border border-top-0 rounded-bottom p-3">
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const PropertyEditor = {
        container: document.getElementById('property-editor'),
        content: document.getElementById('editor-content'),
        title: document.getElementById('editor-title'),
        actions: document.getElementById('editor-actions'),
        
        currentComponent: null,
        currentProperties: {},
        originalProperties: {},
        propertyDefinitions: {},
        validationRules: {},
        
        init() {
            this.bindEvents();
        },
        
        bindEvents() {
            // Component selection events
            document.addEventListener('component-selected', this.handleComponentSelected.bind(this));
            document.addEventListener('component-deselected', this.handleComponentDeselected.bind(this));
            
            // Editor controls
            document.getElementById('close-editor').addEventListener('click', this.close.bind(this));
            document.getElementById('reset-properties').addEventListener('click', this.resetProperties.bind(this));
            document.getElementById('apply-properties').addEventListener('click', this.applyProperties.bind(this));
            document.getElementById('apply-and-close').addEventListener('click', this.applyAndClose.bind(this));
        },
        
        async handleComponentSelected(event) {
            const { componentId, instanceId, element } = event.detail;
            
            try {
                this.show();
                this.showLoading();
                
                // Get component info
                const response = await fetch(`/panel/builder/components/${componentId}/info`);
                const result = await response.json();
                
                if (result.success) {
                    this.currentComponent = {
                        componentId,
                        instanceId,
                        element,
                        metadata: result.data.metadata,
                        properties: result.data.properties,
                        validation: result.data.validation,
                        defaults: result.data.defaults
                    };
                    
                    this.propertyDefinitions = result.data.properties;
                    this.validationRules = result.data.validation;
                    
                    // Load current properties from element or use defaults
                    const savedProperties = this.loadPropertiesFromElement(element);
                    this.originalProperties = Object.keys(savedProperties).length > 0 ? savedProperties : { ...result.data.defaults };
                    
                    // Filter properties to only include those defined in property definitions
                    const validPropertyKeys = Object.keys(this.propertyDefinitions);
                    this.originalProperties = Object.fromEntries(
                        Object.entries(this.originalProperties)
                            .filter(([key]) => validPropertyKeys.includes(key))
                    );
                    
                    // Merge with defaults for any missing valid properties
                    Object.keys(this.propertyDefinitions).forEach(key => {
                        if (!(key in this.originalProperties) && key in result.data.defaults) {
                            this.originalProperties[key] = result.data.defaults[key];
                        }
                    });
                    
                    this.currentProperties = { ...this.originalProperties };
                    
                    console.log('Loaded properties from element:', savedProperties);
                    console.log('Using properties:', this.currentProperties);
                    
                    this.renderEditor();
                } else {
                    this.showError('Failed to load component properties');
                }
            } catch (error) {
                console.error('Error loading component properties:', error);
                this.showError('Error loading properties');
            }
        },
        
        loadPropertiesFromElement(element) {
            const properties = {};
            
            // Extract properties from data attributes
            Array.from(element.attributes).forEach(attr => {
                if (attr.name.startsWith('data-prop-')) {
                    const propName = attr.name.replace('data-prop-', '');
                    let propValue = attr.value;
                    
                    // Try to parse JSON values (for arrays/objects)
                    try {
                        if ((propValue.startsWith('[') && propValue.endsWith(']')) || 
                            (propValue.startsWith('{') && propValue.endsWith('}'))) {
                            propValue = JSON.parse(propValue);
                        }
                    } catch (e) {
                        // Keep original string value if JSON parsing fails
                    }
                    
                    properties[propName] = propValue;
                }
            });
            
            return properties;
        },
        
        handleComponentDeselected() {
            this.hide();
        },
        
        renderEditor() {
            this.title.textContent = `${this.currentComponent.metadata.name} Properties`;
            this.content.innerHTML = '';
            this.actions.style.display = 'block';
            
            // Handle nested property structure
            const properties = this.flattenProperties(this.propertyDefinitions);
            
            // Group properties by category
            const grouped = this.groupProperties(properties);
            
            Object.keys(grouped).forEach(groupName => {
                const groupProperties = grouped[groupName];
                const groupElement = this.createPropertyGroup(groupName, groupProperties);
                this.content.appendChild(groupElement);
            });
            
            // Bind property events
            this.bindPropertyEvents();
        },
        
        flattenProperties(properties) {
            const flattened = {};
            
            Object.keys(properties).forEach(key => {
                const property = properties[key];
                
                // Check if this is a nested group of properties
                if (property && typeof property === 'object' && !property.type && !property.label) {
                    // This is a group, flatten its contents
                    Object.keys(property).forEach(subKey => {
                        flattened[subKey] = property[subKey];
                    });
                } else {
                    // This is a direct property
                    flattened[key] = property;
                }
            });
            
            console.log('Flattened properties:', flattened);
            return flattened;
        },
        
        groupProperties(properties) {
            const groups = {
                'Basic': {},
                'Styling': {},
                'Advanced': {}
            };
            
            Object.keys(properties).forEach(key => {
                const property = properties[key];
                let group = 'Basic';
                
                if (key.includes('color') || key.includes('background') || key.includes('css') || key.includes('margin') || key.includes('padding')) {
                    group = 'Styling';
                } else if (key.includes('id') || key.includes('class') || key.includes('validation')) {
                    group = 'Advanced';
                }
                
                groups[group][key] = property;
            });
            
            // Remove empty groups
            Object.keys(groups).forEach(groupName => {
                if (Object.keys(groups[groupName]).length === 0) {
                    delete groups[groupName];
                }
            });
            
            return groups;
        },
        
        createPropertyGroup(groupName, properties) {
            const template = document.getElementById('property-group-template');
            const groupElement = template.content.cloneNode(true);
            
            groupElement.querySelector('.property-group-title').textContent = groupName;
            const content = groupElement.querySelector('.property-group-content');
            
            Object.keys(properties).forEach(key => {
                const property = properties[key];
                const fieldElement = this.createPropertyField(key, property);
                content.appendChild(fieldElement);
            });
            
            // Group toggle functionality
            const toggle = groupElement.querySelector('.group-toggle');
            toggle.addEventListener('click', () => {
                const groupContent = groupElement.querySelector('.property-group-content');
                const icon = toggle.querySelector('i');
                
                if (groupContent.style.display === 'none') {
                    groupContent.style.display = 'block';
                    icon.classList.remove('ti-chevron-right');
                    icon.classList.add('ti-chevron-down');
                } else {
                    groupContent.style.display = 'none';
                    icon.classList.remove('ti-chevron-down');
                    icon.classList.add('ti-chevron-right');
                }
            });
            
            return groupElement;
        },
        
        createPropertyField(key, property) {
            const type = property.type || 'text';
            const templateId = `property-${type}-template`;
            const template = document.getElementById(templateId);
            
            if (!template) {
                console.warn(`Template not found for property type: ${type}`);
                return this.createPropertyField(key, { ...property, type: 'text' });
            }
            
            const fieldElement = template.content.cloneNode(true);
            const container = fieldElement.querySelector('.property-field');
            
            container.dataset.property = key;
            
            // Set label
            const label = fieldElement.querySelector('.property-label');
            label.textContent = property.label || key;
            if (property.required) {
                label.innerHTML += ' <span class="text-danger">*</span>';
            }
            
            // Set description
            const description = fieldElement.querySelector('.property-description');
            if (description && property.description) {
                description.textContent = property.description;
            }
            
            // Set input properties
            const input = fieldElement.querySelector('.property-input');
            this.configureInput(input, key, property);
            
            return fieldElement;
        },
        
        configureInput(input, key, property) {
            let currentValue = this.currentProperties[key] || property.default || '';
            
            // Special handling for features property that might be JSON array
            if (key === 'features' && typeof currentValue === 'string') {
                try {
                    const parsed = JSON.parse(currentValue);
                    if (Array.isArray(parsed)) {
                        currentValue = parsed.join('\n');
                    }
                } catch (e) {
                    // Keep as string if not valid JSON
                }
            }
            
            switch (property.type) {
                case 'select':
                    this.configureSelect(input, property, currentValue);
                    break;
                case 'boolean':
                    input.checked = Boolean(currentValue);
                    break;
                case 'checkbox':
                    input.checked = Boolean(currentValue);
                    break;
                case 'multiselect':
                    this.configureMultiSelect(input, property, currentValue);
                    break;
                case 'number':
                    input.value = currentValue;
                    if (property.min !== undefined) input.min = property.min;
                    if (property.max !== undefined) input.max = property.max;
                    if (property.step !== undefined) input.step = property.step;
                    break;
                case 'textarea':
                    input.value = currentValue;
                    if (property.rows) input.rows = property.rows;
                    break;
                case 'url':
                case 'color':
                case 'icon':
                case 'image':
                    input.value = currentValue;
                    this.configureSpecialInput(input, property);
                    break;
                default:
                    input.value = currentValue;
                    if (property.placeholder) input.placeholder = property.placeholder;
                    if (property.maxlength) input.maxLength = property.maxlength;
            }
            
            // Add validation
            if (property.required) {
                input.required = true;
            }
            
            // Store original value
            input.dataset.originalValue = currentValue;
        },
        
        configureSelect(select, property, currentValue) {
            select.innerHTML = '';
            
            if (property.options) {
                Object.keys(property.options).forEach(value => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = property.options[value];
                    option.selected = value === currentValue;
                    select.appendChild(option);
                });
            }
        },
        
        configureMultiSelect(select, property, currentValue) {
            select.innerHTML = '';
            const values = Array.isArray(currentValue) ? currentValue : [];
            
            if (property.options) {
                Object.keys(property.options).forEach(value => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = property.options[value];
                    option.selected = values.includes(value);
                    select.appendChild(option);
                });
            }
        },
        
        configureSpecialInput(input, property) {
            const container = input.closest('.property-field');
            
            switch (property.type) {
                case 'url':
                    const testBtn = container.querySelector('button');
                    testBtn.addEventListener('click', () => {
                        if (input.value) {
                            window.open(input.value, '_blank');
                        }
                    });
                    break;
                    
                case 'color':
                    const colorPicker = container.querySelector('input[type="color"]');
                    colorPicker.value = input.value || '#000000';
                    
                    colorPicker.addEventListener('change', () => {
                        input.value = colorPicker.value;
                        input.dispatchEvent(new Event('input'));
                    });
                    break;
                    
                case 'icon':
                    const iconPreview = container.querySelector('.icon-preview');
                    const updateIcon = () => {
                        const iconName = input.value;
                        if (iconName) {
                            iconPreview.className = `ti ti-${iconName.replace('ti-', '').replace('ti ti-', '')} icon-preview`;
                        } else {
                            iconPreview.className = 'ti ti-square icon-preview';
                        }
                    };
                    
                    input.addEventListener('input', updateIcon);
                    updateIcon();
                    break;
                    
                case 'image':
                    this.configureImageInput(container, input);
                    break;
                    
                case 'checkbox':
                    // Checkbox is already handled by the template
                    break;
                    
                case 'multiselect':
                    // Handle multi-select options
                    if (property.options) {
                        Object.entries(property.options).forEach(([value, label]) => {
                            const option = document.createElement('option');
                            option.value = value;
                            option.textContent = label;
                            input.appendChild(option);
                        });
                    }
                    break;
            }
        },
        
        configureImageInput(container, input) {
            const preview = container.querySelector('.image-preview');
            const placeholder = container.querySelector('.upload-placeholder');
            const img = container.querySelector('img');
            const removeBtn = container.querySelector('.remove-image');
            
            const updatePreview = () => {
                if (input.value) {
                    img.src = input.value;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                } else {
                    preview.style.display = 'none';
                    placeholder.style.display = 'block';
                }
            };
            
            input.addEventListener('input', updatePreview);
            removeBtn.addEventListener('click', () => {
                input.value = '';
                updatePreview();
                input.dispatchEvent(new Event('input'));
            });
            
            updatePreview();
        },
        
        bindPropertyEvents() {
            const inputs = this.content.querySelectorAll('.property-input');
            
            console.log(`Binding events to ${inputs.length} property inputs`);
            
            inputs.forEach(input => {
                input.addEventListener('input', (e) => {
                    const propertyField = e.target.closest('.property-field');
                    const property = propertyField ? propertyField.dataset.property : null;
                    
                    if (!property) {
                        console.warn('Property field not found for input:', e.target);
                        return;
                    }
                    
                    console.log(`Property changed: ${property}`);
                    
                    let value;
                    
                    if (e.target.type === 'checkbox') {
                        value = e.target.checked;
                    } else if (e.target.multiple) {
                        // Handle multiselect
                        const selectedOptions = Array.from(e.target.selectedOptions);
                        value = selectedOptions.map(option => option.value);
                    } else {
                        value = e.target.value;
                        
                        // Special handling for features textarea - convert to JSON array
                        if (property === 'features' && e.target.tagName === 'TEXTAREA') {
                            const lines = value.split('\n').filter(line => line.trim());
                            value = JSON.stringify(lines);
                        }
                    }
                    
                    this.currentProperties[property] = value;
                    console.log(`Updated ${property} to:`, value);
                    this.validateProperty(property, value);
                    this.previewChanges();
                });
            });
        },
        
        validateProperty(property, value) {
            const field = this.content.querySelector(`[data-property="${property}"]`);
            
            // Check if field exists
            if (!field) {
                console.warn(`Property field not found for: ${property}`);
                return true; // Don't fail validation if field doesn't exist
            }
            
            const input = field.querySelector('.property-input');
            const error = field.querySelector('.property-error');
            
            // Check if input exists
            if (!input) {
                console.warn(`Property input not found for: ${property}`);
                return true;
            }
            
            // Clear previous validation
            input.classList.remove('is-invalid');
            if (error) error.textContent = '';
            
            // Validate based on rules
            const rules = this.validationRules[property];
            if (rules) {
                // Basic validation (extend as needed)
                if (rules.includes('required') && !value) {
                    this.showFieldError(field, 'This field is required');
                    return false;
                }
            }
            
            return true;
        },
        
        showFieldError(field, message) {
            if (!field) {
                console.warn('Cannot show error: field is null');
                return;
            }
            
            const input = field.querySelector('.property-input');
            const error = field.querySelector('.property-error');
            
            if (input) {
                input.classList.add('is-invalid');
            }
            if (error) {
                error.textContent = message;
            }
        },
        
        previewChanges() {
            // Implement live preview if needed
            console.log('Properties changed:', this.currentProperties);
        },
        
        resetProperties() {
            this.currentProperties = { ...this.originalProperties };
            this.renderEditor();
        },
        
        async applyProperties() {
            if (this.validateAllProperties()) {
                try {
                    // Re-render component with new properties
                    const response = await fetch('/panel/builder/components/render', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken
                        },
                        body: JSON.stringify({
                            component_id: this.currentComponent.componentId,
                            properties: this.currentProperties
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // Update component in canvas
                        const componentContent = this.currentComponent.element.querySelector('.component-content');
                        componentContent.innerHTML = result.data.html;
                        
                        // Save properties to data attributes for persistence
                        this.savePropertiesToElement();
                        
                        // Update stored properties
                        this.originalProperties = { ...this.currentProperties };
                        
                        this.showSuccess('Properties applied successfully');
                        
                        // Trigger canvas save to history
                        if (window.CanvasSystem && window.CanvasSystem.saveToHistory) {
                            window.CanvasSystem.saveToHistory();
                        }
                    } else {
                        this.showError('Failed to apply properties');
                    }
                } catch (error) {
                    console.error('Error applying properties:', error);
                    this.showError('Error applying properties');
                }
            }
        },
        
        savePropertiesToElement() {
            if (!this.currentComponent || !this.currentComponent.element) return;
            
            const element = this.currentComponent.element;
            
            // Clear existing property data attributes
            Array.from(element.attributes).forEach(attr => {
                if (attr.name.startsWith('data-prop-')) {
                    element.removeAttribute(attr.name);
                }
            });
            
            // Only save properties that are defined in the component's property definitions
            const validPropertyKeys = Object.keys(this.propertyDefinitions);
            
            // Set new property data attributes - only for valid properties
            Object.entries(this.currentProperties).forEach(([key, value]) => {
                // Only save if the property is defined in component's property definitions
                if (validPropertyKeys.includes(key) && value !== null && value !== undefined && value !== '') {
                    // Convert arrays/objects to JSON strings
                    const attributeValue = typeof value === 'object' ? JSON.stringify(value) : String(value);
                    element.setAttribute(`data-prop-${key}`, attributeValue);
                }
            });
            
            console.log('Properties saved to element (filtered):', 
                Object.fromEntries(
                    Object.entries(this.currentProperties)
                        .filter(([key]) => validPropertyKeys.includes(key))
                )
            );
        },
        
        async applyAndClose() {
            await this.applyProperties();
            this.close();
        },
        
        validateAllProperties() {
            let isValid = true;
            
            // Only validate properties that have corresponding DOM elements
            const propertyFields = this.content.querySelectorAll('.property-field');
            const availableProperties = new Set();
            
            propertyFields.forEach(field => {
                const propertyName = field.dataset.property;
                if (propertyName) {
                    availableProperties.add(propertyName);
                }
            });
            
            console.log('Available property fields:', Array.from(availableProperties));
            console.log('Current properties:', Object.keys(this.currentProperties));
            
            // Only validate properties that have DOM elements
            Object.keys(this.currentProperties).forEach(property => {
                if (availableProperties.has(property)) {
                    console.log(`Validating property: ${property} = ${this.currentProperties[property]}`);
                    if (!this.validateProperty(property, this.currentProperties[property])) {
                        isValid = false;
                    }
                } else {
                    console.log(`Skipping validation for property without DOM element: ${property}`);
                }
            });
            
            return isValid;
        },
        
        show() {
            this.container.style.display = 'block';
        },
        
        hide() {
            this.container.style.display = 'none';
            this.currentComponent = null;
        },
        
        close() {
            this.hide();
            
            // Deselect component in canvas
            if (this.currentComponent && this.currentComponent.element) {
                this.currentComponent.element.classList.remove('selected');
            }
        },
        
        showLoading() {
            this.content.innerHTML = `
                <div class="d-flex align-items-center justify-content-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading properties...</span>
                    </div>
                </div>
            `;
            this.actions.style.display = 'none';
        },
        
        showError(message) {
            this.content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i>
                    ${message}
                </div>
            `;
        },
        
        showSuccess(message) {
            // Show temporary success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                <i class="ti ti-check me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            this.content.insertBefore(alert, this.content.firstChild);
            
            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 3000);
        }
    };
    
    PropertyEditor.init();
});
</script>

<style>
.property-editor {
    border-left: 1px solid var(--tblr-border-color);
    background: var(--tblr-bg-surface);
    overflow-y: auto;
}

.property-field {
    position: relative;
}

.property-group-header {
    cursor: pointer;
    user-select: none;
}

.property-group-header:hover {
    background-color: var(--tblr-bg-surface-secondary) !important;
}

.group-toggle {
    transition: transform 0.2s ease;
}

.image-upload-area {
    position: relative;
}

.upload-placeholder {
    cursor: pointer;
    transition: all 0.2s ease;
}

.upload-placeholder:hover {
    background-color: var(--tblr-bg-surface-secondary);
    border-color: var(--tblr-primary);
}

.form-control.is-invalid {
    border-color: var(--tblr-danger);
}

.property-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(var(--tblr-primary-rgb), 0.25);
}

.icon-preview {
    font-size: 1.2rem;
}
</style>
