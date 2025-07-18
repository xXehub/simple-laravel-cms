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
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) =>
                        `<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select user" value="${row.id}"/>`,
                },
                // Name with avatar
                {
                    data: "name",
                    name: "name",
                    render: (data, type, row) => {
                        const avatarUrl =
                            row.avatar_url ||
                            "https://ui-avatars.com/api/?name=" +
                                encodeURIComponent(data.charAt(0)) +
                                "&color=ffffff&background=0ea5e9&size=32&rounded=false&bold=true";
                        return `<span class="avatar avatar-xs me-2" style="background-image: url('${avatarUrl}');"></span>${data}`;
                    },
                },
                // Username
                {
                    data: "username",
                    name: "username",
                },
                // Email
                {
                    data: "email",
                    name: "email",
                },
                // Roles badges
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
                // Created at (formatted)
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
                // Action buttons
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
                processing: "Memuat...",
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
                    previous: "Previous",
                },
            },
        };
    }

    // --- Initialize DataTable and Setup Event Handlers ---
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
                window.usersTable = table; // For debugging
                setupEventHandlers();
                return table;
            });
        });
    }

    // --- Setup All Event Handlers ---
    function setupEventHandlers() {
        setupCheckboxHandlers();
        setupBulkDeleteHandlers();
        setupDataTableEvents();
    }

    // --- Checkbox Handlers for Bulk Selection ---
    function setupCheckboxHandlers() {
        // Select all checkbox
        $("#select-all")
            .off("change.users")
            .on("change.users", function () {
                const isChecked = this.checked;
                $(".table-selectable-check").prop("checked", isChecked);
                updateSelectedUsers();
                updateBulkDeleteButton();
            });

        // Individual row checkboxes
        $(document)
            .off("change.users", ".table-selectable-check")
            .on("change.users", ".table-selectable-check", function () {
                updateSelectedUsers();
                updateSelectAllState();
                updateBulkDeleteButton();
            });
    }

    // --- Update Array of Selected Users ---
    function updateSelectedUsers() {
        selectedUsers = [];
        $(".table-selectable-check:checked").each(function () {
            selectedUsers.push($(this).val());
        });
        $("#selected-count").text(selectedUsers.length);
    }

    // --- Update Select All Checkbox State (indeterminate/checked) ---
    function updateSelectAllState() {
        const totalCheckboxes = $(".table-selectable-check").length;
        const checkedCheckboxes = $(".table-selectable-check:checked").length;
        $("#select-all").prop(
            "indeterminate",
            checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes
        );
        $("#select-all").prop(
            "checked",
            checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0
        );
    }

    // --- Enable/Disable Bulk Delete Button ---
    function updateBulkDeleteButton() {
        const hasSelected = selectedUsers.length > 0;
        $("#delete-selected-btn").prop("disabled", !hasSelected);
        $("#selected-count").text(selectedUsers.length);
    }

    // --- Bulk Delete Handlers ---
    function setupBulkDeleteHandlers() {
        // Show selected users in delete modal
        $("#deleteSelectedModal")
            .off("show.bs.modal.users")
            .on("show.bs.modal.users", function () {
                const selectedList = selectedUsers
                    .map((id) => `<li>User ID: <strong>${id}</strong></li>`)
                    .join("");
                $("#selected-users-list").html(selectedList);
            });

        // Confirm bulk delete
        $("#confirm-delete-selected")
            .off("click.users")
            .on("click.users", function () {
                // Implement AJAX bulk delete here if needed
            });
    }

    // --- DataTable Draw Event Handlers ---
    function setupDataTableEvents() {
        // Update record info on draw
        $(document)
            .off("draw.dt.users", "#datatable-users")
            .on("draw.dt.users", "#datatable-users", function () {
                updateRecordInfo();
            });
    }

    // --- Update Record Info (footer) ---
    function updateRecordInfo() {
        if (usersTable && usersTable.page) {
            const info = usersTable.page.info();
            $("#record-info").html(
                `Showing <strong>${info.start + 1} to ${
                    info.end
                }</strong> of <strong>${info.recordsDisplay}</strong> entries`
            );
        }
    }

    // --- Refresh DataTable ---
    function refreshDataTable() {
        if (usersTable) {
            usersTable.ajax.reload(null, false);
        }
    }

    // --- Edit User (AJAX load for modal) ---
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
                document.getElementById("edit_username").value = user.username;
                document.getElementById("edit_email").value = user.email;
                document.getElementById("edit_password").value = "";
                document.getElementById("edit_password_confirmation").value =
                    "";

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

    // --- Delete User (set modal fields) ---
    function deleteUser(userId, userName) {
        document.getElementById("delete_user_id").value = userId;
        document.getElementById("delete_user_name").textContent = userName;
    }

    // --- Modal Handlers (reset forms on close) ---
    function setupModalHandlers() {
        $("#createUserModal").on("hidden.bs.modal", function () {
            document.getElementById("createUserForm").reset();
        });
        $("#editUserModal").on("hidden.bs.modal", function () {
            document.getElementById("editUserForm").reset();
        });
    }

    // --- Page Length Dropdown Handler ---
    function setPageListItems(event) {
        event.preventDefault();
        const value = parseInt(event.target.getAttribute("data-value"));
        if (usersTable && value) {
            usersTable.page.len(value).draw();
            $("#page-count").text(value);
        }
    }

    // --- Set Bulk Delete Route (from Blade) ---
    let bulkDeleteRoute = "";
    function setBulkDeleteRoute(route) {
        bulkDeleteRoute = route;
    }

    // --- Public API ---
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

// --- Global compatibility functions (for legacy usage) ---
window.editUser = (userId, editRoute) =>
    UsersDataTable.editUser(userId, editRoute);
window.deleteUser = (userId, userName) =>
    UsersDataTable.deleteUser(userId, userName);
window.setPageListItems = (event) => UsersDataTable.setPageListItems(event);
