@extends('admin.main')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-2 pb-3">
    <div>
        <h3 id="main_title" class="display">Memoriales</h3>
        <p class="text-muted mb-0">
            Revisa tus memoriales creados
        </p>
    </div>
    <div class="ms-auto">
        <a href="{{route('payment')}}" class="btn btn-primary m-1">
            <span><i class="fas fa-bag-shopping d-md-none"></i></span>
            <span class="d-none d-md-block"><i class="fas fa-bag-shopping me-2"></i> Comprar memorial</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Memoriales</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-address-card text-primary"></i></h6>
                    </div>
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">{{$memorials}}</h3>
                    </div>
                    <div class="col-12"><small id="revenue-change" class="text-muted">Memoriales a tu cargo</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Vistas Totales</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-eye text-primary"></i></h6>
                    </div>
                    {{-- Add ID for dynamic value --}}
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">{{$visits}}</h3>
                    </div>
                    {{-- Add ID for dynamic change --}}
                    <div class="col-12"><small id="revenue-change" class="text-muted">Personas que han visitado tus memoriales</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table id="memorials-table"
                class="table text-bg-dark card-dark border-dark"
                data-url="{{route('api.memorial.memorials')}}"
                data-pagination="true"
                data-classes="table"
                data-side-pagination="server"
                data-page-size="10"
                data-search="true"
                data-search-on-enter-key="false"
                data-show-refresh="true"
                data-ajax="ajaxRequest">
                <thead>
                    <tr>
                        <th data-field="slug" data-formatter="actionsFormatter">Ver</th>
                        <th data-field="deceased_name" data-sortable="true">Nombre</th>
                        <!-- <th data-field="created_at" data-sortable="true">Vistas</th> -->
                        <th data-field="is_public" data-formatter="statusFormatter">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


@vite(["resources/js/admin/memorials.js"])
@endsection