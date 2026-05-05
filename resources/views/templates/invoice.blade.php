<!DOCTYPE html>
<html>

<head>
    <style>
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            border-bottom: 1px solid #ccc;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        header h2 {
            margin: 0;
            font-size: 18px;
            padding-top: 0px !important;
        }


        /* header img {
            height: 70px;
        } */

        body {
            font-family: "sans-serif", serif;
            font-stretch: condensed;
            letter-spacing: -0.5px;
            text-align: start;
            margin-top: 80px;
            /* push body down */
        }


        h2,
        h3 {
            padding: 0px;
            margin: 0px;
        }


        table {
            text-align: start;
            width: 100%;
            border-collapse: collapse;
        }

        tr,
        td,
        th {
            text-align: start !important;
            vertical-align: middle;
            padding: 2px;
        }

        thead td,
        h3,
        h4 {
            font-size: 12px;
        }

        h1 {
            padding-bottom: 0px;
            margin-bottom: 0px;
            font-size: 20px;
            font-weight: bold;
        }

        u {
            font-weight: bold;
            font-size: 16px;
        }

        h2 {
            font-size: 16px;
        }


        .mb-2 {
            /* padding-bottom: 10px; */
            padding-top: 5px;
        }

        thead {
            border-top: 2px solid black;
            border-bottom: 2px solid black;
        }

        .section {
            margin-top: 30px;
        }

        .left {

            text-align: left;
        }

        .right {
            text-align: right;
        }
    </style>

</head>

<body>
    <header>
        <table width="100%">
            <tr>
                <td style="text-align: left; vertical-align: middle;">
                    <span>Selahi {{ $created_at?->format('d/m/Y h:i') }}</span>
                    <h2>Folio {{ $folio }}</h2>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <img src="{{ public_path('img/rectangle_logo.jpg') }}" width="180">
                    <!-- <img src="{{asset('img/rectangle_logo.jpg')}}" width="150" alt=""> -->

                </td>
            </tr>
        </table>
    </header>


    <div class="section">
        <table class="table-data">
            <tr>
                <td>
                    <h1>Folio:</h1> {{ $folio }}<br><br>
                    <h1>Fecha:</h1> {{ $created_at?->format('d/m/Y') }}
                </td>
                <td class="right">
                    <h1>Estado:</h1> {{ strtoupper($state) }}<br><br>
                    <h1>Moneda:</h1> {{ strtoupper($currency) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h1>Cliente</h1>
        <table class="border-top border-bottom">
            <tr>
                <td>
                    {{ $customer_name ?? $memorial_client_name}}<br>
                    {{ $customer_email ?? $memorial_client_email}}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h1>Detalle</h1>
        <br>
        <table>
            <thead class="border-top border-bottom">
                <tr>
                    <th class="left">Concepto</th>
                    <th class="right">Cantidad</th>
                    <th class="right">Precio</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="left">{{ $product_name ?? "Memorial"." ($client)" }}</td>
                    <td class="right">1</td>
                    <td class="right">${{ number_format($amount, 2) }}</td>
                    <td class="right">${{ number_format($amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td></td>
                <td width="40%">
                    <table>
                        <tr>
                            <td>Subtotal:</td>
                            <td class="right">${{ $amount_subtotal ? number_format($amount_subtotal, 2) : number_format($amount,2) }}</td>
                        </tr>
                        <tr>
                            <td>Impuestos:</td>
                            <td class="right">${{ number_format($tax, 2)}}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="bold">Total:</td>
                            <td class="right bold">${{ $amount_total ? number_format($amount_total, 2) : number_format($amount,2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h1>Método de Pago</h1>
        <table class="border-top border-bottom">
            <tr>
                <td>
                    <u>Tipo:</u> {{ $payment_method ? ucfirst($payment_method) : "N/D"}}<br>
                    <u>Marca:</u> {{ $card_brand ? strtoupper($card_brand) : "N/D"}}<br>
                    <u>Terminación:</u> **** {{ $card_last4 }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section small">
        <h1>Referencia de Pago</h1>
        <br>
        <u>Payment Intent:</u> {{ $stripe_payment_intent_id }}<br>
        <u>Charge ID:</u> {{ $stripe_charge_id ?? "N/D" }}
    </div>

    <footer style="text-align: center;">
        <h5>
            <a>
                <img src="{{ public_path('img/rectangle_icon.jpg') }}" width="100">
            </a>
        </h5>
    </footer>
</body>

</html>