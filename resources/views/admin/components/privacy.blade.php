<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-dark border border-dark">
            <div class="modal-body p-4 text-center text-dark">
                <form class="needs-validation" id="privacy-form" novalidate>
                    <div class="row g-3 p-3">
                        <div class="col-12">
                            <div id="confirm_privacy_icon" class="feature-icon bg-success-subtle d-inline-flex align-items-center justify-content-center">
                                <i id="privacy_icon" class="fas fa-lock fa-2xl text-success"></i>
                            </div>
                        </div>
                        <div class="col-12">
                            <h3 class="fw-bold text-dark" id="privacy_title">¿Estás seguro de cambiar a Privado?</h3>
                        </div>
                        <div class="col-12">
                            <p class="text-muted" id="privacy_text">This action cannot be undone. All values associated with this field will be lost</p>
                        </div>
                        <input type="hidden" name="is_public" id="privacy_is_public">
                        <div class="col-12 text-start" id="privacy_password_container">
                            <label for="privacy_password" class="form-label fw-semibold small text-dark">
                                Contraseña
                            </label>
                            <input
                                type="text"
                                class="text-dark form-control card-dark border border-dark"
                                id="privacy_password"
                                name="password"
                                placeholder="Escribir contraseña">
                        </div>
                        <div class="col-12">
                            <button type="submit" id="confirm_privacy_btn" class="btn btn-success w-100 ">Confirmar</button>
                        </div>
                        <div class="col-12">
                            <a class="btn btn-light border w-100 " id="cancel_btn" data-bs-dismiss="modal">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>