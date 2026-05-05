@extends('main')

@section('content')
<section class="container text-center">
    <div class="container justify-items-center align-content-center py-5 my-4" style="justify-items: center;">
        <div class="col-12 col-md-6 col-lg-5 col-xl-4 py-4">
            <div class="card card-dark border border-dark shadow-lg text-center">
                <div class="card-body p-4">

                    <h2 class="mb-3 text-dark">
                        {{ $memorial->deceased_name }}
                    </h2>

                    <p class="text-muted">
                        Este memorial es privado. Ingresa la contraseña para acceder.
                    </p>

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('memory', $memorial->slug) }}">
                        @csrf

                        <div class="mb-3 text-start text-dark">
                            <label class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    class="text-dark form-control card-dark border border-dark"
                                    id="password"
                                    name="password"
                                    placeholder="Ingresa la contraseña de acceso"
                                    required>
                                <span class="input-group-text text-bg-dark border border-dark" style="cursor: pointer;">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>

                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <button class="btn btn-primary w-100">
                            Acceder
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
<script>
    const togglePassword = document.querySelector('.input-group-text');
    const password = document.querySelector('#password');
    const icon = togglePassword.querySelector('i');

    togglePassword.addEventListener('click', function(e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // toggle the eye slash icon
        if (type === 'password') {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
</script>

@endsection