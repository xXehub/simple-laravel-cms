/**
 * Pages DataTable Configuration - Optimized Pattern
 * @author KantorKu SuperApp Team
 * @version 1.0.0 - Following users.js pattern for consistency
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
                { // Status
                    data: "status_badge",
                    name: "is_published",
                    orderable: false,
                    searchable: false
                },
                { // Template
                    data: "template",
                    name: "template",
                    render: (data) => data || 'default'
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
        .then(response => response.json())
        .then(data => {
            const page = data.page;
            pageCache.set(numericId, page); // Cache untuk next time
            fillEditForm(page);
        })
        .catch(error => {
            console.error('Error loading page:', error);
            alert('Error loading page data');
        });
    }

    // Simplified form filling
    function fillEditForm(page) {
        $('#edit_page_id').val(page.id);
        $('#edit_title').val(page.title);
        $('#edit_slug').val(page.slug);
        $('#edit_content').val(page.content);
        $('#edit_template').val(page.template || '');
        $('#edit_meta_title').val(page.meta_title || '');
        $('#edit_meta_description').val(page.meta_description || '');
        $('#edit_sort_order').val(page.sort_order || 0);
        $('#edit_is_published').prop('checked', page.is_published);

        // Update form action - use correct URL pattern
        const form = $('#editPageForm')[0];
        if (form) {
            form.action = `/panel/pages/${page.id}`;
        }

        // Show the modal after loading data using jQuery method for compatibility
        $('#editPageModal').modal('show');
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
            { modalSelector: "#createPageModal", formSelector: "#createPageForm" },
            { modalSelector: "#editPageModal", formSelector: "#editPageForm" }
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
