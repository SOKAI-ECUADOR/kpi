<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PlanSeguro;
use App\Models\PlanSeguroDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;






class PlanSeguroController extends Controller
{
    public function index($id,Request $request)
    {
        //lista todos los plan segufuro activo de la empresa
        $buscar = $request->buscar;
        if ($buscar == '') { 
            $recupera = PlanSeguro::select("plan_seguro.id_plan_seguro","plan_seguro.nombre","plan_seguro.descuento","seguro.nombre as nombre_seguro")
            ->join('seguro','seguro.id_seguro','=','plan_seguro.id_seguro')
            ->where('seguro.id_empresa',"=",$id)
            ->where('plan_seguro.estado',"=",'Activo')
            ->orderByRaw('id_plan_seguro DESC')->get();
        } else {
            //lista todos los plan seguguro activo de la empresa con la busqueda del nombre
            $recupera = PlanSeguro::select("plan_seguro.id_plan_seguro","plan_seguro.nombre","plan_seguro.descuento","seguro.nombre as nombre_seguro")
                ->join('seguro','seguro.id_seguro','=','plan_seguro.id_seguro')
                ->where(function ($q) use ($buscar) {
                    $q->where('plan_seguro.nombre', 'like', '%' . $buscar . '%');
                })
                ->where('seguro.id_empresa',"=",$id)
                ->where('plan_seguro.estado',"=",'Activo')
                ->orderByRaw('id_plan_seguro DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function productos($id){
        //trae solo los productos con categoria de medicamentos
        $recupera=DB::select("SELECT id_producto,cod_principal,descripcion,nombre,null as agregado from producto where id_empresa={$id} and sector=1 and categoria='Medicamentos' order by id_producto desc");
        return $recupera;
    }
    public function guardar(Request $request){
        //se amplia el tiempo de ejecucion segundos
        ini_set('max_execution_time', 1050);
        //guarda plan seguro cabecera
        $pl=new PlanSeguro();
        $pl->nombre=$request->nombre;
        $pl->descuento=$request->descuento;
        $pl->estado='Activo';
        $pl->ucrea=$request->ucrea;
        $pl->id_seguro=$request->seguro;
        $pl->save();
        $id_pl=$pl->id_plan_seguro;
        //guarda los productos de planes
        for($i=0;$i<count($request->productos);$i++){
            $pldt=new PlanSeguroDetalle();
            $pldt->agregado=$request->productos[$i]["agregado"];
            $pldt->id_producto=$request->productos[$i]["id_producto"];
            $pldt->id_plan_seguro=$id_pl;
            $pldt->save();
        }
        
    }
    public function recuperar($id){
        //recupera toda el encabezado de plan seguro
        $recupera=DB::select("SELECT * FROM plan_seguro where id_plan_seguro={$id}");
        return $recupera;
    }
    public function productos_editar($id,Request $request){
        ini_set('max_execution_time', 1050);
        //trae los productos guardado en el plan seguro y trae productos nuevos que sean medicamentos
        $recupera=DB::select("SELECT producto.id_producto,cod_principal,descripcion,nombre,agregado,id_plan_seguro_detalle from plan_seguro_detalle
                            INNER JOIN producto
                            ON producto.id_producto=plan_seguro_detalle.id_producto
                                where plan_seguro_detalle.id_plan_seguro=$id
        UNION
        SELECT t1.id_producto,cod_principal,descripcion,nombre,null as agregado,null as id_plan_seguro_detalle
         FROM producto t1
         WHERE NOT EXISTS (SELECT NULL
                             FROM plan_seguro_detalle t2
                            WHERE t2.id_producto = t1.id_producto and t2.id_plan_seguro=$id) and id_empresa={$request->empresa} and sector=1 and categoria='Medicamentos'
                                                ORDER BY id_producto desc");
        return $recupera;
    }
    public function editar(Request $request){
        //se amplia el tiempo de ejecucion segundos
        ini_set('max_execution_time', 1050);
        //guarda plan seguro cabecera
        $pl=PlanSeguro::find($request->id);
        $pl->nombre=$request->nombre;
        $pl->descuento=$request->descuento;
        $pl->estado='Activo';
        $pl->umodifica=$request->umodifica;
        $pl->id_seguro=$request->seguro;
        $pl->save();
        $id_pl=$request->id;
        //guarda los productos de planes
        for($i=0;$i<count($request->productos);$i++){
            if($request->productos[$i]["id_plan_seguro_detalle"]!==null){
                $pldt=PlanSeguroDetalle::find($request->productos[$i]["id_plan_seguro_detalle"]);
                $pldt->agregado=$request->productos[$i]["agregado"];
                $pldt->id_producto=$request->productos[$i]["id_producto"];
                $pldt->id_plan_seguro=$id_pl;
                $pldt->save();
            }else{
                $pldt=new PlanSeguroDetalle();
                $pldt->agregado=$request->productos[$i]["agregado"];
                $pldt->id_producto=$request->productos[$i]["id_producto"];
                $pldt->id_plan_seguro=$id_pl;
                $pldt->save();
            }
        }
        
    }
    public function delete($id){
        // actualiza para que en la vista nopueda ver ese registro
        $seg=PlanSeguro::find($id);
        $seg->estado='Inactivo';
        $seg->save();
    }
    public function list_plan_seguro($id)
    {
            $recupera = PlanSeguro::select("*")
            ->where('plan_seguro.id_seguro',"=",$id)
            ->orderByRaw('id_plan_seguro DESC')->get();
        return [
            'recupera' => $recupera
        ];
    }
}
