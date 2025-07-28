/**
 * Roles DataTable Configuration
 * Server-side processing enabled for better performance
 * @author KantorKu SuperApp Team
 * @version 3.0.0
 */

window.RolesDataTable = (function () {
    "use strict";

    let rolesTable;
    let selectedRoles = [];

    // --- Table Configuration ---
    function getTableConfig(route) {
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: {
                    url: route,
                    type: "GET"
                },
                order: [[1, "desc"]], // ID descending
                deferRender: true
            }
        });

        const rolesConfig = {
            columns: [
                { // Checkbox
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        if (row.name === 'admin') {
                            return ''; // Don't show checkbox for admin role
                        }
                        return `<input class="form-check-input m-0 align-middle table-selectable-check" 
                               type="checkbox" value="${row.id}"/>`;
                    }
                },
                { // ID
                    data: "id",
                    name: "id"
                },
                { // Name
                    data: "name",
                    name: "name",
                    render: (data) => `<strong>${data}</strong>`
                },
                { // Guard Name
                    data: "guard_name",
                    name: "guard_name"
                },
                { // Permissions Count
                    data: "permissions_count",
                    name: "permissions_count",
                    orderable: false,
                    searchable: false,
                    render: (data) => `<span class="badge bg-info">${data} permissions</span>`
                },
                { // Created Date
                    data: "created_at",
                    name: "created_at",
                    render: (data) => new Date(data).toLocaleDateString("en-GB", {
                        day: "2-digit",
                        month: "short",
                        year: "numeric"
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
                DataTableGlobal.buildCustomPagination(rolesTable, "#datatable-pagination");
                DataTableGlobal.updateRecordInfo(rolesTable, "#record-info", "roles");
                DataTableGlobal.updateSelectAllState();
                updateBulkDeleteButton();
            }
        };

        return $.extend(true, {}, baseConfig, rolesConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            rolesTable = $("#rolesTable").DataTable(getTableConfig(route));
            
            // Setup global handlers
            DataTableGlobal.createSearchHandler(rolesTable, "#advanced-table-search");
            DataTableGlobal.setupPageLengthHandler(rolesTable, ".dropdown-item[data-value]", "#page-count");
            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");
            
            // Setup specific handlers
            setupEventHandlers();
            
            return rolesTable;
        });
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        DataTableGlobal.setupCheckboxHandlers(selectedRoles, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton
        });

        setupModalHandlers();
    }

    function updateBulkDeleteButton() {
        $("#delete-selected-btn").prop("disabled", selectedRoles.length === 0);
        $("#selected-count").text(selectedRoles.length);
    }

    // --- Modal Handlers ---
    function setupModalHandlers() {
        // Setup create modal handler
        $('#createRoleModal').on('show.bs.modal', function() {
            // Reset form when modal is shown
            $('#createRoleForm')[0]?.reset();
        });

        // Setup edit modal handler
        $('#editRoleModal').on('show.bs.modal', function() {
            // Modal will be populated by editRole function
        });

        // Refresh table after modal operations
        $('.modal').on('hidden.bs.modal', function() {
            if ($(this).find('.alert-success').length > 0 || window.tableNeedsRefresh) {
                refreshDataTable();
                window.tableNeedsRefresh = false;
            }
        });
    }

    // --- Bulk Delete ---
    function setupBulkDeleteHandlers() {
        DataTableGlobal.setupBulkDeleteHandler({
            modalSelector: "#deleteSelectedModal",
            selectedArray: selectedRoles,
            deleteRoute: window.roleBulkDeleteRoute,
            confirmBtnSelector: "#confirm-delete-selected",
            entityName: "roles",
            tableInstance: rolesTable,
            updateCallback: updateBulkDeleteButton,
            customRequestData: function() {
                return {
                    role_ids: selectedRoles,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    _method: 'DELETE'
                };
            }
        });
    }

    // --- Role Management Functions ---
    function editRole(roleId, editRoute = null) {
        const url = editRoute ? editRoute.replace(':id', roleId) : window.roleEditRoute.replace(':id', roleId);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.role && data.permissions) {
                populateEditModal(data.role, data.permissions);
            }
        })
        .catch(error => {
            console.error('Error fetching role data:', error);
            alert('Error loading role data');
        });
    }

    function populateEditModal(role, permissions) {
        // Update form action URL with the actual role ID
        const editForm = document.getElementById('editRoleForm');
        if (editForm && window.roleUpdateRoute) {
            editForm.action = window.roleUpdateRoute.replace(':id', role.id);
        }
        
        // Populate basic fields
        document.getElementById('edit_role_id').value = role.id;
        document.getElementById('edit_name').value = role.name;
        
        // Clear and populate permissions checkboxes
        const permissionCheckboxes = document.querySelectorAll('#editRoleModal input[name="permissions[]"]');
        const rolePermissions = role.permissions.map(p => p.name);
        
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = rolePermissions.includes(checkbox.value);
        });
    }

    function deleteRole(roleId, roleName) {
        if (typeof confirmDeleteRole === 'function') {
            confirmDeleteRole(roleId, roleName);
        } else {
            // Fallback confirmation
            if (confirm(`Are you sure you want to delete role "${roleName}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.roleDeleteRoute.replace(':id', roleId);
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);
                
                // Add method override
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    }

    // --- Helper Functions ---
    function refreshDataTable() {
        if (rolesTable) {
            rolesTable.ajax.reload(null, false);
            selectedRoles.length = 0;
            DataTableGlobal.updateSelectAllState();
            updateBulkDeleteButton();
        }
    }

    function setBulkDeleteRoute(route) {
        window.roleBulkDeleteRoute = route;
        setupBulkDeleteHandlers();
    }

    // --- Public API ---
    return {
        initialize,
        setupModalHandlers,
        setBulkDeleteRoute,
        refreshDataTable,
        editRole,
        deleteRole,
        
        // Expose for backward compatibility
        get table() { return rolesTable; },
        get selectedItems() { return selectedRoles; }
    };
})();

// Global functions for backward compatibility
window.editRole = function(roleId) {
    RolesDataTable.editRole(roleId);
};

window.deleteRole = function(roleId, roleName) {
    RolesDataTable.deleteRole(roleId, roleName);
};

window.confirmBulkDeleteRoles = function() {
    const checkedBoxes = $('.table-selectable-check:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select roles to delete');
        return;
    }
    
    // Trigger the bulk delete modal
    $('#deleteSelectedModal').modal('show');
};
