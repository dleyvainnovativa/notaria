@extends('admin.memorial')

@section('content')
<!-- <div class="d-flex justify-content-between align-items-center mb-2 pb-3">
    <div>
        <h3 id="main_title" class="display">Galería</h3>
        <p class="text-muted mb-0">
            Conserva y comparte fotografías que guardan sus recuerdos
        </p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#accountModal">
        <span class="d-md-block d-none"><i class="fas fa-add me-2"></i> Agregar</span>
        <span class="d-md-none d-block"><i class="fas fa-add"></i></span>
    </button>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#accountModal">
        <span class="d-md-block d-none"><i class="fas fa-sort me-2"></i> Ordenar</span>
        <span class="d-md-none d-block"><i class="fas fa-sort"></i></span>
    </button>
</div> -->

<div class="d-flex justify-content-between align-items-center mb-2 pb-3">
    <div>
        <h3 id="main_title" class="display">Galería</h3>
        <p class="text-muted mb-0">
            Conserva y comparte fotografías que guardan sus recuerdos
        </p>
    </div>
    <div class="ms-auto">
        <button class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#accountModal">
            <span class="d-md-block d-none"><i class="fas fa-add me-2"></i> Agregar</span>
            <span class="d-md-none d-block"><i class="fas fa-add"></i></span>
        </button>
    </div>
    <div class="">
        <button class="btn btn-primary m-1" data-bs-toggle="offcanvas" data-bs-target="#orderOffcanvas">
            <span class="d-md-block d-none"><i class="fas fa-sort me-2"></i> Ordenar</span>
            <span class="d-md-none d-block"><i class="fas fa-sort"></i></span>
        </button>
    </div>
</div>
<div class="row g-4">
    <div class="col-12 h-100">
        <div class="row g-4 grid-gallery">
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
    </div>
    <div class="col-md-12 col-12 text-end ms-auto">
        <a href="{{ route('admin.memorial.messages', $memorial_slug) }}" class="">Ir a Mensajes <i class="ms-2 fas fa-chevron-right"></i></a>
    </div>
</div>

<div class="offcanvas offcanvas-end card-dark border border-dark text-dark" tabindex="-1" id="orderOffcanvas" aria-labelledby="orderOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="orderOffcanvasLabel">Ordenar galería</h5>
        <button type="button" class="btn ms-auto" data-bs-dismiss="offcanvas" aria-label="Cerrar">
            <i class="fas fa-xmark fa-lg text-dark"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="grid-gallery-order">

        </div>
    </div>
    <div class="offcanvas-footer p-3 border-top border-dark text-end">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="offcanvas">Cancelar</button>
        <a id="saveOrderBtn" type="button" class="btn btn-primary">Guardar orden</a>
    </div>
</div>

<div
    class="modal fade"
    id="accountModal"
    tabindex="-1"
    aria-labelledby="accountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2 card-dark border border-dark">

            <!-- Modal Header -->
            <div class="modal-header border-0">
                <h5 class="modal-title text-dark mb-0 fw-bold" id="accountModalLabel">
                    Agregar nuevas imágenes
                </h5>
                <button type="button" class="btn ms-auto" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="fas fa-xmark fa-lg text-dark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-dark">
                <form id="gallery-form" class="row g-4 needs-validation" novalidate>
                    <div class="col-12">
                        <label class="form-label">Imágenes</label>

                        <div
                            id="dropzone"
                            class="border border-dark rounded px-4 py-5 text-center card-dark cursor-pointer">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                            <p class="mb-1 fw-semibold">Arrastra las imágenes aquí</p>
                            <small class="text-muted">o haz click para seleccionarlas</small>

                            <input
                                type="file"
                                id="gallery_input"
                                class="d-none"
                                accept="image/*"
                                multiple>
                        </div>

                        <div id="preview_container" class="row g-3 mt-3"></div>
                    </div>

                    <!-- Actions -->
                    <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Agregar imágenes
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@vite(['resources/js/admin/gallery.js'])

@endsection