<x-layout.app title="Dashboard" :use-fluid="false">
    {{-- Page Header --}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Dashboard</h2>
                    <div class="page-subtitle">Welcome back, {{ Auth::user()->name }}!</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Body --}}
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Welcome!</div>
                            </div>
                            <div class="h1 mb-3">{{ Auth::user()->name }}</div>
                            <div class="d-flex mb-2">
                                <div class="flex-fill">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">100% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-2">
                                    <span class="text-muted">User</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Your Role</div>
                            </div>
                            <div class="h1 mb-3">{{ Auth::user()->roles->first()->name ?? 'No Role' }}</div>
                            <div class="d-flex mb-2">
                                <div class="flex-fill">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">75% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-2">
                                    <span class="text-muted">Role</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Permissions</div>
                            </div>
                            <div class="h1 mb-3">{{ Auth::user()->getAllPermissions()->count() }}</div>
                            <div class="d-flex mb-2">
                                <div class="flex-fill">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-info" style="width: 50%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">50% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-2">
                                    <span class="text-muted">permissions</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Menu Access</div>
                            </div>
                            <div class="h1 mb-3">{{ isset($userMenus) ? $userMenus->count() : 0 }}</div>
                            <div class="d-flex mb-2">
                                <div class="flex-fill">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-warning" style="width: 25%" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">25% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-2">
                                    <span class="text-muted">menus</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Your Permissions</h3>
                        </div>
                        <div class="card-body">
                            @if (Auth::user()->getAllPermissions()->count() > 0)
                                <div class="row">
                                    @foreach (Auth::user()->getAllPermissions() as $permission)
                                        <div class="col-md-4 col-lg-3 mb-2">
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="status-dot d-block bg-success"></span>
                                                    </div>
                                                    <div class="col text-truncate">
                                                        <span class="text-body d-block">{{ $permission->name }}</span>
                                                        <div class="d-block text-muted text-truncate mt-n1">Permission granted</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                            <path d="M9 10l.01 0"/>
                                            <path d="M15 10l.01 0"/>
                                            <path d="M9.5 15a3.5 3.5 0 0 0 5 0"/>
                                        </svg>
                                    </div>
                                    <p class="empty-title">No permissions assigned</p>
                                    <p class="empty-subtitle text-muted">
                                        Contact your administrator to assign permissions to your account.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
