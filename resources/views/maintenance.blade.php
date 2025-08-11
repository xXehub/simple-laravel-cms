<x-layout.app title="{{ setting('welcome_title', 'Laravel Superapp CMS') }}" :pakai-sidebar="false" :pakaiFluid="false"
    :pakaiTopBar="false" :hasDivPage="true" bodyClass="body-marketing body-gradient">

    <body class="border-top-wide border-primary">
        <!-- END GLOBAL THEME SCRIPT -->
        <div class="page page-center">
            <div class="container-tight py-4">
                <div class="empty">

                    <div class="empty-img">
                        <img src="{{ setting_image('logo_app', 'static/logo-surabaya-hebat.png') }}"
                            alt="{{ setting('app_name', 'Aplikasi') }}">
                    </div>
                    <p class="empty-title">Sedang dalam perbaikan</p>
                    <p class="empty-subtitle text-secondary">
                        Sistem akan kembali normal secepatnya.<br>
                        Jika Anda adalah administrator, silakan login untuk mengakses panel admin.
                    </p>
                    <div class="empty-action">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-4">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/arrow-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-2">
                                <path d="M5 12l14 0" />
                                <path d="M5 12l6 6" />
                                <path d="M5 12l6 -6" />
                            </svg>
                            Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-layout.app>
