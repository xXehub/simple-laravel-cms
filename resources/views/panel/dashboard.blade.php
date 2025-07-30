<x-layout.app title="Dashboard | Panel" :pakaiSidebar="true" :pakaiTopBar="false">

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">
                            Summary
                        </div>
                        <h2 class="page-title">
                            Dashboard Superadmin
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-12 col-md-auto ms-auto d-print-none">
                        <div class="page-pretitle">
                            Summary
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">

                    {{-- card 1 --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-muted text-uppercase small fw-bold">TOTAL USER
                                    </div>
                                    <div class="dropdown">
                                        <a class="text-muted" href="#" data-bs-toggle="dropdown">Last 7
                                            days</a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item active" href="#">Last 7 days</a>
                                            <a class="dropdown-item" href="#">Last 30 days</a>
                                            <a class="dropdown-item" href="#">Last 3 months</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="bg-primary text-white avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                        </svg>
                                    </span>
                                    <div class="h1 m-0 ms-3 d-flex align-items-center">
                                        {{ number_format($stats['total_users']) }}
                                        <div class="ms-2 text-success d-flex align-items-center"
                                            style="font-size: 15px;">
                                            {{ $growth['users'] }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16"
                                                height="16" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="3 17 9 11 13 15 21 7" />
                                                <polyline points="14 7 21 7 21 14" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex align-items-center border-top pt-3">
                                    <a href="#" class="text-muted text-decoration-none small">Lebih
                                        lengkap</a>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="ms-auto">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="9 6 15 12 9 18" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- card 2 --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-muted text-uppercase small fw-bold">TOTAL PAGES
                                    </div>
                                    <div class="dropdown">
                                        <a class="text-muted" href="#" data-bs-toggle="dropdown">Last 7
                                            days</a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item active" href="#">Last 7 days</a>
                                            <a class="dropdown-item" href="#">Last 30 days</a>
                                            <a class="dropdown-item" href="#">Last 3 months</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="bg-green text-white avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-file-text">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14,2 14,8 20,8" />
                                            <line x1="16" y1="13" x2="8" y2="13" />
                                            <line x1="16" y1="17" x2="8" y2="17" />
                                            <polyline points="10,9 9,9 8,9" />
                                        </svg>
                                    </span>
                                    <div class="h1 m-0 ms-3 d-flex align-items-center">
                                        {{ number_format($stats['total_pages']) }}
                                        <div class="ms-2 text-success d-flex align-items-center"
                                            style="font-size: 15px;">
                                            {{ $growth['pages'] }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16"
                                                height="16" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="3 17 9 11 13 15 21 7" />
                                                <polyline points="14 7 21 7 21 14" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex align-items-center border-top pt-3">
                                    <a href="#" class="text-muted text-decoration-none small">Lebih
                                        lengkap</a>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="ms-auto">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="9 6 15 12 9 18" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- card 3 --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-muted text-uppercase small fw-bold">TOTAL MENUS
                                    </div>
                                    <div class="dropdown">
                                        <a class="text-muted" href="#" data-bs-toggle="dropdown">Last 7
                                            days</a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item active" href="#">Last 7 days</a>
                                            <a class="dropdown-item" href="#">Last 30 days</a>
                                            <a class="dropdown-item" href="#">Last 3 months</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="bg-primary text-white avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-menu-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <line x1="4" y1="6" x2="20" y2="6" />
                                            <line x1="4" y1="12" x2="20" y2="12" />
                                            <line x1="4" y1="18" x2="20" y2="18" />
                                        </svg>
                                    </span>
                                    <div class="h1 m-0 ms-3 d-flex align-items-center">
                                        {{ number_format($stats['total_menus']) }}
                                        <div class="ms-2 text-success d-flex align-items-center"
                                            style="font-size: 15px;">
                                            {{ $growth['menus'] }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16"
                                                height="16" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="3 17 9 11 13 15 21 7" />
                                                <polyline points="14 7 21 7 21 14" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex align-items-center border-top pt-3">
                                    <a href="#" class="text-muted text-decoration-none small">Lebih
                                        lengkap</a>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="ms-auto">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="9 6 15 12 9 18" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- card 4 --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-muted text-uppercase small fw-bold">TOTAL ROLES
                                    </div>
                                    <div class="dropdown">
                                        <a class="text-muted" href="#" data-bs-toggle="dropdown">Last 7
                                            days</a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item active" href="#">Last 7 days</a>
                                            <a class="dropdown-item" href="#">Last 30 days</a>
                                            <a class="dropdown-item" href="#">Last 3 months</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="bg-primary text-white avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-circle-dashed-check">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95" />
                                            <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44" />
                                            <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92" />
                                            <path d="M8.56 20.31a9 9 0 0 0 3.44 .69" />
                                            <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95" />
                                            <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44" />
                                            <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92" />
                                            <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69" />
                                            <path d="M9 12l2 2l4 -4" />
                                        </svg>
                                    </span>
                                    <div class="h1 m-0 ms-3 d-flex align-items-center">
                                        {{ number_format($stats['total_roles']) }}
                                        <div class="ms-2 text-success d-flex align-items-center"
                                            style="font-size: 15px;">
                                            {{ $growth['roles'] }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16"
                                                height="16" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="3 17 9 11 13 15 21 7" />
                                                <polyline points="14 7 21 7 21 14" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex align-items-center border-top pt-3">
                                    <a href="#" class="text-muted text-decoration-none small">Lebih
                                        lengkap</a>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="ms-auto">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="9 6 15 12 9 18" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- recent users table --}}
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">User Terbaru</h3>
                            </div>
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-vcenter card-table">
                                    <thead class="sticky-top bg-white">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Bergabung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentUsers as $user)
                                            <tr>
                                                <td>{{ $user['name'] }}</td>
                                                <td class="text-secondary">{{ $user['email'] }}</td>
                                                <td>
                                                    <span class="badge bg-primary-lt">{{ $user['role'] }}</span>
                                                </td>
                                                <td class="sort-status">
                                                    @if ($user['status'] === 'Verified')
                                                        <span class="badge bg-success-lt">Verified</span>
                                                    @else
                                                        <span class="badge bg-warning-lt">Unverified</span>
                                                    @endif
                                                </td>
                                                <td class="text-secondary">{{ $user['created_at'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- system overview --}}
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">System Overview</h3>
                                <div style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Component</th>
                                                <th class="text-end">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topContent as $item)
                                                <tr>
                                                    <td>
                                                        <div class="progressbg">
                                                            <div class="progress progress-3 progressbg-progress">
                                                                <div class="progress-bar bg-primary-lt"
                                                                    style="width: {{ $item['percentage'] }}%"
                                                                    role="progressbar"
                                                                    aria-valuenow="{{ $item['percentage'] }}"
                                                                    aria-valuemin="0" aria-valuemax="100"
                                                                    aria-label="{{ $item['percentage'] }}% Complete">
                                                                    <span
                                                                        class="visually-hidden">{{ $item['percentage'] }}%
                                                                        Complete</span>
                                                                </div>
                                                            </div>
                                                            <div class="progressbg-text">{{ $item['title'] }}</div>
                                                        </div>
                                                    </td>
                                                    <td class="w-1 fw-bold text-end">
                                                        {{ $item['count'] }}/{{ $item['total'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Most Visited Menus</h3>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Page name</th>
                                            <th>Visitors</th>
                                            <th>Unique</th>
                                            <th colspan="2">Bounce rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mostVisitedPages as $index => $page)
                                        <tr>
                                            <td>
                                                /{{ $page['slug'] }}
                                                <a href="{{ url($page['slug']) }}" class="ms-1" aria-label="Open page" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                        <path d="M9 15l6 -6" />
                                                        <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                        <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                    </svg>
                                                </a>
                                            </td>
                                            <td class="text-secondary">{{ number_format($page['visitors']) }}</td>
                                            <td class="text-secondary">{{ number_format($page['unique']) }}</td>
                                            <td class="text-secondary">{{ $page['bounce_rate'] }}</td>
                                            <td class="text-end w-1">
                                                <div class="chart-sparkline chart-sparkline-sm" id="sparkline-bounce-rate-{{ $index + 1 }}"></div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


              {{-- Dynamic charts for each page --}}
              <script>
                  document.addEventListener("DOMContentLoaded", function () {
                      @foreach($mostVisitedPages as $index => $page)
                      if (window.ApexCharts && document.getElementById("sparkline-bounce-rate-{{ $index + 1 }}")) {
                          new ApexCharts(document.getElementById("sparkline-bounce-rate-{{ $index + 1 }}"), {
                              chart: {
                                  type: "line",
                                  fontFamily: "inherit",
                                  height: 24,
                                  animations: {
                                      enabled: false,
                                  },
                                  sparkline: {
                                      enabled: true,
                                  },
                              },
                              tooltip: {
                                  enabled: false,
                              },
                              stroke: {
                                  width: 2,
                                  lineCap: "round",
                              },
                              series: [
                                  {
                                      color: "var(--tblr-primary)",
                                      data: {{ json_encode($page['chart_data']) }},
                                  },
                              ],
                          }).render();
                      }
                      @endforeach
                  });
              </script>
                </div>
            </div>
        </div>


</x-layout.app>
