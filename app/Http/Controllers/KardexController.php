<?php

namespace App\Http\Controllers;

include 'class/generarReportes.php';

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;
use Barryvdh\DomPDF\Facade as PDF;
use generarReportes;

class KardexController extends Controller
{
    public function index(Request $request, $id)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where('sector', '=', 1)
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
                ->where('sector', '=', 1)
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_producto DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    //productos filtrados
    public function ProductFilter(Request $request)
    {
        $query = "SELECT * FROM producto WHERE id_empresa = {$request->id_empresa} AND sector = 1 ";
        if ($request->id_linea != 0) {
            $query .= " AND id_linea_producto = {$request->id_linea}";
        }
        if ($request->id_tipo != 0) {
            $query .= " AND id_tipo_producto = {$request->id_tipo}";
        }
        if ($request->id_marca != 0) {
            $query .= " AND id_marca = {$request->id_marca}";
        }
        if ($request->id_modelo != 0) {
            $query .= " AND id_modelo = {$request->id_modelo}";
        }
        if ($request->id_presentacion != 0) {
            $query .= " AND id_presentacion = {$request->id_presentacion}";
        }
        $recupera = DB::select($query);
        return  $recupera;
    }
    //bodega filtrado
    public function BodegaFilter(Request $request)
    {
        $query = "SELECT b.id_bodega, b.nombre FROM producto_bodega pb INNER JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE pb.id_empresa = {$request->id_empresa}";
        if ($request->id_producto != 0) {
            $query .= " AND id_producto = {$request->id_producto}";
        }
        $recupera = DB::select($query);
        return  $recupera;
    }
    //generar kardex
    public function generarReporte(Request $request)
    {
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit','4000M');
        $request = json_decode($request->data);
        $kardex = [];
        $qproductos = $request->products;
        $qbodega  = $request->bodega;
        $qlineaprod = $request->linea_product;
        $qtipoprod = $request->tipo_product;
        $qmarca = $request->marca;
        $qmodelo = $request->modelo;
        $qpresentacion = $request->presentacion;
        $qempresa = $request->company;
        //productos
        $queryproductos = "SELECT pb.id_producto_bodega, pb.id_producto, p.cod_principal, p.cod_alterno, p.nombre AS nombre_producto, p.existencia_minima, p.existencia_maxima, pb.id_bodega, b.nombre AS nombre_bodega, pb.cantidad, pb.costo_unitario, pb.costo_total FROM producto_bodega pb  JOIN bodega b ON pb.id_bodega = b.id_bodega JOIN producto p ON pb.id_producto = p.id_producto WHERE pb.id_empresa = {$qempresa} ";

        if ($qbodega->id != 0) {
            $queryproductos .= "AND pb.id_bodega = {$qbodega->id} ";
        }
        if ($qproductos->id != 0) {
            $queryproductos .= "AND pb.id_producto = {$qproductos->id} ";
        } else {
            if ($qlineaprod->id != 0) {
                $queryproductos .= "AND p.id_linea_producto = {$qlineaprod->id} ";
            }
            if ($qtipoprod->id != 0) {
                $queryproductos .= "AND p.id_tipo_producto = {$qtipoprod->id} ";
            }
            if ($qmarca->id != 0) {
                $queryproductos .= "AND p.id_marca = {$qmarca->id} ";
            }
            if ($qmodelo->id != 0) {
                $queryproductos .= "AND p.id_modelo = {$qmodelo->id} ";
            }
            if ($qpresentacion->id != 0) {
                $queryproductos .= "AND p.id_presentacion = {$qpresentacion->id} ";
            }
        }
        $kardex = DB::select($queryproductos);
        //transacciones
        for ($i = 0; $i < count($kardex); $i++) {
            $querytransacciones = "SELECT bi.id_bodega_ingreso AS id_transaccion,bid.id_bodega_ingreso_detalle as detalle, bi.num_ingreso AS numero_transaccion,'Ingreso' AS tipo, IF(bi.id_factura_compra IS NOT NULL, fc.fech_emision, SUBSTR(bi.fecha_ingreso, 1, 10)) AS fecha_transaccion, bi.fcrea AS fecha_aux, bi.tipo_ingreso AS tipo_transaccion, bi.observ_ingreso AS documento, bid.cantidad AS cantidad_ingreso, bid.costo_unitario AS costo_unitario_ingreso, bid.costo_total AS costo_total_ingreso, NULL AS cantidad_egreso, NULL AS costo_unitario_egreso, NULL AS costo_total_egreso, NULL AS cantidad_saldo, NULL AS costo_unitario_saldo, NULL AS costo_total_saldo, 
            CASE WHEN bi.id_importacion IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN proveedor_importacion pi ON p.id_proveedor = pi.id_proveedor WHERE pi.id_importacion = bi.id_importacion limit 1) ELSE 
            CASE WHEN bi.id_factura IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN factura f ON c.id_cliente = f.id_cliente WHERE f.id_factura = bi.id_factura) ELSE
            CASE WHEN bi.id_nota_credito IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_credito nc ON c.id_cliente = nc.id_cliente WHERE nc.id_nota_credito = bi.id_nota_credito) ELSE
            CASE WHEN bi.id_nota_venta IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_venta nv ON c.id_cliente = nv.id_cliente WHERE nv.id_nota_venta = bi.id_nota_venta) ELSE
            CASE WHEN bi.id_factura_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN factura_compra fc ON p.id_proveedor = fc.id_proveedor WHERE fc.id_factcompra = bi.id_factura_compra) ELSE
            CASE WHEN bi.id_nota_credito_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN nota_credito_compra ncc ON p.id_proveedor = ncc.id_proveedor WHERE ncc.id_nota_credito_compra = bi.id_nota_credito_compra) ELSE 
            NULL END END END END END END AS agente
            FROM bodega_ingreso bi
            INNER JOIN bodega_ingreso_detalle bid ON bi.id_bodega_ingreso = bid.id_bodega_ingreso
            LEFT JOIN factura_compra fc ON bi.id_factura_compra=fc.id_factcompra
            WHERE  bid.id_producto = {$kardex[$i]->id_producto} AND bi.id_bodega = {$kardex[$i]->id_bodega}
            UNION
            SELECT be.id_bodega_egreso AS id_transaccion,bed.id_bodega_egreso_detalle as detalle, be.num_egreso AS numero_transaccion, 'Egreso' AS tipo, SUBSTR(be.fcrea, 1, 10) AS fecha_transaccion, be.fcrea AS fecha_aux, be.tipo_egreso AS tipo_transaccion, be.observ_egreso AS documento, NULL AS cantidad_ingreso, NULL AS costo_unitario_ingreso, NULL AS costo_total_ingreso, bed.cantidad AS cantidad_egreso, bed.costo_unitario AS costo_unitario_egreso, bed.costo_total AS costo_total_egreso, NULL AS cantidad_saldo, NULL AS costo_unitario_saldo, NULL AS costo_total_saldo,
            CASE WHEN be.id_factura IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN factura f ON c.id_cliente = f.id_cliente WHERE f.id_factura = be.id_factura) ELSE 
            CASE WHEN be.id_nota_credito IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_credito nc ON c.id_cliente = nc.id_cliente WHERE nc.id_nota_credito = be.id_nota_credito) ELSE 
            CASE WHEN be.id_factura_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN factura_compra fc ON p.id_proveedor = fc.id_proveedor WHERE fc.id_factcompra = be.id_factura_compra) ELSE
            CASE WHEN be.id_nota_credito_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN nota_credito_compra ncc ON p.id_proveedor = ncc.id_proveedor WHERE ncc.id_nota_credito_compra = be.id_nota_credito_compra) ELSE
            CASE WHEN be.id_nota_venta IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_venta nv ON c.id_cliente = nv.id_cliente WHERE nv.id_nota_venta = be.id_nota_venta) ELSE 
            NULL END END END END END  AS agente
            FROM bodega_egreso be
            INNER JOIN bodega_egreso_detalle bed ON be.id_bodega_egreso = bed.id_bodega_egreso
            WHERE bed.id_producto = {$kardex[$i]->id_producto} AND be.id_bodega = {$kardex[$i]->id_bodega}
            ORDER BY fecha_transaccion, fecha_aux ASC;";
            //dd($querytransacciones);
            $transacciones = DB::select($querytransacciones);
            for ($j = 0; $j < count($transacciones); $j++) {
                if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste", "Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                    $transacciones[$j]->documento = "";
                }
                if ($j == 0) {
                    if ($transacciones[$j]->tipo == "Ingreso") {
                        $transacciones[$j]->costo_total_ingreso = round($transacciones[$j]->cantidad_ingreso * $transacciones[$j]->costo_unitario_ingreso, 6);
                        $transacciones[$j]->cantidad_saldo = $transacciones[$j]->cantidad_ingreso;
                        $transacciones[$j]->costo_unitario_saldo = $transacciones[$j]->costo_unitario_ingreso;
                        $transacciones[$j]->costo_total_saldo = $transacciones[$j]->costo_total_ingreso;
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste"))) {
                            $transacciones[$j]->documento = "Ingreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    } elseif ($transacciones[$j]->tipo == "Egreso") {
                        $transacciones[$j]->cantidad_saldo = round((0) - ($transacciones[$j]->cantidad_egreso), 6);
                        $transacciones[$j]->costo_total_saldo = round((0) - ($transacciones[$j]->costo_total_egreso), 6);
                        if ($transacciones[$j]->cantidad_saldo != 0) {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                            $transacciones[$j]->documento = "Egreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    }
                } else {
                    if ($transacciones[$j]->tipo == "Ingreso") {
                        $transacciones[$j]->costo_total_ingreso = round($transacciones[$j]->cantidad_ingreso * $transacciones[$j]->costo_unitario_ingreso, 6);
                        $transacciones[$j]->cantidad_saldo = round(($transacciones[$j - 1]->cantidad_saldo) + ($transacciones[$j]->cantidad_ingreso), 6);
                        $transacciones[$j]->costo_total_saldo = round(($transacciones[$j - 1]->costo_total_saldo) + ($transacciones[$j]->costo_total_ingreso), 6);
                        if ($transacciones[$j]->cantidad_saldo != 0) {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste"))) {
                            $transacciones[$j]->documento = "Ingreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    } elseif ($transacciones[$j]->tipo == "Egreso") {
                        if(preg_match("/Eliminacion Factura Compra/", $transacciones[$j]->documento)){
                            $nfactura = explode("Eliminacion Factura Compra: ", $transacciones[$j]->documento)[0];
                            for ($k = 0; $k < count($transacciones); $k++) {
                                if($transacciones[$k]->documento == "Factura Compra: " . $nfactura && $transacciones[$k]->agente == null){
                                    $transacciones[$j]->costo_unitario_egreso = $transacciones[$k]->costo_unitario_ingreso;
                                }
                            }
                        }
                        else{
                            $transacciones[$j]->costo_unitario_egreso = $transacciones[$j-1]->costo_unitario_saldo;
                        }
                        $transacciones[$j]->costo_total_egreso = round($transacciones[$j]->cantidad_egreso * $transacciones[$j]->costo_unitario_egreso, 6);
                        $transacciones[$j]->cantidad_saldo = round(($transacciones[$j - 1]->cantidad_saldo) - ($transacciones[$j]->cantidad_egreso), 6);
                        $transacciones[$j]->costo_total_saldo = round(($transacciones[$j - 1]->costo_total_saldo) - ($transacciones[$j]->costo_total_egreso), 6);
                        if ($transacciones[$j]->cantidad_saldo == 0) {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                            $transacciones[$j]->documento = "Egreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    }
                }
                $kardex[$i]->cantidad = $transacciones[$j]->cantidad_saldo;
                $kardex[$i]->costo_unitario = $transacciones[$j]->costo_unitario_saldo;
                $kardex[$i]->costo_total = $transacciones[$j]->costo_total_saldo;
            }
            $kardex[$i]->transacciones = $transacciones;
        }
        //funcionaliudad CEFUEGOS codigo alterno de producto visualzicion en kardex
        if ($qempresa == 7 || $qempresa == 9 || $qempresa == 10 || $qempresa == 71) {
            $empres = DB::select("SELECT nombre_empresa FROM empresa WHERE id_empresa = ${qempresa}");
            if (in_array($empres[0]->nombre_empresa, array("C.E. FUEGOS GROUP", "C.E.FUEGOS SYSTEM", "C.E. FUEGOS", "CORTINAS DLUXE"))) {
                for ($z = 0; $z < count($kardex); $z++) {
                    $kardex[$z]->cod_principal = $kardex[$z]->cod_alterno;
                }
            }
        }

        //Nueva version kardex con fpdf
        $Reportes = new generarReportes();
        $strPDF = $Reportes->PDFKardex($kardex);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        //Antigua version kardex con dompdf
        /*$pdf = PDF::loadView('pdf.kardex', compact('kardex'))->setPaper('a4', 'landscape');
        return $pdf->stream('karex.pdf');*/
    }

    public function kardexNegativo(){
        ini_set('max_execution_time', 53200);
        ini_set('memory_limit','4000M');

        $kardex = [];
        $kardexNegativo = [];
        $qempresa = 71;
        //productos
        $queryproductos = "SELECT pb.id_producto_bodega, pb.id_producto, p.cod_principal, p.cod_alterno, p.nombre AS nombre_producto, p.existencia_minima, p.existencia_maxima, pb.id_bodega, b.nombre AS nombre_bodega, pb.cantidad, pb.costo_unitario, pb.costo_total FROM producto_bodega pb  JOIN bodega b ON pb.id_bodega = b.id_bodega JOIN producto p ON pb.id_producto = p.id_producto WHERE pb.id_empresa = {$qempresa} ";

        $kardex = DB::select($queryproductos);
        //transacciones
        for ($i = 0; $i < count($kardex); $i++) {
            $querytransacciones = "SELECT bi.id_bodega_ingreso AS id_transaccion,bid.id_bodega_ingreso_detalle as detalle, bi.num_ingreso AS numero_transaccion,'Ingreso' AS tipo, IF(bi.id_factura_compra IS NOT NULL, fc.fech_emision, SUBSTR(bi.fecha_ingreso, 1, 10)) AS fecha_transaccion, bi.fcrea AS fecha_aux, bi.tipo_ingreso AS tipo_transaccion, bi.observ_ingreso AS documento, bid.cantidad AS cantidad_ingreso, bid.costo_unitario AS costo_unitario_ingreso, bid.costo_total AS costo_total_ingreso, NULL AS cantidad_egreso, NULL AS costo_unitario_egreso, NULL AS costo_total_egreso, NULL AS cantidad_saldo, NULL AS costo_unitario_saldo, NULL AS costo_total_saldo, 
            CASE WHEN bi.id_importacion IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN proveedor_importacion pi ON p.id_proveedor = pi.id_proveedor WHERE pi.id_importacion = bi.id_importacion limit 1) ELSE 
            CASE WHEN bi.id_factura IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN factura f ON c.id_cliente = f.id_cliente WHERE f.id_factura = bi.id_factura) ELSE
            CASE WHEN bi.id_nota_credito IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_credito nc ON c.id_cliente = nc.id_cliente WHERE nc.id_nota_credito = bi.id_nota_credito) ELSE
            CASE WHEN bi.id_nota_venta IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_venta nv ON c.id_cliente = nv.id_cliente WHERE nv.id_nota_venta = bi.id_nota_venta) ELSE
            CASE WHEN bi.id_factura_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN factura_compra fc ON p.id_proveedor = fc.id_proveedor WHERE fc.id_factcompra = bi.id_factura_compra) ELSE
            CASE WHEN bi.id_nota_credito_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN nota_credito_compra ncc ON p.id_proveedor = ncc.id_proveedor WHERE ncc.id_nota_credito_compra = bi.id_nota_credito_compra) ELSE 
            NULL END END END END END END AS agente
            FROM bodega_ingreso bi
            INNER JOIN bodega_ingreso_detalle bid ON bi.id_bodega_ingreso = bid.id_bodega_ingreso
            LEFT JOIN factura_compra fc ON bi.id_factura_compra=fc.id_factcompra
            WHERE  bid.id_producto = {$kardex[$i]->id_producto} AND bi.id_bodega = {$kardex[$i]->id_bodega}
            UNION
            SELECT be.id_bodega_egreso AS id_transaccion,bed.id_bodega_egreso_detalle as detalle, be.num_egreso AS numero_transaccion, 'Egreso' AS tipo, SUBSTR(be.fcrea, 1, 10) AS fecha_transaccion, be.fcrea AS fecha_aux, be.tipo_egreso AS tipo_transaccion, be.observ_egreso AS documento, NULL AS cantidad_ingreso, NULL AS costo_unitario_ingreso, NULL AS costo_total_ingreso, bed.cantidad AS cantidad_egreso, bed.costo_unitario AS costo_unitario_egreso, bed.costo_total AS costo_total_egreso, NULL AS cantidad_saldo, NULL AS costo_unitario_saldo, NULL AS costo_total_saldo,
            CASE WHEN be.id_factura IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN factura f ON c.id_cliente = f.id_cliente WHERE f.id_factura = be.id_factura) ELSE 
            CASE WHEN be.id_nota_credito IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_credito nc ON c.id_cliente = nc.id_cliente WHERE nc.id_nota_credito = be.id_nota_credito) ELSE 
            CASE WHEN be.id_factura_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN factura_compra fc ON p.id_proveedor = fc.id_proveedor WHERE fc.id_factcompra = be.id_factura_compra) ELSE
            CASE WHEN be.id_nota_credito_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN nota_credito_compra ncc ON p.id_proveedor = ncc.id_proveedor WHERE ncc.id_nota_credito_compra = be.id_nota_credito_compra) ELSE
            CASE WHEN be.id_nota_venta IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_venta nv ON c.id_cliente = nv.id_cliente WHERE nv.id_nota_venta = be.id_nota_venta) ELSE 
            NULL END END END END END  AS agente
            FROM bodega_egreso be
            INNER JOIN bodega_egreso_detalle bed ON be.id_bodega_egreso = bed.id_bodega_egreso
            WHERE bed.id_producto = {$kardex[$i]->id_producto} AND be.id_bodega = {$kardex[$i]->id_bodega}
            ORDER BY fecha_transaccion, fecha_aux ASC;";
            //dd($querytransacciones);
            $transacciones = DB::select($querytransacciones);
            for ($j = 0; $j < count($transacciones); $j++) {
                if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste", "Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                    $transacciones[$j]->documento = "";
                }
                if ($j == 0) {
                    if ($transacciones[$j]->tipo == "Ingreso") {
                        $transacciones[$j]->costo_total_ingreso = round($transacciones[$j]->cantidad_ingreso * $transacciones[$j]->costo_unitario_ingreso, 6);
                        $transacciones[$j]->cantidad_saldo = $transacciones[$j]->cantidad_ingreso;
                        $transacciones[$j]->costo_unitario_saldo = $transacciones[$j]->costo_unitario_ingreso;
                        $transacciones[$j]->costo_total_saldo = $transacciones[$j]->costo_total_ingreso;
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste"))) {
                            $transacciones[$j]->documento = "Ingreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    } elseif ($transacciones[$j]->tipo == "Egreso") {
                        $transacciones[$j]->cantidad_saldo = round((0) - ($transacciones[$j]->cantidad_egreso), 6);
                        $transacciones[$j]->costo_total_saldo = round((0) - ($transacciones[$j]->costo_total_egreso), 6);
                        if ($transacciones[$j]->cantidad_saldo != 0) {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                            $transacciones[$j]->documento = "Egreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    }
                } else {
                    if ($transacciones[$j]->tipo == "Ingreso") {
                        $transacciones[$j]->costo_total_ingreso = round($transacciones[$j]->cantidad_ingreso * $transacciones[$j]->costo_unitario_ingreso, 6);
                        $transacciones[$j]->cantidad_saldo = round(($transacciones[$j - 1]->cantidad_saldo) + ($transacciones[$j]->cantidad_ingreso), 6);
                        $transacciones[$j]->costo_total_saldo = round(($transacciones[$j - 1]->costo_total_saldo) + ($transacciones[$j]->costo_total_ingreso), 6);
                        if ($transacciones[$j]->cantidad_saldo != 0) {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste"))) {
                            $transacciones[$j]->documento = "Ingreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    } elseif ($transacciones[$j]->tipo == "Egreso") {
                        if(preg_match("/Eliminacion Factura Compra/", $transacciones[$j]->documento)){
                            $nfactura = explode("Eliminacion Factura Compra: ", $transacciones[$j]->documento)[0];
                            for ($k = 0; $k < count($transacciones); $k++) {
                                if($transacciones[$k]->documento == "Factura Compra: " . $nfactura && $transacciones[$k]->agente == null){
                                    $transacciones[$j]->costo_unitario_egreso = $transacciones[$k]->costo_unitario_ingreso;
                                }
                            }
                        }
                        else{
                            $transacciones[$j]->costo_unitario_egreso = $transacciones[$j-1]->costo_unitario_saldo;
                        }
                        $transacciones[$j]->costo_total_egreso = round($transacciones[$j]->cantidad_egreso * $transacciones[$j]->costo_unitario_egreso, 6);
                        $transacciones[$j]->cantidad_saldo = round(($transacciones[$j - 1]->cantidad_saldo) - ($transacciones[$j]->cantidad_egreso), 6);
                        $transacciones[$j]->costo_total_saldo = round(($transacciones[$j - 1]->costo_total_saldo) - ($transacciones[$j]->costo_total_egreso), 6);
                        if ($transacciones[$j]->cantidad_saldo == 0) {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                            $transacciones[$j]->documento = "Egreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    }
                }
                $kardex[$i]->cantidad = $transacciones[$j]->cantidad_saldo;
                $kardex[$i]->costo_unitario = $transacciones[$j]->costo_unitario_saldo;
                $kardex[$i]->costo_total = $transacciones[$j]->costo_total_saldo;
            }
            $kardex[$i]->transacciones = $transacciones;

        }
        if ($qempresa == 7 || $qempresa == 9 || $qempresa == 10 || $qempresa == 71) {
            $empres = DB::select("SELECT nombre_empresa FROM empresa WHERE id_empresa = ${qempresa}");
            if (in_array($empres[0]->nombre_empresa, array("C.E. FUEGOS GROUP", "C.E.FUEGOS SYSTEM", "C.E. FUEGOS", "CORTINAS DLUXE"))) {
                for ($z = 0; $z < count($kardex); $z++) {
                    $kardex[$z]->cod_principal = $kardex[$z]->cod_alterno;
                }
            }
        }

        for($i=0; $i<count($kardex); $i++){
            $esNegativo = false;
            for($j=0; $j<count($kardex[$i]->transacciones); $j++){
                if($kardex[$i]->transacciones[$j]->cantidad_saldo<0 || $kardex[$i]->transacciones[$j]->costo_total_saldo<0){
                    $esNegativo = true;
                }
            }

            if($esNegativo){
                array_push($kardexNegativo, $kardex[$i]);
            }
        }

        foreach ($kardexNegativo as $kardexs){

            ?>

            <table BORDER="1">
        
                <tr>
                    <td bgcolor="#ffa959" colspan="13" width="1100" ALIGN="CENTER" size="12">
                        <b>KARDEX NEGATIVO</b>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffc590" colspan="7" width="550" ALIGN="CENTER" size="7"><b>PRODUCTO: <?= strtoupper($kardexs->cod_principal) . ' - ' . strtoupper($kardexs->nombre_producto); ?> </b></td>
                    <td bgcolor="#ffc590" colspan="6" width="550" ALIGN="CENTER" size="7"><b>BODEGA: <?= strtoupper($kardexs->nombre_bodega) ?> </b></td>
                </tr>
                <tr>
                    <td width="550" colspan="7" ALIGN="CENTER" size="7"><b>EXISTENCIA MÍNIMA: <?= $kardexs->existencia_minima ?> </b></td>
                    <td width="550" colspan="6" ALIGN="CENTER" size="7"><b>EXISTENCIA MÁXIMA: <?= $kardexs->existencia_maxima ?> </b></td>
                </tr>
                <tr>
                    <td bgcolor="#ffca99" colspan="5" width="366.66" ALIGN="CENTER" size="7"><b>CANTIDAD: <?= number_format($kardexs->cantidad, 6, ".", ",") ?> </b></td>
                    <td bgcolor="#ffca99" colspan="4" width="366.66" ALIGN="CENTER" size="7"><b>COSTO UNITARIO: <?= number_format($kardexs->costo_unitario, 6, ".", ",") ?> </b></td>
                    <td bgcolor="#ffca99" colspan="4" width="366.66" ALIGN="CENTER" size="7"><b>COSTO TOTAL: <?= number_format($kardexs->costo_total, 6, ".", ",") ?> </b></td>
                </tr>
                <br>
                <tr>
                    <td bgcolor="#ffa959" colspan="4" width="440" ALIGN="CENTER" size="7"><b>INFORMACIÓN</b></td>
                    <td bgcolor="#ffa959" colspan="3" width="220" ALIGN="CENTER" size="7"><b>INGRESOS</b></td>
                    <td bgcolor="#ffa959" colspan="3" width="220" ALIGN="CENTER" size="7"><b>EGRESOS</b></td>
                    <td bgcolor="#ffa959" colspan="3" width="220" ALIGN="CENTER" size="7"><b>SALDOS</b></td>
                </tr>
                <tr>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="28.35">No.</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.35">Fecha</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="195">Transaccion</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="143.35">Proveedor o Cliente</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33">Cantidad</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33" style="font-size: 45%;">Costo Unitario</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33">Costo Total</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33">Cantidad</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33" style="font-size: 45%;">Costo Unitario</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33">Costo Total</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33">Cantidad</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33" style="font-size: 45%;">Costo Unitario</td>
                    <td ALIGN="CENTER" bgcolor="#ffc590" width="73.33">Costo Total</td>
                </tr>

                <?php
                $i=0;
                foreach ($kardexs->transacciones as $trans){
    
                    if($trans->cantidad_saldo<0 || $trans->costo_total_saldo<0){
                        $color = "#d9b723";
                    }
                    else{
                        if (($i % 2) == 0) {
                            $color = "#ffffff";
                        }
                        else{
                            $color = "#d8d8d8";
                        }
                    }
                    

                    ?>
    
                    <tr>
                        <td width="28.35" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>"> <?= $trans->numero_transaccion ?> </td>
                        <td width="73.35" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>"> <?= explode(" ", $trans->fecha_transaccion)[0] ?> </td>
                        <td width="195" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>"> <?= $trans->documento ?> </td>
                        <?php
                        if ($trans->agente !=null){
                            ?>
                            <td width="143.35" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>"><?= $trans->agente ?></td>
                            <?php
                        }
                        else{
                            ?>
                            <td width="143.35" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>"><?= $trans->tipo_transaccion ?></td>
                            <?php
                        }
                        if($trans->cantidad_ingreso !=null){
                            ?>
                            <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->cantidad_ingreso, 6, ".", ",") ?></td>
                            <?php
                        }
                        else{
                            ?>
                            <td width="73.33" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>">&nbsp;</td>
                            <?php
                        }
                        if ($trans->costo_unitario_ingreso !=null){
                            ?>
                            <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->costo_unitario_ingreso, 6, ".", ",") ?></td>
                            <?php
                        }
                        else{
                            ?>
                            <td width="73.33" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>">&nbsp;</td>
                            <?php
                        }
                        if($trans->costo_total_ingreso !=null){
                            ?>
                            <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->costo_total_ingreso, 6, ".", ",") ?></td>
                            <?php
                        }
                        else{
                            ?>
                            <td width="73.33" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>">&nbsp;</td>
                            <?php
                        }
                        if($trans->cantidad_egreso !=null){
                            ?>
                            <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->cantidad_egreso, 6, ".", ",") ?></td>
                            <?php
                        }
                        else{
                            ?>
                            <td width="73.33" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>">&nbsp;</td>
                            <?php
                        }
                        if ($trans->costo_unitario_egreso !=null){
                            ?>
                            <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->costo_unitario_egreso, 6, ".", ",") ?></td>
                            <?php
                        }
                        else{
                            ?>
                            <td width="73.33" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>">&nbsp;</td>
                            <?php
                        }
                        if($trans->costo_total_egreso !=null){
                            ?>
                            <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->costo_total_egreso, 6, ".", ",") ?></td>
                            <?php
                        }
                        else{
                            ?>
                            <td width="73.33" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>">&nbsp;</td>
                            <?php
                        }
                        ?>
                        <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->cantidad_saldo, 6, ".", ",") ?></td>
                        <?php
                        if ($trans->costo_unitario_saldo !==null){
                            ?>
                            <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->costo_unitario_saldo, 6, ".", ",") ?></td>
                            <?php
                        }
                        else{
                            ?>
                            <td width="73.33" height="50" ALIGN="CENTER" bgcolor="<?= $color ?>">&nbsp;</td>
                            <?php
                        }
                        ?>
                        <td width="73.33" height="50" ALIGN="RIGHT" bgcolor="<?= $color ?>"><?= number_format($trans->costo_total_saldo, 6, ".", ",") ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </table><br><br>
            <?php
        }

    }

    public function kardexregresion(){

        ini_set('max_execution_time', 53200);
        ini_set('memory_limit','4000M');

        $kardex = [];
        $kardexQueries = [];
        $qempresa = 71;
        //productos
        $queryproductos = "SELECT pb.id_producto_bodega, pb.id_producto, p.cod_principal, p.cod_alterno, p.nombre AS nombre_producto, p.existencia_minima, p.existencia_maxima, pb.id_bodega, b.nombre AS nombre_bodega, pb.cantidad, pb.costo_unitario, pb.costo_total FROM producto_bodega pb  JOIN bodega b ON pb.id_bodega = b.id_bodega JOIN producto p ON pb.id_producto = p.id_producto WHERE pb.id_empresa = {$qempresa} ";

        $kardex = DB::select($queryproductos);
        //transacciones
        for ($i = 0; $i < count($kardex); $i++) {
            $querytransacciones = "SELECT bi.id_bodega_ingreso AS id_transaccion,bid.id_bodega_ingreso_detalle as detalle, bi.num_ingreso AS numero_transaccion,'Ingreso' AS tipo, IF(bi.id_factura_compra IS NOT NULL, fc.fech_emision, SUBSTR(bi.fecha_ingreso, 1, 10)) AS fecha_transaccion, bi.fcrea AS fecha_aux, bi.tipo_ingreso AS tipo_transaccion, bi.observ_ingreso AS documento, bid.cantidad AS cantidad_ingreso, bid.costo_unitario AS costo_unitario_ingreso, bid.costo_total AS costo_total_ingreso, NULL AS cantidad_egreso, NULL AS costo_unitario_egreso, NULL AS costo_total_egreso, NULL AS cantidad_saldo, NULL AS costo_unitario_saldo, NULL AS costo_total_saldo, 
            CASE WHEN bi.id_importacion IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN proveedor_importacion pi ON p.id_proveedor = pi.id_proveedor WHERE pi.id_importacion = bi.id_importacion limit 1) ELSE 
            CASE WHEN bi.id_factura IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN factura f ON c.id_cliente = f.id_cliente WHERE f.id_factura = bi.id_factura) ELSE
            CASE WHEN bi.id_nota_credito IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_credito nc ON c.id_cliente = nc.id_cliente WHERE nc.id_nota_credito = bi.id_nota_credito) ELSE
            CASE WHEN bi.id_nota_venta IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_venta nv ON c.id_cliente = nv.id_cliente WHERE nv.id_nota_venta = bi.id_nota_venta) ELSE
            CASE WHEN bi.id_factura_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN factura_compra fc ON p.id_proveedor = fc.id_proveedor WHERE fc.id_factcompra = bi.id_factura_compra) ELSE
            CASE WHEN bi.id_nota_credito_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN nota_credito_compra ncc ON p.id_proveedor = ncc.id_proveedor WHERE ncc.id_nota_credito_compra = bi.id_nota_credito_compra) ELSE 
            NULL END END END END END END AS agente
            FROM bodega_ingreso bi
            INNER JOIN bodega_ingreso_detalle bid ON bi.id_bodega_ingreso = bid.id_bodega_ingreso
            LEFT JOIN factura_compra fc ON bi.id_factura_compra=fc.id_factcompra
            WHERE  bid.id_producto = {$kardex[$i]->id_producto} AND bi.id_bodega = {$kardex[$i]->id_bodega}
            UNION
            SELECT be.id_bodega_egreso AS id_transaccion,bed.id_bodega_egreso_detalle as detalle, be.num_egreso AS numero_transaccion, 'Egreso' AS tipo, SUBSTR(be.fcrea, 1, 10) AS fecha_transaccion, be.fcrea AS fecha_aux, be.tipo_egreso AS tipo_transaccion, be.observ_egreso AS documento, NULL AS cantidad_ingreso, NULL AS costo_unitario_ingreso, NULL AS costo_total_ingreso, bed.cantidad AS cantidad_egreso, bed.costo_unitario AS costo_unitario_egreso, bed.costo_total AS costo_total_egreso, NULL AS cantidad_saldo, NULL AS costo_unitario_saldo, NULL AS costo_total_saldo,
            CASE WHEN be.id_factura IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN factura f ON c.id_cliente = f.id_cliente WHERE f.id_factura = be.id_factura) ELSE 
            CASE WHEN be.id_nota_credito IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_credito nc ON c.id_cliente = nc.id_cliente WHERE nc.id_nota_credito = be.id_nota_credito) ELSE 
            CASE WHEN be.id_factura_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN factura_compra fc ON p.id_proveedor = fc.id_proveedor WHERE fc.id_factcompra = be.id_factura_compra) ELSE
            CASE WHEN be.id_nota_credito_compra IS NOT NULL THEN (SELECT p.nombre_proveedor FROM proveedor p INNER JOIN nota_credito_compra ncc ON p.id_proveedor = ncc.id_proveedor WHERE ncc.id_nota_credito_compra = be.id_nota_credito_compra) ELSE
            CASE WHEN be.id_nota_venta IS NOT NULL THEN (SELECT c.nombre FROM cliente c INNER JOIN nota_venta nv ON c.id_cliente = nv.id_cliente WHERE nv.id_nota_venta = be.id_nota_venta) ELSE 
            NULL END END END END END  AS agente
            FROM bodega_egreso be
            INNER JOIN bodega_egreso_detalle bed ON be.id_bodega_egreso = bed.id_bodega_egreso
            WHERE bed.id_producto = {$kardex[$i]->id_producto} AND be.id_bodega = {$kardex[$i]->id_bodega}
            ORDER BY fecha_transaccion, fecha_aux ASC;";
            //dd($querytransacciones);
            $transacciones = DB::select($querytransacciones);
            for ($j = 0; $j < count($transacciones); $j++) {
                if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste", "Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                    $transacciones[$j]->documento = "";
                }
                if ($j == 0) {
                    if ($transacciones[$j]->tipo == "Ingreso") {
                        $transacciones[$j]->costo_total_ingreso = round($transacciones[$j]->cantidad_ingreso * $transacciones[$j]->costo_unitario_ingreso, 6);
                        $transacciones[$j]->cantidad_saldo = $transacciones[$j]->cantidad_ingreso;
                        $transacciones[$j]->costo_unitario_saldo = $transacciones[$j]->costo_unitario_ingreso;
                        $transacciones[$j]->costo_total_saldo = $transacciones[$j]->costo_total_ingreso;
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste"))) {
                            $transacciones[$j]->documento = "Ingreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    } elseif ($transacciones[$j]->tipo == "Egreso") {
                        $transacciones[$j]->cantidad_saldo = round((0) - ($transacciones[$j]->cantidad_egreso), 6);
                        $transacciones[$j]->costo_total_saldo = round((0) - ($transacciones[$j]->costo_total_egreso), 6);
                        if ($transacciones[$j]->cantidad_saldo != 0) {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                            $transacciones[$j]->documento = "Egreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    }
                } else {
                    if ($transacciones[$j]->tipo == "Ingreso") {
                        $transacciones[$j]->costo_total_ingreso = round($transacciones[$j]->cantidad_ingreso * $transacciones[$j]->costo_unitario_ingreso, 6);
                        $transacciones[$j]->cantidad_saldo = round(($transacciones[$j - 1]->cantidad_saldo) + ($transacciones[$j]->cantidad_ingreso), 6);
                        $transacciones[$j]->costo_total_saldo = round(($transacciones[$j - 1]->costo_total_saldo) + ($transacciones[$j]->costo_total_ingreso), 6);
                        if ($transacciones[$j]->cantidad_saldo != 0) {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Inventario Inicial", "Fabricación", "Ingreso por Ajuste"))) {
                            $transacciones[$j]->documento = "Ingreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    } elseif ($transacciones[$j]->tipo == "Egreso") {
                        if(preg_match("/Eliminacion Factura Compra/", $transacciones[$j]->documento)){
                            $nfactura = explode("Eliminacion Factura Compra: ", $transacciones[$j]->documento)[0];
                            for ($k = 0; $k < count($transacciones); $k++) {
                                if($transacciones[$k]->documento == "Factura Compra: " . $nfactura && $transacciones[$k]->agente == null){
                                    $transacciones[$j]->costo_unitario_egreso = $transacciones[$k]->costo_unitario_ingreso;
                                }
                            }
                        }
                        else{
                            $transacciones[$j]->costo_unitario_egreso = $transacciones[$j-1]->costo_unitario_saldo;
                        }
                        $transacciones[$j]->costo_total_egreso = round($transacciones[$j]->cantidad_egreso * $transacciones[$j]->costo_unitario_egreso, 6);
                        $transacciones[$j]->cantidad_saldo = round(($transacciones[$j - 1]->cantidad_saldo) - ($transacciones[$j]->cantidad_egreso), 6);
                        $transacciones[$j]->costo_total_saldo = round(($transacciones[$j - 1]->costo_total_saldo) - ($transacciones[$j]->costo_total_egreso), 6);
                        if ($transacciones[$j]->cantidad_saldo == 0) {
                            $transacciones[$j]->costo_unitario_saldo = 0.000000;
                        } else {
                            $transacciones[$j]->costo_unitario_saldo = round(($transacciones[$j]->costo_total_saldo) / ($transacciones[$j]->cantidad_saldo), 6);
                        }
                        if (in_array($transacciones[$j]->tipo_transaccion, array("Ventas", "Auto Consumo", "Egreso por Ajuste", "Devolucion por Ingreso"))) {
                            $transacciones[$j]->documento = "Egreso Bodega: {$transacciones[$j]->numero_transaccion}";
                        }
                    }
                }
                $kardex[$i]->cantidad = $transacciones[$j]->cantidad_saldo;
                $kardex[$i]->costo_unitario = $transacciones[$j]->costo_unitario_saldo;
                $kardex[$i]->costo_total = $transacciones[$j]->costo_total_saldo;
            }
            $kardex[$i]->transacciones = $transacciones;

        }
        if ($qempresa == 7 || $qempresa == 9 || $qempresa == 10 || $qempresa == 71) {
            $empres = DB::select("SELECT nombre_empresa FROM empresa WHERE id_empresa = ${qempresa}");
            if (in_array($empres[0]->nombre_empresa, array("C.E. FUEGOS GROUP", "C.E.FUEGOS SYSTEM", "C.E. FUEGOS", "CORTINAS DLUXE"))) {
                for ($z = 0; $z < count($kardex); $z++) {
                    $kardex[$z]->cod_principal = $kardex[$z]->cod_alterno;
                }
            }
        }

        foreach ($kardex as $kardexs){
            $queryaux="UPDATE producto_bodega SET cantidad='" . $kardexs->cantidad . "', costo_unitario='" . $kardexs->costo_unitario . "', costo_total='" . $kardexs->costo_total . "' WHERE id_producto_bodega='" . $kardexs->id_producto_bodega . "'";
            array_push($kardexQueries, $queryaux);
            foreach ($kardexs->transacciones as $trans){
                if($trans->tipo == "Ingreso"){
                    $queryaux="UPDATE bodega_ingreso_detalle SET cantidad='" . $trans->cantidad_ingreso . "', costo_unitario='" . $trans->costo_unitario_ingreso . "', costo_total='" . $trans->costo_total_ingreso . "' WHERE id_bodega_ingreso_detalle='" . $trans->detalle . "'";
                }
                else if($trans->tipo == "Egreso"){
                    $queryaux="UPDATE bodega_egreso_detalle SET cantidad='" . $trans->cantidad_egreso . "', costo_unitario='" . $trans->costo_unitario_egreso . "', costo_total='" . $trans->costo_total_egreso . "' WHERE id_bodega_egreso_detalle='" . $trans->detalle . "'";
                }
                array_push($kardexQueries, $queryaux);
            }
        }

        //echo "Funcional";
        //echo "<br>";
        //$i=1;
        foreach($kardexQueries as $kquery){
            //echo $i;
            //echo "<br>";
            $execquery = DB::unprepared($kquery);
            //$i++;
        }
        //echo count($kardexQueries);

		echo "<h1>Kardex Actualizado Correctamente</h1>";
        /*echo "<pre>";
        print_r($kardexQueries);
        echo "</pre>";*/

    }
}
