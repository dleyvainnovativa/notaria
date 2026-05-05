@extends('admin.memorial')

@section('content')
<div class="pb-2">
    <h3 id="main_title" class="display">Mensajes</h3>
    <p id="main_subttitle" class="text-muted">
        Lee y conserva los mensajes y dedicatorias enviadas en su memoria
    </p>
</div>
<div class="row g-4 grid-messages">
    <div class="col-12 h-100">
        <div class="card card-dark border border-dark bg-dark text-dark shimmer">
            <div class="card-body p-4">
                <p class="text-muted mb-0">
                    Cargando
                </p>
            </div>
        </div>
    </div>
    <!-- <div class="col-12 col-md-6">
        <div class="card card-dark border border-dark shadow-sm">
            <div class="card-body p-4 text-dark">
                <div class="row g-3">
                    <div class="col-12">
                        <span class="badge text-bg-success">Aprobada</span>
                    </div>
                    <div class="col-12">
                        <p class="mb-0">"Abuelita, your pozole recipe will live on in our kitchen forever. I make it every Sunday and think of you."</p>
                    </div>
                    <div class="col-auto">
                        <span class="text-muted fw-bold">Ana María</span>
                    </div>
                    <div class="col-auto ms-auto">
                        <span class="text-muted fw-light">January 2024</span>
                    </div>
                    <div class="col-12 ms-auto text-end">
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <button type="button" class="btn btn-danger">Rechazar</button>
                            <button type="button" class="btn btn-success">Aprobar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>

@vite(["resources/js/admin/tributes.js"])

@endsection