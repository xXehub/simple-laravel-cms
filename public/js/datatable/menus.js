/**
 * Menus DataTable Configuration
 * Clean and maintainable DataTable implementation for menus management
 * @author KantorKu SuperApp Team
 * @version 2.1.0
 */

window.MenusDataTable = (function () {
    "use strict";

    let menusTable;
    let tomSelectInstances = {};

    // Table configuration (minimize looping, explicit columns)
    function getTableConfig(route) {
        return {
            ajax: { url: route, type: "GET" },
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) =>
                        `<input class=\"form-check-input m-0 align-middle table-selectable-check\" type=\"checkbox\" aria-label=\"Select menu\" value=\"${row.id}\"/>`,
                },
                { data: 0, name: "id", className: "text-secondary" },
                { data: 1, name: "nama_menu", className: "fw-medium" },
                { data: 2, name: "slug", className: "text-secondary" },
                {
                    data: 3,
                    name: "parent_id",
                    orderable: false,
                    className: "text-secondary",
                },
                { data: 4, name: "route_name" },
                // ICONS COLUMN: explicitly set orderable: false
                { data: 5, name: "icon", orderable: false, className: "text-center" },
                { data: 6, name: "urutan", className: "text-center" },
                {
                    data: 7,
                    name: "is_active",
                    orderable: false,
                    className: "text-center",
                },
                { data: 8, name: "roles", orderable: false },
                {
                    data: 9,
                    name: "actions",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                },
            ],
            order: [
                [6, "asc"],
                [1, "asc"],
            ],
            pageLength: 15,
            language: {
                processing: "Loading...",
                zeroRecords: "No menus found",
                info: "",
                infoEmpty: "",
                infoFiltered: "",
                emptyTable: "No menu data available",
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
                var ids = ["create_roles", "edit_roles", "create_parent_id", "edit_parent_id"];
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
            if (tomSelectInstances[key] && typeof tomSelectInstances[key].destroy === "function") {
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
        if (window.bootstrap?.Modal) return bootstrap.Modal.getInstance(el)?.hide();
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
                            tomSelectInstances["create_parent_id"] = new TomSelect(createSel, {});
                        }
                    }
                    if (editSel) {
                        updateSelectOptions(editSel, data.parentMenus);
                        if (tomSelectInstances["edit_parent_id"]) {
                            tomSelectInstances["edit_parent_id"].destroy();
                            tomSelectInstances["edit_parent_id"] = new TomSelect(editSel, {});
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
                option.textContent = options[key].replace(/└─\s/g, "").replace(/Parent\s-\s/g, "");
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
            if (arr && arr.length) for (var i = 0; i < arr.length; i++) ts.addItem(arr[i].toString());
        } else {
            var el = document.getElementById(id);
            if (el) {
                var opts = el.options;
                for (var j = 0; j < opts.length; j++) opts[j].selected = false;
                if (arr && arr.length) for (var k = 0; k < arr.length; k++) {
                    var o = el.querySelector('option[value="' + arr[k] + '"]');
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
        setRoles("edit_roles", (menu.roles || []).map(function (r) { return r.id; }));
        for (var key in tomSelectInstances) if (tomSelectInstances[key] && typeof tomSelectInstances[key].sync === "function") tomSelectInstances[key].sync();
        showModal(document.getElementById("editMenuModal"));
    }
    function openEditModal(menu, route) {
        fillEditModal(menu);
        refreshParentMenuOptions(route)
            .then(function () { setSelect("edit_parent_id", menu.parent_id, "edit_parent_id"); })
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
        getTable: function () { return menusTable; }
    };
})();

// Global compatibility functions (optional, for legacy usage)
window.filterTable = function (type) { MenusDataTable.filterTable(type); };
window.openEditModal = function (menu, route) { MenusDataTable.openEditModal(menu, route); };
window.openDeleteModal = function (menu) { MenusDataTable.openDeleteModal(menu); };
