{{-- Component Sidebar for Page Builder --}}
{{-- 
Sidebar that displays all available components organized by category
Uses simple click to add components to canvas
--}}

<div class="builder-sidebar" style="width: 300px; height: 100%; overflow-y: auto;">
    <!-- Sidebar Header -->
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0">
            <i class="ti ti-components me-2"></i>
            Components
        </h3>
        <button class="btn btn-ghost-secondary btn-sm" id="refresh-components" title="Refresh Components">
            <i class="ti ti-refresh"></i>
        </button>
    </div>

    <!-- Search Bar -->
    <div class="card-body border-bottom">
        <div class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="component-search" placeholder="Search components...">
                <button class="btn btn-outline-secondary" type="button" id="clear-search">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="mb-0">
            <select class="form-select" id="category-filter">
                <option value="">All Categories</option>
                <option value="layout">Layout</option>
                <option value="content">Content</option>
                <option value="cards">Cards</option>
                <option value="advanced">Advanced</option>
                <option value="templates">Templates</option>
            </select>
        </div>
    </div>

    <!-- Components List -->
    <div class="card-body p-0" id="components-container">
        <div class="d-flex align-items-center justify-content-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading components...</span>
            </div>
        </div>
    </div>
</div>

<!-- Component Category Template -->
<template id="component-category-template">
    <div class="component-category" data-category="">
        <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-light border-bottom">
            <h4 class="mb-0 fs-6 fw-bold text-muted category-name"></h4>
            <button class="btn btn-ghost-secondary btn-sm category-toggle" type="button">
                <i class="ti ti-chevron-down"></i>
            </button>
        </div>
        <div class="category-components"></div>
    </div>
</template>

<!-- Component Item Template -->
<template id="component-item-template">
    <div class="component-item border-bottom" data-component-id="">
        <div class="d-flex align-items-center p-3">
            <div class="component-icon me-3">
                <i class="ti ti-square text-primary" style="font-size: 1.5rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="component-name fw-semibold mb-1"></div>
                <div class="component-description text-muted small"></div>
            </div>
            <div class="component-actions">
                <button class="btn btn-ghost-primary btn-sm add-component" title="Add to Canvas">
                    <i class="ti ti-plus"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Empty State Template -->
