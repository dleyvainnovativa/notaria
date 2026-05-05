<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{env('APP_NAME')}} | En memoria de {{$memorial->deceased_name}}</title>
    <meta name="description" content="@yield('description',  env('APP_NAME').' | Recordar es seguir amando')">
    <link rel="icon" type="image/png" href="{{ asset('img/icon/favicon-96x96.png')}}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/icon/favicon.svg')}}" />
    <link rel="shortcut icon" href="{{ asset('img/icon/favicon.ico')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icon/apple-touch-icon.png')}}" />
    <meta name="apple-mobile-web-app-title" content="{{ env('APP_NAME')}}" />
    <link rel="manifest" href="{{ asset('img/icon/site.webmanifest')}}" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- Light mode -->
    <meta name="apple-mobile-web-app-status-bar-style" content="white-translucent" media="(prefers-color-scheme: light)">
    <!-- Dark mode -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" media="(prefers-color-scheme: dark)">
    <!-- <meta name="theme-color" content="#ffffff"> -->
    <!-- Light mode -->
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <!-- Dark mode -->
    <meta name="theme-color" content="#0b0b18" media="(prefers-color-scheme: dark)">
    <meta name="app-url" content="{{route('home')}}/">
    <meta name="api-url" content="{{route('api')}}/">
    <meta name="admin-url" content="{{route('admin')}}/">
    <meta property="og:image" content="{{ asset('img/og.png') }}">
    <meta property="og:title" content="@yield('title', env('APP_NAME').' | Home')">
    <meta property="og:description" content="@yield('description',  env('APP_NAME').' | Recordar es seguir amando')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{route('home')}}">
    @vite(['resources/scss/app.scss', 'resources/js/app.js','resources/css/theme.css', 'resources/css/memorial.css',
    'resources/js/memory/gallery.js', 'resources/js/memory/memorial.js', 'resources/js/memory/partners.js'
    ])
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

<body class="bg-dark h-100">

    @include('components.fonts')

    <main class="h-100 text-dark">
        @yield('content')
    </main>
    @include("components.memory.info")
    @include("components.memory.timeline")
    @include("components.memory.message")

    @include("components.alert")
    @include("components.notification")
    @include("components.popup")
</body>


</html>