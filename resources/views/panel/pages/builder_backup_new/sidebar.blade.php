{{-- Component Sidebar for Page Builder --}}
{{-- 
Sidebar that displays all available components organized by category
Uses simple click to add components to canvas
--}}

<div class="d-flex flex-column h-100 border-end" style="width: 280px;">
    <!-- Sidebar Header -->
    <div class="navbar navbar-expand-md navbar-light border-bottom">
        <div class="container-fluid">
            <span class="navbar-brand navbar-brand-autodark">
                <i class="ti ti-components me-2"></i>
                Components
            </span>
            <button class="btn btn-icon btn-sm" id="refresh-components" title="Refresh Components">
                <i class="ti ti-refresh"></i>
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="p-3 border-bottom bg-light">
        <div class="mb-2">
            <div class="input-group input-group-flat">
                <input type="text" class="form-control" id="component-search" placeholder="Search components...">
                <span class="input-group-text">
                    <button class="btn btn-link btn-sm p-0 lh-1" id="clear-search" title="Clear search">
                        <i class="ti ti-x"></i>
                    </button>
                </span>
            </div>
        </div>
        <select class="form-select" id="category-filter">
            <option value="">All Categories</option>
            <option value="layout">Layout</option>
            <option value="content">Content</option>
            <option value="cards">Cards</option>
            <option value="advanced">Advanced</option>
            <option value="templates">Templates</option>
        </select>
    </div>

    <!-- Components List -->
    <div class="flex-fill overflow-auto" id="components-container">
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
        <div class="list-group-header sticky-top">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="list-group-header-title category-name"></h4>
                </div>
                <div class="col-auto">
                    <button class="btn btn-icon btn-sm category-toggle" type="button">
                        <i class="ti ti-chevron-down"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="list-group list-group-flush category-components"></div>
    </div>
</template>

<!-- Component Item Template -->
<template id="component-item-template">
    <div class="list-group-item list-group-item-action component-item" data-component-id="">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="avatar avatar-sm">
                    <i class="ti ti-square text-primary"></i>
                </div>
            </div>
            <div class="col text-truncate">
                <div class="text-body d-block component-name"></div>
                <small class="text-muted text-truncate mt-n1 component-description"></small>
            </div>
            <div class="col-auto">
                <button class="btn btn-icon btn-sm add-component" title="Add to Canvas">
                    <i class="ti ti-plus"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Empty State Template -->
<template id="empty-state-template">
    <div class="empty">
        <div class="empty-icon">
            <i class="ti ti-search"></i>
        </div>
        <p class="empty-title">No components found</p>
        <p class="empty-subtitle text-muted">
            Try adjusting your search or filter criteria
        </p>
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
                if (componentsContainer.style.display === 'none') {
                    componentsContainer.style.display = 'block';
                    icon.classList.remove('ti-chevron-right');
                    icon.classList.add('ti-chevron-down');
                } else {
                    componentsContainer.style.display = 'none';
                    icon.classList.remove('ti-chevron-down');
                    icon.classList.add('ti-chevron-right');
                }
            });
            
            this.container.appendChild(categoryElement);
        },
        
        renderComponent(componentId, component) {
            const template = document.getElementById('component-item-template');
            const componentElement = template.content.cloneNode(true);
            
            const itemDiv = componentElement.querySelector('.component-item');
            itemDiv.dataset.componentId = componentId;
            
            const icon = componentElement.querySelector('.avatar i');
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
                <div class="alert alert-danger m-3" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="ti ti-alert-circle alert-icon"></i>
                        </div>
                        <div>
                            ${message}
                        </div>
                    </div>
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


