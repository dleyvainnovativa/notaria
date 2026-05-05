<!doctype html>
<html>

<body>
    <div
        style='background-color:#FFFFFF;color:#333333;font-family:"Helvetica Neue", "Arial Nova", "Nimbus Sans", Arial, sans-serif;font-size:16px;font-weight:400;letter-spacing:0.15008px;line-height:1.5;margin:0;padding:32px 0;min-height:100%;width:100%'>
        <table
            align="center"
            width="100%"
            style="margin:0 auto;max-width:600px;background-color:#FFFFFF;border-radius:16px"
            role="presentation"
            cellspacing="0"
            cellpadding="0"
            border="0">
            <tbody>
                <tr style="width:100%">
                    <td>
                        <div style="padding:16px 24px 16px 24px;text-align:center">
                            <img
                                alt="Sample product"
                                src="https://selahi.innovativa.mx/img/logo_email4.png"
                                width="350"
                                style="width:350px;outline:none;border:none;text-decoration:none;vertical-align:middle;display:inline-block;max-width:100%" />
                        </div>
                        <div style="padding:16px 24px 24px 24px">
                            <table
                                align="center"
                                width="100%"
                                cellpadding="0"
                                border="0"
                                style="table-layout:fixed;border-collapse:collapse">
                                <tbody style="width:100%">
                                    <tr style="width:100%">
                                        <td
                                            style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:0">
                                            <div style="padding:0px 0px 0px 0px">
                                                <h2
                                                    style="font-weight:normal;text-align:left;margin:0;font-size:24px;padding:0px 0px 0px 0px">
                                                    Recibo de compra.
                                                </h2>
                                            </div>
                                        </td>
                                        <td
                                            style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:0">
                                            <div style="padding:0px 0px 0px 0px">
                                                <div
                                                    style="color:#808080;font-size:14px;font-weight:normal;text-align:right;padding:0px 0px 0px 0px">
                                                    #{{$payment->folio}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h3
                            style="font-weight:normal;text-align:left;margin:0;font-size:20px;padding:16px 24px 0px 24px">
                            Gracias por comprar con nosotros
                        </h3>
                        <div
                            style="color:#404040;font-size:16px;font-weight:normal;text-align:left;padding:16px 24px 16px 24px">
                            Hola <b>{{$user->name}}</b>. Gracias por confiar en nosotros para honrar y
                            preservar la historia de quien tanto significó en sus vidas.
                            Será un honor acompañarlos en este homenaje y ayudar a que sus
                            recuerdos sigan vivos.
                        </div>
                        <div style="padding:12px 24px 28px 24px">
                            <table
                                align="center"
                                width="100%"
                                cellpadding="0"
                                border="0"
                                style="table-layout:fixed;border-collapse:collapse">
                                <tbody style="width:100%">
                                    <tr style="width:100%">
                                        <td
                                            style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:0;width:140px">
                                            <div style="text-align:left;padding:16px 0px 16px 0px">
                                                <a
                                                    href="{{route('login')}}"
                                                    style="color:#FFFFFF;font-size:16px;font-weight:bold;background-color:#31623D;border-radius:4px;display:inline-block;padding:8px 12px;text-decoration:none"
                                                    target="_blank"><span><!--[if mso
                                ]><i
                                  style="letter-spacing: 12px;mso-font-width:-100%;mso-text-raise:18"
                                  hidden
                                  >&nbsp;</i
                                ><!
                              [endif]--></span><span>Ver Memorial</span><span><!--[if mso
                                ]><i
                                  style="letter-spacing: 12px;mso-font-width:-100%"
                                  hidden
                                  >&nbsp;</i
                                ><!
                              [endif]--></span></a>
                                            </div>
                                        </td>
                                        <td
                                            style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:0">
                                            <div style="padding:16px 24px 16px 0px">
                                                <a
                                                    href="{{route('home')}}/q/{{$qrCode->uuid}}/download"
                                                    style="color:#FFFFFF;font-size:16px;font-weight:bold;background-color:#999999;border-radius:4px;display:inline-block;padding:8px 12px;text-decoration:none"
                                                    target="_blank"><span><!--[if mso
                                ]><i
                                  style="letter-spacing: 12px;mso-font-width:-100%;mso-text-raise:18"
                                  hidden
                                  >&nbsp;</i
                                ><!
                              [endif]--></span><span>Descargar QR</span><span><!--[if mso
                                ]><i
                                  style="letter-spacing: 12px;mso-font-width:-100%"
                                  hidden
                                  >&nbsp;</i
                                ><!
                              [endif]--></span></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h3
                            style="font-weight:normal;text-align:left;margin:0;font-size:20px;padding:16px 24px 0px 24px">
                            Resumen de Orden
                        </h3>
                        <div style="padding:16px 24px 16px 24px">
                            <div style="padding:0px 0px 0px 0px">
                                <div style="padding:0px 0px 0px 0px">
                                    <table
                                        align="center"
                                        width="100%"
                                        cellpadding="0"
                                        border="0"
                                        style="table-layout:fixed;border-collapse:collapse">
                                        <tbody style="width:100%">
                                            <tr style="width:100%">
                                                <td
                                                    style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:10.666666666666666px;width:64px">
                                                    <div style="padding:4px 4px 4px 4px">
                                                        <div style="padding:0px 0px 0px 0px">
                                                            <div style="padding:0px 0px 0px 0px">
                                                                <img
                                                                    alt="Sample product"
                                                                    src="https://selahi.innovativa.mx/img/icon/web-app-manifest-512x512.png"
                                                                    style="outline:none;border:none;text-decoration:none;vertical-align:middle;display:inline-block;max-width:100%" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    style="box-sizing:content-box;vertical-align:middle;padding-left:5.333333333333333px;padding-right:5.333333333333333px">
                                                    <div style="padding:0px 0px 0px 0px">
                                                        <div
                                                            style="font-size:16px;font-weight:bold;text-align:left;padding:0px 0px 4px 0px">
                                                            Memorial Selahi
                                                        </div>
                                                        <div
                                                            style="color:#808080;font-size:14px;font-weight:normal;text-align:left;padding:0px 0px 0px 0px">
                                                            Homenaje a &quot;{{$memorial->deceased_name}}&quot;
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    style="box-sizing:content-box;vertical-align:middle;padding-left:10.666666666666666px;padding-right:0;width:80px">
                                                    <div style="padding:0px 0px 0px 0px">
                                                        <div
                                                            style="font-size:16px;font-weight:bold;text-align:right;padding:0px 0px 0px 0px">
                                                            ${{$payment->amount}}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div style="padding:8px 0px 8px 0px">
                                <hr
                                    style="width:100%;border:none;border-top:1px solid #EEEEEE;margin:0" />
                            </div>
                        </div>
                        <div style="padding:0px 0px 0px 0px">
                            <div style="padding:0px 24px 8px 24px">
                                <table
                                    align="center"
                                    width="100%"
                                    cellpadding="0"
                                    border="0"
                                    style="table-layout:fixed;border-collapse:collapse">
                                    <tbody style="width:100%">
                                        <tr style="width:100%">
                                            <td
                                                style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:8px">
                                                <div
                                                    style="color:#737373;font-size:16px;font-weight:normal;text-align:right;padding:0px 0px 0px 0px">
                                                    Subtotal
                                                </div>
                                            </td>
                                            <td
                                                style="box-sizing:content-box;vertical-align:middle;padding-left:8px;padding-right:0">
                                                <div
                                                    style="font-weight:bold;text-align:right;padding:0px 0px 0px 0px">
                                                    ${{$payment->amount}}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="padding:0px 24px 8px 24px">
                                <table
                                    align="center"
                                    width="100%"
                                    cellpadding="0"
                                    border="0"
                                    style="table-layout:fixed;border-collapse:collapse">
                                    <tbody style="width:100%">
                                        <tr style="width:100%">
                                            <td
                                                style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:8px">
                                                <div
                                                    style="color:#737373;font-weight:normal;text-align:right;padding:0px 0px 0px 0px">
                                                    Servicio
                                                </div>
                                            </td>
                                            <td
                                                style="box-sizing:content-box;vertical-align:middle;padding-left:8px;padding-right:0">
                                                <div
                                                    style="font-weight:bold;text-align:right;padding:0px 0px 0px 0px">
                                                    ${{$payment->tax ?? "0.00"}}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="padding:16px 28px 16px 28px">
                                <hr
                                    style="width:100%;border:none;border-top:1px solid #EEEEEE;margin:0" />
                            </div>
                            <div style="padding:16px 24px 16px 24px">
                                <table
                                    align="center"
                                    width="100%"
                                    cellpadding="0"
                                    border="0"
                                    style="table-layout:fixed;border-collapse:collapse">
                                    <tbody style="width:100%">
                                        <tr style="width:100%">
                                            <td
                                                style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:8px">
                                                <div
                                                    style="font-weight:normal;text-align:right;padding:4px 0px 4px 0px">
                                                    Total
                                                </div>
                                            </td>
                                            <td
                                                style="box-sizing:content-box;vertical-align:middle;padding-left:8px;padding-right:0">
                                                <div
                                                    style="font-weight:bold;text-align:right;padding:4px 0px 4px 0px">
                                                    ${{$payment->amount}}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <h3
                            style="font-weight:normal;text-align:left;margin:0;font-size:20px;padding:40px 24px 24px 24px">
                            Información
                        </h3>
                        <div style="padding:16px 24px 16px 24px">
                            <table
                                align="center"
                                width="100%"
                                cellpadding="0"
                                border="0"
                                style="table-layout:fixed;border-collapse:collapse">
                                <tbody style="width:100%">
                                    <tr style="width:100%">
                                        <td
                                            style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:0">
                                            <div style="padding:0px 0px 0px 0px">
                                                <div
                                                    style="font-size:16px;font-weight:bold;padding:0px 0px 8px 0px">
                                                    Información del cliente
                                                </div>
                                                <div
                                                    style="font-size:14px;font-weight:normal;padding:0px 0px 0px 0px">
                                                    {{$user->name}}
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            style="box-sizing:content-box;vertical-align:middle;padding-left:0;padding-right:0">
                                            <div style="padding:0px 0px 0px 0px">
                                                <div
                                                    style="font-size:16px;font-weight:bold;text-align:left;padding:0px 0px 8px 0px">
                                                    Fecha de Compra
                                                </div>
                                                <div
                                                    style="font-size:14px;font-weight:normal;text-align:left;padding:0px 0px 0px 0px">
                                                    {{$payment->created_at}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="padding:16px 0px 16px 0px">
                            <hr
                                style="width:100%;border:none;border-top:1px solid #EEEEEE;margin:0" />
                        </div>
                        <div
                            style="font-size:14px;font-weight:normal;text-align:left;padding:16px 24px 16px 24px">
                            Un espacio digital para honrar, recordar y preservar historias
                            que viven para siempre.
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>