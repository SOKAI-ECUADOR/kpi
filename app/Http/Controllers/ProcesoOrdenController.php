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
use App\Models\Marca;
use App\Models\Modelo;
use PhpParser\Node\Stmt\Return_;
use Carbon\Carbon;

class ProcesoOrdenController extends Controller
{
    //Formula de produccion y proceso de produccion
    public function indexform(Request $request, $id)
    {

        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo'),
                'id_form_prod' => FormulaProduccion::select('id_formula_produccion')
                    ->whereColumn('nombre_form', 'producto.form_prod')
            ])
                ->whereNotNull('form_prod')
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_producto DESC')->get();
        } else {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo'),
                'id_form_prod' => FormulaProduccion::select('id_formula_produccion')
                    ->whereColumn('nombre_form', 'producto.form_prod')
            ])
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_principal', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_alterno', 'like', '%' . $buscar . '%');
                })
                ->whereNotNull('form_prod')
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_producto DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function getingred(Request $request)
    {
        $ingredientes = FormulaIngrediente::select("formula_ingrediente.*", "producto.nombre", "producto.cod_principal", "producto.cod_alterno", "producto.sector", "formula_ingrediente.cant_unit_prod as canti")
            ->join("producto", "producto.id_producto", "=", "formula_ingrediente.id_producto")
            ->where("formula_ingrediente.id_formula_produccion", "=", $request->id_formula_produccion)
            ->get();
        /* for ($i = 0; $i < count($formula); $i++) {
            for ($e = 0; $e < count($ingreds); $e++) {
                if ($formula[$i]->id_producto === $ingreds[$e]->id_producto) {
                    $ingreds[$e]->control = true;
                    array_push($formingred, $ingreds[$e]);
                }else{
                    $formula[$i]->control = false;
                    array_push($formingred, $formula[$i]);
                }
            }
        }*/
        return  $ingredientes;
    }

    public function codproduc($id)
    {
        $sel = DB::select("SELECT num_orden FROM proceso_produccion  WHERE id_empresa = $id ORDER BY  num_orden DESC LIMIT 1;");
        $principal = "";
        if (count($sel) == 1) {
            $dato = $sel[0]->num_orden;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return [
            "num_orden" => $principal
        ];
    }

    public function stockbodegaingrediente(Request $request)
    {
        $bodegas = Bodega::select("id_bodega", "nombre", "id_plan_cuentas", "id_establecimiento", "id_empresa")->where("id_empresa", "=", $request->id_empresa)->where("id_establecimiento", "=", $request->id_establecimiento)->get();
        for ($i = 0; $i < count($bodegas); $i++) {
            $stock = ProductoBodega::select("id_producto_bodega", "id_producto", "cantidad", "costo_unitario", "costo_total")->where("id_bodega", "=", $bodegas[$i]->id_bodega)->where("id_empresa", "=", $bodegas[$i]->id_empresa)->get();
            $bodegas[$i]->stock = $stock;
        }

        return $bodegas;
    }

    public function store(Request $request)
    {
        //codigo produccion
        $sel = DB::select("SELECT num_orden FROM proceso_produccion  WHERE id_empresa = $request->id_empresa ORDER BY  num_orden DESC LIMIT 1;");
        $principal = "";
        if (count($sel) == 1) {
            $dato = $sel[0]->num_orden;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        //Cabecera Orden Produccion
        $proceso = new ProcesoProduccion();
        $proceso->num_orden = $principal;
        $proceso->detalle = $request->detalle;
        $proceso->estado = 1;
        $proceso->fecha_inicio = $request->fecha_inicio;
        $proceso->id_empresa = $request->id_empresa;
        $proceso->id_establecimiento = $request->id_establecimiento;
        $proceso->id_proyecto = $request->id_proyecto;
        $proceso->save();
        //Almacenamiento de Productos
        for ($a = 0; $a < count($request->productos); $a++) {
            $product = new ProcesoProducto();
            $product->id_producto = $request->productos[$a]["id"];
            $product->id_proceso_produccion = $proceso->id_proceso_produccion;
            $product->cantidad = $request->productos[$a]["cant_prod"];
            $product->id_formula_produccion = $request->productos[$a]["id_form_prod"];
            $product->save();
        }
        //validacion si ingredientes son productos para egreso de bodega
        $hayegreso = false;
        for ($i = 0; $i < count($request->ingredientes); $i++) {
            if ($request->ingredientes[$i]["sector"] == 1) {
                $hayegreso = true;
            }
        }
        // if ($hayegreso == true) {
        //     //traer numero egreso
        //     $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $request->id_empresa ORDER BY  num_egreso DESC LIMIT 1;");
        //     $numeroegreso = "";
        //     if (count($numegre) == 1) {
        //         $dato = $numegre[0]->num_egreso;
        //         $tot = $dato + 1;
        //         $numeroegreso = $tot;
        //     } else {
        //         $numeroegreso = 1;
        //     }
        //     //egreso encabezado
        //     $egreso = new BodegaEgreso();
        //     $egreso->num_egreso = $numeroegreso;
        //     $egreso->fecha_egreso = $request->fecha_inicio;
        //     $egreso->tipo_egreso = "Proceso de Producción";
        //     $egreso->observ_egreso = "Orden: {$principal}";
        //     $egreso->id_proyecto = $request->id_proyecto;
        //     $egreso->id_bodega = $request->id_bodega;
        //     $egreso->id_empresa = $request->id_empresa;
        //     $egreso->id_proceso_produccion = $proceso->id_proceso_produccion;
        //     $egreso->save();
        // }
        //Almacenamiento de ingredientes
        for ($a = 0; $a < count($request->ingredientes); $a++) {
            //ingredientes de proceso de produccion
            $ingred = new ProcesoIngrediente();
            $ingred->cantidad_orden = $request->ingredientes[$a]["canti"];
            $ingred->costo_unitario_orden = $request->ingredientes[$a]["costo_unitario"];
            $ingred->id_producto = $request->ingredientes[$a]["id_producto"];
            $ingred->id_proceso_produccion = $proceso->id_proceso_produccion;
            if ($request->ingredientes[$a]["sector"] == 1) {
                if(isset($request->ingredientes[$a]["id_bodega"])){
                    $ingred->id_bodega = $request->ingredientes[$a]["id_bodega"];
                }
                if(isset($request->ingredientes[$a]["id_producto_bodega"])){
                    $ingred->id_producto_bodega = $request->ingredientes[$a]["id_producto_bodega"];
                }
                
            }
            if(isset($request->ingredientes[$a]["id_proyecto"])){
                $ingred->id_proyecto = $request->ingredientes[$a]["id_proyecto"];
            }
            $ingred->save();
            //validar si ingredite es producto paraegresarlo 
            // if ($request->ingredientes[$a]["sector"] == 1) {
            //     //egreso de bodega de ingredientes
            //     $qstock = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->ingredientes[$a]["id_producto"] . " AND `id_bodega` =" . $request->id_bodega);
            //     if (count($qstock) == 1) {
            //         $prb = ProductoBodega::findOrFail($qstock[0]->id_producto_bodega);
            //         $prb->cantidad = $prb->cantidad - $request->ingredientes[$a]["canti"];
            //         $prb->costo_total = $prb->costo_total - ($request->ingredientes[$a]["canti"] * $request->ingredientes[$a]["costo_unitario"]);
            //         if ($prb->cantidad != 0) {
            //             $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
            //         } else {
            //             $prb->costo_unitario = 0;
            //         }
            //         $prb->save();
            //         //bodega egreso detalle
            //         $bed = new BodegaEgresoDetalle();
            //         $bed->cantidad = $request->ingredientes[$a]["canti"];
            //         $bed->costo_unitario = $request->ingredientes[$a]["costo_unitario"];
            //         $bed->costo_total = $request->ingredientes[$a]["canti"] * $request->ingredientes[$a]["costo_unitario"];
            //         $bed->id_bodega_egreso = $egreso->id_bodega_egreso;
            //         $bed->id_producto = $request->ingredientes[$a]["id_producto"];
            //         $bed->id_proyecto = $request->id_proyecto;
            //         $bed->id_proceso_ingrediente = $ingred->id_proceso_ingrediente;
            //         $bed->save();
            //         //edit proceso ingrediente acorde a egreso
            //         $pingred = ProcesoIngrediente::findOrFail($ingred->id_proceso_ingrediente);
            //         $pingred->id_producto_bodega =  $qstock[0]->id_producto_bodega;
            //         $pingred->id_bodega_egreso_detalle_orden =  $bed->id_bodega_egreso_detalle;
            //         $pingred->save();
            //     }
            // }
            $pprod = ProcesoProducto::select("id_proceso_producto")->where('id_proceso_produccion', "=", $proceso->id_proceso_produccion)->get();
            for ($j = 0; $j < count($pprod); $j++) {
                $pcant = new ProcesoCantidad();
                $pcant->cantidad = 0;
                $pcant->id_proceso_produccion = $proceso->id_proceso_produccion;
                $pcant->id_proceso_producto = $pprod[$j]->id_proceso_producto;
                $pcant->id_proceso_ingrediente = $ingred->id_proceso_ingrediente;
                $pcant->save();
            }
        }
        if($hayegreso == true){
            self::CabeceraBodegaEgreso($proceso->id_proceso_produccion,$principal,[]);
        }
        //return $idp;
    }
    public function CabeceraBodegaEgreso($id,$nro,$lotes){
        $hoy = Carbon::now();
        $factura = DB::select("SELECT * from proceso_produccion where id_proceso_produccion=$id");
        $detalle = DB::select("SELECT distinct id_bodega FROM proceso_ingrediente,producto where proceso_ingrediente.id_producto=producto.id_producto and producto.sector=1 and id_proceso_produccion=$id");
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$factura[0]->id_empresa}");
        if (count($detalle) > 0) {
            for ($a = 0; $a < count($detalle); $a++) {
                $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = {$factura[0]->id_empresa} ORDER BY  num_egreso DESC LIMIT 1");
                $numeroingreso = "";
                if (count($numegre) == 1) {
                    $dato = $numegre[0]->num_egreso;
                    $tot = $dato + 1;
                    $numeroingreso = $tot;
                } else {
                    $numeroingreso = 1;
                }
                $egreso = new BodegaEgreso();
                $egreso->num_egreso = $numeroingreso;
                $egreso->fecha_egreso = $hoy;
                $egreso->tipo_egreso = 'Proceso de Producción';
                $egreso->observ_egreso = 'Orden: ' . $nro;
                $egreso->id_empresa = $factura[0]->id_empresa;
                $egreso->id_bodega = $detalle[$a]->id_bodega;
                $egreso->id_proceso_produccion = $id;
                $egreso->save();
            }
        }
        if (count($detalle) > 0) {
            //self::DetalleBodegaIngreso($id, $ingreso->fecha_ingreso, $nro_factura,$productos);
            self::ProductoBodegaEgreso($id, $egreso->fecha_egreso, $factura[0]->num_orden,[]);
        }
    }
    public function ProductoBodegaEgreso($id, $fecha_ingreso, $nro_factura,$productos){

        $bodega_egreso= DB::select("SELECT * FROM bodega_egreso WHERE id_proceso_produccion = $id and observ_egreso like '%Orden:%'");
        $productos=[];
        if(count($bodega_egreso)>0){
            $productos= DB::select("SELECT proceso_ingrediente.* FROM proceso_ingrediente,producto where proceso_ingrediente.id_producto=producto.id_producto and producto.sector=1 and id_proceso_produccion=$id");
        }
        if(count($productos)>0){
            for ($i = 0; $i < count($productos); $i++) {
                $cantidad = $productos[$i]->cantidad_orden;
                $precio = $productos[$i]->costo_unitario_orden;
                $total= $cantidad*$precio;
                $id_producto_bodega = $productos[$i]->id_bodega;
                if (isset($id_producto_bodega) && $id_producto_bodega) {
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cantidad,costo_total=if(cantidad=0 or cantidad is null,0,costo_total-$total),costo_unitario=if(costo_total=0 or costo_total is null,0,costo_total/cantidad) WHERE id_producto = {$productos[$i]->id_producto} and id_bodega={$id_producto_bodega}");
                }
            }
        }

        if(count($productos) > 0){
            self::DetalleBodegaEgreso($id, $fecha_ingreso, $nro_factura,$productos);
        }
    }
    public function DetalleBodegaEgreso($id, $fecha, $nro_factura,$productos)
    {
        $hoy = Carbon::now();
        $detalle = DB::select("SELECT proceso_ingrediente.* FROM proceso_ingrediente,producto where proceso_ingrediente.id_producto=producto.id_producto and producto.sector=1 and id_proceso_produccion=$id");
        if (count($detalle) > 0) {
            for ($a = 0; $a < count($detalle); $a++) {
                //if($detalle[$a]->sector==1){
                    //$observ= 'Factura Compra: ' . $nro_factura;
                    $bodega_ingreso = DB::select("SELECT * from bodega_egreso where id_bodega={$detalle[$a]->id_bodega} and id_proceso_produccion=$id and observ_egreso like '%Orden:%'");
                    
                    $costo_unit=$detalle[$a]->costo_unitario_orden;
                    
                    $bed = new BodegaEgresoDetalle();
                    $bed->cantidad = $detalle[$a]->cantidad_orden;
                    $bed->costo_unitario = $costo_unit;
                    $bed->costo_total = $bed->cantidad * $bed->costo_unitario;

                    $bed->id_bodega_egreso = $bodega_ingreso[0]->id_bodega_egreso;
                    $bed->id_producto = $detalle[$a]->id_producto;
                    $bed->id_proceso_ingrediente = $detalle[$a]->id_proceso_ingrediente;
                    $bed->id_proyecto = $detalle[$a]->id_proyecto;
                    $bed->save();
                    //ingreso ProductoBodegaLotes
                    // $id_bi=$bodega_ingreso[0]->id_bodega_ingreso;
                    // $id_bid=$bed->id_bodega_ingreso_detalle;
                    // $id_producto=$detalle[$a]->id_producto;
                    // $id_producto_bodega=$detalle[$a]->id_producto_bodega;
                    // for ($i = 0; $i < count($productos); $i++) {
                    //     if($productos[$i]["id_producto"]==$detalle[$a]->id_producto && $productos[$i]["id_proyecto"]==$detalle[$a]->id_proyecto && $productos[$i]["id_bodega"]==$detalle[$a]->id_bodega){
                    //         $pbl = new ProductoBodegaLotes();
                    //         $pbl->nombre = $productos[$i]["lote"];
                    //         $pbl->cantidad_original = $productos[$i]["cantidad"];
                    //         $pbl->cantidad_real = $productos[$i]["cantidad"];
                    //         $pbl->fecha_fabricacion = $productos[$i]["fecha_fabricacion"];
                    //         $pbl->fecha_vencimiento = $productos[$i]["fecha_vencimiento"];
                    //         $pbl->id_producto=$detalle[$a]->id_producto;
                    //         $pbl->id_producto_bodega=$detalle[$a]->id_producto_bodega;
                    //         $pbl->id_bodega_ingreso=$bodega_ingreso[0]->id_bodega_ingreso;
                    //         $pbl->id_bodega_ingreso_detalle=$id_bid;
                    //         $pbl->save();
                    //     }
                        
                    // }
                    
            }
            
            
        }

        //DB::delete("DELETE FROM bodega_ingreso WHERE id_factura_compra = $id");

    }
    public function getorden($id)
    {
        $form = ProcesoProduccion::select("*")->where('id_proceso_produccion', "=", $id)->get();
        $productos = ProcesoProducto::select("proceso_producto.id_proceso_producto", "proceso_producto.cantidad as cant_prod", "proceso_producto.costo_unitario", "proceso_producto.costo_total", "proceso_producto.id_formula_produccion", "producto.id_producto as id", "producto.cod_principal as cod_principal", "producto.cod_alterno as cod_alterno", "producto.nombre as nombre", "producto.form_prod as form_prod", "proceso_producto.id_bodega")
            ->join("producto", "producto.id_producto", "=", "proceso_producto.id_producto")
            ->where('id_proceso_produccion', "=", $id)->get();
        $ingred = ProcesoIngrediente::select("proceso_ingrediente.*", "proceso_ingrediente.cantidad_orden as canti", "proceso_ingrediente.costo_unitario_orden as costo_unitario", "producto.cod_principal as cod_principal",  "producto.cod_alterno as cod_alterno", "producto.nombre as nombre", "producto.sector as sector", "producto.sector as proceso_cantidad")
            ->join("producto", "producto.id_producto", "=", "proceso_ingrediente.id_producto")
            ->where('id_proceso_produccion', "=", $id)->get();
        $cant = ProcesoCantidad::select("proceso_cantidad.*", "producto.cod_principal as cod_principal",  "producto.cod_alterno as cod_alterno", "producto.nombre as nombre")
            ->join("proceso_producto", "proceso_producto.id_proceso_producto", "=", "proceso_cantidad.id_proceso_producto")
            ->join("producto", "producto.id_producto", "=", "proceso_producto.id_producto")
            ->where('proceso_cantidad.id_proceso_produccion', "=", $id)->get();
        //agrega la procesos_cantidad al ingrediente correspondiente
        for ($i = 0; $i < count($ingred); $i++) {
            $array = [];
            for ($j = 0; $j < count($cant); $j++) {

                if ($ingred[$i]->id_proceso_ingrediente == $cant[$j]->id_proceso_ingrediente) {
                    array_push($array, $cant[$j]);
                }
            }
            $ingred[$i]->proceso_cantidad = $array;
        }
        //si ya se hace liquidacion calcula costos de $productos
        if ($form[0]->estado == 2) {
            // for ($j = 0; $j < count($productos); $j++) {
            //     $formula = FormulaIngrediente::select("*")->where("formula_ingrediente.id_formula_produccion", "=", $productos[$j]->id_formula_produccion)->get();
            //     for ($r = 0; $r < count($ingred); $r++) {
            //         for ($e = 0; $e < count($formula); $e++) {                          
            //             if ($ingred[$r]->id_producto == $formula[$e]->id_producto) {
            //                 $productos[$j]->costo_total += ($ingred[$r]->costo_unitario_orden * $formula[$e]->cant_unit_prod) * $productos[$j]->cant_prod;
            //             }
            //         }
            //         for ($q = 0; $q < count($ingred[$r]->proceso_cantidad); $q++) {
            //             if ($ingred[$r]->proceso_cantidad[$q]["id_proceso_producto"] == $productos[$j]->id_proceso_producto) {
            //                 $productos[$j]->costo_total += ($ingred[$r]->costo_unitario_produccion * $ingred[$r]->proceso_cantidad[$q]["cantidad"]);
            //             }
            //         }
            //         $productos[0]->costo_total += ($ingred[$r]->cantidad_liquidacion * $ingred[$r]->costo_unitario_liquidacion);

            //     }
            //     $productos[$j]->costo_unitario = $productos[$j]->costo_total / $productos[$j]->cant_prod;
            // }

            for ($r = 0; $r < count($ingred); $r++) {
                $productos[0]->costo_total += ($ingred[$r]->cantidad_liquidacion * $ingred[$r]->costo_unitario_liquidacion);
            }
            $productos[0]->costo_unitario = $productos[0]->costo_total / $productos[0]->cant_prod;
        }
        //si ya esta liquidado
        if($form[0]->estado == 3){
            //agregar productos e ingredientes en un solo array
            $form[0]->productos = $productos;
            $form[0]->ingredientes = $ingred;
            if(count($productos)>0){
                $form[0]->id_bodega = $productos[0]->id_bodega;
            }else{
                $form[0]->id_bodega = '';
            }
            
        }else{
            //agregar productos e ingredientes en un solo array
            $form[0]->productos = $productos;
            $form[0]->ingredientes = $ingred;
        }
        
        return $form[0];
    }
}
