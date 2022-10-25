<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modelo;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Modelo::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_modelo DESC')->get();
        } else {
            $recupera = Modelo::select('*')
                ->where(function($q) use ($buscar){
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('descripcion', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_modelo DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function todo($id)
    {
        $tipo = Modelo::select("*")->where("id_empresa", "=", $id)->get();
        return $tipo;
    }

    public function store(Request $request)
    {
        $datos = new Modelo();
        $datos->nombre = $request->nombre;
        $datos->descripcion = $request->descripcion;
        $datos->id_empresa = $request->id_empresa;
        $datos->save();
    }

    public function editar(Request $request)
    {
        $datos= Modelo::findOrFail($request->id);
        $datos->nombre = $request->nombre;
        $datos->descripcion = $request->descripcion;
        $datos->id_empresa = $request->id_empresa;
        $datos->save();  
    }

    public function eliminar ($id){
        Modelo::destroy($id);
    }   
}
