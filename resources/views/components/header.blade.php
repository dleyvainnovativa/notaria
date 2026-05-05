<nav id="navbar-top" class="navbar navbar-expand-lg navbar-dark text-bg-dark fixed-top border-bottom" aria-label="Offcanvas navbar large">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between container">
            <a class="navbar-brand" href="{{route('home')}}">
                <img class="appLogo" src="{{asset('img/logo2.png')}}" width="160" alt="">
            </a>
            <a class="position-relative ms-auto me-3">
                <div class="theme-switch ">
                    <input type="checkbox" id="themeToggle">
                    <label for="themeToggle" class="switch border border-primary">
                        <span class="icon moon"><i class="fas fa-moon"></i></span>
                        <span class="icon sun"><i class="fas fa-sun"></i></span>
                        <span class="slider"></span>
                    </label>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation"><i class="fas fa-bars-staggered text-dark"></i></button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <a class="navbar-brand" href="{{route('home')}}">
                        <img class="appLogo" src="{{asset('img/logo2.png')}}" width="160" alt="">
                    </a>
                    <button type="button" class="btn btn-dark ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fas fa-xmark fa-lg text-dark"></i></button>
                </div>
                <div class="offcanvas-body ">
                    <ul class="navbar-nav justify-content-end flex-grow-1">
                        <li class="nav-item py-2 me-2">
                            <a class="btn btn-primary rounded-pill btn-sm" href="{{route('payment')}}">
                                <span class="px-3">Compra ahora</span>
                            </a>
                        </li>
                        <li class="nav-item py-2">
                            <a class="btn btn-outline-primary rounded-pill btn-sm px-4" href="{{route('login')}}">
                                <span class="">
                                    <i class="fas fa-arrow-right-to-bracket me-2"></i> Iniciar Sesión
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="offcanvas-footer">
                    <div class="d-block d-lg-none pt-4">
                        <div class="container p-4">
                            <hr>

                            <div class="row g-3 mx-auto">
                                <div class="col-auto">
                                    <a class="text-dark" href="#"><i class="fab fa-facebook fa-lg"></i></a>
                                </div>
                                <div class="col-auto">
                                    <a class="text-dark" href="#"><i class="fab fa-instagram fa-lg"></i></a>
                                </div>
                                <div class="col-auto">
                                    <a class="text-dark" href="#"><i class="fab fa-tiktok fa-lg"></i></a>
                                </div>
                            </div>
                            <div class="text-center mt-4 pb-2">
                                <small class="text-muted fw-lighter">© 2025 {{ env('APP_NAME')}}. Todos los derechos reservados. </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>