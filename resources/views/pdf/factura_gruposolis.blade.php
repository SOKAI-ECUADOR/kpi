<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{$clave_acceso}}</title>
        <style>
            @page {
                margin: 25px;
                font-family: Arial;
            }
            *{
                box-sizing:border-box;
            }
            body {
                margin: 0;
                padding:0;
                font-family: sans-serif;
            }
            .titulo1{
                font-size: 16px;
                font-weight: bold;
            }
            .titulo1-1-1-1{
                font-size: 14px;
            }
            .titulo1-1{
                font-size: 16px;
            }
            .titulo2{
                font-size: 11.8px;
            }
            .titulo3{
                font-size: 11px;
            }
            .texto{
                font-size: 10px;
            }
            .text-center{
                text-align: center!important;
                width: 100%;
            }
            .mb-0{
                margin-bottom: 3px;
            }
            .mb-1{
                margin-bottom: 6px;
            }
            .mb-2{
                margin-bottom: 8px;
            }
            .mb-3{
                margin-bottom: 10px;
            }
            .mr-3{
                margin-right: 10px;
            }
            .mr-4{
                margin-right: 20px;
            }
            .mt-3{
                margin-top:10px;
            }
            .bold{
                font-weight: bold;
            }
            .nobold{
                font-weight: 10;
            }
            th,td{
                font-size: 9px;
                padding: 5px;
            }
            .bordeado{
                border:1px solid #000;
                border-radius: 10px;
                padding: 10px 10px;
            }
            .bordeado1{
                border:1px solid #000;
            }
            .ml-5{
                margin-left:30px;
            }
        </style>
    </head>
    <body>
        <div style="margin-top: 60px; align-items: center">
            <div style="width: 53%; display: inline-block;">
                <div style="width:95%">
                    <div style="text-align:center">
                        <div style="height:160px;margin-bottom:13px;">
                            <div style="vertical-align:middle;top:50%;">
                                <img src="{{ storage_path('logos/'.$empresa->logo) }}" alt="logo" style="max-width:100%;max-height:160px;text-align:center;">
                            </div>
                        </div>
                        <!--<div style="width:100%;display:block;margin-top:-10px;" class="titulo2 mb-3">COMPROBANTE GENERADO EN EL AMBIENTE DE
                            @if($empresa->ambiente==2)
                                PRODUCCION
                            @else
                                PRUEBAS
                            @endif
                        </div>-->
                    </div>
                    <div style="width:100%;" class="bordeado">
                        <div class="titulo1-1-1">{{$empresa->razon_social}}</div>
                        <div style="width:100%" class="titulo3 mb-3">{{$empresa->nombre_empresa}}</div>
                        <div style="width:100%" class="mb-1"><span class="titulo3 bold">Dirección Matriz:</span>
                            @if($empresa->direccion_empresa!=null && $empresa->direccion_empresa!='null')
                                <span class="texto">{{$empresa->direccion_empresa}}</span>
                            @endif
                        </div>
                        <div style="width:100%" class="mb-1"><span class="titulo3 bold">Contacto:</span>
                            @if($empresa->telefono!=null && $empresa->telefono!='null')
                                <span class="texto mr-4">{{$empresa->telefono}}</span>
                            @endif
                        </div>
                        <div style="width:100%" class="mb-1 text-center">
                            @if($empresa->urlweb!=null && $empresa->urlweb!='null')
                                <span class="titulo3 bold text-center">{{$empresa->urlweb}}</span>
                            @endif
                        </div>
                        @if($empresa->obligado_contabilidad==1)
                            <div style="width:100%" class="mb-1"><span class="titulo3 bold">OBLIGADO A LLEVAR CONTABILIDAD:</span> <span class="texto">SI</span></div>
                        @else
                            <div style="width:100%" class="mb-1"><span class="titulo3 bold">OBLIGADO A LLEVAR CONTABILIDAD:</span> <span class="texto">NO</span></div>
                        @endif
                        @if($empresa->leyenda==2)
                            <div style="width:100%" class="mb-1 text-center"><span class="titulo3 text-center" style="font-size: 9px;">AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN MICROEMPRESA</span></div>
                        @elseif($empresa->leyenda==3)
                            <div style="width:100%" class="mb-1 text-center"><span class="titulo3 text-center" style="font-size: 9px;">AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN RIMPE</span></div>
                        @elseif($empresa->leyenda!=null && $empresa->leyenda!='null')
                            <div style="width:100%" class="mb-1 text-center"><span class="titulo3 text-center" style="font-size: 9px;">{{$empresa->leyenda}}</span></div>
                        @endif
                    </div>
                </div>
            </div>
            <div style="width: 43%; display: inline-block;" class="bordeado">
                <div style="width:100%" class="mb-1"><span class="titulo1">R.U.C.:</span> <span class="titulo1-1">{{$empresa->ruc_empresa}}</span></div>
                <div style="width:100%"><span class="titulo1">FACTURA N°:</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo2">N° {{substr($clave_acceso,24,3)}}-{{substr($clave_acceso,27,3)}}-{{substr($clave_acceso,30,9)}}</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo1">NÚMERO DE AUTORIZACIÓN:</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo2">{{$clave_acceso}}</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo1">FECHA Y HORA DE AUTORIZACION:</span></div>
                <div style="width:100%" class="mb-3"><span class="titulo1-1">{{$factura->fmodifica}}</span></div>

                @if($empresa->ambiente==2)
                    <div style="width:100%" class="mb-1"><span class="titulo1">AMBIENTE:</span> <span class="titulo1-1 mr-4">PRODUCCION</span> </div>
                @else
                    <div style="width:100%" class="mb-1"><span class="titulo1">AMBIENTE:</span> <span class="titulo1-1 mr-4">PRUEBAS</span> </div>
                @endif
                @if($empresa->tipo_emision==1)
                    <div style="width:100%" class="mb-1"><span class="titulo1">EMISIÓN:</span> <span class="titulo1-1 mr-4">NORMAL</span> </div>
                @else
                    <div style="width:100%" class="mb-1"><span class="titulo1">EMISIÓN:</span> <span class="titulo1-1 mr-4">NORMAL</span> </div>
                @endif

                <div style="width:100%" class="mb-1"><span class="titulo1">CLAVE DE ACCESO</span> </div>
                <div style="width:100%;display:block">
                    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($clave_acceso, 'C128',3,33)}}" style="width:100%;height:65px">
                    <span style="display:block;font-size:10px;text-align:center">{{$clave_acceso}}</span>
                </div>
            </div>
            <div style="width: 100%;margin-top: -60px;" class="bordeado1">
                <div style="padding:5px">
                    <div style="width:100%"><span class="titulo3 bold">Razón Social / Nombres y Apellidos:</span> <span class="texto">{{$cliente->nombre}}</span></div>
                    <div style="width:100%"><span class="titulo3 bold" style="width: 48%; display: inline-block">RUC / CI: <span class="texto nobold">{{$cliente->identificacion}}</span></span> <span class="titulo3 bold" style="text-align: right">Correo: <span class="texto nobold">{{$cliente->email}}</span></span></div>
                    <div style="width:100%"><span class="titulo3 bold">Fecha de Emisión:</span> <span class="texto">{{$factura->fecha_emision}}</span></div>
                    <div style="width:100%"><span class="titulo3 bold">Dirección:</span> <span class="texto">{{$cliente->direccion}}</span></div>
                    <div style="width:100%"><span class="titulo3 bold" style="width: 48%; display: inline-block;">Teléfonos: <span class="texto nobold">{{$cliente->telefono}}</span></span>
                    @if($factura->clave_acceso_guia)
                        <span class="titulo3 bold" style="text-align: right">Guías de Remisión: <span class="texto nobold">{{substr($factura->clave_acceso_guia,24,3)}}-{{substr($factura->clave_acceso_guia,27,3)}}-{{substr($factura->clave_acceso_guia,30,9)}}</span></span></div>
                    @endif
                </div>
            </div>
        </div>
        <div style="margin-top:25px;">
            <table border="1" cellpadding="2px" cellspacing="0px"  style="width:100%">
                <thead>
                    <tr>
                        <th style="width:35px">Codigo</th>
                        <th style="width:35px">Cantidad</th>
                        <th>Descripción</th>
                        <th style="width:35px">prec. Unitario</th>
                        <th style="width:35px">Ice</th>
                        <th style="width:35px">Dscto.</th>
                        <th style="width:35px">Prec. Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $ice = 0;
                    ?>
                   @foreach($detalles as $detalle)
                        <tr>
                            <td class="text-center">{{$detalle->cod_principal}}</td>
                            <td class="text-center">{{$detalle->cantidad}}</td>
                            <td>{{$detalle->nombre}}</td>
                            <td class="text-center">{{$detalle->precio}}</td>
                            <td class="text-center">
                                <!-- @if($detalle->valor_ice>0)
                                    ${{number_format($detalle->valor_ice,2,'.','')}}
                                @elseif($detalle->total_ice_pr)
                                    ${{number_format($detalle->total_ice_pr * $detalle->cantidad,2,'.','')}}
                                @else
                                    $0.00
                                @endif -->
                                @if($detalle->total_ice_pr)
                                    ${{number_format($detalle->total_ice_pr * $detalle->cantidad,2,'.','')}}
                                @else
                                    $0.00  
                                @endif
                            </td>
                            <td class="text-center">@if($detalle->descuento) @if($detalle->p_descuento==1)$@endif{{$detalle->descuento}}@if($detalle->p_descuento==0)%@endif @else $0.00 @endif</td>
                            <td class="text-center">${{$detalle->total}}</td>
                        </tr>
                        <?php
                            if($detalle->total_ice_pr){
                                //$ice += $detalle->valor_ice;
                                $ice += $detalle->total_ice_pr * $detalle->cantidad;
                            } //* $detalle->cantidad;
                            // }else{
                            //     $ice += $detalle->total_ice_pr * $detalle->cantidad;
                            // }
                        ?>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div style="margin-bottom:30px;">
            <div style="float: left; width: 60%;">
                <div style="position:absolute">
                    <div style="margin-bottom:5px;margin-top:10px">Formas de pago:</div>
                    @foreach($pagos as $pago)
                        <div style="border: 1px solid #000;margin-bottom:5px;">
                            <div style="width:100%;margin-top:15px;">
                                <span class="titulo3 bold" style="width: 48%;display: inline-block;text-align:center">Forma de pago SRI <br><span class="nobold" style="font-size:8px">
                                    @if($pago->descripcion)
                                        {{$pago->descripcion}}
                                    @else
                                        OTROS CON UTILIZACION DEL SISTEMA FINANCIERO
                                    @endif
                                </span></span>
                                <span class="titulo3 bold" style="width: 48%;display: inline-block;text-align:center">Forma de pago <br><span class="nobold" style="font-size:8px">
                                    @if($pago->descripcionfp)
                                        {{$pago->descripcionfp}}
                                    @else
                                        CREDITO
                                    @endif
                                </span></span>
                            </div>
                            <div style="width:100%;margin-top:5px">
                                <span class="titulo3 bold" style="width: 23%;display: inline-block;text-align:center">Un. tiempo <br><span class="nobold" style="font-size:8px">{{$pago->unidad_tiempo}}</span></span>
                                <span class="titulo3 bold" style="width: 23%;display: inline-block;text-align:center">Tiempo <br><span class="nobold" style="font-size:8px">{{$pago->tiempos_pagos}}</span></span>
                                <span class="titulo3 bold" style="width: 23%;display: inline-block;text-align:center">Total <br><span class="nobold" style="font-size:8px">{{$pago->total}}</span></span>
                                <span class="titulo3 bold" style="width: 23%;display: inline-block;text-align:center">Plazo <br><span class="nobold" style="font-size:8px">{{$pago->plazo}}</span></span>
                            </div>
                        </div>
                    @endforeach
                    @if($factura->orden_compra || $factura->migo ||  $factura->observacion)
                        <div style="margin-bottom:5px;margin-top:10px">Información adicional:</div>
                        <div style="border: 1px solid #000;padding:2 5px;">
                            @if($factura->orden_compra)
                                <span class="titulo3 bold" style="width: 50%;display: inline-block;">
                                @if($empresa->compra)
                                    {{$empresa->compra}}
                                @else
                                    Orden de compra:
                                @endif
                                <span class="nobold">{{$factura->orden_compra}}</span></span>
                            @endif
                            @if($factura->migo)
                                <span class="titulo3 bold" style="width: 47%;display: inline-block;">
                                @if($empresa->migo)
                                    {{$empresa->migo}}
                                @else
                                    Migo:
                                @endif
                                <span class="nobold">{{$factura->migo}}</span></span>
                            @endif
                                <br>
                            @if($factura->observacion)
                                <span class="titulo3 bold" style="width: 98%;display: inline-block;">
                                @if($factura->id_empresa==52)
                                    Kilometraje:
                                @else
                                    Descripción:
                                @endif
                                <span class="nobold">{{$factura->observacion}}</span></span>
                            @endif
                        </div>
                    @endif
                    @if($empresa->id_empresa==54 && $empresa->nombre_empresa=='MEGASOFTDEV')
                        <div style="border: 1px solid #000;margin-top:15px;">
                            <div style="width:100%;margin-bottom:0px;border-bottom:1px solid #000;padding: 2px 5px;">
                                <span class="titulo3 nobold" style="display:block;text-align:center">POR FAVOR EMITIR EL CHEQUE A NOMBRE DE</span>
                                <span class="bold" style="font-size:16px;display:block;text-align:center">
                                    MEGASOFTDEV CORPORATION TECHSOLM S.A.S.
                                </span>
                            </div>
                            <div style="width: 100%;margin-top:0px;font-size:10px;text-align:center">
                                Realice sus depósitos y transferencias a:
                            </div>
                            <div style="width:100%;margin-top:15px">
                                <div style="width: 3%;display: inline-block"></div>
                                <div style="width: 45%;display: inline-block;text-align:center">
                                    <div style="display:block;font-size:11px;">BANCO PCHINCHA</div>
                                    <div style="display:block;font-size:11px;">Cuenta corriente</div>
                                    <div style="display:block;font-size:11px;">21002306640</div>
                                </div>
                                <div style="width: 45%;display: inline-block;text-align:center">
                                    <div style="display:block;font-size:11px;">MUTUALISTA PICHINCHA</div>
                                    <div style="display:block;font-size:11px;">Cuenta Ahorros</div>
                                    <div style="display:block;font-size:11px;">68035829</div>
                                </div>
                            </div>
                            <div style="width:100%;margin-top:5px;margin-bottom:5px;">
                                <div style="display:block;font-size:11px;text-align:center">RUC: 1793100147001</div>
                                <div style="display:block;font-size:11px;text-align:center"><a href = "mailto: pagos@sokai.com.ec">pagos@sokai.com.ec</a></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div style="margin-left: 65%; width: 35%;margin-top:10px">
                <div style="width: 100%; display: inline-block;border:1px solid #000;margin-top:5px">
                    <div style="width:100%;border-bottom: 1px solid #000;">
                        <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">SUBTOTAL SIN IMPUESTOS:</span>
                        <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->subtotal_sin_impuesto}}</span>
                    </div>
                    @if($ice>0)
                        <div style="width:100%;border-bottom: 1px solid #000;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">VALOR ICE:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{number_format($ice,2,'.','')}}</span>
                        </div>
                    @endif
                    @if($factura->subtotal_0>0)
                        <div style="width:100%;border-bottom: 1px solid #000;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">SUBTOTAL 0%:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->subtotal_0}}</span>
                        </div>
                    @endif
                    @if($factura->subtotal_no_obj_iva>0)
                        <div style="width:100%;border-bottom: 1px solid #000;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">SUBTOTAL NO OBJETO DE IVA:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->subtotal_no_obj_iva}}</span>
                        </div>
                    @endif
                    @if($factura->subtotal_12>0)
                        <div style="width:100%;border-bottom: 1px solid #000;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">SUBTOTAL 12%:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->subtotal_12}}</span>
                        </div>
                        <div style="width:100%;border-bottom: 1px solid #000;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">IVA 12% :</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->iva_12}}</span>
                        </div>
                    @endif
                    <div style="width:100%;border-bottom: 1px solid #000;">
                        <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">DESCUENTO:</span>
                        <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->descuento}}</span>
                    </div>
                    <div style="width:100%;border-bottom: 1px solid #000;">
                        <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">VALOR TOTAL:</span>
                        <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->valor_total}}</span>
                    </div>
                </div>
            </div>
        </div>
        @if($empresa->id_empresa==1)
            <footer style="position:absolute;margin-top:6em;">
                <div style="position:absolute;margin-left:0%;width:50%;height:100px">
                    <div style="display:block;text-align:center;">________________________</div>
                    <div style="display:block;text-align:center;">FIRMA AUTORIZADA</div>
                </div>
                <div style="position:absolute;margin-left:50%;width:50%;height:100px">
                    <div style="display:block;text-align:center;">________________________</div>
                    <div style="display:block;text-align:center;">FIRMA COMPRADOR</div>
                </div>
                <br><br><br>
                <div class="titulo1-1 text-center" style="text-align:center">
                    Recibí conforme la mercadería detallada en la presente factura, cuyo valor Debo y Pagaré con cheque cruzado a nombre de HUGO SOLIS GOMEZJURADO
                </div>
            </footer>
        @endif
    </body>
</html>
