<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Plancuenta;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Grupo;
use App\Models\Caja;

include 'class/generarReportes.php';

use generarReportes;


class PlancuentasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request,$id)
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if($buscar == ''){
            $sel = DB::select("SELECT plan_cuentas.*,moneda.nomb_moneda,grupo.nomb_grupo FROM plan_cuentas 
            INNER JOIN moneda on moneda.id_moneda=plan_cuentas.id_moneda
            INNER JOIN grupo on grupo.id_grupo=plan_cuentas.id_grupo
            WHERE id_empresa = $id order by plan_cuentas.codcta asc");
        }else{
            $sel = DB::select("SELECT plan_cuentas.*,moneda.nomb_moneda,grupo.nomb_grupo FROM plan_cuentas 
            INNER JOIN moneda on moneda.id_moneda=plan_cuentas.id_moneda
            INNER JOIN grupo on grupo.id_grupo=plan_cuentas.id_grupo
            WHERE (nomcta LIKE '%$buscar%' OR codcta LIKE '%$buscar%') AND id_empresa = $id order by plan_cuentas.codcta asc");
        }
        
        return $sel;
        /*$buscar = $request->buscar;
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
                    $q->where('plan_cuentas.codcta', 'like',"'%{$buscar}%'")
                        ->orWhere('plan_cuentas.nomcta', 'like',"'%{$buscar}%'");
                })
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->orderByRaw('plan_cuentas.codcta ASC')
                ->get();
        }
        return [
            'recupera' => $recupera
        ];*/
    }
    public function movimiento(Request $request, $id)
    {
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
                ->where("plan_cuentas.id_grupo", "=", 2)
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
                    $q->where('plan_cuentas.codcta', 'like', "'%$buscar%'")
                        ->orWhere('plan_cuentas.nomcta', 'like', "'%$buscar%'");
                })
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->where("plan_cuentas.id_grupo", "=", 2)
                ->orderByRaw('plan_cuentas.codcta ASC')
                ->get();
        }
        return [
            'recupera' => $recupera
        ];
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
        $var=0;
        $var=substr($request->codcta,-1);
        //".$request->codcta"
        $select2 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '.'"'.$request->codcta.'"'.' and id_empresa='.$request->id_empresa);

        if($request->id_grupo==1){
            if (count($select2) >= 1) {
                return "existe";
            }
            if($var=="."){
                $ctas = new Plancuenta();

                $ctas->codcta = $request->codcta;
                $ctas->num_cuenta = $request->num_cuenta;
                $ctas->nomcta = $request->nomcta;
                $ctas->bansel = $request->bansel;
                $ctas->ucrea=$request->ucrea;
                $ctas->id_grupo = $request->id_grupo;
                $ctas->id_moneda = $request->id_moneda;
                $ctas->id_banco = $request->id_banco;
                $ctas->id_empresa = $request->id_empresa;

                $ctas->save();
            }else{
                return "NO vale";
            }
        }else{
           if(is_numeric($var)){
                if (count($select2) >= 1) {
                    return "existe";
                }
                $ctas = new Plancuenta();
                $ctas->codcta = $request->codcta;
                $ctas->num_cuenta = $request->num_cuenta;
                $ctas->nomcta = $request->nomcta;
                $ctas->bansel = $request->bansel;
                $ctas->ucrea=$request->ucrea;
                $ctas->id_grupo = $request->id_grupo;
                $ctas->id_moneda = $request->id_moneda;
                $ctas->id_banco = $request->id_banco;
                $ctas->id_empresa = $request->id_empresa;
                $ctas->save();
           }else{
            return "mov mal";
           }
        }
        //return $var;

        /*$ctas = new Plancuenta();
        $ctas->id_empresa = $request->id_empresa;
        $ctas->codcta = $request->codcta;
        $ctas->nomcta = $request->nomcta;
        $ctas->id_moneda = $request->id_moneda;
        $ctas->bansel = $request->bansel;
        $ctas->id_grupo = $request->id_grupo;
        $ctas->save();*/
    }

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
    public function update(Request $request)
    {
        $select1 = DB::select('SELECT codcta FROM `plan_cuentas` WHERE id_plan_cuentas = '.$request->id);
        $var=0;
        $var=substr($request->codcta,-1);
        $select2 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = ' . "'$request->codcta'".' and id_empresa='.$request->id_empresa);

        if($request->codcta===$select1[0]->codcta){
                $ctas = Plancuenta::find($request->id);

                $ctas->codcta = $request->codcta;
                $ctas->num_cuenta = $request->num_cuenta;
                $ctas->nomcta = $request->nomcta;
                $ctas->bansel = $request->bansel;
                $ctas->umodifica=$request->umodifica;
                $ctas->id_grupo = $request->id_grupo;
                $ctas->id_moneda = $request->id_moneda;
                $ctas->id_banco = $request->id_banco;


            $ctas->save();

        }else{
            if($request->id_grupo==1){
                if (count($select2) >= 1) {
                    return "existe";
                }
                if($var=="."){
                    $ctas = Plancuenta::find($request->id);
                    $ctas->codcta = $request->codcta;
                    $ctas->num_cuenta = $request->num_cuenta;
                    $ctas->nomcta = $request->nomcta;
                    $ctas->bansel = $request->bansel;
                    $ctas->umodifica=$request->umodifica;
                    $ctas->id_grupo = $request->id_grupo;
                    $ctas->id_moneda = $request->id_moneda;
                    $ctas->id_banco = $request->id_banco;
                    $ctas->save();
                }else{
                    return "NO vale";
                }
            }else{
               if(is_numeric($var)){
                    if (count($select2) >= 1) {
                        return "existe";
                    }
                    $ctas = Plancuenta::find($request->id);
                    $ctas->codcta = $request->codcta;
                    $ctas->num_cuenta = $request->num_cuenta;
                    $ctas->nomcta = $request->nomcta;
                    $ctas->bansel = $request->bansel;
                    $ctas->umodifica=$request->umodifica;
                    $ctas->id_grupo = $request->id_grupo;
                    $ctas->id_moneda = $request->id_moneda;
                    $ctas->id_banco = $request->id_banco;
                    $ctas->save();
               }else{
                return "mov mal";
               }
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
        //
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `plan_cuentas` WHERE id_ctas=' . $id);
        return $recupera;
    }

    public function eliminar($id)
    {
        Plancuenta::destroy($id);
        /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
    }

    public function getEmpresa()
    {
        $data = Empresa::get();

        return response()->json($data);
    }
    public function getMoneda()
    {
        $data = Moneda::get();

        return response()->json($data);
    }
    public function getcodplancuentas($id)
    {
        $cod = Plancuenta::select("codcta")->where("id_ctas", "=", $id)->get();
        return $cod[0];
    }

    public function balanceComprobacion(Request $request)
    {
        $bs = "";
        if ($request->tipo_busqueda == 1) {
            $dt = "";
            if ($request->dateinicio) {
                $dt = "a.fecha >=$request->dateinicio";
            }
            if ($request->dateinicio && $request->datefin) {
                $dt = "a.fecha BETWEEN " . $request->dateinicio . " AND " . $request->datefin;
            }
            if ($request->datefin) {
                $dt = "a.fecha <=$request->datefin";
            }
            $bs .= "WHERE " . $dt;
        } else if ($request->tipo_busqueda == 2) {
            $bs .= "WHERE f.id_proveedor = " . $request->proveedor_busqueda;
        } else if ($request->tipo_busqueda == 3) {
            $bs .= "WHERE f.id_proveedor = " . $request->ruc_busqueda;
        }
        $reporte = DB::select("SELECT p.codcta, p.nomcta, ad.debe, ad.haber, e.nombre_empresa, e.logo
        FROM plan_cuentas p INNER JOIN asientos_detalle ad ON ad.id_ctas=p.id_ctas INNER JOIN asientos a ON a.id_asientos=ad.id_asientos INNER JOIN proyecto pr ON a.id_proyecto=pr.id_proyecto INNER JOIN empresa e on pr.id_empresa=e.id_empresa " . $bs . " ORDER BY a.fecha");
        $Reportes = new generarReportes();
        $Reportes->balanceComprobacion($reporte, $request->dateinicio, $request->datefin);
    }
    public function select(Request $request, $id)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = Plancuenta::select('*')
                ->where('id_empresa', '=', $id)
                ->orderByRaw('codcta ASC')
                ->get();
        } else {
            $recupera = Plancuenta::select('*')
            ->where(function ($q) use ($buscar) {
                $q->where('codcta', 'like', '%' . $buscar . '%')
                    ->orWhere('nomcta', 'like', '%' . $buscar . '%');
            })
                ->where('id_empresa', "=", $id)
                ->orderByRaw('codcta ASC')
                ->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function selectplan_cuentas(Request $request, $id)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = DB::select("SELECT * FROM plan_cuentas WHERE id_empresa=$id ORDER BY codcta LIMIT 25");
        } else {
            $recupera = DB::select("SELECT * FROM plan_cuentas WHERE id_empresa= $id AND (nomcta LIKE '%$buscar%' OR codcta LIKE '%$buscar%') ORDER BY codcta LIMIT 25");
        }
        return [
            'recupera' => $recupera
        ];
    }
}
