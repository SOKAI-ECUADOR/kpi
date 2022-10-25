<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>


        body{
            margin-left: 0px;
            margin-right: 0px;
            margin-top: 0px;
            margin-bottom: 0px;
            font-family: "Arial";
            
        }

        
        
        table {
                border-collapse: collapse;
            }
            hr{
                page-break-after: always;
                border: none;
                margin: 0;
                padding: 0;
                
            }
            @media (max-width:600px)  { 
            
            }
            
    </style>
</head>
<body leftmargin=-8 topmargin=-8>
<div>
<?php
$logo='logos/'.$empresas->logo;
?>

                                
                                    <div align="center">
                                        <img src="{{ storage_path('logos/'.$empresas->logo) }}" alt="logo" style="max-width:100%;max-height:160px;text-align:center;">
                                        
                                    </div>
                                
                            

                            @if($empresas->id_empresa==60)
                                <div align="center">
                                    <FONT SIZE=3><B>VITAL PHARMA</B></FONT>
                                </div>
                            @else
                                <div align="center">
                                    
                                    <FONT SIZE=3><B>{{$empresas->nombre_empresa}}</B></FONT>
                                </div>
                            @endif
                            <div align="center">
                                
                                <FONT SIZE=2>{{$users->nombres}} {{$users->apellidos}}</FONT>
                            </div>
                            <div align="center">
                                <FONT SIZE=2>Ruc:{{$empresas->ruc_empresa}}</FONT>
                            </div>
                            @if($empresas->ambiente==2)
                                <div align="center">
                                    
                                    <FONT SIZE=2>Ambiente: PRODUCCION</FONT>
                                </div>
                            @else
                                <div align="center">
                                    <FONT SIZE=2>Ambiente: PRUEBAS</FONT>
                                </div>
                            @endif
                            <div align="center">
                                
                                <FONT SIZE=2>Matriz: {{$empresas->direccion_empresa}}</FONT>
                            </div>
                            <div align="center">
                                
                                <FONT SIZE=2>Telefono: {{$empresas->telefono}}</FONT>
                            </div>
                            @if($empresas->id_empresa==60)
                                <div align="center">
                                    
                                    <FONT SIZE=2><b>SUCURSAL: VITAL PHARMA</b></FONT>
                                </div>
                            @else
                                <div align="center">
                                    <FONT SIZE=2><b>SUCURSAL: {{$empresas->nombre_empresa}}</b></FONT>
                                </div>
                            @endif
                            <div align="center">
                                
                                <FONT SIZE=2>{{$users->direccion}}</FONT>
                            </div>
                            <div align="center">
                                
                                <FONT SIZE=2>Autorizacion SRI</FONT>
                            </div>
                            <div align="center">
                                
                                <FONT SIZE=1>{{$facturas->clave_acceso}}</FONT>
                            </div>
                            <div align="center">
                                
                                <FONT SIZE=2>Clave de Acceso</FONT>
                            </div>
                            <div align="center">
                                
                                <FONT SIZE=1>{{$facturas->clave_acceso}}</FONT>
                            </div>
                            <div align="center">
                                <FONT SIZE=3>FACTURA</FONT>
                            </div>
                            <div align="center">
                                
                                <FONT SIZE=2>No. : {{$facturas->nro_factura}}</FONT>
                            </div>
                            
                            
                            <table border="0"   style="width:115%;margin-left:-2em!important">
                                <tr>
                                    <td style="text-align:left;width:16%;">
                                        
                                        <FONT SIZE=2>CLIENTE :</FONT>
                                    </td>
                                    <td style="text-align:left;width:115%!important;">
                                        
                                        <FONT SIZE=2>{{$clientes->nombre}}</FONT>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;width:16%;">
                                        
                                        <FONT SIZE=2>CI:/R.U.C. :</FONT>
                                    </td>
                                    <td style="text-align:left;width:115%!important;">
                                        
                                        <FONT SIZE=2>{{$clientes->identificacion}}</FONT>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;width:16%;">
                                        
                                        <FONT SIZE=2>DIRECCION:</FONT>
                                    </td>
                                    <td style="text-align:left;width:115%!important;">
                                        <FONT SIZE=2>{{$clientes->direccion}}</FONT>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;width:16%;">
                                        
                                        <FONT SIZE=2>TELEFONO :</FONT>
                                    </td>

                                    <td style="text-align:left;width:115%!important;">
                                        
                                        <FONT SIZE=2>{{$clientes->telefono}}</FONT>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;width:16%;">
                                        
                                        <FONT SIZE=2>Vendedor :</FONT>
                                    </td>
                                    <td style="text-align:left;width:115%!important;">
                                        
                                        <FONT SIZE=2>{{$facturas->nombre_vendedor}}</FONT>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;width:16%;">
                                        
                                        <FONT SIZE=2>Hora:</FONT>
                                    </td>
                                    
                                    <td style="text-align:left;width:115%!important;">
                                        
                                        <FONT SIZE=2>{{$hora}} - Fecha: {{$fecha}}</FONT>
                                    </td>
                                </tr>
                            </table>
                            
                            
