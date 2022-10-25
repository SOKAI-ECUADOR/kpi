<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

class CierreContableController extends Controller
{
    //
    public function index(Request $request){
        $response=DB::select("SELECT asientos.*,asientos_comprobante.codigo as codigo_comprobante,asientos_comprobante.tipo from asientos INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante=asientos.id_asientos_comprobante where id_empresa={$request->id_empresa} and cierre_contable='Estado Contable'");
        return [
            'recupera' => $response
        ];
    }
    public function getCierreEstado($id){
        $cabecera=DB::select("SELECT asientos.*,asientos_comprobante.codigo as codigo_comprobante,asientos_comprobante.tipo from asientos INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante=asientos.id_asientos_comprobante where id_asientos={$id}");
        $detalle=DB::select("SELECT asientos_detalle.*,concat(codcta,'-',nomcta) as nombre_cuenta from asientos_detalle INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas where id_asientos={$id}");
        return [
            'cabecera' => $cabecera,
            'detalle'=> $detalle
        ];
    }
    public function listarCierrePeriodo(Request $request){
        
        $año=substr($request->fecha,0,4);
        
        $proyecto=DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $diario=DB::select("SELECT max(numero) as numero from asientos where id_empresa={$request->id_empresa} and id_asientos_comprobante=3");
        $ingreso=DB::select("SELECT plan_cuentas.codcta,nombre_empresa from empresa inner join plan_cuentas on plan_cuentas.id_plan_cuentas=empresa.id_plan_cuentas_ingreso where plan_cuentas.id_empresa={$request->id_empresa}");
        $egreso=DB::select("SELECT plan_cuentas.codcta from empresa inner join plan_cuentas on plan_cuentas.id_plan_cuentas=empresa.id_plan_cuentas_costo where plan_cuentas.id_empresa={$request->id_empresa}");
        $gasto=DB::select("SELECT plan_cuentas.codcta from empresa inner join plan_cuentas on plan_cuentas.id_plan_cuentas=empresa.id_plan_cuentas_gasto where plan_cuentas.id_empresa={$request->id_empresa}");
        $resultado=DB::select("SELECT plan_cuentas.id_plan_cuentas from empresa inner join plan_cuentas on plan_cuentas.id_plan_cuentas=empresa.id_plan_cuentas_resultado where plan_cuentas.id_empresa={$request->id_empresa}");
        // dd("SELECT if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))<0,null,sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))) as debe,if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))<0,sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)),null) as haber,sum(if(debe is null,0,debe)) as suma_debe,sum(if(haber is null,0,haber)) as suma_haber, sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) as saldo,asientos_detalle.id_plan_cuentas,CONCAT(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        // proyecto.id_proyecto,proyecto.descripcion,
        // max(plan_cuentas.bansel) as bansel
        // from asientos_detalle 
        // INNER JOIN plan_cuentas on plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas
        // INNER JOIN asientos on asientos.id_asientos=asientos_detalle.id_asientos
        // INNER JOIN proyecto on proyecto.id_proyecto=asientos_detalle.id_proyecto
        // where plan_cuentas.id_empresa={$request->id_empresa} and asientos.fecha BETWEEN date('{$año}-01-01') and date('{$año}-12-31') and (plan_cuentas.codcta like '{$ingreso[0]->codcta}%' or plan_cuentas.codcta like '{$egreso[0]->codcta}%' or plan_cuentas.codcta  like '{$gasto[0]->codcta}%')
        // GROUP BY asientos_detalle.id_plan_cuentas,asientos_detalle.id_proyecto
        // ORDER BY max(plan_cuentas.codcta) asc");


        // $asientos=DB::select("SELECT if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))<0,null,(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)))) as haber,if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))<0,(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)))*-1,null) as debe,sum(if(debe is null,0,debe)) as suma_debe,sum(if(haber is null,0,haber)) as suma_haber, sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) as saldo,asientos_detalle.id_plan_cuentas,CONCAT(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        // proyecto.id_proyecto,proyecto.descripcion,
        // max(plan_cuentas.bansel) as bansel
        // from asientos_detalle 
        // INNER JOIN plan_cuentas on plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas
        // INNER JOIN asientos on asientos.id_asientos=asientos_detalle.id_asientos
        // INNER JOIN proyecto on proyecto.id_proyecto=asientos_detalle.id_proyecto
        // where plan_cuentas.id_empresa={$request->id_empresa} and asientos.id_proyecto is not null 
        // and asientos.fecha BETWEEN date('{$año}-01-01') and date('{$año}-12-31') 
        // and (plan_cuentas.codcta like '{$ingreso[0]->codcta}%' or plan_cuentas.codcta like '{$egreso[0]->codcta}%' or plan_cuentas.codcta  like '{$gasto[0]->codcta}%')
        // GROUP BY asientos_detalle.id_plan_cuentas,asientos_detalle.id_proyecto
        // ORDER BY max(plan_cuentas.codcta) asc");
        $asientos=DB::select("SELECT if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))<0,null,(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)))) as haber,if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))<0,(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)))*-1,null) as debe,sum(if(debe is null,0,debe)) as suma_debe,sum(if(haber is null,0,haber)) as suma_haber, sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) as saldo,asientos_detalle.id_plan_cuentas,CONCAT(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        proyecto.id_proyecto,proyecto.descripcion,
        max(plan_cuentas.bansel) as bansel
        from asientos_detalle 
        INNER JOIN plan_cuentas on plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas
        INNER JOIN asientos on asientos.id_asientos=asientos_detalle.id_asientos
        INNER JOIN proyecto on proyecto.id_proyecto=asientos_detalle.id_proyecto
        where plan_cuentas.id_empresa={$request->id_empresa} and asientos.id_proyecto is not null 
        and date(asientos.fecha) between date('{$año}-01-01') and date('{$año}-12-31')
        and (asientos.estado='Activo' or asientos.estado is null)
        and (plan_cuentas.codcta like '{$ingreso[0]->codcta}%' or plan_cuentas.codcta like '{$egreso[0]->codcta}%' or plan_cuentas.codcta  like '{$gasto[0]->codcta}%')
        GROUP BY asientos_detalle.id_plan_cuentas,asientos_detalle.id_proyecto
        ORDER BY max(plan_cuentas.codcta) asc");

        $numero=$diario[0]->numero+1;
        return [
            'ingreso_cod'=>$ingreso[0]->codcta,
            'egreso_cod'=>$egreso[0]->codcta,
            'gasto_cod'=>$gasto[0]->codcta,
            'asientos_detalle'=>$asientos,
            'proyecto_id'=>$proyecto[0]->id_proyecto,
            'nombre_empresa'=>$ingreso[0]->nombre_empresa,
            'numero'=>$numero
        ];
    }
    public function listarCierrePeriodoCtaResultado(Request $request){
        $año=substr($request->fecha,0,4);
        $proyecto=DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $diario=DB::select("SELECT max(numero) as numero from asientos where id_empresa={$request->id_empresa} and id_asientos_comprobante=3");
        $ingreso=DB::select("SELECT plan_cuentas.codcta,nombre_empresa from empresa inner join plan_cuentas on plan_cuentas.id_plan_cuentas=empresa.id_plan_cuentas_ingreso where plan_cuentas.id_empresa={$request->id_empresa}");
        $egreso=DB::select("SELECT plan_cuentas.codcta from empresa inner join plan_cuentas on plan_cuentas.id_plan_cuentas=empresa.id_plan_cuentas_costo where plan_cuentas.id_empresa={$request->id_empresa}");
        $gasto=DB::select("SELECT plan_cuentas.codcta from empresa inner join plan_cuentas on plan_cuentas.id_plan_cuentas=empresa.id_plan_cuentas_gasto where plan_cuentas.id_empresa={$request->id_empresa}");
        $resultado=DB::select("SELECT plan_cuentas.id_plan_cuentas from empresa inner join plan_cuentas on plan_cuentas.id_plan_cuentas=empresa.id_plan_cuentas_resultado where plan_cuentas.id_empresa={$request->id_empresa}");

        $asiento_resultado=DB::select("SELECT asientos_detalle.id_proyecto,proyecto.descripcion,if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))<0,(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)))*-1,sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber))) as saldo,
        (select id_plan_cuentas from plan_cuentas where id_plan_cuentas={$resultado[0]->id_plan_cuentas}) as id_plan_cuentas,
        (select CONCAT(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas={$resultado[0]->id_plan_cuentas}) as nombre_cuenta,
        null as otro,sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) as otro_saldo
        from asientos_detalle 
        INNER JOIN plan_cuentas on plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas
        INNER JOIN asientos on asientos.id_asientos=asientos_detalle.id_asientos
        INNER JOIN proyecto on proyecto.id_proyecto=asientos_detalle.id_proyecto
        where plan_cuentas.id_empresa={$request->id_empresa} and asientos.id_proyecto is not null 
        and date(asientos.fecha) between date('{$año}-01-01') and date('{$año}-12-31')
        and (asientos.estado='Activo' or asientos.estado is null)
        and (plan_cuentas.codcta like '{$ingreso[0]->codcta}%' or plan_cuentas.codcta like '{$egreso[0]->codcta}%' or plan_cuentas.codcta  like '{$gasto[0]->codcta}%')
        GROUP BY asientos_detalle.id_proyecto
        ORDER BY asientos_detalle.id_proyecto asc");
        return [
            'asiento_resultado'=>$asiento_resultado
        ];
    }
    public function agregarAsiento(Request $request){
        //FacturaCompra::where('id_factcompra',$request->cod_rol)->update(['contabilidad'=>'1']);
        $asientos=new Asientos();
        $asientos->automatico=0;
        $asientos->numero=$request->numero;
        $asientos->codigo=$request->codigo;
        $asientos->codigo_rol=$request->cod_rol;
        $asientos->fecha=$request->fecha;
        $asientos->razon_social=$request->razon_social;
        $asientos->concepto=$request->concepto;
        $asientos->cierre_contable="Estado Contable";
        $asientos->periodo=$request->periodo;
        $asientos->ucrea=$request->ucrea;
        $asientos->id_proyecto=$request->id_proyecto;
        $asientos->id_empresa=$request->id_empresa;
        $asientos->id_asientos_comprobante=3;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request){
        foreach($request->detalle as $detalle){
            $asiento=new Asientos_contables_detalle();
            $asiento->proyecto=$detalle["descripcion"];
            $asiento->debe=$detalle["debe"];
            $asiento->haber=$detalle["haber"];
            $asiento->ucrea=$request->ucrea;
            $asiento->id_plan_cuentas=$detalle["id_plan_cuentas"];
            $asiento->id_asientos=$request->id_asientos;
            $asiento->id_proyecto=$detalle["id_proyecto"];
            $asiento->save();
        }
        foreach($request->resultado as $detalle){
            $asiento=new Asientos_contables_detalle();
            $asiento->proyecto=$detalle["descripcion"];
            if($detalle["otro_saldo"]>0){
                $asiento->debe=$detalle["saldo"];
            }
            if($detalle["otro_saldo"]<0){
                $asiento->haber=$detalle["saldo"];
            }
            $asiento->ucrea=$request->ucrea;
            $asiento->id_plan_cuentas=$detalle["id_plan_cuentas"];
            $asiento->id_asientos=$request->id_asientos;
            $asiento->id_proyecto=$detalle["id_proyecto"];
            $asiento->save();
        }
    }
    public function eliminar($id){
        Asientos::where("id_asientos", "=", $id)->delete();
    }
    // funciones del cierre mes
    public function indexCierreMes(Request $request){
        $response=DB::select("SELECT asientos.*,asientos_comprobante.codigo as codigo_comprobante,asientos_comprobante.tipo from asientos INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante=asientos.id_asientos_comprobante where id_empresa={$request->id_empresa} and cierre_contable='Cierre Mes'");
        return [
            'recupera' => $response
        ];
    }
    public function agregarAsientoCierreMes(Request $request){
        //FacturaCompra::where('id_factcompra',$request->cod_rol)->update(['contabilidad'=>'1']);
        $select=DB::select("SELECT max(numero) as numero from asientos where id_empresa={$request->id_empresa} and id_asientos_comprobante=3");
        $empresa=DB::select("SELECT * from empresa where id_empresa={$request->id_empresa}");
        $numero=1;
        if($select){
            $numero=$select[0]->numero+1;
        }
        $mes=substr($request->fecha_mes,5,2);
        $anio=substr($request->fecha_mes,0,4);
        $periodo=substr($request->fecha_mes,0,7);
        $concepto="Cierre Mes ".$mes." ".$anio;
        $asientos=new Asientos();
        $asientos->automatico=0;
        $asientos->numero=$numero;
        $asientos->codigo="D-".$numero;
        $asientos->fecha=$request->fecha_mes;
        $asientos->razon_social=$empresa[0]->nombre_empresa;
        $asientos->concepto=$concepto;
        $asientos->cierre_contable="Cierre Mes";
        $asientos->periodo=$periodo;
        $asientos->ucrea=$request->ucrea;
        $asientos->id_empresa=$request->id_empresa;
        $asientos->id_asientos_comprobante=3;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function getFecha($id){
        $select=DB::select("SELECT * from asientos where id_asientos=".$id);
        return [
            'recupera'=>$select
        ];
    }
    public function editarCierre(Request $request){
        //FacturaCompra::where('id_factcompra',$request->cod_rol)->update(['contabilidad'=>'1']);
        
        $mes=substr($request->fecha_mes,5,2);
        $anio=substr($request->fecha_mes,0,4);
        $periodo=substr($request->fecha_mes,0,7);
        $concepto="Cierre Mes ".$mes." ".$anio;
        $asientos=Asientos::find($request->id);
        $asientos->automatico=0;
        $asientos->fecha=$request->fecha_mes;
        $asientos->periodo=$periodo;
        $asientos->umodifica=$request->umodifica;
        $asientos->id_empresa=$request->id_empresa;

        $asientos->save();
        return $asientos->id_asientos;
    }
}
