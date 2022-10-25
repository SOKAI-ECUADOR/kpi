<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Lineaproducto;
use App\Models\Tipoproducto;
use App\Models\Plancuenta;


class LineaproductoController extends Controller
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
            $recupera = Lineaproducto::select(
                "linea_producto.id_linea_producto",
                "linea_producto.codigo",
                "linea_producto.nombre",
                "linea_producto.id_empresa",
                "linea_producto.id_plan_cuentas_compras_iva AS cta_civa_id",
                "plan_cuentas_compras_iva.nomcta AS cta_civa",
                "linea_producto.id_plan_cuentas_compras_iva_0 AS cta_civa0_id",
                "plan_cuentas_compras_iva0.nomcta AS cta_civa0",
                "linea_producto.id_plan_cuentas_ventas_iva AS cta_viva_id",
                "plan_cuentas_ventas_iva.nomcta AS cta_viva",
                "linea_producto.id_plan_cuentas_ventas_iva_0 AS cta_viva0_id",
                "plan_cuentas_ventas_iva0.nomcta AS cta_viva0",
                "linea_producto.id_plan_cuentas_costo AS cta_costo_id",
                "plan_cuentas_costo.nomcta AS cta_costo",
            )
                ->leftjoin("plan_cuentas AS plan_cuentas_compras_iva", "plan_cuentas_compras_iva.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_compras_iva")
                ->leftjoin("plan_cuentas AS plan_cuentas_compras_iva0", "plan_cuentas_compras_iva0.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_compras_iva_0")
                ->leftjoin("plan_cuentas AS plan_cuentas_ventas_iva", "plan_cuentas_ventas_iva.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_ventas_iva")
                ->leftjoin("plan_cuentas AS plan_cuentas_ventas_iva0", "plan_cuentas_ventas_iva0.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_ventas_iva_0")
                ->leftjoin("plan_cuentas AS plan_cuentas_costo", "plan_cuentas_costo.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_costo")
                ->where("linea_producto.id_empresa", "=", $id)->get();
        } else {
            $recupera = Lineaproducto::select(
                "linea_producto.id_linea_producto",
                "linea_producto.codigo",
                "linea_producto.nombre",
                "linea_producto.id_empresa",
                "linea_producto.id_plan_cuentas_compras_iva AS cta_civa_id",
                "plan_cuentas_compras_iva.nomcta AS cta_civa",
                "linea_producto.id_plan_cuentas_compras_iva_0 AS cta_civa0_id",
                "plan_cuentas_compras_iva0.nomcta AS cta_civa0",
                "linea_producto.id_plan_cuentas_ventas_iva AS cta_viva_id",
                "plan_cuentas_ventas_iva.nomcta AS cta_viva",
                "linea_producto.id_plan_cuentas_ventas_iva_0 AS cta_viva0_id",
                "plan_cuentas_ventas_iva0.nomcta AS cta_viva0",
                "linea_producto.id_plan_cuentas_costo AS cta_costo_id",
                "plan_cuentas_costo.nomcta AS cta_costo",
            )
                ->leftjoin("plan_cuentas AS plan_cuentas_compras_iva", "plan_cuentas_compras_iva.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_compras_iva")
                ->leftjoin("plan_cuentas AS plan_cuentas_compras_iva0", "plan_cuentas_compras_iva0.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_compras_iva_0")
                ->leftjoin("plan_cuentas AS plan_cuentas_ventas_iva", "plan_cuentas_ventas_iva.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_ventas_iva")
                ->leftjoin("plan_cuentas AS plan_cuentas_ventas_iva0", "plan_cuentas_ventas_iva0.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_ventas_iva_0")
                ->leftjoin("plan_cuentas AS plan_cuentas_costo", "plan_cuentas_costo.id_plan_cuentas", "=", "linea_producto.id_plan_cuentas_costo")
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('codigo', 'like', '%' . $buscar . '%');
                })
                ->where('linea_producto.id_empresa', '=', $id)
                ->orderByRaw('id_linea_producto DESC')->get();
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

    public function todo($id)
    {
        $tipo = Lineaproducto::select("*")->where("id_empresa", "=", $id)->get();
        return $tipo;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo = new Lineaproducto();
        $tipo->codigo = $request->codigo;
        $tipo->nombre = $request->nombre;
        $tipo->id_plan_cuentas_compras_iva = $request->cta_civa_id;
        $tipo->id_plan_cuentas_compras_iva_0 = $request->cta_civa0_id;
        $tipo->id_plan_cuentas_ventas_iva = $request->cta_viva_id;
        $tipo->id_plan_cuentas_ventas_iva_0 = $request->cta_viva0_id;
        $tipo->id_plan_cuentas_costo = $request->cta_costo_id;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();
    }

    public function editar(Request $request)
    {
        $tipo = Lineaproducto::findOrFail($request->id);
        $tipo->codigo = $request->codigo;
        $tipo->nombre = $request->nombre;
        $tipo->id_plan_cuentas_compras_iva = $request->cta_civa_id;
        $tipo->id_plan_cuentas_compras_iva_0 = $request->cta_civa0_id;
        $tipo->id_plan_cuentas_ventas_iva = $request->cta_viva_id;
        $tipo->id_plan_cuentas_ventas_iva_0 = $request->cta_viva0_id;
        $tipo->id_plan_cuentas_costo = $request->cta_costo_id;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();
    }

    public function eliminar($id)
    {
        //Tipoproducto::where('id_linea_producto', $id)->delete();
        DB::delete("DELETE FROM tipo_producto WHERE id_linea_producto = " . $id);
        Lineaproducto::destroy($id);
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
    public function update(Request $request, $id)
    {
        //
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
}
