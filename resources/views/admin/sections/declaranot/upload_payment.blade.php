<div class="tab-pane fade" id="pane-payment" role="tabpanel" aria-labelledby="tab-payment">
    <div class="card card-dark shadow-sm border border-dark text-dark mb-md-5">
        <div class="card-body p-4">


            <div class="pb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-file-upload text-primary"></i>
                    <h5 class="mb-0 fw-semibold">Subir documento</h5>
                </div>

                <p class="text-muted small mb-0">
                    Arrastra y suelta tu archivo o haz clic para seleccionar
                </p>
            </div>

            <div class="mb-2 upload-wrapper" data-type="calculo">

                <!-- FILE INPUT -->
                <input
                    type="file"
                    name="calculo"
                    class="documentInput d-none"
                    accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">

                <!-- DROP ZONE -->
                <div
                    class="dropZone border border-dark border-dashed rounded-3 text-center p-5 cursor-pointer">

                    <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2"></i>

                    <p class="text-muted mb-1">
                        Arrastra el documento o haz clic para subir
                    </p>

                    <small class="text-muted">
                        DOC, DOCX, PDF hasta 5MB
                    </small>

                    <div class="fileName mt-3 fw-semibold text-primary d-none"></div>

                </div>

            </div>

            <small class="text-muted">
                Formatos soportados: PDF, Word (.doc, .docx)
            </small>

            <div class="col-12 mt-5">
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <a data-prev="tab-info" class="btn btn-outline-primary w-100"><i class="fas fa-chevron-left me-2"></i>Regresar</a>
                    </div>
                    <div class="col-12 col-md-6">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-upload me-2"></i>
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>

            <!-- <div class="mt-4">
            </div> -->


        </div>
    </div>
</div>