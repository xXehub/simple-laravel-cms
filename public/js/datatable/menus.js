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
                    render: function(data, type, row) {
                        return `<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select menu" value="${row.id}"/>`;
                    }
                },
                { 
                    data: "id", 
                    name: "id", 
                    className: "text-secondary" 
                },
                { 
                    data: "nama_menu", 
                    name: "nama_menu", 
                    className: "fw-medium",
                    render: function(data, type, row) {
                        return `<strong>${data}</strong>`;
                    }
                },
                { 
                    data: "slug", 
                    name: "slug", 
                    className: "text-secondary",
                    render: function(data, type, row) {
                        return `<code>${data}</code>`;
                    }
                },
                {
                    data: "parent",
                    name: "parent_id",
                    orderable: false,
                    className: "text-secondary",
                    render: function(data, type, row) {
                        return data 
                            ? `<span class="badge bg-secondary-lt">${data}</span>`
                            : '<span class="text-muted">-</span>';
                    }
                },
                { 
                    data: "route_name", 
                    name: "route_name",
                    render: function(data, type, row) {
                        return data 
                            ? `<code>${data}</code>`
                            : '<span class="text-muted">-</span>';
                    }
                },
                {
                    data: "icon",
                    name: "icon",
                    orderable: false, // Icons should not be orderable
                    className: "text-center",
                    render: function(data, type, row) {
                        return data 
                            ? `<i class="${data}"></i>`
                            : '<span class="text-muted">-</span>';
                    }
                },
                { 
                    data: "urutan", 
                    name: "urutan", 
                    className: "text-center" 
                },
                {
                    data: "is_active",
                    name: "is_active",
                    orderable: false,
                    className: "text-center",
                    render: function(data, type, row) {
                        // Handle boolean values properly
                        return data === true || data === 1 || data === '1'
                            ? '<span class="badge bg-success-lt">Active</span>'
                            : '<span class="badge bg-danger-lt">Inactive</span>';
                    }
                },
                { 
                    data: "roles", 
                    name: "roles", 
                    orderable: false,
                    render: function(data, type, row) {
                        if (data && data.length > 0) {
                            return data.map(role => `<span class="badge bg-secondary-lt me-1">${role}</span>`).join('');
                        }
                        return '<span class="text-muted">No roles</span>';
                    }
                },
                {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                    render: function(data, type, row) {
                        // Return the pre-rendered action HTML from component
                        return data;
                    }
                }
            ],
            order: [
                [7, "asc"], // urutan column
                [2, "asc"], // nama_menu column
            ],
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            language: {
                processing: "Loading menus...",
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
                    previous: "Previous"
                }
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
        if (window.$?.fn?.modal) return $(el).modal("show");
        if (window.bootstrap?.Modal) return new bootstrap.Modal(el).show();
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
        if (window.$?.fn?.modal) return $(el).modal("hide");
        if (window.bootstrap?.Modal)
            return bootstrap.Modal.getInstance(el)?.hide();
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

    // Public API
    return {
        initialize: initialize,
        filterTable: filterTable,
        refreshParentMenuOptions: refreshParentMenuOptions,
        refreshDataTable: refreshDataTable,
        openEditModal: openEditModal,
        openDeleteModal: openDeleteModal,
        setupModalHandlers: setupModalHandlers,
        getTable: function () {
            return menusTable;
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
