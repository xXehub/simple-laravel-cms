/**
 * Menus DataTable Configuration
 *
 * @author KantorKu SuperApp Team
 * @version 1.0.0
 */

window.MenusDataTable = (function () {
    "use strict";

    let menusTable;

    // Menus table configuration
    function getTableConfig(route) {
        return {
            ajax: {
                url: route,
                type: "GET",
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) =>
                        `<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select menu" value="${row.id}"/>`,
                },
                {
                    data: 0,
                    name: "id",
                    className: "text-secondary",
                },
                {
                    data: 1,
                    name: "nama_menu",
                    className: "fw-medium",
                },
                {
                    data: 2,
                    name: "slug",
                    className: "text-secondary",
                },
                {
                    data: 3,
                    name: "parent_id",
                    orderable: false,
                    className: "text-secondary",
                },
                {
                    data: 4,
                    name: "route_name",
                },
                {
                    data: 5,
                    name: "icon",
                    orderable: false,
                    className: "text-center",
                },
                {
                    data: 6,
                    name: "urutan",
                    className: "text-center",
                },
                {
                    data: 7,
                    name: "is_active",
                    orderable: false,
                    className: "text-center",
                },
                {
                    data: 8,
                    name: "roles",
                    orderable: false,
                },
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

    // Initialize menus DataTable
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            // Wait for TomSelect
            function waitForTomSelect() {
                return new Promise((resolve) => {
                    function checkTomSelect() {
                        if (typeof window.TomSelect === "undefined") {
                            setTimeout(checkTomSelect, 100);
                            return;
                        }
                        resolve();
                    }
                    checkTomSelect();
                });
            }

            return waitForTomSelect().then(() => {
                window.tomSelectInstances = window.tomSelectInstances || {};
                initTomSelect();

                const menusTableConfig = getTableConfig(route);

                return DataTableGlobal.initializeDataTable("#menusTable", {
                    tableConfig: menusTableConfig,
                    drawCallbackOptions: {
                        paginationSelector: "#datatable-pagination",
                        tableInstance: "menusTable",
                    },
                    searchInputSelector: "#advanced-table-search",
                    pageCountSelector: "#page-count",
                    enableSelectAll: true,
                    enableSearch: true,
                    enableKeyboardShortcuts: true,
                }).then((table) => {
                    menusTable = table;
                    window.menusTable = table; // Make it globally available
                    return table;
                });
            });
        });
    }

    // TomSelect Functions
    function initTomSelect() {
        destroyTomSelects();
        [
            "create_roles",
            "edit_roles",
            "create_parent_id",
            "edit_parent_id",
        ].forEach((id) => {
            const el = document.getElementById(id);
            if (el && !el.tomselect) {
                window.tomSelectInstances[id] = new TomSelect(el, {});
            }
        });
    }

    function destroyTomSelects() {
        Object.values(window.tomSelectInstances || {}).forEach((ts) =>
            ts?.destroy()
        );
        window.tomSelectInstances = {};
    }

    // Filter functionality
    function filterTable(type) {
        if (!menusTable) return;

        let searchTerm = "";
        switch (type) {
            case "active":
                searchTerm = "Active";
                menusTable.column(8).search(searchTerm).draw();
                break;
            case "inactive":
                searchTerm = "Inactive";
                menusTable.column(8).search(searchTerm).draw();
                break;
            case "parent":
                searchTerm = "^-$";
                menusTable.column(4).search(searchTerm, true, false).draw();
                break;
            case "child":
                searchTerm = "^(?!-).*";
                menusTable.column(4).search(searchTerm, true, false).draw();
                break;
            case "all":
            default:
                menusTable.columns().search("").draw();
                break;
        }
    }

    // Modal functions
    function showModal(el) {
        if (window.$?.fn?.modal) return $(el).modal("show");
        if (window.bootstrap?.Modal) return new bootstrap.Modal(el).show();
        el.classList.add("show");
        el.style.display = "block";
        el.setAttribute("aria-hidden", "false");
        document.body.classList.add("modal-open");
        if (!document.querySelector(".modal-backdrop")) {
            const b = document.createElement("div");
            b.className = "modal-backdrop fade show";
            document.body.appendChild(b);
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
        document.querySelector(".modal-backdrop")?.remove();
    }

    // Menu-specific functions
    function refreshParentMenuOptions(route) {
        return fetch(route, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((r) => {
                if (!r.ok) throw new Error("Network error");
                return r.json();
            })
            .then((data) => {
                if (data.parentMenus) {
                    ["create_parent_id", "edit_parent_id"].forEach((id) => {
                        const sel = document.getElementById(id);
                        if (sel) {
                            updateSelectOptions(sel, data.parentMenus);
                            if (window.tomSelectInstances[id]) {
                                window.tomSelectInstances[id].destroy();
                                window.tomSelectInstances[id] = new TomSelect(
                                    sel,
                                    {}
                                );
                            }
                        }
                    });
                }
                return data;
            });
    }

    function refreshDataTable() {
        if (menusTable) {
            menusTable.ajax.reload(null, false);
        }
    }

    function updateSelectOptions(sel, opts) {
        while (sel.children.length > 1) sel.removeChild(sel.lastChild);
        Object.entries(opts).forEach(([id, name]) => {
            const o = document.createElement("option");
            o.value = id;
            o.textContent = name
                .replace(/└─\s/g, "")
                .replace(/Parent\s-\s/g, "");
            sel.appendChild(o);
        });
    }

    // Utility functions
    function setValue(id, val) {
        const el = document.getElementById(id);
        if (el) el.value = val;
    }

    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    function setChecked(id, val) {
        const el = document.getElementById(id);
        if (el) el.checked = !!val;
    }

    function setSelect(id, val, tsKey) {
        const ts =
            window.tomSelectInstances && window.tomSelectInstances[tsKey];
        if (ts) {
            ts.clear();
            if (val) ts.setValue(val.toString());
        } else {
            const el = document.getElementById(id);
            if (el) el.value = val || "";
        }
    }

    function setRoles(id, arr) {
        const ts = window.tomSelectInstances && window.tomSelectInstances[id];
        if (ts) {
            ts.clear();
            (arr || []).forEach((v) => ts.addItem(v.toString()));
        } else {
            const el = document.getElementById(id);
            if (el) {
                Array.from(el.options).forEach((o) => (o.selected = false));
                (arr || []).forEach((v) => {
                    const o = el.querySelector(`option[value="${v}"]`);
                    if (o) o.selected = true;
                });
            }
        }
    }

    function fillEditModal(menu) {
        const ids = [
            "edit_menu_id",
            "edit_nama_menu",
            "edit_slug",
            "edit_route_name",
            "edit_icon",
            "edit_parent_id",
            "edit_urutan",
            "edit_is_active",
        ];
        if (ids.some((id) => !document.getElementById(id))) return;

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
            (menu.roles || []).map((r) => r.id)
        );

        if (window.tomSelectInstances) {
            Object.values(window.tomSelectInstances).forEach((ts) =>
                ts?.sync()
            );
        }

        showModal(document.getElementById("editMenuModal"));
    }

    function openEditModal(menu, route) {
        fillEditModal(menu);
        refreshParentMenuOptions(route)
            .then(() => {
                setSelect("edit_parent_id", menu.parent_id, "edit_parent_id");
            })
            .catch(() => {});
    }

    function openDeleteModal(menu) {
        const m = document.getElementById("deleteMenuModal");
        if (!m) return;
        setValue("delete_menu_id", menu.id);
        setText("delete_menu_name", menu.nama_menu);
        setText("delete_menu_slug", menu.slug);
        setText("delete_menu_route", menu.route_name || "-");
        showModal(m);
    }

    function setupModalHandlers() {
        // Modal click handlers
        document.addEventListener("click", (e) => {
            if (e.target.closest('[data-bs-dismiss="modal"]')) {
                hideModal(e.target.closest(".modal"));
            }
            if (e.target.classList.contains("modal-backdrop")) {
                document.querySelectorAll(".modal.show").forEach(hideModal);
            }
        });
    }

    // Public API
    return {
        initialize,
        filterTable,
        refreshParentMenuOptions,
        refreshDataTable,
        openEditModal,
        openDeleteModal,
        setupModalHandlers,
        getTable: () => menusTable,
    };
})();

// Make functions globally available for backward compatibility
window.filterTable = (type) => MenusDataTable.filterTable(type);
window.openEditModal = (menu, route) =>
    MenusDataTable.openEditModal(menu, route);
window.openDeleteModal = (menu) => MenusDataTable.openDeleteModal(menu);
