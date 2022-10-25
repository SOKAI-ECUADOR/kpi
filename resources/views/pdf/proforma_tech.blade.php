<!DOCTYPE html>
<style>
        *{margin:0;padding:0;}
        html{background:white;}
        body{width:750px;height:750px;margin:auto;background:white;}
        header{width:750px;height:220px;background:white;}
        section#banner{width:375px;height:220px;float:left;background:white;}
        section#logo{width:375px;height:95px;float:left;background:white;}
        
        nav{width:750px;height:100px;background:white;}
        main{width:750px;height:550px;background:white;}
        .titulo3 {
            font-size: 11px;
        }
        .titulo4 {
            font-size: 13px;
        }
        .titulo5 {
            font-size: 10px;
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
        .bold_pie {
            font:normal 700 10px Arial;
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
    <title>proforma Nro {{$codigo}}</title>
    
</head>

<body>

    <header>
        <section id="banner">
            <br>
                    <div style="text-align:left">
                        <img src="{{ storage_path('logos/'.$proformas[0]->logo) }}" alt="logo" style="max-width:180px;max-height:180px;text-align:left;">  
                    </div>
                    
                    <div style="width:100%" class="titulo5"><b>{{$factura_info2->razon_social}}</b></div>
                    <br>
                    <div style="width:100%" class="titulo6"><b>NOMBRE COMERCIAL:</b>{{$proformas[0]->nombre_empresa}}</div>
                    <div style="width:100%" class="titulo6"><b>RUC:</b> {{$proformas[0]->ruc_empresa}}</div>
                    <div style="width:100%" class="titulo6" ><span class="titulo6 bold">DIR. MATRIZ:</span>
                        @if($proformas[0]->direccion_empresa!=null && $proformas[0]->direccion_empresa!='null')
                        <span class="texto" class="titulo6">{{$proformas[0]->direccion_empresa}}</span>
                        @endif
                    </div>
                    <div style="width:100%" class="titulo6"><b>TELÉFONO:</b>
                        @if($factura_info2->telefono!=null && $factura_info2->telefono!='null')
                        {{$factura_info2->telefono}}
                        @endif
                    </div>
                    <div style="width:100%" class="titulo6"><b>E-MAIL:</b> {{$proformas[0]->email_facturacion}}</div>
                    <div style="width:100%" class="titulo6">
                        <b>WEB:</b>
                        @if($factura_info2->urlweb!=null && $factura_info2->urlweb!='null')
                        <span class="titulo6" style="font-size: 10.5px;">{{$factura_info2->urlweb}}</span>
                        @endif
                    </div>
                    
                    
        </section>
        <br>
        <br>
        <section id="logo">
            <?php
                $proforma_nro="";
                if($codigo<10){
                    $proforma_nro="COT-TECH-".date("Y", strtotime(($proformas[0]->fecha_emision)))."-000".$codigo;
                }else{
                    if($codigo<100){
                        $proforma_nro="COT-TECH-".date("Y", strtotime(($proformas[0]->fecha_emision)))."-00".$codigo;
                    }else{
                        if($codigo<1000){
                            $proforma_nro="COT-TECH-".date("Y", strtotime(($proformas[0]->fecha_emision)))."-0".$codigo;
                        }else{
                            $proforma_nro="COT-TECH-".date("Y", strtotime(($proformas[0]->fecha_emision)))."-".$codigo;
                        }
                    }
                }
            ?>
                    <div style="float:right">
                        <br>
                        <br>
                        <div style="width:100%"><span class="titulo4 bold">COTIZACIÓN No.</span></div>
                        <div style="width:100%"><span class="titulo4">
                        <font color="red">{{$proforma_nro}} </font>
                            <?php
                            echo(date("d-m-Y ", strtotime($proformas[0]->fecha_emision)))
                            ?>
                        </span></div> 
                    </div>
        </section>
        
        
    </header>
    
    <nav>
        <hr style="border:0.7px;">
        <div style="width:100%" class="titulo3"><b>CLIENTE:</b> {{$proformas[0]->cliente}} </div>
        <div style="width:100%" class="titulo3"><b>RUC / CI:</b> {{$proformas[0]->identificacion}} </div>
        <div style="width:100%" class="titulo3"><b>E-MAIL:</b> {{$proformas[0]->email}} </div>
        <div style="width:100%" class="titulo3"><b>FECHA DE EMISIÓN:</b> {{$proformas[0]->fecha_emision}} </div>
        <div style="width:100%" class="titulo3"><b>FECHA DE EXPIRACIÓN:</b> {{$factura_info2->fecha_expiracion}} </div>
        <div style="width:100%" class="titulo3"><b>DIRECCIÓN:</b> {{$proformas[0]->direccion}} </div>
        <div style="width:100%" class="titulo3"><b>TELÉFONO:</b> {{$proformas[0]->telefono}} </div>
        
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
                @foreach($proformas as $detalle)
                <tr>
                    <td class="titulo3 text-center">{{$detalle->cantidad}}</td>
                    <td class="titulo3 text-center">{{$detalle->cod_principal}}</td>
                    <td class="titulo3 text-center">{{$detalle->nombre}}</td>
                    <td class="titulo3 text-center">${{$detalle->precio}}</td>
                    <td class="titulo3 text-center">@if($detalle->descuento) @if($detalle->p_descuento==1)$@endif{{$detalle->descuento}}@if($detalle->p_descuento==0)%@endif @else $0.00 @endif</td>
                    <td class="titulo3 text-center">
                        
                        
                        $0.00
                        
                    </td>
                    
                    <td class="titulo3 text-center">${{$detalle->total_pro}}</td>
                </tr>
                <?php
                
                    //$ice += $detalle->valor_ice;
                    $ice += 0;
                
                ?>
                @endforeach
            </tbody>
        </table>
        <div style="width: 750px;height: 120px;">
                
            <div style="width: 520px;height: 120px;display: inline-block;float:left">
                        
            @if($proformas[0]->nombre_empresa=='MEGASOFTDEV')
                @else
                    <div style="width: 750px;height: 230px">
                        <div  style="width:100%;">
                            <span class="titulo3 bold" style="width: 98%;display: inline-block;margin-top:15px;">
                                INFORMACIÓN ADICIONAL:
                            </span>
                            <div style="width:100%"><span class="titulo3 bold">LUGAR DE ENTREGA:</span> <span class="titulo3">{{$factura_info2->lugar_de_entrega}}</span></div>
                            <div style="width:100%"><span class="titulo3 bold">CONDICIONES DE PAGO:</span> <span class="titulo3">{{$factura_info2->condiciones_de_pago}}</span></div>
                            <div style="width:100%"><span class="titulo3 bold">VENDEDOR:</span> <span class="titulo3">{{$factura_info2->nombre_vendedor}}</span></div>
                            <br>
                            <div style="width:100%"><span class="titulo3">EMITIR CHEQUE O TRANSFERENCIA A : </span> <span class="titulo3 bold">STBTECHNOLOGYEC S.A</span></div>
                            <div style="width:100%"><span class="titulo3">BANCO INTERNACIONAL </span><span class="titulo3">CUENTA CORRIENTE: </span> <span class="titulo3 bold">661419</span></div>
                            
                            <div style="width:100%"><span class="titulo3 bold">RUC:</span> <span class="titulo3">1792684706001 </span><span class="titulo3 bold">E-mail:</span><span class="titulo3" ><a href="administracion@techcompsolutions.com" >administracion@techcompsolutions.com</a></span></div>
                            
                        </div>
                    </div>
                @endif 
            
                        
                        
                        
            </div>     
            <div style="width: 230px;height: 120px;display: inline-block;float:left;" >
                        <div style="width:100%;margin-top:15px;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">SUBTOTAL SIN IMPUESTOS:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$proformas[0]->subtotal}}</span>
                        </div>
                        
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">SUBTOTAL 12%:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$proformas[0]->subtotal_12}}</span>
                        </div>
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">IVA 12% :</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$proformas[0]->iva}}</span>
                        </div>
                        
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">DESCUENTO:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$proformas[0]->descuento}}</span>
                        </div>
                        <div style="width:100%;padding: 0;">
                            <span class="bold" style="font-size:9px;width: 60%;display: inline-block">VALOR TOTAL:</span>
                            <span class="titulo3 nobold" style="width: 30%;display: inline-block;text-align:right">${{$proformas[0]->total}}</span>
                        </div>   
            </div>
                    
        </div>
        <br>
        <br>
        <br>
        <br>
        @if($factura_info2->observacion)
            <hr style="border:0.7px;">
            
            <div style="width: 750px;height: 50px"> 
                    
                        <span class="titulo3" style="width: 98%;display: inline-block;">
                            @if($factura_info2->id_empresa==52)
                            Kilometraje:
                            @else
                            Observaciones:
                            @endif
                            <span class="titulo3">{{$factura_info2->observacion}}</span>
                        </span>
                        
                    
            <div>
        @endif
            
            
                
                

                
                
                <hr style="border:0.7px;">
                <br>
                <br>
                
                <img src="{{ storage_path('pie/tech.png') }}" alt="logo" style="max-width:80px;max-height:100px;text-align:left;" align="left">
                
                <div style="width:100%"><span class="bold_pie">Documento generado por TECHCOMP SOLUTIONS</span></div>
                <div style="width:100%"><span class="bold_pie">© 2021 STBTECHNOLOGYEC S.A Todos los derechos reservados.</span></div>
        
    </main>
    

    
</body>

</html>
