@extends('admin.memorial')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2 pb-3">
    <div>
        <h3 id="main_title" class="display">Línea de Tiempo</h3>
        <p class="text-muted mb-0">
            Organiza y presenta los momentos más importantes de su vida
        </p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#accountModal">
        <span class="d-md-block d-none"><i class="fas fa-add me-2"></i> Agregar</span>
        <span class="d-md-none d-block"><i class="fas fa-add"></i></span>
    </button>
</div>
<div class="row g-4">
    <div class="col-12">

        <div class="row g-4" id="timeline_list">
            <div class="col-12 h-100">
                <div class="card card-dark border border-dark bg-dark text-dark shimmer">
                    <div class="card-body p-4">
                        <p class="text-muted mb-0">
                            Cargando
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="col-12 h-100">
        <div class="card card-dark border border-dark bg-dark h-100 text-dark">
            <div class="card-body  px-4 pb-0">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="flex-grow-1">
                        <span class="text-muted small fw-medium">1952</span>
                        <h5 class="fw-semibold mb-1">The Beginning</h5>
                    </div>

                    <div class="d-flex gap-1">
                        <button
                            class="btn btn-outline-secondary"
                            aria-label="Edit The Beginning">
                            <i class="fa-solid fa-edit text-dark"></i>
                        </button>

                        <button
                            class="btn btn-sm btn-outline-secondary"
                            aria-label="Remove The Beginning">
                            <i class="fa-solid fa-trash text-dark"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <p class="text-muted mb-0">
                    It all started in a small town where dreams were as wide as the sky.
                    The foundation was laid for something that would grow beyond anyone's
                    imagination, a legacy built on hard work and unwavering hope.
                </p>
            </div>
        </div>
    </div> -->
    </div>
    <div class="col-md-12 col-12 text-end ms-auto">
        <a href="{{ route('admin.memorial.gallery', $memorial_slug) }}" class="">Ir a Galería <i class="ms-2 fas fa-chevron-right"></i></a>
    </div>
</div>
<form id="timeline-form" class="needs-validation" novalidate>
    <div
        class="modal fade"
        id="accountModal"
        tabindex="-1"
        aria-labelledby="accountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content p-2 card-dark border border-dark">

                <!-- Modal Header -->
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark mb-0 fw-bold" id="accountModalLabel">
                        Agregar nuevo evento
                    </h5>
                    <button type="button" class="btn ms-auto" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="fas fa-xmark fa-lg text-dark"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body text-dark">

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label">Título</label>
                            <input
                                type="text"
                                name="title"
                                class="form-control card-dark text-dark border border-dark"
                                placeholder="Un título significativo para este momento"
                                required>
                            <div class="invalid-feedback">
                                El título es obligatorio.
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Año o fecha</label>
                            <input
                                type="date"
                                name="event_date"
                                class="form-control card-dark text-dark border border-dark"
                                placeholder="Ej. 1995 o 15 de marzo de 2020"
                                required>
                            <div class="invalid-feedback">
                                La fecha es obligatoria.
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Historia</label>
                            <textarea
                                name="description"
                                rows="4"
                                required
                                class="form-control card-dark text-dark border border-dark"
                                placeholder="Cuenta la historia de este momento…"></textarea>
                        </div>

                        <!-- Actions -->


                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button
                        type="button"
                        class="btn btn-outline-primary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Agregar evento
                    </button>
                </div>
            </div>
        </div>
</form>

@vite(["resources/js/admin/timeline.js"])

@endsection