</div>
                                @if($facturas->pedido>0)
                                    <table border="0"   style="width:115%;margin-left:-2em!important">
                                        <thead>
                                            <tr>
                                                <th style="width:10px;text-align:center;" ><FONT SIZE=2>No Orden</FONT></th>
                                                <th style="width:10px;text-align:center;" ><FONT SIZE=2>No Mesa</FONT></th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            
                                                <tr>
                                                    <td style="width:10px;text-align:center;" ><FONT SIZE=2>{{$facturas->orden_compra}}</FONT></td>
                                                    <td style="width:10px;text-align:center;"  ><FONT SIZE=2>{{$facturas->migo}}</FONT></td>
                                                </tr>

                                        </tbody>
                                    </table>
                                @endif
<br>
<div >
<table border="0"   style="width:115%;margin-left:-2em!important">
                <thead>
                    <tr>
                        <th style="width:45px;text-align:left;" ><FONT SIZE=2>Descripcion</FONT></th>
                        <th style="width:1px;text-align:right;" ><FONT SIZE=2>Cant.</FONT></th>
                        <th style="width:5px;text-align:right;" ><FONT SIZE=2>P.U</FONT></th>
                        <th style="width:5px;text-align:right;" ><FONT SIZE=2>Subtotal</FONT></th>
                    </tr>
                    
                </thead>
                <tbody>
                    <?php
                        $total=0;
                    ?>
                    @foreach($detalle as $detalle)
                        <tr>
                            <td style="width:45px" ><FONT SIZE=2>{{$detalle->nombre}}</FONT></td>
                            <td style="width:1px;text-align:right;"  ><FONT SIZE=2>{{$detalle->cantidad}}</FONT></td>
                            <td style="width:5px;text-align:right;"   ><FONT SIZE=2>{{number_format($detalle->precio,2,".","")}}</FONT></td>
                            <td  style="width:5px;text-align:right;"  ><FONT SIZE=2>{{number_format($detalle->total,2,".","")}}</FONT></td>
                        </tr>
                        <?php
                            
                            $total+=$detalle->total;
                        ?>
                    @endforeach
                </tbody>
            </table>
