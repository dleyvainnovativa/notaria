@extends('admin.main')

@section('content')
<div class="pb-2">
    <h3 id="main_title" class="display">Pagos</h3>
    <p id="main_subttitle" class="text-muted">
        Consulta, descarga y gestiona tus comprobantes de pago
    </p>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Total</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-money-bill-1-wave text-primary"></i></h6>
                    </div>
                    {{-- Add ID for dynamic value --}}
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">${{$amount}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card card-dark border border-dark bg-dark">
            <div class="card-body p-4 text-dark">
                <div class="row g-2">
                    <div class="col-auto me-auto">
                        <h6 class="my-0">Pagos</h6>
                    </div>
                    <div class="col-auto ms-auto">
                        <h6 class="my-0"><i class="fa fa-money-bill-1-wave text-primary"></i></h6>
                    </div>
                    {{-- Add ID for dynamic value --}}
                    <div class="col-12">
                        <h3 id="revenue-value" class="my-0 fw-bold">{{$payments}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table id="payments-table"
                class="table text-bg-dark card-dark border-dark"
                data-url="{{route('api.memorial.payments')}}"
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
                        {{-- data-formatter is used to render complex cells --}}
                        <th data-field="id" data-formatter="actionsFormatter">Acciones</th>
                        <th data-field="folio" data-sortable="true">Folio</th>
                        <th data-field="client" data-sortable="true">Cliente</th>
                        <th data-field="amount" data-sortable="true">Monto</th>
                        <th data-field="created_at" data-sortable="true">Fecha</th>
                        <th data-field="state" data-formatter="statusFormatter">Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@vite(["resources/js/admin/payments.js"])


@endsection