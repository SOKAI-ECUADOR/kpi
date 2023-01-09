<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActaInmueble;
use App\Models\ActaInmuebleImagen;
use App\Models\ActaProvincia;
use App\Models\ActaCanton;
use App\Models\ActaCiudad;
use App\Models\ActaParroquia;
use App\Models\ActaEstado;
use App\Models\ActaInmuebleMunicipio;
use App\Models\ActaInmuebleEscritura;
use App\Models\ActaInmuebleAvaluo;
use App\Models\ActaInmuebleEntorno;
use App\Models\ActaInmuebleTerreno;
use App\Models\ActaInmuebleEdificacion;
use App\Models\ActaInmuebleCriterioValoracion;
use App\Models\ActaInmuebleResumenValoracion;
use App\Models\ActaInmuebleImagenTipo;
use App\Models\ActaInmuebleFlujo;

use Carbon\Carbon;


class ActaInmuebleController extends Controller
{
    
    //listar actainmueble
    public function buscaractainmuebles(Request $request){
        
        $acta_inmueble_numero_inmueble = $request-> acta_inmueble_numero_inmueble;
        $acta_inmueble_estado = $request-> acta_inmueble_estado;
        $acta_inmueble_empresa = $request-> acta_inmueble_empresa;
        $acta_inmueble_fecha_inicio = $request-> acta_inmueble_fecha_inicio;
        $acta_inmueble_fecha_fin = $request-> acta_inmueble_fecha_fin;
        $where = [];



        if ($acta_inmueble_numero_inmueble != null) 
            $where[] = ['acta_inmueble.numero_interno', '=',$acta_inmueble_numero_inmueble];
        
        if ($acta_inmueble_estado != '') 
            $where[] = ['acta_inmueble.acta_estado_id', '=',$acta_inmueble_estado];

        if ($acta_inmueble_empresa != '') 
            $where[] = ['acta_inmueble.empresa_id', '=',$acta_inmueble_empresa];    

        $start_date_convert = null;
        $end_date_convert = null;
    
        if ($acta_inmueble_fecha_inicio != null)
            $start_date_convert = Carbon::createFromFormat('Y-m-d', $acta_inmueble_fecha_inicio)->startOfDay();
        
        if ($acta_inmueble_fecha_fin != null) {
            $end_date_convert = Carbon::createFromFormat('Y-m-d', $acta_inmueble_fecha_fin)->endOfDay();   
            $end_date_convert = $end_date_convert->addDays(1);
        }

        $recupera = DB::table('acta_inmueble')
                ->join('acta_estado', 'acta_estado.id', '=', 'acta_inmueble.acta_estado_id')
                ->where($where)
                ->Where(function ($query) use ($start_date_convert, $end_date_convert) {
                    if($start_date_convert == null || $end_date_convert == null)
                        return null;
                    $query->WhereBetween('acta_inmueble.fcrea', [$start_date_convert, $end_date_convert]);
                })
                ->select(
                    'acta_inmueble.id as id',
                    'acta_inmueble.nombre as nombre',
                    'acta_inmueble.institucion as institucion',
                    'acta_estado.nombre as estado_nombre',
                    'acta_inmueble.fcrea as fecha_creacion'
                    )
                    ->orderByRaw('acta_inmueble.id ASC')->get();
        return [
            'recupera' => $recupera
        ];
    }

