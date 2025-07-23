/**
 * Permissions DataTable Configuration
 * Server-side processing enabled for better performance
 * @author KantorKu SuperApp Team
 * @version 1.0.0
 */

window.PermissionsDataTable = (function () {
    "use strict";

    let permissionsTable; // DataTable instance
    let selectedPermissions = []; // Array of selected permission IDs for bulk actions

    // --- DataTable Configuration ---
    function getTableConfig(route) {
        return {
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: {
                url: route,
                type: "GET",
                data: function (d) {
                    return d;
                },
                error: function (xhr, error, thrown) {
                    console.error("DataTable Ajax Error:", error, thrown);
                },
            },
            columns: [
                // Checkbox column for selection
                {
                    data: "checkbox",
                    name: "checkbox",
                    orderable: false,
                    searchable: false,
                    width: "30px",
                },
                // Row number instead of ID
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false,
                    width: "60px",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                // Permission name
                {
                    data: "name",
                    name: "name",
                    render: (data) => `${data}`,
                },
                // Group with badge
                {
                    data: "group_badge",
                    name: "group",
                    orderable: false,
                    searchable: true,
                },
                // Guard name
                {
                    data: "guard_name",
                    name: "guard_name",
                },
                // Created at (formatted)
                {
                    data: "created_at",
                    name: "created_at",
                    render: (data) =>
                        new Date(data).toLocaleDateString("en-US", {
                            year: "numeric",
                            month: "short",
                            day: "numeric",
                        }),
                },
                // Action buttons
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                    width: "120px",
                },
            ],
            order: [[2, "asc"]], // Order by name column (index 2)
            ...DataTableGlobal.generateStandardConfig({
                updateRecordInfo: true,
            }),
        };
    }

    // --- Update record info display ---
    function updateRecordInfo(settings) {
        const api = new $.fn.dataTable.Api(settings);
        const pageInfo = api.page.info();
        const recordInfo = document.getElementById("record-info");

        if (recordInfo) {
            const start = pageInfo.recordsDisplay > 0 ? pageInfo.start + 1 : 0;
            const end = pageInfo.end;
            const total = pageInfo.recordsTotal;
            const filtered = pageInfo.recordsDisplay;

            let infoText = `Showing <strong>${start} to ${end}</strong> of <strong>${filtered} entries</strong>`;
            if (total !== filtered) {
                infoText += ` (filtered from <strong>${total}</strong> total entries)`;
            }

            recordInfo.innerHTML = infoText;
        }
    }

    // --- Set page count and refresh table ---
    function setPageListItems(event) {
        event.preventDefault();
        const value = parseInt(event.target.getAttribute("data-value"), 10);
        if (!isNaN(value) && permissionsTable) {
            permissionsTable.page.len(value).draw();
            DataTableGlobal.updatePageCount(value);
        }
    }

    // --- Initialize permissions table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            const tableConfig = getTableConfig(route);

            // Override the updateRecordInfo for this specific table
            window.updateRecordInfo = updateRecordInfo;

            permissionsTable = $("#datatable-permissions").DataTable(
                tableConfig
            );

            // Set up search functionality
            DataTableGlobal.createSearchHandler(
                permissionsTable,
                "#advanced-table-search"
            );

            // Set up keyboard shortcuts
            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            // Set up bulk selection
            setupBulkSelection();

            // Set initial page count
            DataTableGlobal.updatePageCount(tableConfig.pageLength);

            // Set up page length handler
            window.setPageListItems = setPageListItems;

            return permissionsTable;
        });
    }

    // --- Setup modal event handlers ---
    function setupModalHandlers() {
        // Clear form when create modal is closed
        const createModal = document.getElementById("createPermissionModal");
        if (createModal) {
            createModal.addEventListener("hidden.bs.modal", function () {
                document.getElementById("createPermissionForm").reset();
            });
        }

        // Clear form when edit modal is closed
        const editModal = document.getElementById("editPermissionModal");
        if (editModal) {
            editModal.addEventListener("hidden.bs.modal", function () {
                const editForm = document.getElementById("editPermissionForm");
                editForm.reset();
                // Reset form action URL
                editForm.action = editForm.action.replace(/\/\d+$/, "/:id");
            });
        }

        // Clear form when delete modal is closed
        const deleteModal = document.getElementById("deletePermissionModal");
        if (deleteModal) {
            deleteModal.addEventListener("hidden.bs.modal", function () {
                const deleteForm = document.getElementById(
                    "deletePermissionForm"
                );
                deleteForm.reset();
                // Reset form action URL
                deleteForm.action = deleteForm.action.replace(/\/\d+$/, "/:id");
            });
        }
    }

    // --- Bulk selection functionality ---
    function setupBulkSelection() {
        // Select all checkbox handler
        DataTableGlobal.setupSelectAllHandler(
            "#select-all",
            ".table-selectable-check"
        );

        // Individual checkbox change handler
        $(document).on("change", ".table-selectable-check", function () {
            updateSelectedPermissions();
            updateBulkDeleteButton();
        });

        // Select all checkbox change handler
        $(document).on("change", "#select-all", function () {
            updateSelectedPermissions();
            updateBulkDeleteButton();
        });
    }

    // --- Update selected permissions array ---
    function updateSelectedPermissions() {
        selectedPermissions = [];
        $(".table-selectable-check:checked").each(function () {
            selectedPermissions.push(parseInt($(this).val()));
        });
    }

    // --- Update bulk delete button state ---
    function updateBulkDeleteButton() {
        const deleteBtn = $("#delete-selected-btn");
        const selectedCount = $("#selected-count");

        if (selectedPermissions.length > 0) {
            deleteBtn.prop("disabled", false);
            selectedCount.text(selectedPermissions.length);
        } else {
            deleteBtn.prop("disabled", true);
            selectedCount.text("0");
        }
    }

    // --- Edit permission function ---
    function editPermission(permissionId, editRoute) {
        // Update form action URL
        const editForm = document.getElementById("editPermissionForm");
        editForm.action = editForm.action.replace(":id", permissionId);

        // Fetch permission data via AJAX
        fetch(editRoute.replace(":id", permissionId), {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                const permission = data.permission;

                // Set values in modal
                document.getElementById("edit_permission_id").value =
                    permission.id;
                document.getElementById("edit_name").value = permission.name;
                document.getElementById("edit_group").value =
                    permission.group || "";
            })
            .catch((error) => {
                console.error("Error loading permission data:", error);
                alert("Error loading permission data");
            });
    }

    // --- Delete permission function ---
    function deletePermission(permissionId, permissionName) {
        // Update form action URL
        const deleteForm = document.getElementById("deletePermissionForm");
        deleteForm.action = deleteForm.action.replace(":id", permissionId);

        document.getElementById("delete_permission_id").value = permissionId;
        document.getElementById("delete_permission_name").textContent =
            permissionName;
    }

    // --- Refresh datatable ---
    function refreshDataTable() {
        if (permissionsTable) {
            permissionsTable.ajax.reload(null, false);
        }
    }

    // --- Bulk delete functionality ---
    let bulkDeleteRoute = "";

    function setBulkDeleteRoute(route) {
        bulkDeleteRoute = route;
    }

    function handleBulkDelete() {
        if (selectedPermissions.length === 0) {
            alert("Please select permissions to delete");
            return;
        }

        if (!bulkDeleteRoute) {
            console.error("Bulk delete route not configured");
            return;
        }

        if (
            confirm(
                `Are you sure you want to delete ${selectedPermissions.length} permission(s)?`
            )
        ) {
            $.ajax({
                url: bulkDeleteRoute,
                method: "DELETE",
                data: {
                    ids: selectedPermissions,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        refreshDataTable();
                        selectedPermissions = [];
                        updateBulkDeleteButton();
                        $("#select-all").prop("checked", false);
                    }
                },
                error: function (xhr) {
                    console.error("Bulk delete error:", xhr.responseText);
                    alert("Error deleting permissions. Please try again.");
                },
            });
        }
    }

    // --- Public API ---
    return {
        initialize,
        setupModalHandlers,
        setupBulkSelection,
        editPermission,
        deletePermission,
        refreshDataTable,
        setBulkDeleteRoute,
        handleBulkDelete,

        // Expose table instance for external access
        getTable: () => permissionsTable,
    };
})();

// --- Backward compatibility functions ---
window.editPermission = function (permissionId) {
    // This will be set up by the main page
    if (window.permissionEditRoute) {
        PermissionsDataTable.editPermission(
            permissionId,
            window.permissionEditRoute
        );
    } else {
        console.error("Permission edit route not configured");
    }
};

window.deletePermission = function (permissionId, permissionName) {
    PermissionsDataTable.deletePermission(permissionId, permissionName);
};
