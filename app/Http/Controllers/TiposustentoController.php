<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tiposustento;

class TiposustentoController extends Controller
{
    //
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Tiposustento::where("id_empresa",$id)->orderBy("id_sustento", "ASC")->get(); 
        }else{
            $recupera = Tiposustento::select("*")
                ->where(function($q) use ($buscar){
                    $q->where('cod_sustento', 'like','%'.$buscar.'%') 
                    ->orWhere('descrip_sustento', 'like','%'.$buscar.'%');
                })->where('id_empresa','=',$id)->get();
            //->where('id_empresa','=',$id)->orderBy("id_sustento", "DESC")->get();
        } 
        return [ 
            'recupera' => $recupera
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $tipo =new Tiposustento();
        $tipo->cod_sustento=$request->cod_sustento;
        $tipo->descrip_sustento=$request->descrip_sustento;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();
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
        $tipo =Tiposustento::findOrFail($request->id);
        $tipo->cod_sustento=$request->cod_sustento;
        $tipo->descrip_sustento=$request->descrip_sustento;
        //$tipo->id_empresa = $request->id_empresa;
        $tipo->save();
    }
    public function abrir(Request $request)
    {
        /*Destination::addSelect(['last_flight' => Flight::select('name')
        ->whereColumn('destination_id', 'destinations.id')
        ->orderBy('arrived_at', 'desc')
        ->limit(1)*/

        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `tipo_sustento` WHERE id_sustento='.$id);
        return $recupera;
    }
    public function eliminar ($id){
        Tiposustento::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }
}