<template id="empty-state-template">
    <div class="empty-state d-flex align-items-center justify-content-center py-5">
        <div class="text-center">
            <i class="ti ti-search text-muted" style="font-size: 3rem;"></i>
            <h3 class="mt-3 text-muted">No components found</h3>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ComponentSidebar = {
        container: document.getElementById('components-container'),
        searchInput: document.getElementById('component-search'),
        categoryFilter: document.getElementById('category-filter'),
        clearSearchBtn: document.getElementById('clear-search'),
        refreshBtn: document.getElementById('refresh-components'),
        
        components: [],
        filteredComponents: [],
        
        init() {
            this.bindEvents();
            this.loadComponents();
        },
        
        bindEvents() {
            // Search functionality
            this.searchInput.addEventListener('input', this.debounce(() => {
                this.filterComponents();
            }, 300));
            
            // Category filter
            this.categoryFilter.addEventListener('change', () => {
                this.filterComponents();
            });
            
            // Clear search
            this.clearSearchBtn.addEventListener('click', () => {
                this.searchInput.value = '';
                this.filterComponents();
            });
            
            // Refresh components
            this.refreshBtn.addEventListener('click', () => {
                this.loadComponents();
            });
        },
        
        async loadComponents() {
            try {
                this.showLoading();
                
                const response = await fetch('/panel/builder/components', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    this.components = result.data;
                    this.renderComponents();
                } else {
                    this.showError('Failed to load components: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error loading components:', error);
                this.showError('Error loading components: ' + error.message);
            }
        },
        
        filterComponents() {
            const search = this.searchInput.value.toLowerCase();
            const category = this.categoryFilter.value;
            
            this.filteredComponents = {};
            
            Object.keys(this.components).forEach(categoryKey => {
                if (category && categoryKey !== category) {
                    return;
                }
                
                const categoryData = this.components[categoryKey];
                const filteredComponentsInCategory = {};
                
                Object.keys(categoryData.components).forEach(componentId => {
                    const component = categoryData.components[componentId];
                    const matchesSearch = !search || 
                        component.name.toLowerCase().includes(search) ||
                        component.description.toLowerCase().includes(search);
                    
                    if (matchesSearch) {
                        filteredComponentsInCategory[componentId] = component;
                    }
                });
                
                if (Object.keys(filteredComponentsInCategory).length > 0) {
                    this.filteredComponents[categoryKey] = {
                        ...categoryData,
                        components: filteredComponentsInCategory
                    };
                }
            });
            
            this.renderComponents();
        },
        
        renderComponents() {
            const componentsToRender = Object.keys(this.filteredComponents).length > 0 ? 
                this.filteredComponents : this.components;
            
            if (Object.keys(componentsToRender).length === 0) {
                this.showEmptyState();
                return;
            }
            
            this.container.innerHTML = '';
            
            Object.keys(componentsToRender).forEach(categoryKey => {
                const category = componentsToRender[categoryKey];
                this.renderCategory(categoryKey, category);
            });
        },
        
        renderCategory(categoryKey, category) {
            const template = document.getElementById('component-category-template');
            const categoryElement = template.content.cloneNode(true);
            
            const categoryDiv = categoryElement.querySelector('.component-category');
            categoryDiv.dataset.category = categoryKey;
            
            const categoryName = categoryElement.querySelector('.category-name');
            categoryName.textContent = category.name;
            
            const componentsContainer = categoryElement.querySelector('.category-components');
            
            Object.keys(category.components).forEach(componentId => {
                const component = category.components[componentId];
                const componentElement = this.renderComponent(componentId, component);
                componentsContainer.appendChild(componentElement);
            });
            
            // Category toggle functionality
            const toggle = categoryElement.querySelector('.category-toggle');
            toggle.addEventListener('click', () => {
                const icon = toggle.querySelector('i');
                componentsContainer.style.display = componentsContainer.style.display === 'none' ? 'block' : 'none';
                icon.classList.toggle('ti-chevron-down');
                icon.classList.toggle('ti-chevron-right');
            });
            
            this.container.appendChild(categoryElement);
        },
        
        renderComponent(componentId, component) {
            const template = document.getElementById('component-item-template');
            const componentElement = template.content.cloneNode(true);
            
            const itemDiv = componentElement.querySelector('.component-item');
            itemDiv.dataset.componentId = componentId;
            
            const icon = componentElement.querySelector('.component-icon i');
            icon.className = component.icon || 'ti ti-square';
            
            const name = componentElement.querySelector('.component-name');
            name.textContent = component.name;
            
            const description = componentElement.querySelector('.component-description');
            description.textContent = component.description;
            
            // Simple click to add component to canvas
            itemDiv.addEventListener('click', () => {
                this.addComponentToCanvas(componentId, component);
            });
            
            return componentElement;
        },
        
        addComponentToCanvas(componentId, component) {
            // Emit event for canvas to handle
            const event = new CustomEvent('add-component', {
                detail: { componentId, component }
            });
            document.dispatchEvent(event);
        },
        
        showLoading() {
            this.container.innerHTML = `
                <div class="d-flex align-items-center justify-content-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading components...</span>
                    </div>
                </div>
            `;
        },
        
        showError(message) {
            this.container.innerHTML = `
                <div class="alert alert-danger m-3">
                    <i class="ti ti-alert-circle me-2"></i>
                    ${message}
                </div>
            `;
        },
        
        showEmptyState() {
            const template = document.getElementById('empty-state-template');
            const emptyState = template.content.cloneNode(true);
            this.container.innerHTML = '';
            this.container.appendChild(emptyState);
        },
        
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };
    
    ComponentSidebar.init();
});
</script>

<style>
.builder-sidebar {
    border-right: 1px solid var(--tblr-border-color);
    background: var(--tblr-bg-surface);
}

.component-item {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.component-item:hover {
    background-color: var(--tblr-bg-surface-secondary);
}

.component-item:active {
    background-color: var(--tblr-primary-lt);
    transform: scale(0.98);
}

.component-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--tblr-bg-surface-secondary);
    border-radius: var(--tblr-border-radius);
}

.category-toggle {
    transition: transform 0.2s ease;
}

.empty-state {
    min-height: 200px;
}
</style>
