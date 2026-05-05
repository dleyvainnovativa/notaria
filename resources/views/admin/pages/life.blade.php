@extends('admin.memorial')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2 pb-3">
    <div>
        <h3 id="main_title" class="display">Su Vida</h3>
        <p class="text-muted mb-0">
            Administra y edita la historia de su vida
        </p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#lifeModal">
        <span class="d-md-block d-none"><i class="fas fa-edit me-2"></i> Editar</span>
        <span class="d-md-none d-block"><i class="fas fa-edit"></i></span>
    </button>
</div>
<div class="row g-4">
    <div class="col-12">
        <div class="card card-dark border border border-dark">
            <div class="card-body p-4 shimmer text-dark" id="life-entries">
                <p class="text-muted pb-0 mb-0">Cargando...</p>
            </div>
        </div>
    </div>
    <div class="col-auto text-end ms-auto">
        <a href="{{ route('admin.memorial.timeline', $memorial_slug) }}" class="">Ir a Línea de Tiempo <i class="ms-2 fas fa-chevron-right"></i></a>
    </div>

</div>

<form class="needs-validation" id="life-form" novalidate>
    <div
        class="modal fade"
        id="lifeModal"
        tabindex="-1"
        aria-labelledby="lifeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content p-2 card-dark border border-dark">

                <!-- Modal Header -->
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark mb-0 fw-bold" id="lifeModalLabel">
                        Editar Historia
                    </h5>
                    <button type="button" class="btn ms-auto" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="fas fa-xmark fa-lg text-dark"></i>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body text-dark">
                    <form id="life-form" class="needs-validation h-100" novalidate>
                        <div class="row g-4 h-100">
                            <div class="col-12 h-100">
                                <textarea class="card-dark form-control text-dark border border-dark shimmer h-100" name="life" id="life">
                        </textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>
@vite(["resources/js/admin/life.js"])

@endsection