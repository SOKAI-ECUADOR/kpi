<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Depreciacion;
use App\Models\DepreciacionDetalle;
use App\Models\ActivoFijo;
use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

include 'class/generarReportes.php';
use generarReportes;

class DepreciacionController extends Controller
{
    //
    public function index(Request $request,$id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            // $recupera = Importacion::select('importacion.*')
            // //->join('factur','producto.id_producto','=','importacion.id_producto')
            // ->where('id_punto_emision',$id)
            // ->where('cod_importacion',like)
            // ->orderByRaw('importacion.id_importacion ASC')->get();
            $recupera =DB::select("SELECT * from depreciacion where id_empresa={$id}");
        }else{
            // $recupera = Importacion::select('activos_fijos.*')
            // //->join('producto','producto.id_producto','=','activos_fijos.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_activos_fijos',$id)
            // ->orderByRaw('activos_fijos.id_activos_fijos ASC')->get();
            $recupera =DB::select("SELECT * from depreciacion where id_empresa={$id}");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function getActivoTodos(Request $request,$id){
        $recupera =DB::select("SELECT activos_fijos.id_activos_fijos,activos_fijos.numero_meses_depreciacion,activos_fijos.valor_depreciacion,activos_fijos.nombre as nombre_activo_fijo,activo_fijo_grupo.nombre as grupo_activo,activo_fijo_tipo.nombre as tipo_activo,if(activos_fijos.id_factura_compra is null,activos_fijos.fecha_factura,factura_compra.fech_emision) as fecha_activo,
        LAST_DAY(activos_fijos.fecha_factura) as ultimo_dia,TIMESTAMPDIFF(DAY, activos_fijos.fecha_factura, LAST_DAY(activos_fijos.fecha_factura)) AS dias_transcurridos
        from activos_fijos 
        INNER JOIN activo_fijo_grupo ON activo_fijo_grupo.id_activo_fijo_grupo=activos_fijos.id_grupo_activo_fijo 
        INNER JOIN activo_fijo_tipo ON activo_fijo_tipo.id_activo_fijo_tipo=activos_fijos.id_tipo_activo_fijo  
        LEFT JOIN factura_compra ON activos_fijos.id_factura_compra=factura_compra.id_factcompra
        where  activos_fijos.id_empresa={$id} and activos_fijos.valor_actual>0");
        return [
            'recupera' => $recupera
        ];
    }
    public function getActivoEspecial(Request $request,$id){
        $array=[];
        if($request->tipo){
            array_push($array,"activos_fijos.id_tipo_activo_fijo={$request->tipo}");
        }
        if($request->grupo){
            array_push($array,"activos_fijos.id_grupo_activo_fijo={$request->grupo}");
        }
        $adicional=implode(" and ",$array);
        $recupera =DB::select("SELECT activos_fijos.id_activos_fijos,activos_fijos.numero_meses_depreciacion,activos_fijos.valor_depreciacion,activos_fijos.nombre as nombre_activo_fijo,activo_fijo_grupo.nombre as grupo_activo,activo_fijo_tipo.nombre as tipo_activo,if(activos_fijos.id_factura_compra is null,activos_fijos.fecha_factura,factura_compra.fech_emision) as fecha_activo,
        LAST_DAY(activos_fijos.fecha_factura) as ultimo_dia,TIMESTAMPDIFF(DAY, activos_fijos.fecha_factura, LAST_DAY(activos_fijos.fecha_factura)) AS dias_transcurridos
        from activos_fijos 
        INNER JOIN activo_fijo_grupo ON activo_fijo_grupo.id_activo_fijo_grupo=activos_fijos.id_grupo_activo_fijo 
        INNER JOIN activo_fijo_tipo ON activo_fijo_tipo.id_activo_fijo_tipo=activos_fijos.id_tipo_activo_fijo 
        LEFT JOIN factura_compra ON activos_fijos.id_factura_compra=factura_compra.id_factcompra 
        where {$adicional} and activos_fijos.valor_actual>0 and activos_fijos.id_empresa={$id}");
        return [
            'recupera' => $recupera
        ];
    }
    public function getActivoIndividual(Request $request){
        $activo =DB::select("SELECT activos_fijos.id_activos_fijos,activos_fijos.numero_meses_depreciacion,activos_fijos.valor_depreciacion,activos_fijos.nombre as nombre_activo_fijo,activo_fijo_grupo.nombre as grupo_activo,activo_fijo_tipo.nombre as tipo_activo,if(activos_fijos.id_factura_compra is null,concat('Nombre Activo: ',activos_fijos.nombre,' Factura: ',SUBSTR(activos_fijos.nro_factura,1,3),'-',SUBSTR(activos_fijos.nro_factura,4,3),'-',SUBSTR(activos_fijos.nro_factura,7,9)),concat('Nombre Activo: ',activos_fijos.nombre,' Factura: ',SUBSTR(factura_compra.descripcion,1,3),'-',SUBSTR(factura_compra.descripcion,4,3),'-',SUBSTR(factura_compra.descripcion,7,9))) as nombre_activo,if(activos_fijos.id_factura_compra is null,activos_fijos.fecha_factura,factura_compra.fech_emision) as fecha_activo,
        LAST_DAY(activos_fijos.fecha_factura) as ultimo_dia,TIMESTAMPDIFF(DAY, activos_fijos.fecha_factura, LAST_DAY(activos_fijos.fecha_factura)) AS dias_transcurridos
        from activos_fijos 
        INNER JOIN activo_fijo_grupo ON activo_fijo_grupo.id_activo_fijo_grupo=activos_fijos.id_grupo_activo_fijo 
        INNER JOIN activo_fijo_tipo ON activo_fijo_tipo.id_activo_fijo_tipo=activos_fijos.id_tipo_activo_fijo 
        LEFT JOIN factura_compra ON activos_fijos.id_factura_compra=factura_compra.id_factcompra
        where activos_fijos.id_empresa={$request->id_empresa} and activos_fijos.valor_actual>0  and (activos_fijos.nro_factura like '%{$request->factura}%' or activos_fijos.nombre like '%{$request->factura}%' or factura_compra.descripcion like '%{$request->factura}%')");
        if($activo){
            return [
                'activo' => $activo
            ];
        }else{
            return 'error';
        }
    }
    public function abrirDepreciacion($id){
        $cabecera=DB::select("SELECT * from depreciacion where id_depreciacion={$id}"); 
        $detalle=DB::select("SELECT * from depreciacion_detalle where id_depreciacion={$id}");
        return [
            'cabecera' => $cabecera[0],
            'detalle'=> $detalle
        ];
    }
    public function cabecera(Request $request){
        $select=DB::select("SELECT max(codigo_depreciacion) as codigo_depreciacion from depreciacion where id_empresa={$request->id_empresa}");
        $codigo=1;
        if($select){
            $codigo=$select[0]->codigo_depreciacion+1;
        }else{
            $codigo=1;
        }
        $estado=0;
        if($request->todos_activos==true){
            $estado=1;
        }
        //return $codigo;
        $dep=new Depreciacion();
        $dep->codigo_depreciacion=$codigo;
        $dep->fecha_inicio=$request->fecha_inicio;
        $dep->fecha_fin=$request->fecha_fin;
        $dep->todos_activos=$estado;
        $dep->ucrea=$request->ucrea;
        $dep->id_grupo_activo_fijo=$request->id_grupo_activo_fijo;
        $dep->id_tipo_activo_fijo=$request->id_tipo_activo_fijo;
        $dep->id_empresa=$request->id_empresa;
        $dep->save();
        return $dep->id_depreciacion;
    }
    public function detalle(Request $request){
        $id=$request->id_depreciacion;
        for ($a = 0; $a < count($request->activos_fijos); $a++) {
            $dep=new DepreciacionDetalle();
            $dep->nombre_activo_fijo=$request->activos_fijos[$a]["nombre_activo_fijo"];
            $dep->fecha_activo=$request->activos_fijos[$a]["fecha_activo"];
            $dep->dias_transcurridos=$request->activos_fijos[$a]["dias_transcurridos"];
            $dep->grupo_activo=$request->activos_fijos[$a]["grupo_activo"];
            $dep->tipo_activo=$request->activos_fijos[$a]["tipo_activo"];
            if(!$request->activos_fijos[$a]["numero_meses_depreciacion"] || $request->activos_fijos[$a]["numero_meses_depreciacion"]==null){
                $dep->valor_depreciacion=($request->activos_fijos[$a]["valor_depreciacion"]/30)*30;
            }else{
                $dep->valor_depreciacion=($request->activos_fijos[$a]["valor_depreciacion"]/30)*$request->activos_fijos[$a]["dias_transcurridos"];
            }
            $dep->id_depreciacion=$id;
            $dep->id_activo_fijo=$request->activos_fijos[$a]["id_activos_fijos"];
            $dep->save();

            $af=ActivoFijo::find($request->activos_fijos[$a]["id_activos_fijos"]);
            if(!$request->activos_fijos[$a]["numero_meses_depreciacion"] || $request->activos_fijos[$a]["numero_meses_depreciacion"]==null){
                $af->depreciacion_acumulada=$af->depreciacion_acumulada+(($request->activos_fijos[$a]["valor_depreciacion"]/30)*30);
            }else{
                $af->depreciacion_acumulada=$af->depreciacion_acumulada+(($request->activos_fijos[$a]["valor_depreciacion"]/30)*$request->activos_fijos[$a]["dias_transcurridos"]);
            }
            
            $af->valor_actual=$af->valor_bien-$af->depreciacion_acumulada;
            $af->numero_meses_depreciacion=$af->numero_meses_depreciacion+1;
            $af->ultima_depreciacion=$request->fecha_fin;
            $af->save();
        }
    }
    public function eliminar ($id){
        Depreciacion::destroy($id);
    }
    public function verAsiento(Request $request,$id){
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'DP-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
        $cod_asiento="";
        if($codigo){
            $lenght=strlen($codigo[0]->codigo);
            $cod_asiento=$codigo[0]->codigo+1;
        }else{
            $cod_asiento="1";
        }
        $cod_asiento_ant="";
        if($codigo){
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=19 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $lenght=strlen($codigo[0]->codigo);
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }

        }
        $dep_cab=DB::select("SELECT depreciacion.*,empresa.nombre_empresa from depreciacion INNER JOIN empresa ON empresa.id_empresa=depreciacion.id_empresa where id_depreciacion={$id}");
        $proyecto=DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $dep_detalle_debe=DB::select("SELECT if(activos_fijos.id_plan_cuentas_debito is null,'no','si') as exist_cta_activos_fijos,if(activos_fijos.id_plan_cuentas_debito is null,activo_fijo_grupo.id_plan_cuenta_debito,activos_fijos.id_plan_cuentas_debito) as id_plan_cuenta,(select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(activos_fijos.id_plan_cuentas_debito is null,activo_fijo_grupo.id_plan_cuenta_debito,activos_fijos.id_plan_cuentas_debito)) as nombre_cuenta,proyecto.descripcion,proyecto.id_proyecto,depreciacion_detalle.valor_depreciacion as debe,null as haber  from depreciacion_detalle
        INNER JOIN depreciacion
        ON depreciacion.id_depreciacion=depreciacion_detalle.id_depreciacion
        INNER JOIN activos_fijos
        ON activos_fijos.id_activos_fijos=depreciacion_detalle.id_activo_fijo
        LEFT JOIN proyecto
        on proyecto.id_proyecto=activos_fijos.id_proyecto
        INNER JOIN activo_fijo_grupo
        ON activo_fijo_grupo.id_activo_fijo_grupo=activos_fijos.id_grupo_activo_fijo
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=activo_fijo_grupo.id_plan_cuenta_debito
        where depreciacion_detalle.id_depreciacion={$id}");
        $dep_detalle_haber=DB::select("SELECT if(activos_fijos.id_plan_cuentas_credito is null,'no','si') as exist_cta_activos_fijos,if(activos_fijos.id_plan_cuentas_credito is null,activo_fijo_grupo.id_plan_cuenta_credito,activos_fijos.id_plan_cuentas_credito) as id_plan_cuenta,(select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(activos_fijos.id_plan_cuentas_credito is null,activo_fijo_grupo.id_plan_cuenta_credito,activos_fijos.id_plan_cuentas_credito)) as nombre_cuenta,proyecto.descripcion,proyecto.id_proyecto,null as debe,depreciacion_detalle.valor_depreciacion as haber  from depreciacion_detalle
        INNER JOIN depreciacion
        ON depreciacion.id_depreciacion=depreciacion_detalle.id_depreciacion
        INNER JOIN activos_fijos
        ON activos_fijos.id_activos_fijos=depreciacion_detalle.id_activo_fijo
        LEFT JOIN proyecto
        on proyecto.id_proyecto=activos_fijos.id_proyecto
        INNER JOIN activo_fijo_grupo
        ON activo_fijo_grupo.id_activo_fijo_grupo=activos_fijos.id_grupo_activo_fijo
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=activo_fijo_grupo.id_plan_cuenta_credito
        where depreciacion_detalle.id_depreciacion={$id}");
        $dep_activos=DB::select("SELECT id_activo_fijo,id_depreciacion from depreciacion_detalle where depreciacion_detalle.id_depreciacion={$id}");
        return [
            'codigo'=>$cod_asiento,
            'codigo_anterior'=>$cod_asiento_ant,
            'cabecera'=>$dep_cab[0],
            'id_proyecto'=>$proyecto[0]->id_proyecto,
            'detalle_debe'=>$dep_detalle_debe,
            'detalle_haber'=>$dep_detalle_haber,
            'activos'=>$dep_activos
        ];
    }
    public function fecha_depreciacion($id){
        $recupera=DB::select("SELECT max(fecha_fin) as fecha_fin_mes, if(max(fecha_fin) is null,null,DATE_ADD(max(fecha_fin),INTERVAL 1 MONTH)) as max_fecha from depreciacion where id_empresa={$id}");
        return $recupera;
    }
    public function agregarAsiento(Request $request){
        Depreciacion::where('id_depreciacion',$request->cod_rol)->update(['contabilidad'=>'1']);
        $asientos=new Asientos();
        $asientos->automatico=0;
        $asientos->numero=$request->numero;
        $asientos->codigo=$request->codigo;
        $asientos->codigo_rol=$request->cod_rol;
        $asientos->fecha=$request->fecha;
        $asientos->razon_social=$request->razon_social;
        $asientos->tipo_identificacion=$request->tipo_identificacion;
        $asientos->ruc_ci=$request->ruc_ci;
        $asientos->concepto=$request->concepto;
        $asientos->ucrea=$request->ucrea;
        $asientos->id_proyecto=$request->id_proyecto;
        $asientos->id_asientos_comprobante=19;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request){
        foreach($request->activos as $activo){
            $av=ActivoFijo::find($activo["id_activo_fijo"]);
            $av->observaciones=1;
            $av->save();
        }
        foreach($request->detalle_debe as $debe){
            $asiento=new Asientos_contables_detalle();
            if($debe["debe"]>0){
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->debe=$debe["debe"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuenta"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                    $asiento->save();
            }
        }
        foreach($request->detalle_haber as $haber){
            $asiento=new Asientos_contables_detalle();
            if($haber["haber"]>0){
                    $asiento->proyecto=$haber["descripcion"];
                    $asiento->haber=$haber["haber"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$haber["id_plan_cuenta"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$haber["id_proyecto"];
                    $asiento->save();
            }
        }
    }
    public function generarPDF(Request $request){
        //dd($request);
        $cabecera=DB::select("SELECT * from depreciacion where id_depreciacion={$request->id_asientos}"); 
        $detalle=DB::select("SELECT * from depreciacion_detalle where id_depreciacion={$request->id_asientos}");
        $empresa=DB::select("SELECT * from empresa where id_empresa={$request->id_empresa}");
        $user=DB::select("SELECT CONCAT(nombres,' ',apellidos) as nombre from user where id=".$cabecera[0]->ucrea);
        if($request->destinatario==null && $request->email==null){
            $pdf=new generarReportes();
            $strPDF =$pdf->PDFDepreciacion($detalle,$empresa,$cabecera,$user[0]->nombre);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
          }else{
            $carpetanombre2 = constant("DATA_EMPRESA").$empresa[0]->id_empresa;
            $carpeta2 = $carpetanombre2."/depreciacion/".$select[0]->id_plan_cuentas."/".$select[0]->fecha_conciliacion;
            if (!file_exists($carpeta2)) {
                mkdir($carpeta2, 0755,true);
            }
            $pdf=new generarReportes();
            $strPDF =$pdf->PDFDepreciacion($select,$empresa,$select[0]->nomcta,$usuario[0]->nombre,$carpeta2);
          }
    }
}
