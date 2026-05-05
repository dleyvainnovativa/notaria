@extends('admin.memorial')

@section('content')
<div class="pb-2">
    <h3 id="main_title" class="display">Información</h3>
    <p id="main_subttitle" class="text-muted">
        Administra y actualiza los datos principales del memorial
    </p>
</div>
<div class="row g-4">
    <div class="col-12 col-md-5 col-lg-4 col-xl-4">
        <div class="card card-dark bg-dark border border-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-3">
                    <!-- <div class="col-12 col-md-12 mx-auto text-center">
                        <img id="profile-photo" class="img-fluid rounded object-fit-cover w-100 shimmer image-shimmer" src="" alt="">
                    </div> -->
                    <div class="col-12 col-md-12 mx-auto text-center">
                        <div class="profile-image-wrapper mx-auto">

                            <img
                                id="profile-photo"
                                src=""
                                alt="Profile photo"
                                class="img-fluid rounded object-fit-cover w-100 profile-image shimmer image-shimmer">

                            <!-- Overlay -->
                            <div class="profile-image-overlay">
                                <i class="fas fa-camera"></i>
                                <span>Cambiar foto</span>
                            </div>

                            <!-- Hidden file input -->
                            <input
                                type="file"
                                id="profileImageInput"
                                accept="image/*">
                        </div>
                    </div>

                    <!-- <div class="col-md-12 col-12 text-end ms-auto">
                        <button class="btn btn-primary w-100">Cambiar foto</button>
                    </div> -->
                    <div class="col-md-12 col-12 text-end ms-auto">
                        <button id="privacy_btn" class="btn btn-outline-danger w-100" onclick="openPrivacy()">Cambiar a Privado</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-7 col-lg-8 col-xl-8 h-100">
        <form id="info-form" class="needs-validation" novalidate>
            <div class="card card-dark bg-dark h-100 border border-dark">
                <div class="card-body p-4 text-dark">
                    <div class="row g-4 pb-4">
                        <div class="col-md-12 col-12">
                            <h5 class="mb-0 fw-bold">Información Básica</h5>
                            <small class="text-muted">Escribe la información necesaria para mostrar</small>
                        </div>
                        <div class="col-md-12 col-12">
                            <label for="deceased_name" class="form-label">Nombre</label>
                            <input disabled type="text" name="deceased_name" id="deceased_name" class="card-dark form-control shimmer input-shimmer  text-dark border border-dark" id="deceased_name" required>
                        </div>
                        <div class="col-md-12 col-12">
                            <label for="epitaph" class="form-label">Epitafío</label>
                            <textarea disabled rows="3" type="text" name="epitaph" id="epitaph" class="card-dark form-control shimmer input-shimmer  text-dark border border-dark" id="epitaph"></textarea>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="validationCustom01" class="form-label">Fecha de nacimiento</label>
                            <input disabled type="date" name="birthday" class="card-dark form-control shimmer input-shimmer  text-dark border border-dark" id="birthday" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="validationCustom01" class="form-label">Fecha de fallecimiento</label>
                            <input disabled type="date" name="deathday" class="card-dark form-control shimmer input-shimmer  text-dark border border-dark" id="deathday" required>
                        </div>
                        <div class="col-md-12 col-12">
                            <label for="validationCustom01" class="form-label">Playlist</label>
                            <input disabled type="text" name="playlist" class="card-dark form-control shimmer input-shimmer  text-dark border border-dark" id="playlist">
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-3 d-flex">
                <div class="text-start me-auto">
                    <button type="submit" class="btn btn-primary" id="save_btn">Guardar</button>
                </div>
                <div class="my-auto text-end">
                    <a href="{{ route('admin.memorial.life', $memorial_slug) }}" class="">Ir a Vida <i class="ms-2 fas fa-chevron-right"></i></a>
                </div>
            </div>
        </form>
    </div>
    <!-- <div class="col-md-12 col-12 text-end ms-auto">
        <a href="{{ route('admin.memorial.life', $memorial_slug) }}" class="">Ir a Vida <i class="ms-2 fas fa-chevron-right"></i></a>
    </div> -->
</div>
@include("admin.components.privacy")
@vite(["resources/js/admin/info.js", "resources/css/admin/info.css"])

@endsection