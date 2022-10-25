<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivoFijo;

class ActivoFijoController extends Controller
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
            $recupera =DB::select("SELECT activos_fijos.*,if(activos_fijos.nombre_proveedor is null,proveedor.nombre_proveedor,activos_fijos.nombre_proveedor) as prov_nombre,if(activos_fijos.nro_factura is null,factura_compra.descripcion,activos_fijos.nro_factura) as descricion_factura from activos_fijos LEFT JOIN proveedor ON proveedor.id_proveedor=activos_fijos.id_proveedor LEFT JOIN factura_compra ON factura_compra.id_factcompra=activos_fijos.id_factura_compra where activos_fijos.id_empresa={$id} order by activos_fijos.id_activos_fijos ASC");
        }else{
            // $recupera = Importacion::select('activos_fijos.*')
            // //->join('producto','producto.id_producto','=','activos_fijos.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_activos_fijos',$id)
            // ->orderByRaw('activos_fijos.id_activos_fijos ASC')->get();
            $recupera =DB::select("SELECT activos_fijos.*,if(activos_fijos.nombre_proveedor is null,proveedor.nombre_proveedor,activos_fijos.nombre_proveedor) as prov_nombre,if(activos_fijos.nro_factura is null,factura_compra.descripcion,activos_fijos.nro_factura) as descricion_factura from activos_fijos LEFT JOIN proveedor ON proveedor.id_proveedor=activos_fijos.id_proveedor LEFT JOIN factura_compra ON factura_compra.id_factcompra=activos_fijos.id_factura_compra where activos_fijos.id_empresa={$id} and (activos_fijos.nombre like '%{$buscar}%' or proveedor.nombre_proveedor like '%{$buscar}%') order by activos_fijos.id_activos_fijos ASC");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function abrirGrupo(Request $request,$id){
        $recupera =DB::select("SELECT * from activo_fijo_grupo where id_activo_fijo_grupo={$id}");
        $activo_grupo=DB::select("SELECT if(sum(valor_depreciacion) is null,0,sum(valor_depreciacion)) as acum_valor_depreciacion from activos_fijos where id_grupo_activo_fijo={$id} and id_empresa={$request->id_empresa}");
        return [
            'recupera' => $recupera,
            'acum_valor_depreciacion'=>$activo_grupo
        ];
    }
    public function abrir($id){
        $recupera =DB::select("SELECT activos_fijos.*,factura_compra.fech_emision,factura_compra.descripcion,factura_compra.nro_autorizacion as nro_autorizacion_factura,if(activos_fijos.nombre_proveedor is null,proveedor.nombre_proveedor,activos_fijos.nombre_proveedor) as prov_nombre,(select nomcta from plan_cuentas where activos_fijos.id_plan_cuentas_debito=plan_cuentas.id_plan_cuentas) as nombre_cuenta_debito,(select nomcta from plan_cuentas where activos_fijos.id_plan_cuentas_credito=plan_cuentas.id_plan_cuentas) as nombre_cuenta_credito 
        from activos_fijos 
        LEFT JOIN proveedor ON proveedor.id_proveedor=activos_fijos.id_proveedor 
        LEFT JOIN factura_compra ON factura_compra.id_factcompra=activos_fijos.id_factura_compra 
        where activos_fijos.id_activos_fijos={$id}");
        return $recupera;
    }
    public function store(Request $request){
        $select=DB::select("SELECT max(codigo) as codigo from activos_fijos where id_empresa={$request->id_empresa}");
        $codigo='';
        if($select){
            $codigo=$select[0]->codigo+1;
        }else{
            $codigo=1; 
        }
        $activo=new ActivoFijo();
        $activo->codigo=$codigo;
        $activo->codigo_barra=$request->codigo_barra;
        $activo->nombre=$request->nombre;
        $activo->depreciacion_mensual=$request->depreciacion_mensual;
        $activo->valor_bien=$request->valor_bien;
        $activo->valor_residual=$request->valor_residual;
        $activo->valor_depreciar=$request->valor_depreciar;
        $activo->valor_depreciacion=$request->valor_depreciacion;
        $activo->valor_reavaluo=$request->valor_reavaluo;
        $activo->depreciacion_acumulada=$request->depreciacion_acumulada;
        $activo->valor_actual=$request->valor_actual;
        $activo->fecha_movimiento=$request->fecha_movimiento;
        $activo->ultima_depreciacion=$request->ultima_depreciacion;
        $activo->fecha_factura=$request->fecha_factura;
        $activo->nro_factura=$request->nro_factura;
        $activo->nro_autorizacion=$request->nro_autorizacion;
        $activo->nombre_proveedor=$request->nombre_proveedor;
        $activo->ucrea=$request->ucrea;
        $activo->id_plan_cuentas_debito=$request->id_plan_cuentas_debito;
        $activo->id_plan_cuentas_credito=$request->id_plan_cuentas_credito;
        $activo->id_area_activo_fijo=$request->id_area_activo_fijo;
        $activo->id_departamento=$request->id_departamento;
        $activo->id_empresa=$request->id_empresa;
        $activo->id_grupo_activo_fijo=$request->id_grupo_activo_fijo;
        $activo->id_tipo_activo_fijo=$request->id_tipo_activo_fijo;
        $activo->id_factura_compra=$request->id_factura_compra;
        $activo->id_empleado=$request->id_empleado;
        $activo->id_proveedor=$request->id_proveedor;
        $activo->id_proyecto=$request->id_proyecto;
        $activo->id_moneda=$request->id_moneda;
        $activo->save();
    }
    public function update(Request $request){
        $activo=ActivoFijo::find($request->id);
        $activo->codigo_barra=$request->codigo_barra;
        $activo->nombre=$request->nombre;
        $activo->depreciacion_mensual=$request->depreciacion_mensual;
        $activo->valor_bien=$request->valor_bien;
        $activo->valor_residual=$request->valor_residual;
        $activo->valor_depreciar=$request->valor_depreciar;
        $activo->valor_depreciacion=$request->valor_depreciacion;
        $activo->valor_reavaluo=$request->valor_reavaluo;
        $activo->depreciacion_acumulada=$request->depreciacion_acumulada;
        $activo->valor_actual=$request->valor_actual;
        $activo->fecha_movimiento=$request->fecha_movimiento;
        $activo->ultima_depreciacion=$request->ultima_depreciacion;
        $activo->fecha_factura=$request->fecha_factura;
        $activo->nro_factura=$request->nro_factura;
        $activo->nro_autorizacion=$request->nro_autorizacion;
        $activo->nombre_proveedor=$request->nombre_proveedor;
        $activo->umodifica=$request->umodifica;
        $activo->id_plan_cuentas_debito=$request->id_plan_cuentas_debito;
        $activo->id_plan_cuentas_credito=$request->id_plan_cuentas_credito;
        $activo->id_area_activo_fijo=$request->id_area_activo_fijo;
        $activo->id_departamento=$request->id_departamento;
        $activo->id_empresa=$request->id_empresa;
        $activo->id_grupo_activo_fijo=$request->id_grupo_activo_fijo;
        $activo->id_tipo_activo_fijo=$request->id_tipo_activo_fijo;
        $activo->id_factura_compra=$request->id_factura_compra;
        $activo->id_empleado=$request->id_empleado;
        $activo->id_proveedor=$request->id_proveedor;
        $activo->id_proyecto=$request->id_proyecto;
        $activo->id_moneda=$request->id_moneda;
        $activo->save();
    }
    public function eliminar($id){
        ActivoFijo::destroy($id);
    }
}
