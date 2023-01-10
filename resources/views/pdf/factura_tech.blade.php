<!DOCTYPE html>
<style>
        *{margin:0;padding:0;}
        html{background:white;}
        body{width:750px;height:750px;margin:auto;background:white;}
        header{width:750px;height:280px;background:white;}
        section#banner{width:375px;height:280px;float:left;background:white;}
        section#logo{width:375px;height:95px;float:left;background:white;}
        section#login{width:375px;height:125px;float:left;background:white;}
        nav{width:750px;height:120px;background:white;}
        main{width:750px;height:550px;background:white;}
        .titulo3 {
            font-size: 11px;
        }
        .titulo4 {
            font-size: 13px;
        }
        .titulo5 {
            font-size: 9px;
        }
        .titulo6 {
            font-size: 10px;
        }
        .bold {
            font-weight: bold;
        }
        a { color: #000000; text-decoration:"none"; }
        .text-center {
            text-align: center !important;
            width: 100%;
        }
        table {
            border-collapse: collapse;
        }
        table thead tr th{
            border-bottom: 1px solid #000000;
        }
        table th, table td {
            border-left: 1px solid rgba(0,0,0,0.2);
            border-right: 1px solid rgba(0,0,0,0.2);
        }
        table th, table td{ /* Added padding for better layout after collapsing */
            padding: 4px 8px;
        }
        .nobold {
            font-weight: 50;
        }

        .texto {
            font-size: 10px;
        }
        .columna {
            width:33.33%;
            height:100px;
            display: inline-block
        }

        /* @media (max-width: 500px) {
            
            .columna {
                width:auto;
                float:none;
            }
            
        } */
        
    </style>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{$clave_acceso}}</title>
    
</head>

