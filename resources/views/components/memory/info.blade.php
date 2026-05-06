<div class="offcanvas offcanvas-bottom custom-offcanvas text-bg-dark border border-dark" tabindex="-1" id="infoBottomSheet" aria-labelledby="infoBottomSheetLabel">
    <div class="offcanvas-header pt-4 px-4 container">
        <!-- <h5 class="offcanvas-title" id="infoBottomSheetLabel">Offcanvas bottom</h5> -->
        <img class="appLogo" src="{{asset('img/logo.png')}}" width="160" alt="">

        <button type="button" class="btn btn-dark ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fas fa-xmark fa-lg text-dark"></i></button>

    </div>
    <div class="offcanvas-body px-0">
        <main class="">
            <div class="">
                <section class="text-bg-dark py-1 container" id="tour-bg">
                    <div class="container pb-5">
                        <div class="align-content-center my-5 pb-5" id="hero-content">
                            <div class="row g-3 text-center">
                                <div class="col-12 col-md-10 col-lg-6 mx-auto display" data-aos-delay="200">
                                    <h2 class="title">
                                        Crear Memorial
                                    </h2>
                                </div>

                                <div class="col-12 col-md-10 col-lg-7 mx-auto" data-aos-delay="200">
                                    <p class="fs-5 text-muted fw-light">
                                        Una forma sencilla y respetuosa de honrar la memoria de un ser querido
                                    </p>
                                </div>

                                <div class="col-12 col-md-10 col-lg-8 mx-auto">
                                    <div class="row g-4 justify-content-center">
                                        <div class="col-auto">
                                            <a class="btn btn-primary btn-lg rounded-pill" data-aos-delay="200" data-aos="fade-up-left" href="{{route('payment')}}">
                                                <span class="fs-6 px-3">Crear Memorial</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="text-bg-dark py-4">
                    <div class="container my-4">
                        <div class="text-center container">
                            <div class="pb-5">
                                <h1 class="text-dark display subtitle">Cómo funciona</h1>
                                <p class="text-muted fs-5">Cuatro pasos sencillos para crear un memorial digital duradero</p>
                            </div>

                            <div class="row g-4">
                                <!-- Step 1 -->
                                <div class="col-12 col-md-6 col-lg-3 position-relative" data-aos-delay="0">
                                    <div class="text-center">
                                        <div class="position-relative mb-4 d-inline-block">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle"
                                                style="width:70px;height:70px;">
                                                <i class="fa-regular fa-user fs-3 text-primary"></i>
                                            </div>
                                            <span class="position-absolute top-0 fs-6 start-100 translate-middle badge rounded-pill bg-primary">
                                                1
                                            </span>
                                        </div>
                                        <h4 class="mb-3 display">
                                            Completa la información del memorial
                                        </h4>
                                        <p class="text-muted">
                                            Ingresa los datos básicos de tu ser querido para comenzar el homenaje.
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div class="col-12 col-md-6 col-lg-3 position-relative" data-aos-delay="100">
                                    <div class="text-center">
                                        <div class="position-relative mb-4 d-inline-block">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle"
                                                style="width:70px;height:70px;">
                                                <i class="far fa-image fs-3 text-primary"></i>
                                            </div>
                                            <span class="position-absolute top-0 fs-6 start-100 translate-middle badge rounded-pill bg-primary">
                                                2
                                            </span>
                                        </div>
                                        <h4 class="mb-3 display">
                                            Sube fotos y recuerdos
                                        </h4>
                                        <p class="text-muted">
                                            Agrega fotografías, historias y momentos significativos.
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="col-12 col-md-6 col-lg-3 position-relative" data-aos-delay="200">
                                    <div class="text-center">
                                        <div class="position-relative mb-4 d-inline-block">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle"
                                                style="width:70px;height:70px;">
                                                <i class="fa-solid fa-qrcode fs-3 text-primary"></i>
                                            </div>
                                            <span class="position-absolute top-0 fs-6 start-100 translate-middle badge rounded-pill bg-primary">
                                                3
                                            </span>
                                        </div>
                                        <h4 class="mb-3 display">
                                            Recibe tu memorial con QR
                                        </h4>
                                        <p class="text-muted">
                                            Obtén un código QR único para colocar en lápidas, urnas o recuerdos.
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 4 -->
                                <div class="col-12 col-md-6 col-lg-3" data-aos-delay="300">
                                    <div class="text-center">
                                        <div class="position-relative mb-4 d-inline-block">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle"
                                                style="width:70px;height:70px;">
                                                <i class="fa-regular fa-heart fs-3 text-primary"></i>
                                            </div>
                                            <span class="position-absolute top-0 fs-6 start-100 translate-middle badge rounded-pill bg-primary">
                                                4
                                            </span>
                                        </div>
                                        <h4 class="mb-3 display">
                                            Comparte y recuerda
                                        </h4>
                                        <p class="text-muted">
                                            Familia y amigos pueden escanear para visitar, recordar y contribuir.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="text-bg-dark py-1 container" id="tour-bg">
                    <div class="container pb-5">
                        <div class="align-content-center my-5 pb-5" id="hero-content">
                            <section class="py-5 px-3">
                                <div class="container">
                                    <div class="mx-auto transition-all opacity-100" style="max-width: 720px;">
                                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-4 gap-md-5">

                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle"
                                                    style="width:40px;height:40px;">
                                                    <i class="text-primary fas fa-cancel"></i>
                                                </div>
                                                <span class="fw-medium text-dark">Sin suscripciones</span>
                                            </div>

                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle"
                                                    style="width:40px;height:40px;">
                                                    <i class="text-primary fas fa-shield"></i>
                                                </div>
                                                <span class="fw-medium text-dark">Sin anuncios</span>
                                            </div>

                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle"
                                                    style="width:40px;height:40px;">
                                                    <i class="text-primary fas fa-users"></i>
                                                </div>
                                                <span class="fw-medium text-dark">Tu familia controla el memorial</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </section>

                            <div class="row g-3 text-center">
                                <div class="col-12 col-md-12 col-lg-7 mx-auto display" data-aos-delay="200">
                                    <hr>
                                </div>

                                <div class="col-12 col-md-10 col-lg-6 mx-auto display" data-aos-delay="200">
                                    <h2 class="subtitle">
                                        ¿Listo para honrar su memoria?
                                    </h2>
                                </div>

                                <div class="col-12 col-md-10 col-lg-7 mx-auto" data-aos-delay="200">
                                    <p class="fs-5 text-muted fw-light">
                                        Crea un homenaje digital duradero que las familias atesorarán por siempre.
                                    </p>
                                </div>

                                <div class="col-12 col-md-10 col-lg-8 mx-auto">
                                    <div class="row g-4 justify-content-center">
                                        <div class="col-auto">
                                            <a class="btn btn-primary btn-lg rounded-pill" href="{{route('payment')}}">
                                                <span class="fs-6 px-3">Comenzar a crear un memorial</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const offcanvas = document.querySelector('#infoBottomSheet');
        const body = offcanvas.querySelector('#infoBottomSheet .offcanvas-body');

        const maxTop = -20; // %
        const maxSide = 4; // %
        const minTop = 0;
        const minSide = 0;

        const maxRadius = 50; // px
        const minRadius = 0;

        body.addEventListener('scroll', () => {
            const scrollTop = body.scrollTop;
            const maxScroll = 250; // tweak sensitivity

            // Clamp progress between 0 and 1
            const progress = Math.min(scrollTop / maxScroll, 1);

            // Interpolate margins
            const currentTop = maxTop + (minTop - maxTop) * progress;
            const currentSide = maxSide + (minSide - maxSide) * progress;

            // Interpolate radius
            const currentRadius = maxRadius + (minRadius - maxRadius) * progress;

            offcanvas.style.marginBottom = `${currentTop}%`;
            offcanvas.style.marginLeft = `${currentSide}%`;
            offcanvas.style.marginRight = `${currentSide}%`;

            offcanvas.style.borderTopLeftRadius = `${currentRadius}px`;
            offcanvas.style.borderTopRightRadius = `${currentRadius}px`;
        });
    });
</script>