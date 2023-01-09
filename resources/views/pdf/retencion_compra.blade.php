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
                            @if($empresa->telefono!=null && $empresa->telefono!='null' && $empresa->telefono!=0)
                                <span class="texto mr-4">{{$empresa->telefono}}</span>
                            @endif
                        </div>
                        <div style="width:100%" class="mb-1 text-center">
                            @if($empresa->urlweb!=null && $empresa->urlweb!='null')
                                <span class="titulo3 bold text-center">{{$empresa->urlweb}}</span>
                            @endif
                        </div>
                        @if($empresa->noresolucion)
                            <div style="width:100%" class="mb-1"><span class="titulo3 bold">Contribuyente Especial Nro: </span> <span class="texto">{{$empresa->noresolucion}}</span></div>
                        @endif
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
                <div style="width:100%"><span class="titulo1">COMPROBANTE DE RETENCIÓN:</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo2">N° {{substr($factura->observacion,24,3)}}-{{substr($factura->observacion,27,3)}}-{{substr($factura->observacion,30,9)}}</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo1">NÚMERO DE AUTORIZACIÓN:</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo2">{{$factura->observacion}}</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo1">FECHA Y HORA DE AUTORIZACION:</span></div>
                @if(isset($factura->fecha_factura))
                    <div style="width:100%" class="mb-3"><span class="titulo1-1">{{$factura->fecha_factura}}</span></div>
                @else
                    <div style="width:100%" class="mb-3"><span class="titulo1-1">{{$factura->fecha_envio}}</span></div>
                @endif
                

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
                    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($factura->observacion, 'C128',3,33)}}" style="width:100%;height:65px">
                    <span style="display:block;font-size:10px;text-align:center">{{$factura->observacion}}</span>
                </div>
            </div>
            <div style="width: 100%;margin-top: -60px;" class="bordeado1">
                <div style="padding:5px">
                    <div style="width:100%"><span class="titulo3 bold">Razón Social / Nombres y Apellidos:</span> <span class="texto">{{$proveedor->nombre_proveedor}}</span></div>
                    <div style="width:100%"><span class="titulo3 bold" style="width: 48%; display: inline-block">RUC / CI: <span class="texto nobold">{{$proveedor->identif_proveedor}}</span></span> <span class="titulo3 bold" style="text-align: right">Correo: <span class="texto nobold">{{$proveedor->email}}</span></span></div>
                    <div style="width:100%"><span class="titulo3 bold">Fecha de Emisión:</span> <span class="texto">{{$factura->fech_emision}}</span></div>
                    <div style="width:100%"><span class="titulo3 bold">Dirección:</span> <span class="texto">{{$proveedor->direccion_prov}}</span></div>
                    @if($proveedor->telefono_prov!=0)
                        <div style="width:100%"><span class="titulo3 bold" style="width: 48%; display: inline-block;">Teléfonos: <span class="texto nobold">{{$proveedor->telefono_prov}}</span></span>
                    @endif
                </div>
            </div>
        </div>
        <div style="margin-top:25px;">
            <table border="1" cellpadding="2px" cellspacing="0px"  style="width:99%">
                <thead>
                    <tr>
                        <th style="width:40px">Comprobante</th>
                        <th style="width:40px">Número</th>
                        <th style="width:40px">Fecha Emisión</th>
                        <!--<th style="width:20px">Ejercicio Fiscal</th>-->
                        <th style="width:20px">Base Imponible</th>
                        <th style="width:20px">Impuesto</th>
                        <th style="width:20px">Código</th>
                        <th style="width:20px">Porcentaje de Retención</th>
                        <th style="width:20px">Valor Retenido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total = 0;
                    ?>

                    @foreach($retenciones as $retencion)
                        @if($retencion->cantidadrenta)
                            <tr>
                            @if(isset($factura->id_liquidacion_compra))
                                <td class="text-center">LIQUIDACION COMPRA</td>
                            @else
                                <td class="text-center">FACTURA</td>
                            @endif
                                
                                <td class="text-center">{{substr($retencion->descripcion,0,3)}}-{{substr($retencion->descripcion,3,3)}}-{{substr($retencion->descripcion,6,15)}}</td>
                                <td class="text-center">{{$retencion->dia}}-{{$retencion->mes}}-{{$retencion->anio}}</td>
                                <td class="text-center">${{$retencion->baserenta}}</td>
                                <td class="text-center">RENTA</td>
                                <td class="text-center">{{$retencion->cod_retencion_renta}}</td>
                                <td class="text-center">{{$retencion->porcentajerenta}}</td>
                                <td class="text-center">${{$retencion->cantidadrenta}}</td>
                            </tr>
                            <?php
                                $total = $total + $retencion->cantidadrenta;
                            ?>
                        @endif
                        @if($retencion->cantidadiva)
                            <?php
                                $p_iva = intval(str_replace("%", '', $retencion->porcentajeiva));
                            ?>
                            <tr>
                            
                            @if(isset($factura->id_liquidacion_compra))
                                <td class="text-center">LIQUIDACION COMPRA</td>
                            @else
                                <td class="text-center">FACTURA</td>
                            @endif
                                <td class="text-center">{{substr($retencion->descripcion,0,3)}}-{{substr($retencion->descripcion,3,3)}}-{{substr($retencion->descripcion,6,15)}}</td>
                                <td class="text-center">{{$retencion->dia}}-{{$retencion->mes}}-{{$retencion->anio}}</td>
                                <td class="text-center">${{ number_format(($retencion->cantidadiva * 100) / $p_iva, 2, '.', '') }}</td>
                                <td class="text-center">IVA</td>
                                <td class="text-center">{{$retencion->cod_retencion_iva}}</td>
                                <td class="text-center">{{$retencion->porcentajeiva}}</td>
                                <td class="text-center">${{$retencion->cantidadiva}}</td>
                            </tr>
                            <?php
                                $total = $total + $retencion->cantidadiva;
                            ?>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-bottom:30px;">
            <div style="margin-left: 70%; width: 30%;margin-top:10px">
                <div style="width: 100%; display: inline-block;border:1px solid #000;margin-top:5px">
                    <div style="width:100%;border-bottom: 1px solid #000;">
                        <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">VALOR TOTAL:</span>
                        <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{number_format($total, 2, '.', '')}}</span>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
