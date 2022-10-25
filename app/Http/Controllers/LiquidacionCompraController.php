<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiquidacionCompra;
use App\Models\Liquidaction_compra_pagos;
use App\Models\Detalle_liquidacion_compra;
use App\Models\Retencion_Liquidacion_Compra;
use App\Models\Importacion;
use App\Models\Moneda;
use App\Models\ProductoFactura;
use App\Models\Proveedor;
use App\Models\Provincia;
use App\Models\Ptoemision;
use App\Models\Tiposustento;
use App\Models\ProductoBodega;
use App\Models\Cuentaporpagar;
use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;
use Carbon\Carbon;
use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;
use Illuminate\Support\Facades\DB;

use DOMDocument;

include 'class/generarReportes.php';
include_once getenv("FILE_CONFIG_PHP");

use generarReportes;

class LiquidacionCompraController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $idempresa = $request->datos["id_empresa"];
        $idestablecimiento = $request->datos["id_establecimiento"];
        $puntoemision = $request->datos["id_punto_emision"];
        if ($buscar == '') {
            //$recupera = DB::select("SELECT liquidacion_compra.*, liquidacion_compra.estado AS estadof, liquidacion_compra.fmodifica AS fecha_autorizacion, empresa.*, cliente.*, moneda.nomb_moneda AS moneda, establecimiento.codigo AS codigoes, punto_emision.codigo AS codigope, establecimiento.direccion AS direccion_establecimiento FROM liquidacion_compra INNER JOIN empresa ON empresa.id_empresa = $idempresa INNER JOIN cliente on cliente.id_cliente = liquidacion_compra.id_cliente INNER JOIN establecimiento ON establecimiento.id_establecimiento = $idestablecimiento INNER JOIN punto_emision on punto_emision.id_punto_emision = $puntoemision INNER JOIN moneda on moneda.id_moneda = empresa.id_moneda WHERE liquidacion_compra.id_empresa = $idempresa ORDER BY SUBSTRING(liquidacion_compra.clave_acceso, 31, 9) DESC");
            $recupera = DB::select("SELECT liquidacion_compra.*, liquidacion_compra.estado AS estadof, liquidacion_compra.fecha_envio AS fecha_autorizacion, empresa.*, moneda.nomb_moneda AS moneda, establecimiento.codigo AS codigoes, punto_emision.codigo AS codigope, establecimiento.direccion AS direccion_establecimiento,proveedor.*,(select max(respuesta) from retencion_liquidacion_compra where id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra) as respuesta_retencion,(select max(id_retencion_liquidacion_compra) from retencion_liquidacion_compra where id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra) as id_retliquidcompra
                                    FROM liquidacion_compra 
                                    INNER JOIN empresa 
                                    ON empresa.id_empresa = $idempresa 
                                    INNER JOIN establecimiento 
                                    ON establecimiento.id_establecimiento = $idestablecimiento 
                                    INNER JOIN punto_emision 
                                    on punto_emision.id_punto_emision = $puntoemision 
                                    INNER JOIN moneda 
                                    on moneda.id_moneda = empresa.id_moneda
                                    INNER JOIN proveedor 
                                    on proveedor.id_proveedor = liquidacion_compra.id_proveedor
                                    WHERE liquidacion_compra.id_empresa = $idempresa 
                                    ORDER BY SUBSTRING(liquidacion_compra.clave_acceso, 31, 9) DESC");
                                    // dd("SELECT liquidacion_compra.*, liquidacion_compra.estado AS estadof, liquidacion_compra.fecha_envio AS fecha_autorizacion, empresa.*, moneda.nomb_moneda AS moneda, establecimiento.codigo AS codigoes, punto_emision.codigo AS codigope, establecimiento.direccion AS direccion_establecimiento,proveedor.*,(select respuesta from retencion_liquidacion_compra where id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra) as respuesta_retencion,(select sum(id_retencion_liquidacion_compra) from retencion_liquidacion_compra where id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra) as id_retliquidcompra
                                    // FROM liquidacion_compra 
                                    // INNER JOIN empresa 
                                    // ON empresa.id_empresa = $idempresa 
                                    // INNER JOIN establecimiento 
                                    // ON establecimiento.id_establecimiento = $idestablecimiento 
                                    // INNER JOIN punto_emision 
                                    // on punto_emision.id_punto_emision = $puntoemision 
                                    // INNER JOIN moneda 
                                    // on moneda.id_moneda = empresa.id_moneda
                                    // INNER JOIN proveedor 
                                    // on proveedor.id_proveedor = liquidacion_compra.id_proveedor
                                    // WHERE liquidacion_compra.id_empresa = $idempresa 
                                    // ORDER BY SUBSTRING(liquidacion_compra.clave_acceso, 31, 9) DESC");
        } else {
            $recupera = DB::select("SELECT liquidacion_compra.*, liquidacion_compra.estado AS estadof, liquidacion_compra.fecha_envio AS fecha_autorizacion, empresa.*, moneda.nomb_moneda AS moneda, establecimiento.codigo AS codigoes, punto_emision.codigo AS codigope, establecimiento.direccion AS direccion_establecimiento,proveedor.*,(select max(respuesta) from retencion_liquidacion_compra where id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra) as respuesta_retencion,(select max(id_retencion_liquidacion_compra) from retencion_liquidacion_compra where id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra) as id_retliquidcompra
                                    FROM liquidacion_compra 
                                    INNER JOIN empresa ON empresa.id_empresa = $idempresa 
                                    INNER JOIN cliente on cliente.id_cliente = liquidacion_compra.id_cliente 
                                    INNER JOIN establecimiento ON establecimiento.id_establecimiento = $idestablecimiento
                                    INNER JOIN punto_emision on punto_emision.id_punto_emision = $puntoemision 
                                    INNER JOIN moneda on moneda.id_moneda = empresa.id_moneda 
                                    WHERE (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR liquidacion_compra.respuesta like '%$buscar%' OR liquidacion_compra.clave_acceso like '%$buscar%') AND liquidacion_compra.id_empresa = $idempresa ORDER BY SUBSTRING(liquidacion_compra.clave_acceso, 31, 9) DESC");
        }
        return $recupera;
    }
    public function eliminar($id){
        $del = LiquidacionCompra::findOrFail($id);
        $del->estado = 0;
        $del->save();
    }
    public function clave($id){
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , if(pe.secuencial_liquidacion_compra<=1 || pe.secuencial_liquidacion_compra is NULL,1,pe.secuencial_liquidacion_compra) as numeral FROM user u INNER JOIN empresa e on e.id_empresa=u.id_empresa INNER JOIN establecimiento es on es.id_empresa=e.id_empresa INNER JOIN punto_emision pe on pe.id_empresa=e.id_empresa WHERE u.id = " . $id);
        $respuesta_retencion = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision , if(pe.secuencial_retencion<=1 || pe.secuencial_retencion is NULL,1,pe.secuencial_retencion) as numeral FROM user u INNER JOIN empresa e on e.id_empresa=u.id_empresa INNER JOIN establecimiento es on es.id_empresa=e.id_empresa INNER JOIN punto_emision pe on pe.id_empresa=e.id_empresa WHERE u.id = " . $id);
        $valor = $respuesta[0]->numeral;
        $valor_retencion = $respuesta_retencion[0]->numeral;

        return [
            'secuencial' => $valor,
            'recupera' => $respuesta,
            'sencuencial_retencion'=>$valor_retencion,
            'recupera_retencion' => $respuesta_retencion,
        ];
    }
    public function guardar_liquidacion_clave(Request $request)
    {
        $valor = "";
        $valor1 = "";
        $clave_acceso = trim($request->factura["clave_acceso"]);
        $clave_acceso_retencion = trim($request->retencion);

        //recupera el numero de clave de acceso
        $ca = substr($clave_acceso, 24, 15);
        $ca_rt = substr($clave_acceso_retencion, 24, 15);
        //verifica si el numero en la clave de acceso existe, al ser asi manda una variable como repetido
        $res = DB::select("SELECT * FROM liquidacion_compra WHERE clave_acceso like '%{$ca}%' and id_empresa={$request->id_empresa}");
        $lc_clave="";
        if (count($res) >= 1) {
            $lc_clave= "repetido";
        }
        $res_2 = DB::select("SELECT observacion FROM liquidacion_compra 
                            INNER JOIN retencion_liquidacion_compra
                            on retencion_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                            WHERE observacion like '%{$ca_rt}%' and id_empresa={$request->id_empresa}
                                UNION
                            SELECT observacion FROM factura_compra 
                            INNER JOIN retencion_factura_comp
                            on retencion_factura_comp.id_factura=factura_compra.id_factcompra
                            WHERE observacion like '%{$ca_rt}%' and id_empresa={$request->id_empresa}");
        $rt_clave="";
        if (count($res_2) >= 1) {
            $rt_clave= "repetido";
        }
        return[
            'clave_lc'=>$lc_clave,
            'clave_retencion'=>$rt_clave
        ];
    }
    public function guardar_liquidacion(Request $request){
        ini_set('max_execution_time', 900);
        $liquidacion_compra=DB::select("SELECT max(id_liquidacion_compra) as id_liquidacion_compra FROM liquidacion_compra");
        $id_liquidacion_compra=1;
        if(count($liquidacion_compra)>0){
            $id_liquidacion_compra=$liquidacion_compra[0]->id_liquidacion_compra+1;
        }
        $nro_liquidacion=substr($request->factura["clave_acceso"],24,15);
        $s_liquidacionsubstr = substr($request->factura["clave_acceso"], -19, -10);
        $clave_acceso_lc = trim($request->factura["clave_acceso"]);
        $clave_acceso_retencion = trim($request->retencion_clave_acceso);
        $ca = substr($clave_acceso_lc, 24, 15);
        $ca_rt = substr($clave_acceso_retencion, 24, 15);
        $slc = $s_liquidacionsubstr + 1;
        $idp = $request->usuario["id_punto_emision"];
        $numero = $request->factura["nfactura"];
        $autorizacion = $request->factura["autorizacion"];
        $verificacion = DB::select("SELECT * FROM liquidacion_compra WHERE clave_acceso like '%{$ca }%' and id_empresa={$request->usuario["id_empresa"]}");
        $data_empresa=DB::select("SELECT * from empresa where id_empresa={$request->usuario["id_empresa"]}");
        if(count($verificacion)>=1){
            return "error numero";
        }
        if ($request->retencion_estado) {
            if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                $res_3 = DB::select("SELECT observacion FROM liquidacion_compra 
                INNER JOIN retencion_liquidacion_compra
                on retencion_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                WHERE observacion like '%{$ca_rt}%' and id_empresa={$request->usuario["id_empresa"]}
                UNION
                SELECT observacion FROM factura_compra 
                INNER JOIN retencion_factura_comp
                on retencion_factura_comp.id_factura=factura_compra.id_factcompra
                WHERE observacion like '%{$ca_rt}%' and id_empresa={$request->usuario["id_empresa"]}");
                if(count($res_3)>0){
                    return "error numero retencion";
                }
            }
            
            
        }
        if(isset($request->factura["id_orden"])){
            $id_orden = $request->factura["id_orden"];
            DB::update("UPDATE orden_compra SET estado = 2 WHERE id_orden_compra = $id_orden");
        }
        if(isset($request->factura["id_orden"])){
            $id_orden = $request->factura["id_orden"];
            DB::update("UPDATE orden_compra SET estado = 2 WHERE id_orden_compra = $id_orden");
        }
        $hoy = Carbon::now();

        $factc = new LiquidacionCompra();
        $factc->id_liquidacion_compra =$id_liquidacion_compra;
        $factc->respuesta = "ERROR";
        $for_pago_emp=DB::select("SELECT * FROM forma_pagos where id_empresa={$request->usuario["id_empresa"]}");
        $factc->destino_pago = $request->factura["destino_pago"];
        $factc->gasto_import = intval(preg_replace('/[^0-9]+/', '', $request->factura["gastos"]), 10);
        $factc->documento_tributario = intval(preg_replace('/[^0-9]+/', '', $request->factura["docutributario"]), 10);
        $factc->id_importacion = $request->factura["importacion"];
        $factc->orden_compra = $request->factura["orden_compra"];
        
        $factc->descripcion = $nro_liquidacion;
        $factc->clave_acceso = $request->factura["clave_acceso"];
        $factc->fecha_emision = $request->factura["fecha_emision"];
        $factc->fech_validez = $request->factura["fecha_validez"];
        $factc->nro_autorizacion = $request->factura["clave_acceso"];

        $factc->subtotal_sin_impuesto = $request->subtotal;
        $factc->subtotal_12 = $request->subtotal12;
        $factc->subtotal_0 = $request->subtotal0;
        $factc->subtotal_no_obj_iva = $request->no_impuesto;
        $factc->descuento = $request->descuento;
        $factc->estado = 1;
        $factc->ambiente = $data_empresa[0]->ambiente;
        $factc->tipo_emision = $data_empresa[0]->tipo_emision;
        //$factc->valor_ice = 0;
        //$factc->valor_irbpnr = 0;
        $factc->iva_12 = $request->valor12;
        $factc->valor_total = $request->total;
        //$factc->modo_orden = 0;
        //$factc->facturado_orden = 1;

        $factc->id_sustento = $request->factura["tipo_sustento"];
        $factc->id_proveedor = $request->cliente;
        //$factc->id_cliente_asoc = $request->id_cliente_asoc;
        $factc->id_user = $request->usuario["id"];
        $factc->id_empresa = $request->usuario["id_empresa"];
        $factc->id_establecimiento = $request->usuario["id_establecimiento"];
        $factc->id_punto_emision = $request->usuario["id_punto_emision"];
        if ($request->retencion_estado) {
            if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                $factc->observacion = $request->retencion_clave_acceso;
            }
        }
        
        if(count($for_pago_emp)>0){
            //$factc->id_forma_pagos = $for_pago_emp[0]->id_forma_pagos;
        }
        $factc->id_tipo_comprobante = $request->factura["tipo_comprobante"];
        $factc->save();

        DB::update("UPDATE punto_emision SET secuencial_liquidacion_compra = '$slc' WHERE id_punto_emision = $idp");
        $ruta_2 = constant("DATA_EMPRESA") . $request->usuario["id_empresa"] . '/comprobantes/liquidacion_compra';
        if (!file_exists($ruta_2)) {
            mkdir($ruta_2, 0755, true);
        }
        $id = $id_liquidacion_compra;
        $id_bodega_ingreso = "";
        
        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc = new Detalle_liquidacion_compra();
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = round($request->productos[$a]["precio"], 2);
            $dfactc->descuento = $request->productos[$a]["descuento"];
            $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
            if ($request->productos[$a]["p_descuento"] == 1) {
                $dfactc->total = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
            } else {
                $dfactc->total = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
            }
            $dfactc->id_iva = $request->productos[$a]["iva"];
            $dfactc->id_ice = $request->productos[$a]["ice"];
            $dfactc->irbpnr = 0;
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            $dfactc->id_liquidacion_compra = $id;
            if(isset($request->productos[$a]["id_producto_bodega"])){
                $dfactc->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
            }
            if (isset($request->productos[$a]["proyecto"])) {
                $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
            }
            $dfactc->save();
            $factcompradet = $dfactc->id_detalle_liquidacion_compra;
            if(isset($request->productos[$a]["id_bodega"])){
                $v_producto = $request->productos[$a]["id_producto"];
                $v_bodega = $request->productos[$a]["id_bodega"];
                $v_empresa = $request->usuario["id_empresa"];
                $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = $v_producto AND id_bodega = $v_bodega AND id_empresa = $v_empresa");
                if(count($v_res)>=1){
                    $cant = $request->productos[$a]["cantidad"];
                    if ($request->productos[$a]["p_descuento"] == 1) {
                        $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                    } else {
                        $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                    }

                    $idpb = $v_res[0]->id_producto_bodega;
                    $cant_bodega = $v_res[0]->cantidad;
                    $total_bodega = $v_res[0]->costo_total;

                    $resultado_costo_unitario = ($total_bodega + $total_ingreso) / ($cant_bodega + $cant);

                    DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant, costo_unitario = $resultado_costo_unitario, costo_total = cantidad * $resultado_costo_unitario WHERE id_producto_bodega = $idpb");

                    $idempresa = $request->usuario["id_empresa"];
                    
                }else{
                    $prdb = new ProductoBodega();
                    $prdb->cantidad = $request->productos[$a]["cantidad"];
                    $prdb->costo_unitario = $request->productos[$a]["precio"];
                    $prdb->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
                    $prdb->id_producto = $request->productos[$a]["id_producto"];
                    $prdb->id_bodega = $request->productos[$a]["id_bodega"];
                    $prdb->id_empresa = $request->usuario["id_empresa"];
                    $prdb->save();

                    $idpbn = $prdb->id_producto_bodega;

                    $idempresa = $request->usuario["id_empresa"];

                    $idpb = $prdb->id_producto_bodega;
                    DB::update("UPDATE detalle_liquidacion_compra SET id_producto_bodega = $idpbn WHERE id_detalle_liquidacion_compra = $factcompradet");

                }
            }
        }

        if ($request->pagos["estado"]) {
            for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                    $pag = new Liquidaction_compra_pagos();
                    $pag->id_formas_pagos = null;
                    $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                    $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                    $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                    $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                    $pag->plazo = 1;
                    $pag->unidad_tiempo = 'Dias';
                    $pag->estado = 1;
                    $pag->fecha = $hoy;
                    $pag->id_liquidacion_compra = $id;
                    $pag->tiempos_pagos = 1;
                    $pag->anticipo = 1;
                    $pag->save();

                    $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                    //DB::update("UPDATE ctas_pagar SET abono = abono - $cpago WHERE id_proveedor = $request->cliente AND tipo = 3");
                    $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_pagar WHERE id_proveedor = $cliente AND tipo=3 ORDER BY id_ctaspagar ASC");
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctaspagar;
                                $pagado = $abono[$i]->abono;

                                if ($cpago > $pagado) {
                                    $cpc = Cuentaporpagar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                                    $cpc->abono = 0;
                                    $cpc->umodifica = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = $cpago - $pagado;
                                } else {
                                    $cpc = Cuentaporpagar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                                    $cpc->abono = $cpc->abono - $cpago;
                                    $cpc->umodifica = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = 0;
                                }
                            }
                } else {
                    if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                        $pag = new Liquidaction_compra_pagos();
                        $pag->id_formas_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                        $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                        $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                        $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                        $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                        $pag->plazo = 1;
                        $pag->unidad_tiempo = 'Días';
                        $pag->estado = 1;
                        $pag->fecha = $hoy;
                        if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                            $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                        }
                        $pag->id_liquidacion_compra = $id;
                        $pag->anticipo = null;
                        $pag->save();

                        $cxc = new Cuentaporpagar();
                        $cxc->num_cuota = 1;
                        $cxc->fecha_pago = $hoy;
                        $cxc->periodo_pagos = "Dias";
                        $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                        $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                        if (isset($request->pagos["datos"][$a]["banco_pago"])) {
                            $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                        }
                        if (isset($request->pagos["datos"][$a]["tarjeta"])) {
                            $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                        }
                        if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                            $cxc->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                        }
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->valor_pagado = 0;
                        $cxc->estado = 1;
                        $cxc->tipo = 2;
                        $cxc->id_liquidacion_compra = $id;
                        $cxc->id_proveedor = $request->cliente;
                        $cxc->id_empresa=$request->usuario["id_empresa"];
                        $cxc->save();
                    }
                }
            }
        }
        $fecharec = "";
        if ($request->creditos["estado"]) {
            $pag = new Liquidaction_compra_pagos();
            $pag->id_formas_pagos = $for_pago_emp[0]->id_forma_pagos;
            $pag->total = $request->creditos["monto"];
            $pag->plazo = $request->creditos["plazos"];
            $pag->unidad_tiempo = $request->creditos["periodo"];
            $pag->tiempos_pagos = $request->creditos["tiempo"];
            $pag->estado = 2;
            $pag->fecha = $hoy;
            $pag->id_liquidacion_compra = $id;
            $pag->save();

            $hoy = Carbon::parse($request->factura["fecha_emision"]);
            $fd = "";
            for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                $cxc = new Cuentaporpagar();
                $cxc->num_cuota = $a + 1;
                if ($a < 1) {
                    if ($request->creditos["periodo"] == "Años") {
                        $fecharec = $hoy->addYears($request->creditos["tiempo"]);
                        $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Meses") {
                        $fecharec = $hoy->addMonths($request->creditos["tiempo"]);
                        $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Semanas") {
                        $fecharec = $hoy->addWeeks($request->creditos["tiempo"]);
                        $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                    } else {
                        $fecharec = $hoy->addDays($request->creditos["tiempo"]);
                        $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
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
                $cxc->fecha_factura = $request->factura["fecha_emision"];
                $cxc->fecha_pago = $fd;
                $cxc->periodo_pagos = $request->creditos["periodo"];
                $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                $cxc->valor_pagado = 0;
                $cxc->estado = 1;
                $cxc->tipo = 1;
                $cxc->id_liquidacion_compra = $id;
                $cxc->id_proveedor = $request->cliente;
                $cxc->id_empresa=$request->usuario["id_empresa"];
                $cxc->save();
            }
        }

        //guardado de factura de retencion
        if ($request->retencion_estado) {
            //si la retencionexiste ingresa a la condicion
            if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                
                //si existe, suma +1 a la secuencial de retencion de punto de emision
                $s_facturasubstr = substr($request->retencion_clave_acceso, -19, -10);
                $sf = $s_facturasubstr + 1;
                
                DB::update("UPDATE punto_emision SET secuencial_retencion = '$sf' WHERE id_punto_emision = $idp");
                $ruta = constant("DATA_EMPRESA") . $request->usuario["id_empresa"] . '/comprobantes/retencioncompra/liquidacion_compra';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0755, true);
                }
                //recorre las retenciones existentes
                for ($i = 0; $i < count($request->valorretenciones); $i++) {
                    if ($request->valorretenciones[$i]["iva"] != null || $request->valorretenciones[$i]["renta"] != null) {
                        $ret = new Retencion_Liquidacion_Compra();
                        $ret->id_liquidacion_compra = $id;
                        $ret->respuesta = 'ERROR';
                        //verifica si es retencion de iva
                        if(isset($request->valorretenciones[$i]["iva"]["id_retencion"])){
                            $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                        }

                        //verifica si es retención de renta
                        if(isset($request->valorretenciones[$i]["renta"]["id_retencion"])){
                            $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                        }
                        if(isset($request->valorretenciones[$i]["iva"]["id_retencion"])){
                            $ret->baseiva = $request->valorretenciones[$i]["baseiva"];
                            $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                            $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                        }
                        if(isset($request->valorretenciones[$i]["renta"]["id_retencion"])){
                            $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                            $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                            $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                        }
                        $ret->save();
                    }
                }
            }
        }

        //si guarda exitosamente genera la factura del pdf
        // $facturaPDF = new generarPDF();
        // $facturaPDF->Facturacompra($request);

        self::CabeceraBodegaIngreso($id,$request->factura["clave_acceso"]);

        return LiquidacionCompra::select('liquidacion_compra.*', 'empresa.*', 'proveedor.*', 'proveedor.email as emailpr', 'moneda.nomb_moneda as moneda', 'liquidacion_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'liquidacion_compra.id_empresa')
            ->join('proveedor', 'proveedor.id_proveedor', '=', 'liquidacion_compra.id_proveedor')
            ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
            ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
            ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
            ->where("liquidacion_compra.id_liquidacion_compra", "=", $id)
            ->orderByRaw('liquidacion_compra.id_liquidacion_compra DESC')->get();
    }
    public function CabeceraBodegaIngreso($id,$nro_factura){
        $hoy = Carbon::now();
        $clave_acceso = substr($nro_factura, -19, -10);
        $factura=DB::select("SELECT * from liquidacion_compra where id_liquidacion_compra=$id");
        $detalle=DB::select("SELECT distinct id_bodega from detalle_liquidacion_compra 
                                INNER JOIN producto ON producto.id_producto=detalle_liquidacion_compra.id_producto 
                                INNER JOIN producto_bodega ON producto_bodega.id_producto_bodega=detalle_liquidacion_compra.id_producto_bodega 
                                where producto.sector=1 and id_liquidacion_compra=$id");
        $proyecto=DB::select("SELECT * from proyecto where id_empresa={$factura[0]->id_empresa}");
        if(count($detalle)>0){
            for ($a = 0; $a < count($detalle); $a++) {
                $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = {$factura[0]->id_empresa} ORDER BY  num_ingreso DESC LIMIT 1");
                $numeroingreso = "";
                if (count($numegre) == 1) {
                    $dato = $numegre[0]->num_ingreso;
                    $tot = $dato + 1;
                    $numeroingreso = $tot;
                } else {
                    $numeroingreso = 1;
                }
                $ingreso = new BodegaIngreso();
                $ingreso->num_ingreso = $numeroingreso;
                $ingreso->fecha_ingreso = $hoy;
                $ingreso->tipo_ingreso = "Ingreso de Liquidacion";
                $ingreso->observ_ingreso = 'Liquidacion Compra: ' . $clave_acceso;
                $ingreso->id_proyecto = $proyecto[0]->id_proyecto;
                $ingreso->id_bodega = $detalle[$a]->id_bodega;
                $ingreso->id_empresa = $factura[0]->id_empresa;
                $ingreso->id_liquidacion_compra = $id;
                $ingreso->save();
            }
        }
        if(count($detalle)>0){
            self::DetalleBodegaIngreso($id,$ingreso->fecha_ingreso,$nro_factura);
        }
        

    }
    public function DetalleBodegaIngreso($id,$fecha,$nro_factura){
            $detalle=DB::select("SELECT detalle_liquidacion_compra.cantidad,detalle_liquidacion_compra.precio,detalle_liquidacion_compra.id_producto,detalle_liquidacion_compra.id_detalle_liquidacion_compra,detalle_liquidacion_compra.id_proyecto,id_bodega from detalle_liquidacion_compra 
                                INNER JOIN producto ON producto.id_producto=detalle_liquidacion_compra.id_producto 
                                INNER JOIN producto_bodega ON producto_bodega.id_producto_bodega=detalle_liquidacion_compra.id_producto_bodega 
                                where producto.sector=1 and id_liquidacion_compra=$id");
            if(count($detalle)>0){
                for ($a = 0; $a < count($detalle); $a++) {
                    //$observ= 'Factura Compra: ' . $nro_factura;
                    $bodega_ingreso=DB::select("SELECT * from bodega_ingreso where id_bodega={$detalle[$a]->id_bodega} and id_liquidacion_compra=$id");
                    $bed = new BodegaIngresoDetalle();
                    $bed->cantidad = $detalle[$a]->cantidad;
                    $bed->costo_unitario = $detalle[$a]->precio;
                    $bed->costo_total = $detalle[$a]->cantidad*$detalle[$a]->precio;

                    $bed->id_bodega_ingreso = $bodega_ingreso[0]->id_bodega_ingreso;
                    $bed->id_producto = $detalle[$a]->id_producto;
                    $bed->id_detalle_liquidacion_compra = $detalle[$a]->id_detalle_liquidacion_compra;
                    $bed->id_proyecto = $detalle[$a]->id_proyecto;
                    $bed->save();
                }
            }
            
    }
    public function llamado_retencion($id){
        return LiquidacionCompra::select('liquidacion_compra.*', 'empresa.*', 'proveedor.*', 'proveedor.email as emailpr', 'moneda.nomb_moneda as moneda', 'liquidacion_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'liquidacion_compra.id_empresa')
            ->join('proveedor', 'proveedor.id_proveedor', '=', 'liquidacion_compra.id_proveedor')
            ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
            ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
            ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
            ->where("liquidacion_compra.id_liquidacion_compra", "=", $id)
            ->orderByRaw('liquidacion_compra.id_liquidacion_compra DESC')->get();
    }
    public function recuperar($id){
        //recupera al igual que factura venta los datos de factura compra
        $ctas = DB::select("SELECT * FROM ctas_pagar WHERE id_liquidacion_compra = $id AND tipo = 1");
        $datos = 0;
        //las referencia se convierte de string a array
        for($g=0; $g<count($ctas); $g++){
            $id_cta = $ctas[$g]->id_ctaspagar;
            $res = DB::select("SELECT * FROM ctas_pagar_pagos WHERE referencia LIKE '%$id_cta%'");
            for($f=0; $f<count($res); $f++){
                $ref = explode(";",$res[$f]->referencia);
                for($i=0; $i<count($ref); $i++){
                    if($i%4==1){
                        $datos++;
                    }
                }
            }
        }
        //datos generales de la afctura de compra
        $factura = DB::select("SELECT fc.*, (SELECT id_retencion_liquidacion_compra FROM retencion_liquidacion_compra WHERE id_liquidacion_compra = fc.id_liquidacion_compra LIMIT 1) AS id_retfactcompra,(SELECT respuesta FROM retencion_liquidacion_compra WHERE id_liquidacion_compra = fc.id_liquidacion_compra LIMIT 1) as respuesta_retencion FROM liquidacion_compra fc WHERE fc.id_liquidacion_compra = " . $id);
        $detalle_factura = DB::select("SELECT dfc.*, p.cod_principal, p.cod_alterno, p.sector, bod.nombre AS nombrebodega FROM detalle_liquidacion_compra dfc INNER JOIN producto p ON dfc.id_producto=p.id_producto LEFT JOIN producto_bodega pb ON pb.id_producto_bodega = dfc.id_producto_bodega LEFT JOIN bodega bod ON bod.id_bodega=pb.id_bodega WHERE dfc.id_liquidacion_compra = " . $id);
        $proveedor = DB::select("SELECT * FROM proveedor WHERE id_proveedor = " . $factura[0]->id_proveedor);

        $pagos = DB::select("SELECT pc.id_plan_cuentas AS plan_cuenta, pc.codcta AS cuenta, fp.id_formas_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS nro_trans, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta,fp.anticipo FROM liquidacion_compra_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas WHERE fp.estado = 1 AND fp.id_liquidacion_compra = " . $id);
        $creditos = DB::select("SELECT estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto FROM liquidacion_compra_pagos WHERE estado = 2 AND id_liquidacion_compra = " . $id);
        $iva = DB::select("SELECT r.*,rf.baseiva, rf.porcentajeiva, rf.cantidadiva FROM retencion_liquidacion_compra rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_liquidacion_compra = " . $id);
        $renta = DB::select("SELECT r.*, rf.baserenta, rf.porcentajerenta FROM retencion_liquidacion_compra rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_liquidacion_compra = " . $id);

        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }

        return [
            "factura" => $factura[0],
            "detalle_factura" => $detalle_factura,
            "proveedor" => $proveedor[0],
            "pagos" => $pagos,
            "creditos" => $factura_creditos,
            "iva" => $iva,
            "renta" => $renta,
            "cuentas" => $datos
        ];
    }
    public function verpagoproveedor(Request $request){
        $factura=DB::select("SELECT * from liquidacion_compra where id_liquidacion_compra={$request->id}");
        $pago_factura=DB::select("SELECT * from liquidacion_compra_pagos where id_liquidacion_compra={$request->id}");
        $respuesta="no";
        if(count($pago_factura)>0){
            $numero_monto=number_format($request->monto,2,".","");
            if($pago_factura[0]->total!==$numero_monto){
                $cta_prov_total=DB::select("SELECT sum(valor_pagado) as total from ctas_pagar where id_liquidacion_compra={$request->id}");
                if(count($cta_prov_total)>0){
                    if($cta_prov_total[0]->total>0){
                        $respuesta="si";
                    }
                }
            }
        }
        return $respuesta;
    }
    public function vertipcomprob(Request $request)
    {
        $id=$request->id_empresa;
        $buscar = $request->buscar;
        
        $recupera = DB::select("SELECT * FROM tipo_comprobante where id_empresa={$id} and cod_tipcomprob='03'");
        
        return [
            'recupera' => $recupera
        ];
    }
    public function editar_liquidacion_compra(Request $request){
        $valorcompra = DB::select("SELECT * FROM liquidacion_compra WHERE id_liquidacion_compra = " . $request->factura["id_liquidacion_compra"]);
        $valorcompra_retencion = DB::select("SELECT * FROM retencion_liquidacion_compra WHERE id_liquidacion_compra = " . $request->factura["id_liquidacion_compra"]);
        $for_pago_emp=DB::select("SELECT * FROM forma_pagos where id_empresa={$request->usuario["id_empresa"]}");
        $verificacion = DB::select("SELECT * FROM liquidacion_compra WHERE clave_acceso like '%{$request->factura['clave_acceso']}%' and id_liquidacion_compra={$request->factura['id_liquidacion_compra']}");
        if(count($verificacion)<1){
            $verificacion_2 = DB::select("SELECT * FROM liquidacion_compra WHERE clave_acceso like '%{$request->factura['clave_acceso']}%' and id_empresa={$request->usuario['id_empresa']}");
            if(count($verificacion_2)>=1){
                return "error numero";
            }
            
        }
        //dd($request->valorretenciones);
        $nro_liquidacion=substr($request->factura["clave_acceso"],24,15);
        $hoy = Carbon::now();
        //verifica si esta enviado al sri o no
        if($valorcompra[0]->respuesta != "Enviado"){
            $factc = LiquidacionCompra::findOrFail($request->factura["id_liquidacion_compra"]);
            $factc->destino_pago = $request->factura["destino_pago"];
            $factc->gasto_import = intval(preg_replace('/[^0-9]+/', '', $request->factura["gastos"]), 10);
            $factc->documento_tributario = intval(preg_replace('/[^0-9]+/', '', $request->factura["docutributario"]), 10);
            $factc->id_importacion = $request->factura["importacion"];
            $factc->orden_compra = $request->factura["orden_compra"];
            
            $factc->descripcion = $nro_liquidacion;
            $factc->clave_acceso = $request->factura["clave_acceso"];
            $factc->fecha_emision = $request->factura["fecha_emision"];
            $factc->fech_validez = $request->factura["fecha_validez"];
            $factc->nro_autorizacion = $request->factura["clave_acceso"];

            $factc->subtotal_sin_impuesto = $request->subtotal;
            $factc->subtotal_12 = $request->subtotal12;
            $factc->subtotal_0 = $request->subtotal0;
            $factc->subtotal_no_obj_iva = $request->no_impuesto;
            $factc->descuento = $request->descuento;
            //$factc->valor_ice = 0;
            //$factc->valor_irbpnr = 0;
            $factc->iva_12 = $request->valor12;
            $factc->valor_total = $request->total;
            //$factc->modo_orden = 0;
            //$factc->facturado_orden = 1;

            $factc->id_sustento = $request->factura["tipo_sustento"];
            $factc->id_proveedor = $request->cliente;
            //$factc->id_cliente_asoc = $request->id_cliente_asoc;
            $factc->id_user = $request->usuario["id"];
            $factc->id_empresa = $request->usuario["id_empresa"];
            $factc->id_establecimiento = $request->usuario["id_establecimiento"];
            $factc->id_punto_emision = $request->usuario["id_punto_emision"];
            if(count($valorcompra_retencion)>0){
                if($valorcompra_retencion[0]->respuesta != "Enviado"){
                    //si la retencion ya fue enviada al sri no ingresara a esta condicional
                    if ($request->retencion_estado) {
                        //verifica si existe iva o renta en la retención
                        if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                            $factc->observacion = $request->retencion_clave_acceso;
                        }
                    }
                }
            }else{
                if ($request->retencion_estado) {
                    //verifica si existe iva o renta en la retención
                    if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                        $factc->observacion = $request->retencion_clave_acceso;
                    }
                }
            }
            //$factc->observacion = $request->retencion_clave_acceso;
            $factc->id_tipo_comprobante = $request->factura["tipo_comprobante"];
            $factc->save();

            $id = $request->factura["id_liquidacion_compra"];
            $savebode = 0;
            $id_bodega_ingreso = "";
            $detalles_existentes = [];
            for ($a = 0; $a < count($request->productos); $a++) {
                if(isset($request->productos[$a]["id_detalle_liquidacion_compra"])){
                    $rees = DB::select("SELECT * FROM detalle_liquidacion_compra WHERE id_detalle_liquidacion_compra = ".$request->productos[$a]["id_detalle_liquidacion_compra"]);

                    $dfactc = Detalle_liquidacion_compra::findOrFail($request->productos[$a]["id_detalle_liquidacion_compra"]);
                    $dfactc->nombre = $request->productos[$a]["nombre"];
                    $dfactc->cantidad = $request->productos[$a]["cantidad"];
                    $dfactc->precio = number_format($request->productos[$a]["precio"],2,".","");
                    $dfactc->descuento = $request->productos[$a]["descuento"];
                    $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
                    if ($request->productos[$a]["p_descuento"] == 1) {
                        $dfactc->total = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                    } else {
                        $dfactc->total = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - ((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                    }
                    $dfactc->id_iva = $request->productos[$a]["iva"];
                    $dfactc->id_ice = $request->productos[$a]["ice"];
                    $dfactc->irbpnr = 0;
                    $dfactc->id_producto = $request->productos[$a]["id_producto"];
                    $dfactc->id_liquidacion_compra = $id;
                    if(isset($request->productos[$a]["id_producto_bodega"])){
                        $dfactc->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                    }
                    if (isset($request->productos[$a]["proyecto"])) {
                        $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
                    }
                    $dfactc->save();

                    array_push($detalles_existentes, $request->productos[$a]["id_detalle_liquidacion_compra"]);

                    $factcompradet = $request->productos[$a]["id_detalle_liquidacion_compra"];

                    if(isset($request->productos[$a]["id_producto_bodega"])){
                        $valer = $rees[0]->cantidad;
                        $valerprecio = number_format($rees[0]->precio,2,".","");

                        $valorreal = ($request->productos[$a]["cantidad"]) - ($valer);
                        $valorrealprecio = ($request->productos[$a]["precio"]) - ($valerprecio);

                        if ($valorreal!=0 || $valorrealprecio!=0) {
                            $idpb = $request->productos[$a]["id_producto_bodega"];
                            //valores producto inical
                            $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                            $cantidad_bodega_i = $reses[0]->cantidad;
                            $total_bodega_i = $reses[0]->costo_total;
                            //valores de ingreso antes de cambio
                            $cantidad_ing_p = $rees[0]->cantidad;
                            if ($rees[0]->p_descuento == 1) {
                                $total_ing_p = round((number_format($rees[0]->precio,2,".","") * $rees[0]->cantidad) - $rees[0]->descuento, 2);
                            } else {
                                $total_ing_p = round((number_format($rees[0]->precio,2,".","") * $rees[0]->cantidad) - ((number_format($rees[0]->precio) * $rees[0]->cantidad) * $rees[0]->descuento) / 100, 2);
                            }
                            $costo_total_i = $total_bodega_i - $total_ing_p;
                            $cantidad_total_i = $cantidad_bodega_i - $cantidad_ing_p;
                            $costo_unitario_i = $costo_total_i / $cantidad_total_i;
                            //DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cantidad_total_i, costo_unitario = $costo_unitario_i, costo_total = $costo_total_i WHERE id_producto_bodega = $idpb");

                            //valores producto final
                            // $reses_f = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                            // $cantidad_bodega_f = $reses_f[0]->cantidad;
                            // $total_bodega_f = $reses_f[0]->costo_total;
                            //valores del ingreso despues del cambio
                            $cantidad_f = $request->productos[$a]["cantidad"];
                            if ($request->productos[$a]["p_descuento"] == 1) {
                                $total_ing_f = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                            } else {
                                $total_ing_f = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - ((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                            }
                            $costo_total_f = $total_ing_f + $total_bodega_f;
                            $cantidad_total_f = $cantidad_bodega_f + $cantidad_f;
                            $costo_unitario_f = $costo_total_f / $cantidad_total_f;
                            //DB::update("UPDATE producto_bodega SET cantidad = cantidad + ($cantidad_f), costo_unitario = $costo_unitario_f, costo_total = $costo_total_f  WHERE id_producto_bodega = $idpb");

                            $costo_unitario = $request->productos[$a]["precio"];
                            $cantidad_nueva = $request->productos[$a]["cantidad"];
                            $total_nueva = $request->productos[$a]["precio"] * $request->productos[$a]["cantidad"];
                            //DB::update("UPDATE bodega_ingreso_detalle SET cantidad = $cantidad_nueva, costo_unitario = $costo_unitario, costo_total = $total_nueva WHERE id_detalle_factura_compra = " . $request->productos[$a]["id_detalle_factura_compra"]);
                        }
                    }else if(isset($request->productos[$a]["id_bodega"])){
                        $prdb = new ProductoBodega();
                        $prdb->cantidad = $request->productos[$a]["cantidad"];
                        $prdb->costo_unitario = number_format($request->productos[$a]["precio"],2,".","");
                        $prdb->costo_total = $request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"],2,".","");
                        $prdb->id_producto = $request->productos[$a]["id_producto"];
                        $prdb->id_bodega = $request->productos[$a]["id_bodega"];
                        $prdb->id_empresa = $request->usuario["id_empresa"];
                        $prdb->save();

                        $idpbn = $prdb->id_producto_bodega;

                        $idempresa = $request->usuario["id_empresa"];

                        $idpb = $prdb->id_producto_bodega;
                        DB::update("UPDATE detalle_liquidacion_compra SET id_producto_bodega = $idpbn WHERE id_detalle_liquidacion_compra = $factcompradet");

                        //registro de egreso
                        $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
                        $numeroingreso = "";
                        if (count($numegre) == 1) {
                            $dato = $numegre[0]->num_ingreso;
                            $tot = $dato + 1;
                            $numeroingreso = $tot;
                        } else {
                            $numeroingreso = 1;
                        }
                        $idbodega = $request->productos[$a]["id_bodega"];
                        if($savebode == 0){
                            $ingreso = new BodegaIngreso();
                            $ingreso->num_ingreso = $numeroingreso;
                            $ingreso->fecha_ingreso = $hoy;
                            $ingreso->tipo_ingreso = "Ingreso de Liquidacion";
                            $ingreso->observ_ingreso = 'Liquidacion Compra';
                            $ingreso->id_proyecto = $request->factura["proyectos"];
                            $ingreso->id_bodega = $idbodega;
                            $ingreso->id_empresa = $request->usuario["id_empresa"];
                            $ingreso->id_liquidacion_compra = $id;
                            $ingreso->save();

                            $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                            $savebode++;
                        }

                        $bed = new BodegaIngresoDetalle();
                        $bed->cantidad = $request->productos[$a]["cantidad"];
                        $bed->costo_unitario = number_format($request->productos[$a]["precio"],2,".","");
                        $bed->costo_total = $request->productos[$a]["cantidad"]*number_format($request->productos[$a]["precio"],2,".","");
                        $bed->id_bodega_ingreso = $id_bodega_ingreso;
                        $bed->id_producto = $request->productos[$a]["id_producto"];
                        $bed->id_detalle_liquidacion_compra = $factcompradet;
                        $bed->id_proyecto = $request->productos[$a]["proyecto"];
                        $bed->save();
                    }
                }else{
                    $dfactc = new Detalle_liquidacion_compra();
                    $dfactc->nombre = $request->productos[$a]["nombre"];
                    $dfactc->cantidad = $request->productos[$a]["cantidad"];
                    $dfactc->precio = number_format($request->productos[$a]["precio"],2,".","");
                    $dfactc->descuento = $request->productos[$a]["descuento"];
                    $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
                    if ($request->productos[$a]["p_descuento"] == 1) {
                        $dfactc->total = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                    } else {
                        $dfactc->total = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - ((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                    }
                    $dfactc->id_iva = $request->productos[$a]["iva"];
                    $dfactc->id_ice = $request->productos[$a]["ice"];
                    $dfactc->irbpnr = 0;
                    $dfactc->id_producto = $request->productos[$a]["id_producto"];
                    $dfactc->id_liquidacion_compra = $id;
                    if(isset($request->productos[$a]["id_producto_bodega"])){
                        $dfactc->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                    }
                    if (isset($request->productos[$a]["proyecto"])) {
                        $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
                    }
                    $dfactc->save();
                    $factcompradet = $dfactc->id_detalle_liquidacion_compra;

                    array_push($detalles_existentes, $dfactc->id_detalle_liquidacion_compra);

                    if(isset($request->productos[$a]["id_producto_bodega"])){
                        $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = ".$request->productos[$a]["id_producto_bodega"]);
                        $idbodega = $reses[0]->id_bodega;
                        $cant = $request->productos[$a]["cantidad"];
                        $idpb = $request->productos[$a]["id_producto_bodega"];
                        if ($request->productos[$a]["p_descuento"] == 1) {
                            $total_ingreso = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                        } else {
                            $total_ingreso = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - ((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                        }

                        $cant_bodega = $reses[0]->cantidad;
                        $total_bodega = $reses[0]->costo_total;

                        $resultado_costo_unitario = ($total_bodega + $total_ingreso) / ($cant_bodega + $cant);

                        DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant, costo_unitario = $resultado_costo_unitario, costo_total = cantidad * $resultado_costo_unitario WHERE id_producto_bodega = $idpb");

                        $idempresa = $request->usuario["id_empresa"];
                        //registro de egreso
                        $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
                        $numeroingreso = "";
                        if (count($numegre) == 1) {
                            $dato = $numegre[0]->num_ingreso;
                            $tot = $dato + 1;
                            $numeroingreso = $tot;
                        } else {
                            $numeroingreso = 1;
                        }

                        if($savebode == 0){
                            $clave_acceso = substr($request->factura["clave_acceso"], -19, -10);
                            $ingreso = new BodegaIngreso();
                            $ingreso->num_ingreso = $numeroingreso;
                            $ingreso->fecha_ingreso = $hoy;
                            $ingreso->tipo_ingreso = "Ingreso de Liquidacion Compra de " . $clave_acceso;
                            $ingreso->observ_ingreso = 'Liquidacion Compra';
                            $ingreso->id_proyecto = $request->factura["proyectos"];
                            $ingreso->id_bodega = $idbodega;
                            $ingreso->id_empresa = $request->usuario["id_empresa"];
                            $ingreso->id_liquidacion_compra = $id;
                            $ingreso->save();
                            $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                            $savebode++;
                        }

                        $bed = new BodegaIngresoDetalle();
                        $bed->cantidad = $request->productos[$a]["cantidad"];
                        $bed->costo_unitario = number_format($request->productos[$a]["precio"],2,".","");
                        $bed->costo_total = $request->productos[$a]["cantidad"]*number_format($request->productos[$a]["precio"],2,".","");
                        $bed->id_bodega_ingreso = $id_bodega_ingreso;
                        $bed->id_producto = $request->productos[$a]["id_producto"];
                        $bed->id_detalle_liquidacion_compra = $factcompradet;
                        $bed->id_proyecto = $request->productos[$a]["proyecto"];
                        $bed->save();
                    }else if(isset($request->productos[$a]["id_bodega"])){
                        $prdb = new ProductoBodega();
                        $prdb->cantidad = $request->productos[$a]["cantidad"];
                        $prdb->costo_unitario = number_format($request->productos[$a]["precio"],2,".","");
                        $prdb->costo_total = $request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"],2,".","");
                        $prdb->id_producto = $request->productos[$a]["id_producto"];
                        $prdb->id_bodega = $request->productos[$a]["id_bodega"];
                        $prdb->id_empresa = $request->usuario["id_empresa"];
                        $prdb->save();

                        $idpbn = $prdb->id_producto_bodega;

                        $idempresa = $request->usuario["id_empresa"];

                        $idpb = $prdb->id_producto_bodega;
                        DB::update("UPDATE detalle_liquidacion_compra SET id_producto_bodega = $idpbn WHERE id_detalle_liquidacion_compra = $factcompradet");

                        //registro de egreso
                        $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
                        $numeroingreso = "";
                        if (count($numegre) == 1) {
                            $dato = $numegre[0]->num_ingreso;
                            $tot = $dato + 1;
                            $numeroingreso = $tot;
                        } else {
                            $numeroingreso = 1;
                        }

                        $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = ".$prdb->id_producto_bodega);
                        $idbodega = $reses[0]->id_bodega;

                        if($savebode == 0){
                            $clave_acceso = substr($request->factura["clave_acceso"], -19, -10);
                            $ingreso = new BodegaIngreso();
                            $ingreso->num_ingreso = $numeroingreso;
                            $ingreso->fecha_ingreso = $hoy;
                            $ingreso->tipo_ingreso = "Ingreso de Liquidacion Compra de " . $clave_acceso;
                            $ingreso->observ_ingreso = 'Liquidacion  Compra';
                            $ingreso->id_proyecto = $request->factura["proyectos"];
                            $ingreso->id_bodega = $idbodega;
                            $ingreso->id_empresa = $request->usuario["id_empresa"];
                            $ingreso->id_liquidacion_compra = $id;
                            $ingreso->save();

                            $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                            $savebode++;
                        }

                        $bed = new BodegaIngresoDetalle();
                        $bed->cantidad = $request->productos[$a]["cantidad"];
                        $bed->costo_unitario = number_format($request->productos[$a]["precio"],2,".","");
                        $bed->costo_total = $request->productos[$a]["cantidad"]*number_format($request->productos[$a]["precio"],2,".","");
                        $bed->id_bodega_ingreso = $id_bodega_ingreso;
                        $bed->id_producto = $request->productos[$a]["id_producto"];
                        $bed->id_detalle_liquidacion_compra = $factcompradet;
                        $bed->id_proyecto = $request->productos[$a]["proyecto"];
                        $bed->save();
                    }
                }
            }
            //borrado de detalles eliminados
            if(count($detalles_existentes)>=1){
                $bsbs = "SELECT * FROM detalle_liquidacion_compra WHERE id_liquidacion_compra = $id AND ";
                foreach ($detalles_existentes as $dt_id) {
                    $bsbs .= "id_detalle_liquidacion_compra != $dt_id AND ";
                }
                $res_bsbs = substr($bsbs, 0, -4);
                $seldbs = DB::select($res_bsbs);
                if ($seldbs) {
                    for ($i = 0; $i < count($seldbs); $i++) {
                        if(isset($seldbs[$i]->id_producto_bodega)){
                            $rescuse_id_r = $seldbs[$i]->id_producto_bodega;
                            $rescuse_id_c = $seldbs[$i]->cantidad;
                            DB::update("UPDATE producto_bodega SET cantidad = cantidad - $rescuse_id_c, costo_total = (cantidad - $rescuse_id_c) * costo_unitario WHERE id_producto_bodega = $rescuse_id_r");
                        }
                        DB::delete("DELETE FROM bodega_ingreso_detalle WHERE id_detalle_liquidacion_compra = " . $seldbs[$i]->id_detalle_liquidacion_compra);
                        DB::delete("DELETE FROM detalle_liquidacion_compra WHERE id_detalle_liquidacion_compra = " . $seldbs[$i]->id_detalle_liquidacion_compra);
                    }
                }
            }
        }else{
            $nro_liquidacion=substr($request->factura["clave_acceso"],24,15);
            $factc = LiquidacionCompra::findOrFail($request->factura["id_liquidacion_compra"]);
            $factc->destino_pago = $request->factura["destino_pago"];
            $factc->gasto_import = intval(preg_replace('/[^0-9]+/', '', $request->factura["gastos"]), 10);
            $factc->documento_tributario = intval(preg_replace('/[^0-9]+/', '', $request->factura["docutributario"]), 10);
            $factc->id_importacion = $request->factura["importacion"];
            $factc->orden_compra = $request->factura["orden_compra"];
            $factc->descripcion = $nro_liquidacion;
            $factc->nro_autorizacion = $request->factura["autorizacion"];
            if(count($valorcompra_retencion)>0){
                if($valorcompra_retencion[0]->respuesta != "Enviado"){
                    //si la retencion ya fue enviada al sri no ingresara a esta condicional
                    if ($request->retencion_estado) {
                        //verifica si existe iva o renta en la retención
                        if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                            $factc->observacion = $request->retencion_clave_acceso;
                        }
                    }
                }
            }else{
                if ($request->retencion_estado) {
                    //verifica si existe iva o renta en la retención
                    if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                        $factc->observacion = $request->retencion_clave_acceso;
                    }
                }
            }
            //$factc->id_forma_pagos = 1;
            //$factc->modo_orden = 0;
            //$factc->facturado_orden = 1;
            $factc->id_sustento = $request->factura["tipo_sustento"];
            $factc->id_tipo_comprobante = $request->factura["tipo_comprobante"];
            $factc->save();
            $id = $request->factura["id_liquidacion_compra"];

            $id_bodega_ingreso = "";
            for ($a = 0; $a < count($request->productos); $a++) {
                $factcompradet = $request->productos[$a]["id_detalle_liquidacion_compra"];
                $verificabodega = DB::select("SELECT * FROM detalle_liquidacion_compra WHERE id_detalle_liquidacion_compra = $factcompradet");
                $valor_pbodega = $verificabodega[0]->id_producto_bodega;
                if(!isset($valor_pbodega)){
                    if(isset($request->productos[$a]["id_bodega"])){
                        $v_producto = $request->productos[$a]["id_producto"];
                        $v_bodega = $request->productos[$a]["id_bodega"];
                        $v_empresa = $request->usuario["id_empresa"];
                        $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = $v_producto AND id_bodega = $v_bodega AND id_empresa = $v_empresa");
                        if(count($v_res)>=1){
                            $cant = $request->productos[$a]["cantidad"];
                            if ($request->productos[$a]["p_descuento"] == 1) {
                                $total_ingreso = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                            } else {
                                $total_ingreso = round((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) - ((number_format($request->productos[$a]["precio"],2,".","") * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                            }

                            $idpb = $v_res[0]->id_producto_bodega;
                            $cant_bodega = $v_res[0]->cantidad;
                            $total_bodega = $v_res[0]->costo_total;

                            $resultado_costo_unitario = ($total_bodega + $total_ingreso) / ($cant_bodega + $cant);

                            DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant, costo_unitario = $resultado_costo_unitario, costo_total = (cantidad + $cant) * $resultado_costo_unitario WHERE id_producto_bodega = $idpb");

                            $idempresa = $request->usuario["id_empresa"];
                            //registro de egreso
                            $bdg = $request->productos[$a]["id_bodega"];
                            if(isset($bdg)){
                                DB::update("UPDATE bodega_ingreso SET id_bodega = $bdg WHERE id_liquidacion_compra = " . $request->factura["id_liquidacion_compra"]);
                            }
                        }else{
                            $prdb = new ProductoBodega();
                            $prdb->cantidad = $request->productos[$a]["cantidad"];
                            $prdb->costo_unitario = number_format($request->productos[$a]["precio"],2,".","");
                            $prdb->costo_total = $request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"],2,".","");
                            $prdb->id_producto = $request->productos[$a]["id_producto"];
                            $prdb->id_bodega = $request->productos[$a]["id_bodega"];
                            $prdb->id_empresa = $request->usuario["id_empresa"];
                            $prdb->save();

                            $idpbn = $prdb->id_producto_bodega;

                            $idempresa = $request->usuario["id_empresa"];

                            $idpb = $prdb->id_producto_bodega;
                            DB::update("UPDATE detalle_liquidacion_compra SET id_producto_bodega = $idpbn WHERE id_detalle_liquidacion_compra = $factcompradet");

                            //registro de egreso
                            if(isset($request->productos[$a]["id_bodega"])){
                                $bdg = $request->productos[$a]["id_bodega"];
                                DB::update("UPDATE bodega_ingreso SET id_bodega = $bdg WHERE id_liquidacion_compra = " . $request->factura["id_liquidacion_compra"]);
                            }
                        }
                    }
                }
            }
        }
        DB::delete("DELETE FROM liquidacion_compra_pagos WHERE id_liquidacion_compra = $id AND estado = 1");
        DB::delete("DELETE FROM ctas_pagar WHERE id_liquidacion_compra = $id AND tipo = 2");

        $ctas = DB::select("SELECT * FROM ctas_pagar WHERE id_liquidacion_compra = $id AND tipo = 1");
        $datos = [];
        for($g=0; $g<count($ctas); $g++){
            $id_cta = $ctas[$g]->id_ctaspagar;
            $res = DB::select("SELECT ctas_pagar_pagos.* FROM ctas_pagar_pagos,proveedor WHERE ctas_pagar_pagos.referencia LIKE '%;{$id_cta};%' and proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor and proveedor.id_empresa={$request->usuario["id_empresa"]}");
            for($f=0; $f<count($res); $f++){
                $ref = explode(";",$res[$f]->referencia);
                for($i=0; $i<count($ref); $i++){
                    if($i%4==1){
                        array_push($datos, $ref[$i]);
                    }
                }
            }
        }
        if(count($datos)==0){
            DB::delete("DELETE FROM liquidacion_compra_pagos WHERE id_liquidacion_compra = $id AND estado = 2");
            DB::delete("DELETE FROM ctas_pagar WHERE id_liquidacion_compra = $id AND tipo = 1");
            $fecharec = "";
            if ($request->creditos["estado"]) {
                $pag = new Liquidaction_compra_pagos();
                $pag->id_formas_pagos = null;
                $pag->total = $request->creditos["monto"];
                $pag->plazo = $request->creditos["plazos"];
                $pag->unidad_tiempo = $request->creditos["periodo"];
                $pag->tiempos_pagos = $request->creditos["tiempo"];
                $pag->estado = 2;
                $pag->fecha = $hoy;
                $pag->id_liquidacion_compra = $id;
                $pag->save();

                $hoy = Carbon::parse($request->factura["fecha_emision"]);
                $fd = "";
                for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                    $cxc = new Cuentaporpagar();
                    $cxc->num_cuota = $a + 1;
                    if ($a < 1) {
                        if ($request->creditos["periodo"] == "Años") {
                            $fecharec = $hoy->addYears($request->creditos["tiempo"]);
                            $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Meses") {
                            $fecharec = $hoy->addMonths($request->creditos["tiempo"]);
                            $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Semanas") {
                            $fecharec = $hoy->addWeeks($request->creditos["tiempo"]);
                            $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                        } else {
                            $fecharec = $hoy->addDays($request->creditos["tiempo"]);
                            $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
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
                    $cxc->fecha_factura = $request->factura["fecha_emision"];
                    $cxc->fecha_pago = $fd;
                    $cxc->periodo_pagos = $request->creditos["periodo"];
                    $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                    $cxc->valor_pagado = 0;
                    $cxc->estado = 1;
                    $cxc->tipo = 1;
                    $cxc->id_liquidacion_compra = $id;
                    $cxc->id_proveedor = $request->cliente;
                    $cxc->save();
                }
            }
        }

        if ($request->pagos["estado"]) {
            for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                    $pag = new Liquidaction_compra_pagos();
                    $pag->id_formas_pagos = null;
                    $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                    $pag->plazo = 1;
                    $pag->unidad_tiempo = 'Dias';
                    $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                    $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                    $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                    $pag->estado = 1;
                    $pag->fecha = $hoy;
                    $pag->id_liquidacion_compra = $id;
                    $pag->tiempos_pagos = 1;
                    $pag->anticipo = 1;
                    if(isset($request->pagos["datos"][$a]["plan_cuenta"])){
                        $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                    }
                    $pag->save();

                    $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                    //DB::update("UPDATE ctas_pagar SET abono = abono - $cpago WHERE id_proveedor = $request->cliente AND tipo = 3");
                    $cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_pagar WHERE id_proveedor = $cliente AND tipo=3 ORDER BY id_ctaspagar ASC");
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctaspagar;
                                $pagado = $abono[$i]->abono;

                                if ($cpago > $pagado) {
                                    $cpc = Cuentaporpagar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                                    $cpc->abono = 0;
                                    $cpc->umodifica = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = $cpago - $pagado;
                                } else {
                                    $cpc = Cuentaporpagar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                                    $cpc->abono = $cpc->abono - $cpago;
                                    $cpc->umodifica = session()->get('usuariosesion')['id'];
                                    $cpc->save();

                                    $cpago = 0;
                                }
                            }
                } else {
                    if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                        $pag = new Liquidaction_compra_pagos();
                        $pag->id_formas_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                        $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                        $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                        $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                        $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                        $pag->plazo = 1;
                        $pag->unidad_tiempo = 'Días';
                        $pag->estado = 1;
                        $pag->fecha = $hoy;
                        if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                            $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                        }
                        $pag->id_liquidacion_compra = $id;
                        $pag->anticipo = null;
                        $pag->save();

                        $cxc = new Cuentaporpagar();
                        $cxc->num_cuota = 1;
                        $cxc->fecha_pago = $hoy;
                        $cxc->periodo_pagos = "Dias";
                        $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                        $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                        if (isset($request->pagos["datos"][$a]["banco_pago"])) {
                            $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                        }
                        if (isset($request->pagos["datos"][$a]["tarjeta"])) {
                            $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                        }
                        if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                            $cxc->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                        }
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->valor_pagado = 0;
                        $cxc->estado = 1;
                        $cxc->tipo = 2;
                        $cxc->id_liquidacion_compra = $id;
                        $cxc->id_proveedor = $request->cliente;
                        $cxc->save();
                    }
                }
            }
        }
        if(count($valorcompra_retencion)>0){
            if($valorcompra_retencion[0]->respuesta != "Enviado"){
                //si la retencion ya fue enviada al sri no ingresara a esta condicional
                if ($request->retencion_estado) {
                    //verifica si existe iva o renta en la retención
                    if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                        //Borra la retencion antigua
                        DB::delete("DELETE FROM retencion_liquidacion_compra WHERE id_liquidacion_compra = $id");
                        $s_facturasubstr = substr($request->retencion_clave_acceso, -19, -10);
                        $sf = $s_facturasubstr + 1;
                        $idp = $request->usuario["id_punto_emision"];
                        DB::update("UPDATE punto_emision SET secuencial_retencion = '$sf' WHERE id_punto_emision = $idp");
    
                        //recorre las retenciones existentes y guarda los registros
                        for ($i = 0; $i < count($request->valorretenciones); $i++) {
                            if ($request->valorretenciones[$i]["iva"] != null || $request->valorretenciones[$i]["renta"] != null) {
                                $ret = new Retencion_Liquidacion_Compra();
                                $ret->id_liquidacion_compra = $id;
                                if(isset($request->valorretenciones[$i]["iva"]["id_retencion"])){
                                    $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                                }
                                if(isset($request->valorretenciones[$i]["renta"]["id_retencion"])){
                                    $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                                }
                                if(isset($request->valorretenciones[$i]["renta"]["id_retencion"])){
                                    $ret->baseiva = $request->valorretenciones[$i]["baseiva"];
                                    $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                                    $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                                }
                                if(isset($request->valorretenciones[$i]["renta"]["id_retencion"])){
                                    $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                                    $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                                    $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                                }
                                
                                $ret->save();
                            }
                        }
                    }
                }
            }
        }else{
            if ($request->retencion_estado) {
                //verifica si existe iva o renta en la retención
                if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                    $s_facturasubstr = substr($request->retencion_clave_acceso, -19, -10);
                    $sf = $s_facturasubstr + 1;
                    $idp = $request->usuario["id_punto_emision"];
                    DB::update("UPDATE punto_emision SET secuencial_retencion = '$sf' WHERE id_punto_emision = $idp");

                    //recorre las retenciones existentes y guarda los registros
                    for ($i = 0; $i < count($request->valorretenciones); $i++) {
                        if ($request->valorretenciones[$i]["iva"] != null || $request->valorretenciones[$i]["renta"] != null) {
                            $ret = new Retencion_Liquidacion_Compra();
                            $ret->id_liquidacion_compra = $id;
                            if(isset($request->valorretenciones[$i]["iva"]["id_retencion"])){
                                $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                            }
                            if(isset($request->valorretenciones[$i]["renta"]["id_retencion"])){
                                $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                            }
                            if(isset($request->valorretenciones[$i]["renta"]["id_retencion"])){
                                $ret->baseiva = $request->valorretenciones[$i]["baseiva"];
                                $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                                $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                            }
                            if(isset($request->valorretenciones[$i]["renta"]["id_retencion"])){
                                $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                                $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                                $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                            }
                            
                            $ret->save();
                        }
                    }
                }
            }
        }
        
        $retencion="";
        $liquidacion="";
        if($valorcompra[0]->respuesta == "Enviado"){
            $liquidacion="Enviado";
        }
        if(count($valorcompra_retencion)>0){
            if($valorcompra_retencion[0]->respuesta == "Enviado"){
                $retencion="Enviado";
            }
        }
        $datos=LiquidacionCompra::select('liquidacion_compra.*', 'empresa.*', 'proveedor.*', 'proveedor.email as emailpr', 'moneda.nomb_moneda as moneda', 'liquidacion_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento','liquidacion_compra.fcrea')
        ->join('empresa', 'empresa.id_empresa', '=', 'liquidacion_compra.id_empresa')
        ->join('proveedor', 'proveedor.id_proveedor', '=', 'liquidacion_compra.id_proveedor')
        ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
        ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
        ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
        ->where("liquidacion_compra.id_liquidacion_compra", "=", $id)
        ->orderByRaw('liquidacion_compra.id_liquidacion_compra DESC')->get();
        //verifica si esta enviado al sri si no esta enviado al sri recupera los registros de dicha retencion y su respectiva factura
        if($liquidacion != "Enviado" || $retencion != "Enviado"){
            return [
                "liquidacion"=>$liquidacion,
                "retencion"=>$retencion,
                "datos"=>$datos
            ];
        }else{
            return [
                "liquidacion"=>$liquidacion,
                "retencion"=>$retencion,
                "datos"=>[]
            ];
        }
    }
    public function eliminar_liquidacion_compra(Request $rq)
    {
        $hoy = Carbon::now();
        $factura = $rq->datos["id_liquidacion_compra"];
        $id_empresa = $rq->datos["id_empresa"];
        $id_proveedor_not = $rq->datos["id_proveedor"];
        $clave = substr($rq->datos["clave_acceso"], 24, 15);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$id_empresa} limit 1");
        $pto_emision = DB::select("SELECT * from punto_emision where id_punto_emision={$rq->id_pto} limit 1");
        //DB::delete("DELETE FROM bodega_egreso WHERE id_factura = $factura");
        $egreso = DB::select("SELECT bed.*,be.id_empresa,be.id_liquidacion_compra,be.id_bodega from bodega_ingreso_detalle as bed INNER JOIN bodega_ingreso as be ON be.id_bodega_ingreso=bed.id_bodega_ingreso WHERE be.id_liquidacion_compra = $factura");
        $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $id_empresa ORDER BY  num_egreso DESC LIMIT 1;");
        $numeroegreso = "";
        //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
        if (count($numegre) == 1) {
            $dato = $numegre[0]->num_egreso;
            $tot = $dato + 1;
            $numeroegreso = $tot;
        } else {
            $numeroegreso = 1;
        }

        $productos = DB::select("SELECT * FROM detalle_liquidacion_compra WHERE id_liquidacion_compra = $factura");

        $savebode = 0;
        $id_bodega_ingreso = "";

        if (count($egreso) > 0) {
            for ($i = 0; $i < count($egreso); $i++) {
                DB::update("UPDATE producto_bodega SET cantidad = cantidad - {$egreso[$i]->cantidad}, costo_total = costo_total-{$egreso[$i]->costo_total},costo_unitario=costo_total/cantidad WHERE id_producto = {$egreso[$i]->id_producto} and id_bodega={$egreso[$i]->id_bodega}");
                if ($savebode == 0) {
                    $egresos = new BodegaEgreso();
                    $egresos->num_egreso = $numeroegreso;
                    $egresos->fecha_egreso = $hoy;
                    $egresos->tipo_egreso = "Egreso de Liquidacion Compra";
                    $egresos->observ_egreso = 'Cancelacion Liquidacion Compra: ' . $clave;
                    $egresos->id_proyecto = $proyecto[0]->id_proyecto;
                    //if (isset($egreso[$i]->id_bodega)) {
                    $egresos->id_bodega = $egreso[$i]->id_bodega;
                    //}
                    $egresos->id_empresa = $egreso[$i]->id_empresa;
                    //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    $egresos->id_liquidacion_compra = $egreso[$i]->id_liquidacion_compra;
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
                $bed->id_detalle_liquidacion_compra = $egreso[$i]->id_detalle_liquidacion_compra;
                $bed->save();
            }
        }






        Cuentaporpagar::where("id_liquidacion_compra", "=", $factura)->delete();

        $res = DB::select("SELECT * FROM ctas_pagar_pagos WHERE referencia LIKE '%lc:{$factura}%' and id_proveedor={$id_proveedor_not}");
        $datos = new \ArrayObject();
        for ($f = 0; $f < count($res); $f++) {
            $ref = explode(";", $res[$f]->referencia);
            for ($i = 0; $i < count($ref); $i++) {
                if ($i % 4 == 3) {
                    if ($ref[$i] == 790) {
                        $val3 = $ref[$i - 3];
                        $val2 = $ref[$i - 2];
                        $val1 = $ref[$i - 1];
                        $val = "lc:".$ref[$i];
                        $datos->append("$val3;$val2;$val1;$val");
                    }
                }
            }
        }
        foreach ($datos as $rs) {
            $revisarid = DB::select("SELECT * FROM ctas_pagar_pagos WHERE referencia like '%$rs%' and id_proveedor={$id_proveedor_not}");
            $id = $revisarid[0]->id_ctas_pagar_pagos;

            DB::update("UPDATE ctas_pagar_pagos SET referencia = replace(referencia, '$rs', '') WHERE referencia like '%$rs%'");

            $revisarids = DB::select("SELECT * FROM ctas_pagar_pagos WHERE id_ctas_pagar_pagos = $id");
            $reff = $revisarids[0]->referencia;

            if ($reff == "") {
                DB::delete("DELETE FROM ctas_pagar_pagos WHERE id_ctas_pagar_pagos = $id");
            }
        }
        $fact = LiquidacionCompra::find($factura);
        $fact->estado = 0;
        $fact->save();
    }
    public function liquidacionCompraContabilizar(Request $request,$id){
        $empresa=DB::select("SELECT empresa.*,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,proveedor.tipo_identificacion from empresa,liquidacion_compra,proveedor where proveedor.id_empresa=empresa.id_empresa and liquidacion_compra.id_proveedor=proveedor.id_proveedor and liquidacion_compra.id_empresa=empresa.id_empresa and liquidacion_compra.id_liquidacion_compra=".$id);
        $proyecto=DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $factura = DB::select("SELECT f.* FROM liquidacion_compra f WHERE f.id_liquidacion_compra =".$id);
        $renta_retencion_asiento=DB::select("SELECT retencion.id_plan_cuentas,detalle_liquidacion_compra.total,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto,2) as porcentaje,retencion_liquidacion_compra.baserenta,
        retencion_liquidacion_compra.porcentajerenta,retencion_liquidacion_compra.cantidadrenta,round(retencion_liquidacion_compra.cantidadrenta*(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto),2) as haber,null as debe,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_liquidacion_compra.id_proyecto,proyecto.descripcion,(select sum(cantidadrenta) from retencion_liquidacion_compra where id_liquidacion_compra={$id}) as total_renta,detalle_liquidacion_compra.id_detalle_liquidacion_compra as id_detalle
                FROM retencion_liquidacion_compra
                INNER JOIN retencion
                ON retencion.id_retencion=retencion_liquidacion_compra.id_retencion_renta
                INNER JOIN liquidacion_compra
                on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                INNER JOIN detalle_liquidacion_compra
                on detalle_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                left JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                where liquidacion_compra.id_liquidacion_compra={$id} and liquidacion_compra.id_empresa={$request->id_empresa}
        ORDER BY detalle_liquidacion_compra.id_proyecto");
        $iva_retencion_asiento=DB::select(
                            "SELECT retencion.id_plan_cuentas,detalle_liquidacion_compra.total,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto,2) as porcentaje,
                            retencion_liquidacion_compra.porcentajeiva,retencion_liquidacion_compra.cantidadiva,round((retencion_liquidacion_compra.cantidadiva)*(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto),2) as haber,null as debe,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_liquidacion_compra.id_proyecto,proyecto.descripcion,(select sum(cantidadiva) from retencion_liquidacion_compra where id_liquidacion_compra={$id}) as total_iva,0 as acumula,detalle_liquidacion_compra.id_detalle_liquidacion_compra as id_detalle
                                    FROM retencion_liquidacion_compra
                                    INNER JOIN retencion
                                    ON retencion.id_retencion=retencion_liquidacion_compra.id_retencion_iva
                                    INNER JOIN liquidacion_compra
                                    on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                    INNER JOIN detalle_liquidacion_compra
                                    on detalle_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                                    left JOIN plan_cuentas
                                    on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                    LEFT JOIN proyecto
                                    on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                                    where liquidacion_compra.id_liquidacion_compra={$id} and liquidacion_compra.id_empresa={$request->id_empresa}
                            ORDER BY detalle_liquidacion_compra.id_proyecto");
        $query="SELECT round(sum(liquidacion_compra_pagos.total)/count(liquidacion_compra_pagos.id_liquidacion_compra_pagos),2) as total,round(sum(detalle_liquidacion_compra.total)/max(liquidacion_compra.subtotal_sin_impuesto),2) as porcentaje,sum(detalle_liquidacion_compra.total) as valor_producto,round((sum(detalle_liquidacion_compra.total)/max(liquidacion_compra.subtotal_sin_impuesto)*(sum(liquidacion_compra_pagos.total)/count(liquidacion_compra_pagos.id_liquidacion_compra_pagos))),2) as haber,null as debe,detalle_liquidacion_compra.id_proyecto,proyecto.descripcion,plan_cuentas.id_plan_cuentas as id_plan_cuenta_grupo_prov,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta_grupo_prov,if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_prov,(select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as id_plan_cuenta_prov,(select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as nombre_cuenta_prov,max(liquidacion_compra_pagos.total) as total_pago
        from liquidacion_compra_pagos
                INNER JOIN liquidacion_compra
                ON liquidacion_compra.id_liquidacion_compra=liquidacion_compra_pagos.id_liquidacion_compra
                INNER JOIN proveedor
                ON proveedor.id_proveedor=liquidacion_compra.id_proveedor
                Left JOIN grupo_proveedor
                ON grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                Left JOIN plan_cuentas
                ON plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                INNER JOIN detalle_liquidacion_compra
                ON detalle_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
        where liquidacion_compra_pagos.id_liquidacion_compra={$id} and liquidacion_compra_pagos.estado=2
        GROUP BY detalle_liquidacion_compra.id_proyecto
        ORDER BY detalle_liquidacion_compra.id_proyecto asc";
        $creditos=DB::select("SELECT * from liquidacion_compra_pagos where id_liquidacion_compra={$id} and estado=2");
        if(count($creditos)>0){
            $cliente = DB::select($query);
        }else{
            $cliente=[];
        }
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'LC-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
        $cod_asiento="";
        if($codigo){
            $lenght=strlen($codigo[0]->codigo);
            $cod_asiento=$codigo[0]->codigo+1;
        }else{
            $cod_asiento="1";
        }
        $cod_asiento_ant="";
        if($codigo){
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=23 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $lenght=strlen($codigo[0]->codigo);
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }

        }
        $producto_asiento=DB::select("SELECT detalle_liquidacion_compra.total,if(producto.iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
                                        if(producto.sector=1,linea_producto.id_plan_cuentas_compras_iva,null) as id_plan_cuentas_iva_12,
                                        if(producto.sector=1,linea_producto.id_plan_cuentas_compras_iva_0,null) as id_plan_cuentas_iva_0,
                                        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva is null,0,linea_producto.id_plan_cuentas_compras_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
                                        (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva is null,0,linea_producto.id_plan_cuentas_compras_iva) and id_empresa={$request->id_empresa}) as grupo_cuenta_12,
                                        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva_0 is null,0,linea_producto.id_plan_cuentas_compras_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
                                        (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva_0 is null,0,linea_producto.id_plan_cuentas_compras_iva_0) and id_empresa={$request->id_empresa}) as grupo_cuenta_0,
                                        plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
                                        concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
                                        plan_cuentas.id_grupo as grupo_cuenta_servicio,
                                        null as haber,detalle_liquidacion_compra.total as debe
                                        from detalle_liquidacion_compra
                                        INNER JOIN producto
                                        ON producto.id_producto=detalle_liquidacion_compra.id_producto
                                        INNER JOIN liquidacion_compra
                                        ON liquidacion_compra.id_liquidacion_compra=detalle_liquidacion_compra.id_liquidacion_compra
                                        LEFT JOIN plan_cuentas
                                        ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
                                        LEFT JOIN proyecto
                                        on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                                        LEFT JOIN linea_producto
                                        on linea_producto.id_linea_producto=producto.id_linea_producto
                                        where detalle_liquidacion_compra.id_liquidacion_compra={$id}");
        $iva_asiento=DB::select("SELECT detalle_liquidacion_compra.total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as haber,round(if(detalle_liquidacion_compra.id_iva=2,detalle_liquidacion_compra.total*(12/100),0),2) as debe,liquidacion_compra.iva_12
            from liquidacion_compra,retencion,plan_cuentas,detalle_liquidacion_compra
                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle_liquidacion_compra.id_producto
                       where liquidacion_compra.id_liquidacion_compra={$id} and liquidacion_compra.id_liquidacion_compra=detalle_liquidacion_compra.id_liquidacion_compra and retencion.descrip_retencion='IVA. en Compras' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                       ORDER BY detalle_liquidacion_compra.id_detalle_liquidacion_compra asc");
        $forma_pagos_sin_plc=DB::select("SELECT liquidacion_compra_pagos.total,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto*	liquidacion_compra_pagos.total,2) as haber,null as debe,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_liquidacion_compra.id_proyecto,proyecto.descripcion,liquidacion_compra_pagos.id_formas_pagos as id_forma_pagos,liquidacion_compra_pagos.fecha_pago,liquidacion_compra_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
                                        from liquidacion_compra_pagos
                                        INNER JOIN forma_pagos
                                        on forma_pagos.id_forma_pagos=liquidacion_compra_pagos.id_formas_pagos
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                                        INNER JOIN liquidacion_compra
                                        on liquidacion_compra.id_liquidacion_compra=liquidacion_compra_pagos.id_liquidacion_compra
                                        INNER JOIN detalle_liquidacion_compra
                                        on detalle_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                                        where liquidacion_compra_pagos.estado=1 and liquidacion_compra_pagos.id_liquidacion_compra={$id} and liquidacion_compra_pagos.anticipo is null and liquidacion_compra_pagos.id_plan_cuentas is null
                                        ORDER BY detalle_liquidacion_compra.id_proyecto asc");
        $pagos_sin_plc=DB::select("SELECT sum(liquidacion_compra_pagos.total) as total_pago from liquidacion_compra_pagos  where liquidacion_compra_pagos.estado=1 and liquidacion_compra_pagos.id_liquidacion_compra={$id} and liquidacion_compra_pagos.anticipo is null and liquidacion_compra_pagos.id_plan_cuentas is null");
        $forma_pagos_con_plc=DB::select("SELECT liquidacion_compra_pagos.total,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto*	liquidacion_compra_pagos.total,2) as haber,null as debe,liquidacion_compra_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_liquidacion_compra.id_proyecto,proyecto.descripcion,liquidacion_compra_pagos.id_formas_pagos as id_forma_pagos,liquidacion_compra_pagos.fecha_pago,liquidacion_compra_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
                                        from liquidacion_compra_pagos
                                        INNER JOIN forma_pagos
                                        on forma_pagos.id_forma_pagos=liquidacion_compra_pagos.id_formas_pagos
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=liquidacion_compra_pagos.id_plan_cuentas
                                        INNER JOIN liquidacion_compra
                                        on liquidacion_compra.id_liquidacion_compra=liquidacion_compra_pagos.id_liquidacion_compra
                                        INNER JOIN detalle_liquidacion_compra
                                        on detalle_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                                        where liquidacion_compra_pagos.estado=1 and liquidacion_compra_pagos.id_liquidacion_compra={$id} and liquidacion_compra_pagos.anticipo is null and liquidacion_compra_pagos.id_plan_cuentas is not null
                                        ORDER BY detalle_liquidacion_compra.id_proyecto asc");
        $pagos_con_plc=DB::select("SELECT sum(liquidacion_compra_pagos.total) as total_pago from liquidacion_compra_pagos  where liquidacion_compra_pagos.estado=1 and liquidacion_compra_pagos.id_liquidacion_compra={$id} and liquidacion_compra_pagos.anticipo is null and liquidacion_compra_pagos.id_plan_cuentas is not null");
        $forma_pagos_anticipo=DB::select("SELECT liquidacion_compra_pagos.total,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto*liquidacion_compra_pagos.total,2) as haber,null as debe,grupo_proveedor.id_plan_cuentas_anticipo as id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_liquidacion_compra.id_proyecto,proyecto.descripcion,liquidacion_compra_pagos.id_formas_pagos as id_forma_pagos,liquidacion_compra_pagos.fecha_pago,liquidacion_compra_pagos.numero_transaccion,null as nombre_pago
                                        from liquidacion_compra_pagos
                                        INNER JOIN liquidacion_compra
                                        on liquidacion_compra.id_liquidacion_compra=liquidacion_compra_pagos.id_liquidacion_compra
                                        INNER JOIN proveedor
                                        on proveedor.id_proveedor=liquidacion_compra.id_proveedor
                                        LEFT JOIN grupo_proveedor
                                        on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas_anticipo
                                        INNER JOIN detalle_liquidacion_compra
                                        on detalle_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                                        where liquidacion_compra_pagos.estado=1 and liquidacion_compra_pagos.id_liquidacion_compra={$id} and liquidacion_compra_pagos.anticipo is not null and liquidacion_compra_pagos.id_plan_cuentas is null
                                        ORDER BY detalle_liquidacion_compra.id_proyecto asc");
        $pagos_anticipo=DB::select("SELECT sum(liquidacion_compra_pagos.total) as total_pago from liquidacion_compra_pagos  where liquidacion_compra_pagos.estado=1 and liquidacion_compra_pagos.id_liquidacion_compra={$id} and liquidacion_compra_pagos.anticipo is not null and liquidacion_compra_pagos.id_plan_cuentas is null");
        $ice_fact=DB::select("SELECT ice.valor as total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,ice.valor as debe,null as haber,detalle_liquidacion_compra.id_detalle_liquidacion_compra as id_detalle from detalle_liquidacion_compra
        INNER JOIN liquidacion_compra
        on liquidacion_compra.id_liquidacion_compra=detalle_liquidacion_compra.id_liquidacion_compra
        INNER JOIN ice
        on ice.id_ice=detalle_liquidacion_compra.id_ice
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=ice.id_plan_cuentas
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
        where liquidacion_compra.id_liquidacion_compra={$id} and ice.valor>0
        ORDER BY detalle_liquidacion_compra.id_detalle_liquidacion_compra asc");
        $fecha_emision=substr($factura[0]->fecha_emision,0,-3);
        $anio_emision=substr($factura[0]->fecha_emision,0,4);
        $fecha_cierre=DB::select("SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Cierre Mes' and periodo='{$fecha_emision}'
                                UNION
                                SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Estado Contable' and periodo='{$anio_emision}'");
        $total_retencion=DB::select("SELECT sum(cantidadiva) as cantidad_iva,sum(cantidadrenta) as cantidad_renta  from retencion_factura_comp where retencion_factura_comp.id_factura={$id}");
        $asiento="";
        if(count($fecha_cierre)>0){
            $asiento="no";
        }else{
            $asiento="si";
        }
        $total_pagos_sin_plc=0;
        $total_pagos_con_plc=0;
        $total_pagos_anticipo=0;
        $total_retencion_iva=0;
        $total_retencion_renta=0;
        if(count($pagos_sin_plc)>0){
            $total_pagos_sin_plc=$pagos_sin_plc[0]->total_pago;
        }
        if(count($pagos_con_plc)>0){
            $total_pagos_con_plc=$pagos_con_plc[0]->total_pago;
        }
        if(count($pagos_anticipo)>0){
            $total_pagos_anticipo=$pagos_anticipo[0]->total_pago;
        }
        if(count($total_retencion)>0){
            $total_retencion_iva=$total_retencion[0]->cantidad_iva;
            $total_retencion_renta=$total_retencion[0]->cantidad_renta;
        }
        return [
            'factura' => $factura[0],
            'asiento_permitido'=>$asiento,
            'cliente' => $cliente,
            'empresa'=>$empresa[0],
            'codigo'=>$cod_asiento,
            'codigo_anterior'=>$cod_asiento_ant,
            'producto_asientos'=>$producto_asiento,
            'doce_iva_asiento'=>$iva_asiento,
            'retencion_asiento'=>$renta_retencion_asiento,
            'iva_retencion_asiento'=>$iva_retencion_asiento,
            'pagos_asientos_sin_plc'=>$forma_pagos_sin_plc,
            'pagos_asientos_con_plc'=>$forma_pagos_con_plc,
            'pagos_asientos_anticipo'=>$forma_pagos_anticipo,
            'id_proyecto'=>$proyecto[0]->id_proyecto,
            'ice'=>$ice_fact,
            'total_pagos_sin_plc'=>$total_pagos_sin_plc,
            'total_pagos_con_plc'=>$total_pagos_con_plc,
            'total_pagos_anticipo'=>$total_pagos_anticipo,
            'total_retencion_iva'=>$total_retencion_iva,
            'total_retencion_renta'=>$total_retencion_renta
        ];
    }
    public function agregarAsiento(Request $request){
        LiquidacionCompra::where('id_liquidacion_compra',$request->cod_rol)->update(['contabilidad'=>'1']);
        $asientos=new Asientos();
        $asientos->automatico=0;
        $asientos->numero=$request->numero;
        $asientos->codigo=$request->codigo;
        $asientos->codigo_rol=$request->cod_rol;
        $asientos->fecha=$request->fecha;
        $asientos->razon_social=$request->razon_social;
        $asientos->tipo_identificacion=$request->tipo_identificacion;
        $asientos->ruc_ci=$request->ruc_ci;
        $asientos->concepto=$request->concepto;
        $asientos->ucrea=$request->ucrea;
        $asientos->id_proyecto=$request->id_proyecto;
        $asientos->id_asientos_comprobante=23;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request){
        foreach($request->productos as $haber){
            $asiento=new Asientos_contables_detalle();
            if($haber["sector"]=="producto" && $haber["iva"]=="doce"){
                $asiento->proyecto=$haber["descripcion"];
                $asiento->debe=$haber["debe"];
                $asiento->ucrea=$request->ucrea;
                $asiento->id_plan_cuentas=$haber["id_plan_cuentas_iva_12"];
                $asiento->id_asientos=$request->id_asientos;
                $asiento->id_proyecto=$haber["id_proyecto"];
            }
            if($haber["sector"]=="producto" && $haber["iva"]=="cero"){
                $asiento->proyecto=$haber["descripcion"];
                $asiento->debe=$haber["debe"];
                $asiento->ucrea=$request->ucrea;
                $asiento->id_plan_cuentas=$haber["id_plan_cuentas_iva_0"];
                $asiento->id_asientos=$request->id_asientos;
                $asiento->id_proyecto=$haber["id_proyecto"];
            }
            if($haber["sector"]=="servicio"){
                $asiento->proyecto=$haber["descripcion"];
                $asiento->debe=$haber["debe"];
                $asiento->ucrea=$request->ucrea;
                $asiento->id_plan_cuentas=$haber["id_plan_cuentas_servicio"];
                $asiento->id_asientos=$request->id_asientos;
                $asiento->id_proyecto=$haber["id_proyecto"];
            }
            $asiento->save();
        }
        foreach($request->iva_12 as $haber){
            $asiento=new Asientos_contables_detalle();
                if($haber["debe"]>0){
                    $asiento->proyecto=$haber["descripcion"];
                    $asiento->debe=$haber["debe"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$haber["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$haber["id_proyecto"];
                }
                $asiento->save();
        }

        foreach($request->pagos_sin_plc as $debe){
            $asiento=new Asientos_contables_detalle();
            if(count($debe)>0){
                if($debe["haber"]>0){
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->haber=$debe["haber"];
                    if($debe["bansel"]!==null){
                        $asiento->no_documento=$debe["numero_transaccion"];
                        $asiento->fecha_de_pago=$debe["fecha_pago"];
                        $asiento->id_forma_pagos=$debe["id_forma_pagos"];
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                    $asiento->save();
                }
            }

        }
        foreach($request->pagos_con_plc as $debe){
            $asiento=new Asientos_contables_detalle();
            if(count($debe)>0){
                if($debe["haber"]>0){
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->haber=$debe["haber"];
                    if($debe["bansel"]!==null){
                        $asiento->no_documento=$debe["numero_transaccion"];
                        $asiento->fecha_de_pago=$debe["fecha_pago"];
                        $asiento->id_forma_pagos=$debe["id_forma_pagos"];
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                    $asiento->save();
                }
            }

        }
        foreach($request->pagos_anticipo as $debe){
            $asiento=new Asientos_contables_detalle();
            if(count($debe)>0){
                if($debe["haber"]>0){
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->haber=$debe["haber"];
                    if($debe["bansel"]!==null){
                        $asiento->no_documento=$debe["numero_transaccion"];
                        $asiento->fecha_de_pago=$debe["fecha_pago"];
                        $asiento->id_forma_pagos=$debe["id_forma_pagos"];
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                    $asiento->save();
                }
            }

        }

        foreach($request->creditos as $debe){
            $asiento=new Asientos_contables_detalle();
            if(count($debe)>0){
                if($debe["exist_plan_cuenta_prov"]=="si"){
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->haber=$debe["haber"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuenta_prov"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                }else{
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->haber=$debe["haber"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuenta_grupo_prov"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                }

                $asiento->save();
            }
        }

        foreach($request->retencion_iva as $debe){
            $asiento=new Asientos_contables_detalle();
            if(count($debe)>0){
                $asiento->proyecto=$debe["descripcion"];
                $asiento->haber=$debe["haber"];
                $asiento->ucrea=$request->ucrea;
                $asiento->id_plan_cuentas=$debe["id_plan_cuentas"];
                $asiento->id_asientos=$request->id_asientos;
                $asiento->id_proyecto=$debe["id_proyecto"];
                $asiento->save();
            }
        }
        foreach($request->retencion_renta as $debe){
            $asiento=new Asientos_contables_detalle();
            if(count($debe)>0){
                $asiento->proyecto=$debe["descripcion"];
                $asiento->haber=$debe["haber"];
                $asiento->ucrea=$request->ucrea;
                $asiento->id_plan_cuentas=$debe["id_plan_cuentas"];
                $asiento->id_asientos=$request->id_asientos;
                $asiento->id_proyecto=$debe["id_proyecto"];
                $asiento->save();
            }
        }

    }

    public function liquidacion_compra_pdf($id, $tipo)
    {
        //Selecciona la factura que va enviar
        $facturas = DB::select("SELECT *,(select id_retencion_liquidacion_compra from retencion_liquidacion_compra where id_liquidacion_compra={$id}) as exist_retencion FROM liquidacion_compra WHERE id_liquidacion_compra = $id");
        $id_cliente = $facturas[0]->id_proveedor;
        $id_empresa = $facturas[0]->id_empresa;
        $id_punto_emision = $facturas[0]->id_punto_emision;
        $id_establecimiento = $facturas[0]->id_establecimiento;
        $clave_acceso = $facturas[0]->descripcion;
        $clave_acceso_2 = $facturas[0]->clave_acceso;

        //selecciona los clientes, empresa, los productos, pagos y clientes para crear en pdf
        $clientes = DB::select("SELECT * FROM proveedor WHERE id_proveedor = $id_cliente");
        $empresas = DB::select("SELECT em.*, es.urlweb FROM empresa em INNER JOIN establecimiento es ON es.id_establecimiento = $id_establecimiento INNER JOIN punto_emision pe ON pe.id_punto_emision = $id_punto_emision WHERE em.id_empresa = $id_empresa");
        $detalles = DB::select("SELECT det.*, pr.cod_principal, pr.cod_alterno, pr.total_ice as total_ice_pr FROM detalle_liquidacion_compra as det INNER JOIN producto pr ON det.id_producto=pr.id_producto WHERE det.id_liquidacion_compra = $id");
        $pagos = DB::select("SELECT fp.*, fps.descripcion, fpag.descripcion AS descripcionfp FROM liquidacion_compra_pagos fp LEFT JOIN forma_pagos fpag ON fp.id_formas_pagos=fpag.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri=fpag.id_forma_pagos_sri WHERE fp.id_liquidacion_compra=$id");
        $cliente = $clientes[0];
        $empresa = $empresas[0];
        $factura = $facturas[0];
        //envia a la vista de factura_venta los datos almacenados en las variables  mdiante compact
        $pdf = \PDF::loadView('pdf/liquidacion_compra', compact("factura", "cliente", "empresa", "detalles", "clave_acceso","clave_acceso_2", "pagos"));
        $carpeta2 = constant("DATA_EMPRESA") . "$id_empresa/comprobantes/liquidacion_compra";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0755, true);
        }
        //si la url tiene d va a descargar caso contrario solo va a ser una vista
        if ($tipo == 'd') {
            return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/liquidacion_compra/$factura->clave_acceso.pdf")->download("$clave_acceso.pdf");
        } else {
            return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/liquidacion_compra/$factura->clave_acceso.pdf")->stream("$clave_acceso.pdf");
        }
        //envia a la vista de factura_venta los datos almacenados en las variables  mdiante compact

    }
    public function liquidacion_comprastotales(Request $request){
        $queries = [];
        $inners = [];
        $fields = [];
        $initial = null;
        $final = null;
        if ($request->dates) {
            $info_date = json_decode($request->dates, true);
            if ($request->currentDate !== "true") {
                $initial = str_replace("-010-", "-10-", $info_date["range"]["initial"]);
                $final = str_replace("-010-", "-10-", $info_date["range"]["final"]);
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(f.fecha_emision) between date('{$initial}') and date('{$final}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(f.fech_validez) between date('{$initial}') and date('{$final}')\n");
                }
                if ($info_date["option"] == 3) {
                    array_push($queries, "date(f.fecha_emision) between date('{$initial}') and date('{$final}')\n");
                }
            } else {
                $initial = str_replace("-010-", "-10-", $info_date["value"]);
                $final = str_replace("-010-", "-10-", $info_date["value"]);
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(f.fecha_emision) = date('{$final}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(f.fecha_emision) = date('{$final}')\n");
                }
                if ($info_date["option"] == 3) {
                    array_push($queries, "date(f.fecha_emision) = date('{$final}')\n");
                }
            }
        }
        if ($request->establishment) {
            $info_establishment = json_decode($request->establishment, true);
            if ($info_establishment["id"] != 0) {
                array_push($queries, "f.id_establecimiento = {$info_establishment["id"]}\n");
            }

        }
        if ($request->pointOfEmission) {
            $info_point_emission = json_decode($request->pointOfEmission, true);
            if ($info_point_emission["id"] != 0) {
                array_push($queries, "f.id_punto_emision = {$info_point_emission["id"]}\n");
            }

        }
        // if ($request->project) {
        //     $info_project = json_decode($request->project, true);
        //     if ($info_project["id"] != 0) {
        //         array_push($queries, "f.id_proyecto = {$info_project["id"]}\n");
        //     }

        // }
        if ($request->provider) {
            $info_provider = json_decode($request->provider, true);
            if ($info_provider["id"] != 0) {
                array_push($queries, "f.id_proveedor = {$info_provider["id"]}\n");
            }

        }
        if($request->rol_user==2){
            array_push($queries, "f.id_user = {$request->id_user}\n");
        }else{
            if ($request->user) {
                $info_user = json_decode($request->user, true);
                if ($info_user["id"] != 0) {
                    array_push($queries, "f.id_user = {$info_user["id"]}\n");
                }

            }
        }

        // if ($request->wayToPay) {
        //     $info_payment = json_decode($request->wayToPay, true);
        //     if ($info_payment["id"] != 0) {
        //         array_push($queries, "f.id_forma_pagos = {$info_payment["id"]}\n");
        //     }

        // }
        if ($request->invoice) {
            $info_invoice = json_decode($request->invoice);
            if ($info_invoice->all == false) {
                if ($info_invoice->retention) {
                    array_push($inners, "INNER JOIN retencion_liquidacion_compra r ON r.id_liquidacion_compra = f.id_liquidacion_compra\n");
                    array_push($fields, "r.cantidadiva,\n");
                    array_push($fields, "r.cantidadrenta,\n");
                } else {
                    array_push($inners, "LEFT JOIN retencion_liquidacion_compra r ON r.id_liquidacion_compra = f.id_liquidacion_compra\n");
                    array_push($fields, "r.cantidadiva,\n");
                    array_push($fields, "r.cantidadrenta,\n");
                }
                if ($info_invoice->credit) {
                    array_push($inners, "INNER JOIN liquidacion_compra_pagos cr ON f.id_liquidacion_compra = cr.id_liquidacion_compra\n");
                } else {
                    array_push($inners, "LEFT JOIN liquidacion_compra_pagos cr ON f.id_liquidacion_compra = cr.id_liquidacion_compra\n");
                }
            } else {
                array_push($inners, "LEFT JOIN liquidacion_compra_pagos cr ON f.id_liquidacion_compra = cr.id_liquidacion_compra\n");
                array_push($inners, "LEFT JOIN retencion_liquidacion_compra r ON r.id_liquidacion_compra = f.id_liquidacion_compra\n");
                array_push($fields, "r.cantidadiva,\n");
                array_push($fields, "r.cantidadrenta,\n");
                array_push($fields, "if(r.id_liquidacion_compra,'si','no') as retencion,\n");
            }
            if ($info_invoice->typeSearch == 1) {
                $typeSearch = ">=";
            }
            if ($info_invoice->typeSearch == 0) {
                $typeSearch = "=";
            }
            if ($info_invoice->typeSearch == -1) {
                $typeSearch = "<=";
            }
            if (is_numeric($info_invoice->totalCount) && $info_invoice->typeSearch != 2) {
                $info_invoice->totalCount = intval($info_invoice->totalCount);
                array_push($queries, "f.valor_total {$typeSearch} {$info_invoice->totalCount}\n");
            }
            if (!$info_invoice->allType) {
                if ($info_invoice->taxDocument) {
                    array_push($queries, "f.documento_tributario = 1\n");
                } else {
                    array_push($queries, "f.documento_tributario = 0\n");
                }
                if ($info_invoice->importCosts) {
                    array_push($queries, "f.gasto_import = 1\n");
                } else {
                    array_push($queries, "f.gasto_import = 0\n");
                }
            }
        }
        $queries = implode(" and ", $queries);
        $inners = implode("", $inners);
        $fields = implode("", $fields);
        $query = "
        SELECT
            f.id_liquidacion_compra as id_factcompra,
            f.fecha_emision as fech_emision,
            f.documento_tributario,
            f.subtotal_0,
            f.subtotal_12,
            f.subtotal_no_obj_iva,
            f.descuento,
            f.iva_12,
            f.estado,
            f.valor_total as total_factura,
            f.descripcion,
            tc.descrip_tipcomprob,
            f.respuesta,
            f.observacion,
            {$fields}
            p.identif_proveedor,
            p.nombre_proveedor,
            e.id_empresa,
            e.nombre_empresa,
            e.logo
        FROM liquidacion_compra f
        INNER JOIN empresa e
            ON e.id_empresa = f.id_empresa
        INNER JOIN proveedor p
            ON p.id_proveedor = f.id_proveedor
        LEFT JOIN tipo_comprobante tc
            ON tc.id_tipcomprobante = f.id_tipo_comprobante
        {$inners}
        WHERE f.id_empresa = {$request->company}  and
        {$queries} ORDER BY f.fecha_emision asc;
        ";
        $retenciones=DB::select("SELECT sum(cantidadiva) as cantidad_iva,sum(cantidadrenta) as cantidad_irf,f.id_liquidacion_compra as id_factcompra from liquidacion_compra as f
        LEFT JOIN retencion_liquidacion_compra
        on retencion_liquidacion_compra.id_liquidacion_compra=f.id_liquidacion_compra
        WHERE f.id_empresa = {$request->company}  and 
        {$queries}
        GROUP BY f.id_liquidacion_compra");

        //dd($query);
        $reporte = DB::select($query);

        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            $Reportes = new generarReportes();
            $strPDF = $Reportes->liquidacion_compra_reporte($reporte, $initial, $final,$retenciones);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
    
}
