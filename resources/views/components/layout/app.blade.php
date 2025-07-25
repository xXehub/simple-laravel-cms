@props(['title' => 'KantorKu SuperApp', 'pakaiSidebar' => false, 'pakaiTopBar' => null])

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'KantorKu SuperApp' }} - Pemerintah Kota Surabaya</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Meta Tags for SEO -->
    <meta name="description" content="Sistem Informasi Terintegrasi Pemerintah Kota Surabaya">
    <meta name="keywords" content="KantorKu, SuperApp, Surabaya, Pemerintah, Sistem Informasi">
    <meta name="author" content="Pemerintah Kota Surabaya">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- tabler icons cdn --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    <!-- Page Level Styles -->
    <link href="{{ asset('libs/jsvectormap/dist/jsvectormap.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('libs/tom-select/dist/css/tom-select.bootstrap5.min.css?1744816592') }}" rel="stylesheet" />
    <link href="{{ asset('libs/nouislider/dist/nouislider.min.css?1744816592') }}" rel="stylesheet" />

    <!-- Tabler CSS Framework -->
    <link href="{{ asset('dist/css/tabler.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-themes.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-socials.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-marketing.css') }}" rel="stylesheet" />

    <!-- Demo Styles -->
    <link href="{{ asset('preview/css/demo.css?1744816593') }}" rel="stylesheet" />

    <!-- Custom Font -->
    <style>
        @import url("https://rsms.me/inter/inter.css");
    </style>

    <!-- Laravel Mix Assets -->
    @vite(['resources/js/app.js'])

</head>

@php
    $authRoutes = [
        'login',
        'register',
        'password.request',
        'password.reset',
        'password.confirm',
        'verification.notice',
        'verification.verify',
    ];

    // If pakaiTopBar is explicitly set, use it; otherwise auto-detect based on auth routes
    $showTopBar = $pakaiTopBar !== null ? $pakaiTopBar : !in_array(Route::currentRouteName(), $authRoutes);
@endphp

<body class="layout-fluid">
    <script src="{{ asset('dist/js/tabler-theme.min.js?1744816593') }}"></script>

    <div class="page">
        <x-layout.tema-builder />

        @includeWhen($pakaiSidebar, 'components.layout.sidebar')
        @includeWhen($showTopBar, 'components.layout.top-bar')

        <div class="page-wrapper">
            {{-- <x-layout.flash-messages /> --}}
            {{ $slot }}
        </div>

        @includeWhen($showTopBar, 'components.layout.footer')
    </div>
    {{-- untuk setting tema --}}
    <x-layout.tema-builder />

    <!-- Profile & Settings Modals -->
    <x-modals.profile />
    <x-modals.settings />

    <!-- Core JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('dist/js/tabler.min.js') }}"></script>
    <script src="{{ asset('js/modal-alert.js') }}"></script>

    <!-- DataTable Global Components -->
    <script src="{{ asset('js/datatable-global.js') }}"></script>

    <!-- Page Level Scripts -->
    <script src="{{ asset('libs/apexcharts/dist/apexcharts.min.js?1744816593') }}" defer></script>
    <script src="{{ asset('libs/jsvectormap/dist/jsvectormap.min.js?1667333929') }}" defer></script>
    <script src="{{ asset('libs/jsvectormap/dist/maps/world.js?1667333929') }}" defer></script>
    <script src="{{ asset('libs/jsvectormap/dist/maps/world-merc.js?1667333929') }}" defer></script>
    <script src="{{ asset('libs/nouislider/dist/nouislider.min.js?1667333929') }}" defer></script>
    <script src="{{ asset('libs/litepicker/dist/litepicker.js?1667333929') }}" defer></script>
    <script src="{{ asset('libs/tom-select/dist/js/tom-select.base.min.js?1667333929') }}" defer></script>
    <script src="{{ asset('preview/js/demo.min.js?1667333929') }}"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <!-- Third Party Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>

</html>
