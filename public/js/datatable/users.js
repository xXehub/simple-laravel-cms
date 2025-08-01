/**
 * Users DataTable Configuration - Simplified Optimized Version
 * @author KantorKu SuperApp Team
 * @version 2.3.0 - Faster edit modal with simplified code
 */

window.UsersDataTable = (function () {
    "use strict";

    let usersTable;
    let selectedUsers = [];
    let userCache = new Map(); // Simple cache for faster edit loading

    // --- Table Configuration ---
    function getTableConfig(route) {
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: { url: route, type: "GET" },
                order: [[1, "asc"]],
                deferRender: true
            }
        });

        const usersConfig = {
            columns: [
                { // Checkbox
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        // Cache data saat render untuk edit cepat
                        userCache.set(row.id, row);
                        return `<input class="form-check-input m-0 align-middle table-selectable-check" 
                               type="checkbox" value="${row.id}"/>`;
                    }
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
                { data: "username", name: "username" },
                { data: "email", name: "email" },
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
                        year: "numeric", month: "long", day: "numeric"
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
                
                // Manage cache size
                if (userCache.size > 500) userCache.clear();
            }
        };

        return $.extend(true, {}, baseConfig, usersConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            usersTable = $("#datatable-users").DataTable(getTableConfig(route));

            // Setup handlers dari DataTableGlobal
            DataTableGlobal.createSearchHandler(usersTable, "#advanced-table-search");
            DataTableGlobal.setupPageLengthHandler(usersTable, ".dropdown-item[data-value]", "#page-count");
            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            setupEventHandlers();
            return usersTable;
        });
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        DataTableGlobal.setupCheckboxHandlers(selectedUsers, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton
        });
    }

    function updateBulkDeleteButton() {
        $("#delete-selected-btn").prop("disabled", selectedUsers.length === 0);
        $("#selected-count").text(selectedUsers.length);
    }

    // --- User Management - OPTIMIZED ---
    function editUser(userId, editRoute = null) {
        const numericId = parseInt(userId);
        
        // Cek cache dulu - instant loading jika ada
        const cachedUser = userCache.get(numericId);
        if (cachedUser) {
            fillEditForm(cachedUser);
            return;
        }

        // Jika tidak ada di cache, fetch dari server
        const route = editRoute || window.userEditRoute;
        if (!route) {
            console.error('Edit route not configured');
            alert('Edit route not configured properly');
            return;
        }

        fetch(route.replace(':id', userId), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const user = data.user;
            userCache.set(numericId, user); // Cache untuk next time
            fillEditForm(user);
        })
        .catch(error => {
            console.error('Error loading user:', error);
            alert('Error loading user data');
        });
    }

    // Simplified form filling
    function fillEditForm(user) {
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
        if (user.roles) {
            user.roles.forEach(role => {
                $(`#edit_roles_container input[value="${role.name || role}"]`).prop('checked', true);
            });
        }
    }

    function deleteUser(userId, userName) {
        if (typeof confirmDeleteUser === 'function') {
            confirmDeleteUser(userId, userName);
        } else {
            $('#delete_user_id').val(userId);
            $('#delete_user_name').text(userName);

            const form = $('#deleteUserForm')[0];
            if (form) {
                form.action = form.action.includes(':id') ?
                    form.action.replace(':id', userId) :
                    form.action.replace(/\/\d+$/, '') + '/' + userId;
            }
        }
    }

    // --- Bulk Delete ---
    let bulkDeleteRoute = "";

    function setupBulkDeleteHandlers() {
        DataTableGlobal.setupBulkDeleteHandler({
            modalSelector: "#deleteSelectedModal",
            selectedArray: selectedUsers,
            deleteRoute: bulkDeleteRoute,
            confirmBtnSelector: "#confirm-delete-selected",
            entityName: "user",
            tableInstance: usersTable,
            updateCallback: updateBulkDeleteButton
        });
    }

    function setBulkDeleteRoute(route) {
        bulkDeleteRoute = route;
        if (usersTable) setupBulkDeleteHandlers();
    }

    // --- Helper Functions ---
    function setupModalHandlers() {
        DataTableGlobal.setupStandardModalHandlers([
            { modalSelector: "#createUserModal", formSelector: "#createUserForm" },
            { modalSelector: "#editUserModal", formSelector: "#editUserForm" }
        ]);
    }

    function setPageListItems(event) {
        DataTableGlobal.createPageLengthHandler(usersTable, "#page-count")(event);
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
        refreshDataTable: () => {
            userCache.clear(); // Clear cache saat refresh
            return DataTableGlobal.refreshDataTable(usersTable);
        },
        updateRecordInfo: () => DataTableGlobal.updateRecordInfo(usersTable, "#record-info", "users")
    };
})();

// --- Legacy Support ---
window.editUser = (userId, editRoute) => UsersDataTable.editUser(userId, editRoute);
window.deleteUser = (userId, userName) => UsersDataTable.deleteUser(userId, userName);
window.setPageListItems = (event) => UsersDataTable.setPageListItems(event);