/**
 * Permissions DataTable Configuration - Optimized Version
 * @author KantorKu SuperApp Team
 * @version 2.3.0 - Faster edit modal with cache optimization
 */

window.PermissionsDataTable = (function () {
    "use strict";

    let permissionsTable;
    let selectedPermissions = [];
    let permissionCache = new Map(); // Cache for faster edit loading

    // --- Table Configuration ---
    function getTableConfig(route) {
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: { url: route, type: "GET" },
                order: [[2, "asc"]],
                deferRender: true,
            },
        });

        const permissionsConfig = {
            columns: [
                {
                    // Checkbox
                    data: "checkbox",
                    name: "checkbox",
                    orderable: false,
                    searchable: false,
                    width: "30px",
                    render: (data, type, row) => {
                        // Cache data saat render untuk edit cepat
                        permissionCache.set(row.id, row);
                        return data; // Return checkbox HTML from server
                    },
                },
                {
                    // Row number
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false,
                    width: "60px",
                    render: (data, type, row, meta) =>
                        meta.row + meta.settings._iDisplayStart + 1,
                },
                { data: "name", name: "name" },
                {
                    data: "group_badge",
                    name: "group",
                    orderable: false,
                    searchable: true,
                },
                { data: "guard_name", name: "guard_name" },
                {
                    // Created date
                    data: "created_at",
                    name: "created_at",
                    render: (data) =>
                        new Date(data).toLocaleDateString("en-US", {
                            year: "numeric",
                            month: "short",
                            day: "numeric",
                        }),
                },
                {
                    // Actions
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                    width: "120px",
                },
            ],
            drawCallback: function () {
                DataTableGlobal.buildCustomPagination(
                    permissionsTable,
                    "#datatable-pagination"
                );
                DataTableGlobal.updateRecordInfo(
                    permissionsTable,
                    "#record-info",
                    "permissions"
                );
                DataTableGlobal.updateSelectAllState();
                updateBulkDeleteButton();

                // Manage cache size
                if (permissionCache.size > 500) permissionCache.clear();
            },
        };

        return $.extend(true, {}, baseConfig, permissionsConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            // Check if table element exists
            const tableElement = $("#datatable-permissions");
            if (tableElement.length === 0) {
                console.error("Table element #datatable-permissions not found");
                return null;
            }

            permissionsTable = tableElement.DataTable(getTableConfig(route));

            // Setup handlers dengan safety checks
            if ($("#advanced-table-search").length > 0) {
                DataTableGlobal.createSearchHandler(
                    permissionsTable,
                    "#advanced-table-search"
                );
            }

            if (
                $(".dropdown-item[data-value]").length > 0 &&
                $("#page-count").length > 0
            ) {
                DataTableGlobal.setupPageLengthHandler(
                    permissionsTable,
                    ".dropdown-item[data-value]",
                    "#page-count"
                );
            }

            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            setupEventHandlers();
            setupModalHandlers();

            return permissionsTable;
        });
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        DataTableGlobal.setupCheckboxHandlers(selectedPermissions, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton,
        });
    }

    function updateBulkDeleteButton() {
        const deleteBtn = $("#delete-selected-btn");
        const selectedCount = $("#selected-count");

        if (deleteBtn.length > 0) {
            deleteBtn.prop("disabled", selectedPermissions.length === 0);
        }
        if (selectedCount.length > 0) {
            selectedCount.text(selectedPermissions.length);
        }
    }

    // --- Modal Handlers dengan Safety Checks ---
    function setupModalHandlers() {
        // Clear form when create modal is closed
        const createModal = document.getElementById("createPermissionModal");
        if (createModal) {
            createModal.addEventListener("hidden.bs.modal", function () {
                const form = document.getElementById("createPermissionForm");
                if (form) form.reset();
            });
        }

        // Clear form when edit modal is closed
        const editModal = document.getElementById("editPermissionModal");
        if (editModal) {
            editModal.addEventListener("hidden.bs.modal", function () {
                const editForm = document.getElementById("editPermissionForm");
                if (editForm) {
                    editForm.reset();
                    // Reset form action URL
                    editForm.action = editForm.action.replace(/\/\d+$/, "/:id");
                }
            });
        }

        // Clear form when delete modal is closed
        const deleteModal = document.getElementById("deletePermissionModal");
        if (deleteModal) {
            deleteModal.addEventListener("hidden.bs.modal", function () {
                const deleteForm = document.getElementById(
                    "deletePermissionForm"
                );
                if (deleteForm) {
                    deleteForm.reset();
                    // Reset form action URL
                    deleteForm.action = deleteForm.action.replace(
                        /\/\d+$/,
                        "/:id"
                    );
                }
            });
        }
    }

    // --- Permission Management - OPTIMIZED ---
    function editPermission(permissionId, editRoute = null) {
        const numericId = parseInt(permissionId);

        // Cek cache dulu - instant loading jika ada
        const cachedPermission = permissionCache.get(numericId);
        if (cachedPermission) {
            fillEditForm(cachedPermission);
            return;
        }

        // Jika tidak ada di cache, fetch dari server
        const route = editRoute || window.permissionEditRoute;
        if (!route) {
            console.error("Edit route not configured");
            alert("Edit route not configured properly");
            return;
        }

        const url = route.replace(":id", permissionId);

        fetch(url, {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(
                        `HTTP ${response.status}: ${response.statusText}`
                    );
                }
                return response.json();
            })
            .then((data) => {
                if (data.permission) {
                    // Cache permission untuk next time
                    permissionCache.set(numericId, data.permission);
                    fillEditForm(data.permission);
                } else {
                    throw new Error("Invalid response format");
                }
            })
            .catch((error) => {
                console.error("Error fetching permission data:", error);
                alert("Failed to load permission data. Please try again.");
            });
    }

    // Simplified form filling dengan safety checks
    function fillEditForm(permission) {
        // Fill form fields dengan safety checks
        const fields = {
            edit_permission_id: permission.id,
            edit_name: permission.name,
            edit_group: permission.group || "",
        };

        Object.entries(fields).forEach(([fieldId, value]) => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = value;
            }
        });

        // Update form action URL dengan ID yang benar
        const editForm = document.getElementById("editPermissionForm");
        if (editForm) {
            const originalAction = editForm.action;

            if (window.permissionUpdateRoute) {
                // Gunakan route yang sudah didefinisi
                editForm.action = window.permissionUpdateRoute.replace(
                    ":id",
                    permission.id
                );
            } else {
                // Fallback method
                if (editForm.action.includes(":id")) {
                    editForm.action = editForm.action.replace(
                        ":id",
                        permission.id
                    );
                } else {
                    // Remove existing ID and add new one
                    editForm.action =
                        editForm.action.replace(/\/\d+$/, "") +
                        "/" +
                        permission.id;
                }
            }

            console.log("Form action updated:", {
                original: originalAction,
                updated: editForm.action,
                permissionId: permission.id,
            });
        }

        // Show modal dengan safety check
        const editModal = $("#editPermissionModal");
        if (editModal.length > 0) {
            editModal.modal("show");
        }
    }

    function deletePermission(permissionId, permissionName) {
        if (typeof confirmDeletePermission === "function") {
            confirmDeletePermission(permissionId, permissionName);
        } else {
            // Fallback dengan safety checks
            const deleteIdField = $("#delete_permission_id");
            const deleteNameField = $("#delete_permission_name");

            if (deleteIdField.length > 0) {
                deleteIdField.val(permissionId);
            }
            if (deleteNameField.length > 0) {
                deleteNameField.text(permissionName);
            }

            // Update form action
            const form = $("#deletePermissionForm")[0];
            if (form) {
                form.action = form.action.includes(":id")
                    ? form.action.replace(":id", permissionId)
                    : form.action.replace(/\/\d+$/, "") + "/" + permissionId;
            }
        }
    }

    // --- Bulk Delete ---
    let bulkDeleteRoute = "";

    function setupBulkDeleteHandlers() {
        if (bulkDeleteRoute) {
            DataTableGlobal.setupBulkDeleteHandler({
                modalSelector: "#deleteSelectedModal",
                selectedArray: selectedPermissions,
                deleteRoute: bulkDeleteRoute,
                confirmBtnSelector: "#confirm-delete-selected",
                entityName: "permission",
                tableInstance: permissionsTable,
                updateCallback: updateBulkDeleteButton,
            });
        }
    }

    function setBulkDeleteRoute(route) {
        bulkDeleteRoute = route;
        if (permissionsTable) {
            setupBulkDeleteHandlers();
        }
    }

    // --- Helper Functions ---
    function setupStandardModalHandlers() {
        DataTableGlobal.setupStandardModalHandlers([
            {
                modalSelector: "#createPermissionModal",
                formSelector: "#createPermissionForm",
            },
            {
                modalSelector: "#editPermissionModal",
                formSelector: "#editPermissionForm",
            },
        ]);
    }

    function setPageListItems(event) {
        DataTableGlobal.createPageLengthHandler(
            permissionsTable,
            "#page-count"
        )(event);
    }

    function refreshDataTable() {
        if (permissionsTable) {
            permissionCache.clear(); // Clear cache saat refresh
            return DataTableGlobal.refreshDataTable(permissionsTable);
        }
    }

    function updateRecordInfo() {
        if (permissionsTable) {
            DataTableGlobal.updateRecordInfo(
                permissionsTable,
                "#record-info",
                "permissions"
            );
        }
    }

    // --- Public API ---
    return {
        initialize,
        editPermission,
        deletePermission,
        setupModalHandlers: setupStandardModalHandlers,
        setPageListItems,
        setBulkDeleteRoute,
        setupEventHandlers,
        getTable: () => permissionsTable,
        getSelectedPermissions: () => selectedPermissions,
        refreshDataTable,
        updateRecordInfo,
    };
})();

// --- Legacy Support dengan DOM Ready Check ---
$(document).ready(function () {
    window.editPermission = (permissionId, editRoute = null) => {
        PermissionsDataTable.editPermission(permissionId, editRoute);
    };

    window.deletePermission = (permissionId, permissionName) => {
        if (typeof confirmDeletePermission === "function") {
            confirmDeletePermission(permissionId, permissionName);
        } else {
            PermissionsDataTable.deletePermission(permissionId, permissionName);
        }
    };

    window.setPageListItems = (event) => {
        PermissionsDataTable.setPageListItems(event);
    };
});
