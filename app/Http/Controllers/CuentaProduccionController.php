<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentaProduccion;
use Illuminate\Support\Facades\DB;
//recupera las librerias de generar reporte y envio de email del SRI ubicado en class de controllers
include 'class/generarReportes.php';
include 'class/sendEmail.php';

use DOMDocument;
use generarReportes;
use sendEmail;


class CuentaProduccionController extends Controller
{
    //
    public function index(Request $request,$id){
        if($request->buscar==''){
            $recupera=DB::select("SELECT * from cuenta_produccion where id_empresa=$id");
        }else{
            $recupera=DB::select("SELECT * from cuenta_produccion where (cod_cuenta like '%{$request->buscar}%' or nombre_cuenta like '%{$request->buscar}%') and id_empresa=$id");
        }
        return $recupera;
    }
    public function store(Request $request){
        $import =new CuentaProduccion();
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->ucrea=$request->ucrea;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function update(Request $request){
        $import =CuentaProduccion::find($request->id);
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->umodifica=$request->umodifica;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function delete(Request $request){
        CuentaProduccion::destroy($request->datos);
    }
    public function pdf_produccion($id){
        $proceso_produccion=DB::select("SELECT proceso_produccion.*,establecimiento.nombre,proyecto.descripcion as descripcion_proyecto from proceso_produccion 
                                        LEFT JOIN establecimiento on establecimiento.id_establecimiento=proceso_produccion.id_establecimiento
                                        LEFT JOIN proyecto on proyecto.id_proyecto=proceso_produccion.id_proyecto
                                        where id_proceso_produccion=$id");
        $proceso_ingrediente=DB::select("SELECT proceso_ingrediente.*,producto.cod_principal,producto.cod_alterno,producto.nombre, bodega.nombre AS bodega, proyecto.descripcion AS proyecto from proceso_ingrediente
                                        INNER JOIN proceso_produccion
                                        ON proceso_produccion.id_proceso_produccion=proceso_ingrediente.id_proceso_produccion
                                        INNER JOIN producto
                                        ON producto.id_producto=proceso_ingrediente.id_producto
                                        INNER JOIN bodega
                                        ON proceso_ingrediente.id_bodega=bodega.id_bodega
                                        INNER JOIN proyecto
                                        ON proceso_ingrediente.id_proyecto=proyecto.id_proyecto
                                        where proceso_ingrediente.id_proceso_produccion=$id");
        $proceso_producir=DB::select("SELECT proceso_producto.*,producto.cod_principal,producto.cod_alterno,producto.nombre,bodega.nombre as nombre_bodega,formula_produccion.nombre_form as nombre_formula from proceso_producto
                                        INNER JOIN proceso_produccion
                                        ON proceso_produccion.id_proceso_produccion=proceso_producto.id_proceso_produccion
                                        INNER JOIN producto
                                        ON producto.id_producto=proceso_producto.id_producto
                                        LEFT JOIN bodega
                                        ON bodega.id_bodega=proceso_producto.id_bodega
                                        LEFT JOIN formula_produccion
                                        ON formula_produccion.id_formula_produccion=proceso_producto.id_formula_produccion
                                        where proceso_producto.id_proceso_produccion=$id");
        $empresa=DB::select("SELECT * from empresa where id_empresa={$proceso_produccion[0]->id_empresa}");

        $Reportes = new generarReportes();
        $strPDF = $Reportes->Produccion_Pdf($proceso_produccion,$proceso_ingrediente, $proceso_producir,$empresa[0]);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
    }
}
