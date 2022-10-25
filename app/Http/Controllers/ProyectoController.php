<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
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
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Proyecto::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_proyecto DESC')->get();
        } else {
            $recupera = Proyecto::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo', 'like', "'%$buscar%'")
                        ->where('descripcion', 'like', "'%$buscar%'")
                        ->orWhere('ubicacion', 'like', "'%$buscar%'");
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_proyecto DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function getProyecto(Request $request, $id)
    {
        //
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Proyecto::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_proyecto ASC')->get();
        } else {
            $recupera = Proyecto::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo', 'like', "'%$buscar%'")
                        ->where('descripcion', 'like', "'%$buscar%'")
                        ->orWhere('ubicacion', 'like', "'%$buscar%'");
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_proyecto ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }





    public function eliminar($id)
    {

        DB::delete("DELETE FROM proyecto WHERE id_proyecto = " . $id);
        Proyecto::destroy($id);
    }

    public function store(Request $request)
    {
        $select= DB::select("SELECT  codigo from proyecto where id_empresa=".$request->id_empresa." order by id_proyecto DESC limit 1");
        $codigo=0;
        if($select){
            if($select[0]->codigo<10){
                $r=$select[0]->codigo+1;
                $codigo="0".$r;
            }else{
                $r=$select[0]->codigo+1;
                $codigo=$r;
            }
        }else{
            $codigo="01";
        }
        
        
            $tipo = new Proyecto();
            $tipo->codigo = $codigo;
            $tipo->descripcion = $request->descripcion;
            $tipo->ubicacion = $request->ubicacion;
            $tipo->id_empresa = $request->id_empresa;
            $tipo->save();
        
        
    }

    public function editar(Request $request)
    {
        $select1= DB::select("SELECT  codigo from proyecto where  id_proyecto=".$request->id);
        $select2= DB::select("SELECT count(codigo) as codigo from proyecto where codigo='".$request->codigo."' and id_empresa=".$request->id_empresa);
        if($request->codigo===$select1[0]->codigo){
            $tipo = Proyecto::findOrFail($request->id);
            $tipo->codigo = $request->codigo;
            $tipo->descripcion = $request->descripcion;
            $tipo->ubicacion = $request->ubicacion;
            $tipo->id_empresa = $request->id_empresa;
            $tipo->save();
        }else{
            if($select2[0]->codigo>=1){
                return "existe";
            }else{
                $tipo = Proyecto::findOrFail($request->id);
                $tipo->codigo = $request->codigo;
                $tipo->descripcion = $request->descripcion;
                $tipo->ubicacion = $request->ubicacion;
                $tipo->id_empresa = $request->id_empresa;
                $tipo->save();
            }
        }
    }
    public function abrir($id)
    {
        $recupera =  DB::select("SELECT * FROM proyecto where id_proyecto=" . $id);
        return $recupera;
    }

    public function listproy(Request $request)
    {
        $idlp = $request->id;
        $tipo = Proyecto::select("*")->where("id_empresa", "=", $request->id)->get();
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
