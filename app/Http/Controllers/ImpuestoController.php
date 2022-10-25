<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Impuesto;

class ImpuestoController extends Controller
{
    public function index(Request $request)
    {
        /*$empresa = Emisor::all();
        return $empresa;*/
        //if (!$request->ajax()) return redirect('/');
        $id=$request->id_empresa;
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Impuesto::where("id_empresa",$id)->orderBy("cod_imp", "ASC")->orderBy("tipo_imp", "ASC")->get(); 
        }else{
            $recupera = Impuesto::select("*")
                        ->where(function($q) use ($buscar){
                            $q->where('descrip_imp', 'like','%'.$buscar.'%') 
                            ->orWhere('tipo_imp', 'like','%'.$buscar.'%')
                            ->orWhere('cod_imp', 'like','%'.$buscar.'%'); 
                        })->where('id_empresa', '=', $id)
                        ->orderBy("cod_imp", "ASC")->orderBy("tipo_imp", "ASC")->get();
                        //->orderBy("id_imp", "DESC")->get();
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
        $impuesto=new Impuesto();
        $impuesto->cod_imp=$request->cod_imp;
        $impuesto->descrip_imp=$request->descrip_imp;
        $impuesto->tipo_imp=$request->tipo_imp;
        $impuesto->porcen_imp=$request->porcen_imp;
        $impuesto->id_empresa=$request->id_empresa;
        $impuesto->save();

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
        $impuesto=Impuesto::findOrFail($request->id);
        $impuesto->cod_imp=$request->cod_imp;
        $impuesto->descrip_imp=$request->descrip_imp;
        $impuesto->tipo_imp=$request->tipo_imp;
        $impuesto->porcen_imp=$request->porcen_imp;
        $impuesto->id_empresa=$request->id_empresa;
        $impuesto->save();
    }
    public function abrir(Request $request)
    {
        /*Destination::addSelect(['last_flight' => Flight::select('name')
        ->whereColumn('destination_id', 'destinations.id')
        ->orderBy('arrived_at', 'desc')
        ->limit(1)*/

        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `impuesto` WHERE id_imp='.$id);
        return $recupera;
    }
    public function eliminar ($id){
        Impuesto::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }

}
