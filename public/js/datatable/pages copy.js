/**
 * Pages DataTable Configuration - Following users.js pattern
 * @author KantorKu SuperApp Team
 * @version 1.0.0 - Consistent with users.js pattern
 */

window.PagesDataTable = (function () {
    "use strict";

    let pagesTable;
    let selectedPages = [];
    let pageCache = new Map(); // Simple cache for faster edit loading

    // --- Table Configuration ---
    function getTableConfig(route) {
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: { url: route, type: "GET" },
                order: [[1, "asc"]],
                deferRender: true
            }
        });

        const pagesConfig = {
            columns: [
                { // Checkbox
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        // Cache data saat render untuk edit cepat
                        pageCache.set(row.id, row);
                        return `<input class="form-check-input m-0 align-middle table-selectable-check" 
                               type="checkbox" value="${row.id}"/>`;
                    }
                },
                { // Title
                    data: "title",
                    name: "title",
                    render: (data, type, row) => {
                        return `<strong>${data}</strong>`;
                    }
                },
                { // Slug
                    data: "slug", 
                    name: "slug",
                    render: (data) => `<code>${data}</code>`
                },
                { // Page Type
                    data: "page_type",
                    name: "page_type",
                    orderable: false,
                    searchable: false
                },
                { // Template Info
                    data: "template_info",
                    name: "template",
                    orderable: false,
                    searchable: false
                },
                { // Status
                    data: "status_badge",
                    name: "is_published",
                    orderable: false,
                    searchable: false
                },
                { // Created date
                    data: "created_at",
                    name: "created_at",
                    render: (data) => new Date(data).toLocaleDateString("en-US", {
                        year: "numeric", month: "long", day: "numeric"
                    })
                },
                { // Actions
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-end"
                }
            ],
            drawCallback: function () {
                DataTableGlobal.buildCustomPagination(pagesTable, "#datatable-pagination");
                DataTableGlobal.updateRecordInfo(pagesTable, "#record-info", "pages");
                DataTableGlobal.updateSelectAllState();
                updateBulkDeleteButton();
                
                // Manage cache size
                if (pageCache.size > 500) pageCache.clear();
            }
        };

        return $.extend(true, {}, baseConfig, pagesConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            pagesTable = $("#datatable-pages").DataTable(getTableConfig(route));

            // Setup handlers dari DataTableGlobal
            DataTableGlobal.createSearchHandler(pagesTable, "#advanced-table-search");
            DataTableGlobal.setupPageLengthHandler(pagesTable, ".dropdown-item[data-value]", "#page-count");
            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            setupEventHandlers();
            return pagesTable;
        });
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        DataTableGlobal.setupCheckboxHandlers(selectedPages, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton
        });
    }

    function updateBulkDeleteButton() {
        $("#delete-selected-btn").prop("disabled", selectedPages.length === 0);
        $("#selected-count").text(selectedPages.length);
    }

    // --- Page Management - OPTIMIZED ---
    function editPage(pageId, editRoute = null) {
        const numericId = parseInt(pageId);
        
        // Cek cache dulu - instant loading jika ada
        const cachedPage = pageCache.get(numericId);
        if (cachedPage) {
            fillEditForm(cachedPage);
            return;
        }

        // Jika tidak ada di cache, fetch dari server
        const route = editRoute || window.pageEditRoute;
        if (!route) {
            console.error('Edit route not configured');
            alert('Edit route not configured properly');
            return;
        }

        fetch(route.replace(':id', pageId), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.page) {
                const page = data.page;
                pageCache.set(numericId, page); // Cache untuk next time
                fillEditForm(page);
            } else {
                throw new Error(data.message || 'Failed to load page data');
            }
        })
        .catch(error => {
            console.error('Error loading page:', error);
            alert('Error loading page data: ' + error.message);
        });
    }

    // Simplified form filling with TomSelect support
    function fillEditForm(page) {
        // Fill form fields with updated IDs
        document.getElementById('update_page_id').value = page.id;
        document.getElementById('update_title').value = page.title || '';
        document.getElementById('update_slug').value = page.slug || '';
        document.getElementById('update_content').value = page.content || '';
        document.getElementById('update_meta_title').value = page.meta_title || '';
        document.getElementById('update_meta_description').value = page.meta_description || '';
        document.getElementById('update_sort_order').value = page.sort_order || 0;
        document.getElementById('update_is_published').checked = !!page.is_published;

        // Handle TomSelect for template - call global function from update.blade.php
        if (typeof window.setUpdatePageTemplate === 'function') {
            window.setUpdatePageTemplate(page.template || '');
        } else {
            // Fallback if TomSelect not available
            const templateSelect = document.getElementById('update_template');
            if (templateSelect) {
                templateSelect.value = page.template || '';
            }
        }

        // Update form action - use correct URL pattern
        const form = document.getElementById('updatePageForm');
        if (form) {
            form.action = form.action.includes(':id') ?
                form.action.replace(':id', page.id) :
                form.action.replace(/\/\d+$/, '') + '/' + page.id;
        }

        // Show modal using Tabler.io method (no Bootstrap dependency)
        showUpdatePageModal();
    }

    // --- Modal Management ---
    function showUpdatePageModal() {
        // Use global function from update.blade.php if available
        if (typeof window.showUpdatePageModal === 'function') {
            window.showUpdatePageModal();
        } else {
            // Fallback using Tabler.io modal pattern
            const modal = document.getElementById('updatePageModal');
            if (modal) {
                modal.style.display = 'block';
                modal.classList.add('show');
                document.body.classList.add('modal-open');
                
                // Add backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
        }
    }

    // --- Update Success Handler ---
    function handleUpdateSuccess(pageData = null) {
        // Update cache if page data provided
        if (pageData && pageData.id) {
            pageCache.set(pageData.id, pageData);
        }
        
        // Refresh DataTable
        refreshDataTable();
        
        // Show success message using global alert system if available
        if (typeof window.showSuccessAlert === 'function') {
            window.showSuccessAlert('Page updated successfully!');
        }
    }

    function deletePage(pageId, pageTitle) {
        if (typeof confirmDeletePage === 'function') {
            confirmDeletePage(pageId, pageTitle);
        } else {
            // Use global confirmation modal - no form needed
            console.warn('Global confirmDeletePage function not available, using fallback');
        }
    }

    function viewPage(pageSlug) {
        if (window.pageViewRoute) {
            const url = window.pageViewRoute.replace(':slug', pageSlug);
            window.open(url, '_blank');
        } else {
            console.error('Page view route not configured');
        }
    }

    // --- Bulk Delete ---
    let bulkDeleteRoute = "";

    function setupBulkDeleteHandlers() {
        DataTableGlobal.setupBulkDeleteHandler({
            modalSelector: "#deleteSelectedModal",
            selectedArray: selectedPages,
            deleteRoute: bulkDeleteRoute,
            confirmBtnSelector: "#confirm-delete-selected",
            entityName: "page",
            tableInstance: pagesTable,
            updateCallback: updateBulkDeleteButton
        });
    }

    function setBulkDeleteRoute(route) {
        bulkDeleteRoute = route;
        if (pagesTable) setupBulkDeleteHandlers();
    }

    // --- Helper Functions ---
    function setupModalHandlers() {
        DataTableGlobal.setupStandardModalHandlers([
            { modalSelector: "#createTemplatePageModal", formSelector: "#createTemplatePageForm" },
            { modalSelector: "#createBuilderPageModal", formSelector: "#createBuilderPageForm" },
            { modalSelector: "#updatePageModal", formSelector: "#updatePageForm" },
            { modalSelector: "#uploadTemplateModal", formSelector: "#uploadTemplateForm" }
        ]);
    }

    function setPageListItems(event) {
        DataTableGlobal.createPageLengthHandler(pagesTable, "#page-count")(event);
    }

    // --- Public API ---
    return {
        initialize,
        editPage,
        deletePage,
        viewPage,
        setupModalHandlers,
        setPageListItems,
        setBulkDeleteRoute,
        setupEventHandlers,
        showUpdatePageModal,
        handleUpdateSuccess,
        fillEditForm,
        getTable: () => pagesTable,
        getSelectedPages: () => selectedPages,
        refreshDataTable: () => {
            pageCache.clear(); // Clear cache saat refresh
            return DataTableGlobal.refreshDataTable(pagesTable);
        },
        updateRecordInfo: () => DataTableGlobal.updateRecordInfo(pagesTable, "#record-info", "pages")
    };
})();

// --- Legacy Support ---
window.editPage = (pageId, editRoute) => PagesDataTable.editPage(pageId, editRoute);
window.deletePage = (pageId, pageTitle) => PagesDataTable.deletePage(pageId, pageTitle);
window.viewPage = (pageSlug) => PagesDataTable.viewPage(pageSlug);
window.setPageListItems = (event) => PagesDataTable.setPageListItems(event);

// --- New Global Functions for Modal Integration ---
window.showUpdatePageModal = () => PagesDataTable.showUpdatePageModal();
window.handlePageUpdateSuccess = (pageData) => PagesDataTable.handleUpdateSuccess(pageData);
window.fillPageEditForm = (pageData) => PagesDataTable.fillEditForm(pageData);
