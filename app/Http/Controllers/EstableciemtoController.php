<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use App\Models\Empresa;

class EstableciemtoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            $recupera = Establecimiento::select("*")->where("id_empresa", "=", $id)->orderBy("id_establecimiento", "DESC")->get();
        } else {
            $recupera = Establecimiento::select("*")
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('codigo', 'like', '%' . $buscar . '%')
                        ->orWhere('nombre_comercial', 'like', '%' . $buscar . '%')
                        ->orWhere('direccion', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)->orderBy("id_establecimiento", "DESC")->get();
        }
        return [
            'recupera' => $recupera
        ];
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
    public function store(Request $request)
    {
        //
        $establec = new Establecimiento();
        $establec->nombre = $request->nombre;
        $establec->codigo = $request->codigo;
        $establec->urlweb = $request->urlweb;
        $establec->nombre_comercial = $request->nombre_comercial;
        $establec->direccion = $request->direccion;
        $establec->estado = $request->radios1;
        $establec->id_empresa = $request->id_empresa;
        $establec->save();
    }

    public function todo($id)
    {
        $establec = Establecimiento::select("*")->where("id_empresa", "=", $id)->get();
        $establec[count($establec) + 1] = array("id_establecimiento" => 0, "nombre" => "Admin");
        return $establec;
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
    public function update(Request $request)
    {
        $establec = Establecimiento::findorFail($request->id);
        $establec->nombre = $request->nombre;
        $establec->codigo = $request->codigo;
        $establec->urlweb = $request->urlweb;
        $establec->nombre_comercial = $request->nombre_comercial;
        $establec->direccion = $request->direccion;
        $establec->estado = $request->radios1;
        $establec->id_empresa = $request->id_empresa;
        $establec->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::select('DELETE FROM punto_emision WHERE id_establecimiento =' . $id);
        Establecimiento::destroy($id);
    }
    public function abriremisor()
    {
        $recupera = DB::select('SELECT * FROM empresa');
        // $recupera = DB::all();
        return $recupera;
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM establecimiento WHERE id_establecimiento =' . $id);
        return [
            "recupera" => $recupera,

        ];
    }
}
