<section class="col-12 col-md-8 col-lg-8 col-xl-7 mx-auto">
    <div class="card card-dark shadow-sm border border-dark pb-4" data-aos-delay="200" data-aos="fade-up">
        <div class="card-body p-4">
            <div class="row g-4 text-dark">
                <div class="col-12">
                    <h4 class="font-serif display fw-bold">Información del memorial</h4>
                </div>

                <!-- Nombre del ser querido -->
                <div class="col-12">
                    <label class="form-label fw-medium">
                        Nombre de tu ser querido <span class="fw-bold text-primary">*</span>
                    </label>
                    <input type="text" name="deceased_name" class="form-control form-control-lg card-dark border border-dark" placeholder="Ingresa su nombre completo" required>
                    <div class="invalid-feedback">
                        El nombre de tu ser querido no puede estar vacío.
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium">
                        Tu nombre <span class="fw-bold text-primary">*</span>
                    </label>
                    <input type="text" value="{{ session('user_name') }}" name="user_name" class="form-control form-control-lg card-dark border border-dark" placeholder="Tu nombre completo" required>
                    <div class="invalid-feedback">
                        Tu nombre no puede estar vacío.
                    </div>
                </div>

                <!-- Email -->
                <div class="col-12">
                    <label class="form-label fw-medium">
                        Tu correo electrónico <span class="fw-bold text-primary">*</span>
                    </label>
                    <input type="email" value="{{ session('user_email') }}" name="user_email" class="form-control form-control-lg card-dark border border-dark" placeholder="tu@email.com" required>
                    <div class="form-text mb-2 text-muted">
                        Te enviaremos el acceso para administrar el memorial
                    </div>
                    <div class="invalid-feedback">
                        Ingresa un correo válido.
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="col-12">
                    <label class="form-label fw-medium">
                        Número telefónico <span class="text-primary fw-bold">(opcional)</span>
                    </label>
                    <input type="tel" name="user_phone" class="form-control form-control-lg card-dark border border-dark" placeholder="+52 000 000 0000">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkDefault" required>
                        <label class="form-check-label text-primary" for="checkDefault">
                            <a href="{{route('privacy')}}" target="_blank">Aceptar términos y condiciones</a>
                        </label>
                        <div class="invalid-feedback">
                            Favor de revisar nuestro aviso de privacidad y aceptar.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>