<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retencion;
use Illuminate\Support\Facades\DB;
use App\Models\Moneda;
use App\Models\Impuesto;
use App\Models\Plancuenta;

class RetencionController extends Controller
{
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
            $recupera = Retencion::select('*')->addSelect(['cod_retencion' => Impuesto::select('cod_imp')
                        ->whereColumn('id_imp', 'retencion.id_impuesto'),
                    ])->addSelect(['nomcta' => Plancuenta::select('nomcta')
                        ->whereColumn('id_plan_cuentas', 'retencion.id_plan_cuentas'),
                    ])
                    ->where("id_empresa",$id)
                    ->orderByRaw('retencion.id_retencion DESC')->get();
        }else{
            $recupera = Retencion::select('*')->addSelect(['cod_retencion' => Impuesto::select('cod_imp')
                        ->whereColumn('id_imp', 'retencion.id_impuesto'),
                    ])->addSelect(['nomcta' => Plancuenta::select('nomcta')
                        ->whereColumn('id_plan_cuentas', 'retencion.id_plan_cuentas'),
                    ])
                    ->where(function($q) use ($buscar){
                        $q->orWhere('cod_retencion', 'like', '%' . $buscar . '%')
                        ->orWhere('descrip_retencion', 'like', '%' . $buscar . '%')
                        ->orWhere('tipo_retencion', 'like', '%' . $buscar . '%')
                        ->orWhere('porcen_retencion', 'like', '%' . $buscar . '%');
                    })->where("id_empresa",$id)
                    ->orderByRaw('retencion.id_retencion DESC')->get();
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
     * Retencion
     */
    public function store(Request $request)
    {
        $nro="1";
        $select=DB::select('SELECT codcta from plan_cuentas where codcta='."'$request->cta_contable'");
        $select2= DB::select("SELECT count(cod_retencion) as codigo from retencion where cod_retencion='".$request->cod_retencion."' and id_empresa=".$request->id_empresa);
        $nro_ret=DB::select("SELECT cod_retencion from retencion where id_empresa={$request->id_empresa} ORDER BY id_retencion desc limit 1");
        if($nro_ret){
            if($nro_ret[0]->cod_retencion!==null || $nro_ret[0]->cod_retencion!==null){
                $nro=$nro_ret[0]->cod_retencion+1;
            }
            
        }
        $retencion=new Retencion();
        $retencion->cod_retencion=$nro;
        $retencion->descrip_retencion=$request->descrip_retencion;
        $retencion->porcen_retencion=$request->porcen_retencion;
        $retencion->tipo_retencion=$request->tipo_retencion;
        $retencion->tipoiva_retencion=$request->tipoiva_retencion;
        $retencion->id_plan_cuentas=$request->id_cuenta;
        $retencion->id_moneda=$request->id_moneda;
        $retencion->id_impuesto=$request->id_impuesto;
        $retencion->id_empresa=$request->id_empresa;
        $retencion->save();
        /*if($select2[0]->codigo>=1){
            return "existe";
        }else{
            if($request->cta_contable==null){
                $retencion->save();
                return "vacio";
            }else{
                if(!$select){
                 return "mal";
                }else{
                 $retencion->save();
                 return "bien";
                }
            }
        }*/

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
        $retencion=Retencion::findOrFail($request->id);
        //$retencion->cod_retencion=$request->cod_retencion;
        $retencion->descrip_retencion=$request->descrip_retencion;
        $retencion->porcen_retencion=$request->porcen_retencion;
        $retencion->tipo_retencion=$request->tipo_retencion;
        $retencion->tipoiva_retencion=$request->tipoiva_retencion;
        $retencion->id_plan_cuentas=$request->id_cuenta;
        $retencion->id_moneda=$request->id_moneda;
        $retencion->id_impuesto=$request->id_impuesto;
        $retencion->id_empresa=$request->id_empresa;
        $retencion->save();
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        //$recupera = DB::select('SELECT * FROM `retencion` WHERE id_retencion='.$id);
        $recupera = Retencion::addSelect(['cuenta_resultado' => Plancuenta::select('nomcta')
        ->whereColumn('id_plan_cuentas', 'retencion.id_plan_cuentas')
        ])
        ->where('id_retencion', '=', $id)
        ->get();
        return $recupera;
    }
    public function eliminar ($id){
        Retencion::destroy($id);  
    }
    public function getMoneda(){
        $data=Moneda::all();
        return $data;
    }
    public function getImpuesto(Request $request){
        $id=$request->porcen_imp;
        $recupera = DB::select('SELECT * FROM `impuesto` WHERE porcen_imp='.$id.' and id_empresa='.$request->id_empresa);
        return $recupera;
    }
    public function clave($id)
    {
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , if(pe.secuencial_retencion<=1 || pe.secuencial_retencion is NULL,1,pe.secuencial_retencion) as numeral FROM user u INNER JOIN empresa e on e.id_empresa=u.id_empresa INNER JOIN establecimiento es on es.id_empresa=e.id_empresa INNER JOIN punto_emision pe on pe.id_empresa=e.id_empresa WHERE u.id = " . $id);
        $valor = $respuesta[0]->numeral; 
        return [
            'secuencial' => $valor,
            'recupera' => $respuesta
        ];
    }
}
