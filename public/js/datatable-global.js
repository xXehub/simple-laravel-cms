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
                <a class="page-link datatable-pagination-btn" href="#" data-action="previous" ${prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ""}>
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
            paginationHtml += `<li class="page-item"><a class="page-link datatable-pagination-btn" href="#" data-action="page" data-page="${totalPages - 1}">${totalPages}</a></li>`;
        }

        // Next button
        const nextDisabled = pageInfo.page === pageInfo.pages - 1 ? "disabled" : "";
        paginationHtml += `
            <li class="page-item ${nextDisabled}">
                <a class="page-link datatable-pagination-btn" href="#" data-action="next" ${nextDisabled ? 'tabindex="-1" aria-disabled="true"' : ""}>
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
        if (opts.updateRecordInfo && typeof window.updateRecordInfo === "function") {
            window.updateRecordInfo(settings);
        }
    }

    // --- Page Length Dropdown Handler ---
    function createPageLengthHandler(tableInstance, pageCountSelector = "#page-count") {
        return function (event) {
            event.preventDefault();
            const value = parseInt($(event.target).text(), 10);
            if (!isNaN(value)) {
                tableInstance.page.len(value).draw();
                $(pageCountSelector).text(value);
            }
        };
    }

    // --- Search Input Handler ---
    function createSearchHandler(tableInstance, searchInputSelector = "#advanced-table-search") {
        $(searchInputSelector).on("keyup", function () {
            tableInstance.search(this.value).draw();
        });
    }

    // --- Keyboard Shortcuts for Search ---
    function setupKeyboardShortcuts(searchInputSelector = "#advanced-table-search") {
        $(document).on("keydown", function (e) {
            // Focus search input on Ctrl+F
            if ((e.ctrlKey || e.metaKey) && e.key === "f") {
                e.preventDefault();
                $(searchInputSelector).focus();
            }
        });
    }

    // --- Select All Checkbox Handler ---
    function setupSelectAllHandler(selectAllSelector = "#select-all", checkboxClass = ".table-selectable-check") {
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
            pageLength: config.defaultPageLength,
            lengthMenu: [config.lengthMenu, config.lengthMenu],
            language: config.language,
            drawCallback: function (settings) {
                standardDrawCallback(settings, options);
            },
        };
        return $.extend(true, {}, defaultConfig, options.tableConfig || {});
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
                $(options.pageCountSelector).text(config.pageLength);
            }
            if (options.pageLengthHandlerSelector) {
                $(options.pageLengthHandlerSelector).on("click", createPageLengthHandler(table, options.pageCountSelector));
            }
            if (options.selectAllSelector) {
                setupSelectAllHandler(options.selectAllSelector, options.checkboxClass);
            }
            if (options.enableKeyboardShortcuts) {
                setupKeyboardShortcuts(options.searchInputSelector);
            }
            return table;
        });
    }

    // --- Public API ---
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