     //guardar actainmueble
     public function guardarinmueble(Request $request){

        $editor_inmueble = $request['editor_inmueble'];
        $user_id = $request['user_id'];
        $empresa_id = $request['empresa_id'];

        $dom = new \DOMDocument;
        $dom->loadHTML(mb_convert_encoding( $editor_inmueble, 'HTML-ENTITIES', 'UTF-8'));

        $tables = $dom->getElementsByTagName('table');
        $contador = 1;

        $tabla_general = new \DOMDocument;

        foreach($tables as $table) {

            if($contador == 1){
                
                $tabla_general = $dom->saveHTML($table);     
            } else if ($contador == 2){
                //$tabla_proveedor_datos = $dom->saveHTML($table);
            } else if ($contador == 3){
                //$tabla_cliente_direccion = $dom->saveHTML($table);    
            } else if ($contador == 5){
                //$tabla_detalle_completa = $dom->saveHTML($table);    
            } else if ($contador == 6){
                //$tabla_detalle = $dom->saveHTML($table);
            } 
            $contador += 1;          
        }

        $dom_tabla = new \DOMDocument();
        $dom_tabla->loadHTML(mb_convert_encoding($tabla_general, 'HTML-ENTITIES', 'UTF-8'));
        $listado_tr_tabla = $dom_tabla->getElementsByTagName('tr');


        $nombre = '';
        $institucion = '';
        $finalidad_avaluo = '';
        $agencia_oficina = '';
        $nombre_cliente = '';
        $direccion = '';
        $fecha_inspeccion = '';
        $tipo_bien_descripcion = '';
        $tipo_bien_descripcion_detalle = ''; 
        $ubicacion = '';
        $ubicacion_provincia = '';
        $ubicacion_canton = '';
        $ubicacion_ciudad = '';
        $ubicacion_parroquia = '';
        $ubicacion_barrio = '';
        $ubicacion_manzana = '';
        $ubicacion_lote = '';
        $ubicacion_latitud = '';
        $ubicacion_longitud = '';
        $ubicacion_predio = '';
        $datos_municipales_detalle = '';
        $ano_impuesto_predial = 0;
        $clave_catastral = '';
        $geo_clave = '';
        $ano_1_numero = 0;
        $ano_1_valor = 0;
        $ano_1_construccion = 0;
        $ano_1_terreno = 0;
        $ano_2_numero = 0;
        $ano_2_valor = 0;
        $ano_2_construccion = 0;
        $ano_2_terreno = 0;

        $datos_escritura_detalle = '';
        $datos_escritura_notaria = '';
        $datos_escritura_canton = 223; // NO ENCONTRADO
        $datos_escritura_fecha = '';
        $datos_escritura_superficie = 0;
        $datos_escritura_cuantia = 0;

        $datos_avaluo_detalle = '';
        $datos_avaluo_valor_reposicion = 0;
        $datos_avaluo_valor_actual = 0;
        $datos_avaluo_realizacion = 0;
        $datos_avaluo_reposicion_construccion = 0;
        $datos_avaluo_actual_construccion = 0;
        $datos_avaluo_realizacion_construccion = 0;
        $datos_avaluo_total_reposicion = 0;
        $datos_avaluo_total_actual = 0;
        $datos_avaluo_total_realizacion = 0;

        $contador_tr = 1;
        foreach($listado_tr_tabla as $tr) {
            if($contador_tr == 1) {
                $nombre = $tr->childNodes->item(3)->nodeValue;
            }
            if($contador_tr == 3) {
                $institucion = $tr->childNodes->item(3)->nodeValue;
                $finalidad_avaluo = $tr->childNodes->item(7)->nodeValue;
            }
            if($contador_tr == 4) {
                $agencia_oficina = $tr->childNodes->item(3)->nodeValue;
                $nombre_cliente = $tr->childNodes->item(7)->nodeValue;
            }
            if($contador_tr == 5) {
                $direccion = $tr->childNodes->item(3)->nodeValue;
                $fecha_inspeccion = $tr->childNodes->item(7)->nodeValue;
            }
            if($contador_tr == 7) {
                $tipo_bien_descripcion = $tr->childNodes->item(3)->nodeValue;
            }
            if($contador_tr == 8) {
                $tipo_bien_descripcion_detalle = $tr->childNodes->item(1)->nodeValue;
            }
            if($contador_tr == 9) {
                $ubicacion = $tr->childNodes->item(3)->nodeValue;
            }
            if($contador_tr == 10) {
                // Buscar provincia por nombre pero con mayuscula
                $ubicacion_provincia = $tr->childNodes->item(3)->nodeValue;
                $provincia = ActaProvincia::where('nombre', 'LIKE', '%'.strtoupper($ubicacion_provincia).'%')
                ->get()->first();

                if($provincia == null) {
                    $ubicacion_provincia = 26; // NO ENCONTRADO
                } else {
                    $ubicacion_provincia = $provincia -> id;         
                }
                // Buscar canton por nombre pero con mayuscula
                $ubicacion_canton = $tr->childNodes->item(7)->nodeValue;
                $canton = ActaCanton::where('nombre', 'LIKE', '%'.strtoupper($ubicacion_canton).'%')
                ->get()->first();
                if($canton == null) {
                    $ubicacion_canton = 223; // NO ENCONTRADO
                } else {
                    $ubicacion_canton = $canton -> id;         
                }    

                // Buscar ciudad por nombre pero con mayuscula
                $ubicacion_ciudad = $tr->childNodes->item(11)->nodeValue;
                $ciudad = ActaCiudad::where('nombre', 'LIKE', '%'.strtoupper($ubicacion_ciudad).'%')
                ->get()->first();
                if($ciudad == null) {
                    $ubicacion_ciudad = 222;  // NO ENCONTRADO
                } else {
                    $ubicacion_ciudad = $ciudad -> id;         
                }

                // Buscar parroquia por nombre pero con mayuscula
                $ubicacion_parroquia = $tr->childNodes->item(15)->nodeValue;
                $parroquia = ActaParroquia::where('nombre', 'LIKE', '%'.strtoupper($ubicacion_parroquia).'%')
                ->get()->first();
                if($parroquia == null) {
                    $ubicacion_parroquia = 1025;
                } else {
                    $ubicacion_parroquia = $parroquia -> id;         
                }
            }
            if($contador_tr == 11) {
                $ubicacion_barrio = $tr->childNodes->item(3)->nodeValue;
                $ubicacion_manzana = $tr->childNodes->item(7)->nodeValue;
                $ubicacion_lote = $tr->childNodes->item(11)->nodeValue;
            }
            if($contador_tr == 12) {
                $ubicacion_latitud= $tr->childNodes->item(3)->nodeValue;
                $ubicacion_longitud = $tr->childNodes->item(7)->nodeValue;
                $ubicacion_predio = $tr->childNodes->item(11)->nodeValue;
            }
            if($contador_tr == 13) {
                $datos_municipales_detalle = $tr->childNodes->item(3)->nodeValue;             
            }
            if($contador_tr == 14) {
                $ano_impuesto_predial = $tr->childNodes->item(3)->nodeValue;
                $clave_catastral = $tr->childNodes->item(7)->nodeValue;
                $geo_clave = $tr->childNodes->item(11)->nodeValue;                   
            }
            if($contador_tr == 16) {
                $ano_1_numero = $tr->childNodes->item(1)->nodeValue;
                $ano_1_valor = $tr->childNodes->item(3)->nodeValue;
                $ano_1_construccion = $tr->childNodes->item(5)->nodeValue;
                $ano_1_terreno = $tr->childNodes->item(7)->nodeValue;
            }
            if($contador_tr == 17) {
                $ano_2_numero = $tr->childNodes->item(1)->nodeValue;
                $ano_2_valor = $tr->childNodes->item(3)->nodeValue;
                $ano_2_construccion = $tr->childNodes->item(5)->nodeValue;
                $ano_2_terreno = $tr->childNodes->item(7)->nodeValue;
            }
            if($contador_tr == 18) {
                $datos_escritura_detalle = $tr->childNodes->item(3)->nodeValue;
            }
            if($contador_tr == 19) {
                $datos_escritura_notaria = $tr->childNodes->item(3)->nodeValue;
                $datos_escritura_canton = $tr->childNodes->item(7)->nodeValue;

                $canton_escritura = ActaCanton::where('nombre', 'LIKE', '%'.strtoupper($datos_escritura_canton).'%')
                ->get()->first();
                if($canton_escritura == null) {
                    $datos_escritura_canton = 223; // NO ENCONTRADO
                } else {
                    $datos_escritura_canton = $canton_escritura -> id;         
                }
            }
            if($contador_tr == 20) {
                $datos_escritura_fecha = $tr->childNodes->item(3)->nodeValue;
                $datos_escritura_superficie = $tr->childNodes->item(7)->nodeValue;
            }
            if($contador_tr == 21) {
                $datos_escritura_cuantia = $tr->childNodes->item(3)->nodeValue;
            }
            if($contador_tr == 22) {
                $datos_avaluo_detalle = $tr->childNodes->item(5)->nodeValue;
            }
            if($contador_tr == 24) {
                $datos_avaluo_valor_reposicion = $tr->childNodes->item(3)->nodeValue;
                $datos_avaluo_valor_actual = $tr->childNodes->item(5)->nodeValue;
                $datos_avaluo_realizacion = $tr->childNodes->item(7)->nodeValue;
            }
            if($contador_tr == 25) {
                $datos_avaluo_reposicion_construccion = $tr->childNodes->item(3)->nodeValue;
                $datos_avaluo_actual_construccion = $tr->childNodes->item(5)->nodeValue;
                $datos_avaluo_realizacion_construccion = $tr->childNodes->item(7)->nodeValue;
            }
            if($contador_tr == 26) {
                $datos_avaluo_total_reposicion = $tr->childNodes->item(3)->nodeValue;
                $datos_avaluo_total_actual = $tr->childNodes->item(5)->nodeValue;
                $datos_avaluo_total_realizacion = $tr->childNodes->item(7)->nodeValue;
            }

            $contador_tr += 1; 
        }


        $contador_tr = 1;
        
        $acta_inmueble_entorno_detalle = '';
        $acta_inmueble_entorno_listado = [];
        $acta_inmueble_entorno_servicio = '';
        $acta_inmueble_entorno_impacto_ambiental = '';
        $acta_inmueble_entorno_equipamiento_zona = [];
        $acta_inmueble_entorno_descripcion_zona = '';
        $acta_inmueble_entorno_observacion_ocupacion = '';
    
        $esta_2 = false;
        $inicia_2_1 = false;
        $inicia_2_2 = false;
        $inicia_2_3 = false;
        $inicia_2_4 = false;
        $inicia_2_5 = false;
        $inicia_2_6 = false;

        $contador_2_1 = 0;
        $contador_2_2 = 0;
        $contador_2_3 = 0;
        $contador_2_4 = 0;
        $contador_2_5 = 0;
        $contador_2_6 = 0;


        $acta_inmueble_terreno_localizacion = [];
        $acta_inmueble_terreno_caracteristicas_fisicas = [];
        $acta_inmueble_terreno_cerramiento = '';
        $acta_inmueble_terreno_linderos = [];
        $acta_inmueble_terreno_area_terreno = [];
        
        $esta_3 = false;
        $inicia_3_1 = false;
        $inicia_3_2 = false;
        $inicia_3_3 = false;
        $inicia_3_4 = false;

        $contador_3_1 = 0;
        $contador_3_2 = 0;
        $contador_3_3 = 0;
        $contador_3_4 = 0;

        $acta_inmueble_edificacion_caracteristicas = [];
        $acta_inmueble_edificacion_areas_edificacion = [];
        $acta_inmueble_edificacion_areas_edificacion_total = [];
        $acta_inmueble_edificacion_areas_edificacion_otros = [];
        $acta_inmueble_edificacion_areas_edificacion_otros_total = [];
        $acta_inmueble_edificacion_resumen_infraestructura = [];
        $acta_inmueble_edificacion_conservacion_mantenimiento = '';
        $acta_inmueble_edificacion_descripcion_funcional = '';
        
        $esta_4 = false;
        $inicia_4_1 = false;
        $inicia_4_2 = false;
        $inicia_4_2_1 = false;
        $inicia_4_3 = false;
        $inicia_4_4 = false;
        $inicia_4_5 = false;

        $contador_4_1 = 0;
        $contador_4_2 = 0;
        $contador_4_2_1 = 0;
        $contador_4_3 = 0;
        $contador_4_4 = 0;
        $contador_4_5 = 0;

        $acta_inmueble_criterios_valoracion = [];
        $acta_inmueble_criterios_valoracion_calificacion = [];
        $acta_inmueble_valoracion_terreno_detalle = '';
        $acta_inmueble_valoracion_terreno = [];
        $acta_inmueble_valoracion_terreno_total = [];
        $acta_inmueble_valoracion_construcciones = '';
 
        $esta_5 = false;
        $inicia_5_1 = false;
        $inicia_5_2_1 = false;
        $inicia_5_2_2 = false;
        $tiene_valoracion_calificacion = false;
        $tiene_tabla_plantilla_antecedentes = false;
        $contador_5_1 = 0;
        $contador_5_2_1 = 0;
        $contador_5_2_2 = 0;
        $contador_calificacion = 0;
        $contador_tabla_plantilla = 0;

        $esta_6 = false;
        $inicia_6_1 = false;
        $contador_6 = 0;
        $acta_inmueble_resumen_valoracion = [];
        $acta_inmueble_resumen_valoracion_reposicion = '';
        $acta_inmueble_resumen_valoracion_mercado = '';
        $acta_inmueble_resumen_valoracion_realizacion = '';

        foreach($listado_tr_tabla as $tr) {
            $fila_valor = $tr->childNodes->item(1)->nodeValue;
            
            if(\Str::contains($fila_valor, '2.- CARACTERÍSTICAS DEL ENTORNO')){
                $esta_2 = true;
                $esta_3 = false;
                $esta_4 = false;
                $esta_5 = false;
                $esta_6 = false;
            }
            if(\Str::contains($fila_valor, '3.- TERRENO')){
                $esta_2 = false;
                $esta_3 = true;
                $esta_4 = false;
                $esta_5 = false;
                $esta_6 = false;
            }
            if(\Str::contains($fila_valor, '4.- EDIFICACIÓN')){
                $esta_2 = false;
                $esta_3 = false;
                $esta_4 = true;
                $esta_5 = false;
                $esta_6 = false;
            }
            if(\Str::contains($fila_valor, '5.- CRITERIOS Y MÉTODOS EMPLEADOS EN LA VALORACIÓN:')){
                $esta_2 = false;
                $esta_3 = false;
                $esta_4 = false;
                $esta_5 = true;
                $esta_6 = false;
            }
            if(\Str::contains($fila_valor, '6.- CUADRO RESUMEN DE VALORACIÓN')){
                $esta_2 = false;
                $esta_3 = false;
                $esta_4 = false;
                $esta_5 = false;
                $esta_6 = true;
            }

            if($esta_2 == true){

                if(\Str::contains($fila_valor, '2.1.-')){
                    $acta_inmueble_entorno_detalle = $tr->childNodes->item(3)->nodeValue;
                    $inicia_2_1 = true;
                    $inicia_2_2 = false;
                    $inicia_2_3 = false;
                    $inicia_2_4 = false;
                    $inicia_2_5 = false;
                    $inicia_2_6 = false;
                }
                if(\Str::contains($fila_valor, '2.2.-')){
                    
                    $inicia_2_1 = false;
                    $inicia_2_2 = true;
                    $inicia_2_3 = false;
                    $inicia_2_4 = false;
                    $inicia_2_5 = false;
                    $inicia_2_6 = false;
                }
                if(\Str::contains($fila_valor, '2.3.-')){
                    $inicia_2_1 = false;
                    $inicia_2_2 = false;
                    $inicia_2_3 = true;
                    $inicia_2_4 = false;
                    $inicia_2_5 = false;
                    $inicia_2_6 = false;
                }
                if(\Str::contains($fila_valor, '2.4.-')){
                    $inicia_2_1 = false;
                    $inicia_2_2 = false;
                    $inicia_2_3 = false;
                    $inicia_2_4 = true;
                    $inicia_2_5 = false;
                    $inicia_2_6 = false;
                }
                if(\Str::contains($fila_valor, '2.5.-')){
                    $inicia_2_1 = false;
                    $inicia_2_2 = false;
                    $inicia_2_3 = false;
                    $inicia_2_4 = false;
                    $inicia_2_5 = true;
                    $inicia_2_6 = false;
                }
                if(\Str::contains($fila_valor, '2.6.-')){
                    $inicia_2_1 = false;
                    $inicia_2_2 = false;
                    $inicia_2_3 = false;
                    $inicia_2_4 = false;
                    $inicia_2_5 = false;
                    $inicia_2_6 = true;
                }

                if($inicia_2_1 == true){
                    if($contador_2_1 > 0){
                        $key = $tr->childNodes->item(1)->nodeValue;
                        $value = $tr->childNodes->item(3)->nodeValue; 
                        $acta_inmueble_entorno_listado[$key] = $value;
                    }
                    $contador_2_1++;
                } elseif($inicia_2_2 == true){
                    if($contador_2_2 == 1){
                        $acta_inmueble_entorno_servicio = $tr->childNodes->item(1)->nodeValue;
                    }    
                    $contador_2_2++;
                } elseif($inicia_2_3 == true){
                    if($contador_2_3 == 1 ){
                        $acta_inmueble_entorno_impacto_ambiental = $tr->childNodes->item(1)->nodeValue;
                    }    
                    $contador_2_3++;
                }
                if($inicia_2_4 == true){
                    if($contador_2_4 >= 1){
                        $key = $tr->childNodes->item(1)->nodeValue;
                        $value = $tr->childNodes->item(3)->nodeValue; 
                        $acta_inmueble_entorno_equipamiento_zona[$key] = $value;
                    }
                    $contador_2_4++;
                }
                if($inicia_2_5 == true){
                    if($contador_2_5 == 1){
                        $acta_inmueble_entorno_descripcion_zona = $tr->childNodes->item(1)->nodeValue;
                    }    
                    $contador_2_5++;
                }
                if($inicia_2_6 == true){
                    if($contador_2_6 == 1){
                        $acta_inmueble_entorno_observacion_ocupacion = $tr->childNodes->item(1)->nodeValue;
                    }    
                    $contador_2_6++;
                }   
            }   
            if($esta_3 == true){
                
                if(\Str::contains($fila_valor, '3.1')){
                    $inicia_3_1 = true;
                    $inicia_3_2 = false;
                    $inicia_3_3 = false;
                    $inicia_3_4 = false;
                }
                if(\Str::contains($fila_valor, '3.2')){
                    $inicia_3_1 = false;
                    $inicia_3_2 = true;
                    $inicia_3_3 = false;
                    $inicia_3_4 = false;
                }
                if(\Str::contains($fila_valor, '3.3')){
                    $inicia_3_1 = false;
                    $inicia_3_2 = false;
                    $inicia_3_3 = true;
                    $inicia_3_4 = false;
                }
                if(\Str::contains($fila_valor, '3.4')){
                    $inicia_3_1 = false;
                    $inicia_3_2 = false;
                    $inicia_3_3 = false;
                    $inicia_3_4 = true;
                }

                if($inicia_3_1 == true){
                    if($contador_3_1 > 0){
                        $key = $tr->childNodes->item(1)->nodeValue;
                        $value = $tr->childNodes->item(3)->nodeValue; 
                        $acta_inmueble_terreno_localizacion[$key] = $value;
                    }
                    $contador_3_1++;
                }

                if($inicia_3_2 == true){                   
                    if($contador_3_2 > 0){
                        $key = $tr->childNodes->item(1)->nodeValue;
                        $value = $tr->childNodes->item(3)->nodeValue; 
                        $acta_inmueble_terreno_caracteristicas_fisicas[$key] = $value;
                    }
                    $contador_3_2++;
                }
                if($inicia_3_3 == true){
                    if($contador_3_3 == 1){
                        $acta_inmueble_terreno_cerramiento = $tr->childNodes->item(1)->nodeValue;
                    }    
                    $contador_3_3++;
                }
                
                if($inicia_3_4 == true){
                    if(\Str::contains($fila_valor, 'ÁREA DEL TERRENO (m2):')){
                        $acta_inmueble_terreno_area_terreno = $tr->childNodes->item(3)->nodeValue.'|'.$tr->childNodes->item(5)->nodeValue; 
                        $inicia_3_4 == false; 
                    } else {
                    if($contador_3_4 > 1){
                            $linderos = $tr->childNodes->item(1)->nodeValue;
                            $coordenadas = $tr->childNodes->item(3)->nodeValue;
                            $descripcion = $tr->childNodes->item(5)->nodeValue;
                            $escritura = $tr->childNodes->item(7)->nodeValue;
                            $comprobacion_sitio = $tr->childNodes->item(9)->nodeValue;
                            if(strlen($linderos) != 2){
                                $acta_inmueble_terreno_linderos [$contador_3_4] = $linderos.'|'.$coordenadas.'|'.$descripcion.'|'.$escritura.'|'.$comprobacion_sitio;
                            }
                        }    
                        $contador_3_4++;
                    }
                }
            }    
            if($esta_4 == true){
                
                if(\Str::contains($fila_valor, '4.1')){
                    $inicia_4_1 = true;
                    $inicia_4_2 = false;
                    $inicia_4_2_1 = false;
                    $inicia_4_3 = false;
                    $inicia_4_4 = false;
                    $inicia_4_5 = false;
                }
                if(\Str::contains($fila_valor, '4.2')){
                    $inicia_4_1 = false;
                    $inicia_4_2 = true;
                    $inicia_4_2_1 = false;
                    $inicia_4_3 = false;
                    $inicia_4_4 = false;
                    $inicia_4_5 = false;
                }
                if(\Str::contains($fila_valor, '4.2.1')){
                    $inicia_4_1 = false;
                    $inicia_4_2 = false;
                    $inicia_4_2_1 = true;
                    $inicia_4_3 = false;
                    $inicia_4_4 = false;
                    $inicia_4_5 = false;
                }
                if(\Str::contains($fila_valor, '4.3')){
                    $inicia_4_1 = false;
                    $inicia_4_2 = false;
                    $inicia_4_2_1 = false;
                    $inicia_4_3 = true;
                    $inicia_4_4 = false;
                    $inicia_4_5 = false;
                }
                if(\Str::contains($fila_valor, '4.4')){
                    $inicia_4_1 = false;
                    $inicia_4_2 = false;
                    $inicia_4_2_1 = false;
                    $inicia_4_3 = false;
                    $inicia_4_4 = true;
                    $inicia_4_5 = false;
                }
                if(\Str::contains($fila_valor, '4.5')){
                    $inicia_4_1 = false;
                    $inicia_4_2 = false;
                    $inicia_4_2_1 = false;
                    $inicia_4_3 = false;
                    $inicia_4_4 = false;
                    $inicia_4_5 = true;
                }

                if($inicia_4_1 == true){
                
                    if($contador_4_1 > 0){
                        $key = $tr->childNodes->item(1)->nodeValue;
                        $value = $tr->childNodes->item(3)->nodeValue; 
                        $acta_inmueble_edificacion_caracteristicas[$key] = $value;
                    }
                    $contador_4_1++;
                }
                if($inicia_4_2 == true){
                    if(\Str::contains($fila_valor, 'TOTAL ÁREA DE EDIFICACIÓN m2:')){
                        $acta_inmueble_edificacion_areas_edificacion_total['total_area_edificacion'] = $tr->childNodes->item(3)->nodeValue.'|'.$tr->childNodes->item(5)->nodeValue; 
                        $inicia_4_2 == false; 
                    } else {
                        if($contador_4_2 > 1){
                                $descripcion = $tr->childNodes->item(1)->nodeValue;
                                $area_cubierta = $tr->childNodes->item(3)->nodeValue;
                                $area_descubierta = $tr->childNodes->item(5)->nodeValue;
                            
                                if(strlen($descripcion) != 2){
                                    $acta_inmueble_edificacion_areas_edificacion [$descripcion] = $area_cubierta.'|'.$area_descubierta;
                                }   
                        }
                        $contador_4_2++;
                    }
                }
                if($inicia_4_2_1 == true){
                    if(\Str::contains($fila_valor, 'TOTAL ÁREA OTROS m2:')){
                        $acta_inmueble_edificacion_areas_edificacion_otros_total['total_area_otros'] = $tr->childNodes->item(3)->nodeValue.'|'.$tr->childNodes->item(5)->nodeValue; 
                        $inicia_4_2_1 == false; 
                    } else {
                        if($contador_4_2_1 > 1){
                                $descripcion = $tr->childNodes->item(1)->nodeValue;
                                $area_cubierta = $tr->childNodes->item(3)->nodeValue;
                                $area_descubierta = $tr->childNodes->item(5)->nodeValue;
                            
                                if(strlen($descripcion) != 2){
                                    $acta_inmueble_edificacion_areas_edificacion_otros [$descripcion] = $area_cubierta.'|'.$area_descubierta;
                                }   
                        }
                        $contador_4_2_1++;
                    }
                }
                if($inicia_4_3 == true){
                    if($contador_4_3 > 1){
                        $descripcion = $tr->childNodes->item(1)->nodeValue;
                        $unidad = $tr->childNodes->item(3)->nodeValue;
                        $cantidad = $tr->childNodes->item(5)->nodeValue;
                            
                            if(strlen($descripcion) != 2){
                                $acta_inmueble_edificacion_resumen_infraestructura [$descripcion] = $unidad.'|'.$cantidad;
                            }   
                    }
                    $contador_4_3++;
                }
                if($inicia_4_4 == true){
                    if($contador_4_4 == 1){
                        $acta_inmueble_edificacion_conservacion_mantenimiento = $tr->childNodes->item(1)->nodeValue;
                    }    
                    $contador_4_4++;
                }
                if($inicia_4_5 == true){
                    if($contador_4_5 == 1){
                        $acta_inmueble_edificacion_descripcion_funcional = $tr->childNodes->item(1)->nodeValue;
                    }    
                    $contador_4_5++;
                }
            }    
            if($esta_5 == true){
                if(\Str::contains($fila_valor, '5.1')){
                    $inicia_5_1 = true;
                    $inicia_5_2_1 = false;
                    $inicia_5_2_2 = false;
                }
                if(\Str::contains($fila_valor, '5.2.1')){
                    $inicia_5_1 = false;
                    $inicia_5_2_1 = true;
                    $inicia_5_2_2 = false;
                }
                if(\Str::contains($fila_valor, '5.2.2')){
                    $inicia_5_1 = false;
                    $inicia_5_2_1 = false;
                    $inicia_5_2_2 = true;
                }
                    
                if($inicia_5_1 == true){
                    if(\Str::contains($fila_valor, 'TIPOLÓGICAS DEL SECTOR:')){
                        $acta_inmueble_criterios_valoracion[$tr->childNodes->item(1)->nodeValue] = $tr->childNodes->item(3)->nodeValue;  
                    }
                    if(\Str::contains($fila_valor, 'ENTORNO SOCIAL:')){
                        $acta_inmueble_criterios_valoracion[$tr->childNodes->item(1)->nodeValue] = $tr->childNodes->item(3)->nodeValue;  
                    }
                    if(\Str::contains($fila_valor, 'OFERTA Y DEMANDA EN LA ZONA ESTUDIADA:')){
                        $acta_inmueble_criterios_valoracion[$tr->childNodes->item(1)->nodeValue] = $tr->childNodes->item(3)->nodeValue;  
                    } 
                    if(\Str::contains($fila_valor, 'PLUSVALÍA DEL SECTOR:')){
                        $acta_inmueble_criterios_valoracion[$tr->childNodes->item(1)->nodeValue] = $tr->childNodes->item(3)->nodeValue;  
                    } 
                    if(\Str::contains($fila_valor, 'CALIFICACIÓN DEL INMUEBLE')){
                        $tiene_valoracion_calificacion = true;                        
                    }
                    if(\Str::contains($fila_valor, 'FACTIBILIDAD COMERCIAL:')){
                        $tiene_valoracion_calificacion = false;
                        $acta_inmueble_criterios_valoracion[$tr->childNodes->item(1)->nodeValue] = $tr->childNodes->item(3)->nodeValue;  
                    } 
                    if(\Str::contains($fila_valor, 'VALOR DE REALIZACIÓN:')){                                  
                        $acta_inmueble_criterios_valoracion[$tr->childNodes->item(1)->nodeValue] = $tr->childNodes->item(3)->nodeValue;  
                        $acta_inmueble_criterios_valoracion[$tr->childNodes->item(5)->nodeValue] = $tr->childNodes->item(7)->nodeValue;
                    } 
                    if($tiene_valoracion_calificacion == true){
                        if($contador_calificacion >=1){                  
                            $acta_inmueble_criterios_valoracion_calificacion[$tr->childNodes->item(1)->nodeValue] = $tr->childNodes->item(3)->nodeValue;
                        }           
                        $contador_calificacion++; 
                    }
                }    
                if($inicia_5_2_1 == true){
                    
                    if($contador_5_2_1 == 2){
                        $acta_inmueble_valoracion_terreno_detalle = $tr->childNodes->item(1)->nodeValue;
                    }    
                        $contador_5_2_1++;
                        if(\Str::contains($fila_valor, 'PLANILLA Y SELECCIÓN DE ANTECEDENTES')){
                            $tiene_tabla_plantilla_antecedentes = true;
                        }
        
                        if(\Str::contains($fila_valor, 'TOTAL PLANILLA Y SELECCIÓN ANTECEDENTES:')){
                            $acta_inmueble_valoracion_terreno_total['valoracion_terreno_total'] = $tr->childNodes->item(3)->nodeValue;  
                            $tiene_tabla_plantilla_antecedentes == false;
                            $contador_tabla_plantilla = 0;
                        }
                        if($tiene_tabla_plantilla_antecedentes == true){
                            if($contador_tabla_plantilla >= 3){
                                if($tr->childNodes->length == 21){
                                    $terrenos_en_venta = $tr->childNodes->item(1)->nodeValue;
                                    $area_m_2 = $tr->childNodes->item(3)->nodeValue;
                                    $valor_m_2 = $tr->childNodes->item(5)->nodeValue;
                                    $frente = $tr->childNodes->item(7)->nodeValue;
                                    $ubicacion_planilla = $tr->childNodes->item(9)->nodeValue;
                                    $tamano = $tr->childNodes->item(11)->nodeValue;
                                    $forma = $tr->childNodes->item(13)->nodeValue;
                                    $adecuacion = $tr->childNodes->item(15)->nodeValue;
                                    $homogenizados = $tr->childNodes->item(17)->nodeValue;
                                    $valor_unitario = $tr->childNodes->item(19)->nodeValue;   
                                    if(strlen($terrenos_en_venta) != 2){
                                        $acta_inmueble_valoracion_terreno [$contador_tabla_plantilla] = $terrenos_en_venta.'|'.$area_m_2.'|'.$valor_m_2.'|'.$frente.'|'.$ubicacion_planilla.'|'.$tamano.'|'.$forma.'|'.$adecuacion.'|'.$homogenizados.'|'.$valor_unitario;
                                    }
                                }
                            }
                            $contador_tabla_plantilla++;
                        }      
                }    
                if($inicia_5_2_2 == true){
                    if($contador_5_2_2 == 1){
                        $tiene_tabla_plantilla_antecedentes == false;
                        $acta_inmueble_valoracion_construcciones = $tr->childNodes->item(1)->nodeValue;
                    }    
                        $contador_5_2_2++;
                }   
            }    
            if($esta_6 == true){
                if($contador_6 > 1){

                    if($tr->childNodes->length > 7){
                        $descripcion = $tr->childNodes->item(1)->nodeValue;
                        $area_m_2 = $tr->childNodes->item(3)->nodeValue;
                        $valor_reposicion = $tr->childNodes->item(5)->nodeValue;
                        $valor_actual = $tr->childNodes->item(7)->nodeValue;
                        $valor_realizacion = $tr->childNodes->item(9)->nodeValue;
                        
                        if(\Str::contains($descripcion, 'TOTALES')){
                            $acta_inmueble_resumen_valoracion_reposicion = $valor_reposicion;
                            $acta_inmueble_resumen_valoracion_mercado = $valor_actual;
                            $acta_inmueble_resumen_valoracion_realizacion = $valor_realizacion;                 
                        }
                        if(strlen($descripcion) != 2){
                            $acta_inmueble_resumen_valoracion [$contador_6] = $descripcion.'|'.$area_m_2.'|'.$valor_reposicion.'|'.$valor_actual.'|'.$valor_realizacion;
                        }
                    }
                }        
                $contador_6++;
            }    
        }
        
        $actainmueble = new ActaInmueble();
        $actainmueble->nombre = $nombre;
        $actainmueble->institucion = $institucion;
        $actainmueble->finalidad_avaluo = $finalidad_avaluo;
        $actainmueble->agencia_oficina = $agencia_oficina;
        $actainmueble->nombre_cliente = $nombre_cliente;
        $actainmueble->direccion = $direccion;
        $actainmueble->fecha_inspeccion = $fecha_inspeccion;
        $actainmueble->tipo_bien_descripcion = $tipo_bien_descripcion;
        $actainmueble->tipo_bien_descripcion_detalle = $tipo_bien_descripcion_detalle;
        $actainmueble->ubicacion = $ubicacion;
        $actainmueble->acta_provincia_id = $ubicacion_provincia;
        $actainmueble->acta_canton_id = $ubicacion_canton;
        $actainmueble->acta_ciudad_id = $ubicacion_ciudad;
        $actainmueble->acta_parroquia_id = $ubicacion_parroquia;
        $actainmueble->ubicacion_barrio = $ubicacion_barrio;
        $actainmueble->ubicacion_manzana = $ubicacion_manzana;
        $actainmueble->ubicacion_lote = $ubicacion_lote;
        $actainmueble->ubicacion_latitud = $ubicacion_latitud;
        $actainmueble->ubicacion_longitud = $ubicacion_longitud;
        $actainmueble->ubicacion_predio = $ubicacion_predio;
        $actainmueble->acta_estado_id = 11; // Creado Estado Inmueble
        $actainmueble->user_id = $user_id;
        $actainmueble->empresa_id = $empresa_id;
        $actainmueble->save();

        $actainmuebleflujo = new ActaInmuebleFlujo();
        $estado = ActaEstado::find(11);
        $actainmuebleflujo-> detalle = "Estado actual ".$estado->nombre;
        $actainmuebleflujo-> acta_inmueble_id = $actainmueble->id;
        $actainmuebleflujo-> user_id = $user_id;
        $actainmuebleflujo->save();


        $actainmueblemunicipio = new ActaInmuebleMunicipio();
        $actainmueblemunicipio -> detalle = $datos_municipales_detalle;
        $actainmueblemunicipio -> ano_impuesto_predial = $ano_impuesto_predial;
        $actainmueblemunicipio -> clave_catastral = $clave_catastral;
        $actainmueblemunicipio -> geo_clave = $geo_clave;
        $actainmueblemunicipio -> ano_1_numero = $ano_1_numero;
        $actainmueblemunicipio -> ano_1_valor = $ano_1_valor;
        $actainmueblemunicipio -> ano_1_construccion = $ano_1_construccion;
        $actainmueblemunicipio -> ano_1_terreno = $ano_1_terreno;
        $actainmueblemunicipio -> ano_2_numero = $ano_2_numero;
        $actainmueblemunicipio -> ano_2_valor = $ano_2_valor;
        $actainmueblemunicipio -> ano_2_construccion = $ano_2_construccion;
        $actainmueblemunicipio -> ano_2_terreno = $ano_2_terreno;
        $actainmueblemunicipio->user_id = $user_id;
        $actainmueblemunicipio->acta_inmueble_id = $actainmueble -> id;
        $actainmueblemunicipio->save();

        $actainmuebleescritura = new ActaInmuebleEscritura();
        $actainmuebleescritura -> detalle = $datos_escritura_detalle;
        $actainmuebleescritura -> notaria = $datos_escritura_notaria;
        $actainmuebleescritura -> acta_canton_id = $datos_escritura_canton;
        $actainmuebleescritura -> fecha_escrituracion_registro = $datos_escritura_fecha;
        $actainmuebleescritura -> superficie = $datos_escritura_superficie;
        $actainmuebleescritura -> cuantia = $datos_escritura_cuantia;
        $actainmuebleescritura->user_id = $user_id;
        $actainmuebleescritura->acta_inmueble_id = $actainmueble -> id;
        $actainmuebleescritura->save();

        $actainmuebleavaluo = new ActaInmuebleAvaluo();
        $actainmuebleavaluo -> detalle = $datos_avaluo_detalle;
        $actainmuebleavaluo -> valor_reposicion_terreno = $datos_avaluo_valor_reposicion;
        $actainmuebleavaluo -> valor_actual_terreno = $datos_avaluo_valor_actual;
        $actainmuebleavaluo -> valor_realizacion_terreno = $datos_avaluo_realizacion;
        $actainmuebleavaluo -> valor_reposicion_construccion = $datos_avaluo_reposicion_construccion;
        $actainmuebleavaluo -> valor_actual_construccion = $datos_avaluo_actual_construccion;
        $actainmuebleavaluo -> valor_realizacion_construccion = $datos_avaluo_realizacion_construccion;
        $actainmuebleavaluo -> valor_total_reposicion = $datos_avaluo_total_reposicion;
        $actainmuebleavaluo -> valor_total_actual = $datos_avaluo_total_actual;
        $actainmuebleavaluo -> valor_total_realizacion = $datos_avaluo_total_realizacion;
        $actainmuebleavaluo->user_id = $user_id;
        $actainmuebleavaluo->acta_inmueble_id = $actainmueble -> id;
        $actainmuebleavaluo->save();

        

        $acta_inmueble_entorno = [
            'acta_inmueble_id' => $actainmueble -> id,
            'propiedades' => [    
                'detalle' => $acta_inmueble_entorno_detalle,
                'entorno' => $acta_inmueble_entorno_listado,
                'servicio' => $acta_inmueble_entorno_servicio,            
                'impacto_ambiental' => $acta_inmueble_entorno_impacto_ambiental,
                'equipamiento_zona' => $acta_inmueble_entorno_equipamiento_zona,
                'descripcion_zona' => $acta_inmueble_entorno_descripcion_zona,
                'observacion_ocupacion' => $acta_inmueble_entorno_observacion_ocupacion,
            ],
            'user_id' => $user_id,
        ];
  
        $acta_inmueble_entorno_retorno = ActaInmuebleEntorno::create($acta_inmueble_entorno);

    
        $acta_inmueble_terreno = [
            'acta_inmueble_id' => $actainmueble -> id,
            'propiedades' => [    
                'localizacion' => $acta_inmueble_terreno_localizacion,
                'caracteristicas_fisicas' => $acta_inmueble_terreno_caracteristicas_fisicas,
                'cerramiento' => $acta_inmueble_terreno_cerramiento,            
                'linderos' => $acta_inmueble_terreno_linderos,
                'area_terreno' => $acta_inmueble_terreno_area_terreno,
            ],
            'user_id' => $user_id,
        ];
  
        $acta_inmueble_terreno_retorno = ActaInmuebleTerreno::create($acta_inmueble_terreno);

        
        $acta_inmueble_edificacion = [
            'acta_inmueble_id' => $actainmueble -> id,
            'propiedades' => [    
                'caracteristicas' => $acta_inmueble_edificacion_caracteristicas,
                'areas_edificacion' => $acta_inmueble_edificacion_areas_edificacion,
                'areas_edificacion_total' => $acta_inmueble_edificacion_areas_edificacion_total,
                'areas_edificacion_otros' => $acta_inmueble_edificacion_areas_edificacion_otros,
                'areas_edificacion_otros_total' => $acta_inmueble_edificacion_areas_edificacion_otros_total,
                'resumen_infraestructura' => $acta_inmueble_edificacion_resumen_infraestructura,            
                'conservacion_mantenimiento' => $acta_inmueble_edificacion_conservacion_mantenimiento,
                'descripcion_funcional' => $acta_inmueble_edificacion_descripcion_funcional,
            ],
            'user_id' => $user_id,
        ];
  
        $acta_inmueble_edificacion_retorno = ActaInmuebleEdificacion::create($acta_inmueble_edificacion);
    
        $acta_inmueble_criterio_valoracion = [
            'acta_inmueble_id' => $actainmueble -> id,
            'propiedades' => [    
                'criterios_valoracion' => $acta_inmueble_criterios_valoracion,
                'criterios_valoracion_calificacion' => $acta_inmueble_criterios_valoracion_calificacion,
                'valoracion_terreno' => $acta_inmueble_valoracion_terreno,
                'valoracion_terreno_detalle' => $acta_inmueble_valoracion_terreno_detalle,
                'valoracion_terreno_total' => $acta_inmueble_valoracion_terreno_total,
                'valoracion_construcciones' => $acta_inmueble_valoracion_construcciones,
            ],
            'user_id' => $user_id,
        ];
  
        $acta_inmueble_criterio_valoracion_retorno = ActaInmuebleCriterioValoracion::create($acta_inmueble_criterio_valoracion);

        $acta_inmueble_resumen_valoracion = [
            'acta_inmueble_id' => $actainmueble -> id,
            'propiedades' => [    
                'resumen_valoracion' => $acta_inmueble_resumen_valoracion,
                'resumen_valoracion_reposicion' => $acta_inmueble_resumen_valoracion_reposicion,
                'resumen_valoracion_mercado' => $acta_inmueble_resumen_valoracion_mercado,
                'resumen_valoracion_realizacion' => $acta_inmueble_resumen_valoracion_realizacion,
            ],
            'user_id' => $user_id,
        ];

        $acta_inmueble_resumen_valoracion_retorno = ActaInmuebleResumenValoracion::create($acta_inmueble_resumen_valoracion);

        return [
            'inmueble_id' => $actainmueble ->id
        ];

    }

