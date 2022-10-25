<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Notadebitocompra;
use App\Models\Cuentaporpagar;
use App\Models\Detalle_nota_debito_compra;
use App\Models\Nota_debito_compra_pagos;
use Carbon\Carbon;

include 'class/generarPDF.php';
use DOMDocument;
use generarPDF;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

class NotadebitoCompraController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;
        if ($buscar == '') {
                $recupera = DB::select("SELECT fc.descripcion as numerofacturacompra, nota_debito_compra.*, nota_debito_compra.estado as estadof, nota_debito_compra.fmodifica as fecha_autorizacion, empresa.*, proveedor.*, moneda.nomb_moneda as moneda, nota_debito_compra.descuento as descuentototal, establecimiento.codigo as codigoes, punto_emision.codigo as codigope, establecimiento.direccion as direccion_establecimiento from nota_debito_compra  left join factura_compra fc ON fc.id_factcompra=nota_debito_compra.id_factura_compra inner join empresa on empresa.id_empresa = ".$request->datos["id_empresa"]." inner join proveedor on proveedor.id_proveedor = nota_debito_compra.id_proveedor inner join establecimiento on establecimiento.id_establecimiento = ".$request->datos["id_establecimiento"]." inner join punto_emision on punto_emision.id_punto_emision = ".$request->datos["id_punto_emision"]." inner join moneda on moneda.id_moneda = empresa.id_moneda where nota_debito_compra.id_empresa = ".$request->datos["id_empresa"]." and nota_debito_compra.modo = 1 order by nota_debito_compra.fecha_emision DESC");
        } else {
            $recupera = DB::select("SELECT fc.descripcion as numerofacturacompra, nota_debito_compra.*,nota_debito_compra.estado as estadof, nota_debito_compra.fmodifica as fecha_autorizacion, empresa.*, proveedor.*, moneda.nomb_moneda as moneda, nota_debito_compra.descuento as descuentototal, establecimiento.codigo as codigoes, punto_emision.codigo as codigope, establecimiento.direccion as direccion_establecimiento from nota_debito_compra  left join factura_compra fc ON fc.id_factcompra=nota_debito_compra.id_factura_compra inner join empresa on empresa.id_empresa = ".$request->datos["id_empresa"]." inner join proveedor on proveedor.id_proveedor = nota_debito_compra.id_proveedor inner join establecimiento on establecimiento.id_establecimiento = ".$request->datos["id_establecimiento"]." inner join punto_emision on punto_emision.id_punto_emision = ".$request->datos["id_punto_emision"]." inner join moneda on moneda.id_moneda = empresa.id_moneda where (proveedor.nombre_proveedor like '%$buscar%' OR proveedor.contacto like '%$buscar%' OR proveedor.telefono_prov like '%$buscar%' OR proveedor.identif_proveedor like '%$buscar%' OR nota_debito_compra.respuesta like '%$buscar%' OR nota_debito_compra.clave_acceso like '%$buscar%') AND nota_debito_compra.id_empresa = ".$request->datos["id_empresa"]." and nota_debito_compra.modo = 1 order by nota_debito_compra.fecha_emision DESC");
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function eliminar($id){
        $del = Notadebitocompra::findOrFail($id);
        $del->estado = 0;
        $del->save();
        
        DB::delete("DELETE FROM ctas_pagar WHERE id_nota_debito_compra = $id");
    }
    public function guardar_factura(Request $request){
        $now = Carbon::now();
        $notadebito = new Notadebitocompra();
        $notadebito->modo = 1;
        $notadebito->ambiente = 1;
        $notadebito->tipo_emision = 1;
        $notadebito->respuesta = "Enviado";
        $notadebito->fecha_emision = $request->factura["fecha"];
        $notadebito->autorizacionfactura= $request->factura["autorizacion"];
        $notadebito->clave_acceso= $request->factura["autorizacion"];
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
        $notadebito->id_proveedor = $request->proveedor;
        $notadebito->id_user = $request->usuario["id"];
        $notadebito->id_punto_emision = $request->usuario["id_punto_emision"];
        $notadebito->id_empresa = $request->usuario["id_empresa"];
        $notadebito->id_establecimiento = $request->usuario["id_establecimiento"];
        $notadebito->id_proyecto = $request->factura["proyectos"];
        $notadebito->totalpropinaf = '0.00';
        $notadebito->pp_descuento = $request->descuento;
        $notadebito->id_factura_compra = $request->id_factcompra;
        $notadebito->save();

        $id = $notadebito->id_nota_debito_compra;

        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle_nota_debito_compra(); 
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]);
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->id_nota_debito_compra = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->id_nota_debito_compra = $id;
            $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            $detalle->save();

            /*$prod = Producto::findOrFail($request->productos[$a]["id_producto"]);
            $prod->
            $prod->save();*/
        }
        $idfactura = $request->id_factcompra;

        if ($request->pagos["estado"]) {
            for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                    $pag = new Nota_debito_compra_pagos(); 
                    $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                    $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                    $pag->plazo = 1;
                    $pag->unidad_tiempo = 'Días';
                    $pag->estado = 1;
                    $pag->fecha = $now;
                    $pag->id_nota_debito_compra = $id;
                    $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                    $pag->numero_transaccion = isset($request->pagos["datos"][$a]["nro_trans"]);
                    $pag->tiempos_pagos = 1;
                    $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                    $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                    $pag->save();

                    $cxc = new Cuentaporpagar();
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
                    $cxc->id_factura_compra = $idfactura;
                    $cxc->id_proveedor = $request->id_proveedor;
                    $cxc->id_nota_debito_compra = $id;
                    $cxc->save();
                }
            }
        }
        $fecharec = "";
        if ($request->creditos["estado"]) {
            $pag = new Nota_debito_compra_pagos();
            $pag->id_forma_pagos = null;
            $pag->total = $request->creditos["monto"];
            $pag->plazo = $request->creditos["plazos"];
            $pag->unidad_tiempo = $request->creditos["periodo"];
            $pag->tiempos_pagos = $request->creditos["tiempo"];
            $pag->estado = 2;
            $pag->fecha = $now;
            $pag->id_nota_debito_compra = $id;
            $pag->id_banco = null;
            $pag->id_plan_cuentas = null;
            $pag->save();

            $fd = "";
            for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                $cxc = new Cuentaporpagar();
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
                $cxc->id_factura_compra = $idfactura;
                $cxc->id_proveedor = $request->id_proveedor;
                $cxc->id_nota_debito_compra = $id;
                $cxc->save();
            }
        }
        return;
    }
    public function editar_factura(Request $request){
        $now = Carbon::now();
        $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
        $sf = $s_facturasubstr + 1;
        $idp = $request->usuario["id_punto_emision"];
        DB::update("UPDATE punto_emision SET secuencial_factura = '$sf' WHERE id_punto_emision = $idp");

        $id = $request->id;

        $notadebito = Notadebitocompra::findOrFail($id);
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
        $notadebito->save();

        DB::delete("DELETE FROM detalle_nota_debito WHERE id_nota_debito = $id");

        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle_nota_debito_compra(); 
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
                    $pag = new Nota_debito_compra_pagos(); 
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

                    $cxc = new Cuentaporpagar();
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
                    $cxc->save();
                }
            }
        }
        $fecharec = "";
        if ($request->creditos["estado"]) {
            $pag = new Nota_debito_compra_pagos();
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
                $cxc = new Cuentaporpagar();
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
                $cxc->save();
            }
        }

        $fact = Notadebitocompra::select('nota_debito.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'nota_debito.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
        ->join('empresa', 'empresa.id_empresa', '=', 'nota_debito.id_empresa')
        ->join('cliente', 'cliente.id_cliente', '=', 'nota_debito.id_cliente')
        ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
        ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
        ->leftjoin('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
        ->where("nota_debito.id_nota_debito", "=", $request->id)
        ->orderByRaw('nota_debito.id_nota_debito DESC')->get();
        return  $fact[0];
    }
    public function verAsiento(Request $request,$id){
        $nota_credito=DB::select("SELECT nota_debito_compra.*,proveedor.nombre_proveedor as nombre,proveedor.tipo_identificacion,proveedor.identif_proveedor as identificacion from proveedor,nota_debito_compra where  proveedor.id_proveedor=nota_debito_compra.id_proveedor and nota_debito_compra.id_nota_debito_compra=".$id);
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'NDC-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
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
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=14 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }
            
            
        }
        $productos=DB::select("SELECT detalle_nota_debito_compra.total,if(detalle_nota_debito_compra.id_iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
        if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva,null) as id_plan_cuentas_iva_12,
        if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva_0,null) as id_plan_cuentas_iva_0,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
        concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
        null as haber,detalle_nota_debito_compra.total as debe 
		FROM detalle_nota_debito_compra
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_debito_compra.id_proyecto
        INNER JOIN producto
        on producto.id_producto=detalle_nota_debito_compra.id_producto
        LEFT JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        LEFT JOIN plan_cuentas
        ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
        where detalle_nota_debito_compra.id_nota_debito_compra={$id}");
        $iva_asiento=DB::select("SELECT detalle_nota_debito_compra.total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as haber,round(if(detalle_nota_debito_compra.id_iva=2,(detalle_nota_debito_compra.total)*(12/100),0),2) as debe
        from nota_debito_compra,retencion,plan_cuentas,detalle_nota_debito_compra
                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle_nota_debito_compra.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle_nota_debito_compra.id_producto
                       where nota_debito_compra.id_nota_debito_compra={$id} and nota_debito_compra.id_nota_debito_compra=detalle_nota_debito_compra.id_nota_debito_compra and retencion.descrip_retencion='IVA. en Compras' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                        ORDER BY detalle_nota_debito_compra.id_detalle_nota_debito_compra asc");
        $query = "SELECT round(sum(nota_debito_compra_pagos.total)/count(nota_debito_compra_pagos.id_nota_debito_compra),2) as total,round(sum(detalle_nota_debito_compra.total)/max(nota_debito_compra.subtotal_sin_impuesto),2) as porcentaje,sum(detalle_nota_debito_compra.total) as valor_producto,round((sum(detalle_nota_debito_compra.total)/max(nota_debito_compra.subtotal_sin_impuesto)*(sum(nota_debito_compra_pagos.total)/count(nota_debito_compra_pagos.id_nota_debito_compra))),2) as haber,null as debe,detalle_nota_debito_compra.id_proyecto,proyecto.descripcion,plan_cuentas.id_plan_cuentas,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,
        if(proveedor.id_plan_cuentas is null, 'no','si') as exist_plc_cl,
        (select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as id_plan_cuentas_cl,
        (select CONCAT(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as nombre_cuenta_cl
        from nota_debito_compra_pagos
        INNER JOIN nota_debito_compra
        ON nota_debito_compra.id_nota_debito_compra=nota_debito_compra_pagos.id_nota_debito_compra
        INNER JOIN proveedor
        ON proveedor.id_proveedor=nota_debito_compra.id_proveedor
        Left JOIN grupo_proveedor
        ON grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
        Left JOIN plan_cuentas
        ON plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
        INNER JOIN detalle_nota_debito_compra
        ON detalle_nota_debito_compra.id_nota_debito_compra=nota_debito_compra.id_nota_debito_compra
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_debito_compra.id_proyecto
        where nota_debito_compra_pagos.id_nota_debito_compra={$id} and nota_debito_compra_pagos.estado=2
        GROUP BY detalle_nota_debito_compra.id_proyecto
        ORDER BY detalle_nota_debito_compra.id_proyecto asc";
        //dd($query);
        $creditos = DB::select("SELECT * from nota_debito_compra_pagos where id_nota_debito_compra={$id} and estado=2");
        if (count($creditos) > 0) {
            $cliente = DB::select($query);
        } else {
            $cliente = [];
        }
        $forma_pagos_sin_plc = DB::select("SELECT nota_debito_compra_pagos.total,round(detalle_nota_debito_compra.total/nota_debito_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_nota_debito_compra.total/nota_debito_compra.subtotal_sin_impuesto*	nota_debito_compra_pagos.total,2) as haber,null as debe,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_nota_debito_compra.id_proyecto,proyecto.descripcion,nota_debito_compra_pagos.id_forma_pagos,nota_debito_compra_pagos.fecha_pago,nota_debito_compra_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago 
        from nota_debito_compra_pagos
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=nota_debito_compra_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
        INNER JOIN nota_debito_compra
        on nota_debito_compra.id_nota_debito_compra=nota_debito_compra_pagos.id_nota_debito_compra
        INNER JOIN detalle_nota_debito_compra
        on detalle_nota_debito_compra.id_nota_debito_compra=nota_debito_compra.id_nota_debito_compra
        left JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_debito_compra.id_proyecto
        where nota_debito_compra_pagos.estado=1 and nota_debito_compra_pagos.id_nota_debito_compra={$id} and nota_debito_compra_pagos.id_plan_cuentas is null
        ORDER BY detalle_nota_debito_compra.id_proyecto asc");
        $forma_pagos_con_plc = DB::select("SELECT nota_debito_compra_pagos.total,round(detalle_nota_debito_compra.total/nota_debito_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_nota_debito_compra.total/nota_debito_compra.subtotal_sin_impuesto*	nota_debito_compra_pagos.total,2) as haber,null as debe,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_nota_debito_compra.id_proyecto,proyecto.descripcion,nota_debito_compra_pagos.id_forma_pagos,nota_debito_compra_pagos.fecha_pago,nota_debito_compra_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago 
        from nota_debito_compra_pagos
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=nota_debito_compra_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=nota_debito_compra_pagos.id_plan_cuentas
        INNER JOIN nota_debito_compra
        on nota_debito_compra.id_nota_debito_compra=nota_debito_compra_pagos.id_nota_debito_compra
        INNER JOIN detalle_nota_debito_compra
        on detalle_nota_debito_compra.id_nota_debito_compra=nota_debito_compra.id_nota_debito_compra
        left JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_debito_compra.id_proyecto
        where nota_debito_compra_pagos.estado=1 and nota_debito_compra_pagos.id_nota_debito_compra={$id}  and nota_debito_compra_pagos.id_plan_cuentas is not null
        ORDER BY detalle_nota_debito_compra.id_proyecto asc");
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
        Notadebitocompra::where('id_nota_debito_compra', $request->cod_rol)->update(['contabilidad' => '1']);
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
        $asientos->id_asientos_comprobante = 14;

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

        foreach ($request->pagos_sin_plc as $haber) {
            $asiento = new Asientos_contables_detalle();
            if (count($haber) > 0) {
                if ($haber["haber"] > 0) {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    if ($haber["fecha_pago"] !== null) {
                        if($haber["numero_transaccion"]!==0){
                            $asiento->no_documento = $haber["numero_transaccion"];
                        }
                        
                        $asiento->fecha_de_pago = $haber["fecha_pago"];
                        $asiento->id_forma_pagos = $haber["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->pagos_con_plc as $haber) {
            $asiento = new Asientos_contables_detalle();
            if (count($haber) > 0) {
                if ($haber["haber"] > 0) {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    if ($haber["fecha_pago"] !== null) {
                        if($haber["numero_transaccion"]!==0){
                            $asiento->no_documento = $haber["numero_transaccion"];
                        }
                        $asiento->fecha_de_pago = $haber["fecha_pago"];
                        $asiento->id_forma_pagos = $haber["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->pagos_anticipo as $haber) {
            $asiento = new Asientos_contables_detalle();
            if (count($haber) > 0) {
                if ($haber["haber"] > 0) {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    if ($haber["fecha_pago"] !== null) {
                        if($haber["numero_transaccion"]!==0){
                            $asiento->no_documento = $haber["numero_transaccion"];
                        }
                        $asiento->fecha_de_pago = $haber["fecha_pago"];
                        $asiento->id_forma_pagos = $haber["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                    $asiento->save();
                }
            }
        }

        foreach ($request->creditos as $haber) {
            $asiento = new Asientos_contables_detalle();
            if (count($haber) > 0) {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                if($haber["exist_plc_cl"]=='si'){
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas_cl"];
                }else{
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                }
                
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
                $asiento->save();
            }
        }
    }
    public function recuperar($id){
        $factura = DB::select("SELECT * FROM nota_debito WHERE id_nota_debito = " . $id);
        $productos = DB::select("SELECT dnc.*, p.cod_principal FROM detalle_nota_debito dnc INNER JOIN producto p ON p.id_producto=dnc.id_producto WHERE dnc.id_nota_debito = " . $id);
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
        $productos = DB::select("SELECT dnc.*, p.cod_principal FROM detalle_nota_debito dnc INNER JOIN producto p ON p.id_producto=dnc.id_producto WHERE id_nota_debito = " . $id);
        $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = " . $cuerpo[0]->id_empresa);
        return [
            'cuerpo' => $cuerpo[0],
            'productos' => $productos,
            'empresa' => $empresa[0]
        ];
    }
}
