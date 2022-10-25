<?php

namespace App\Http\Controllers;

use App\Models\Ingresos_egresos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ingregresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        for ($a = 0; $a < count($request->ingresose); $a++) {
            if ($request->ingresose[$a]["decripcion"] != null) {
                $select=DB::select("SELECT decripcion from ingresos_egresos where decripcion="."'".$request->ingresose[$a]["decripcion"]."'"." and id_empresa=".$request->id_empresa." and id_departamento=".$request->departamento);
                $select1=DB::select("SELECT id_plan_cuentas from plan_cuentas where nomcta="."'".$request->ingresose[$a]["id_plan_cuentas_1"]."'");
                if($select){
                    return "existe";
                }else{
                    $ingresos = new Ingresos_egresos();
                    $ingresos->decripcion = $request->ingresose[$a]["decripcion"];
                    $ingresos->tipo = $request->ingresose[$a]["tipo"];
                    if($request->ingresose[$a]["tipo"]==="Ingreso"){
                        if(isset($request->ingresose[$a]["iess"])){
                            $ingresos->iess = $request->ingresose[$a]["iess"];
                        }
                    }
                    $ingresos->id_empresa = $request->id_empresa;
                    $ingresos->id_plan_cuentas_1 = $request->ingresose[$a]["cod_cuenta_1"];
                    $ingresos->id_plan_cuentas_2 = $request->ingresose[$a]["cod_cuenta_2"];
                    $ingresos->id_departamento =$request->departamento;
                    
                    $ingresos->save();
                }
            }
        }
        return $ingresos;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listar(Request $request, $id)
    {
        //
       /* $id = $request->id;
        $ingresos = DB::select("SELECT e.id_ineg,e.id_empresa,e.decripcion,e.tipo,(select codcta from plan_cuentas where id_plan_cuentas=e.id_plan_cuentas_2)as id_plan_cuentas_2,(select codcta from plan_cuentas where id_plan_cuentas=e.id_plan_cuentas_1)as id_plan_cuentas_1
        FROM ingresos_egresos e 
        ");
        return ['recupera' => $ingresos];*/
        $id = $request->id;
        $ingresos = DB::select("SELECT e.id_ineg,e.id_empresa,e.decripcion,e.tipo,e.id_plan_cuentas_1,e.id_plan_cuentas_2,d.dep_nombre,e.id_departamento
        FROM ingresos_egresos e 
        INNER JOIN departamento d ON e.id_departamento=d.id_departamento where e.id_empresa=  '$id' GROUP BY d.dep_nombre");
        return ['recupera' => $ingresos];

    }
    public function listarin(Request $request){

        $id =$request->id;
        $in =DB::select("SELECT *,false as contador from ingresos_egresos where id_empresa ='$id' order  BY tipo desc");
        return $in;
 
    }
    public function veringresos(Request $request)
    { 
        $id = $request->id;
        //$veringre =DB::select('SELECT * FROM ingresos_egresos WHERE id_ineg='.$id);
        $veringre =DB::select('SELECT e.id_ineg,e.id_departamento,e.iess,e.decripcion,e.tipo,e.id_empresa,e.id_plan_cuentas_1 as cod_cuenta_1,e.id_plan_cuentas_2 as cod_cuenta_2,(select nomcta from plan_cuentas where id_plan_cuentas=e.id_plan_cuentas_2)as id_plan_cuentas_2,(select nomcta from plan_cuentas where id_plan_cuentas=e.id_plan_cuentas_1)as id_plan_cuentas_1,d.dep_nombre 
        FROM ingresos_egresos as e,departamento as d where e.id_departamento=d.id_departamento and e.id_departamento='.$id);
        //return ['recuperas' => $veringre];
        return $veringre;
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
    public function update(Request $request)
    {
        //    
        $id = $request->id;
    /*    $veringre =DB::select('SELECT e.decripcion,e.tipo,e.id_plan_cuentas_1,e.id_plan_cuentas_2,d.dep_nombre 
        FROM ingresos_egresos e 
        INNER JOIN departamento d ON e.id_departamento=d.id_departamento where e.id_departamento='.$id);*/

        for ($a = 0; $a < count($request->ingresose); $a++) {
            $select=DB::select("SELECT id_plan_cuentas from plan_cuentas where codcta="."'".$request->ingresose[$a]["id_plan_cuentas_2"]."'");
                $select1=DB::select("SELECT id_plan_cuentas from plan_cuentas where codcta="."'".$request->ingresose[$a]["id_plan_cuentas_1"]."'");
            if ($request->ingresose[$a]["id_ineg"] != null) {
                
                $ingresos = Ingresos_egresos::find($request->ingresose[$a]["id_ineg"]);
                $ingresos->decripcion = $request->ingresose[$a]["decripcion"];
                $ingresos->tipo = $request->ingresose[$a]["tipo"];
                $ingresos->iess = $request->ingresose[$a]["iess"];
                $ingresos->id_empresa = $request->id_empresa;
                
                $ingresos->id_plan_cuentas_1 = $request->ingresose[$a]["cod_cuenta_1"];
                $ingresos->id_plan_cuentas_2 = $request->ingresose[$a]["cod_cuenta_2"];
                $ingresos->id_departamento = $request->ingresose[$a]["id_departamento"];
                $ingresos->save();
                //$ingresos->id_plan_cuentas_2 = $request->ingresose[$a]["id_plan_cuentas_2"];
            
                //$ingresos->save();
            }
            if($request->ingresose[$a]["id_ineg"] === null){
                $ingresos = new Ingresos_egresos();
                $ingresos->decripcion = $request->ingresose[$a]["decripcion"];
                $ingresos->tipo = $request->ingresose[$a]["tipo"];
                $ingresos->iess = $request->ingresose[$a]["iess"];
                $ingresos->id_empresa = $request->id_empresa;
                $ingresos->id_departamento = $request->departamento;
                $ingresos->id_plan_cuentas_1 = $request->ingresose[$a]["cod_cuenta_1"];
                $ingresos->id_plan_cuentas_2 = $request->ingresose[$a]["cod_cuenta_2"];
                $ingresos->id_departamento = $request->departamento;
                
                $ingresos->save();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ingresos_egresos::destroy($id);
    }
    public function eliminartodo($id)
    {
        Ingresos_egresos::where('id_departamento',$id)->delete();
    }
}