    // Busqueda de acta inmueble por id
    public function buscaractainmueble($id){

        $acta_inmueble = DB::table('acta_inmueble')
        ->join('acta_estado', 'acta_estado.id', '=', 'acta_inmueble.acta_estado_id')
        ->join('user', 'user.id', '=', 'acta_inmueble.user_id')
        ->where("acta_inmueble.id", "=", $id)
        ->select(
            'acta_inmueble.id as id',
            'acta_inmueble.nombre as nombre',
            'acta_inmueble.numero_interno as numero_interno',
            'acta_inmueble.institucion as institucion',
            'acta_inmueble.finalidad_avaluo as finalidad_avaluo',
            'acta_inmueble.agencia_oficina as agencia_oficina',
            'acta_inmueble.nombre_cliente as nombre_cliente',
            'acta_inmueble.direccion as direccion',
            'acta_inmueble.fecha_inspeccion as fecha_inspeccion',
            'acta_inmueble.tipo_bien_descripcion as tipo_bien_descripcion',
            'acta_inmueble.tipo_bien_descripcion_detalle as tipo_bien_descripcion_detalle',
            'acta_inmueble.ubicacion as ubicacion',
            'acta_inmueble.acta_provincia_id as ubicacion_provincia_id',
            'acta_inmueble.acta_canton_id as ubicacion_canton_id',
            'acta_inmueble.acta_ciudad_id as ubicacion_ciudad_id',
            'acta_inmueble.acta_parroquia_id as ubicacion_parroquia_id',
            'acta_inmueble.ubicacion_barrio as ubicacion_barrio',
            'acta_inmueble.ubicacion_manzana as ubicacion_manzana',
            'acta_inmueble.ubicacion_lote as ubicacion_lote',
            'acta_inmueble.ubicacion_latitud as ubicacion_latitud',
            'acta_inmueble.ubicacion_longitud as ubicacion_longitud',
            'acta_inmueble.ubicacion_predio as ubicacion_predio',
            'user.nombres as usuario_nombre',
            'user.apellidos as usuario_apellido',
            'acta_estado.nombre as estado_nombre',
            'acta_inmueble.fcrea as fecha_creacion',
            'acta_inmueble.acta_estado_id as acta_estado_id',
            )->get()->first();

        $acta_inmueble_municipio = DB::table('acta_inmueble_municipio')
            ->where("acta_inmueble_municipio.acta_inmueble_id", "=", $id)
            ->select(
                'acta_inmueble_municipio.id as id',
                'acta_inmueble_municipio.detalle as detalle',
                'acta_inmueble_municipio.ano_impuesto_predial as ano_impuesto_predial',
                'acta_inmueble_municipio.clave_catastral as clave_catastral',
                'acta_inmueble_municipio.geo_clave as geo_clave',
                'acta_inmueble_municipio.ano_1_numero as ano_1_numero',
                'acta_inmueble_municipio.ano_1_valor as ano_1_valor',
                'acta_inmueble_municipio.ano_1_construccion as ano_1_construccion',
                'acta_inmueble_municipio.ano_1_terreno as ano_1_terreno',
                'acta_inmueble_municipio.ano_2_numero as ano_2_numero',
                'acta_inmueble_municipio.ano_2_valor as ano_2_valor',
                'acta_inmueble_municipio.ano_2_construccion as ano_2_construccion',
                'acta_inmueble_municipio.ano_2_terreno as ano_2_terreno',
                )->get()->first();
        
            $acta_inmueble_escritura = DB::table('acta_inmueble_escritura')
                ->where("acta_inmueble_escritura.acta_inmueble_id", "=", $id)
                ->join('acta_canton', 'acta_canton.id', '=', 'acta_inmueble_escritura.acta_canton_id')
                ->select(
                    'acta_inmueble_escritura.id as id',
                    'acta_inmueble_escritura.detalle as detalle',
                    'acta_inmueble_escritura.notaria as notaria',
                    'acta_canton.id as acta_canton_id',
                    'acta_inmueble_escritura.fecha_escrituracion_registro as fecha_escrituracion_registro',
                    'acta_inmueble_escritura.superficie as superficie',
                    'acta_inmueble_escritura.cuantia as cuantia'
                    )->get()->first();

                $acta_inmueble_avaluo = DB::table('acta_inmueble_avaluo')
                    ->where("acta_inmueble_avaluo.acta_inmueble_id", "=", $id)
                    ->select(
                        'acta_inmueble_avaluo.id as id',
                        'acta_inmueble_avaluo.valor_reposicion_terreno as valor_reposicion_terreno',
                        'acta_inmueble_avaluo.valor_actual_terreno as valor_actual_terreno',
                        'acta_inmueble_avaluo.valor_realizacion_terreno as valor_realizacion_terreno',
                        'acta_inmueble_avaluo.valor_reposicion_construccion as valor_reposicion_construccion',
                        'acta_inmueble_avaluo.valor_actual_construccion as valor_actual_construccion',
                        'acta_inmueble_avaluo.valor_realizacion_construccion as valor_realizacion_construccion',
                        'acta_inmueble_avaluo.valor_total_reposicion as valor_total_reposicion',
                        'acta_inmueble_avaluo.valor_total_actual as valor_total_actual',
                        'acta_inmueble_avaluo.valor_total_realizacion as valor_total_realizacion'
                        )->get()->first();
                
                $acta_inmueble_entorno = DB::table('acta_inmueble_entorno')
                        ->where("acta_inmueble_entorno.acta_inmueble_id", "=", $id)
                        ->select(
                            'acta_inmueble_entorno.id as id',
                            'acta_inmueble_entorno.propiedades as propiedades'
                            )->get()->first(); 
                            
                $acta_inmueble_terreno = DB::table('acta_inmueble_terreno')
                        ->where("acta_inmueble_terreno.acta_inmueble_id", "=", $id)
                        ->select(
                            'acta_inmueble_terreno.id as id',
                            'acta_inmueble_terreno.propiedades as propiedades'
                            )->get()->first();
                $acta_inmueble_edificacion = DB::table('acta_inmueble_edificacion')
                        ->where("acta_inmueble_edificacion.acta_inmueble_id", "=", $id)
                        ->select(
                            'acta_inmueble_edificacion.id as id',
                            'acta_inmueble_edificacion.propiedades as propiedades'
                            )->get()->first();
                $acta_inmueble_criterio_valoracion = DB::table('acta_inmueble_criterio_valoracion')
                        ->where("acta_inmueble_criterio_valoracion.acta_inmueble_id", "=", $id)
                        ->select(
                            'acta_inmueble_criterio_valoracion.id as id',
                            'acta_inmueble_criterio_valoracion.propiedades as propiedades'
                        )->get()->first(); 
                $acta_inmueble_resumen_valoracion = DB::table('acta_inmueble_resumen_valoracion')
                        ->where("acta_inmueble_resumen_valoracion.acta_inmueble_id", "=", $id)
                        ->select(
                            'acta_inmueble_resumen_valoracion.id as id',
                            'acta_inmueble_resumen_valoracion.propiedades as propiedades'
                        )->get()->first();            

                $acta_inmueble_flujos = DB::table('acta_inmueble_flujo')
                    ->join('user', 'user.id', '=', 'acta_inmueble_flujo.user_id')
                    ->where("acta_inmueble_flujo.acta_inmueble_id", "=", $id)
                    ->orderByRaw('acta_inmueble_flujo.id ASC')
                    ->select(
                        'acta_inmueble_flujo.id as id',
                        'acta_inmueble_flujo.detalle as detalle',
                        'user.nombres as nombres_usuario',
                        'user.apellidos as apellidos_usuario',
                        'acta_inmueble_flujo.fcrea as fecha_creacion',
                    )->get();             

        return [
            'acta_inmueble' => $acta_inmueble,
            'acta_inmueble_municipio' => $acta_inmueble_municipio,
            'acta_inmueble_escritura' => $acta_inmueble_escritura,
            'acta_inmueble_avaluo' => $acta_inmueble_avaluo,
            'acta_inmueble_entorno' => $acta_inmueble_entorno,
            'acta_inmueble_terreno' => $acta_inmueble_terreno,
            'acta_inmueble_edificacion' => $acta_inmueble_edificacion,
            'acta_inmueble_criterio_valoracion' => $acta_inmueble_criterio_valoracion,
            'acta_inmueble_resumen_valoracion' => $acta_inmueble_resumen_valoracion,
            'acta_inmueble_flujos' => $acta_inmueble_flujos,

        ];
    }
        
