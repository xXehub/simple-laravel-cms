/**
 * Global DataTable Components
 * Reusable DataTable functions and utilities
 *
 * @author KantorKu SuperApp Team
 * @version 1.0.0
 */

window.DataTableGlobal = (function () {
    "use strict";

    // --- Default Configuration ---
    const config = {
        defaultPageLength: 15,
        lengthMenu: [10, 15, 25, 50, 100],
        language: {
            processing: `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-2">Memuat data...</div>
            </div>
        `,
            zeroRecords: "Tidak ada data yang ditemukan",
            info: "",
            infoEmpty: "",
            infoFiltered: "",
            emptyTable: "Tidak ada data yang tersedia",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya",
            },
        },
    };

    // --- Wait for jQuery & DataTables to be loaded ---
    function waitForLibraries() {
        return new Promise((resolve) => {
            function checkLibraries() {
                if (
                    typeof $ === "undefined" ||
                    typeof $.fn.DataTable === "undefined"
                ) {
                    setTimeout(checkLibraries, 100);
                    return;
                }
                resolve();
            }
            checkLibraries();
        });
    }

    // --- Build Custom Pagination (Tabler.io style) ---
    function buildCustomPagination(api, paginationSelector) {
        const pageInfo = api.page.info();
        const pagination = $(paginationSelector);
        pagination.empty();

        let paginationHtml = "";
        const totalPages = pageInfo.pages;
        const currentPage = pageInfo.page + 1;

        // Previous button
        const prevDisabled = pageInfo.page === 0 ? "disabled" : "";
        paginationHtml += `
            <li class="page-item ${prevDisabled}">
                <a class="page-link datatable-pagination-btn" href="#" data-action="previous" ${
                    prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ""
                }>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M15 6l-6 6l6 6"/>
                    </svg>
                    sebelumnya
                </a>
            </li>
        `;

        // Smart page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        // First page shortcut
        if (startPage > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link datatable-pagination-btn" href="#" data-action="page" data-page="0">1</a></li>`;
            if (startPage > 2) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
            }
        }

        // Visible page numbers
        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === currentPage ? "active" : "";
            const pageIndex = i - 1;
            paginationHtml += `<li class="page-item ${isActive}"><a class="page-link datatable-pagination-btn" href="#" data-action="page" data-page="${pageIndex}">${i}</a></li>`;
        }

        // Last page shortcut
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
            }
            paginationHtml += `<li class="page-item"><a class="page-link datatable-pagination-btn" href="#" data-action="page" data-page="${
                totalPages - 1
            }">${totalPages}</a></li>`;
        }

        // Next button
        const nextDisabled =
            pageInfo.page === pageInfo.pages - 1 ? "disabled" : "";
        paginationHtml += `
            <li class="page-item ${nextDisabled}">
                <a class="page-link datatable-pagination-btn" href="#" data-action="next" ${
                    nextDisabled ? 'tabindex="-1" aria-disabled="true"' : ""
                }>
                    selanjutnya
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M9 6l6 6l-6 6"/>
                    </svg>
                </a>
            </li>
        `;

        pagination.html(paginationHtml);
        setupPaginationEventHandlers(paginationSelector, api);
    }

    // --- Pagination Button Event Handlers ---
    function setupPaginationEventHandlers(paginationSelector, api) {
        // Remove old handlers to avoid duplicates
        $(paginationSelector).off("click.datatable-pagination");

        // Delegate click events for pagination
        $(paginationSelector).on(
            "click.datatable-pagination",
            ".datatable-pagination-btn",
            function (e) {
                e.preventDefault();
                const $btn = $(this);
                const action = $btn.data("action");
                const page = $btn.data("page");
                if ($btn.parent().hasClass("disabled")) return false;
                if (action === "previous") {
                    api.page("previous").draw("page");
                } else if (action === "next") {
                    api.page("next").draw("page");
                } else if (action === "page") {
                    api.page(page).draw("page");
                }
                return false;
            }
        );
    }

    // --- Standard Draw Callback for DataTable ---
    function standardDrawCallback(settings, options = {}) {
        const api = new $.fn.dataTable.Api(settings);
        const opts = {
            paginationSelector: "#datatable-pagination",
            tableInstance: null,
            updateRecordInfo: false,
            ...options,
        };
        
        buildCustomPagination(api, opts.paginationSelector);
        // Optionally update record info
        if (
            opts.updateRecordInfo &&
            typeof window.updateRecordInfo === "function"
        ) {
            window.updateRecordInfo(settings);
        }
    }

    // --- Page Length Dropdown Handler ---
    function createPageLengthHandler(
        tableInstance,
        pageCountSelector = "#page-count"
    ) {
        return function (event) {
            event.preventDefault();

            // Get value dari data-value attribute atau text content
            let value = parseInt(event.target.getAttribute("data-value"));
            if (!value) {
                // Fallback ke parsing text jika data-value tidak ada
                value = parseInt($(event.target).text(), 10);
            }

            if (
                !isNaN(value) &&
                tableInstance &&
                typeof tableInstance.page === "object"
            ) {
                // Update page length
                tableInstance.page.len(value).draw();

                // Update display counter
                $(pageCountSelector).text(value);

                console.log("Page length updated to:", value);
            }
        };
    }

    // --- Search Input Handler ---
    function createSearchHandler(
        tableInstance,
        searchInputSelector = "#advanced-table-search"
    ) {
        // Clear previous handlers
        $(searchInputSelector).off("keyup.search input.search");

        // Setup search dengan delay untuk performa
        let searchTimeout;
        $(searchInputSelector).on("keyup.search input.search", function () {
            clearTimeout(searchTimeout);
            const searchValue = this.value;

            searchTimeout = setTimeout(() => {
                if (
                    tableInstance &&
                    typeof tableInstance.search === "function"
                ) {
                    tableInstance.search(searchValue).draw();
                }
            }, 300); // Delay 300ms untuk mengurangi request
        });
    }

    // --- Setup Page Length Handler Global ---
    function setupPageLengthHandler(
        tableInstance,
        dropdownSelector = ".dropdown-item[data-value]",
        pageCountSelector = "#page-count"
    ) {
        // Clear previous handlers untuk avoid duplicates
        $(dropdownSelector).off("click.pagelength");

        // Setup new handler
        $(dropdownSelector).on("click.pagelength", function (e) {
            e.preventDefault();

            const value = parseInt(this.getAttribute("data-value"));
            if (!isNaN(value) && tableInstance) {
                // Update page length
                tableInstance.page.len(value).draw();

                // Update display
                $(pageCountSelector).text(value);

                console.log("Page length set to:", value);
            }
        });
    }

    // --- Checkbox Selection Handlers (Global) ---
    function setupCheckboxHandlers(selectedArray, updateCallbacks = {}) {
        const {
            onUpdate = () => {},
            onStateChange = () => {},
            onButtonUpdate = () => {},
        } = updateCallbacks;

        // Select all checkbox
        $("#select-all")
            .off("change.global-checkbox")
            .on("change.global-checkbox", function () {
                const isChecked = this.checked;
                $(".table-selectable-check").prop("checked", isChecked);
                updateSelectedArray(selectedArray);
                onUpdate();
                onButtonUpdate();
            });

        // Individual row checkboxes
        $(document)
            .off("change.global-checkbox", ".table-selectable-check")
            .on(
                "change.global-checkbox",
                ".table-selectable-check",
                function () {
                    updateSelectedArray(selectedArray);
                    updateSelectAllState();
                    onStateChange();
                    onButtonUpdate();
                }
            );
    }

    // --- Update Selected Array (Global) ---
    function updateSelectedArray(
        selectedArray,
        countSelector = "#selected-count"
    ) {
        selectedArray.length = 0; // Clear array
        $(".table-selectable-check:checked").each(function () {
            selectedArray.push($(this).val());
        });
        $(countSelector).text(selectedArray.length);
        return selectedArray;
    }

    // --- Update Select All Checkbox State (Global) ---
    function updateSelectAllState(
        selectAllSelector = "#select-all",
        checkboxClass = ".table-selectable-check"
    ) {
        const totalCheckboxes = $(checkboxClass).length;
        const checkedCheckboxes = $(checkboxClass + ":checked").length;
        $(selectAllSelector).prop(
            "indeterminate",
            checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes
        );
        $(selectAllSelector).prop(
            "checked",
            checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0
        );
    }

    // --- Update Record Info (Global) ---
    function updateRecordInfo(
        tableInstance,
        infoSelector = "#record-info",
        entityName = "entries"
    ) {
        if (tableInstance && tableInstance.page) {
            const info = tableInstance.page.info();
            const start = info.start + 1;
            const end = info.end;
            const total = info.recordsTotal;
            const filtered = info.recordsDisplay;

            let infoText = "";
            if (total > 0) {
                if (filtered !== total) {
                    infoText = `Menampilkan <strong>${start} to ${end}</strong> of <strong>${filtered}</strong> ${entityName} (difilter dari <strong>${total}</strong> total)`;
                } else {
                    infoText = `Menampilkan <strong>${start} to ${end}</strong> of <strong>${total}</strong> ${entityName}`;
                }
            } else {
                infoText = `Menampilkan <strong>0 to 0</strong> of <strong>0 ${entityName}</strong>`;
            }

            $(infoSelector).html(infoText);
        }
    }

    // --- Refresh DataTable (Global) ---
    function refreshDataTable(tableInstance, resetSelection = false) {
        if (tableInstance) {
            tableInstance.ajax.reload(null, false);

            if (resetSelection) {
                $("#select-all").prop("checked", false);
                $(".table-selectable-check").prop("checked", false);
            }
        }
    }

    // --- Standard Modal Handlers (Global) ---
    function setupStandardModalHandlers(modalConfigs = []) {
        modalConfigs.forEach((config) => {
            $(config.modalSelector).on("hidden.bs.modal", function () {
                if (config.formSelector) {
                    const form = document.querySelector(config.formSelector);
                    if (form) {
                        form.reset();
                    }
                }
                if (config.callback) {
                    config.callback();
                }
            });
        });
    }

    // --- Keyboard Shortcuts for Search ---
    function setupKeyboardShortcuts(
        searchInputSelector = "#advanced-table-search"
    ) {
        $(document).on("keydown", function (e) {
            // Focus search input on Ctrl+F
            if ((e.ctrlKey || e.metaKey) && e.key === "f") {
                e.preventDefault();
                $(searchInputSelector).focus();
            }
        });
    }

    // --- Select All Checkbox Handler ---
    function setupSelectAllHandler(
        selectAllSelector = "#select-all",
        checkboxClass = ".table-selectable-check"
    ) {
        $(selectAllSelector).on("change", function () {
            const checked = this.checked;
            $(checkboxClass).prop("checked", checked).trigger("change");
        });
    }

    // --- Generate Standard DataTable Config ---
    function generateStandardConfig(options = {}) {
        const defaultConfig = {
            processing: true,
            serverSide: true,
            autoWidth: false,
            dom: "rt",
             classes: {
                sTable: "table table-vcenter table-striped card-table", // opsi ke 2 : table table-vcenter card-table table-selectable
                sWrapper: "table-responsive",
            },
            pageLength: config.defaultPageLength,
            lengthMenu: [config.lengthMenu, config.lengthMenu],
            language: config.language,
            drawCallback: function (settings) {
                standardDrawCallback(settings, options);
            },
        };
        return $.extend(true, {}, defaultConfig, options.tableConfig || {});
    }

    // --- TomSelect Modal Management (Global) ---
    function setupTomSelectModalHandlers(modalConfig, tomSelectInstances) {
        const { createModal, editModal, initCallback } = modalConfig;

        if (createModal) {
            $(createModal).on("show.bs.modal", function () {
                setTimeout(() => {
                    if (initCallback) initCallback("create");
                }, 200);
            });
        }

        if (editModal) {
            $(editModal).on("show.bs.modal", function () {
                setTimeout(() => {
                    if (initCallback) initCallback("edit");
                }, 200);
            });
        }

        if (createModal || editModal) {
            $(`${createModal || ""}, ${editModal || ""}`).on(
                "hidden.bs.modal",
                function () {
                    destroyTomSelects(tomSelectInstances);
                }
            );
        }
    }

    // --- Initialize TomSelect Instances (Global) ---
    function initializeTomSelectInstances(selectors, tomSelectInstances) {
        return waitForTomSelect().then(() => {
            selectors.forEach((selectId) => {
                const element = document.getElementById(selectId);
                if (element && !tomSelectInstances[selectId]) {
                    if (element.tomselect) {
                        try {
                            element.tomselect.destroy();
                        } catch (error) {
                            console.error(
                                `Failed to destroy existing TomSelect for ${selectId}:`,
                                error
                            );
                        }
                    }

                    try {
                        const config = {
                            plugins: ["remove_button", "clear_button"],
                            allowEmptyOption: selectId.includes("parent"),
                            create: false,
                            placeholder: selectId.includes("parent")
                                ? "Select parent menu..."
                                : selectId.includes("roles")
                                ? "Select roles..."
                                : "Select option...",
                        };

                        if (selectId.includes("roles")) {
                            config.plugins = ["remove_button"];
                            config.maxItems = null;
                        }

                        tomSelectInstances[selectId] = new TomSelect(
                            element,
                            config
                        );
                    } catch (error) {
                        console.error(
                            `Failed to initialize TomSelect for ${selectId}:`,
                            error
                        );
                    }
                }
            });
        });
    }

    // --- Unified Bulk Delete Handler (Global) ---
    function setupBulkDeleteHandler(config) {
        const {
            selectedArray,
            deleteRoute,
            entityName = "items",
            tableInstance,
            updateCallback,
            customRequestData = null,
        } = config;

        // Handle bulk delete button click
        $(document)
            .off("click.bulkdelete", ".btn-bulk-delete")
            .on("click.bulkdelete", ".btn-bulk-delete", function () {
                if (selectedArray.length === 0) {
                    if (window.showToast) {
                        window.showToast(
                            "warning",
                            "Warning!",
                            `No ${entityName}s selected`
                        );
                    } else {
                        alert(`No ${entityName}s selected`);
                    }
                    return;
                }

                // Use the new modal confirmation alert system
                if (window.showConfirmationModal) {
                    window.showConfirmationModal({
                        id: "delete-selected",
                        type: "danger",
                        title: `Delete Selected ${
                            entityName.charAt(0).toUpperCase() +
                            entityName.slice(1)
                        }s`,
                        message: `Are you sure you want to delete the selected ${
                            selectedArray.length
                        } ${entityName}${
                            selectedArray.length > 1 ? "s" : ""
                        }? This action cannot be undone.`,
                        confirmText: "Delete Selected",
                        cancelText: "Cancel",
                        onConfirm: function () {
                            performBulkDelete();
                        },
                    });
                } else {
                    // Fallback to basic confirmation
                    if (
                        confirm(
                            `Are you sure you want to delete the selected ${
                                selectedArray.length
                            } ${entityName}${
                                selectedArray.length > 1 ? "s" : ""
                            }?`
                        )
                    ) {
                        performBulkDelete();
                    }
                }
            });

        function performBulkDelete() {
            // Prepare request data
            let requestData;
            if (customRequestData && typeof customRequestData === "function") {
                requestData = customRequestData(selectedArray);
            } else {
                // Auto-detect field name based on entity
                const fieldName =
                    entityName === "permission" ? "ids" : `${entityName}_ids`;
                requestData = { [fieldName]: selectedArray };
            }

            // Create a form and submit it to trigger session-based modal alerts
            const form = document.createElement("form");
            form.method = "POST";
            form.action = deleteRoute;
            form.style.display = "none";

            // Add CSRF token
            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            form.appendChild(csrfInput);

            // Add method override for DELETE
            const methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "DELETE";
            form.appendChild(methodInput);

            // Add the selected IDs
            Object.entries(requestData).forEach(([key, value]) => {
                if (Array.isArray(value)) {
                    value.forEach((id) => {
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = `${key}[]`;
                        input.value = id;
                        form.appendChild(input);
                    });
                } else {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
            });

            // Append and submit the form
            document.body.appendChild(form);
            form.submit();
        }
    }

    // --- Update Select Options (Global) ---
    function updateSelectOptions(selectId, options, cleanText = false) {
        const selectElement = document.getElementById(selectId);
        if (!selectElement) return;

        while (selectElement.children.length > 1) {
            selectElement.removeChild(selectElement.lastChild);
        }

        Object.entries(options).forEach(([value, text]) => {
            const option = document.createElement("option");
            option.value = value;
            option.textContent = cleanText
                ? text.replace(/[└├│─\s]+/g, "").trim()
                : text;
            selectElement.appendChild(option);
        });
    }

    // --- TomSelect Utilities ---
    function waitForTomSelect() {
        return new Promise((resolve) => {
            const checkTomSelect = () => {
                if (typeof TomSelect !== "undefined") {
                    resolve();
                } else {
                    setTimeout(checkTomSelect, 100);
                }
            };
            checkTomSelect();
        });
    }

    function destroyTomSelects(tomSelectInstances) {
        Object.values(tomSelectInstances).forEach((instance) => {
            if (instance && typeof instance.destroy === "function") {
                try {
                    instance.destroy();
                } catch (error) {
                    console.warn("Error destroying TomSelect:", error);
                }
            }
        });
        // Clear the instances object
        Object.keys(tomSelectInstances).forEach((key) => {
            delete tomSelectInstances[key];
        });
    }

    function setTomSelectValue(tomSelectInstances, selectId, value) {
        const instance = tomSelectInstances[selectId];
        if (instance) {
            try {
                if (Array.isArray(value)) {
                    instance.setValue(
                        value.map((v) =>
                            typeof v === "object" ? v.id || v.name || v : v
                        )
                    );
                } else {
                    instance.setValue(value || "");
                }
            } catch (error) {
                console.warn(
                    `Failed to set TomSelect value for ${selectId}:`,
                    error
                );
            }
        }
    }

    function syncTomSelects(tomSelectInstances) {
        Object.values(tomSelectInstances).forEach((instance) => {
            if (instance && typeof instance.sync === "function") {
                try {
                    instance.sync();
                } catch (error) {
                    console.warn("TomSelect sync error:", error);
                }
            }
        });
    }

    // --- Initialize DataTable with All Features ---
    function initializeDataTable(tableSelector, options = {}) {
        return waitForLibraries().then(() => {
            const config = generateStandardConfig(options);
            const table = $(tableSelector).DataTable(config);
            // Setup search, page length, keyboard shortcuts, etc if provided
            if (options.searchInputSelector) {
                createSearchHandler(table, options.searchInputSelector);
            }
            if (options.pageCountSelector) {
                updatePageCount(config.pageLength, options.pageCountSelector);
            }
            if (options.pageLengthHandlerSelector) {
                setupPageLengthHandler(
                    table,
                    options.pageLengthHandlerSelector,
                    options.pageCountSelector
                );
            }
            if (options.selectAllSelector) {
                setupSelectAllHandler(
                    options.selectAllSelector,
                    options.checkboxClass
                );
            }
            if (options.enableKeyboardShortcuts) {
                setupKeyboardShortcuts(options.searchInputSelector);
            }
            return table;
        });
    } // --- Public API ---
    return {
        // Core utilities
        waitForLibraries,
        buildCustomPagination,
        standardDrawCallback,
        initializeDataTable,
        generateStandardConfig,
        setupPaginationEventHandlers,

        // Event helpers
        createPageLengthHandler,
        createSearchHandler,
        setupKeyboardShortcuts,
        setupSelectAllHandler,
        setupPageLengthHandler,

        // Global Helpers
        setupCheckboxHandlers,
        updateSelectedArray,
        updateSelectAllState,
        updateRecordInfo,
        refreshDataTable,
        setupStandardModalHandlers,

        // TomSelect utilities
        waitForTomSelect,
        destroyTomSelects,
        setTomSelectValue,
        syncTomSelects,
        setupTomSelectModalHandlers,
        initializeTomSelectInstances,

        // Additional utilities
        setupBulkDeleteHandler,
        updateSelectOptions,

        // Config
        config,

        // Utility: update page count display
        updatePageCount: function (value, selector = "#page-count") {
            $(selector).text(value);
        },
    };
})();

// --- Global Helper Functions (for backward compatibility) ---
window.updatePagination = function (settings) {
    DataTableGlobal.standardDrawCallback(settings);
};

window.updateRecordInfo = function (settings) {
    // Default empty implementation - can be overridden per page
    console.log("Record info updated", settings);
};
