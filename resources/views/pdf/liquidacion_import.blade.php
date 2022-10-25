<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <style>
            @page {
                margin: 25px;
                font-family: Arial;
            }
            *{
                box-sizing:border-box;
            }
            .page-break {
                page-break-inside: auto;
            }
            body {
                margin: 5px;
                padding:5px;
                font-family: sans-serif;
            }
            /*div {
                word-wrap: break-word;         
                overflow-wrap: break-word;     
                width: 100%;
            }*/
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
            /* .mb-0{
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
            } */
            /*table {
                table-layout: fixed;
                width: 100%;
            }

            table td {
                page-break-inside: auto;
            }*/
            th,td{
                font-size: 9px;
                padding: 5px;
            }
            /* .bordeado{
                border:1px solid #000;
                border-radius: 10px;
                padding: 10px 10px;
            }
            .bordeado1{
                border:1px solid #000;
            }
            .ml-5{
                margin-left:30px;
            } */
            .column{
                break-before: column; 
                break-after: column;
            }
            .ejemplo{
                border-collapse:separate;
                    border-spacing: 1;
                    border:solid black 1px;
                    border-radius:10px;
                    -moz-border-radius:10px;
                    -webkit-border-radius: 5px; 
            }
            .text-rigth{
                text-align: right!important;
                width: 100%;
            }
            .bold {
                font-weight: bold;
            }
            .nobold {
                font-weight: 50;
            }
            #pageFooter:after {
                
                content:"de " counter(page);
                left: 0; 
                top: 100%;
                white-space: nowrap; 
                z-index: 20;
                -moz-border-radius: 5px; 
                -moz-box-shadow: 0px 0px 4px #222;  
                background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);  
            }
        </style>
    </head>
    <body>
    
    <div>
    <table  cellpadding="2px" cellspacing="0px" class="ejemplo" >
    
            <tr>
				<td rowspan="2" style="width:150px;height: 30px;!important">
                    @if(file_exists(storage_path('logos/'.$empresa->logo)))
                        <img src="{{ storage_path('logos/'.$empresa->logo) }}" alt="logo" style="width:150px;height: 60px;text-align:center;">
                    @else
                        <h2><font color="red">NO EXISTE LOGO</font></h2>
                    @endif
                    
                </td>
				<td style="width:435px;height: 15px;!important" class="text-center"><h2>{{$empresa->nombre_empresa}}</h2></td>
				<td  style="width:100px;height: 15px;">
                    <span class="bold">Fecha:</span>
                    <span>
                    @if(isset($importacion->fech_importacion) && $importacion->fech_importacion!==null)
                        <?php
                            echo(date("d/m/Y", strtotime($importacion->fech_importacion)));
                        ?>
                    @endif
                    </span>
                </td>
			</tr>
			<tr>
				<td style="width:435px;height: 15px;!important" class="text-center"><h2>Liquidacion Importacion</h2></td>
                <td style="width:100px;height: 15px;" VALIGN="TOP">
                    <div id="pageFooter" ><span class="bold">Pagina: </span>1 </div>
                    
                    
                </td>
			</tr>
        
        
    </table>
    <!-- <tr>
				<td rowspan="2" style="width:180px;">Celda Combinada</td>
				<td style="width:735px;height: 40px;!important" class="text-center">Celda 5</td>
				<td  style="width:100px;height: 28px;!important">Fecha:</td>
			</tr>
			<tr>
				<td style="height: 15px;!important"></td>
                <td style="width:100px;height: 28px;!important" VALIGN="TOP">Celda Combinada</td>
			</tr> -->
            <?php
               $total_cantidad=0;
               $total_costo=0;
               $nuevo_costo0=0;  
               $array_n_costo=[];  
               $array_n_costo_total=[];  
            ?>
            @foreach($prod_importacion as $detail0)
                <?php
                    $total_costo+=number_format($detail0->cantidad*$detail0->precio,2,".","");
                    $total_cantidad+=number_format($detail0->cantidad,2,".","");
                ?>
            @endforeach
            @foreach($prod_importacion as $detail0)
                <?php
                    
                    
                    $nuevo_costo01=0;
                    $nuevo_costo02=0;
                    if(isset($array_datos[0])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[0],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[0]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }
                    if(isset($array_datos[1])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[1],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[1]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[2])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[2],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[2]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[3])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[3],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[3]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[4])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[4],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[4]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }
                    if(isset($array_datos[5])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[5],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[5]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[6])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[6],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[6]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[7])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[7],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[7]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }
                    if(isset($array_datos[8])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[8],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[8]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[9])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[9],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[9]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[10])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[10],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[10]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }   
                    if(isset($array_datos[11])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[11],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[11]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[12])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[12],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[12]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[13])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[13],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[13]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[14])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[14],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[14]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[15])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[15],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[15]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }   
                    if(isset($array_datos[16])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[16],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[16]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[17])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[17],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[17]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[18])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[18],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[18]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }    
                    if(isset($array_datos[19])){
                        if($importacion->forma_liquidacion==1){
                            $nuevo_costo0+=number_format($array_datos[19],2,".","");
                        }else{
                            $nuevo_costo0+=number_format($array_datos[19]*(($detail0->cantidad*$detail0->precio)/$total_costo)/$detail0->cantidad,2,".","");
                        }
                    }     
                    array_push($array_n_costo,$nuevo_costo0); 
                    array_push($array_n_costo_total,$nuevo_costo0*$detail0->cantidad);                        
                    $nuevo_costo0=0;                  
                ?>
            @endforeach
            
    </div>
    <br>
        <div class="column">
                <table border="1" cellpadding="2px" cellspacing="0px" style="width: 675px;max-width: 675px;">
                            <thead>
                                <tr class="column">
                                
                                    
                                    <th style="width:20px">CÓDIGO</th>
                                    
                                    <th style="width:100px">NOMBRE</th>
                                    <th style="width:83px">PROYECTO</th>
                                    <th style="width:100px">BODEGA</th>
                                    <th style="width:65px">CANTIDAD</th>
                                    <th style="width:55px">COSTO UNITARIO</th>
                                    
                                    <th style="width:50px">COSTO TOTAL</th>

     
                                    
                                    <th style="width:65px">NUEVO COSTO UNITARIO</th>
                                    <th style="width:65px">NUEVO COSTO TOTAL</th>

                                </tr>  
                            </thead>
                            <?php
                                $count=-1;
                            ?>
                            <tbody>
                                
                                    @foreach($prod_importacion as $detail2)
                                    <tr>
                                        @if($detail2->cod_alterno!==null)
                                            <td class="text-center" style="width:20px">{{$detail2->cod_alterno}}</td>
                                        @else
                                            <td class="text-center" style="width:20px">{{$detail2->codigo}}</td>
                                        @endif
                                        
                                        <td style="width:100px">{{$detail2->nombre}}</td>
                                        <td style="width:83px">{{$detail2->descripcion}}</td>
                                        <td style="width:100px">{{$detail2->nombrebodega}}</td>
                                        <td class="text-center" style="width:65px">{{$detail2->cantidad}}</td>
                                        <td class="text-rigth" style="width:55px">{{$detail2->precio}}</td>
                                        
                                        <td class="text-rigth" style="width:50px">{{$detail2->total}}</td>
                                        <?php
                                            $nuevo_costo=0;
                                            $count++;
                                        ?>
                                           
                                            

                                            
                                        
                                        
                                            
                                                <td class="text-rigth" style="width:65px">{{number_format($array_n_costo[$count],2,".",",")}}</td>
                                                <td class="text-rigth" style="width:65px">{{number_format($array_n_costo[$count]*$detail2->cantidad,2,".",".")}}</td>
                                            
                                            
                                        
                                        
                                    </tr>    
                                    @endforeach
                                    
                                    <!-- <td class="text-center" >1.51</td>
                                    <td class="text-center">1.51</td>
                                    <td class="text-center">1.51</td>
                                    <td class="text-center">12.41</td>
                                    <td class="text-center">12.41</td> -->
                                    
                                
                            </tbody>
                </table>
                <br>

        </div>
        <br>
        <table  style="width: 425px;max-width: 425px;!important" ALIGN="center"  border="1" cellpadding="2px" cellspacing="0px">
            <thead>
                <tr>
                    <th class="text-center" style="width:25px">ID</th>
                    <th class="text-center" style="width:300px">DESCRIPCION</th>
                    <th class="text-center" style="width:100px">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                            <?php
                                $count_total=0;
                            ?>
                @foreach($facturas as $detail3)
                    <tr>
                        <td class="text-center" style="width:25px">{{$detail3->id_factcompra}}</td>
                        <td style="width:300px">{{$detail3->nombre}}</td>
                        <td class="text-rigth" style="width:100px">{{$detail3->total}}</td>
                                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        
        <table  style="width: 425px;max-width: 425px;!important" ALIGN="center" >
            <tr>
                
                <th style="width:330px !important;" >TOTAL</th>
                <th style="width:100px !important;" class="text-rigth">
                    <?php
                        echo(number_format($importacion->total_importacion,2,".",","));
                    ?>
                </th>
            </tr>
        </table>
        <br>
        <table  style="width: 723px;max-width: 723px;!important" >
            <tr>
                <th class="text-center">SALDO SIN LIQUIDAR</th>
                <th class="text-center">TOTAL PRODUCTOS</th>
                <th class="text-center">TOTAL LIQUIDACION</th>
            </tr>
            <tr>
                @if($importacion->estado=='Liquidado')
                    <td class="text-center">{{number_format($importacion->total_liquidacion,2,".",",")}}</td>
                @else
                    <td class="text-center">{{number_format($importacion->total_importacion,2,".",",")}}</td>
                @endif
                
                <td class="text-center">{{$total_cantidad}}</td>
                <td class="text-center">
                <?php
                    echo(number_format(array_sum($array_n_costo_total),2,".",","));
                ?>
                </td>                                        
            </tr>
        </table>
            
        
        
        
        
    </body>
</html>