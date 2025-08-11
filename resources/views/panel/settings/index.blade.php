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
                            <!-- Tabs Navigation -->
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-all" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">Semua Settings</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-general" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">General</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-branding" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Branding</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-seo" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">SEO</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-contact" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Contact</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-social" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Social</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-feature" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Feature</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-landing" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Landing</a>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- All Settings Tab -->
                                <div class="tab-pane active show" id="tabs-all" role="tabpanel">
                                    <div class="card-table" id="main-table-container">
                                        <x-form.datatable-header searchPlaceholder="Search settings..." :showBulkDelete="true"
                                            bulkDeletePermission="delete-settings">
                                        </x-form.datatable-header>
                                        <div id="advanced-table">
                                            <div class="table-responsive">
                                                <table id="settingsTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">
                                                                <input class="form-check-input m-0 align-middle" type="checkbox"
                                                                    aria-label="Select all settings" id="select-all" />
                                                            </th>
                                                            <th class="text-muted" style="width: 60px;">ID</th>
                                                            <th>Setting Key</th>
                                                            <th class="text-center" style="width: 100px;">Type</th>
                                                            <th class="text-center" style="width: 100px;">Group</th>
                                                            <th style="width: 200px;">Description</th>
                                                            <th style="width: 250px;">Value</th>
                                                            <th class="w-3 text-center">Actions</th>
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
                                
                                <!-- General Tab -->
                                <div class="tab-pane" id="tabs-general" role="tabpanel">
                                    <div class="card-table">
                                        <div id="general-table-placeholder"></div>
                                    </div>
                                </div>
                                
                                <!-- Branding Tab -->
                                <div class="tab-pane" id="tabs-branding" role="tabpanel">
                                    <div class="card-table">
                                        <div id="branding-table-placeholder"></div>
                                    </div>
                                </div>
                                
                                <!-- SEO Tab -->
                                <div class="tab-pane" id="tabs-seo" role="tabpanel">
                                    <div class="card-table">
                                        <div id="seo-table-placeholder"></div>
                                    </div>
                                </div>
                                
                                <!-- Contact Tab -->
                                <div class="tab-pane" id="tabs-contact" role="tabpanel">
                                    <div class="card-table">
                                        <div id="contact-table-placeholder"></div>
                                    </div>
                                </div>
                                
                                <!-- Social Tab -->
                                <div class="tab-pane" id="tabs-social" role="tabpanel">
                                    <div class="card-table">
                                        <div id="social-table-placeholder"></div>
                                    </div>
                                </div>
                                
                                <!-- Feature Tab -->
                                <div class="tab-pane" id="tabs-feature" role="tabpanel">
                                    <div class="card-table">
                                        <div id="feature-table-placeholder"></div>
                                    </div>
                                </div>
                                
                                <!-- Landing Tab -->
                                <div class="tab-pane" id="tabs-landing" role="tabpanel">
                                    <div class="card-table">
                                        <div id="landing-table-placeholder"></div>
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
                    
                    // Setup tab filtering
                    setupTabFiltering(table);
                })
                .catch(error => {
                    console.error('Failed to initialize Settings DataTable:', error);
                });

            // Setup tab filtering function
            function setupTabFiltering(table) {
                const mainTableContainer = document.getElementById('main-table-container');
                
                // Handle tab clicks
                document.querySelectorAll('.nav-tabs a[data-bs-toggle="tab"]').forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function (e) {
                        const target = e.target.getAttribute('href');
                        const group = target.replace('#tabs-', '');
                        
                        // Move table to active tab
                        if (group === 'all') {
                            // Move table back to all tab
                            const allTabPane = document.getElementById('tabs-all');
                            if (allTabPane && mainTableContainer && !allTabPane.contains(mainTableContainer)) {
                                allTabPane.appendChild(mainTableContainer);
                            }
                            // Show all settings
                            table.column(4).search('').draw(); // Column 4 is Group column
                        } else {
                            // Move table to specific tab placeholder
                            const targetPlaceholder = document.getElementById(group + '-table-placeholder');
                            if (targetPlaceholder && mainTableContainer) {
                                targetPlaceholder.appendChild(mainTableContainer);
                            }
                            // Filter by group
                            table.column(4).search('^' + group + '$', true, false).draw();
                        }
                        
                        // Ensure table is visible and properly sized
                        setTimeout(() => {
                            table.columns.adjust().responsive.recalc();
                        }, 100);
                    });
                });
            }

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        SettingsDataTable.refreshDataTable();
                    }, 1000);
                });
            @endif

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.editSetting = (settingId) => SettingsDataTable.editSetting(settingId,
                '{{ route('panel.settings.edit', ':id') }}');
            window.deleteSetting = (settingId, settingKey) => confirmDeleteSetting(settingId, settingKey);
        </script>
    @endpush
</x-layout.app>
