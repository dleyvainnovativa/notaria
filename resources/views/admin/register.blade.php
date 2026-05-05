<!DOCTYPE html>
<html class="bg-dark h-100" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', env('APP_NAME').' | Panel')</title>
    <meta name="description" content="@yield('description',  env('APP_NAME').' | Registro de Usuario')">
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
    <meta property="og:description" content="@yield('description',  env('APP_NAME').' | Registro de Usuario')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{route('home')}}">

    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/css/admin/theme.css','resources/js/register.js',
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
</head>

<body class="card-dark h-100">
    <div class="container h-100">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-xl-4 col-lg-6 col-md-7 col-12 mx-auto my-auto">
                    <div class="text-center  mb-2">
                        <img src="{{asset('img/icon.png')}}" width="140" alt="">
                    </div>
                    <div class="text-center mb-4 d-none d-lg-block">
                        <h2 class="fw-bold mb-2 text-dark">
                            Crear nueva cuenta
                        </h2>
                        <p class="text-muted">
                            Crea tu cuenta para acceder a tus memorias digitales
                        </p>
                    </div>
                    <div class="text-center mb-4 d-lg-none">
                        <small class="text-muted">
                            Crea tu cuenta para acceder a tus memorias digitales
                        </small>
                    </div>
                    <form action="#" method="POST" id="register-form" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold small text-dark">
                                Nombre
                            </label>
                            <input type="text" class="text-dark form-control card-dark border border-dark" id="name" name="name" placeholder="Tu nombre" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold small text-dark">
                                Correo electrónico
                            </label>
                            <input type="email" class="text-dark form-control card-dark border border-dark" id="email" name="email" placeholder="correo@ejemplo.com" required>
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label fw-semibold small text-dark">
                                Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" class="text-dark form-control card-dark border border-dark" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                                <span class="input-group-text text-bg-dark border border-dark" style="cursor: pointer;">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="confirm_password" class="form-label fw-semibold small text-dark">
                                Confirmar contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" class="text-dark form-control card-dark border border-dark" id="confirm_password" name="confirm_password" placeholder="Ingresa tu contraseña" required>
                                <span class="input-group-text text-bg-dark border border-dark" style="cursor: pointer;">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div id="error-message" class="alert alert-danger mb-0" style="display: none;"></div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                Crear nueva cuenta
                            </button>
                        </div>

                        <div class="col-12 text-center">
                            <a href="{{route('login')}}" class="text-decoration-none text-muted text-center">
                                ¿Ya tienes cuenta? Inicia sesión
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed-top">
        <div class="p-3 float-end">
            <a class=" position-relative">
                <div class="theme-switch ">
                    <input type="checkbox" id="themeToggle">
                    <label for="themeToggle" class="switch border border-primary">
                        <span class="icon moon"><i class="fas fa-moon"></i></span>
                        <span class="icon sun"><i class="fas fa-sun"></i></span>
                        <span class="slider"></span>
                    </label>
                </div>
            </a>
        </div>

    </div>
</body>

</html>