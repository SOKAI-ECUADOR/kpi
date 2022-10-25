<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProcesoProduccion;
use App\Models\ProcesoProducto;
use App\Models\ProcesoIngrediente;
use App\Models\ProcesoCantidad;
use App\Models\FormulaProduccion;
use App\Models\FormulaProducto;
use App\Models\FormulaIngrediente;
use App\Models\Bodega;
use App\Models\ProductoBodega;
use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;
use App\Models\Establecimiento;
use App\Models\Producto;
use PhpParser\Node\Stmt\Return_;


class ProcesoLiquidacionController extends Controller
{
    public function getordenprod($id)
    {
        $prod = ProcesoProducto::select("proceso_producto.cantidad as cant_prod", "producto.id_producto as id", "producto.cod_principal as cod_principal", "producto.nombre as nombre", "producto.form_prod as form_prod", "proceso_producto.id_bodega as bodega_liquid")
            ->join("producto", "producto.id_producto", "=", "proceso_producto.id_producto")
            ->where('id_proceso_produccion', "=", $id)->get();
        return [
            "datos" => $prod,
        ];
    }
    public function getingred(Request $request)
    {
        $idpp = $request->pr;
        $ingreds = ProcesoIngrediente::select("proceso_ingrediente.*", "producto.nombre", "producto.cod_principal")
            ->join("producto", "producto.id_producto", "=", "proceso_ingrediente.id_producto")
            ->join("producto_bodega", "producto_bodega.id_producto", "=", "producto.id_producto")
            ->where("proceso_ingrediente.id_proceso_produccion", "=", $idpp)
            ->get();
        return $ingreds;
    }

    public function store(Request $request)
    {
        $proceso = ProcesoProduccion::findOrFail($request->id_proceso_produccion);
        $proceso->fecha_fin = $request->fecha_fin;
        $proceso->estado = 3;
        $proceso->save();

        $numingres = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $request->id_empresa ORDER BY  num_ingreso DESC LIMIT 1;");
        $numeroingreso = "";
        if (count($numingres) == 1) {
            $dato = $numingres[0]->num_ingreso;
            $tot = $dato + 1;
            $numeroingreso = $tot;
        } else {
            $numeroingreso = 1;
        }
        //ingreso encabezado
        $ingreso = new BodegaIngreso();
        $ingreso->num_ingreso = $numeroingreso;
        $ingreso->fecha_ingreso = $proceso->fecha_fin;
        $ingreso->tipo_ingreso = "Proceso de Producción";
        $ingreso->observ_ingreso = "Liquidación: {$proceso->num_orden}";
        $ingreso->id_proyecto = $request->id_proyecto;
        $ingreso->id_bodega = $request->id_bodega;
        $ingreso->id_empresa = $request->id_empresa;
        $ingreso->id_proceso_produccion = $proceso->id_proceso_produccion;
        $ingreso->save();

        for ($a = 0; $a < count($request->productos); $a++) {
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->productos[$a]["id"] . " AND `id_bodega` =" . $request->id_bodega);
            if (count($sel) <= 0) {
                $prb = new ProductoBodega();
                $prb->cantidad = $request->productos[$a]["cant_prod"];
                $prb->costo_unitario = $request->productos[$a]["costo_unitario"];
                $prb->costo_total = $request->productos[$a]["costo_total"];
                $prb->id_producto = $request->productos[$a]["id"];
                $prb->id_bodega = $request->id_bodega;
                $prb->id_empresa = $request->id_empresa;
                $prb->save();

                $bid = new BodegaIngresoDetalle();
                $bid->cantidad = $request->productos[$a]["cant_prod"];
                $bid->costo_unitario = $request->productos[$a]["costo_unitario"];
                $bid->costo_total = $request->productos[$a]["costo_total"];
                $bid->id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                $bid->id_producto = $request->productos[$a]["id"];
                $bid->id_proyecto = $proceso->id_proyecto;
                $bid->id_proceso_producto = $request->productos[$a]["id_proceso_producto"];
                $bid->save();
            } else {
                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                $prb->cantidad = $prb->cantidad + $request->productos[$a]["cant_prod"];
                $prb->costo_total = $request->productos[$a]["costo_total"] + $prb->costo_total;
                if ($prb->cantidad != 0) {
                    $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                } else {
                    $prb->costo_unitario = 0;
                }
                $prb->save();

                $bid = new BodegaIngresoDetalle();
                $bid->cantidad = $request->productos[$a]["cant_prod"];
                $bid->costo_unitario = $request->productos[$a]["costo_unitario"];
                $bid->costo_total = $request->productos[$a]["costo_total"];
                $bid->id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                $bid->id_producto = $request->productos[$a]["id"];
                $bid->id_proyecto = $proceso->id_proyecto;
                $bid->id_proceso_producto = $request->productos[$a]["id_proceso_producto"];
                $bid->save();
            }
            $prp = ProcesoProducto::findOrFail($request->productos[$a]["id_proceso_producto"]);
            $prp->costo_unitario = $request->productos[$a]["costo_unitario"];
            $prp->costo_total = $request->productos[$a]["costo_total"];
            $prp->id_bodega = $request->id_bodega;
            $prp->id_producto_bodega = $prb->id_producto_bodega;
            $prp->id_bodega_ingreso_detalle = $bid->id_bodega_ingreso_detalle;
            $prp->save();
        }
    }
}
