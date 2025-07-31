/**
 * Menus DataTable Configuration
 * Clean version following users.js pattern with simple caching
 * @author KantorKu SuperApp Team
 * @version 3.1.0
 */

window.MenusDataTable = (function () {
    "use strict";

    let menusTable;
    let selectedMenus = [];
    let tomSelectInstances = {};
    let parentMenuOptions = {};
    let menuCache = new Map(); // Cache for faster edit loading
    let parentMenuCache = null; // Cache parent menus

    // --- Table Configuration ---
    function getTableConfig(route) {
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: {
                    url: route,
                    type: "GET",
                    dataSrc: function (json) {
                        // Cache menu data for fast edit
                        if (json.data && Array.isArray(json.data)) {
                            json.data.forEach((menu) => {
                                if (menu.id) menuCache.set(menu.id, menu);
                            });
                        }
                        return json.data;
                    },
                },
                order: [
                    [7, "asc"], // urutan column
                    [3, "asc"], // nama_menu column
                ],
                deferRender: true,
            },
        });

        const menusConfig = {
            columns: [
                {
                    // Checkbox
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) =>
                        `<input class="form-check-input m-0 align-middle table-selectable-check" 
                               type="checkbox" value="${row.id}"/>`,
                },
                {
                    // Order controls
                    data: "order_controls",
                    name: "order_controls",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                },
                {
                    // Icon
                    data: "icon",
                    name: "icon",
                    orderable: false,
                    className: "text-center",
                    render: (data) =>
                        data
                            ? `<span class="avatar avatar-xs me-2"><i class="${data}"></i></span>`
                            : '<span class="text-muted">-</span>',
                },
                {
                    // Nama Menu
                    data: "nama_menu",
                    name: "nama_menu",
                },
                {
                    // Slug
                    data: "slug",
                    name: "slug",
                    className: "text-secondary",
                    render: (data) => `<code>${data}</code>`,
                },
                {
                    // Parent
                    data: "parent",
                    name: "parent_id",
                    orderable: false,
                    className: "text-secondary",
                    render: (data) =>
                        data
                            ? `<span class="badge bg-secondary-lt">${data}</span>`
                            : '<span class="text-muted">-</span>',
                },
                {
                    // Route name
                    data: "route_name",
                    name: "route_name",
                    render: (data) =>
                        data
                            ? `<code>${data}</code>`
                            : '<span class="text-muted">-</span>',
                },
                {
                    // Urutan
                    data: "urutan",
                    name: "urutan",
                    className: "text-center",
                },
                {
                    // Status
                    data: "is_active",
                    name: "is_active",
                    orderable: false,
                    className: "text-center",
                    render: (data) =>
                        data
                            ? '<span class="badge bg-success-lt">Active</span>'
                            : '<span class="badge bg-danger-lt">Inactive</span>',
                },
                {
                    // Roles
                    data: "roles",
                    name: "roles",
                    orderable: false,
                    render: (data) => {
                        if (data && data.length > 0) {
                            return data
                                .map((role) => {
                                    const roleName =
                                        typeof role === "object"
                                            ? role.name
                                            : role;
                                    return `<span class="badge bg-secondary-lt me-1">${roleName}</span>`;
                                })
                                .join("");
                        }
                        return '<span class="badge bg-danger-lt me-1">Tidak Ada</span>';
                    },
                },
                {
                    // Actions
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                },
            ],
            drawCallback: function () {
                DataTableGlobal.buildCustomPagination(
                    menusTable,
                    "#datatable-pagination"
                );
                DataTableGlobal.updateRecordInfo(
                    menusTable,
                    "#record-info",
                    "menus"
                );
                DataTableGlobal.updateSelectAllState();
                updateBulkDeleteButton();
            },
        };

        return $.extend(true, {}, baseConfig, menusConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            menusTable = $("#menusTable").DataTable(getTableConfig(route));

            DataTableGlobal.createSearchHandler(
                menusTable,
                "#advanced-table-search"
            );
            DataTableGlobal.setupPageLengthHandler(
                menusTable,
                ".dropdown-item[data-value]",
                "#page-count"
            );
            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            setupEventHandlers();

            return menusTable;
        });
    }

    // --- TomSelect Management ---
    function initializeTomSelectInstances() {
        const selectors = [
            "create_route_type",
            "create_parent_id",
            "create_roles",
            "edit_route_type",
            "edit_parent_id",
            "edit_roles",
        ];

        return DataTableGlobal.initializeTomSelectInstances(
            selectors,
            tomSelectInstances
        );
    }

    function clearTomSelectInstances() {
        DataTableGlobal.destroyTomSelects(tomSelectInstances);
    }

    function resetCreateForm() {
        $("#createMenuForm")[0]?.reset();

        DataTableGlobal.setTomSelectValue(
            tomSelectInstances,
            "create_route_type",
            "public"
        );
        DataTableGlobal.setTomSelectValue(
            tomSelectInstances,
            "create_parent_id",
            ""
        );
        DataTableGlobal.setTomSelectValue(
            tomSelectInstances,
            "create_roles",
            []
        );
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        DataTableGlobal.setupCheckboxHandlers(selectedMenus, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton,
        });

        setupModalHandlers();
        setupTomSelectModalHandlers();
    }

    function setupTomSelectModalHandlers() {
        DataTableGlobal.setupTomSelectModalHandlers(
            {
                createModal: "#createMenuModal",
                editModal: "#editMenuModal",
                initCallback: (type) => {
                    initializeTomSelectInstances().then(() => {
                        if (type === "create") resetCreateForm();
                    });
                },
            },
            tomSelectInstances
        );
    }

    function updateBulkDeleteButton() {
        $("#delete-selected-btn").prop("disabled", selectedMenus.length === 0);
        $("#selected-count").text(selectedMenus.length);
    }

    // --- Bulk Delete ---
    function setupBulkDeleteHandlers() {
        DataTableGlobal.setupBulkDeleteHandler({
            modalSelector: "#deleteSelectedModal",
            selectedArray: selectedMenus,
            deleteRoute: bulkDeleteRoute,
            confirmBtnSelector: "#confirm-delete-selected",
            entityName: "menu",
            tableInstance: menusTable,
            updateCallback: updateBulkDeleteButton,
        });
    }

    // --- Menu Management with Cache ---
    function openCreateModal(route) {
        if (parentMenuCache) {
            parentMenuOptions = parentMenuCache;
            updateCreateParentMenuSelect();
            initializeTomSelectInstances().then(() => {
                resetCreateForm();
                $("#createMenuModal").modal("show");
            });
        } else {
            refreshParentMenuOptions(route)
                .then(() => {
                    updateCreateParentMenuSelect();
                    return initializeTomSelectInstances();
                })
                .then(() => {
                    resetCreateForm();
                    $("#createMenuModal").modal("show");
                });
        }
    }

    function openEditModal(menu, route) {
        const cachedMenu = menuCache.get(menu.id) || menu;

        if (parentMenuCache) {
            parentMenuOptions = parentMenuCache;
            updateParentMenuSelect();
            initializeTomSelectInstances().then(() => {
                fillEditModal(cachedMenu);
                $("#editMenuModal").modal("show");
            });
        } else {
            refreshParentMenuOptions(route)
                .then(() => {
                    updateParentMenuSelect();
                    return initializeTomSelectInstances();
                })
                .then(() => {
                    fillEditModal(cachedMenu);
                    $("#editMenuModal").modal("show");
                });
        }
    }

    function openDeleteModal(menu) {
        const cachedMenu = menuCache.get(menu.id) || menu;

        $("#delete_menu_id").val(cachedMenu.id);
        $("#delete_menu_name").text(cachedMenu.nama_menu);
        $("#delete_menu_slug").text(cachedMenu.slug);
        $("#delete_menu_route").text(cachedMenu.route_name || "-");

        const form = $("#deleteMenuForm")[0];
        if (form && cachedMenu.id) {
            form.action = form.action.includes(":id")
                ? form.action.replace(":id", cachedMenu.id)
                : form.action.replace(/\/\d+$/, "") + "/" + cachedMenu.id;
        }

        $("#deleteMenuModal").modal("show");
    }

    function fillEditModal(menu) {
        if (!$("#edit_menu_id").length) return;

        const form = $("#editMenuForm")[0];
        if (form && menu.id) {
            form.action = form.action.includes(":id")
                ? form.action.replace(":id", menu.id)
                : form.action.replace(/\/\d+$/, "") + "/" + menu.id;
        }

        // Fill basic fields
        $("#edit_menu_id").val(menu.id);
        $("#edit_nama_menu").val(menu.nama_menu);
        $("#edit_slug").val(menu.slug);
        $("#edit_route_name").val(menu.route_name || "");
        $("#edit_controller_class").val(menu.controller_class || "");
        $("#edit_view_path").val(menu.view_path || "");
        $("#edit_icon").val(menu.icon || "");
        $("#edit_middleware_list").val(menu.middleware_list || "");
        $("#edit_meta_title").val(menu.meta_title || "");
        $("#edit_meta_description").val(menu.meta_description || "");
        $("#edit_urutan").val(menu.urutan);
        $("#edit_is_active").prop("checked", menu.is_active == 1);

        // Set TomSelect values
        DataTableGlobal.setTomSelectValue(
            tomSelectInstances,
            "edit_route_type",
            menu.route_type || "public"
        );

        const parentValue = menu.parent_id ? String(menu.parent_id) : "";
        DataTableGlobal.setTomSelectValue(
            tomSelectInstances,
            "edit_parent_id",
            parentValue
        );

        // Handle roles
        let roleValues = [];
        if (Array.isArray(menu.roles)) {
            roleValues = menu.roles.map((role) => {
                if (typeof role === "object") {
                    return role.id
                        ? String(role.id)
                        : String(role.name || role);
                }
                return String(role);
            });
        } else if (menu.roles && typeof menu.roles === "string") {
            roleValues = menu.roles.split(",").map((r) => String(r.trim()));
        }

        if (tomSelectInstances["edit_roles"] && roleValues.length > 0) {
            const availableOptions = Object.keys(
                tomSelectInstances["edit_roles"].options
            );
            const validRoleValues = roleValues.filter((value) =>
                availableOptions.includes(value)
            );

            if (validRoleValues.length === 0 && Array.isArray(menu.roles)) {
                const rolesSelect = document.getElementById("edit_roles");
                if (rolesSelect) {
                    Array.from(rolesSelect.options).forEach((option) => {
                        const shouldSelect = menu.roles.some((role) => {
                            const roleName =
                                typeof role === "object" ? role.name : role;
                            return roleName === option.textContent.trim();
                        });
                        if (shouldSelect) validRoleValues.push(option.value);
                    });
                }
            }

            DataTableGlobal.setTomSelectValue(
                tomSelectInstances,
                "edit_roles",
                validRoleValues
            );
        }

        DataTableGlobal.syncTomSelects(tomSelectInstances);
    }

    // --- Utility Functions ---
    function refreshParentMenuOptions(route) {
        return fetch(route, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                parentMenuOptions = data.parentMenus || data.parent_menus || {};
                parentMenuCache = parentMenuOptions; // Cache it
                return parentMenuOptions;
            })
            .catch((error) => {
                console.error("Error fetching parent menu options:", error);
                parentMenuOptions = {};
                return parentMenuOptions;
            });
    }

    function updateParentMenuSelect() {
        DataTableGlobal.updateSelectOptions(
            "edit_parent_id",
            parentMenuOptions,
            true
        );
    }

    function updateCreateParentMenuSelect() {
        DataTableGlobal.updateSelectOptions(
            "create_parent_id",
            parentMenuOptions,
            true
        );
    }

    function filterTable(type) {
        if (!menusTable) return;

        if (type === "active") {
            menusTable.columns(8).search("1").draw();
        } else if (type === "inactive") {
            menusTable.columns(8).search("0").draw();
        } else {
            menusTable.columns(8).search("").draw();
        }
    }

    // --- Menu Order Handlers ---
    function setupMenuOrderHandlers(moveOrderRoute) {
        $(document).off("click.menu-order", ".move-menu-btn");

        $(document).on("click.menu-order", ".move-menu-btn", function (e) {
            e.preventDefault();
            e.stopPropagation();

            const menuId = $(this).data("menu-id");
            const direction = $(this).data("direction");

            if (menuId && direction) {
                moveMenuOrder(menuId, direction, moveOrderRoute);
            }
        });
    }

    function moveMenuOrder(menuId, direction, moveOrderRoute) {
        const url = moveOrderRoute
            .replace(":id", menuId)
            .replace(":direction", direction);

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                "X-Requested-With": "XMLHttpRequest",
            },
            body: new URLSearchParams({
                menu_id: menuId,
                direction: direction,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    menuCache.clear(); // Clear cache after order change
                    parentMenuCache = null;
                    menusTable.ajax.reload(null, false);
                    if (window.showToast) {
                        window.showToast("success", "Success!", data.message);
                    }
                } else {
                    throw new Error(data.message || "Failed to move menu");
                }
            })
            .catch((error) => {
                console.error("Move menu error:", error);
                if (window.showToast) {
                    window.showToast(
                        "error",
                        "Error!",
                        "Failed to move menu order"
                    );
                }
            });
    }

    // --- Helper Functions ---
    function setupModalHandlers() {
        DataTableGlobal.setupStandardModalHandlers([
            {
                modalSelector: "#createMenuModal",
                formSelector: "#createMenuForm",
            },
            {
                modalSelector: "#editMenuModal",
                formSelector: "#editMenuModal",
            },
        ]);
    }

    function setPageListItems(event) {
        DataTableGlobal.createPageLengthHandler(
            menusTable,
            "#page-count"
        )(event);
    }

    let bulkDeleteRoute = "";

    function setBulkDeleteRoute(route) {
        bulkDeleteRoute = route;
        if (menusTable) {
            setupBulkDeleteHandlers();
        }
    }

    // --- Initialize All Handlers ---
    function initializeAllHandlers(bulkDeleteRoute, csrfToken) {
        setBulkDeleteRoute(bulkDeleteRoute);
        setupEventHandlers();
    }

    // --- Public API ---
    return {
        initialize,
        filterTable,
        openCreateModal,
        openEditModal,
        openDeleteModal,
        moveMenuOrder,
        setupMenuOrderHandlers,
        initializeAllHandlers,
        refreshParentMenuOptions,
        setupModalHandlers,
        setPageListItems,
        setBulkDeleteRoute,
        setupEventHandlers,
        initializeTomSelectInstances,
        getTable: () => menusTable,
        getSelectedMenus: () => selectedMenus,
        refreshDataTable: () => {
            menuCache.clear();
            parentMenuCache = null;
            return DataTableGlobal.refreshDataTable(menusTable);
        },
        getTomSelectInstances: () => tomSelectInstances,
        updateRecordInfo: () =>
            DataTableGlobal.updateRecordInfo(
                menusTable,
                "#record-info",
                "menus"
            ),
    };
})();

// --- Legacy Support ---
window.filterTable = (type) => MenusDataTable.filterTable(type);
window.openCreateModal = (route) => MenusDataTable.openCreateModal(route);
window.openEditModal = (menu, route) =>
    MenusDataTable.openEditModal(menu, route);
window.openDeleteModal = (menu) => MenusDataTable.openDeleteModal(menu);
window.setPageListItems = (event) => MenusDataTable.setPageListItems(event);
