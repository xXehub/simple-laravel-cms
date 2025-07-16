/**
 * Menus DataTable Configuration
 * Server-side processing enabled for better performance
 * @author KantorKu SuperApp Team
 * @version 2.2.0
 */

window.MenusDataTable = (function () {
    "use strict";

    let menusTable;
    let tomSelectInstances = {};

    // Table configuration with server-side processing
    function getTableConfig(route) {
        return {
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: {
                url: route,
                type: "GET",
                data: function (d) {
                    // Send additional parameters for server-side processing
                    return d;
                },
                error: function (xhr, error, thrown) {
                    console.error("DataTable Ajax Error:", error, thrown);
                },
            },
            columns: [{
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select menu" value="${row.id}"/>`;
                    },
                },
                {
                    data: "icon",
                    name: "icon",
                    orderable: false, // Icons should not be orderable
                    className: "text-center",
                    //  render: (data) =>
                    //     `<span class="avatar avatar-xs me-2" style="background-image: url(./static/avatars/000m.jpg);"></span>${data}`,
                    render: function (data, type, row) {
                        return data ?
                            `<span class="avatar avatar-xs me-2" style="background-image: url(./static/avatars/000m.jpg);"><i class="${data}"></i></span>` :
                            '<span class="text-muted">-</span>';
                    },
                },
                // {
                //     data: "id",
                //     name: "id",
                //     className: "text-secondary",
                // },
                {
                    data: "nama_menu",
                    name: "nama_menu",
                    // className: "text-secondary",
                    // render: function(data, type, row) {
                    //     return `<strong>${data}</strong>`;
                    // }
                    render: function (data, type, row) {
                        return `${data}`;
                    },
                },
                {
                    data: "slug",
                    name: "slug",
                    className: "text-secondary",
                    render: function (data, type, row) {
                        return `<code>${data}</code>`;
                    },
                },
                {
                    data: "parent",
                    name: "parent_id",
                    orderable: false,
                    className: "text-secondary",
                    render: function (data, type, row) {
                        return data ?
                            `<span class="badge bg-secondary-lt">${data}</span>` :
                            '<span class="text-muted">-</span>';
                    },
                },
                {
                    data: "route_name",
                    name: "route_name",
                    render: function (data, type, row) {
                        return data ?
                            `<code>${data}</code>` :
                            '<span class="text-muted">-</span>';
                    },
                },

                {
                    data: "urutan",
                    name: "urutan",
                    className: "text-center",
                },
                {
                    data: "is_active",
                    name: "is_active",
                    orderable: false,
                    className: "text-center",
                    render: function (data, type, row) {
                        // Handle boolean values properly
                        return data === true || data === 1 || data === "1" ?
                            '<span class="badge bg-success-lt">Active</span>' :
                            '<span class="badge bg-danger-lt">Inactive</span>';
                    },
                },
                {
                    data: "roles",
                    name: "roles",
                    orderable: false,
                    render: function (data, type, row) {
                        if (data && data.length > 0) {
                            return data
                                .map(
                                    (role) =>
                                    `<span class="badge bg-secondary-lt me-1">${role}</span>`
                                )
                                .join("");
                        }
                        return '<span class="text-muted">No roles</span>';
                    },
                },
                {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                    render: function (data, type, row) {
                        // Return the pre-rendered action HTML from component
                        return data;
                    },
                },
            ],
            order: [
                [7, "asc"], // urutan column
                [2, "asc"], // nama_menu column
            ],
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            language: {
                processing: "Memuat...",
                zeroRecords: "No menus found",
                info: "Showing _START_ to _END_ of _TOTAL_ menus",
                infoEmpty: "Showing 0 to 0 of 0 menus",
                infoFiltered: "(filtered from _MAX_ total menus)",
                lengthMenu: "Show _MENU_ menus per page",
                search: "Search menus:",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous",
                },
            },
        };
    }

    // Initialize DataTable and TomSelect
    function initialize(route) {
        return DataTableGlobal.waitForLibraries()
            .then(waitForTomSelect)
            .then(function () {
                destroyTomSelects();
                // Inisialisasi TomSelect hanya pada elemen yang ada
                var ids = [
                    "create_roles",
                    "edit_roles",
                    "create_parent_id",
                    "edit_parent_id",
                ];
                for (var i = 0; i < ids.length; i++) {
                    var el = document.getElementById(ids[i]);
                    if (el && !el.tomselect) {
                        tomSelectInstances[ids[i]] = new TomSelect(el, {});
                    }
                }
                window.tomSelectInstances = tomSelectInstances;
                return DataTableGlobal.initializeDataTable("#menusTable", {
                    tableConfig: getTableConfig(route),
                    drawCallbackOptions: {
                        paginationSelector: "#datatable-pagination",
                        tableInstance: "menusTable",
                    },
                    searchInputSelector: "#advanced-table-search",
                    pageCountSelector: "#page-count",
                    enableSelectAll: true,
                    enableSearch: true,
                    enableKeyboardShortcuts: true,
                });
            })
            .then(function (table) {
                menusTable = table;
                window.menusTable = table;
                return table;
            });
    }

    function waitForTomSelect() {
        return new Promise(function (resolve) {
            (function check() {
                if (typeof window.TomSelect === "undefined") {
                    setTimeout(check, 100);
                    return;
                }
                resolve();
            })();
        });
    }

    function destroyTomSelects() {
        for (var key in tomSelectInstances) {
            if (
                tomSelectInstances[key] &&
                typeof tomSelectInstances[key].destroy === "function"
            ) {
                tomSelectInstances[key].destroy();
            }
        }
        tomSelectInstances = {};
    }

    // Table filtering (tanpa perulangan)
    function filterTable(type) {
        if (!menusTable) return;
        if (type === "active") {
            menusTable.column(8).search("Active").draw();
        } else if (type === "inactive") {
            menusTable.column(8).search("Inactive").draw();
        } else if (type === "parent") {
            menusTable.column(4).search("^-$", true, false).draw();
        } else if (type === "child") {
            menusTable.column(4).search("^(?!-).*", true, false).draw();
        } else {
            menusTable.columns().search("").draw();
        }
    }

    // Modal utilities (tanpa OOP/perulangan)
    function showModal(el) {
        // Prefer jQuery modal if available
        if (window.$ && $.fn && $.fn.modal) {
            return $(el).modal("show");
        }
        // Fallback to Bootstrap 5 Modal if available
        if (window.bootstrap && bootstrap.Modal) {
            return new bootstrap.Modal(el).show();
        }
        // Manual fallback
        el.classList.add("show");
        el.style.display = "block";
        el.setAttribute("aria-hidden", "false");
        document.body.classList.add("modal-open");
        if (!document.querySelector(".modal-backdrop")) {
            var backdrop = document.createElement("div");
            backdrop.className = "modal-backdrop fade show";
            document.body.appendChild(backdrop);
        }
    }

    function hideModal(el) {
        // Prefer jQuery modal if available
        if (window.$ && $.fn && $.fn.modal) {
            return $(el).modal("hide");
        }
        // Fallback to Bootstrap 5 Modal if available
        if (window.bootstrap && bootstrap.Modal) {
            var instance = bootstrap.Modal.getInstance(el);
            if (instance) return instance.hide();
        }
        // Manual fallback
        el.classList.remove("show");
        el.style.display = "none";
        el.setAttribute("aria-hidden", "true");
        document.body.classList.remove("modal-open");
        var bd = document.querySelector(".modal-backdrop");
        if (bd) bd.remove();
    }

    // Menu operations (tanpa perulangan)
    function refreshParentMenuOptions(route) {
        return fetch(route, {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            })
            .then(function (response) {
                if (!response.ok) throw new Error("Network error");
                return response.json();
            })
            .then(function (data) {
                if (data.parentMenus) {
                    var createSel = document.getElementById("create_parent_id");
                    var editSel = document.getElementById("edit_parent_id");
                    if (createSel) {
                        updateSelectOptions(createSel, data.parentMenus);
                        if (tomSelectInstances["create_parent_id"]) {
                            tomSelectInstances["create_parent_id"].destroy();
                            tomSelectInstances["create_parent_id"] =
                                new TomSelect(createSel, {});
                        }
                    }
                    if (editSel) {
                        updateSelectOptions(editSel, data.parentMenus);
                        if (tomSelectInstances["edit_parent_id"]) {
                            tomSelectInstances["edit_parent_id"].destroy();
                            tomSelectInstances["edit_parent_id"] =
                                new TomSelect(editSel, {});
                        }
                    }
                }
                return data;
            });
    }

    function refreshDataTable() {
        if (menusTable) menusTable.ajax.reload(null, false);
    }

    function updateSelectOptions(select, options) {
        while (select.children.length > 1) select.removeChild(select.lastChild);
        for (var key in options) {
            if (Object.prototype.hasOwnProperty.call(options, key)) {
                var option = document.createElement("option");
                option.value = key;
                option.textContent = options[key]
                    .replace(/└─\s/g, "")
                    .replace(/Parent\s-\s/g, "");
                select.appendChild(option);
            }
        }
    }

    // Form utilities (tanpa perulangan)
    function setValue(id, val) {
        var el = document.getElementById(id);
        if (el) el.value = val;
    }

    function setText(id, val) {
        var el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    function setChecked(id, val) {
        var el = document.getElementById(id);
        if (el) el.checked = !!val;
    }

    function setSelect(id, val, tsKey) {
        var ts = tomSelectInstances[tsKey];
        if (ts) {
            ts.clear();
            if (val) ts.setValue(val.toString());
        } else {
            var el = document.getElementById(id);
            if (el) el.value = val || "";
        }
    }

    function setRoles(id, arr) {
        var ts = tomSelectInstances[id];
        if (ts) {
            ts.clear();
            if (arr && arr.length)
                for (var i = 0; i < arr.length; i++)
                    ts.addItem(arr[i].toString());
        } else {
            var el = document.getElementById(id);
            if (el) {
                var opts = el.options;
                for (var j = 0; j < opts.length; j++) opts[j].selected = false;
                if (arr && arr.length)
                    for (var k = 0; k < arr.length; k++) {
                        var o = el.querySelector(
                            'option[value="' + arr[k] + '"]'
                        );
                        if (o) o.selected = true;
                    }
            }
        }
    }

    // Modal operations (tanpa perulangan)
    function fillEditModal(menu) {
        if (!document.getElementById("edit_menu_id")) return;
        setValue("edit_menu_id", menu.id);
        setValue("edit_nama_menu", menu.nama_menu);
        setValue("edit_slug", menu.slug);
        setValue("edit_route_name", menu.route_name || "");
        setValue("edit_icon", menu.icon || "");
        setValue("edit_urutan", menu.urutan);
        setChecked("edit_is_active", menu.is_active == 1);
        setSelect("edit_parent_id", menu.parent_id, "edit_parent_id");
        setRoles(
            "edit_roles",
            (menu.roles || []).map(function (r) {
                return r.id;
            })
        );
        for (var key in tomSelectInstances)
            if (
                tomSelectInstances[key] &&
                typeof tomSelectInstances[key].sync === "function"
            )
                tomSelectInstances[key].sync();
        showModal(document.getElementById("editMenuModal"));
    }

    function openEditModal(menu, route) {
        fillEditModal(menu);
        refreshParentMenuOptions(route)
            .then(function () {
                setSelect("edit_parent_id", menu.parent_id, "edit_parent_id");
            })
            .catch(function () {});
    }

    function openDeleteModal(menu) {
        var modal = document.getElementById("deleteMenuModal");
        if (!modal) return;
        setValue("delete_menu_id", menu.id);
        setText("delete_menu_name", menu.nama_menu);
        setText("delete_menu_slug", menu.slug);
        setText("delete_menu_route", menu.route_name || "-");
        showModal(modal);
    }

    function setupModalHandlers() {
        document.addEventListener("click", function (e) {
            if (e.target.closest('[data-bs-dismiss="modal"]')) {
                hideModal(e.target.closest(".modal"));
            }
            if (e.target.classList.contains("modal-backdrop")) {
                var modals = document.querySelectorAll(".modal.show");
                for (var i = 0; i < modals.length; i++) hideModal(modals[i]);
            }
        });
    }

    // State management for bulk operations
    let selectedMenus = [];

    // Setup checkbox handlers for bulk operations
    function setupCheckboxHandlers() {
        // Handle select all checkbox
        $('#select-all').on('change', function () {
            const isChecked = this.checked;
            $('.table-selectable-check').prop('checked', isChecked);
            updateSelectedMenus();
            updateBulkDeleteButton();
            updateActionButtonsState();
        });

        // Handle individual checkboxes
        $(document).on('change', '.table-selectable-check', function () {
            updateSelectedMenus();
            updateSelectAllState();
            updateBulkDeleteButton();
            updateActionButtonsState();
        });
    }

    // Update selected menus array
    function updateSelectedMenus() {
        selectedMenus = [];
        $('.table-selectable-check:checked').each(function () {
            const row = menusTable.row($(this).closest('tr')).data();
            if (row) {
                selectedMenus.push({
                    id: row.id,
                    nama_menu: row.nama_menu,
                    slug: row.slug
                });
            }
        });

        $('#selected-count').text(selectedMenus.length);
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
        const hasSelected = selectedMenus.length > 0;
        $('#delete-selected-btn').prop('disabled', !hasSelected);
        $('#selected-count').text(selectedMenus.length);
    }

    // Disable/enable action buttons based on selection
    function updateActionButtonsState() {
        const hasSelected = selectedMenus.length > 0;

        // Disable individual action buttons when items are selected
        if (hasSelected) {
            $('.btn-outline-primary, .btn-outline-danger').not('#delete-selected-btn').prop('disabled', true);
        } else {
            $('.btn-outline-primary, .btn-outline-danger').not('#delete-selected-btn').prop('disabled', false);
        }
    }

    // Setup bulk delete handlers
    function setupBulkDeleteHandlers(bulkDeleteRoute, csrfToken) {
        // Show selected menus in delete modal
        $('#deleteSelectedModal').on('show.bs.modal', function () {
            $('#delete-selected-count').text(selectedMenus.length);

            let menusList = '';
            selectedMenus.forEach(menu => {
                menusList += `<div class="border rounded p-2 mb-1">
                    <strong>${menu.nama_menu}</strong><br>
                    <small class="text-muted">Slug: ${menu.slug}</small>
                </div>`;
            });

            $('#selected-menus-list').html(menusList);
        });

        // Handle bulk delete confirmation
        $('#confirm-delete-selected').on('click', function () {
            const menuIds = selectedMenus.map(menu => menu.id);
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
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        menu_ids: menuIds
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
                    alert('Error deleting menus. Please try again.');
                })
                .finally(() => {
                    // Reset button
                    button.prop('disabled', false).html(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                        <polyline points="3,6 5,6 21,6"></polyline>
                        <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2,2h4a2,2,0,0,1,2,2V6"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    Delete Selected
                `);
                });
        });
    }

    // Update record information
    function updateRecordInfo() {
        if (menusTable && menusTable.page) {
            const info = menusTable.page.info();
            if (info) {
                const start = info.start + 1;
                const end = info.end;
                const total = info.recordsTotal;
                const filtered = info.recordsFiltered;

                let infoText = '';
                if (total === 0) {
                    infoText = 'Showing <strong>0 to 0</strong> of <strong>0 entries</strong>';
                } else if (filtered !== total) {
                    infoText =
                        `Showing <strong>${start} to ${end}</strong> of <strong>${filtered} entries</strong> (filtered from ${total} total entries)`;
                } else {
                    infoText = `Showing <strong>${start} to ${end}</strong> of <strong>${total} entries</strong>`;
                }

                $('#record-info').html(infoText);
            }
        }
    }

    // Setup draw callback to update UI states
    function setupDrawCallback() {
        // Override draw callback to update record info
        $(document).on('draw.dt', '#menusTable', function () {
            updateRecordInfo();
            updateSelectedMenus();
            updateSelectAllState();
            updateBulkDeleteButton();
            updateActionButtonsState();
        });
    }

    // Setup page length handler
    function setupPageLengthHandler() {
        window.setPageListItems = function (event) {
            event.preventDefault();
            const value = parseInt(event.target.getAttribute('data-value'));
            if (menusTable && value) {
                menusTable.page.len(value).draw();
                $('#page-count').text(value);
            }
        };
    }


    // Initialize all handlers
    function initializeAllHandlers(bulkDeleteRoute, csrfToken) {
        setupCheckboxHandlers();
        setupBulkDeleteHandlers(bulkDeleteRoute, csrfToken);
        setupDrawCallback();
        setupPageLengthHandler();
    }

    // Public API
    return {
        initialize: initialize,
        filterTable: filterTable,
        refreshParentMenuOptions: refreshParentMenuOptions,
        refreshDataTable: refreshDataTable,
        openEditModal: openEditModal,
        openDeleteModal: openDeleteModal,
        setupModalHandlers: setupModalHandlers,
        initializeAllHandlers: initializeAllHandlers,
        updateRecordInfo: updateRecordInfo,
        getTable: function () {
            return menusTable;
        },
        getSelectedMenus: function () {
            return selectedMenus;
        },
    };
})();

// Global compatibility functions (optional, for legacy usage)
window.filterTable = function (type) {
    MenusDataTable.filterTable(type);
};
window.openEditModal = function (menu, route) {
    MenusDataTable.openEditModal(menu, route);
};
window.openDeleteModal = function (menu) {
    MenusDataTable.openDeleteModal(menu);
};
