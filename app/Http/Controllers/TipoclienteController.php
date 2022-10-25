<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tipocliente;
use Illuminate\Support\Facades\DB;


class TipoclienteController extends Controller
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
            $recupera = Tipocliente::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_tipo_cliente DESC')->get();
        } else {
            $recupera = Tipocliente::select('*')
                ->where('descripcion_tipo_cliente', 'like', '%' . $buscar . '%')
                ->where("id_empresa","=",$id)
                ->orderByRaw('id_tipo_cliente DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function store(Request $request)
    {
        $tipo = new Tipocliente();
        $tipo->descripcion_tipo_cliente = $request->descripcion_tipo_cliente;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function editar(Request $request)
    {
        $tipo= Tipocliente::findOrFail($request->id);
        $tipo->descripcion_tipo_cliente = $request->descripcion_tipo_cliente;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function eliminar ($id){ 
        
        DB::delete("DELETE FROM tipo_cliente WHERE id_tipo_cliente = ".$id);
        Tipocliente::destroy($id);
    }

    public function todo()
    {
        $tipo = Tipocliente::all();
        return $tipo;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
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
