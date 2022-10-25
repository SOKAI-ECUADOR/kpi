<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrupoProveedor;
use App\Models\Plancuenta;

class GrupoProveedorController extends Controller
{
    //
    public function index(Request $request,$id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = GrupoProveedor::addSelect(['cta_contable' => Plancuenta::select('codcta')
            ->whereColumn('id_plan_cuentas', 'grupo_proveedor.id_plan_cuentas'),
            'cta_descuento' => Plancuenta::select('codcta')
            ->whereColumn('id_plan_cuentas', 'grupo_proveedor.id_plan_cuentas_descuento'),
            'cta_anticipo' => Plancuenta::select('codcta')
            ->whereColumn('id_plan_cuentas', 'grupo_proveedor.id_plan_cuentas_anticipo')
            ])
            ->where('id_empresa', '=', $id)
            ->get();
        } else {
            $recupera = GrupoProveedor::addSelect(['cta_contable' => Plancuenta::select('codcta')
            ->whereColumn('id_plan_cuentas', 'grupo_proveedor.id_plan_cuentas'),
            'cta_descuento' => Plancuenta::select('codcta')
            ->whereColumn('id_plan_cuentas', 'grupo_proveedor.id_plan_cuentas_descuento'),
            'cta_anticipo' => Plancuenta::select('codcta')
            ->whereColumn('id_plan_cuentas', 'grupo_proveedor.id_plan_cuentas_anticipo')
            ])
                ->where(function($q) use ($buscar){
                    $q->where('nombre_grupoprov', 'like', '%' . $buscar . '%')
                    ->orWhere('cod_grupoprov', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_grupoprov DESC')->get();
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
        /*$select1 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_contable'");
        $select2 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_descuento'");
        $select3 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_anticipo'");
        if(!$select1 || !$select2 && !$select3){
            return "existe";
        }*/
        $cod= DB::select('SELECT count(cod_grupoprov) as conteo FROM `grupo_proveedor` WHERE cod_grupoprov = '."'$request->cod_grupoprov'".' and id_empresa ='.$request->id_empresa);
       
        // if($cod[0]->conteo>=1){
        //     return "existe";
        // }else{
            $grupo =new GrupoProveedor();
            $grupo->cod_grupoprov=$request->cod_grupoprov;
            $grupo->nombre_grupoprov=$request->nombre_grupoprov;
            $grupo->importador=$request->importador;
            $grupo->id_plan_cuentas=$request->id_cta_contable;
            $grupo->id_plan_cuentas_descuento=$request->id_cta_descuento;
            $grupo->id_plan_cuentas_anticipo=$request->id_cta_anticipo;
            /*$grupo->cta_contable=$request->cta_contable;
            $grupo->cta_descuento=$request->cta_descuento;
            $grupo->cta_anticipo=$request->cta_anticipo;*/
            $grupo->id_empresa=$request->id_empresa;
            $grupo->save();
        //}
        
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
        
        $select1 = DB::select('SELECT cod_grupoprov FROM `grupo_proveedor` WHERE id_grupoprov = '.$request->id);
        $cod= DB::select('SELECT cod_grupoprov FROM `grupo_proveedor` WHERE cod_grupoprov = '."'$request->cod_grupoprov'".' and id_empresa ='.$request->id_empresa);
        $var=count($cod);
        $grupo =GrupoProveedor::findOrFail($request->id);
        $grupo->cod_grupoprov=$request->cod_grupoprov;
        $grupo->nombre_grupoprov=$request->nombre_grupoprov;
        $grupo->importador=$request->importador;
        $grupo->id_plan_cuentas=$request->id_cta_contable;
        $grupo->id_plan_cuentas_descuento=$request->id_cta_descuento;
        $grupo->id_plan_cuentas_anticipo=$request->id_cta_anticipo;
        /*$grupo->cta_contable=$request->cta_contable;
        $grupo->cta_descuento=$request->cta_descuento;
        $grupo->cta_anticipo=$request->cta_anticipo;*/
        $grupo->id_empresa=$request->id_empresa;
        //$grupo->save();
        //if($var===$request->cod_grupoprov){
            $grupo->save();
        // }else{
        //     if($var>=1){
        //         return "existe";
        //     }else{
        //         $grupo->save();
                
        //     }
        // }
    } 

    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT grp.*,(select p.nomcta from plan_cuentas as p ,grupo_proveedor as grp where p.id_plan_cuentas=grp.id_plan_cuentas and grp.id_grupoprov='.$id.') as cta_contable,(select p.nomcta from plan_cuentas as p ,grupo_proveedor as grp where p.id_plan_cuentas=grp.id_plan_cuentas_descuento and grp.id_grupoprov='.$id.') as cta_descuento,(select p.nomcta from plan_cuentas as p ,grupo_proveedor as grp where p.id_plan_cuentas=grp.id_plan_cuentas_anticipo and grp.id_grupoprov='.$id.') as cta_anticipo FROM `grupo_proveedor` as grp WHERE grp.id_grupoprov='.$id);
        return $recupera;
    }
    public function eliminar ($id){
        GrupoProveedor::destroy($id);
    }
}
