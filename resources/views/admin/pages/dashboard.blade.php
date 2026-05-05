@extends('admin.memorial')

@section('content')
<!-- <div class="pb-2">
    <h3 id="main_title" class="display">Dashboard</h3>
    <p id="main_subttitle" class="text-muted">Manage your account settings and preferences</p>
</div> -->
<div class="d-flex justify-content-between align-items-center mb-2 pb-3">
    <div>
        <h3 id="main_title" class="display">Panel Conmemorativo</h3>
        <p class="text-muted mb-0">
            Administra la información, privacidad y recuerdos de este memorial digital
        </p>
    </div>
    <div class="ms-auto">
        <a href="{{route('home')}}/memory/{{$memorial_slug}}" target="_blank" class="btn btn-primary m-1">
            <span><i class="fas fa-eye d-md-none"></i></span>
            <span class="d-none d-md-block"><i class="fas fa-eye me-2"></i> Ver memorial</span>
        </a>
    </div>
    <div class="">
        <a href="{{route('home')}}/q/{{$qr}}/download" target="_blank" class="btn btn-primary m-1">
            <span><i class="fas fa-qrcode d-md-none"></i></span>
            <span class="d-none d-md-block"><i class="fas fa-qrcode me-2"></i> Descargar QR</span>
        </a>
    </div>
</div>
<div class="row g-4">
    <a href="{{ route('admin.memorial.messages', $memorial_slug) }}" class="col-6 col-md-6">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Mensajes</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-message text-primary"></i></h6>
                    </div>
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">{{$tributes}}</h3>
                    </div>
                    <div class="col-12 d-none d-md-block"><small id="revenue-change" class="text-muted">Personas que han escrito al memorial</small></div>
                </div>
            </div>
        </div>
    </a>
    <div class="col-6 col-md-6">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Vistas</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-eye text-primary"></i></h6>
                    </div>
                    {{-- Add ID for dynamic value --}}
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">{{$visits}}</h3>
                    </div>
                    {{-- Add ID for dynamic change --}}
                    <div class="col-12 d-none d-md-block"><small id="revenue-change" class="text-muted">Personas que han visitado tus memoriales</small></div>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.memorial.info', $memorial_slug) }}" class="col-12 col-md-4 col-lg-4">
        <div class="card card-dark border border-dark h-100 shadow-sm text-dark text-start">
            <div class="card-body p-4">
                <div class="feature col">
                    <!-- <div class="feature-icon d-inline-flex align-items-center justify-content-center"> -->
                    <i class="fas fa-circle-info fa-xl text-primary"></i>
                    <!-- </div> -->
                    <h4 class="py-3 display fw-bold">Datos básicos</h4>
                    <p class="text-muted">
                        Edita los datos de tu memorial
                    </p>
                </div>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.memorial.gallery', $memorial_slug) }}" class="col-12 col-md-4 col-lg-4">
        <div class="card card-dark border border-dark h-100 shadow-sm text-dark text-start">
            <div class="card-body p-4">
                <div class="feature col">
                    <!-- <div class="feature-icon d-inline-flex align-items-center justify-content-center"> -->
                    <i class="fas fa-photo-film fa-xl text-primary"></i>
                    <!-- </div> -->
                    <h4 class="py-3 display fw-bold">Galería</h4>
                    <p class="text-muted">
                        Actualiza la galería tu memorial
                    </p>
                </div>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.memorial.life', $memorial_slug) }}" class="col-12 col-md-4 col-lg-4">
        <div class="card card-dark border border-dark h-100 shadow-sm text-dark text-start">
            <div class="card-body p-4">
                <div class="feature col">
                    <!-- <div class="feature-icon d-inline-flex align-items-center justify-content-center"> -->
                    <i class="fas fa-heart fa-xl text-primary"></i>
                    <!-- </div> -->
                    <h4 class="py-3 display fw-bold">Su Vida</h4>
                    <p class="text-muted">
                        Actualiza los datos de su vida
                    </p>
                </div>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.memorial.invitations', $memorial_slug) }}" class="col-12 col-md-4 col-lg-4">
        <div class="card card-dark border border-dark h-100 shadow-sm text-dark text-start">
            <div class="card-body p-4">
                <div class="feature col">
                    <!-- <div class="feature-icon d-inline-flex align-items-center justify-content-center"> -->
                    <i class="fas fa-envelope fa-xl text-primary"></i>
                    <!-- </div> -->
                    <h4 class="py-3 display fw-bold">Invitar</h4>
                    <p class="text-muted">
                        Invita a tus familiares y amigos a colaborar en el memorial
                    </p>
                </div>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.memorial.timeline', $memorial_slug) }}" class="col-12 col-md-4 col-lg-4">
        <div class="card card-dark border border-dark h-100 shadow-sm text-dark text-start">
            <div class="card-body p-4">
                <div class="feature col">
                    <!-- <div class="feature-icon d-inline-flex align-items-center justify-content-center"> -->
                    <i class="fas fa-timeline fa-xl text-primary"></i>
                    <!-- </div> -->
                    <h4 class="py-3 display fw-bold">Línea de Tiempo</h4>
                    <p class="text-muted">
                        Actualiza la línea de tiempo de tu memorial
                    </p>
                </div>
            </div>
        </div>
    </a>
</div>

@endsection