    //editar acta inmueble
    public function editaractainmueble(Request $request){

        $cambio_estado = 0;

        $inmueble = ActaInmueble::find($request->id);
        $inmueble->nombre=$request->nombre;
        $inmueble->institucion=$request->inmueble_institucion;
        $inmueble->finalidad_avaluo=$request->inmueble_finalidad_avaluo;
        $inmueble->agencia_oficina=$request->inmueble_agencia_oficina;
        $inmueble->nombre_cliente=$request->inmueble_nombre_cliente;
        $inmueble->direccion=$request->inmueble_direccion;
        $inmueble->fecha_inspeccion=$request->inmueble_fecha_inspeccion;
        $inmueble->tipo_bien_descripcion=$request->inmueble_tipo_bien_descripcion;
        $inmueble->tipo_bien_descripcion_detalle=$request->inmueble_tipo_bien_descripcion_detalle;
        $inmueble->ubicacion=$request->inmueble_ubicacion;
        $inmueble->acta_provincia_id=$request->inmueble_provincia;
        $inmueble->acta_canton_id=$request->inmueble_canton;
        $inmueble->acta_parroquia_id=$request->inmueble_parroquia;
        $inmueble->acta_ciudad_id=$request->inmueble_ciudad;
        $inmueble->ubicacion_barrio=$request->inmueble_barrio_urbanizacion;
        $inmueble->ubicacion_manzana=$request->inmueble_manzana;
        $inmueble->ubicacion_lote=$request->inmueble_lote;
        $inmueble->ubicacion_latitud=$request->inmueble_latitud;
        $inmueble->ubicacion_longitud=$request->inmueble_longitud;

        if($inmueble->acta_estado_id != $request->inmueble_estado) {
            $cambio_estado = 1;
            $inmueble->acta_estado_id=$request->inmueble_estado;
        }        
        
        $inmueble->ubicacion_predio=$request->inmueble_predio;
        $inmueble->save();

        $actainmuebleflujo = new ActaInmuebleFlujo();
        $estado = ActaEstado::find($request->inmueble_estado);

        if($cambio_estado == 1) {
            $actainmuebleflujo-> detalle = "Estado actual ".$estado->nombre;
            $actainmuebleflujo-> acta_inmueble_id = $request->id;
            $actainmuebleflujo-> user_id = $request->user_id;
            $actainmuebleflujo->save();
        }

        $actainmueblemunicipio = ActaInmuebleMunicipio::where('acta_inmueble_id',"=",$request->id)->first();
        $actainmueblemunicipio-> detalle = $request->inmueble_datos_municipales_detalle;
        $actainmueblemunicipio-> ano_impuesto_predial = $request->inmueble_datos_municipales_ano_impuesto_predial;
        $actainmueblemunicipio-> clave_catastral = $request->inmueble_datos_municipales_clave_catastral;
        $actainmueblemunicipio-> geo_clave = $request->inmueble_datos_municipales_geo_clave;
        $actainmueblemunicipio-> ano_1_numero = $request->inmueble_municipio_ano_1_numero;
        $actainmueblemunicipio-> ano_1_valor = $request->inmueble_municipio_ano_1_valor;
        $actainmueblemunicipio-> ano_1_construccion = $request->inmueble_municipio_ano_1_construccion;
        $actainmueblemunicipio-> ano_1_terreno = $request->inmueble_municipio_ano_1_terreno;
        $actainmueblemunicipio-> ano_2_numero = $request->inmueble_municipio_ano_2_numero;
        $actainmueblemunicipio-> ano_2_valor = $request->inmueble_municipio_ano_2_valor;
        $actainmueblemunicipio-> ano_2_construccion = $request->inmueble_municipio_ano_2_construccion;
        $actainmueblemunicipio-> ano_2_terreno = $request->inmueble_municipio_ano_2_terreno;
        $actainmueblemunicipio->save();

        $actainmuebleescritura = ActaInmuebleEscritura::where('acta_inmueble_id',"=",$request->id)->first();
        $actainmuebleescritura-> detalle = $request->inmueble_escritura_detalle;
        $actainmuebleescritura-> notaria = $request->inmueble_escritura_notaria;
        $actainmuebleescritura-> acta_canton_id = $request->inmueble_escritura_canton;
        $actainmuebleescritura-> fecha_escrituracion_registro = $request->inmueble_escritura_fecha;
        $actainmuebleescritura-> superficie = $request->inmueble_escritura_superficie;
        $actainmuebleescritura-> cuantia = $request->inmueble_escritura_cuantia;
        $actainmuebleescritura->save();

        $actainmuebleavaluo = ActaInmuebleAvaluo::where('acta_inmueble_id',"=",$request->id)->first();
        $actainmuebleavaluo -> detalle = '';
        $actainmuebleavaluo -> valor_reposicion_terreno = $request->inmueble_avaluo_valor_reposicion_terreno;
        $actainmuebleavaluo -> valor_actual_terreno = $request->inmueble_avaluo_valor_actual_terreno;
        $actainmuebleavaluo -> valor_realizacion_terreno = $request->inmueble_avaluo_valor_realizacion_terreno;
        $actainmuebleavaluo -> valor_reposicion_construccion = $request->inmueble_avaluo_valor_reposicion_construccion;
        $actainmuebleavaluo -> valor_actual_construccion = $request->inmueble_avaluo_valor_actual_construccion;
        $actainmuebleavaluo -> valor_realizacion_construccion = $request->inmueble_avaluo_valor_realizacion_construccion;
        $actainmuebleavaluo -> valor_total_reposicion = $request->inmueble_avaluo_valor_total_reposicion;
        $actainmuebleavaluo -> valor_total_actual = $request->inmueble_avaluo_valor_total_actual;
        $actainmuebleavaluo -> valor_total_realizacion = $request->inmueble_avaluo_valor_total_realizacion;
        $actainmuebleavaluo->save();


        $actainmuebleentorno = ActaInmuebleEntorno ::where('acta_inmueble_id',"=",$request->id)->first();
        
        $acta_inmueble_entorno_propiedades = [   
            'detalle' => $request -> inmueble_entorno_detalle,
            'entorno' => $request -> inmueble_entorno_listado,
            'servicio' => $request -> inmueble_entorno_servicios,            
            'impacto_ambiental' => $request -> inmueble_entorno_impacto_ambiental,
            'equipamiento_zona' => $request -> inmueble_entorno_equipamiento,
            'descripcion_zona' => $request -> inmueble_entorno_descripcion_zona,
            'observacion_ocupacion' => $request -> inmueble_entorno_observaciones,
        ];

        $actainmuebleentorno -> propiedades = $acta_inmueble_entorno_propiedades; 
        $actainmuebleentorno -> save();
        
        $actainmuebleterreno = ActaInmuebleTerreno ::where('acta_inmueble_id',"=",$request->id)->first();
        
        $acta_inmueble_terreno_propiedades = [   
            'localizacion' => $request -> inmueble_terreno_localizacion,
            'caracteristicas_fisicas' => $request -> inmueble_terreno_caracteristicas_fisicas,
            'cerramiento' => $request -> inmueble_terreno_cerramiento,     
            'linderos' => $request -> inmueble_terreno_linderos_dimensiones,
            'area_terreno' => $request -> inmueble_terreno_linderos_dimensiones_area,
        ];

        $actainmuebleterreno -> propiedades = $acta_inmueble_terreno_propiedades; 
        $actainmuebleterreno -> save();

        $actainmuebleedificacion = ActaInmuebleEdificacion ::where('acta_inmueble_id',"=",$request->id)->first();
        
        $acta_inmueble_edificacion_propiedades = [   
            'caracteristicas' => $request -> inmueble_edificacion_caracteristicas,
            'areas_edificacion' => $request -> inmueble_edificacion_areas_edificacion,
            'areas_edificacion_total' => $request -> inmueble_edificacion_areas_edificacion_total,     
            'areas_edificacion_otros' => $request -> inmueble_edificacion_areas_edificacion_otros,
            'areas_edificacion_otros_total' => $request -> inmueble_edificacion_areas_edificacion_otros_total,
            'resumen_infraestructura' => $request -> inmueble_edificacion_resumen_infraestructura,
            'conservacion_mantenimiento' => $request -> inmueble_edificacion_conservacion_mantenimiento,
            'descripcion_funcional' => $request -> inmueble_edificacion_descripcion_funcional,     
        ];

        $actainmuebleedificacion -> propiedades = $acta_inmueble_edificacion_propiedades; 
        $actainmuebleedificacion -> save();

        $actainmueblecriteriovaloracion = ActaInmuebleCriterioValoracion ::where('acta_inmueble_id',"=",$request->id)->first();
        
        $acta_inmueble_criterios_valoracion_propiedades = [   
            'criterios_valoracion' => $request -> inmueble_criterio_valoracion_listado,
            'criterios_valoracion_calificacion' => $request -> inmueble_criterio_valoracion_calificacion_listado,
            'valoracion_terreno' => $request -> inmueble_criterio_valoracion_terreno_listado,     
            'valoracion_terreno_detalle' => $request -> inmueble_criterio_valoracion_terreno_detalle,
            'valoracion_terreno_total' => $request -> inmueble_criterio_valoracion_terreno_total,
            'valoracion_construcciones' => $request -> inmueble_criterio_valoracion_construcciones,
        ];

        $actainmueblecriteriovaloracion -> propiedades = $acta_inmueble_criterios_valoracion_propiedades; 
        $actainmueblecriteriovaloracion -> save();


        $actainmuebleresumenvaloracion = ActaInmuebleResumenValoracion ::where('acta_inmueble_id',"=",$request->id)->first();
        
        $acta_inmueble_resumen_valoracion_propiedades = [   
            'resumen_valoracion' => $request -> inmueble_resumen_valoracion_tabla,
            'resumen_valoracion_reposicion' => $request -> inmueble_resumen_valoracion_reposicion,
            'resumen_valoracion_mercado' => $request -> inmueble_resumen_valoracion_mercado,  
            'resumen_valoracion_realizacion' => $request -> inmueble_resumen_valoracion_realizacion,
        ];

        $actainmuebleresumenvaloracion -> propiedades = $acta_inmueble_resumen_valoracion_propiedades; 
        $actainmuebleresumenvaloracion -> save();

    }

