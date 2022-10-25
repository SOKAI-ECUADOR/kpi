<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormulaProduccion;
use App\Models\FormulaProducto;
use App\Models\FormulaIngrediente;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;
use Symfony\Component\HttpFoundation\Response;

class FormulaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = FormulaProduccion::select("*")
                ->where("formula_produccion.id_empresa", "=", $id)
                ->orderByRaw('formula_produccion.id_formula_produccion DESC')->get();
        } else {
            $recupera = FormulaProduccion::select("*")
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo_produccion', 'like', '%' . $buscar . '%')
                        ->orWhere('nombre_form', 'like', '%' . $buscar . '%');
                })
                ->where("formula_produccion.id_empresa", "=", $id)
                ->orderByRaw('formula_produccion.id_formula_produccion DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function codform($id)
    {
        $sel = DB::select("SELECT codigo_produccion AS  codigo_produccion   FROM formula_produccion  WHERE id_empresa = $id ORDER BY  codigo_produccion DESC LIMIT 1;");
        $principal = "";
        if (count($sel) >= 1) {
            $dato = $sel[0]->codigo_produccion;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return [
            "codigo_produccion" => $principal
        ];
    }

    public function store(Request $request)
    {
        //codigo produccion
        $sel = DB::select("SELECT codigo_produccion AS  codigo_produccion   FROM formula_produccion  WHERE id_empresa = $request->id_empresa ORDER BY  codigo_produccion DESC LIMIT 1;");
        $principal = "";
        if (count($sel) >= 1) {
            $dato = $sel[0]->codigo_produccion;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        //cabecera
        $formula = new FormulaProduccion();
        $formula->codigo_produccion = $principal;
        $formula->nombre_form = $request->nombre_form;
        $formula->id_empresa = $request->id_empresa;
        $formula->save();


        $idp = $formula->id_formula_produccion;
        //producto a producir
        for ($a = 0; $a < count($request->productos); $a++) {
            $formula1 = new FormulaProducto();
            $formula1->id_producto = $request->productos[$a]["id"];
            $formula1->id_formula_produccion = $idp;
            $formula1->save();


            $producto =  Producto::findOrFail($request->productos[$a]["id"]);
            $producto->form_prod = $request->nombre_form;
            $producto->save();
        }
        //ingredientes
        for ($a = 0; $a < count($request->ingredientes); $a++) {
            $formula2 = new FormulaIngrediente();
            $formula2->id_producto = $request->ingredientes[$a]["id"];
            $formula2->id_formula_produccion = $idp;
            $formula2->cant_unit_prod = $request->ingredientes[$a]["cant_form"];
            $formula2->save();
        }
        return $idp;
    }

    public function update(Request $request)
    {
        $formula = FormulaProduccion::findOrFail($request->id_formula_produccion);
        $formula->codigo_produccion = $request->codigo_produccion;
        $formula->nombre_form = $request->nombre_form;
        $formula->id_empresa = $request->id_empresa;
        $formula->save();

        $idp = $request->id_formula_produccion;

        DB::delete("DELETE FROM formula_producto WHERE id_formula_produccion=" . $idp);
        DB::delete("DELETE FROM formula_ingrediente WHERE id_formula_produccion=" . $idp);
        DB::update("UPDATE producto SET form_prod = '' WHERE form_prod ='" . $request->nombre_form . "'");

        //producto a producir
        for ($a = 0; $a < count($request->productos); $a++) {


            $formula1 = new FormulaProducto();
            $formula1->id_producto = $request->productos[$a]["id"];
            $formula1->id_formula_produccion = $idp;
            $formula1->save();


            $producto =  Producto::findOrFail($request->productos[$a]["id"]);
            $producto->form_prod = $request->nombre_form;
            $producto->save();
        }
        //ingredientes
        for ($a = 0; $a < count($request->ingredientes); $a++) {
            $formula2 = new FormulaIngrediente();
            $formula2->id_producto = $request->ingredientes[$a]["id"];
            $formula2->id_formula_produccion = $idp;
            $formula2->cant_unit_prod = $request->ingredientes[$a]["cant_form"];
            $formula2->save();
        }
    }

    public function delete($id)
    {
        $codform = DB::select("SELECT id_producto FROM formula_producto WHERE id_formula_produccion =$id;");
        for ($i = 0; $i < count($codform); $i++) {
            $producto =  Producto::findOrFail($codform[$i]->id_producto);
            $producto->form_prod = null;
            $producto->save();
        };

        FormulaProduccion::destroy($id);
        DB::delete("DELETE FROM formula_producto WHERE id_formula_produccion=" . $id);
        DB::delete("DELETE FROM formula_ingrediente WHERE id_formula_produccion=" . $id);
    }

    public function getform($id)
    {
        $form = FormulaProduccion::select("*")->where('id_formula_produccion', "=", $id)->get();
        return $form[0];
    }

    public function getformprod($id)
    {
        $formprod = FormulaProducto::select("formula_producto.id_producto as id", "producto.cod_principal", "producto.cod_alterno", "producto.nombre")
            ->join("formula_produccion", "formula_produccion.id_formula_produccion", "=", "formula_producto.id_formula_produccion")
            ->join("producto", "producto.id_producto", "=", "formula_producto.id_producto")
            ->where("formula_producto.id_formula_produccion", "=", $id)->get();
        return [
            "datos" => $formprod,
        ];
    }
    public function getformingred($id)
    {
        $formprod = FormulaIngrediente::select("formula_ingrediente.id_producto as id", "producto.cod_principal", "producto.cod_alterno", "producto.nombre", "formula_ingrediente.cant_unit_prod as cant_form")
            ->join("formula_produccion", "formula_produccion.id_formula_produccion", "=", "formula_ingrediente.id_formula_produccion")
            ->join("producto", "producto.id_producto", "=", "formula_ingrediente.id_producto")
            ->where("formula_ingrediente.id_formula_produccion", "=", $id)->get();
        return [
            "datos" => $formprod,
        ];
    }
    //proceso produccion
    public function traeform($id)
    {
        $form = FormulaProduccion::select("formula_produccion.*", "producto.nombre as nomproduct")
            ->join("producto", "producto.id_producto", "=", "formula_produccion.id_producto_produ")
            ->where('id_formula_produccion', "=", $id)->get();
        return $form[0];
    }
    // traer productos para crear formula
    public function getproductlistformula(Request $request, $id)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where('id_empresa', '=', $id)
                ->where('sector', '=', 1)
                ->whereNull('form_prod')
                ->orderByRaw('id_producto DESC')->get();
        } else {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_principal', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_alterno', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->where('sector', '=', 1)
                ->whereNull('form_prod')
                ->orderByRaw('id_producto DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
        // traer ingredientes para crear formula
        public function getingredlistformula(Request $request, $id)
        {
            $buscar = str_replace(array(" "), "%", $request->buscar);
            if ($buscar == '') {
                $recupera = Producto::addSelect([
                    'nombremarca' => Marca::select('nombre')
                        ->whereColumn('id_marca', 'producto.id_marca'),
                    'nombremodelo' => Modelo::select('nombre')
                        ->whereColumn('id_modelo', 'producto.id_modelo')
                ])
                    ->where('id_empresa', '=', $id)
                    ->orderByRaw('id_producto DESC')->get();
            } else {
                $recupera = Producto::addSelect([
                    'nombremarca' => Marca::select('nombre')
                        ->whereColumn('id_marca', 'producto.id_marca'),
                    'nombremodelo' => Modelo::select('nombre')
                        ->whereColumn('id_modelo', 'producto.id_modelo')
                ])
                    ->where(function ($q) use ($buscar) {
                        $q->where('nombre', 'like', '%' . $buscar . '%')
                            ->orWhere('cod_principal', 'like', '%' . $buscar . '%')
                            ->orWhere('cod_alterno', 'like', '%' . $buscar . '%');
                    })
                    ->where('id_empresa', '=', $id)
                    ->orderByRaw('id_producto DESC')->get();
            }
            return [
                'recupera' => $recupera
            ];
        }
}
