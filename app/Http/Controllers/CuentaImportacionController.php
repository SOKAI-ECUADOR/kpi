<?php

namespace App\Http\Controllers;
use App\Models\Cuenta_Importacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CuentaImportacionController extends Controller
{
    public function index(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            // $recupera = Importacion::select('importacion.*')
            // //->join('factur','producto.id_producto','=','importacion.id_producto')
            // ->where('id_punto_emision',$id)
            // ->where('cod_importacion',like)
            // ->orderByRaw('importacion.id_importacion ASC')->get();
            $recupera =DB::select("SELECT * from cuenta_importacion where id_empresa={$id} order by id_cuenta_importacion ASC");
        }else{
            // $recupera = Importacion::select('importacion.*')
            // //->join('producto','producto.id_producto','=','importacion.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_punto_emision',$id)
            // ->orderByRaw('importacion.id_importacion ASC')->get();
            $recupera =DB::select("SELECT * from cuenta_importacion where id_empresa={$id} and (cod_cuenta like '%{$buscar}%' or nombre_cuenta like '%{$buscar}%') order by id_cuenta_importacion ASC");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function store(Request $request){
        $import =new Cuenta_Importacion();
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->ucrea=$request->ucrea;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function abrir($id){
        $recupera =DB::select("SELECT * from cuenta_importacion where id_cuenta_importacion={$id}");
        return [
            'recupera' => $recupera
        ];
    }
    public function update(Request $request){
        $import =Cuenta_Importacion::findOrFail($request->id);
        $import->cod_cuenta=$request->cod_cuenta;
        $import->nombre_cuenta=$request->nombre_cuenta;
        $import->umodifica=$request->umodifica;
        $import->id_plan_cuentas=$request->id_plan_cuentas;
        $import->id_empresa=$request->id_empresa;
        $import->save();
    }
    public function eliminar($id){
        Cuenta_Importacion::destroy($id);
    }
}