    // Busqueda de imagenes por medio del acta inmueble id
    public function buscaractainmuebleimagenes($id){

        $recupera = DB::table('acta_inmueble_imagen')
            ->where("acta_inmueble_imagen.acta_inmueble_id", "=", $id)
            ->join('acta_inmueble_imagen_tipo', 'acta_inmueble_imagen_tipo.id', '=', 'acta_inmueble_imagen.acta_inmueble_imagen_tipo_id')
            ->select(
                'acta_inmueble_imagen.id as id',
                'acta_inmueble_imagen.titulo as titulo',
                'acta_inmueble_imagen_tipo.nombre as orden',
                'acta_inmueble_imagen.acta_inmueble_imagen_tipo_id as acta_inmueble_imagen_tipo_id',
                'acta_inmueble_imagen.nombre as nombre',
                'acta_inmueble_imagen.archivo as archivo',
                'acta_inmueble_imagen.fcrea as fcrea'
                )->orderByRaw('acta_inmueble_imagen.acta_inmueble_imagen_tipo_id ASC')->get();
                
        return [
            'recupera' => $recupera
        ];
    }

    // Agregar imagen por inmueble 
    public function agregaractainmuebleimagen(Request $request){
        
        $titulo = $request["inmueble_imagen_titulo"];
        $orden = $request["inmueble_imagen_orden"];
        $archivo = $request["inmueble_imagen_archivo"];
        $acta_inmueble_id = $request["acta_inmueble_id"];
        
        $user_id = $request["user_id"];
        $empresa_id = $request["empresa_id"];

        $data = (object) array("encoded_archive" => "", "type_archive" => "", "name_archive" => "");

        $data_archive = fopen($archivo, 'rb');
        $size_archive = filesize($archivo);
        $contents = fread($data_archive, $size_archive);
        fclose($data_archive);
    
        $imagen = new ActaInmuebleImagen();
        $imagen->titulo = $titulo;
        $imagen->nombre = $archivo->getClientOriginalName();
        $imagen->acta_inmueble_imagen_tipo_id = $orden;
        $imagen->archivo = base64_encode($contents);
        $imagen->user_id = $user_id;
        $imagen->empresa_id = $empresa_id;
        $imagen->acta_inmueble_id = $acta_inmueble_id;
        $imagen->save();

        return [
            'recupera' => $imagen
        ];
    }   

