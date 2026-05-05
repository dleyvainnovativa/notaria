@extends('admin.main')

@section('content')
<div class="bg-dark pb-4">
    <div class="row g-4">
        <div class="col-12 col-md-12 col-lg-12 col-xl-8">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card card-dark border border-dark text-dark p-2 shadow-sm">
                        <div class="card-body">
                            <div class="row py-2 g-4">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-8">

                                            <h2 class="text-dark fw-bold"><a href='{{route("admin.payments")}}'>
                                                    <i class="fas fa-chevron-left me-2"></i></a>Factura</h2>
                                            <h3 class="text-muted">{{$payment->folio}}</h3>
                                        </div>
                                        <div class="col-4 ms-auto text-end">
                                            <span class="badge text-bg-success">{{$payment->state}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <p class="pb-0 mb-0 fw-bolder">Origen:</p>
                                    <p class="pb-0 mb-0">{{env("APP_NAME")}}</p>
                                    <p class="pb-0 mb-0 text-muted">Veracruz, México</p>
                                    <p class="pb-0 mb-0 text-muted"><a href="mailto:{{env('CONTACT_EMAIL')}}">{{env("CONTACT_EMAIL")}}</a></p>
                                </div>
                                <div class="col-md-6 col-12">
                                    <p class="pb-0 mb-0 fw-bolder">Para:</p>
                                    <p class="pb-0 mb-0 fw-bold">{{ $payment->product_name ?? "Memorial" }} ({{$memorial->deceased_name}})</p>
                                    <p class="pb-0 mb-0 text-muted">{{ $payment->customer_name ?? $payment->memorial->user->name }}</p>
                                    <p class="pb-0 mb-0 text-muted">{{ $payment->customer_email ?? $payment->memorial->user->email }}</p>
                                </div>
                                <div class="col-12">
                                    <table id="tablaGastos"
                                        class="table text-bg-dark card-dark  border-dark"
                                        data-toggle="table">
                                        <thead>
                                            <tr>
                                                <th class="text-start" data-field="folio">Descripción</th>
                                                <th class="text-end" data-field="date">Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>{{ $payment->product_name ?? "Memorial" }} ({{$memorial->deceased_name}})</td>
                                                <td class="text-end">
                                                    {{ $payment->currency_symbol }} {{ $payment->amount_total ? number_format($payment->amount_total, 2) : number_format($payment->amount, 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-6 col-xl-4 ms-auto pe-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="text-muted">Subtotal</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span>{{ $payment->currency_symbol }} {{ $payment->amount_subtotal ? number_format($payment->amount_subtotal, 2) : number_format($payment->amount, 2) }}</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted">Tax</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span>{{ $payment->currency_symbol }} {{ number_format($payment->tax, 2) }}</span>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-6">
                                            <span class="fw-bold h4">Total</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span class="fw-bold h4">
                                                {{ $payment->currency_symbol }} {{ $payment->amount_total ? number_format($payment->amount_total, 2) : number_format($payment->amount, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card card-dark border border-dark text-dark p-2 shadow-sm">
                        <div class="card-body">
                            <div class="row py-2 g-4">
                                <div class="col-12">
                                    <h2 class="text-dark fw-bolder mb-0">Información de Pago</h2>
                                </div>

                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="d-flex">
                                        <div>
                                            <i class="fas fa-cash-register fa-lg text-primary me-3"></i>
                                        </div>

                                        <div>
                                            <p class="py-0 my-0 fw-bolder">Método de Pago</p>
                                            <span class="text-muted">
                                                {{ $payment->card_display ?? ucfirst($payment->payment_method ?? 'N/A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="d-flex">
                                        <div>
                                            <i class="far fa-credit-card fa-lg text-primary me-3"></i>
                                        </div>
                                        <div>
                                            <p class="py-0 my-0 fw-bolder">Tipo de Tarjeta</p>
                                            <span class="text-muted">
                                                {{ strtoupper($payment->card_brand ?? 'N/A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="d-flex">
                                        <div>
                                            <i class="fas fa-credit-card fa-lg text-primary me-3"></i>
                                        </div>

                                        <div>
                                            <p class="py-0 my-0 fw-bolder">Tarjeta Usada</p>
                                            <span class="text-muted">
                                                {{ $payment->card_last4 ? '**** ' . $payment->card_last4 : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="d-flex">
                                        <div>
                                            <i class="fas fa-hashtag fa-lg text-primary me-3"></i>
                                        </div>

                                        <div>
                                            <p class="py-0 my-0 fw-bolder">ID de Transacción</p>
                                            <span class="text-muted">
                                                {{ $payment->stripe_payment_intent_id }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="d-flex">
                                        <div>
                                            <i class="fas fa-calendar fa-lg text-primary me-3"></i>
                                        </div>

                                        <div>
                                            <p class="py-0 my-0 fw-bolder">Fecha de Pago</p>
                                            <span class="text-muted">
                                                {{ $payment->paid_at?->format('d/m/Y') ?? $payment->created_at?->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="d-flex">
                                        <div>
                                            <i class="fas fa-money-bill-wave fa-lg text-primary me-3"></i>
                                        </div>
                                        <div>
                                            <p class="py-0 my-0 fw-bolder">Monto</p>
                                            <span class="text-muted">
                                                {{ $payment->currency_symbol }} {{ $payment->amount_total ? number_format($payment->amount_total, 2) : number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12 col-md-12 col-lg-12 col-xl-4">
            <div class="row g-4">
                <div class="col-12 col-lg-6 col-xl-12 col-md-6">
                    <div class="card card-dark border border-dark text-dark p-2 shadow-sm">
                        <div class="card-body">
                            <div class="row py-2 g-4">
                                <div class="col-12">
                                    <h2 class="text-dark fw-bold">Detalles de Factura</h2>
                                </div>

                                <div class="col-12">
                                    <div class="row">

                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div>
                                                    <i class="fas fa-calendar fa-lg text-primary me-3"></i>
                                                </div>
                                                <div>
                                                    <p class="py-0 my-0 fw-bolder">Fecha de emisión</p>
                                                    <span class="text-muted">
                                                        {{ $payment->paid_at?->format('d/m/Y') ?? $payment->created_at?->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div>
                                                    <i class="fas fa-check-circle fa-lg text-primary me-3"></i>
                                                </div>
                                                <div>
                                                    <p class="py-0 my-0 fw-bolder">Estado</p>
                                                    <span class="text-muted">
                                                        {{ $payment->state }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div>
                                                    <i class="fas fa-money-bill-1 fa-lg text-primary me-3"></i>
                                                </div>
                                                <div>
                                                    <p class="py-0 my-0 fw-bolder">Total</p>
                                                    <span class="text-muted">
                                                        {{ $payment->currency_symbol }} {{ $payment->amount_total ? number_format($payment->amount_total, 2) : number_format($payment->amount, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div>
                                                    <i class="fas fa-user fa-lg text-primary me-3"></i>
                                                </div>
                                                <div>
                                                    <p class="py-0 my-0 fw-bolder">Cliente</p>
                                                    <span class="text-muted">
                                                        {{ $payment->customer_name ?? $payment->memorial->user->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div>
                                                    <i class="fas fa-envelope fa-lg text-primary me-3"></i>
                                                </div>
                                                <div>
                                                    <p class="py-0 my-0 fw-bolder">Correo</p>
                                                    <span class="text-muted">
                                                        {{ $payment->customer_email ?? $payment->memorial->user->email}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl-12 col-md-6">
                    <div class="card card-dark border border-dark text-dark p-2 shadow-sm">
                        <div class="card-body">
                            <div class="row py-2 g-2">
                                <div class="col-12">
                                    <h2 class="text-dark fw-bold">Acciones</h2>
                                </div>
                                <div class="col-12">
                                    <a class="btn btn-primary w-100 text-start" href="{{$payment->id}}/download"><i class="fa fa-download me-3"></i>Descargar PDF</a>
                                </div>
                                @if (isset($payment->receipt_url))
                                <div class="col-12">
                                    <a class="btn btn-outline-primary w-100 text-start" href="{{$payment->receipt_url}}" target="_blank"><i class="fab fa-stripe-s me-3"></i>Factura Stripe</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection