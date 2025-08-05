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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createPageModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon me-1">
                                        <line x1="12" y1="5" x2="12" y2="19">
                                        </line>
                                        <line x1="5" y1="12" x2="19" y2="12">
                                        </line>
                                    </svg>
                                    Add Page
                                </button>
                            @else
                                <a href="{{ route('panel.pages.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon me-1">
                                        <line x1="12" y1="5" x2="12" y2="19">
                                        </line>
                                        <line x1="5" y1="12" x2="19" y2="12">
                                        </line>
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
                                <x-form.datatable-header
                                    searchPlaceholder="Search pages..."
                                    :showBulkDelete="true"
                                    bulkDeletePermission="delete-pages" />
                                <div id="advanced-table">
                                    <div class="table-responsive">
                                        <table id="datatable-pages">
                                            <thead>
                                                <tr>
                                                    <th class="w-1">
                                                        <input class="form-check-input m-0 align-middle" type="checkbox"
                                                            aria-label="Select all pages" id="select-all" />
                                                    </th>
                                                    <th>Title</th>
                                                    <th>Slug</th>
                                                    <th>Status</th>
                                                    <th>Template</th>
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
                                            <p class="m-0 text-secondary" id="record-info">Showing <strong>0 to 0</strong>
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

    {{-- Page Modals --}}
    @can('create-pages')
        <x-modals.pages.create />
    @endcan
    @can('update-pages')
        <x-modals.pages.edit />
    @endcan

    @push('scripts')
        <!-- Pages DataTable Module -->
        <script src="{{ asset('js/datatable/pages.js') }}"></script>
        <script>
            let pagesTable;

            // Set global routes
            window.pageEditRoute = '{{ route('panel.pages.edit', ':id') }}';
            window.pageDeleteRoute = '{{ route('panel.pages.destroy', ':id') }}';
            window.pageViewRoute = '{{ route('dynamic.page', ':slug') }}'; // Use dynamic route for page viewing

            // Initialize Pages DataTable
            PagesDataTable.initialize('{{ route('panel.pages') }}')
                .then(table => {
                    pagesTable = table;
                    PagesDataTable.setupModalHandlers();
                    PagesDataTable.setBulkDeleteRoute('{{ route('panel.pages.bulkDestroy') }}');
                })
                .catch(error => {
                    console.error('Failed to initialize Pages DataTable:', error);
                });

            // Auto-refresh on success
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        PagesDataTable.refreshDataTable();
                    }, 1000);
                });
            @endif

            // Backward compatibility (optional, can be removed if not used elsewhere)
            window.editPage = (pageId) => PagesDataTable.editPage(pageId, '{{ route('panel.pages.edit', ':id') }}');
            window.deletePage = (pageId, pageTitle) => PagesDataTable.deletePage(pageId, pageTitle);
            window.viewPage = (pageSlug) => PagesDataTable.viewPage(pageSlug);
        </script>
    @endpush
</x-layout.app>

