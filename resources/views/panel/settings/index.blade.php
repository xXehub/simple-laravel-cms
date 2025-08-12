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
                                        <a href="#tabs-all" class="nav-link active" data-bs-toggle="tab"
                                            aria-selected="true" role="tab">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon me-2">
                                                <path
                                                    d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                            </svg>
                                            </svg> Semua Settings</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-general" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" role="tab" tabindex="-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon me-2">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1 -7 0a5 5 0 0 0 -7 0v-9z" />
                                                <path d="M5 21v-7" />
                                            </svg>
                                            </svg>
                                            General</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-branding" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" role="tab" tabindex="-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                <path d="M10 16.5l2 -3l2 3m-2 -3v-2l3 -1m-6 0l3 1" />
                                                <circle cx="12" cy="7.5" r=".5" fill="currentColor" />
                                            </svg>
                                            </svg>
                                            Branding</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-seo" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" role="tab" tabindex="-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M11 18.004h-4.343c-2.572 -.004 -4.657 -2.011 -4.657 -4.487c0 -2.475 2.085 -4.482 4.657 -4.482c.393 -1.762 1.794 -3.2 3.675 -3.773c1.88 -.572 3.956 -.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M20.2 20.2l1.8 1.8" /></svg>
                                            </svg>
                                            SEO</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-contact" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" role="tab" tabindex="-1">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                               <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>Contact</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-social" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" role="tab" tabindex="-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                               <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 10.663c0 -4.224 -4.041 -7.663 -9 -7.663s-9 3.439 -9 7.663c0 3.783 3.201 6.958 7.527 7.56c1.053 .239 .932 .644 .696 2.133c-.039 .238 -.184 .932 .777 .512c.96 -.42 5.18 -3.201 7.073 -5.48c1.304 -1.504 1.927 -3.029 1.927 -4.715v-.01z" /></svg>Social</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-feature" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" role="tab" tabindex="-1">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                               <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 17h-6a1 1 0 0 1 -1 -1v-12a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v7.5" /><path d="M3 13h10" /><path d="M8 21h3" /><path d="M10 17l-.5 4" /><path d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" /></svg>
                                            Feature</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-landing" class="nav-link" data-bs-toggle="tab"
                                            aria-selected="false" role="tab" tabindex="-1">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                               <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15.157 11.81l4.83 1.295a2 2 0 1 1 -1.036 3.863l-14.489 -3.882l-1.345 -6.572l2.898 .776l1.414 2.45l2.898 .776l-.12 -7.279l2.898 .777l2.052 7.797z" /><path d="M3 21h18" /></svg>
                                               Landing</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- All Settings Tab -->
                                <div class="tab-pane active show" id="tabs-all" role="tabpanel">
                                    <div class="card-table" id="main-table-container">
                                        <x-form.datatable-header searchPlaceholder="Search settings..."
                                            :showBulkDelete="true" bulkDeletePermission="delete-settings">
                                        </x-form.datatable-header>
                                        <div id="advanced-table">
                                            <div class="table-responsive">
                                                <table id="settingsTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">
                                                                <input class="form-check-input m-0 align-middle"
                                                                    type="checkbox" aria-label="Select all settings"
                                                                    id="select-all" />
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
                                                    <p class="m-0 text-secondary" id="record-info">Showing <strong>0
                                                            to
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
                    tab.addEventListener('shown.bs.tab', function(e) {
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
