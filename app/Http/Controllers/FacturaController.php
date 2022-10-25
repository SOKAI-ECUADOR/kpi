<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cuentaporcobrar;
use App\Models\Detalle;
use App\Models\DetalleGuiaRemision;
use App\Models\Factura;
use App\Models\FacturaGuiaDeRemision;
use App\Models\Factura_pagos;
use App\Models\Guia_remision;
use App\Models\Impuesto;
use App\Models\Retencion;
use App\Models\Retencion_factura;
use App\Models\Retencion_factura_comp;
use App\Models\Detalle_nota_credito;
use App\Models\Usuario;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use App\Models\Ptoemision;
use App\Models\ProductoBodega;

use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;

use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

use App\Models\NotaVentaPago;
use App\Models\NotaVentaRetencion;

use App\Models\NotaVenta;
use App\Models\NotaVentaDetalle;

use App\Models\NotaVentaGuia;
use App\Models\DetalleGuiaRemisionNotaVenta;

use App\Models\Cuentaporpagar;
use App\Models\Ctas_pagar_pagos;


use App\Models\Ctas_cobrar_pagos;

use App\Models\CuotaExtraFactura;
use App\Models\CuotaExtraNotaVenta;

include 'class/generarReportes.php';
include_once getenv("FILE_CONFIG_PHP");

use generarReportes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include 'AsientosAutomaticosController.php';

include 'class/sendEmail.php';

use sendEMail;

include 'class/imprimiTicket.php';

use imprimiTicket;

use DOMDocument;
use Dompdf\Dompdf;
use Dompdf\Options;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        //dd($request);
        $buscar = str_replace(array(" ", "_", "-"), "%", $request->buscar);
        $rol = DB::select("SELECT user.*,rol.nombre,empresa.nombre_empresa from user,rol,empresa where user.id_empresa=empresa.id_empresa and rol.id_rol=user.id_rol and  id={$request->datos["id"]}");
        if ($rol[0]->nombre_empresa == 'GRUPO SOLIS INGENIERIA ESPECIALIZADA' || $rol[0]->nombre_empresa == 'QUIMALCO CIA LTDA') {
            if ($buscar == '') {
                //if ($rol[0]->nombre !== 'Usuario') {
                $recupera = DB::select("SELECT `factura`.*,factura.migo as migo_factura,factura.created_by as  created_by_factura,factura.updated_by as updated_by_factura,factura.id_vendedor as id_vendedor_factura, factura.estado as estadof, if(`factura`.`fecha_factura` is null,`factura`.`fmodifica`,`factura`.`fecha_factura`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, (select codigo from establecimiento where id_establecimiento=factura.id_establecimiento) as `codigoes`, (select codigo from punto_emision where id_punto_emision=factura.id_punto_emision) as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS fecha_inicio_tr from `factura` inner join `empresa` on `empresa`.`id_empresa` = " . $request->datos["id_empresa"] . " inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = " . $request->datos["id_establecimiento"] . " inner join `punto_emision` on `punto_emision`.`id_punto_emision` = " . $request->datos["id_punto_emision"] . " inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where `factura`.`id_empresa` = " . $request->datos["id_empresa"] . " and `factura`.`modo` = 1 and `factura`.`modo_acumulado` is null ORDER BY factura.fecha_emision DESC, SUBSTR(factura.clave_acceso,25,15) DESC");
                // } else {
                //     $recupera = DB::select("SELECT `factura`.*, factura.estado as estadof, if(`factura`.`fecha_factura` is null,`factura`.`fmodifica`,`factura`.`fecha_factura`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS fecha_inicio_tr from `factura` inner join `empresa` on `empresa`.`id_empresa` = " . $request->datos["id_empresa"] . " inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = " . $request->datos["id_establecimiento"] . " inner join `punto_emision` on `punto_emision`.`id_punto_emision` = " . $request->datos["id_punto_emision"] . " inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where `factura`.`id_empresa` = " . $request->datos["id_empresa"] . " and `factura`.`modo` = 1 and `factura`.`modo_acumulado` is null and `factura`.`id_user`={$request->datos["id"]} ORDER BY factura.fecha_emision DESC, factura.id_factura DESC");
                // }

                //$recupera=Factura::select("*","factura.estado as estadof","if(factura.fecha_factura is null,factura.fmodifica,factura.fecha_factura) as fecha_autorizacion","empresa")
            } else {
                //if ($rol[0]->nombre !== 'Usuario') {
                $recupera = DB::select("SELECT `factura`.*,factura.migo as migo_factura,factura.created_by as  created_by_factura,factura.updated_by as updated_by_factura,factura.id_vendedor as id_vendedor_factura, factura.estado as estadof, if(`factura`.`fecha_factura` is null,`factura`.`fmodifica`,`factura`.`fecha_factura`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, (select codigo from establecimiento where id_establecimiento=factura.id_establecimiento) as `codigoes`, (select codigo from punto_emision where id_punto_emision=factura.id_punto_emision) as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS fecha_inicio_tr from `factura` inner join `empresa` on `empresa`.`id_empresa` = " . $request->datos["id_empresa"] . " inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = " . $request->datos["id_establecimiento"] . " inner join `punto_emision` on `punto_emision`.`id_punto_emision` = " . $request->datos["id_punto_emision"] . " inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR factura.respuesta like '%$buscar%' OR factura.clave_acceso like '%$buscar%') AND `factura`.`id_empresa` = " . $request->datos["id_empresa"] . " and `factura`.`modo` = 1 and `factura`.`modo_acumulado` is null ORDER BY factura.fecha_emision DESC, SUBSTR(factura.clave_acceso,25,15) DESC");
                // } else {
                //     $recupera = DB::select("SELECT `factura`.*,factura.estado as estadof, if(`factura`.`fecha_factura` is null,`factura`.`fmodifica`,`factura`.`fecha_factura`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS fecha_inicio_tr from `factura` inner join `empresa` on `empresa`.`id_empresa` = " . $request->datos["id_empresa"] . " inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = " . $request->datos["id_establecimiento"] . " inner join `punto_emision` on `punto_emision`.`id_punto_emision` = " . $request->datos["id_punto_emision"] . " inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR factura.respuesta like '%$buscar%' OR factura.clave_acceso like '%$buscar%') AND `factura`.`id_empresa` = " . $request->datos["id_empresa"] . " and `factura`.`modo` = 1 and `factura`.`modo_acumulado` is null and `factura`.`id_user`={$request->datos["id"]} ORDER BY factura.fecha_emision DESC, factura.id_factura DESC");
                // }
            }
        } else {
            if($rol[0]->nombre_empresa == 'CORTINAS DLUXE'){
                if ($buscar == '') {
                    $recupera = DB::select("SELECT `factura`.*,factura.migo as migo_factura,factura.created_by as  created_by_factura,factura.updated_by as updated_by_factura,factura.id_vendedor as id_vendedor_factura, factura.estado as estadof, if(`factura`.`fecha_factura` is null,`factura`.`fmodifica`,`factura`.`fecha_factura`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS fecha_inicio_tr 
                                            from `factura` 
                                            inner join `empresa` on `empresa`.`id_empresa` = `factura`.`id_empresa` 
                                            inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` 
                                            inner join `establecimiento` on `establecimiento`.`id_establecimiento` = `factura`.`id_establecimiento` 
                                            inner join `punto_emision` on `punto_emision`.`id_punto_emision` = `factura`.`id_punto_emision` 
                                            inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` 
                                            where `factura`.`id_empresa` = {$request->datos["id_empresa"]} and `factura`.`modo` = 1 and `factura`.`modo_acumulado` is null 
                                            ORDER BY factura.fecha_emision DESC, SUBSTR(factura.clave_acceso,25,15) DESC");
                } else {
                    $recupera = DB::select("SELECT `factura`.*,factura.migo as migo_factura,factura.created_by as  created_by_factura,factura.updated_by as updated_by_factura,factura.id_vendedor as id_vendedor_factura,factura.estado as estadof, if(`factura`.`fecha_factura` is null,`factura`.`fmodifica`,`factura`.`fecha_factura`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS fecha_inicio_tr 
                    from `factura` 
                    inner join `empresa` on `empresa`.`id_empresa` = `factura`.`id_empresa` 
                    inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` 
                    inner join `establecimiento` on `establecimiento`.`id_establecimiento` = `factura`.`id_establecimiento` 
                    inner join `punto_emision` on `punto_emision`.`id_punto_emision` = `factura`.`id_punto_emision` 
                    inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` 
                    where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR factura.respuesta like '%$buscar%' OR factura.clave_acceso like '%$buscar%') 
                        AND `factura`.`id_empresa` = {$request->datos["id_empresa"]}  and `factura`.`modo` = 1 and `factura`.`modo_acumulado` is null ORDER BY factura.fecha_emision DESC, SUBSTR(factura.clave_acceso,25,15) DESC");
                }
            }else{
                if ($buscar == '') {
                    $recupera = DB::select("SELECT `factura`.*,factura.migo as migo_factura,factura.created_by as  created_by_factura,factura.updated_by as updated_by_factura,factura.id_vendedor as id_vendedor_factura, factura.estado as estadof, if(`factura`.`fecha_factura` is null,`factura`.`fmodifica`,`factura`.`fecha_factura`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS fecha_inicio_tr 
                                            from `factura` 
                                            inner join `empresa` on `empresa`.`id_empresa` = `factura`.`id_empresa` 
                                            inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` 
                                            inner join `establecimiento` on `establecimiento`.`id_establecimiento` = `factura`.`id_establecimiento` 
                                            inner join `punto_emision` on `punto_emision`.`id_punto_emision` = `factura`.`id_punto_emision` 
                                            inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` 
                                            where `factura`.`id_empresa` = {$request->datos["id_empresa"]} and `factura`.`modo` = 1 and `factura`.`modo_acumulado` is null and `factura`.`id_punto_emision`={$request->datos["id_punto_emision"]}
                                            ORDER BY factura.fecha_emision DESC,SUBSTR(factura.clave_acceso,25,15) desc");
                } else {
                    $recupera = DB::select("SELECT `factura`.*,factura.migo as migo_factura,factura.created_by as  created_by_factura,factura.updated_by as updated_by_factura,factura.id_vendedor as id_vendedor_factura,factura.estado as estadof, if(`factura`.`fecha_factura` is null,`factura`.`fmodifica`,`factura`.`fecha_factura`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS fecha_inicio_tr 
                    from `factura` 
                    inner join `empresa` on `empresa`.`id_empresa` = `factura`.`id_empresa` 
                    inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` 
                    inner join `establecimiento` on `establecimiento`.`id_establecimiento` = `factura`.`id_establecimiento` 
                    inner join `punto_emision` on `punto_emision`.`id_punto_emision` = `factura`.`id_punto_emision` 
                    inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` 
                    where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR factura.respuesta like '%$buscar%' OR factura.clave_acceso like '%$buscar%') 
                        AND `factura`.`id_empresa` = {$request->datos["id_empresa"]} and `factura`.`id_punto_emision`={$request->datos["id_punto_emision"]} and `factura`.`modo` = 1 and `factura`.`modo_acumulado` is null ORDER BY factura.fecha_emision DESC,SUBSTR(factura.clave_acceso,25,15) desc");
                }
            }
            
        }
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($recupera); $i++) {
                if ($recupera[$i]->created_by_factura == session()->get('usuariosesion')['id'] || $recupera[$i]->updated_by_factura == session()->get('usuariosesion')['id'] || $recupera[$i]->id_vendedor == session()->get('usuariosesion')['id_vendedor'] || $recupera[$i]->id_vendedor_factura == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $recupera[$i]);
                }
            }
            $recupera = $dat;
        }
        return $recupera;
    }
    public function index_acumulada(Request $request)
    {
        $buscar = str_replace(array(" ", "_", "-"), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = DB::select("SELECT `nota_venta`.*,nota_venta.created_by as created_by_nota_venta,nota_venta.updated_by as updated_by_nota_venta,nota_venta.id_vendedor as id_vendedor_nota_venta, nota_venta.estado as estadof, if(`nota_venta`.`fecha_nota_venta` is null,`nota_venta`.`fmodifica`,`nota_venta`.`fecha_nota_venta`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `nota_venta`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS fecha_inicio_tr from `nota_venta` inner join `empresa` on `empresa`.`id_empresa` = " . $request->datos["id_empresa"] . " inner join `cliente` on `cliente`.`id_cliente` = `nota_venta`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = " . $request->datos["id_establecimiento"] . " inner join `punto_emision` on `punto_emision`.`id_punto_emision` = " . $request->datos["id_punto_emision"] . " inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where `nota_venta`.`id_empresa` = " . $request->datos["id_empresa"] . " and `nota_venta`.`modo` = 1 and `nota_venta`.`modo_acumulado` is null ORDER BY nota_venta.fecha_emision DESC, nota_venta.id_nota_venta DESC");
        } else {
            $recupera = DB::select("SELECT `nota_venta`.*,nota_venta.created_by as created_by_nota_venta,nota_venta.updated_by as updated_by_nota_venta,nota_venta.id_vendedor as id_vendedor_nota_venta,nota_venta.estado as estadof, if(`nota_venta`.`fecha_nota_venta` is null,`nota_venta`.`fmodifica`,`nota_venta`.`fecha_nota_venta`) as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `nota_venta`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento`, (SELECT id_guia FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS guia, (SELECT respuesta FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS respuesta_guia, (SELECT clave_acceso FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS clave_acceso_guia, (SELECT fecha_inicio_tr FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS fecha_inicio_tr from `nota_venta` inner join `empresa` on `empresa`.`id_empresa` = " . $request->datos["id_empresa"] . " inner join `cliente` on `cliente`.`id_cliente` = `nota_venta`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = " . $request->datos["id_establecimiento"] . " inner join `punto_emision` on `punto_emision`.`id_punto_emision` = " . $request->datos["id_punto_emision"] . " inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR nota_venta.respuesta like '%$buscar%' OR nota_venta.clave_acceso like '%$buscar%') AND `nota_venta`.`id_empresa` = " . $request->datos["id_empresa"] . " and `nota_venta`.`modo` = 1 and `nota_venta`.`modo_acumulado` is null ORDER BY nota_venta.fecha_emision DESC, nota_venta.id_nota_venta DESC");
        }
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($recupera); $i++) {
                if ($recupera[$i]->created_by_nota_venta == session()->get('usuariosesion')['id'] || $recupera[$i]->updated_by_nota_venta == session()->get('usuariosesion')['id'] || $recupera[$i]->id_vendedor_nota_venta == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $recupera[$i]);
                }
            }
            $recupera = $dat;
        }
        return $recupera;
    }

    public function dataClient()
    {
        ini_set('max_execution_time', 53200);
        $client = DB::select("SELECT  count(id_cliente) as cantidad,'enero' as mes from cliente where id_empresa=1 and fcrea like '2020-01%' UNION
        select  count(id_cliente) as cantidad,'febrero' as mes from cliente where id_empresa=1 and fcrea like '2020-02%' UNION
        select  count(id_cliente) as cantidad,'marzo' as mes from cliente where id_empresa=1 and fcrea like '2020-03%' UNION
        select  count(id_cliente) as cantidad,'abril' as mes from cliente where id_empresa=1 and fcrea like '2020-04%' UNION
        select  count(id_cliente) as cantidad,'mayo' as mes from cliente where id_empresa=1 and fcrea like '2020-05%' UNION
        select  count(id_cliente) as cantidad,'junio' as mes from cliente where id_empresa=1 and fcrea like '2020-06%' UNION
        select  count(id_cliente) as cantidad,'julio' as mes from cliente where id_empresa=1 and fcrea like '2020-07%' UNION
        select  count(id_cliente) as cantidad,'agosto' as mes from cliente where id_empresa=1 and fcrea like '2020-08%' UNION
        select  count(id_cliente) as cantidad,'septiembre' as mes from cliente where id_empresa=1 and fcrea like '2020-09%' UNION
        select  count(id_cliente) as cantidad,'octubre' as mes from cliente where id_empresa=1 and fcrea like '2020-10%' UNION
        select  count(id_cliente) as cantidad,'noviembre' as mes from cliente where id_empresa=1 and fcrea like '2020-11%' UNION
        select  count(id_cliente) as cantidad,'diciembre' as mes from cliente where id_empresa=1 and fcrea like '2020-12%'");
        //$data=[];
        $datos_filtrado2 = [];
        foreach ($client as $key => $value) {
            if ($client[$key]->cantidad > 0) {
                $datos_filtrado2[] = $client[$key];
            }
        }
        return $datos_filtrado2;
    }

    public function ejemplos($id)
    {
        //return $id;
        //return;
        /*$res =DB::select("SELECT * FROM ctas_cobrar_pagos WHERE referencia LIKE '%790%'");
        $datos = new \ArrayObject();
        for($f=0; $f<count($res); $f++){
            $ref = explode(";",$res[$f]->referencia);
            for($i=0; $i<count($ref); $i++){
                if($i%4==3){
                    if($ref[$i]== 790){
                        $val3 =$ref[$i-3];
                        $val2 =$ref[$i-2];
                        $val1 =$ref[$i-1];
                        $val =$ref[$i];
                        $datos->append("$val3;$val2;$val1;$val");
                    }
                }
            }
        }
        foreach($datos as $rs){
            $revisarid = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE referencia like '%$rs%'");
            $id = $revisarid[0]->id_ctas_cobrar_pagos;

            DB::update("UPDATE ctas_cobrar_pagos SET referencia = replace(referencia, '$rs', '') WHERE referencia like '%$rs%'");

            $revisarids = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = $id");
            $reff = $revisarids[0]->referencia;

            if($reff == ""){
                DB::delete("DELETE FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = $id");
            }
        }
        $res11 =DB::select("SELECT * FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = 20 OR id_ctas_cobrar_pagos = 21 OR id_ctas_cobrar_pagos = 22");
        return $res11;*/
        ini_set('max_execution_time', 53200);
        //CABEZERAS
        //importación
        //bodega_ingreso
        //bodega_egreso
        //bodega_transferencia
        //factura
        //factura_compra
        //nota_credito
        //nota_credito_compra
        //CUERPOS
        //producto_importación
        //bodega_ingreso_detalle
        //bodega_egreso_detalle
        //bodega_transferencia_detalle
        //detalle_factura
        //detalle_factura_compra
        //detalle_nota_credito
        //detalle_nota_credito_compra

        //DB::update(update bodega_egreso set tipo_egreso='Egreso Nota Credito' , observ_ingreso='Nota Credito Compra:' where tipo_ingreso="Ventas" and observ_egreso="NOTA DE CREDITO 111 MAL VALOR"

        //DB::delete("DELETE FROM factura WHERE id_factura=88 OR id_factura=89  ");
        // DB::delete("DELETE FROM bodega_egreso  WHERE observ_egreso = 'Factura Venta'  and id_empresa={$id}");
        // DB::delete("DELETE FROM bodega_ingreso  WHERE observ_ingreso = 'Factura Compra' and id_empresa={$id}");
        // DB::delete("DELETE FROM bodega_ingreso  WHERE observ_ingreso like '%Cancelacion Factura%' and id_empresa={$id}");

        // DB::delete("DELETE FROM bodega_egreso  WHERE observ_egreso = 'Nota de crédito Compra' and id_empresa={$id}");
        // DB::delete("DELETE FROM bodega_egreso  WHERE observ_egreso like '%Cancelacion Nota Credito Venta%' and id_empresa={$id}");

        // DB::delete("DELETE FROM bodega_egreso  WHERE tipo_egreso = 'Egreso Transferencia'and id_empresa={$id} ");
        // DB::delete("DELETE FROM bodega_egreso  WHERE tipo_egreso = 'Egreso Nota Credito Compra'and id_empresa={$id} ");
        // DB::delete("DELETE FROM bodega_ingreso  WHERE tipo_ingreso = 'Ingreso Transferencia' and id_empresa={$id}");

        // DB::delete("DELETE FROM bodega_egreso  WHERE tipo_egreso = 'Egreso de Factura' and id_empresa={$id}");
        // DB::delete("DELETE FROM bodega_ingreso  WHERE tipo_ingreso = 'Ingreso de Factura' and id_empresa={$id}");
        // //areglar datos ---pendiente
        // DB::delete("DELETE FROM bodega_egreso  WHERE tipo_egreso = 'Egreso de Nota de crédito' and id_empresa={$id}");
        // DB::delete("DELETE FROM bodega_ingreso  WHERE tipo_ingreso = 'Ingreso Nota Credito' and id_empresa={$id}");
        DB::delete("DELETE FROM bodega_ingreso  WHERE (tipo_ingreso<>'Inventario Inicial' and tipo_ingreso<>'Ingreso por Ajuste' and tipo_ingreso<>'Importacion' and tipo_ingreso<>'Fabricación') and id_empresa={$id}");
        DB::delete("DELETE FROM bodega_egreso  WHERE (tipo_egreso<>'Egreso por Ajuste' and  tipo_egreso<>'Ventas' and  tipo_egreso<>'Auto Consumo' and tipo_egreso<>'Devolucion por Ingreso') and id_empresa={$id}");


        DB::update("UPDATE producto_bodega bdt INNER JOIN bodega bd ON bd.id_bodega=bdt.id_bodega SET bdt.cantidad=0, bdt.costo_unitario=0, bdt.costo_total=0 where bd.id_empresa={$id}");



        $res = DB::select(" SELECT id_bodega_ingreso as id, 'bodega_ingreso' as tabla, bdt.fcrea                                     FROM bodega_ingreso bdt INNER JOIN bodega bd ON bd.id_bodega=bdt.id_bodega where bd.id_empresa={$id} UNION
                                SELECT id_bodega_egreso as id, 'bodega_egreso' as tabla, bdt.fcrea                                       FROM bodega_egreso bdt INNER JOIN bodega bd ON bd.id_bodega=bdt.id_bodega where bd.id_empresa={$id} UNION
                                SELECT id_bodega_transferencia as id, 'bodega_transferencia_enviada' as tabla, bdt.fcrea                 FROM bodega_transferencia bdt INNER JOIN bodega bd ON bd.id_bodega=bdt.emisor_trans where bd.id_empresa={$id}  UNION
                                SELECT id_bodega_transferencia as id, 'bodega_transferencia_recibida' as tabla, bdt.fmodifica as 'fcrea' FROM bodega_transferencia bdt INNER JOIN bodega bd ON bd.id_bodega=bdt.receptor_trans where bd.id_empresa={$id}  UNION
                                SELECT id_factura as id, 'factura' as tabla, fcrea                                                   FROM factura where  estado=1 and clave_acceso is not null and id_empresa={$id}  UNION
                                SELECT id_factcompra as id, 'factura_compra' as tabla, fcrea                                         FROM factura_compra where descripcion is not null and id_empresa={$id}  UNION
                                SELECT id_nota_credito as id, 'nota_credito' as tabla, fcrea                                         FROM nota_credito where estado=1 and clave_acceso is not null  and id_empresa={$id} UNION
                                SELECT id_nota_credito_compra as id, 'nota_credito_compra' as tabla, fcrea                           FROM nota_credito_compra  where id_empresa={$id}
                                
                                ORDER BY fcrea ASC
                         ");
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        $data = "Registros Generados Exitosamente " . $empresa[0]->nombre_empresa;
        for ($z = 0; $z < count($res); $z++) {
            $id = $res[$z]->id;
            switch ($res[$z]->tabla) {
                case "bodega_ingreso":
                    $bi = DB::select("SELECT * FROM bodega_ingreso WHERE id_bodega_ingreso =$id;");
                    $bid = DB::select("SELECT * FROM bodega_ingreso_detalle WHERE id_bodega_ingreso =$id;");
                    for ($i = 0; $i < count($bid); $i++) {
                        $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $bid[$i]->id_producto . " AND `id_bodega` =" . $bi[0]->id_bodega);
                        if (count($sel) <= 0) {
                            $prb = new ProductoBodega();
                            $prb->cantidad = $bid[$i]->cantidad;
                            $prb->costo_unitario = $bid[$i]->costo_unitario;
                            $prb->costo_total = $bid[$i]->costo_total;
                            $prb->id_producto = $bid[$i]->id_producto;
                            $prb->id_bodega = $bi[0]->id_bodega;
                            $prb->id_empresa = $bi[0]->id_empresa;
                            $prb->save();
                        } else {
                            $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                            $prb->cantidad = $prb->cantidad + $bid[$i]->cantidad;
                            $prb->costo_total = $prb->costo_total + $bid[$i]->costo_total;
                            if ($prb->cantidad != 0) {
                                $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                            } else {
                                $prb->costo_unitario = 0;
                            }
                            $prb->save();
                        }
                    }
                    break;
                case "bodega_egreso":
                    $be = DB::select("SELECT * FROM bodega_egreso WHERE id_bodega_egreso =$id;");
                    $bed = DB::select("SELECT * FROM bodega_egreso_detalle WHERE id_bodega_egreso =$id;");
                    for ($i = 0; $i < count($bed); $i++) {
                        $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $bed[$i]->id_producto . " AND `id_bodega` =" . $be[0]->id_bodega);
                        if (count($sel) == 1) {
                            $pbed = BodegaEgresoDetalle::findOrFail($bed[$i]->id_bodega_egreso_detalle);
                            $pbed->costo_unitario = $sel[0]->costo_unitario;
                            $pbed->costo_total = $bed[$i]->cantidad * $sel[0]->costo_unitario;
                            $pbed->save();

                            $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                            $prb->cantidad = $prb->cantidad - $pbed->cantidad;
                            $prb->costo_total = $prb->costo_total - $pbed->costo_total;
                            if ($prb->cantidad != 0) {
                                $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                            } else {
                                $prb->costo_unitario = 0;
                            }
                            $prb->save();
                        }
                    }
                    break;
                case "bodega_transferencia_enviada":
                    $bte = DB::select("SELECT * FROM bodega_transferencia WHERE id_bodega_transferencia = $id;");
                    $bted = DB::select("SELECT * FROM bodega_transferencia_detalle WHERE id_bodega_transferencia = $id;");
                    //egreso encabezado
                    $egreso = new BodegaEgreso();
                    $egreso->fecha_egreso = $bte[0]->f_iniciacion;
                    $egreso->tipo_egreso = "Egreso Transferencia";
                    $egreso->observ_egreso = "Envio de Transferencia: " . $bte[0]->num_trans;
                    $egreso->id_bodega = $bte[0]->emisor_trans;
                    $egreso->id_empresa = $bte[0]->id_empresa;
                    $egreso->id_bodega_transferencia = $bte[0]->id_bodega_transferencia;
                    $egreso->save();
                    for ($i = 0; $i < count($bted); $i++) {
                        $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $bted[$i]->id_producto . " AND `id_bodega` =" . $bte[0]->emisor_trans);
                        if (count($sel) == 1) {
                            $pbed = new BodegaEgresoDetalle();
                            $pbed->cantidad = $bted[$i]->cant_env;
                            $pbed->costo_unitario = $bted[$i]->costo_unitario;
                            $pbed->costo_total = $bted[$i]->costo_total;
                            $pbed->id_bodega_egreso = $egreso->id_bodega_egreso;
                            $pbed->id_producto = $bted[$i]->id_producto;
                            if (isset($bted[$i]->id_proyecto)) {
                                $pbed->id_proyecto = $bted[$i]->id_proyecto;
                            } else if (isset($bte[0]->id_proyecto)) {
                                $pbed->id_proyecto = $bte[0]->id_proyecto;
                            }
                            $pbed->id_bodega_transferencia_detalle = $bted[$i]->id_bodega_transferencia_detalle;
                            $pbed->save();

                            $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                            $prb->cantidad = $prb->cantidad - $pbed->cantidad;
                            $prb->costo_total = $prb->costo_total - $pbed->costo_total;
                            if ($prb->cantidad != 0) {
                                $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                            } else {
                                $prb->costo_unitario = 0;
                            }
                            $prb->save();
                        }
                    }
                    break;
                case "bodega_transferencia_recibida":
                    $btr = DB::select("SELECT * FROM bodega_transferencia WHERE id_bodega_transferencia = $id;");
                    $btrd = DB::select("SELECT * FROM bodega_transferencia_detalle WHERE id_bodega_transferencia = $id;");
                    //ingreso encabezado
                    $gbi = 0;
                    for ($j = 0; $j < count($btrd); $j++) {
                        $gbi = $gbi + $btrd[$j]->cant_recib;
                    }
                    if ($gbi > 0) {
                        $ingreso = new BodegaIngreso();
                        $ingreso->fecha_ingreso = $btr[0]->f_finalizacion;
                        $ingreso->tipo_ingreso = "Ingreso Transferencia";
                        $ingreso->observ_ingreso = "Recepción de Transferencia: " . $btr[0]->num_trans;
                        $ingreso->id_bodega = $btr[0]->receptor_trans;
                        $ingreso->id_empresa = $btr[0]->id_empresa;
                        $ingreso->id_bodega_transferencia = $btr[0]->id_bodega_transferencia;
                        $ingreso->save();
                    }
                    for ($i = 0; $i < count($btrd); $i++) {
                        if ($btrd[$i]->cant_recib > 0) {
                            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $btrd[$i]->id_producto . " AND `id_bodega` =" . $btr[0]->receptor_trans);
                            if (count($sel) <= 0) {
                                $prb = new ProductoBodega();
                                $prb->cantidad = $btrd[$i]->cant_recib;
                                $prb->costo_unitario = $btrd[$i]->costo_unitario;
                                $prb->costo_total = $btrd[$i]->costo_total;
                                $prb->id_producto = $btrd[$i]->id_producto;
                                $prb->id_bodega = $btr[0]->receptor_trans;
                                $prb->id_empresa = $btr[0]->id_empresa;
                                $prb->save();

                                $boid = new BodegaIngresoDetalle();
                                $boid->cantidad = $btrd[$i]->cant_recib;
                                $boid->costo_unitario = $btrd[$i]->costo_unitario;
                                $boid->costo_total = $btrd[$i]->costo_unitario * $btrd[$i]->cant_recib;
                                $boid->id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                                $boid->id_producto = $btrd[$i]->id_producto;
                                if (isset($btrd[$i]->id_proyecto)) {
                                    $boid->id_proyecto = $btrd[$i]->id_proyecto;
                                } else if (isset($btr[0]->id_proyecto)) {
                                    $boid->id_proyecto = $btr[0]->id_proyecto;
                                }
                                $boid->id_bodega_transferencia_detalle = $btrd[$i]->id_bodega_transferencia_detalle;
                                $boid->save();
                            } else {
                                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                                $prb->cantidad = $prb->cantidad + $btrd[$i]->cant_recib;
                                $prb->costo_total = $prb->costo_total + ($btrd[$i]->costo_unitario * $btrd[$i]->cant_recib);
                                $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                                $prb->save();

                                $boid = new BodegaIngresoDetalle();
                                $boid->cantidad = $btrd[$i]->cant_recib;
                                $boid->costo_unitario = $btrd[$i]->costo_unitario;
                                $boid->costo_total = $btrd[$i]->costo_unitario * $btrd[$i]->cant_recib;
                                $boid->id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                                $boid->id_producto = $btrd[$i]->id_producto;
                                if (isset($btrd[$i]->id_proyecto)) {
                                    $boid->id_proyecto = $btrd[$i]->id_proyecto;
                                } else if (isset($btr[0]->id_proyecto)) {
                                    $boid->id_proyecto = $btr[0]->id_proyecto;
                                }
                                $boid->id_bodega_transferencia_detalle = $btrd[$i]->id_bodega_transferencia_detalle;
                                $boid->save();
                            }
                        }
                    }
                    break;
                case "factura":
                    $reg = DB::select("SELECT *, (SELECT count(*) FROM detalle fc INNER JOIN producto pr on pr.id_producto=fc.id_producto WHERE fc.id_factura=factura.id_factura AND pr.sector=1) as sector FROM factura WHERE (id_empresa=1 OR id_empresa=32 OR id_empresa=34 OR id_empresa=47 OR id_empresa=49 OR id_empresa=50 OR id_empresa=66) AND id_factura=$id");
                    for ($i = 0; $i < count($reg); $i++) {
                        if ($reg[$i]->sector >= 1) {
                            $id_factura = $reg[$i]->id_factura;
                            $clave = $reg[$i]->clave_acceso;
                            $clave_acceso = substr($clave, -19, -10);
                            $fecha = $reg[$i]->fcrea;
                            $empresa = $reg[$i]->id_empresa;

                            //egreso encabezado

                            // $egreso = new BodegaEgreso();
                            // $egreso->num_egreso = 999;
                            // $egreso->fecha_egreso = $fecha;
                            // $egreso->tipo_egreso = 'Egreso de Factura';
                            // $egreso->observ_egreso = 'Factura Venta: ' . $clave_acceso;
                            // $egreso->id_empresa = $empresa;
                            // if ($empresa == 1) {
                            //     $egreso->id_bodega = 21;
                            // }
                            // if ($empresa == 32) {
                            //     $egreso->id_bodega = 20;
                            // }
                            // if ($empresa == 34) {
                            //     $egreso->id_bodega = 22;
                            // }
                            // if ($empresa == 47) {
                            //     $egreso->id_bodega = 26;
                            // }
                            // if ($empresa == 49) {
                            //     $egreso->id_bodega = 29;
                            // }
                            // if ($empresa == 50) {
                            //     $egreso->id_bodega = 32;
                            // }
                            // if ($empresa == 66) {
                            //     $egreso->id_bodega = 34;
                            // }

                            // $egreso->id_factura = $id_factura;
                            // $egreso->save();

                            // 

                            $detalles = DB::select("SELECT det.*, pb.id_bodega, pb.costo_unitario FROM detalle det LEFT JOIN producto_bodega pb ON pb.id_producto_bodega = det.id_producto_bodega INNER JOIN producto p ON p.id_producto=det.id_producto WHERE det.id_factura = $id_factura AND p.sector=1");
                            $contar = 0;
                            for ($f = 0; $f < count($detalles); $f++) {
                                if ($detalles[$f]->id_bodega) {
                                    if ($contar == 0) {
                                        $num_egreso = DB::select("SELECT max(num_egreso) as num_egreso from bodega_egreso where id_empresa={$empresa}");
                                        //$numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $idempresa ORDER BY  num_egreso DESC LIMIT 1;");
                                        $numeroegreso = "";
                                        if (isset($num_egreso[0]->num_egreso)) {
                                            $dato = $num_egreso[0]->num_egreso;
                                            $tot = $dato + 1;
                                            $numeroegreso = $tot;
                                        } else {
                                            $numeroegreso = 1;
                                        }
                                        $egreso = new BodegaEgreso();
                                        $egreso->num_egreso = $numeroegreso;
                                        $egreso->fecha_egreso = $fecha;
                                        $egreso->tipo_egreso = 'Egreso de Factura';
                                        $egreso->observ_egreso = 'Factura Venta: ' . $clave_acceso;
                                        $egreso->id_empresa = $empresa;
                                        $egreso->id_bodega = $detalles[$f]->id_bodega;
                                        $egreso->id_factura = $detalles[$f]->id_factura;
                                        $egreso->save();
                                        $id_bodega_egreso = $egreso->id_bodega_egreso;
                                        $contar++;
                                    }
                                }
                                if ($detalles[$f]->id_bodega) {
                                    $bed = new BodegaEgresoDetalle();
                                    $bed->cantidad = $detalles[$f]->cantidad;
                                    $bed->costo_unitario = $detalles[$f]->costo_unitario;
                                    $bed->costo_total = $detalles[$f]->cantidad * $detalles[$f]->costo_unitario;
                                    $bed->id_bodega_egreso = $id_bodega_egreso;
                                    $bed->id_producto = $detalles[$f]->id_producto;
                                    if (isset($detalles[$f]->id_proyecto)) {
                                        $bed->id_proyecto = $detalles[$f]->id_proyecto;
                                    }
                                    $bed->id_detalle = $detalles[$f]->id_detalle;
                                    $bed->save();
                                    if (isset($detalles[$f]->id_producto_bodega)) {
                                        $idpb = $detalles[$f]->id_producto_bodega;
                                        $cantidad = $detalles[$f]->cantidad;
                                        $facturaspb = "UPDATE producto_bodega SET cantidad = cantidad - $cantidad, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $idpb";
                                        DB::update($facturaspb);
                                    }
                                }
                            }
                        }
                    }
                    break;
                case "factura_compra":
                    $reg = DB::select("SELECT *, (SELECT count(*) FROM detalle_factura_compra dfc INNER JOIN producto pr on pr.id_producto=dfc.id_producto WHERE dfc.id_factura=factura_compra.id_factcompra AND pr.sector=1) as sector FROM factura_compra WHERE (id_empresa=1 OR id_empresa=32 OR id_empresa=34 OR id_empresa=47 OR id_empresa=49 OR id_empresa=50 OR id_empresa=66) AND id_factcompra = $id");
                    for ($i = 0; $i < count($reg); $i++) {
                        if ($reg[$i]->sector >= 1) {
                            $id_empresa = $reg[$i]->id_empresa;
                            $id_factura = $reg[$i]->id_factcompra;
                            if ($reg[$i]->descripcion) {
                                $clave = $reg[$i]->descripcion;
                            } else {
                                $clave = $reg[$i]->id_factcompra;
                            }
                            $clave_acceso = $clave;
                            $fecha = $reg[$i]->fcrea;
                            $empresa = $reg[$i]->id_empresa;
                            //egreso encabezado

                            // $ingreso = new BodegaIngreso();
                            // $ingreso->num_ingreso = 1;
                            // $ingreso->fecha_ingreso = $fecha;
                            // $ingreso->tipo_ingreso = 'Ingreso de Factura';
                            // $ingreso->observ_ingreso = 'Factura Compra: ' . $clave_acceso;
                            // $ingreso->id_empresa = $empresa;
                            // if ($empresa == 1) {
                            //     $ingreso->id_bodega = 21;
                            // }
                            // if ($empresa == 32) {
                            //     $ingreso->id_bodega = 20;
                            // }
                            // if ($empresa == 34) {
                            //     $ingreso->id_bodega = 22;
                            // }
                            // if ($empresa == 47) {
                            //     $ingreso->id_bodega = 26;
                            // }
                            // if ($empresa == 48) {
                            //     $ingreso->id_bodega = 21;
                            // }
                            // if ($empresa == 49) {
                            //     $ingreso->id_bodega = 29;
                            // }
                            // if ($empresa == 50) {
                            //     $ingreso->id_bodega = 32;
                            // }
                            // if ($empresa == 66) {
                            //     $ingreso->id_bodega = 34;
                            // }
                            // $ingreso->id_factura_compra = $id_factura;
                            // $ingreso->save();

                            // 

                            $detalles = DB::select("SELECT det.*, pb.id_bodega, pb.costo_total AS costo_total_bodega, pb.cantidad AS cantidad_bodega FROM detalle_factura_compra det LEFT JOIN producto_bodega pb ON pb.id_producto_bodega = det.id_producto_bodega INNER JOIN producto p ON p.id_producto=det.id_producto WHERE det.id_factura = $id_factura AND p.sector=1");
                            $contar = 0;
                            for ($f = 0; $f < count($detalles); $f++) {
                                if ($detalles[$f]->id_producto_bodega == null) {
                                    $respb = DB::select("SELECT * FROM producto_bodega WHERE id_producto = " . $detalles[$f]->id_producto);
                                    if (count($respb) >= 1) {
                                        $pb_recupera = $respb[0]->id_producto_bodega;
                                        DB::update("UPDATE detalle_factura_compra SET id_producto_bodega = $pb_recupera WHERE id_detalle_factura_compra = " . $detalles[$f]->id_detalle_factura_compra);

                                        $dpb = $pb_recupera;
                                        $dcosto_total_bodega = $detalles[$f]->costo_total_bodega; //306
                                        $dcantidad_bodega = $detalles[$f]->cantidad_bodega; //360
                                        $dcantidad = $detalles[$f]->cantidad; //3500
                                        $dcosto_total_ingreso = $detalles[$f]->cantidad * $detalles[$f]->precio; //3500 * 0.5
                                        //(306 + 1750)/(360 + 3500)
                                        $resultado_costo_unitario = ($dcosto_total_bodega + $dcosto_total_ingreso) / ($dcantidad_bodega + $dcantidad);

                                        $query = "UPDATE producto_bodega SET cantidad = cantidad + $dcantidad, costo_unitario = $resultado_costo_unitario , costo_total = cantidad * $resultado_costo_unitario WHERE id_producto_bodega = $dpb";
                                        DB::update($query);
                                    } else {
                                        $resultado_costo_unitario = $detalles[$f]->cantidad;

                                        $prdb = new ProductoBodega();
                                        $prdb->cantidad = $detalles[$f]->cantidad;
                                        $prdb->costo_unitario = $detalles[$f]->precio;
                                        $prdb->costo_total = $detalles[$f]->cantidad * $detalles[$f]->precio;
                                        $prdb->id_producto = $detalles[$f]->id_producto;
                                        if ($empresa == 1) {
                                            $prdb->id_bodega = 21;
                                        }
                                        if ($empresa == 32) {
                                            $prdb->id_bodega = 20;
                                        }
                                        if ($empresa == 34) {
                                            $prdb->id_bodega = 22;
                                        }
                                        if ($empresa == 47) {
                                            $prdb->id_bodega = 26;
                                        }
                                        if ($empresa == 48) {
                                            $prdb->id_bodega = 21;
                                        }
                                        if ($empresa == 49) {
                                            $prdb->id_bodega = 29;
                                        }
                                        if ($empresa == 50) {
                                            $prdb->id_bodega = 32;
                                        }

                                        $prdb->id_empresa = $id_empresa;
                                        $prdb->save();

                                        $pb_recupera = $prdb->id_producto_bodega;
                                        DB::update("UPDATE detalle_factura_compra SET id_producto_bodega = $pb_recupera WHERE id_detalle_factura_compra = " . $detalles[$f]->id_detalle_factura_compra);
                                    }
                                } else {
                                    $dpb = $detalles[$f]->id_producto_bodega;
                                    $dcosto_total_bodega = $detalles[$f]->costo_total_bodega; //188.5682
                                    $dcantidad_bodega = $detalles[$f]->cantidad_bodega; //310

                                    $dcantidad = $detalles[$f]->cantidad; //100
                                    $dcosto_total_ingreso = $detalles[$f]->cantidad * $detalles[$f]->precio; //100*0.5 = 50

                                    // (188.5682 + 50)/(310 + 100)

                                    if (($dcantidad_bodega + $dcantidad) != 0) {
                                        $resultado_costo_unitario = ($dcosto_total_bodega + $dcosto_total_ingreso) / ($dcantidad_bodega + $dcantidad);
                                    } else {
                                        $resultado_costo_unitario = 0;
                                    }

                                    $query = "UPDATE producto_bodega SET cantidad = cantidad + $dcantidad, costo_unitario = $resultado_costo_unitario , costo_total = cantidad * $resultado_costo_unitario WHERE id_producto_bodega = $dpb";
                                    DB::update($query);
                                }

                                $databb = DB::select("SELECT * FROM detalle_factura_compra dfc INNER JOIN producto_bodega pb ON dfc.id_producto_bodega = pb.id_producto_bodega WHERE dfc.id_detalle_factura_compra = " . $detalles[$f]->id_detalle_factura_compra);
                                if (count($databb) >= 1) {
                                    if ($contar == 0) {
                                        $num_ingreso = DB::select("SELECT max(num_ingreso) as num_ingreso from bodega_ingreso where id_empresa={$empresa}");
                                        //$numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1;");
                                        $numeroingreso = "";
                                        if (isset($num_ingreso[0]->num_ingreso)) {
                                            $dato = $num_ingreso[0]->num_ingreso;
                                            $tot = $dato + 1;
                                            $numeroingreso = $tot;
                                        } else {
                                            $numeroingreso = 1;
                                        }
                                        $ingreso = new BodegaIngreso();
                                        $ingreso->num_ingreso = $numeroingreso;
                                        $ingreso->fecha_ingreso = $fecha;
                                        $ingreso->tipo_ingreso = 'Ingreso de Factura';
                                        $ingreso->observ_ingreso = 'Factura Compra: ' . $clave_acceso;
                                        $ingreso->id_empresa = $empresa;
                                        $ingreso->id_bodega = $databb[0]->id_bodega;
                                        $ingreso->id_factura_compra = $id_factura;
                                        $ingreso->save();
                                        $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                                        $contar++;
                                    }
                                }

                                $bed = new BodegaIngresoDetalle();
                                $bed->cantidad = $detalles[$f]->cantidad;
                                $bed->costo_unitario = $detalles[$f]->precio;
                                $bed->costo_total = $detalles[$f]->total;
                                $bed->id_bodega_ingreso = $id_bodega_ingreso;
                                $bed->id_producto = $detalles[$f]->id_producto;
                                if (isset($detalles[$f]->id_proyecto)) {
                                    $bed->id_proyecto = $detalles[$f]->id_proyecto;
                                }
                                $bed->id_detalle_factura_compra = $detalles[$f]->id_detalle_factura_compra;
                                $bed->save();
                            }
                        }
                    }
                    break;
                case "nota_credito":
                    $reg = DB::select("SELECT *, (SELECT count(*) FROM detalle_nota_credito dnc INNER JOIN producto pr on pr.id_producto=dnc.id_producto WHERE dnc.id_nota_credito=nota_credito.id_nota_credito AND pr.sector=1) as sector FROM nota_credito WHERE (id_empresa=1 OR id_empresa=32 OR id_empresa=34 OR id_empresa=47 OR id_empresa=49 OR id_empresa=50 OR id_empresa=66) AND id_nota_credito=$id");
                    for ($i = 0; $i < count($reg); $i++) {
                        if ($reg[$i]->sector >= 1) {
                            $id_nota_credito = $reg[$i]->id_nota_credito;
                            $clave = $reg[$i]->clave_acceso;
                            $clave_acceso = substr($clave, -19, -10);
                            $fecha = $reg[$i]->fcrea;
                            $empresa = $reg[$i]->id_empresa;

                            //ingreso encabezado

                            // $ingreso = new BodegaIngreso();
                            // $ingreso->num_ingreso = 1;
                            // $ingreso->fecha_ingreso = $fecha;
                            // $ingreso->tipo_ingreso = 'Ingreso Nota Credito';
                            // $ingreso->observ_ingreso = 'Nota Credito: ' . $clave_acceso;
                            // $ingreso->id_empresa = $empresa;
                            // if ($empresa == 1) {
                            //     $ingreso->id_bodega = 21;
                            // }
                            // if ($empresa == 32) {
                            //     $ingreso->id_bodega = 20;
                            // }
                            // if ($empresa == 34) {
                            //     $ingreso->id_bodega = 22;
                            // }
                            // if ($empresa == 47) {
                            //     $ingreso->id_bodega = 26;
                            // }
                            // if ($empresa == 48) {
                            //     $ingreso->id_bodega = 21;
                            // }
                            // if ($empresa == 49) {
                            //     $ingreso->id_bodega = 29;
                            // }
                            // if ($empresa == 50) {
                            //     $ingreso->id_bodega = 32;
                            // }
                            // if ($empresa == 66) {
                            //     $ingreso->id_bodega = 34;
                            // }
                            // $ingreso->id_nota_credito = $id_nota_credito;
                            // $ingreso->save();

                            // 

                            $detalles = DB::select("SELECT nc.*, pb.id_bodega FROM detalle_nota_credito nc LEFT JOIN producto_bodega pb ON pb.id_producto_bodega = nc.id_producto_bodega INNER JOIN producto p ON p.id_producto=nc.id_producto WHERE nc.id_nota_credito = $id_nota_credito AND p.sector=1");
                            $contar = 0;
                            for ($f = 0; $f < count($detalles); $f++) {
                                if ($detalles[$f]->id_producto_bodega == null) {
                                    $respb = DB::select("SELECT * FROM producto_bodega WHERE id_producto = " . $detalles[$f]->id_producto);
                                    if (count($respb) >= 1) {
                                        $pb_recupera = $respb[0]->id_producto_bodega;
                                        DB::update("UPDATE detalle_nota_credito SET id_producto_bodega = $pb_recupera WHERE id_detalle_nota_credito = " . $detalles[$f]->id_detalle_nota_credito);

                                        $dpb = $pb_recupera;
                                        $dcantidad = $detalles[$f]->cantidad;
                                        DB::update("UPDATE producto_bodega SET cantidad = cantidad + $dcantidad, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $dpb");
                                    }
                                } else {
                                    $dpb = $detalles[$f]->id_producto_bodega;
                                    $dcantidad = $detalles[$f]->cantidad;
                                    DB::update("UPDATE producto_bodega SET cantidad = cantidad + $dcantidad, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $dpb");
                                }
                                $databb = DB::select("SELECT * FROM detalle_nota_credito dnc INNER JOIN producto_bodega pb ON dnc.id_producto_bodega = pb.id_producto_bodega WHERE dnc.id_detalle_nota_credito = " . $detalles[$f]->id_detalle_nota_credito);
                                if (count($databb) >= 1) {
                                    if ($contar == 0) {
                                        $num_ingreso = DB::select("SELECT max(num_ingreso) as num_ingreso from bodega_ingreso where id_empresa={$empresa}");
                                        //$numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1;");
                                        $numeroingreso = "";
                                        if (isset($num_ingreso[0]->num_ingreso)) {
                                            $dato = $num_ingreso[0]->num_ingreso;
                                            $tot = $dato + 1;
                                            $numeroingreso = $tot;
                                        } else {
                                            $numeroingreso = 1;
                                        }
                                        $ingreso = new BodegaIngreso();
                                        $ingreso->num_ingreso = $numeroingreso;
                                        $ingreso->fecha_ingreso = $fecha;
                                        $ingreso->tipo_ingreso = 'Ingreso Nota Credito';
                                        $ingreso->observ_ingreso = 'Nota Credito: ' . $clave_acceso;
                                        $ingreso->id_empresa = $empresa;
                                        $ingreso->id_bodega = $databb[0]->id_bodega;
                                        $ingreso->id_nota_credito = $id_nota_credito;
                                        $ingreso->save();
                                        $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                                        $contar++;
                                    }
                                }
                                $bed = new BodegaIngresoDetalle();
                                $bed->cantidad = $detalles[$f]->cantidad;
                                $bed->costo_unitario = $detalles[$f]->precio;
                                $bed->costo_total = $detalles[$f]->total;
                                $bed->id_bodega_ingreso = $id_bodega_ingreso;
                                $bed->id_producto = $detalles[$f]->id_producto;
                                if (isset($detalles[$f]->id_proyecto)) {
                                    $bed->id_proyecto = $detalles[$f]->id_proyecto;
                                }
                                $bed->id_detalle_nota_credito = $detalles[$f]->id_detalle_nota_credito;
                                $bed->save();
                            }
                        }
                    }
                    break;
                case "nota_credito_compra":
                    $reg = DB::select("SELECT *, (SELECT count(*) FROM detalle_nota_credito_compra dnc INNER JOIN producto pr on pr.id_producto=dnc.id_producto WHERE dnc.id_nota_credito_compra=nota_credito_compra.id_nota_credito_compra AND pr.sector=1) as sector FROM nota_credito_compra WHERE (id_empresa=1 OR id_empresa=32 OR id_empresa=34 OR id_empresa=47 OR id_empresa=49 OR id_empresa=50 OR id_empresa=66) AND id_nota_credito_compra=$id");
                    for ($i = 0; $i < count($reg); $i++) {
                        if ($reg[$i]->sector >= 1) {
                            $id_nota_credito_compra = $reg[$i]->id_nota_credito_compra;
                            $clave = $reg[$i]->clave_acceso;
                            $clave_acceso = substr($clave, -19, -10);
                            $fecha = $reg[$i]->fcrea;
                            $empresa = $reg[$i]->id_empresa;

                            //ingreso encabezado
                            $num_egreso = DB::select("SELECT max(num_egreso) as num_egreso from bodega_egreso where id_empresa={$empresa}");
                            //$numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $idempresa ORDER BY  num_egreso DESC LIMIT 1;");
                            $numeroegreso = "";
                            if (isset($num_egreso[0]->num_egreso)) {
                                $dato = $num_egreso[0]->num_egreso;
                                $tot = $dato + 1;
                                $numeroegreso = $tot;
                            } else {
                                $numeroegreso = 1;
                            }
                            $egreso = new BodegaEgreso();
                            $egreso->num_egreso = $numeroegreso;
                            $egreso->fecha_egreso = $fecha;
                            $egreso->tipo_egreso = 'Egreso Nota Credito Compra';
                            $egreso->observ_egreso = 'Nota Credito Compra: ' . $clave_acceso;
                            $egreso->id_empresa = $empresa;
                            if ($empresa == 1) {
                                $egreso->id_bodega = 21;
                            }
                            if ($empresa == 32) {
                                $egreso->id_bodega = 20;
                            }
                            if ($empresa == 34) {
                                $egreso->id_bodega = 22;
                            }
                            if ($empresa == 47) {
                                $egreso->id_bodega = 26;
                            }
                            if ($empresa == 48) {
                                $egreso->id_bodega = 21;
                            }
                            if ($empresa == 49) {
                                $egreso->id_bodega = 29;
                            }
                            if ($empresa == 50) {
                                $egreso->id_bodega = 32;
                            }
                            if ($empresa == 66) {
                                $egreso->id_bodega = 34;
                            }
                            $egreso->id_nota_credito_compra = $id_nota_credito_compra;
                            $egreso->save();

                            $id_bodega_egreso = $egreso->id_bodega_egreso;

                            $detalles = DB::select("SELECT nc.*, pb.id_bodega FROM detalle_nota_credito_compra nc LEFT JOIN producto_bodega pb ON pb.id_producto_bodega = nc.id_producto_bodega INNER JOIN producto p ON p.id_producto=nc.id_producto WHERE nc.id_nota_credito_compra = $id_nota_credito_compra AND p.sector=1");
                            $contar = 0;
                            for ($f = 0; $f < count($detalles); $f++) {
                                if ($detalles[$f]->id_producto_bodega == null) {
                                    $respb = DB::select("SELECT * FROM producto_bodega WHERE id_producto = " . $detalles[$f]->id_producto);
                                    if (count($respb) >= 1) {
                                        $pb_recupera = $respb[0]->id_producto_bodega;
                                        DB::update("UPDATE detalle_nota_credito_compra SET id_producto_bodega = $pb_recupera WHERE id_detalle_nota_credito_compra = " . $detalles[$f]->id_detalle_nota_credito_compra);

                                        $dpb = $pb_recupera;
                                        $dcantidad = $detalles[$f]->cantidad;
                                        DB::update("UPDATE producto_bodega SET cantidad = cantidad - $dcantidad, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $dpb");
                                    }
                                } else {
                                    $dpb = $detalles[$f]->id_producto_bodega;
                                    $dcantidad = $detalles[$f]->cantidad;
                                    DB::update("UPDATE producto_bodega SET cantidad = cantidad - $dcantidad, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $dpb");
                                }
                                $databb = DB::select("SELECT * FROM detalle_nota_credito_compra nc INNER JOIN producto_bodega pb ON nc.id_producto_bodega = pb.id_producto_bodega WHERE nc.id_detalle_nota_credito_compra = " . $detalles[$f]->id_detalle_nota_credito_compra);
                                if (count($databb) >= 1) {
                                    if ($contar == 0) {
                                        $egreso = BodegaEgreso::find($id_bodega_egreso);
                                        $egreso->id_bodega = $databb[0]->id_bodega;
                                        $egreso->save();
                                        $contar++;
                                    }
                                }
                                $bed = new BodegaEgresoDetalle();
                                $bed->cantidad = $detalles[$f]->cantidad;
                                $bed->costo_unitario = $detalles[$f]->precio;
                                $bed->costo_total = $detalles[$f]->total;
                                $bed->id_bodega_egreso = $id_bodega_egreso;
                                $bed->id_producto = $detalles[$f]->id_producto;
                                if (isset($detalles[$f]->id_proyecto)) {
                                    $bed->id_proyecto = $detalles[$f]->id_proyecto;
                                }
                                $bed->id_detalle_nota_credito_compra = $detalles[$f]->id_detalle_nota_credito_compra;
                                $bed->save();
                            }
                        }
                    }
                    break;
            }
        }
        return $data;
    }
    public function ejemplos_producto($id)
    {
        ini_set('max_execution_time', 53200);
        DB::update("UPDATE producto_bodega bdt INNER JOIN bodega bd ON bd.id_bodega=bdt.id_bodega SET bdt.cantidad=0, bdt.costo_unitario=0, bdt.costo_total=0 where bd.id_empresa={$id}");
        $res = DB::select(" SELECT id_bodega_ingreso as id, 'bodega_ingreso' as tabla, bdt.fcrea                                     FROM bodega_ingreso bdt INNER JOIN bodega bd ON bd.id_bodega=bdt.id_bodega where bd.id_empresa={$id} UNION
                            SELECT id_bodega_egreso as id, 'bodega_egreso' as tabla, bdt.fcrea                                       FROM bodega_egreso bdt INNER JOIN bodega bd ON bd.id_bodega=bdt.id_bodega where bd.id_empresa={$id}      
                            ORDER BY fcrea ASC
                         ");
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        $data = "Registros2 Generados2 Exitosamente " . $empresa[0]->nombre_empresa;
        for ($z = 0; $z < count($res); $z++) {
            $id = $res[$z]->id;
            switch ($res[$z]->tabla) {
                case "bodega_ingreso":
                    $bi = DB::select("SELECT * FROM bodega_ingreso WHERE id_bodega_ingreso =$id;");
                    $bid = DB::select("SELECT * FROM bodega_ingreso_detalle WHERE id_bodega_ingreso =$id;");
                    for ($i = 0; $i < count($bid); $i++) {
                        $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $bid[$i]->id_producto . " AND `id_bodega` =" . $bi[0]->id_bodega);
                        if (count($sel) <= 0) {
                            $prb = new ProductoBodega();
                            $prb->cantidad = $bid[$i]->cantidad;
                            $prb->costo_unitario = $bid[$i]->costo_unitario;
                            $prb->costo_total = $bid[$i]->costo_total;
                            $prb->id_producto = $bid[$i]->id_producto;
                            $prb->id_bodega = $bi[0]->id_bodega;
                            $prb->id_empresa = $bi[0]->id_empresa;
                            $prb->save();
                        } else {
                            $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                            $prb->cantidad = $prb->cantidad + $bid[$i]->cantidad;
                            $prb->costo_total = $prb->costo_total + $bid[$i]->costo_total;
                            if ($prb->cantidad != 0) {
                                $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                            } else {
                                $prb->costo_unitario = 0;
                            }
                            $prb->save();
                        }
                    }
                    break;
                case "bodega_egreso":
                    $be = DB::select("SELECT * FROM bodega_egreso WHERE id_bodega_egreso =$id;");
                    $bed = DB::select("SELECT * FROM bodega_egreso_detalle WHERE id_bodega_egreso =$id;");
                    for ($i = 0; $i < count($bed); $i++) {
                        $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $bed[$i]->id_producto . " AND `id_bodega` =" . $be[0]->id_bodega);
                        if (count($sel) == 1) {
                            $pbed = BodegaEgresoDetalle::findOrFail($bed[$i]->id_bodega_egreso_detalle);
                            $pbed->costo_unitario = $sel[0]->costo_unitario;
                            $pbed->costo_total = $bed[$i]->cantidad * $sel[0]->costo_unitario;
                            $pbed->save();

                            $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                            $prb->cantidad = $prb->cantidad - $pbed->cantidad;
                            $prb->costo_total = $prb->costo_total - $pbed->costo_total;
                            if ($prb->cantidad != 0) {
                                $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                            } else {
                                $prb->costo_unitario = 0;
                            }
                            $prb->save();
                        }
                    }
                    break;
            }
        }
        return $data;
    }

    public function cambio_user_cobro($id)
    {
        ini_set('max_execution_time', 53200);
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        $cxc = DB::select("SELECT ctas_cobrar_pagos.* 
                            from ctas_cobrar_pagos,cliente
                            where cliente.id_cliente=ctas_cobrar_pagos.id_cliente and cliente.id_empresa={$id}");
        $data2 = "Registro Agregado User Ctas Cobrar Pagos de " . $empresa[0]->nombre_empresa;
        if (count($cxc) > 0) {
            for ($i = 0; $i < count($cxc); $i++) {
                $data = explode(";", $cxc[$i]->referencia);
                $registros = count($data) / 4;
                $salto = 0;
                for ($f = 0; $f < $registros; $f++) {
                    $id_cb = $data[1 + $salto];
                    $valor = $data[2 + $salto];
                    $idf = $data[3 + $salto];
                    if ($idf) {
                        $pos = strpos($idf, 'nv:');
                        if ($pos !== false) {
                            $number = substr($idf, 3);
                            $nv = DB::select("SELECT * FROM nota_venta where id_nota_venta={$number}");
                            DB::update("UPDATE ctas_cobrar_pagos SET ucrea={$nv[0]->id_user} WHERE id_ctas_cobrar_pagos = {$cxc[$i]->id_ctas_cobrar_pagos}");
                        } else {
                            $nv = DB::select("SELECT * FROM factura where id_factura={$idf}");
                            DB::update("UPDATE ctas_cobrar_pagos SET ucrea={$nv[0]->id_user} WHERE id_ctas_cobrar_pagos = {$cxc[$i]->id_ctas_cobrar_pagos}");
                        }
                    }
                    //hace el salto de los 4
                    $salto = $salto + 4;
                }
            }
        }
        return $data2;
    }
    public function cambio_posicion_ctasxcobrar($id)
    {
        ini_set('max_execution_time', 53200);
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        // $valor = DB::select("SELECT cop.*, cl.nombre AS nombrecliente, fp.descripcion AS descripcionsri, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral FROM ctas_cobrar_pagos cop INNER JOIN cliente cl ON cl.id_cliente = cop.id_cliente LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri INNER JOIN empresa em ON em.id_empresa = cl.id_empresa WHERE em.id_empresa ={$id}  
        // ORDER BY if(cop.fecha_registro is not null and cop.pagos_por<>'Anticipo',cop.fecha_registro,cop.fecha_pago) asc");
        //recorre las referencias de los registros de pagos y los conviertes
        // $abonos = DB::select("SELECT *,contabilidad FROM ctas_cobrar cb INNER JOIN cliente c ON cb.id_cliente = c.id_cliente INNER JOIN forma_pagos fp ON cb.id_forma_pagos = fp.id_forma_pagos WHERE c.id_empresa = $id AND cb.tipo = 3 ORDER BY cb.id_ctascobrar DESC");
        // for($i=0; $i<count($valor); $i++){
        //     $valor[$i]->referencia = explode(";", $valor[$i]->referencia);
        // }
        // //unifica los registros de pago y los registros de los anticipos
        // $res = array_merge($valor, $abonos);
        //var_dump($valor);
        $data2 = "Actualizado Posicion Ctas Cobrar Pagos de " . $empresa[0]->nombre_empresa;
        $valor = DB::select("SELECT cop.*, cl.nombre AS nombrecliente, fp.descripcion AS descripcionsri, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral FROM ctas_cobrar_pagos cop INNER JOIN cliente cl ON cl.id_cliente = cop.id_cliente LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri INNER JOIN empresa em ON em.id_empresa = cl.id_empresa WHERE em.id_empresa = $id and pagos_por<>'Anticipo'  ORDER BY cop.fecha_pago DESC, cop.id_ctas_cobrar_pagos DESC");
        //recorre las referencias de los registros de pagos y los conviertes
        $abonos = DB::select("SELECT *,contabilidad,IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral FROM ctas_cobrar cb INNER JOIN cliente c ON cb.id_cliente = c.id_cliente INNER JOIN forma_pagos fp ON cb.id_forma_pagos = fp.id_forma_pagos WHERE c.id_empresa = $id AND cb.tipo = 3 ORDER BY cb.id_ctascobrar DESC");
        $res = array_merge($valor, $abonos);
        usort($res, function ($a, $b) {
            return strcmp($b->fechageneral, $a->fechageneral);
        });
        //dd(array_reverse($res));
        $ps = 0;
        foreach (array_reverse($res) as $detail) {
            $ps_cta = DB::select("SELECT max(posicion) as posicion from ctas_cobrar_pagos
                                    INNER JOIN cliente
                                    on cliente.id_cliente=ctas_cobrar_pagos.id_cliente
                                    where cliente.id_empresa={$id} 
                               ");


            $ps++;

            if (isset($detail->id_ctas_cobrar_pagos)) {
                DB::update("UPDATE ctas_cobrar_pagos SET posicion={$ps} WHERE id_ctas_cobrar_pagos = {$detail->id_ctas_cobrar_pagos}");
            } else {
                DB::update("UPDATE ctas_cobrar SET posicion={$ps} WHERE id_ctascobrar = {$detail->id_ctascobrar}");
            }
            // else {
            //     $sel_anticipo = DB::select("SELECT * from ctas_cobrar_pagos where anticipo={$detail->id_ctascobrar}");
            //     if (count($sel_anticipo) > 0) {
            //         DB::update("UPDATE ctas_cobrar_pagos SET posicion={$ps} WHERE id_ctas_cobrar_pagos = {$sel_anticipo[0]->id_ctas_cobrar_pagos}");
            //         $ps_cta_2 = DB::select("SELECT max(posicion) as posicion from ctas_cobrar_pagos
            //                                 INNER JOIN cliente
            //                                 on cliente.id_cliente=ctas_cobrar_pagos.id_cliente
            //                                 where cliente.id_empresa={$id} 
            //                         ");
            //         $ps_2 = 1;
            //         if (count($ps_cta_2) > 0) {
            //             $ps_2 = $ps_cta_2[0]->posicion;
            //         }
            //         DB::update("UPDATE ctas_cobrar SET posicion={$ps_2} WHERE id_ctascobrar = {$detail->id_ctascobrar}");
            //     }
            // }
        }
        // foreach ($valor as $detail) {
        //     $max=DB::select("SELECT max(posicion) as posicion FROM ctas_cobrar_pagos as cop INNER JOIN cliente cl ON cl.id_cliente = cop.id_cliente WHERE cl.id_empresa={$id}");
        //     $conteo=1;
        //     if(count($max)>0){
        //         $conteo=$max[0]->posicion+1;
        //     }
        //     DB::update("UPDATE ctas_cobrar_pagos set posicion={$conteo} where id_ctas_cobrar_pagos={$detail->id_ctas_cobrar_pagos}");
        // }


        // for($i=0; $i<count($res); $i++){
        //     $ps_cta=DB::select("SELECT max(posicion) as posicion from ctas_cobrar_pagos
        //                         INNER JOIN cliente
        //                         on cliente.id_cliente=ctas_cobrar_pagos.id_cliente
        //                         where cliente.id_empresa={$id}");
        //     $ps=1;
        //     if(count($ps_cta)>0){
        //         $ps=$ps_cta[0]->posicion+1;
        //     }
        //     if(isset($res[$i]->id_ctas_cobrar_pagos)){
        //         DB::update("UPDATE ctas_cobrar_pagos SET posicion={$ps} WHERE id_ctas_cobrar_pagos = {$res[$i]->id_ctas_cobrar_pagos}");
        //     }else{
        //         DB::update("UPDATE ctas_cobrar SET posicion={$ps} WHERE id_ctascobrar = {$res[$i]->id_ctascobrar}");
        //     }
        // }
        // for($i=0; $i<count($valor); $i++){
        //     $ps_cta=DB::select("SELECT max(posicion) as posicion from ctas_cobrar_pagos
        //                     INNER JOIN cliente
        //                     on cliente.id_cliente=ctas_cobrar_pagos.id_cliente
        //                     where cliente.id_empresa={$id}");
        //     $ps=1;
        //     if(count($ps_cta)>0){
        //         $ps=$ps_cta[0]->posicion+1;
        //     }
        //     $valor[$i]->id_ctas_cobrar_pagos;
        //     DB::update("UPDATE ctas_cobrar_pagos SET posicion={$ps} WHERE id_ctas_cobrar_pagos = {$valor[$i]->id_ctas_cobrar_pagos}");
        // }
        return $data2;
    }
    public function cambio_posicion_ctasxpagar($id)
    {
        ini_set('max_execution_time', 53200);
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        // $valor = DB::select("SELECT cop.*, cl.nombre AS nombrecliente, fp.descripcion AS descripcionsri, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral FROM ctas_cobrar_pagos cop INNER JOIN cliente cl ON cl.id_cliente = cop.id_cliente LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri INNER JOIN empresa em ON em.id_empresa = cl.id_empresa WHERE em.id_empresa ={$id}  
        // ORDER BY if(cop.fecha_registro is not null and cop.pagos_por<>'Anticipo',cop.fecha_registro,cop.fecha_pago) asc");
        //recorre las referencias de los registros de pagos y los conviertes
        // $abonos = DB::select("SELECT *,contabilidad FROM ctas_cobrar cb INNER JOIN cliente c ON cb.id_cliente = c.id_cliente INNER JOIN forma_pagos fp ON cb.id_forma_pagos = fp.id_forma_pagos WHERE c.id_empresa = $id AND cb.tipo = 3 ORDER BY cb.id_ctascobrar DESC");
        // for($i=0; $i<count($valor); $i++){
        //     $valor[$i]->referencia = explode(";", $valor[$i]->referencia);
        // }
        // //unifica los registros de pago y los registros de los anticipos
        // $res = array_merge($valor, $abonos);
        //var_dump($valor);
        $data2 = "Actualizado Posicion Ctas Pagar Pagos de " . $empresa[0]->nombre_empresa;
        $valor = DB::select("SELECT cop.*,  fp.descripcion AS descripcionsri, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral FROM ctas_pagar_pagos cop INNER JOIN proveedor cl ON cl.id_proveedor = cop.id_proveedor LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri INNER JOIN empresa em ON em.id_empresa = cl.id_empresa WHERE em.id_empresa = $id and pagos_por<>'Anticipo'  ORDER BY cop.fecha_pago DESC, cop.id_ctas_pagar_pagos DESC");
        //recorre las referencias de los registros de pagos y los conviertes
        $abonos = DB::select("SELECT *,contabilidad,IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral FROM ctas_pagar cb INNER JOIN proveedor c ON cb.id_proveedor = c.id_proveedor INNER JOIN forma_pagos fp ON cb.id_forma_pagos = fp.id_forma_pagos WHERE c.id_empresa = $id AND cb.tipo = 3 ORDER BY cb.id_ctaspagar DESC");
        $res = array_merge($valor, $abonos);
        usort($res, function ($a, $b) {
            return strcmp($b->fechageneral, $a->fechageneral);
        });
        //dd(array_reverse($res));
        $ps = 0;
        foreach (array_reverse($res) as $detail) {
            $ps_cta = DB::select("SELECT max(posicion) as posicion from ctas_cobrar_pagos
                                    INNER JOIN cliente
                                    on cliente.id_cliente=ctas_cobrar_pagos.id_cliente
                                    where cliente.id_empresa={$id} 
                               ");


            $ps++;

            if (isset($detail->id_ctas_pagar_pagos)) {
                DB::update("UPDATE ctas_pagar_pagos SET posicion={$ps} WHERE id_ctas_pagar_pagos = {$detail->id_ctas_pagar_pagos}");
            } else {
                DB::update("UPDATE ctas_pagar SET posicion={$ps} WHERE id_ctaspagar = {$detail->id_ctaspagar}");
            }
        }

        return $data2;
    }
    public function cambio_cod_prov($id)
    {
        ini_set('max_execution_time', 53200);
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        $data2 = "Registro Actualizado Cod Proveedor de " . $empresa[0]->nombre_empresa;
        $prov = DB::select("SELECT * from proveedor where id_empresa={$id}");
        foreach ($prov as $detail) {
            $var_1 = preg_replace('/[^0-9]/', '', $detail->cod_proveedor);
            $var_2 = intval($var_1);
            DB::update("UPDATE proveedor set cod_proveedor='{$var_2}' where id_proveedor={$detail->id_proveedor}");
        }
        return $data2;
    }
    public function devolucion_nota_credito_compra($id)
    {
        ini_set('max_execution_time', 53200);
        $hoy = Carbon::now();
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        $data2 = "Registro Actualizado Devolucion Nota Credito Compra en Pago Proveedores de la empresa " . $empresa[0]->nombre_empresa;
        $notc1 = DB::select("SELECT * FROM nota_credito_compra where id_empresa={$id}");
        $fp = DB::select("SELECT id_forma_pagos from forma_pagos where id_empresa={$id}");
        for ($z = 0; $z < count($notc1); $z++) {

            $pos0 = DB::select("SELECT max(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$id}");
            if (count($pos0) > 0) {
                $pos = DB::select("SELECT count(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$id}");
            } else {
                $pos = [];
            }
            $conteo = 1;
            if (count($pos) > 0) {
                $conteo = $pos[0]->posicion + 1;
            }
            $id_factura = $notc1[$z]->id_factura_compra;
            $id_nota_credito = $notc1[$z]->id_nota_credito_compra;
            $cta = DB::select("SELECT * FROM ctas_pagar where id_factura_compra=$id_factura and tipo=1");
            if (count($cta) > 0) {

                $valor = $notc1[$z]->valor_total / count($cta);
                DB::update("UPDATE ctas_pagar set valor_cuota=(select total from factura_compra_pagos where id_factura_compra=ctas_pagar.id_factura_compra and estado=2 limit 1), valor_devolucion=$valor,valor_pagado=valor_pagado+$valor where id_factura_compra=$id_factura and tipo=1");
                $cxcp = new Ctas_pagar_pagos;
                $cxcp->pagos_por = "Cancelación";
                $cxcp->valor_seleccionado = $valor;
                $cxcp->valor_real_pago = $valor;
                if (count($fp) > 0) {
                    $cxcp->id_forma_pagos = $fp[0]->id_forma_pagos;
                }
                //$cxcp->id_banco = $data["id_banco"];
                $cxcp->fecha_pago = $hoy;
                $cxcp->posicion = $conteo;
                $cxcp->fecha_registro = $notc1[$z]->fecha_emision;
                $cxcp->id_proveedor = $notc1[$z]->id_proveedor;
                $cxcp->save();
                //recupera el id de la cuenta
                $idcxcp = $cxcp->id_ctas_pagar_pagos;
                $referencia = null;
                //if(isset($cta[$z]->id_ctaspagar)){
                foreach ($cta as $detail) {
                    $referencia .= substr($notc1[$z]->autorizacionfactura, 0, 3) . "-" . substr($notc1[$z]->autorizacionfactura, 3, 3) . "-" . substr($notc1[$z]->autorizacionfactura, 6, 9) . ";" . $detail->id_ctaspagar . ";" . number_format($valor, 2, ".", "") . ";ntcc:" . $id_nota_credito . ";";
                }

                //}
                $ref = substr($referencia, 0, -1);
                $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
                $cxcp->referencia = $ref;
                $cxcp->save();
            }
        }

        $cta2 = DB::select("SELECT * FROM ctas_pagar where tipo=1 and valor_devolucion>0 and valor_pagado>valor_cuota");


        for ($b = 0; $b < count($cta2); $b++) {
            $pos0 = DB::select("SELECT count(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$id}");
            $conteo0 = 1;
            if (count($pos0) > 0) {
                $conteo0 = $pos0[0]->posicion + 1;
            }
            $id_cxc = $cta2[$b]->id_ctaspagar;
            $id_ctapagar = $cta2[$b]->id_ctaspagar;
            $monto = $cta2[$b]->valor_pagado - $cta2[$b]->valor_cuota;
            $id_proveedor = $cta2[$b]->id_proveedor;
            $cxc = new Cuentaporpagar();
            $cxc->num_cuota = 1;
            $cxc->fecha_pago = $hoy;
            $cxc->valor_cuota = $monto;
            $cxc->estado = 1;
            $cxc->tipo = 3;
            $cxc->abono = $monto;
            if (count($fp) > 0) {
                $cxc->id_forma_pagos = $fp[0]->id_forma_pagos;
            }

            $cxc->id_proveedor = $id_proveedor;
            $cxc->nota_credito = 1;
            //$cxc->fecha_registro = $fecha_registro_pago;
            //$cxc->ucrea=session()->get('usuariosesion')['id'];
            $cxc->posicion = $conteo;

            $cxc->save();
            $id_ctaantcp = $cxc->id_ctaspagar;
            DB::update("UPDATE ctas_pagar set valor_pagado=valor_cuota where id_ctaspagar=$id_ctapagar");
            $cxcp = new Ctas_pagar_pagos();
            $cxcp->pagos_por = "Anticipo";
            $cxcp->valor_seleccionado = $monto;
            $cxcp->valor_real_pago = $monto;
            if (count($fp) > 0) {
                $cxcp->id_forma_pagos = $fp[0]->id_forma_pagos;
            }
            $cxcp->fecha_pago = $hoy;
            //$cxcp->fecha_registro = $fecha_registro_pago;
            $cxcp->id_proveedor = $id_proveedor;
            $cxcp->posicion = $conteo0;
            //$cxcp->anticipo = 1;
            $cxcp->referencia = $id_ctaantcp;
            //$cxcp->ucrea = $request->id_user;
            $cxcp->save();
        }

        return $data2;
    }
    public function devolucion_nota_credito_venta($id)
    {
        ini_set('max_execution_time', 53200);
        $hoy = Carbon::now();
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        $data2 = "Registro Actualizado Devolucion Nota Credito Venta en Cobro Cliente de la empresa " . $empresa[0]->nombre_empresa;
        $notc1 = DB::select("SELECT * FROM nota_credito where id_empresa={$id}");
        $fp = DB::select("SELECT id_forma_pagos from forma_pagos where id_empresa={$id}");
        for ($z = 0; $z < count($notc1); $z++) {

            $pos0 = DB::select("SELECT max(id_ctas_cobrar_pagos) as posicion from ctas_cobrar_pagos INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar_pagos.id_cliente where cliente.id_empresa={$id}");
            if (count($pos0) > 0) {
                $pos = DB::select("SELECT count(id_ctas_cobrar_pagos) as posicion from ctas_cobrar_pagos INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar_pagos.id_cliente where cliente.id_empresa={$id}");
            } else {
                $pos = [];
            }
            $conteo = 1;
            if (count($pos) > 0) {
                $conteo = $pos[0]->posicion + 1;
            }
            $id_factura = $notc1[$z]->id_factura;
            $id_nota_credito = $notc1[$z]->id_nota_credito;
            $cta = DB::select("SELECT * FROM ctas_cobrar where id_factura=$id_factura and tipo=1 ");
            if (count($cta) > 0) {

                $valor = $notc1[$z]->valor_total / count($cta);
                DB::update("UPDATE ctas_cobrar set valor_cuota=(select total from factura_pagos where id_factura=ctas_cobrar.id_factura and estado=2 limit 1), valor_devolucion=$valor,valor_pagado=valor_pagado+$valor where id_factura=$id_factura and tipo=1");
                $cxcp = new Ctas_cobrar_pagos;
                $cxcp->pagos_por = "Nota Credito";
                $cxcp->valor_seleccionado = $valor;
                $cxcp->valor_real_pago = $valor;
                if (count($fp) > 0) {
                    $cxcp->id_forma_pagos = $fp[0]->id_forma_pagos;
                }
                //$cxcp->id_banco = $data["id_banco"];
                $cxcp->fecha_pago = $hoy;
                //$cxcp->posicion = $conteo;
                $cxcp->fecha_registro = $notc1[$z]->fecha_emision;
                $cxcp->id_cliente = $notc1[$z]->id_cliente;
                $cxcp->nota_credito = 1;
                $cxcp->save();
                //recupera el id de la cuenta
                $idcxcp = $cxcp->id_ctas_cobrar_pagos;
                $referencia = null;
                //if(isset($cta[$z]->id_ctascobrar)){
                foreach ($cta as $detail) {
                    $referencia .= substr($notc1[$z]->autorizacionfactura, 0, 3) . "-" . substr($notc1[$z]->autorizacionfactura, 3, 3) . "-" . substr($notc1[$z]->autorizacionfactura, 6, 9) . ";" . $detail->id_ctascobrar . ";" . number_format($valor, 2, ".", "") . ";ntcc:" . $id_nota_credito . ";";
                }

                //}
                $ref = substr($referencia, 0, -1);
                $cxcp = Ctas_cobrar_pagos::findOrFail($idcxcp);
                $cxcp->referencia = $ref;
                $cxcp->save();
            }
        }

        $cta2 = DB::select("SELECT * FROM ctas_cobrar where tipo=1 and valor_devolucion>0 and valor_pagado>valor_cuota");


        for ($b = 0; $b < count($cta2); $b++) {
            $pos0 = DB::select("SELECT count(id_ctas_cobrar_pagos) as posicion from ctas_cobrar_pagos INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar_pagos.id_cliente where cliente.id_empresa={$id}");
            $conteo0 = 1;
            if (count($pos0) > 0) {
                $conteo0 = $pos0[0]->posicion + 1;
            }
            $id_cxc = $cta2[$b]->id_ctascobrar;
            $id_ctacobrar = $cta2[$b]->id_ctascobrar;
            $monto = $cta2[$b]->valor_pagado - $cta2[$b]->valor_cuota;
            $id_cliente = $cta2[$b]->id_cliente;
            $cxc = new Cuentaporcobrar();
            $cxc->num_cuota = 1;
            $cxc->fecha_pago = $hoy;
            $cxc->valor_cuota = $monto;
            $cxc->estado = 1;
            $cxc->tipo = 3;
            $cxc->abono = $monto;
            if (count($fp) > 0) {
                $cxc->id_forma_pagos = $fp[0]->id_forma_pagos;
            }

            $cxc->id_cliente = $id_cliente;
            //$cxc->fecha_registro = $fecha_registro_pago;
            //$cxc->ucrea=session()->get('usuariosesion')['id'];
            //$cxc->posicion = $conteo;

            $cxc->save();
            $id_ctaantcp = $cxc->id_ctascobrar;
            DB::update("UPDATE ctas_cobrar set valor_pagado=valor_cuota where id_ctascobrar=$id_ctacobrar");
            $cxcp = new Ctas_cobrar_pagos();
            $cxcp->pagos_por = "Anticipo";
            $cxcp->valor_seleccionado = $monto;
            $cxcp->valor_real_pago = $monto;
            if (count($fp) > 0) {
                $cxcp->id_forma_pagos = $fp[0]->id_forma_pagos;
            }
            $cxcp->fecha_pago = $hoy;
            //$cxcp->fecha_registro = $fecha_registro_pago;
            $cxcp->id_cliente = $id_cliente;
            $cxcp->posicion = $conteo0;
            //$cxcp->anticipo = 1;
            $cxcp->referencia = $id_ctaantcp;
            //$cxcp->ucrea = $request->id_user;
            $cxcp->save();
        }

        return $data2;
    }
    public function store(Request $request)
    {
        $hoy = Carbon::now();
        $factura = new Factura();
        $factura->modo = 1;
        $factura->estado = 1;
        $factura->ambiente = $request->ambiente;
        $factura->tipo_emision = $request->tipo_emision;
        $factura->fecha_emision = $request->fecha_emision;
        $factura->clave_acceso = $request->clave_acceso;
        $factura->observacion = $request->observacion;
        $factura->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $factura->subtotal_12 = $request->subtotal_12;
        $factura->subtotal_0 = $request->subtotal_0;
        $factura->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = $request->valor_ice;
        $factura->valor_irbpnr = $request->valor_irbpnr;
        $factura->iva_12 = $request->iva_12;
        $factura->propina = $request->propina;
        $factura->estatus = 1;
        $factura->propina = $request->propina;
        $factura->propina = $request->propina;
        $factura->valor_total = $request->valor_total;
        $factura->id_cliente = $request->id_cliente;
        $factura->id_user = $request->id_user;
        $factura->id_punto_emision = $request->id_punto_emision;
        $factura->id_empresa = $request->id_empresa;
        $factura->id_establecimiento = $request->id_establecimiento;
        $factura->totalpropinaf = $request->totalpropinaf;
        $factura->pp_descuento = $request->pp_descuento;
        $factura->orden_compra = $request->ordencompra;
        $factura->migo = $request->migo;
        $factura->id_proyecto = $request->proyecto;
        $factura->id_forma_pagos = $request->forma_pago;
        $factura->created_by = session()->get('usuariosesion')['id'];
        $factura->updated_by = session()->get('usuariosesion')['id'];
        $factura->save();
        $id = $factura->id_factura;
        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
            $transportistas = new FacturaGuiaDeRemision();
            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
            $transportistas->placa_tr = $request->transportista['placa_transporte'];
            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
            $transportistas->cod_sustento_tr = 1;
            $transportistas->id_empresa = $request->id_empresa; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_factura = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_cliente = $request->id_cliente;
            $transportistas->id_user = $request->id_user;
            $transportistas->id_punto_emision = $request->id_punto_emision;
            $transportistas->id_establecimiento = $request->id_establecimiento;
            $transportistas->save();
            $idt = $transportistas->id_guia;
        }
        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->save();

            /*$prod = Producto::findOrFail($request->productos[$a]["id_producto"]);
            $prod->
            $prod->save();*/

            if ($request->guia) {
                $detguia = new DetalleGuiaRemision();
                $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                $detguia->descripcion = $request->productos[$a]["nombre"];
                $detguia->cantidad = $request->productos[$a]["cantidad"];
                $detguia->id_producto = $request->productos[$a]["id_producto"];
                $detguia->id_guia_remision = $idt;
                $detguia->save();
            }
        }
        if ($request->verpagos) {
            for ($a = 0; $a < count($request->valorpagos); $a++) {
                if ($request->valorpagos[$a]["metodo_pago"] != null && $request->valorpagos[$a]["cantidad_pago"] != 0) {
                    $pag = new Factura_pagos();
                    $pag->id_forma_pagos = $request->valorpagos[$a]["metodo_pago"];
                    $pag->total = $request->valorpagos[$a]["cantidad_pago"];
                    $pag->plazo = 1;
                    $pag->unidad_tiempo = 'Días';
                    $pag->estado = 1;
                    $pag->fecha = $hoy;
                    $pag->id_factura = $id;
                    $pag->save();

                    $cxc = new Cuentaporcobrar();
                    $cxc->num_cuota = 1;
                    $cxc->fecha_pago = $hoy;
                    $cxc->periodo_pagos = "Dia";
                    $cxc->valor_cuota = $request->valorpagos[$a]["cantidad_pago"];
                    $cxc->id_forma_pagos = $request->valorpagos[$a]["metodo_pago"];
                    $cxc->id_banco = $request->valorpagos[$a]["banco"];
                    $cxc->numero_tarjeta = $request->valorpagos[$a]["tarjeta"];
                    $cxc->valor_pagado = 0;
                    $cxc->estado = 1;
                    $cxc->tipo = 2;
                    $cxc->id_factura = $id;
                    $cxc->id_cliente = $request->id_cliente;
                    $cxc->created_by = session()->get('usuariosesion')['id'];
                    $cxc->updated_by = session()->get('usuariosesion')['id'];
                    $cxc->save();
                }
            }
        }
        $fecharec = "";
        if ($request->vercreditos) {
            $pag = new Factura_pagos();
            $pag->id_forma_pagos = 7;
            $pag->total = $request->monto;
            $pag->plazo = $request->plazos;
            $pag->unidad_tiempo = $request->periodo;
            $pag->estado = 2;
            $pag->fecha = $hoy;
            $pag->id_factura = $id;
            $pag->save();

            $hoy = $request->fecha_emision;
            $fd = "";
            for ($a = 0; $a < $request->plazos; $a++) {
                $cxc = new Cuentaporcobrar();
                $cxc->num_cuota = $request->$a + 1;
                if ($a < 1) {
                    if ($request->periodo == "Años") {
                        $fecharec = $hoy->addYears($request->saltos_pagos);
                        $fd = $hoy->addYears($request->saltos_pagos)->format('Y-m-d');
                    } else if ($request->periodo == "Meses") {
                        $fecharec = $hoy->addMonths($request->saltos_pagos);
                        $fd = $hoy->addMonths($request->saltos_pagos)->format('Y-m-d');
                    } else if ($request->periodo == "Semanas") {
                        $fecharec = $hoy->addWeeks($request->saltos_pagos);
                        $fd = $hoy->addWeeks($request->saltos_pagos)->format('Y-m-d');
                    } else {
                        $fecharec = $hoy->addDays($request->saltos_pagos);
                        $fd = $hoy->addDays($request->saltos_pagos)->format('Y-m-d');
                    }
                } else {
                    if ($request->periodo == "Años") {
                        $fd = $fecharec->addYears($request->saltos_pagos)->format('Y-m-d');
                    } else if ($request->periodo == "Meses") {
                        $fd = $fecharec->addMonths($request->saltos_pagos)->format('Y-m-d');
                    } else if ($request->periodo == "Semanas") {
                        $fd = $fecharec->addWeeks($request->saltos_pagos)->format('Y-m-d');
                    } else {
                        $fd = $fecharec->addDays($request->saltos_pagos)->format('Y-m-d');
                    }
                }
                $cxc->fecha_pago = $fd;
                $cxc->periodo_pagos = $request->periodo;
                $cxc->valor_cuota = round($request->monto / $request->plazos, 2, PHP_ROUND_HALF_UP);
                $cxc->valor_pagado = 0;
                $cxc->estado = 1;
                $cxc->tipo = 1;
                $cxc->id_factura = $id;
                $cxc->id_cliente = $request->id_cliente;
                $cxc->created_by = session()->get('usuariosesion')['id'];
                $cxc->updated_by = session()->get('usuariosesion')['id'];
                $cxc->save();
            }
        }
        if ($request->verretenciones) {
            for ($i = 0; $i < count($request->retencion); $i++) {
                if ($request->retencion[$i]["iva"] != null && $request->retencion[$i]["renta"] != null) {
                    $ret = new Retencion_factura();
                    $ret->id_factura = $id;
                    $ret->id_retencion_iva = $request->retencion[$i]["iva"]["id_retencion"];
                    $ret->id_retencion_renta = $request->retencion[$i]["renta"]["id_retencion"];

                    $ret->porcentajeiva = $request->retencion[$i]["porcentajeiva"];
                    $ret->cantidadiva = $request->retencion[$i]["cantidadiva"];
                    $ret->baserenta = $request->retencion[$i]["baserenta"];
                    $ret->porcentajerenta = $request->retencion[$i]["porcentajerenta"];
                    $ret->cantidadrenta = $request->retencion[$i]["cantidadrenta"];
                    $ret->save();
                }
            }
        }
        return [
            "factura" => Factura::select('factura.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'factura.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
                ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
                ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
                ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
                ->where("factura.id_factura", "=", $id)
                ->orderByRaw('factura.id_factura DESC')->get(),
            "guia" => Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->addSelect(['clave_acceso' => Factura::select('clave_acceso')
                    ->whereColumn('factura.id_factura', 'guia_remision.id_factura')])
                ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
                ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
                ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
                ->where("guia_remision.id_factura", "=", $id)
                ->orderByRaw('guia_remision.id_guia DESC')->get(),
        ];
        // return $request;
    }
    public function indexEmpresa($id)
    {
        $recupera = DB::table('empresa')
            ->where('id_empresa', $id)->get();
        return $recupera;
    }
    public function verproductos(Request $request)
    {
        $idfactura = $request->idfactura;
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp);
            $recupera = Detalle::select('*')
                ->orderByRaw('id_detalle DESC')->paginate($cantidadp);
        } else {
            $recupera = Detalle::select('*')->where(function ($q) use ($buscar) {
                $q->where('descripcion', '=', $buscar)
                    ->orWhere('cantidad', '=', $buscar);
            })
                ->where('id_factura', '=', $idfactura)
                ->orderByRaw('id_detalle DESC')->paginate($cantidadp);
        }
        return [
            'pagination' => [
                'total' => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page' => $recupera->perPage(),
                'last_page' => $recupera->lastPage(),
                'from' => $recupera->firstItem(),
                'to' => $recupera->lastItem(),
                'count' => ceil($recupera->total() / $cantidadp),
            ],
            'recupera' => $recupera,
        ];
    }
    public function buscarfac()
    {
        $recupera = "asdasd";
        return $recupera;
    }
    public function abrir()
    {
        $respuesta = DB::select('*');
        return $respuesta;
    }
    public function clave($id)
    {
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , IF(pe.secuencial_factura<=1 || pe.secuencial_factura IS NULL,1,pe.secuencial_factura) AS numeral FROM user u LEFT JOIN empresa e ON e.id_empresa=u.id_empresa LEFT JOIN establecimiento es ON es.id_establecimiento=u.id_establecimiento LEFT JOIN punto_emision pe ON pe.id_punto_emision=u.id_punto_emision WHERE u.id =" . $id);

        $valor =  $respuesta[0]->numeral;
        $tbl_interes = DB::select("SELECT * from tabla_interes where id_empresa={$respuesta[0]->id_empresa}");
        $exist_interes = 0;
        if (count($tbl_interes) > 0) {
            $exist_interes = 1;
        }

        return [
            'secuencial' => $valor,
            'recupera' => $respuesta,
            'exist_interes' => $exist_interes
        ];
    }
    public function clave_nota_venta($id)
    {
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , if(pe.secuencial_nota_venta<=1 || pe.secuencial_nota_venta is NULL,1,pe.secuencial_nota_venta) as numeral FROM user u 
                                INNER JOIN empresa e on e.id_empresa=u.id_empresa 
                                INNER JOIN establecimiento es on es.id_establecimiento=u.id_establecimiento
                                INNER JOIN punto_emision pe on pe.id_punto_emision=u.id_punto_emision
                                WHERE u.id = " . $id);
        $tbl_interes = DB::select("SELECT * from tabla_interes where id_empresa={$respuesta[0]->id_empresa}");
        $exist_interes = 0;
        if (count($tbl_interes) > 0) {
            $exist_interes = 1;
        }
        $valor =  $respuesta[0]->numeral;

        return [
            'secuencial' => $valor,
            'recupera' => $respuesta,
            'exist_interes' => $exist_interes
        ];
    }
    public function clave_guia($id)
    {
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , if(pe.secuencial_guia_remision<=1 || pe.secuencial_guia_remision is NULL,1,pe.secuencial_guia_remision) as numeral FROM user u INNER JOIN empresa e on e.id_empresa=u.id_empresa INNER JOIN establecimiento es on es.id_empresa=e.id_empresa INNER JOIN punto_emision pe on pe.id_empresa=e.id_empresa WHERE u.id = " . $id);

        $valor =  $respuesta[0]->numeral;

        return [
            'secuencial' => $valor,
            'recupera' => $respuesta,
        ];
    }
    public function clave_guia_nota_venta($id)
    {
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , if(pe.secuencial_guia_remision<=1 || pe.secuencial_guia_remision is NULL,1,pe.secuencial_guia_remision) as numeral FROM user u INNER JOIN empresa e on e.id_empresa=u.id_empresa INNER JOIN establecimiento es on es.id_empresa=e.id_empresa INNER JOIN punto_emision pe on pe.id_empresa=e.id_empresa WHERE u.id = " . $id);

        $valor =  $respuesta[0]->numeral;

        return [
            'secuencial' => $valor,
            'recupera' => $respuesta,
        ];
    }
    public function eliminar(Request $rq)
    {
        $hoy = Carbon::now();
        $factura = $rq->datos["id_factura"];
        $id_empresa = $rq->datos["id_empresa"];
        $clave = substr($rq->datos["clave_acceso"], 24, 15);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$id_empresa} limit 1");
        $pto_emision = DB::select("SELECT * from punto_emision where id_punto_emision={$rq->id_pto} limit 1");
        //DB::delete("DELETE FROM bodega_egreso WHERE id_factura = $factura");
        $egreso = DB::select("SELECT bed.*,be.id_empresa,be.id_factura,be.id_bodega from bodega_egreso_detalle as bed INNER JOIN bodega_egreso as be ON be.id_bodega_egreso=bed.id_bodega_egreso WHERE be.id_factura = $factura");
        $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $id_empresa ORDER BY  num_ingreso DESC LIMIT 1;");
        $numeroegreso = "";
        //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
        if (count($numegre) == 1) {
            $dato = $numegre[0]->num_ingreso;
            $tot = $dato + 1;
            $numeroegreso = $tot;
        } else {
            $numeroegreso = 1;
        }

        $productos = DB::select("SELECT * FROM detalle WHERE id_factura = $factura");

        $savebode = 0;
        $id_bodega_ingreso = "";
        // for ($i = 0; $i < count($productos); $i++) {
        //     $cantidad = $productos[$i]->cantidad;
        //     $id_producto_bodega = $productos[$i]->id_producto_bodega;
        //     if (isset($id_producto_bodega) && $id_producto_bodega) {
        //         DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cantidad, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $id_producto_bodega");

        //         if ($savebode == 0) {
        //             $egreso = new BodegaIngreso();
        //             $egreso->num_ingreso = $numeroegreso;
        //             $egreso->fecha_ingreso = $hoy;
        //             $egreso->tipo_ingreso = "Ingreso de Factura";
        //             $egreso->observ_ingreso = 'Cancelacion Factura Venta: ' . $clave;
        //             $egreso->id_proyecto = $proyecto[0]->id_proyecto;
        //             if ($pto_emision[0]->id_bodega) {
        //                 $egreso->id_bodega = $pto_emision[0]->id_bodega;
        //             }
        //             $egreso->id_empresa = $id_empresa;
        //             //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
        //             $egreso->id_factura = $factura;
        //             $egreso->save();
        //             $id_bodega_ingreso=$egreso->id_bodega_ingreso;
        //             $savebode++;
        //         }

        //         $bed = new BodegaIngresoDetalle();
        //         $bed->cantidad = $cantidad;
        //         $bed->costo_unitario = $productos[$i]->precio;
        //         $bed->costo_total = $cantidad * $productos[$i]->precio;
        //         $bed->id_bodega_ingreso = $id_bodega_ingreso;
        //         $bed->id_producto = $productos[$i]->id_producto;
        //         $bed->id_proyecto = $productos[$i]->id_proyecto;
        //         //$bed->id_detalle = $id_detalle;
        //         $bed->save();
        //     }
        // }
        if (count($egreso) > 0) {
            for ($i = 0; $i < count($egreso); $i++) {
                DB::update("UPDATE producto_bodega SET cantidad = cantidad + {$egreso[$i]->cantidad}, costo_total = costo_total+{$egreso[$i]->costo_total},costo_unitario=costo_total/cantidad WHERE id_producto = {$egreso[$i]->id_producto} and id_bodega={$egreso[$i]->id_bodega}");
                if ($savebode == 0) {
                    $egresos = new BodegaIngreso();
                    $egresos->num_ingreso = $numeroegreso;
                    $egresos->fecha_ingreso = $hoy;
                    $egresos->tipo_ingreso = "Ingreso de Factura";
                    $egresos->observ_ingreso = 'Cancelacion Factura Venta: ' . $clave;
                    $egresos->id_proyecto = $proyecto[0]->id_proyecto;
                    //if (isset($egreso[$i]->id_bodega)) {
                    $egresos->id_bodega = $egreso[$i]->id_bodega;
                    //}
                    $egresos->id_empresa = $egreso[$i]->id_empresa;
                    //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    $egresos->id_factura = $egreso[$i]->id_factura;
                    $egresos->save();
                    $id_bodega_ingreso = $egresos->id_bodega_ingreso;
                    $savebode++;
                }
                $bed = new BodegaIngresoDetalle();
                $bed->cantidad = $egreso[$i]->cantidad;
                $bed->costo_unitario = $egreso[$i]->costo_unitario;
                $bed->costo_total = $egreso[$i]->costo_total;
                $bed->id_bodega_ingreso = $id_bodega_ingreso;
                $bed->id_producto = $egreso[$i]->id_producto;
                $bed->id_proyecto = $egreso[$i]->id_proyecto;
                //$bed->id_detalle = $id_detalle;
                $bed->save();
            }
        }





        $del = Factura::findOrFail($factura);
        $del->estado = 0;
        $del->save();
        Cuentaporcobrar::where("id_factura", "=", $factura)->delete();

        $res = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE referencia LIKE '%$factura%'");
        $datos = new \ArrayObject();
        for ($f = 0; $f < count($res); $f++) {
            $ref = explode(";", $res[$f]->referencia);
            for ($i = 0; $i < count($ref); $i++) {
                if ($i % 4 == 3) {
                    if ($ref[$i] == 790) {
                        $val3 = $ref[$i - 3];
                        $val2 = $ref[$i - 2];
                        $val1 = $ref[$i - 1];
                        $val = $ref[$i];
                        $datos->append("$val3;$val2;$val1;$val");
                    }
                }
            }
        }
        foreach ($datos as $rs) {
            $revisarid = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE referencia like '%$rs%'");
            $id = $revisarid[0]->id_ctas_cobrar_pagos;

            DB::update("UPDATE ctas_cobrar_pagos SET referencia = replace(referencia, '$rs', '') WHERE referencia like '%$rs%'");

            $revisarids = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = $id");
            $reff = $revisarids[0]->referencia;

            if ($reff == "") {
                DB::delete("DELETE FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = $id");
            }
        }
    }
    public function eliminarnota_venta(Request $rq)
    {
        $hoy = Carbon::now();
        $factura = $rq->datos["id_nota_venta"];
        $id_empresa = $rq->datos["id_empresa"];
        $id_cliente_not = $rq->datos["id_cliente"];
        $clave = substr($rq->datos["clave_acceso"], 24, 15);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$id_empresa} limit 1");
        $pto_emision = DB::select("SELECT * from punto_emision where id_punto_emision={$rq->id_pto} limit 1");
        //DB::delete("DELETE FROM bodega_egreso WHERE id_factura = $factura");
        $egreso = DB::select("SELECT bed.*,be.id_empresa,be.id_nota_venta,be.id_bodega from bodega_egreso_detalle as bed INNER JOIN bodega_egreso as be ON be.id_bodega_egreso=bed.id_bodega_egreso WHERE be.id_nota_venta = $factura");
        $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $id_empresa ORDER BY  num_ingreso DESC LIMIT 1;");
        $numeroegreso = "";
        //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
        if (count($numegre) == 1) {
            $dato = $numegre[0]->num_ingreso;
            $tot = $dato + 1;
            $numeroegreso = $tot;
        } else {
            $numeroegreso = 1;
        }

        $productos = DB::select("SELECT * FROM detalle_nota_venta WHERE id_nota_venta = $factura");

        $savebode = 0;
        $id_bodega_ingreso = "";
        // for ($i = 0; $i < count($productos); $i++) {
        //     $cantidad = $productos[$i]->cantidad;
        //     $id_producto_bodega = $productos[$i]->id_producto_bodega;
        //     if (isset($id_producto_bodega) && $id_producto_bodega) {
        //         DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cantidad, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $id_producto_bodega");

        //         if ($savebode == 0) {
        //             $egreso = new BodegaIngreso();
        //             $egreso->num_ingreso = $numeroegreso;
        //             $egreso->fecha_ingreso = $hoy;
        //             $egreso->tipo_ingreso = "Ingreso de Factura";
        //             $egreso->observ_ingreso = 'Cancelacion Factura Venta: ' . $clave;
        //             $egreso->id_proyecto = $proyecto[0]->id_proyecto;
        //             if ($pto_emision[0]->id_bodega) {
        //                 $egreso->id_bodega = $pto_emision[0]->id_bodega;
        //             }
        //             $egreso->id_empresa = $id_empresa;
        //             //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
        //             $egreso->id_factura = $factura;
        //             $egreso->save();
        //             $id_bodega_ingreso=$egreso->id_bodega_ingreso;
        //             $savebode++;
        //         }

        //         $bed = new BodegaIngresoDetalle();
        //         $bed->cantidad = $cantidad;
        //         $bed->costo_unitario = $productos[$i]->precio;
        //         $bed->costo_total = $cantidad * $productos[$i]->precio;
        //         $bed->id_bodega_ingreso = $id_bodega_ingreso;
        //         $bed->id_producto = $productos[$i]->id_producto;
        //         $bed->id_proyecto = $productos[$i]->id_proyecto;
        //         //$bed->id_detalle = $id_detalle;
        //         $bed->save();
        //     }
        // }
        if (count($egreso) > 0) {
            for ($i = 0; $i < count($egreso); $i++) {
                DB::update("UPDATE producto_bodega SET cantidad = cantidad + {$egreso[$i]->cantidad}, costo_total = costo_total+{$egreso[$i]->costo_total},costo_unitario=costo_total/cantidad WHERE id_producto = {$egreso[$i]->id_producto} and id_bodega={$egreso[$i]->id_bodega}");
                if ($savebode == 0) {
                    $egresos = new BodegaIngreso();
                    $egresos->num_ingreso = $numeroegreso;
                    $egresos->fecha_ingreso = $hoy;
                    $egresos->tipo_ingreso = "Ingreso de Nota Venta";
                    $egresos->observ_ingreso = 'Cancelacion Nota Venta: ' . $clave;
                    $egresos->id_proyecto = $proyecto[0]->id_proyecto;
                    //if (isset($egreso[$i]->id_bodega)) {
                    $egresos->id_bodega = $egreso[$i]->id_bodega;
                    //}
                    $egresos->id_empresa = $egreso[$i]->id_empresa;
                    //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    $egresos->id_nota_venta = $egreso[$i]->id_nota_venta;
                    $egresos->save();
                    $id_bodega_ingreso = $egresos->id_bodega_ingreso;
                    $savebode++;
                }
                $bed = new BodegaIngresoDetalle();
                $bed->cantidad = $egreso[$i]->cantidad;
                $bed->costo_unitario = $egreso[$i]->costo_unitario;
                $bed->costo_total = $egreso[$i]->costo_total;
                $bed->id_bodega_ingreso = $id_bodega_ingreso;
                $bed->id_producto = $egreso[$i]->id_producto;
                $bed->id_proyecto = $egreso[$i]->id_proyecto;
                $bed->id_detalle_nota_venta = $egreso[$i]->id_detalle_nota_venta;
                $bed->save();
            }
        }






        Cuentaporcobrar::where("id_nota_venta", "=", $factura)->delete();

        $res = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE referencia LIKE '%nv:{$factura}%' and id_cliente={$id_cliente_not}");
        $datos = new \ArrayObject();
        for ($f = 0; $f < count($res); $f++) {
            $ref = explode(";", $res[$f]->referencia);
            for ($i = 0; $i < count($ref); $i++) {
                if ($i % 4 == 3) {
                    if ($ref[$i] == 790) {
                        $val3 = $ref[$i - 3];
                        $val2 = $ref[$i - 2];
                        $val1 = $ref[$i - 1];
                        $val = "nv:" . $ref[$i];
                        $datos->append("$val3;$val2;$val1;$val");
                    }
                }
            }
        }
        foreach ($datos as $rs) {
            $revisarid = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE referencia like '%$rs%' and id_cliente={$id_cliente_not}");
            $id = $revisarid[0]->id_ctas_cobrar_pagos;

            DB::update("UPDATE ctas_cobrar_pagos SET referencia = replace(referencia, '$rs', '') WHERE referencia like '%$rs%'");

            $revisarids = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = $id");
            $reff = $revisarids[0]->referencia;

            if ($reff == "") {
                DB::delete("DELETE FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = $id");
            }
        }
        $fact = NotaVenta::find($factura);
        $fact->estado = 0;
        $fact->updated_by = session()->get('usuariosesion')['id'];
        $fact->save();
    }
    public function storeprof(Request $request)
    {
        $id = $request->id;
        $factura = Factura::findOrFail($id);
        $factura->modo = 1;
        $factura->ambiente = $request->ambiente;
        $factura->tipo_emision = $request->tipo_emision;
        $factura->fecha_emision = $request->fecha_emision;
        $factura->forma_pago = $request->forma_pago;
        $factura->clave_acceso = $request->clave_acceso;
        $factura->observacion = $request->observacion;
        $factura->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $factura->subtotal_12 = $request->subtotal_12;
        $factura->subtotal_0 = $request->subtotal_0;
        $factura->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = $request->valor_ice;
        $factura->valor_irbpnr = $request->valor_irbpnr;
        $factura->iva_12 = $request->iva_12;
        $factura->propina = $request->propina;
        $factura->estatus = 0;
        $factura->propina = $request->propina;
        $factura->propina = $request->propina;
        $factura->valor_total = $request->valor_total;
        $factura->id_cliente = $request->id_cliente;
        $factura->id_user = $request->id_user;
        $factura->id_punto_emision = $request->id_punto_emision;
        $factura->id_empresa = $request->id_empresa;
        $factura->id_establecimiento = $request->id_establecimiento;
        $factura->save();
        DB::select('DELETE FROM detalle WHERE id_factura =' . $id);
        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"];
            $detalle->iva = $request->productos[$a]["iva"];
            $detalle->ice = $request->productos[$a]["ice"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->save();
        }
        for ($a = 0; $a < count($request->valorpagos); $a++) {
            if ($request->valorpagos[$a]["metodo_pago"] != null && $request->valorpagos[$a]["cantidad_pago"] != 0) {
                $cxc = new Cuentaporcobrar();
                $cxc->unidad_tiempo = "Dias";
                $cxc->nro_cuota = 1;
                $cxc->cuotas_totales = 1;
                $cxc->forma_pago = $request->valorpagos[$a]["metodo_pago"];
                $cxc->banco = $request->valorpagos[$a]["banco"];
                $cxc->nro_tarjeta = $request->valorpagos[$a]["tarjeta"];
                $cxc->cta_contable = $request->valorpagos[$a]["cuenta"];
                $cxc->monto = $request->valorpagos[$a]["cantidad_pago"];
                $cxc->abono = 0;
                $cxc->saldo = 0;
                $cxc->comentario = $request->valorpagos[$a]["comentario_pago"];
                $cxc->estado = 1;
                $cxc->tipo = 2;
                $cxc->id_factura = $id;
                $cxc->created_by = session()->get('usuariosesion')['id'];
                $cxc->updated_by = session()->get('usuariosesion')['id'];
                $cxc->save();
            }
        }
        $cxc = new Cuentaporcobrar();
        $cxc->unidad_tiempo = $request->periodo;
        $cxc->nro_cuota = 1;
        $cxc->cuotas_totales = $request->plazos;
        $cxc->forma_pago = "Crédito";
        $cxc->monto = $request->monto;
        $cxc->abono = 0;
        $cxc->saldo = $request->monto;
        $cxc->estado = 1;
        $cxc->tipo = 1;
        $cxc->id_factura = $id;
        $cxc->created_by = session()->get('usuariosesion')['id'];
        $cxc->updated_by = session()->get('usuariosesion')['id'];
        $cxc->save();

        for ($i = 0; $i < count($request->retencion); $i++) {
            if ($request->retencion[$i]["iva"] != null && $request->retencion[$i]["renta"] != null) {
                $ret = new Retencion_factura();
                $ret->id_factura = $id;
                $ret->id_retencion_iva = $request->retencion[$i]["iva"];
                $ret->id_retencion_renta = $request->retencion[$i]["renta"];
                $ret->save();
            }
        }

        return Factura::select('factura.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'factura.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
            ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
            ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
            ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
            ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
            ->where("factura.id_factura", "=", $id)
            ->orderByRaw('factura.id_factura DESC')->get();
    }
    /*--------------------------------------Funciones Proforma--------------------------------------*/
    public function indexp(Request $request, $id)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        $rol = DB::select("SELECT user.*,rol.nombre,empresa.nombre_empresa from user,rol,empresa where user.id_empresa=empresa.id_empresa and rol.id_rol=user.id_rol and  id={$request->id_user}");
        if ($rol[0]->nombre_empresa == 'GRUPO SOLIS INGENIERIA ESPECIALIZADA') {
            if ($buscar == '') {
                //if ($rol[0]->nombre !== 'Usuario') {

                $recupera = Factura::select('factura.*', 'factura.created_by as created_by_factura', 'factura.updated_by as updated_by_factura', 'factura.id_vendedor as id_vendedor_factura', 'factura.codigo as codigo_proforma', 'empresa.*', 'cliente.*')
                    ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                    ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')

                    ->where("factura.id_empresa", "=", $id)
                    ->where("factura.estatus", "=", 0)
                    ->orderByRaw('factura.id_factura DESC')->get();
                // } else {
                //     $recupera = Factura::select('factura.*', 'factura.codigo as codigo_proforma', 'empresa.*', 'cliente.*')
                //         ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                //         ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')

                //         ->where("factura.id_empresa", "=", $id)
                //         ->where("factura.estatus", "=", 0)
                //         ->where("factura.id_user", "=", $request->id_user)
                //         ->orderByRaw('factura.id_factura DESC')->get();
                // }
            } else {
                //if ($rol[0]->nombre !== 'Usuario') {
                $recupera = Factura::select('factura.*', 'factura.created_by as created_by_factura', 'factura.updated_by as updated_by_factura', 'factura.id_vendedor as id_vendedor_factura', 'factura.codigo as codigo_proforma', 'empresa.*', 'cliente.*')
                    ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                    ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
                    ->where(function ($q) use ($buscar) {
                        $q->where('cliente.nombre', 'like', '%' . $buscar . '%')
                            ->orWhere('factura.codigo', 'like', '%' . $buscar . '%');
                    })
                    ->where("factura.id_empresa", "=", $id)
                    ->where("factura.estatus", "=", 0)
                    ->orderByRaw('factura.id_factura DESC')->get();
                // } else {
                //     $recupera = Factura::select('factura.*', 'factura.codigo as codigo_proforma', 'empresa.*', 'cliente.*')
                //         ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                //         ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
                //         ->where(function ($q) use ($buscar) {
                //             $q->where('cliente.nombre', 'like', '%' . $buscar . '%')
                //                 ->orWhere('factura.codigo', 'like', '%' . $buscar . '%');
                //         })
                //         ->where("factura.id_empresa", "=", $id)
                //         ->where("factura.estatus", "=", 0)
                //         ->where("factura.id_user", "=", $request->id_user)
                //         ->orderByRaw('factura.id_factura DESC')->get();
                // }
            }
        } else {
            if ($buscar == '') {
                $recupera = Factura::select('factura.*', 'factura.created_by as created_by_factura', 'factura.updated_by as updated_by_factura', 'factura.id_vendedor as id_vendedor_factura', 'factura.codigo as codigo_proforma', 'empresa.*', 'cliente.*')
                    ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                    ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')

                    ->where("factura.id_empresa", "=", $id)
                    ->where("factura.estatus", "=", 0)
                    ->orderByRaw('factura.id_factura DESC')->get();
            } else {
                $recupera = Factura::select('factura.*', 'factura.created_by as created_by_factura', 'factura.updated_by as updated_by_factura', 'factura.id_vendedor as id_vendedor_factura', 'factura.codigo as codigo_proforma', 'empresa.*', 'cliente.*')
                    ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                    ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
                    ->where(function ($q) use ($buscar) {
                        $q->where('cliente.nombre', 'like', '%' . $buscar . '%')
                            ->orWhere('factura.codigo', 'like', '%' . $buscar . '%');
                    })
                    ->where("factura.id_empresa", "=", $id)
                    ->where("factura.estatus", "=", 0)
                    ->orderByRaw('factura.id_factura DESC')->get();
            }
        }
        if($rol[0]->nombre_empresa !== 'CORTINAS DLUXE'){
            if (session()->get('usuariosesion')['filtro_list'] == 1) {
                $dat = [];
                for ($i = 0; $i < count($recupera); $i++) {
                    if ($recupera[$i]->created_by_factura == session()->get('usuariosesion')['id'] || $recupera[$i]->updated_by_factura == session()->get('usuariosesion')['id'] || $recupera[$i]->id_vendedor_factura == session()->get('usuariosesion')['id_vendedor']) {
                        array_push($dat, $recupera[$i]);
                    }
                }
                $recupera = $dat;
            }
        }
        
        return [
            'recupera' => $recupera,
        ];
    }
    public function get_vendedor_profroma($id)
    {
        $user = DB::select("SELECT * FROM user  WHERE id = $id;");
        if ($user[0]->id_rol != 2) {
            $vendedor = DB::select("SELECT *, id_user AS user_admin FROM vendedor  WHERE id_user = $id ORDER BY  id_vendedor DESC LIMIT 1;");
            $vendedor[0]->user_admin = true;
        } else {
            $vendedor = DB::select("SELECT *, id_user AS user_admin FROM vendedor  WHERE id_user = $id ORDER BY  id_vendedor DESC LIMIT 1;");
            $vendedor[0]->user_admin = false;
        }
        return $vendedor;
    }
    public function listar_productos(Request $request)
    {
        $bs = str_replace(" ", "%", $request->buscar);
        $empresa = $request->id_empresa;
        $empresa_data = DB::select("SELECT * from empresa where id_empresa={$empresa}");
        $pto_emision = DB::select("SELECT * from punto_emision where id_bodega is not null  and  id_punto_emision={$request->id_pto_emision}");
        $ce_1 = strpos($empresa_data[0]->nombre_empresa, "C.E. FUEGOS");
        $ce_2 = strpos($empresa_data[0]->nombre_empresa, "C.E.FUEGOS");
        //verifica si existe cliente elegido antes de listar los productos
        //si existe cliente recupera el precio dependiendo de la lista que se asigno al cliente
        if ($request->cliente) {
            $res3 = [];
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
            $seg = 0;
            $pln = 0;
            if (isset($cli[0]->id_seguro) && $cli[0]->id_seguro !== null) {
                $seg = $cli[0]->id_seguro;
            }
            if (isset($cli[0]->id_plan_seguro) && $cli[0]->id_plan_seguro !== null) {
                $pln = $cli[0]->id_plan_seguro;
            }
            //dd("SELECT p.*, $contt, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 ORDER BY p.codigo_barras DESC LIMIT 10");
            //recupera tanto productos como servicios $res = producto, $res1 = servicio
            if (count($pto_emision) > 0) {

                if ($ce_1 !== false || $ce_2 !== false) {
                    //dd("entro");
                    $res3 =  DB::select("SELECT p.*, $contt, pb.id_producto_bodega, pre.nombre AS presentacion, if(pb.cantidad is null,0,pb.cantidad) as cantidad, (select nombre from bodega where id_bodega={$pto_emision[0]->id_bodega}) AS nombrebodega, ice.nombre AS nombreice, {$pto_emision[0]->id_bodega} as id_bodega,null as siiva,$seg as id_seguro,$pln as id_plan_seguro,(select plan_seguro.descuento from plan_seguro_detalle INNER JOIN plan_seguro ON plan_seguro.id_plan_seguro=plan_seguro_detalle.id_plan_seguro INNER JOIN producto ON producto.id_producto=plan_seguro_detalle.id_producto where plan_seguro_detalle.id_plan_seguro=$pln and plan_seguro_detalle.id_producto=p.id_producto and plan_seguro_detalle.agregado=1 limit 1) as descuento_seguro FROM producto p LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion  LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 and p.estado=1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa and pb.id_producto_bodega is null ORDER BY p.codigo_barras DESC LIMIT 10");
                }
                //else{
                $res =  DB::select("SELECT p.*, $contt,pb.id_producto_bodega, pb.cantidad, pre.nombre AS presentacion, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva,mc.nombre as nombre_marca,p.id_marca,$seg as id_seguro,$pln as id_plan_seguro,(select plan_seguro.descuento from plan_seguro_detalle INNER JOIN plan_seguro ON plan_seguro.id_plan_seguro=plan_seguro_detalle.id_plan_seguro INNER JOIN producto ON producto.id_producto=plan_seguro_detalle.id_producto where plan_seguro_detalle.id_plan_seguro=$pln and plan_seguro_detalle.id_producto=p.id_producto and plan_seguro_detalle.agregado=1 limit 1) as descuento_seguro
                FROM producto p 
                LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion 
                LEFT JOIN ice ON ice.id_ice = p.ice 
                LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto 
                LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega 
                LEFT JOIN marca mc ON mc.id_marca=p.id_marca
                WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') and p.estado=1 AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega={$pto_emision[0]->id_bodega} ORDER BY p.codigo_barras DESC LIMIT 10");
                //}

            } else {
                if ($ce_1 !== false || $ce_2 !== false) {
                    $res3 =  DB::select("SELECT p.*, $contt, pb.id_producto_bodega, pre.nombre AS presentacion, if(pb.cantidad is null,0,pb.cantidad) as cantidad, (select nombre from bodega where id_bodega=0) AS nombrebodega, ice.nombre AS nombreice,0 as id_bodega,null as siiva,$seg as id_seguro,$pln as id_plan_seguro,(select plan_seguro.descuento from plan_seguro_detalle INNER JOIN plan_seguro ON plan_seguro.id_plan_seguro=plan_seguro_detalle.id_plan_seguro INNER JOIN producto ON producto.id_producto=plan_seguro_detalle.id_producto where plan_seguro_detalle.id_plan_seguro=$pln and plan_seguro_detalle.id_producto=p.id_producto and plan_seguro_detalle.agregado=1 limit 1) as descuento_seguro FROM producto p LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 and p.estado=1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa and pb.id_producto_bodega is null   ORDER BY p.codigo_barras DESC LIMIT 10");
                }

                $res =  DB::select("SELECT p.*, $contt, pb.id_producto_bodega, pb.cantidad, pre.nombre AS presentacion, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva,mc.nombre as nombre_marca,p.id_marca,$seg as id_seguro,$pln as id_plan_seguro,(select plan_seguro.descuento from plan_seguro_detalle INNER JOIN plan_seguro ON plan_seguro.id_plan_seguro=plan_seguro_detalle.id_plan_seguro INNER JOIN producto ON producto.id_producto=plan_seguro_detalle.id_producto where plan_seguro_detalle.id_plan_seguro=$pln and plan_seguro_detalle.id_producto=p.id_producto and plan_seguro_detalle.agregado=1 limit 1) as descuento_seguro 
                FROM producto p 
                LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion                 
                LEFT JOIN ice ON ice.id_ice = p.ice 
                LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto 
                LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega 
                LEFT JOIN marca mc ON mc.id_marca=p.id_marca
                WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') and p.estado=1 AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega=0 ORDER BY p.codigo_barras DESC LIMIT 10");
            }

            $res1 = DB::select("SELECT p.*, $contt, pre.nombre AS presentacion, ice.nombre AS nombreice,null as siiva FROM producto p LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras like '%$bs%' ) AND p.id_empresa = $empresa and p.estado=1 AND p.tipo_servicio='Venta' AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");
            //
            //concatena los dos array en uno solo y lista los productos dentro de la lista
            $res2 = array_merge($res1, $res, $res3);
            return $res2;
        } else {
            $tri = trim($bs);
            $res3 = [];
            //recupera tanto productos como servicios $res = producto, $res1 = servicio
            if (count($pto_emision) > 0) {

                if ($ce_1 !== false || $ce_2 !== false) {
                    $res3 =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, 0 as cantidad, (select nombre from bodega where id_bodega={$pto_emision[0]->id_bodega}) AS nombrebodega, pre.nombre AS presentacion, ice.nombre AS nombreice,{$pto_emision[0]->id_bodega} as id_bodega,null as siiva FROM producto p LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 and p.estado=1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa and pb.id_producto_bodega is null  ORDER BY p.codigo_barras DESC LIMIT 10");
                }
                //else{
                $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, pre.nombre AS presentacion, ice.nombre AS nombreice, b.id_bodega,null as siiva,mc.nombre as nombre_marca,p.id_marca
                FROM producto p 
                LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion
                LEFT JOIN ice ON ice.id_ice = p.ice 
                LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto 
                LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega 
                LEFT JOIN marca mc ON mc.id_marca=p.id_marca
                WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') and p.estado=1 AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega={$pto_emision[0]->id_bodega} ORDER BY p.codigo_barras DESC LIMIT 10");
                //}
            } else {
                if ($ce_1 !== false || $ce_2 !== false) {
                    $res3 =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, 0 as cantidad, (select nombre from bodega where id_bodega=0) AS nombrebodega, ice.nombre AS nombreice,0 as id_bodega,null as siiva FROM producto p LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') and p.estado=1 AND p.id_empresa = $empresa and pb.id_producto_bodega is null  ORDER BY p.codigo_barras DESC LIMIT 10");
                }
                //else{
                $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, pre.nombre AS presentacion, pre.nombre AS presentacion, ice.nombre AS nombreice, b.id_bodega,null as siiva,mc.nombre as nombre_marca,p.id_marca 
                FROM producto p 
                LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion
                LEFT JOIN ice ON ice.id_ice = p.ice 
                LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto 
                LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega 
                LEFT JOIN marca mc ON mc.id_marca=p.id_marca
                WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') and p.estado=1 AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega=0 ORDER BY p.codigo_barras DESC LIMIT 10");
                //}

            }
            $res1 = DB::select("SELECT p.*, p.pvp_precio1 AS precio, pre.nombre AS presentacion, ice.nombre AS nombreice,null as siiva FROM producto p LEFT JOIN presentacion pre ON pre.id_presentacion = p.id_presentacion LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$tri') AND p.id_empresa = $empresa AND p.tipo_servicio='Venta' AND p.sector = 2 and p.estado=1 ORDER BY p.codigo_barras DESC LIMIT 10");

            //concatena los dos array en uno solo y lista los productos dentro de la lista
            $res2 = array_merge($res1, $res, $res3);
            return $res2;
        }
    }

    public function listar_productos_prof(Request $request)
    {
        $bs = str_replace(" ", "%", $request->buscar);
        $empresa = $request->id_empresa;
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
            $res = DB::select("SELECT p.*, $contt, sum(pb.cantidad) AS cantidad, pre.nombre AS presentacion FROM producto p LEFT JOIN presentacion pre ON p.id_presentacion = pre.id_presentacion LEFT JOIN producto_bodega pb ON p.id_producto = pb.id_producto WHERE (p.cod_principal LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%') AND p.estado = 1 AND p.id_empresa = $empresa  GROUP BY p.id_producto LIMIT 10;");
            return $res;
        } else {
            $res = DB::select("SELECT p.*, sum(pb.cantidad) AS cantidad, pre.nombre AS presentacion FROM producto p LEFT JOIN presentacion pre ON p.id_presentacion = pre.id_presentacion LEFT JOIN producto_bodega pb ON p.id_producto = pb.id_producto WHERE (p.cod_principal LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%') AND p.estado = 1 AND p.id_empresa = $empresa  GROUP BY p.id_producto LIMIT 10;");
            return $res;
        }
    }

    public function storep(Request $request)
    {
        $empresa = $request->usuario["id_empresa"];
        //codigo proforma
        $selnum = DB::select("SELECT codigo FROM factura  WHERE id_empresa = $empresa ORDER BY  codigo DESC LIMIT 1;");
        $prin = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->codigo;
            $tot = $dato + 1;
            $prin = $tot;
        } else {
            $prin = 1;
        }
        $empresa_data = DB::select("SELECT * from empresa where id_empresa={$request->usuario["id_empresa"]}");
        $factura = new Factura();
        $factura->codigo = $prin;
        $factura->modo = 0;
        if (isset($empresa_data[0]->ambiente) && isset($empresa_data[0]->tipo_emision)) {
            $factura->ambiente = $empresa_data[0]->ambiente;
            $factura->tipo_emision = $empresa_data[0]->tipo_emision;
        } else {
            $factura->ambiente = 1;
            $factura->tipo_emision = 1;
        }

        $factura->fecha_emision = $request->factura["fecha_emision"];
        $factura->fecha_expiracion = $request->factura["fecha_expiracion"];
        $factura->estatus = 0;
        $factura->observacion = $request->factura["observacion"];
        $factura->subtotal_sin_impuesto = $request->subtotal;
        $factura->subtotal_12 = $request->subtotal12;
        $factura->subtotal_0 = $request->subtotal0;
        $factura->subtotal_no_obj_iva = $request->no_impuesto;
        $factura->descuento = $request->descuento;

        $factura->valor_ice = $request->valor_ice;
        $factura->valor_irbpnr = $request->valor_irbpnr;

        $factura->iva_12 = $request->valor12;

        $factura->propina = $request->propina;
        $factura->pp_descuento = $request->pp_descuento;
        $factura->valor_total = $request->total;
        $factura->id_cliente = $request->cliente;
        $factura->id_user = $request->usuario["id"];
        $factura->id_punto_emision = $request->usuario["id_punto_emision"];
        $factura->id_empresa = $request->usuario["id_empresa"];
        $factura->id_establecimiento = $request->usuario["id_establecimiento"];
        $factura->id_proyecto = $request->factura["proyectos"];
        $factura->id_vendedor = $request->factura["vendedor"];
        $factura->id_forma_pagos = $request->factura["forma_pago"];
        $factura->lugar_de_entrega = $request->factura["lugarDeEntrega"];
        $factura->condiciones_de_pago = $request->factura["condicionesDePago"];
        $factura->created_by = session()->get('usuariosesion')['id'];
        $factura->updated_by = session()->get('usuariosesion')['id'];
        $factura->save();
        $id_factura = $factura->id_factura;


        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"];
            if ($request->productos[$a]["p_descuento"] == 0) {
                $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) * $request->productos[$a]["descuento"]) / 100);
            } else {
                $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
            }
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            if (!empty($request->productos[$a]["tiempo_entrega"])) {
                $detalle->tiempo_entrega = $request->productos[$a]["tiempo_entrega"];
            }
            if (!empty($request->productos[$a]["cpc"])) {
                $detalle->cpc = $request->productos[$a]["cpc"];
            }
            $detalle->id_factura = $id_factura;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            if (isset($request->productos[$a]["proyecto"])) {
                $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            }
            if (!empty($request->productos[$a]["color"])) {
                $detalle->color = $request->productos[$a]["color"];
            }
            if (!empty($request->productos[$a]["detalle"])) {
                $detalle->detalle = $request->productos[$a]["detalle"];
            }
            if (!empty($request->productos[$a]["mando"])) {
                $detalle->mando = $request->productos[$a]["mando"];
            }
            if (!empty($request->productos[$a]["alto"])) {
                $detalle->alto = $request->productos[$a]["alto"];
            }
            if (!empty($request->productos[$a]["ancho"])) {
                $detalle->ancho = $request->productos[$a]["ancho"];
            }
            $detalle->save();
        }
        //self::crearproformapdf($id_factura, $factura->id_empresa);
        //self::correoproformaDirecto($id_factura, 1);
    }
    public function editarprof(Request $request)
    {
        ini_set('max_execution_time', 1000);
        $empresa_data = DB::select("SELECT * from empresa where id_empresa={$request->usuario["id_empresa"]}");
        $factura = Factura::findOrFail($request->id);
        $factura->modo = 0;
        if (isset($empresa_data[0]->ambiente) && isset($empresa_data[0]->tipo_emision)) {
            $factura->ambiente = $empresa_data[0]->ambiente;
            $factura->tipo_emision = $empresa_data[0]->tipo_emision;
        } else {
            $factura->ambiente = 1;
            $factura->tipo_emision = 1;
        }
        // $factura->ambiente = 1;
        // $factura->tipo_emision = 1;
        $factura->fecha_emision = $request->factura["fecha_emision"];
        $factura->fecha_expiracion = $request->factura["fecha_expiracion"];
        $factura->estatus = 0;
        $factura->observacion = $request->factura["observacion"];
        $factura->subtotal_sin_impuesto = $request->subtotal;
        $factura->subtotal_12 = $request->subtotal12;
        $factura->subtotal_0 = $request->subtotal0;
        $factura->subtotal_no_obj_iva = $request->no_impuesto;
        $factura->descuento = $request->descuento;

        $factura->valor_ice = $request->valor_ice;
        $factura->valor_irbpnr = $request->valor_irbpnr;

        $factura->iva_12 = $request->valor12;

        $factura->propina = $request->propina;
        $factura->pp_descuento = $request->pp_descuento;
        $factura->valor_total = $request->total;
        $factura->id_cliente = $request->cliente;
        $factura->id_user = $request->usuario["id"];
        $factura->id_punto_emision = $request->usuario["id_punto_emision"];
        $factura->id_empresa = $request->usuario["id_empresa"];
        $factura->id_establecimiento = $request->usuario["id_establecimiento"];
        $factura->id_proyecto = $request->factura["proyectos"];
        $factura->id_vendedor = $request->factura["vendedor"];
        $factura->id_forma_pagos = $request->factura["forma_pago"];
        $factura->lugar_de_entrega = $request->factura["lugarDeEntrega"];
        $factura->condiciones_de_pago = $request->factura["condicionesDePago"];
        $factura->updated_by = session()->get('usuariosesion')['id'];
        $factura->save();
        $id_factura = $factura->id_factura;

        //DB::delete("DELETE FROM detalle WHERE id_factura=" . $id_factura);
        $array_exist=[];
        for ($a = 0; $a < count($request->productos); $a++) {
            if(isset($request->productos[$a]["id_detalle"])){
                $detalle = Detalle::find($request->productos[$a]["id_detalle"]);
                $detalle->nombre = $request->productos[$a]["nombre"];
                $detalle->cantidad = $request->productos[$a]["cantidad"];
                $detalle->precio = $request->productos[$a]["precio"];
                $detalle->descuento = $request->productos[$a]["descuento"];
                $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"];
                if ($request->productos[$a]["p_descuento"] == 0) {
                    $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) * $request->productos[$a]["descuento"]) / 100);
                } else {
                    $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                }
                $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                $detalle->id_iva = $request->productos[$a]["iva"];
                $detalle->id_ice = $request->productos[$a]["ice"];
                if (!empty($request->productos[$a]["tiempo_entrega"])) {
                    $detalle->tiempo_entrega = $request->productos[$a]["tiempo_entrega"];
                }
                if (!empty($request->productos[$a]["cpc"])) {
                    $detalle->cpc = $request->productos[$a]["cpc"];
                }
                //$detalle->id_factura = $id_factura;
                $detalle->id_producto = $request->productos[$a]["id_producto"];
                if (isset($request->productos[$a]["proyecto"])) {
                    $detalle->id_proyecto = $request->productos[$a]["proyecto"];
                }
                if (!empty($request->productos[$a]["color"])) {
                    $detalle->color = $request->productos[$a]["color"];
                }
                if (!empty($request->productos[$a]["detalle"])) {
                    $detalle->detalle = $request->productos[$a]["detalle"];
                }
                if (!empty($request->productos[$a]["mando"])) {
                    $detalle->mando = $request->productos[$a]["mando"];
                }
                if (!empty($request->productos[$a]["alto"])) {
                    $detalle->alto = $request->productos[$a]["alto"];
                }
                if (!empty($request->productos[$a]["ancho"])) {
                    $detalle->ancho = $request->productos[$a]["ancho"];
                }
                $detalle->save();
                array_push($array_exist,$detalle->id_detalle);
            }else{
                $detalle = new Detalle();
                $detalle->nombre = $request->productos[$a]["nombre"];
                $detalle->cantidad = $request->productos[$a]["cantidad"];
                $detalle->precio = $request->productos[$a]["precio"];
                $detalle->descuento = $request->productos[$a]["descuento"];
                $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"];
                if ($request->productos[$a]["p_descuento"] == 0) {
                    $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) * $request->productos[$a]["descuento"]) / 100);
                } else {
                    $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                }
                $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                $detalle->id_iva = $request->productos[$a]["iva"];
                $detalle->id_ice = $request->productos[$a]["ice"];
                if (!empty($request->productos[$a]["tiempo_entrega"])) {
                    $detalle->tiempo_entrega = $request->productos[$a]["tiempo_entrega"];
                }
                if (!empty($request->productos[$a]["cpc"])) {
                    $detalle->cpc = $request->productos[$a]["cpc"];
                }
                $detalle->id_factura = $id_factura;
                $detalle->id_producto = $request->productos[$a]["id_producto"];
                if (isset($request->productos[$a]["proyecto"])) {
                    $detalle->id_proyecto = $request->productos[$a]["proyecto"];
                }
                if (!empty($request->productos[$a]["color"])) {
                    $detalle->color = $request->productos[$a]["color"];
                }
                if (!empty($request->productos[$a]["detalle"])) {
                    $detalle->detalle = $request->productos[$a]["detalle"];
                }
                if (!empty($request->productos[$a]["mando"])) {
                    $detalle->mando = $request->productos[$a]["mando"];
                }
                if (!empty($request->productos[$a]["alto"])) {
                    $detalle->alto = $request->productos[$a]["alto"];
                }
                if (!empty($request->productos[$a]["ancho"])) {
                    $detalle->ancho = $request->productos[$a]["ancho"];
                }
                $detalle->save();
                array_push($array_exist,$detalle->id_detalle);
            }
            
        }
        $array_exist=implode(",",$array_exist);
        DB::delete("DELETE FROM detalle WHERE id_factura={$id_factura} and id_detalle not in ({$array_exist})");
        //self::crearproformapdf($id_factura, $factura->id_empresa);
    }
    public function crearproformapdf($id, $id_empresa)
    {
        $proformas = DB::select("SELECT e.logo,e.nombre_empresa,e.ruc_empresa,e.direccion_empresa,e.email_facturacion,
        c.identificacion,f.fecha_emision,c.nombre as cliente,p.cod_principal,p.cod_alterno,df.p_descuento, 
        u.nombre as ciudad, c.contacto,c.direccion,c.email,c.telefono,p.imagen,p.nombre,p.descripcion,p.caracteristicas,p.normativa,p.uso,
        m.nombre as marca,tm.nombre as tipo_medida,um.nombre as unidad_medida,df.color,df.detalle as detalle_cortina,df.mando,df.alto,df.ancho,df.cantidad,df.precio,df.total as total_pro,df.tiempo_entrega,df.cpc,
        f.subtotal_12 as subtotal_12,f.subtotal_sin_impuesto as subtotal, f.descuento,f.codigo, f.iva_12 as iva, f.valor_total as total, CONCAT(v.nombres, ' ', v.apellidos) as vendedor,
        v.email as mailvendedor,v.telefono as telefono_vendedor,prs.nombre as nombre_presentacion,if(df.p_descuento=0,(df.cantidad*df.precio)*(df.descuento/100),df.descuento) as descuento_prod
        from factura f LEFT JOIN detalle df ON f.id_factura=df.id_factura LEFT JOIN
        cliente c ON c.id_cliente=f.id_cliente LEFT JOIN
        empresa e ON e.id_empresa=f.id_empresa LEFT JOIN
        ciudad u ON u.id_ciudad=e.id_ciudad LEFT JOIN
        producto p ON df.id_producto=p.id_producto LEFT JOIN
        marca m ON p.id_marca=m.id_marca LEFT JOIN
        tipo_medida tm ON tm.id_tipo_medida=p.id_tipo_medida LEFT JOIN
        unidad_medida um ON um.id_unidad_medida=p.id_unidad_medida LEFT JOIN
        user v ON v.id = f.id_user LEFT JOIN
        presentacion prs ON p.id_presentacion=prs.id_presentacion
        where f.id_factura= " . $id);
        $factura_info = DB::select("SELECT
                                    e.email_empresa ,
                                    e.id_empresa,
                                    e.telefono,
                                    f.lugar_de_entrega,
                                    f.condiciones_de_pago,
                                    f.observacion,
                                    f.fecha_expiracion,
                                    fp.descripcion as forma_pago,
									est.urlweb,
                                    est.id_establecimiento,
                                    e.razon_social,
                                    vd.nombre_vendedor,
                                    vd.email_vendedor,
                                    e.ruc_empresa
                                    FROM factura f
                                    INNER JOIN empresa e
                                    ON e.id_empresa = f.id_empresa
                                    INNER JOIN establecimiento est
                                    ON est.id_empresa = e.id_empresa
                                    LEFT JOIN forma_pagos fp
                                    ON fp.id_forma_pagos = f.id_forma_pagos
                                    LEFT JOIN vendedor vd
                                    ON vd.id_vendedor = f.id_vendedor
                                    WHERE f.id_factura = " . $id);
        if($factura_info[0]->id_empresa==71 && $factura_info[0]->id_establecimiento==45){
            $ruta = constant("DATA_EMPRESA") . $factura_info[0]->id_empresa . '/comprobantes/proforma';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0755, true);
            }
            $id_factura=$id;
            $codigo=$proformas[0]->codigo;
            $factura_info2=$factura_info[0];
            $pdf = \PDF::loadView('pdf/proforma_Artedeko', compact("id_factura", "proformas", "codigo", "factura_info2"));


                return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "{$factura_info[0]->id_empresa}/comprobantes/proforma/{$id_factura}.pdf")->stream("{$id_factura}.pdf");

        }else{
            //TECHCOMP ruc 1792684706001
            if($proformas[0]->ruc_empresa=='1792684706001'){
                $ruta = constant("DATA_EMPRESA") . $factura_info[0]->id_empresa . '/comprobantes/proforma';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0755, true);
                }
                $id_factura=$id;
                $codigo=$proformas[0]->codigo;
                $factura_info2=$factura_info[0];
                $pdf = \PDF::loadView('pdf/proforma_tech', compact("id_factura", "proformas", "codigo", "factura_info2"));


                return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "{$factura_info[0]->id_empresa}/comprobantes/proforma/proforma-{$proformas[0]->identificacion}-{$proformas[0]->fecha_emision}.pdf")->stream("proforma-{$proformas[0]->identificacion}-{$proformas[0]->fecha_emision}.pdf");
            }else{
                $ruta = constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/proforma';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0755, true);
                }
                $reportePdf = new generarReportes();
                $strPDF = $reportePdf->proforma($id, $proformas, $proformas[0]->codigo, $factura_info[0], $generar_en_servidor = true, $ruta);
            }
            
        }
        
        
    }
    public function eliminarprof($id)
    {
        $empresa = DB::table('factura')->select('id_empresa')->where('id_factura', '=', $id)->get();
        //echo $empresa[0]->id_empresa;
        Factura::destroy($id);
        DB::delete("DELETE FROM detalle WHERE id_factura=" . $id);
        unlink(constant("DATA_EMPRESA") . $empresa[0]->id_empresa . '/comprobantes/proforma/' . $id . '.pdf');
    }
    public function abrirprof($id)
    {
        $factura = DB::select("SELECT * FROM factura WHERE id_factura = " . $id);
        $productos = DB::select("SELECT d.*, p.cod_principal, p.cod_alterno, p.total_ice, p.categoria FROM detalle d INNER JOIN producto p ON p.id_producto=d.id_producto WHERE d.id_factura = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);

        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'cliente' => $cliente[0],

        ];
    }
    public function correoproforma(Request $request)
    {
        $id = $request->id;
        $tipo = $request->tipo;
        $nombre = "";
        if ($tipo == 1) {
            $datos = DB::select("SELECT e.*, c.email,c.identificacion , c.nombre,f.valor_total,f.codigo,f.fecha_emision  FROM factura f INNER JOIN cliente c ON f.id_cliente = c.id_cliente INNER JOIN empresa e ON e.id_empresa = f.id_empresa WHERE id_factura = $id");
            $nombre = $datos[0]->nombre;
        } else {
            $datos =  DB::select("SELECT e.*, f.respuesta AS email, f.migo AS nombre,f.valor_total,f.codigo,f.fecha_emision,c.identificacion  FROM factura f INNER JOIN cliente c ON f.id_cliente = c.id_cliente INNER JOIN empresa e ON e.id_empresa = f.id_empresa WHERE id_factura = $id");
            $nombre = $request->nombrecliente;
            $datos[0]->email = $request->correocliente;
            $datos[0]->nombre = $request->nombrecliente;
        }
        
        self::crearproformapdf($id, $datos[0]->id_empresa);
        $email = new sendEmail();
        $email->enviarProforma($datos[0], $id, $nombre, $tipo);
    }
    public function correoproformaDirecto($id, $tipo)
    {
        try {
            $nombre = "";
            $datos = DB::select("SELECT e.*, c.email , c.nombre,c.identificacion,f.valor_total,f.codigo,f.fecha_emision  FROM factura f INNER JOIN cliente c ON f.id_cliente = c.id_cliente INNER JOIN empresa e ON e.id_empresa = f.id_empresa WHERE id_factura = $id");
            $nombre = $datos[0]->nombre;
            self::crearproformapdf($id, $datos[0]->id_empresa);
            $email = new sendEmail();
            
            $email->enviarProforma($datos[0], $id, $nombre, $tipo);
        } catch (\Exception $e) {
            return "ERROR al enviar al correo";
        }
    }
    /*--------------------------------------Funciones Proforma FIN--------------------------------------*/
    public function listarretenciones(Request $request)
    {
        $rec = Retencion::select("*")->where("id_empresa", "=", $request->empresa)->get();
        return $rec;
    }
    public function listarpretenciones($id)
    {
        $rec = Retencion_factura::select("*")->where("id_factura", "=", $id)->get();
        return $rec;
    }
    public function abrircreditosp($id)
    {
        $rec = Factura_pagos::select("*")->where("id_factura", "=", $id)->where("estado", "=", 2)->get();
        return $rec[0];
    }
    public function abrirpagosp($id)
    {
        $rec1 = Factura_pagos::select("total as monto", "id_forma_pagos as metodo_pago", "total as cantidad_pago")->where("estado", "=", 1)->where("id_factura", "=", $id)->get();
        return $rec1;
    }
    public function traercliente($id)
    {

        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente =" . $id);
        return $cliente;
    }
    public function getVendedores($id)
    {
        //$user=DB::select("SELECT * from user where id=$id");
        $data = DB::select("SELECT * FROM user WHERE id_empresa =" . $id);

        return [
            'recupera' => $data
        ];
    }
    public function getVendedoresVendAdmin($id)
    {
        $data = DB::select("SELECT * FROM vendedor WHERE id_empresa =" . $id);

        return [
            'recupera' => $data
        ];
    }
    public function getVendedoresVend($id)
    {
        $data = DB::select("SELECT * FROM vendedor WHERE id_user =" . $id);

        return [
            'recupera' => $data
        ];
    }
    public function getClientes($id)
    {
        $data = DB::select("SELECT * FROM cliente WHERE id_empresa =" . $id);

        return [
            'recupera' => $data
        ];
    }
    public function reportestotales(Request $request)
    {
        // dd($request);
        $queries = [];
        $retenciones_q = [];
        // if ($request->rol_user == 2) {
        //     $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->user);
        //     if ($vnd) {
        //         array_push($queries, "f.id_vendedor = {$vnd[0]->id_vendedor}\n");
        //     }
        // } else {
        if ($request->vendedor) {
            $info_seller = json_decode($request->vendedor, true);
            if ($info_seller["id"] != 0) {
                array_push($queries, "f.id_vendedor = {$info_seller["id"]}\n");
            }
        }
        //}
        if ($request->client) {
            $info_client = json_decode($request->client, true);
            if ($info_client["id"] != 0) {
                array_push($queries, "f.id_cliente = {$info_client["id"]}\n");
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
        //if ($request->rol_user !== "2" || $request->rol_user !== 2) {
            if ($request->seller) {
                $info_seller = json_decode($request->seller, true);
                if ($info_seller["id"] != 0) {
                    array_push($queries, "(f.created_by = {$info_seller["id"]})\n");
                }
            }
        //}
        // if ($request->rol_user !== "2") {


        //     if ($request->seller) {

        //         $info_seller = json_decode($request->seller, true);

        //         if ($info_seller["id"] != 0) {

        //             array_push($queries, "(f.created_by = {$info_seller["id"]})\n");

        //         }
        //         // else{
        //         //     if ($vnd) {
        //         //         array_push($queries, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
        //         //                                 (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
        //         //         array_push($pagos, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
        //         //                                 (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
        //         //         $nombre_vendedor=$vnd[0]->nombre_vendedor;
        //         //     }
        //         // }
        //     }
        // } else {
        //     //dd("Entro al vendedor");
        //     $vnd = DB::select("SELECT *,concat(nombres,' ',apellidos) as nombre_vendedor from user where id=" . $request->user);
        //     if (count($vnd)>0) {


        //array_push($queries, "(f.created_by = {$vnd[0]->id})\n");
        //         //             array_push($pagos, "ctap.ucrea = {$vnd[0]->id}\n");
        //         //             array_push($solo_pagos, "ctap.ucrea = {$vnd[0]->id}\n");
        //         //             $nombre_vendedor=$vnd[0]->nombre_vendedor;
        //         // 
        //     }
        // }


        $nombre_producto = '';
        if ($request->products) {
            $to_array = function ($product) {

                $new_product = json_decode($product);

                return $new_product->id;
            };
            //$new_product = json_decode($request->products);
            //dd($new_product->id);
            // if($new_product->id!==0){
            //     array_push($queries, "df.id_producto in ({$info_products})\n");
            // }
            //dd($request->products);
            if (gettype($request->products) !== 'string') {
                if (count($request->products) == 1) {
                    $to_array_2 = function ($product) {
                        $new_product = json_decode($product);
                        return $new_product->nombre;
                    };
                    $info_products_2 = implode(",", array_map($to_array_2, $request->products));
                    $nombre_producto = $info_products_2;
                }

                $info_products = implode(",", array_map($to_array, $request->products));
                //dd($info_products);
                if ($info_products !== "0") {
                    array_push($queries, "df.id_producto in ({$info_products})\n");
                }
            } else {
                //dd("es string");
            }
        }
        if ($request->presentacion) {
            $info_presentacion = json_decode($request->presentacion);
            if ($info_presentacion->id != 0) {
                array_push($queries, "pr.id_presentacion={$info_presentacion->id}\n");
            }
        }
        if ($request->model) {
            $info_model = json_decode($request->model);
            if ($info_model->id != 0) {
                array_push($queries, "md.id_modelo={$info_model->id}\n");
            }
        }
        if ($request->marca) {
            $info_marca = json_decode($request->marca);
            if ($info_marca->id != 0) {
                array_push($queries, "mc.id_marca={$info_marca->id}\n");
            }
        }
        if ($request->tipo_producto) {
            $info_tipo_producto = json_decode($request->tipo_producto);
            if ($info_tipo_producto->id != 0) {
                array_push($queries, "tp.id_tipo_producto={$info_tipo_producto->id}\n");
            }
        }
        if ($request->linea_producto) {
            $info_linea_producto = json_decode($request->linea_producto);
            if ($info_linea_producto->id != 0) {
                array_push($queries, "lp.id_linea_producto={$info_linea_producto->id}\n");
            }
        }
        if ($request->grupoCliente) {
            $info_grupo_cliente = json_decode($request->grupoCliente);
            if ($info_grupo_cliente->id != 0) {
                array_push($queries, "c.id_grupo_cliente={$info_grupo_cliente->id}\n");
            }
        }
        if ($request->tipoCliente) {
            $info_tipo_cliente = json_decode($request->tipoCliente);
            if ($info_tipo_cliente->id != 0) {
                array_push($queries, "c.id_tipo_cliente={$info_tipo_cliente->id}\n");
            }
        }
        if ($request->grupoTributario) {
            $info_grupo_tributario = json_decode($request->grupoTributario);
            if ($info_grupo_tributario->id != 0) {
                array_push($queries, "c.grupo_tributario='{$info_tipo_cliente->name}'\n");
            }
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
                array_push($queries, "f.valor_total {$typeSearchTotalCount} {$request->totalCount}\n");
            }
        }
        if ($request->selectedEstablishment) {
            $info_establishment = json_decode($request->selectedEstablishment);
            if ($info_establishment->id != 0) {
                array_push($queries, "f.id_establecimiento = {$info_establishment->id}\n");
            }
        }
        if ($request->selectedPointOfEmission) {
            $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
            if ($info_pointOfEmission->id != 0) {
                array_push($queries, "f.id_punto_emision = {$info_pointOfEmission->id}\n");
            }
        }
        if ($request->selectedProject) {
            $info_project = json_decode($request->selectedProject);
            if ($info_project->id != 0) {
                array_push($queries, "df.id_proyecto = {$info_project->id}\n");
            }
        }
        if ($request->date) {
            if (!is_null(json_decode($request->date))) {
                $request->date = json_decode($request->date);
                $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                array_push($queries, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
                array_push($retenciones_q, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
            } else {
                $date = str_replace("-010-", "-10-", $request->date);
                array_push($queries, "date(f.fecha_emision) = date('{$date}')\n");
                array_push($retenciones_q, "date(f.fecha_emision) = date('{$date}')\n");
            }
        }
        if ($request->all === "false") {
            if ($request->retention === "true") {
                array_push($queries, "exists (select * from retencion_factura rf where rf.id_factura = f.id_factura)\n");
            }
            if ($request->credit === "true") {
                array_push($queries, "exists (SELECT * FROM ctas_cobrar cxc where cxc.id_factura = f.id_factura)\n");
            }
        }
        // if($request->){

        // }
        $queries = implode(" and ", $queries);
        $retenciones_q = implode(" and ", $retenciones_q);
        $modo = "";
        if ($request->modo == "0") {
            $modo = "  and f.codigo is not null  ";
        } else {
            $modo = "  and f.modo= 1  ";
        }
        $modo_fac = $request->modo == "0" ? 0 : 1;
        $estado_factura = "";
        if ($request->modo !== "0") {
            $estado_factura = "  ";
        }
        $query = "
        SELECT
        f.*,
        f.created_by as fcreated_by,
        f.updated_by as fupdated_by,
        f.id_vendedor as fid_vendedor,
        cxc.id_ctascobrar,
        cxc.num_cuota,
        cxc.fecha_pago,
        cxc.periodo_pagos,
        cxc.valor_cuota,
        cxc.pagos_por,
        cxc.id_forma_pagos as id_forma_pagos_cxc,
        cxc.id_banco,
        cxc.numero_tarjeta,
        cxc.numero_transaccion,
        cxc.descuento as descuento_cxc,
        cxc.valor_pagado,
        cxc.estado as estado_cxc,
        cxc.tipo,
        cxc.abono,
        cxc.fcrea as fcrea_cxc,
        cxc.fmodifica as fcrea_cxc,
        cxc.ucrea as ucrea_cxc,
        cxc.umodifica as umodifica_cxc,
        rf.cantidadiva,
        rf.cantidadrenta,
        c.identificacion,
        c.nombre,
        e.id_empresa as idempresa,
        e.nombre_empresa,
        e.logo,
        vd.nombre_vendedor as vendedor
        FROM factura f
        inner join detalle df
        on df.id_factura = f.id_factura
        inner join producto prod
        on df.id_producto = prod.id_producto
        left join linea_producto as lp
        on lp.id_linea_producto=prod.id_linea_producto
        left join tipo_producto as tp
        on tp.id_tipo_producto=prod.id_tipo_producto
        left join marca as mc
        on mc.id_marca=prod.id_marca
        left join modelo as md
        on md.id_modelo=prod.id_modelo
        left join presentacion as pr
        on pr.id_presentacion=prod.id_presentacion
        left join vendedor vd
        on vd.id_vendedor = f.id_vendedor
        inner join cliente c
        on f.id_cliente = c.id_cliente
        inner join empresa e
        on f.id_empresa = e.id_empresa
        inner join user u
        on f.id_user = u.id
        left join ctas_cobrar cxc
        on f.id_factura = cxc.id_factura
        left join retencion_factura rf
        on f.id_factura = rf.id_factura
            WHERE {$queries}
            {$modo}
            and f.id_empresa={$request->company}
            {$estado_factura}
            order by f.fecha_emision,f.clave_acceso asc;
            ";
        //dd($query);
        $reporte = DB::select($query);
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            foreach ($reporte as $report) {
                if ($report->fcreated_by == session()->get('usuariosesion')['id'] || $report->fupdated_by == session()->get('usuariosesion')['id'] || $report->fid_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $report);
                }
            }
            $reporte = $dat;
        }
        // $valores_ice=DB::select("SELECT
        //     if(max(d.valor_ice)>0,ROUND(sum(d.valor_ice*d.cantidad),2),ROUND(sum(p.total_ice*d.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
        // FROM detalle d
        // INNER JOIN producto p ON p.id_producto=d.id_producto
        // LEFT JOIN ice ON ice.id_ice = p.ice
        // INNER JOIN factura f ON f.id_factura=d.id_factura
        // WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
        // GROUP BY f.id_factura");
        $valores_ice = DB::select("SELECT
                ROUND((prod.total_ice*df.cantidad),2) as total_ice,f.clave_acceso,f.id_factura
            FROM detalle df
            INNER JOIN producto prod ON prod.id_producto=df.id_producto
            left join linea_producto as lp
            on lp.id_linea_producto=prod.id_linea_producto
            left join tipo_producto as tp
            on tp.id_tipo_producto=prod.id_tipo_producto
            left join marca as mc
            on mc.id_marca=prod.id_marca
            left join modelo as md
            on md.id_modelo=prod.id_modelo
            left join presentacion as pr
            on pr.id_presentacion=prod.id_presentacion
            LEFT JOIN ice ON ice.id_ice = prod.ice
            INNER JOIN factura f ON f.id_factura=df.id_factura
            WHERE {$queries} and f.id_empresa={$request->company} {$modo} {$estado_factura}
        ");

        $retenciones = DB::select("SELECT sum(cantidadiva) as cantidad_iva,sum(cantidadrenta) as cantidad_irf,f.id_factura 
        from factura as f
        inner join cliente c
        on f.id_cliente = c.id_cliente
        LEFT JOIN retencion_factura
        on retencion_factura.id_factura=f.id_factura
        WHERE f.id_empresa = {$request->company} and {$retenciones_q}
        GROUP BY f.id_factura");
        // dd("SELECT
        //             if((df.valor_ice)>0,ROUND((df.valor_ice*df.cantidad),2),ROUND((p.total_ice*df.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
        //         FROM detalle df
        //         INNER JOIN producto p ON p.id_producto=df.id_producto
        //         LEFT JOIN ice ON ice.id_ice = p.ice
        //         INNER JOIN factura f ON f.id_factura=df.id_factura
        //         WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
        //     ");
        //dd($reporte);
        $Reportes = new generarReportes();
        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            if (property_exists($request->date, 'initialDate')) {
                $strPDF = $Reportes->factura_reporte($reporte, $modo_fac, $valores_ice, $date_initial, $date_final, $nombre_producto, $retenciones);
            } else {
                $strPDF = $Reportes->factura_reporte($reporte, $modo_fac, $valores_ice, $request->date, $request->date, $nombre_producto, $retenciones);
            }
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }

    public function reportesproductovscostos(Request $request)
    {

        $queries = [];
        $retenciones_q = [];
        if ($request->vendedor) {
            $info_seller = json_decode($request->vendedor, true);
            if ($info_seller["id"] != 0) {
                array_push($queries, "f.id_vendedor = {$info_seller["id"]}\n");
            }
        }
        if ($request->client) {
            $info_client = json_decode($request->client, true);
            if ($info_client["id"] != 0) {
                array_push($queries, "f.id_cliente = {$info_client["id"]}\n");
            }
        }
        
            if ($request->seller) {
                $info_seller = json_decode($request->seller, true);
                if ($info_seller["id"] != 0) {
                    array_push($queries, "(f.created_by = {$info_seller["id"]})\n");
                }
            }

        
        if ($request->presentacion) {
            $info_presentacion = json_decode($request->presentacion);
            if ($info_presentacion->id != 0) {
                array_push($queries, "pr.id_presentacion={$info_presentacion->id}\n");
            }
        }
        if ($request->model) {
            $info_model = json_decode($request->model);
            if ($info_model->id != 0) {
                array_push($queries, "md.id_modelo={$info_model->id}\n");
            }
        }
        if ($request->marca) {
            $info_marca = json_decode($request->marca);
            if ($info_marca->id != 0) {
                array_push($queries, "mc.id_marca={$info_marca->id}\n");
            }
        }
        if ($request->tipo_producto) {
            $info_tipo_producto = json_decode($request->tipo_producto);
            if ($info_tipo_producto->id != 0) {
                array_push($queries, "tp.id_tipo_producto={$info_tipo_producto->id}\n");
            }
        }
        if ($request->linea_producto) {
            $info_linea_producto = json_decode($request->linea_producto);
            if ($info_linea_producto->id != 0) {
                array_push($queries, "lp.id_linea_producto={$info_linea_producto->id}\n");
            }
        }
        if ($request->grupoCliente) {
            $info_grupo_cliente = json_decode($request->grupoCliente);
            if ($info_grupo_cliente->id != 0) {
                array_push($queries, "c.id_grupo_cliente={$info_grupo_cliente->id}\n");
            }
        }
        if ($request->tipoCliente) {
            $info_tipo_cliente = json_decode($request->tipoCliente);
            if ($info_tipo_cliente->id != 0) {
                array_push($queries, "c.id_tipo_cliente={$info_tipo_cliente->id}\n");
            }
        }
        if ($request->grupoTributario) {
            $info_grupo_tributario = json_decode($request->grupoTributario);
            if ($info_grupo_tributario->id != 0) {
                array_push($queries, "c.grupo_tributario='{$info_tipo_cliente->name}'\n");
            }
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
                array_push($queries, "f.valor_total {$typeSearchTotalCount} {$request->totalCount}\n");
            }
        }
        if ($request->selectedEstablishment) {
            $info_establishment = json_decode($request->selectedEstablishment);
            if ($info_establishment->id != 0) {
                array_push($queries, "f.id_establecimiento = {$info_establishment->id}\n");
            }
        }
        if ($request->selectedPointOfEmission) {
            $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
            if ($info_pointOfEmission->id != 0) {
                array_push($queries, "f.id_punto_emision = {$info_pointOfEmission->id}\n");
            }
        }
        if ($request->selectedProject) {
            $info_project = json_decode($request->selectedProject);
            if ($info_project->id != 0) {
                array_push($queries, "df.id_proyecto = {$info_project->id}\n");
            }
        }
        if ($request->date) {
            if (!is_null(json_decode($request->date))) {
                $request->date = json_decode($request->date);
                $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                array_push($queries, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
                array_push($retenciones_q, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
            } else {
                $date = str_replace("-010-", "-10-", $request->date);
                array_push($queries, "date(f.fecha_emision) = date('{$date}')\n");
                array_push($retenciones_q, "date(f.fecha_emision) = date('{$date}')\n");
            }
        }
        $queries = implode(" and ", $queries);
        $retenciones_q = implode(" and ", $retenciones_q);

        $producto_is_array = is_array($request->products);
        $producto_all = false;
        $productos = $request->products;

        if(!$producto_is_array){
            $productos = json_decode($request->products);
            $producto_all = true;
        }
        else{
            for($i=0; $i<count($productos); $i++){
                if(json_decode($productos[$i])->id == 0){
                    $producto_all = true;
                }
            }
        }

        //inicializacion del array que guardara los datos
        $lista = [];

        //en caso de que se haya seleccionado todos los productos
        if($producto_all)
        {

            $query = "SELECT pr.cod_principal AS codigo, pr.nombre AS nombre, d.cantidad AS cantidad, d.total AS venta, 
                pr.id_producto as id_producto,
                c.identificacion,
                c.nombre as nombre_cliente,
                e.id_empresa as id_empresa,
                e.nombre_empresa,
                e.logo,
                vd.nombre_vendedor as vendedor,
                be.id_bodega_egreso
                FROM factura f
                LEFT JOIN bodega_egreso be
                ON f.id_factura=be.id_factura
                INNER JOIN detalle d ON f.id_factura=d.id_factura 
                INNER JOIN producto pr ON d.id_producto=pr.id_producto 
                left join vendedor vd
                on vd.id_vendedor = f.id_vendedor
                inner join cliente c
                on f.id_cliente = c.id_cliente
                inner join empresa e
                on f.id_empresa = e.id_empresa
                inner join user u
                on f.id_user = u.id
                WHERE {$queries}
                and f.id_empresa={$request->company} 
                AND f.estado=1 
                order by f.fecha_emision,f.clave_acceso asc;";

            $data = DB::select($query);

            //inicializacion del array que guardara los id de bodega_egreso
            $bodega_egreso_array = [];

            //recorrido de los datos de la consulta, inicializa los costos y utilidades, ademas guarda los id de bodega_egreso en el arreglo
            foreach($data as $list){
                $list->costo = 0;
                $list->utilidad = 0;
                $list->utilidad_porcent = 100;
                
                array_push($bodega_egreso_array, $list->id_bodega_egreso);
            }

            //convierte el array de los id de bodega_egreso en un string para la consulta de los costos
            $bodega_egreso_string = implode("','", $bodega_egreso_array);

            //consulta de costos
            $query2="SELECT bed.costo_total AS costo, id_producto, id_bodega_egreso 
                FROM bodega_egreso_detalle bed
                WHERE bed.id_bodega_egreso IN('" . $bodega_egreso_string . "')";
            $data2 = DB::select($query2);

            //agrupacion de productos en el array lista segun codigo de producto
            foreach($data as $dat){
                $index = null;

                for($i=0; $i<count($lista); $i++){
                    if($lista[$i]->codigo == $dat->codigo){
                        $index = $i;
                    }
                }

                if($index === null){
                    array_push($lista, $dat);
                }

                else{
                    $lista[$index]->cantidad = $lista[$index]->cantidad + $dat->cantidad;
                    $lista[$index]->venta = $lista[$index]->venta + $dat->venta;
                }
            }

            //anexo de costos en la lista de productos
            foreach($data2 as $dat2){
                foreach($lista as $dat){
                    if($dat->id_producto==$dat2->id_producto){
                        $dat->costo = $dat->costo + $dat2->costo;
                    }
                }
            }

            //calculo de utilidades en la lista de productos
            foreach($lista as $list){
                $list->utilidad = $list->venta - $list->costo;
                if($list->costo>0){
                    $list->utilidad_porcent = round(($list->utilidad/$list->costo*100), 2);
                }
            }

        }
        //en caso de que sean productos especificos
        else
        {

            //inicializacion del array de los id de productos
            $productos_array = [];

            //llenado del array de los id de productos
            for($i=0; $i<count($productos); $i++){
                array_push($productos_array, json_decode($productos[$i])->id);
            }

            //conversion a string del array de id de productos para usarlo en la consulta principal
            $productos_string = implode("','", $productos_array);

            //consulta principal
            $query = "SELECT pr.cod_principal AS codigo, pr.nombre AS nombre, d.cantidad AS cantidad, d.total AS venta, 
                pr.id_producto as id_producto,
                c.identificacion,
                c.nombre as nombre_cliente,
                e.id_empresa as id_empresa,
                e.nombre_empresa,
                e.logo,
                vd.nombre_vendedor as vendedor,
                be.id_bodega_egreso
                FROM factura f
                LEFT JOIN bodega_egreso be
                ON f.id_factura=be.id_factura
                INNER JOIN detalle d ON f.id_factura=d.id_factura 
                INNER JOIN producto pr ON d.id_producto=pr.id_producto 
                left join vendedor vd
                on vd.id_vendedor = f.id_vendedor
                inner join cliente c
                on f.id_cliente = c.id_cliente
                inner join empresa e
                on f.id_empresa = e.id_empresa
                inner join user u
                on f.id_user = u.id
                WHERE {$queries}
                and f.id_empresa={$request->company} 
                AND f.estado=1 
                AND d.id_producto IN('" . $productos_string . "') 
                order by f.fecha_emision,f.clave_acceso asc;";

            $data = DB::select($query);

            //inicializacion del array que guardara los id de bodega_egreso
            $bodega_egreso_array = [];

            //recorrido de los datos de la consulta, inicializa los costos y utilidades, ademas guarda los id de bodega_egreso en el arreglo
            foreach($data as $list){
                $list->costo = 0;
                $list->utilidad = 0;
                $list->utilidad_porcent = 100;
                
                array_push($bodega_egreso_array, $list->id_bodega_egreso);
            }

            //convierte el array de los id de bodega_egreso en un string para la consulta de los costos
            $bodega_egreso_string = implode("','", $bodega_egreso_array);

            //consulta de costos
            $query2="SELECT bed.costo_total AS costo, id_producto, id_bodega_egreso 
                FROM bodega_egreso_detalle bed
                WHERE bed.id_bodega_egreso IN('" . $bodega_egreso_string . "')";
            $data2 = DB::select($query2);

            //agrupacion de productos en el array lista segun codigo de producto
            foreach($data as $dat){
                $index = null;

                for($i=0; $i<count($lista); $i++){
                    if($lista[$i]->codigo == $dat->codigo){
                        $index = $i;
                    }
                }

                if($index === null){
                    array_push($lista, $dat);
                }

                else{
                    $lista[$index]->cantidad = $lista[$index]->cantidad + $dat->cantidad;
                    $lista[$index]->venta = $lista[$index]->venta + $dat->venta;
                }
            }

            //anexo de costos en la lista de productos
            foreach($data2 as $dat2){
                foreach($lista as $dat){
                    if($dat->id_producto==$dat2->id_producto){
                        $dat->costo = $dat->costo + $dat2->costo;
                    }
                }
            }

            //calculo de utilidades en la lista de productos
            foreach($lista as $list){
                $list->utilidad = $list->venta - $list->costo;
                if($list->costo>0){
                    $list->utilidad_porcent = round(($list->utilidad/$list->costo*100), 2);
                }
            }

        }

        if ($request->date) {
            if (is_object($request->date)) {
                $date_initial = $request->date->initialDate;
                $date_final = $request->date->finalDate;
            } else {
                $date = $request->date;
                $date_initial = $request->date;
                $date_final = $request->date;
            }
        }

        $Reportes = new generarReportes();
        $strPDF = $Reportes->producto_vs_costos_reporte($lista, $date_initial, $date_final);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        
    }

    public function reportesfacturavscostos(Request $request)
    {

        $queries = [];
        $retenciones_q = [];
        if ($request->vendedor) {
            $info_seller = json_decode($request->vendedor, true);
            if ($info_seller["id"] != 0) {
                array_push($queries, "f.id_vendedor = {$info_seller["id"]}\n");
            }
        }
        if ($request->client) {
            $info_client = json_decode($request->client, true);
            if ($info_client["id"] != 0) {
                array_push($queries, "f.id_cliente = {$info_client["id"]}\n");
            }
        }
        
            if ($request->seller) {
                $info_seller = json_decode($request->seller, true);
                if ($info_seller["id"] != 0) {
                    array_push($queries, "(f.created_by = {$info_seller["id"]})\n");
                }
            }

        
        if ($request->presentacion) {
            $info_presentacion = json_decode($request->presentacion);
            if ($info_presentacion->id != 0) {
                array_push($queries, "pr.id_presentacion={$info_presentacion->id}\n");
            }
        }
        if ($request->model) {
            $info_model = json_decode($request->model);
            if ($info_model->id != 0) {
                array_push($queries, "md.id_modelo={$info_model->id}\n");
            }
        }
        if ($request->marca) {
            $info_marca = json_decode($request->marca);
            if ($info_marca->id != 0) {
                array_push($queries, "mc.id_marca={$info_marca->id}\n");
            }
        }
        if ($request->tipo_producto) {
            $info_tipo_producto = json_decode($request->tipo_producto);
            if ($info_tipo_producto->id != 0) {
                array_push($queries, "tp.id_tipo_producto={$info_tipo_producto->id}\n");
            }
        }
        if ($request->linea_producto) {
            $info_linea_producto = json_decode($request->linea_producto);
            if ($info_linea_producto->id != 0) {
                array_push($queries, "lp.id_linea_producto={$info_linea_producto->id}\n");
            }
        }
        if ($request->grupoCliente) {
            $info_grupo_cliente = json_decode($request->grupoCliente);
            if ($info_grupo_cliente->id != 0) {
                array_push($queries, "c.id_grupo_cliente={$info_grupo_cliente->id}\n");
            }
        }
        if ($request->tipoCliente) {
            $info_tipo_cliente = json_decode($request->tipoCliente);
            if ($info_tipo_cliente->id != 0) {
                array_push($queries, "c.id_tipo_cliente={$info_tipo_cliente->id}\n");
            }
        }
        if ($request->grupoTributario) {
            $info_grupo_tributario = json_decode($request->grupoTributario);
            if ($info_grupo_tributario->id != 0) {
                array_push($queries, "c.grupo_tributario='{$info_tipo_cliente->name}'\n");
            }
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
                array_push($queries, "f.valor_total {$typeSearchTotalCount} {$request->totalCount}\n");
            }
        }
        if ($request->selectedEstablishment) {
            $info_establishment = json_decode($request->selectedEstablishment);
            if ($info_establishment->id != 0) {
                array_push($queries, "f.id_establecimiento = {$info_establishment->id}\n");
            }
        }
        if ($request->selectedPointOfEmission) {
            $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
            if ($info_pointOfEmission->id != 0) {
                array_push($queries, "f.id_punto_emision = {$info_pointOfEmission->id}\n");
            }
        }
        if ($request->selectedProject) {
            $info_project = json_decode($request->selectedProject);
            if ($info_project->id != 0) {
                array_push($queries, "df.id_proyecto = {$info_project->id}\n");
            }
        }
        if ($request->date) {
            if (!is_null(json_decode($request->date))) {
                $request->date = json_decode($request->date);
                $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                array_push($queries, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
                array_push($retenciones_q, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
            } else {
                $date = str_replace("-010-", "-10-", $request->date);
                array_push($queries, "date(f.fecha_emision) = date('{$date}')\n");
                array_push($retenciones_q, "date(f.fecha_emision) = date('{$date}')\n");
            }
        }
        $queries = implode(" and ", $queries);
        $retenciones_q = implode(" and ", $retenciones_q);

        $query = "SELECT SUBSTR(f.clave_acceso, 25, 15) AS factura, f.fecha_emision AS fecha, c.nombre AS nombre_cliente, subtotal_sin_impuesto AS valor,
        c.identificacion,
        e.id_empresa as idempresa,
        e.nombre_empresa,
        e.logo,
        vd.nombre_vendedor as vendedor,
        be.id_bodega_egreso,
        (SELECT SUM(bed.costo_total)
                    FROM bodega_egreso_detalle bed  
                    WHERE bed.id_bodega_egreso=be.id_bodega_egreso) AS costo
        FROM factura f
        INNER JOIN cliente c
        ON f.id_cliente=c.id_cliente 
        LEFT JOIN bodega_egreso be
        ON f.id_factura=be.id_factura 
        left join vendedor vd
        on vd.id_vendedor = f.id_vendedor
        inner join empresa e
        on f.id_empresa = e.id_empresa
        inner join user u
        on f.id_user = u.id 
        WHERE
        {$queries}
        and f.id_empresa={$request->company} 
        AND f.estado=1
        order by f.fecha_emision,f.clave_acceso asc;";
        //dd($query);

        $lista = [];

        $data = DB::select($query);

        if(count($data)>0){

            foreach ($data as $dat) {

                $format = (object) array();
                $format->factura = "";
                $format->fecha = "";
                $format->cliente = "";
                $format->valor = 0;
                $format->costo = 0;
                $format->utilidad = 0;
                $format->utilidad_porcent = 100;

                $format->identificacion = "";
                $format->nombre_cliente = "";
                $format->id_empresa = "";
                $format->nombre_empresa = "";
                $format->logo = "";
                $format->vendedor = "";

                $format->factura = $dat->factura;
                $format->fecha = $dat->fecha;
                $format->cliente = $dat->nombre_cliente;
                $format->valor = $dat->valor;
                //$format->costo = 0;
                $format->costo = $dat->costo;

                /*if(isset($dat->id_bodega_egreso)){

                    $query2 = "SELECT bed.costo_total as costo 
                    FROM bodega_egreso_detalle bed 
                    INNER JOIN bodega_egreso be 
                    ON bed.id_bodega_egreso=be.id_bodega_egreso 
                    WHERE bed.id_bodega_egreso=" . $dat->id_bodega_egreso;

                    $data2 = DB::select($query2);

                    foreach ($data2 as $dat2) {
                        $format->costo = $format->costo + $dat2->costo;
                    }

                    $format->utilidad = $format->valor - $format->costo;
                    $format->utilidad_porcent = round(($format->utilidad/$format->costo*100), 2);

                }

                else{
                    $format->utilidad = $format->valor;
                    $format->utilidad_porcent = 100;
                }*/

                if($format->costo > 0){

                    $format->utilidad = $format->valor - $format->costo;
                    $format->utilidad_porcent = round(($format->utilidad/$format->costo*100), 2);

                }

                else{
                    $format->utilidad = $format->valor;
                    $format->utilidad_porcent = 100;
                }

                $format->identificacion = $dat->identificacion;
                $format->nombre_cliente = $dat->nombre_cliente;
                $format->id_empresa = $dat->idempresa;
                $format->nombre_empresa = $dat->nombre_empresa;
                $format->logo = $dat->logo;
                $format->vendedor = $dat->vendedor;

                array_push($lista, $format);
            }

        }

        if ($request->date) {
            if (is_object($request->date)) {
                $date_initial = $request->date->initialDate;
                $date_final = $request->date->finalDate;
            } else {
                $date = $request->date;
                $date_initial = $request->date;
                $date_final = $request->date;
            }
        }

        $Reportes = new generarReportes();
        $strPDF = $Reportes->factura_vs_costos_reporte($lista, $date_initial, $date_final);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        
    }

    public function reportesvendedor(Request $request)
    {

        $queries = [];
        if ($request->vendedor) {
            $info_seller = json_decode($request->vendedor, true);
            if ($info_seller["id"] != 0) {
                array_push($queries, "f.id_vendedor = {$info_seller["id"]}\n");
            }
        }

        if ($request->date) {
            if (!is_null(json_decode($request->date))) {
                $request->date = json_decode($request->date);
                $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                array_push($queries, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
            } else {
                $date = str_replace("-010-", "-10-", $request->date);
                array_push($queries, "date(f.fecha_emision) = date('{$date}')\n");
            }
        }
        $queries = implode(" and ", $queries);

        $lista = [];

        $query = "SELECT SUBSTR(f.clave_acceso, 25, 15) AS factura, f.fecha_emision AS fecha, 'Factura' AS tipo, (f.subtotal_12+f.subtotal_0) AS subtotal, f.iva_12 AS iva, f.valor_total AS valor, (SELECT SUM(fp.total) FROM factura_pagos fp WHERE fp.id_factura=f.id_factura) AS cobrado, v.nombre_vendedor AS vendedor, c.nombre AS cliente, v.id_empresa AS id_empresa, e.logo AS logo, e.nombre_empresa AS nombre_empresa 
        FROM factura f 
        INNER JOIN vendedor v 
        ON f.id_vendedor=v.id_vendedor 
        INNER JOIN cliente c
        ON f.id_cliente=c.id_cliente 
        INNER JOIN empresa e
        ON f.id_empresa=e.id_empresa
        WHERE f.modo=1
        AND {$queries} 
        order by f.fecha_emision asc;";

        $lista = DB::select($query);

        $query2 = "SELECT SUBSTR(f.clave_acceso, 25, 15) AS factura, f.fecha_emision AS fecha, 'Nota de Venta' AS tipo, f.subtotal_sin_impuesto AS subtotal, f.iva_12 AS iva, f.valor_total AS valor, (SELECT SUM(fp.total) FROM nota_venta_pagos fp WHERE fp.id_nota_venta=f.id_nota_venta) AS cobrado, v.nombre_vendedor AS vendedor, c.nombre AS cliente, v.id_empresa AS id_empresa, e.logo AS logo, e.nombre_empresa AS nombre_empresa 
        FROM nota_venta f 
        INNER JOIN vendedor v 
        ON f.id_vendedor=v.id_vendedor
        INNER JOIN cliente c
        ON f.id_cliente=c.id_cliente 
        INNER JOIN empresa e
        ON f.id_empresa=e.id_empresa
        WHERE f.modo=1
        AND {$queries} 
        order by f.fecha_emision asc;";

        $lista2 = DB::select($query2);

        foreach($lista2 as $list){
            $format = (object) array();
            $format->factura = "";
            $format->fecha = "";
            $format->tipo = "";
            $format->subtotal = 0;
            $format->iva = 0;
            $format->valor = 0;
            $format->cobrado = 0;
            $format->vendedor = "";
            $format->cliente = "";
            $format->id_empresa = "";
            $format->logo = "";
            $format->nombre_empresa = "";
        
            $format->factura = $list->factura;
            $format->fecha = $list->fecha;
            $format->tipo = $list->tipo;
            $format->subtotal = $list->subtotal;
            $format->iva = $list->iva;
            $format->valor = $list->valor;
            $format->cobrado = $list->cobrado;
            $format->vendedor = $list->vendedor;
            $format->cliente = $list->cliente;
            $format->id_empresa = $list->id_empresa;
            $format->logo = $list->logo;
            $format->nombre_empresa = $list->nombre_empresa;

            array_push($lista, $format);
        }
        
        $Reportes = new generarReportes();
        $strPDF = $Reportes->vendedor_reporte($lista, $date_initial, $date_final);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        
    }

    public function reportestotales_nota_venta(Request $request)
    {
        $queries = [];
        if ($request->client) {
            $info_client = json_decode($request->client, true);
            if ($info_client["id"] != 0) {
                array_push($queries, "f.id_cliente = {$info_client["id"]}\n");
            }
        }
        if ($request->rol_user !== 2 || $request->rol_user !== "2") {
            if ($request->seller) {
                $info_seller = json_decode($request->seller, true);
                if ($info_seller["id"] != 0) {
                    array_push($queries, "f.id_vendedor = {$info_seller["id"]}\n");
                }
            }
        }
        /*if ($request->rol_user == 2) {
            $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->user);
            if ($vnd) {
                array_push($queries, "f.id_vendedor = {$vnd[0]->id_vendedor}\n");
            }
        } else {
            if ($request->seller) {
                $info_seller = json_decode($request->seller, true);
                if ($info_seller["id"] != 0) {
                    array_push($queries, "f.id_vendedor = {$info_seller["id"]}\n");
                }
            }
        }*/
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
            array_push($queries, "df.id_producto in ({$info_products})\n");
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
                array_push($queries, "f.valor_total {$typeSearchTotalCount} {$request->totalCount}\n");
            }
        }
        if ($request->selectedEstablishment) {
            $info_establishment = json_decode($request->selectedEstablishment);
            if ($info_establishment->id != 0) {
                array_push($queries, "f.id_establecimiento = {$info_establishment->id}\n");
            }
        }
        if ($request->selectedPointOfEmission) {
            $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
            if ($info_pointOfEmission->id != 0) {
                array_push($queries, "f.id_punto_emision = {$info_pointOfEmission->id}\n");
            }
        }
        if ($request->selectedProject) {
            $info_project = json_decode($request->selectedProject);
            if ($info_project->id != 0) {
                array_push($queries, "f.id_proyecto = {$info_project->id}\n");
            }
        }
        if ($request->date) {
            if (!is_null(json_decode($request->date))) {
                $request->date = json_decode($request->date);
                $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                array_push($queries, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
            } else {
                $date = str_replace("-010-", "-10-", $request->date);
                array_push($queries, "date(f.fecha_emision) = date('{$date}')\n");
            }
        }
        if ($request->all === "false") {
            if ($request->retention === "true") {
                array_push($queries, "exists (select * from retencion_nota_venta rf where rf.id_nota_venta = f.id_nota_venta)\n");
            }
            if ($request->credit === "true") {
                array_push($queries, "exists (SELECT * FROM ctas_cobrar cxc where cxc.id_nota_venta = f.id_nota_venta)\n");
            }
        }
        // if($request->){

        // }
        $queries = implode(" and ", $queries);
        $modo = $request->modo == "0" ? 0 : 1;
        $query = "
        SELECT
        f.*,
        f.created_by as fcreated_by,
        f.updated_by as fupdated_by,
        f.id_vendedor as fid_vendedor,
        cxc.id_ctascobrar,
        cxc.num_cuota,
        cxc.fecha_pago,
        cxc.periodo_pagos,
        cxc.valor_cuota,
        cxc.pagos_por,
        cxc.id_forma_pagos as id_forma_pagos_cxc,
        cxc.id_banco,
        cxc.numero_tarjeta,
        cxc.numero_transaccion,
        cxc.descuento as descuento_cxc,
        cxc.valor_pagado,
        cxc.estado as estado_cxc,
        cxc.tipo,
        cxc.abono,
        cxc.fcrea as fcrea_cxc,
        cxc.fmodifica as fcrea_cxc,
        cxc.ucrea as ucrea_cxc,
        cxc.umodifica as umodifica_cxc,
        rf.cantidadiva,
        rf.cantidadrenta,
        c.identificacion,
        c.nombre,
        e.id_empresa as idempresa,
        e.nombre_empresa,
        e.logo,
        vd.nombre_vendedor as vendedor,
        f.id_nota_venta as id_factura
        FROM nota_venta f
        inner join detalle_nota_venta df
        on df.id_nota_venta = f.id_nota_venta
        left join vendedor vd
        on vd.id_vendedor = f.id_vendedor
        inner join cliente c
        on f.id_cliente = c.id_cliente
        inner join empresa e
        on f.id_empresa = e.id_empresa
        inner join user u
        on f.id_user = u.id
        left join ctas_cobrar cxc
        on f.id_nota_venta = cxc.id_nota_venta
        left join retencion_nota_venta rf
            on f.id_nota_venta = rf.id_nota_venta
            WHERE {$queries}
            and f.modo = {$modo}
            and f.id_empresa={$request->company}
            and f.estado>0
            order by f.fecha_emision,f.clave_acceso asc;
            ";
        //dd($query);
        $reporte = DB::select($query);
        if (session()->get('usuariosesion')['filtro_list'] == 1 || session()->get('usuariosesion')['id_rol'] == 2) {
            $dat = [];
            foreach ($reporte as $report) {
                if ($report->fcreated_by == session()->get('usuariosesion')['id'] || $report->fupdated_by == session()->get('usuariosesion')['id'] || $report->fid_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $report);
                }
            }
            $reporte = $dat;
        }
        // $valores_ice=DB::select("SELECT
        //     if(max(d.valor_ice)>0,ROUND(sum(d.valor_ice*d.cantidad),2),ROUND(sum(p.total_ice*d.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
        // FROM detalle d
        // INNER JOIN producto p ON p.id_producto=d.id_producto
        // LEFT JOIN ice ON ice.id_ice = p.ice
        // INNER JOIN factura f ON f.id_factura=d.id_factura
        // WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
        // GROUP BY f.id_factura");
        $valores_ice = DB::select("SELECT
                if((df.valor_ice)>0,ROUND((df.valor_ice),2),ROUND((p.total_ice*df.cantidad),2)) as total_ice,f.clave_acceso,f.id_nota_venta as id_factura
            FROM detalle_nota_venta df
            INNER JOIN producto p ON p.id_producto=df.id_producto
            LEFT JOIN ice ON ice.id_ice = p.ice
            INNER JOIN nota_venta f ON f.id_nota_venta=df.id_nota_venta
            WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
        ");
        // dd("SELECT
        //             if((df.valor_ice)>0,ROUND((df.valor_ice*df.cantidad),2),ROUND((p.total_ice*df.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
        //         FROM detalle df
        //         INNER JOIN producto p ON p.id_producto=df.id_producto
        //         LEFT JOIN ice ON ice.id_ice = p.ice
        //         INNER JOIN factura f ON f.id_factura=df.id_factura
        //         WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
        //     ");
        //dd($reporte);
        $Reportes = new generarReportes();
        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            if (property_exists($request->date, 'initialDate')) {
                $strPDF = $Reportes->nota_venta_reporte($reporte, $modo, $valores_ice, $date_initial, $date_final, $nombre_producto);
            } else {
                $strPDF = $Reportes->nota_venta_reporte($reporte, $modo, $valores_ice, $request->date, $request->date, $nombre_producto);
            }
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
    public function generaProforma(Request $request)
    {
        $query = "SELECT e.logo,e.nombre_empresa,e.ruc_empresa,e.direccion_empresa,
        c.identificacion,f.fecha_emision,c.nombre as cliente,
        u.nombre as ciudad, c.contacto,c.direccion,c.email,c.telefono,p.imagen,p.nombre,p.descripcion,p.caracteristicas,p.normativa,p.uso,p.cod_alterno,
        m.nombre as marca,tm.nombre as tipo_medida,um.nombre as unidad_medida,df.cantidad,df.precio,df.total as total_pro,
        f.subtotal_sin_impuesto as subtotal, f.descuento, f.iva_12 as iva, f.valor_total as total, CONCAT(v.nombres, ' ', v.apellidos) as vendedor,
        v.email as mailvendedor
        from factura f LEFT JOIN detalle df ON f.id_factura=df.id_factura LEFT JOIN
        cliente c ON c.id_cliente=f.id_cliente LEFT JOIN
        empresa e ON e.id_empresa=f.id_empresa LEFT JOIN
        ciudad u ON u.id_ciudad=e.id_ciudad LEFT JOIN
        producto p ON df.id_producto=p.id_producto LEFT JOIN
        marca m ON p.id_marca=m.id_marca LEFT JOIN
        tipo_medida tm ON tm.id_tipo_medida=p.id_tipo_medida LEFT JOIN
        unidad_medida um ON um.id_unidad_medida=p.id_unidad_medida LEFT JOIN
        user v ON v.id = f.id_user
        where f.id_factura= " . $request->id_factura;
        //dd($query);
        $proformas = DB::select("SELECT e.logo,e.nombre_empresa,e.ruc_empresa,e.direccion_empresa,e.email_facturacion,
        c.identificacion,f.fecha_emision,c.nombre as cliente,p.cod_principal,p.cod_alterno,df.p_descuento, 
        u.nombre as ciudad, c.contacto,c.direccion,c.email,c.telefono,p.imagen,p.nombre,p.descripcion,p.caracteristicas,p.normativa,p.uso,
        m.nombre as marca,tm.nombre as tipo_medida,um.nombre as unidad_medida,df.color,df.detalle as detalle_cortina,df.mando,df.alto,df.ancho,df.cantidad,df.precio,df.total as total_pro,df.tiempo_entrega,df.cpc,
        f.subtotal_12 as subtotal_12,f.subtotal_sin_impuesto as subtotal, f.descuento,f.codigo, f.iva_12 as iva, f.valor_total as total, CONCAT(v.nombres, ' ', v.apellidos) as vendedor,
        v.email as mailvendedor,v.telefono as telefono_vendedor,prs.nombre as nombre_presentacion,if(df.p_descuento=0,(df.cantidad*df.precio)*(df.descuento/100),df.descuento) as descuento_prod
        from factura f LEFT JOIN detalle df ON f.id_factura=df.id_factura LEFT JOIN
        cliente c ON c.id_cliente=f.id_cliente LEFT JOIN
        empresa e ON e.id_empresa=f.id_empresa LEFT JOIN
        ciudad u ON u.id_ciudad=e.id_ciudad LEFT JOIN
        producto p ON df.id_producto=p.id_producto LEFT JOIN
        marca m ON p.id_marca=m.id_marca LEFT JOIN
        tipo_medida tm ON tm.id_tipo_medida=p.id_tipo_medida LEFT JOIN
        unidad_medida um ON um.id_unidad_medida=p.id_unidad_medida LEFT JOIN
        user v ON v.id = f.id_user LEFT JOIN
        presentacion prs ON p.id_presentacion=prs.id_presentacion
        where f.id_factura= " . $request->id_factura);
        $factura_info = DB::select("SELECT
                                        e.email_empresa ,
                                        e.id_empresa,
                                        e.telefono,
                                        f.lugar_de_entrega,
                                        f.condiciones_de_pago,
                                        f.observacion,
                                        f.fecha_expiracion,
                                        fp.descripcion as forma_pago,
										est.urlweb,
                                        est.id_establecimiento,
                                        e.razon_social,
                                        vd.nombre_vendedor,
                                        vd.email_vendedor,
                                        e.ruc_empresa
                                    FROM factura f
                                    INNER JOIN empresa e
                                    ON e.id_empresa = f.id_empresa
                                    INNER JOIN establecimiento est
                                    ON est.id_empresa = e.id_empresa
                                    LEFT JOIN forma_pagos fp
                                    ON fp.id_forma_pagos = f.id_forma_pagos
                                    LEFT JOIN vendedor vd
                                    ON vd.id_vendedor = f.id_vendedor
                                    WHERE id_factura = " . $request->id_factura);
        
        if($factura_info[0]->id_empresa==71 && $factura_info[0]->id_establecimiento==45){
            $ruta = constant("DATA_EMPRESA") . $factura_info[0]->id_empresa . '/comprobantes/proforma';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0755, true);
            }
            $id_factura=$request->id_factura;
            $codigo=$proformas[0]->codigo;
            $factura_info2=$factura_info[0];
            $pdf = \PDF::loadView('pdf/proforma_Artedeko', compact("id_factura", "proformas", "codigo", "factura_info2"));


                return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "{$factura_info[0]->id_empresa}/comprobantes/proforma/{$id_factura}.pdf")->stream("{$id_factura}.pdf");

        }else{
            //TECHCOMP ruc 1792684706001
            if($proformas[0]->ruc_empresa=='1792684706001'){
                $ruta = constant("DATA_EMPRESA") . $factura_info[0]->id_empresa . '/comprobantes/proforma';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0755, true);
                }
                $id_factura=$request->id_factura;
                $codigo=$proformas[0]->codigo;
                $factura_info2=$factura_info[0];
                $pdf = \PDF::loadView('pdf/proforma_tech', compact("id_factura", "proformas", "codigo", "factura_info2"));


                return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "{$factura_info[0]->id_empresa}/comprobantes/proforma/proforma-{$proformas[0]->identificacion}-{$proformas[0]->fecha_emision}.pdf")->stream("proforma-{$proformas[0]->identificacion}-{$proformas[0]->fecha_emision}.pdf");
            }else{
                if (!$proformas) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    $reportePdf = new generarReportes();
                    $strPDF = $reportePdf->proforma($request->id_factura, $proformas, $proformas[0]->codigo, $factura_info[0]);
                    return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }
            }
        }
    }
    public function facturaformapagos($id)
    {
        $res = DB::select("SELECT fp.* FROM forma_pagos fp INNER JOIN forma_pagos_sri fpi ON fp.id_forma_pagos_sri=fpi.id_forma_pagos_sri WHERE fpi.id_empresa = $id");
        return $res;
    }

    //verificacion del número de la factura
    public function guardar_factura_clave(Request $request)
    {

        $valor = "";
        $valor1 = "";
        $clave_acceso = trim($request->factura["clave_acceso"]);
        //recupera el numero de clave de acceso
        $ca = substr($clave_acceso, 30, 9);
        //verifica si el numero en la clave de acceso existe, al ser asi manda una variable como repetido
        $res = DB::select("SELECT * FROM factura WHERE clave_acceso = '{$request->factura["clave_acceso"]}' and id_empresa={$request->id_empresa}");
        if (count($res) >= 1) {
            $valor = "repetido";
        }
        //verifica si el numero en la clave de acceso de guia existe, al ser asi manda una variable como repetido
        if ($request->guia) {
            $clave_acceso = $request->transportista["clave_acceso"];
            $ca = substr($clave_acceso, 30, 9);
            $res = DB::select("SELECT * FROM guia_remision WHERE clave_acceso = '{$request->transportista["clave_acceso"]}' and id_empresa={$request->id_empresa} ");
            if (count($res) >= 1) {
                $valor1 = "repetido";
            }
        }
        return [
            "factura" => $valor,
            "guia" => $valor1
        ];
    }
    //verificacion del número de la factura
    public function guardar_nota_venta_clave(Request $request)
    {
        $valor = "";
        $valor1 = "";
        $clave_acceso = trim($request->factura["clave_acceso"]);
        //recupera el numero de clave de acceso
        $ca = substr($clave_acceso, 30, 9);
        //verifica si el numero en la clave de acceso existe, al ser asi manda una variable como repetido
        $res = DB::select("SELECT * FROM nota_venta WHERE SUBSTRING(clave_acceso, 31, 9) = $ca");
        if (count($res) >= 1) {
            $valor = "repetido";
        }
        //verifica si el numero en la clave de acceso de guia existe, al ser asi manda una variable como repetido
        if ($request->guia) {
            $clave_acceso = $request->transportista["clave_acceso"];
            $ca = substr($clave_acceso, 30, 9);
            $res = DB::select("SELECT * FROM guia_remision WHERE SUBSTRING(clave_acceso, 31, 9) = $ca");
            if (count($res) >= 1) {
                $valor1 = "repetido";
            }
        }
        return [
            "factura" => $valor,
            "guia" => $valor1
        ];
    }
    //verificacion del número de la guia
    public function guardar_guia_clave(Request $request)
    {
        $clave_acceso = $request->factura["clave_acceso"];
        //recupera el numero de clave de acceso
        $ca = substr($clave_acceso, 30, 9);
        //verifica si el numero en la clave de acceso de guia existe, al ser asi manda una variable como repetido
        $res = DB::select("SELECT * FROM guia_remision WHERE SUBSTRING(clave_acceso, 31, 9) = $ca");
        if (count($res) >= 1) {
            return "repetido";
        }
    }
    //guardado de la factura de venta
    public function guardar_factura(Request $request)
    {
        ini_set('max_execution_time', 1000);
        //guarda la cabecera de la factura
        //dd($request->productos);
        $factura_info = DB::select("SELECT * from factura where clave_acceso like '%{$request->factura['clave_acceso']}%' and id_empresa={$request->usuario["id_empresa"]}");
        if (count($factura_info) > 0) {
            return "factura clave";
        }
        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
            $guia_info = DB::select("SELECT * from guia_remision where clave_acceso like '%{$request->transportista['clave_acceso']}%' and id_empresa={$request->usuario["id_empresa"]}");
            if (count($guia_info) > 0) {
                return "guia clave";
            }
        }

        $factura_anterior = 0;
        if ($request->anterior_producto == true) {
            $factura_anterior = 1;
        }
        $empresa_data = DB::select("SELECT * FROM empresa where id_empresa={$request->usuario["id_empresa"]}");
        $ce_1 = strpos($empresa_data[0]->nombre_empresa, "C.E. FUEGOS");
        $ce_2 = strpos($empresa_data[0]->nombre_empresa, "C.E.FUEGOS");
        $hoy = Carbon::now();
        $factura = new Factura();
        $factura->modo = 1;
        $factura->estado = 1;
        $factura->factura_anterior = $factura_anterior;
        $factura->ambiente = $request->factura["ambiente"];
        $factura->tipo_emision = $request->factura["tipo_emision"];
        $factura->fecha_emision = $request->factura["fecha_emision"];
        $factura->clave_acceso = $request->factura["clave_acceso"];
        $factura->observacion = $request->factura["observacion"];
        $factura->subtotal_sin_impuesto = $request->subtotal;
        $factura->subtotal_12 = $request->subtotal12;
        $factura->subtotal_0 = $request->subtotal0;
        $factura->subtotal_no_obj_iva = $request->no_impuesto;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = 0;
        $factura->valor_irbpnr = 0;
        $factura->existe_iva_8 = $request->existe_iva_8;
        $factura->iva_12 = $request->valor12;
        $factura->propina = $request->propinapr;
        $factura->pp_descuento = $request->pp_descuento;
        $factura->estatus = 1;
        if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
            $factura->financiamiento = $request->interes_monto; 
        }
        $factura->valor_total = $request->total;
        $factura->id_cliente = $request->cliente;
        $factura->id_user = $request->usuario["id"];
        $factura->id_punto_emision = $request->usuario["id_punto_emision"];
        $factura->id_empresa = $request->usuario["id_empresa"];
        $factura->id_establecimiento = $request->usuario["id_establecimiento"];
        $factura->totalpropinaf = 1;
        $factura->orden_compra = $request->factura["orden_compra"];
        $factura->migo = $request->factura["migo"];
        $factura->id_forma_pagos = $request->factura["forma_pago"];
        $factura->id_vendedor = $request->factura["vendedor"];
        $factura->created_by = session()->get('usuariosesion')['id'];
        $factura->updated_by = session()->get('usuariosesion')['id'];
        $factura->save();

        //reucpera el id de la cabecera
        $id = $factura->id_factura;

        //recupera el número del secuencial de la factura y agrega uno mas en punto de emision
        $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
        $sf = $s_facturasubstr + 1;
        $idp = $request->usuario["id_punto_emision"];
        DB::update("UPDATE punto_emision SET secuencial_factura = '$sf' WHERE id_punto_emision = $idp");

        //si existe una guia y el número de transporte genera la cabecera de la guia de remisión
        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
            $transportistas = new FacturaGuiaDeRemision();
            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
            $transportistas->respuesta = "ERROR";
            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
            $transportistas->placa_tr = $request->transportista['placa_transporte'];
            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
            $transportistas->cod_sustento_tr = 1;
            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_factura = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_cliente = $request->cliente;
            $transportistas->id_user = $request->usuario["id"];
            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
            $transportistas->save();
            $idt = $transportistas->id_guia;

            //recupera el número del secuencial de la guia y agrega uno mas en punto de emision
            $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
            $sf = $s_facturasubstr + 1;
            $idp = $request->usuario["id_punto_emision"];
            DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");
        }

        $idempresa = $request->usuario["id_empresa"];
        //registro de egreso
        //seleccióna la ultima bodega de egreso que se creo
        $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $idempresa ORDER BY  num_egreso DESC LIMIT 1;");
        $numeroegreso = "";
        //si existe la bodega_egreso cuenta el ultimo num_egreso del registro caso contrario comienza el contador desde 1
        if (count($numegre) == 1) {
            $dato = $numegre[0]->num_egreso;
            $tot = $dato + 1;
            $numeroegreso = $tot;
        } else {
            $numeroegreso = 1;
        }
        $savebode = 0;
        $savebode2 = 0;
        $id_bodega_egreso = "";
        $contador_comida = 0;
        //recorre los productos de la factura
        for ($a = 0; $a < count($request->productos); $a++) {
            $select_comida = DB::select("SELECT * from producto where id_producto={$request->productos[$a]["id_producto"]} and (descripcion like '%COMIDA%' or descripcion like '%BEBIDA%') and id_empresa={$request->usuario["id_empresa"]}");
            if (count($select_comida) > 0) {
                $contador_comida++;
            }
            $detalle = new Detalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            if ($request->usuario["id_empresa"] == 59) {
                $detalle->precio = number_format($request->productos[$a]["precio"], 4, ".", "");
            } else {
                $detalle->precio = $request->productos[$a]["precio"];
            }

            $detalle->descuento = $request->productos[$a]["descuento"];
            if (isset($request->productos[$a]["precio_sin_iva"])) {
                $detalle->valor_sin_iva = $request->productos[$a]["precio_sin_iva"];
            }
            if ($request->usuario["id_empresa"] == 59) {
                if ($request->productos[$a]["p_descuento"] == 0) {
                    $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 2, ".", "")) * $request->productos[$a]["descuento"]) / 100);
                } else {
                    $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - $request->productos[$a]["descuento"]);
                }
            } else {
                if ($request->productos[$a]["p_descuento"] == 0) {
                    $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) * $request->productos[$a]["descuento"]) / 100);
                } else {
                    $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                }
            }


            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            if (isset($request->productos[$a]["id_producto_bodega"])) {
                $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
            }
            if (isset($request->productos[$a]["proyecto"])) {
                $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            }
            if (isset($request->productos[$a]["total_ice"])) {
                $detalle->valor_ice = $request->productos[$a]["total_ice"] * $request->productos[$a]["cantidad"];
            }
            $detalle->save();

            $id_detalle = $detalle->id_detalle;
            //si existe id de producto bodega ingresa a la siguiente sentencia
            if (isset($request->productos[$a]["id_producto_bodega"])) {
                $cant = $request->productos[$a]["cantidad"];
                $idpb = $request->productos[$a]["id_producto_bodega"];
                //actualiza la cantidad de producto bodega restando los productos que se creo en ese momento
                DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $idpb");

                $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                $costo_unitario = $reses[0]->costo_unitario;
                //verifica si ya se guardo una vez la cabecera del egreso
                $bd_egreso_cabecera0 = DB::select("SELECT * from bodega_egreso where id_factura={$id} and id_empresa={$request->usuario["id_empresa"]}");
                if (count($bd_egreso_cabecera0) > 0) {
                    $id_bodega_egreso = $bd_egreso_cabecera0[0]->id_bodega_egreso;
                } else {
                    if ($savebode == 0) {
                        $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                        $idbodega = $reses[0]->id_bodega;
                        $egreso = new BodegaEgreso();
                        $egreso->num_egreso = $numeroegreso;
                        $egreso->fecha_egreso = $hoy;
                        $egreso->tipo_egreso = "Egreso de Factura";
                        $egreso->observ_egreso = 'Factura Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
                        $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                        if ($idbodega) {
                            $egreso->id_bodega = $idbodega;
                        }
                        $egreso->id_empresa = $request->usuario["id_empresa"];
                        $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                        $egreso->id_factura = $id;
                        $egreso->save();

                        $id_bodega_egreso = $egreso->id_bodega_egreso;
                        $savebode++;
                    }
                }


                //guarda el detalle de los egreso con los productos de la factura
                $bed = new BodegaEgresoDetalle();
                $bed->cantidad = $request->productos[$a]["cantidad"];
                $bed->costo_unitario = $costo_unitario;
                $bed->costo_total = $request->productos[$a]["cantidad"] * $costo_unitario;
                $bed->id_bodega_egreso = $id_bodega_egreso;
                $bed->id_producto = $request->productos[$a]["id_producto"];
                $bed->id_proyecto = $request->productos[$a]["proyecto"];
                $bed->id_detalle = $id_detalle;
                $bed->save();
            }
            if ($ce_1 !== false || $ce_2 !== false) {
                if ($request->productos[$a]["sector"] == 1  && $request->productos[$a]["id_producto_bodega"] == null) {
                    $pdb = new ProductoBodega();
                    $pdb->cantidad = 0 - $request->productos[$a]["cantidad"];
                    $pdb->costo_unitario = $request->productos[$a]["precio"];
                    $pdb->costo_total = $pdb->cantidad * $request->productos[$a]["precio"];
                    $pdb->id_producto = $request->productos[$a]["id_producto"];
                    $pdb->id_bodega = $request->productos[$a]["id_bodega_prod"];
                    $pdb->id_empresa = $request->usuario["id_empresa"];
                    $pdb->save();
                    $id_pr_bodega = "";
                    $id_pr_bodega = $pdb->id_producto_bodega;
                    $bd_egreso_cabecera = DB::select("SELECT * from bodega_egreso where id_factura={$id} and id_empresa={$request->usuario["id_empresa"]}");
                    if (count($bd_egreso_cabecera) > 0) {
                        $bd_cabecera = $bd_egreso_cabecera[0]->id_bodega_egreso;
                    } else {
                        if ($savebode2 == 0) {
                            $egreso = new BodegaEgreso();
                            $egreso->num_egreso = $numeroegreso;
                            $egreso->fecha_egreso = $hoy;
                            $egreso->tipo_egreso = "Egreso de Factura";
                            $egreso->observ_egreso = 'Factura Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
                            $egreso->id_proyecto = $request->productos[$a]["proyecto"];

                            $egreso->id_bodega = $request->productos[$a]["id_bodega_prod"];

                            $egreso->id_empresa = $request->usuario["id_empresa"];
                            $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                            $egreso->id_factura = $id;
                            $egreso->save();

                            $bd_cabecera = $egreso->id_bodega_egreso;
                            $savebode2++;
                        }
                    }

                    $bed = new BodegaEgresoDetalle();
                    $bed->cantidad = $request->productos[$a]["cantidad"];
                    $bed->costo_unitario = $request->productos[$a]["precio"];
                    $bed->costo_total = $bed->cantidad * $bed->costo_unitario;
                    $bed->id_bodega_egreso = $bd_cabecera;
                    $bed->id_producto = $request->productos[$a]["id_producto"];
                    $bed->id_proyecto = $request->productos[$a]["proyecto"];
                    $bed->id_detalle = $id_detalle;
                    $bed->save();
                    if ($id_pr_bodega !== "") {
                        DB::update("UPDATE detalle SET id_producto_bodega={$id_pr_bodega} WHERE id_detalle = $id_detalle");
                    }
                }
            }

            //si existe la guia recorre la guia con los productos de la factura
            if ($request->guia) {
                $detguia = new DetalleGuiaRemision();
                $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                $detguia->descripcion = $request->productos[$a]["nombre"];
                $detguia->cantidad = $request->productos[$a]["cantidad"];
                $detguia->id_producto = $request->productos[$a]["id_producto"];
                $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                $detguia->id_guia_remision = $idt;
                $detguia->save();
            }
        }

        //guarda los pagos en la tabla de factura pagos y cuentas por cobrar con tipo 2

        //verifica si existe pagos
        if (isset($request->pagos)) {
            if (isset($request->pagos["estado"])) {
                if ($request->pagos["estado"]) {
                    for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                        //verifica si es anticipo caso contrario es un pago normal
                        if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                            $pag = new Factura_pagos();
                            $pag->id_forma_pagos = null;
                            $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                            $pag->plazo = 1;
                            $pag->unidad_tiempo = 'Dias';
                            $pag->estado = 1;
                            $pag->fecha = $hoy;
                            $pag->id_factura = $id;
                            $pag->tiempos_pagos = 1;
                            $pag->anticipo = 1;
                            $pag->save();

                            $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $cliente AND tipo=3 ORDER BY id_ctascobrar ASC");
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctascobrar;
                                $pagado = $abono[$i]->abono;

                                if ($cpago > $pagado) {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                                    $cpc->abono = 0;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = $cpago - $pagado;
                                } else {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                                    $cpc->abono = $cpc->abono - $cpago;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = 0;
                                }
                            }

                            /*$resct = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $request->cliente AND tipo = 3");
                            if(count($resct)>=1){

                                DB::update("UPDATE ctas_cobrar SET abono = abono - $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
                            }else{
                                $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                                DB::insert("INSERT ctas_cobrar SET abono = $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
                            }*/
                        } else {
                            if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                                $pag = new Factura_pagos();
                                $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                                $pag->plazo = 1;
                                $pag->unidad_tiempo = 'Días';
                                $pag->estado = 1;
                                $pag->fecha = $hoy;
                                $pag->id_factura = $id;
                                $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $pag->tiempos_pagos = 1;
                                $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                                $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                                $pag->anticipo = 0;
                                $pag->save();

                                $cxc = new Cuentaporcobrar();
                                $cxc->num_cuota = 1;
                                $cxc->fecha_pago = $hoy;
                                $cxc->periodo_pagos = "Dia";
                                $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                                $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $cxc->fecha_factura = $request->factura["fecha_emision"];
                                $cxc->valor_pagado = 0;
                                $cxc->estado = 1;
                                $cxc->tipo = 2;
                                $cxc->id_factura = $id;
                                $cxc->id_cliente = $request->cliente;
                                $cxc->id_empresa = $request->usuario["id_empresa"];
                                $cxc->created_by = session()->get('usuariosesion')['id'];
                                $cxc->updated_by = session()->get('usuariosesion')['id'];
                                $cxc->save();
                            }
                        }
                    }
                }
            }
        }

        //guarda los creditos en la tabla de factura pagos y cuentas por cobrar con tipo 1

        //verifica si existe creditos
        if (isset($request->creditos)) {
            if (isset($request->creditos["estado"])) {
                if ($request->creditos["estado"]) {
                    $pag = new Factura_pagos();
                    $pag->id_forma_pagos = null;
                    $pag->total = $request->creditos["monto"];
                    $pag->plazo = $request->creditos["plazos"];
                    $pag->unidad_tiempo = $request->creditos["periodo"];
                    $pag->tiempos_pagos = $request->creditos["tiempo"];
                    $pag->estado = 2;
                    $pag->fecha = $hoy;
                    $pag->id_factura = $id;
                    $pag->id_banco = null;
                    $pag->id_plan_cuentas = null;
                    if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                        $pag->existe_interes =  $request->exist_interes;
                        $pag->capital_monto =  $request->capital_monto;
                        $pag->interes = $request->interes_monto;
                        $pag->total_pagar_interes = $request->total_interes_saldo;
                    }
                    $pag->save();

                    $hoy = Carbon::parse($request->factura["fecha_emision"]);
                    $fd = "";
                    for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                        $cxc = new Cuentaporcobrar();
                        $cxc->num_cuota = $a + 1;
                        if ($request->creditos["periodo"] == "Años") {
                            $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Meses") {
                            $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Semanas") {
                            $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                        } else {
                            $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
                        }
                        $cxc->fecha_pago = $fd;
                        $cxc->periodo_pagos = $request->creditos["periodo"];
                        if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                            $cxc->valor_cuota = round($request->pagoletra, 2, PHP_ROUND_HALF_UP);
                        } else {
                            $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                        }
                        $cxc->valor_pagado = 0;
                        $cxc->estado = 1;
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->tipo = 1;
                        $cxc->id_factura = $id;
                        $cxc->id_cliente = $request->cliente;
                        $cxc->id_empresa = $request->usuario["id_empresa"];
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->created_by = session()->get('usuariosesion')['id'];
                        $cxc->updated_by = session()->get('usuariosesion')['id'];
                        $cxc->save();
                    }
                }
            }
        }

        //guarda las retenciones de la factura ya sea retencion de iva o de renta
        if (isset($request->retencion_estado)) {
            if ($request->retencion_estado) {
                for ($i = 0; $i < count($request->valorretenciones); $i++) {
                    if (isset($request->valorretenciones[$i]["iva"]) || isset($request->valorretenciones[$i]["renta"])) {
                        $ret = new Retencion_factura();
                        $ret->id_factura = $id;
                        // if (isset($request->valorretenciones[$i]["iva"]["id_retencion"])) {
                        //     $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                        // }
                        // if (isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                        //     $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                        // }

                        if (isset($request->valorretenciones[$i]["iva"]["id_retencion"])) {
                            $ret->baseiva = $request->valorretenciones[$i]["baseiva"];
                            $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                            $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                            $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                        }
                        if (isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                            $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                            $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                            $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                            $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                        }
                        if (isset($request->valorretenciones[$i]["iva"]["id_retencion"]) || isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                            $ret->save();
                        }
                    }
                }
            }
        }

        //guarda las cuotas extras
        if (isset($request->cuota_extra)) {
            if (isset($request->cuota_extra["estado"])) {
                if ($request->cuota_extra["estado"]) {
                    for ($a = 0; $a < count($request->cuota_extra["datos"]); $a++) {
                        $cex = new CuotaExtraFactura();
                        $cex->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                        $cex->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                        $cex->estado = 'Activo';
                        $cex->ucrea = $request->usuario["id"];
                        $cex->id_factura = $id;
                        $cex->save();

                        $cxc = new Cuentaporcobrar();
                        $cxc->num_cuota = $a + 1;
                        
                        $cxc->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                        $cxc->periodo_pagos = "Dias";
                        $cxc->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                        
                        $cxc->valor_pagado = 0;
                        $cxc->estado = 1;
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->tipo = 1;
                        $cxc->id_factura = $id;
                        $cxc->id_cliente = $request->cliente;
                        $cxc->id_empresa = $request->usuario["id_empresa"];
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->created_by = session()->get('usuariosesion')['id'];
                        $cxc->updated_by = session()->get('usuariosesion')['id'];
                        $cxc->save();
                    }
                }
            }
        }
        if ($request->usuario["id_empresa"] == 50 && $request->usuario["id_establecimiento"] == 44 && $request->usuario["id_punto_emision"] == 43) {
            //dd($contador_comida);
            if ($contador_comida > 0) {
                $comida_conta = 1;
                $seelct_pedidos = DB::select("SELECT max(pedido) as pedido from factura where id_empresa={$request->usuario["id_empresa"]}");
                if (count($seelct_pedidos) > 0) {
                    $comida_conta = $seelct_pedidos[0]->pedido + 1;
                }
                //dd($id.":id_factura y pedido:".$comida_conta);

                DB::update("UPDATE factura SET pedido = $comida_conta WHERE id_factura = $id");
            }
        }
        //recupera los datos de la empresa y de la factura de venta o de guia dependiendo si existe tal registro
        return [
            "factura" => Factura::select('factura.*','factura.migo as migo_factura', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'factura.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
                ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'factura.id_establecimiento')
                ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'factura.id_punto_emision')
                ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
                ->where("factura.id_factura", "=", $id)
                ->orderByRaw('factura.id_factura DESC')->get(),
            "guia" => Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
                ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
                ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
                ->where("guia_remision.id_factura", "=", $id)
                ->orderByRaw('guia_remision.id_guia DESC')->get()
        ];
    }
    public function productos_reporte(Request $request, $id)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = DB::select("SELECT id_producto,nombre,cod_principal FROM producto where id_empresa={$id}");
        } else {
            $recupera = DB::select("SELECT id_producto,nombre,cod_principal FROM producto where id_empresa={$id}");
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function store_factura_acumulada(Request $request)
    {
        //guarda la cabecera de la factura
        $hoy = Carbon::now();
        $factura = new NotaVenta();
        $factura->modo = 1;
        $factura->estado = 1;
        //$factura->modo_acumulado = 1;
        $factura->ambiente = $request->factura["ambiente"];
        $factura->tipo_emision = $request->factura["tipo_emision"];
        $factura->fecha_emision = $request->factura["fecha_emision"];
        $factura->clave_acceso = $request->factura["clave_acceso"];
        $factura->observacion = $request->factura["observacion"];
        $factura->subtotal_sin_impuesto = $request->subtotal;
        $factura->subtotal_12 = $request->subtotal12;
        $factura->subtotal_0 = $request->subtotal0;
        $factura->subtotal_no_obj_iva = $request->no_impuesto;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = 0;
        $factura->valor_irbpnr = 0;
        $factura->iva_12 = $request->valor12;
        $factura->propina = $request->propinapr;
        $factura->pp_descuento = $request->pp_descuento;
        $factura->estatus = 1;
        if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
            $factura->financiamiento = $request->interes_monto;
        }
        $factura->valor_total = $request->total;
        $factura->id_cliente = $request->cliente;
        $factura->id_user = $request->usuario["id"];
        $factura->id_punto_emision = $request->usuario["id_punto_emision"];
        $factura->id_empresa = $request->usuario["id_empresa"];
        $factura->id_establecimiento = $request->usuario["id_establecimiento"];
        $factura->totalpropinaf = 1;
        $factura->orden_compra = $request->factura["orden_compra"];
        $factura->migo = $request->factura["migo"];
        $factura->id_forma_pagos = $request->factura["forma_pago"];
        $factura->id_vendedor = $request->factura["vendedor"];
        $factura->created_by = session()->get('usuariosesion')['id'];
        $factura->updated_by = session()->get('usuariosesion')['id'];
        $factura->save();
        // $empresa_data = DB::select("SELECT * FROM empresa where id_empresa={$request->usuario["id_empresa"]}");
        // $ce_1 = strpos($empresa_data[0]->nombre_empresa, "C.E. FUEGOS");
        // $ce_2 = strpos($empresa_data[0]->nombre_empresa, "C.E.FUEGOS");

        //reucpera el id de la cabecera
        $id = $factura->id_nota_venta;

        //recupera el número del secuencial de la factura y agrega uno mas en punto de emision
        $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
        $sf = $s_facturasubstr + 1;
        $idp = $request->usuario["id_punto_emision"];
        DB::update("UPDATE punto_emision SET secuencial_nota_venta = '$sf' WHERE id_punto_emision = $idp");

        //si existe una guia y el número de transporte genera la cabecera de la guia de remisión
        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
            $transportistas = new Guia_remision();
            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
            $transportistas->respuesta = "ERROR";
            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
            $transportistas->placa_tr = $request->transportista['placa_transporte'];
            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
            $transportistas->cod_sustento_tr = 1;
            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_nota_venta = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_cliente = $request->cliente;
            $transportistas->id_user = $request->usuario["id"];
            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
            $transportistas->save();
            $idt = $transportistas->id_guia;

            //recupera el número del secuencial de la guia y agrega uno mas en punto de emision
            $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
            $sf = $s_facturasubstr + 1;
            $idp = $request->usuario["id_punto_emision"];
            DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");
        }

        $idempresa = $request->usuario["id_empresa"];
        //registro de egreso
        //seleccióna la ultima bodega de egreso que se creo
        $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $idempresa ORDER BY  num_egreso DESC LIMIT 1;");
        $numeroegreso = "";
        //si existe la bodega_egreso cuenta el ultimo num_egreso del registro caso contrario comienza el contador desde 1
        if (count($numegre) == 1) {
            $dato = $numegre[0]->num_egreso;
            $tot = $dato + 1;
            $numeroegreso = $tot;
        } else {
            $numeroegreso = 1;
        }
        $savebode = 0;
        $id_bodega_egreso = "";
        //recorre los productos de la factura
        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new NotaVentaDetalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            if (isset($request->productos[$a]["precio_sin_iva"])) {
                $detalle->valor_sin_iva = $request->productos[$a]["precio_sin_iva"];
            }

            if ($request->productos[$a]["p_descuento"] == 0) {
                $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) * $request->productos[$a]["descuento"]) / 100);
            } else {
                $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
            }
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_nota_venta = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            if (isset($request->productos[$a]["id_producto_bodega"])) {
                $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
            }
            if (isset($request->productos[$a]["proyecto"])) {
                $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            }
            if (isset($request->productos[$a]["total_ice"])) {
                $detalle->valor_ice = $request->productos[$a]["total_ice"];
            }
            $detalle->save();

            $id_detalle = $detalle->id_detalle_nota_venta;
            //si existe id de producto bodega ingresa a la siguiente sentencia
            if (isset($request->productos[$a]["id_producto_bodega"])) {
                $cant = $request->productos[$a]["cantidad"];
                $idpb = $request->productos[$a]["id_producto_bodega"];
                //actualiza la cantidad de producto bodega restando los productos que se creo en ese momento
                DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $idpb");

                $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                $costo_unitario = $reses[0]->costo_unitario;
                //verifica si ya se guardo una vez la cabecera del egreso
                if ($savebode == 0) {
                    $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                    $idbodega = $reses[0]->id_bodega;
                    $egreso = new BodegaEgreso();
                    $egreso->num_egreso = $numeroegreso;
                    $egreso->fecha_egreso = $hoy;
                    $egreso->tipo_egreso = "Egreso de Nota Venta";
                    $egreso->observ_egreso = 'Nota Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
                    $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    if ($idbodega) {
                        $egreso->id_bodega = $idbodega;
                    }
                    $egreso->id_empresa = $request->usuario["id_empresa"];
                    $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    $egreso->id_nota_venta = $id;
                    $egreso->save();

                    $id_bodega_egreso = $egreso->id_bodega_egreso;
                    $savebode++;
                }

                //guarda el detalle de los egreso con los productos de la factura
                $bed = new BodegaEgresoDetalle();
                $bed->cantidad = $request->productos[$a]["cantidad"];
                $bed->costo_unitario = $costo_unitario;
                $bed->costo_total = $request->productos[$a]["cantidad"] * $costo_unitario;
                $bed->id_bodega_egreso = $id_bodega_egreso;
                $bed->id_producto = $request->productos[$a]["id_producto"];
                $bed->id_proyecto = $request->productos[$a]["proyecto"];
                $bed->id_detalle_nota_venta = $id_detalle;
                $bed->save();
            }
            //solo agrega los productos si son de la empresa cefuegos
            // if ($ce_1 !== false || $ce_2 !== false) {
            //     if ($request->productos[$a]["sector"] == 1  && $request->productos[$a]["id_producto_bodega"] == null) {
            //         $pdb = new ProductoBodega();
            //         $pdb->cantidad = 0 - $request->productos[$a]["cantidad"];
            //         $pdb->costo_unitario = $request->productos[$a]["precio"];
            //         $pdb->costo_total = $pdb->cantidad * $request->productos[$a]["precio"];
            //         $pdb->id_producto = $request->productos[$a]["id_producto"];
            //         $pdb->id_bodega = $request->productos[$a]["id_bodega_prod"];
            //         $pdb->id_empresa = $request->usuario["id_empresa"];
            //         $pdb->save();
            //         $id_pr_bodega = "";
            //         $id_pr_bodega = $pdb->id_producto_bodega;
            //         $bd_egreso_cabecera = DB::select("SELECT * from bodega_egreso where id_factura={$id} and id_empresa={$request->usuario["id_empresa"]}");
            //         if (count($bd_egreso_cabecera) > 0) {
            //             $bd_cabecera = $bd_egreso_cabecera[0]->id_bodega_egreso;
            //         } else {
            //             if ($savebode2 == 0) {
            //                 $egreso = new BodegaEgreso();
            //                 $egreso->num_egreso = $numeroegreso;
            //                 $egreso->fecha_egreso = $hoy;
            //                 $egreso->tipo_egreso = "Egreso de Factura";
            //                 $egreso->observ_egreso = 'Factura Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
            //                 $egreso->id_proyecto = $request->productos[$a]["proyecto"];

            //                 $egreso->id_bodega = $request->productos[$a]["id_bodega_prod"];

            //                 $egreso->id_empresa = $request->usuario["id_empresa"];
            //                 $egreso->id_proyecto = $request->productos[$a]["proyecto"];
            //                 $egreso->id_factura = $id;
            //                 $egreso->save();

            //                 $bd_cabecera = $egreso->id_bodega_egreso;
            //                 $savebode2++;
            //             }
            //         }

            //         $bed = new BodegaEgresoDetalle();
            //         $bed->cantidad = $request->productos[$a]["cantidad"];
            //         $bed->costo_unitario = $request->productos[$a]["precio"];
            //         $bed->costo_total = $bed->cantidad * $bed->costo_unitario;
            //         $bed->id_bodega_egreso = $bd_cabecera;
            //         $bed->id_producto = $request->productos[$a]["id_producto"];
            //         $bed->id_proyecto = $request->productos[$a]["proyecto"];
            //         $bed->id_detalle = $id_detalle;
            //         $bed->save();
            //         if ($id_pr_bodega !== "") {
            //             DB::update("UPDATE detalle SET id_producto_bodega={$id_pr_bodega} WHERE id_detalle = $id_detalle");
            //         }
            //     }
            // }

            //si existe la guia recorre la guia con los productos de la factura
            if ($request->guia) {
                $detguia = new DetalleGuiaRemision();
                $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                $detguia->descripcion = $request->productos[$a]["nombre"];
                $detguia->cantidad = $request->productos[$a]["cantidad"];
                $detguia->id_producto = $request->productos[$a]["id_producto"];
                $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                $detguia->id_guia_remision = $idt;
                $detguia->save();
            }
        }

        //guarda los pagos en la tabla de factura pagos y cuentas por cobrar con tipo 2

        //verifica si existe pagos
        if (isset($request->pagos)) {
            if (isset($request->pagos["estado"])) {
                if ($request->pagos["estado"]) {
                    for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                        //verifica si es anticipo caso contrario es un pago normal
                        if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                            $pag = new NotaVentaPago();
                            $pag->id_forma_pagos = null;
                            $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                            $pag->plazo = 1;
                            $pag->unidad_tiempo = 'Dias';
                            $pag->estado = 1;
                            $pag->fecha = $hoy;
                            $pag->id_nota_venta = $id;
                            $pag->tiempos_pagos = 1;
                            $pag->anticipo = 1;
                            $pag->save();

                            $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $cliente AND tipo=3 ORDER BY id_ctascobrar ASC");
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctascobrar;
                                $pagado = $abono[$i]->abono;

                                if ($cpago > $pagado) {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                                    $cpc->abono = 0;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = $cpago - $pagado;
                                } else {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                                    $cpc->abono = $cpc->abono - $cpago;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = 0;
                                }
                            }

                            /*$resct = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $request->cliente AND tipo = 3");
                            if(count($resct)>=1){

                                DB::update("UPDATE ctas_cobrar SET abono = abono - $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
                            }else{
                                $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                                DB::insert("INSERT ctas_cobrar SET abono = $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
                            }*/
                        } else {
                            if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                                $pag = new NotaVentaPago();
                                $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                                $pag->plazo = 1;
                                $pag->unidad_tiempo = 'Días';
                                $pag->estado = 1;
                                $pag->fecha = $hoy;
                                $pag->id_nota_venta = $id;
                                $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $pag->tiempos_pagos = 1;
                                $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                                $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                                $pag->anticipo = 0;
                                $pag->save();

                                $cxc = new Cuentaporcobrar();
                                $cxc->num_cuota = 1;
                                $cxc->fecha_pago = $hoy;
                                $cxc->periodo_pagos = "Dia";
                                $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                                $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $cxc->fecha_factura = $request->factura["fecha_emision"];
                                $cxc->valor_pagado = 0;
                                $cxc->estado = 1;
                                $cxc->tipo = 2;
                                $cxc->id_nota_venta = $id;
                                $cxc->id_cliente = $request->cliente;
                                $cxc->id_empresa = $request->usuario["id_empresa"];
                                $cxc->created_by = session()->get('usuariosesion')['id'];
                                $cxc->updated_by = session()->get('usuariosesion')['id'];
                                $cxc->save();
                            }
                        }
                    }
                }
            }
        }

        //guarda los creditos en la tabla de factura pagos y cuentas por cobrar con tipo 1

        //verifica si existe creditos
        if (isset($request->creditos)) {
            if (isset($request->creditos["estado"])) {
                if ($request->creditos["estado"]) {
                    $pag = new NotaVentaPago();
                    $pag->id_forma_pagos = null;
                    $pag->total = $request->creditos["monto"];
                    $pag->plazo = $request->creditos["plazos"];
                    $pag->unidad_tiempo = $request->creditos["periodo"];
                    $pag->tiempos_pagos = $request->creditos["tiempo"];
                    $pag->estado = 2;
                    $pag->fecha = $hoy;
                    $pag->id_nota_venta = $id;
                    $pag->id_banco = null;
                    $pag->id_plan_cuentas = null;
                    if (isset($request->interes_monto)) {
                        if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                            $pag->existe_interes =  $request->exist_interes;
                            $pag->capital_monto =  $request->capital_monto;
                            $pag->interes = $request->interes_monto;
                            $pag->total_pagar_interes = $request->total_interes_saldo;
                        }
                    }

                    $pag->save();

                    $hoy = Carbon::parse($request->factura["fecha_emision"]);
                    $fd = "";
                    for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                        $cxc = new Cuentaporcobrar();
                        $cxc->num_cuota = $a + 1;
                        if ($request->creditos["periodo"] == "Años") {
                            $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Meses") {
                            $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Semanas") {
                            $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                        } else {
                            $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
                        }
                        $cxc->fecha_pago = $fd;
                        $cxc->periodo_pagos = $request->creditos["periodo"];
                        if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                            $cxc->valor_cuota = round($request->pagoletra, 2, PHP_ROUND_HALF_UP);
                        } else {
                            $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                        }
                        //$cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                        $cxc->valor_pagado = 0;
                        $cxc->estado = 1;
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->tipo = 1;
                        $cxc->id_nota_venta = $id;
                        $cxc->id_cliente = $request->cliente;
                        $cxc->id_empresa = $request->usuario["id_empresa"];
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->created_by = session()->get('usuariosesion')['id'];
                        $cxc->updated_by = session()->get('usuariosesion')['id'];
                        $cxc->save();
                    }
                }
            }
        }
        //guarda las cuotas extras
        if (isset($request->cuota_extra)) {
            if (isset($request->cuota_extra["estado"])) {
                if ($request->cuota_extra["estado"]) {
                    for ($a = 0; $a < count($request->cuota_extra["datos"]); $a++) {
                        $cex = new CuotaExtraNotaVenta();
                        $cex->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                        $cex->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                        $cex->estado = 'Activo';
                        $cex->ucrea = $request->usuario["id"];
                        $cex->id_nota_venta = $id;
                        $cex->save();

                        $cxc = new Cuentaporcobrar();
                        $cxc->num_cuota = $a + 1;
                        
                        $cxc->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                        $cxc->periodo_pagos = "Dias";
                        $cxc->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                        
                        $cxc->valor_pagado = 0;
                        $cxc->estado = 1;
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->tipo = 1;
                        $cxc->id_factura = $id;
                        $cxc->id_cliente = $request->cliente;
                        $cxc->id_empresa = $request->usuario["id_empresa"];
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->created_by = session()->get('usuariosesion')['id'];
                        $cxc->updated_by = session()->get('usuariosesion')['id'];
                        $cxc->save();
                    }
                }
            }
        }
        //guarda las retenciones de la factura ya sea retencion de iva o de renta
        if (isset($request->retencion_estado)) {
            if ($request->retencion_estado) {
                for ($i = 0; $i < count($request->valorretenciones); $i++) {
                    if (isset($request->valorretenciones[$i]["iva"]) || isset($request->valorretenciones[$i]["renta"])) {
                        $ret = new NotaVentaRetencion();
                        $ret->id_nota_venta = $id;
                        $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                        $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];

                        $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                        $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                        $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                        $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                        $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                        $ret->save();
                    }
                }
            }
        }
        //recupera los datos de la empresa y de la factura de venta o de guia dependiendo si existe tal registro


        return [
            "nota_venta" => NotaVenta::select('nota_venta.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'nota_venta.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'nota_venta.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'nota_venta.id_cliente')
                ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
                ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
                ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
                ->where("nota_venta.id_nota_venta", "=", $id)
                ->orderByRaw('nota_venta.id_nota_venta DESC')->get(),
            "guia" => Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
                ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
                ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
                ->where("guia_remision.id_nota_venta", "=", $id)
                ->orderByRaw('guia_remision.id_guia DESC')->get()
        ];
    }
    //lista mediante el id de guia los datos de la empresa para generar la clave de acceso
    public function listar_guia_clave($id)
    {
        return Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
            ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
            ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
            ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
            ->where("guia_remision.id_guia", "=", $id)
            ->orderByRaw('guia_remision.id_guia DESC')->get();
    }
    //actualización de la factura de venta
    public function editar_factura(Request $request)
    {
        ini_set('max_execution_time', 1000);
        $valorcompra = Factura::where("id_factura", "=", $request->factura["id_factura"])->first();
        //echo($request->factura["clave_acceso"]);
        //return; 
        //guarda la fecha actual del servidor
        $hoy = Carbon::now();
        //verifica si la factura ya esta enviado al SRI
        if ($valorcompra->respuesta != "Enviado") {
            //verifica si es una proforma, si es proforma suma el valor del secuencial de factura en punto de emisión
            // if ($valorcompra->modo == 0) {
            //     $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
            //     $sf = $s_facturasubstr + 1;
            //     $idp = $request->usuario["id_punto_emision"];
            //     DB::update("UPDATE punto_emision SET secuencial_factura = '$sf' WHERE id_punto_emision = $idp");
            // }
            $clb=substr($request->factura["clave_acceso"],24,15);
            $factura_info=DB::select("SELECT * from factura where id_empresa={$request->usuario["id_empresa"]} and SUBSTR(clave_acceso,25,15) like '%{$clb}%' and id_factura<>{$request->factura["id_factura"]}");
            if(count($factura_info)>0){
                return "existe clave factura";
            }
            $empresa_data = DB::select("SELECT * FROM empresa where id_empresa={$request->usuario["id_empresa"]}");
            $ce_1 = strpos($empresa_data[0]->nombre_empresa, "C.E. FUEGOS");
            $ce_2 = strpos($empresa_data[0]->nombre_empresa, "C.E.FUEGOS");
            $exist_iva8=0;
            if($request->existe_iva_8==true){
                $exist_iva8=1;
            }
            //actualiza la factura cabecera
            $factura = Factura::findOrFail($request->factura["id_factura"]);
            $factura->modo = 1;
            $factura->estado = 1;
            $factura->ambiente = $request->factura["ambiente"];
            $factura->tipo_emision = $request->factura["tipo_emision"];
            $factura->fecha_emision = $request->factura["fecha_emision"];
            $factura->clave_acceso = $request->factura["clave_acceso"];
            $factura->observacion = $request->factura["observacion"];
            $factura->subtotal_sin_impuesto = $request->subtotal;
            $factura->subtotal_12 = $request->subtotal12;
            $factura->subtotal_0 = $request->subtotal0;
            $factura->subtotal_no_obj_iva = $request->no_impuesto;
            $factura->descuento = $request->descuento;
            $factura->valor_ice = 0;
            $factura->valor_irbpnr = 0;
            $factura->existe_iva_8 = $exist_iva8;
            $factura->iva_12 = $request->valor12;
            $factura->propina = $request->propinapr;
            $factura->pp_descuento = $request->pp_descuento;
            if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                $factura->financiamiento = $request->interes_monto;
            }
            $factura->valor_total = $request->total;
            $factura->id_cliente = $request->cliente;
            $factura->id_user = $request->usuario["id"];
            if($factura->estatus==0 || $factura->estatus=="0"){
                $factura->id_punto_emision = $request->usuario["id_punto_emision"];
                $factura->id_establecimiento = $request->usuario["id_establecimiento"];
            }
            //$factura->id_punto_emision = $request->usuario["id_punto_emision"];
            //$factura->id_empresa = $request->usuario["id_empresa"];
            
            $factura->totalpropinaf = 1;
            $factura->orden_compra = $request->factura["orden_compra"];
            $factura->migo = $request->factura["migo"];
            $factura->id_forma_pagos = $request->factura["forma_pago"];
            $factura->id_vendedor = $request->factura["vendedor"];
            $factura->save();
            if($factura->estatus==0 || $factura->estatus=="0"){
                //recupera el número del secuencial de la factura y agrega uno mas en punto de emision
                $s_facturasubstr_0 = substr($request->factura["clave_acceso"], -19, -10);
                $sf_0 = $s_facturasubstr_0+ 1;
                $idp_0 = $request->usuario["id_punto_emision"];
                DB::update("UPDATE punto_emision SET secuencial_factura = '$sf_0' WHERE id_punto_emision = $idp_0");
            }
            //recupera el id de factura
            $id = $request->factura["id_factura"];

            //verifica si envia guia
            if ($request->guia) {
                //verifica si la guia fue enviada al SRI
                if ($request->factura["respuesta_guia"] != "Enviado") {
                    if (isset($request->transportista['id'])) {
                        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
                            //Actualiza la cabecera de la guia
                            $transportistas = FacturaGuiaDeRemision::findOrFail($request->transportista['id']);
                            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
                            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
                            $transportistas->respuesta = "ERROR";
                            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
                            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
                            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
                            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
                            $transportistas->placa_tr = $request->transportista['placa_transporte'];
                            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
                            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
                            $transportistas->cod_sustento_tr = 1;
                            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_factura = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_cliente = $request->cliente;
                            $transportistas->id_user = $request->usuario["id"];
                            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
                            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
                            $transportistas->save();
                            $idt = $request->transportista['id'];

                            //elimina el detalle de la guia si existiera
                            DB::delete("DELETE FROM `detalle_guia_remision` WHERE id_guia_remision = $idt");
                        }
                    } else {
                        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
                            //si no exisita una guia y se genera en ese momento crea una nueva cabecera de guia
                            $transportistas = new FacturaGuiaDeRemision();
                            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
                            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
                            $transportistas->respuesta = "ERROR";
                            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
                            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
                            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
                            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
                            $transportistas->placa_tr = $request->transportista['placa_transporte'];
                            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
                            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
                            $transportistas->cod_sustento_tr = 1;
                            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_factura = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_cliente = $request->cliente;
                            $transportistas->id_user = $request->usuario["id"];
                            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
                            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
                            $transportistas->save();
                            $idt = $transportistas->id_guia;

                            //elimina el detalle de la guia si existiera
                            DB::delete("DELETE FROM `detalle_guia_remision` WHERE id_guia_remision = $idt");

                            //suma mas 1 al secuencial de guia en punto de emision
                            $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
                            $sf = $s_facturasubstr + 1;
                            $idp = $request->usuario["id_punto_emision"];
                            DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");
                        }
                    }
                }
            }
            $savebode = 0;
            $id_bodega_egreso = "";
            //sirve para saber si existe los detalles existentes
            $detalles_existentes = [];
            //si tiene bodega los productos guarda en una variable para guardar en la cabecera de egreso
            for ($a = 0; $a < count($request->productos); $a++) {
                $bdegval = DB::select("SELECT * FROM bodega_egreso WHERE id_factura = $id");
                if ($bdegval) {
                    if ($savebode == 0) {
                        $id_bodega_egreso = $bdegval[0]->id_bodega_egreso;
                        $savebode++;
                    }
                }
            }
            for ($a = 0; $a < count($request->productos); $a++) {
                //verifica si existe el detalle de producto (si es un producto que si exisitia en factura)
                //caso contrario genera nuevo detalle de producto
                if (isset($request->productos[$a]["id_detalle"])) {
                    $rees = DB::select("SELECT * FROM detalle WHERE id_detalle = " . $request->productos[$a]["id_detalle"]);
                    $valer = $rees[0]->cantidad;

                    $detalle = Detalle::findOrFail($request->productos[$a]["id_detalle"]);
                    $detalle->nombre = $request->productos[$a]["nombre"];
                    $detalle->cantidad = $request->productos[$a]["cantidad"];
                    if ($request->usuario["id_empresa"] == 59) {
                        $detalle->precio = number_format($request->productos[$a]["precio"], 4, ".", "");
                    } else {
                        $detalle->precio = $request->productos[$a]["precio"];
                    }

                    $detalle->descuento = $request->productos[$a]["descuento"];
                    if ($request->usuario["id_empresa"] == 59) {
                        if ($request->productos[$a]["p_descuento"] == 0) {
                            $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) * $request->productos[$a]["descuento"]) / 100);
                        } else {
                            $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - $request->productos[$a]["descuento"]);
                        }
                    } else {
                        if ($request->productos[$a]["p_descuento"] == 0) {
                            $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) * $request->productos[$a]["descuento"]) / 100);
                        } else {
                            $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                        }
                    }

                    if (isset($request->productos[$a]["precio_sin_iva"])) {
                        $detalle->valor_sin_iva = $request->productos[$a]["precio_sin_iva"];
                    }
                    $detalle->id_iva = $request->productos[$a]["iva"];
                    $detalle->id_ice = $request->productos[$a]["ice"];
                    $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                    $detalle->id_factura = $id;
                    $detalle->id_producto = $request->productos[$a]["id_producto"];
                    if (isset($request->productos[$a]["id_producto_bodega"])) {
                        $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                    }
                    if (isset($request->productos[$a]["proyecto"])) {
                        $detalle->id_proyecto = $request->productos[$a]["proyecto"];
                    }
                    if (isset($request->productos[$a]["total_ice"])) {
                        $detalle->valor_ice = $request->productos[$a]["total_ice"] * $request->productos[$a]["cantidad"];
                    }
                    $detalle->save();

                    $detalle_id_d = $request->productos[$a]["id_detalle"];

                    //verifica si el egreso del producto existe
                    //caso contrario crea el egreso del registro
                    $select_dtt = DB::select("SELECT * FROM bodega_egreso_detalle WHERE id_detalle = " . $detalle_id_d);
                    if (count($select_dtt) <= 0) {
                        if (isset($request->productos[$a]["id_producto_bodega"]) && $request->productos[$a]["id_producto_bodega"] !== null) {
                            $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                            $idbodega = $reses[0]->id_bodega;
                            $costo_unitario = $reses[0]->costo_unitario;

                            $cant = $request->productos[$a]["cantidad"];
                            $idpb = $request->productos[$a]["id_producto_bodega"];
                            DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $idpb");

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
                            //verifica si ya se guardo una vez la cabecera del egreso
                            if ($savebode == 0) {
                                $egreso = new BodegaEgreso();
                                $egreso->num_egreso = $numeroegreso;
                                $egreso->fecha_egreso = $hoy;
                                $egreso->tipo_egreso = "Egreso de Factura";
                                $egreso->observ_egreso = 'Factura Venta: '. substr($request->factura["clave_acceso"], -19, -10);
                                $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                                $egreso->id_bodega = $idbodega;
                                $egreso->id_empresa = $request->usuario["id_empresa"];
                                $egreso->id_factura = $id;
                                $egreso->save();
                                $id_bodega_egreso = $egreso->id_bodega_egreso;
                                $savebode++;
                            }

                            //guarda el egreso del producto si no existe
                            $bed = new BodegaEgresoDetalle();
                            $bed->cantidad = $request->productos[$a]["cantidad"];
                            $bed->costo_unitario = $costo_unitario;
                            if ($request->productos[$a]["cantidad"] > 0) {
                                $bed->costo_total = $request->productos[$a]["cantidad"] * $costo_unitario;
                            } else {
                                $bed->costo_total = 0;
                            }
                            $bed->id_proyecto = $request->productos[$a]["proyecto"];
                            $bed->id_bodega_egreso = $id_bodega_egreso;
                            $bed->id_producto = $request->productos[$a]["id_producto"];
                            $bed->id_detalle = $request->productos[$a]["id_detalle"];
                            $bed->save();
                            //agrega al array de productos existentes el id del detalle
                            array_push($detalles_existentes, $detalle_id_d);
                        } else {
                            array_push($detalles_existentes, $detalle_id_d);
                        }
                    } else if ($request->productos[$a]["id_producto_bodega"] !== null) {
                        $valorreal = $valer - ($request->productos[$a]["cantidad"]);
                        if ($valorreal != 0) {
                            $cant = $request->productos[$a]["cantidad"];
                            $idpb = $request->productos[$a]["id_producto_bodega"];
                            //actualiza el producto de bodega con la cantidad cambiada del editar de factura
                            DB::update("UPDATE producto_bodega SET cantidad = cantidad + ($valorreal), costo_total = cantidad * costo_unitario  WHERE id_producto_bodega = $idpb");
                            //verifica si el valor se cambio
                            if ($valorreal != 0) {
                                $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                                $idbodega = $reses[0]->id_bodega;
                                $costo_unitario = $reses[0]->costo_unitario;
                                $cantidad_nueva = $request->productos[$a]["cantidad"];
                                //si el valor cambio edita la bodega de egreso del producto con los valores actuales
                                DB::update("UPDATE bodega_egreso_detalle SET cantidad = $cant, costo_total = $cant * costo_unitario WHERE id_detalle = " . $request->productos[$a]["id_detalle"]);
                            }
                        }
                        //agrega al array de productos existentes el id del detalle
                        array_push($detalles_existentes, $detalle_id_d);
                    } else {
                        //agrega al array de productos existentes el id del detalle
                        array_push($detalles_existentes, $detalle_id_d);
                    }
                } else {
                    //si es producto nuevo en el detalle genera un nuevo registro
                    $detalle = new Detalle();
                    $detalle->nombre = $request->productos[$a]["nombre"];
                    $detalle->cantidad = $request->productos[$a]["cantidad"];
                    if ($request->usuario["id_empresa"] == 59) {
                        $detalle->precio = number_format($request->productos[$a]["precio"], 4, ".", "");
                    } else {
                        $detalle->precio = $request->productos[$a]["precio"];
                    }

                    $detalle->descuento = $request->productos[$a]["descuento"];
                    if ($request->usuario["id_empresa"] == 59) {
                        if ($request->productos[$a]["p_descuento"] == 0) {
                            $detalle->total = ((($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) * $request->productos[$a]["descuento"]) / 100));
                        } else {
                            $detalle->total = ((($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - $request->productos[$a]["descuento"]));
                        }
                    } else {
                        if ($request->productos[$a]["p_descuento"] == 0) {
                            $detalle->total = round((($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) * $request->productos[$a]["descuento"]) / 100), 2);
                        } else {
                            $detalle->total = round((($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]), 2);
                        }
                    }

                    if (isset($request->productos[$a]["precio_sin_iva"])) {
                        $detalle->valor_sin_iva = $request->productos[$a]["precio_sin_iva"];
                    }
                    $detalle->id_iva = $request->productos[$a]["iva"];
                    $detalle->id_ice = $request->productos[$a]["ice"];
                    $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                    $detalle->id_factura = $id;
                    $detalle->id_producto = $request->productos[$a]["id_producto"];
                    if (isset($request->productos[$a]["id_producto_bodega"])) {
                        $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                    }
                    if (isset($request->productos[$a]["proyecto"])) {
                        $detalle->id_proyecto = $request->productos[$a]["proyecto"];
                    }
                    if (isset($request->productos[$a]["total_ice"])) {
                        $detalle->valor_ice = $request->productos[$a]["total_ice"] * $request->productos[$a]["cantidad"];
                    }
                    $detalle->save();

                    $detalle_id_d = $detalle->id_detalle;
                    //verifica si el id producto bodega existe
                    if (isset($request->productos[$a]["id_producto_bodega"]) && $request->productos[$a]["id_producto_bodega"] !== null) {
                        $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                        $idbodega = $reses[0]->id_bodega;
                        $costo_unitario = $reses[0]->costo_unitario;

                        $cant = $request->productos[$a]["cantidad"];
                        $idpb = $request->productos[$a]["id_producto_bodega"];
                        DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $idpb");

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

                        //verifica si la bodega de egreso cabecera existe una vez
                        if ($savebode == 0) {
                            $egreso = new BodegaEgreso();
                            $egreso->num_egreso = $numeroegreso;
                            $egreso->fecha_egreso = $hoy;
                            $egreso->tipo_egreso = "Egreso de Factura";
                            $egreso->observ_egreso = 'Factura Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
                            $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                            $egreso->id_bodega = $idbodega;
                            $egreso->id_empresa = $request->usuario["id_empresa"];
                            $egreso->id_factura = $id;
                            $egreso->save();
                            $id_bodega_egreso = $egreso->id_bodega_egreso;
                            $savebode++;
                        }

                        $bed = new BodegaEgresoDetalle();
                        $bed->cantidad = $request->productos[$a]["cantidad"];
                        $bed->costo_unitario = $costo_unitario;
                        if ($request->productos[$a]["cantidad"] > 0) {
                            $bed->costo_total = $request->productos[$a]["cantidad"] * $costo_unitario;
                        } else {
                            $bed->costo_total = 0;
                        }
                        $bed->id_proyecto = $request->productos[$a]["proyecto"];
                        $bed->id_bodega_egreso = $id_bodega_egreso;
                        $bed->id_producto = $request->productos[$a]["id_producto"];
                        $bed->id_detalle = $detalle_id_d;
                        $bed->save();
                        array_push($detalles_existentes, $detalle_id_d);
                    } else {
                        array_push($detalles_existentes, $detalle_id_d);
                    }
                }
                if ($ce_1 !== false || $ce_2 !== false) {
                    if ($request->productos[$a]["sector"] == 1  && $request->productos[$a]["id_producto_bodega"] == null) {
                        $pt_em_b = DB::select("SELECT * from punto_emision where id_punto_emision={$request->usuario["id_punto_emision"]}");
                        if (isset($pt_em_b[0]->id_bodega) && $pt_em_b[0]->id_bodega !== null) {
                            $pdb = new ProductoBodega();
                            $pdb->cantidad = 0 - $request->productos[$a]["cantidad"];
                            $pdb->costo_unitario = $request->productos[$a]["precio"];
                            $pdb->costo_total = $pdb->cantidad * $request->productos[$a]["precio"];
                            $pdb->id_producto = $request->productos[$a]["id_producto"];
                            $pdb->id_bodega = $pt_em_b[0]->id_bodega;
                            $pdb->id_empresa = $request->usuario["id_empresa"];
                            $pdb->save();
                            $id_pr_bodega = "";
                            $id_pr_bodega = $pdb->id_producto_bodega;
                            $bd_egreso_cabecera = DB::select("SELECT * from bodega_egreso where id_factura={$id} and id_empresa={$request->usuario["id_empresa"]}");
                            if (count($bd_egreso_cabecera) > 0) {
                                $bd_cabecera = $bd_egreso_cabecera[0]->id_bodega_egreso;
                            } else {
                                if ($savebode2 == 0) {
                                    $egreso = new BodegaEgreso();
                                    $egreso->num_egreso = $numeroegreso;
                                    $egreso->fecha_egreso = $hoy;
                                    $egreso->tipo_egreso = "Egreso de Factura";
                                    $egreso->observ_egreso = 'Factura Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
                                    $egreso->id_proyecto = $request->productos[$a]["proyecto"];

                                    $egreso->id_bodega = $pt_em_b[0]->id_bodega;

                                    $egreso->id_empresa = $request->usuario["id_empresa"];
                                    $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                                    $egreso->id_factura = $id;
                                    $egreso->save();

                                    $bd_cabecera = $egreso->id_bodega_egreso;
                                    $savebode2++;
                                }
                            }

                            $bed = new BodegaEgresoDetalle();
                            $bed->cantidad = $request->productos[$a]["cantidad"];
                            $bed->costo_unitario = $request->productos[$a]["precio"];
                            $bed->costo_total = $bed->cantidad * $bed->costo_unitario;
                            $bed->id_bodega_egreso = $bd_cabecera;
                            $bed->id_producto = $request->productos[$a]["id_producto"];
                            $bed->id_proyecto = $request->productos[$a]["proyecto"];
                            $bed->id_detalle = $detalle_id_d;
                            $bed->save();
                            if ($id_pr_bodega !== "") {
                                DB::update("UPDATE detalle SET id_producto_bodega={$id_pr_bodega} WHERE id_detalle = $detalle_id_d");
                            }
                        }
                    }
                }
                //verifica si es un detalle de la guia nueva
                if ($request->guia) {
                    $detguia = new DetalleGuiaRemision();
                    $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                    $detguia->descripcion = $request->productos[$a]["nombre"];
                    $detguia->cantidad = $request->productos[$a]["cantidad"];
                    $detguia->id_producto = $request->productos[$a]["id_producto"];
                    $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                    $detguia->id_guia_remision = $idt;
                    $detguia->save();
                }
            }
            //borra los productos que han sido borrados del detalle de la factura
            if (count($detalles_existentes) >= 1) {
                $bsbs = "SELECT * FROM detalle WHERE id_factura = $id AND ";
                foreach ($detalles_existentes as $dt_id) {
                    $bsbs .= "id_detalle != $dt_id AND ";
                }
                $res_bsbs = substr($bsbs, 0, -4);
                //dd($res_bsbs);
                $seldbs = DB::select($res_bsbs);
                if ($seldbs) {
                    for ($i = 0; $i < count($seldbs); $i++) {
                        $rescuse_id = DB::select("SELECT * FROM detalle WHERE id_detalle = " . $seldbs[$i]->id_detalle);
                        if (isset($rescuse_id[0]->id_producto_bodega)) {
                            $rescuse_id_r = $rescuse_id[0]->id_producto_bodega;
                            $rescuse_id_c = $rescuse_id[0]->cantidad;
                            DB::update("UPDATE producto_bodega SET cantidad = cantidad + $rescuse_id_c, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $rescuse_id_r");
                        }
                        DB::delete("DELETE FROM bodega_egreso_detalle WHERE id_detalle = " . $seldbs[$i]->id_detalle);
                        DB::delete("DELETE FROM detalle WHERE id_detalle = " . $seldbs[$i]->id_detalle);
                    }
                }
            }
        } else {
            //si la factura existe edita la cabecera
            $factura = Factura::findOrFail($request->factura["id_factura"]);
            $factura->observacion = $request->factura["observacion"];
            $factura->orden_compra = $request->factura["orden_compra"];
            $factura->migo = $request->factura["migo"];
            $factura->id_vendedor = $request->factura["vendedor"];
            $factura->save();

            $id = $request->factura["id_factura"];
            //verifica si la guia existe y si esta con datos ademas de que la respuesta no haya sido enviada
            if ($request->guia) {
                if ($request->factura["respuesta_guia"] != "Enviado") {
                    //verifica si la guia ya existia antes de editar
                    if (isset($request->transportista['id'])) {
                        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
                            $transportistas = FacturaGuiaDeRemision::findOrFail($request->transportista['id']);
                            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
                            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
                            $transportistas->respuesta = "ERROR";
                            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
                            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
                            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
                            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
                            $transportistas->placa_tr = $request->transportista['placa_transporte'];
                            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
                            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
                            $transportistas->cod_sustento_tr = 1;
                            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_factura = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_cliente = $request->cliente;
                            $transportistas->id_user = $request->usuario["id"];
                            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
                            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
                            $transportistas->save();
                            $idt = $request->transportista['id'];
                        }
                        //caso contrario crea nueva guia la cabecera
                    } else {
                        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
                            $transportistas = new FacturaGuiaDeRemision();
                            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
                            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
                            $transportistas->respuesta = "ERROR";
                            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
                            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
                            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
                            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
                            $transportistas->placa_tr = $request->transportista['placa_transporte'];
                            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
                            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
                            $transportistas->cod_sustento_tr = 1;
                            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_factura = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_cliente = $request->cliente;
                            $transportistas->id_user = $request->usuario["id"];
                            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
                            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
                            $transportistas->save();
                            $idt = $transportistas->id_guia;

                            $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
                            $sf = $s_facturasubstr + 1;
                            $idp = $request->usuario["id_punto_emision"];
                            DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");
                        }
                        //se guarda los productos en el detalle de guia si es nuevo registro de edición de factura
                        for ($a = 0; $a < count($request->productos); $a++) {
                            if ($request->guia) {
                                $detguia = new DetalleGuiaRemision();
                                $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                                $detguia->descripcion = $request->productos[$a]["nombre"];
                                $detguia->cantidad = $request->productos[$a]["cantidad"];
                                $detguia->id_producto = $request->productos[$a]["id_producto"];
                                $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                                $detguia->id_guia_remision = $idt;
                                $detguia->save();
                            }
                        }
                    }
                }
            }
        }

        //borra las retenciones, cuentas y factura_pagos para generar nuevamente
        DB::delete("DELETE FROM retencion_factura WHERE id_factura = $id");
        DB::delete("DELETE FROM ctas_cobrar WHERE id_factura = $id AND tipo = 2");
        DB::delete("DELETE FROM factura_pagos WHERE id_factura = $id AND estado = 1");

        //selecciona las cuentas por cobrar de tipo 1 de (credito)
        $ctas = DB::select("SELECT * FROM ctas_cobrar WHERE id_factura = $id AND tipo = 1");
        $datos = [];

        //recorre las referencias de pagos al existit
        for ($g = 0; $g < count($ctas); $g++) {
            $id_cta = $ctas[$g]->id_ctascobrar;
            $res = DB::select("SELECT ctas_cobrar_pagos.* FROM ctas_cobrar_pagos,cliente WHERE cliente.id_cliente=ctas_cobrar_pagos.id_cliente and cliente.id_empresa={$request->usuario['id_empresa']} and referencia LIKE '%;{$id_cta};%'");
            for ($f = 0; $f < count($res); $f++) {
                $ref = explode(";", $res[$f]->referencia);
                for ($i = 0; $i < count($ref); $i++) {
                    if ($i % 4 == 1) {
                        array_push($datos, $ref[$i]);
                    }
                }
            }
        }

        //verifica si existe algun pago si existe pago no se podra borrar las cuentas por cobrar
        if (count($datos) == 0) {
            //borra las cuentas por cobrar y la factura de pago que sean créditos
            DB::delete("DELETE FROM factura_pagos WHERE id_factura = $id AND estado = 2");
            DB::delete("DELETE FROM ctas_cobrar WHERE id_factura = $id AND tipo = 1");
            //verifica si existe creditos para guardar
            if (isset($request->creditos)) {
                if (isset($request->creditos["estado"])) {
                    if ($request->creditos["estado"]) {
                        //genera a factura apgos
                        $pag = new Factura_pagos();
                        $pag->id_forma_pagos = null;
                        $pag->total = $request->creditos["monto"];
                        $pag->plazo = $request->creditos["plazos"];
                        $pag->unidad_tiempo = $request->creditos["periodo"];
                        $pag->tiempos_pagos = $request->creditos["tiempo"];
                        $pag->estado = 2;
                        $pag->fecha = $hoy;
                        $pag->id_factura = $id;
                        $pag->id_banco = null;
                        $pag->id_plan_cuentas = null;
                        if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                            $pag->existe_interes =  $request->exist_interes;
                            $pag->capital_monto =  $request->capital_monto;
                            $pag->interes = $request->interes_monto;
                            $pag->total_pagar_interes = $request->total_interes_saldo;
                        }
                        $pag->save();

                        //guarda la cuenta por cobrar
                        $hoy = Carbon::parse($request->factura["fecha_emision"]);
                        $fd = "";
                        for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                            $cxc = new Cuentaporcobrar();
                            $cxc->num_cuota = $a + 1;
                            //verifica el perido de guardado y agrega dependiendo el periodo la suma a la fecha deñ registro
                            if ($request->creditos["periodo"] == "Años") {
                                $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                            } else if ($request->creditos["periodo"] == "Meses") {
                                $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                            } else if ($request->creditos["periodo"] == "Semanas") {
                                $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                            } else {
                                $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
                            }
                            $cxc->fecha_pago = $fd;
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            $cxc->periodo_pagos = $request->creditos["periodo"];
                            if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                                $cxc->valor_cuota = round($request->pagoletra, 2, PHP_ROUND_HALF_UP);
                            } else {
                                $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                            }
                            //$cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                            $cxc->valor_pagado = 0;
                            $cxc->estado = 1;
                            $cxc->tipo = 1;
                            $cxc->id_factura = $id;
                            $cxc->id_cliente = $request->cliente;
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            $cxc->created_by = session()->get('usuariosesion')['id'];
                            $cxc->updated_by = session()->get('usuariosesion')['id'];
                            $cxc->save();
                        }
                    }
                }
            }
        }
        //verifica si existe pagos en la factura
        if (isset($request->pagos)) {
            if (isset($request->pagos["estado"])) {
                if ($request->pagos["estado"]) {
                    $anticipo_creado = $request->anticipo_creado;
                    serialize($anticipo_creado);
                    //verifica si es anticipo, si es anticipo genera la actualización del anticipo
                    if (isset($anticipo_creado)) {
                        if ($anticipo_creado > 0) {
                            $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $cliente AND tipo=3 ORDER BY id_ctascobrar DESC");
                            //recorre los anticipos y actualiza si es anticipo parcial resta del anticipo creado caso contrario guarda el anticipo y el anticipo creado queda en 0
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctascobrar;
                                if ($anticipo_creado > $abono[$i]->valor_pagado) {
                                    $pagado = $abono[$i]->abono;

                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = 0;
                                    $cpc->abono = $cpc->abono + $abono[$i]->valor_pagado;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $anticipo_creado = $anticipo_creado - $abono[$i]->valor_pagado;
                                } else {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado - $anticipo_creado;
                                    $cpc->abono = $cpc->abono + $anticipo_creado;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $anticipo_creado = 0;
                                }
                            }
                        }
                    }

                    //recorre los pagos
                    for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                        if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                            $pag = new Factura_pagos();
                            $pag->id_forma_pagos = null;
                            $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                            $pag->plazo = 1;
                            $pag->unidad_tiempo = 'Dias';
                            $pag->estado = 1;
                            $pag->fecha = $hoy;
                            $pag->id_factura = $id;
                            $pag->tiempos_pagos = 1;
                            $pag->anticipo = 1;
                            $pag->save();

                            $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $cliente AND tipo=3 ORDER BY id_ctascobrar ASC");
                            //recorre los anticipos y actualiza si es anticipo parcial resta del anticipo creado caso contrario guarda el anticipo y el anticipo creado queda en 0
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctascobrar;
                                if ($cpago > $abono[$i]->abono) {
                                    $pagado = $abono[$i]->abono;

                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                                    $cpc->abono = 0;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = $cpago - $abono[$i]->abono;
                                } else {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                                    $cpc->abono = $cpc->abono - $cpago;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = 0;
                                }
                            }

                            /*$cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            DB::update("UPDATE ctas_cobrar SET abono = abono - $cpago WHERE id_cliente = $request->cliente AND tipo = 3");*/
                        } else {
                            //si es nievo registro genera una nueva factura pagos y cuenats por cobrar
                            if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                                $pag = new Factura_pagos();
                                $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                                $pag->plazo = 1;
                                $pag->unidad_tiempo = 'Días';
                                $pag->estado = 1;
                                $pag->fecha = $hoy;
                                $pag->id_factura = $id;
                                $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $pag->tiempos_pagos = 1;
                                $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                                $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                                $pag->anticipo = 0;
                                $pag->save();

                                $cxc = new Cuentaporcobrar();
                                $cxc->num_cuota = 1;
                                $cxc->fecha_pago = $hoy;
                                $cxc->periodo_pagos = "Dia";
                                $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                                $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $cxc->valor_pagado = 0;
                                $cxc->fecha_factura = $request->factura["fecha_emision"];
                                $cxc->estado = 1;
                                $cxc->tipo = 2;
                                $cxc->id_factura = $id;
                                $cxc->id_cliente = $request->cliente;
                                $cxc->created_by = session()->get('usuariosesion')['id'];
                                $cxc->updated_by = session()->get('usuariosesion')['id'];
                                $cxc->save();
                            }
                        }
                    }
                }
            }
        }

        //verifica si la retencion de estado existe ya sea de iva o de renta
        if (isset($request->retencion_estado)) {
            if ($request->retencion_estado) {
                for ($i = 0; $i <= count($request->valorretenciones); $i++) {
                    if (isset($request->valorretenciones[$i]["iva"]) || isset($request->valorretenciones[$i]["renta"])) {
                        $ret = new Retencion_factura();
                        $ret->id_factura = $id;
                        if (isset($request->valorretenciones[$i]["iva"]["id_retencion"])) {
                            $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                        }
                        if (isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                            $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                        }
                        if (isset($request->valorretenciones[$i]["iva"]["id_retencion"])) {
                            $ret->baseiva = $request->valorretenciones[$i]["baseiva"];
                            $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                            $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                        }
                        if (isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                            $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                            $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                            $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                        }
                        $ret->save();
                    }
                }
            }
        }

        //guarda las cuotas extras
        if (isset($request->cuota_extra)) {
            if (isset($request->cuota_extra["estado"])) {
                if ($request->cuota_extra["estado"]) {
                    for ($a = 0; $a < count($request->cuota_extra["datos"]); $a++) {
                        if (isset($request->cuota_extra["datos"][$a]["id_cuota_extra"])) {
                            $cex = CuotaExtraFactura::find($request->cuota_extra["datos"][$a]["id_cuota_extra"]);
                            $cex->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                            $cex->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                            $cex->estado = 'Activo';
                            $cex->umodifica = $request->usuario["id"];
                            //$cex->id_factura=$id;
                            $cex->save();
                        } else {
                            $cex = new CuotaExtraFactura();
                            $cex->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                            $cex->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                            $cex->estado = 'Activo';
                            $cex->ucrea = $request->usuario["id"];
                            $cex->id_factura = $id;
                            $cex->save();

                            $cxc = new Cuentaporcobrar();
                            $cxc->num_cuota = $a + 1;
                            
                            $cxc->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                            $cxc->periodo_pagos = "Dias";
                            $cxc->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                            
                            $cxc->valor_pagado = 0;
                            $cxc->estado = 1;
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            $cxc->tipo = 1;
                            $cxc->id_factura = $id;
                            $cxc->id_cliente = $request->cliente;
                            $cxc->id_empresa = $request->usuario["id_empresa"];
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            $cxc->created_by = session()->get('usuariosesion')['id'];
                            $cxc->updated_by = session()->get('usuariosesion')['id'];
                            $cxc->save();
                        }
                    }
                }
            }
        }
        //verifica si existe guia y si existe renta
        $guia_r = "Enviado";
        $factura_r = "Enviado";
        //si existe una actualización de guia
        if (isset($request->factura["respuesta_guia"])) {
            if ($request->factura["respuesta_guia"] != "Enviado") {
                $guia_r = Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                    ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
                    ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
                    ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
                    ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
                    ->where("guia_remision.id_factura", "=", $id)
                    ->orderByRaw('guia_remision.id_guia DESC')->get();
            }
        }
        //si existe una factura edicion genera los registros de fatcura
        if ($valorcompra->respuesta != "Enviado") {
            $factura_r = DB::select("select factura.*, factura.migo as migo_factura, empresa.*, cliente.*, moneda.nomb_moneda as moneda, factura.descuento as descuentototal, establecimiento.codigo as codigoes, punto_emision.codigo as codigope, establecimiento.direccion as direccion_establecimiento FROM factura INNER JOIN empresa ON empresa.id_empresa = factura.id_empresa INNER JOIN cliente ON cliente.id_cliente = factura.id_cliente INNER JOIN establecimiento ON establecimiento.id_establecimiento = factura.id_establecimiento INNER JOIN punto_emision ON punto_emision.id_punto_emision = factura.id_punto_emision INNER JOIN moneda ON moneda.id_moneda = empresa.id_moneda WHERE factura.id_factura = $id");
        }

        //recupera los datos de la factura y guia en caso de existir, caso contrario envia un array vacio
        return [
            "factura" => $factura_r,
            "guia" => $guia_r
        ];
    }
    //actualización de la factura de venta
    public function editar_nota_venta(Request $request)
    {
        ini_set('max_execution_time', 1000);
        //return $request;
        $valorcompra = NotaVenta::where("id_nota_venta", "=", $request->factura["id_factura"])->first();
        //guarda la fecha actual del servidor
        $hoy = Carbon::now();
        //verifica si la factura ya esta enviado al SRI
        if ($valorcompra->respuesta != "Enviado") {
            $clb=substr($request->factura["clave_acceso"],24,15);
            $factura_info=DB::select("SELECT * from nota_venta where id_empresa={$request->usuario["id_empresa"]} and SUBSTR(clave_acceso,25,15) like '%{$clb}%' and id_nota_venta<>{$request->factura["id_factura"]}");
            if(count($factura_info)>0){
                return "existe clave factura";
            }
            //verifica si es una proforma, si es proforma suma el valor del secuencial de factura en punto de emisión
            if ($valorcompra->modo == 0) {
                $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
                $sf = $s_facturasubstr + 1;
                $idp = $request->usuario["id_punto_emision"];
                DB::update("UPDATE punto_emision SET secuencial_nota_venta = '$sf' WHERE id_punto_emision = $idp");
            }

            //actualiza la factura cabecera
            $factura = NotaVenta::findOrFail($request->factura["id_factura"]);
            $factura->modo = 1;
            $factura->estado = 1;
            $factura->ambiente = $request->factura["ambiente"];
            $factura->tipo_emision = $request->factura["tipo_emision"];
            $factura->fecha_emision = $request->factura["fecha_emision"];
            $factura->clave_acceso = $request->factura["clave_acceso"];
            $factura->observacion = $request->factura["observacion"];
            $factura->subtotal_sin_impuesto = $request->subtotal;
            $factura->subtotal_12 = $request->subtotal12;
            $factura->subtotal_0 = $request->subtotal0;
            $factura->subtotal_no_obj_iva = $request->no_impuesto;
            $factura->descuento = $request->descuento;
            $factura->valor_ice = 0;
            $factura->valor_irbpnr = 0;
            $factura->iva_12 = $request->valor12;
            $factura->propina = $request->propinapr;
            $factura->pp_descuento = $request->pp_descuento;
            if (isset($request->exist_interes) && ($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                $factura->financiamiento = $request->interes_monto;
            }
            $factura->valor_total = $request->total;
            $factura->id_cliente = $request->cliente;
            $factura->id_user = $request->usuario["id"];
            $factura->id_punto_emision = $request->usuario["id_punto_emision"];
            //$factura->id_empresa = $request->usuario["id_empresa"];
            $factura->id_establecimiento = $request->usuario["id_establecimiento"];
            $factura->totalpropinaf = 1;
            $factura->orden_compra = $request->factura["orden_compra"];
            $factura->migo = $request->factura["migo"];
            $factura->id_forma_pagos = $request->factura["forma_pago"];
            $factura->id_vendedor = $request->factura["vendedor"];
            $factura->updated_by = session()->get('usuariosesion')['id'];
            $factura->save();

            //recupera el id de factura
            $id = $request->factura["id_factura"];

            //verifica si envia guia
            if ($request->guia) {
                //verifica si la guia fue enviada al SRI
                if ($request->factura["respuesta_guia"] != "Enviado") {
                    if (isset($request->transportista['id'])) {
                        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
                            //Actualiza la cabecera de la guia
                            $transportistas = Guia_remision::findOrFail($request->transportista['id']);
                            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
                            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
                            $transportistas->respuesta = "ERROR";
                            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
                            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
                            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
                            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
                            $transportistas->placa_tr = $request->transportista['placa_transporte'];
                            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
                            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
                            $transportistas->cod_sustento_tr = 1;
                            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_nota_venta = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_cliente = $request->cliente;
                            $transportistas->id_user = $request->usuario["id"];
                            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
                            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
                            $transportistas->save();
                            $idt = $request->transportista['id'];

                            //elimina el detalle de la guia si existiera
                            DB::delete("DELETE FROM `detalle_guia_remision` WHERE id_guia_remision= $idt");
                        }
                    } else {
                        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
                            //si no exisita una guia y se genera en ese momento crea una nueva cabecera de guia
                            $transportistas = new Guia_remision();
                            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
                            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
                            $transportistas->respuesta = "ERROR";
                            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
                            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
                            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
                            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
                            $transportistas->placa_tr = $request->transportista['placa_transporte'];
                            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
                            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
                            $transportistas->cod_sustento_tr = 1;
                            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_nota_venta = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_cliente = $request->cliente;
                            $transportistas->id_user = $request->usuario["id"];
                            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
                            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
                            $transportistas->save();
                            $idt = $transportistas->id_guia;

                            //elimina el detalle de la guia si existiera
                            DB::delete("DELETE FROM `detalle_guia_remision` WHERE id_guia_remision = $idt");

                            //suma mas 1 al secuencial de guia en punto de emision
                            $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
                            $sf = $s_facturasubstr + 1;
                            $idp = $request->usuario["id_punto_emision"];
                            DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");
                        }
                    }
                }
            }
            $savebode = 0;
            $id_bodega_egreso = "";
            //sirve para saber si existe los detalles existentes
            $detalles_existentes = [];
            //si tiene bodega los productos guarda en una variable para guardar en la cabecera de egreso
            for ($a = 0; $a < count($request->productos); $a++) {
                $bdegval = DB::select("SELECT * FROM bodega_egreso WHERE id_nota_venta = $id");
                if ($bdegval) {
                    if ($savebode == 0) {
                        $id_bodega_egreso = $bdegval[0]->id_bodega_egreso;
                        $savebode++;
                    }
                }
            }
            for ($a = 0; $a < count($request->productos); $a++) {
                //verifica si existe el detalle de producto (si es un producto que si exisitia en factura)
                //caso contrario genera nuevo detalle de producto
                if (isset($request->productos[$a]["id_detalle"])) {
                    $rees = DB::select("SELECT * FROM detalle_nota_venta WHERE id_detalle_nota_venta = " . $request->productos[$a]["id_detalle"]);
                    $valer = $rees[0]->cantidad;

                    $detalle = NotaVentaDetalle::findOrFail($request->productos[$a]["id_detalle"]);
                    $detalle->nombre = $request->productos[$a]["nombre"];
                    $detalle->cantidad = $request->productos[$a]["cantidad"];
                    $detalle->precio = $request->productos[$a]["precio"];
                    $detalle->descuento = $request->productos[$a]["descuento"];
                    if ($request->productos[$a]["p_descuento"] == 0) {
                        $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) * $request->productos[$a]["descuento"]) / 100);
                    } else {
                        $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                    }
                    if (isset($request->productos[$a]["precio_sin_iva"])) {
                        $detalle->valor_sin_iva = $request->productos[$a]["precio_sin_iva"];
                    }
                    $detalle->id_iva = $request->productos[$a]["iva"];
                    $detalle->id_ice = $request->productos[$a]["ice"];
                    $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                    $detalle->id_nota_venta = $id;
                    $detalle->id_producto = $request->productos[$a]["id_producto"];
                    if (isset($request->productos[$a]["id_producto_bodega"])) {
                        $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                    }
                    if (isset($request->productos[$a]["proyecto"])) {
                        $detalle->id_proyecto = $request->productos[$a]["proyecto"];
                    }
                    if (isset($request->productos[$a]["total_ice"])) {
                        $detalle->valor_ice = $request->productos[$a]["total_ice"] * $request->productos[$a]["cantidad"];
                    }
                    $detalle->save();

                    $detalle_id_d = $request->productos[$a]["id_detalle"];

                    //verifica si el egreso del producto existe
                    //caso contrario crea el egreso del registro
                    $select_dtt = DB::select("SELECT * FROM bodega_egreso_detalle WHERE id_detalle_nota_venta = " . $detalle_id_d);
                    if (count($select_dtt) <= 0) {
                        if (isset($request->productos[$a]["id_producto_bodega"]) && $request->productos[$a]["id_producto_bodega"] !== null) {
                            $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                            $idbodega = $reses[0]->id_bodega;
                            $costo_unitario = $reses[0]->costo_unitario;

                            $cant = $request->productos[$a]["cantidad"];
                            $idpb = $request->productos[$a]["id_producto_bodega"];
                            DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $idpb");

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
                            //verifica si ya se guardo una vez la cabecera del egreso
                            if ($savebode == 0) {
                                $egreso = new BodegaEgreso();
                                $egreso->num_egreso = $numeroegreso;
                                $egreso->fecha_egreso = $hoy;
                                $egreso->tipo_egreso = "Egreso de Nota Venta";
                                $egreso->observ_egreso = 'Nota Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
                                $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                                $egreso->id_bodega = $idbodega;
                                $egreso->id_empresa = $request->usuario["id_empresa"];
                                $egreso->id_nota_venta = $id;
                                $egreso->save();
                                $id_bodega_egreso = $egreso->id_bodega_egreso;
                                $savebode++;
                            }

                            //guarda el egreso del producto si no existe
                            $bed = new BodegaEgresoDetalle();
                            $bed->cantidad = $request->productos[$a]["cantidad"];
                            $bed->costo_unitario = $costo_unitario;
                            if ($request->productos[$a]["cantidad"] > 0) {
                                $bed->costo_total = $request->productos[$a]["cantidad"] * $costo_unitario;
                            } else {
                                $bed->costo_total = 0;
                            }
                            $bed->id_proyecto = $request->productos[$a]["proyecto"];
                            $bed->id_bodega_egreso = $id_bodega_egreso;
                            $bed->id_producto = $request->productos[$a]["id_producto"];
                            $bed->id_detalle_nota_venta = $request->productos[$a]["id_detalle"];
                            $bed->save();
                            //agrega al array de productos existentes el id del detalle
                            array_push($detalles_existentes, $detalle_id_d);
                        } else {
                            array_push($detalles_existentes, $detalle_id_d);
                        }
                    } else if ($request->productos[$a]["id_producto_bodega"] !== null) {
                        $valorreal = $valer - ($request->productos[$a]["cantidad"]);
                        if ($valorreal != 0) {
                            $cant = $request->productos[$a]["cantidad"];
                            $idpb = $request->productos[$a]["id_producto_bodega"];
                            //actualiza el producto de bodega con la cantidad cambiada del editar de factura
                            DB::update("UPDATE producto_bodega SET cantidad = cantidad + ($valorreal), costo_total = cantidad * costo_unitario  WHERE id_producto_bodega = $idpb");
                            //verifica si el valor se cambio
                            if ($valorreal != 0) {
                                $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                                $idbodega = $reses[0]->id_bodega;
                                $costo_unitario = $reses[0]->costo_unitario;
                                $cantidad_nueva = $request->productos[$a]["cantidad"];
                                //si el valor cambio edita la bodega de egreso del producto con los valores actuales
                                DB::update("UPDATE bodega_egreso_detalle SET cantidad = $cant, costo_total = $cant * costo_unitario WHERE id_detalle_nota_venta = " . $request->productos[$a]["id_detalle"]);
                            }
                        }
                        //agrega al array de productos existentes el id del detalle
                        array_push($detalles_existentes, $detalle_id_d);
                    } else {
                        //agrega al array de productos existentes el id del detalle
                        array_push($detalles_existentes, $detalle_id_d);
                    }
                } else {
                    //si es producto nuevo en el detalle genera un nuevo registro
                    $detalle = new NotaVentaDetalle();
                    $detalle->nombre = $request->productos[$a]["nombre"];
                    $detalle->cantidad = $request->productos[$a]["cantidad"];
                    $detalle->precio = $request->productos[$a]["precio"];
                    $detalle->descuento = $request->productos[$a]["descuento"];
                    if ($request->productos[$a]["p_descuento"] == 0) {
                        $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"])) * $request->productos[$a]["descuento"]) / 100;
                    } else {
                        $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                    }
                    if (isset($request->productos[$a]["precio_sin_iva"])) {
                        $detalle->valor_sin_iva = $request->productos[$a]["precio_sin_iva"];
                    }
                    $detalle->id_iva = $request->productos[$a]["iva"];
                    $detalle->id_ice = $request->productos[$a]["ice"];
                    $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                    $detalle->id_nota_venta = $id;
                    $detalle->id_producto = $request->productos[$a]["id_producto"];
                    if (isset($request->productos[$a]["id_producto_bodega"])) {
                        $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                    }
                    if (isset($request->productos[$a]["proyecto"])) {
                        $detalle->id_proyecto = $request->productos[$a]["proyecto"];
                    }
                    if (isset($request->productos[$a]["total_ice"])) {
                        $detalle->valor_ice = $request->productos[$a]["total_ice"] * $request->productos[$a]["cantidad"];
                    }
                    $detalle->save();

                    $detalle_id_d = $detalle->id_detalle_nota_venta;
                    //verifica si el id producto bodega existe
                    if (isset($request->productos[$a]["id_producto_bodega"]) && $request->productos[$a]["id_producto_bodega"] !== null) {
                        $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                        $idbodega = $reses[0]->id_bodega;
                        $costo_unitario = $reses[0]->costo_unitario;

                        $cant = $request->productos[$a]["cantidad"];
                        $idpb = $request->productos[$a]["id_producto_bodega"];
                        DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $idpb");

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

                        //verifica si la bodega de egreso cabecera existe una vez
                        if ($savebode == 0) {
                            $egreso = new BodegaEgreso();
                            $egreso->num_egreso = $numeroegreso;
                            $egreso->fecha_egreso = $hoy;
                            $egreso->tipo_egreso = "Egreso de Nota Venta";
                            $egreso->observ_egreso = 'Nota Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
                            $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                            $egreso->id_bodega = $idbodega;
                            $egreso->id_empresa = $request->usuario["id_empresa"];
                            $egreso->id_nota_venta = $id;
                            $egreso->save();
                            $id_bodega_egreso = $egreso->id_bodega_egreso;
                            $savebode++;
                        }

                        $bed = new BodegaEgresoDetalle();
                        $bed->cantidad = $request->productos[$a]["cantidad"];
                        $bed->costo_unitario = $costo_unitario;
                        if ($request->productos[$a]["cantidad"] > 0) {
                            $bed->costo_total = $request->productos[$a]["cantidad"] * $costo_unitario;
                        } else {
                            $bed->costo_total = 0;
                        }
                        $bed->id_proyecto = $request->productos[$a]["proyecto"];
                        $bed->id_bodega_egreso = $id_bodega_egreso;
                        $bed->id_producto = $request->productos[$a]["id_producto"];
                        $bed->id_detalle_nota_venta = $detalle_id_d;
                        $bed->save();
                        array_push($detalles_existentes, $detalle_id_d);
                    } else {
                        array_push($detalles_existentes, $detalle_id_d);
                    }
                }
                //verifica si es un detalle de la guia nueva
                if ($request->guia) {
                    $detguia = new DetalleGuiaRemision();
                    $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                    $detguia->descripcion = $request->productos[$a]["nombre"];
                    $detguia->cantidad = $request->productos[$a]["cantidad"];
                    $detguia->id_producto = $request->productos[$a]["id_producto"];
                    $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                    $detguia->id_guia_remision = $idt;
                    $detguia->save();
                }
            }
            //borra los productos que han sido borrados del detalle de la factura
            if (count($detalles_existentes) >= 1) {
                $bsbs = "SELECT * FROM detalle_nota_venta WHERE id_nota_venta = $id AND ";
                foreach ($detalles_existentes as $dt_id) {
                    $bsbs .= "id_detalle_nota_venta != $dt_id AND ";
                }
                $res_bsbs = substr($bsbs, 0, -4);
                //dd($res_bsbs);
                $seldbs = DB::select($res_bsbs);
                if ($seldbs) {
                    for ($i = 0; $i < count($seldbs); $i++) {
                        $rescuse_id = DB::select("SELECT * FROM detalle_nota_venta WHERE id_detalle_nota_venta = " . $seldbs[$i]->id_detalle_nota_venta);
                        if (isset($rescuse_id[0]->id_producto_bodega)) {
                            $rescuse_id_r = $rescuse_id[0]->id_producto_bodega;
                            $rescuse_id_c = $rescuse_id[0]->cantidad;
                            DB::update("UPDATE producto_bodega SET cantidad = cantidad + $rescuse_id_c, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $rescuse_id_r");
                        }
                        DB::delete("DELETE FROM bodega_egreso_detalle WHERE id_detalle_nota_venta = " . $seldbs[$i]->id_detalle_nota_venta);
                        DB::delete("DELETE FROM detalle_nota_venta WHERE id_detalle_nota_venta = " . $seldbs[$i]->id_detalle_nota_venta);
                    }
                }
            }
        } else {
            $clb=substr($request->factura["clave_acceso"],24,15);
            $factura_info=DB::select("SELECT * from nota_venta where id_empresa={$request->usuario["id_empresa"]} and SUBSTR(clave_acceso,25,15) like '%{$clb}%' and id_nota_venta<>{$request->factura["id_factura"]}");
            if(count($factura_info)>0){
                return "existe clave factura";
            }
            if ($valorcompra->modo == 0) {
                $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
                $sf = $s_facturasubstr + 1;
                $idp = $request->usuario["id_punto_emision"];
                DB::update("UPDATE punto_emision SET secuencial_nota_venta = '$sf' WHERE id_punto_emision = $idp");
            }
            //si la factura existe edita la cabecera
            $factura = NotaVenta::findOrFail($request->factura["id_factura"]);
            $factura->observacion = $request->factura["observacion"];
            $factura->orden_compra = $request->factura["orden_compra"];
            $factura->migo = $request->factura["migo"];
            $factura->id_vendedor = $request->factura["vendedor"];
            $factura->updated_by = session()->get('usuariosesion')['id'];
            $factura->save();

            $id = $request->factura["id_factura"];
            //verifica si la guia existe y si esta con datos ademas de que la respuesta no haya sido enviada
            if ($request->guia) {
                if ($request->factura["respuesta_guia"] != "Enviado") {
                    //verifica si la guia ya existia antes de editar
                    if (isset($request->transportista['id'])) {
                        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
                            // use App\Models\DetalleGuiaRemision;
                            // use App\Models\Factura;
                            // use App\Models\FacturaGuiaDeRemision;
                            // use App\Models\Factura_pagos;
                            // use App\Models\Guia_remision;
                            $transportistas = Guia_remision::findOrFail($request->transportista['id']);
                            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
                            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
                            $transportistas->respuesta = "ERROR";
                            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
                            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
                            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
                            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
                            $transportistas->placa_tr = $request->transportista['placa_transporte'];
                            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
                            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
                            $transportistas->cod_sustento_tr = 1;
                            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_nota_venta = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_cliente = $request->cliente;
                            $transportistas->id_user = $request->usuario["id"];
                            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
                            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
                            $transportistas->save();
                            $idt = $request->transportista['id'];
                        }
                        //caso contrario crea nueva guia la cabecera
                    } else {
                        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
                            $transportistas = new Guia_remision();
                            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
                            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
                            $transportistas->respuesta = "ERROR";
                            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
                            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
                            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
                            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
                            $transportistas->placa_tr = $request->transportista['placa_transporte'];
                            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
                            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
                            $transportistas->cod_sustento_tr = 1;
                            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_nota_venta = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
                            $transportistas->id_cliente = $request->cliente;
                            $transportistas->id_user = $request->usuario["id"];
                            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
                            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
                            $transportistas->save();
                            $idt = $transportistas->id_guia;

                            $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
                            $sf = $s_facturasubstr + 1;
                            $idp = $request->usuario["id_punto_emision"];
                            DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");
                        }
                        //se guarda los productos en el detalle de guia si es nuevo registro de edición de factura
                        for ($a = 0; $a < count($request->productos); $a++) {
                            if ($request->guia) {
                                $detguia = new DetalleGuiaRemision();
                                $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                                $detguia->descripcion = $request->productos[$a]["nombre"];
                                $detguia->cantidad = $request->productos[$a]["cantidad"];
                                $detguia->id_producto = $request->productos[$a]["id_producto"];
                                $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                                $detguia->id_guia_remision = $idt;
                                $detguia->save();
                            }
                        }
                    }
                }
            }
        }

        //borra las retenciones, cuentas y factura_pagos para generar nuevamente
        DB::delete("DELETE FROM retencion_nota_venta WHERE id_nota_venta = $id");
        DB::delete("DELETE FROM ctas_cobrar WHERE id_nota_venta = $id AND tipo = 2");
        DB::delete("DELETE FROM nota_venta_pagos WHERE id_nota_venta = $id AND estado = 1");

        //selecciona las cuentas por cobrar de tipo 1 de (credito)
        $ctas = DB::select("SELECT * FROM ctas_cobrar WHERE id_nota_venta = $id AND tipo = 1");
        $datos = [];

        //recorre las referencias de pagos al existit
        for ($g = 0; $g < count($ctas); $g++) {
            $id_cta = $ctas[$g]->id_ctascobrar;
            $res = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE referencia LIKE '%$id_cta%'");
            for ($f = 0; $f < count($res); $f++) {
                $ref = explode(";", $res[$f]->referencia);
                for ($i = 0; $i < count($ref); $i++) {
                    if ($i % 4 == 1) {
                        array_push($datos, $ref[$i]);
                    }
                }
            }
        }

        //verifica si existe algun pago si existe pago no se podra borrar las cuentas por cobrar
        if (count($datos) == 0) {
            //borra las cuentas por cobrar y la factura de pago que sean créditos
            DB::delete("DELETE FROM nota_venta_pagos WHERE id_nota_venta = $id AND estado = 2");
            DB::delete("DELETE FROM ctas_cobrar WHERE id_nota_venta = $id AND tipo = 1");
            //verifica si existe creditos para guardar
            if (isset($request->creditos)) {
                if (isset($request->creditos["estado"])) {
                    if ($request->creditos["estado"]) {
                        //genera a nota_venta apgos
                        $pag = new NotaVentaPago();
                        $pag->id_forma_pagos = null;
                        $pag->total = $request->creditos["monto"];
                        $pag->plazo = $request->creditos["plazos"];
                        $pag->unidad_tiempo = $request->creditos["periodo"];
                        $pag->tiempos_pagos = $request->creditos["tiempo"];
                        $pag->estado = 2;
                        $pag->fecha = $hoy;
                        $pag->id_nota_venta = $id;
                        $pag->id_banco = null;
                        $pag->id_plan_cuentas = null;
                        if (isset($request->exist_interes) && ($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                            $pag->existe_interes =  $request->exist_interes;
                            $pag->capital_monto =  $request->capital_monto;
                            $pag->interes = $request->interes_monto;
                            $pag->total_pagar_interes = $request->total_interes_saldo;
                        }
                        $pag->save();

                        //guarda la cuenta por cobrar
                        $hoy = Carbon::parse($request->factura["fecha_emision"]);
                        $fd = "";
                        for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                            $cxc = new Cuentaporcobrar();
                            $cxc->num_cuota = $a + 1;
                            //verifica el perido de guardado y agrega dependiendo el periodo la suma a la fecha deñ registro
                            if ($request->creditos["periodo"] == "Años") {
                                $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                            } else if ($request->creditos["periodo"] == "Meses") {
                                $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                            } else if ($request->creditos["periodo"] == "Semanas") {
                                $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                            } else {
                                $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
                            }
                            $cxc->fecha_pago = $fd;
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            $cxc->periodo_pagos = $request->creditos["periodo"];
                            if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                                $cxc->valor_cuota = round($request->pagoletra, 2, PHP_ROUND_HALF_UP);
                            } else {
                                $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                            }
                            //$cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                            $cxc->valor_pagado = 0;
                            $cxc->estado = 1;
                            $cxc->tipo = 1;
                            $cxc->id_nota_venta = $id;
                            $cxc->id_cliente = $request->cliente;
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            $cxc->created_by = session()->get('usuariosesion')['id'];
                            $cxc->updated_by = session()->get('usuariosesion')['id'];
                            $cxc->save();
                        }
                    }
                }
            }
        }
        //verifica si existe pagos en la factura
        if (isset($request->pagos)) {
            if (isset($request->pagos["estado"])) {
                if ($request->pagos["estado"]) {
                    $anticipo_creado = $request->anticipo_creado;
                    serialize($anticipo_creado);
                    //verifica si es anticipo, si es anticipo genera la actualización del anticipo
                    if (isset($anticipo_creado)) {
                        if ($anticipo_creado > 0) {
                            $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $cliente AND tipo=3 ORDER BY id_ctascobrar DESC");
                            //recorre los anticipos y actualiza si es anticipo parcial resta del anticipo creado caso contrario guarda el anticipo y el anticipo creado queda en 0
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctascobrar;
                                if ($anticipo_creado > $abono[$i]->valor_pagado) {
                                    $pagado = $abono[$i]->abono;

                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = 0;
                                    $cpc->abono = $cpc->abono + $abono[$i]->valor_pagado;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $anticipo_creado = $anticipo_creado - $abono[$i]->valor_pagado;
                                } else {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado - $anticipo_creado;
                                    $cpc->abono = $cpc->abono + $anticipo_creado;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $anticipo_creado = 0;
                                }
                            }
                        }
                    }

                    //recorre los pagos
                    for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                        if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                            $pag = new NotaVetaPago();
                            $pag->id_forma_pagos = null;
                            $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                            $pag->plazo = 1;
                            $pag->unidad_tiempo = 'Dias';
                            $pag->estado = 1;
                            $pag->fecha = $hoy;
                            $pag->id_nota_venta = $id;
                            $pag->tiempos_pagos = 1;
                            $pag->anticipo = 1;
                            $pag->save();

                            $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $cliente AND tipo=3 ORDER BY id_ctascobrar ASC");
                            //recorre los anticipos y actualiza si es anticipo parcial resta del anticipo creado caso contrario guarda el anticipo y el anticipo creado queda en 0
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctascobrar;
                                if ($cpago > $abono[$i]->abono) {
                                    $pagado = $abono[$i]->abono;

                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                                    $cpc->abono = 0;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = $cpago - $abono[$i]->abono;
                                } else {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                                    $cpc->abono = $cpc->abono - $cpago;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = 0;
                                }
                            }

                            /*$cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            DB::update("UPDATE ctas_cobrar SET abono = abono - $cpago WHERE id_cliente = $request->cliente AND tipo = 3");*/
                        } else {
                            //si es nievo registro genera una nueva factura pagos y cuenats por cobrar
                            if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                                $pag = new NotaVentaPago();
                                $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                                $pag->plazo = 1;
                                $pag->unidad_tiempo = 'Días';
                                $pag->estado = 1;
                                $pag->fecha = $hoy;
                                $pag->id_nota_venta = $id;
                                $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $pag->tiempos_pagos = 1;
                                $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                                $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                                $pag->anticipo = 0;
                                $pag->save();

                                $cxc = new Cuentaporcobrar();
                                $cxc->num_cuota = 1;
                                $cxc->fecha_pago = $hoy;
                                $cxc->periodo_pagos = "Dia";
                                $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                                $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $cxc->valor_pagado = 0;
                                $cxc->fecha_factura = $request->factura["fecha_emision"];
                                $cxc->estado = 1;
                                $cxc->tipo = 2;
                                $cxc->id_nota_venta = $id;
                                $cxc->id_cliente = $request->cliente;
                                $cxc->created_by = session()->get('usuariosesion')['id'];
                                $cxc->updated_by = session()->get('usuariosesion')['id'];
                                $cxc->save();
                            }
                        }
                    }
                }
            }
        }

        //verifica si la retencion de estado existe ya sea de iva o de renta
        if (isset($request->retencion_estado)) {
            if ($request->retencion_estado) {
                for ($i = 0; $i <= count($request->valorretenciones); $i++) {
                    if (isset($request->valorretenciones[$i]["iva"]) || isset($request->valorretenciones[$i]["renta"])) {
                        $ret = new NotaVentaRetencion();
                        $ret->id_nota_venta = $id;
                        if (isset($request->valorretenciones[$i]["iva"]["id_retencion"])) {
                            $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                        }
                        if (isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                            $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                        }
                        if (isset($request->valorretenciones[$i]["iva"]["id_retencion"])) {
                            $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                            $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                        }
                        if (isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                            $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                            $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                            $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                        }
                        $ret->save();
                    }
                }
            }
        }
        //guarda las cuotas extras
        if (isset($request->cuota_extra)) {
            if (isset($request->cuota_extra["estado"])) {
                if ($request->cuota_extra["estado"]) {
                    for ($a = 0; $a < count($request->cuota_extra["datos"]); $a++) {
                        if (isset($request->cuota_extra["datos"][$a]["id_cuota_extra"])) {
                            $cex = CuotaExtraNotaVenta::find($request->cuota_extra["datos"][$a]["id_cuota_extra"]);
                            $cex->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                            $cex->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                            $cex->estado = 'Activo';
                            $cex->umodifica = $request->usuario["id"];
                            //$cex->id_factura=$id;
                            $cex->save();
                        } else {
                            $cex = new CuotaExtraNotaVenta();
                            $cex->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                            $cex->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                            $cex->estado = 'Activo';
                            $cex->ucrea = $request->usuario["id"];
                            $cex->id_nota_venta = $id;
                            $cex->save();

                            $cxc = new Cuentaporcobrar();
                            $cxc->num_cuota = $a + 1;
                            
                            $cxc->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                            $cxc->periodo_pagos = "Dias";
                            $cxc->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                            
                            $cxc->valor_pagado = 0;
                            $cxc->estado = 1;
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            $cxc->tipo = 1;
                            $cxc->id_factura = $id;
                            $cxc->id_cliente = $request->cliente;
                            $cxc->id_empresa = $request->usuario["id_empresa"];
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            $cxc->created_by = session()->get('usuariosesion')['id'];
                            $cxc->updated_by = session()->get('usuariosesion')['id'];
                            $cxc->save();
                        }
                    }
                }
            }
        }
        //     //verifica si existe guia y si existe renta
        $guia_r = "Enviado";
        $factura_r = "Enviado";
        //     //si existe una actualización de guia
        if (isset($request->factura["respuesta_guia"])) {
            if ($request->factura["respuesta_guia"] != "Enviado") {
                $guia_r = Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                    ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
                    ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
                    ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
                    ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
                    ->where("guia_remision.id_nota_venta", "=", $id)
                    ->orderByRaw('guia_remision.id_guia DESC')->get();
            }
        }
        //     //si existe una factura edicion genera los registros de fatcura
        //     if ($valorcompra->respuesta != "Enviado") {
        //         $factura_r = DB::select("select factura.*, empresa.*, cliente.*, moneda.nomb_moneda as moneda, factura.descuento as descuentototal, establecimiento.codigo as codigoes, punto_emision.codigo as codigope, establecimiento.direccion as direccion_establecimiento FROM factura INNER JOIN empresa ON empresa.id_empresa = factura.id_empresa INNER JOIN cliente ON cliente.id_cliente = factura.id_cliente INNER JOIN establecimiento ON establecimiento.id_establecimiento = factura.id_establecimiento INNER JOIN punto_emision ON punto_emision.id_punto_emision = factura.id_punto_emision INNER JOIN moneda ON moneda.id_moneda = empresa.id_moneda WHERE factura.id_factura = $id");
        //     }

        //     //recupera los datos de la factura y guia en caso de existir, caso contrario envia un array vacio
        return [
            "factura" => $factura_r,
            "guia" => $guia_r
        ];
    }
    //crea una nota de venta a partir de una proforma
    public function notaventa_proforma(Request $request)
    {
        ini_set('max_execution_time', 1000);
        $clb=substr($request->factura["clave_acceso"],24,15);
        $factura_info=DB::select("SELECT * from nota_venta where id_empresa={$request->usuario["id_empresa"]} and SUBSTR(clave_acceso,25,15) like '%{$clb}%' ");
        if(count($factura_info)>0){
            return "existe clave factura";
        }
        //guarda la cabecera de la factura
        $hoy = Carbon::now();
        $factura = new NotaVenta();
        $factura->modo = 1;
        $factura->estado = 1;
        //$factura->modo_acumulado = 1;
        $factura->ambiente = $request->factura["ambiente"];
        $factura->tipo_emision = $request->factura["tipo_emision"];
        $factura->fecha_emision = $request->factura["fecha_emision"];
        $factura->clave_acceso = $request->factura["clave_acceso"];
        $factura->observacion = $request->factura["observacion"];
        $factura->subtotal_sin_impuesto = $request->subtotal;
        $factura->subtotal_12 = $request->subtotal12;
        $factura->subtotal_0 = $request->subtotal0;
        $factura->subtotal_no_obj_iva = $request->no_impuesto;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = 0;
        $factura->valor_irbpnr = 0;
        $factura->iva_12 = $request->valor12;
        $factura->propina = $request->propinapr;
        $factura->pp_descuento = $request->pp_descuento;
        $factura->estatus = 0;
        $factura->valor_total = $request->total;
        $factura->id_cliente = $request->cliente;
        $factura->id_user = $request->usuario["id"];
        $factura->id_punto_emision = $request->usuario["id_punto_emision"];
        $factura->id_empresa = $request->usuario["id_empresa"];
        $factura->id_establecimiento = $request->usuario["id_establecimiento"];
        $factura->totalpropinaf = 1;
        $factura->orden_compra = $request->factura["orden_compra"];
        $factura->migo = $request->factura["migo"];
        $factura->id_forma_pagos = $request->factura["forma_pago"];
        $factura->id_vendedor = $request->factura["vendedor"];
        $factura->created_by = session()->get('usuariosesion')['id'];
        $factura->updated_by = session()->get('usuariosesion')['id'];
        $factura->save();

        //reucpera el id de la cabecera
        $id = $factura->id_nota_venta;

        //recupera el número del secuencial de la factura y agrega uno mas en punto de emision
        $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
        $sf = $s_facturasubstr + 1;
        $idp = $request->usuario["id_punto_emision"];
        DB::update("UPDATE punto_emision SET secuencial_nota_venta = '$sf' WHERE id_punto_emision = $idp");

        //si existe una guia y el número de transporte genera la cabecera de la guia de remisión
        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
            $transportistas = new Guia_remision();
            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
            $transportistas->respuesta = "ERROR";
            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
            $transportistas->placa_tr = $request->transportista['placa_transporte'];
            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
            $transportistas->cod_sustento_tr = 1;
            $transportistas->id_empresa = $request->usuario["id_empresa"]; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_nota_venta = $id; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_cliente = $request->cliente;
            $transportistas->id_user = $request->usuario["id"];
            $transportistas->id_punto_emision = $request->usuario["id_punto_emision"];
            $transportistas->id_establecimiento = $request->usuario["id_establecimiento"];
            $transportistas->save();
            $idt = $transportistas->id_guia;

            //recupera el número del secuencial de la guia y agrega uno mas en punto de emision
            $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
            $sf = $s_facturasubstr + 1;
            $idp = $request->usuario["id_punto_emision"];
            DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");
        }

        $idempresa = $request->usuario["id_empresa"];
        //registro de egreso
        //seleccióna la ultima bodega de egreso que se creo
        $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $idempresa ORDER BY  num_egreso DESC LIMIT 1;");
        $numeroegreso = "";
        //si existe la bodega_egreso cuenta el ultimo num_egreso del registro caso contrario comienza el contador desde 1
        if (count($numegre) == 1) {
            $dato = $numegre[0]->num_egreso;
            $tot = $dato + 1;
            $numeroegreso = $tot;
        } else {
            $numeroegreso = 1;
        }
        $savebode = 0;
        $id_bodega_egreso = "";
        //recorre los productos de la factura
        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new NotaVentaDetalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = number_format($request->productos[$a]["precio"], 2, ".", "");
            $detalle->descuento = $request->productos[$a]["descuento"];
            if (isset($request->productos[$a]["precio_sin_iva"])) {
                $detalle->valor_sin_iva = $request->productos[$a]["precio_sin_iva"];
            }

            if ($request->productos[$a]["p_descuento"] == 0) {
                $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 2, ".", "")) - (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 2, ".", "")) * $request->productos[$a]["descuento"]) / 100);
            } else {
                $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 2, ".", "")) - $request->productos[$a]["descuento"]);
            }
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_nota_venta = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            if (isset($request->productos[$a]["id_producto_bodega"])) {
                $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
            }
            if (isset($request->productos[$a]["proyecto"])) {
                $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            }
            if (isset($request->productos[$a]["total_ice"])) {
                $detalle->valor_ice = $request->productos[$a]["total_ice"];
            }
            $detalle->save();

            $id_detalle = $detalle->id_detalle_nota_venta;
            //si existe id de producto bodega ingresa a la siguiente sentencia
            if (isset($request->productos[$a]["id_producto_bodega"])) {
                $cant = $request->productos[$a]["cantidad"];
                $idpb = $request->productos[$a]["id_producto_bodega"];
                //actualiza la cantidad de producto bodega restando los productos que se creo en ese momento
                DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant, costo_total = cantidad * costo_unitario WHERE id_producto_bodega = $idpb");

                $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                $costo_unitario = $reses[0]->costo_unitario;
                //verifica si ya se guardo una vez la cabecera del egreso
                if ($savebode == 0) {
                    $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                    $idbodega = $reses[0]->id_bodega;
                    $egreso = new BodegaEgreso();
                    $egreso->num_egreso = $numeroegreso;
                    $egreso->fecha_egreso = $hoy;
                    $egreso->tipo_egreso = "Egreso de Nota Venta";
                    $egreso->observ_egreso = 'Nota Venta: ' . substr($request->factura["clave_acceso"], -19, -10);
                    $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    if ($idbodega) {
                        $egreso->id_bodega = $idbodega;
                    }
                    $egreso->id_empresa = $request->usuario["id_empresa"];
                    $egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    $egreso->id_nota_venta = $id;
                    $egreso->save();

                    $id_bodega_egreso = $egreso->id_bodega_egreso;
                    $savebode++;
                }

                //guarda el detalle de los egreso con los productos de la factura
                $bed = new BodegaEgresoDetalle();
                $bed->cantidad = $request->productos[$a]["cantidad"];
                $bed->costo_unitario = $costo_unitario;
                $bed->costo_total = $request->productos[$a]["cantidad"] * $costo_unitario;
                $bed->id_bodega_egreso = $id_bodega_egreso;
                $bed->id_producto = $request->productos[$a]["id_producto"];
                $bed->id_proyecto = $request->productos[$a]["proyecto"];
                $bed->id_detalle_nota_venta = $id_detalle;
                $bed->save();
            }

            //si existe la guia recorre la guia con los productos de la factura
            if ($request->guia) {
                $detguia = new DetalleGuiaRemision();
                $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                $detguia->descripcion = $request->productos[$a]["nombre"];
                $detguia->cantidad = $request->productos[$a]["cantidad"];
                $detguia->id_producto = $request->productos[$a]["id_producto"];
                $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                $detguia->id_guia_remision = $idt;
                $detguia->save();
            }
        }

        //guarda los pagos en la tabla de factura pagos y cuentas por cobrar con tipo 2

        //verifica si existe pagos
        if (isset($request->pagos)) {
            if (isset($request->pagos["estado"])) {
                if ($request->pagos["estado"]) {
                    for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                        //verifica si es anticipo caso contrario es un pago normal
                        if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                            $pag = new NotaVentaPago();
                            $pag->id_forma_pagos = null;
                            $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                            $pag->plazo = 1;
                            $pag->unidad_tiempo = 'Dias';
                            $pag->estado = 1;
                            $pag->fecha = $hoy;
                            $pag->id_nota_venta = $id;
                            $pag->tiempos_pagos = 1;
                            $pag->anticipo = 1;
                            $pag->save();

                            $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $cliente AND tipo=3 ORDER BY id_ctascobrar ASC");
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctascobrar;
                                $pagado = $abono[$i]->abono;

                                if ($cpago > $pagado) {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                                    $cpc->abono = 0;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = $cpago - $pagado;
                                } else {
                                    $cpc = Cuentaporcobrar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                                    $cpc->abono = $cpc->abono - $cpago;
                                    $cpc->updated_by = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = 0;
                                }
                            }

                            /*$resct = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $request->cliente AND tipo = 3");
                            if(count($resct)>=1){

                                DB::update("UPDATE ctas_cobrar SET abono = abono - $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
                            }else{
                                $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                                DB::insert("INSERT ctas_cobrar SET abono = $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
                            }*/
                        } else {
                            if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                                $pag = new NotaVentaPago();
                                $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                                $pag->plazo = 1;
                                $pag->unidad_tiempo = 'Días';
                                $pag->estado = 1;
                                $pag->fecha = $hoy;
                                $pag->id_nota_venta = $id;
                                $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $pag->tiempos_pagos = 1;
                                $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                                $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                                $pag->anticipo = 0;
                                $pag->save();

                                $cxc = new Cuentaporcobrar();
                                $cxc->num_cuota = 1;
                                $cxc->fecha_pago = $hoy;
                                $cxc->periodo_pagos = "Dia";
                                $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                                $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                if (isset($request->pagos["datos"][$a]["nro_trans"])) {
                                    $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                $cxc->fecha_factura = $request->factura["fecha_emision"];
                                $cxc->valor_pagado = 0;
                                $cxc->estado = 1;
                                $cxc->tipo = 2;
                                $cxc->id_nota_venta = $id;
                                $cxc->id_cliente = $request->cliente;
                                $cxc->id_empresa = $request->usuario["id_empresa"];
                                $cxc->created_by = session()->get('usuariosesion')['id'];
                                $cxc->updated_by = session()->get('usuariosesion')['id'];
                                $cxc->save();
                            }
                        }
                    }
                }
            }
        }

        //guarda los creditos en la tabla de factura pagos y cuentas por cobrar con tipo 1

        //verifica si existe creditos
        if (isset($request->creditos)) {
            if (isset($request->creditos["estado"])) {
                if ($request->creditos["estado"]) {
                    $pag = new NotaVentaPago();
                    $pag->id_forma_pagos = null;
                    $pag->total = $request->creditos["monto"];
                    $pag->plazo = $request->creditos["plazos"];
                    $pag->unidad_tiempo = $request->creditos["periodo"];
                    $pag->tiempos_pagos = $request->creditos["tiempo"];
                    $pag->estado = 2;
                    $pag->fecha = $hoy;
                    $pag->id_nota_venta = $id;
                    $pag->id_banco = null;
                    $pag->id_plan_cuentas = null;
                    if (isset($request->interes_monto)) {
                        if (($request->exist_interes == 1 || $request->exist_interes == '1') && $request->interes_monto > 0) {
                            $pag->existe_interes =  $request->exist_interes;
                            $pag->interes = $request->interes_monto;
                            $pag->total_pagar_interes = $request->total_interes_saldo;
                        }
                    }

                    $pag->save();

                    $hoy = Carbon::parse($request->factura["fecha_emision"]);
                    $fd = "";
                    for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                        $cxc = new Cuentaporcobrar();
                        $cxc->num_cuota = $a + 1;
                        if ($request->creditos["periodo"] == "Años") {
                            $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Meses") {
                            $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Semanas") {
                            $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                        } else {
                            $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
                        }
                        $cxc->fecha_pago = $fd;
                        $cxc->periodo_pagos = $request->creditos["periodo"];
                        $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                        $cxc->valor_pagado = 0;
                        $cxc->estado = 1;
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->tipo = 1;
                        $cxc->id_nota_venta = $id;
                        $cxc->id_cliente = $request->cliente;
                        $cxc->id_empresa = $request->usuario["id_empresa"];
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->created_by = session()->get('usuariosesion')['id'];
                        $cxc->updated_by = session()->get('usuariosesion')['id'];
                        $cxc->save();
                    }
                }
            }
        }
        //guarda las cuotas extras
        if (isset($request->cuota_extra)) {
            if (isset($request->cuota_extra["estado"])) {
                if ($request->cuota_extra["estado"]) {
                    for ($a = 0; $a < count($request->cuota_extra["datos"]); $a++) {
                        $cex = new CuotaExtraNotaVenta();
                        $cex->valor_cuota = $request->cuota_extra["datos"][$a]["valor_pago"];
                        $cex->fecha_pago = $request->cuota_extra["datos"][$a]["fecha_pago"];
                        $cex->estado = 'Activo';
                        $cex->ucrea = $request->usuario["id"];
                        $cex->id_nota_venta = $id;
                        $cex->save();
                    }
                }
            }
        }
        //guarda las retenciones de la factura ya sea retencion de iva o de renta
        if (isset($request->retencion_estado)) {
            if ($request->retencion_estado) {
                for ($i = 0; $i < count($request->valorretenciones); $i++) {
                    if (isset($request->valorretenciones[$i]["iva"]) || isset($request->valorretenciones[$i]["renta"])) {
                        $ret = new NotaVentaRetencion();
                        $ret->id_nota_venta = $id;
                        $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                        $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];

                        $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                        $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                        $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                        $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                        $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                        $ret->save();
                    }
                }
            }
        }
        //recupera los datos de la empresa y de la factura de venta o de guia dependiendo si existe tal registro
        if (isset($request->id_proforma)) {
            $prof = Factura::findOrFail($request->id_proforma);
            $prof->modo = 2;
            $prof->save();
        }

        return [
            "nota_venta" => NotaVenta::select('nota_venta.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'nota_venta.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'nota_venta.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'nota_venta.id_cliente')
                ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
                ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
                ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
                ->where("nota_venta.id_nota_venta", "=", $id)
                ->orderByRaw('nota_venta.id_nota_venta DESC')->get(),
            "guia" => Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
                ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
                ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
                ->where("guia_remision.id_nota_venta", "=", $id)
                ->orderByRaw('guia_remision.id_guia DESC')->get()
        ];
    }
    public function recuperar($id)
    {

        //recupera los datos generales de una afctura para poder editar los registros
        //de igual forma recupera la guia en caso de existir una guia
        $factura = DB::select("SELECT factura.*,empresa.nombre_empresa, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia FROM factura,empresa WHERE empresa.id_empresa=factura.id_empresa and id_factura = " . $id);
        //cliente
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);
        $pln = 0;
        $seg = 0;
        if (isset($cliente[0]->id_plan_seguro) && $cliente[0]->id_plan_seguro !== null) {
            $pln = $cliente[0]->id_plan_seguro;
        }
        if (isset($cliente[0]->id_seguro) && $cliente[0]->id_seguro !== null) {
            $seg = $cliente[0]->id_seguro;
        }
        $productos = DB::select("SELECT d.*, p.cod_principal, p.cod_alterno, ice.nombre as nombreice, ROUND(p.total_ice,2) AS total_ice,p.sector,pb.cantidad as cantidadreal,$seg as id_seguro,$pln as id_plan_seguro,(select plan_seguro.descuento from plan_seguro_detalle INNER JOIN plan_seguro ON plan_seguro.id_plan_seguro=plan_seguro_detalle.id_plan_seguro INNER JOIN producto ON producto.id_producto=plan_seguro_detalle.id_producto where plan_seguro_detalle.id_plan_seguro=$pln and plan_seguro_detalle.id_producto=p.id_producto and plan_seguro_detalle.agregado=1 limit 1) as descuento_seguro 
        FROM detalle d 
        INNER JOIN producto p ON p.id_producto=d.id_producto
        LEFT JOIN ice ON ice.id_ice = p.ice
        LEFT JOIN producto_bodega pb on pb.id_producto_bodega=d.id_producto_bodega
        WHERE d.id_factura = " . $id);


        $pagos = DB::select("SELECT fp.numero_transaccion AS nro_trans, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta, fp.anticipo FROM factura_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas WHERE fp.estado = 1 AND fp.id_factura = " . $id);
        $creditos = DB::select("SELECT estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto,existe_interes,interes,total_pagar_interes,capital_monto FROM factura_pagos WHERE estado = 2 AND id_factura = " . $id);
        $iva = DB::select("SELECT r.*,rf.baseiva, rf.porcentajeiva, rf.cantidadiva,rf.id_retencion_factura FROM retencion_factura rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_factura = $id ORDER BY rf.id_factura DESC");
        $renta = DB::select("SELECT r.*, rf.baserenta, rf.porcentajerenta,rf.id_retencion_factura FROM retencion_factura rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_factura = $id ORDER BY rf.id_factura DESC");

        $guia = DB::select("SELECT * FROM guia_remision WHERE id_factura = $id ORDER BY id_factura DESC LIMIT 1");

        $cuotas = DB::select("SELECT * from cuota_extra_factura where id_factura=$id");

        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }
        $guias = "";
        if (count($guia)) {
            $guias = $guia[0];
        }

        //retorna con sus valores mediante un objeto los registros
        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'cliente' => $cliente[0],
            'pagos' => $pagos,
            'creditos' => $factura_creditos,
            'iva' => $iva,
            'renta' => $renta,
            'guia' => $guias,
            'cuota_extra' => $cuotas
        ];
    }
    public function recuperar_nota_venta($id)
    {

        //recupera los datos generales de una afctura para poder editar los registros
        //de igual forma recupera la guia en caso de existir una guia
        $factura = DB::select("SELECT *, (SELECT respuesta FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS respuesta_guia FROM nota_venta WHERE id_nota_venta = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);
        $pln = 0;
        $seg = 0;
        if (isset($cliente[0]->id_plan_seguro) && $cliente[0]->id_plan_seguro !== null) {
            $pln = $cliente[0]->id_plan_seguro;
        }
        if (isset($cliente[0]->id_seguro) && $cliente[0]->id_seguro !== null) {
            $seg = $cliente[0]->id_seguro;
        }
        $productos = DB::select("SELECT d.*, p.cod_principal, p.cod_alterno, ice.nombre as nombreice, ROUND(p.total_ice,2) AS total_ice,p.sector,pb.cantidad as cantidadreal,$seg as id_seguro,$pln as id_plan_seguro,(select plan_seguro.descuento from plan_seguro_detalle INNER JOIN plan_seguro ON plan_seguro.id_plan_seguro=plan_seguro_detalle.id_plan_seguro INNER JOIN producto ON producto.id_producto=plan_seguro_detalle.id_producto where plan_seguro_detalle.id_plan_seguro=$pln and plan_seguro_detalle.id_producto=p.id_producto and plan_seguro_detalle.agregado=1 limit 1) as descuento_seguro  
        FROM detalle_nota_venta d 
        INNER JOIN producto p ON p.id_producto=d.id_producto
        LEFT JOIN ice ON ice.id_ice = p.ice
        LEFT JOIN producto_bodega pb on pb.id_producto_bodega=d.id_producto_bodega
        WHERE d.id_nota_venta = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);

        $pagos = DB::select("SELECT fp.numero_transaccion AS nro_trans, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta, fp.anticipo FROM nota_venta_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas WHERE fp.estado = 1 AND fp.id_nota_venta = " . $id);
        $creditos = DB::select("SELECT estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto,existe_interes,interes,total_pagar_interes FROM nota_venta_pagos WHERE estado = 2 AND id_nota_venta = " . $id);
        $iva = DB::select("SELECT r.*, rf.porcentajeiva, rf.cantidadiva FROM retencion_nota_venta rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_nota_venta = $id ORDER BY rf.id_nota_venta DESC");
        $renta = DB::select("SELECT r.*, rf.baserenta, rf.porcentajerenta FROM retencion_nota_venta rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_nota_venta = $id ORDER BY rf.id_nota_venta DESC");

        $guia = DB::select("SELECT * FROM guia_remision WHERE id_nota_venta = $id ORDER BY id_nota_venta DESC LIMIT 1");
        $cuotas=DB::select("SELECT * from cuota_extra_nota_venta WHERE id_nota_venta = $id");

        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }
        $guias = "";
        if (count($guia)) {
            $guias = $guia[0];
        }

        //retorna con sus valores mediante un objeto los registros
        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'cliente' => $cliente[0],
            'pagos' => $pagos,
            'creditos' => $factura_creditos,
            'iva' => $iva,
            'renta' => $renta,
            'guia' => $guias,
            'cuota_extra' => $cuotas
        ];
    }
    public function facturaver($id)
    {

        //es la llamada de la factura unicamente de vista de la factura
        $factura = DB::select("SELECT f.*, pr.descripcion as des_proyecto FROM factura f LEFT JOIN proyecto pr ON f.id_proyecto=pr.id_proyecto WHERE id_factura = " . $id);
        $productos = DB::select("SELECT d.*, p.cod_principal, p.cod_alterno, p.total_ice, pr.descripcion as descripcion_proyecto FROM detalle d INNER JOIN producto p ON p.id_producto=d.id_producto LEFT JOIN proyecto pr ON pr.id_proyecto = d.id_proyecto WHERE d.id_factura = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);

        $pagos = DB::select("SELECT fpa.descripcion as descripcionpagos, b.nombre_banco, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta FROM factura_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas LEFT JOIN forma_pagos fpa ON fpa.id_forma_pagos = fp.id_forma_pagos LEFT JOIN banco b ON b.id_banco = fp.id_banco WHERE fp.estado = 1 AND fp.id_factura = " . $id);
        $creditos = DB::select("SELECT estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto FROM factura_pagos WHERE estado = 2 AND id_factura = " . $id);
        $iva = DB::select("SELECT * FROM retencion_factura rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_factura = " . $id);
        $renta = DB::select("SELECT * FROM retencion_factura rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_factura = " . $id);

        //recupera la primera factura de créditos
        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }

        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'cliente' => $cliente[0],
            'pagos' => $pagos,
            'creditos' => $factura_creditos,
            'iva' => $iva,
            'renta' => $renta,
        ];
    }
    public function nota_ventaver($id)
    {

        //es la llamada de la factura unicamente de vista de la factura
        $factura = DB::select("SELECT f.*, pr.descripcion as des_proyecto FROM nota_venta f LEFT JOIN proyecto pr ON f.id_proyecto=pr.id_proyecto WHERE id_nota_venta = " . $id);
        $productos = DB::select("SELECT d.*, p.cod_principal, p.cod_alterno, p.total_ice, pr.descripcion as descripcion_proyecto FROM detalle_nota_venta d INNER JOIN producto p ON p.id_producto=d.id_producto LEFT JOIN proyecto pr ON pr.id_proyecto = d.id_proyecto WHERE d.id_nota_venta = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);

        $pagos = DB::select("SELECT fpa.descripcion as descripcionpagos, b.nombre_banco, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta FROM nota_venta_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas LEFT JOIN forma_pagos fpa ON fpa.id_forma_pagos = fp.id_forma_pagos LEFT JOIN banco b ON b.id_banco = fp.id_banco WHERE fp.estado = 1 AND fp.id_nota_venta = " . $id);
        $creditos = DB::select("SELECT estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto FROM nota_venta_pagos WHERE estado = 2 AND id_nota_venta = " . $id);
        $iva = DB::select("SELECT * FROM retencion_nota_venta rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_nota_venta = " . $id);
        $renta = DB::select("SELECT * FROM retencion_nota_venta rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_nota_venta = " . $id);

        //recupera la primera factura de créditos
        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }

        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'cliente' => $cliente[0],
            'pagos' => $pagos,
            'creditos' => $factura_creditos,
            'iva' => $iva,
            'renta' => $renta,
        ];
    }
    //funciones para asientos factura
    public function facturaContabilizar(Request $request, $id)
    {

        $empresa = DB::select("SELECT empresa.*,cliente.nombre,cliente.identificacion,cliente.tipo_identificacion from empresa,factura,cliente where cliente.id_empresa=empresa.id_empresa and factura.id_cliente=cliente.id_cliente and factura.id_empresa=empresa.id_empresa and factura.id_factura=" . $id);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $factura = DB::select("SELECT f.*, pr.descripcion as des_proyecto FROM factura f LEFT JOIN proyecto pr ON f.id_proyecto=pr.id_proyecto WHERE id_factura = " . $id . " and f.id_empresa=" . $request->id_empresa);
        //$productos = DB::select("SELECT d.*, p.cod_principal FROM detalle d INNER JOIN producto p ON p.id_producto=d.id_producto WHERE d.id_factura = " . $id);

        $renta_retencion_asiento = DB::select(/*"SELECT concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,
        sum(retencion_factura.cantidadrenta) as total,max(retencion_factura.porcentajerenta) as porcentajeiva,
        round(sum(retencion_factura.cantidadrenta),2) as debe,null as haber,proyecto.descripcion,detalle.id_proyecto*/
            /*"SELECT concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,
        sum(detalle.total) as total,max(retencion_factura.porcentajerenta) as porcentajeiva,
        round(sum(detalle.total)*(max(retencion_factura.porcentajerenta)/100),2) as debe,null as haber,proyecto.descripcion,detalle.id_proyecto
        FROM retencion_factura
        INNER JOIN retencion
        ON retencion.id_retencion=retencion_factura.id_retencion_renta
        INNER JOIN factura
        on factura.id_factura=retencion_factura.id_factura
        INNER JOIN detalle
        on detalle.id_factura=factura.id_factura
        left JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
        LEFT JOIN proyecto
	    on proyecto.id_proyecto=detalle.id_proyecto
        where factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
            GROUP BY detalle.id_proyecto"*/
            "SELECT retencion.id_plan_cuentas,detalle.total,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,retencion_factura.baserenta,
        retencion_factura.porcentajerenta,retencion_factura.cantidadrenta,retencion_factura.cantidadrenta*(detalle.total/factura.subtotal_sin_impuesto) as debe,null as haber,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle.id_proyecto,proyecto.descripcion
                FROM retencion_factura
                INNER JOIN retencion
                ON retencion.id_retencion=retencion_factura.id_retencion_renta
                INNER JOIN factura
                on factura.id_factura=retencion_factura.id_factura
                INNER JOIN detalle
                on detalle.id_factura=factura.id_factura
                left JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
                where factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
        ORDER BY detalle.id_proyecto"
        );

        //if($factura[0]->subtotal_12>0){
        $iva_retencion_asiento = DB::select(/*"SELECT concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
                max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,
                sum(retencion_factura.cantidadiva) as total,
                max(retencion_factura.porcentajeiva) as porcentajeiva,
                        round(sum(retencion_factura.cantidadiva),2) as debe,null as haber,proyecto.descripcion,detalle.id_proyecto*/
            /*"SELECT concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
                        max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,
                                max(producto.iva) as iva,
                        if(max(producto.iva)=2,(sum(detalle.total)*(12/100)),sum(detalle.total)) as total,
                        max(retencion_factura.porcentajeiva) as porcentajeiva,
                               round(if(max(producto.iva)=2,(sum(detalle.total)*(12/100)),sum(detalle.total)) *(max(retencion_factura.porcentajeiva)/100),2) as debe_sin_iva_0,null as haber,proyecto.descripcion,detalle.id_proyecto,
                        round(if(sum(producto.iva)%2<>0,(sum(detalle.total)-max(factura.subtotal_0))*(12/100),if(max(producto.iva)=2,(sum(detalle.total)*(12/100)),sum(detalle.total))),2) as debe_sin_porc,
						round(if(sum(producto.iva)%2<>0,(sum(detalle.total)-max(factura.subtotal_0))*(12/100)*(max(retencion_factura.porcentajeiva)/100),if(max(producto.iva)=2,(sum(detalle.total)*(12/100)*(max(retencion_factura.porcentajeiva)/100)),sum(detalle.total)*(max(retencion_factura.porcentajeiva)/100))),2) as debe
                        FROM retencion_factura
                        INNER JOIN retencion
                        ON retencion.id_retencion=retencion_factura.id_retencion_iva
                        INNER JOIN factura
                        on factura.id_factura=retencion_factura.id_factura
                        INNER JOIN detalle
                        on detalle.id_factura=factura.id_factura
                        left JOIN plan_cuentas
                        on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                        LEFT JOIN proyecto
                        on proyecto.id_proyecto=detalle.id_proyecto
                        INNER JOIN producto
                        on producto.id_producto=detalle.id_producto
                        where factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
            GROUP BY detalle.id_proyecto"*/
            "SELECT retencion.id_plan_cuentas,detalle.total,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,
            retencion_factura.porcentajeiva,retencion_factura.cantidadiva,(retencion_factura.cantidadiva)*(detalle.total/factura.subtotal_sin_impuesto) as debe,null as haber,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle.id_proyecto,proyecto.descripcion
                    FROM retencion_factura
                    INNER JOIN retencion
                    ON retencion.id_retencion=retencion_factura.id_retencion_iva
                    INNER JOIN factura
                    on factura.id_factura=retencion_factura.id_factura
                    INNER JOIN detalle
                    on detalle.id_factura=factura.id_factura
                    left JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                    LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle.id_proyecto
                    where factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
            ORDER BY detalle.id_proyecto"
        );
        // }else{
        //     $iva_retencion_asiento=DB::select(/*"SELECT concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        //         max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,
        //         sum(retencion_factura.cantidadiva) as total,
        //         max(retencion_factura.porcentajeiva) as porcentajeiva,
        //                 round(sum(retencion_factura.cantidadiva),2) as debe,null as haber,proyecto.descripcion,detalle.id_proyecto*/
        //                 "SELECT concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        //                 max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,
        //                         max(producto.iva) as iva,
        //                 if(max(producto.iva)=2,(sum(detalle.total)*(12/100)),sum(detalle.total)) as total,
        //                 max(retencion_factura.porcentajeiva) as porcentajeiva,
        //                        round(if(max(producto.iva)=2,(sum(detalle.total)*(12/100)),sum(detalle.total)) *(max(retencion_factura.porcentajeiva)/100),2) as debe_sin_iva_0,null as haber,proyecto.descripcion,detalle.id_proyecto,
        //                                 0 as debe
        //                 FROM retencion_factura
        //                 INNER JOIN retencion
        //                 ON retencion.id_retencion=retencion_factura.id_retencion_iva
        //                 INNER JOIN factura
        //                 on factura.id_factura=retencion_factura.id_factura
        //                 INNER JOIN detalle
        //                 on detalle.id_factura=factura.id_factura
        //                 left JOIN plan_cuentas
        //                 on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
        //                 LEFT JOIN proyecto
        //                 on proyecto.id_proyecto=detalle.id_proyecto
        //                 INNER JOIN producto
        //                 on producto.id_producto=detalle.id_producto
        //                 where factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
        //         GROUP BY detalle.id_proyecto");
        // }
        $exist_pagos = DB::select("SELECT sum(factura_pagos.id_forma_pagos) as forma_pago  from factura_pagos,factura where factura.id_factura=factura_pagos.id_factura and factura.id_empresa={$request->id_empresa} and factura_pagos.id_factura={$id} and factura_pagos.estado=1");
        $exist_pagos_2 = DB::select("SELECT *  from factura_pagos,factura where factura.id_factura=factura_pagos.id_factura and factura.id_empresa={$request->id_empresa} and factura_pagos.id_factura={$id} and factura_pagos.estado=1");
        /*if(count($exist_pagos_2)<=0){

            $query="SELECT max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
            round(sum(detalle.total),2) as credito,
						round(if(max(producto.iva)=2,sum(detalle.total)*(12/100),0),2) as iva,
            round(sum(retencion_factura.cantidadrenta),2) as retencion_fuente,
            round(sum(retencion_factura.cantidadiva),2) as retencion_iva,
            round(sum(detalle.total)+if(max(producto.iva)=2,sum(detalle.total)*(12/100),0)-
            if(sum(retencion_factura.cantidadrenta) is null ,0,sum(retencion_factura.cantidadrenta))-
            if(sum(retencion_factura.cantidadiva) is null ,0,sum(retencion_factura.cantidadiva)),2) as debe,
            null as haber,proyecto.descripcion,max(detalle.id_detalle) as id_detalle,round(sum(detalle.total)*(sum(retencion_factura.porcentajeiva)/100),2) as porcentaje_iva,round(sum(detalle.total)*(sum(retencion_factura.porcentajerenta)/100),2) as porcentaje_renta,detalle.id_proyecto
                                    from cliente
                                    INNER JOIN factura
                                    ON factura.id_cliente=cliente.id_cliente
                                    INNER JOIN grupo_cliente
                                    ON grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
                                    INNER JOIN plan_cuentas
                                    ON plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
                                    INNER JOIN detalle
                                    ON detalle.id_factura=factura.id_factura
                                    LEFT JOIN proyecto
                                    on proyecto.id_proyecto=detalle.id_proyecto
                                    LEFT JOIN retencion_factura
                                    on retencion_factura.id_factura=factura.id_factura
									INNER JOIN producto
									on producto.id_producto=detalle.id_producto
                                    where  factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
                                    GROUP BY detalle.id_proyecto";
        }else{
            if($exist_pagos[0]->forma_pago<=0){

                //dd($exist_pagos[0]->forma_pago);
                $query=
                "SELECT max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
                round(sum(detalle.total),2) as credito,
                            round(if(max(producto.iva)=2,sum(detalle.total)*(12/100),0),2) as iva,
                round(sum(detalle.total)*(max(retencion_factura.porcentajerenta)/100),2) as retencion_fuente,
                round(if(max(producto.iva)=2,(sum(detalle.total)*(12/100)),sum(detalle.total)),2) as retencion_iva,
                round(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)),2) as forma_pagos,
                    round(sum(detalle.total)+if(max(producto.iva)=2,sum(detalle.total)*(12/100),0)-
                    if(sum(retencion_factura.cantidadrenta) is null ,0,sum(detalle.total)*(max(retencion_factura.porcentajerenta)/100))-
                if(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)) is null,0,sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)))-
                            if(max(producto.iva)=2,(sum(detalle.total)*(12/100))*(max(retencion_factura.porcentajeiva)/100),0),2) as debe,
                null as haber,proyecto.descripcion,max(detalle.id_detalle) as id_detalle,round(sum(detalle.total)*(sum(retencion_factura.porcentajeiva)/100),2) as porcentaje_iva,round(sum(detalle.total)*(sum(retencion_factura.porcentajerenta)/100),2) as porcentaje_renta,
                detalle.id_proyecto
                                                    from cliente
                                                    INNER JOIN factura
                                                    ON factura.id_cliente=cliente.id_cliente
                                                    INNER JOIN grupo_cliente
                                                    ON grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
                                                    INNER JOIN plan_cuentas
                                                    ON plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
                                                    INNER JOIN detalle
                                                    ON detalle.id_factura=factura.id_factura
                                                    LEFT JOIN proyecto
                                                    on proyecto.id_proyecto=detalle.id_proyecto
                                                    LEFT JOIN retencion_factura
                                                    on retencion_factura.id_factura=factura.id_factura
                                                    LEFT JOIN factura_pagos
                                                    on factura_pagos.id_factura=factura.id_factura
                                                    INNER JOIN producto
                                                    on producto.id_producto=detalle.id_producto
                                        where factura.id_factura={$id} and factura.id_empresa={$empresa[0]->id_empresa} and factura_pagos.estado=1
                                                    GROUP BY detalle.id_proyecto";
            }else{
                //dd($exist_pagos[0]->forma_pago);
                $query=/*"SELECT max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
                round(sum(detalle.total),2) as credito,
                            round(if(max(producto.iva)=2,sum(detalle.total)*(12/100),0),2) as iva,
                round(sum(retencion_factura.cantidadrenta),2) as retencion_fuente,
                round(sum(retencion_factura.cantidadiva),2) as retencion_iva,
                round(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)),2) as forma_pagos,
                    round(sum(detalle.total)+if(max(producto.iva)=2,sum(detalle.total)*(12/100),0)-
                    if(sum(retencion_factura.cantidadrenta) is null ,0,sum(retencion_factura.cantidadrenta))-
                            if(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)) is null,0,sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)))-
                                if(sum(retencion_factura.cantidadiva) is null ,0,sum(retencion_factura.cantidadiva)),2) as debe,
                null as haber,proyecto.descripcion,max(detalle.id_detalle) as id_detalle,round(sum(detalle.total)*(sum(retencion_factura.porcentajeiva)/100),2) as porcentaje_iva,round(sum(detalle.total)*(sum(retencion_factura.porcentajerenta)/100),2) as porcentaje_renta,
                detalle.id_proyecto*/
        /*"SELECT max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
                round(sum(detalle.total),2) as credito,
                            round(if(max(producto.iva)=2,sum(detalle.total)*(12/100),0),2) as iva,
                round(sum(detalle.total)*(max(retencion_factura.porcentajerenta)/100),2) as retencion_fuente,
                round(if(max(producto.iva)=2,(sum(detalle.total)*(12/100)),sum(detalle.total)),2) as retencion_iva,
                round(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)),2) as forma_pagos,
                    round(sum(detalle.total)+if(max(producto.iva)=2,sum(detalle.total)*(12/100),0)-
                    if(sum(retencion_factura.cantidadrenta) is null ,0,sum(detalle.total)*(max(retencion_factura.porcentajerenta)/100))-
                if(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)) is null,0,sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)))-
                            if(max(producto.iva)=2,(sum(detalle.total)*(12/100))*(max(retencion_factura.porcentajeiva)/100),sum(detalle.total)*(max(retencion_factura.porcentajeiva)/100)),2) as debe,
                null as haber,proyecto.descripcion,max(detalle.id_detalle) as id_detalle,round(sum(detalle.total)*(sum(retencion_factura.porcentajeiva)/100),2) as porcentaje_iva,round(sum(detalle.total)*(sum(retencion_factura.porcentajerenta)/100),2) as porcentaje_renta,
                detalle.id_proyecto*/
        /*"SELECT max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
                round(sum(detalle.total)/count(detalle.id_detalle),2) as credito,
                            round(if(max(producto.iva)=2,(sum(detalle.total)/count(detalle.id_detalle))*(12/100),0),2) as iva,

                round(sum(retencion_factura.cantidadrenta)/count(DISTINCT retencion_factura.id_retencion_factura)*((sum(detalle.total)/count(detalle.id_detalle))/max(factura.subtotal_sin_impuesto)),2) as retencion_fuente,
                round(if(max(producto.iva)=2,(sum(retencion_factura.cantidadiva)/count(DISTINCT retencion_factura.id_retencion_factura)),(sum(retencion_factura.cantidadiva)/count(DISTINCT retencion_factura.id_retencion_factura))*0),2) as retencion_iva,
								sum(factura_pagos.total)/count(DISTINCT factura_pagos.id_facturas_pagos),
								(sum(factura_pagos.total)/count(DISTINCT factura_pagos.id_facturas_pagos)) pagos,
								(((sum(detalle.total)/count(detalle.id_detalle)))/max(factura.subtotal_sin_impuesto)) as porce_pago,
                round((sum(factura_pagos.total)/count(DISTINCT factura_pagos.id_facturas_pagos))*(((sum(detalle.total)/count(detalle.id_detalle)))/max(factura.subtotal_sin_impuesto)),2) as forma_pagos,
                round((sum(detalle.total)/count(detalle.id_detalle))+
								if(max(producto.iva)=2,(sum(detalle.total)/count(detalle.id_detalle))*(12/100),0)-
                if(sum(retencion_factura.cantidadrenta) is null ,0,sum(retencion_factura.cantidadrenta)/count(DISTINCT retencion_factura.id_retencion_factura)*((sum(detalle.total)/count(detalle.id_detalle))/max(factura.subtotal_sin_impuesto)))-
                if(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)) is null,0,(sum(factura_pagos.total)/count(DISTINCT factura_pagos.id_facturas_pagos))*(((sum(detalle.total)/count(detalle.id_detalle)))/max(factura.subtotal_sin_impuesto)))-
                if(max(producto.iva)=2,(sum(retencion_factura.cantidadiva)/count(DISTINCT retencion_factura.id_retencion_factura)),(sum(retencion_factura.cantidadiva)/count(DISTINCT retencion_factura.id_retencion_factura))*0),2) as debe,
                null as haber,proyecto.descripcion,max(detalle.id_detalle) as id_detalle,round(sum(detalle.total)*(sum(retencion_factura.porcentajeiva)/100),2) as porcentaje_iva,round(sum(detalle.total)*(sum(retencion_factura.porcentajerenta)/100),2) as porcentaje_renta,
                detalle.id_proyecto
                                                    from cliente
                                                    INNER JOIN factura
                                                    ON factura.id_cliente=cliente.id_cliente
                                                    INNER JOIN grupo_cliente
                                                    ON grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
                                                    INNER JOIN plan_cuentas
                                                    ON plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
                                                    INNER JOIN detalle
                                                    ON detalle.id_factura=factura.id_factura
                                                    LEFT JOIN proyecto
                                                    on proyecto.id_proyecto=detalle.id_proyecto
                                                    LEFT JOIN retencion_factura
                                                    on retencion_factura.id_factura=factura.id_factura
                                                    LEFT JOIN factura_pagos
                                                    on factura_pagos.id_factura=factura.id_factura
                                                    INNER JOIN producto
                                                    on producto.id_producto=detalle.id_producto
                                        where factura.id_factura={$id} and factura.id_empresa={$empresa[0]->id_empresa} and factura_pagos.estado=1
                                                    GROUP BY detalle.id_proyecto";
            }


        }*/
        $query = "SELECT round(sum(factura_pagos.total)/count(factura_pagos.id_facturas_pagos),2) as total,round(sum(detalle.total)/max(factura.subtotal_sin_impuesto),2) as porcentaje,sum(detalle.total) as valor_producto,round((sum(detalle.total)/max(factura.subtotal_sin_impuesto)*(sum(factura_pagos.total)/count(factura_pagos.id_facturas_pagos))),2) as debe,null as haber,detalle.id_proyecto,proyecto.descripcion,plan_cuentas.id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        if(cliente.id_plan_cuentas is null, 'no','si') as exist_plc_cl,cliente.id_cliente,
        (select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas)) as id_plan_cuentas_cl,
        (select CONCAT(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas)) as nombre_cuenta_cl
        from factura_pagos
        INNER JOIN factura
        ON factura.id_factura=factura_pagos.id_factura
        INNER JOIN cliente
        ON cliente.id_cliente=factura.id_cliente
        Left JOIN grupo_cliente
        ON grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
        Left JOIN plan_cuentas
        ON plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
        INNER JOIN detalle
        ON detalle.id_factura=factura.id_factura
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle.id_proyecto
        where factura_pagos.id_factura={$id} and factura_pagos.estado=2
        GROUP BY detalle.id_proyecto
        ORDER BY detalle.id_proyecto asc";
        //dd($query);
        $creditos = DB::select("SELECT * from factura_pagos where id_factura={$id} and estado=2");
        if (count($creditos) > 0) {
            $cliente = DB::select($query);
        } else {
            $cliente = [];
        }




        $pagos = DB::select("SELECT fpa.descripcion as descripcionpagos, b.nombre_banco, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta FROM factura_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas LEFT JOIN forma_pagos fpa ON fpa.id_forma_pagos = fp.id_forma_pagos LEFT JOIN banco b ON b.id_banco = fp.id_banco WHERE fp.estado = 1 AND fp.id_factura = " . $id);
        $iva = DB::select("SELECT * FROM retencion_factura rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_factura = " . $id);
        $renta = DB::select("SELECT * FROM retencion_factura rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_factura = " . $id);

        $codigo = DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'FV-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=" . $request->id_empresa);
        $cod_asiento = "";
        if ($codigo) {
            $lenght = strlen($codigo[0]->codigo);
            $cod_asiento = $codigo[0]->codigo + 1;
        } else {
            $cod_asiento = "1";
        }
        $cod_asiento_ant = "";
        if ($codigo) {
            $codigo_ant = DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=6 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=" . $request->id_empresa . " ORDER BY asientos.codigo desc limit 1");
            //$lenght=strlen($codigo[0]->codigo);
            //dd("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=6 and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if ($codigo_ant) {
                $cod_asiento_ant = $codigo_ant[0]->numero;
            } else {
                $lenght = strlen($codigo[0]->codigo);
                $cod_asiento_ant = substr($codigo[0]->codigo, 3, $lenght);
            }
        }

        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }

        $producto_asiento = DB::select(/*"SELECT if(max(producto.sector)=1,'producto','servicio') as sector,
        if(max(producto.iva)=2,'doce','cero') as iva,
        if(max(producto.sector)=1,max(linea_producto.id_plan_cuentas_ventas_iva),null) as id_plan_cuentas_iva_12,
        if(max(producto.sector)=1,max(linea_producto.id_plan_cuentas_ventas_iva_0),null) as id_plan_cuentas_iva_0,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(max(linea_producto.id_plan_cuentas_ventas_iva) is null,0,max(linea_producto.id_plan_cuentas_ventas_iva)) and id_empresa={$empresa[0]->id_empresa}) as nombre_cuenta_12,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(max(linea_producto.id_plan_cuentas_ventas_iva_0) is null,0,max(linea_producto.id_plan_cuentas_ventas_iva_0)) and id_empresa={$empresa[0]->id_empresa}) as nombre_cuenta_0,
        max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas_servicio,
        concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,null as debe,
                sum(detalle.total) as haber,max(proyecto.descripcion) as descripcion,detalle.id_proyecto
                    from detalle
                            INNER JOIN producto
                            ON producto.id_producto=detalle.id_producto
                            INNER JOIN factura
                            ON factura.id_factura=detalle.id_factura
                            LEFT JOIN plan_cuentas
                            ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
                            LEFT JOIN proyecto
                            on proyecto.id_proyecto=detalle.id_proyecto
                            LEFT JOIN linea_producto
                            on linea_producto.id_linea_producto=producto.id_linea_producto
                    where detalle.id_factura={$id} and factura.id_empresa={$request->id_empresa}
        GROUP BY detalle.id_proyecto"*/
            "SELECT detalle.total,if(producto.iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
            if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva,null) as id_plan_cuentas_iva_12,
            if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva_0,null) as id_plan_cuentas_iva_0,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
            (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$request->id_empresa}) as grupo_cuenta_12,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
            (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$request->id_empresa}) as grupo_cuenta_0,
            plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
            concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
            plan_cuentas.id_grupo as grupo_cuenta_servicio,
            null as debe,detalle.total as haber
            from detalle
            INNER JOIN producto
            ON producto.id_producto=detalle.id_producto
            INNER JOIN factura
            ON factura.id_factura=detalle.id_factura
            LEFT JOIN plan_cuentas
            ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle.id_proyecto
            LEFT JOIN linea_producto
            on linea_producto.id_linea_producto=producto.id_linea_producto
            where detalle.id_factura={$id}"
        );

        /*if($factura[0]->subtotal_12>0){
            $iva_asiento=DB::select(//"SELECT ROUND(if(max(producto.iva)=2,sum(detalle.total)*(12/100),0),2) as haber,null as debe,max(detalle.id_detalle) as id_detalle,CONCAT(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,proyecto.descripcion,detalle.id_proyecto,max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas
                "SELECT ROUND(if(max(producto.iva)=2,sum(detalle.total)*(12/100),0),2) as haber_sin_iva_0,null as debe,max(detalle.id_detalle) as id_detalle,CONCAT(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,proyecto.descripcion,detalle.id_proyecto,max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,
                round(if(sum(producto.iva)%2<>0,(sum(detalle.total)-max(factura.subtotal_0))*(12/100),if(max(producto.iva)=2,(sum(detalle.total)*(12/100)),sum(detalle.total))),2) as haber
                from factura,retencion,plan_cuentas,detalle
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
                INNER JOIN producto
                on producto.id_producto=detalle.id_producto
                where factura.id_factura={$id} and factura.id_factura=detalle.id_factura and retencion.descrip_retencion='IVA. en Ventas' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                GROUP BY detalle.id_proyecto ORDER BY proyecto.descripcion asc");
        }else{
            $iva_asiento=[];
        }*/
        $iva_asiento = DB::select("SELECT detalle.total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as debe,if(detalle.id_iva=2,(detalle.total+(if(producto.total_ice is null,0,producto.total_ice)*detalle.cantidad))*(12/100),0) as haber
        from factura,retencion,plan_cuentas,detalle
                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle.id_producto
						LEFT JOIN ice
						on ice.id_ice=detalle.id_ice
                       where factura.id_factura={$id} and factura.id_factura=detalle.id_factura and retencion.descrip_retencion='IVA. en Ventas' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                       ORDER BY detalle.id_detalle asc");
        $exist_plan_cuenta = DB::select("SELECT sum(id_plan_cuentas) as existe_plan_cuenta  from factura_pagos where id_factura={$id} and  estado=1");
        $exist_anticipo = DB::select("SELECT sum(anticipo) as anticipo from factura_pagos where id_factura={$id} and  estado=1");
        $forma_pagos_sin_plc = DB::select("SELECT factura_pagos.total,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,round(detalle.total/factura.subtotal_sin_impuesto*	factura_pagos.total,2) as debe,null as haber,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle.id_proyecto,proyecto.descripcion,factura_pagos.id_forma_pagos,factura_pagos.fecha_pago,factura_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
        from factura_pagos
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
        INNER JOIN factura
        on factura.id_factura=factura_pagos.id_factura
        INNER JOIN detalle
        on detalle.id_factura=factura.id_factura
        left JOIN proyecto
        on proyecto.id_proyecto=detalle.id_proyecto
        where factura_pagos.estado=1 and factura_pagos.id_factura={$id} and factura_pagos.anticipo<=0 and factura_pagos.id_plan_cuentas is null
        ORDER BY detalle.id_proyecto asc");
        $forma_pagos_con_plc = DB::select("SELECT factura_pagos.total,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,round(detalle.total/factura.subtotal_sin_impuesto*	factura_pagos.total,2) as debe,null as haber,factura_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle.id_proyecto,proyecto.descripcion,factura_pagos.id_forma_pagos,factura_pagos.fecha_pago,factura_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
        from factura_pagos
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=factura_pagos.id_plan_cuentas
        INNER JOIN factura
        on factura.id_factura=factura_pagos.id_factura
        INNER JOIN detalle
        on detalle.id_factura=factura.id_factura
        left JOIN proyecto
        on proyecto.id_proyecto=detalle.id_proyecto
        where factura_pagos.estado=1 and factura_pagos.id_factura={$id} and factura_pagos.anticipo<=0 and factura_pagos.id_plan_cuentas is not null
        ORDER BY detalle.id_proyecto asc");
        $forma_pagos_anticipo = DB::select("SELECT factura_pagos.total,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,round(detalle.total/factura.subtotal_sin_impuesto*factura_pagos.total,2) as debe,null as haber,grupo_cliente.id_plan_cuentas_anticipo as id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle.id_proyecto,proyecto.descripcion,factura_pagos.id_forma_pagos,factura_pagos.fecha_pago,factura_pagos.numero_transaccion,null as nombre_pago
        from factura_pagos
        INNER JOIN factura
        on factura.id_factura=factura_pagos.id_factura
        INNER JOIN cliente
        on cliente.id_cliente=factura.id_cliente
        LEFT JOIN grupo_cliente
        on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas_anticipo
        INNER JOIN detalle
        on detalle.id_factura=factura.id_factura
        left JOIN proyecto
        on proyecto.id_proyecto=detalle.id_proyecto
        where factura_pagos.estado=1 and factura_pagos.id_factura={$id} and factura_pagos.anticipo>0 and factura_pagos.id_plan_cuentas is null
        ORDER BY detalle.id_proyecto asc");
        $total_retencion = DB::select("SELECT sum(cantidadiva) as cantidad_iva,sum(cantidadrenta) as cantidad_renta  from retencion_factura where retencion_factura.id_factura={$id}");
        /*if($exist_plan_cuenta[0]->existe_plan_cuenta<=0 && $exist_pagos[0]->forma_pago<=0){
            $forma_pagos=DB::select("SELECT round(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)),2) as debe,(sum(detalle.total)/count(proyecto.id_proyecto)) as porc,null as haber,proyecto.descripcion as descripcion,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,proyecto.id_proyecto,max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas,detalle.id_proyecto
                FROM factura_pagos
                INNER JOIN forma_pagos
                on forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                INNER JOIN factura
                on factura_pagos.id_factura=factura.id_factura
                INNER JOIN detalle
                on factura.id_factura=detalle.id_factura
                INNER JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
                where factura_pagos.estado=1 and factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
                GROUP BY detalle.id_proyecto");
        }else{
            if($exist_anticipo[0]->anticipo>0){
                $forma_pagos=DB::select("SELECT round(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)),2) as debe,(sum(detalle.total)/count(proyecto.id_proyecto)) as porc,null as haber,proyecto.descripcion as descripcion,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,proyecto.id_proyecto,max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas
                FROM factura_pagos
                LEFT JOIN forma_pagos
                on forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos
                INNER JOIN factura
                on factura_pagos.id_factura=factura.id_factura
                INNER JOIN cliente
                on cliente.id_cliente=factura.id_cliente
                INNER JOIN grupo_cliente
                on cliente.id_grupo_cliente=grupo_cliente.id_grupo_cliente
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas_anticipo
                INNER JOIN detalle
                on factura.id_factura=detalle.id_factura
                INNER JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
                where factura_pagos.estado=1 and factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
                GROUP BY detalle.id_proyecto");
             }else{
                $forma_pagos=DB::select("SELECT round(sum(factura_pagos.total)*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto)),2) as debe,(sum(detalle.total)/count(proyecto.id_proyecto)) as porc,null as haber,proyecto.descripcion as descripcion,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,proyecto.id_proyecto,max(plan_cuentas.id_plan_cuentas) as id_plan_cuentas
                FROM factura_pagos
                INNER JOIN forma_pagos
                on forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos
				LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=factura_pagos.id_plan_cuentas
                INNER JOIN factura
                on factura_pagos.id_factura=factura.id_factura
                INNER JOIN detalle
                on factura.id_factura=detalle.id_factura
                INNER JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
                where factura_pagos.estado=1 and factura.id_factura={$id} and factura.id_empresa={$request->id_empresa}
                GROUP BY detalle.id_proyecto");
             }
        }*/

        $ice_factura = DB::select("SELECT producto.total_ice as total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as debe,round(detalle.cantidad*if(producto.total_ice is null,0,producto.total_ice),2) as haber,detalle.id_detalle
        from detalle
        INNER JOIN factura
        on factura.id_factura=detalle.id_factura
        INNER JOIN ice
        on ice.id_ice=detalle.id_ice
		INNER JOIN producto
        on producto.id_producto=detalle.id_producto
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=ice.id_plan_cuentas
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle.id_proyecto
        where factura.id_factura={$id} and factura.id_empresa={$request->id_empresa} and  producto.total_ice>0
        ORDER BY detalle.id_detalle asc");
        $fecha_emision = substr($factura[0]->fecha_emision, 0, -3);
        $anio_emision = substr($factura[0]->fecha_emision, 0, 4);
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
        $total_pagos_sin_plc = 0;
        $total_pagos_con_plc = 0;
        $total_pagos_anticipo = 0;
        $total_retencion_iva = 0;
        $total_retencion_renta = 0;
        if (count($forma_pagos_sin_plc) > 0) {
            if (isset($forma_pagos_sin_plc[0]->total_pago)) {
                $total_pagos_sin_plc = $forma_pagos_sin_plc[0]->total_pago;
            } else {
                $total_pagos_sin_plc = $forma_pagos_sin_plc[0]->total;
            }
        }
        if (count($forma_pagos_con_plc) > 0) {
            if (isset($forma_pagos_con_plc[0]->total_pago)) {
                $total_pagos_con_plc = $forma_pagos_con_plc[0]->total_pago;
            } else {
                $total_pagos_con_plc = $forma_pagos_con_plc[0]->total;
            }
        }
        if (count($forma_pagos_anticipo) > 0) {
            if (isset($forma_pagos_anticipo[0]->total_pago)) {
                $total_pagos_anticipo = $forma_pagos_anticipo[0]->total_pago;
            } else {
                $total_pagos_anticipo = $forma_pagos_anticipo[0]->total;
            }
        }
        if (count($total_retencion) > 0) {
            $total_retencion_iva = $total_retencion[0]->cantidad_iva;
            $total_retencion_renta = $total_retencion[0]->cantidad_renta;
        }
        return [
            'factura' => $factura[0],
            'asiento_permitido' => $asiento,
            'cliente' => $cliente,
            'pagos' => $pagos,
            'creditos' => $factura_creditos,
            'iva' => $iva,
            'renta' => $renta,
            'empresa' => $empresa[0],
            'codigo' => $cod_asiento,
            'codigo_anterior' => $cod_asiento_ant,
            'producto_asientos' => $producto_asiento,
            'doce_iva_asiento' => $iva_asiento,
            'retencion_asiento' => $renta_retencion_asiento,
            'iva_retencion_asiento' => $iva_retencion_asiento,
            'pagos_asientos_sin_plc' => $forma_pagos_sin_plc,
            'pagos_asientos_con_plc' => $forma_pagos_con_plc,
            'pagos_asientos_anticipo' => $forma_pagos_anticipo,
            'id_proyecto' => $proyecto[0]->id_proyecto,
            'ice' => $ice_factura,
            'total_pagos_sin_plc' => $total_pagos_sin_plc,
            'total_pagos_con_plc' => $total_pagos_con_plc,
            'total_pagos_anticipo' => $total_pagos_anticipo,
            'total_retencion_iva' => $total_retencion_iva,
            'total_retencion_renta' => $total_retencion_renta

        ];
    }
    public function agregarAsiento(Request $request)
    {
        Factura::where('id_factura', $request->cod_rol)->update(['contabilidad' => '1']);
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
        $asientos->id_asientos_comprobante = 6;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request)
    {
        foreach ($request->productos as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["sector"] == "producto" && $haber["iva"] == "doce") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_iva_12"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            if ($haber["sector"] == "producto" && $haber["iva"] == "cero") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_iva_0"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            if ($haber["sector"] == "servicio") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_servicio"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }
        foreach ($request->ice as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["haber"] > 0) {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }
        foreach ($request->iva_12 as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["haber"] > 0) {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }

        foreach ($request->pagos_sin_plc as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["debe"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->pagos_con_plc as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["debe"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->pagos_anticipo as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["debe"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }

        foreach ($request->creditos as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }

        foreach ($request->retencion_iva as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
        foreach ($request->retencion_renta as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
    }
    ////////////////////////////////////
    //funciones para asientos nota venta
    public function notaVentaContabilizar(Request $request, $id)
    {

        $empresa = DB::select("SELECT empresa.*,cliente.nombre,cliente.identificacion,cliente.tipo_identificacion from empresa,nota_venta,cliente where cliente.id_empresa=empresa.id_empresa and nota_venta.id_cliente=cliente.id_cliente and nota_venta.id_empresa=empresa.id_empresa and nota_venta.id_nota_venta=" . $id);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $nota_venta = DB::select("SELECT f.*, pr.descripcion as des_proyecto FROM nota_venta f LEFT JOIN proyecto pr ON f.id_proyecto=pr.id_proyecto WHERE id_nota_venta = " . $id . " and f.id_empresa=" . $request->id_empresa);


        $renta_retencion_asiento = DB::select(
            "SELECT retencion.id_plan_cuentas,detalle_nota_venta.total,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto,2) as porcentaje,retencion_nota_venta.baserenta,
        retencion_nota_venta.porcentajerenta,retencion_nota_venta.cantidadrenta,round(retencion_nota_venta.cantidadrenta*(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto),2) as debe,null as haber,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_nota_venta.id_proyecto,proyecto.descripcion
                FROM retencion_nota_venta
                INNER JOIN retencion
                ON retencion.id_retencion=retencion_nota_venta.id_retencion_renta
                INNER JOIN nota_venta
                on nota_venta.id_nota_venta=retencion_nota_venta.id_nota_venta
                INNER JOIN detalle_nota_venta
                on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
                left JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
                where nota_venta.id_nota_venta={$id} and nota_venta.id_empresa={$request->id_empresa}
        ORDER BY detalle_nota_venta.id_proyecto"
        );

        $iva_retencion_asiento = DB::select(
            "SELECT retencion.id_plan_cuentas,detalle_nota_venta.total,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto,2) as porcentaje,
            retencion_nota_venta.porcentajeiva,retencion_nota_venta.cantidadiva,round((retencion_nota_venta.cantidadiva)*(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto),2) as debe,null as haber,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_nota_venta.id_proyecto,proyecto.descripcion
                    FROM retencion_nota_venta
                    INNER JOIN retencion
                    ON retencion.id_retencion=retencion_nota_venta.id_retencion_iva
                    INNER JOIN nota_venta
                    on nota_venta.id_nota_venta=retencion_nota_venta.id_nota_venta
                    INNER JOIN detalle_nota_venta
                    on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
                    left JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                    LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
                    where nota_venta.id_nota_venta={$id} and nota_venta.id_empresa={$request->id_empresa}
            ORDER BY detalle_nota_venta.id_proyecto"
        );

        $exist_pagos = DB::select("SELECT sum(nota_venta_pagos.id_forma_pagos) as forma_pago  from nota_venta_pagos,nota_venta where nota_venta.id_nota_venta=nota_venta_pagos.id_nota_venta and nota_venta.id_empresa={$request->id_empresa} and nota_venta_pagos.id_nota_venta={$id} and nota_venta_pagos.estado=1");
        $exist_pagos_2 = DB::select("SELECT *  from nota_venta_pagos,nota_venta where nota_venta.id_nota_venta=nota_venta_pagos.id_nota_venta and nota_venta.id_empresa={$request->id_empresa} and nota_venta_pagos.id_nota_venta={$id} and nota_venta_pagos.estado=1");

        $query = "SELECT round(sum(nota_venta_pagos.total)/count(nota_venta_pagos.id_nota_venta_pagos),2) as total,round(sum(detalle_nota_venta.total)/max(nota_venta.subtotal_sin_impuesto),2) as porcentaje,sum(detalle_nota_venta.total) as valor_producto,round((sum(detalle_nota_venta.total)/max(nota_venta.subtotal_sin_impuesto)*(sum(nota_venta_pagos.total)/count(nota_venta_pagos.id_nota_venta_pagos))),2) as debe,null as haber,detalle_nota_venta.id_proyecto,proyecto.descripcion,plan_cuentas.id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        if(cliente.id_plan_cuentas is null, 'no','si') as exist_plc_cl,cliente.id_cliente,
        (select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas)) as id_plan_cuentas_cl,
        (select CONCAT(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas)) as nombre_cuenta_cl
        from nota_venta_pagos
        INNER JOIN nota_venta
        ON nota_venta.id_nota_venta=nota_venta_pagos.id_nota_venta
        INNER JOIN cliente
        ON cliente.id_cliente=nota_venta.id_cliente
        Left JOIN grupo_cliente
        ON grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
        Left JOIN plan_cuentas
        ON plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
        INNER JOIN detalle_nota_venta
        ON detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
        where nota_venta_pagos.id_nota_venta={$id} and nota_venta_pagos.estado=2
        GROUP BY detalle_nota_venta.id_proyecto
        ORDER BY detalle_nota_venta.id_proyecto asc";
        //dd($query);
        $creditos = DB::select("SELECT * from nota_venta_pagos where id_nota_venta={$id} and estado=2");
        if (count($creditos) > 0) {
            $cliente = DB::select($query);
        } else {
            $cliente = [];
        }




        $pagos = DB::select("SELECT fpa.descripcion as descripcionpagos, b.nombre_banco, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta FROM nota_venta_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas LEFT JOIN forma_pagos fpa ON fpa.id_forma_pagos = fp.id_forma_pagos LEFT JOIN banco b ON b.id_banco = fp.id_banco WHERE fp.estado = 1 AND fp.id_nota_venta = " . $id);
        $iva = DB::select("SELECT * FROM retencion_nota_venta rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_nota_venta = " . $id);
        $renta = DB::select("SELECT * FROM retencion_nota_venta rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_nota_venta = " . $id);

        $codigo = DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'NV-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=" . $request->id_empresa);
        $cod_asiento = "";
        if ($codigo) {
            $lenght = strlen($codigo[0]->codigo);
            $cod_asiento = $codigo[0]->codigo + 1;
        } else {
            $cod_asiento = "1";
        }
        $cod_asiento_ant = "";
        if ($codigo) {
            $codigo_ant = DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=22 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=" . $request->id_empresa . " ORDER BY asientos.codigo desc limit 1");
            //$lenght=strlen($codigo[0]->codigo);
            //dd("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=6 and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if ($codigo_ant) {
                $cod_asiento_ant = $codigo_ant[0]->numero;
            } else {
                $lenght = strlen($codigo[0]->codigo);
                $cod_asiento_ant = substr($codigo[0]->codigo, 3, $lenght);
            }
        }

        $nota_venta_creditos = "";
        if (count($creditos)) {
            $nota_venta_creditos = $creditos[0];
        }

        $producto_asiento = DB::select(
            "SELECT detalle_nota_venta.total,if(producto.iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
            if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva,null) as id_plan_cuentas_iva_12,
            if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva_0,null) as id_plan_cuentas_iva_0,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
            (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$request->id_empresa}) as grupo_cuenta_12,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
            (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$request->id_empresa}) as grupo_cuenta_0,
            plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
            concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
            plan_cuentas.id_grupo as grupo_cuenta_servicio,
            null as debe,detalle_nota_venta.total as haber
            from detalle_nota_venta
            INNER JOIN producto
            ON producto.id_producto=detalle_nota_venta.id_producto
            INNER JOIN nota_venta
            ON nota_venta.id_nota_venta=detalle_nota_venta.id_nota_venta
            LEFT JOIN plan_cuentas
            ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
            LEFT JOIN linea_producto
            on linea_producto.id_linea_producto=producto.id_linea_producto
            where detalle_nota_venta.id_nota_venta={$id}"
        );
        $iva_asiento = DB::select("SELECT detalle_nota_venta.total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as debe,round(if(detalle_nota_venta.id_iva=2,(detalle_nota_venta.total+(if(producto.total_ice is null,0,producto.total_ice)*detalle_nota_venta.cantidad))*(12/100),0),2) as haber
        from nota_venta,retencion,plan_cuentas,detalle_nota_venta
                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle_nota_venta.id_producto
						INNER JOIN ice
						on ice.id_ice=detalle_nota_venta.id_ice
                       where nota_venta.id_nota_venta={$id} and nota_venta.id_nota_venta=detalle_nota_venta.id_nota_venta and retencion.descrip_retencion='IVA. en Ventas' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                       ORDER BY detalle_nota_venta.id_detalle_nota_venta asc");
        $exist_plan_cuenta = DB::select("SELECT sum(id_plan_cuentas) as existe_plan_cuenta  from nota_venta_pagos where id_nota_venta={$id} and  estado=1");
        $exist_anticipo = DB::select("SELECT sum(anticipo) as anticipo from nota_venta_pagos where id_nota_venta={$id} and  estado=1");
        $forma_pagos_sin_plc = DB::select("SELECT nota_venta_pagos.total,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto,2) as porcentaje,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto*	nota_venta_pagos.total,2) as debe,null as haber,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_nota_venta.id_proyecto,proyecto.descripcion,nota_venta_pagos.id_forma_pagos,nota_venta_pagos.fecha_pago,nota_venta_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
        from nota_venta_pagos
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=nota_venta_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
        INNER JOIN nota_venta
        on nota_venta.id_nota_venta=nota_venta_pagos.id_nota_venta
        INNER JOIN detalle_nota_venta
        on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
        left JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
        where nota_venta_pagos.estado=1 and nota_venta_pagos.id_nota_venta={$id} and nota_venta_pagos.anticipo<=0 and nota_venta_pagos.id_plan_cuentas is null
        ORDER BY detalle_nota_venta.id_proyecto asc");
        $forma_pagos_con_plc = DB::select("SELECT nota_venta_pagos.total,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto,2) as porcentaje,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto*	nota_venta_pagos.total,2) as debe,null as haber,nota_venta_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_nota_venta.id_proyecto,proyecto.descripcion,nota_venta_pagos.id_forma_pagos,nota_venta_pagos.fecha_pago,nota_venta_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
        from nota_venta_pagos
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=nota_venta_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=nota_venta_pagos.id_plan_cuentas
        INNER JOIN nota_venta
        on nota_venta.id_nota_venta=nota_venta_pagos.id_nota_venta
        INNER JOIN detalle_nota_venta
        on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
        left JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
        where nota_venta_pagos.estado=1 and nota_venta_pagos.id_nota_venta={$id} and nota_venta_pagos.anticipo<=0 and nota_venta_pagos.id_plan_cuentas is not null
        ORDER BY detalle_nota_venta.id_proyecto asc");
        $forma_pagos_anticipo = DB::select("SELECT nota_venta_pagos.total,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto,2) as porcentaje,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto*nota_venta_pagos.total,2) as debe,null as haber,grupo_cliente.id_plan_cuentas_anticipo as id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_nota_venta.id_proyecto,proyecto.descripcion,nota_venta_pagos.id_forma_pagos,nota_venta_pagos.fecha_pago,nota_venta_pagos.numero_transaccion,null as nombre_pago
        from nota_venta_pagos
        INNER JOIN nota_venta
        on nota_venta.id_nota_venta=nota_venta_pagos.id_nota_venta
        INNER JOIN cliente
        on cliente.id_cliente=nota_venta.id_cliente
        LEFT JOIN grupo_cliente
        on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas_anticipo
        INNER JOIN detalle_nota_venta
        on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
        left JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
        where nota_venta_pagos.estado=1 and nota_venta_pagos.id_nota_venta={$id} and nota_venta_pagos.anticipo>0 and nota_venta_pagos.id_plan_cuentas is null
        ORDER BY detalle_nota_venta.id_proyecto asc");
        $ice_nota_venta = DB::select("SELECT producto.total_ice as total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as debe,round(detalle_nota_venta.cantidad*if(producto.total_ice is null,0,producto.total_ice),2) as haber,detalle_nota_venta.id_detalle_nota_venta
        from detalle_nota_venta
        INNER JOIN nota_venta
        on nota_venta.id_nota_venta=detalle_nota_venta.id_nota_venta
        INNER JOIN ice
        on ice.id_ice=detalle_nota_venta.id_ice
		INNER JOIN producto
        on producto.id_producto=detalle_nota_venta.id_producto
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=ice.id_plan_cuentas
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
        where nota_venta.id_nota_venta={$id} and nota_venta.id_empresa={$request->id_empresa} and  producto.total_ice>0
        ORDER BY detalle_nota_venta.id_detalle_nota_venta asc");
        $fecha_emision = substr($nota_venta[0]->fecha_emision, 0, -3);
        $anio_emision = substr($nota_venta[0]->fecha_emision, 0, 4);
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
            'nota_venta' => $nota_venta[0],
            'asiento_permitido' => $asiento,
            'cliente' => $cliente,
            'pagos' => $pagos,
            'creditos' => $nota_venta_creditos,
            'iva' => $iva,
            'renta' => $renta,
            'empresa' => $empresa[0],
            'codigo' => $cod_asiento,
            'codigo_anterior' => $cod_asiento_ant,
            'producto_asientos' => $producto_asiento,
            'doce_iva_asiento' => $iva_asiento,
            'retencion_asiento' => $renta_retencion_asiento,
            'iva_retencion_asiento' => $iva_retencion_asiento,
            'pagos_asientos_sin_plc' => $forma_pagos_sin_plc,
            'pagos_asientos_con_plc' => $forma_pagos_con_plc,
            'pagos_asientos_anticipo' => $forma_pagos_anticipo,
            'id_proyecto' => $proyecto[0]->id_proyecto,
            'ice' => $ice_nota_venta

        ];
    }
    public function agregarAsientoNotaVenta(Request $request)
    {

        NotaVenta::where('id_nota_venta', $request->cod_rol)->update(['contabilidad' => '1']);
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
        $asientos->id_asientos_comprobante = 22;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalleNotaVenta(Request $request)
    {
        foreach ($request->productos as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["sector"] == "producto" && $haber["iva"] == "doce") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_iva_12"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            if ($haber["sector"] == "producto" && $haber["iva"] == "cero") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_iva_0"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            if ($haber["sector"] == "servicio") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_servicio"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }
        foreach ($request->ice as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["haber"] > 0) {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }
        foreach ($request->iva_12 as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["haber"] > 0) {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }

        foreach ($request->pagos_sin_plc as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["debe"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->pagos_con_plc as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["debe"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->pagos_anticipo as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["debe"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }

        foreach ($request->creditos as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }

        foreach ($request->retencion_iva as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
        foreach ($request->retencion_renta as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
    }
    ////////////////////////////////////
    public function vendedores(Request $request)
    {
        $user = DB::select("SELECT * from user where id=" . $request->empresa);
        if ($user[0]->id_rol == 2) {
            $ver = DB::select("SELECT * FROM vendedor WHERE id_user =" . $request->empresa);
        } else {
            $ver = DB::select("SELECT * FROM vendedor WHERE id_empresa =" . $user[0]->id_empresa);
        }

        return $ver;
    }
    public function vendedoresEmpresa(Request $request)
    {
        $ver = DB::select("SELECT * FROM vendedor WHERE id_empresa =" . $request->empresa);
        return $ver;
    }
    public function verificaproducto(Request $request)
    {
        $empresa = $request->usuario["id_empresa"];
        $respuesta = array();
        for ($a = 0; $a < count($request->productos); $a++) {
            $id = $request->productos[$a]["id_producto"];
            $id_detalle = $request->productos[$a]["id_detalle"];
            $empresa = $request->usuario["id_empresa"];
            $establecimiento = $request->usuario["id_establecimiento"];

            $res =  DB::select("SELECT det.id_detalle, det.cantidad, det.precio, det.descuento as descuento_f, det.p_descuento, p.*, pb.id_producto_bodega AS id_producto_bodega, b.nombre AS nombrebodega, pb.cantidad as cantidadreal, ice.nombre AS nombreice, p.total_ice as total_ice_f FROM producto p LEFT JOIN detalle det ON det.id_detalle = $id_detalle LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND p.id_producto = $id AND p.id_empresa = $empresa");
            $res1 = DB::select("SELECT det.id_detalle, det.cantidad, det.precio, det.descuento as descuento_f, det.p_descuento, p.*, ice.nombre AS nombreice FROM producto p LEFT JOIN detalle det ON det.id_detalle = $id_detalle LEFT JOIN ice ON ice.id_ice = p.ice WHERE p.sector = 2 AND p.id_producto = $id AND p.id_empresa = $empresa");
            $res2 = array_merge($res1, $res);
            array_push($respuesta, $res2[0]);
        }
        return $respuesta;
    }
    function duplicar(Request $request)
    {
        //mediante el id de la factura ya existente recupera los datos de la factura para duplicar los registros de dicha factura elejida
        $id = $request->id;
        $factura = DB::select("SELECT *, (SELECT respuesta FROM guia_remision WHERE id_factura = factura.id_factura LIMIT 1) AS respuesta_guia FROM factura WHERE id_factura = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);
        $pln = 0;
        $seg = 0;
        if (isset($cliente[0]->id_plan_seguro) && $cliente[0]->id_plan_seguro !== null) {
            $pln = $cliente[0]->id_plan_seguro;
        }
        if (isset($cliente[0]->id_seguro) && $cliente[0]->id_seguro !== null) {
            $seg = $cliente[0]->id_seguro;
        }
        $productos = DB::select("SELECT d.*, p.cod_principal, p.cod_alterno, ice.nombre as nombreice, p.total_ice,pb.cantidad as cantidadreal,p.sector,$seg as id_seguro,$pln as id_plan_seguro,(select plan_seguro.descuento from plan_seguro_detalle INNER JOIN plan_seguro ON plan_seguro.id_plan_seguro=plan_seguro_detalle.id_plan_seguro INNER JOIN producto ON producto.id_producto=plan_seguro_detalle.id_producto where plan_seguro_detalle.id_plan_seguro=$pln and plan_seguro_detalle.id_producto=p.id_producto and plan_seguro_detalle.agregado=1 limit 1) as descuento_seguro 
        FROM detalle d 
        INNER JOIN producto p ON p.id_producto=d.id_producto 
        LEFT JOIN ice ON ice.id_ice = p.ice 
        LEFT JOIN producto_bodega pb ON pb.id_producto_bodega=d.id_producto_bodega 
        WHERE d.id_factura = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);

        $pagos = DB::select("SELECT fp.numero_transaccion AS nro_trans, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta FROM factura_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas WHERE fp.estado = 1 AND fp.id_factura = " . $id);
        $creditos = DB::select("SELECT estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto,existe_interes,interes,total_pagar_interes FROM factura_pagos WHERE estado = 2 AND id_factura = " . $id);
        $iva = DB::select("SELECT r.*, rf.porcentajeiva, rf.cantidadiva FROM retencion_factura rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_factura = $id ORDER BY rf.id_factura DESC");
        $renta = DB::select("SELECT r.*, rf.baserenta, rf.porcentajerenta FROM retencion_factura rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_factura = $id ORDER BY rf.id_factura DESC");

        $guia = DB::select("SELECT * FROM guia_remision WHERE id_factura = $id ORDER BY id_factura DESC LIMIT 1");
        $cuotas = DB::select("SELECT * from cuota_extra_factura where id_factura=$id");
        //recupera el primer credito de la factura
        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }

        //recupera el primer registro de la guia
        $guias = "";
        if (count($guia)) {
            $guias = $guia[0];
        }

        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'cliente' => $cliente[0],
            'pagos' => $pagos,
            'creditos' => $factura_creditos,
            'iva' => $iva,
            'renta' => $renta,
            'guia' => $guias,
            'cuota_extra' => $cuotas
        ];
    }
    function duplicar_nota_venta(Request $request)
    {
        //mediante el id de la factura ya existente recupera los datos de la factura para duplicar los registros de dicha factura elejida
        $id = $request->id;
        $factura = DB::select("SELECT *, (SELECT respuesta FROM guia_remision WHERE id_nota_venta = nota_venta.id_nota_venta LIMIT 1) AS respuesta_guia FROM nota_venta WHERE id_nota_venta = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);
        $pln = 0;
        $seg = 0;
        if (isset($cliente[0]->id_plan_seguro) && $cliente[0]->id_plan_seguro !== null) {
            $pln = $cliente[0]->id_plan_seguro;
        }
        if (isset($cliente[0]->id_seguro) && $cliente[0]->id_seguro !== null) {
            $seg = $cliente[0]->id_seguro;
        }
        $productos = DB::select("SELECT d.*, p.cod_principal, p.cod_alterno, ice.nombre as nombreice, p.total_ice,pb.cantidad as cantidadreal,p.sector,$seg as id_seguro,$pln as id_plan_seguro,(select plan_seguro.descuento from plan_seguro_detalle INNER JOIN plan_seguro ON plan_seguro.id_plan_seguro=plan_seguro_detalle.id_plan_seguro INNER JOIN producto ON producto.id_producto=plan_seguro_detalle.id_producto where plan_seguro_detalle.id_plan_seguro=$pln and plan_seguro_detalle.id_producto=p.id_producto and plan_seguro_detalle.agregado=1 limit 1) as descuento_seguro
        FROM detalle_nota_venta d 
        INNER JOIN producto p ON p.id_producto=d.id_producto 
        LEFT JOIN ice ON ice.id_ice = p.ice 
        LEFT JOIN producto_bodega pb ON pb.id_producto_bodega=d.id_producto_bodega 
        WHERE d.id_nota_venta = " . $id);


        $pagos = DB::select("SELECT fp.numero_transaccion AS nro_trans, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta FROM nota_venta_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas WHERE fp.estado = 1 AND fp.id_nota_venta = " . $id);
        $creditos = DB::select("SELECT estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto,existe_interes,interes,total_pagar_interes FROM nota_venta_pagos WHERE estado = 2 AND id_nota_venta = " . $id);
        $iva = DB::select("SELECT r.*, rf.porcentajeiva, rf.cantidadiva FROM retencion_nota_venta rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_nota_venta = $id ORDER BY rf.id_nota_venta DESC");
        $renta = DB::select("SELECT r.*, rf.baserenta, rf.porcentajerenta FROM retencion_nota_venta rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_nota_venta = $id ORDER BY rf.id_nota_venta DESC");

        $guia = DB::select("SELECT * FROM guia_remision WHERE id_nota_venta = $id ORDER BY id_nota_venta DESC LIMIT 1");
        $cuotas = DB::select("SELECT * from cuota_extra_nota_venta where id_nota_venta=$id");
        //recupera el primer credito de la nota_venta
        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }

        //recupera el primer registro de la guia
        $guias = "";
        if (count($guia)) {
            $guias = $guia[0];
        }

        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'cliente' => $cliente[0],
            'pagos' => $pagos,
            'creditos' => $factura_creditos,
            'iva' => $iva,
            'renta' => $renta,
            'guia' => $guias,
            'cuota_extra' => $cuotas
        ];
    }
    public function imprimirTicket($id, $tipo)
    {
        date_default_timezone_set('America/Bogota');
        $fecha = date('d/m/Y', time());
        $hora = date('h:i:s', time());
        $factura = DB::select("SELECT factura.*,CONCAT(SUBSTR(clave_acceso,25,3),'-',SUBSTR(clave_acceso,28,3),'-',SUBSTR(clave_acceso,31,9)) as nro_factura,vendedor.nombre_vendedor from factura LEFT JOIN vendedor ON vendedor.id_vendedor=factura.id_vendedor where  factura.id_factura=$id");
        $detalle = DB::select("SELECT detalle.*,producto.descripcion as descripcion_producto from detalle,producto where producto.id_producto=detalle.id_producto and detalle.id_factura=$id");
        $detalle_comida = DB::select("SELECT detalle.*,producto.descripcion as descripcion_producto,if(producto.descripcion like '%COMIDA%','comida','bebida') as tipo_pedido from detalle,producto where producto.id_producto=detalle.id_producto and (producto.descripcion like '%COMIDA%' or producto.descripcion like '%BEBIDA%') and detalle.id_factura=$id");
        $empresa = DB::select("SELECT * from empresa where id_empresa={$factura[0]->id_empresa}");
        $user = DB::select("SELECT * from user,establecimiento where establecimiento.id_establecimiento=user.id_establecimiento and user.id={$factura[0]->id_user}");
        $cliente = DB::select("SELECT * from cliente where id_cliente={$factura[0]->id_cliente}");
        $pagos = DB::select("SELECT descripcion,total,estado from factura_pagos INNER JOIN forma_pagos on forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos where factura_pagos.estado=1 and id_factura=$id");
        // return [
        //     'factura'=>$factura[0],
        //     'productos' => $detalle,
        //     'cliente' => $cliente[0],
        //     'pagos' => $pagos,
        //     'empresa'=>$empresa[0],
        //     'user'=>$user[0],
        //     'fecha'=>$fecha,
        //     'hora'=>$hora
        // ];

        $facturas = $factura[0];
        $clientes = $cliente[0];
        $empresas = $empresa[0];
        $users = $user[0];
        $carpeta = constant("DATA_EMPRESA") . $empresa[0]->id_empresa . '/comprobantes/factura_ticket';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0755, true);
        }
        if ($factura[0]->id_empresa == 60) {

            $pdf = \PDF::loadView('pdf/ticket_factura', compact("facturas", "clientes", "empresas", "users", "detalle", "pagos", "fecha", "hora", "detalle_comida", "pagos"))->setPaper(array(0, 0, 230, 3276)); //->setPaper(array(0, 0,230,3276), 'portrait');
        } else {
            $pdf = \PDF::loadView('pdf/ticket_factura', compact("facturas", "clientes", "empresas", "users", "detalle", "pagos", "fecha", "hora", "detalle_comida", "pagos"))->setPaper(array(0, 0, 230, 1076)); //->setPaper(array(0, 0,230,3276), 'portrait');
        }
        //$pdf = \PDF::loadView('pdf/ticket_factura', compact("facturas", "clientes", "empresas", "users", "detalle", "pagos", "fecha", "hora", "detalle_comida", "pagos"))->setPaper(array(0, 0, 230, 3276)); //->setPaper(array(0, 0,230,3276), 'portrait');
        if ($tipo == 'd') {

            return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "{$empresa[0]->id_empresa}/comprobantes/factura_ticket/{$factura[0]->clave_acceso}.pdf")->stream("{$factura[0]->clave_acceso}.pdf");
        } else {

            return $pdf->setWarnings(false)->stream("{$factura[0]->clave_acceso}.pdf");
        }

        //$ticket=new imprimiTicket();
        //$ticket->factura_venta_ticket($factura[0],$detalle,$empresa[0],$user[0],$cliente[0],$pagos,$request->nombre_impresora,$fecha,$hora);
        // $pdf=new generarReportes();
        // $strPDF=$pdf->imprimirFactura($factura[0],$detalle,$empresa[0],$user[0],$cliente[0],$pagos,$request->nombre_impresora,$fecha,$hora,$ruta);
        // return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        //return response($strPDF, 200)->header('Content-Type', 'application/pdf');
    }
    public function factura_fisica(Request $request)
    {
        // se ejecutara los select de la factura,los productos y pagos
        $factura=DB::select("SELECT * from factura INNER JOIN cliente ON cliente.id_cliente=factura.id_cliente where id_factura={$request->id}");
        $detalle=DB::select("SELECT detalle.* from detalle INNER JOIN producto ON producto.id_producto=detalle.id_producto where id_factura={$request->id}");
        $factura_pagos=DB::select("SELECT * from factura_pagos 
                                    LEFT JOIN forma_pagos
                                    ON forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos 
                                    where id_factura={$request->id}");
        $empresa=DB::select("SELECT empresa.* from factura INNER JOIN empresa ON empresa.id_empresa=factura.id_empresa where id_factura={$request->id}");
        $Reportes = new generarReportes();
        $strPDF = $Reportes->factura_fisica_pdf($factura,$detalle,$factura_pagos,$empresa);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');

    }
    public function ejemplo_ticket($id)
    {
        date_default_timezone_set('America/Bogota');
        $fecha = date('d/m/Y', time());
        $hora = date('h:i:s', time());
        $factura = DB::select("SELECT factura.*,CONCAT(SUBSTR(clave_acceso,25,3),'-',SUBSTR(clave_acceso,28,3),'-',SUBSTR(clave_acceso,31,9)) as nro_factura,vendedor.nombre_vendedor from factura LEFT JOIN vendedor ON vendedor.id_vendedor=factura.id_vendedor where  factura.id_factura=$id");
        $detalle = DB::select("SELECT detalle.*,producto.descripcion as descripcion_producto from detalle,producto where producto.id_producto=detalle.id_producto and detalle.id_factura=$id");
        $detalle_comida = DB::select("SELECT detalle.*,producto.descripcion as descripcion_producto,if(producto.descripcion like '%COMIDA%','comida','bebida') as tipo_pedido from detalle,producto where producto.id_producto=detalle.id_producto and (producto.descripcion like '%COMIDA%' or producto.descripcion like '%BEBIDA%') and detalle.id_factura=$id");
        $empresa = DB::select("SELECT * from empresa where id_empresa={$factura[0]->id_empresa}");
        $user = DB::select("SELECT * from user,establecimiento where establecimiento.id_establecimiento=user.id_establecimiento and user.id={$factura[0]->id_user}");
        $cliente = DB::select("SELECT * from cliente where id_cliente={$factura[0]->id_cliente}");
        $pagos = DB::select("SELECT descripcion,total,estado from factura_pagos INNER JOIN forma_pagos on forma_pagos.id_forma_pagos=factura_pagos.id_forma_pagos where factura_pagos.estado=1 and id_factura=$id");
        $ticket = new imprimiTicket();
        $ticket->factura_venta_ticket($factura[0], $detalle, $empresa[0], $user[0], $cliente[0], $pagos, $request->nombre_impresora, $fecha, $hora);
    }
    public function imprimirTicketNotaVenta($id, $tipo)
    {
        date_default_timezone_set('America/Bogota');
        $fecha = date('d/m/Y', time());
        $hora = date('h:i:s', time());
        $factura = DB::select("SELECT factura.*,CONCAT(SUBSTR(clave_acceso,25,3),'-',SUBSTR(clave_acceso,28,3),'-',SUBSTR(clave_acceso,31,9)) as nro_factura,vendedor.nombre_vendedor from nota_venta as factura LEFT JOIN vendedor ON vendedor.id_vendedor=factura.id_vendedor where  factura.id_nota_venta=$id");
        $detalle = DB::select("SELECT * from detalle_nota_venta where id_nota_venta=$id");
        $empresa = DB::select("SELECT * from empresa where id_empresa={$factura[0]->id_empresa}");
        $user = DB::select("SELECT * from user,establecimiento where establecimiento.id_establecimiento=user.id_establecimiento and user.id={$factura[0]->id_user}");
        $cliente = DB::select("SELECT * from cliente where id_cliente={$factura[0]->id_cliente}");
        $pagos = DB::select("SELECT descripcion,total from nota_venta_pagos INNER JOIN forma_pagos on forma_pagos.id_forma_pagos=nota_venta_pagos.id_forma_pagos where nota_venta_pagos.estado=1 and id_nota_venta=$id");
        // return [
        //     'factura'=>$factura[0],
        //     'productos' => $detalle,
        //     'cliente' => $cliente[0],
        //     'pagos' => $pagos,
        //     'empresa'=>$empresa[0],
        //     'user'=>$user[0],
        //     'fecha'=>$fecha,
        //     'hora'=>$hora
        // ];

        $facturas = $factura[0];
        $clientes = $cliente[0];
        $empresas = $empresa[0];
        $users = $user[0];
        $carpeta = constant("DATA_EMPRESA") . $empresa[0]->id_empresa . '/comprobantes/nota_venta_ticket';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0755, true);
        }
        $pdf = \PDF::loadView('pdf/ticket_nota_venta', compact("facturas", "clientes", "empresas", "users", "detalle", "pagos", "fecha", "hora"))->setPaper(array(0, 0, 230, 1076)); //->setPaper(array(0, 0, 230, 3276)); //->setPaper(array(0, 0,230,3276), 'portrait');
        if ($tipo == 'd') {

            return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "{$empresa[0]->id_empresa}/comprobantes/nota_venta_ticket/{$factura[0]->clave_acceso}.pdf")->stream("{$factura[0]->clave_acceso}.pdf");
        } else {

            return $pdf->setWarnings(false)->stream("{$factura[0]->clave_acceso}.pdf");
        }

        //$ticket=new imprimiTicket();
        //$ticket->factura_venta_ticket($factura[0],$detalle,$empresa[0],$user[0],$cliente[0],$pagos,$request->nombre_impresora,$fecha,$hora);
        // $pdf=new generarReportes();
        // $strPDF=$pdf->imprimirFactura($factura[0],$detalle,$empresa[0],$user[0],$cliente[0],$pagos,$request->nombre_impresora,$fecha,$hora,$ruta);
        // return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        //return response($strPDF, 200)->header('Content-Type', 'application/pdf');
    }
    public function productos_anterior(Request $request)
    {
        $pto_emision = DB::select("SELECT * from punto_emision where id_bodega is not null  and  id_punto_emision={$request->id_pto_emision}");
        //verifica si existe cliente elegido antes de listar los productos
        //si existe cliente recupera el precio dependiendo de la lista que se asigno al cliente
        if ($request->id) {
            $factura = DB::select("SELECT max(fecha_factura) as fecha_emision from factura where id_cliente=$request->id ");
            // $cli = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $request->id);
            // $precio = $cli[0]->lista_precios;

            // //dd("SELECT p.*, $contt, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 ORDER BY p.codigo_barras DESC LIMIT 10");
            // //recupera tanto productos como servicios $res = producto, $res1 = servicio
            // if(count($pto_emision)>0){
            //     $res =  DB::select("SELECT p.*, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega={$pto_emision[0]->id_bodega} ORDER BY p.codigo_barras DESC LIMIT 10");
            // }else{
            //     $res =  DB::select("SELECT p.*, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega=0 ORDER BY p.codigo_barras DESC LIMIT 10");
            // }

            // $res1 = DB::select("SELECT p.*, $contt, ice.nombre AS nombreice,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");
            // //
            // //concatena los dos array en uno solo y lista los productos dentro de la lista
            // $res2 = array_merge($res1, $res);
            if (count($factura) > 0) {
                $id_prod = 0;
                if ($request->id_producto) {
                    $id_prod = $request->id_producto;
                }
                $res2 = DB::select("SELECT detalle.precio from detalle 
                    INNER JOIN factura
                    on factura.id_factura=detalle.id_factura
                    INNER JOIN cliente
                    on cliente.id_cliente=factura.id_cliente
                    where cliente.id_cliente={$request->id} and detalle.id_producto={$id_prod} order by detalle.id_detalle desc limit 1");
                if (count($res2) > 0) {
                    if ($res2[0]->precio > 0) {
                        return $res2;
                    } else {
                        return "no hay producto";
                    }
                } else {
                    return "no hay producto";
                }
            } else {
                return "vacio";
            }
        } else {
            return "no existe";
        }
        // else{
        //     //recupera tanto productos como servicios $res = producto, $res1 = servicio
        //     if(count($pto_emision)>0){
        //         $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega={$pto_emision[0]->id_bodega} ORDER BY p.codigo_barras DESC LIMIT 10");
        //     }else{
        //         $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega=0 ORDER BY p.codigo_barras DESC LIMIT 10");
        //     }

        //     $res1 = DB::select("SELECT p.*, p.pvp_precio1 AS precio, ice.nombre AS nombreice,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");

        //     //concatena los dos array en uno solo y lista los productos dentro de la lista
        //     $res2 = array_merge($res1, $res);
        //     return $res2;
        // }
    }
    public function productos_anterior_nota_venta(Request $request)
    {
        $pto_emision = DB::select("SELECT * from punto_emision where id_bodega is not null  and  id_punto_emision={$request->id_pto_emision}");
        //verifica si existe cliente elegido antes de listar los productos
        //si existe cliente recupera el precio dependiendo de la lista que se asigno al cliente
        if ($request->id) {
            $factura = DB::select("SELECT max(fecha_emision) as fecha_emision from nota_venta where id_cliente=$request->id ");
            // $cli = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $request->id);
            // $precio = $cli[0]->lista_precios;

            // //dd("SELECT p.*, $contt, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 ORDER BY p.codigo_barras DESC LIMIT 10");
            // //recupera tanto productos como servicios $res = producto, $res1 = servicio
            // if(count($pto_emision)>0){
            //     $res =  DB::select("SELECT p.*, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega={$pto_emision[0]->id_bodega} ORDER BY p.codigo_barras DESC LIMIT 10");
            // }else{
            //     $res =  DB::select("SELECT p.*, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega=0 ORDER BY p.codigo_barras DESC LIMIT 10");
            // }

            // $res1 = DB::select("SELECT p.*, $contt, ice.nombre AS nombreice,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");
            // //
            // //concatena los dos array en uno solo y lista los productos dentro de la lista
            // $res2 = array_merge($res1, $res);
            if (count($factura) > 0) {
                $id_prod = 0;
                if ($request->id_producto) {
                    $id_prod = $request->id_producto;
                }
                $res2 = DB::select("SELECT detalle_nota_venta.precio 
                from detalle_nota_venta 
                    INNER JOIN nota_venta
                    on nota_venta.id_nota_venta=detalle_nota_venta.id_nota_venta
                    INNER JOIN cliente
                    on cliente.id_cliente=nota_venta.id_cliente
                    where cliente.id_cliente={$request->id} and detalle_nota_venta.id_producto={$id_prod} order by detalle_nota_venta.id_detalle_nota_venta desc limit 1");
                if (count($res2) > 0) {
                    if ($res2[0]->precio > 0) {
                        return $res2;
                    } else {
                        return "no hay producto";
                    }
                } else {
                    return "no hay producto";
                }
            } else {
                return "vacio";
            }
        } else {
            return "no existe";
        }
        // else{
        //     //recupera tanto productos como servicios $res = producto, $res1 = servicio
        //     if(count($pto_emision)>0){
        //         $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega={$pto_emision[0]->id_bodega} ORDER BY p.codigo_barras DESC LIMIT 10");
        //     }else{
        //         $res =  DB::select("SELECT p.*, p.pvp_precio1 AS precio, pb.id_producto_bodega, pb.cantidad, b.nombre AS nombrebodega, ice.nombre AS nombreice, b.id_bodega,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice LEFT JOIN producto_bodega pb ON pb.id_producto = p.id_producto LEFT JOIN bodega b ON b.id_bodega = pb.id_bodega WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND b.visible = 0 AND b.id_bodega=0 ORDER BY p.codigo_barras DESC LIMIT 10");
        //     }

        //     $res1 = DB::select("SELECT p.*, p.pvp_precio1 AS precio, ice.nombre AS nombreice,null as siiva FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 ORDER BY p.codigo_barras DESC LIMIT 10");

        //     //concatena los dos array en uno solo y lista los productos dentro de la lista
        //     $res2 = array_merge($res1, $res);
        //     return $res2;
        // }
    }
    public function reportesCierreCaja(Request $request)
    {
        //dd("aqui llega");
        $queries = [];
        $fields = [];
        $inners = [];
        $clients = [];
        $pagos = [];
        $ctas_pagos = [];
        $solo_pagos = [];

        // $queries = [];

        if ($request->company == 59) {
            if ($request->date) {
                if (!is_null(json_decode($request->date))) {
                    $request->date = json_decode($request->date);
                    $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                    $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                    array_push($queries, "date(cta.fecha_factura) between date('{$date_initial}') and date('{$date_final}')\n");
                    array_push($pagos, "((date(ctap.fecha_pago) between date('{$date_initial}') and date('{$date_final}')) or
                                                      (date(ctap.fecha_registro) between date('{$date_initial}') and date('{$date_final}')))\n");
                    array_push($pagos, "date(cta.fecha_factura) between date('{$date_initial}') and date('{$date_final}')\n");
                    // array_push($solo_pagos, "((date(ctap.fecha_pago) between date('{$date_initial}') and date('{$date_final}')) or
                    //                                     (date(ctap.fecha_registro) between date('{$date_initial}') and date('{$date_final}')))\n");
                    array_push($solo_pagos, "if(ctap.fecha_registro is not null,
                                                    date(ctap.fecha_registro) between date('{$date_initial}') and date('{$date_final}'),
                                                    date(ctap.fecha_pago) between date('{$date_initial}') and date('{$date_final}')
                                                )\n");
                    //array_push($solo_pagos, "cta.fecha_factura<'{$date_initial}'\n");
                } else {
                    $date_initial = str_replace("-010-", "-10-", $request->date);
                    $date_final = str_replace("-010-", "-10-", $request->date);
                    //$date = str_replace("-010-", "-10-", $request->date);
                    array_push($queries, "cta.fecha_factura like '{$date_initial}%'\n");
                    array_push($pagos, "((ctap.fecha_pago like '{$date_initial}%') or
                                                      (ctap.fecha_registro like '{$date_initial}%'))\n");
                    array_push($pagos, "cta.fecha_factura like '{$date_initial}%'\n");
                    // array_push($solo_pagos, "((ctap.fecha_pago like '{$date_initial}%') or
                    //                                   (ctap.fecha_registro like '{$date_initial}%')) \n");
                    array_push($solo_pagos, "if(ctap.fecha_registro is not null,
                                                    ctap.fecha_registro like '{$date_initial}%',
                                                    ctap.fecha_pago like '{$date_initial}%'
                                                )\n");
                    //array_push($solo_pagos, "cta.fecha_factura<'{$date_initial}'\n");
                }
            }
            if ($request->client) {
                $info_client = json_decode($request->client);

                if ($info_client->id != 0) {

                    $exists_client = true;
                    array_push($queries, "cliente.id_cliente = {$info_client->id}\n");
                    array_push($pagos, "ctap.id_cliente = {$info_client->id}\n");
                    array_push($solo_pagos, "ctap.id_cliente = {$info_client->id}\n");
                }
            } else {
                $exists_client = false;
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
                    array_push($queries, "((f.valor_total {$typeSearchTotalCount} {$request->totalCount}) or
                                                (nv.valor_total {$typeSearchTotalCount} {$request->totalCount}))\n");
                    array_push($pagos, "((f.valor_total {$typeSearchTotalCount} {$request->totalCount}) or
                                                (nv.valor_total {$typeSearchTotalCount} {$request->totalCount}))\n");
                    array_push($solo_pagos, "((f.valor_total {$typeSearchTotalCount} {$request->totalCount}) or
                                                (nv.valor_total {$typeSearchTotalCount} {$request->totalCount}))\n");
                }
            }
            if ($request->selectedEstablishment) {
                $info_establishment = json_decode($request->selectedEstablishment);
                if ($info_establishment->id != 0) {
                    array_push($queries, "((f.id_establecimiento = {$info_establishment->id}) or
                                                (nv.id_establecimiento = {$info_establishment->id})) \n");
                    array_push($pagos, "((f.id_establecimiento = {$info_establishment->id}) or
                                                (nv.id_establecimiento = {$info_establishment->id})) \n");
                    array_push($solo_pagos, "((f.id_establecimiento = {$info_establishment->id}) or
                                                (nv.id_establecimiento = {$info_establishment->id})) \n");
                }
            }
            if ($request->selectedPointOfEmission) {
                $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
                if ($info_pointOfEmission->id != 0) {
                    array_push($queries, "((f.id_punto_emision = {$info_pointOfEmission->id}) or
                                                (nv.id_punto_emision = {$info_pointOfEmission->id}))\n");
                    array_push($pagos, "((f.id_punto_emision = {$info_pointOfEmission->id}) or
                                                (nv.id_punto_emision = {$info_pointOfEmission->id}))\n");
                    array_push($solo_pagos, "((f.id_punto_emision = {$info_pointOfEmission->id}) or
                                                (nv.id_punto_emision = {$info_pointOfEmission->id}))\n");
                }
            }
            $nombre_vendedor = "";
            if ($request->rol_user !== "2" || ($request->user_name == 'Erick' && $request->user_lastname == 'Zambrano Mendoza')) {


                if ($request->users) {

                    $info_seller = json_decode($request->users, true);
                    if ($info_seller["id"] != 0) {

                        array_push($queries, "((f.id_user = {$info_seller["id"]}) or 
                                                    (nv.id_user = {$info_seller["id"]}))\n");
                        array_push($pagos, "ctap.ucrea = {$info_seller["id"]}\n");
                        array_push($solo_pagos, "ctap.ucrea = {$info_seller["id"]}\n");
                        $nombre_vendedor = $info_seller["fullname"];
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
                if (count($vnd) > 0) {


                    array_push($queries, "((f.id_user = {$vnd[0]->id}) or
                                                    (nv.id_user = {$vnd[0]->id}))\n");
                    array_push($pagos, "ctap.ucrea = {$vnd[0]->id}\n");
                    array_push($solo_pagos, "ctap.ucrea = {$vnd[0]->id}\n");
                    $nombre_vendedor = $vnd[0]->nombre_vendedor;
                }
            }

            $fields = implode("", $fields);
            $queries = implode(" AND ", $queries);
            $pagos = implode(" and ", $pagos);
            $solo_pagos = implode(" and ", $solo_pagos);
            $query =
                "SELECT sum(valor_cuota) as valor_cuota,cliente.id_cliente,cliente.nombre,cliente.identificacion,sum(if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota)) as valor_pago,sum(valor_cuota)-sum(if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota)) as descuento,                empresa.nombre_empresa,empresa.id_empresa,empresa.logo                
                FROM ctas_cobrar as cta
                    INNER JOIN cliente on cliente.id_cliente=cta.id_cliente
                    INNER JOIN empresa on empresa.id_empresa=cliente.id_empresa                                    
                    LEFT JOIN factura as f  on f.id_factura=cta.id_factura                                   
                    LEFT JOIN nota_venta as nv  on nv.id_nota_venta=cta.id_nota_venta 
                    where {$queries} and (cta.tipo=1 or cta.tipo=2) and cliente.id_empresa={$request->company} GROUP BY cta.id_cliente 
                ";
            $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->company);
            //dd($empresa);
            $reporte = DB::select($query);
            //dd($reporte);
            $valores_pagado =

                "SELECT sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2))) as  valor_pagado,cta.id_factura,cta.referencias,cta.id_cliente,(select nombre from cliente where id_cliente=cta.id_cliente) as nombre,(select identificacion from cliente where id_cliente=cta.id_cliente) as identificacion
                        from ctas_cobrar as cta
                        INNER JOIN ctas_cobrar_pagos as ctap
                        on ctap.id_cliente=cta.id_cliente
                        INNER JOIN forma_pagos
                        on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                        LEFT JOIN factura as f
                        on f.id_factura=cta.id_factura
                        LEFT JOIN nota_venta as nv
                        on nv.id_nota_venta=cta.id_nota_venta
                                                    where {$pagos}  and forma_pagos.id_empresa={$request->company} and (cta.tipo=1 or cta.tipo=2) and ctap.referencia like concat('%;',cta.id_ctascobrar,';%')
                                                    GROUP BY cta.id_cliente
                                                    ORDER BY (select nombre from cliente where id_cliente=cta.id_cliente) asc";
            $valores_pago =

                "SELECT sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2))) as  valor_pagado,cta.id_factura,cta.referencias,cta.id_cliente,(select nombre from cliente where id_cliente=cta.id_cliente) as nombre,(select identificacion from cliente where id_cliente=cta.id_cliente) as identificacion
                                from ctas_cobrar as cta
                                INNER JOIN ctas_cobrar_pagos as ctap
                                on ctap.id_cliente=cta.id_cliente
                                INNER JOIN forma_pagos
                                on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                                LEFT JOIN factura as f
                                on f.id_factura=cta.id_factura
                                LEFT JOIN nota_venta as nv
                                on nv.id_nota_venta=cta.id_nota_venta
                                                            where {$solo_pagos}  and forma_pagos.id_empresa={$request->company} and (cta.tipo=1 or cta.tipo=2) and ctap.referencia like concat('%;',cta.id_ctascobrar,';%')
                                                            GROUP BY cta.id_cliente
                                                            ORDER BY (select nombre from cliente where id_cliente=cta.id_cliente) asc";

            $reporte_valores_pagado = DB::select($valores_pagado);
            $reporte_valores_pago = DB::select($valores_pago);
            //dd($reporte_valores_pago);
            $Reportes = new generarReportes();

            if (!$reporte && !$reporte_valores_pago) {
                return response('no-data-report', 200)->header('Content-Type', 'application/json');
            } else {
                $strPDF = $Reportes->cierre_caja_reporteSUMIPAN($reporte, $reporte_valores_pagado, $reporte_valores_pago, $date_initial, $date_final, $empresa[0], $nombre_vendedor);
                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
            }
        } else {
            if($request->company == 34){
                if ($request->client) {
                    $info_client = json_decode($request->client, true);
                    if ($info_client["id"] != 0) {
                        array_push($queries, "cl.id_cliente = {$info_client["id"]}\n");
                        array_push($ctas_pagos, "cl.id_cliente = {$info_client["id"]}\n");
                    }
                }
                // if ($request->rol_user == 2) {
                //     $vnd = DB::select("SELECT * from user where id=" . $request->user);
                //     if ($vnd) {
                //         array_push($queries, "cxc.ucrea = {$request->user}\n");
                //         array_push($ctas_pagos, "cxc.created_by = {$request->user}\n");
                //     }
                // } else {
                //     if ($request->users) {
                //         $info_seller = json_decode($request->users, true);
                //         if ($info_seller["id"] != 0) {
                //             array_push($queries, "cxc.ucrea = {$info_seller["id"]}\n");
                //             array_push($ctas_pagos, "cxc.created_by = {$info_seller["id"]}\n");
                //         }
                //     }
                // }
    
                $nombre_producto = '';
                if ($request->selectedEstablishment) {
                    $info_establishment = json_decode($request->selectedEstablishment);
                    if ($info_establishment->id != 0) {
                        array_push($queries, "user.id_establecimiento = {$info_establishment->id}\n");
                        array_push($ctas_pagos, "user.id_establecimiento = {$info_establishment->id}\n");
                    }
                }
                if ($request->selectedPointOfEmission) {
                    $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
                    if ($info_pointOfEmission->id != 0) {
                        array_push($queries, "user.id_punto_emision = {$info_pointOfEmission->id}\n");
                        array_push($ctas_pagos, "user.id_punto_emision = {$info_pointOfEmission->id}\n");
                    }
                }
    
                if ($request->date) {
                    if (!is_null(json_decode($request->date))) {
                        $request->date = json_decode($request->date);
                        $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                        $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                        array_push($queries, "if(cxc.fecha_registro is null,
                                                date(cxc.fecha_pago) between date('{$date_initial}') and date('{$date_final}'),
                                                date(cxc.fecha_registro) between date('{$date_initial}') and date('{$date_final}'))\n");
                        array_push($ctas_pagos, "if(cxc.fecha_registro is null,
                                                date(cxc.fecha_pago) between date('{$date_initial}') and date('{$date_final}'),
                                                date(cxc.fecha_registro) between date('{$date_initial}') and date('{$date_final}'))\n");
                    } else {
                        $date = str_replace("-010-", "-10-", $request->date);
                        array_push($queries, "if(cxc.fecha_registro is null,
                                                                        date(cxc.fecha_pago) = date('{$date}'),
                                                                        date(cxc.fecha_registro) = date('{$date}'))\n");
                        array_push($ctas_pagos, "if(cxc.fecha_registro is null,
                                                                        date(cxc.fecha_pago) = date('{$date}'),
                                                                        date(cxc.fecha_registro) = date('{$date}'))\n");                                                
                    }
                }

    
                $queries = implode(" and ", $queries);
                $ctas_pagos = implode(" and ", $ctas_pagos);
                $modo = $request->modo == "0" ? 0 : 1;

                $query="SELECT cxc.posicion,cl.nombre,if(cxc.pagos_por like '%Anticipo%','ANTICIPO',fp.descripcion) as forma_pago_cxc,if(cxc.fecha_registro is null,cxc.fecha_pago,cxc.fecha_registro) as fecha_emision,cxc.valor_real_pago as valor_total
                                    from ctas_cobrar_pagos as cxc
                                    INNER JOIN cliente  as cl
                                    on cl.id_cliente=cxc.id_cliente
                                    LEFT JOIN user
                                    on user.id=cxc.ucrea
                                    LEFT JOIN establecimiento as est
                                    on est.id_establecimiento=user.id_establecimiento
                                    LEFT JOIN punto_emision as pto
                                    on pto.id_punto_emision=user.id_punto_emision
                                    LEFT JOIN forma_pagos as fp
                                    on fp.id_forma_pagos=cxc.id_forma_pagos
                                    where {$queries} and cl.id_empresa={$request->company} and cxc.pagos_por not like '%Anticipo%' and cxc.pago_anticipo is null
                                    UNION
                                    SELECT cxc.posicion,cl.nombre,'ANTICIPO' as forma_pago_cxc,if(cxc.fecha_registro is null,cxc.fecha_pago,cxc.fecha_registro) as fecha_emision,valor_cuota as valor_total
									from ctas_cobrar as cxc
                                    INNER JOIN cliente  as cl
                                    on cl.id_cliente=cxc.id_cliente
                                    LEFT JOIN user
                                    on user.id=cxc.created_by
                                    LEFT JOIN establecimiento as est
                                    on est.id_establecimiento=user.id_establecimiento
                                    LEFT JOIN punto_emision as pto
                                    on pto.id_punto_emision=user.id_punto_emision
                                    LEFT JOIN forma_pagos as fp
                                    on fp.id_forma_pagos=cxc.id_forma_pagos
									where {$ctas_pagos} and cl.id_empresa={$request->company} and cxc.tipo=3 
									ORDER BY posicion";
                // dd("SELECT max(descripcion) as descripcion,sum(valor_total) as valor_total
                // from(
                //     select fp.descripcion,sum(cxc.valor_real_pago) as valor_total
                //     from ctas_cobrar_pagos as cxc
                //     INNER JOIN cliente  as cl
                //     on cl.id_cliente=cxc.id_cliente
                //     LEFT JOIN user
                //     on user.id=cxc.ucrea
                //     LEFT JOIN establecimiento as est
                //     on est.id_establecimiento=user.id_establecimiento
                //     LEFT JOIN punto_emision as pto
                //     on pto.id_punto_emision=user.id_punto_emision
                //     LEFT JOIN forma_pagos as fp
                //     on fp.id_forma_pagos=cxc.id_forma_pagos
                //     where {$queries} and cl.id_empresa={$request->company}  and cxc.pagos_por not like '%Anticipo%'
                //     GROUP BY cxc.id_forma_pagos
                //     UNION
                //     select fp.descripcion,sum(cxc.valor_real_pago) as valor_total
                //     from ctas_cobrar_pagos as cxc
                //     INNER JOIN cliente  as cl
                //     on cl.id_cliente=cxc.id_cliente
                //     LEFT JOIN user
                //     on user.id=cxc.ucrea
                //     LEFT JOIN establecimiento as est
                //     on est.id_establecimiento=user.id_establecimiento
                //     LEFT JOIN punto_emision as pto
                //     on pto.id_punto_emision=user.id_punto_emision
                //     LEFT JOIN forma_pagos as fp
                //     on fp.id_forma_pagos=cxc.id_forma_pagos
                //     where {$ctas_pagos} and cl.id_empresa={$request->company}  and cxc.tipo=3
                //     GROUP BY cxc.id_forma_pagos
                //     ) t
                //     GROUP BY id_forma_pagos
                //     ORDER BY descripcion");
                $reporte = DB::select($query);
                $anticipos=[];
                // DB::select("SELECT sum(cxc.valor_real_pago) as valor_total
                //                         from ctas_cobrar_pagos as cxc
                //                         INNER JOIN cliente  as cl
                //                         on cl.id_cliente=cxc.id_cliente
                //                         LEFT JOIN user
                //                         on user.id=cxc.ucrea
                //                         LEFT JOIN establecimiento as est
                //                         on est.id_establecimiento=user.id_establecimiento
                //                         LEFT JOIN punto_emision as pto
                //                         on pto.id_punto_emision=user.id_punto_emision
                //                         where {$queries} and cl.id_empresa={$request->company} and cxc.pagos_por like '%Anticipo%'
                //                         Group By cxc.pagos_por");
                if(count($anticipos)>0){
                    $pagos_fact=DB::select("SELECT fp.descripcion as descripcion,sum(cxc.valor_real_pago) as valor_total
                                                from ctas_cobrar_pagos as cxc
                                                INNER JOIN cliente  as cl
                                                on cl.id_cliente=cxc.id_cliente
                                                LEFT JOIN user
                                                on user.id=cxc.ucrea
                                                LEFT JOIN establecimiento as est
                                                on est.id_establecimiento=user.id_establecimiento
                                                LEFT JOIN punto_emision as pto
                                                on pto.id_punto_emision=user.id_punto_emision
                                                LEFT JOIN forma_pagos as fp
                                                on fp.id_forma_pagos=cxc.id_forma_pagos
                                                where {$queries} and cl.id_empresa={$request->company}
                                                GROUP BY cxc.id_forma_pagos
                                            UNION
                                            SELECT 'ANTICIPO' as descripcion,sum(cxc.valor_real_pago) as valor_total
                                                from ctas_cobrar_pagos as cxc
                                                INNER JOIN cliente  as cl
                                                on cl.id_cliente=cxc.id_cliente
                                                LEFT JOIN user
                                                on user.id=cxc.ucrea
                                                LEFT JOIN establecimiento as est
                                                on est.id_establecimiento=user.id_establecimiento
                                                LEFT JOIN punto_emision as pto
                                                on pto.id_punto_emision=user.id_punto_emision
                                                where {$queries} and cl.id_empresa={$request->company} and cxc.pagos_por like '%Anticipo%'
                                                ORDER BY descripcion");
                }else{
                    
                    $pagos_fact=DB::select("SELECT fp.descripcion,sum(cxc.valor_real_pago) as valor_total
                                                from ctas_cobrar_pagos as cxc
                                                INNER JOIN cliente  as cl
                                                on cl.id_cliente=cxc.id_cliente
                                                LEFT JOIN user
                                                on user.id=cxc.ucrea
                                                LEFT JOIN establecimiento as est
                                                on est.id_establecimiento=user.id_establecimiento
                                                LEFT JOIN punto_emision as pto
                                                on pto.id_punto_emision=user.id_punto_emision
                                                LEFT JOIN forma_pagos as fp
                                                on fp.id_forma_pagos=cxc.id_forma_pagos
                                                where {$queries} and cl.id_empresa={$request->company}  and cxc.pagos_por not like '%Anticipo%'
                                                GROUP BY cxc.id_forma_pagos
                                                UNION
                                                SELECT 'ANTICIPO' as descripcion,sum(cxc.valor_cuota) as valor_total
                                                from ctas_cobrar as cxc
                                                INNER JOIN cliente  as cl
                                                on cl.id_cliente=cxc.id_cliente
                                                LEFT JOIN user
                                                on user.id=cxc.ucrea
                                                LEFT JOIN establecimiento as est
                                                on est.id_establecimiento=user.id_establecimiento
                                                LEFT JOIN punto_emision as pto
                                                on pto.id_punto_emision=user.id_punto_emision
                                                LEFT JOIN forma_pagos as fp
                                                on fp.id_forma_pagos=cxc.id_forma_pagos
                                                where {$ctas_pagos} and cl.id_empresa={$request->company}  and cxc.tipo=3
                                                ORDER BY descripcion
                                                ");
                }
                
                $valores_ice = [];
     
                $empresa = DB::select("SELECT * FROM empresa where id_empresa={$request->company}");
                $Reportes = new generarReportes();
                if (!$reporte) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if (property_exists($request->date, 'initialDate')) {
                        $strPDF = $Reportes->cierre_caja_reporte($reporte, $modo, $valores_ice, $date_initial, $date_final, $nombre_producto, $pagos_fact, $empresa);
                    } else {
                        $strPDF = $Reportes->cierre_caja_reporte($reporte, $modo, $valores_ice, $request->date, $request->date, $nombre_producto, $pagos_fact, $empresa);
                    }
                    return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }
            }else{
                if ($request->client) {
                    $info_client = json_decode($request->client, true);
                    if ($info_client["id"] != 0) {
                        array_push($queries, "cl.id_cliente = {$info_client["id"]}\n");
                    }
                }
                if ($request->rol_user == 2) {
                    $vnd = DB::select("SELECT * from user where id=" . $request->user);
                    if ($vnd) {
                        array_push($queries, "((f.created_by = {$request->user}) or
                                                            (nv.created_by = {$request->user}))\n");
                    }
                } else {
                    if ($request->users) {
                        $info_seller = json_decode($request->users, true);
                        if ($info_seller["id"] != 0) {
                            array_push($queries, "((f.created_by = {$info_seller["id"]}) or 
                                                                (nv.created_by = {$info_seller["id"]}))\n");
                        }
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
                    array_push($queries, "df.id_producto in ({$info_products})\n");
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
                        array_push($queries, "((f.valor_total {$typeSearchTotalCount} {$request->totalCount}) or
                                                            (nv.valor_total {$typeSearchTotalCount} {$request->totalCount}))\n");
                    }
                }
                if ($request->selectedEstablishment) {
                    $info_establishment = json_decode($request->selectedEstablishment);
                    if ($info_establishment->id != 0) {
                        array_push($queries, "((f.id_establecimiento = {$info_establishment->id}) or
                                                            (nv.id_establecimiento = {$info_establishment->id})) \n");
                    }
                }
                if ($request->selectedPointOfEmission) {
                    $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
                    if ($info_pointOfEmission->id != 0) {
                        array_push($queries, "((f.id_punto_emision = {$info_pointOfEmission->id}) or
                                                            (nv.id_punto_emision = {$info_pointOfEmission->id}))\n");
                    }
                }
                if ($request->selectedProject) {
                    $info_project = json_decode($request->selectedProject);
                    if ($info_project->id != 0) {
                        array_push($queries, "f.id_proyecto = {$info_project->id}\n");
                    }
                }
                if ($request->date) {
                    if (!is_null(json_decode($request->date))) {
                        $request->date = json_decode($request->date);
                        $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                        $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                        array_push($queries, "((date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')) or
                                                            (date(nv.fecha_emision) between date('{$date_initial}') and date('{$date_final}')))\n");
                    } else {
                        $date = str_replace("-010-", "-10-", $request->date);
                        array_push($queries, "((date(f.fecha_emision) = date('{$date}')) or
                                                            (date(nv.fecha_emision) = date('{$date}')))\n");
                    }
                }
                if ($request->all === "false") {
                    if ($request->retention === "true") {
                        array_push($queries, "exists (select * from retencion_factura rf where rf.id_factura = f.id_factura)\n");
                    }
                    if ($request->credit === "true") {
                        array_push($queries, "exists (SELECT * FROM ctas_cobrar cxc where cxc.id_factura = f.id_factura)\n");
                    }
                }
                // if($request->){
    
                // }
                $queries = implode(" and ", $queries);
                $modo = $request->modo == "0" ? 0 : 1;
                // $query = "
                // SELECT
                // f.*,
                // cxc.id_ctascobrar,
                // cxc.num_cuota,
                // cxc.fecha_pago,
                // cxc.periodo_pagos,
                // cxc.valor_cuota,
                // cxc.pagos_por,
                // cxc.id_forma_pagos as id_forma_pagos_cxc,
                // cxc.id_banco,
                // cxc.numero_tarjeta,
                // cxc.numero_transaccion,
                // cxc.descuento as descuento_cxc,
                // cxc.valor_pagado,
                // cxc.estado as estado_cxc,
                // cxc.tipo,
                // cxc.abono,
                // cxc.fcrea as fcrea_cxc,
                // cxc.fmodifica as fcrea_cxc,
                // cxc.ucrea as ucrea_cxc,
                // cxc.umodifica as umodifica_cxc,
                // fpago.descripcion as forma_pago_cxc,
                // rf.cantidadiva,
                // rf.cantidadrenta,
                // c.identificacion,
                // c.nombre,
                // e.id_empresa as idempresa,
                // e.nombre_empresa,
                // e.logo,
                // vd.nombre_vendedor as vendedor
                // FROM factura f
                // inner join detalle df
                // on df.id_factura = f.id_factura
                // left join vendedor vd
                // on vd.id_vendedor = f.id_vendedor
                // inner join cliente c
                // on f.id_cliente = c.id_cliente
                // inner join empresa e
                // on f.id_empresa = e.id_empresa
                // inner join user u
                // on f.id_user = u.id
                // left join ctas_cobrar cxc
                // on f.id_factura = cxc.id_factura
                // left join retencion_factura rf
                // on f.id_factura = rf.id_factura
                // left join forma_pagos fpago
                // on fpago.id_forma_pagos = cxc.id_forma_pagos
                //     WHERE {$queries}
                //     and f.modo = {$modo}
                //     and f.id_empresa={$request->company}
                //     and f.estado>0
                //     order by f.fecha_emision,f.clave_acceso asc;
                //     ";
                $query = "SELECT if(f.clave_acceso is null,nv.clave_acceso,f.clave_acceso) as clave_acceso,if(f.fecha_emision is null,nv.fecha_emision,f.fecha_emision) as fecha_emision,f.id_factura,nv.id_nota_venta,cl.nombre,max(fpago.descripcion) as forma_pago_cxc,sum(cxc.valor_cuota) as valor_total
                            FROM ctas_cobrar as cxc
                                        LEFT JOIN factura as f
                                        on f.id_factura = cxc.id_factura
                                        LEFT JOIN nota_venta as nv
                                        on nv.id_nota_venta = cxc.id_nota_venta
                                        left join forma_pagos fpago
                                        on fpago.id_forma_pagos = cxc.id_forma_pagos
                                        INNER JOIN cliente  as cl
                                        on cl.id_cliente=cxc.id_cliente
                                            WHERE {$queries}  and cl.id_empresa={$request->company} and if(f.id_factura is null,nv.estado>0,f.estado>0)
                                            GROUP BY f.id_factura,nv.id_nota_venta,cl.id_cliente
                                            ORDER BY if(f.fecha_emision is null,nv.fecha_emision,f.fecha_emision)";
                //dd($query);
                $pagos_fact = DB::select("SELECT forma_pagos.descripcion,sum(ctas_cobrar.valor_cuota) as valor_total 
                            from ctas_cobrar 
                            LEFT JOIN factura as f
                            on f.id_factura=ctas_cobrar.id_factura
                            LEFT JOIN nota_venta as nv
                            on nv.id_nota_venta=ctas_cobrar.id_nota_venta
                            LEFT JOIN forma_pagos
                            on forma_pagos.id_forma_pagos=ctas_cobrar.id_forma_pagos
                            INNER JOIN cliente  as cl
                            on cl.id_cliente=ctas_cobrar.id_cliente
                            where {$queries} and cl.id_empresa={$request->company} and if(f.id_factura is null,nv.estado>0,f.estado>0)
                            GROUP BY forma_pagos.id_forma_pagos");
                $reporte = DB::select($query);
                // $valores_ice=DB::select("SELECT
                //     if(max(d.valor_ice)>0,ROUND(sum(d.valor_ice*d.cantidad),2),ROUND(sum(p.total_ice*d.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
                // FROM detalle d
                // INNER JOIN producto p ON p.id_producto=d.id_producto
                // LEFT JOIN ice ON ice.id_ice = p.ice
                // INNER JOIN factura f ON f.id_factura=d.id_factura
                // WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
                // GROUP BY f.id_factura");
                $valores_ice = [];
                //     $valores_ice=DB::select("SELECT
                //         if((df.valor_ice)>0,ROUND((df.valor_ice),2),ROUND((p.total_ice*df.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
                //     FROM detalle df
                //     INNER JOIN producto p ON p.id_producto=df.id_producto
                //     LEFT JOIN ice ON ice.id_ice = p.ice
                //     INNER JOIN factura f ON f.id_factura=df.id_factura
                //     WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
                // ");
                // dd("SELECT
                //             if((df.valor_ice)>0,ROUND((df.valor_ice*df.cantidad),2),ROUND((p.total_ice*df.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
                //         FROM detalle df
                //         INNER JOIN producto p ON p.id_producto=df.id_producto
                //         LEFT JOIN ice ON ice.id_ice = p.ice
                //         INNER JOIN factura f ON f.id_factura=df.id_factura
                //         WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
                //     ");
                //dd($reporte);
                $empresa = DB::select("SELECT * FROM empresa where id_empresa={$request->company}");
                $Reportes = new generarReportes();
                if (!$reporte) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if (property_exists($request->date, 'initialDate')) {
                        $strPDF = $Reportes->cierre_caja_reporte($reporte, $modo, $valores_ice, $date_initial, $date_final, $nombre_producto, $pagos_fact, $empresa);
                    } else {
                        $strPDF = $Reportes->cierre_caja_reporte($reporte, $modo, $valores_ice, $request->date, $request->date, $nombre_producto, $pagos_fact, $empresa);
                    }
                    return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }
            }
            
        }
    }
    public function reportesCheckList(Request $request)
    {
        $facturas = [];
        $nota_venta = [];
        $vend = [];
        if ($request->client) {
            $info_client = json_decode($request->client, true);
            if ($info_client["id"] != 0) {
                array_push($queries, "cl.id_cliente = {$info_client["id"]}\n");
            }
        }
        // if ($request->rol_user == 2) {
        //     $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->user);
        //     if ($vnd) {
        //         array_push($queries, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
        //                                 (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
        //     }
        // } else {
        $vendedor_todos = false;
        if ($request->seller) {
            $info_seller = json_decode($request->seller, true);
            if ($info_seller["id"] != 0) {
                array_push($facturas, "f.id_vendedor = {$info_seller["id"]}\n");
                array_push($nota_venta, "nv.id_vendedor = {$info_seller["id"]}\n");
                array_push($vend, "((f.id_vendedor = {$info_seller["id"]}) or
                                       (nv.id_vendedor = {$info_seller["id"]}))\n");
            } else {
                $vendedores = DB::select("SELECT * from vendedor where id_empresa={$request->company}");
                $vendedor_todos = true;
            }
        }
        //}

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

            array_push($facturas, "df.id_producto in ({$info_products})\n");
            array_push($nota_venta, "dn.id_producto in ({$info_products})\n");
            array_push($vend, "((dn.id_producto in ({$info_products})) or 
                                        (df.id_producto in ({$info_products})))\n");
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
                array_push($facturas, "f.valor_total {$typeSearchTotalCount} {$request->totalCount}\n");
                array_push($nota_venta, "nv.valor_total {$typeSearchTotalCount} {$request->totalCount}\n");
                array_push($vend, "((f.valor_total {$typeSearchTotalCount} {$request->totalCount}) or
                                                        (nv.valor_total {$typeSearchTotalCount} {$request->totalCount}))\n");
            }
        }
        if ($request->selectedEstablishment) {
            $info_establishment = json_decode($request->selectedEstablishment);
            if ($info_establishment->id != 0) {
                array_push($facturas, "f.id_establecimiento = {$info_establishment->id}\n");
                array_push($nota_venta, "nv.id_establecimiento = {$info_establishment->id}\n");
                array_push($vend, "((f.id_establecimiento = {$info_establishment->id}) or
                                                        (nv.id_establecimiento = {$info_establishment->id})) \n");
            }
        }
        if ($request->selectedPointOfEmission) {
            $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
            if ($info_pointOfEmission->id != 0) {
                array_push($facturas, "f.id_punto_emision = {$info_pointOfEmission->id}\n");
                array_push($nota_venta, "nv.id_punto_emision = {$info_pointOfEmission->id}\n");
                array_push($vend, "((f.id_punto_emision = {$info_pointOfEmission->id}) or
                                                        (nv.id_punto_emision = {$info_pointOfEmission->id}))\n");
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
                array_push($facturas, "date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
                array_push($nota_venta, "date(nv.fecha_emision) between date('{$date_initial}') and date('{$date_final}')\n");
                array_push($vend, "((date(f.fecha_emision) between date('{$date_initial}') and date('{$date_final}')) or
                                                        (date(nv.fecha_emision) between date('{$date_initial}') and date('{$date_final}')))\n");
            } else {
                $date = str_replace("-010-", "-10-", $request->date);
                array_push($facturas, "f.fecha_emision like '{$date}%'\n");
                array_push($nota_venta, "nv.fecha_emision like '{$date}%'\n");
                array_push($vend, "((f.fecha_emision like '{$date}%') or
                                            (nv.fecha_emision like '{$date}%'))\n");
            }
        }

        // if($request->){

        // }
        $facturas = implode(" and ", $facturas);
        $nota_venta = implode(" and ", $nota_venta);
        $vend = implode(" and ", $vend);



        // $query="SELECT if(f.clave_acceso is null,nv.clave_acceso,f.clave_acceso) as clave_acceso,if(f.fecha_emision is null,nv.fecha_emision,f.fecha_emision) as fecha_emision,f.id_factura,nv.id_nota_venta,cl.nombre,max(fpago.descripcion) as forma_pago_cxc,sum(cxc.valor_cuota) as valor_total
        // FROM ctas_cobrar as cxc
        //             LEFT JOIN factura as f
        //             on f.id_factura = cxc.id_factura
        //             LEFT JOIN nota_venta as nv
        //             on nv.id_nota_venta = cxc.id_nota_venta
        //             left join forma_pagos fpago
        //             on fpago.id_forma_pagos = cxc.id_forma_pagos
        //             INNER JOIN cliente  as cl
        //             on cl.id_cliente=cxc.id_cliente
        //                 WHERE {$queries}  and cl.id_empresa={$request->company} and if(f.id_factura is null,nv.estado>0,f.estado>0)
        //                 GROUP BY f.id_factura,nv.id_nota_venta,cl.id_cliente
        //                 ORDER BY if(f.fecha_emision is null,nv.fecha_emision,f.fecha_emision)";
        // //dd($query);
        // $pagos_fact=DB::select("SELECT forma_pagos.descripcion,sum(ctas_cobrar.valor_cuota) as valor_total 
        // from ctas_cobrar 
        // LEFT JOIN factura as f
        // on f.id_factura=ctas_cobrar.id_factura
        // LEFT JOIN nota_venta as nv
        // on nv.id_nota_venta=ctas_cobrar.id_nota_venta
        // LEFT JOIN forma_pagos
        // on forma_pagos.id_forma_pagos=ctas_cobrar.id_forma_pagos
        // INNER JOIN cliente  as cl
        // on cl.id_cliente=ctas_cobrar.id_cliente
        // where {$queries} and cl.id_empresa={$request->company} and if(f.id_factura is null,nv.estado>0,f.estado>0)
        // GROUP BY forma_pagos.id_forma_pagos");

        if ($vendedor_todos == false) {
            $query = "SELECT sum(qty) as total_cantidad, id_producto,max(nombre) as nombre_producto,max(id_vendedor) as id_vendedor,max(nombre_vendedor) as nombre_vendedor
            from (
                select sum(df.cantidad) as qty, prod.id_producto,prod.nombre,max(vd.id_vendedor) as id_vendedor,max(vd.nombre_vendedor) as nombre_vendedor
                from detalle as df
                    INNER JOIN factura as f
                    on f.id_factura=df.id_factura
                    INNER JOIN vendedor as vd
                    on vd.id_vendedor=f.id_vendedor
                    INNER JOIN producto as prod
                    on prod.id_producto=df.id_producto
                where f.id_empresa={$request->company} and {$facturas} 
                group by prod.id_producto
            
                union all
            
                select sum(dn.cantidad) as qty,prod.id_producto,prod.nombre,max(vd.id_vendedor) as id_vendedor,max(vd.nombre_vendedor) as nombre_vendedor
                    from detalle_nota_venta as dn
                    INNER JOIN nota_venta as nv
                    on nv.id_nota_venta=dn.id_nota_venta
                    INNER JOIN vendedor as vd
                    on vd.id_vendedor=nv.id_vendedor
                    INNER JOIN producto as prod
                    on prod.id_producto=dn.id_producto
                    where  nv.id_empresa={$request->company} and {$nota_venta}
                    GROUP BY prod.id_producto
            ) t
            group by id_producto
            ORDER BY nombre_vendedor";
        } else {
            if (count($vendedores) > 0) {
                $array_vd = [];
                for ($a = 0; $a < count($vendedores); $a++) {
                    array_push($array_vd, "SELECT sum(qty) as total_cantidad, id_producto,max(nombre) as nombre_producto,max(id_vendedor) as id_vendedor,max(nombre_vendedor) as nombre_vendedor
                                            from (
                                                select sum(df.cantidad) as qty, prod.id_producto,prod.nombre,max(vd.id_vendedor) as id_vendedor,max(vd.nombre_vendedor) as nombre_vendedor
                                                from detalle as df
                                                    INNER JOIN factura as f
                                                    on f.id_factura=df.id_factura
                                                    INNER JOIN vendedor as vd
                                                    on vd.id_vendedor=f.id_vendedor
                                                    INNER JOIN producto as prod
                                                    on prod.id_producto=df.id_producto
                                                where f.id_empresa={$request->company} and {$facturas} and f.id_vendedor = {$vendedores[$a]->id_vendedor}
                                                group by prod.id_producto
                                            
                                                union all
                                            
                                                select sum(dn.cantidad) as qty,prod.id_producto,prod.nombre,max(vd.id_vendedor) as id_vendedor,max(vd.nombre_vendedor) as nombre_vendedor
                                                    from detalle_nota_venta as dn
                                                    INNER JOIN nota_venta as nv
                                                    on nv.id_nota_venta=dn.id_nota_venta
                                                    INNER JOIN vendedor as vd
                                                    on vd.id_vendedor=nv.id_vendedor
                                                    INNER JOIN producto as prod
                                                    on prod.id_producto=dn.id_producto
                                                    where  nv.id_empresa={$request->company} and {$nota_venta} and nv.id_vendedor = {$vendedores[$a]->id_vendedor}
                                                    GROUP BY prod.id_producto
                                            ) t
                                            group by id_producto
                                            ");
                }
                $array_vd = implode(" UNION ", $array_vd);
                $query = $array_vd . " ORDER BY nombre_vendedor";
            } else {
                $query = "SELECT sum(qty) as total_cantidad, id_producto,max(nombre) as nombre_producto,max(id_vendedor) as id_vendedor,max(nombre_vendedor) as nombre_vendedor
                from (
                    select sum(df.cantidad) as qty, prod.id_producto,prod.nombre,max(vd.id_vendedor) as id_vendedor,max(vd.nombre_vendedor) as nombre_vendedor
                    from detalle as df
                        INNER JOIN factura as f
                        on f.id_factura=df.id_factura
                        INNER JOIN vendedor as vd
                        on vd.id_vendedor=f.id_vendedor
                        INNER JOIN producto as prod
                        on prod.id_producto=df.id_producto
                    where f.id_empresa={$request->company} and {$facturas}
                    group by prod.id_producto
                
                    union all
                
                    select sum(dn.cantidad) as qty,prod.id_producto,prod.nombre,max(vd.id_vendedor) as id_vendedor,max(vd.nombre_vendedor) as nombre_vendedor
                        from detalle_nota_venta as dn
                        INNER JOIN nota_venta as nv
                        on nv.id_nota_venta=dn.id_nota_venta
                        INNER JOIN vendedor as vd
                        on vd.id_vendedor=nv.id_vendedor
                        INNER JOIN producto as prod
                        on prod.id_producto=dn.id_producto
                        where  nv.id_empresa=0 and {$nota_venta}
                        GROUP BY prod.id_producto
                ) t
                group by id_producto
                ORDER BY nombre_vendedor";
            }
        }

        $reporte = DB::select($query);
        $query_vd = "SELECT vd.id_vendedor,vd.nombre_vendedor 
        from vendedor as vd
        LEFT JOIN factura as f
        on f.id_vendedor=vd.id_vendedor
        LEFT JOIN detalle as df
        on f.id_factura=df.id_factura
        LEFT JOIN nota_venta as nv
        on nv.id_vendedor=vd.id_vendedor
        LEFT JOIN detalle_nota_venta as dn
        on nv.id_nota_venta=dn.id_nota_venta
        where vd.id_empresa={$request->company} and {$vend}
        GROUP BY vd.id_vendedor
        Order by vd.nombre_vendedor";
        $vendedores = DB::select($query_vd);
        // $valores_ice=DB::select("SELECT
        //     if(max(d.valor_ice)>0,ROUND(sum(d.valor_ice*d.cantidad),2),ROUND(sum(p.total_ice*d.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
        // FROM detalle d
        // INNER JOIN producto p ON p.id_producto=d.id_producto
        // LEFT JOIN ice ON ice.id_ice = p.ice
        // INNER JOIN factura f ON f.id_factura=d.id_factura
        // WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
        // GROUP BY f.id_factura");
        $valores_ice = [];
        //     $valores_ice=DB::select("SELECT
        //         if((df.valor_ice)>0,ROUND((df.valor_ice),2),ROUND((p.total_ice*df.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
        //     FROM detalle df
        //     INNER JOIN producto p ON p.id_producto=df.id_producto
        //     LEFT JOIN ice ON ice.id_ice = p.ice
        //     INNER JOIN factura f ON f.id_factura=df.id_factura
        //     WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
        // ");
        // dd("SELECT
        //             if((df.valor_ice)>0,ROUND((df.valor_ice*df.cantidad),2),ROUND((p.total_ice*df.cantidad),2)) as total_ice,f.clave_acceso,f.id_factura
        //         FROM detalle df
        //         INNER JOIN producto p ON p.id_producto=df.id_producto
        //         LEFT JOIN ice ON ice.id_ice = p.ice
        //         INNER JOIN factura f ON f.id_factura=df.id_factura
        //         WHERE {$queries} and f.id_empresa={$request->company} and f.modo = {$modo}
        //     ");
        //dd($reporte);
        $empresa = DB::select("SELECT * FROM empresa where id_empresa={$request->company}");
        $Reportes = new generarReportes();
        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            if (property_exists($request->date, 'initialDate')) {
                $strPDF = $Reportes->check_list_reporte($reporte, $date_initial, $date_final, $vendedores, $empresa);
            } else {
                $strPDF = $Reportes->check_list_reporte($reporte, $request->date, $request->date, $vendedores, $empresa);
            }
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
}
