    @props(['title' => null, 'isAdmin' => false, 'bodyClass' => '', 'navClass' => 'bg-dark', 'useSidebar' => null])
    <div class="page-header d-print-none">
        <div class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container-xl">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                            <a href=".">
                                <img src="{{ asset('static/logo2.webp') }}" width="110" height="32" alt="Tabler"
                                    class="navbar-brand-image" />
                            </a>
                        </h1>

                        @guest
                            <div class="navbar-nav flex-row order-md-last">
                                <div class="nav-item d-none d-md-flex me-3">
                                    <div class="btn-list">
                                        <a href="{{ route('register') }}" class="btn btn-outline-teal" rel="noreferrer">
                                            Daftar
                                        </a>
                                        <a href="{{ route('login') }}" class="btn btn-teal d-flex align-items-center"
                                            rel="noreferrer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" />
                                                <path d="M3 12h13l-3 -3" />
                                                <path d="M13 15l3 -3" />
                                            </svg>
                                            Masuk
                                        </a>
                                    </div>
                                </div>
                                <div class="nav-item d-md-none">
                                    <a href="{{ route('login') }}" class="btn btn-teal d-sm-none btn-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-login-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" />
                                            <path d="M3 12h13l-3 -3" />
                                            <path d="M13 15l3 -3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endguest
                        @auth
                            <div class="navbar-nav flex-row order-md-last">
                                <div class="nav-item d-none d-md-flex me-3">
                                    {{-- KOSONG --}}
                                </div>
                                <div class="d-none d-md-flex">
                                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                                        </svg>
                                    </a>
                                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="12" r="4" />
                                            <path
                                                d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                        </svg>
                                    </a>
                                    <div class="nav-item dropdown d-none d-md-flex me-3">
                                        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                            aria-label="Show notifications">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                                <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                            </svg>
                                            <span class="badge bg-red"></span>
                                        </a>
                                        <div
                                            class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Last updates</h3>
                                                </div>
                                                <div class="list-group list-group-flush list-group-hoverable">
                                                    <div class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto"><span
                                                                    class="status-dot status-dot-animated bg-red d-block"></span>
                                                            </div>
                                                            <div class="col text-truncate">
                                                                <a href="#" class="text-body d-block">Example 1</a>
                                                                <div class="d-block text-muted text-truncate mt-n1">Change
                                                                    deprecated
                                                                    html tags to text decoration classes (#29604)</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="#" class="list-group-item-actions">
                                                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon text-muted" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto"><span class="status-dot d-block"></span>
                                                            </div>
                                                            <div class="col text-truncate">
                                                                <a href="#" class="text-body d-block">Example 2</a>
                                                                <div class="d-block text-muted text-truncate mt-n1">
                                                                    justify-content:between ⇒ justify-content:space-between
                                                                    (#29734)
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="#" class="list-group-item-actions show">
                                                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon text-yellow" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto"><span class="status-dot d-block"></span>
                                                            </div>
                                                            <div class="col text-truncate">
                                                                <a href="#" class="text-body d-block">Example 3</a>
                                                                <div class="d-block text-muted text-truncate mt-n1">Update
                                                                    change-version.js (#29736)</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="#" class="list-group-item-actions">
                                                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon text-muted" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto"><span
                                                                    class="status-dot status-dot-animated bg-teal d-block"></span>
                                                            </div>
                                                            <div class="col text-truncate">
                                                                <a href="#" class="text-body d-block">Example 4</a>
                                                                <div class="d-block text-muted text-truncate mt-n1">
                                                                    Regenerate
                                                                    package-lock.json (#29730)</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="#" class="list-group-item-actions">
                                                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon text-muted" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0"
                                        data-bs-toggle="dropdown" aria-label="Open user menu">
                                        <span class="avatar avatar-sm"
                                            style="background-image: url(
             @if (Auth::user()->gambar_profile) {{ url('storage/' . Auth::user()->gambar_profile) }}
             @else{{ asset('static/avatars/AVATAR_SAPI.png') }} @endif
           )">
                                        </span>
                                        {{-- <span class="avatar avatar-sm"
                           style="background-image: url({{ asset('static/avatars/AVATAR_SAPI.png') }})"></span> --}}
                                        <div class="d-none d-xl-block ps-2">
                                            <div>{{ ucfirst(Auth::user()->username ?? 'Guest') }}</div>
                                            <div class="mt-1 small text-muted">{{ ucfirst(Auth::user()->type ?? 'Guest') }}
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <a href="#" class="dropdown-item">Status</a>
                                        <a href="#" class="dropdown-item">Profile</a>
                                        <a href="#" class="dropdown-item">Feedback</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="./settings.html" class="dropdown-item">Settings</a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"
                                            class="dropdown-item">Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
