<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', env('APP_NAME').' | Panel')</title>
    <meta name="description" content="@yield('description',  env('APP_NAME').' | Editar Memorial')">
    <link rel="icon" type="image/png" href="{{ asset('img/icon/favicon-96x96.png')}}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/icon/favicon.svg')}}" />
    <link rel="shortcut icon" href="{{ asset('img/icon/favicon.ico')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icon/apple-touch-icon.png')}}" />
    <meta name="apple-mobile-web-app-title" content="{{ env('APP_NAME')}}" />
    <link rel="manifest" href="{{ asset('img/icon/site.webmanifest')}}" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white-translucent" media="(prefers-color-scheme: light)">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0b0b18" media="(prefers-color-scheme: dark)">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{route('home')}}/">
    <meta name="api-url" content="{{route('api')}}/">
    <meta name="admin-url" content="{{route('admin')}}/">
    <meta property="og:image" content="{{ asset('img/og.png') }}">
    <meta property="og:title" content="@yield('title', env('APP_NAME').' | Home')">
    <meta property="og:description" content="@yield('description',  env('APP_NAME').' | Editar Memorial')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{route('home')}}">

    <!-- Vite Assets -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/admin/app.js', 'resources/css/admin/theme.css'])


    <!-- Font Awesome (your preload is fine) -->
    <script preload src="https://kit.fontawesome.com/d544c5e79c.js" crossorigin="anonymous"></script>
    <script>
        (function() {
            try {
                const theme = localStorage.getItem("theme");
                if (theme === "light") {
                    document.documentElement.classList.add("theme-light");
                }
            } catch (e) {}
        })();
    </script>
</head>

<body class="bg-dark">
    <input type="hidden" id="memorial_slug" value="{{$memorial_slug}}">
    <div id="wrapper" class="text-bg-dark ">
        <div id="sidebar-overlay" class="d-none"></div>
        @include('admin.components.sidebar')
        <div id="page-content-wrapper" class="bg-dark">
            @include('admin.components.header')
            <main class="container-fluid p-4 bg-dark mb-5">
                @yield('content')

            </main>

        </div>
    </div>
    <script src="{{ asset('js/sw-register.js') }}"></script>
    @include("components.alert")
    @include("components.notification")
    @include("components.popup")
    <script>
        let tableOptions = {
            // 1. Custom Loading Template
            loadingTemplate: function(message) {
                return `
                    <div class="d-flex flex-column align-items-center justify-content-center p-5">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <span class="text-muted">Cargando, por favor espere...</span>
                    </div>
                `;
            },

            // 2. Custom Icons using Font Awesome
            iconsPrefix: 'fa', // Tell Bootstrap Table to use the 'fa' class prefix
            icons: {
                paginationSwitchDown: 'fa-caret-square-down',
                paginationSwitchUp: 'fa-caret-square-up',
                refresh: 'fa-sync-alt',
                toggleOff: 'fa-toggle-off',
                toggleOn: 'fa-toggle-on',
                columns: 'fa-th-list',
                detailOpen: 'fa-plus',
                detailClose: 'fa-minus',
                fullscreen: 'fa-expand-arrows-alt',
                search: 'fa-search',
                clearSearch: 'fa-trash'
            }
        };

        window.tableOptions = tableOptions;
    </script>
</body>

</html>