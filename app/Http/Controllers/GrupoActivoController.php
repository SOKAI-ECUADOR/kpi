<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivoGrupo;

class GrupoActivoController extends Controller
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
            $recupera =DB::select("SELECT * from activo_fijo_grupo where id_empresa={$id} order by id_activo_fijo_grupo ASC");
        }else{
            // $recupera = Importacion::select('activo_fijo_grupo.*')
            // //->join('producto','producto.id_producto','=','activo_fijo_grupo.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_activo_fijo_grupo',$id)
            // ->orderByRaw('activo_fijo_grupo.id_activo_fijo_grupo ASC')->get();
            $recupera =DB::select("SELECT * from activo_fijo_grupo where id_empresa={$id} and nombre like '%{$buscar}%' order by id_activo_fijo_grupo ASC");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    
    public function abrir($id){
        $recupera =DB::select("SELECT *,(select nomcta from plan_cuentas where activo_fijo_grupo.id_plan_cuenta_debito=plan_cuentas.id_plan_cuentas) as nombre_cuenta_debito,(select nomcta from plan_cuentas where activo_fijo_grupo.id_plan_cuenta_credito=plan_cuentas.id_plan_cuentas) as nombre_cuenta_credito from activo_fijo_grupo where id_activo_fijo_grupo={$id}");
        return $recupera;
    }
    public function store(Request $request){
        $select=DB::select("SELECT max(codigo) as codigo from activo_fijo_grupo where id_empresa={$request->id_empresa}");
        $codigo='';
        if($select){
            $codigo=$select[0]->codigo+1;
        }else{
            $codigo=1; 
        }
        $activo=new ActivoGrupo();
        $activo->codigo=$codigo;
        $activo->nombre=$request->nombre;
        $activo->anios=$request->anios;
        $activo->valor_residual=$request->valor_residual;
        $activo->porcentaje=$request->porcentaje;
        $activo->ucrea=$request->ucrea;
        $activo->id_empresa=$request->id_empresa;
        $activo->id_plan_cuenta_debito=$request->id_plan_cuenta_debito;
        $activo->id_plan_cuenta_credito=$request->id_plan_cuenta_credito;
        $activo->save();
    }
    public function update(Request $request){
        $activo=ActivoGrupo::find($request->id);
        $activo->nombre=$request->nombre;
        $activo->anios=$request->anios;
        $activo->valor_residual=$request->valor_residual;
        $activo->porcentaje=$request->porcentaje;
        $activo->ucrea=$request->ucrea;
        $activo->id_empresa=$request->id_empresa;
        $activo->id_plan_cuenta_debito=$request->id_plan_cuenta_debito;
        $activo->id_plan_cuenta_credito=$request->id_plan_cuenta_credito;
        $activo->save();
    }
    public function eliminar($id){
        ActivoGrupo::destroy($id);
    }
}