<body>

    <header>
        <section id="banner">
                    <div style="text-align:center">
                        <img src="{{ storage_path('logos/'.$empresa->logo) }}" alt="logo" style="max-width:180px;max-height:180px;text-align:center;">  
                    </div>
                    <br>
                    <div style="width:100%" class="titulo5"><b>{{$empresa->razon_social}}</b></div>
                    <div style="width:100%" class="titulo6">{{$empresa->nombre_empresa}}</div>
                    <div style="width:100%"><span class="titulo6 bold">RUC:</span> <span class="titulo6">{{$empresa->ruc_empresa}}</span></div>
                    <div style="width:100%" class="titulo6" ><span class="titulo6 bold">DIR. MATRIZ:</span>
                        @if($empresa->direccion_empresa!=null && $empresa->direccion_empresa!='null')
                        <span class="texto" class="titulo6">{{$empresa->direccion_empresa}}</span>
                        @endif
                    </div>
                    <div style="width:100%" ><span class="titulo6 bold">TELÉFONO:</span>
                        @if($empresa->telefono!=null && $empresa->telefono!='null')
                        <span class="titulo6">{{$empresa->telefono}}</span>
                        @endif
                    </div>
                    <div style="width:100%" ><span class="titulo6 bold">E-MAIL:</span> <span class="titulo6">{{$empresa->email_empresa}}</span></div>
                    <div style="width:100%">
                        <span class="titulo3 bold">WEB:</span>
                        @if($empresa->urlweb!=null && $empresa->urlweb!='null')
                        <span class="titulo3" style="font-size: 10.5px;">{{$empresa->urlweb}}</span>
                        @endif
                    </div>
                    @if($empresa->nombre_empresa=="C.E. FUEGOS GROUP")
                        <div style="width:100%" class="titulo3"><span class="titulo3 bold">Ventas: </span> <span class="titulo3;texto">cefuegos@yahoo.es</span></div>
                        <div style="width:100%" class="titulo3"><span class="titulo3 bold">Contabilidad: </span> <span class="titulo3;texto">group.contabilidad@outlook.com</span></div>
                    @endif
                    @if($empresa->nombre_empresa=="C.E.FUEGOS SYSTEM")
                        <div style="width:100%" class="titulo3"><span class="titulo3 bold">Ventas: </span> <span class="titulo3;texto">cefuegos@yahoo.es</span></div>
                        <div style="width:100%" class="titulo3"><span class="titulo3 bold">Contabilidad: </span> <span class="titulo3;texto">cefuegos.system@hotmail.com</span></div>
                    @endif
                    @if($empresa->nombre_empresa=="C.E. FUEGOS")
                        <div style="width:100%" class="titulo3"><span class="titulo3 bold">Ventas: </span> <span class="titulo3;texto">cefuegos@yahoo.es</span></div>
                        <div style="width:100%" class="titulo3"><span class="titulo3 bold">Contabilidad: </span> <span class="titulo3;texto">cefuegos.asistente@gmail.com</span></div>
                    @endif
                    @if($empresa->noresolucion)
                        <div style="width:100%" class="titulo3"><span class="titulo3 bold">Contribuyente Especial Nro: </span> <span class="titulo3;texto" >{{$empresa->noresolucion}}</span></div>
                    @endif
                    @if($empresa->obligado_contabilidad==1)
                    <div style="width:100%" class="titulo3"><span class="titulo3 bold">OBLIGADO A LLEVAR CONTABILIDAD:</span> <span class="titulo3;texto">SI</span></div>
                    @else
                    <div style="width:100%" class="titulo3"><span class="titulo3 bold">OBLIGADO A LLEVAR CONTABILIDAD:</span> <span class="titulo3;texto">NO</span></div>
                    @endif
                    @if($empresa->leyenda==2)
                    <div style="width:100%" ><span class="titulo3 bold" style="font-size: 9px;">AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN MICROEMPRESA</span></div>
                    @elseif($empresa->leyenda!=null && $empresa->leyenda!='null')
                    <div style="width:100%" ><span class="titulo3 bold" style="font-size: 9px;">{{$empresa->leyenda}}</span></div>
                    @endif
        </section>
        <section id="logo">
                    <div style="float:right">
                        <br>
                        <br>
                        <div style="width:100%"><span class="titulo4 bold">FACTURA No. <font color="red">{{substr($clave_acceso,24,3)}}-{{substr($clave_acceso,27,3)}}-{{substr($clave_acceso,30,9)}}</font></span></div>
                        <div style="width:100%"><span class="titulo4">{{$factura->fecha_factura}}</span></div> 
                    </div>
        </section>
        
        <div sytyle="width:375px;float:left;">
            <br>
            <div style="width:100%" class="titulo4"><b> </b></div>
            <br>
            <div style="width:100%" class="titulo4"><b> </b></div>
            <br>
            <div style="width:100%" class="titulo4"><b> </b></div>
            <br>
            <div style="width:100%" class="titulo4"><b>Número de autorización:</b></div>
            <div style="width:100%" class="titulo4"><font color="red">{{$clave_acceso}}</font> </div>
            <div style="width:100%" ><span class="titulo4 bold">Ambiente:</span> 
                <span class="titulo4">
                            @if($empresa->ambiente==2)
                                PRODUCCION
                            @else
                                PRUEBAS
                            @endif
                </span>
            </div>
            
            @if($empresa->tipo_emision==1)
                <div style="width:100%" ><span class="titulo4 bold">Emisión:</span> <span class="titulo4">NORMAL</span> </div>
            @else
                <div style="width:100%" ><span class="titulo4 bold">Emisión:</span> <span class="titulo4">NORMAL</span> </div>
            @endif
            
            <div style="width:100%" class="titulo4"><b>Clave de acceso:</b></div>
            <div style="width:100%;display:block">
                <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($clave_acceso, 'C128',3,33)}}" style="width:375px;height:11px">
                <span style="display:block;font-size:13px;text-align:center">{{$clave_acceso}}</span>
            </div>
        </div>
    </header>
    <nav>
        <hr style="border:0.7px;">
        <div style="width:100%" ><span class="titulo3 bold">CLIENTE:</span> <span class="titulo3">{{$cliente->nombre}}</span> </div>
        <div style="width:100%" ><span class="titulo3 bold">RUC / CI:</span> <span class="titulo3">{{$cliente->identificacion}}</span> </div>
        <div style="width:100%" ><span class="titulo3 bold">E-MAIL:</span> <span class="titulo3">{{$cliente->email}}</span> </div>
        <div style="width:100%" ><span class="titulo3 bold">FECHA DE EMISIÓN:</span> <span class="titulo3">{{$factura->fecha_emision}}</span> </div>
        <div style="width:100%" ><span class="titulo3 bold">DIRECCIÓN:</span> <span class="titulo3">{{$cliente->direccion}}</span> </div>
        <div style="width:100%" ><span class="titulo3 bold">TELÉFONO:</span> <span class="titulo3">{{$cliente->telefono}}</span> </div>
        
    </nav>
    <main>
    
        <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
            <thead>
                <tr>
                    <th style="width:50px;height: 18.5px;"  class="titulo3">Cantidad</th>
                    <th style="width:50px;height: 18.5px;"  class="titulo3">Codigo</th>
                    <th style="width:230px;height: 18.5px;"  class="titulo3">Descripcion</th>
                    <th style="width:70px;height: 18.5px;"  class="titulo3">Precio Unitario</th>
                    <th style="width:50px;height: 18.5px;"  class="titulo3">Descuento</th>
                    <th style="width:50px;height: 18.5px;"  class="titulo3">Ice</th>
                    <th style="width:50px;height: 18.5px;"  class="titulo3">Total</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $ice = 0;
                $comida=0;
                ?>
                @foreach($detalles as $detalle)
                <tr>
                    <td class="titulo3 text-center">{{$detalle->cantidad}}</td>
                    <td class="titulo3 text-center">{{$detalle->cod_principal}}</td>
                    <td class="titulo3 text-center">{{$detalle->nombre}}</td>
                    <td class="titulo3 text-center">${{$detalle->precio}}</td>
                    <td class="titulo3 text-center">@if($detalle->descuento) @if($detalle->p_descuento==1)$@endif{{$detalle->descuento}}@if($detalle->p_descuento==0)%@endif @else $0.00 @endif</td>
                    <td class="titulo3 text-center">
                        <!-- @if($detalle->valor_ice>0)
                                    ${{number_format($detalle->valor_ice,2,'.','')}}
                                @elseif($detalle->total_ice_pr)
                                    ${{number_format($detalle->total_ice_pr * $detalle->cantidad,2,'.','')}}
                                @else
                                    $0.00
                                @endif -->
                        @if($detalle->total_ice_pr)
                        ${{bcdiv($detalle->total_ice_pr * $detalle->cantidad,2,'.','')}}
                        @else
                        $0.00
                        @endif
                    </td>
                    
                    <td class="titulo3 text-center">${{$detalle->total}}</td>
                </tr>
                <?php
                if ($detalle->total_ice_pr) {
                    //$ice += $detalle->valor_ice;
                    $ice += $detalle->total_ice_pr * $detalle->cantidad;
                }
                ?>
                @endforeach
            </tbody>
        </table>
        <div style="width: 750px;height: 120px;">
                
            <div style="width: 520px;height: 120px;display: inline-block;float:left">
                        
                         
            @foreach($pagos as $pago)
                <div style="margin-bottom:5px;">
                    <div style="width:100%;margin-top:30px;">
                            <span class="titulo3 bold" style="width: 48%;display: inline-block">Forma de pago SRI <br><span class="nobold" style="font-size:9px">
                                @if($pago->descripcion)
                                {{$pago->descripcion}}
                                @else
                                OTROS CON UTILIZACION DEL SISTEMA FINANCIERO
                                @endif
                            </span></span>
                            @if($empresa->nombre_empresa!=='C.E. FUEGOS GROUP' && $empresa->nombre_empresa!=='C.E.FUEGOS SYSTEM' && $empresa->nombre_empresa!=='C.E. FUEGOS')
                                <span class="titulo3 bold" style="width: 48%;display: inline-block">Forma de pago <br><span class="nobold" style="font-size:9px">
                                    @if($pago->descripcionfp)
                                    {{$pago->descripcionfp}}
                                    @else
                                    CREDITO
                                    @endif
                                </span></span>
                            @endif
                    </div>
                    <div style="width:100%;margin-top:5px">
                        <span class="titulo3 bold" style="width: 36%;display: inline-block">Periodo de Pago <br><span class="nobold" style="font-size:10px">{{$pago->unidad_tiempo}}</span></span>
                        <span class="titulo3 bold" style="width: 20%;display: inline-block">Tiempo de Pago <br><span class="nobold" style="font-size:10px">{{$pago->tiempos_pagos}}</span></span>
                        <span class="titulo3 bold" style="width: 20%;display: inline-block">Monto de  Pago<br><span class="nobold" style="font-size:11px">{{$pago->total}}</span></span>
                        <span class="titulo3 bold" style="width: 18%;display: inline-block">Plazo de Pago <br><span class="nobold" style="font-size:10px">{{$pago->plazo}}</span></span>
                    </div>
                </div>
                @endforeach
                        
                        
                        
            </div>     
            <div style="width: 230px;height: 120px;display: inline-block;float:left;" >
                        <div style="width:100%;margin-top:15px;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">SUBTOTAL SIN IMPUESTOS:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->subtotal_sin_impuesto}}</span>
                        </div>
                        <!-- @if($ice>0) -->
                        <div style="width:100%;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">VALOR ICE:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{number_format($ice,2,'.','')}}</span>
                        </div>
                        <!-- @endif -->
                        <!-- @if($factura->subtotal_0>0) -->
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">SUBTOTAL 0%:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->subtotal_0}}</span>
                        </div>
                        <!-- @endif -->
                        <!-- @if($factura->subtotal_no_obj_iva>0) -->
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">SUBTOTAL NO OBJETO DE IVA:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->subtotal_no_obj_iva}}</span>
                        </div>
                        <!-- @endif -->
                        <!-- @if($factura->subtotal_12>0) -->
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">SUBTOTAL 12%:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->subtotal_12}}</span>
                        </div>
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">IVA 12% :</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->iva_12}}</span>
                        </div>
                        <!-- @endif -->
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">DESCUENTO:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->descuento}}</span>
                        </div>
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">VALOR TOTAL:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$factura->valor_total}}</span>
                        </div>   
            </div>
                    
        </div>
        @if($factura->observacion)
            <hr style="border:0.7px;">
            
            <div style="width: 750px;height: 50px"> 
                    
                        <span class="titulo3" style="width: 98%;display: inline-block;">
                            @if($factura->id_empresa==52)
                            Kilometraje:
                            @else
                            Observaciones:
                            @endif
                            <span class="titulo3">{{$factura->observacion}}</span>
                        </span>
                        
                    
            <div>
        @endif
            
            
                <hr style="border:0.7px;"> 
                @if($empresa->nombre_empresa=='MEGASOFTDEV')
                @else
                    <div style="width: 750px;height: 230px">
                        <div  style="width:100%;">
                            <span class="titulo3 bold" style="width: 98%;display: inline-block;margin-top:15px;">
                                Información adicional:
                            </span>
                            <div style="width:100%"><span class="titulo3 bold">Régimen:</span> <span class="titulo3">Contribuyente Régimen Microempresa</span></div>
                            <div style="width:100%"><span class="titulo3 bold">Agente Retención:</span> <span class="titulo3"> Resolución NAC-DNCRASC20-00000001</span></div>
                            <div style="width:100%"><span class="titulo3 bold">IMPORTANTE:</span> <span class="titulo3">En caso de generar retenciones en la fuente del Impuesto a la Renta, solicitamos retener el 1.75%.RLORTI Art.253.22, por ser STBTECHNOLOGYEC S.A. Contrinbuyente de Régimen de Microempresas</span></div>
                            <br>
                            <div style="width:100%"><span class="titulo3">EMITIR CHEQUE O TRANSFERENCIA A : </span> <span class="titulo3 bold">STBTECHNOLOGYEC S.A</span></div>
                            <div style="width:100%"><span class="titulo3">BANCO INTERNACIONAL </span><span class="titulo3">CUENTA CORRIENTE: </span> <span class="titulo3 bold">661419</span></div>
                            
                            <div style="width:100%"><span class="titulo3 bold">RUC:</span> <span class="titulo3">1792684706001 </span><span class="titulo3 bold">E-mail:</span><span class="titulo3" ><a href="administracion@techcompsolutions.com" >administracion@techcompsolutions.com</a></span></div>
                            
                        </div>
                    </div>
                @endif

                
                
                <hr style="border:0.7px;">
                <img src="{{ storage_path('pie/LOGO SOKAI.jpg') }}" alt="logo" style="max-width:80px;max-height:100px;text-align:left;" align="left">
                <div style="width:100%"><span class="titulo4 bold">Documento generado por SOKAI ECUADOR</span></div>
                <div style="width:100%"><span class="titulo4 bold">© 2021 Megasoftdev. Todos los derechos reservados.</span></div>
        
    </main>
    

    
</body>

</html>
