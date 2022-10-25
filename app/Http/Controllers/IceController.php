<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ice;
use App\Models\Ice_formula;
use Illuminate\Support\Facades\DB;

class IceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //lista los registros de ice del sistema mediante la empresa registrada
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp);
            $recupera = Ice::select('ice.*', 'ice_formula.formula', 'plan_cuentas.nomcta as cta_cont')->leftjoin('ice_formula', 'ice.id_ice_formula', '=', 'ice_formula.id_ice_formula')->leftjoin('plan_cuentas', 'ice.id_plan_cuentas', '=', 'plan_cuentas.id_plan_cuentas')->where("ice.id_empresa", "=", $id)->orderByRaw('ice.id_ice DESC')->get();
        } else {
            $recupera = Ice::select('ice.*', 'ice_formula.formula', 'plan_cuentas.nomcta as cta_cont')->leftjoin('ice_formula', 'ice.id_ice_formula', '=', 'ice_formula.id_ice_formula')->leftjoin('plan_cuentas', 'ice.id_plan_cuentas', '=', 'plan_cuentas.id_plan_cuentas')
                ->where(function ($q) use ($buscar) {
                    $q->where('ice.codigo', 'like', '%' . $buscar . '%')
                        ->orWhere('ice.nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('ice.valor', 'like', '%' . $buscar . '%')
                        ->orWhere('ice_formula.formula', 'like', '%' . $buscar . '%');
                })
                ->where("ice.id_empresa", "=", $id)
                ->orderByRaw('ice.id_ice DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }


    public function store(Request $request)
    {
        //guarda un nuevo ice
        $ice = new Ice();
        $ice->codigo = $request->codigo;
        $ice->nombre = $request->nombre;
        $ice->valor = $request->valor;
        $ice->observacion = $request->observacion;
        $ice->id_ice_formula = $request->formula;
        $ice->id_plan_cuentas = $request->id_plan_cuentas;
        $ice->id_empresa = $request->id_empresa;
        $ice->save();
    }

    public function editar(Request $request)
    {
        //edita el ice generado
        $ice = Ice::findOrFail($request->id);
        $ice->codigo = $request->codigo;
        $ice->nombre = $request->nombre;
        $ice->valor = $request->valor;
        $ice->observacion = $request->observacion;
        $ice->id_ice_formula = $request->formula;
        $ice->id_plan_cuentas = $request->id_plan_cuentas;
        $ice->id_empresa = $request->id_empresa;
        $ice->save();
    }

    public function eliminar($id)
    {

        //elimina el ice mediante el id
        Ice::destroy($id);
    }
    public function select($id)
    {
        //selecciona el ice con su fórmula
        return Ice::select('ice.*', 'ice_formula.formula')
            ->leftjoin('ice_formula', 'ice.id_ice_formula', '=', 'ice_formula.id_ice_formula')
            ->where("ice.id_empresa", "=", $id)->get();
    }

    //no tocar esta funcion hasta implementar nuevo ice
    public function todo()
    {
        //recupera todos los ice
        $recupera = Ice::all();
        return $recupera;
    }
}
