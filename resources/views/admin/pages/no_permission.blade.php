@extends('admin.memorial')

@section('content')
<!-- <div class="pb-2">
    <h3 id="main_title" class="display">Dashboard</h3>
    <p id="main_subttitle" class="text-muted">Manage your account settings and preferences</p>
</div> -->

<section class="text-bg-dark pt-4 pb-5">
    <div class="container text-center pb-5">
        <div class="row g-4 py-4">
            <div class="col-lg-12 col-12 col-md-12 mx-auto">
                <div class="row g-2 text-center">
                    <div class="col-12">
                        <!-- <h3 class="feature-icon bg-success-subtle d-inline-flex align-items-center justify-content-center"> -->
                        <h2 class="">
                            <i class="far fa-circle-xmark text-muted fa-2xl"></i>
                        </h2>
                    </div>
                    <div class="col-12">
                        <h1 class="title">¡No tienes permiso!</h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12 col-md-12 mx-auto text-center">
                <div class="card card-dark border border-dark text-dark">
                    <div class="card-body p-4">
                        <h3 class="fw-bold"> Lo sentimos mucho</h3>
                        <p class=" text-muted">
                            No tienes permiso para acceder a esta página. Verifica que la dirección esté escrita correctamente o regresa al inicio para continuar navegando.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection