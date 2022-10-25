<?php

namespace App\Http\Controllers;
use App\Models\CuentaEgresoBodega;
use App\Models\CuentaIngresoBodega;
use App\Models\CuentaTransfBodega;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CuentasBodegaController extends Controller
{
    //
    public function indexCta_Ingreso(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            // $recupera = Importacion::select('importacion.*')
            // //->join('factur','producto.id_producto','=','importacion.id_producto')
            // ->where('id_punto_emision',$id)
            // ->where('cod_importacion',like)
            // ->orderByRaw('importacion.id_importacion ASC')->get();
            $recupera =DB::select("SELECT cuenta_bodega_ingreso.*,id_cuenta_bodega_ingreso as id_cuenta_ingreso_bodega,bodega.nombre as nombre_bodega from cuenta_bodega_ingreso left join bodega on bodega.id_bodega=cuenta_bodega_ingreso.id_bodega where cuenta_bodega_ingreso.id_empresa={$id} order by id_cuenta_bodega_ingreso ASC");
        }else{
            // $recupera = Importacion::select('ingreso_bodega.*')
            // //->join('producto','producto.id_producto','=','ingreso_bodega.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_punto_emision',$id)
            // ->orderByRaw('ingreso_bodega.id_ingreso_bodega ASC')->get();
            $recupera =DB::select("SELECT cuenta_bodega_ingreso.*,id_cuenta_bodega_ingreso as id_cuenta_ingreso_bodega,bodega.nombre as nombre_bodega from cuenta_bodega_ingreso left join bodega on bodega.id_bodega=cuenta_bodega_ingreso.id_bodega where cuenta_bodega_ingreso.id_empresa={$id} and (cod_cuenta like '%{$buscar}%' or nombre_cuenta like '%{$buscar}%') order by id_cuenta_bodega_ingreso ASC");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function indexCta_Egreso(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            // $recupera = Importacion::select('importacion.*')
            // //->join('factur','producto.id_producto','=','importacion.id_producto')
            // ->where('id_punto_emision',$id)
            // ->where('cod_importacion',like)
            // ->orderByRaw('importacion.id_importacion ASC')->get();
            $recupera =DB::select("SELECT cuenta_bodega_egreso.*,id_cuenta_bodega_egreso as id_cuenta_egreso_bodega,bodega.nombre as nombre_bodega from cuenta_bodega_egreso left join bodega on bodega.id_bodega=cuenta_bodega_egreso.id_bodega where cuenta_bodega_egreso.id_empresa={$id} order by id_cuenta_bodega_egreso ASC");
        }else{
            // $recupera = Importacion::select('egreso_bodega.*')
            // //->join('producto','producto.id_producto','=','egreso_bodega.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_punto_emision',$id)
            // ->orderByRaw('egreso_bodega.id_egreso_bodega ASC')->get();
            $recupera =DB::select("SELECT cuenta_bodega_egreso.*,id_cuenta_bodega_egreso as id_cuenta_egreso_bodega,bodega.nombre as nombre_bodega from cuenta_bodega_egreso left join bodega on bodega.id_bodega=cuenta_bodega_egreso.id_bodega where cuenta_bodega_egreso.id_empresa={$id} and (cod_cuenta like '%{$buscar}%' or nombre_cuenta like '%{$buscar}%') order by id_cuenta_bodega_egreso ASC");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function indexCta_Transf(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            // $recupera = Importacion::select('importacion.*')
            // //->join('factur','producto.id_producto','=','importacion.id_producto')
            // ->where('id_punto_emision',$id)
            // ->where('cod_importacion',like)
            // ->orderByRaw('importacion.id_importacion ASC')->get();
            $recupera =DB::select("SELECT cuenta_bodega_transferencia.*,id_cuenta_bodega_transferencia as id_cuenta_transf_bodega,bodega.nombre as nombre_bodega from cuenta_bodega_transferencia left join bodega on bodega.id_bodega=cuenta_bodega_transferencia.id_bodega where cuenta_bodega_transferencia.id_empresa={$id} order by id_cuenta_bodega_transferencia ASC");
        }else{
            // $recupera = Importacion::select('transferencia_bodega.*')
            // //->join('producto','producto.id_producto','=','transferencia_bodega.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_punto_emision',$id)
            // ->orderByRaw('transferencia_bodega.id_transferencia_bodega ASC')->get();
            $recupera =DB::select("SELECT cuenta_bodega_transferencia.*,id_cuenta_bodega_transferencia as id_cuenta_transf_bodega,bodega.nombre as nombre_bodega from cuenta_bodega_transferencia left join bodega on bodega.id_bodega=cuenta_bodega_transferencia.id_bodega where cuenta_bodega_transferencia.id_empresa={$id} and (cod_cuenta like '%{$buscar}%' or nombre_cuenta like '%{$buscar}%') order by id_cuenta_bodega_transferencia ASC");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function storeCta_Ingreso(Request $request){
        $import =new CuentaIngresoBodega();
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->ucrea=$request->ucrea;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_bodega=$request->id_bodega;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function storeCta_Egreso(Request $request){
        $import =new CuentaEgresoBodega();
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->ucrea=$request->ucrea;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_bodega=$request->id_bodega;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function storeCta_Transf(Request $request){
        $import =new CuentaTransfBodega();
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->ucrea=$request->ucrea;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_bodega=$request->id_bodega;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function abrirCta_Ingreso($id){
        $recupera =DB::select("SELECT *,id_cuenta_bodega_ingreso as id_cuenta_ingreso_bodega from cuenta_bodega_ingreso where id_cuenta_bodega_ingreso={$id}");
        return [
            'recupera' => $recupera
        ];
    }
    public function abrirCta_Egreso($id){
        $recupera =DB::select("SELECT *,id_cuenta_bodega_egreso as id_cuenta_egreso_bodega from cuenta_bodega_egreso where id_cuenta_bodega_egreso={$id}");
        return [
            'recupera' => $recupera
        ];
    }
    public function abrirCta_Transf($id){
        $recupera =DB::select("SELECT *,id_cuenta_bodega_transferencia as id_cuenta_transf_bodega from cuenta_bodega_transferencia where id_cuenta_bodega_transferencia={$id}");
        return [
            'recupera' => $recupera
        ];
    }
    public function updateCta_Ingreso(Request $request){
        $import =CuentaIngresoBodega::findOrFail($request->id);
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->umodifica=$request->umodifica;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_bodega=$request->id_bodega;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function updateCta_Egreso(Request $request){
        $import =CuentaEgresoBodega::findOrFail($request->id);
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->umodifica=$request->umodifica;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_bodega=$request->id_bodega;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function updateCta_Transf(Request $request){
        $import =CuentaTransfBodega::findOrFail($request->id);
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->umodifica=$request->umodifica;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_bodega=$request->id_bodega;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function eliminarCta_Ingreso($id){
        CuentaIngresoBodega::destroy($id);
    }
    public function eliminarCta_Egreso($id){
        CuentaEgresoBodega::destroy($id);
    }
    public function eliminarCta_Transf($id){
        CuentaTransfBodega::destroy($id);
    }
}
