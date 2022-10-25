<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TablaInteres;
use App\Models\TablaInteresAnual;
use App\Models\Plancuenta;

class TablaInteresController extends Controller
{
    //listar tabla interes
    public function index(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar == '') { 
            $recupera = TablaInteres::select("*")
             ->where("id_empresa", "=", $id)
             ->WhereNull("delete")
            ->orderByRaw('codigo_periodo ASC')->get();
        } else {
            $recupera = TablaInteres::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo_periodo', '=', $buscar);
                })
                 ->where("id_empresa", "=", $id)
                 ->WhereNull("delete")
                ->orderByRaw('codigo_periodo ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    //guardar registros
    public function store(Request $request){
        $cod_per=DB::select("SELECT max(codigo_periodo) as codigo_periodo from tabla_interes where id_empresa={$request->id_empresa}");
        $cod=1;
        if(count($cod_per)>0){
            $cod=$cod_per[0]->codigo_periodo+1;
        }
        $int=new TablaInteres();
        $int->codigo_periodo=$cod;
        $int->interes=$request->interes;
        $int->ucrea=$request->ucrea;
        $int->id_empresa=$request->id_empresa;
        $int->save();
    }
    //actualizar registros
    public function update(Request $request){
        $int=TablaInteres::find($request->id);
        $int->interes=$request->interes;
        $int->umodifica=$request->umodifica;
        $int->id_empresa=$request->id_empresa;
        $int->save();
    }
    //eliminar tabla interes de la vista
    public function delete($id,$user){
        TablaInteres::where("id_tabla_interes","=",$id)->update(['delete'=>1,'udelete'=>$user]);
    }

    ////////////////////////////// Interes Anual
    //listar tabla interes
    public function index_anual(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar == '') { 
            // $recupera = TablaInteresAnual::select("*")
            // ->addSelect([
            //     "nombre_cuenta" => Plancuenta::select(DB::raw("CONCAT('codcta','-','nomcta') AS display_name"))->pluck('display_name')
            //         ->whereColumn('id_plan_cuentas', 'tabla_interes_anual.id_plan_cuentas'),
            // ])
            //  ->where("id_empresa", "=", $id)
            //  ->WhereNull("delete")
            // ->orderByRaw('id_tabla_interes_anual DESC')->get();
            $recupera =DB::select("SELECT tabla_interes_anual.*,(select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=tabla_interes_anual.id_plan_cuentas limit 1) as nombre_cuenta from tabla_interes_anual where id_empresa={$id} and tabla_interes_anual.delete is null");
        } else {
            // $recupera = TablaInteresAnual::select('*')
            //     ->where(function ($q) use ($buscar) {
            //         $q->where('periodo_pago', '=', $buscar);
            //     })
            //      ->where("id_empresa", "=", $id)
            //      ->WhereNull("delete")
            //     ->orderByRaw('id_tabla_interes_anual DESC')->get();
            $recupera =DB::select("SELECT tabla_interes_anual.*,(select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=tabla_interes_anual.id_plan_cuentas limit 1) as nombre_cuenta from tabla_interes_anual where id_empresa={$id} and (periodo_pago like '%{$buscar}%' or interes_anual like '%{$buscar}%') and tabla_interes_anual.delete is null");
        }
        return [
            'recupera' => $recupera
        ];
    }
    //guardar registros 
    public function store_anual(Request $request){
        $int=new TablaInteresAnual();
        $int->interes_anual=$request->interes_anual;
        $int->periodo_pago=$request->periodo_pago;
        $int->tiempo_pago=$request->tiempo_pago;
        $int->ucrea=$request->ucrea;
        $int->id_empresa=$request->id_empresa;
        $int->id_plan_cuentas=$request->id_plan_cuentas;
        $int->save();
    }
    //actualizar registros
    public function update_anual(Request $request){
        $int=TablaInteresAnual::find($request->id);
        $int->interes_anual=$request->interes_anual;
        $int->periodo_pago=$request->periodo_pago;
        $int->tiempo_pago=$request->tiempo_pago;
        $int->umodifica=$request->umodifica;
        $int->id_empresa=$request->id_empresa;
        $int->id_plan_cuentas=$request->id_plan_cuentas;
        $int->save();
    }
    //eliminar tabla interes de la vista
    public function delete_anual($id,$user){
        TablaInteresAnual::where("id_tabla_interes_anual","=",$id)->update(['delete'=>1,'udelete'=>$user]);
    }
}
