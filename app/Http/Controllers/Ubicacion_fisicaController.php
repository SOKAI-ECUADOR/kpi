<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ubicacion_fisica;

class Ubicacion_fisicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Ubicacion_fisica::select('ubicacion_fisica.*', 'bodega.nombre as nombrebodega')
                                        ->join('bodega', 'bodega.id_bodega', '=', 'ubicacion_fisica.id_bodega')
                                        ->paginate($cantidadp);
        } else {
            $recupera = Ubicacion_fisica::orderByRaw('id_ubicacion_fisica DESC')->select('*')
                ->where('nombre', 'like', '%' . $buscar . '%')
                ->orderByRaw('id_ubicacion_fisica DESC')->paginate($cantidadp);
        }
        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => ceil($recupera->total() / $cantidadp),
            ],
            'recupera' => $recupera
        ];
    }

    public function todo()
    {
        $tipo = Ubicacion_fisica::all();
        return $tipo;
    }
    public function todor(Request $request)
    {
        $id = $request->id;
        $tipo = Ubicacion_fisica::all()->where('id_bodega','=',$id);
        return $tipo;
    }

    public function store(Request $request)
    {
        $tipo = new Ubicacion_fisica();
        $tipo->nombre = $request->nombre;
        $tipo->descripcion = $request->descripcion;
        $tipo->id_bodega = $request->id_bodega;
        $tipo->save();
    }

    public function editar(Request $request)
    {
        $tipo= Ubicacion_fisica::findOrFail($request->id);
        $tipo->nombre = $request->nombre;
        $tipo->descripcion = $request->descripcion;
        $tipo->id_bodega = $request->selecbodega;
 
        $tipo->save();  
    }

    public function eliminar ($id){
        Ubicacion_fisica::destroy($id);
    }
}
