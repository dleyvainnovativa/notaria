<footer class="text-dark pt-5 pb-3 border-top border-dark text-bg-dark" id="footer">
    <div class="container">
        <div class="container">
            <div class="row">
                <!-- Brand -->
                <div class="col-md-4 pb-4">
                    <img class="appLogo" src="{{asset('img/logo.png')}}" width="160" alt="Memorial Digital">


                    <p class="my-2 col-md-10 pb-0 mb-1 text-muted fw-light">
                        Un espacio digital para honrar, recordar y preservar historias que viven para siempre.
                    </p>

                    <div class="row g-3">
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
                </div>

                <!-- Links -->
                <div class="col-md-4">
                    <h6 class="fw-bold text-muted">Enlaces</h6>
                    <ul class="list-unstyled">
                        <li class="nav-item">
                            <a class="text-dark" href="{{route('home')}}">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="text-dark" href="{{route('payment')}}">Crear memorial</a>
                        </li>
                        <li class="nav-item">
                            <a class="text-dark" href="{{route('privacy')}}">Aviso de Privacidad</a>
                        </li>
                    </ul>
                </div>

                <!-- Why -->
                <div class="col-md-4">
                    <h6 class="text-muted fw-bold">¿Por qué elegir {{ env('APP_NAME') }}?</h6>
                    <ul class="list-unstyled text-muted fw-light">
                        <li>Memoriales digitales privados y seguros</li>
                        <li>Acceso permanente sin suscripciones</li>
                        <li>Control total por parte de la familia</li>
                    </ul>
                </div>

                <!-- Copyright -->
                <div class="text-center mt-4 pb-2">
                    <small class="text-muted fw-lighter">
                        © 2025 {{ env('APP_NAME') }}. Honrando recuerdos, preservando historias.
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>