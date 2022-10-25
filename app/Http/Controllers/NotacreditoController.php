<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Notacredito;
use App\Models\Cliente;
use App\Models\Detalle_nota_credito;
use App\Models\Producto;
use App\Models\Factura;
use App\Models\Detalle;
use Carbon\Carbon;

use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;

use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

use App\Models\Cuentaporcobrar;
use App\Models\Ctas_cobrar_pagos;

include 'class/generarPDF.php';

use generarPDF;

include 'class/generarReportes.php';

use generarReportes;

class NotacreditoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = DB::select("SELECT `nota_credito`.*,nota_credito.created_by as created_by_nota_credito,nota_credito.updated_by as updated_by_nota_credito, nota_credito.estado as estadof, `nota_credito`.`fmodifica` as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `nota_credito`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento` from `nota_credito` inner join `empresa` on `empresa`.`id_empresa` = " . $request->datos["id_empresa"] . " inner join `cliente` on `cliente`.`id_cliente` = `nota_credito`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = " . $request->datos["id_establecimiento"] . " inner join `punto_emision` on `punto_emision`.`id_punto_emision` = " . $request->datos["id_punto_emision"] . " inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where `nota_credito`.`id_empresa` = " . $request->datos["id_empresa"] . " and `nota_credito`.`modo` = 1 ORDER BY nota_credito.fecha_emision DESC");
        } else {
            $recupera = DB::select("SELECT `nota_credito`.*,nota_credito.created_by as created_by_nota_credito,nota_credito.updated_by as updated_by_nota_credito,nota_credito.estado as estadof, `nota_credito`.`fmodifica` as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `nota_credito`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento` from `nota_credito` inner join `empresa` on `empresa`.`id_empresa` = " . $request->datos["id_empresa"] . " inner join `cliente` on `cliente`.`id_cliente` = `nota_credito`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = " . $request->datos["id_establecimiento"] . " inner join `punto_emision` on `punto_emision`.`id_punto_emision` = " . $request->datos["id_punto_emision"] . " inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR nota_credito.respuesta like '%$buscar%' OR nota_credito.clave_acceso like '%$buscar%') AND `nota_credito`.`id_empresa` = " . $request->datos["id_empresa"] . " and `nota_credito`.`modo` = 1 order by nota_credito.fecha_emision DESC");
        }
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($recupera); $i++) {
                if ($recupera[$i]->created_by_nota_credito == session()->get('usuariosesion')['id'] || $recupera[$i]->updated_by_nota_credito == session()->get('usuariosesion')['id'] || $recupera[$i]->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $recupera[$i]);
                }
            }
            $recupera = $dat;
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function listar_cliente(Request $request)
    {
        $bs = $request->buscar;
        $empresa = $request->empresa;
        if ($bs == '') {
            $res = DB::select("SELECT *,(select nombre from plan_seguro where id_plan_seguro=cliente.id_plan_seguro) as nombre_plan_seguro,(select nombre from seguro where id_seguro=cliente.id_seguro) as nombre_seguro FROM cliente WHERE id_empresa = $empresa ORDER BY id_cliente DESC LIMIT 10");
        } else {
            $res = DB::select("SELECT *,(select nombre from plan_seguro where id_plan_seguro=cliente.id_plan_seguro) as nombre_plan_seguro,(select nombre from seguro where id_seguro=cliente.id_seguro) as nombre_seguro FROM cliente WHERE (codigo LIKE '%$bs%' OR nombre LIKE '%$bs%' OR identificacion LIKE '%$bs%' OR email LIKE '%$bs%' OR telefono LIKE '%$bs%' or nombre_adicional like '%$bs%') AND id_empresa = $empresa ORDER BY id_cliente DESC LIMIT 10");
        }
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($res); $i++) {
                if ($res[$i]->created_by == session()->get('usuariosesion')['id'] || $res[$i]->updated_by == session()->get('usuariosesion')['id'] || $res[$i]->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $res[$i]);
                }
            }
            $res = $dat;
        }
        return $res;
    }

    //lista los productos generales del sistema mediante la empresa
    public function listar_productos(Request $request)
    {
        $bs = str_replace(" ", "%", $request->buscar);
        $empresa = $request->id_empresa;
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

            //recupera tanto productos como servicios $res = producto, $res1 = servicio
            $res =  DB::select("SELECT p.*, $contt, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 ORDER BY p.codigo_barras DESC LIMIT 10");
            $res1 = DB::select("SELECT p.*, $contt, ice.nombre AS nombreice FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");

            //concatena los dos array en uno solo y lista los productos dentro de la lista
            $res2 = array_merge($res1, $res);
            return $res2;
        } else {
            //recupera tanto productos como servicios $res = producto, $res1 = servicio
            $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 ORDER BY p.codigo_barras DESC LIMIT 10");
            $res1 = DB::select("SELECT p.*, p.pvp_precio1 AS precio, ice.nombre AS nombreice FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");

            //concatena los dos array en uno solo y lista los productos dentro de la lista
            $res2 = array_merge($res1, $res);
            return $res2;
        }
    }
    //lista los productos generales del sistema mediante la empresa sin necesidad de listar cliente
    public function listar_productos1(Request $request)
    {
        $bs = str_replace(" ", "%", $request->buscar);
        $empresa = $request->id_empresa;
        //recupera tanto productos como servicios $res = producto, $res1 = servicio
        $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, presentacion.nombre AS presentacion, b.id_bodega FROM producto p LEFT JOIN presentacion ON presentacion.id_presentacion = p.id_presentacion LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa and p.estado>0 ORDER BY p.codigo_barras DESC LIMIT 10");
        $res1 = DB::select("SELECT p.*, p.pvp_precio1 AS precio, ice.nombre AS nombreice, presentacion.nombre AS presentacion FROM producto p LEFT JOIN presentacion ON presentacion.id_presentacion = p.id_presentacion LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 AND p.tipo_servicio='Compra' and p.estado>0 ORDER BY p.codigo_barras DESC LIMIT 10");

        //concatena los dos array en uno solo y lista los productos dentro de la lista
        $res2 = array_merge($res1, $res);
        return $res2;
    }
    public function listar_facturas(Request $request)
    {
        //lista la factura mediante la clave de acceso, busqueda e id-empresa
        $bs = $request->buscar;
        $empresa = $request->id_empresa;
        $establecimiento = $request->id_establecimiento;
        $res =  DB::select("SELECT * FROM factura WHERE clave_acceso LIKE '%$bs%' AND id_empresa = $empresa AND id_establecimiento = $establecimiento LIMIT 10");
        return $res;
    }
    public function listar_creacion_cliente($id)
    {
        //lista todos los datos de cliente requerido tanto para listar como para crear nuevo cliente
        $grupo_cliente = DB::select("SELECT * FROM grupo_cliente WHERE id_empresa = " . $id);
        $tipo_cliente = DB::select("SELECT * FROM tipo_cliente WHERE id_empresa = " . $id);
        $provincia = DB::select("SELECT * FROM provincia");
        $vendedor = DB::select("SELECT * FROM vendedor WHERE id_empresa = " . $id);
        $forma_pago = DB::select("SELECT * FROM forma_pagos");
        $proyectos = DB::select("SELECT * FROM proyecto WHERE id_empresa = " . $id);
        $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = " . $id);
        return [
            "grupo_cliente" => $grupo_cliente,
            "tipo_cliente" => $tipo_cliente,
            "provincia" => $provincia,
            "vendedor" => $vendedor,
            "forma_pago" => $forma_pago,
            "proyectos" => $proyectos,
            "empresa" => $empresa[0],
        ];
    }
    public function listar_canton($id)
    {
        $res = DB::select("SELECT * FROM ciudad WHERE id_provincia = " . $id);
        return $res;
    }
    public function listar_parroquia($id)
    {
        $res = DB::select("SELECT * FROM parroquia WHERE id_ciudad = " . $id);
        return $res;
    }
    public function listar_cuenta_contable(Request $request)
    {
        $id = $request->empresa;
        $bs = $request->buscar;
        $res = DB::select("SELECT * FROM plan_cuentas WHERE (codcta LIKE '%$bs%' OR nomcta LIKE '%$bs%') AND id_empresa = $id AND plan_cuentas.id_grupo = 2 order by codcta asc");
        return $res;
    }

    //guarda el cliente por el módulo de factura
    public function guardar_cliente(Request $request)
    {
        if ($request->cliente['identificacion']) {
            if ($request->cliente['tipo_identificacion'] !== "Consumidor Final") {
                $sel = DB::select("SELECT `identificacion` FROM `cliente` WHERE `identificacion` = '" . $request->cliente['identificacion'] . "' and id_empresa = '" . $request->empresa . "'");
                if ($sel) {
                    return "error_identificacion";
                }
            }
        }
        if ($request->cliente['id_cuenta_contable'] == null && $request->cliente['cuenta_contable'] != '') {
            $buscar = DB::select("SELECT * FROM plan_cuentas WHERE codcta = " . $request->cliente['cuenta_contable']);
            $cuenta = $buscar[0];
        } else {
            $cuenta = $request->cliente['id_cuenta_contable'];
        }
        $emails = "";
        if (isset($request->emails)) {
            $emails = implode(";", $request->emails);
        } else {
            $emails = $request->cliente['e_mail'];
        }
        $cliente = new Cliente();
        //$cliente->codigo = $request->cliente['codigo'];
        $cliente->codigo = $request->codigocliente;
        $cliente->nombre = $request->cliente['nombre'];
        $cliente->tipo_identificacion = $request->cliente['tipo_identificacion'];
        $cliente->identificacion = $request->cliente['identificacion'];
        $cliente->direccion = $request->cliente['direccion'];
        $cliente->email = $emails;
        $cliente->telefono = $request->cliente['telefono'];
        $cliente->contacto = $request->cliente['contacto'];
        $cliente->estado = $request->cliente['estado'];
        $cliente->id_plan_cuentas = $cuenta;
        $cliente->comentario = $request->cliente['comentario'];
        $cliente->descuento = $request->cliente['descuento'];
        $cliente->num_pago = $request->cliente['numero_pagos'];
        $cliente->id_grupo_cliente = $request->cliente['grupo_cliente'];
        $cliente->id_tipo_cliente = $request->cliente['tipo_cliente'];
        $cliente->grupo_tributario = $request->cliente['grupo_tributario'];
        $cliente->id_cuidad = $request->cliente['canton'];
        $cliente->id_parroquia = $request->cliente['parroquia'];
        $cliente->id_provincia = $request->cliente['provincia'];
        $cliente->parte_relacionada = $request->cliente['parte_relacionada'];
        $cliente->id_vendedor = $request->cliente['vendedor'];
        $cliente->lista_precios = $request->cliente['lista_precios'];
        $cliente->limite_credito = $request->cliente['limite_credito'];
        $cliente->id_forma_pagos = $request->cliente['forma_pago'];
        $cliente->id_empresa = $request->empresa;
        $cliente->save();
    }
    public function verAsiento(Request $request, $id)
    {
        $nota_credito = DB::select("SELECT nota_credito.*,(select id_factura from factura where id_empresa={$request->id_empresa} and clave_acceso like concat('%',nota_credito.autorizacionfactura,'%') limit 1) as id_factura,cliente.nombre,cliente.tipo_identificacion,cliente.identificacion from cliente,nota_credito where  cliente.id_cliente=nota_credito.id_cliente and nota_credito.id_nota_credito=" . $id);
        $codigo = DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'NCF-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=" . $request->id_empresa);
        $cod_asiento = "";
        if ($codigo) {
            $lenght = strlen($codigo[0]->codigo);
            $valor = $codigo[0]->codigo + 1;
            $cod_asiento = $codigo[0]->codigo + 1;
        } else {
            $cod_asiento = "1";
        }
        $cod_asiento_ant = "";
        if ($codigo) {
            $codigo_ant = DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=11 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=" . $request->id_empresa . " ORDER BY asientos.codigo desc limit 1");
            if ($codigo_ant) {
                $cod_asiento_ant = $codigo_ant[0]->numero;
            }
        }
        $productos = DB::select("SELECT detalle_nota_credito.total,if(detalle_nota_credito.id_iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
        if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva,null) as id_plan_cuentas_iva_12,
        if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva_0,null) as id_plan_cuentas_iva_0,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
        concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
        null as haber,detalle_nota_credito.total as debe FROM detalle_nota_credito
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_credito.id_proyecto
        INNER JOIN producto
        on producto.id_producto=detalle_nota_credito.id_producto
        LEFT JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        LEFT JOIN plan_cuentas
        ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
        where detalle_nota_credito.id_nota_credito={$id}");
        $iva_asiento = DB::select("SELECT detalle_nota_credito.total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as haber,if(detalle_nota_credito.id_iva=2,(detalle_nota_credito.total)*(12/100),0) as debe,nota_credito.iva_12 as total_iva_12
        from nota_credito,retencion,plan_cuentas,detalle_nota_credito
                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle_nota_credito.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle_nota_credito.id_producto
                       where nota_credito.id_nota_credito={$id} and nota_credito.id_nota_credito=detalle_nota_credito.id_nota_credito and retencion.descrip_retencion='IVA. en Ventas' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                       ORDER BY detalle_nota_credito.id_detalle_nota_credito asc");
        $ice_factura = DB::select("SELECT producto.total_ice as total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as haber,round(detalle_nota_credito.cantidad*if(producto.total_ice is null,0,producto.total_ice),2) as debe,detalle_nota_credito.id_detalle_nota_credito
        from detalle_nota_credito
        INNER JOIN nota_credito
        on nota_credito.id_nota_credito=detalle_nota_credito.id_nota_credito
        INNER JOIN ice
        on ice.id_ice=detalle_nota_credito.id_ice
				INNER JOIN producto
        on producto.id_producto=detalle_nota_credito.id_producto
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=ice.id_plan_cuentas
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_credito.id_proyecto
        where nota_credito.id_nota_credito={$id}  and  producto.total_ice>0
        ORDER BY detalle_nota_credito.id_detalle_nota_credito asc");
        $count_pagos=DB::select("SELECT factura_pagos.* FROM factura_pagos where factura_pagos.id_factura={$nota_credito[0]->id_factura}");
        $exist_credito=DB::select("SELECT factura_pagos.* FROM factura_pagos where factura_pagos.id_factura={$nota_credito[0]->id_factura} and estado=2");
        $exist_pagos=DB::select("SELECT factura_pagos.* FROM factura_pagos where factura_pagos.id_factura={$nota_credito[0]->id_factura} and estado=1");
        if(count($exist_credito)>0){
            $total_crd=count($count_pagos);
            $credito = DB::select("SELECT detalle_nota_credito.total,if(cliente.id_plan_cuentas is null,'no','si') as exist_plc_cl,plan_cuentas.id_plan_cuentas as id_plan_cuentas_cliente,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta_cliente,
            (select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas)) as id_plan_cuenta_grupo,
            (select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas)) as nombre_cuenta_grupo,proyecto.id_proyecto,proyecto.descripcion,if(detalle_nota_credito.id_iva=2,detalle_nota_credito.total+(detalle_nota_credito.total*0.12),detalle_nota_credito.total)/{$total_crd} as haber,null as debe,detalle_nota_credito.id_detalle_nota_credito
            from detalle_nota_credito
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle_nota_credito.id_proyecto
            INNER JOIN nota_credito
            on nota_credito.id_nota_credito=detalle_nota_credito.id_nota_credito
            INNER JOIN cliente
            on cliente.id_cliente=nota_credito.id_cliente
            LEFT JOIN grupo_cliente
            on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=cliente.id_plan_cuentas
            where detalle_nota_credito.id_nota_credito={$id}");
        }else{
            $credito = [];
        }
        if(count($exist_pagos)>0){
            $exist_plan_cuenta = DB::select("SELECT sum(id_plan_cuentas) as existe_plan_cuenta  from factura_pagos where id_factura={$nota_credito[0]->id_factura} and  estado=1");
            $plc=0;
            if(count($exist_plan_cuenta)>0){
                if($exist_plan_cuenta[0]->existe_plan_cuenta>0){
                    $plc=$exist_plan_cuenta[0]->existe_plan_cuenta;
                }
                
            }
            $exist_anticipo = DB::select("SELECT sum(anticipo) as anticipo from factura_pagos where id_factura={$nota_credito[0]->id_factura} and  estado=1");
            $total_pago=count($count_pagos);
            $pagos_nc = DB::select("SELECT detalle_nota_credito.total,if({$plc}<=0,'no','si') as exist_plc_cl,
            (select plan_cuentas.id_plan_cuentas FROM factura_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=factura_pagos.id_plan_cuentas where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as id_plan_cuentas_cliente,
            (select CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) FROM factura_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=factura_pagos.id_plan_cuentas where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as nombre_cuenta_cliente,
            (select plan_cuentas.bansel FROM factura_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=factura_pagos.id_plan_cuentas where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as bansel_cliente,
            
            (select plan_cuentas.id_plan_cuentas FROM factura_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as id_plan_cuenta_grupo,
            (select CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) FROM factura_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as nombre_cuenta_grupo,
            (select plan_cuentas.bansel FROM factura_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as bansel_grupo,

            (select forma_pagos.descripcion FROM factura_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as nombre_pago,
            (select forma_pagos.id_forma_pagos FROM factura_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as id_forma_pagos,
            (select factura_pagos.fecha_pago FROM factura_pagos  where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as fecha_pago,
            (select factura_pagos.numero_transaccion FROM factura_pagos  where factura_pagos.id_factura={$nota_credito[0]->id_factura} and factura_pagos.estado=1 limit 1) as numero_transaccion,

            proyecto.id_proyecto,proyecto.descripcion,if(detalle_nota_credito.id_iva=2,detalle_nota_credito.total+(detalle_nota_credito.total*0.12),detalle_nota_credito.total)/{$total_crd} as haber,null as debe,detalle_nota_credito.id_detalle_nota_credito
            
            
            from detalle_nota_credito
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle_nota_credito.id_proyecto
            INNER JOIN nota_credito
            on nota_credito.id_nota_credito=detalle_nota_credito.id_nota_credito
            INNER JOIN cliente
            on cliente.id_cliente=nota_credito.id_cliente
            LEFT JOIN grupo_cliente
            on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=cliente.id_plan_cuentas
            where detalle_nota_credito.id_nota_credito={$id}");
        }else{
            $pagos_nc=[];
        }
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $fecha_emision = substr($nota_credito[0]->fecha_emision, 0, -3);
        $anio_emision = substr($nota_credito[0]->fecha_emision, 0, 4);
        $fecha_cierre = DB::select("SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Cierre Mes' and periodo='{$fecha_emision}'
                                UNION
                                SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Estado Contable' and periodo='{$anio_emision}'");
        $asiento = "";
        if (count($fecha_cierre) > 0) {
            $asiento = "no";
        } else {
            $asiento = "si";
        }
        return [
            'codigo' => $cod_asiento,
            'asiento_permitido' => $asiento,
            'codigo_anterior' => $cod_asiento_ant,
            'nota_credito_fact' => $nota_credito[0],
            'producto_asientos' => $productos,
            'doce_iva_asiento' => $iva_asiento,
            'cliente' => $credito,
            'proyecto' => $proyecto[0]->id_proyecto,
            'pagos' => $pagos_nc
        ];
    }
    public function agregarAsiento(Request $request)
    {
        Notacredito::where('id_nota_credito', $request->cod_rol)->update(['contabilidad' => '1']);
        $asientos = new Asientos();
        $asientos->automatico = 0;
        $asientos->numero = $request->numero;
        $asientos->codigo = $request->codigo;
        $asientos->codigo_rol = $request->cod_rol;
        $asientos->fecha = $request->fecha;
        $asientos->razon_social = $request->razon_social;
        $asientos->tipo_identificacion = $request->tipo_identificacion;
        $asientos->ruc_ci = $request->ruc_ci;
        $asientos->concepto = $request->concepto;
        $asientos->ucrea = $request->ucrea;
        $asientos->id_proyecto = $request->id_proyecto;
        $asientos->id_asientos_comprobante = 11;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request)
    {
        foreach ($request->productos as $debe) {
            $asiento = new Asientos_contables_detalle();
            if ($debe["sector"] == "producto" && $debe["iva"] == "doce") {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas_iva_12"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
            }
            if ($debe["sector"] == "producto" && $debe["iva"] == "cero") {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas_iva_0"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
            }
            if ($debe["sector"] == "servicio") {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas_servicio"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
            }
            $asiento->save();
        }
        foreach ($request->iva_12 as $debe) {
            $asiento = new Asientos_contables_detalle();
            if ($debe["debe"] > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
            }
            $asiento->save();
        }
        foreach ($request->creditos as $haber) {
            $asiento = new Asientos_contables_detalle();
            if (count($haber) > 0) {
                if ($haber["exist_plc_cl"] == "si") {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas_cliente"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                } else {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuenta_grupo"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                }

                $asiento->save();
            }
        }
        foreach ($request->pagos as $haber) {
            $asiento = new Asientos_contables_detalle();
            if (count($haber) > 0) {
                if ($haber["exist_plc_cl"] == "si") {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas_cliente"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                    if ($haber["bansel_cliente"] !== null) {
                        $asiento->no_documento = $haber["numero_transaccion"];
                        $asiento->fecha_de_pago = $haber["fecha_pago"];
                        $asiento->id_forma_pagos = $haber["id_forma_pagos"];
                    }
                } else {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuenta_grupo"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                    if ($haber["bansel_grupo"] !== null) {
                        $asiento->no_documento = $haber["numero_transaccion"];
                        $asiento->fecha_de_pago = $haber["fecha_pago"];
                        $asiento->id_forma_pagos = $haber["id_forma_pagos"];
                    }
                }

                $asiento->save();
            }
        }
    }
    public function verificarcliente($id)
    {
        $cliente = DB::select("SELECT * FROM cliente WHERE id_empresa = " . $id . " ORDER BY id_cliente DESC limit 1");
        if ($cliente) {
            $dato = $cliente[0]->codigo;
            $var = 0;
            for ($i = strlen($dato); $i > 0; $i--) {
                if ($dato[$i - 1] == '-') {
                    $var = $i;
                    break;
                }
            }
            $numero = substr($dato, $var) + 1;
            $cod = substr($dato, 0, $var);
            return $cod . $numero;
        } else {
            return "vacio";
        }
    }
    public function guardar_factura(Request $request)
    {
        ini_set('max_execution_time', 800);
        $notacredito = new Notacredito();
        $notacredito->modo = 1;
        $notacredito->ambiente = $request->factura["ambiente"];
        $notacredito->tipo_emision = 1;
        $notacredito->fecha_emision = $request->factura["fecha"];
        $notacredito->forma_pago = $request->factura["forma_pago"];
        $notacredito->autorizacionfactura = $request->factura["documento"];
        $notacredito->clave_acceso = $request->factura["clave_acceso"];
        $notacredito->fechaAutorizacion = $request->factura["fecha_doc"];
        $notacredito->observacion = $request->factura["observacion"];
        $notacredito->subtotal_sin_impuesto = $request->subtotal;
        $notacredito->subtotal_12 = $request->subtotal12;
        $notacredito->subtotal_0 = $request->subtotal0;
        $notacredito->subtotal_no_obj_iva = $request->no_impuesto;
        $notacredito->descuento = $request->descuento;
        $notacredito->valor_ice = '0.00';
        $notacredito->valor_irbpnr = '0.00';
        $notacredito->iva_12 = $request->valor12;
        $notacredito->estatus = 1;
        $notacredito->estado = 1;
        $notacredito->valor_total = $request->total;
        $notacredito->id_cliente = $request->cliente;
        $notacredito->id_user = $request->usuario["id"];
        $notacredito->id_punto_emision = $request->usuario["id_punto_emision"];
        $notacredito->id_empresa = $request->usuario["id_empresa"];
        $notacredito->id_establecimiento = $request->usuario["id_establecimiento"];
        $notacredito->id_proyecto = $request->factura["proyectos"];
        $notacredito->totalpropinaf = '0.00';
        $notacredito->pp_descuento = $request->descuento;
        $notacredito->id_factura = $request->id_factura;
        $notacredito->motivo = $request->factura["motivo"];
        $notacredito->created_by = session()->get('usuariosesion')['id'];
        $notacredito->updated_by = session()->get('usuariosesion')['id'];
        $notacredito->save();

        $id = $notacredito->id_nota_credito;
        $nro_nota_credito_bodega = substr($request->factura["clave_acceso"], -19, -10);
        $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
        $sf = $s_facturasubstr + 1;
        $idp = $request->usuario["id_punto_emision"];
        DB::update("UPDATE punto_emision SET secuencial_nota_credito = '$sf' WHERE id_punto_emision = $idp");

        //resta los pagos de nota de crédito
        /*$idfactura = $request->id_factura;
        $notc = DB::select("SELECT * FROM ctas_cobrar WHERE id_factura = $idfactura AND id_nota_debito IS NULL AND (valor_cuota - valor_pagado) > 0");
        if(count($notc)>=1){
            $cont = count($notc);
            $valor = $request->total / $cont;
            $notc1 = DB::select("SELECT * FROM ctas_cobrar WHERE id_factura = $idfactura AND id_nota_debito IS NULL AND (valor_cuota - valor_pagado) > $valor");
            $cont1 = count($notc1);
            $valor1 = $request->total / $cont1;
            DB::update("UPDATE ctas_cobrar SET valor_cuota = valor_cuota - $valor1, numero_transaccion = $valor1 WHERE id_factura =  $idfactura AND id_nota_debito IS NULL AND (valor_cuota - valor_pagado) > $valor1");
        }*/
        $savebode = 0;
        $hoy = Carbon::now();
        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle_nota_credito();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            if(isset($request->productos[$a]["cantidad_dsc"])){
                $detalle->cantidad_dsc = $request->productos[$a]["cantidad_dsc"];
            } 
            $detalle->descuento_comp = $request->productos[$a]["descuento"];
            $detalle->descuento = number_format($request->productos[$a]["descuento"]/$request->productos[$a]["cantidad_dsc"]*$request->productos[$a]["cantidad"],2,".",""); 
            $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - number_format($request->productos[$a]["descuento"]/$request->productos[$a]["cantidad_dsc"]*$request->productos[$a]["cantidad"],2,".",""));
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_nota_credito = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
            $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            $detalle->save();

            if (isset($request->productos[$a]["id_producto_bodega"])) {
                $idempresa = $request->usuario["id_empresa"];
                //registro de ingreso
                $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1;");
                $numeroingreso = "";
                if (count($numegre) == 1) {
                    $dato = $numegre[0]->num_ingreso;
                    $tot = $dato + 1;
                    $numeroingreso = $tot;
                } else {
                    $numeroingreso = 1;
                }

                $cant = $request->productos[$a]["cantidad"];
                $prec = $request->productos[$a]["precio"];
                $idpb = $request->productos[$a]["id_producto_bodega"];
                DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant,costo_total=costo_total+($cant*$prec),costo_unitario=costo_total/cantidad WHERE id_producto_bodega = $idpb");

                $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                $idbodega = $reses[0]->id_bodega;
                if ($savebode == 0) {
                    $ingreso = new BodegaIngreso();
                    $ingreso->num_ingreso = $numeroingreso;
                    $ingreso->fecha_ingreso = $hoy;
                    $ingreso->tipo_ingreso = "Ingreso Nota de Credito";
                    $ingreso->observ_ingreso = 'Nota Credito Venta: ' . $nro_nota_credito_bodega;
                    $ingreso->id_proyecto = $request->factura["proyectos"];
                    $ingreso->id_bodega = $idbodega;
                    $ingreso->id_empresa = $request->usuario["id_empresa"];
                    $ingreso->id_nota_credito = $id;
                    $ingreso->save();
                    $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                    $savebode++;
                }

                $bed = new BodegaIngresoDetalle();
                $bed->cantidad = $request->productos[$a]["cantidad"];
                $bed->costo_unitario = $request->productos[$a]["precio"];
                $bed->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
                $bed->id_bodega_ingreso = $id_bodega_ingreso;
                $bed->id_producto = $request->productos[$a]["id_producto"];
                $bed->id_proyecto = $request->productos[$a]["proyecto"];
                $bed->save();
            }
            /*$prod = Producto::findOrFail($request->productos[$a]["id_producto"]);
            $prod->
            $prod->save();*/
        }
        //resta los pagos de nota de crédito
        $id_factcompra = $request->id_factura;
        $notc = DB::select("SELECT * FROM ctas_cobrar WHERE id_factura = $id_factcompra AND id_nota_debito IS NULL AND id_nota_venta IS NULL and ctas_cobrar.tipo=1");
        if(count($notc)>0){
            $cont = count($notc);
            $valor = $request->total / $cont;
            $notc1 = DB::select("SELECT * FROM ctas_cobrar WHERE id_factura = $id_factcompra AND id_nota_debito IS NULL AND id_nota_venta IS NULL and ctas_cobrar.tipo=1");
        }else{
            $notc1=[];
        }
        
        $cont1 = count($notc1);
        if($cont1>0){
            //dd($notc1[0]->id_ctaspagar);
            $fp=DB::select("SELECT id_forma_pagos from forma_pagos where id_empresa={$request->usuario["id_empresa"]}");
            $pos0=DB::select("SELECT max(id_ctas_cobrar_pagos) as posicion from ctas_cobrar_pagos INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar_pagos.id_cliente where cliente.id_empresa={$request->usuario["id_empresa"]}");
            if(count($pos0)>0){
                $pos=DB::select("SELECT count(id_ctas_cobrar_pagos) as posicion from ctas_cobrar_pagos INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar_pagos.id_cliente where cliente.id_empresa={$request->usuario["id_empresa"]}");
            }else{
                $pos=[];
            }
            $conteo=1;
            if(count($pos)>0){
                $conteo=$pos[0]->posicion+1;
            }
            $valor1 = $request->total / $cont1;
            DB::update("UPDATE ctas_cobrar SET valor_pagado = valor_pagado + $valor1, valor_devolucion =  $valor1,updated_by={$request->usuario["id"]} WHERE id_factura =  $id_factcompra AND id_nota_debito IS NULL AND id_nota_venta IS NULL and ctas_cobrar.tipo=1 ");

            $cxcp = new Ctas_cobrar_pagos;
            $cxcp->pagos_por = "Nota Credito";
            $cxcp->valor_seleccionado = $valor1;
            $cxcp->valor_real_pago = $valor1;
            if(count($fp)>0){
                $cxcp->id_forma_pagos = $fp[0]->id_forma_pagos;
            }
            //$cxcp->id_banco = $data["id_banco"];
            $cxcp->fecha_pago = $hoy;
            //$cxcp->posicion = $conteo;
            $cxcp->fecha_registro = $request->factura["fecha"];
            $cxcp->id_cliente = $request->cliente;
            $cxcp->nota_credito = 1;
            $cxcp->created_by = $request->usuario["id"];
            $cxcp->save();
            //recupera el id de la cuenta
            $idcxcp = $cxcp->id_ctas_cobrar_pagos;
            $referencia = null;

            for($i=0;$i<count($notc1);$i++){
                
                $referencia .= substr($request->factura["clave_acceso"],24,3)."-".substr($request->factura["clave_acceso"],27,3)."-".substr($request->factura["clave_acceso"],30,9) . ";" .$notc1[$i]->id_ctascobrar . ";" . number_format($valor1,2,".","") . ";ntcv:" . $id. ";";
            }
            $ref = substr($referencia,0,-1);
            $cxcp = Ctas_cobrar_pagos::findOrFail($idcxcp);
            $cxcp->referencia = $ref;
            $cxcp->save();
            //return $idcxcp;
            for($i=0;$i<count($notc1);$i++){
                $cta3=DB::select("SELECT * from ctas_cobrar where id_ctascobrar={$notc1[$i]->id_ctascobrar} and valor_pagado>valor_cuota and valor_devolucion is not null and id_factura={$id_factcompra}");
                if(count($cta3)>0){
                    //$id_cxc=$cxc->id_ctascobrar;  
                    $id_ctacobrar=$cta3[0]->id_ctascobrar;
                    DB::update("UPDATE ctas_cobrar set valor_pagado=valor_cuota where id_ctascobrar=$id_ctacobrar");
                    $monto=$cta3[0]->valor_pagado-$cta3[0]->valor_cuota;
                    $id_cliente=$cta3[0]->id_cliente;
                    $cxc2 = new Cuentaporcobrar();
                    $cxc2->num_cuota = 1;
                    $cxc2->fecha_pago = $hoy;
                    $cxc2->valor_cuota = $monto;
                    $cxc2->estado = 1;
                    $cxc2->tipo = 3;
                    $cxc2->valor_pagado = 0;
                    $cxc2->abono = $monto;
                    if(count($fp)>0){
                        $cxc2->id_forma_pagos = $fp[0]->id_forma_pagos;
                    }
                    $cxc2->id_cliente = $id_cliente;
                    $cxc2->fecha_registro = $request->factura["fecha"];
                    $cxc2->fecha_pago = $request->factura["fecha"];
                    $cxc2->posicion = $conteo;
                    //$cxc2->ucrea=session()->get('usuariosesion')['id'];
                    $cxc2->save();
                    $id_cxc2=$cxc2->id_ctascobrar;

                    $cxcp2 = new Ctas_cobrar_pagos();
                    $cxcp2->pagos_por = "Anticipo";
                    $cxcp2->valor_seleccionado = $monto;
                    $cxcp2->valor_real_pago = $monto;
                    if(count($fp)>0){
                        $cxcp2->id_forma_pagos = $fp[0]->id_forma_pagos;
                    }
                    $cxcp2->fecha_pago = $hoy;
                    $cxcp2->fecha_registro = $request->factura["fecha"];
                    $cxcp2->id_cliente = $id_cliente;
                    $cxcp2->posicion = $conteo;
                    //$cxcp2->anticipo = 1;
                    $cxcp2->referencia = $id_cxc2;
                    //$cxcp2->ucrea = $request->id_user;
                    $cxcp2->save();
                
                }
            }
        }
        $fact = Notacredito::select('nota_credito.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'nota_credito.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'nota_credito.id_empresa')
            ->join('cliente', 'cliente.id_cliente', '=', 'nota_credito.id_cliente')
            ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
            ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
            ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
            ->where("nota_credito.id_nota_credito", "=", $id)
            ->orderByRaw('nota_credito.id_nota_credito DESC')->get();
        return  $fact[0];
    }
    public function editar_factura(Request $request)
    {
        ini_set('max_execution_time', 800);
        $notacredito = Notacredito::findOrFail($request->id);
        $notacredito->modo = 1;
        $notacredito->ambiente = $request->factura["ambiente"];
        $notacredito->tipo_emision = 1;
        $notacredito->fecha_emision = $request->factura["fecha"];
        $notacredito->forma_pago = $request->factura["forma_pago"];
        $notacredito->autorizacionfactura = $request->factura["documento"];
        $notacredito->clave_acceso = $request->factura["clave_acceso"];
        $notacredito->fechaAutorizacion = $request->factura["fecha_doc"];
        $notacredito->observacion = $request->factura["observacion"];
        $notacredito->subtotal_sin_impuesto = $request->subtotal;
        $notacredito->subtotal_12 = $request->subtotal12;
        $notacredito->subtotal_0 = $request->subtotal0;
        $notacredito->subtotal_no_obj_iva = $request->no_impuesto;
        $notacredito->descuento = $request->descuento;
        $notacredito->valor_ice = '0.00';
        $notacredito->valor_irbpnr = '0.00';
        $notacredito->iva_12 = $request->valor12;
        $notacredito->estatus = 1;
        $notacredito->estado = 1;
        $notacredito->valor_total = $request->total;
        $notacredito->id_cliente = $request->cliente;
        $notacredito->id_user = $request->usuario["id"];
        $notacredito->id_punto_emision = $request->usuario["id_punto_emision"];
        $notacredito->id_empresa = $request->usuario["id_empresa"];
        $notacredito->id_establecimiento = $request->usuario["id_establecimiento"];
        $notacredito->id_proyecto = $request->factura["proyectos"];
        $notacredito->totalpropinaf = '0.00';
        $notacredito->pp_descuento = $request->descuento;
        $notacredito->motivo = $request->factura["motivo"];
        $notacredito->updated_by = session()->get('usuariosesion')['id'];
        $notacredito->save();

        $hoy = Carbon::now();
        $detalles_existentes = [];
        $savebode = 0;
        for ($a = 0; $a < count($request->productos); $a++) {
            if (isset($request->productos[$a]["id_detalle_nota_credito"])) {
                $detalle = Detalle_nota_credito::findOrFail($request->productos[$a]["id_detalle_nota_credito"]);
                $detalle->nombre = $request->productos[$a]["nombre"];
                $detalle->cantidad = $request->productos[$a]["cantidad"];
                $detalle->precio = $request->productos[$a]["precio"];
                if(isset($request->productos[$a]["cantidad_dsc"])){
                    $detalle->cantidad_dsc = $request->productos[$a]["cantidad_dsc"];
                }
                if(isset($request->productos[$a]["descuento_comp"])){
                    $detalle->descuento_comp = $request->productos[$a]["descuento_comp"];
                    $detalle->descuento = number_format($request->productos[$a]["descuento_comp"]/$request->productos[$a]["cantidad_dsc"]*$request->productos[$a]["cantidad"],2,".","");
                    $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - number_format($request->productos[$a]["descuento_comp"]/$request->productos[$a]["cantidad_dsc"]*$request->productos[$a]["cantidad"],2,".",""));
                }else{
                    $detalle->descuento = $request->productos[$a]["descuento"];
                    $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                }
                $detalle->id_iva = $request->productos[$a]["iva"];
                $detalle->id_ice = $request->productos[$a]["ice"];
                $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                $detalle->id_nota_credito = $request->id;
                $detalle->id_producto = $request->productos[$a]["id_producto"];
                $detalle->id_proyecto = $request->productos[$a]["proyecto"];
                $detalle->save();

                $rees = DB::select("SELECT * FROM detalle_nota_credito WHERE id_detalle_nota_credito = " . $request->productos[$a]["id_detalle_nota_credito"]);
                $valer = $rees[0]->cantidad;
                $valorreal = ($request->productos[$a]["cantidad"]) - $valer;
                array_push($detalles_existentes, $request->productos[$a]["id_detalle_nota_credito"]);
                if (isset($request->productos[$a]["id_producto_bodega"])) {
                    $cant = $request->productos[$a]["cantidad"];
                    $idpb = $request->productos[$a]["id_producto_bodega"];
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad + ($valorreal) WHERE id_producto_bodega = $idpb");

                    if ($valorreal > 0) {
                        $idempresa = $request->usuario["id_empresa"];
                        //registro de ingreso
                        $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1;");
                        $numeroingreso = "";
                        if (count($numegre) == 1) {
                            $dato = $numegre[0]->num_ingreso;
                            $tot = $dato + 1;
                            $numeroingreso = $tot;
                        } else {
                            $numeroingreso = 1;
                        }
                    } else {
                        $idempresa = $request->usuario["id_empresa"];
                        //registro de egreso
                        $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $idempresa ORDER BY  num_egreso DESC LIMIT 1;");
                        $numeroegreso = "";
                        if (count($numegre) == 1) {
                            $dato = $numegre[0]->num_egreso;
                            $tot = $dato + 1;
                            $numeroegreso = $tot;
                        } else {
                            $numeroegreso = 1;
                        }

                        $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                        $idbodega = $reses[0]->id_bodega;
                    }
                }
            } else {
                $detalle = new Detalle_nota_credito();
                $detalle->nombre = $request->productos[$a]["nombre"];
                $detalle->cantidad = $request->productos[$a]["cantidad"];
                $detalle->precio = $request->productos[$a]["precio"];
                $detalle->descuento = $request->productos[$a]["descuento"];
                $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                $detalle->id_iva = $request->productos[$a]["iva"];
                $detalle->id_ice = $request->productos[$a]["ice"];
                $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                $detalle->id_nota_credito = $request->id;
                $detalle->id_producto = $request->productos[$a]["id_producto"];
                $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                $detalle->save();
                $detallenc = $detalle->id_detalle_nota_credito;
                array_push($detalles_existentes, $detallenc);

                $idempresa = $request->usuario["id_empresa"];
                //registro de ingreso
                $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1;");
                $numeroingreso = "";
                if (count($numegre) == 1) {
                    $dato = $numegre[0]->num_ingreso;
                    $tot = $dato + 1;
                    $numeroingreso = $tot;
                } else {
                    $numeroingreso = 1;
                }

                if (isset($request->productos[$a]["id_producto_bodega"])) {
                    $cant = $request->productos[$a]["cantidad"];
                    $idpb = $request->productos[$a]["id_producto_bodega"];
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant WHERE id_producto_bodega = $idpb");
                }
            }
        }

        $id = $request->id;
        $bsbs = "SELECT * FROM detalle_nota_credito WHERE id_nota_credito = $id AND ";
        foreach ($detalles_existentes as $dt_id) {
            $bsbs .= "id_detalle_nota_credito != $dt_id AND ";
        }
        $res_bsbs = substr($bsbs, 0, -4);
        $seldbs = DB::select($res_bsbs);

        if ($seldbs) {
            for ($i = 0; $i < count($seldbs); $i++) {
                $rescuse_id = DB::select("SELECT * FROM detalle_nota_credito WHERE id_detalle_nota_credito = " . $seldbs[$i]->id_detalle_nota_credito);
                if (isset($rescuse_id[0]->id_producto_bodega)) {
                    $rescuse_id_r = $rescuse_id[0]->id_producto_bodega;
                    $rescuse_id_c = $rescuse_id[0]->cantidad;
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad - $rescuse_id_c, costo_total = (cantidad - $rescuse_id_c) * costo_unitario WHERE id_producto_bodega = $rescuse_id_r");
                }
                DB::delete("DELETE FROM bodega_ingreso_detalle WHERE id_detalle = " . $seldbs[$i]->id_detalle);
                DB::delete("DELETE FROM detalle WHERE id_detalle = " . $seldbs[$i]->id_detalle);
            }
        }
        $id_factcompra = 0;
        if(isset($request->id_factura) && $request->id_factura!==null){
            $id_factcompra = $request->id_factura;
        }
        
        $notc = DB::select("SELECT * FROM ctas_cobrar WHERE id_factura = $id_factcompra AND id_nota_debito IS NULL  AND valor_devolucion is not null");
        if(count($notc)>0){
            $cont = count($notc);
            $valor = $request->total / $cont;
            $notc1 = DB::select("SELECT * FROM ctas_cobrar WHERE id_factura = $id_factcompra AND id_nota_debito IS NULL  AND valor_devolucion is not null");
        }else{
            $notc1 =[];
        }
        
        $cont1 = count($notc1);
        if(count($notc1)>0){
            $valor1 = $request->total / $cont1;
            DB::update("UPDATE ctas_cobrar SET valor_pagado = valor_pagado -(valor_devolucion - $valor1), valor_devolucion =  $valor1,updated_by={$request->usuario["id"]} WHERE id_factura =  $id_factcompra AND id_nota_debito IS NULL  AND valor_devolucion is not null");
            $cta_pago=DB::select("SELECT ctas_cobrar_pagos.* from ctas_cobrar_pagos INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar_pagos.id_cliente where referencia like '%;ntcc:$id%' and cliente.id_empresa={$request->usuario["id_empresa"]}");
            for($i=0;$i<count($notc1);$i++){
                if(isset($cta_pago[$i]->id_ctas_cobrar_pagos)){
                    $cxcp = Ctas_cobrar_pagos::find($cta_pago[$i]->id_ctas_cobrar_pagos);
                    $cxcp->valor_seleccionado = $valor1;
                    $cxcp->valor_real_pago = $valor1;
                    $cxcp->fecha_pago = $hoy;
                    $referencia="";
                    $referencia = substr($request->factura["clave_acceso"],24,3)."-".substr($request->factura["clave_acceso"],27,3)."-".substr($request->factura["clave_acceso"],30,9) . ";" .$notc1[$i]->id_ctascobrar . ";" . number_format($valor1,2,".","") . ";ntcv:" . $id. ";";
                    $ref = substr($referencia,0,-1);
                    $cxcp->referencia = $ref;
                    $cxcp->save();
                }
            }
        }

        $fact = Notacredito::select('nota_credito.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'nota_credito.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'nota_credito.id_empresa')
            ->join('cliente', 'cliente.id_cliente', '=', 'nota_credito.id_cliente')
            ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
            ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
            ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
            ->where("nota_credito.id_nota_credito", "=", $request->id)
            ->orderByRaw('nota_credito.id_nota_credito DESC')->get();
        return  $fact[0];
    }
    public function clave($id)
    {
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , if(pe.secuencial_nota_credito<=1 || pe.secuencial_nota_credito is NULL,1,pe.secuencial_nota_credito) as numeral FROM user u INNER JOIN empresa e on e.id_empresa=u.id_empresa INNER JOIN establecimiento es on es.id_empresa=e.id_empresa INNER JOIN punto_emision pe on pe.id_empresa=e.id_empresa WHERE u.id = " . $id);

        $valor = $respuesta[0]->numeral;

        return [
            'secuencial' => $valor,
            'recupera' => $respuesta
        ];
    }
    public function ver($id)
    {
        $cuerpo = DB::select("SELECT nc.*, c.nombre, c.identificacion, c.email, c.telefono, c.direccion, c.tipo_identificacion FROM nota_credito nc INNER JOIN cliente c ON c.id_cliente = nc.id_cliente WHERE id_nota_credito = " . $id);
        $productos = DB::select("SELECT dnc.*, p.cod_principal, p.cod_alterno FROM detalle_nota_credito dnc INNER JOIN producto p ON p.id_producto=dnc.id_producto WHERE id_nota_credito = " . $id);
        $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = " . $cuerpo[0]->id_empresa);
        return [
            'cuerpo' => $cuerpo[0],
            'productos' => $productos,
            'empresa' => $empresa[0]
        ];
    }
    public function recuperar($id)
    {
        $factura = DB::select("SELECT * FROM nota_credito WHERE id_nota_credito = " . $id);
        $productos = DB::select("SELECT dnc.*, p.cod_principal, p.cod_alterno FROM detalle_nota_credito dnc INNER JOIN producto p ON p.id_producto=dnc.id_producto WHERE dnc.id_nota_credito = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);
        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'cliente' => $cliente[0],
        ];
    }
    public function eliminar($id, $documento)
    {
        $hoy = Carbon::now();
        //dd($nro_nota_credito_bodega);
        $del = Notacredito::findOrFail($id);
        $del->estado = 0;
        $del->save();
        $id_empresa = $del->id_empresa;

        $result = DB::select("SELECT * FROM factura WHERE clave_acceso LIKE '%$documento%' and id_empresa={$id_empresa}");
        $id_factura = $result[0]->id_factura;
        DB::update("UPDATE ctas_cobrar SET valor_cuota = valor_cuota + IF(numero_transaccion IS NULL, 0, numero_transaccion) WHERE id_factura =  $id_factura");
        $ntc = DB::select("SELECT * from nota_credito where id_nota_credito={$id}");
        $nro_nota_credito_bodega = substr($ntc[0]->clave_acceso, -19, -10);
        $egreso = DB::select("SELECT bed.*,be.id_empresa,be.id_factura,be.id_bodega from bodega_ingreso_detalle as bed INNER JOIN bodega_ingreso as be ON be.id_bodega_ingreso=bed.id_bodega_ingreso WHERE be.id_empresa = {$id_empresa} and (be.observ_ingreso='Nota Credito Venta: {$nro_nota_credito_bodega}' or be.observ_ingreso='Nota Credito: {$nro_nota_credito_bodega}')");
        $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $id_empresa ORDER BY  num_egreso DESC LIMIT 1;");
        $numeroegreso = "";
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$id_empresa} limit 1");
        //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
        if (count($numegre) == 1) {
            $dato = $numegre[0]->num_egreso;
            $tot = $dato + 1;
            $numeroegreso = $tot;
        } else {
            $numeroegreso = 1;
        }
        $savebode = 0;
        $id_bodega_ingreso = "";
        if (count($egreso) > 0) {
            for ($i = 0; $i < count($egreso); $i++) {
                DB::update("UPDATE producto_bodega SET cantidad = cantidad - {$egreso[$i]->cantidad}, costo_total = costo_total - {$egreso[$i]->costo_total},costo_unitario=costo_total/cantidad WHERE id_producto = {$egreso[$i]->id_producto} and id_bodega={$egreso[$i]->id_bodega}");
                if ($savebode == 0) {
                    $egresos = new BodegaEgreso();
                    $egresos->num_egreso = $numeroegreso;
                    $egresos->fecha_egreso = $hoy;
                    $egresos->tipo_egreso = "Egreso de Factura";
                    $egresos->observ_egreso = 'Cancelacion Nota Credito Venta: ' . $nro_nota_credito_bodega;
                    $egresos->id_proyecto = $proyecto[0]->id_proyecto;
                    //if (isset($egreso[$i]->id_bodega)) {
                    $egresos->id_bodega = $egreso[$i]->id_bodega;
                    //}
                    $egresos->id_empresa = $egreso[$i]->id_empresa;
                    //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    $egresos->id_nota_credito = $id;
                    $egresos->save();
                    $id_bodega_egreso = $egresos->id_bodega_egreso;
                    $savebode++;
                }
                $bed = new BodegaEgresoDetalle();
                $bed->cantidad = $egreso[$i]->cantidad;
                $bed->costo_unitario = $egreso[$i]->costo_unitario;
                $bed->costo_total = $egreso[$i]->costo_total;
                $bed->id_bodega_egreso = $id_bodega_egreso;
                $bed->id_producto = $egreso[$i]->id_producto;
                $bed->id_proyecto = $egreso[$i]->id_proyecto;
                //$bed->id_detalle = $id_detalle;
                $bed->save();
            }
        }
    }
    public function buscarfactura(Request $request)
    {
        $factura = Factura::select("*")->where("clave_acceso", "like", '%' . $request->factura . '%')->where("id_empresa", "=", $request->id_empresa)->get();
        if (count($factura) > 0) {
            $detalle = Detalle::select("detalle.*", "producto.cod_principal", "producto.cod_alterno")->leftJoin("producto", "producto.id_producto", "=", "detalle.id_producto")->where("detalle.id_factura", "=", $factura[0]->id_factura)->get();
            $cliente = Cliente::select("*")->where("id_cliente", "=", $factura[0]->id_cliente)->get();
            return [
                'factura' => $factura[0],
                'detalle' => $detalle,
                'cliente' => $cliente[0]
            ];
        } else {
            return 'error';
        }
    }
    public function generarPDF(Request $request)
    {
        $nota_credito = DB::select("SELECT nota_credito.*,empresa.razon_social,empresa.direccion_empresa,establecimiento.direccion,empresa.obligado_contabilidad,empresa.ruc_empresa,punto_emision.codigo as cod_pto,SUBSTR(nota_credito.clave_acceso,31,9) as secuencia,establecimiento.codigo as cod_estab,cliente.nombre,cliente.identificacion,cliente.direccion as direccion_cliente,cliente.telefono,cliente.email FROM nota_credito
        INNER JOIN empresa
        on empresa.id_empresa=nota_credito.id_empresa
        INNER JOIN punto_emision
        on punto_emision.id_punto_emision=nota_credito.id_punto_emision
        INNER JOIN establecimiento
        on establecimiento.id_establecimiento=nota_credito.id_establecimiento
        INNER JOIN cliente
        on cliente.id_cliente=nota_credito.id_cliente
        where id_nota_credito=" . $request->id_nota_credito);
        $detalle = DB::select("SELECT * from detalle_nota_credito where id_nota_credito=" . $request->id_nota_credito);

        $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->id_empresa);
        $response = new generarPDF();
        $response->notaCreditoEjemploPDF($nota_credito[0], $detalle, $request->id_empresa, $empresa[0]->logo, null, $nota_credito[0]->fcrea, null, null, $empresa[0]->nombre_empresa);
        return response('no-data-report', 200)->header('Content-Type', 'application/json');
    }
    public function generarReport(Request $request)
    {
        $queries = [];
        if ($request->client) {
            $info_client = json_decode($request->client, true);
            if ($info_client["id"] != 0) {
                array_push($queries, "ntc.id_cliente = {$info_client["id"]}\n");
            }
        }
        // if ($request->rol_user == 2) {
        //     $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->user);
        //     if ($vnd) {
        //         array_push($queries, "f.id_vendedor = {$vnd[0]->id_vendedor}\n");
        //     }
        // } else {
        //     if ($request->seller) {
        //         $info_seller = json_decode($request->seller, true);
        //         if ($info_seller["id"] != 0) {
        //             array_push($queries, "f.id_vendedor = {$info_seller["id"]}\n");
        //         }
        //     }
        // }
        $nombre_vendedor = "";
        if ($request->rol_user !== "2") {
            

            if ($request->seller) {

                $info_seller = json_decode($request->seller, true);
                
                if ($info_seller["id"] != 0) {
                   
                    array_push($queries, "(ntc.created_by = {$info_seller["id"]})\n");
                    
                }
                // else{
                //     if ($vnd) {
                //         array_push($queries, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
                //                                 (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
                //         array_push($pagos, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
                //                                 (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
                //         $nombre_vendedor=$vnd[0]->nombre_vendedor;
                //     }
                // }
            }
        } else {
            //dd("Entro al vendedor");
            $vnd = DB::select("SELECT *,concat(nombres,' ',apellidos) as nombre_vendedor from user where id=" . $request->user);
            if (count($vnd)>0) {

                
                        array_push($queries, "(ntc.created_by = {$vnd[0]->id})\n");
                //             array_push($pagos, "ctap.ucrea = {$vnd[0]->id}\n");
                //             array_push($solo_pagos, "ctap.ucrea = {$vnd[0]->id}\n");
                //             $nombre_vendedor=$vnd[0]->nombre_vendedor;
                // 
            }
        }
        $nombre_producto = '';
        if ($request->products) {
            $to_array = function ($product) {
                $new_product = json_decode($product);
                return $new_product->id;
            };
            if (count($request->products) == 1) {
                $to_array_2 = function ($product) {
                    $new_product = json_decode($product);
                    return $new_product->nombre;
                };
                $info_products_2 = implode(",", array_map($to_array_2, $request->products));
                $nombre_producto = $info_products_2;
            }
            $info_products = implode(",", array_map($to_array, $request->products));
            array_push($queries, "ntcd.id_producto in ({$info_products})\n");
        }
        if ($request->totalCount) {
            if ($request->typeSearchTotalCount == 1) {
                $typeSearchTotalCount = ">=";
            }
            if ($request->typeSearchTotalCount == 0) {
                $typeSearchTotalCount = "=";
            }
            if ($request->typeSearchTotalCount == -1) {
                $typeSearchTotalCount = "<=";
            }
            if (is_numeric($request->totalCount)) {
                $request->totalCount = intval($request->totalCount);
                array_push($queries, "ntc.valor_total {$typeSearchTotalCount} {$request->totalCount}\n");
            }
        }
        if ($request->selectedEstablishment) {
            $info_establishment = json_decode($request->selectedEstablishment);
            if ($info_establishment->id != 0) {
                array_push($queries, "ntc.id_establecimiento = {$info_establishment->id}\n");
            }
        }
        if ($request->selectedPointOfEmission) {
            $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
            if ($info_pointOfEmission->id != 0) {
                array_push($queries, "ntc.id_punto_emision = {$info_pointOfEmission->id}\n");
            }
        }
        // if ($request->selectedProject) {
        //     $info_project = json_decode($request->selectedProject);
        //     if ($info_project->id != 0) {
        //         array_push($queries, "f.id_proyecto = {$info_project->id}\n");
        //     }
        // }
        if ($request->date) {
            if (!is_null(json_decode($request->date))) {
                $request->date = json_decode($request->date);
                $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                array_push($queries, "date(ntc.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
            } else {
                $date = str_replace("-010-", "-10-", $request->date);
                //$fecha_emision=substr($fecha_emision,0,-3);
                $ntc = DB::select("SELECT min(fecha_emision) as fecha_emision from nota_credito where id_empresa={$request->company}");
                $date_initial = str_replace("-010-", "-10-", $ntc[0]->fecha_emision);
                $date_final = $date;
                array_push($queries, "date(ntc.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
            }
        }
        // if ($request->all === "false") {
        //     if ($request->retention === "true") {
        //         array_push($queries, "exists (select * from retencion_factura rf where rf.id_factura = f.id_factura)\n");
        //     }
        //     if ($request->credit === "true") {
        //         array_push($queries, "exists (SELECT * FROM ctas_cobrar cxc where cxc.id_factura = f.id_factura)\n");
        //     }
        // }
        $queries = implode(" and ", $queries);
        $query = "SELECT
        ntc.*,
        ntc.created_by as ntccreated_by,
        ntc.updated_by as ntcupdated_by,
        c.id_vendedor as cid_vendedor,
        c.identificacion,
        c.nombre,
        e.id_empresa as idempresa,
        e.nombre_empresa,
        e.logo
        from nota_credito as ntc
        inner join detalle_nota_credito ntcd
        on ntcd.id_nota_credito = ntc.id_nota_credito
        inner join cliente c
        on ntc.id_cliente = c.id_cliente
        inner join empresa e
        on ntc.id_empresa = e.id_empresa
        where {$queries}
        and ntc.id_empresa={$request->company}
        and ntc.estado>0
        order by ntc.fecha_emision,ntc.clave_acceso asc";
        $reporte = DB::select($query);
        if (session()->get('usuariosesion')['filtro_list'] == 1 || session()->get('usuariosesion')['id_rol'] == 2) {
            $dat = [];
            foreach ($reporte as $report) {
                if ($report->ntccreated_by == session()->get('usuariosesion')['id'] || $report->ntcupdated_by == session()->get('usuariosesion')['id'] || $report->cid_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $report);
                }
            }
            $reporte = $dat;
        }
        $Reportes = new generarReportes();
        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            if (property_exists($request->date, 'initialDate')) {
                $strPDF = $Reportes->nota_credito_reporte($reporte, $date_initial, $date_final, $nombre_producto);
            } else {
                $strPDF = $Reportes->nota_credito_reporte($reporte, $date_initial, $date_final, $nombre_producto);
            }
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
}
