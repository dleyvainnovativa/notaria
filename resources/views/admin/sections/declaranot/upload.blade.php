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