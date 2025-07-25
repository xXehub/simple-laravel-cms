/**
 * Users DataTable Configuration
 * Server-side processing enabled for better performance
 * @author KantorKu SuperApp Team
 * @version 2.2.0
 */

window.UsersDataTable = (function () {
    "use strict";

    let usersTable; // DataTable instance
    let selectedUsers = []; // Array of selected user IDs for bulk actions

    // --- Table Configuration ---
    function getTableConfig(route) {
        // Base config from global with AJAX route
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: {
                    url: route,
                    type: "GET"
                },
                order: [
                    [1, "asc"]
                ],
                deferRender: true
            }
        });

        // Users-specific columns and settings
        const usersConfig = {
            columns: [{ // Checkbox
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) =>
                        `<input class="form-check-input m-0 align-middle table-selectable-check" 
                               type="checkbox" value="${row.id}"/>`
                },
                { // Name with avatar
                    data: "name",
                    name: "name",
                    render: (data, type, row) => {
                        const avatar = row.avatar_url ||
                            `https://ui-avatars.com/api/?name=${encodeURIComponent(data.charAt(0))}&color=ffffff&background=0ea5e9&size=32&rounded=false&bold=true`;
                        return `<span class="avatar avatar-xs me-2" style="background-image: url('${avatar}');"></span>${data}`;
                    }
                },
                {
                    data: "username",
                    name: "username"
                },
                {
                    data: "email",
                    name: "email"
                },
                { // Roles
                    data: "roles",
                    name: "roles",
                    orderable: false,
                    searchable: false,
                    render: (data) => data?.length > 0 ?
                        data.map(role => `<span class="badge bg-primary-lt me-1">${role}</span>`).join("") :
                        '<span class="badge bg-secondary-lt">No Role</span>'
                },
                { // Created date
                    data: "created_at",
                    name: "created_at",
                    render: (data) => new Date(data).toLocaleDateString("en-US", {
                        year: "numeric",
                        month: "long",
                        day: "numeric"
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
                DataTableGlobal.buildCustomPagination(usersTable, "#datatable-pagination");
                DataTableGlobal.updateRecordInfo(usersTable, "#record-info", "users");
                DataTableGlobal.updateSelectAllState();
                updateBulkDeleteButton();
            }
        };

        return $.extend(true, {}, baseConfig, usersConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            // Create table
            usersTable = $("#datatable-users").DataTable(getTableConfig(route));

            // Setup global handlers
            DataTableGlobal.createSearchHandler(usersTable, "#advanced-table-search");
            DataTableGlobal.setupPageLengthHandler(usersTable, ".dropdown-item[data-value]", "#page-count");
            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            // Setup specific handlers
            setupEventHandlers();

            return usersTable;
        });
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        // Checkbox selection with bulk delete button updates
        DataTableGlobal.setupCheckboxHandlers(selectedUsers, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton
        });

        setupBulkDeleteHandlers();
    }

    function updateBulkDeleteButton() {
        $("#delete-selected-btn").prop("disabled", selectedUsers.length === 0);
        $("#selected-count").text(selectedUsers.length);
    }

    // --- Bulk Delete ---
    function setupBulkDeleteHandlers() {
        // Show selected users in modal
        $("#deleteSelectedModal").on("show.bs.modal", function () {
            const userList = selectedUsers.map(id => `<li>User ID: <strong>${id}</strong></li>`).join("");
            $("#selected-users-list").html(`<ul>${userList}</ul>`);
            $("#delete-selected-count").text(selectedUsers.length);
        });

        // Confirm bulk delete
        $("#confirm-delete-selected").on("click", function () {
            if (selectedUsers.length === 0) return alert('No users selected');

            const btn = this;
            const originalText = btn.innerHTML;

            // Loading state
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Deleting...';

            // Delete request
            fetch(bulkDeleteRoute, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        user_ids: selectedUsers
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Close modal
                    $('#deleteSelectedModal').modal('hide');

                    // Show message
                    alert(data.status === 'success' ? `Success: ${data.message}` : `Error: ${data.message}`);

                    // Refresh and reset
                    DataTableGlobal.refreshDataTable(usersTable, true);
                    selectedUsers.length = 0;
                    updateBulkDeleteButton();
                })
                .catch(error => {
                    console.error('Bulk delete error:', error);
                    alert('Failed to delete selected users. Please try again.');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });
    }

    // --- User Management ---
    function editUser(userId, editRoute) {
        const url = editRoute.replace(':id', userId);

        fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const user = data.user;

                // Fill form fields
                $('#edit_user_id').val(user.id);
                $('#edit_name').val(user.name);
                $('#edit_username').val(user.username);
                $('#edit_email').val(user.email);
                $('#edit_password, #edit_password_confirmation').val('');

                // Update form action
                const form = $('#editUserForm')[0];
                form.action = form.action.includes(':id') ?
                    form.action.replace(':id', user.id) :
                    form.action.replace(/\/\d+$/, '') + '/' + user.id;

                // Handle roles
                $('#edit_roles_container input[name="roles[]"]').prop('checked', false);
                user.roles.forEach(role => {
                    $(`#edit_roles_container input[value="${role.name}"]`).prop('checked', true);
                });
            })
            .catch(error => {
                console.error('Error loading user:', error);
                alert('Error loading user data');
            });
    }

    function deleteUser(userId, userName) {
        $('#delete_user_id').val(userId);
        $('#delete_user_name').text(userName);

        // Update form action
        const form = $('#deleteUserForm')[0];
        form.action = form.action.includes(':id') ?
            form.action.replace(':id', userId) :
            form.action.replace(/\/\d+$/, '') + '/' + userId;
    }

    // --- Helper Functions ---
    function setupModalHandlers() {
        DataTableGlobal.setupStandardModalHandlers([{
                modalSelector: "#createUserModal",
                formSelector: "#createUserForm"
            },
            {
                modalSelector: "#editUserModal",
                formSelector: "#editUserForm"
            }
        ]);
    }

    function setPageListItems(event) {
        DataTableGlobal.createPageLengthHandler(usersTable, "#page-count")(event);
    }

    let bulkDeleteRoute = "";

    function setBulkDeleteRoute(route) {
        bulkDeleteRoute = route;
    }

    // --- Public API ---
    return {
        initialize,
        editUser,
        deleteUser,
        setupModalHandlers,
        setPageListItems,
        setBulkDeleteRoute,
        setupEventHandlers,
        getTable: () => usersTable,
        getSelectedUsers: () => selectedUsers,
        refreshDataTable: () => DataTableGlobal.refreshDataTable(usersTable),
        updateRecordInfo: () => DataTableGlobal.updateRecordInfo(usersTable, "#record-info", "users")
    };
})();

// --- Legacy Support ---
window.editUser = (userId, editRoute) => UsersDataTable.editUser(userId, editRoute);
window.deleteUser = (userId, userName) => UsersDataTable.deleteUser(userId, userName);
window.setPageListItems = (event) => UsersDataTable.setPageListItems(event);
