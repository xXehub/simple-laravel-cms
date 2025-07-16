/**
 * Users DataTable Configuration
 * Server-side processing enabled for better performance
 * @author KantorKu SuperApp Team
 * @version 2.2.0
 */

window.UsersDataTable = (function () {
    "use strict";

    let usersTable;
    let selectedUsers = [];

    // Users table configuration with server-side processing
    function getTableConfig(route) {
        return {
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: {
                url: route,
                type: "GET",
                data: function(d) {
                    return d;
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTable Ajax Error:', error, thrown);
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) =>
                        `<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select user" value="${row.id}"/>`,
                },
                {
                    data: "name",
                    name: "name",
                    render: (data) =>
                        `<span class="avatar avatar-xs me-2" style="background-image: url(./static/avatars/000m.jpg);"></span>${data}`,
                },
                {
                    data: "email",
                    name: "email",
                },
                {
                    data: "roles",
                    name: "roles",
                    orderable: false,
                    searchable: false,
                    render: (data) => {
                        if (data && data.length > 0) {
                            return data
                                .map(
                                    (role) =>
                                        `<span class="badge bg-primary-lt me-1">${role}</span>`
                                )
                                .join("");
                        }
                        return '<span class="badge bg-secondary-lt">No Role</span>';
                    },
                },
                {
                    data: "created_at",
                    name: "created_at",
                    render: (data) =>
                        new Date(data).toLocaleDateString("en-US", {
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                        }),
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                },
            ],
            order: [[1, "asc"]],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            language: {
                processing: "Loading users...",
                zeroRecords: "No users found",
                info: "Showing _START_ to _END_ of _TOTAL_ users",
                infoEmpty: "Showing 0 to 0 of 0 users",
                infoFiltered: "(filtered from _MAX_ total users)",
                lengthMenu: "Show _MENU_ users per page",
                search: "Search users:",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
        };
    }

    // Initialize users DataTable
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            const usersTableConfig = getTableConfig(route);

            return DataTableGlobal.initializeDataTable("#datatable-users", {
                tableConfig: usersTableConfig,
                drawCallbackOptions: {
                    paginationSelector: "#datatable-pagination",
                    tableInstance: "usersTable",
                },
                enableSelectAll: true,
                enableSearch: true,
                enableKeyboardShortcuts: true,
            }).then((table) => {
                usersTable = table;
                window.usersTable = table;
                
                // Setup event handlers
                setupEventHandlers();
                
                return table;
            });
        });
    }

    // Setup all event handlers
    function setupEventHandlers() {
        setupCheckboxHandlers();
        setupBulkDeleteHandlers();
        setupDataTableEvents();
    }

    // Setup checkbox handlers for bulk operations
    function setupCheckboxHandlers() {
        // Handle select all checkbox
        $('#select-all').off('change.users').on('change.users', function() {
            const isChecked = this.checked;
            $('.table-selectable-check').prop('checked', isChecked);
            updateSelectedUsers();
            updateBulkDeleteButton();
            updateActionButtonsState();
        });

        // Handle individual checkboxes
        $(document).off('change.users', '.table-selectable-check').on('change.users', '.table-selectable-check', function() {
            updateSelectedUsers();
            updateSelectAllState();
            updateBulkDeleteButton();
            updateActionButtonsState();
        });
    }

    // Update selected users array
    function updateSelectedUsers() {
        selectedUsers = [];
        $('.table-selectable-check:checked').each(function() {
            const row = usersTable.row($(this).closest('tr')).data();
            if (row) {
                selectedUsers.push({
                    id: row.id,
                    name: row.name,
                    email: row.email
                });
            }
        });

        $('#selected-count').text(selectedUsers.length);
    }

    // Update select all checkbox state
    function updateSelectAllState() {
        const totalCheckboxes = $('.table-selectable-check').length;
        const checkedCheckboxes = $('.table-selectable-check:checked').length;

        $('#select-all').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#select-all').prop('checked', checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0);
    }

    // Update bulk delete button state
    function updateBulkDeleteButton() {
        const hasSelected = selectedUsers.length > 0;
        $('#delete-selected-btn').prop('disabled', !hasSelected);
        $('#selected-count').text(selectedUsers.length);
    }

    // Disable/enable action buttons based on selection
    function updateActionButtonsState() {
        const hasSelected = selectedUsers.length > 0;

        // Disable individual action buttons when items are selected
        if (hasSelected) {
            $('.btn-outline-primary, .btn-outline-danger').not('#delete-selected-btn').prop('disabled', true);
        } else {
            $('.btn-outline-primary, .btn-outline-danger').not('#delete-selected-btn').prop('disabled', false);
        }
    }

    // Setup bulk delete handlers
    function setupBulkDeleteHandlers() {
        // Show selected users in delete modal
        $('#deleteSelectedModal').off('show.bs.modal.users').on('show.bs.modal.users', function() {
            $('#delete-selected-count').text(selectedUsers.length);

            let usersList = '';
            selectedUsers.forEach(user => {
                usersList += `<div class="border rounded p-2 mb-1">
                    <strong>${user.name}</strong><br>
                    <small class="text-muted">Email: ${user.email}</small>
                </div>`;
            });

            $('#selected-users-list').html(usersList);
        });

        // Handle bulk delete confirmation
        $('#confirm-delete-selected').off('click.users').on('click.users', function() {
            const userIds = selectedUsers.map(user => user.id);
            const button = $(this);

            // Disable button and show loading
            button.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Deleting...
            `);

            // Send request
            fetch(bulkDeleteRoute, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_ids: userIds
                })
            })
            .then(response => {
                if (response.ok) {
                    // Close modal and refresh table
                    $('#deleteSelectedModal').modal('hide');
                    location.reload(); // Simple reload to show flash messages
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting users. Please try again.');
            })
            .finally(() => {
                // Reset button
                button.prop('disabled', false).html(`
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                    <polyline points="3,6 5,6 21,6"></polyline>
                    <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                Delete Selected
            `);
            });
        });
    }

    // Setup DataTable specific events
    function setupDataTableEvents() {
        // Override draw callback to update record info
        $(document).off('draw.dt.users', '#datatable-users').on('draw.dt.users', '#datatable-users', function() {
            updateRecordInfo();
            updateSelectedUsers();
            updateSelectAllState();
            updateBulkDeleteButton();
            updateActionButtonsState();
        });
    }

    // Update record information
    function updateRecordInfo() {
        if (usersTable && usersTable.page) {
            const info = usersTable.page.info();
            if (info) {
                const start = info.start + 1;
                const end = info.end;
                const total = info.recordsTotal;
                const filtered = info.recordsFiltered;
                
                let infoText = '';
                if (total === 0) {
                    infoText = 'Showing <strong>0 to 0</strong> of <strong>0 entries</strong>';
                } else if (filtered !== total) {
                    infoText = `Showing <strong>${start} to ${end}</strong> of <strong>${filtered} entries</strong> (filtered from ${total} total entries)`;
                } else {
                    infoText = `Showing <strong>${start} to ${end}</strong> of <strong>${total} entries</strong>`;
                }
                
                $('#record-info').html(infoText);
            }
        }
    }

    // Refresh DataTable
    function refreshDataTable() {
        if (usersTable) {
            usersTable.ajax.reload(null, false);
        }
    }

    // User action functions
    function editUser(userId, editRoute) {
        fetch(`${editRoute}?id=${userId}`, {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                const user = data.user;
                document.getElementById("edit_user_id").value = user.id;
                document.getElementById("edit_name").value = user.name;
                document.getElementById("edit_email").value = user.email;
                document.getElementById("edit_password").value = "";
                document.getElementById("edit_password_confirmation").value = "";

                const editRoleCheckboxes = document.querySelectorAll(
                    '#edit_roles_container input[name="roles[]"]'
                );
                editRoleCheckboxes.forEach(
                    (checkbox) => (checkbox.checked = false)
                );

                user.roles.forEach((role) => {
                    const editCheckbox = document.querySelector(
                        `#edit_roles_container input[value="${role.name}"]`
                    );
                    if (editCheckbox) {
                        editCheckbox.checked = true;
                    }
                });
            })
            .catch((error) => {
                console.error("Error loading user data:", error);
                alert("Error loading user data");
            });
    }

    function deleteUser(userId, userName) {
        document.getElementById("delete_user_id").value = userId;
        document.getElementById("delete_user_name").textContent = userName;
    }

    function setupModalHandlers() {
        // Reset forms on modal close
        $("#createUserModal").on("hidden.bs.modal", function () {
            document.getElementById("createUserForm").reset();
        });
        $("#editUserModal").on("hidden.bs.modal", function () {
            document.getElementById("editUserForm").reset();
        });
    }

    // Page length handler
    function setPageListItems(event) {
        event.preventDefault();
        const value = parseInt(event.target.getAttribute('data-value'));
        if (usersTable && value) {
            usersTable.page.len(value).draw();
            $('#page-count').text(value);
        }
    }

    // Initialize bulk delete route (will be set from Blade)
    let bulkDeleteRoute = '';
    function setBulkDeleteRoute(route) {
        bulkDeleteRoute = route;
    }

    // Public API
    return {
        initialize,
        updateRecordInfo,
        refreshDataTable,
        editUser,
        deleteUser,
        setupModalHandlers,
        setPageListItems,
        setBulkDeleteRoute,
        setupEventHandlers,
        getTable: () => usersTable,
        getSelectedUsers: () => selectedUsers,
    };
})();

// Global compatibility functions
window.editUser = (userId, editRoute) => UsersDataTable.editUser(userId, editRoute);
window.deleteUser = (userId, userName) => UsersDataTable.deleteUser(userId, userName);
window.setPageListItems = (event) => UsersDataTable.setPageListItems(event);
