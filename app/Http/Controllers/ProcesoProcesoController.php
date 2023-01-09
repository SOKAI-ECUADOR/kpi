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
use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;
use App\Models\Establecimiento;
use App\Models\Producto;
use PhpParser\Node\Stmt\Return_;

class ProcesoProcesoController extends Controller
{

    public function store(Request $request)
    {
        //cabecera
        $proceso = ProcesoProduccion::findOrFail($request->id_proceso_produccion);
        $proceso->fecha_proceso = $request->fecha_proceso;
        $proceso->estado = 2;
        $proceso->save();
        // comprueba si se pidieron mas ingredientes
        $hayegreso = false;
        for ($i = 0; $i < count($request->ingredientes); $i++) {
            if ($request->ingredientes[$i]["sector"] == 1 && $request->ingredientes[$i]["cantidad_produccion"] > 0) {
                $hayegreso = true;
            }
        }
        if ($hayegreso == false) {
            //edita valores de ingrediente cuando no se hizo mas egresos de bodega
            for ($j = 0; $j < count($request->ingredientes); $j++) {
                //si ingrediente solo se actualiza informacion de produccion
                if (isset($request->ingredientes[$j]["id_proceso_ingrediente"])) {
                    $ingred = ProcesoIngrediente::findOrFail($request->ingredientes[$j]["id_proceso_ingrediente"]);
                    $ingred->cantidad_produccion = $request->ingredientes[$j]["cantidad_produccion"];
                    $ingred->cantidad_liquidacion =  $ingred->cantidad_orden + $request->ingredientes[$j]["cantidad_produccion"];
                    if ($ingred->cantidad_produccion == 0) {
                        $ingred->costo_unitario_produccion = 0;
                        $ingred->costo_unitario_liquidacion = $ingred->costo_unitario_orden;
                    } else {
                        $ingred->costo_unitario_produccion = $request->ingredientes[$j]["costo_unitario_produccion"];
                        $ingred->costo_unitario_liquidacion = ($ingred->costo_unitario_orden + $ingred->costo_unitario_produccion) / 2;
                    }
                    $ingred->save();
                } else {
                    //si hay nuevo ingrediente nuevo solo servicio
                    $ingred = new ProcesoIngrediente();
                    $ingred->cantidad_orden = 0;
                    $ingred->cantidad_produccion = $request->ingredientes[$j]["cantidad_produccion"];
                    $ingred->cantidad_liquidacion =  $ingred->cantidad_orden + $request->ingredientes[$j]["cantidad_produccion"];
                    $ingred->costo_unitario_orden = 0;
                    $ingred->costo_unitario_produccion = $request->ingredientes[$j]["costo_unitario_produccion"];
                    $ingred->costo_unitario_liquidacion = ($ingred->costo_unitario_orden + $ingred->costo_unitario_produccion) / 2;
                    $ingred->id_producto = $request->ingredientes[$j]["id_producto"];
                    $ingred->id_proceso_produccion = $proceso->id_proceso_produccion;
                    if ($request->ingredientes[$j]["sector"] == 1) {
                        $ingred->id_bodega = $request->id_bodega;
                        $ingred->id_producto_bodega = $request->ingredientes[$j]["id_producto_bodega"];
                    }
                    $ingred->save();
                }
                for ($k = 0; $k < count($request->ingredientes[$j]["proceso_cantidad"]); $k++) {
                    if (isset($request->ingredientes[$j]["proceso_cantidad"][$k]["id_proceso_cantidad"])) {
                        $prcant =  ProcesoCantidad::findOrFail($request->ingredientes[$j]["proceso_cantidad"][$k]["id_proceso_cantidad"]);
                        $prcant->cantidad = $request->ingredientes[$j]["proceso_cantidad"][$k]["cantidad"];
                        $prcant->save();
                    } else {
                        $prcant = new ProcesoCantidad();
                        $prcant->cantidad = $request->ingredientes[$j]["proceso_cantidad"][$k]["cantidad"];
                        $prcant->id_proceso_produccion = $request->ingredientes[$j]["proceso_cantidad"][$k]["id_proceso_produccion"];
                        $prcant->id_proceso_producto = $request->ingredientes[$j]["proceso_cantidad"][$k]["id_proceso_producto"];
                        $prcant->id_proceso_ingrediente = $ingred->id_proceso_ingrediente;
                        $prcant->save();
                    }
                }
            }
        } else {
            //edita valores de ingrediente y egreso de bodega 
            //traer numero egreso
            $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $request->id_empresa ORDER BY  num_egreso DESC LIMIT 1;");
            $numeroegreso = "";
            if (count($numegre) == 1) {
                $dato = $numegre[0]->num_egreso;
                $tot = $dato + 1;
                $numeroegreso = $tot;
            } else {
                $numeroegreso = 1;
            }
            //egreso encabezado
            $egreso = new BodegaEgreso();
            $egreso->num_egreso = $numeroegreso;
            $egreso->fecha_egreso = $request->fecha_proceso;
            $egreso->tipo_egreso = "Proceso de Producción";
            $egreso->observ_egreso = "Proceso: {$proceso->num_orden}";
            $egreso->id_proyecto = $proceso->id_proyecto;
            $egreso->id_bodega = $request->id_bodega;
            $egreso->id_empresa = $request->id_empresa;
            $egreso->id_proceso_produccion = $proceso->id_proceso_produccion;
            $egreso->save();
            //recorre ingredientes egreso selectivo
            for ($a = 0; $a < count($request->ingredientes); $a++) {
                //si ingrediente es un producto
                if ($request->ingredientes[$a]["sector"] == 1) {
                    //si se añade algun ingrediente
                    if ($request->ingredientes[$a]["cantidad_produccion"] > 0) {
                        //egreso de bodega de ingredientes
                        $qstock = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->ingredientes[$a]["id_producto"] . " AND `id_bodega` =" . $request->id_bodega);
                        if (count($qstock) == 1) {
                            $prb = ProductoBodega::findOrFail($qstock[0]->id_producto_bodega);
                            $prb->cantidad = $prb->cantidad - $request->ingredientes[$a]["cantidad_produccion"];
                            $prb->costo_total = $prb->costo_total - ($request->ingredientes[$a]["cantidad_produccion"] * $request->ingredientes[$a]["costo_unitario_produccion"]);
                            if ($prb->cantidad != 0) {
                                $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                            } else {
                                $prb->costo_unitario = 0;
                            }
                            $prb->save();
                            //bodega egreso detalle
                            $bed = new BodegaEgresoDetalle();
                            $bed->cantidad = $request->ingredientes[$a]["cantidad_produccion"];
                            $bed->costo_unitario = $request->ingredientes[$a]["costo_unitario_produccion"];
                            $bed->costo_total = $request->ingredientes[$a]["cantidad_produccion"] * $request->ingredientes[$a]["costo_unitario_produccion"];
                            $bed->id_bodega_egreso = $egreso->id_bodega_egreso;
                            $bed->id_producto = $request->ingredientes[$a]["id_producto"];
                            $bed->id_proyecto = $proceso->id_proyecto;
                            $bed->save();
                        }
                        //si ingrediente producto ya se almaceno antes
                        if (isset($request->ingredientes[$a]["id_proceso_ingrediente"])) {
                            //edicion proceso ingrediente 
                            $ingred = ProcesoIngrediente::findOrFail($request->ingredientes[$a]["id_proceso_ingrediente"]);
                            $ingred->cantidad_produccion = $request->ingredientes[$a]["cantidad_produccion"];
                            $ingred->cantidad_liquidacion =  $ingred->cantidad_orden + $request->ingredientes[$a]["cantidad_produccion"];
                            $ingred->costo_unitario_produccion = $request->ingredientes[$a]["costo_unitario_produccion"];
                            $ingred->costo_unitario_liquidacion = ($ingred->costo_unitario_orden + $ingred->costo_unitario_produccion) / 2;
                            $ingred->id_bodega_egreso_detalle_produccion = $bed->id_bodega_egreso_detalle;
                            $ingred->save();
                        } else {
                            //si ingrediente producto es nuevo añadido
                            $ingred = new ProcesoIngrediente();
                            $ingred->cantidad_orden = 0;
                            $ingred->cantidad_produccion = $request->ingredientes[$a]["cantidad_produccion"];
                            $ingred->cantidad_liquidacion =  $ingred->cantidad_orden + $request->ingredientes[$a]["cantidad_produccion"];
                            $ingred->costo_unitario_orden = 0;
                            $ingred->costo_unitario_produccion = $request->ingredientes[$a]["costo_unitario_produccion"];
                            $ingred->costo_unitario_liquidacion = ($ingred->costo_unitario_orden + $ingred->costo_unitario_produccion) / 2;
                            $ingred->id_producto = $request->ingredientes[$a]["id_producto"];
                            $ingred->id_proceso_produccion = $proceso->id_proceso_produccion;
                            $ingred->id_bodega = $request->id_bodega;
                            $ingred->id_producto_bodega = $request->ingredientes[$a]["id_producto_bodega"];
                            $ingred->id_bodega_egreso_detalle_produccion = $bed->id_bodega_egreso_detalle;
                            $ingred->save();
                        }
                        //edicion de egreso detalle
                        $bede = BodegaEgresoDetalle::findOrFail($ingred->id_bodega_egreso_detalle_produccion);
                        $bede->id_proceso_ingrediente = $ingred->id_proceso_ingrediente;
                        $bede->save();
                    } elseif ($request->ingredientes[$a]["cantidad_produccion"] == 0) {
                        //si ingrediente producto ya se almaceno antes
                        if (isset($request->ingredientes[$a]["id_proceso_ingrediente"])) {
                            //edicion proceso ingrediente 
                            $ingred = ProcesoIngrediente::findOrFail($request->ingredientes[$a]["id_proceso_ingrediente"]);
                            $ingred->cantidad_produccion = $request->ingredientes[$a]["cantidad_produccion"];
                            $ingred->cantidad_liquidacion =  $ingred->cantidad_orden + $request->ingredientes[$a]["cantidad_produccion"];
                            $ingred->costo_unitario_produccion = 0;
                            $ingred->costo_unitario_liquidacion = ($ingred->costo_unitario_orden + $ingred->costo_unitario_produccion) / 2;
                            $ingred->save();
                        } else {
                            //si ingrediente producto es nuevo añadido
                            $ingred = new ProcesoIngrediente();
                            $ingred->cantidad_orden = 0;
                            $ingred->cantidad_produccion = $request->ingredientes[$a]["cantidad_produccion"];
                            $ingred->cantidad_liquidacion =  $ingred->cantidad_orden + $request->ingredientes[$a]["cantidad_produccion"];
                            $ingred->costo_unitario_orden = 0;
                            $ingred->costo_unitario_produccion = 0;
                            $ingred->costo_unitario_liquidacion = ($ingred->costo_unitario_orden + $ingred->costo_unitario_produccion) / 2;
                            $ingred->id_producto = $request->ingredientes[$a]["id_producto"];
                            $ingred->id_proceso_produccion = $proceso->id_proceso_produccion;
                            $ingred->save();
                        }
                    }
                    //si ingrediente es servicio 
                } elseif ($request->ingredientes[$a]["sector"] == 2) {
                    //si ingrediente servicio ya existia en orden
                    if (isset($request->ingredientes[$a]["id_proceso_ingrediente"])) {
                        $ingred = ProcesoIngrediente::findOrFail($request->ingredientes[$a]["id_proceso_ingrediente"]);
                        $ingred->cantidad_produccion = $request->ingredientes[$a]["cantidad_produccion"];
                        $ingred->cantidad_liquidacion =  $ingred->cantidad_orden + $request->ingredientes[$a]["cantidad_produccion"];
                        $ingred->costo_unitario_produccion = $request->ingredientes[$a]["costo_unitario_produccion"];
                        $ingred->costo_unitario_liquidacion = ($ingred->costo_unitario_orden + $ingred->costo_unitario_produccion) / 2;
                        $ingred->save();
                    } else {
                        //si hay  ingrediente servicio es nuevo en la produccion
                        $ingred = new ProcesoIngrediente();
                        $ingred->cantidad_orden = 0;
                        $ingred->cantidad_produccion = $request->ingredientes[$a]["cantidad_produccion"];
                        $ingred->cantidad_liquidacion =  $ingred->cantidad_orden + $request->ingredientes[$a]["cantidad_produccion"];
                        $ingred->costo_unitario_orden = 0;
                        $ingred->costo_unitario_produccion = $request->ingredientes[$a]["costo_unitario_produccion"];
                        $ingred->costo_unitario_liquidacion = ($ingred->costo_unitario_orden + $ingred->costo_unitario_produccion) / 2;
                        $ingred->id_producto = $request->ingredientes[$a]["id_producto"];
                        $ingred->id_proceso_produccion = $proceso->id_proceso_produccion;
                        $ingred->save();
                    }
                }
                for ($k = 0; $k < count($request->ingredientes[$a]["proceso_cantidad"]); $k++) {
                    if (isset($request->ingredientes[$a]["proceso_cantidad"][$k]["id_proceso_cantidad"])) {
                        $prcant =  ProcesoCantidad::findOrFail($request->ingredientes[$a]["proceso_cantidad"][$k]["id_proceso_cantidad"]);
                        $prcant->cantidad = $request->ingredientes[$a]["proceso_cantidad"][$k]["cantidad"];
                        $prcant->save();
                    } else {
                        $prcant = new ProcesoCantidad();
                        $prcant->cantidad = $request->ingredientes[$a]["proceso_cantidad"][$k]["cantidad"];
                        $prcant->id_proceso_produccion = $request->ingredientes[$a]["proceso_cantidad"][$k]["id_proceso_produccion"];
                        $prcant->id_proceso_producto = $request->ingredientes[$a]["proceso_cantidad"][$k]["id_proceso_producto"];
                        $prcant->id_proceso_ingrediente = $ingred->id_proceso_ingrediente;
                        $prcant->save();
                    }
                }
            }
        }
    }

    public function getnewingred(Request $request, $id, $ide)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $ingreds = DB::select("SELECT pb.*, p.nombre, p.cod_principal, p.cod_alterno, p.sector, pb.cantidad AS stock FROM producto p JOIN producto_bodega pb ON p.id_producto = pb.id_producto WHERE pb.id_bodega=$id AND p.id_empresa = $ide UNION SELECT NULL as id_producto_bodega, NULL as cantidad, NULL as costo_unitario, NULL as costo_total, producto.id_producto, NULL as id_bodega, producto.id_empresa, producto.nombre, producto.cod_principal,  producto.cod_alterno, producto.sector, '-' as stock FROM producto WHERE sector=2 AND id_empresa = $ide");
        } else {
            $ingreds = DB::select("SELECT pb.*, p.nombre, p.cod_principal, p.cod_alterno, p.sector, pb.cantidad AS stock FROM producto p JOIN producto_bodega pb ON p.id_producto = pb.id_producto WHERE pb.id_bodega = $id AND p.id_empresa = $ide AND  (p.nombre LIKE '%$buscar%' OR p.cod_principal LIKE '%$buscar%') UNION SELECT NULL as id_producto_bodega, NULL as cantidad, NULL as costo_unitario, NULL as costo_total, producto.id_producto, NULL as id_bodega, producto.id_empresa, producto.nombre, producto.cod_principal, producto.cod_alterno, producto.sector, '-' as stock FROM producto WHERE sector=2 AND id_empresa = $ide AND (nombre LIKE '%$buscar%' OR cod_principal LIKE '%$buscar%')");
        }
        return [
            'recupera' => $ingreds
        ];
    }
}