    // eliminar imagen por inmueble   
    public function eliminaractainmuebleimagen($id){
        ActaInmuebleImagen::where("id","=",$id)->delete();
    }

    
    // Busqueda de imagen por id
    public function buscaractainmuebleimagen($id){

        $recupera = ActaInmuebleImagen::select("*")
        ->where("id", "=", $id)
        ->get()->first();

        return [
            'recupera' => $recupera
        ];
    }

    //editar acta inmueble imagen
    public function editaractainmuebleimagen(Request $request){

        $id = $request["inmueble_imagen_id_editar"];
        $titulo = $request["inmueble_imagen_titulo_editar"];
        $orden = $request["inmueble_imagen_orden_editar"];
        $archivo = $request["inmueble_imagen_archivo_editar"];

        $imagen = ActaInmuebleImagen::find($id);
        $imagen->titulo = $titulo;

        if($archivo != null) {
            $data = (object) array("encoded_archive" => "", "type_archive" => "", "name_archive" => "");
    
            $data_archive = fopen($archivo, 'rb');
            $size_archive = filesize($archivo);
            $contents = fread($data_archive, $size_archive);
            fclose($data_archive);
            $imagen->nombre = $archivo->getClientOriginalName();
            $imagen->archivo = base64_encode($contents);
        }

        $imagen->acta_inmueble_imagen_tipo_id = $orden;
        $imagen->save();
    }


