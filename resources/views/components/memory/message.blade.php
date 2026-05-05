<div class="offcanvas offcanvas-bottom text-bg-dark border border-dark" tabindex="-1" id="messageBottomSheet" aria-labelledby="messageBottomSheetLabel">
    <div class="offcanvas-header pt-4 px-4 container">
        <h5 class="offcanvas-title display" id="messageBottomSheetLabel">Dejar un mensaje</h5>
        <button type="button" class="btn btn-dark ms-auto" data-bs-dismiss="offcanvas" aria-label="Cerrar">
            <i class="fas fa-xmark fa-lg text-dark"></i>
        </button>
    </div>
    <div class="offcanvas-body px-0">
        <main class="container pt-2 pb-4 col-12">
            <div class="container">
                <form id="tribute-form" class="needs-validation row g-4" novalidate>
                    <input type="hidden" name="memorial_slug" id="memorial_slug" value="{{ $memorial->slug }}">

                    <div class="col-md-12 col-12">
                        <label for="validationCustom01" class="form-label">Nombre</label>
                        <input type="text" name="author_name" class="form-control text-bg-dark border border-dark" id="validationCustom01" required>
                        <div class="valid-feedback">
                            ¡Todo correcto!
                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea
                            name="message"
                            placeholder="Comparte un recuerdo, una historia o un mensaje"
                            class="form-control text-bg-dark border border-dark"
                            id="message"
                            rows="4"
                            maxlength="200"
                            required></textarea>

                        <span class="pull-right label label-default" id="count_message">0 / 200</span>

                        <div class="valid-feedback">
                            ¡Todo correcto!
                        </div>
                    </div>

                    <div class="col-md-12 col-12 text-end ms-auto">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100">
                            <span class="fs-6 px-3">Enviar mensaje</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>