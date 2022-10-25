<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipoproducto;

class TipoproductoController extends Controller
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
            $recupera = Tipoproducto::select('tipo_producto.*', 'linea_producto.nombre as nombrelinea')
                        ->join('linea_producto', 'linea_producto.id_linea_producto', '=', 'tipo_producto.id_linea_producto')
                        ->where("tipo_producto.id_empresa", "=", $id)->orderByRaw('id_tipo_producto DESC')->get();
        } else {
            $recupera = Tipoproducto::select('tipo_producto.*', 'linea_producto.nombre as nombrelinea')
                ->join('linea_producto', 'linea_producto.id_linea_producto', '=', 'tipo_producto.id_linea_producto')
                ->where(function($q) use ($buscar){
                    $q->where('tipo_producto.nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('tipo_producto.codigo', 'like', '%' . $buscar . '%');
                })
                ->where("tipo_producto.id_empresa", "=", $id)
                ->orderByRaw('id_tipo_producto DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function todo()
    {
        $tipo = Tipoproducto::all();
        return $tipo;
    }
 
    public function todor(Request $request, $id)
    {
        $idlp = $request->id;
        $tipo = Tipoproducto::select("*")->where('id_linea_producto','=',$idlp)->where("id_empresa", "=", $id)->get();
        return $tipo;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo = new Tipoproducto();
        $tipo->codigo = $request->codtipo;
        $tipo->nombre = $request->nombretipo;
        $tipo->utilidad = $request->utilidadtipo;
        $tipo->id_linea_producto = $request->seleclinea;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();
    }

    public function editar(Request $request)
    {
        $tipo= Tipoproducto::findOrFail($request->id);
        $tipo->codigo = $request->codtipo;
        $tipo->nombre = $request->nombretipo;
        $tipo->utilidad = $request->utilidadtipo;
        $tipo->id_linea_producto = $request->seleclinea;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function eliminar ($id){
        Tipoproducto::destroy($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
