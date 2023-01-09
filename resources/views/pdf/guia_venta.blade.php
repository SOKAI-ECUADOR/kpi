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
                font-size: 10px;
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
                        <div style="width:100%;display:block;margin-top:-10px;" class="titulo2 mb-3">COMPROBANTE GENERADO EN EL AMBIENTE DE 
                            @if($empresa->ambiente==2)
                                PRODUCCION
                            @else
                                PRUEBAS
                            @endif
                        </div>
                    </div>
                    <div style="width:100%;" class="bordeado">
                        <div class="titulo1-1-1 mb-3">{{$empresa->razon_social}}</div>
                        <div style="width:100%" class="titulo3 mb-3">{{$empresa->nombre_empresa}}</div>
                        <div style="width:100%" class="mb-1"><span class="titulo3 bold">Dirección Matriz:</span> <span class="texto">{{$empresa->direccion_empresa}}</span></div>
                        <div style="width:100%" class="mb-1"><span class="titulo3 bold">Contacto:</span> <span class="texto mr-4">{{$empresa->telefono}}</span></div>
                        <div style="width:100%" class="mb-1 text-center"><span class="titulo3 bold text-center">{{$empresa->urlweb}}</span></div>
                        @if($empresa->noresolucion)
                            <div style="width:100%" class="mb-1"><span class="titulo3 bold">Contribuyente Especial Nro: </span> <span class="texto">{{$empresa->noresolucion}}</span></div>
                        @endif
                        @if($empresa->obligado_contabilidad==1)
                            <div style="width:100%" class="mb-1"><span class="titulo3 bold">OBLIGADO A LLEVAR CONTABILIDAD:</span> <span class="texto">SI</span></div>
                        @else
                            <div style="width:100%" class="mb-1"><span class="titulo3 bold">OBLIGADO A LLEVAR CONTABILIDAD:</span> <span class="texto">NO</span></div>
                        @endif
                        @if($empresa->leyenda==2)
                            <div style="width:100%" class="mb-1 text-center"><span class="titulo3 text-center">AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN MICROEMPRESA</span></div>
                        @elseif($empresa->leyenda==3)
                            <div style="width:100%" class="mb-1 text-center"><span class="titulo3 text-center">AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN RIMPE</span></div>
                        @elseif($empresa->leyenda!=null && $empresa->leyenda!='null')
                            <div style="width:100%" class="mb-1 text-center"><span class="titulo3 text-center">{{$empresa->leyenda}}</span></div>
                        @endif
                    </div>
                </div>
            </div>
            <div style="width: 43%; display: inline-block;" class="bordeado">
                <div style="width:100%" class="mb-2"><span class="titulo1">R.U.C.:</span> <span class="titulo1-1">{{$empresa->ruc_empresa}}</span></div>
                <div style="width:100%"><span class="titulo1">GUÍA DE REMISIÓN N°:</span></div>
                <div style="width:100%" class="mb-2"><span class="titulo2">N° {{substr($clave_acceso,24,3)}}-{{substr($clave_acceso,27,3)}}-{{substr($clave_acceso,30,9)}}</span></div>
                <div style="width:100%" class="mb-2"><span class="titulo1">NÚMERO DE AUTORIZACIÓN:</span></div>
                <div style="width:100%" class="mb-2"><span class="titulo2">{{$clave_acceso}}</span></div>
                <div style="width:100%" class="mb-2"><span class="titulo1">FECHA Y HORA DE AUTORIZACION:</span></div>
                <div style="width:100%" class="mb-3"><span class="titulo1-1">{{$guia->fecha_factura}}</span></div>

                @if($empresa->ambiente==2)
                    <div style="width:100%" class="mb-2"><span class="titulo1">AMBIENTE:</span> <span class="titulo1-1 mr-4">PRODUCCION</span> </div>
                @else
                    <div style="width:100%" class="mb-2"><span class="titulo1">AMBIENTE:</span> <span class="titulo1-1 mr-4">PRUEBAS</span> </div>
                @endif
                @if($empresa->tipo_emision==1)
                    <div style="width:100%" class="mb-2"><span class="titulo1">EMISIÓN:</span> <span class="titulo1-1 mr-4">NORMAL</span> </div>
                @else
                    <div style="width:100%" class="mb-2"><span class="titulo1">EMISIÓN:</span> <span class="titulo1-1 mr-4">NORMAL</span> </div>
                @endif
                
                <div style="width:100%" class="mb-2"><span class="titulo1">CLAVE DE ACCESO</span> </div>
                <div style="width:100%;display:block" class="mb-2">
                    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($clave_acceso, 'C128',3,33)}}" style="width:100%;height:65px">
                    <span style="display:block;font-size:10px;text-align:center">{{$clave_acceso}}</span>
                </div>
            </div>
            <div style="width: 100%;margin-top: -55px;" class="bordeado1">
                <div style="padding:5px">
                    <div style="width:100%" class="mb-1"><span class="titulo3 bold">Razón Social / Nombres y Apellidos:</span> <span class="texto">{{$guia->razon_social_tr}}</span></div>
                    <div style="width:100%" class="mb-1"><span class="titulo3 bold">RUC / CI (Transportista):</span> <span class="texto">{{$guia->identificacion_tr}}</span></div>

                    <div style="width:100%"><span class="titulo3 bold">Placa:</span> <span class="texto">{{$guia->placa_tr}}</span></div>
                    @if($guia->dir_partida_tr)
                        <div style="width:100%"><span class="titulo3 bold">Punto de Partida:</span> <span class="texto">{{$guia->dir_partida_tr}}</span></div>
                    @else
                        <div style="width:100%"><span class="titulo3 bold">Punto de Partida:</span> <span class="texto">{{$empresa->direccion_empresa}}</span></div>
                    @endif
                    <div style="width:100%"><span class="titulo3 bold" style="width: 48%; display: inline-block;">Fecha Inicio Transporte: <span class="texto nobold">{{$guia->fecha_inicio_tr}}</span></span> <span class="titulo3 bold" style="text-align: right">Fecha fin Transporte: <span class="texto nobold">{{$guia->fecha_fin_tr}}</span></span></div>
                </div>
            </div>
            <div style="width: 100%;" class="mt-3">
                @if(isset($factura->clave_acceso))
                    <div style="width:100%" class="mb-3"><span class="titulo2 bold">Comprobante de Venta: <span class="texto nobold">N° {{substr($factura->clave_acceso,24,3)}}-{{substr($factura->clave_acceso,27,3)}}-{{substr($factura->clave_acceso,30,9)}} </span></span>  <span class="titulo3 bold ml-5" style="text-align: right">Fecha de Emisión: <span class="texto nobold">{{$factura->fmodifica}}</span></span></div>
                    <div style="width:100%" class="mb-3"><span class="titulo2 bold">Número de Autorización: <span class="texto nobold">{{$factura->clave_acceso}} </span></span></div>
                @endif
                <div style="width:100%" class="mb-3"><span class="titulo2 bold">Motivo Translado: <span class="texto nobold">{{$guia->motivo_translado_tr}} </span></span></div>
                @if($guia->destino_tr)
                    <div style="width:100%" class="mb-3"><span class="titulo2 bold">Destino (Punto de llegada): <span class="texto nobold">{{$guia->destino_tr}} </span></span></div>
                @else
                    <div style="width:100%" class="mb-3"><span class="titulo2 bold">Destino (Punto de llegada): <span class="texto nobold">{{$cliente->direccion}} </span></span></div>
                @endif
                
                <div style="width:100%" class="mb-3"><span class="titulo2 bold">RUC/CI (Destinatario): <span class="texto nobold">{{$cliente->identificacion}} </span></span>  <span class="titulo3 bold ml-5" style="text-align: right">Razón Social / Nombres Apellidos: <span class="texto nobold">{{$cliente->nombre}}</span></span></div>
                <!-- <div style="width:100%" class="mb-3"><span class="titulo2 bold">Ruta: <span class="texto nobold">{{$guia->ruta_tr}} </span></span></div> -->
            </div>
        </div>

        <div>
            <table border="1" cellpadding="2px" cellspacing="0px"  style="width:100%">
                <thead>
                    <tr>
                        <th style="width:50px">Código</th>
                        @foreach($detalles as $detalle)
                            @if($detalle->item)
                                <th style="width:55px">Items</th>
                                @break
                            @endif
                        @endforeach
                        <th>Descripción</th>
                        <th style="width:40px">Cant</th>
                    </tr>  
                </thead>
                <tbody>
                    @foreach($detalles as $detalle)
                        <tr>
                            @if($detalle->cod_alterno)
                                <td class="text-center">{{$detalle->cod_alterno}}</td>
                            @else
                                <td class="text-center">{{$detalle->cod_principal}}</td>
                            @endif
                            @if($detalle->item)
                                <td class="text-center">{{$detalle->item}}</td>
                            @endif
                            <td>{{$detalle->descripcion}}</td>
                            <td class="text-center">{{$detalle->cantidad}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($guia->observacion_tr)
                        <div style="margin-bottom:5px;margin-top:10px">Información adicional:</div>
                        <div style="border: 1px solid #000;padding:10px;">
                             
                           
                                <span class="titulo3 bold" style="width: 98%;display: inline-block;">
                                
                                    Observacion:
                                
                                <span class="nobold">{{$guia->observacion_tr}}</span></span>
                            
                        </div>
        @endif
        @if($empresa->ruc_empresa=='1792640105001')
            <div style="display: inline-block; margin-top: 100px;">
                <div style="width: 50%; float: left; margin-left: 50px;">
                    <div style="font-size: 12px; color: #000;">______________________________________</div>
                    <div style="font-size: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transportista Responsable</div>
                    <div style="font-size: 12px;"><b>Nombre:</b> {{$guia->razon_social_tr}}</div>
                    <div style="font-size: 12px;"><b>C.I:</b> {{$guia->identificacion_tr}}</div>
                    <div style="font-size: 12px;"><b>Fecha y Hora:</b> </div>
                </div>
                <div style="width: 50%; float: right; margin-right: -50px;">
                    <div style="font-size: 12px; color: #000;">______________________________________</div>
                    <div style="font-size: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cliente Recib&iacute; Conforme</div>
                    <div style="font-size: 12px;"><b>Nombre:</b></div>
                    <div style="font-size: 12px;"><b>C.I:</b></div>
                    <div style="font-size: 12px;"><b>Fecha y Hora:</b> </div>
                </div>
            </div>
        @endif
                        
    </body>
</html>