<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductoBodegaLotes;

class ProductoBodegaLotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        // $buscar = $request->buscar;
        // if ($buscar == '') {
        //     //$impuestos = Impuesto::paginate($cantidadp); 
        //     $recupera = ProductoBodegaLotes::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_modelo DESC')->get();
        // } else {
        //     $recupera = ProductoBodegaLotes::select('*')
        //         ->where(function($q) use ($buscar){
        //             $q->where('nombre', 'like', '%' . $buscar . '%')
        //             ->orWhere('descripcion', 'like', '%' . $buscar . '%');
        //         })
        //         ->where("id_empresa", "=", $id)
        //         ->orderByRaw('id_modelo DESC')->get();
        // }
        // return [
        //     'recupera' => $recupera
        // ];
    }

    public function todo($id)
    {
        $tipo = ProductoBodegaLotes::select("*")->where("id_empresa", "=", $id)->get();
        return $tipo;
    }

    public function store(Request $request)
    {
        $datos = new ProductoBodegaLotes();
        $datos->save();
    }

    public function editar(Request $request)
    {
        $datos= ProductoBodegaLotes::findOrFail($request->id);
        $datos->save();  
    }

    public function eliminar ($id){
        ProductoBodegaLotes::destroy($id);
    }   
}
