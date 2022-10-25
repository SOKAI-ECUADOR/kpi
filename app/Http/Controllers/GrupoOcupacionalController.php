<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\GrupoOcupacional;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GrupoOcupacionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = GrupoOcupacional::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_grupo DESC')->get();
        } else {
            $recupera = GrupoOcupacional::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('grup_nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('grup_estado', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_grupo DESC')->get();
        }
        return ['recupera' => $recupera];
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
        $grupoOcu = new GrupoOcupacional();
        $grupoOcu->grup_nombre = $request->grup_nombre;
        $grupoOcu->grup_estado = "Activo";
        $grupoOcu->ucrea = $request->ucrea;
        $grupoOcu->id_empresa = $request->id_empresa;
        $grupoOcu->save();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $grupoOcu = GrupoOcupacional::find($request->id);
        $grupoOcu->grup_nombre = $request->grup_nombre;
        $grupoOcu->grup_estado = $request->grup_estado;
        $grupoOcu->umodifica = $request->umodifica;
        $grupoOcu->id_empresa = $request->id_empresa;
        $grupoOcu->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)

    {
        //
        GrupoOcupacional::destroy($id);
    }

    public function verGrupoOcu(Request $request)
    {
        $id = $request->id;
        $grupoOcu = DB::select("SELECT * FROM grupo_ocupacional WHERE id_grupo =".$id);
        return $grupoOcu;
    }
}
