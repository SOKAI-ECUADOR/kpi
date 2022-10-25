<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivoArea;

class AreaActivoController extends Controller
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
            $recupera =DB::select("SELECT * from activo_fijo_area where id_empresa={$id} order by id_activo_fijo_area ASC");
        }else{
            // $recupera = Importacion::select('activo_fijo_area.*')
            // //->join('producto','producto.id_producto','=','activo_fijo_area.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_activo_fijo_area',$id)
            // ->orderByRaw('activo_fijo_area.id_activo_fijo_area ASC')->get();
            $recupera =DB::select("SELECT * from activo_fijo_area where id_empresa={$id} and nombre like '%{$buscar}%' order by id_activo_fijo_area ASC");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function abrir($id){
        $recupera =DB::select("SELECT * from activo_fijo_area where id_activo_fijo_area={$id}");
        return $recupera;
    }
    public function store(Request $request){
        $select=DB::select("SELECT max(codigo) as codigo from activo_fijo_area where id_empresa={$request->id_empresa}");
        $codigo='';
        if($select){
            $codigo=$select[0]->codigo+1;
        }else{
            $codigo=1; 
        }
        $activo=new ActivoArea();
        $activo->codigo=$codigo;
        $activo->nombre=$request->nombre;
        $activo->ucrea=$request->ucrea;
        $activo->id_empresa=$request->id_empresa;
        $activo->save();
    }
    public function update(Request $request){
        $activo=ActivoArea::find($request->id);
        $activo->nombre=$request->nombre;
        $activo->umodifica=$request->umodifica;
        $activo->id_empresa=$request->id_empresa;
        $activo->save();
    }
    public function eliminar($id){
        ActivoArea::destroy($id);
    }
}
