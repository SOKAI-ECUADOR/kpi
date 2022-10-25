<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tipocomprobante;

class TipocomprobanteController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id=$request->id_empresa;
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Tipocomprobante::where("id_empresa",$id)->orderBy("id_tipcomprobante", "ASC")->get(); 
        }else{
            $recupera = Tipocomprobante::select("*")
                        ->where(function($q) use ($buscar){
                            $q->orWhere('cod_tipcomprob', 'like', '%' . $buscar . '%')
                            ->orWhere('descrip_tipcomprob', 'like', '%' . $buscar . '%');
                        })->where("id_empresa",$id)->get();
                        
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
        $tipo =new Tipocomprobante();
        $tipo->cod_tipcomprob=$request->cod_tipcomprob;
        $tipo->descrip_tipcomprob=$request->descrip_tipcomprob;
        $tipo->id_empresa=$request->id_empresa;
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
        $tipo =Tipocomprobante::findOrFail($request->id);
        $tipo->cod_tipcomprob=$request->cod_tipcomprob;
        $tipo->descrip_tipcomprob=$request->descrip_tipcomprob;
        //$tipo->venta_tipcomprob=$request->venta_tipcomprob;
        $tipo->id_empresa=$request->id_empresa;
        $tipo->save();
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `tipo_comprobante` WHERE id_tipcomprobante='.$id);
        return $recupera;
    }
    public function eliminar ($id){
        Tipocomprobante::destroy($id);
      }
}
