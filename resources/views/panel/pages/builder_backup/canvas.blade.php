{{-- Canvas System for Page Builder --}}
{{-- 
Canvas area where components can be dropped and arranged
Uses Sortable.js for drag & drop functionality
--}}

<div class="builder-canvas-container">
    <!-- Canvas Toolbar -->
    <div class="canvas-toolbar border-bottom d-flex align-items-center justify-content-between p-3">
        <div class="d-flex align-items-center">
            <h3 class="mb-0 me-3">Page Canvas</h3>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm active" data-view="desktop">
                    <i class="ti ti-device-desktop me-1"></i>
                    Desktop
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" data-view="tablet">
                    <i class="ti ti-device-tablet me-1"></i>
                    Tablet
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" data-view="mobile">
                    <i class="ti ti-device-mobile me-1"></i>
                    Mobile
                </button>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" id="canvas-undo" title="Undo">
                <i class="ti ti-arrow-back-up"></i>
            </button>
            <button class="btn btn-outline-secondary btn-sm" id="canvas-redo" title="Redo">
                <i class="ti ti-arrow-forward-up"></i>
            </button>
            <div class="vr"></div>
            <button class="btn btn-outline-success btn-sm" id="save-page" title="Save Page">
                <i class="ti ti-device-floppy me-1"></i>
                Save
            </button>
            <button class="btn btn-outline-info btn-sm" id="preview-page" title="Preview Page">
                <i class="ti ti-eye me-1"></i>
                Preview
            </button>
        </div>
    </div>

    <!-- Canvas Area -->
    <div class="canvas-area" id="builder-canvas">
        <div class="canvas-viewport" id="canvas-viewport" data-view="desktop">
            <div class="canvas-content" id="canvas-content">
                <!-- Drop Zone -->
                <div class="drop-zone active" id="main-drop-zone">
                    <div class="drop-zone-content">
                        <div class="text-center">
                            <i class="ti ti-drag-drop text-muted" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 text-muted">Start Building Your Page</h3>
                            <p class="text-muted">Drag components from the sidebar to start building your page</p>
                            <div class="mt-3">
                                <span class="badge bg-success me-1">Phase 0 ✓</span>
                                <span class="badge bg-success me-1">Phase 1 ✓</span>
                                <span class="badge bg-success me-1">Phase 2 ✓</span>
                                <span class="badge bg-success me-1">Phase 3 ✓</span>
                                <span class="badge bg-primary">Phase 4 - Builder System</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Component Element Template -->
<template id="canvas-component-template">
    <div class="canvas-component" data-component-id="">
        <div class="component-wrapper">
            <div class="component-controls">
                <div class="component-toolbar">
                    <button class="btn btn-sm btn-outline-primary component-edit" title="Edit Properties">
                        <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary component-move" title="Move Component">
                        <i class="ti ti-arrows-move"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info component-duplicate" title="Duplicate">
                        <i class="ti ti-copy"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger component-delete" title="Delete">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
            <div class="component-content"></div>
        </div>
        <div class="component-drop-zones">
            <div class="drop-zone" data-position="before"></div>
            <div class="drop-zone" data-position="after"></div>
        </div>
    </div>
</template>

