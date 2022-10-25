<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametrizacion;
use App\Models\Plancuenta;
use App\Models\Moneda;
use App\Models\Grupo;

class ParametrizacionController extends Controller
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
            if ($request->ingresose[$a]["descripcion"] != null) {
                $select=DB::select("SELECT descripcion from parametrizacion where descripcion="."'".$request->ingresose[$a]["descripcion"]."'"." and id_departamento=".$request->departamento);
                $select1=DB::select("SELECT id_plan_cuentas from plan_cuentas where nomcta="."'".$request->ingresose[$a]["id_plan_cuentas_1"]."'");
                if($select){
                    return "existe";
                }else{
                    $ingresos = new Parametrizacion();
                    $ingresos->descripcion = $request->ingresose[$a]["descripcion"];
                    $ingresos->valor_decimo_cuarto = $request->ingresose[$a]["valor_decimo_cuarto"];
                    $ingresos->id_plan_cuentas1 = $request->ingresose[$a]["cod_cuenta_1"];
                    $ingresos->id_plan_cuentas2 = $request->ingresose[$a]["cod_cuenta_2"];
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
        $ingresos = DB::select("SELECT distinct parametrizacion.id_departamento,departamento.dep_nombre from parametrizacion,departamento,empresa where parametrizacion.id_departamento=departamento.id_departamento and departamento.id_empresa=".$id);
        return ['recupera' => $ingresos];

    }
    public function listarin(Request $request){

        $id =$request->id;
        $in =DB::select("SELECT * from ingresos_egresos where id_empresa ='$id' order  BY tipo desc");
        return $in;
 
    }
    public function veringresos(Request $request)
    { 
        $id = $request->id;
        //$veringre =DB::select('SELECT * FROM ingresos_egresos WHERE id_ineg='.$id);
        $veringre =DB::select('SELECT e.id_parametrizacion,e.id_departamento,e.descripcion,e.valor_decimo_cuarto,e.id_plan_cuentas1 as cod_cuenta_1,e.id_plan_cuentas2 as cod_cuenta_2,(select nomcta from plan_cuentas where id_plan_cuentas=e.id_plan_cuentas2)as id_plan_cuentas_2,(select nomcta from plan_cuentas where id_plan_cuentas=e.id_plan_cuentas1)as id_plan_cuentas_1,d.dep_nombre 
        FROM parametrizacion as e,departamento as d where e.id_departamento=d.id_departamento and e.id_departamento='.$id);
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
            //$select=DB::select("SELECT id_plan_cuentas from plan_cuentas where codcta="."'".$request->ingresose[$a]["id_plan_cuentas_2"]."'");
              //  $select1=DB::select("SELECT id_plan_cuentas from plan_cuentas where codcta="."'".$request->ingresose[$a]["id_plan_cuentas_1"]."'");
            if ($request->ingresose[$a]["id_parametrizacion"] != null) {
                
                $ingresos = Parametrizacion::find($request->ingresose[$a]["id_parametrizacion"]);
                $ingresos->descripcion = $request->ingresose[$a]["descripcion"];
                    $ingresos->valor_decimo_cuarto = $request->ingresose[$a]["valor_decimo_cuarto"];
                    $ingresos->id_plan_cuentas1 = $request->ingresose[$a]["cod_cuenta_1"];
                    $ingresos->id_plan_cuentas2 = $request->ingresose[$a]["cod_cuenta_2"];
                    $ingresos->id_departamento =$request->departamento;
                //$ingresos->id_plan_cuentas_2 = $request->ingresose[$a]["id_plan_cuentas_2"];
            
                $ingresos->save();
            }
            if($request->ingresose[$a]["id_parametrizacion"] === null){
                $ingresos = new Parametrizacion();
                $ingresos->descripcion = $request->ingresose[$a]["descripcion"];
                $ingresos->valor_decimo_cuarto = $request->ingresose[$a]["valor_decimo_cuarto"];
                $ingresos->id_plan_cuentas1 = $request->ingresose[$a]["cod_cuenta_1"];
                $ingresos->id_plan_cuentas2 = $request->ingresose[$a]["cod_cuenta_2"];
                $ingresos->id_departamento =$request->departamento;
                
                $ingresos->save();
            }
        }
    }
    public function ObtenerPlanCuentas(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Plancuenta::select('plan_cuentas.*')
                ->addSelect([
                    'nomb_moneda' => Moneda::select('nomb_moneda')
                        ->whereColumn('id_moneda', 'plan_cuentas.id_moneda'),
                    'nomb_grupo' => Grupo::select('nomb_grupo')
                        ->whereColumn('id_grupo', 'plan_cuentas.id_grupo')
                ])
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->where("plan_cuentas.id_grupo", "=",2)
                ->orderByRaw('plan_cuentas.codcta ASC')->get();
        } else {
            $recupera = Plancuenta::select('plan_cuentas.*')
                ->addSelect([
                    'nomb_moneda' => Moneda::select('nomb_moneda')
                        ->whereColumn('id_moneda', 'plan_cuentas.id_moneda'),
                    'nomb_grupo' => Grupo::select('nomb_grupo')
                        ->whereColumn('id_grupo', 'plan_cuentas.id_grupo')
                ])
                ->where(function ($q) use ($buscar) {
                    $q->where('plan_cuentas.codcta', 'like', '%' . $buscar . '%')
                        ->orWhere('plan_cuentas.nomcta', 'like', '%' . $buscar . '%');
                })
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->where("plan_cuentas.id_grupo", "=",2)
                ->orderByRaw('plan_cuentas.codcta ASC')
                ->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Parametrizacion::destroy($id);
    }
    public function eliminartodo($id)
    {
        Parametrizacion::where('id_departamento',$id)->delete();
    }
}
