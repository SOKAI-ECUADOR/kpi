<!DOCTYPE html>
<style>    
        body {
            font-size: 8px;
            height: 842px;
            width: 630px;
            max-width: 630px;
            margin-left: auto;
            margin-right: auto;
        }
        .titulo_tablas {
            font-size: 10px;
            background-color: #0000ff;
            color: white;
            text-transform: uppercase;
            text-align:center;
            font-weight: bold;
        }
        table thead tr th {
            border-bottom: 1px solid #000000;
        }
        table tbody tr td {
            border-bottom: 1px solid #000000;
        }
        table th, table td {
            border-left: 1px solid #000000;
            border-right: 1px solid #000000;
        }

        .contenedor {
            width: 100%;
            margin-top:20px;
            color:white;
            position: relative;
            
        }

        .contenedor > .item {
            
            position: relative;
            width: 33.3%;
            display: inline-block;
            
        } 

        .contenedor:after {
            clear: both;
        }


</style>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reporte Inmueble {{$acta_inmueble -> id}} - {{$acta_inmueble -> nombre}}</title>
</head>

<body>
    <center><h3>Reporte Inmueble {{$acta_inmueble -> id}} - {{$acta_inmueble -> nombre}}</h3></center>   
    <br />
    <center><b>VISTA GENERAL</b></center>

    @php($counter=1)
    
    @php($collection_images = collect($inmueble_imagenes))
    @php($inmueble_imagenes_filtrado_vista_general = $collection_images->where('orden', 1))

    @php($numero_total_vista_general = count($inmueble_imagenes_filtrado_vista_general))
    @php($numero_final_tiene=0)

    @php($mas_tres_filas = [1,4,7,10,13,16,19,22,25,28,31,34,37,40,43,46,49,52,55,58])

    @if(in_array($numero_total_vista_general, $mas_tres_filas))    
        @php($numero_final_tiene=1)
    @endif
    
    <table style="font-size:10px;">
    @foreach($inmueble_imagenes_filtrado_vista_general as $imagen)
    @if($loop->index + 1 == $numero_total_vista_general && $numero_final_tiene == 1)
        <tr> 
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                                {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                    &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @else
            @if($loop->index % 3 == 0) 
                <tr> 
            @endif

            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                            {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>

            @if($counter == 3)
                @php($counter=0)
                </tr>
            @endif
            @php($counter=$counter+1);

        @endif     

    @endforeach    
    </table>  
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <thead>
                <tr>
                    <th colspan=4  class="titulo_tablas"><b>1. DATOS DEL SOLICITANTE</b></th>
                </tr>
        </thead>
        <tbody>            
            <tr>
                <td>INSTITUCIÓN:</td>
                <td>{{$acta_inmueble -> institucion}}</td>
                <td>FINALIDAD AVALÚO:</td>
                <td>{{$acta_inmueble -> finalidad_avaluo}}</td>
            </tr>
            <tr>
                <td>AGENCIA/OFICINA:</td>
                <td>{{$acta_inmueble -> agencia_oficina}}</td>
                <td>NOMBRE DEL CLIENTE:</td>
                <td>{{$acta_inmueble -> nombre_cliente}}</td>
            </tr>
            <tr>
                <td>DIRECCIÓN:</td>
                <td>{{$acta_inmueble -> direccion}}</td>
                <td>FECHA DE INSPECCIÓN:</td>
                <td>{{$acta_inmueble -> fecha_inspeccion}}</td>
            </tr>
        </tbody>
    </table>
    <br />

    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>    
            <tr>
                <td colspan=4><b>1.1- TIPO DE BIEN</b></td>
            </tr>
            <tr>
                <td colspan=1 style="width: 25%;">DESCRIPCIÓN:</td>
                <td colspan=3 style="width: 75%;">{{$acta_inmueble -> tipo_bien_descripcion}}</td>
            </tr>
            <tr>
                <td colspan=4>{{$acta_inmueble -> tipo_bien_descripcion_detalle}}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody> 
            <tr>
                <td colspan=1 ><b>1.2  UBICACIÓN</b></td>
                <td colspan=7 >{{$acta_inmueble -> ubicacion}}</td>
            </tr>           
            <tr>
                <td>PROVINCIA:</td>
                <td>{{$acta_inmueble -> ubicacion_provincia}}</td>
                <td>CANTÓN:</td>
                <td>{{$acta_inmueble -> ubicacion_canton}}</td>
                <td>CIUDAD:</td>
                <td>{{$acta_inmueble -> ubicacion_ciudad}}</td>
                <td>PARROQUIA:</td>
                <td>{{$acta_inmueble -> ubicacion_parroquia}}</td>
            </tr>
            <tr>
                <td colspan=1>BARRIO/URBANIZACIÓN:</td>
                <td colspan=3>{{$acta_inmueble -> ubicacion_barrio}}</td>
                <td>MANZANA:</td>
                <td>{{$acta_inmueble -> ubicacion_manzana}}</td>
                <td>LOTE:</td>
                <td>{{$acta_inmueble -> ubicacion_lote}}</td>
            </tr>
            <tr>
                <td>LATITUD:</td>
                <td>{{$acta_inmueble -> ubicacion_latitud}}</td>
                <td>LONGITUD:</td>
                <td>{{$acta_inmueble -> ubicacion_longitud}}</td>
                <td colspan=4></td>
            </tr>
        </tbody>
    </table>
    <br/>    
    <center><b>UBICACIÓN DEL BIEN</b></center>

    @php($counter=1)
    
    @php($inmueble_imagenes_filtrado_ubicacion_bien = $collection_images->where('orden', 2))

    @php($numero_total_ubicacion_bien = count($inmueble_imagenes_filtrado_ubicacion_bien))
    @php($numero_final_tiene=0)

    @if(in_array($numero_total_ubicacion_bien, $mas_tres_filas))    
        @php($numero_final_tiene=1)
    @endif
    
    <table style="font-size:10px;">
    @foreach($inmueble_imagenes_filtrado_ubicacion_bien as $imagen)
    @if($loop->index + 1 == $numero_total_ubicacion_bien && $numero_final_tiene == 1)
        <tr> 
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                                {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                    &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @else
            @if($loop->index % 3 == 0) 
                <tr> 
            @endif

            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                            {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>

            @if($counter == 3)
                @php($counter=0)
                </tr>
            @endif
            @php($counter=$counter+1);

        @endif     

    @endforeach    
    </table>  
    <br />

    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody> 
            <tr>
                <td colspan=1 ><b>1.3  DATOS MUNICIPALES:</b></td>
                <td colspan=3 >{{$acta_inmueble_municipio -> detalle}}</td>
            </tr>           
            <tr>
                <td style="width:25%">AÑO DEL PAGO DEL IMPUESTO PREDIAL:</td>
                <td style="width:25%">{{$acta_inmueble_municipio -> ano_impuesto_predial}}</td>
                <td style="width:25%">CLAVE CATASTRAL:</td>
                <td style="width:25%">{{$acta_inmueble_municipio -> clave_catastral}}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody> 
            <tr>
                <td colspan=4 ><center>AVALÚO MUNICIPAL</center></td>
            </tr>           
            <tr>
                <td style="width:25%"><center>AÑO</center></td>
                <td style="width:25%"><center>VALOR</center></td>
                <td style="width:25%"><center>CONSTRUCCIÓN</center></td>
                <td style="width:25%"><center>TERRENO</center></td>
            </tr>
            <tr>
                <td style="width:25%"><center>{{$acta_inmueble_municipio -> ano_1_numero}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_municipio -> ano_1_valor}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_municipio -> ano_1_construccion}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_municipio -> ano_1_terreno}}</center></td>
            </tr>
            <tr>
                <td><center>{{$acta_inmueble_municipio -> ano_2_numero}}</center></td>
                <td><center>{{$acta_inmueble_municipio -> ano_2_valor}}</center></td>
                <td><center>{{$acta_inmueble_municipio -> ano_2_construccion}}</center></td>
                <td><center>{{$acta_inmueble_municipio -> ano_2_terreno}}</center></td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody> 
            <tr>
                <td colspan=1 ><b>1.4 DATOS DE LAS ESCRITURAS</b></td>
                <td colspan=3 >{{$acta_inmueble_escritura -> detalle}}</td>
            </tr>           
            <tr>
                <td style="width:25%">NOTARÍA:</td>
                <td style="width:25%">{{$acta_inmueble_escritura -> notaria}}</td>
                <td style="width:25%">CANTÓN:</td>
                <td style="width:25%">{{$acta_inmueble_escritura -> acta_canton}}</td>
            </tr>
            <tr>
                <td style="width:25%">FECHA ESCRITURACIÓN / REGISTRO:</td>
                <td style="width:25%">{{$acta_inmueble_escritura -> fecha_escrituracion_registro}}</td>
                <td style="width:25%">SUPERFICIE(m2):</td>
                <td style="width:25%">{{$acta_inmueble_escritura -> superficie}}</td>
            </tr>
            <tr>
                <td style="width:25%">CUANTÍA:</td>
                <td style="width:25%">{{$acta_inmueble_escritura -> cuantia}}</td>
                <td style="width:25%"></td>
                <td style="width:25%"></td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody> 
            <tr>
                <td colspan=4 ><center><b>1.5 RESUMEN DEL AVALÚO</b></center></td>
            </tr>           
            <tr>
                <td style="width:25%"><center>DESCRIPCIÓN</center></td>
                <td style="width:25%"><center>V. REPOSICIÓN US$</center></td>
                <td style="width:25%"><center>V. ACTUAL US$</center></td>
                <td style="width:25%"><center>V. REALIZACIÓN US$</center></td>
            </tr>
            <tr>
                <td style="width:25%">TERRENO</td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_reposicion_terreno}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_actual_terreno}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_realizacion_terreno}}</center></td>
            </tr>
            <tr>
                <td style="width:25%">CONSTRUCCIÓN</td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_reposicion_construccion}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_actual_construccion}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_realizacion_construccion}}</center></td>
            </tr>
            <tr>
                <td style="width:25%">TOTAL</td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_total_reposicion}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_total_actual}}</center></td>
                <td style="width:25%"><center>{{$acta_inmueble_avaluo -> valor_total_realizacion}}</center></td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <thead>
                <tr>
                    <th colspan=4  class="titulo_tablas"><b>2.- CARACTERÍSTICAS DEL ENTORNO</b></th>
                </tr>
        </thead>
        <tbody>            
            <tr>
                <td colspan=1><b>2.1.- ENTORNO </b></td>
                <td colspan=3>{{$acta_inmueble_entorno -> propiedades['detalle']}}</td>
            </tr>
        </tbody>
    </table>
    <br />
    @php($counter=1)
    
    @php($inmueble_imagenes_filtrado_entorno = $collection_images->where('orden', 3))

    @php($numero_total_entorno = count($inmueble_imagenes_filtrado_entorno))
    @php($numero_final_tiene=0)

    @if(in_array($numero_total_entorno, $mas_tres_filas))    
        @php($numero_final_tiene=1)
    @endif
    
    <table style="font-size:10px;">
    @foreach($inmueble_imagenes_filtrado_entorno as $imagen)
    @if($loop->index + 1 == $numero_total_entorno && $numero_final_tiene == 1)
        <tr> 
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                                {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                    &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @else
            @if($loop->index % 3 == 0) 
                <tr> 
            @endif

            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                            {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>

            @if($counter == 3)
                @php($counter=0)
                </tr>
            @endif
            @php($counter=$counter+1);

        @endif     

    @endforeach    
    </table>  
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>      
        @foreach($acta_inmueble_entorno -> propiedades['entorno'] as $key => $value)      
            <tr>
                <td colspan=1 style="width:25%" >{{$key}}</td>
                <td colspan=3 style="width:75%">{{$value}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br />
    <div><b>2.2- SERVICIOS</b></div>
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>            
            <tr>
                <td colspan=4>{{$acta_inmueble_entorno -> propiedades['servicio']}}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <div><b>2.3- IMPACTO AMBIENTAL</b></div>
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>            
            <tr>
                <td colspan=4>{{$acta_inmueble_entorno -> propiedades['impacto_ambiental']}}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <div><b>2.4- EQUIPAMIENTO DE LA ZONA</b></div>
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>      
        @foreach($acta_inmueble_entorno -> propiedades['equipamiento_zona'] as $key => $value)      
            <tr>
                <td colspan=1 style="width:25%" >{{$key}}</td>
                <td colspan=3 style="width:75%">{{$value}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br />
    <div><b>2.5- DESCRIPCIÓN DE LA ZONA</b></div>
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>            
            <tr>
                <td colspan=4>{{$acta_inmueble_entorno -> propiedades['descripcion_zona']}}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <div><b>2.6- OBSERVACIONES DE OCUPACIÓN SEGÚN EL ENTORNO</b></div>
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>            
            <tr>
                <td colspan=4>{{$acta_inmueble_entorno -> propiedades['observacion_ocupacion']}}</td>
            </tr>
        </tbody>
    </table>
    <br />    
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <thead>
                <tr>
                    <th colspan=4  class="titulo_tablas"><b>3.- TERRENO</b></th>
                </tr>
        </thead>
    </table>
    <br />
    <div><b>3.1- LOCALIZACIÓN</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>      
        @foreach($acta_inmueble_terreno -> propiedades['localizacion'] as $key => $value)      
            <tr>
                <td colspan=1 style="width:25%" >{{$key}}</td>
                <td colspan=3 style="width:75%">{{$value}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br />
    <div><b>3.2- CARACTERÍSTICAS FÍSICAS</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>      
        @foreach($acta_inmueble_terreno -> propiedades['caracteristicas_fisicas'] as $key => $value)      
            <tr>
                <td colspan=1 style="width:25%" >{{$key}}</td>
                <td colspan=3 style="width:75%">{{$value}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br />
    <div><b>3.3- CERRAMIENTO</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>        
            <tr>
                <td colspan=4 style="width:100%" >{{$acta_inmueble_terreno -> propiedades['cerramiento']}}</td>
            </tr>
        </tbody>
    </table>
    <br />    
    <div><b>3.4- LINDEROS Y DIMENSIONES GENERALES DEL TERRENO</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <thead>
            <tr>
                <th style="width:10%"><center>LINDEROS</center></th>
                <th style="width:10%"><center>COORDENADAS</center></th>
                <th style="width:30%"><center>DESCRIPCIÓN</center></th>
                <th style="width:25%"><center>ESCRITURA</center></th>
                <th style="width:25%"><center>COMPROB. EN SITIO</center></th>
            </tr>
        </thead>    
        <tbody>      
        @foreach($acta_inmueble_terreno -> propiedades['linderos'] as $key => $value)      
            <tr>
                <td style="width:10%" >{{ explode('|',$value)[0]}}</td>
                <td style="width:10%">{{ explode('|',$value)[1]}}</td>
                <td style="width:30%" >{{ explode('|',$value)[2]}}</td>
                <td style="width:25%"><center>{{ explode('|',$value)[3]}}</center></td>
                <td style="width:25%"><center>{{ explode('|',$value)[4]}}</center></td>
            </tr>
        @endforeach
            <tr>
                <td style="width:50%" colspan=3 ><center>ÁREA DEL TERRENO (m2):</center></td>
                <td style="width:25%" colspan=1><center>{{ explode('|',$acta_inmueble_terreno -> propiedades['area_terreno'])[0] }}</center></td>
                <td style="width:25%" colspan=1><center>{{ explode('|',$acta_inmueble_terreno -> propiedades['area_terreno'])[1] }}</center></td>
            </tr>
        </tbody>
    </table>
    @php($counter=1)
    
    @php($inmueble_imagenes_filtrado_terreno = $collection_images->where('orden', 4))

    @php($numero_total_terreno = count($inmueble_imagenes_filtrado_terreno))
    @php($numero_final_tiene=0)

    @if(in_array($numero_total_terreno, $mas_tres_filas))    
        @php($numero_final_tiene=1)
    @endif
    <br />
    <table style="font-size:10px;">
    @foreach($inmueble_imagenes_filtrado_terreno as $imagen)
    @if($loop->index + 1 == $numero_total_terreno && $numero_final_tiene == 1)
        <tr> 
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                                {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                    &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @else
            @if($loop->index % 3 == 0) 
                <tr> 
            @endif

            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                            {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>

            @if($counter == 3)
                @php($counter=0)
                </tr>
            @endif
            @php($counter=$counter+1);

        @endif     

    @endforeach    
    </table>  
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <thead>
                <tr>
                    <th colspan=4  class="titulo_tablas"><b>4.- EDIFICACIÓN</b></th>
                </tr>
        </thead>
    </table>
    <br />
    <div><b>4.1- CARACTERÍSTICAS</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>      
        @foreach($acta_inmueble_edificacion -> propiedades['caracteristicas'] as $key => $value)      
            <tr>
                <td colspan=1 style="width:25%" >{{$key}}</td>
                <td colspan=3 style="width:75%">{{$value}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br /> 
    <div><b>4.2.- CUADRO DE ÁREAS DE EDIFICACIÓN:</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>
            <tr>
                <td style="width:50%"><center>DESCRIPCIÓN</center></td>
                <td style="width:25%"><center>ÁREA CUBIERTA (m2)</center></td>
                <td style="width:25%"><center>ÁREA DESCUBIERTA (m2)</center></td>
            </tr>      
        @foreach($acta_inmueble_edificacion -> propiedades['areas_edificacion'] as $key => $value)      
            <tr>
                <td style="width:50%">{{ $key }}</td>
                <td style="width:25%"><center>{{ explode('|',$value)[0]}}</center></td>
                <td style="width:25%"><center>{{ explode('|',$value)[1]}}</center></td>
            </tr>
        @endforeach
            <tr>
                <td style="width:50%"><center>TOTAL (m2):</center></td>
                <td style="width:25%"><center>{{ explode('|',$acta_inmueble_edificacion -> propiedades['areas_edificacion_total']['total_area_edificacion'])[0]}}</center></td>
                <td style="width:25%"><center>{{ explode('|',$acta_inmueble_edificacion -> propiedades['areas_edificacion_total']['total_area_edificacion'])[1]}}</center></td>
            </tr>
        </tbody>
    </table>
    <br />    
    <div><b>4.2.1-CUADRO DE ÁREAS OTROS:</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>
            <tr>
                <td style="width:50%"><center>DESCRIPCIÓN</center></td>
                <td style="width:25%"><center>ÁREA CUBIERTA (m2)</center></td>
                <td style="width:25%"><center>ÁREA DESCUBIERTA (m2)</center></td>
            </tr>      
        @foreach($acta_inmueble_edificacion -> propiedades['areas_edificacion_otros'] as $key => $value)      
            <tr>
                <td style="width:50%">{{ $key }}</td>
                <td style="width:25%"><center>{{ explode('|',$value)[0]}}</center></td>
                <td style="width:25%"><center>{{ explode('|',$value)[1]}}</center></td>
            </tr>
        @endforeach
            <tr>
                <td style="width:50%"><center>TOTAL (m2):</center></td>
                <td style="width:25%"><center>{{ explode('|',$acta_inmueble_edificacion -> propiedades['areas_edificacion_otros_total']['total_area_otros'])[0]}}</center></td>
                <td style="width:25%"><center>{{ explode('|',$acta_inmueble_edificacion -> propiedades['areas_edificacion_otros_total']['total_area_otros'])[1]}}</center></td>
            </tr>
        </tbody>
    </table>
    <br />  
    <div><b>4.3.-RESUMEN DE INFRAESTRUCTURA</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>
            <tr>
                <td style="width:50%"><center>DESCRIPCIÓN</center></td>
                <td style="width:25%"><center>UNIDADES</center></td>
                <td style="width:25%"><center>CANTIDAD</center></td>
            </tr>      
        @foreach($acta_inmueble_edificacion -> propiedades['resumen_infraestructura'] as $key => $value)      
            <tr>
                <td style="width:50%">{{ $key }}</td>
                <td style="width:25%"><center>{{ explode('|',$value)[0]}}</center></td>
                <td style="width:25%"><center>{{ explode('|',$value)[1]}}</center></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br />     
    <div><b>4.4.-CONSERVACIÓN Y MANTENIMIENTO</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>        
            <tr>
                <td colspan=4 style="width:100%" >{{$acta_inmueble_edificacion -> propiedades['conservacion_mantenimiento']}}</td>
            </tr>
        </tbody>
    </table>
    <br /> 
    <div><b>4.5.-DESCRIPCIÓN FUNCIONAL</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>        
            <tr>
                <td colspan=4 style="width:100%" >{{$acta_inmueble_edificacion -> propiedades['descripcion_funcional']}}</td>
            </tr>
        </tbody>
    </table>
    <br />  
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <thead>
                <tr>
                    <th colspan=4  class="titulo_tablas"><b>5.-CRITERIOS Y MÉTODOS EMPLEADOS EN LA VALORACIÓN:</b></th>
                </tr>
        </thead>
    </table>
    <br />
    <div><b>5.1.-CRITERIOS PARA LA VALORACIÓN</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>        
            @foreach($acta_inmueble_criterio_valoracion -> propiedades['criterios_valoracion'] as $key => $value)      
            <tr>
                <td colspan=1 style="width:25%" >{{$key}}</td>
                <td colspan=3 style="width:75%">{{$value}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan=4>&nbsp;</td>
            </tr> 
            <tr>
                <td style="width:25%" colspan=4 ><center>CALIFICACIÓN DEL INMUEBLE</center></td>
            </tr>
            @foreach($acta_inmueble_criterio_valoracion -> propiedades['criterios_valoracion_calificacion'] as $key => $value)
            <tr>
                <td style="width:25%" colspan=2>{{ $key }}</td>
                <td style="width:75%" colspan=2>{{ $value }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan=4>&nbsp;</td>
            </tr>    
        </tbody>
    </table>
    <br />
    <div><b>5.2. METODOLOGÍAS DE VALORACIÓN</b></div>
    <br />
    <div><b>5.2.1.- VALORACIÓN DEL TERRENO</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>        
            <tr>
                <td colspan=4 style="width:100%" >{{$acta_inmueble_criterio_valoracion -> propiedades['valoracion_terreno_detalle']}}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>
            <tr>
                <td colspan=10><center>HOMOGENIZACIÓN DE MUESTRAS DE MERCADO</center></td>
            </tr>
            <tr>
                <td colspan=10><center>PLANILLA Y SELECCIÓN DE ANTECEDENTES</center></td>
            </tr>
            <tr>
                <td colspan=1><center>TERRENOS EN VENTA</center></td>
                <td colspan=1><center>ÁREA m2</center></td>
                <td colspan=1><center>VALOR ($/m2)</center></td>
                <td colspan=1><center>FRENTE</center></td>
                <td colspan=1><center>UBICACIÓN</center></td>
                <td colspan=1><center>TAMAÑO</center></td>
                <td colspan=1><center>FORMA</center></td>
                <td colspan=1><center>ADECUACIÓN</center></td>
                <td colspan=1><center>HOMOGENIZADOS</center></td>
                <td colspan=1><center>VALOR UNITARIO</center></td>
            </tr>      
        @foreach($acta_inmueble_criterio_valoracion -> propiedades['valoracion_terreno'] as $key => $value)      
            <tr>
                <td colspan=1>{{ explode('|',$value)[0]}}</td>
                <td colspan=1>{{ explode('|',$value)[1]}}</td>
                <td colspan=1>{{ explode('|',$value)[2]}}</td>
                <td colspan=1><center>{{ explode('|',$value)[3]}}</center></td>
                <td colspan=1><center>{{ explode('|',$value)[4]}}</center></td>
                <td colspan=1><center>{{ explode('|',$value)[5]}}</center></td>
                <td colspan=1><center>{{ explode('|',$value)[6]}}</center></td>
                <td colspan=1><center>{{ explode('|',$value)[7]}}</center></td>
                <td colspan=1><center>{{ explode('|',$value)[8]}}</center></td>
                <td colspan=1><center>{{ explode('|',$value)[9]}}</center></td>
            </tr>
        @endforeach
            <tr>
                <td style="width:90%" colspan=9></td>
                <td style="width:10%"><center>{{ explode('|',$acta_inmueble_criterio_valoracion -> propiedades['valoracion_terreno_total']['valoracion_terreno_total'])[0]}}</center></td>
            </tr>
        </tbody>
    </table>    
    <br />
    <div><b>5.2.2.-VALORACIÓN DE LAS CONSTRUCCIONES E INFRAESTRUCTURA</b></div>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <tbody>        
            <tr>
                <td colspan=4 style="width:100%" >{{$acta_inmueble_criterio_valoracion -> propiedades['valoracion_construcciones']}}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <thead>
                <tr>
                    <th colspan=5  class="titulo_tablas"><b>6.- CUADRO RESUMEN DE VALORACIÓN</b></th>
                </tr>
        </thead>
        <tbody>            
            <tr>
                <td style="width:30%" colspan=1><center>DESCRIPCIÓN</center></td>
                <td style="width:10%" colspan=1><center>ÁREA m2</center></td>
                <td style="width:20%" colspan=1><center>VALOR DE REPOSICIÓN US$</center></td>
                <td style="width:20%" colspan=1><center>VALOR ACTUAL US$</center></td>
                <td style="width:20%" colspan=1><center>VALOR DE REALIZACIÓN US$</center></td>
            </tr>
            @foreach($acta_inmueble_resumen_valoracion -> propiedades['resumen_valoracion'] as $key => $value)      
            <tr>
                <td style="width:30%" colspan=1>{{ explode('|',$value)[0]}}</td>
                <td style="width:10%" colspan=1><center>{{ explode('|',$value)[1]}}</center></td>
                <td style="width:20%" colspan=1><center>{{ explode('|',$value)[2]}}</center></td>
                <td style="width:20%" colspan=1><center>{{ explode('|',$value)[3]}}</center></td>
                <td style="width:20%" colspan=1><center>{{ explode('|',$value)[4]}}</center></td>
            </tr>
            @endforeach
            <tr>
                <td style="width:90%" colspan=4>VALOR DE REPOSICIÓN (US$)…........................................................................</td>
                <td style="width:10%" colspan=1><center>{{ explode('|',$acta_inmueble_resumen_valoracion -> propiedades['resumen_valoracion_reposicion'])[0]}}</center></td>
            </tr>
            <tr>
                <td style="width:90%" colspan=4>VALOR DE MERCADO (US$)…...........................................................................</td>
                <td style="width:10%" colspan=1><center>{{ explode('|',$acta_inmueble_resumen_valoracion -> propiedades['resumen_valoracion_mercado'])[0]}}</center></td>
            </tr>
            <tr>
                <td style="width:90%" colspan=4>VALOR DE REALIZACIÓN (US$)….......................................................................</td>
                <td style="width:10%" colspan=1><center>{{ explode('|',$acta_inmueble_resumen_valoracion -> propiedades['resumen_valoracion_realizacion'])[0]}}</center></td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;" border=2 FRAME="hsides" RULES="none">
        <thead>
                <tr>
                    <th colspan=1  class="titulo_tablas"><b>SET DE FOTOGRAFÍAS</b></th>
                </tr>
        </thead>
    </table>
    <br />

    @php($counter=1)
    
    @php($inmueble_imagenes_filtrado_otros = $collection_images->where('orden', 5))

    @php($numero_total_otros = count($inmueble_imagenes_filtrado_otros))
    @php($numero_final_tiene=0)

    @if(in_array($numero_total_otros, $mas_tres_filas))    
        @php($numero_final_tiene=1)
    @endif
    <br />
    <table style="font-size:10px;">
    @foreach($inmueble_imagenes_filtrado_otros as $imagen)
    @if($loop->index + 1 == $numero_total_otros && $numero_final_tiene == 1)
        <tr> 
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                                {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style='border:none;'>
                <table>
                    <tr>
                        <td style='border:none;width: 199px;height:144px;'></td>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;width: 199px;" >
                                    &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @else
            @if($loop->index % 3 == 0) 
                <tr> 
            @endif

            <td style='border:none;'>
                <table>
                    <tr>
                        <td style="border-top: 1px solid #000000">
                            <img src="data:image/png;base64,{{$imagen->archivo }}" style="width: 199px;height:144px;"/>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #0000ff;">
                        <td style="border-top: 1px solid #0000ff;" class="titulo_tablas">
                            {{ $imagen->titulo }}
                        </td>
                    </tr>
                </table>
            </td>

            @if($counter == 3)
                @php($counter=0)
                </tr>
            @endif
            @php($counter=$counter+1);

        @endif     

    @endforeach    
    </table>  
    <br />

    
</body>

</html>