<!-- Drop Zone Template -->
<template id="drop-zone-template">
    <div class="drop-zone" data-position="">
        <div class="drop-zone-indicator">
            <i class="ti ti-plus"></i>
            <span>Drop component here</span>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const CanvasSystem = {
        canvas: document.getElementById('canvas-content'),
        viewport: document.getElementById('canvas-viewport'),
        mainDropZone: document.getElementById('main-drop-zone'),
        sortable: null,
        
        components: [],
        history: [],
        historyIndex: -1,
        selectedComponent: null,
        
        init() {
            // Verify all required elements exist
            if (!this.canvas) {
                console.error('Canvas element not found: canvas-content');
                return;
            }
            if (!this.viewport) {
                console.error('Viewport element not found: canvas-viewport');
                return;
            }
            if (!this.mainDropZone) {
                console.error('Main drop zone not found: main-drop-zone');
                return;
            }
            
            console.log('Canvas system initialized successfully with Sortable.js');
            this.initSortable();
            this.bindEvents();
            this.loadPageData();
        },
        
        initSortable() {
            // Check if Sortable is available
            if (typeof Sortable === 'undefined') {
                console.error('Sortable.js not loaded! Please include Sortable.js library.');
                return;
            }
            
            // Initialize Sortable.js on the canvas for reordering existing components
            this.sortable = Sortable.create(this.canvas, {
                group: {
                    name: 'canvas-components',
                    pull: false,
                    put: true // Allow components from sidebar
                },
                animation: 200,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                fallbackClass: 'sortable-fallback',
                scroll: true,
                scrollSensitivity: 100,
                scrollSpeed: 15,
                draggable: '.canvas-component', // Only canvas components are draggable
                handle: '.canvas-component', // Allow dragging entire component, not just handle
                
                // Handle adding new components from sidebar
                onAdd: (evt) => {
                    const item = evt.item;
                    const componentId = item.dataset.componentId;
                    
                    console.log('Adding component to canvas:', componentId);
                    if (componentId) {
                        this.replaceWithRealComponent(item, componentId);
                        this.hideMainDropZone();
                    } else {
                        console.warn('No component ID found on dropped item');
                        item.remove();
                    }
                },
                
                // Handle reordering existing components
                onSort: (evt) => {
                    console.log('Component reordered from index', evt.oldIndex, 'to', evt.newIndex);
                    this.updateComponentOrder();
                    this.saveToHistory();
                },
                
                // Visual feedback during drag
                onStart: (evt) => {
                    console.log('Drag started:', evt.item);
                    evt.item.classList.add('dragging');
                    this.canvas.classList.add('sorting-active');
                    
                    // Show visual indicators
                    this.showDropIndicators();
                },
                
                onEnd: (evt) => {
                    console.log('Drag ended:', evt);
                    evt.item.classList.remove('dragging');
                    this.canvas.classList.remove('sorting-active');
                    
                    // Hide visual indicators
                    this.hideDropIndicators();
                },
                
                // Enhanced move validation
                onMove: (evt) => {
                    // Allow moving between canvas components
                    const related = evt.related;
                    return !related.classList.contains('drop-zone-content') && 
                           !related.classList.contains('component-controls');
                },
                
                // Filter out non-draggable elements
                filter: '.component-controls, .component-toolbar',
                preventOnFilter: false
            });
            
            console.log('Sortable.js initialized for canvas drag & drop reordering');
        },
        
        bindEvents() {
            // Viewport switching
            document.querySelectorAll('[data-view]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const view = e.target.dataset.view || e.currentTarget.dataset.view;
                    console.log('Switching viewport to:', view);
                    if (view) {
                        this.switchViewport(view);
                    } else {
                        console.warn('No view data found on button:', e.target);
                    }
                });
            });
            
            // Canvas click events (for component selection)
            this.canvas.addEventListener('click', this.handleCanvasClick.bind(this));
            
            // Global component events from sidebar
            document.addEventListener('add-component', this.handleAddComponent.bind(this));
            
            // Save and preview
            document.getElementById('save-page').addEventListener('click', this.savePage.bind(this));
            document.getElementById('preview-page').addEventListener('click', this.previewPage.bind(this));
            
            // Undo/Redo
            document.getElementById('canvas-undo').addEventListener('click', this.undo.bind(this));
            document.getElementById('canvas-redo').addEventListener('click', this.redo.bind(this));
            
            // Keyboard shortcuts
            document.addEventListener('keydown', this.handleKeyboard.bind(this));
        },
        
        switchViewport(view) {
            // Validate view parameter
            if (!view) {
                console.warn('switchViewport: view parameter is required');
                return;
            }
            
            // Update active button
            document.querySelectorAll('[data-view]').forEach(btn => {
                if (btn.classList) {
                    btn.classList.remove('active');
                }
            });
            
            const targetButton = document.querySelector(`[data-view="${view}"]`);
            if (targetButton && targetButton.classList) {
                targetButton.classList.add('active');
            } else {
                console.warn(`switchViewport: button not found for view: ${view}`);
            }
            
            // Update viewport
            if (this.viewport) {
                this.viewport.dataset.view = view;
                this.viewport.className = `canvas-viewport canvas-${view}`;
            } else {
                console.warn('switchViewport: viewport element not found');
            }
        },
        
        async replaceWithRealComponent(placeholderItem, componentId) {
            try {
                // Get component HTML from server
                const response = await fetch('/panel/builder/components/render', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({
                        component_id: componentId,
                        properties: {}
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Create real component element
                    const componentElement = this.createComponentElement(componentId, result.data.html);
                    
                    // Replace placeholder with real component
                    placeholderItem.parentNode.replaceChild(componentElement, placeholderItem);
                    
                    // Add to components array
                    this.components.push({
                        id: this.generateId(),
                        componentId,
                        element: componentElement,
                        instanceId: componentElement.dataset.instanceId
                    });
                    
                    this.saveToHistory();
                    console.log('Component added successfully:', componentId);
                } else {
                    console.error('Failed to render component:', result.message);
                    placeholderItem.remove(); // Remove placeholder if failed
                }
            } catch (error) {
                console.error('Error adding component:', error);
                placeholderItem.remove(); // Remove placeholder if failed
            }
        },
        
        hideMainDropZone() {
            if (this.mainDropZone && this.components.length > 0) {
                this.mainDropZone.style.display = 'none';
            }
        },
        
        showMainDropZone() {
            if (this.mainDropZone && this.components.length === 0) {
                this.mainDropZone.style.display = 'block';
            }
        },
        
        createComponentElement(componentId, html) {
            const template = document.getElementById('canvas-component-template');
            if (!template) {
                console.error('Component template not found');
                return null;
            }
            
            const element = template.content.cloneNode(true);
            const componentDiv = element.querySelector('.canvas-component');
            
            if (!componentDiv) {
                console.error('Component div not found in template');
                return null;
            }
            
            componentDiv.dataset.componentId = componentId;
            componentDiv.dataset.instanceId = this.generateId();
            
            const content = componentDiv.querySelector('.component-content');
            if (content) {
                content.innerHTML = html;
            }
            
            // Save default properties to data attributes
            this.saveDefaultPropertiesToElement(componentDiv, componentId);
            
            // Bind component controls
            this.bindComponentControls(componentDiv);
            
            return componentDiv;
        },
        
        async saveDefaultPropertiesToElement(element, componentId) {
            try {
                // Get component info to retrieve default properties
                const response = await fetch(`/panel/builder/components/${componentId}/info`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                });
                
                const result = await response.json();
                
                if (result.success && result.data.defaults) {
                    // Save default properties as data attributes
                    Object.entries(result.data.defaults).forEach(([key, value]) => {
                        if (value !== null && value !== undefined && value !== '') {
                            const attributeValue = typeof value === 'object' ? JSON.stringify(value) : String(value);
                            element.setAttribute(`data-prop-${key}`, attributeValue);
                        }
                    });
                    
                    console.log('Default properties saved for component:', componentId, result.data.defaults);
                }
            } catch (error) {
                console.error('Error saving default properties:', error);
            }
        },
        
        bindComponentControls(componentElement) {
            if (!componentElement) return;
            
            const editBtn = componentElement.querySelector('.component-edit');
            const duplicateBtn = componentElement.querySelector('.component-duplicate');
            const deleteBtn = componentElement.querySelector('.component-delete');
            
            if (editBtn) {
                editBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.editComponent(componentElement);
                });
            }
            
            if (duplicateBtn) {
                duplicateBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.duplicateComponent(componentElement);
                });
            }
            
            if (deleteBtn) {
                deleteBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.deleteComponent(componentElement);
                });
            }
            
            // Make component selectable
            componentElement.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectComponent(componentElement);
            });
        },
        
        selectComponent(element) {
            if (!element) return;
            
            // Remove previous selection
            document.querySelectorAll('.canvas-component.selected').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Select new component
            element.classList.add('selected');
            this.selectedComponent = element;
            
            // Emit event for property editor
            const event = new CustomEvent('component-selected', {
                detail: {
                    componentId: element.dataset.componentId,
                    instanceId: element.dataset.instanceId,
                    element: element
                }
            });
            document.dispatchEvent(event);
        },
        
        editComponent(element) {
            this.selectComponent(element);
            // Property editor will handle the editing
        },
        
        duplicateComponent(element) {
            if (!element) return;
            
            const clone = element.cloneNode(true);
            clone.dataset.instanceId = this.generateId();
            
            // Re-bind events for the clone
            this.bindComponentControls(clone);
            
            // Insert after the original
            if (element.parentNode) {
                element.parentNode.insertBefore(clone, element.nextSibling);
            }
            
            this.saveToHistory();
        },
        
        deleteComponent(element) {
            if (!element) return;
            
            if (confirm('Are you sure you want to delete this component?')) {
                element.remove();
                
                // Remove from components array
                this.components = this.components.filter(comp => 
                    comp.element !== element
                );
                
                // Show main drop zone if no components left
                if (this.components.length === 0) {
                    this.showMainDropZone();
                }
                
                this.saveToHistory();
            }
        },
        
        updateComponentOrder() {
            // Update the internal components array based on DOM order
            const canvasComponents = this.canvas.querySelectorAll('.canvas-component');
            const newOrder = [];
            
            canvasComponents.forEach((element, index) => {
                const componentId = element.dataset.componentId;
                const instanceId = element.dataset.instanceId;
                
                // Find the component in our array and update its order
                const component = this.components.find(comp => 
                    comp.element === element || comp.instanceId === instanceId
                );
                
                if (component) {
                    component.order = index;
                    newOrder.push(component);
                }
            });
            
            // Update the components array with new order
            this.components = newOrder;
            console.log('Component order updated:', this.components.map(c => c.componentId));
        },
        
        showDropIndicators() {
            // Add visual indicators during drag operations
            this.canvas.querySelectorAll('.canvas-component').forEach((component, index) => {
                // Add drop indicator elements if they don't exist
                if (!component.querySelector('.drop-indicator-before')) {
                    const beforeIndicator = document.createElement('div');
                    beforeIndicator.className = 'drop-indicator drop-indicator-before';
                    beforeIndicator.innerHTML = '<div class="drop-line"></div>';
                    component.insertBefore(beforeIndicator, component.firstChild);
                }
                
                if (!component.querySelector('.drop-indicator-after')) {
                    const afterIndicator = document.createElement('div');
                    afterIndicator.className = 'drop-indicator drop-indicator-after';
                    afterIndicator.innerHTML = '<div class="drop-line"></div>';
                    component.appendChild(afterIndicator);
                }
            });
        },
        
        hideDropIndicators() {
            // Remove visual indicators after drag operations
            this.canvas.querySelectorAll('.drop-indicator').forEach(indicator => {
                indicator.remove();
            });
        },
        
        generateId() {
            return 'comp_' + Math.random().toString(36).substr(2, 9);
        },
        
        saveToHistory() {
            const state = {
                html: this.canvas.innerHTML,
                components: this.getCanvasState().components,
                timestamp: Date.now()
            };
            
            // Remove future history if we're not at the end
            this.history = this.history.slice(0, this.historyIndex + 1);
            this.history.push(state);
            this.historyIndex++;
            
            // Limit history size
            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
            
            console.log('Saved to history. Index:', this.historyIndex, 'Total:', this.history.length);
        },
        
        undo() {
            if (this.historyIndex > 0) {
                this.historyIndex--;
                this.restoreFromHistory();
            }
        },
        
        redo() {
            if (this.historyIndex < this.history.length - 1) {
                this.historyIndex++;
                this.restoreFromHistory();
            }
        },
        
        restoreFromHistory() {
            const state = this.history[this.historyIndex];
            if (state) {
                // Store current HTML and components
                const currentHTML = this.canvas.innerHTML;
                const currentComponents = this.getCanvasState().components;
                
                // Restore canvas HTML
                this.canvas.innerHTML = state.html || '';
                
                // Re-bind all events after restoring HTML
                this.rebindEvents();
                
                // Update components array
                this.components = state.components || [];
                
                // Show notification
                this.showNotification(`State restored (${this.historyIndex + 1}/${this.history.length})`, 'info');
                
                console.log('Restored state:', state);
            }
        },
        
        handleAddComponent(e) {
            const { componentId, component } = e.detail;
            this.addComponent(componentId, component);
            this.hideMainDropZone();
        },
        
        async addComponent(componentId, componentData, targetElement = null) {
            try {
                // Get component HTML
                const response = await fetch('/panel/builder/components/render', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({
                        component_id: componentId,
                        properties: {}
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const componentElement = this.createComponentElement(componentId, result.data.html);
                    
                    if (targetElement && targetElement.classList.contains('drop-zone')) {
                        this.insertComponentAtDropZone(componentElement, targetElement);
                    } else {
                        this.canvas.appendChild(componentElement);
                    }
                    
                    this.components.push({
                        id: this.generateId(),
                        componentId,
                        data: componentData,
                        element: componentElement
                    });
                    
                    this.saveToHistory();
                    this.updateCanvas();
                } else {
                    this.showError('Failed to add component');
                }
            } catch (error) {
                console.error('Error adding component:', error);
                this.showError('Error adding component');
            }
        },
        
        bindComponentControls(componentElement) {
            const editBtn = componentElement.querySelector('.component-edit');
            const moveBtn = componentElement.querySelector('.component-move');
            const duplicateBtn = componentElement.querySelector('.component-duplicate');
            const deleteBtn = componentElement.querySelector('.component-delete');
            
            editBtn.addEventListener('click', () => {
                this.editComponent(componentElement);
            });
            
            duplicateBtn.addEventListener('click', () => {
                this.duplicateComponent(componentElement);
            });
            
            deleteBtn.addEventListener('click', () => {
                this.deleteComponent(componentElement);
            });
            
            // Make component selectable
            componentElement.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectComponent(componentElement);
            });
        },
        
        selectComponent(element) {
            // Remove previous selection
            document.querySelectorAll('.canvas-component.selected').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Select new component
            element.classList.add('selected');
            this.selectedComponent = element;
            
            // Emit event for property editor
            const event = new CustomEvent('component-selected', {
                detail: {
                    componentId: element.dataset.componentId,
                    instanceId: element.dataset.instanceId,
                    element: element
                }
            });
            document.dispatchEvent(event);
        },
        
        editComponent(element) {
            this.selectComponent(element);
            // Property editor will handle the editing
        },
        
        duplicateComponent(element) {
            const clone = element.cloneNode(true);
            clone.dataset.instanceId = this.generateId();
            
            // Re-bind events for the clone
            this.bindComponentControls(clone);
            
            // Insert after the original
            element.parentNode.insertBefore(clone, element.nextSibling);
            
            this.saveToHistory();
        },
        
        deleteComponent(element) {
            if (confirm('Are you sure you want to delete this component?')) {
                element.remove();
                
                // Remove from components array
                this.components = this.components.filter(comp => 
                    comp.element !== element
                );
                
                this.saveToHistory();
                this.updateCanvas();
            }
        },
        
        insertComponentAtDropZone(componentElement, dropZone) {
            const position = dropZone.dataset.position;
            const parentComponent = dropZone.closest('.canvas-component');
            
            if (position === 'before') {
                parentComponent.parentNode.insertBefore(componentElement, parentComponent);
            } else if (position === 'after') {
                parentComponent.parentNode.insertBefore(componentElement, parentComponent.nextSibling);
            }
        },
        
        hideMainDropZone() {
            if (this.components.length > 0) {
                this.mainDropZone.style.display = 'none';
            }
        },
        
        showMainDropZone() {
            if (this.components.length === 0) {
                this.mainDropZone.style.display = 'block';
            }
        },
        
        updateCanvas() {
            // Update drop zones, component order, etc.
            this.hideMainDropZone();
            this.updateDropZones();
        },
        
        updateDropZones() {
            // Add drop zones between components
            const components = this.canvas.querySelectorAll('.canvas-component');
            components.forEach((comp, index) => {
                // Logic for dynamic drop zones can be added here
            });
        },
        
        handleCanvasClick(e) {
            if (e.target === this.canvas || e.target.classList.contains('canvas-content')) {
                // Deselect all components
                document.querySelectorAll('.canvas-component.selected').forEach(el => {
                    el.classList.remove('selected');
                });
                this.selectedComponent = null;
                
                // Hide property editor
                const event = new CustomEvent('component-deselected');
                document.dispatchEvent(event);
            }
        },
        
        handleKeyboard(e) {
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case 'z':
                        e.preventDefault();
                        this.undo();
                        break;
                    case 'y':
                        e.preventDefault();
                        this.redo();
                        break;
                    case 's':
                        e.preventDefault();
                        this.savePage();
                        break;
                }
            }
            
            if (e.key === 'Delete' && this.selectedComponent) {
                this.deleteComponent(this.selectedComponent);
            }
        },
        
        undo() {
            if (this.historyIndex > 0) {
                this.historyIndex--;
                this.restoreFromHistory();
                console.log('Undo performed. History index:', this.historyIndex);
            } else {
                this.showNotification('No more actions to undo', 'warning');
            }
        },
        
        redo() {
            if (this.historyIndex < this.history.length - 1) {
                this.historyIndex++;
                this.restoreFromHistory();
                console.log('Redo performed. History index:', this.historyIndex);
            } else {
                this.showNotification('No more actions to redo', 'warning');
            }
        },
        
        rebindEvents() {
            // Re-bind events after history restore
            this.canvas.querySelectorAll('.canvas-component').forEach(element => {
                this.bindComponentControls(element);
            });
        },
        
        async savePage(status = 'draft') {
            try {
                const canvasState = this.getCanvasState();
                
                const response = await fetch('/panel/builder/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({
                        page_id: window.pageData.id,
                        components: canvasState.components || []
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotification('Page saved successfully!', 'success');
                    
                    // Update window.pageData with saved data
                    if (result.data) {
                        window.pageData = { ...window.pageData, ...result.data };
                    }
                    
                    // Update history after successful save
                    this.saveToHistory();
                } else {
                    this.showError('Failed to save page: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error saving page:', error);
                this.showError('Error saving page: ' + error.message);
            }
        },
        
        getCanvasState() {
            const components = [];
            
            this.canvas.querySelectorAll('.canvas-component').forEach((element, index) => {
                const componentId = element.dataset.componentId;
                const instanceId = element.dataset.instanceId;
                
                if (componentId) {
                    components.push({
                        id: instanceId,
                        component_id: componentId,
                        order: index,
                        properties: this.getComponentProperties(element)
                    });
                }
            });
            
            return {
                components: components,
                layout: {
                    viewport: this.viewport ? this.viewport.dataset.view : 'desktop'
                },
                meta: {
                    version: '1.0',
                    created_at: new Date().toISOString(),
                    components_count: components.length
                }
            };
        },
        
        getComponentProperties(element) {
            // Get properties from data attributes or other sources
            const properties = {};
            
            // Extract properties from data attributes
            Array.from(element.attributes).forEach(attr => {
                if (attr.name.startsWith('data-prop-')) {
                    const propName = attr.name.replace('data-prop-', '');
                    let propValue = attr.value;
                    
                    // Try to parse JSON values (for arrays/objects)
                    try {
                        // Check if it looks like JSON
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
        
        async previewPage() {
            try {
                const canvasState = this.getCanvasState();
                
                // Call preview endpoint with current components
                const response = await fetch('/panel/builder/preview', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({
                        page_id: window.pageData.id,
                        components: canvasState.components || []
                    })
                });
                
                const result = await response.json();
                
                if (result.success && result.data.preview_url) {
                    // Open preview in new window
                    window.open(result.data.preview_url, '_blank');
                    this.showNotification('Opening preview...', 'info');
                } else {
                    this.showError('Failed to open preview: ' + (result.message || 'Unknown error'));
                }
                
            } catch (error) {
                console.error('Error opening preview:', error);
                this.showError('Error opening preview: ' + error.message);
            }
        },
        
        showNotification(message, type = 'info') {
            // Simple notification implementation
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed`;
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="ti ti-check-circle me-2"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Fade in
            setTimeout(() => notification.style.opacity = '1', 100);
            
            // Fade out and remove
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        },
        
        async loadPageData() {
            // Load existing page data if editing
            if (window.pageData && window.pageData.id) {
                try {
                    const response = await fetch('/panel/builder/load', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken
                        },
                        body: JSON.stringify({
                            page_id: window.pageData.id
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success && result.data.components && result.data.components.length > 0) {
                        // Load components into canvas
                        await this.loadComponents(result.data.components);
                        console.log('Loaded existing page data:', result.data);
                        
                        // Save initial state after loading (only once)
                        this.saveToHistory();
                    } else {
                        // No components to load, just save empty state
                        this.saveToHistory();
                    }
                } catch (error) {
                    console.error('Error loading page data:', error);
                    // Save empty state even if loading fails
                    this.saveToHistory();
                }
            } else {
                // No page data, save empty state
                this.saveToHistory();
            }
        },
        
        async loadComponents(components) {
            // Clear existing canvas but keep main drop zone
            const canvasChildren = Array.from(this.canvas.children);
            canvasChildren.forEach(child => {
                if (!child.id || child.id !== 'main-drop-zone') {
                    child.remove();
                }
            });
            
            // Load each component
            for (const componentData of components) {
                try {
                    await this.loadSingleComponent(componentData);
                } catch (error) {
                    console.error('Error loading component:', componentData, error);
                }
            }
            
            // Hide main drop zone if we have components
            if (components.length > 0) {
                this.hideMainDropZone();
            }
        },
        
        async loadSingleComponent(componentData) {
            const response = await fetch('/panel/builder/components/render', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({
                    component_id: componentData.component_id,
                    properties: componentData.properties || {}
                })
            });
            
            const result = await response.json();
            
            if (result.success && result.data && result.data.html) {
                // Create component element
                const componentElement = this.createComponentElement(componentData.component_id, result.data.html);
                
                if (componentElement) {
                    // Restore instance ID
                    componentElement.dataset.instanceId = componentData.id;
                    
                    // Save loaded properties to data attributes
                    if (componentData.properties) {
                        Object.entries(componentData.properties).forEach(([key, value]) => {
                            if (value !== null && value !== undefined && value !== '') {
                                const attributeValue = typeof value === 'object' ? JSON.stringify(value) : String(value);
                                componentElement.setAttribute(`data-prop-${key}`, attributeValue);
                            }
                        });
                    }
                    
                    // Add to canvas
                    this.canvas.appendChild(componentElement);
                    
                    console.log('Loaded component with properties:', componentData.component_id, componentData.properties);
                }
            }
        },
        
        generateId() {
            return 'comp_' + Math.random().toString(36).substr(2, 9);
        },
        
        showError(message) {
            // Show error notification using the same notification system
            this.showNotification(message, 'danger');
            console.error(message);
        }
    };
    
    // Make CanvasSystem globally available
    window.CanvasSystem = CanvasSystem;
    
    // Initialize the canvas system
    CanvasSystem.init();
});
</script>

<style>
.builder-canvas-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.canvas-toolbar {
    flex-shrink: 0;
    background: var(--tblr-bg-surface);
}

.canvas-area {
    flex: 1;
    overflow: auto;
    background: #f8f9fa;
}

.canvas-viewport {
    transition: all 0.3s ease;
    margin: 20px auto;
    background: white;
    border: 1px solid var(--tblr-border-color);
    border-radius: var(--tblr-border-radius);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.canvas-viewport.canvas-desktop {
    width: 100%;
    max-width: 1200px;
}

.canvas-viewport.canvas-tablet {
    width: 768px;
    max-width: 100%;
}

.canvas-viewport.canvas-mobile {
    width: 375px;
    max-width: 100%;
}

.canvas-content {
    min-height: 600px;
    position: relative;
}

.drop-zone {
    min-height: 60px;
    border: 2px dashed transparent;
    border-radius: var(--tblr-border-radius);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.drop-zone.active {
    border-color: var(--tblr-primary);
    background: rgba(var(--tblr-primary-rgb), 0.1);
}

.drop-zone:hover,
.drop-zone.drag-over {
    border-color: var(--tblr-primary);
    background: rgba(var(--tblr-primary-rgb), 0.1);
}

.drop-zone-content {
    text-align: center;
    padding: 4rem 2rem;
}

.drop-zone-indicator {
    display: none;
    color: var(--tblr-primary);
    font-size: 0.875rem;
}

.drop-zone:hover .drop-zone-indicator,
.drop-zone.drag-over .drop-zone-indicator {
    display: block;
}

.canvas-component {
    position: relative;
    margin: 1rem 0;
}

.canvas-component:hover .component-controls,
.canvas-component.selected .component-controls {
    opacity: 1;
}

.component-controls {
    position: absolute;
    top: -40px;
    right: 0;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.component-toolbar {
    display: flex;
    gap: 0.25rem;
    background: white;
    border: 1px solid var(--tblr-border-color);
    border-radius: var(--tblr-border-radius);
    padding: 0.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.canvas-component.selected {
    outline: 2px solid var(--tblr-primary);
    outline-offset: 2px;
}

.component-wrapper {
    position: relative;
}

/* Sortable.js styles for drag & drop */
.sortable-ghost {
    opacity: 0.4;
    background: rgba(var(--tblr-primary-rgb), 0.1);
    border: 2px dashed var(--tblr-primary);
    border-radius: var(--tblr-border-radius);
}

.sortable-chosen {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
}

.sortable-drag {
    transform: rotate(2deg);
    opacity: 0.8;
}

.sortable-fallback {
    display: none;
}

.sorting-active {
    background: rgba(var(--tblr-primary-rgb), 0.05);
}

/* Drop indicators for drag & drop reordering */
.drop-indicator {
    position: absolute;
    left: 0;
    right: 0;
    height: 3px;
    background: transparent;
    z-index: 100;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.drop-indicator-before {
    top: -6px;
}

.drop-indicator-after {
    bottom: -6px;
}

.drop-line {
    height: 3px;
    background: var(--tblr-primary);
    border-radius: 2px;
    margin: 0 10px;
    box-shadow: 0 2px 4px rgba(var(--tblr-primary-rgb), 0.3);
}

.sorting-active .drop-indicator {
    opacity: 1;
}

/* Enhanced sortable styles */
.canvas-component.dragging {
    opacity: 0.7;
    transform: rotate(1deg) scale(1.02);
    z-index: 1001;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
}

.canvas-component .component-move {
    cursor: grab;
}

.canvas-component .component-move:active {
    cursor: grabbing;
}

/* Show move handle more prominently when sorting is active */
.sorting-active .component-move {
    background: var(--tblr-primary);
    color: white;
    border-color: var(--tblr-primary);
}

@media (max-width: 768px) {
    .canvas-viewport.canvas-desktop,
    .canvas-viewport.canvas-tablet {
        width: 100%;
        margin: 10px;
    }
}
</style>
