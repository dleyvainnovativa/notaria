@extends('action')

@section('content')

<section class="text-bg-dark align-content-center h-100 align-self-center align-items-center">
    <div class="container text-center pb-5">
        <div class="row g-4 py-4">
            <div class="col-lg-12 col-12 col-md-12 mx-auto">
                <div class="row g-2 text-center">
                    <div class="col-12">
                        <h2>
                            <i class="fas fa-arrow-right-from-bracket text-dark fa-2xl"></i>
                        </h2>
                    </div>
                    <div class="col-12">
                        <h1 class="title">Sesión cerrada</h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12 col-md-12 mx-auto text-center">
                <div class="card card-dark border border-dark text-dark">
                    <div class="card-body p-4">
                        <h3 class="fw-bold">Has salido de tu cuenta correctamente</h3>
                        <p class="text-muted">
                            Tu sesión se ha cerrado de forma segura.
                            Si necesitas volver a acceder, solo inicia sesión nuevamente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 col-12 mx-auto">
                <div class="row g-2">
                    <div class="col-lg-6 col-12 col-md-6 mx-auto text-start">
                        <a href="{{ route('login') }}" class="btn btn-primary mt-3 w-100 btn-lg">
                            Iniciar sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@vite(["resources/js/logout.js"])

@endsection