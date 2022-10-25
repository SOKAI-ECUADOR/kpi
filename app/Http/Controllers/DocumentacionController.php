<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Documentacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentacionController extends Controller
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
            $recupera = Documentacion::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_documento DESC')->get();
        } else {
            $recupera = Documentacion::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('doc_descripcion', 'like', '%' . $buscar . '%')
                    ->orWhere('doc_estatus', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_documento DESC')->get();
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
        $documento = new Documentacion();
        $documento->doc_descripcion = $request->doc_descripcion;
        $documento->doc_estatus = "Activo";
        $documento->id_empresa = $request->id_empresa;
        $documento->save();
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
        $documento = Documentacion::find($request->id);
        $documento->doc_descripcion = $request->doc_descripcion;
        $documento->doc_estatus = $request->doc_estatus;
        $documento->id_empresa = $request->id_empresa;
        $documento->save();
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
        Documentacion::destroy($id);
    }

    public function verDocumento(Request $request)
    {
        $id = $request->id;
        $documento = DB::select("SELECT * FROM documentacion WHERE id_documento =".$id);
        return $documento;
    }
}
