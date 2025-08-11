/**
 * Pages DataTable Configuration - Clean & Optimized
 * @author KantorKu SuperApp Team
 * @version 3.0.0 - Clean, optimized with fast caching
 */

window.PagesDataTable = (function () {
    "use strict";

    let pagesTable;
    let selectedPages = [];
    let pageCache = new Map();
    let tomSelectInstances = {};
    let templateOptions = {};
    let templateCache = null;

    // --- Table Configuration ---
    function getTableConfig(route) {
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: { 
                    url: route, 
                    type: "GET",
                    dataSrc: function(json) {
                        if (json.data && Array.isArray(json.data)) {
                            json.data.forEach(page => {
                                if (page.id) pageCache.set(page.id, page);
                            });
                        }
                        return json.data;
                    }
                },
                order: [[1, "asc"]],
                deferRender: true
            }
        });

        const pagesConfig = {
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) =>
                        `<input class="form-check-input m-0 align-middle table-selectable-check" 
                               type="checkbox" value="${row.id}"/>`
                },
                {
                    data: "title",
                    name: "title",
                    render: (data, type, row) => `${data}`
                },
                {
                    data: "slug", 
                    name: "slug",
                    render: (data) => `<code>${data}</code>`
                },
                {
                    data: "page_type",
                    name: "page_type",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "template_info",
                    name: "template",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "status_badge",
                    name: "is_published",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "created_at",
                    name: "created_at",
                    render: (data) => new Date(data).toLocaleDateString("en-US", {
                        year: "numeric", month: "long", day: "numeric"
                    })
                },
                {
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
                
                if (pageCache.size > 500) pageCache.clear();
            }
        };

        return $.extend(true, {}, baseConfig, pagesConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            pagesTable = $("#datatable-pages").DataTable(getTableConfig(route));

            DataTableGlobal.createSearchHandler(pagesTable, "#advanced-table-search");
            DataTableGlobal.setupPageLengthHandler(pagesTable, ".dropdown-item[data-value]", "#page-count");
            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            setupEventHandlers();
            return pagesTable;
        });
    }

    // --- TomSelect Management ---
    function initializeTomSelectInstances() {
        const selectors = ['update_template'];
        
        return DataTableGlobal.waitForTomSelect().then(() => {
            selectors.forEach((selectId) => {
                const element = document.getElementById(selectId);
                if (element && !tomSelectInstances[selectId]) {
                    if (element.tomselect) {
                        element.tomselect.destroy();
                    }

                    tomSelectInstances[selectId] = new TomSelect(element, {
                        plugins: ["clear_button"],
                        allowEmptyOption: false,
                        create: false,
                        placeholder: "Select Template..."
                    });
                }
            });
        });
    }

    function clearTomSelectInstances() {
        DataTableGlobal.destroyTomSelects(tomSelectInstances);
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        DataTableGlobal.setupCheckboxHandlers(selectedPages, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton
        });

        setupModalHandlers();
        setupTomSelectModalHandlers();
    }

    function setupTomSelectModalHandlers() {
        $('#updatePageModal').on('hidden.bs.modal', function() {
            clearTomSelectInstances();
        });
    }

    function updateBulkDeleteButton() {
        $("#delete-selected-btn").prop("disabled", selectedPages.length === 0);
        $("#selected-count").text(selectedPages.length);
    }

    // --- Page Management - Optimized with caching ---
    function editPage(pageId, editRoute = null) {
        const numericId = parseInt(pageId);
        const cachedPage = pageCache.get(numericId);
        
        if (cachedPage && templateCache) {
            // Fast path: use cached data and templates
            showEditModal(cachedPage);
            return;
        }

        // Load templates if not cached
        const templatesPromise = templateCache ? 
            Promise.resolve(templateCache) : 
            refreshTemplateOptions();

        // Load page data if not cached
        const pagePromise = cachedPage ? 
            Promise.resolve(cachedPage) : 
            fetchPageData(pageId, editRoute);

        // Execute both promises concurrently for speed
        Promise.all([templatesPromise, pagePromise])
            .then(([templates, page]) => {
                if (!cachedPage) pageCache.set(numericId, page);
                showEditModal(page);
            })
            .catch(error => {
                if (cachedPage) showEditModal(cachedPage);
                else alert('Error loading page data');
            });
    }

    function fetchPageData(pageId, editRoute) {
        const route = editRoute || window.pageEditRoute;
        if (!route) {
            throw new Error('Edit route not configured');
        }

        return fetch(route.replace(':id', pageId), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => data.page);
    }

    function showEditModal(page) {
        updateTemplateSelect();
        
        initializeTomSelectInstances()
            .then(() => {
                fillEditModal(page);
                $('#updatePageModal').modal('show');
            })
            .catch(() => {
                fillEditModal(page);
                $('#updatePageModal').modal('show');
            });
    }

    function fillEditModal(page) {
        if (!$('#update_page_id').length) return;

        const form = $('#updatePageForm')[0];
        if (form && page.id) {
            form.action = form.action.includes(':id') ?
                form.action.replace(':id', page.id) :
                form.action.replace(/\/\d+$/, '') + '/' + page.id;
        }

        $('#update_page_id').val(page.id);
        $('#update_title').val(page.title || '');
        $('#update_slug').val(page.slug || '');
        $('#update_content').val(page.content || '');
        $('#update_meta_title').val(page.meta_title || '');
        $('#update_meta_description').val(page.meta_description || '');
        $('#update_sort_order').val(page.sort_order || 0);
        $('#update_is_published').prop('checked', !!page.is_published);

        const templateValue = page.template ? String(page.template) : '';
        if (templateValue && tomSelectInstances['update_template']) {
            DataTableGlobal.setTomSelectValue(tomSelectInstances, 'update_template', templateValue);
        }

        DataTableGlobal.syncTomSelects(tomSelectInstances);
    }

    // --- Utility Functions ---
    function refreshTemplateOptions() {
        if (!window.templatesRoute) {
            templateOptions = {};
            templateCache = templateOptions;
            return Promise.resolve(templateOptions);
        }

        return fetch(window.templatesRoute, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            templateOptions = {};
            
            if (Array.isArray(data)) {
                data.forEach(template => {
                    const key = template.value || template.name || template;
                    const value = template.label || template.display_name || template.name || template;
                    templateOptions[key] = value;
                });
            } else if (data.templates && Array.isArray(data.templates)) {
                data.templates.forEach(template => {
                    const key = template.value || template.name || template;
                    const value = template.label || template.display_name || template.name || template;
                    templateOptions[key] = value;
                });
            }
            
            templateCache = templateOptions;
            return templateOptions;
        })
        .catch(error => {
            templateOptions = {};
            templateCache = templateOptions;
            return templateOptions;
        });
    }

    function updateTemplateSelect() {
        DataTableGlobal.updateSelectOptions('update_template', templateOptions, false);
    }

    // --- Other Functions ---
    function deletePage(pageId, pageTitle) {
        const cachedPage = pageCache.get(parseInt(pageId)) || { id: pageId, title: pageTitle };
        
        $('#delete_page_id').val(cachedPage.id);
        $('#delete_page_title').text(cachedPage.title);

        const form = $('#deletePageForm')[0];
        if (form && cachedPage.id) {
            form.action = form.action.includes(':id') ?
                form.action.replace(':id', cachedPage.id) :
                form.action.replace(/\/\d+$/, '') + '/' + cachedPage.id;
        }
        
        $('#deletePageModal').modal('show');
    }

    function viewPage(pageSlug) {
        if (window.pageViewRoute) {
            window.open(window.pageViewRoute.replace(':slug', pageSlug), '_blank');
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
        getTable: () => pagesTable,
        getSelectedPages: () => selectedPages,
        refreshDataTable: () => {
            pageCache.clear();
            templateCache = null;
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