    //Creación del reporte general del inmueble en pdf
    public function reporte_inmueble_pdf($acta_inmueble_id)
    {
        $acta_inmueble = DB::table('acta_inmueble')
        ->join('acta_estado', 'acta_estado.id', '=', 'acta_inmueble.acta_estado_id')
        ->join('user', 'user.id', '=', 'acta_inmueble.user_id')
        ->where("acta_inmueble.id", "=", $acta_inmueble_id)
        ->select(DB::raw(
            'acta_inmueble.id as id, acta_inmueble.nombre as nombre, acta_inmueble.numero_interno as numero_interno , acta_inmueble.institucion as institucion ,
             acta_inmueble.finalidad_avaluo as finalidad_avaluo, acta_inmueble.agencia_oficina as agencia_oficina , acta_inmueble.nombre_cliente as nombre_cliente ,
             acta_inmueble.direccion as direccion, acta_inmueble.fecha_inspeccion as fecha_inspeccion, acta_inmueble.tipo_bien_descripcion as tipo_bien_descripcion ,
             acta_inmueble.tipo_bien_descripcion_detalle as tipo_bien_descripcion_detalle, acta_inmueble.ubicacion as ubicacion,
             (SELECT nombre FROM acta_provincia WHERE id = acta_inmueble.acta_provincia_id) as ubicacion_provincia,
             (SELECT nombre FROM acta_canton WHERE id = acta_inmueble.acta_canton_id) as ubicacion_canton,
             (SELECT nombre FROM acta_ciudad WHERE id = acta_inmueble.acta_ciudad_id) as ubicacion_ciudad,
             (SELECT nombre FROM acta_parroquia WHERE id = acta_inmueble.acta_parroquia_id) as ubicacion_parroquia,
             acta_inmueble.ubicacion_barrio as ubicacion_barrio,
             acta_inmueble.ubicacion_manzana as ubicacion_manzana,
             acta_inmueble.ubicacion_lote as ubicacion_lote,
             acta_inmueble.ubicacion_latitud as ubicacion_latitud,
             acta_inmueble.ubicacion_longitud as ubicacion_longitud,
             user.nombres as usuario_nombre,
             user.apellidos as usuario_apellido,
             acta_estado.nombre as estado_nombre,
             acta_inmueble.fcrea as fecha_creacion'
            ))->get()->first();

        $acta_inmueble_municipio = DB::table('acta_inmueble_municipio')
            ->where("acta_inmueble_municipio.acta_inmueble_id", "=", $acta_inmueble_id)
            ->select(
                'acta_inmueble_municipio.id as id',
                'acta_inmueble_municipio.detalle as detalle',
                'acta_inmueble_municipio.ano_impuesto_predial as ano_impuesto_predial',
                'acta_inmueble_municipio.clave_catastral as clave_catastral',
                'acta_inmueble_municipio.ano_1_numero as ano_1_numero',
                'acta_inmueble_municipio.ano_1_valor as ano_1_valor',
                'acta_inmueble_municipio.ano_1_construccion as ano_1_construccion',
                'acta_inmueble_municipio.ano_1_terreno as ano_1_terreno',
                'acta_inmueble_municipio.ano_2_numero as ano_2_numero',
                'acta_inmueble_municipio.ano_2_valor as ano_2_valor',
                'acta_inmueble_municipio.ano_2_construccion as ano_2_construccion',
                'acta_inmueble_municipio.ano_2_terreno as ano_2_terreno',
                )->get()->first();

        $acta_inmueble_escritura = DB::table('acta_inmueble_escritura')
            ->where("acta_inmueble_escritura.acta_inmueble_id", "=", $acta_inmueble_id)
            ->join('acta_canton', 'acta_canton.id', '=', 'acta_inmueble_escritura.acta_canton_id')
            ->select(
                'acta_inmueble_escritura.id as id',
                'acta_inmueble_escritura.detalle as detalle',
                'acta_inmueble_escritura.notaria as notaria',
                'acta_canton.nombre as acta_canton',
                'acta_inmueble_escritura.fecha_escrituracion_registro as fecha_escrituracion_registro',
                'acta_inmueble_escritura.superficie as superficie',
                'acta_inmueble_escritura.cuantia as cuantia'
                )->get()->first();


            $acta_inmueble_avaluo = DB::table('acta_inmueble_avaluo')
                ->where("acta_inmueble_avaluo.acta_inmueble_id", "=", $acta_inmueble_id)
                ->select(
                    'acta_inmueble_avaluo.id as id',
                    'acta_inmueble_avaluo.valor_reposicion_terreno as valor_reposicion_terreno',
                    'acta_inmueble_avaluo.valor_actual_terreno as valor_actual_terreno',
                    'acta_inmueble_avaluo.valor_realizacion_terreno as valor_realizacion_terreno',
                    'acta_inmueble_avaluo.valor_reposicion_construccion as valor_reposicion_construccion',
                    'acta_inmueble_avaluo.valor_actual_construccion as valor_actual_construccion',
                    'acta_inmueble_avaluo.valor_realizacion_construccion as valor_realizacion_construccion',
                    'acta_inmueble_avaluo.valor_total_reposicion as valor_total_reposicion',
                    'acta_inmueble_avaluo.valor_total_actual as valor_total_actual',
                    'acta_inmueble_avaluo.valor_total_realizacion as valor_total_realizacion'
                    )->get()->first();      


            $inmueble_imagenes = DB::table('acta_inmueble_imagen')
                    ->where("acta_inmueble_imagen.acta_inmueble_id", "=", $acta_inmueble_id)
                    ->join('acta_inmueble_imagen_tipo', 'acta_inmueble_imagen_tipo.id', '=', 'acta_inmueble_imagen.acta_inmueble_imagen_tipo_id')
                    ->select(
                        'acta_inmueble_imagen.id as id',
                        'acta_inmueble_imagen.titulo as titulo',
                        'acta_inmueble_imagen_tipo.id as orden',
                        'acta_inmueble_imagen.nombre as nombre',
                        'acta_inmueble_imagen.archivo as archivo'
                        )->get();

                    
            $acta_inmueble_entorno = ActaInmuebleEntorno::select("*")
                    ->where("acta_inmueble_id", "=", $acta_inmueble_id)
                    ->get()->first();

            $acta_inmueble_terreno = ActaInmuebleTerreno::select("*")
                    ->where("acta_inmueble_id", "=", $acta_inmueble_id)
                    ->get()->first();

            $acta_inmueble_edificacion = ActaInmuebleEdificacion::select("*")
                    ->where("acta_inmueble_id", "=", $acta_inmueble_id)
                    ->get()->first();
            
            $acta_inmueble_criterio_valoracion = ActaInmuebleCriterioValoracion::select("*")
                    ->where("acta_inmueble_id", "=", $acta_inmueble_id)
                    ->get()->first();

            $acta_inmueble_resumen_valoracion = ActaInmuebleResumenValoracion::select("*")
                    ->where("acta_inmueble_id", "=", $acta_inmueble_id)
                    ->get()->first();


        $pdf = \PDF::loadView('pdf/reporte_acta_inmueble', compact("acta_inmueble", "acta_inmueble_municipio", "acta_inmueble_escritura", "acta_inmueble_avaluo","inmueble_imagenes","acta_inmueble_entorno","acta_inmueble_terreno","acta_inmueble_edificacion","acta_inmueble_criterio_valoracion","acta_inmueble_resumen_valoracion"));
                
        return $pdf->setPaper('a4', 'portrait')->setWarnings(false)->stream("Reporte_Inmueble_$acta_inmueble->id.pdf");   
    }

    //listar actaimagenestipos
    public function buscaractaimagenestipos(Request $request){

        $recupera = ActaInmuebleImagenTipo::select("*")
        ->where("activo", "=", 1)
        ->orderByRaw('orden ASC')
        ->get();
        
        return [
            'recupera' => $recupera
        ];
    }

}
