/**
 * Users DataTable Configuration - Optimized Version
 * Server-side processing enabled for better performance
 * @author KantorKu SuperApp Team
 * @version 2.3.0 - Optimized for faster edit modal loading
 */

window.UsersDataTable = (function () {
    "use strict";

    let usersTable; // DataTable instance
    let selectedUsers = []; // Array of selected user IDs for bulk actions
    let userDataCache = new Map(); // Cache for user data to speed up edit modal
    let editModalPreloader = null; // Store preloader element

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
                    render: (data, type, row) => {
                        // Cache row data when rendering for faster edit access
                        userDataCache.set(row.id, row);
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
            setupEditModalOptimizations();

            return usersTable;
        });
    }

    // --- Edit Modal Optimizations ---
    function setupEditModalOptimizations() {
        // Create preloader element if it doesn't exist
        if (!editModalPreloader) {
            editModalPreloader = $(`
                <div id="edit-modal-preloader" class="d-flex justify-content-center align-items-center" style="min-height: 200px; display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
        }

        // Pre-populate form fields when modal is about to show
        $('#editUserModal').on('show.bs.modal', function (e) {
            const userId = $(e.relatedTarget).data('user-id');
            if (userId) {
                preloadEditForm(userId);
            }
        });

        // Clear cache when table data is refreshed
        if (usersTable) {
            usersTable.on('draw', function () {
                // Keep cache size manageable
                if (userDataCache.size > 1000) {
                    userDataCache.clear();
                }
            });
        }
    }

    function preloadEditForm(userId) {
        // Check if data is already cached
        const cachedUser = userDataCache.get(parseInt(userId));
        if (cachedUser) {
            populateEditForm(cachedUser);
            return;
        }

        // If not cached, show preloader and fetch from server
        showEditPreloader();
        editUser(userId);
    }

    function showEditPreloader() {
        const modalBody = $('#editUserModal .modal-body');
        const originalContent = modalBody.html();
        modalBody.data('original-content', originalContent);
        modalBody.html(editModalPreloader);
        editModalPreloader.show();
    }

    function hideEditPreloader() {
        const modalBody = $('#editUserModal .modal-body');
        const originalContent = modalBody.data('original-content');
        if (originalContent) {
            modalBody.html(originalContent);
        }
    }

    function populateEditForm(user) {
        hideEditPreloader();
        
        // Use requestAnimationFrame for smoother UI updates
        requestAnimationFrame(() => {
            // Fill form fields
            $('#edit_user_id').val(user.id);
            $('#edit_name').val(user.name);
            $('#edit_username').val(user.username);
            $('#edit_email').val(user.email);
            $('#edit_password, #edit_password_confirmation').val('');

            // Update form action efficiently
            const form = $('#editUserForm')[0];
            if (form) {
                form.action = form.action.includes(':id') ?
                    form.action.replace(':id', user.id) :
                    form.action.replace(/\/\d+$/, '') + '/' + user.id;
            }

            // Handle roles efficiently
            const roleInputs = $('#edit_roles_container input[name="roles[]"]');
            roleInputs.prop('checked', false);
            
            if (user.roles && user.roles.length > 0) {
                const roleNames = user.roles.map(role => role.name || role);
                roleInputs.each(function() {
                    if (roleNames.includes(this.value)) {
                        this.checked = true;
                    }
                });
            }
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
    }

    function updateBulkDeleteButton() {
        $("#delete-selected-btn").prop("disabled", selectedUsers.length === 0);
        $("#selected-count").text(selectedUsers.length);
    }

    // --- Bulk Delete ---
    function setupBulkDeleteHandlers() {
        console.log('Setting up bulk delete handlers with route:', bulkDeleteRoute);
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

    // --- User Management ---
    function editUser(userId, editRoute = null) {
        const numericUserId = parseInt(userId);
        
        // Try to get from cache first
        const cachedUser = userDataCache.get(numericUserId);
        if (cachedUser) {
            populateEditForm(cachedUser);
            return Promise.resolve(cachedUser);
        }

        // Use provided editRoute or fall back to global route
        const route = editRoute || window.userEditRoute;
        
        if (!route) {
            console.error('Edit route not provided and global route not found');
            alert('Edit route not configured properly');
            return Promise.reject('Edit route not configured');
        }
        
        const url = route.replace(':id', userId);

        // Use AbortController for request cancellation if needed
        const controller = new AbortController();
        
        return fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                const user = data.user;
                
                // Cache the user data for future use
                userDataCache.set(numericUserId, user);
                
                populateEditForm(user);
                return user;
            })
            .catch(error => {
                hideEditPreloader();
                console.error('Error loading user:', error);
                
                // More user-friendly error messages
                if (error.name === 'AbortError') {
                    console.log('Request was cancelled');
                } else if (error.message.includes('HTTP 404')) {
                    alert('User not found');
                } else if (error.message.includes('HTTP 403')) {
                    alert('Access denied');
                } else {
                    alert('Error loading user data. Please try again.');
                }
                
                throw error;
            });
    }

    function deleteUser(userId, userName) {
        // Use the new confirmation modal if available
        if (typeof confirmDeleteUser === 'function') {
            confirmDeleteUser(userId, userName);
        } else {
            // Fallback to old method if confirmation modal is not available
            $('#delete_user_id').val(userId);
            $('#delete_user_name').text(userName);

            // Update form action
            const form = $('#deleteUserForm')[0];
            if (form) {
                form.action = form.action.includes(':id') ?
                    form.action.replace(':id', userId) :
                    form.action.replace(/\/\d+$/, '') + '/' + userId;
            }
        }
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
        console.log('Setting bulk delete route for users:', route);
        bulkDeleteRoute = route;
        // Setup handlers after route is set
        if (usersTable) {
            console.log('Setting up bulk delete handlers for users');
            setupBulkDeleteHandlers();
        }
    }

    // Cache management functions
    function clearCache() {
        userDataCache.clear();
    }

    function getCacheSize() {
        return userDataCache.size;
    }

    function preloadUserData(users) {
        if (Array.isArray(users)) {
            users.forEach(user => {
                userDataCache.set(user.id, user);
            });
        }
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
        clearCache,
        getCacheSize,
        preloadUserData,
        getTable: () => usersTable,
        getSelectedUsers: () => selectedUsers,
        refreshDataTable: () => {
            // Clear cache when refreshing to ensure fresh data
            clearCache();
            return DataTableGlobal.refreshDataTable(usersTable);
        },
        updateRecordInfo: () => DataTableGlobal.updateRecordInfo(usersTable, "#record-info", "users")
    };
})();

// --- Legacy Support ---
window.editUser = (userId, editRoute) => UsersDataTable.editUser(userId, editRoute);
window.deleteUser = (userId, userName) => UsersDataTable.deleteUser(userId, userName);
window.setPageListItems = (event) => UsersDataTable.setPageListItems(event);