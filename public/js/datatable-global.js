/**
 * Global DataTable Components
 * Reusable DataTable functions and utilities
 *
 * @author KantorKu SuperApp Team
 * @version 1.0.0
 */

window.DataTableGlobal = (function () {
    "use strict";

    // --- Configuration ---
    const config = {
        defaultPageLength: 15,
        lengthMenu: [10, 15, 25, 50, 100],
        language: {
            processing: "Memuat...",
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

    // --- Library Loading Check ---
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

    // --- Pagination Utilities ---
    function buildCustomPagination(
        api,
        paginationSelector,
        tableInstance = null
    ) {
        const pageInfo = api.page.info();
        const pagination = $(paginationSelector);
        pagination.empty();

        // Build custom pagination with Tabler.io styling
        let paginationHtml = "";
        const totalPages = pageInfo.pages;
        const currentPage = pageInfo.page + 1;

        // Previous button with proper disabled state
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

        // Page numbers with smart pagination
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        // First page link if not in visible range
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

        // Last page link if not in visible range
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
            }

            paginationHtml += `<li class="page-item"><a class="page-link datatable-pagination-btn" href="#" data-action="page" data-page="${
                totalPages - 1
            }">${totalPages}</a></li>`;
        }

        // Next button with proper disabled state
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

        // Setup event delegation for pagination buttons
        setupPaginationEventHandlers(paginationSelector, api);
    }

    // --- Pagination Event Handlers ---
    function setupPaginationEventHandlers(paginationSelector, api) {
        // Remove existing handlers to prevent duplicate bindings
        $(paginationSelector).off("click.datatable-pagination");

        // Add event delegation for pagination buttons
        $(paginationSelector).on(
            "click.datatable-pagination",
            ".datatable-pagination-btn",
            function (e) {
                e.preventDefault();

                const $btn = $(this);
                const action = $btn.data("action");
                const page = $btn.data("page");

                if ($btn.parent().hasClass("disabled")) {
                    return false;
                }

                if (action === "previous") {
                    api.page("previous").draw(false);
                } else if (action === "next") {
                    api.page("next").draw(false);
                } else if (action === "page" && page !== undefined) {
                    api.page(parseInt(page)).draw(false);
                }

                return false;
            }
        );
    }

    // --- Standard DrawCallback Function ---
    function standardDrawCallback(settings, options = {}) {
        const api = new $.fn.dataTable.Api(settings);
        const opts = {
            paginationSelector: "#datatable-pagination",
            tableInstance: null,
            updateRecordInfo: false,
            ...options,
        };

        // Build pagination (no longer need tableInstance parameter)
        buildCustomPagination(api, opts.paginationSelector);

        // Update record info if needed
        if (
            opts.updateRecordInfo &&
            typeof window.updateRecordInfo === "function"
        ) {
            window.updateRecordInfo(settings);
        }
    }

    // --- Page Length Change Handler ---
    function createPageLengthHandler(
        tableInstance,
        pageCountSelector = "#page-count"
    ) {
        return function (event) {
            event.preventDefault();
            const value = parseInt(event.target.getAttribute("data-value"));

            if (typeof tableInstance === "string") {
                // If tableInstance is a string, use window[tableInstance]
                window[tableInstance].page.len(value).draw();
            } else {
                // If tableInstance is the actual DataTable object
                tableInstance.page.len(value).draw();
            }

            $(pageCountSelector).text(value);
        };
    }

    // --- Search Handler ---
    function createSearchHandler(
        tableInstance,
        searchInputSelector = "#advanced-table-search"
    ) {
        $(searchInputSelector).on("keyup", function () {
            if (typeof tableInstance === "string") {
                window[tableInstance].search(this.value).draw();
            } else {
                tableInstance.search(this.value).draw();
            }
        });
    }

    // --- Keyboard Shortcut Handler ---
    function setupKeyboardShortcuts(
        searchInputSelector = "#advanced-table-search"
    ) {
        $(document).on("keydown", function (e) {
            if (e.ctrlKey && e.key === "k") {
                e.preventDefault();
                $(searchInputSelector).focus();
            }
        });
    }

    // --- Select All Handler ---
    function setupSelectAllHandler(
        selectAllSelector = "#select-all",
        checkboxClass = ".table-selectable-check"
    ) {
        $(selectAllSelector).on("change", function () {
            $(checkboxClass).prop("checked", this.checked);
        });
    }

    // --- Standard DataTable Configuration Generator ---
    function generateStandardConfig(options = {}) {
        const defaultConfig = {
            processing: true,
            serverSide: true,
            autoWidth: false,
            dom: "rt",
            pageLength: config.defaultPageLength,
            lengthMenu: [config.lengthMenu, config.lengthMenu],
            language: config.language,
            drawCallback: function (settings) {
                standardDrawCallback(
                    settings,
                    options.drawCallbackOptions || {}
                );
            },
        };

        return $.extend(true, {}, defaultConfig, options.tableConfig || {});
    }

    // --- Initialize Complete DataTable Setup ---
    function initializeDataTable(tableSelector, options = {}) {
        return waitForLibraries().then(() => {
            const opts = {
                tableConfig: {},
                drawCallbackOptions: {},
                searchInputSelector: "#advanced-table-search",
                selectAllSelector: "#select-all",
                checkboxClass: ".table-selectable-check",
                pageCountSelector: "#page-count",
                enableSearch: true,
                enableSelectAll: false,
                enableKeyboardShortcuts: true,
                ...options,
            };

            // Generate configuration
            const config = generateStandardConfig(opts);

            // Initialize DataTable
            const table = $(tableSelector).DataTable(config);

            // Setup additional handlers
            if (opts.enableSearch) {
                createSearchHandler(table, opts.searchInputSelector);
            }

            if (opts.enableSelectAll) {
                setupSelectAllHandler(
                    opts.selectAllSelector,
                    opts.checkboxClass
                );
            }

            if (opts.enableKeyboardShortcuts) {
                setupKeyboardShortcuts(opts.searchInputSelector);
            }

            // Create page length handler and expose globally
            window.setPageListItems = createPageLengthHandler(
                table,
                opts.pageCountSelector
            );

            return table;
        });
    }

    // --- Public API ---
    return {
        // Core functions
        waitForLibraries,
        buildCustomPagination,
        standardDrawCallback,
        initializeDataTable,
        generateStandardConfig,
        setupPaginationEventHandlers,

        // Event handlers
        createPageLengthHandler,
        createSearchHandler,
        setupKeyboardShortcuts,
        setupSelectAllHandler,

        // Configuration
        config,

        // Utilities
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