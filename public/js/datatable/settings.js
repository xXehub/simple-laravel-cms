/**
 * Settings DataTable Configuration - Optimized Version
 * @author KantorKu SuperApp Team
 * @version 3.1.0 - Faster edit modal with cache optimization
 */

window.SettingsDataTable = (function () {
    "use strict";

    let settingsTable;
    let selectedSettings = [];
    let settingCache = new Map(); // Cache for faster edit loading

    // --- Table Configuration ---
    function getTableConfig(route) {
        const baseConfig = DataTableGlobal.generateStandardConfig({
            tableConfig: {
                ajax: { url: route, type: "GET" },
                order: [[1, "asc"]], // ID ascending
                deferRender: true,
            },
        });

        const settingsConfig = {
            columns: [
                {
                    // Checkbox
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        // Cache data saat render untuk edit cepat
                        settingCache.set(row.id, row);

                        return `<input class="form-check-input m-0 align-middle table-selectable-check" 
                               type="checkbox" value="${row.id}"/>`;
                    },
                },
                { 
                    data: "id", 
                    name: "id",
                    className: "text-muted"
                },
                {
                    data: "key",
                    name: "key",
                    render: (data) => `${data}`,
                },
                {
                    data: "type",
                    name: "type",
                    render: (data) => {
                        const typeColors = {
                            'string': 'blue',
                            'text': 'green',
                            'textarea': 'cyan',
                            'number': 'yellow',
                            'boolean': 'purple',
                            'email': 'orange',
                            'url': 'indigo',
                            'image': 'pink',
                            'color': 'red'
                        };
                        const color = typeColors[data?.toLowerCase()] || 'secondary';
                        return `<span class="badge bg-${color}-lt">${data || 'string'}</span>`;
                    }
                },
                {
                    data: "group",
                    name: "group",
                    render: (data) => {
                        if (!data) return '<span class="text-muted">—</span>';
                        const groupColors = {
                            'general': 'blue-lt',
                            'branding': 'purple-lt',
                            'seo': 'green-lt',
                            'contact': 'yellow-lt',
                            'social': 'orange-lt',
                            'feature': 'red-lt'
                        };
                        const color = groupColors[data?.toLowerCase()] || 'secondary-lt';
                        return `<span class="badge bg-${color}">${data}</span>`;
                    }
                },
                {
                    data: "description",
                    name: "description",
                    render: (data) => {
                        if (!data) return '<span class="text-muted">—</span>';
                        if (data.length > 60) {
                            return `<span class="text-secondary" title="${data}">${data.substring(0, 60)}...</span>`;
                        }
                        return `<span class="text-secondary">${data}</span>`;
                    }
                },
                {
                    data: "value",
                    name: "value",
                    render: (data, type, row) => {
                        if (!data) return '<span class="text-muted">—</span>';
                        
                        // Handle different value types
                        if (row.type === 'boolean') {
                            const isTrue = data === '1' || data === 'true' || data === true;
                            return isTrue 
                                ? '<span class="badge bg-green text-green-fg">Enabled</span>'
                                : '<span class="badge bg-red text-red-fg">Disabled</span>';
                        }
                        
                        if (row.type === 'file' || row.type === 'image') {
                            return `<span class="badge bg-info-lt"><i class="ti ti-file me-1"></i>File</span>`;
                        }
                        
                        if (data.length > 40) {
                            return `<span class="text-truncate d-inline-block" style="max-width: 200px;" title="${data}">${data}</span>`;
                        }
                        
                        return `<code class="text-dark">${data}</code>`;
                    }
                },
                {
                    // Actions
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                },
            ],
            drawCallback: function () {
                DataTableGlobal.buildCustomPagination(
                    settingsTable,
                    "#datatable-pagination"
                );
                DataTableGlobal.updateRecordInfo(
                    settingsTable,
                    "#record-info",
                    "settings"
                );
                DataTableGlobal.updateSelectAllState();
                updateBulkDeleteButton();

                // Manage cache size
                if (settingCache.size > 500) settingCache.clear();
            },
        };

        return $.extend(true, {}, baseConfig, settingsConfig);
    }

    // --- Initialize Table ---
    function initialize(route) {
        return DataTableGlobal.waitForLibraries().then(() => {
            // Check if table element exists
            const tableElement = $("#settingsTable");
            if (tableElement.length === 0) {
                console.error("Table element #settingsTable not found");
                return null;
            }

            settingsTable = tableElement.DataTable(getTableConfig(route));

            // Setup handlers dengan safety checks
            if ($("#advanced-table-search").length > 0) {
                DataTableGlobal.createSearchHandler(
                    settingsTable,
                    "#advanced-table-search"
                );
            }

            if (
                $(".dropdown-item[data-value]").length > 0 &&
                $("#page-count").length > 0
            ) {
                DataTableGlobal.setupPageLengthHandler(
                    settingsTable,
                    ".dropdown-item[data-value]",
                    "#page-count"
                );
            }

            DataTableGlobal.setupKeyboardShortcuts("#advanced-table-search");

            setupEventHandlers();
            return settingsTable;
        });
    }

    // --- Event Handlers ---
    function setupEventHandlers() {
        DataTableGlobal.setupCheckboxHandlers(selectedSettings, {
            onUpdate: updateBulkDeleteButton,
            onStateChange: updateBulkDeleteButton,
            onButtonUpdate: updateBulkDeleteButton,
        });

        setupModalHandlers();
    }

    function updateBulkDeleteButton() {
        const deleteBtn = $("#delete-selected-btn");
        const selectedCount = $("#selected-count");

        if (deleteBtn.length > 0) {
            deleteBtn.prop("disabled", selectedSettings.length === 0);
        }
        if (selectedCount.length > 0) {
            selectedCount.text(selectedSettings.length);
        }
    }

    // --- Modal Handlers ---
    function setupModalHandlers() {
        // Setup create modal handler dengan safety check
        const createModal = $("#createSettingModal");
        if (createModal.length > 0) {
            createModal.on("show.bs.modal", function () {
                const form = $("#createSettingForm")[0];
                if (form) form.reset();
            });
        }

        // Setup edit modal handler
        const editModal = $("#editSettingModal");
        if (editModal.length > 0) {
            editModal.on("show.bs.modal", function () {
                // Modal will be populated by editSetting function
            });
        }

        // Refresh table after modal operations
        $(".modal").on("hidden.bs.modal", function () {
            if (
                $(this).find(".alert-success").length > 0 ||
                window.tableNeedsRefresh
            ) {
                refreshDataTable();
                window.tableNeedsRefresh = false;
            }
        });
    }

    // --- Setting Management Functions - OPTIMIZED ---
    function editSetting(settingId, editRoute = null) {
        const numericId = parseInt(settingId);

        // Cek cache dulu - instant loading jika ada
        const cachedSetting = settingCache.get(numericId);
        if (cachedSetting) {
            fillEditForm(cachedSetting);
            return;
        }

        // Jika tidak ada di cache, fetch dari server
        const route = editRoute || window.settingEditRoute;
        if (!route) {
            console.error("Edit route not configured");
            alert("Edit route not configured properly");
            return;
        }

        const url = route.replace(":id", settingId);

        fetch(url, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.setting) {
                    // Cache setting untuk next time
                    settingCache.set(numericId, data.setting);

                    fillEditForm(data.setting);
                }
            })
            .catch((error) => {
                console.error("Error fetching setting data:", error);
                alert("Error loading setting data");
            });
    }

    // Simplified form filling dengan safety checks
    function fillEditForm(setting) {
        // Update form action dengan safety check
        const editForm = document.getElementById("editSettingForm");
        if (editForm && window.settingUpdateRoute) {
            editForm.action = window.settingUpdateRoute.replace(":id", setting.id);
        }

        // Populate basic fields dengan safety checks
        const settingIdField = document.getElementById("edit_setting_id");
        const keyField = document.getElementById("edit_key");

        if (settingIdField) settingIdField.value = setting.id;
        if (keyField) keyField.value = setting.key || "";

        // Handle TomSelect for type field
        if (window.editTypeTomSelect) {
            window.editTypeTomSelect.setValue(setting.type || '');
        } else {
            const typeField = document.getElementById("edit_type");
            if (typeField) typeField.value = setting.type || "";
        }

        // Handle TomSelect for group field
        if (window.editGroupTomSelect) {
            window.editGroupTomSelect.setValue(setting.group || '');
        } else {
            const groupField = document.getElementById("edit_group");
            if (groupField) groupField.value = setting.group || "";
        }

        // Handle description field
        const descriptionField = document.getElementById("edit_description");
        if (descriptionField) descriptionField.value = setting.description || "";

        // Update value field berdasarkan type dan nilai
        if (typeof window.updateEditValueField === 'function') {
            window.updateEditValueField(setting.type || 'text', setting.value || '');
        } else {
            // Fallback jika function belum ready
            const valueField = document.getElementById("edit_value");
            if (valueField) valueField.value = setting.value || "";
        }
    }

    // --- ini untuk delete setting ---
    function deleteSetting(settingId, settingKey) {
        if (typeof confirmDeleteSetting === "function") {
            confirmDeleteSetting(settingId, settingKey);
        }
    }

    // --- ini untuk bulk delete setting @datatable-global.js ---
    function setupBulkDeleteHandlers() {
        if (window.settingBulkDeleteRoute) {
            DataTableGlobal.setupBulkDeleteHandler({
                modalSelector: "#deleteSelectedModal",
                selectedArray: selectedSettings,
                deleteRoute: window.settingBulkDeleteRoute,
                confirmBtnSelector: "#confirm-delete-selected",
                entityName: "settings",
                tableInstance: settingsTable,
                updateCallback: updateBulkDeleteButton,
                customRequestData: function () {
                    const csrfToken = document.querySelector(
                        'meta[name="csrf-token"]'
                    );
                    return {
                        setting_ids: selectedSettings,
                        _token: csrfToken
                            ? csrfToken.getAttribute("content")
                            : "",
                        _method: "DELETE",
                    };
                },
            });
        }
    }

    function setBulkDeleteRoute(route) {
        window.settingBulkDeleteRoute = route;
        if (settingsTable) setupBulkDeleteHandlers();
    }

    // --- Helper Functions ---
    function refreshDataTable() {
        if (settingsTable) {
            settingCache.clear(); // Clear cache saat refresh
            settingsTable.ajax.reload(null, false);
            selectedSettings.length = 0;
            DataTableGlobal.updateSelectAllState();
            updateBulkDeleteButton();
        }
    }

    function confirmBulkDeleteSettings() {
        const checkedBoxes = $(".table-selectable-check:checked");
        if (checkedBoxes.length === 0) {
            alert("Please select settings to delete");
            return;
        }

        // Trigger the bulk delete modal dengan safety check
        const deleteModal = $("#deleteSelectedModal");
        if (deleteModal.length > 0) {
            deleteModal.modal("show");
        }
    }

    // --- Public API ---
    return {
        initialize,
        setupModalHandlers,
        setBulkDeleteRoute,
        refreshDataTable,
        editSetting,
        deleteSetting,
        confirmBulkDeleteSettings,

        // Expose for backward compatibility
        get table() {
            return settingsTable;
        },
        get selectedItems() {
            return selectedSettings;
        },
    };
})();

// --- Global functions for backward compatibility dengan DOM Ready Check ---
$(document).ready(function () {
    window.editSetting = function (settingId) {
        SettingsDataTable.editSetting(settingId);
    };

    window.deleteSetting = function (settingId, settingKey) {
        SettingsDataTable.deleteSetting(settingId, settingKey);
    };

    window.confirmBulkDeleteSettings = function () {
        SettingsDataTable.confirmBulkDeleteSettings();
    };
});