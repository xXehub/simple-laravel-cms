/**
 * Users DataTable Configuration
 * Server-side processing enabled for better performance
 * @author KantorKu SuperApp Team
 * @version 2.0.0
 */

window.UsersDataTable = (function () {
    "use strict";

    let dataTable;

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
                    // Send additional parameters for server-side processing
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
                    tableInstance: "dataTable",
                },
                enableSelectAll: true,
                enableSearch: true,
                enableKeyboardShortcuts: true,
            }).then((table) => {
                dataTable = table;
                window.dataTable = table; // Make it globally available
                return table;
            });
        });
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

    // Public API
    return {
        initialize,
        editUser,
        deleteUser,
        setupModalHandlers,
        getTable: () => dataTable,
    };
})();

// Make functions globally available for backward compatibility
window.editUser = (userId, editRoute) =>
    UsersDataTable.editUser(userId, editRoute);
window.deleteUser = (userId, userName) =>
    UsersDataTable.deleteUser(userId, userName);
