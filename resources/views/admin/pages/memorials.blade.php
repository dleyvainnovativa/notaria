@extends('admin.main')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-2 pb-3">
    <div>
        <h3 id="main_title" class="display">Dashboard</h3>
        <p class="text-muted mb-0">
            Bienvenido de nuevo
        </p>
    </div>
    <div class="ms-auto">
        <a href="{{route('payment')}}" class="btn btn-primary m-1">
            <span><i class="fas fa-coins d-md-none"></i></span>
            <span class="d-none d-md-block"><i class="fas fa-coins me-2"></i> Comprar tokens</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-4">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Documentos procesados</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-file text-primary"></i></h6>
                    </div>
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">{{$memorials}}</h3>
                    </div>
                    <div class="col-12"><small id="revenue-change" class="text-muted">Procesados exitosamente</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Tokens</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-coins text-primary"></i></h6>
                    </div>
                    {{-- Add ID for dynamic value --}}
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">{{$visits}}</h3>
                    </div>
                    {{-- Add ID for dynamic change --}}
                    <div class="col-12"><small id="revenue-change" class="text-muted">Tokens restantes</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Usuarios</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-users text-primary"></i></h6>
                    </div>
                    {{-- Add ID for dynamic value --}}
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">{{$visits}}</h3>
                    </div>
                    {{-- Add ID for dynamic change --}}
                    <div class="col-12"><small id="revenue-change" class="text-muted">Usuarios asignados</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <h5 class="fw-bold">Acciones Rápidas</h5>
    </div>
    <div class="col-auto">
        <a href="{{route('admin.document')}}" class="btn btn-primary"><i class="fas fa-file-upload me-2"></i>Subir documento</a>
    </div>
    <div class="col-auto">
        <a href="{{route('payment')}}" class="btn btn-outline-primary"><i class="fas fa-coins me-2"></i>+ Tokens</a>
    </div>


    <div class="col-12">
        <h5 class="fw-bold">Actividad Reciente</h5>
    </div>
    <div class="col-12">
        <div class="card card-dark border border-dark shadow-sm">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item p-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="fas fa-file-circle-check"></i>
                            </div>

                            <div>
                                <div class="fw-medium">Documento procesado</div>
                                <small class="text-muted">escritura_2024_001.pdf</small>
                            </div>
                        </div>
                        <small class="text-muted mt-2 mt-md-0 d-flex align-items-center gap-1 text-end">
                            <i class="fas fa-clock"></i> Hace 5 min
                        </small>
                    </div>
                    <div class="list-group-item p-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="fas fa-file-circle-check"></i>
                            </div>

                            <div>
                                <div class="fw-medium">Documento procesado</div>
                                <small class="text-muted">contrato_venta_marzo.pdf</small>
                            </div>
                        </div>
                        <small class="text-muted mt-2 mt-md-0 d-flex align-items-center gap-1 text-end">
                            <i class="fas fa-clock"></i> Hace 23 min
                        </small>
                    </div>
                    <div class="list-group-item p-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="fas fa-coins"></i>
                            </div>

                            <div>
                                <div class="fw-medium">Tokens adquiridos</div>
                                <small class="text-muted">+100 tokens</small>
                            </div>
                        </div>
                        <small class="text-muted mt-2 mt-md-0 d-flex align-items-center gap-1 text-end">
                            <i class="fas fa-clock"></i> Hace 1 hora
                        </small>
                    </div>
                    <div class="list-group-item p-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="fas fa-file-circle-check"></i>
                            </div>

                            <div>
                                <div class="fw-medium">Documento procesado</div>
                                <small class="text-muted">poder_notarial_056.pdf</small>
                            </div>
                        </div>
                        <small class="text-muted mt-2 mt-md-0 d-flex align-items-center gap-1 text-end">
                            <i class="fas fa-clock"></i> Hace 2 horas
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@vite(["resources/js/admin/memorials.js"])
@endsection