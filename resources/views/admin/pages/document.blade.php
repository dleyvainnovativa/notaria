@extends('admin.main')

@section('content')
@vite(["resources/css/admin/steps.css"])
<main class="">
    <div class="tab-pane fade show active" id="main-pane-calendar" role="tabpanel" aria-labelledby="main-tab-calendar">
        <div class="col-12 col-md-8 mx-auto">
            <ul class="nav nav-pills d-flex px-0 " id="stepperTabs" role="tablist" style="gap:0;">
                <li class="nav-item flex-fill text-center calendar-options active" role="presentation">
                    <button class="nav-link text-center active  mx-auto" id="tab-info" data-bs-toggle="pill" data-bs-target="#pane-info" type="button" aria-selected="true" role="tab">
                        <div class="rounded-circle bg-primary-subtle border border-primary text-secondary d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-file-upload" aria-hidden="true"></i>
                        </div>
                        <small class="d-md-block d-none mt-2 text-muted" id="main_name">Subir documento</small>
                    </button>
                </li>
                <li class="nav-item flex-fill text-center calendar-options" role="presentation">
                    <button class="nav-link text-center  mx-auto" id="tab-rules" data-bs-toggle="pill" data-bs-target="#pane-rules" type="button" aria-selected="false" tabindex="-1" role="tab">
                        <div class="rounded-circle bg-primary-subtle border border-primary text-secondary d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-gear" aria-hidden="true"></i>
                        </div>
                        <small class="d-md-block d-none mt-2 text-muted">Reglas y Formato</small>
                    </button>
                </li>
                <li class="nav-item flex-fill text-center calendar-options" role="presentation">
                    <button class="nav-link text-center  mx-auto" id="tab-calendar" data-bs-toggle="pill" data-bs-target="#pane-calendar" type="button" aria-selected="false" tabindex="-1" role="tab">
                        <div class="rounded-circle bg-primary-subtle border border-primary text-secondary d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-info-circle" aria-hidden="true"></i>
                        </div>
                        <small class="d-md-block d-none mt-2 text-muted">Resultado</small>
                    </button>
                </li>
            </ul>
        </div>
        <form id="create_form" class="needs-validation" novalidate="">
            <!-- Step Content -->
            <div class=" pt-4">
                <div class="row g-4">
                    <div class="col-12 col-md-6 mx-auto pt-md-5">
                        <div class="tab-content" id="stepperTabsContent">
                            <div class="tab-pane fade show active" id="pane-info" role="tabpanel" aria-labelledby="tab-info">
                                <div class="card card-dark shadow-sm border border-dark text-dark mb-md-5">
                                    <div class="card-body p-4">
                                        <div class="pb-4">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="fa-solid fa-file-upload text-primary" aria-hidden="true"></i>
                                                <h5 class="mb-0 fw-semibold">Subir documento</h5>
                                            </div>
                                            <p class="text-muted small mb-0">
                                                Arrastra y suelta tu archivo o haz clic para seleccionar
                                            </p>
                                        </div>
                                        <div class="mb-2">
                                            <input type="file" id="imageInput" name="thumbnail" class="d-none" accept="image/png, image/jpeg">
                                            <div id="dropZone" class="border border border-dark border-dashed rounded-3 text-center p-5 cursor-pointer">
                                                <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2" aria-hidden="true"></i>
                                                <p class="text-muted mb-1">
                                                    Arrastra el documento o haz clic para subir
                                                </p>
                                                <small class="text-muted">
                                                    DOC, PDF hasta 5MB
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">Formatos soportados: PDF, Word (.doc, .docx)
                                        </small>
                                        <div class="mt-4">
                                            <button class="btn btn-primary w-100"><i class="fas fa-upload me-2"></i>Siguiente</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="pane-rules" role="tabpanel" aria-labelledby="tab-rules">
                                <div class="card card-dark shadow-sm border border-dark text-dark mb-md-5">
                                    <div class="card-body p-4">
                                        <div class="py-5">
                                            <div class="d-flex flex-column align-items-center justify-content-center text-center pt-5 pb-4">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-4"
                                                    style="width:70px; height:70px;">

                                                    <div class="spinner-border text-white" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>

                                                </div>
                                                <p class="fw-semibold mb-1">Procesando documento...</p>
                                                <small class="text-muted">Extrayendo información para formato SAT</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="pane-calendar" role="tabpanel" aria-labelledby="tab-calendar">
                                <div class="card card-dark shadow-sm border border-dark text-dark mb-md-5">
                                    <div class="card-body p-4">
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <div class="row g-4">
                                                    <div class="col-auto">
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <i class="fa-solid fa-info-circle text-primary" aria-hidden="true"></i>
                                                            <h5 class="mb-0 fw-semibold">Datos extraídos</h5>
                                                        </div>
                                                        <p class="text-muted small mb-0">
                                                            Revisa y edita la información antes de exportar
                                                        </p>
                                                    </div>
                                                    <div class="col-auto my-auto ms-md-auto">
                                                        <span class="badge text-bg-primary"><i class="fas fa-coins me-2"></i>1 token utilizado
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Nombre Completo</label>
                                                <input type="text" name="description" class="form-control card-dark border border-dark text-dark" value="Juan Carlos Pérez González" required>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">RFC</label>
                                                <input type="text" name="description" class="form-control card-dark border border-dark text-dark" value="PEGJ800101ABC" required>
                                            </div>
                                            <div class="col-12 col-md-12">
                                                <label class="form-label">Dirección</label>
                                                <input type="text" name="description" class="form-control card-dark border border-dark text-dark" value="Av. Reforma 123, Col. Centro, Ciudad de México" required>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Código Postal</label>
                                                <input type="text" name="description" class="form-control card-dark border border-dark text-dark" value="06000" required>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Régimen fiscal</label>
                                                <input type="text" name="description" class="form-control card-dark border border-dark text-dark" value="601 - General de Ley Personas Morales" required>
                                            </div>

                                            <div class="col-12 col-md-12">
                                                <label class="form-label">Uso de CFDI</label>
                                                <input type="text" name="description" class="form-control card-dark border border-dark text-dark" value="G03 - Gastos en general" required>
                                            </div>
                                            <div class="col-12 mt-5">
                                                <div class="row g-4">
                                                    <div class="col-12 col-md-6">
                                                        <button class="btn btn-primary w-100"><i class="fas fa-download me-2"></i>Exportar documento</button>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <button class="btn btn-secondary w-100"><i class="fas fa-arrows-rotate me-2"></i>Procesar nuevo</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        </form>
    </div>
</main>

@endsection