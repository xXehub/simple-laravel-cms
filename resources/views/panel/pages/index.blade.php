<x-layout.app title="Pages Management - Panel Admin" :pakaiSidebar="true" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-header d-print-none" aria-label="Page header">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">Panel/Pages</div>
                        <h2 class="page-title">Pages Management</h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            @can('create-pages')
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#uploadTemplateModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    Upload Template
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Create Page
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#createTemplatePageModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                    <polyline points="14,2 14,8 20,8" />
                                                    <line x1="16" y1="13" x2="8" y2="13" />
                                                    <line x1="16" y1="17" x2="8" y2="17" />
                                                    <polyline points="10,9 9,9 8,9" />
                                                </svg>
                                                Template-based Page
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#createBuilderPageModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <rect x="4" y="4" width="16" height="4" rx="1" />
                                                    <rect x="4" y="12" width="6" height="8" rx="1" />
                                                    <rect x="14" y="12" width="6" height="8" rx="1" />
                                                </svg>
                                                Page Builder
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <a href="{{ route('panel.pages.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Add Page
                                </a>
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
                                <x-form.datatable-header searchPlaceholder="Search pages..." :showBulkDelete="true"
                                    bulkDeletePermission="delete-pages" />
                                <div id="advanced-table">
                                    <div class="table-responsive">
                                        <table id="datatable-pages">
                                            <thead>
                                                <tr>
                                                    <th class="w-1">
                                                        <input class="form-check-input m-0 align-middle"
                                                            type="checkbox" aria-label="Select all pages"
                                                            id="select-all" />
                                                    </th>
                                                    <th>Title</th>
                                                    <th>Slug</th>
                                                    <th>Type</th>
                                                    <th>Template</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                    <th class="w-3"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-tbody">
                                                <!-- Data will be filled by DataTable AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer d-flex align-items-center">
                                        <div class="col-auto d-flex align-items-center">
                                            <p class="m-0 text-secondary" id="record-info">Showing <strong>0 to
                                                    0</strong>
                                                of
                                                <strong>0 entries</strong>
                                            </p>
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

    <!-- Include Modal Components -->
    <x-modals.pages.create-template />
    <x-modals.pages.create-builder />
        <x-modals.pages.update />
    <x-modals.pages.upload-template />


    @push('scripts')
        <!-- Pages DataTable Module -->
        <script src="{{ asset('js/datatable/pages.js') }}"></script>
        <script>
            let pagesTable;

            // Set global routes for fallback and TomSelect
            window.pageEditRoute = '{{ route('panel.pages.edit', ':id') }}';
            window.pageDeleteRoute = '{{ route('panel.pages.destroy', ':id') }}';
            window.templatesRoute = '{{ route('panel.pages.getAvailableTemplates') }}';

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Pages DataTable
                PagesDataTable.initialize('{{ route('panel.pages.datatable') }}')
                    .then(table => {
                        pagesTable = table;
                        PagesDataTable.setBulkDeleteRoute('{{ route('panel.pages.bulkDestroy') }}');
                        
                        // Setup modal handlers after DataTable is ready
                        PagesDataTable.setupModalHandlers();
                    })
                    .catch(error => {
                        console.error('Failed to initialize Pages DataTable:', error);
                    });

                // Auto-refresh on success
                @if (session('success'))
                    setTimeout(() => {
                        if (pagesTable) {
                            PagesDataTable.refreshDataTable();
                        }
                    }, 1000);
                @endif
            });

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.duplicatePage = function(pageId) {
                if (confirm('Duplicate this page?')) {
                    fetch('{{ route('panel.pages.duplicate', ':id') }}'.replace(':id', pageId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                PagesDataTable.refreshDataTable();
                                alert(data.message || 'Page duplicated successfully!');
                            } else {
                                alert('Error: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error duplicating page');
                        });
                }
            };

            window.deletePage = (pageId, pageTitle) => confirmDeletePage(pageId, pageTitle);
        </script>
    @endpush
</x-layout.app>
