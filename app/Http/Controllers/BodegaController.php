<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bodega;
use App\Models\Cliente;

include 'class/generarReportes.php';

use generarReportes;

class BodegaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function codigo($id)
    {
        $selnum = DB::select("SELECT codigo FROM bodega  WHERE id_empresa = $id ORDER BY  codigo DESC LIMIT 1;");
        $principal = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->codigo;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return [
            "codigo" => $principal
        ];
    }
    public function index(Request $request, $id, $ide)
    {
        $buscar = $request->buscar;
        if ($ide == 'any') {

            if ($buscar == '') {
                // $recupera = Bodega::select('bodega.*', 'plan_cuentas.nomcta')->addSelect([
                //         'nomb_departamento' => Departamento::select('dep_nombre')
                //         ->whereColumn('id_departamento', 'area_trabajo.id_departamento')])
                //     ->leftjoin('plan_cuentas', 'bodega.id_plan_cuentas', '=', 'plan_cuentas.id_plan_cuentas')
                //     ->where('bodega.id_empresa', '=', $id)
                //     ->orderByRaw('bodega.id_bodega DESC')->get();
                $recupera =DB::select("SELECT bodega.*,plan_cuentas.nomcta, (select count(*) from bodega_ingreso where id_bodega=bodega.id_bodega) as exist_ingresos
                                        From bodega 
                                        LEFT JOIN plan_cuentas 
                                        ON bodega.id_plan_cuentas=plan_cuentas.id_plan_cuentas
                                        where bodega.id_empresa=$id
                                        Order by bodega.id_bodega DESC");
            } else {
                // $recupera = Bodega::select('bodega.*', 'plan_cuentas.nomcta')
                //     ->leftjoin('plan_cuentas', 'bodega.id_plan_cuentas', '=', 'plan_cuentas.id_plan_cuentas')
                //     ->where(function ($q) use ($buscar) {
                //         $q->where('bodega.codigo', 'like', '%' . $buscar . '%')
                //             ->orWhere('bodega.nombre', 'like', '%' . $buscar . '%')
                //             ->orWhere('bodega.responsable', 'like', '%' . $buscar . '%')
                //             ->orWhere('bodega.ubicacion', 'like', '%' . $buscar . '%')
                //             ->orWhere('bodega.direccion', 'like', '%' . $buscar . '%')
                //             ->orWhere('bodega.telefono', 'like', '%' . $buscar . '%');
                //     })
                //     ->where('bodega.id_empresa', '=', $id)
                //     ->orderByRaw('bodega.id_bodega DESC')->get();
                $recupera =DB::select("SELECT bodega.*,plan_cuentas.nomcta, (select count(*) from bodega_ingreso where id_bodega=bodega.id_bodega) as exist_ingresos 
                                        From bodega 
                                        LEFT JOIN plan_cuentas 
                                        ON bodega.id_plan_cuentas=plan_cuentas.id_plan_cuentas
                                        where bodega.id_empresa=$id and (bodega.codigo like '%$buscar%' or bodega.responsable like '%$buscar%' or bodega.ubicacion like '%$buscar%' or bodega.direccion like '%$buscar%' or bodega.telefono like '%$buscar%')
                                        Order by bodega.id_bodega DESC");
            }
        } else {
            if ($buscar == '') {
                $recupera = Bodega::select('bodega.*', 'plan_cuentas.nomcta')
                    ->leftjoin('plan_cuentas', 'bodega.id_plan_cuentas', '=', 'plan_cuentas.id_plan_cuentas')
                    ->where('bodega.id_empresa', '=', $id)
                    ->where('bodega.id_establecimiento', '=', $ide)
                    ->orderByRaw('bodega.id_bodega DESC')->get();
            } else {
                $recupera = Bodega::select('bodega.*', 'plan_cuentas.nomcta')
                    ->leftjoin('plan_cuentas', 'bodega.id_plan_cuentas', '=', 'plan_cuentas.id_plan_cuentas')
                    ->where(function ($q) use ($buscar) {
                        $q->where('bodega.codigo', 'like', '%' . $buscar . '%')
                            ->orWhere('bodega.nombre', 'like', '%' . $buscar . '%')
                            ->orWhere('bodega.responsable', 'like', '%' . $buscar . '%')
                            ->orWhere('bodega.ubicacion', 'like', '%' . $buscar . '%')
                            ->orWhere('bodega.direccion', 'like', '%' . $buscar . '%')
                            ->orWhere('bodega.telefono', 'like', '%' . $buscar . '%');
                    })
                    ->where('bodega.id_empresa', '=', $id)
                    ->where('bodega.id_establecimiento', '=', $ide)
                    ->orderByRaw('bodega.id_bodega DESC')->get();
            }
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function todo($id)
    {
        $tipo = Bodega::select("*")->where("id_empresa", "=", $id)->get();
        return $tipo;
    }

    public function store(Request $request)
    {
        $tipo = new Bodega();
        $tipo->codigo = $request->codigo;
        $tipo->nombre = $request->nombre;
        $tipo->responsable = $request->responsable;
        $tipo->ubicacion = $request->ubicacion;
        $tipo->direccion = $request->direccion;
        $tipo->telefono = $request->telefono;
        $tipo->visible = $request->visible;
        $tipo->id_plan_cuentas = $request->id_plan_cuentas;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->id_establecimiento = $request->id_establecimiento;
        $tipo->save();
    }

    public function editar(Request $request)
    {
        $tipo = Bodega::findOrFail($request->id);
        $tipo->codigo = $request->codigo;
        $tipo->nombre = $request->nombre;
        $tipo->responsable = $request->responsable;
        $tipo->ubicacion = $request->ubicacion;
        $tipo->direccion = $request->direccion;
        $tipo->telefono = $request->telefono;
        $tipo->visible = $request->visible;
        $tipo->id_plan_cuentas = $request->id_plan_cuentas;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->id_establecimiento = $request->id_establecimiento;
        $tipo->save();
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM bodega WHERE id_bodega=' . $id);
        // $prbd = DB::select('SELECT * FROM producto_bodega WHERE id_producto =' . $id);
        return [
            "recupera" => $recupera,
            //"bodega" => $prbd
        ];
    }
    public function eliminar($id)
    {
        Bodega::destroy($id);
    }
    public function generarReporte(Request $request)
    {
        ini_set('max_execution_time', 3600);
        //$request = json_decode($request->data);
        $kardex = [];
        /*$qproductos = json_decode($request->products, true);
        $qbodega  = json_decode($request->bodega, true);
        $qlineaprod = json_decode($request->linea_product, true);
        $qtipoprod = json_decode($request->tipo_product, true);
        $qmarca = json_decode($request->marca, true);
        $qmodelo = json_decode($request->modelo, true);
        $qpresentacion = json_decode($request->presentacion, true);*/
		
		
		$qproductos = json_decode($request->products);
        $qbodega  = json_decode($request->bodega);
        $qlineaprod = json_decode($request->linea_product);
        $qtipoprod = json_decode($request->tipo_product);
        $qmarca = json_decode($request->marca);
        $qmodelo = json_decode($request->modelo);
        $qpresentacion = json_decode($request->presentacion);
        $dates = json_decode($request->dates);
        date_default_timezone_set('America/Guayaquil');

        if($dates->currentDate->active==false && $dates->currentDate->dateRange->finalDate!=null){
            $fecha = date('Y-m-d', strtotime($dates->currentDate->dateRange->finalDate));
        }
        else{

            $fecha = date('Y-m-d');
        }
		
        $qempresa = $request->company;
        $nombre_modelo = "";
        $nombre_marca = "";
        $nombre_pres = "";
        $nombre_linea_prod = "";
        $nombre_tipo_prod = "";
        $nombre_producto = "";
        $nombre_bodega = "";
        //productos
        // $queryproductos = "SELECT p.* FROM producto_bodega pb  JOIN bodega b ON pb.id_bodega = b.id_bodega JOIN producto p ON pb.id_producto = p.id_producto WHERE pb.id_empresa = {$qempresa} ";
        /* INICIO COMENTADO VIEJO */
		/*$queryproductos = "SELECT producto_bodega.*,producto.cod_principal,producto.cod_alterno,producto.nombre AS nombrep,producto.descripcion,producto.imagen,bodega.nombre as nombre_bodega 
       from producto_bodega 
       INNER JOIN bodega ON bodega.id_bodega=producto_bodega.id_bodega
       INNER JOIN producto ON producto.id_producto=producto_bodega.id_producto
       where bodega.id_empresa={$qempresa} and producto_bodega.cantidad<>0 and producto_bodega.costo_unitario<>0 ";
        $id_bodega = 0;
        $query = [];
        $adicional_elements=[];
        if ($qbodega["id"] != 0) {
            $queryproductos .= "AND producto_bodega.id_bodega = {$qbodega["id"]} ";
            $id_bodega = $qbodega["id"];
            array_push($query, "bodega.id_bodega={$qbodega["id"]}");
            
        }
        if ($qproductos["id"] != 0) {
            $queryproductos .= "AND producto_bodega.id_producto = {$qproductos["id"]} ";
            array_push($query, "producto_bodega.id_producto = {$qproductos["id"]}");
        } else {
            if ($qlineaprod["id"] != 0) {
                $nombre_linea_prod = $qlineaprod["name"];
                $queryproductos .= "AND producto.id_linea_producto = {$qlineaprod["id"]} ";
                array_push($query, "producto.id_linea_producto = {$qlineaprod["id"]}");
                array_push($adicional_elements,"Linea Producto: ".$nombre_linea_prod);
            }
            if ($qtipoprod["id"] != 0) {
                $nombre_tipo_prod = $qtipoprod["name"];
                $queryproductos .= "AND producto.id_tipo_producto = {$qtipoprod["id"]} ";
                array_push($query, "producto.id_tipo_producto = {$qtipoprod["id"]}");
                array_push($adicional_elements,"Tipo Producto: ".$nombre_tipo_prod);
            }
            if ($qmarca["id"] != 0) {
                $nombre_marca = $qmarca["name"];
                $queryproductos .= "AND producto.id_marca = {$qmarca["id"]} ";
                array_push($query, "producto.id_marca = {$qmarca["id"]}");
                array_push($adicional_elements,"Marca: ".$nombre_marca);
            }
            if ($qmodelo["id"] != 0) {
                $nombre_modelo = $qmodelo["name"];
                $queryproductos .= "AND producto.id_modelo = {$qmodelo["id"]} ";
                array_push($query, "producto.id_modelo = {$qmodelo["id"]}");
                array_push($adicional_elements,"Modelo: ".$nombre_modelo);
            }
            if ($qpresentacion["id"] != 0) {
                $nombre_pres = $qpresentacion["name"];
                $queryproductos .= "AND producto.id_presentacion = {$qpresentacion["id"]} ";
                array_push($query, "producto.id_presentacion = {$qpresentacion["id"]}");
                array_push($adicional_elements,"Presentacion: ".$nombre_pres);
            }
        }
        $adicional_elements = implode(" , ", $adicional_elements);
        $query = implode(" and ", $query);
        $precios = DB::select($queryproductos);*/
		/* FIN COMENTADO VIEJO */
		
		
		
		
		
		
		
		
		//dd(json_decode($request->bodega)->id);
		
		$queryproductos = "SELECT pb.id_producto_bodega, pb.id_producto, p.cod_principal, p.cod_alterno, p.nombre AS nombre_producto, p.descripcion, p.existencia_minima, p.existencia_maxima, pb.id_bodega, b.nombre AS nombre_bodega, pb.cantidad, pb.costo_unitario, pb.costo_total FROM producto_bodega pb  JOIN bodega b ON pb.id_bodega = b.id_bodega JOIN producto p ON pb.id_producto = p.id_producto WHERE pb.id_empresa = {$qempresa} and pb.cantidad<>0 and pb.costo_unitario<>0 ";
		
		$id_bodega = 0;
        $query = [];
        $adicional_elements=[];
        if ($qbodega->id != 0) {
            $queryproductos .= "AND producto_bodega.id_bodega = {$qbodega->id} ";
            $id_bodega = $qbodega->id;
            array_push($query, "bodega.id_bodega={$qbodega->id}");
            
        }
        if ($qproductos->id != 0) {
            $queryproductos .= "AND producto_bodega.id_producto = {$qproductos->id} ";
            array_push($query, "producto_bodega.id_producto = {$qproductos->id}");
        } else {
            if ($qlineaprod->id != 0) {
                $nombre_linea_prod = $qlineaprod->name;
                $queryproductos .= "AND producto.id_linea_producto = {$qlineaprod->id} ";
                array_push($query, "producto.id_linea_producto = {$qlineaprod->id}");
                array_push($adicional_elements,"Linea Producto: ".$nombre_linea_prod);
            }
            if ($qtipoprod->id != 0) {
                $nombre_tipo_prod = $qtipoprod->name;
                $queryproductos .= "AND producto.id_tipo_producto = {$qtipoprod->id} ";
                array_push($query, "producto.id_tipo_producto = {$qtipoprod->id}");
                array_push($adicional_elements,"Tipo Producto: ".$nombre_tipo_prod);
            }
            if ($qmarca->id != 0) {
                $nombre_marca = $qmarca->name;
                $queryproductos .= "AND producto.id_marca = {$qmarca->id} ";
                array_push($query, "producto.id_marca = {$qmarca->id}");
                array_push($adicional_elements,"Marca: ".$nombre_marca);
            }
            if ($qmodelo->id != 0) {
                $nombre_modelo = $qmodelo->name;
                $queryproductos .= "AND producto.id_modelo = {$qmodelo->id} ";
                array_push($query, "producto.id_modelo = {$qmodelo->id}");
                array_push($adicional_elements,"Modelo: ".$nombre_modelo);
            }
            if ($qpresentacion->id != 0) {
                $nombre_pres = $qpresentacion->name;
                $queryproductos .= "AND producto.id_presentacion = {$qpresentacion->id} ";
                array_push($query, "producto.id_presentacion = {$qpresentacion->id}");
                array_push($adicional_elements,"Presentacion: ".$nombre_pres);
            }
        }
        $adicional_elements = implode(" , ", $adicional_elements);
        $query = implode(" and ", $query);

        /*if ($qbodega->id != 0) {
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
        }*/

        $kardex = DB::select($queryproductos);
		//dd($kardex);
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
            WHERE  bid.id_producto = {$kardex[$i]->id_producto} AND bi.id_bodega = {$kardex[$i]->id_bodega} AND IF(bi.id_factura_compra IS NOT NULL, fc.fech_emision, SUBSTR(bi.fecha_ingreso, 1, 10)) <= DATE({$fecha}) 
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
            WHERE bed.id_producto = {$kardex[$i]->id_producto} AND be.id_bodega = {$kardex[$i]->id_bodega} AND SUBSTR(be.fcrea, 1, 10) <= DATE({$fecha}) 
            ORDER BY fecha_transaccion, fecha_aux ASC;";
            //dd($querytransacciones);
            $transacciones = DB::select($querytransacciones);
			//dd($transacciones);
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
        //dd($kardex);
        //funcionaliudad CEFUEGOS codigo alterno de producto visualzicion en kardex
        if ($qempresa == 7 || $qempresa == 9 || $qempresa == 10 || $qempresa == 71) {
            $empres = DB::select("SELECT nombre_empresa FROM empresa WHERE id_empresa = ${qempresa}");
            if (in_array($empres[0]->nombre_empresa, array("C.E. FUEGOS GROUP", "C.E.FUEGOS SYSTEM", "C.E. FUEGOS", "CORTINAS DLUXE"))) {
                for ($z = 0; $z < count($kardex); $z++) {
                    $kardex[$z]->cod_principal = $kardex[$z]->cod_alterno;
                }
            }
        }
		
		
		
		
		$precios = $kardex;
		
		
		
		
		
        $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->company);

        if ($query) {
            $query_bd = "SELECT distinct bodega.* from bodega INNER JOIN producto_bodega ON producto_bodega.id_bodega=bodega.id_bodega INNER JOIN producto ON producto_bodega.id_producto=producto.id_producto where bodega.id_empresa={$request->company} and {$query}";
        } else {
            $query_bd = "SELECT distinct bodega.* from bodega INNER JOIN producto_bodega ON producto_bodega.id_bodega=bodega.id_bodega INNER JOIN producto ON producto_bodega.id_producto=producto.id_producto where bodega.id_empresa={$request->company}";
        }

        //dd($query_bd);
        $bodega = DB::select($query_bd);
        $reportePdf = new generarReportes();
        if (!$precios) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            $strPDF = $reportePdf->ReporteInventario($precios, $bodega, $empresa[0],$adicional_elements, $fecha);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
}
