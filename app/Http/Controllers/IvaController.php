<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iva;

class IvaController extends Controller
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
            $recupera = Iva::orderByRaw('id_iva DESC')->paginate($cantidadp);
        } else {
            $recupera = Iva::select('*')
                ->where('nombre', 'like', '%' . $buscar . '%')
                ->orderByRaw('id_iva DESC')->paginate($cantidadp);
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
        $tipo = Iva::all();
        return $tipo;
    }

    public function store(Request $request)
    {
        $tipo = new Iva();
        $tipo->nombre = $request->nombre;
        $tipo->save();
    }

    public function editar(Request $request)
    {
        $tipo= Iva::findOrFail($request->id);
        $tipo->nombre = $request->nombre;
        $tipo->save();  
    }

    public function eliminar ($id){
        Iva::destroy($id); 
    }
}