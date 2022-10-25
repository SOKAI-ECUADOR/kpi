<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Notadebito;
use App\Models\Cliente;
use App\Models\Detalle_nota_debito;
use App\Models\Factura_pagos;
use App\Models\Nota_debito_pagos;
use App\Models\Cuentaporcobrar;
use App\Models\Producto;
use App\Models\Factura;
use App\Models\Detalle;
use Carbon\Carbon;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

class NotadebitoController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;
        if ($buscar == '') {
                $recupera = DB::select("SELECT `nota_debito`.*, nota_debito.estado as estadof, `nota_debito`.`fmodifica` as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `nota_debito`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento` from `nota_debito` inner join `empresa` on `empresa`.`id_empresa` = ".$request->datos["id_empresa"]." inner join `cliente` on `cliente`.`id_cliente` = `nota_debito`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = ".$request->datos["id_establecimiento"]." inner join `punto_emision` on `punto_emision`.`id_punto_emision` = ".$request->datos["id_punto_emision"]." inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where `nota_debito`.`id_empresa` = ".$request->datos["id_empresa"]." and `nota_debito`.`modo` = 1 order by nota_debito.fecha_emision DESC");
        } else {
            $recupera = DB::select("SELECT `nota_debito`.*,nota_debito.estado as estadof, `nota_debito`.`fmodifica` as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `nota_debito`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento` from `nota_debito` inner join `empresa` on `empresa`.`id_empresa` = ".$request->datos["id_empresa"]." inner join `cliente` on `cliente`.`id_cliente` = `nota_debito`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = ".$request->datos["id_establecimiento"]." inner join `punto_emision` on `punto_emision`.`id_punto_emision` = ".$request->datos["id_punto_emision"]." inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR nota_debito.respuesta like '%$buscar%' OR nota_debito.clave_acceso like '%$buscar%') AND `nota_debito`.`id_empresa` = ".$request->datos["id_empresa"]." and `nota_debito`.`modo` = 1 order by nota_debito.fecha_emision DESC");
        }
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($recupera); $i++) {
                if ($recupera[$i]->created_by == session()->get('usuariosesion')['id'] || $recupera[$i]->updated_by == session()->get('usuariosesion')['id'] || $recupera[$i]->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $recupera[$i]);
                }
            }
            $recupera = $dat;
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function eliminar($id){
        $del = Notadebito::findOrFail($id);
        $del->estado = 0;
        $del->save();
        
        DB::delete("DELETE FROM ctas_cobrar WHERE id_nota_debito = $id");
    }
    public function clave($id){
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , if(pe.secuencial_nota_debito<=1 || pe.secuencial_nota_debito is NULL,1,pe.secuencial_nota_debito) as numeral FROM user u INNER JOIN empresa e on e.id_empresa=u.id_empresa INNER JOIN establecimiento es on es.id_empresa=e.id_empresa INNER JOIN punto_emision pe on pe.id_empresa=e.id_empresa WHERE u.id = " . $id);
        $valor = $respuesta[0]->numeral;
        return [
            'secuencial' => $valor,
            'recupera' => $respuesta
        ];
    }
    public function guardar_factura(Request $request){
        $now = Carbon::now();
        $notadebito = new Notadebito();
        $notadebito->modo = 1;
        $notadebito->ambiente = $request->factura["ambiente"];
        $notadebito->tipo_emision = 1;
        $notadebito->fecha_emision = $request->factura["fecha"];
        $notadebito->forma_pago = $request->factura["forma_pago"];
        $notadebito->autorizacionfactura= $request->factura["documento"];
        $notadebito->clave_acceso= $request->factura["clave_acceso"];
        $notadebito->fechaAutorizacion= $now;
        $notadebito->observacion = $request->factura["observacion"];
        $notadebito->subtotal_sin_impuesto = $request->subtotal;
        $notadebito->subtotal_12 = $request->subtotal12;
        $notadebito->subtotal_0 = $request->subtotal0;
        $notadebito->subtotal_no_obj_iva = $request->no_impuesto;
        $notadebito->descuento = $request->descuento;
        $notadebito->valor_ice = '0.00';
        $notadebito->valor_irbpnr = '0.00';
        $notadebito->iva_12 = $request->valor12;
        $notadebito->estatus = 1;
        $notadebito->estado = 1;
        $notadebito->valor_total = $request->total;
        $notadebito->id_cliente = $request->cliente;
        $notadebito->id_user = $request->usuario["id"];
        $notadebito->id_punto_emision = $request->usuario["id_punto_emision"];
        $notadebito->id_empresa = $request->usuario["id_empresa"];
        $notadebito->id_establecimiento = $request->usuario["id_establecimiento"];
        $notadebito->id_proyecto = $request->factura["proyectos"];
        $notadebito->totalpropinaf = '0.00';
        $notadebito->pp_descuento = $request->descuento;
        $notadebito->created_by = session()->get('usuariosesion')['id'];
        $notadebito->updated_by = session()->get('usuariosesion')['id'];
        $notadebito->save();

        $id = $notadebito->id_nota_debito;

        $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
        $sf = $s_facturasubstr + 1;
        $idp = $request->usuario["id_punto_emision"];
        DB::update("UPDATE punto_emision SET secuencial_nota_debito = '$sf' WHERE id_punto_emision = $idp");

        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle_nota_debito(); 
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]);
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->id_nota_debito = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            $detalle->save();

            /*$prod = Producto::findOrFail($request->productos[$a]["id_producto"]);
            $prod->
            $prod->save();*/
        }

        $doc = $request->factura["documento"];
        $recuperaid = DB::select("SELECT * FROM factura WHERE clave_acceso LIKE '%$doc%'");
        $idfactura = $recuperaid[0]->id_factura;

        if ($request->pagos["estado"]) {
            for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                    $pag = new Nota_debito_pagos(); 
                    $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                    $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                    $pag->plazo = 1;
                    $pag->unidad_tiempo = 'Días';
                    $pag->estado = 1;
                    $pag->fecha = $now;
                    $pag->id_nota_debito = $id;
                    $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                    $pag->numero_transaccion = isset($request->pagos["datos"][$a]["nro_trans"]);
                    $pag->tiempos_pagos = 1;
                    $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                    $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                    $pag->save();

                    $cxc = new Cuentaporcobrar();
                    $cxc->num_cuota = 1;
                    $cxc->fecha_pago = $now;
                    $cxc->periodo_pagos = "Dia";
                    $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                    $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                    $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                    $cxc->numero_tarjeta = isset($request->pagos["datos"][$a]["nro_trans"]);
                    $cxc->valor_pagado = 0;
                    $cxc->estado = 1;
                    $cxc->tipo = 2;
                    $cxc->id_factura = $idfactura;
                    $cxc->id_cliente = $request->cliente;
                    $cxc->id_nota_debito = $id;
                    $cxc->created_by = session()->get('usuariosesion')['id'];
                    $cxc->updated_by = session()->get('usuariosesion')['id'];
                    $cxc->save();
                }
            }
        }
        $fecharec = "";
        if ($request->creditos["estado"]) {
            $pag = new Nota_debito_pagos();
            $pag->id_forma_pagos = null;
            $pag->total = $request->creditos["monto"];
            $pag->plazo = $request->creditos["plazos"];
            $pag->unidad_tiempo = $request->creditos["periodo"];
            $pag->tiempos_pagos = $request->creditos["tiempo"];
            $pag->estado = 2;
            $pag->fecha = $now;
            $pag->id_nota_debito = $id;
            $pag->id_banco = null;
            $pag->id_plan_cuentas = null;
            $pag->save();

            $fd = "";
            for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                $cxc = new Cuentaporcobrar();
                $cxc->num_cuota = $a + 1;
                if ($a < 1) {
                    if ($request->creditos["periodo"] == "Años") {
                        $fecharec = $now->addYears($request->creditos["tiempo"]);
                        $fd = $now->addYears($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Meses") {
                        $fecharec = $now->addMonths($request->creditos["tiempo"]);
                        $fd = $now->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Semanas") {
                        $fecharec = $now->addWeeks($request->creditos["tiempo"]);
                        $fd = $now->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                    } else {
                        $fecharec = $now->addDays($request->creditos["tiempo"]);
                        $fd = $now->addDays($request->creditos["tiempo"])->format('Y-m-d');
                    }
                } else {
                    if ($request->creditos["periodo"] == "Años") {
                        $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Meses") {
                        $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Semanas") {
                        $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                    } else {
                        $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
                    }
                }
                $cxc->fecha_pago = $fd;
                $cxc->periodo_pagos = $request->creditos["periodo"];
                $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                $cxc->valor_pagado = 0;
                $cxc->estado = 1;
                $cxc->tipo = 1;
                $cxc->id_factura = $idfactura;
                $cxc->id_cliente = $request->cliente;
                $cxc->id_nota_debito = $id;
                $cxc->created_by = session()->get('usuariosesion')['id'];
                $cxc->updated_by = session()->get('usuariosesion')['id'];
                $cxc->save();
            }
        }
        $fact = Notadebito::select('nota_debito.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'nota_debito.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
        ->join('empresa', 'empresa.id_empresa', '=', 'nota_debito.id_empresa')
        ->join('cliente', 'cliente.id_cliente', '=', 'nota_debito.id_cliente')
        ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
        ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
        ->leftjoin('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
        ->where("nota_debito.id_nota_debito", "=", $id)
        ->orderByRaw('nota_debito.id_nota_debito DESC')->get();
        return  $fact[0];
    }
    public function editar_factura(Request $request){
        $now = Carbon::now();
        $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
        $sf = $s_facturasubstr + 1;
        $idp = $request->usuario["id_punto_emision"];
        DB::update("UPDATE punto_emision SET secuencial_factura = '$sf' WHERE id_punto_emision = $idp");

        $id = $request->id;

        $notadebito = Notadebito::findOrFail($id);
        $notadebito->modo = 1;
        $notadebito->ambiente = $request->factura["ambiente"];
        $notadebito->tipo_emision = 1;
        $notadebito->fecha_emision = $request->factura["fecha"];
        $notadebito->forma_pago = $request->factura["forma_pago"];
        $notadebito->autorizacionfactura= $request->factura["documento"];
        $notadebito->clave_acceso= $request->factura["clave_acceso"];
        $notadebito->fechaAutorizacion= $request->factura["fecha_doc"];
        $notadebito->observacion = $request->factura["observacion"];
        $notadebito->subtotal_sin_impuesto = $request->subtotal;
        $notadebito->subtotal_12 = $request->subtotal12;
        $notadebito->subtotal_0 = $request->subtotal0;
        $notadebito->subtotal_no_obj_iva = $request->no_impuesto;
        $notadebito->descuento = $request->descuento;
        $notadebito->valor_ice = '0.00';
        $notadebito->valor_irbpnr = '0.00';
        $notadebito->iva_12 = $request->valor12;
        $notadebito->estatus = 1;
        $notadebito->estado = 1;
        $notadebito->valor_total = $request->total;
        $notadebito->id_cliente = $request->cliente;
        $notadebito->id_user = $request->usuario["id"];
        $notadebito->id_punto_emision = $request->usuario["id_punto_emision"];
        $notadebito->id_empresa = $request->usuario["id_empresa"];
        $notadebito->id_establecimiento = $request->usuario["id_establecimiento"];
        $notadebito->id_proyecto = $request->factura["proyectos"];
        $notadebito->totalpropinaf = '0.00';
        $notadebito->pp_descuento = $request->descuento;
        $notadebito->updated_by = session()->get('usuariosesion')['id'];
        $notadebito->save();

        DB::delete("DELETE FROM detalle_nota_debito WHERE id_nota_debito = $id");

        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle_nota_debito(); 
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_nota_debito = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            $detalle->save();
        }

        DB::delete("DELETE FROM nota_debito_pagos WHERE id_nota_debito = $id");
        DB::delete("DELETE FROM ctas_cobrar WHERE id_nota_debito = $id");

        $doc = $request->factura["documento"];
        $recuperaid = DB::select("SELECT * FROM factura WHERE clave_acceso LIKE '%$doc%'");
        $idfactura = $recuperaid[0]->id_factura;

        if ($request->pagos["estado"]) {
            for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                    $pag = new Nota_debito_pagos(); 
                    $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                    $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                    $pag->plazo = 1;
                    $pag->unidad_tiempo = 'Días';
                    $pag->estado = 1;
                    $pag->fecha = $now;
                    $pag->id_nota_debito = $id;
                    $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                    $pag->numero_transaccion = isset($request->pagos["datos"][$a]["nro_trans"]);
                    $pag->tiempos_pagos = 1;
                    $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                    $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                    $pag->save();

                    $cxc = new Cuentaporcobrar();
                    $cxc->num_cuota = 1;
                    $cxc->fecha_pago = $now;
                    $cxc->periodo_pagos = "Dia";
                    $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                    $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                    $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                    $cxc->numero_tarjeta = isset($request->pagos["datos"][$a]["nro_trans"]);
                    $cxc->valor_pagado = 0;
                    $cxc->estado = 1;
                    $cxc->tipo = 2;
                    $cxc->id_factura = $idfactura;
                    $cxc->id_cliente = $request->cliente;
                    $cxc->id_nota_debito = $id;
                    $cxc->created_by = session()->get('usuariosesion')['id'];
                    $cxc->updated_by = session()->get('usuariosesion')['id'];
                    $cxc->save();
                }
            }
        }
        $fecharec = "";
        if ($request->creditos["estado"]) {
            $pag = new Nota_debito_pagos();
            $pag->id_forma_pagos = null;
            $pag->total = $request->creditos["monto"];
            $pag->plazo = $request->creditos["plazos"];
            $pag->unidad_tiempo = $request->creditos["periodo"];
            $pag->tiempos_pagos = $request->creditos["tiempo"];
            $pag->estado = 2;
            $pag->fecha = $now;
            $pag->id_nota_debito = $id;
            $pag->id_banco = null;
            $pag->id_plan_cuentas = null;
            $pag->save();

            $fd = "";
            for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                $cxc = new Cuentaporcobrar();
                $cxc->num_cuota = $a + 1;
                if ($a < 1) {
                    if ($request->creditos["periodo"] == "Años") {
                        $fecharec = $now->addYears($request->creditos["tiempo"]);
                        $fd = $now->addYears($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Meses") {
                        $fecharec = $now->addMonths($request->creditos["tiempo"]);
                        $fd = $now->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Semanas") {
                        $fecharec = $now->addWeeks($request->creditos["tiempo"]);
                        $fd = $now->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                    } else {
                        $fecharec = $now->addDays($request->creditos["tiempo"]);
                        $fd = $now->addDays($request->creditos["tiempo"])->format('Y-m-d');
                    }
                } else {
                    if ($request->creditos["periodo"] == "Años") {
                        $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Meses") {
                        $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Semanas") {
                        $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                    } else {
                        $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
                    }
                }
                $cxc->fecha_pago = $fd;
                $cxc->periodo_pagos = $request->creditos["periodo"];
                $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                $cxc->valor_pagado = 0;
                $cxc->estado = 1;
                $cxc->tipo = 1;
                $cxc->id_factura = $idfactura;
                $cxc->id_cliente = $request->cliente;
                $cxc->id_nota_debito = $id;
                $cxc->created_by = session()->get('usuariosesion')['id'];
                $cxc->updated_by = session()->get('usuariosesion')['id'];
                $cxc->save();
            }
        }

        $fact = Notadebito::select('nota_debito.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'nota_debito.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
        ->join('empresa', 'empresa.id_empresa', '=', 'nota_debito.id_empresa')
        ->join('cliente', 'cliente.id_cliente', '=', 'nota_debito.id_cliente')
        ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
        ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
        ->leftjoin('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
        ->where("nota_debito.id_nota_debito", "=", $request->id)
        ->orderByRaw('nota_debito.id_nota_debito DESC')->get();
        return  $fact[0];
    }
    public function recuperar($id){
        $factura = DB::select("SELECT * FROM nota_debito WHERE id_nota_debito = " . $id);
        $productos = DB::select("SELECT dnc.*, p.cod_principal, p.cod_alterno FROM detalle_nota_debito dnc INNER JOIN producto p ON p.id_producto=dnc.id_producto WHERE dnc.id_nota_debito = " . $id);
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente = " . $factura[0]->id_cliente);
        $pagos = DB::select("SELECT fp.id_nota_debito_pagos, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS numero_transaccion, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta FROM nota_debito_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas WHERE fp.estado = 1 AND fp.id_nota_debito = " . $id);
        $creditos = DB::select("SELECT id_nota_debito_pagos, estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto FROM nota_debito_pagos WHERE estado = 2 AND id_nota_debito = " . $id);
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
        ];
    }
    public function ver($id){
        $cuerpo = DB::select("SELECT nc.*, c.nombre, c.identificacion, c.email, c.telefono, c.direccion, c.tipo_identificacion FROM nota_debito nc INNER JOIN cliente c ON c.id_cliente = nc.id_cliente WHERE id_nota_debito = " . $id);
        $productos = DB::select("SELECT dnc.*, p.cod_principal, p.cod_alterno FROM detalle_nota_debito dnc INNER JOIN producto p ON p.id_producto=dnc.id_producto WHERE id_nota_debito = " . $id);
        $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = " . $cuerpo[0]->id_empresa);
        return [
            'cuerpo' => $cuerpo[0],
            'productos' => $productos,
            'empresa' => $empresa[0]
        ];
    }
    public function verAsiento(Request $request,$id){
        $nota_credito=DB::select("SELECT nota_debito.*,cliente.nombre,cliente.tipo_identificacion,cliente.identificacion from cliente,nota_debito where  cliente.id_cliente=nota_debito.id_cliente and nota_debito.id_nota_debito=".$id);
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'NDF-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
        $cod_asiento="";
        if($codigo){
            $lenght=strlen($codigo[0]->codigo);
            $valor=$codigo[0]->codigo+1;
            $cod_asiento=$codigo[0]->codigo+1;
        }else{
            $cod_asiento="1";
        }
        $cod_asiento_ant="";
        if($codigo){
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=12 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }
            
            
        }
        $productos=DB::select("SELECT detalle_nota_debito.total,if(detalle_nota_debito.id_iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
        if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva,null) as id_plan_cuentas_iva_12,
        if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva_0,null) as id_plan_cuentas_iva_0,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
        concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
        null as debe,detalle_nota_debito.total as haber FROM detalle_nota_debito
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_debito.id_proyecto
        INNER JOIN producto
        on producto.id_producto=detalle_nota_debito.id_producto
        LEFT JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        LEFT JOIN plan_cuentas
        ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
        where detalle_nota_debito.id_nota_debito={$id}");
        $iva_asiento=DB::select("SELECT detalle_nota_debito.total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as debe,round(if(detalle_nota_debito.id_iva=2,(detalle_nota_debito.total)*(12/100),0),2) as haber
        from nota_debito,retencion,plan_cuentas,detalle_nota_debito
                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle_nota_debito.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle_nota_debito.id_producto
                       where nota_debito.id_nota_debito={$id} and nota_debito.id_nota_debito=detalle_nota_debito.id_nota_debito and retencion.descrip_retencion='IVA. en Ventas' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                       ORDER BY detalle_nota_debito.id_detalle_nota_debito asc");
        $query = "SELECT round(sum(nota_debito_pagos.total)/count(nota_debito_pagos.id_nota_debito),2) as total,round(sum(detalle_nota_debito.total)/max(nota_debito.subtotal_sin_impuesto),2) as porcentaje,sum(detalle_nota_debito.total) as valor_producto,round((sum(detalle_nota_debito.total)/max(nota_debito.subtotal_sin_impuesto)*(sum(nota_debito_pagos.total)/count(nota_debito_pagos.id_nota_debito))),2) as debe,null as haber,detalle_nota_debito.id_proyecto,proyecto.descripcion,plan_cuentas.id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        if(cliente.id_plan_cuentas is null, 'no','si') as exist_plc_cl,
        (select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas)) as id_plan_cuentas_cl,
        (select CONCAT(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas)) as nombre_cuenta_cl
        from nota_debito_pagos
        INNER JOIN nota_debito
        ON nota_debito.id_nota_debito=nota_debito_pagos.id_nota_debito
        INNER JOIN cliente
        ON cliente.id_cliente=nota_debito.id_cliente
        Left JOIN grupo_cliente
        ON grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
        Left JOIN plan_cuentas
        ON plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
        INNER JOIN detalle_nota_debito
        ON detalle_nota_debito.id_nota_debito=nota_debito.id_nota_debito
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_debito.id_proyecto
        where nota_debito_pagos.id_nota_debito={$id} and nota_debito_pagos.estado=2
        GROUP BY detalle_nota_debito.id_proyecto
        ORDER BY detalle_nota_debito.id_proyecto asc";
        //dd($query);
        $creditos = DB::select("SELECT * from nota_debito_pagos where id_nota_debito={$id} and estado=2");
        if (count($creditos) > 0) {
            $cliente = DB::select($query);
        } else {
            $cliente = [];
        }
        $forma_pagos_sin_plc = DB::select("SELECT nota_debito_pagos.total,round(detalle_nota_debito.total/nota_debito.subtotal_sin_impuesto,2) as porcentaje,round(detalle_nota_debito.total/nota_debito.subtotal_sin_impuesto*	nota_debito_pagos.total,2) as debe,null as haber,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_nota_debito.id_proyecto,proyecto.descripcion,nota_debito_pagos.id_forma_pagos,nota_debito_pagos.fecha_pago,nota_debito_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago 
        from nota_debito_pagos
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=nota_debito_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
        INNER JOIN nota_debito
        on nota_debito.id_nota_debito=nota_debito_pagos.id_nota_debito
        INNER JOIN detalle_nota_debito
        on detalle_nota_debito.id_nota_debito=nota_debito.id_nota_debito
        left JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_debito.id_proyecto
        where nota_debito_pagos.estado=1 and nota_debito_pagos.id_nota_debito={$id} and nota_debito_pagos.id_plan_cuentas is null
        ORDER BY detalle_nota_debito.id_proyecto asc");
        $forma_pagos_con_plc = DB::select("SELECT nota_debito_pagos.total,round(detalle_nota_debito.total/nota_debito.subtotal_sin_impuesto,2) as porcentaje,round(detalle_nota_debito.total/nota_debito.subtotal_sin_impuesto*	nota_debito_pagos.total,2) as debe,null as haber,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_nota_debito.id_proyecto,proyecto.descripcion,nota_debito_pagos.id_forma_pagos,nota_debito_pagos.fecha_pago,nota_debito_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago 
        from nota_debito_pagos
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=nota_debito_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=nota_debito_pagos.id_plan_cuentas
        INNER JOIN nota_debito
        on nota_debito.id_nota_debito=nota_debito_pagos.id_nota_debito
        INNER JOIN detalle_nota_debito
        on detalle_nota_debito.id_nota_debito=nota_debito.id_nota_debito
        left JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_debito.id_proyecto
        where nota_debito_pagos.estado=1 and nota_debito_pagos.id_nota_debito={$id}  and nota_debito_pagos.id_plan_cuentas is not null
        ORDER BY detalle_nota_debito.id_proyecto asc");
        $proyecto=DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $fecha_emision=substr($nota_credito[0]->fecha_emision,0,-3);
        $anio_emision=substr($nota_credito[0]->fecha_emision,0,4);
        $fecha_cierre=DB::select("SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Cierre Mes' and periodo='{$fecha_emision}'
                                UNION
                                SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Estado Contable' and periodo='{$anio_emision}'");
        $asiento="";
        if(count($fecha_cierre)>0){
            $asiento="no";
        }else{
            $asiento="si";
        }
        return [
            'codigo'=>$cod_asiento,
            'asiento_permitido'=>$asiento,
            'codigo_anterior'=>$cod_asiento_ant,
            'nota_credito_fact'=>$nota_credito[0],
            'producto_asientos'=>$productos,
            'doce_iva_asiento' => $iva_asiento,
            'proyecto'=>$proyecto[0]->id_proyecto,
            'creditos'=>$cliente,
            'pagos_asientos_sin_plc' => $forma_pagos_sin_plc,
            'pagos_asientos_con_plc' => $forma_pagos_con_plc
        ];
    }
    public function agregarAsiento(Request $request)
    {
        Notadebito::where('id_nota_debito', $request->cod_rol)->update(['contabilidad' => '1']);
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
        $asientos->id_asientos_comprobante = 12;

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
                    if ($debe["fecha_pago"] !== null) {
                        if($debe["numero_transaccion"]!==0){
                            $asiento->no_documento = $debe["numero_transaccion"];
                        }
                        
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
                    if ($debe["fecha_pago"] !== null) {
                        if($debe["numero_transaccion"]!==0){
                            $asiento->no_documento = $debe["numero_transaccion"];
                        }
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
                    if ($debe["fecha_pago"] !== null) {
                        if($debe["numero_transaccion"]!==0){
                            $asiento->no_documento = $debe["numero_transaccion"];
                        }
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
                if($debe["exist_plc_cl"]=='si'){
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas_cl"];
                }else{
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                }
                
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
    }

    public function listar_cliente(Request $request){
        $bs = $request->buscar;
        $empresa = $request->empresa;
        if ($bs == '') {
            $res = DB::select("SELECT * FROM cliente WHERE id_empresa = $empresa ORDER BY id_cliente  DESC");
        } else {
            $res = DB::select("SELECT * FROM cliente WHERE (codigo LIKE '%$bs%' OR nombre LIKE '%$bs%' OR identificacion LIKE '%$bs%' OR email LIKE '%$bs%' OR telefono LIKE '%$bs%') AND id_empresa = $empresa ORDER BY id_cliente DESC");
        }
        return $res;
    }
    public function listar_productos(Request $request){
        $bs = $request->buscar;
        $empresa = $request->empresa;
        if ($bs == '') {
            $res = DB::select("SELECT p.*, ma.nombre AS nombre_marca, mo.nombre AS nombre_modelo FROM producto p LEFT JOIN marca ma ON p.id_marca = ma.id_marca LEFT JOIN modelo mo ON p.id_modelo = mo.id_modelo WHERE p.id_empresa = $empresa ORDER BY p.id_producto DESC");
        } else {
            $res = DB::select("SELECT p.*, ma.nombre AS nombre_marca, mo.nombre AS nombre_modelo FROM producto p LEFT JOIN marca ma ON p.id_marca = ma.id_marca LEFT JOIN modelo mo ON p.id_modelo = mo.id_modelo WHERE (p.cod_principal LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%') AND p.id_empresa = $empresa ORDER BY p.id_producto DESC");
        }
        return $res;
    }
    public function listar_creacion_cliente($id){
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
    public function listar_canton($id){
        $res = DB::select("SELECT * FROM ciudad WHERE id_provincia = " . $id);
        return $res;
    }
    public function listar_parroquia($id){
        $res = DB::select("SELECT * FROM parroquia WHERE id_ciudad = " . $id);
        return $res;
    }
    public function listar_cuenta_contable(Request $request){
        $id = $request->empresa;
        $bs = $request->buscar;
        $res = DB::select("SELECT * FROM plan_cuentas WHERE (codcta LIKE '%$bs%' OR nomcta LIKE '%$bs%') AND id_empresa = $id");
        return $res;
    }
    public function guardar_cliente(Request $request){
        if($request->cliente['id_cuenta_contable'] == null && $request->cliente['cuenta_contable'] != ''){
            $buscar = DB::select("SELECT * FROM plan_cuentas WHERE codcta = " . $request->cliente['cuenta_contable']);
            $cuenta = $buscar[0]; 
        }else{
            $cuenta = $request->cliente['id_cuenta_contable'];
        }
        $cliente = new Cliente();
        $cliente->nombre = $request->cliente['nombre'];
        $cliente->tipo_identificacion = $request->cliente['tipo_identificacion'];
        $cliente->identificacion= $request->cliente['identificacion'];
        $cliente->direccion= $request->cliente['direccion'];
        $cliente->email= $request->cliente['e_mail'];
        $cliente->telefono = $request->cliente['telefono'];
        $cliente->contacto = $request->cliente['contacto'];
        $cliente->estado= $request->cliente['estado'];
        $cliente->id_plan_cuentas= $cuenta;
        $cliente->comentario= $request->cliente['comentario'];
        $cliente->descuento= $request->cliente['descuento'];
        $cliente->num_pago = $request->cliente['numero_pagos'];
        $cliente->id_grupo_cliente= $request->cliente['grupo_cliente'];
        $cliente->id_tipo_cliente= $request->cliente['tipo_cliente'];
        $cliente->grupo_tributario = $request->cliente['grupo_tributario'];
        $cliente->id_cuidad = $request->cliente['canton'];
        $cliente->id_parroquia= $request->cliente['parroquia'];
        $cliente->id_provincia= $request->cliente['provincia'];
        $cliente->parte_relacionada= $request->cliente['parte_relacionada'];
        $cliente->id_vendedor= $request->cliente['vendedor'];
        $cliente->lista_precios = $request->cliente['lista_precios'];
        $cliente->limite_credito= $request->cliente['limite_credito'];
        $cliente->id_forma_pagos= $request->cliente['forma_pago'];
        $cliente->id_empresa =$request->empresa;
        $cliente->save();
    }
    public function verificarcliente($id){
        $cliente = DB::select("SELECT * FROM cliente WHERE id_empresa = ".$id." ORDER BY id_cliente DESC limit 1");
        if($cliente){
            $dato = $cliente[0]->codigo;
            $var=0;
            for($i=strlen($dato); $i>0; $i--){
                if($dato[$i-1] =='-'){
                    $var = $i;
                    break;
                }
            }
            $numero = substr($dato,$var)+1;
            $cod = substr($dato,0,$var);
            return $cod.$numero;
        }else{
            return "vacio";
        }

    }
    public function buscarfactura(Request $request){
        $factura = Factura::select("*")->where("clave_acceso", "like", '%'.$request->factura.'%')->get();
        if(count($factura)>0){
            $detalle = Detalle::select("detalle.*", "producto.cod_principal")->join("producto", "producto.id_producto", "=", "detalle.id_producto")->where("id_factura", "=", $factura[0]->id_factura)->get();
            $cliente = Cliente::select("*")->where("id_cliente", "=", $factura[0]->id_cliente)->get();
            return [
                'factura' => $factura[0],
                'detalle' => $detalle,
                'cliente' => $cliente[0],
            ];
        }else{
            return 'error';
        }
    }
}
