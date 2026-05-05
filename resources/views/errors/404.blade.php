@extends('main')

@section('content')

<section class="text-bg-dark pt-4 pb-5">
    <div class="container text-center pb-5">
        <div class="row g-4 py-4">
            <div class="col-lg-12 col-12 col-md-12 mx-auto">
                <div class="row g-2 text-center">
                    <div class="col-12">
                        <!-- <h3 class="feature-icon bg-success-subtle d-inline-flex align-items-center justify-content-center"> -->
                        <h2 class="">
                            <i class="far fa-question-circle text-muted fa-2xl"></i>
                        </h2>
                    </div>
                    <div class="col-12">
                        <h1 class="title">¡Página no encontrada!</h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12 col-md-12 mx-auto text-center">
                <div class="card card-dark border border-dark text-dark">
                    <div class="card-body p-4">
                        <h3 class="fw-bold"> Lo sentimos mucho</h3>
                        <p class=" text-muted">
                            La página que estás buscando no existe o fue movida. Verifica que la dirección esté escrita correctamente o regresa al inicio para continuar navegando.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 col-12 mx-auto">
                <div class="row g-2">
                    <div class="col-lg-6 col-12 col-md-6 mx-auto text-start">
                        <a class="btn btn-primary w-100 btn-lg" href="{{route('login')}}">
                            <i class=" fas fa-arrow-right-to-bracket me-2"></i>Inicia Sesión
                        </a>
                    </div>
                    <div class="col-lg-6 col-12 col-md-6 mx-auto text-start">
                        <a class="btn btn-outline-primary w-100 btn-lg" href="{{route('home')}}">
                            Regresar a Home
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection