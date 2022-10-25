<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacturaMasiva;
use App\Models\DetalleMasiva;
use App\Models\FacturaPagosMasiva;

class FacturaMasivaController extends Controller
{
    //app()->call('App\Http\Controllers\FacturacionController@respfactura', [$request, $param2]);
    public function index(Request $request, $id)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = FacturaMasiva::select('factura_masiva.*', 'grupo_cliente.nombre_grupo as grupo_nombre')
                ->join('grupo_cliente', 'grupo_cliente.id_grupo_cliente', '=', 'factura_masiva.id_grupo_cliente')
                ->where("factura_masiva.id_empresa", "=", $id)
                ->orderByRaw('factura_masiva.id_factura_masiva', 'desc')->get();
        } else {
            $recupera = FacturaMasiva::select('factura_masiva.*', 'grupo_cliente.nombre_grupo as grupo_nombre')
                ->join('grupo_cliente', 'grupo_cliente.id_grupo_cliente', '=', 'factura_masiva.id_grupo_cliente')
                ->where(function ($q) use ($buscar) {
                    $q->where('grupo_cliente.nombre_grupo', 'like', '%' . $buscar . '%')
                        ->orWhere('factura_masiva.codigo', 'like', '%' . $buscar . '%')
                        ->orWhere('factura_masiva.nombre', 'like', '%' . $buscar . '%');
                })
                ->where("factura_masiva.id_empresa", "=", $id)
                ->orderByRaw('factura_masiva.id_factura_masiva', 'desc')->get();
        }
        return $recupera;
    }
    //listar productos
    public function listar_productos(Request $request)
    {
        $bs = str_replace(" ", "%", $request->buscar);
        $empresa = $request->id_empresa;
        $pto_emision = DB::select("SELECT * from punto_emision where id_bodega is not null  and  id_punto_emision={$request->id_pto_emision}");
        //verifica si existe cliente elegido antes de listar los productos
        //si existe cliente recupera el precio dependiendo de la lista que se asigno al cliente
        if ($request->cliente) {
            $cli = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $request->cliente);
            $precio = $cli[0]->lista_precios;
            if ($precio == 5) {
                $contt = 'p.precio5 AS precio';
            } else if ($precio == 4) {
                $contt = 'p.precio4 AS precio';
            } else if ($precio == 3) {
                $contt = 'p.precio3 AS precio';
            } else if ($precio == 2) {
                $contt = 'p.precio2 AS precio';
            } else {
                $contt = 'p.pvp_precio1 AS precio';
            }
            $tri = trim($bs);
            //dd("SELECT p.*, $contt, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 ORDER BY p.codigo_barras DESC LIMIT 10");
            //recupera tanto productos como servicios $res = producto, $res1 = servicio
            if (count($pto_emision) > 0) {
                $res =  DB::select("SELECT p.*, $contt,                 pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega={$pto_emision[0]->id_bodega} ORDER BY p.codigo_barras DESC LIMIT 10");
            } else {
                $res =  DB::select("SELECT p.*, $contt,                 pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega=0 ORDER BY p.codigo_barras DESC LIMIT 10");
            }

            $res1 = DB::select("SELECT p.*, $contt, ice.nombre AS nombreice,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras like '%$bs%' ) AND p.id_empresa = $empresa AND p.tipo_servicio='Venta' AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");
            //
            //concatena los dos array en uno solo y lista los productos dentro de la lista
            $res2 = array_merge($res1, $res);
            return $res2;
        } else {
            $tri = trim($bs);
            //recupera tanto productos como servicios $res = producto, $res1 = servicio
            if (count($pto_emision) > 0) {
                $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega={$pto_emision[0]->id_bodega} ORDER BY p.codigo_barras DESC LIMIT 10");
            } else {
                $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega=0 ORDER BY p.codigo_barras DESC LIMIT 10");
            }

            $res1 = DB::select("SELECT p.*, p.pvp_precio1 AS precio, ice.nombre AS nombreice,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa AND p.tipo_servicio='Venta' AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");

            //concatena los dos array en uno solo y lista los productos dentro de la lista
            $res2 = array_merge($res1, $res);
            return $res2;
        }
    }
}
