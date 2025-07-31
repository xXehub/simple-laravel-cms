/**
 * Permissions DataTable Configuration
 * Server-side processing enabled for better performance
 * @author KantorKu SuperApp Team
 * @version 2.2.0
 */

window.PermissionsDataTable = (function () {
    "use strict";

    let permissionsTable;
    let selectedPermissions = [];

    // --- Table Configuration ---
    function getTableConfig(route) {
        // Base config from global
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: { url: route, type: "GET" },
                order: [[2, "asc"]],
                deferRender: true
            }
        });

        // Permissions-specific columns
        const permissionsConfig = {
            columns: [
                { // Checkbox
                    data: "checkbox", name: "checkbox", orderable: false, searchable: false,
                    width: "30px"
                },
                { // Row number
                    data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false,
                    width: "60px",
                    render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                },
                { data: "name", name: "name" },
                { data: "group_badge", name: "group", orderable: false, searchable: true },
                { data: "guard_name", name: "guard_name" },
                { // Created date
                    data: "created_at", name: "created_at",
                    render: (data) => new Date(data).toLocaleDateString("en-US", {
                        year: "numeric", month: "short", day: "numeric"
                    })
                },
                { // Actions
                    data: "action", name: "action", orderable: false, searchable: false,
                    className: "text-end", width: "120px"
                }
            ],
            drawCallback: function() {
                DataTableGlobal.buildCustomPagination(permissionsTable, "#datatable-pagination");
                DataTableGlobal.updateRecordInfo(permissionsTable, "#record-info", "permissions");
                DataTableGlobal.updateSelectAllState();
                updateBulkDeleteButton();
            }
        };

        return $.extend(true, {}, baseConfig, permissionsConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            // Create table
            permissionsTable = $("#datatable-permissions").DataTable(getTableConfig(route));
            
            // Setup global handlers
            DataTableGlobal.createSearchHandler(permissionsTable, "#advanced-table-search");
            DataTableGlobal.setupPageLengthHandler(permissionsTable, ".dropdown-item[data-value]", "#page-count");
            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");
            
            // Setup specific handlers
            setupEventHandlers();
            
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

    // --- Event Handlers ---
    function setupEventHandlers() {
        // Checkbox selection with bulk delete button updates
        DataTableGlobal.setupCheckboxHandlers(selectedPermissions, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton
        });
    }

    function updateBulkDeleteButton() {
        $("#delete-selected-btn").prop("disabled", selectedPermissions.length === 0);
        $("#selected-count").text(selectedPermissions.length);
    }

    // --- Bulk Delete ---
    function setupBulkDeleteHandlers() {
        DataTableGlobal.setupBulkDeleteHandler({
            modalSelector: "#deleteSelectedModal",
            selectedArray: selectedPermissions,
            deleteRoute: bulkDeleteRoute,
            confirmBtnSelector: "#confirm-delete-selected",
            entityName: "permission",
            tableInstance: permissionsTable,
            updateCallback: updateBulkDeleteButton
        });
    }

    // --- Permission Management ---
    function editPermission(permissionId, editRoute = null) {
        // Use provided editRoute or fall back to global route
        const route = editRoute || window.permissionEditRoute;
        
        if (!route) {
            console.error('Edit route not provided and global route not found');
            alert('Edit route not configured properly');
            return;
        }
        
        const url = route.replace(':id', permissionId);
        
        fetch(url, {
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.permission) {
                // Fill form with permission data
                document.getElementById('edit_permission_id').value = data.permission.id;
                document.getElementById('edit_name').value = data.permission.name;
                document.getElementById('edit_group').value = data.permission.group || '';
                
                // Show modal
                $('#editPermissionModal').modal('show');
            } else {
                throw new Error('Invalid response format');
            }
        })
        .catch(error => {
            console.error('Error fetching permission data:', error);
            alert('Failed to load permission data. Please try again.');
        });
    }

    function deletePermission(permissionId, permissionName) {
        // Use the new confirmation modal if available
        if (typeof confirmDeletePermission === 'function') {
            confirmDeletePermission(permissionId, permissionName);
        } else {
            // Fallback to old method if confirmation modal is not available
            $('#delete_permission_id').val(permissionId);
            $('#delete_permission_name').text(permissionName);
            
            // Update form action
            const form = $('#deletePermissionForm')[0];
            if (form) {
                form.action = form.action.includes(':id') 
                    ? form.action.replace(':id', permissionId)
                    : form.action.replace(/\/\d+$/, '') + '/' + permissionId;
            }
        }
    }

    // --- Helper Functions ---
    function setupModalHandlers() {
        DataTableGlobal.setupStandardModalHandlers([
            { modalSelector: "#createPermissionModal", formSelector: "#createPermissionForm" },
            { modalSelector: "#editPermissionModal", formSelector: "#editPermissionForm" }
        ]);
    }

    function setPageListItems(event) {
        DataTableGlobal.createPageLengthHandler(permissionsTable, "#page-count")(event);
    }

    let bulkDeleteRoute = "";
    function setBulkDeleteRoute(route) { 
        bulkDeleteRoute = route; 
        // Setup handlers after route is set
        if (permissionsTable) {
            setupBulkDeleteHandlers();
        }
    }

    // --- Public API ---
    return {
        initialize,
        editPermission,
        deletePermission,
        setupModalHandlers,
        setPageListItems,
        setBulkDeleteRoute,
        setupEventHandlers,
        getTable: () => permissionsTable,
        getSelectedPermissions: () => selectedPermissions,
        refreshDataTable: () => DataTableGlobal.refreshDataTable(permissionsTable),
        updateRecordInfo: () => DataTableGlobal.updateRecordInfo(permissionsTable, "#record-info", "permissions")
    };
})();

// --- Legacy Support ---
window.editPermission = (permissionId, editRoute = null) => PermissionsDataTable.editPermission(permissionId, editRoute);
window.deletePermission = (permissionId, permissionName) => typeof confirmDeletePermission === 'function' ? confirmDeletePermission(permissionId, permissionName) : PermissionsDataTable.deletePermission(permissionId, permissionName);
window.setPageListItems = (event) => PermissionsDataTable.setPageListItems(event);