</div>

                            <?php
                                $subtotal=0;
                                $subtotal=number_format($facturas->subtotal_sin_impuesto,2,".",""); 
                            ?>
                            
                            <table border="0"   style="width:115%;margin-left:-2em!important">
                                <tr>
                                    <td style="text-align:right">
                                        
                                        <FONT SIZE=2>Subtotal: $</FONT>
                                    </td>
                                    <td style="text-align:right;width:10%;">
                                        
                                        <FONT SIZE=2>{{$subtotal}}</FONT>
                                    </td>
                                </tr>
                                @if($facturas->subtotal_0>0)
                                    <tr>
                                        <td style="text-align:right">
                                            
                                            <FONT SIZE=2>Subtotal 0%: $</FONT>
                                        </td>
                                        <td style="text-align:right;width:10%;">
                                            
                                            <FONT SIZE=2>{{number_format($facturas->subtotal_0,2,".","")}}</FONT>
                                        </td>
                                    </tr>
                                @endif
                                @if($facturas->subtotal_no_obj_iva>0)
                                    <tr>
                                        <td style="text-align:right">
                                            <FONT SIZE=2>No Objeto Impuesto: $</FONT>
                                        </td>
                                        <td style="text-align:right;width:10%;">
                                        
                                            <FONT SIZE=2>{{number_format($facturas->subtotal_no_obj_iva,2,".","")}}</FONT>
                                        </td>
                                    </tr>
                                @endif
                                @if($facturas->subtotal_12>0)
                                    <tr>
                                        <td style="text-align:right">
                                            
                                            <FONT SIZE=2>Subtotal 12%: $</FONT>
                                        </td>
                                        <td style="text-align:right;width:10%;">
                                            
                                            <FONT SIZE=2>{{number_format($facturas->subtotal_12,2,".","")}}</FONT>
                                        </td>
                                    </tr>
                                @endif
                                @if($facturas->iva_12>0)
                                    <tr>
                                        <td style="text-align:right">
                                            
                                            <FONT SIZE=2>IVA 12%: $</FONT>
                                        </td>
                                        <td style="text-align:right;width:10%;">
                                            
                                            <FONT SIZE=2>{{number_format($facturas->iva_12,2,".","")}}</FONT>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="text-align:right">-------------</td>
                                    <td style="text-align:right;width:10%;">-------------</td>
                                </tr>
                                <tr>
                                    <td style="text-align:right">
                                        
                                        <FONT SIZE=2>A pagar: $</FONT>
                                    </td>
                                    <td style="text-align:right;width:10%;">
                                        
                                        <FONT SIZE=2>{{number_format($facturas->valor_total,2,".","")}}</FONT>
                                    </td>
                                </tr>
                                @if($facturas->descuento>0)
                                    <tr>
                                        <td style="text-align:right">
                                            
                                            <FONT SIZE=2>Su Descuento: $</FONT>
                                        </td>
                                        <td style="text-align:right;width:15%;">
                                            
                                            <FONT SIZE=2>{{number_format($facturas->descuento,2,".","")}}</FONT>
                                        </td>
                                    </tr>
                                @endif
                                @if(count($pagos)>0)
                                    @foreach($pagos as $pago)
                                        <tr>
                                            <td style="text-align:left">
                                                @if($pago->estado==2)
                                                    <FONT SIZE=2><b>Forma Pago:</b> CREDITO $ {{number_format($pago->total,2,".","")}}</FONT>
                                                @else
                                                    <FONT SIZE=2><b>Forma Pago:</b> {{$pago->descripcion}} $ {{number_format($pago->total,2,".","")}}</FONT>
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                            <td style="text-align:left;width:15%;">
                                                
                                                <FONT SIZE=2>{{number_format($pago->total,2,".","")}}</FONT>
                                            </td>
                                        </tr>  -->
                                    @endforeach
                                    
                                @endif
                            </table>
                            @if($facturas->pedido>0)
                                <br>
                                
                            @endif
                                                <?php
                                                    $total_comidas=0;
                                                    $total_bebidas=0;
                                                ?>
                                            @foreach($detalle_comida as $detalle)
                                                @if($facturas->pedido>0)
                                                    @if($detalle->tipo_pedido=='comida')
                                                        <?php
                                                            $total_comidas++;
                                                        ?>
                                                    @else
                                                        <?php
                                                            $total_bebidas++;
                                                        ?>
                                                    @endif
                                                @endif
                                            @endforeach
                                            
                            @if($facturas->id_empresa==50 && $facturas->id_punto_emision==43 && $facturas->id_establecimiento==44)
                                @if($facturas->pedido>0)
                                    @if($total_comidas>0)
                                    <hr>
                                        <div style="text-align:right">-------------------------------------------------------------------------------------------</div>
                                        <div style="text-align:center"><b>COMIDAS</b></div>
                                        <table border="0"   style="width:115%;margin-left:-2em!important">
                                            <thead>
                                                <tr>
                                                    <th style="width:10px;text-align:center;" ><FONT SIZE=2>No Orden</FONT></th>
                                                    <th style="width:10px;text-align:center;" ><FONT SIZE=2>No Mesa</FONT></th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                                
                                                    <tr>
                                                        <td style="width:10px;text-align:center;" ><FONT SIZE=2>{{$facturas->orden_compra}}</FONT></td>
                                                        <td style="width:10px;text-align:center;"  ><FONT SIZE=2>{{$facturas->migo}}</FONT></td>
                                                    </tr>

                                            </tbody>
                                        </table>
                                        <table border="0"   style="width:115%;margin-left:-2em!important">
                                            <thead>
                                                <tr>
                                                    <th style="width:55px;text-align:left;" ><FONT SIZE=2>Descripcion</FONT></th>
                                                    <th style="width:1px;text-align:right;" ><FONT SIZE=2>Cant.</FONT></th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $total=0;
                                                    
                                                ?>
                                                @foreach($detalle_comida as $detalle)
                                                    @if($detalle->tipo_pedido=='comida')
                                                        <tr>
                                                            <td style="width:45px" ><FONT SIZE=2>{{$detalle->nombre}}</FONT></td>
                                                            <td style="width:1px;text-align:right;"  ><FONT SIZE=2>{{$detalle->cantidad}}</FONT></td>
                                                        </tr>
                
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    @if($total_bebidas>0)
                                        <hr>
                                        <div style="text-align:right">-------------------------------------------------------------------------------------------</div>
                                        <div style="text-align:center"><b>BEBIDAS</b></div>
                                        @if($facturas->pedido>0)
                                        <table border="0"   style="width:115%;margin-left:-2em!important">
                                            <thead>
                                                <tr>
                                                    <th style="width:10px;text-align:center;" ><FONT SIZE=2>No Orden</FONT></th>
                                                    <th style="width:10px;text-align:center;" ><FONT SIZE=2>No Mesa</FONT></th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                                
                                                    <tr>
                                                        <td style="width:10px;text-align:center;" ><FONT SIZE=2>{{$facturas->orden_compra}}</FONT></td>
                                                        <td style="width:10px;text-align:center;"  ><FONT SIZE=2>{{$facturas->migo}}</FONT></td>
                                                    </tr>

                                            </tbody>
                                        </table>
                                        @endif
                                        <table border="0"   style="width:115%;margin-left:-2em!important">
                                            <thead>
                                                <tr>
                                                    <th style="width:55px;text-align:left;" ><FONT SIZE=2>Descripcion</FONT></th>
                                                    <th style="width:1px;text-align:right;" ><FONT SIZE=2>Cant.</FONT></th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $total=0;
                                                ?>
                                                @foreach($detalle_comida as $detalle)
                                                    @if($detalle->tipo_pedido=='bebida')
                                                        <tr>
                                                            <td style="width:45px" ><FONT SIZE=2>{{$detalle->nombre}}</FONT></td>
                                                            <td style="width:1px;text-align:right;"  ><FONT SIZE=2>{{$detalle->cantidad}}</FONT></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    
                                @endif
                            @endif
                            
                            
                            
</div>
                

</body>
</html>