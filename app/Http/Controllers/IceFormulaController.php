<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ice;
use App\Models\Ice_formula;
use Illuminate\Support\Facades\DB;

class IceFormulaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function codigo($id)
    {
        $selnum = DB::select("SELECT codigo FROM ice_formula  WHERE id_empresa = $id ORDER BY  codigo DESC LIMIT 1;");
        $principal = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->codigo;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return [
            "codigo_formula_ice" => $principal
        ];
    }
    public function index(Request $request, $id)
    {

        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Ice_formula::select('*')->where("id_empresa", "=", $id)->orderByRaw('id_ice_formula DESC')->get();
        } else {
            $recupera = Ice_formula::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo', 'like', '%' . $buscar . '%')
                        ->orWhere('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('formula', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_ice_formula DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }


    public function store(Request $request)
    {
        $ice_form = new Ice_formula();
        $ice_form->codigo = $request->codigo;
        $ice_form->nombre = $request->nombre;
        $ice_form->formula = $request->formula;
        $ice_form->id_empresa = $request->id_empresa;
        $ice_form->save();
    }

    public function editar(Request $request)
    {
        $ice_form = Ice_formula::findOrFail($request->id);
        $ice_form->codigo = $request->codigo;
        $ice_form->nombre = $request->nombre;
        $ice_form->formula = $request->formula;
        $ice_form->id_empresa = $request->id_empresa;
        $ice_form->save();
    }

    public function eliminar($id)
    {
        Ice_formula::destroy($id);
    }

    //no tocar esta funcion hasta implementar nuevo ice
    public function todo($id)
    {
        $recupera = Ice_formula::select('*')->where("id_empresa", "=", $id)->get();
        return $recupera;
    }
}
