<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{$codigo}}</title>
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
                padding: 0;
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
            .text-right {
                text-align: right !important;
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
                            <div style="vertical-align:middle;top:60%;">
                                <img src="{{ storage_path('logos/'.$proformas[0]->logo) }}" alt="logo" style="max-width:100%;max-height:160px;text-align:center;">
                            </div>
                        </div>

                    </div>

                    <div style="width:100%;" class="bordeado">
                        <div class="titulo1-1-1">{{$factura_info2->razon_social}}</div>
                        <div style="width:100%" class="titulo3 mb-3">{{$proformas[0]->nombre_empresa}}</div>
                        <div style="width:100%" class="mb-1"><span class="titulo3 bold">Dirección Matriz:</span>
                            @if($proformas[0]->direccion_empresa!=null && $proformas[0]->direccion_empresa!='null')
                                <span class="texto">{{$proformas[0]->direccion_empresa}}</span>
                            @endif
                        </div>
                        <div style="width:100%" class="mb-1"><span class="titulo3 bold">Contacto:</span>
                            @if($factura_info2->telefono!=null && $factura_info2->telefono!='null')
                                <span class="texto mr-4">{{$factura_info2->telefono}}</span>
                            @endif
                        </div>
                        <div style="width:100%" class="mb-1 text-center">
                            @if($factura_info2->urlweb!=null && $factura_info2->urlweb!='null')
                                <span class="titulo3 bold text-center">{{$factura_info2->urlweb}}</span>
                            @endif
                        </div>
                        
                    </div>
                    
                </div>
                
            </div>
            
            <div style="width: 43%; display: inline-block;" class="bordeado">
                <div style="width:100%" class="mb-1"><span class="titulo1">R.U.C.:</span></div>
                <div style="width:100%"><span class="titulo1-1">{{$factura_info2->ruc_empresa}}</span></div>
                <div style="width:100%"><span class="titulo1">PROFORMA</span></div>
                <div style="width:100%" class="mb-1"><span class="titulo2">N° {{$codigo}}</span></div>
                <div style="width:100%"><span class="titulo1">Documento sin Valor Tributario</span></div>
                
            </div>
            
                <div style="width: 100%;margin-top: -10px;" class="bordeado1">
                    <div style="padding:5px">
                        <div style="width:100%"><span class="titulo3 bold">Razón Social / Nombres y Apellidos:</span> <span class="texto">{{$proformas[0]->cliente}}</span></div>
                        <div style="width:100%"><span class="titulo3 bold" style="width: 48%; display: inline-block">RUC / CI: <span class="texto nobold">{{$proformas[0]->identificacion}}</span></span> <span class="titulo3 bold" style="text-align: right">Correo: <span class="texto nobold">{{$proformas[0]->email}}</span></span></div>
                        <div style="width:100%"><span class="titulo3 bold">Fecha de Emisión:</span> <span class="texto">{{$proformas[0]->fecha_emision}}</span></div>
                        <div style="width:100%"><span class="titulo3 bold">Dirección:</span> <span class="texto">{{$proformas[0]->direccion}}</span></div>
                        <div style="width:100%"><span class="titulo3 bold" style="width: 48%; display: inline-block;">Teléfonos: <span class="texto nobold">{{$proformas[0]->telefono}}</span></span>
                    </div>
                </div>
            
        </div>
        
        <div style="margin-top:25px;">
            <table border="1" cellpadding="2px" cellspacing="0px"  style="width: 740px;">
                <thead>
                    <tr>
                        <th style="width:35px">Codigo</th>
                        <th style="width:35px">Cantidad</th>
                        <th style="width:400px">Descripción</th>
                        <th style="width:35px">prec. Unitario</th>
                        <th style="width:35px">Ice</th>
                        <th style="width:35px">Dscto.</th>
                        <th style="width:35px">Prec. Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $ice = 0;
                        $sub_total=0;
                        $descuento=0;
                        $iva=0;
                        $total_fact=0;
                        $subtotal_0=0;
                    ?>
                    @foreach($proformas as $detalle)
                        <tr>
                            @if($detalle->cod_alterno!==null)
                                <td class="text-center">{{$detalle->cod_alterno}}</td>
                            @else
                                <td class="text-center">{{$detalle->cod_principal}}</td>
                            @endif
                            <td class="text-center">{{$detalle->cantidad}}</td>
                            <td>{{$detalle->nombre}}</td>
                            <td class="text-right">{{number_format($detalle->precio,2,".",",")}}</td>
                            <td class="text-right">
                                    $0.00
                            
                            </td>
                            <td class="text-right">{{$detalle->descuento}}</td>
                            <td class="text-right">${{$detalle->total_pro}}</td>
                        </tr>
                        <?php
                            
                                $ice += 0;
                                $sub_total=$detalle->subtotal;
                                $descuento=$detalle->descuento;
                                $iva=$detalle->iva;
                            
                                $total_fact=$detalle->total;
                        ?>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div style="margin-bottom:30px;">
            <div style="float: left; width: 60%;">
                <div style="position:absolute">
                    <div style="margin-bottom:5px;margin-top:10px">Formas de pago:</div>
                    
                        <div style="border: 1px solid #000;margin-bottom:5px;">
                            <div style="width:100%;margin-top:15px;">
                            
                                <span class="titulo3 bold" style="width: 98%;display: inline-block;text-align:center">Forma de pago <br><span class="nobold" style="font-size:8px">
                                    @if($factura_info2->forma_pago)
                                        {{$factura_info2->forma_pago}}
                                    @endif
                                </span></span>
                            </div>
                            
                        </div>
                    
                    @if($factura_info2->observacion)
                        <div style="margin-bottom:5px;margin-top:10px">Información adicional:</div>
                        <div style="border: 1px solid #000;padding:2 5px;">

                            @if($factura_info2->observacion)
                                <span class="titulo3 bold" style="width: 98%;display: inline-block;">
                                @if($factura_info2->id_empresa==52)
                                    Kilometraje:
                                @else
                                    Descripción:
                                @endif
                                <span class="nobold">{{$factura_info2->observacion}}</span></span>
                            @endif
                        </div>
                    @endif
                    
                </div>
            </div>
            <div style="margin-left: 65%; width: 35%;margin-top:10px">
                <div style="width: 100%; display: inline-block;border:1px solid #000;margin-top:5px">
                    <div style="width:100%;border-bottom: 1px solid #000;">
                        <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">SUBTOTAL SIN IMPUESTOS:</span>
                        <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{number_format($sub_total,2,".",",")}}</span>
                    </div>
                    @if($ice>0)
                        <div style="width:100%;border-bottom: 1px solid #000;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">VALOR ICE:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{number_format($ice,2,'.','')}}</span>
                        </div>
                    @endif
                    
                        <div style="width:100%;border-bottom: 1px solid #000;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">IVA 12% :</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{number_format($iva,2,".",",")}}</span>
                        </div>
                    
                    <div style="width:100%;border-bottom: 1px solid #000;">
                        <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">DESCUENTO:</span>
                        <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{number_format($descuento,2,".",",")}}</span>
                    </div>
                    <div style="width:100%;border-bottom: 1px solid #000;">
                        <span class="bold" style="font-size:9px;width: 60%;display: inline-block;padding-left:8px;">VALOR TOTAL:</span>
                        <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{number_format($total_fact,2,".",",")}}</span>
                    </div>
                </div>
            </div>
        </div>
        


    
    </body>
</html>
