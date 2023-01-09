<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Convenio;

class ConvenioController extends Controller
{

	//listar convenios
    public function index(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar == '') { 
            $recupera = Convenio::select("*")
             ->where("id_empresa", "=", $id)
            ->orderByRaw('nombre_convenio ASC')->get();
        } else {
            $recupera = Convenio::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre_convenio', '=', $buscar);
                })
                 ->where("id_empresa", "=", $id)
                ->orderByRaw('nombre_convenio ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //verificar permiso de convenios
    public function permiso($id){
        $recupera = DB::select("SELECT * FROM roles WHERE id_empresa = $id AND nombre = 'Convenio'");
        return [
            'recupera' => $recupera
        ];
    }

    //guardar registros
    public function store(Request $request){
        $cod_conv=DB::select("SELECT max(id_convenio) as id_convenio from convenio");
        $cod=1;
        if(count($cod_conv)>0){
            $cod=$cod_conv[0]->id_convenio+1;
        }
        $conv=new Convenio();
        $conv->id_convenio=$cod;
        $conv->nombre_convenio=$request->nombre;
        $conv->lista_precio_convenio=$request->lista_precio;
        $conv->porcentaje_descuento_convenio=$request->porcentaje_descuento;
        $conv->status_convenio=$request->status;
        $conv->id_empresa=$request->id_empresa;
        $conv->save();
    }

    //actualizar registros
    public function update(Request $request){
        $conv=Convenio::find($request->id);
        $conv->nombre_convenio=$request->nombre;
        $conv->lista_precio_convenio=$request->lista_precio;
        $conv->porcentaje_descuento_convenio=$request->porcentaje_descuento;
        $conv->status_convenio=$request->status;
        $conv->id_empresa=$request->id_empresa;
        $conv->save();
    }

    public function activacion(Request $request){
        $conv=Convenio::find($request->id);
        $status=$request->status==1?0:1;
        $conv->status_convenio=$status;
        $conv->save();
    }

    //eliminar convenio de la vista
    public function delete($id){
        Convenio::where("id_convenio","=",$id)->delete();
    }

}