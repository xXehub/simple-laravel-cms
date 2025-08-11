/**
 * Roles DataTable Configuration - Optimized Version
 * @author KantorKu SuperApp Team
 * @version 3.1.0 - Faster edit modal with cache optimization
 */

window.RolesDataTable = (function () {
    "use strict";

    let rolesTable;
    let selectedRoles = [];
    let roleCache = new Map(); // Cache for faster edit loading

    // --- Table Configuration ---
    function getTableConfig(route) {
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: { url: route, type: "GET" },
                order: [[2, "asc"]], // Nama Role ascending
                deferRender: true,
            },
        });

        const rolesConfig = {
            columns: [
                {
                    // Checkbox
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        // Cache data saat render untuk edit cepat
                        roleCache.set(row.id, row);

                        if (row.name === "admin") {
                            return ""; // Don't show checkbox for admin role
                        }
                        return `<input class="form-check-input m-0 align-middle table-selectable-check" 
                               type="checkbox" value="${row.id}"/>`;
                    },
                },
                { 
                    data: null,
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "name",
                    name: "name",
                    render: (data) => `${data}`,
                },
                { data: "guard_name", name: "guard_name" },
                {
                    // Permissions Count
                    data: "permissions_count",
                    name: "permissions_count",
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        // Get total permissions count (you can adjust this number based on your actual total)
                        const totalPermissions = window.totalPermissionsCount || 36; // Default to 36 if not set
                        const percentage = (data / totalPermissions) * 100;
                        
                        // Determine badge color based on percentage
                        let badgeClass;
                        if (percentage >= 80) {
                            badgeClass = 'badge bg-red text-red-fg'; // High permissions (80%+)
                        } else if (percentage >= 60) {
                            badgeClass = 'badge bg-orange text-orange-fg'; // Medium-high (60-79%)
                        } else if (percentage >= 40) {
                            badgeClass = 'badge bg-yellow text-yellow-fg'; // Medium (40-59%)
                        } else if (percentage >= 20) {
                            badgeClass = 'badge bg-blue text-blue-fg'; // Medium-low (20-39%)
                        } else if (percentage > 0) {
                            badgeClass = 'badge bg-green text-green-fg'; // Low permissions (1-19%)
                        } else {
                            badgeClass = 'badge bg-secondary text-secondary-fg'; // No permissions (0%)
                        }
                        
                        const percentageText = percentage > 0 ? ` (${Math.round(percentage)}%)` : '';
                        return `<span class="${badgeClass}">${data} permissions${percentageText}</span>`;
                    },
                },
                {
                    // Created Date
                    data: "created_at",
                    name: "created_at",
                    render: (data) =>
                        new Date(data).toLocaleDateString("en-GB", {
                            day: "2-digit",
                            month: "short",
                            year: "numeric",
                        }),
                },
                {
                    // Actions
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                },
            ],
            drawCallback: function () {
                DataTableGlobal.buildCustomPagination(
                    rolesTable,
                    "#datatable-pagination"
                );
                DataTableGlobal.updateRecordInfo(
                    rolesTable,
                    "#record-info",
                    "roles"
                );
                DataTableGlobal.updateSelectAllState();
                updateBulkDeleteButton();

                // Manage cache size
                if (roleCache.size > 500) roleCache.clear();
            },
        };

        return $.extend(true, {}, baseConfig, rolesConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            // Check if table element exists
            const tableElement = $("#rolesTable");
            if (tableElement.length === 0) {
                console.error("Table element #rolesTable not found");
                return null;
            }

            rolesTable = tableElement.DataTable(getTableConfig(route));

            // Setup handlers dengan safety checks
            if ($("#advanced-table-search").length > 0) {
                DataTableGlobal.createSearchHandler(
                    rolesTable,
                    "#advanced-table-search"
                );
            }

            if (
                $(".dropdown-item[data-value]").length > 0 &&
                $("#page-count").length > 0
            ) {
                DataTableGlobal.setupPageLengthHandler(
                    rolesTable,
                    ".dropdown-item[data-value]",
                    "#page-count"
                );
            }

            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            setupEventHandlers();
            return rolesTable;
        });
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        DataTableGlobal.setupCheckboxHandlers(selectedRoles, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton,
        });

        setupModalHandlers();
    }

    function updateBulkDeleteButton() {
        const deleteBtn = $("#delete-selected-btn");
        const selectedCount = $("#selected-count");

        if (deleteBtn.length > 0) {
            deleteBtn.prop("disabled", selectedRoles.length === 0);
        }
        if (selectedCount.length > 0) {
            selectedCount.text(selectedRoles.length);
        }
    }

    // --- Modal Handlers ---
    function setupModalHandlers() {
        // Setup create modal handler dengan safety check
        const createModal = $("#createRoleModal");
        if (createModal.length > 0) {
            createModal.on("show.bs.modal", function () {
                const form = $("#createRoleForm")[0];
                if (form) form.reset();
            });
        }

        // Setup edit modal handler
        const editModal = $("#editRoleModal");
        if (editModal.length > 0) {
            editModal.on("show.bs.modal", function () {
                // Modal will be populated by editRole function
            });
        }

        // Refresh table after modal operations
        $(".modal").on("hidden.bs.modal", function () {
            if (
                $(this).find(".alert-success").length > 0 ||
                window.tableNeedsRefresh
            ) {
                refreshDataTable();
                window.tableNeedsRefresh = false;
            }
        });
    }

    // --- Role Management Functions - OPTIMIZED ---
    function editRole(roleId, editRoute = null) {
        const numericId = parseInt(roleId);

        // Cek cache dulu - instant loading jika ada
        const cachedRole = roleCache.get(numericId);
        if (cachedRole && cachedRole.permissions) {
            fillEditForm(cachedRole, cachedRole.permissions);
            return;
        }

        // Jika tidak ada di cache, fetch dari server
        const route = editRoute || window.roleEditRoute;
        if (!route) {
            console.error("Edit route not configured");
            alert("Edit route not configured properly");
            return;
        }

        const url = route.replace(":id", roleId);

        fetch(url, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.role && data.permissions) {
                    // Cache role dengan permissions untuk next time
                    const roleWithPermissions = {
                        ...data.role,
                        permissions: data.role.permissions,
                    };
                    roleCache.set(numericId, roleWithPermissions);

                    fillEditForm(data.role, data.permissions);
                }
            })
            .catch((error) => {
                console.error("Error fetching role data:", error);
                alert("Error loading role data");
            });
    }

    // Simplified form filling dengan safety checks
    function fillEditForm(role, permissions) {
        // Update form action dengan safety check
        const editForm = document.getElementById("editRoleForm");
        if (editForm && window.roleUpdateRoute) {
            editForm.action = window.roleUpdateRoute.replace(":id", role.id);
        }

        // Populate basic fields dengan safety checks
        const roleIdField = document.getElementById("edit_role_id");
        const nameField = document.getElementById("edit_name");

        if (roleIdField) roleIdField.value = role.id;
        if (nameField) nameField.value = role.name;

        // Handle permissions dengan safety check
        const editModal = document.getElementById("editRoleModal");
        if (editModal) {
            const permissionCheckboxes = editModal.querySelectorAll(
                'input[name="permissions[]"]'
            );
            const rolePermissions = role.permissions
                ? role.permissions.map((p) => p.name)
                : [];

            permissionCheckboxes.forEach((checkbox) => {
                checkbox.checked = rolePermissions.includes(checkbox.value);
            });
        }
    }

    // --- ini untuk delete role ---
    function deleteRole(roleId, roleName) {
        if (typeof confirmDeleteRole === "function") {
            confirmDeleteRole(roleId, roleName);
        }
    }

    // --- ini untuk bulk delete role @datatable-global.js ---
    function setupBulkDeleteHandlers() {
        if (window.roleBulkDeleteRoute) {
            DataTableGlobal.setupBulkDeleteHandler({
                modalSelector: "#deleteSelectedModal",
                selectedArray: selectedRoles,
                deleteRoute: window.roleBulkDeleteRoute,
                confirmBtnSelector: "#confirm-delete-selected",
                entityName: "roles",
                tableInstance: rolesTable,
                updateCallback: updateBulkDeleteButton,
                customRequestData: function () {
                    const csrfToken = document.querySelector(
                        'meta[name="csrf-token"]'
                    );
                    return {
                        role_ids: selectedRoles,
                        _token: csrfToken
                            ? csrfToken.getAttribute("content")
                            : "",
                        _method: "DELETE",
                    };
                },
            });
        }
    }

    function setBulkDeleteRoute(route) {
        window.roleBulkDeleteRoute = route;
        if (rolesTable) setupBulkDeleteHandlers();
    }

    // --- Helper Functions ---
    function refreshDataTable() {
        if (rolesTable) {
            roleCache.clear(); // Clear cache saat refresh
            rolesTable.ajax.reload(null, false);
            selectedRoles.length = 0;
            DataTableGlobal.updateSelectAllState();
            updateBulkDeleteButton();
        }
    }

    function confirmBulkDeleteRoles() {
        const checkedBoxes = $(".table-selectable-check:checked");
        if (checkedBoxes.length === 0) {
            alert("Please select roles to delete");
            return;
        }

        // Trigger the bulk delete modal dengan safety check
        const deleteModal = $("#deleteSelectedModal");
        if (deleteModal.length > 0) {
            deleteModal.modal("show");
        }
    }

    // --- Public API ---
    return {
        initialize,
        setupModalHandlers,
        setBulkDeleteRoute,
        refreshDataTable,
        editRole,
        deleteRole,
        confirmBulkDeleteRoles,

        // Expose for backward compatibility
        get table() {
            return rolesTable;
        },
        get selectedItems() {
            return selectedRoles;
        },
    };
})();

// --- Global functions for backward compatibility dengan DOM Ready Check ---
$(document).ready(function () {
    window.editRole = function (roleId) {
        RolesDataTable.editRole(roleId);
    };

    window.deleteRole = function (roleId, roleName) {
        RolesDataTable.deleteRole(roleId, roleName);
    };

    window.confirmBulkDeleteRoles = function () {
        RolesDataTable.confirmBulkDeleteRoles();
    };
});
