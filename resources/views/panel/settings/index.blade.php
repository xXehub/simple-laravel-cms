<x-layout.app title="Settings Management - Panel Admin" :pakai-sidebar="true" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-header d-print-none" aria-label="Page header">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">Panel/Settings</div>
                        <h2 class="page-title">Settings Management</h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            @can('create-settings')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createSettingModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Add Setting
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row row-cards">
                    <x-alert.modal-alert />
                    <!-- Main Table Card -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-table">
                                <x-form.datatable-header searchPlaceholder="Search settings..." :showBulkDelete="true"
                                    bulkDeletePermission="delete-settings" />

                                <div id="advanced-table">
                                    <div class="table-responsive">
                                        <table id="settingsTable">
                                            <thead>
                                                <tr>
                                                    <th class="w-1">
                                                        <input class="form-check-input m-0 align-middle" type="checkbox"
                                                            aria-label="Select all settings" id="select-all" />
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Key</th>
                                                    <th>Type</th>
                                                    <th>Group</th>
                                                    <th>Description</th>
                                                    <th>Value</th>
                                                    <th class="w-3"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-tbody">
                                                <!-- Data will be loaded via DataTables AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer d-flex align-items-center">
                                        <div class="col-auto d-flex align-items-center">
                                            <p class="m-0 text-secondary" id="record-info">Showing <strong>0 to
                                                    0</strong>
                                                of <strong>0 entries</strong></p>
                                        </div>
                                        <ul class="pagination m-0 ms-auto" id="datatable-pagination">
                                            <!-- Pagination will be filled by DataTables -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

     <!-- load komponen modal disini -->
    <x-modals.settings.create />
    <x-modals.settings.edit />
    @push('scripts')
        <!-- Settings DataTable Module -->
        <script src="{{ asset('js/datatable/settings.js') }}"></script>
        <script>
            let settingsTable;

            // Set global edit route for fallback
            window.settingEditRoute = '{{ route('panel.settings.edit', ':id') }}';
            window.settingDeleteRoute = '{{ route('panel.settings.destroy', ':id') }}';
            window.settingUpdateRoute = '{{ route('panel.settings.update', ':id') }}';

            // Initialize Settings DataTable
            SettingsDataTable.initialize('{{ route('panel.settings.datatable') }}')
                .then(table => {
                    settingsTable = table;
                    SettingsDataTable.setupModalHandlers();
                    SettingsDataTable.setBulkDeleteRoute('{{ route('panel.settings.bulkDestroy') }}');
                })
                .catch(error => {
                    console.error('Failed to initialize Settings DataTable:', error);
                });

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        SettingsDataTable.refreshDataTable();
                    }, 1000);
                });
            @endif

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.editSetting = (settingId) => SettingsDataTable.editSetting(settingId, '{{ route('panel.settings.edit', ':id') }}');
            window.deleteSetting = (settingId, settingKey) => confirmDeleteSetting(settingId, settingKey);
        </script>
    @endpush
</x-layout.app>
