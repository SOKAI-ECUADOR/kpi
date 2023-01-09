<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Notacredito;
use App\Models\Detalle_nota_credito;
use App\Models\NotacreditoCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\FacturaCompra;
use App\Models\Detalle_factura_compra;
use App\Models\Detalle_nota_credito_compra;
use Carbon\Carbon;
include 'class/generarPDF.php';
use DOMDocument;
use generarPDF;

use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;

use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

use App\Models\Cuentaporpagar;
use App\Models\Ctas_pagar_pagos;

include 'class/generarReportes.php';
include_once getenv("FILE_CONFIG_PHP");

use generarReportes;

class NotacreditoCompraController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $idempresa = $request->datos["id_empresa"];
        $idpe = $request->datos["id_punto_emision"];
        $ide = $request->datos["id_establecimiento"];

        if ($buscar == '') {
                $recupera = DB::select("SELECT nota_credito_compra.*, nota_credito_compra.estado AS estadof, nota_credito_compra.fmodifica AS fecha_autorizacion, empresa.*, proveedor.*, moneda.nomb_moneda AS moneda, nota_credito_compra.descuento AS descuentototal, establecimiento.codigo AS codigoes, punto_emision.codigo AS codigope, establecimiento.direccion AS direccion_establecimiento FROM nota_credito_compra INNER JOIN empresa ON empresa.id_empresa = $idempresa INNER JOIN proveedor ON proveedor.id_proveedor = nota_credito_compra.id_proveedor INNER JOIN establecimiento ON establecimiento.id_establecimiento = $ide INNER JOIN punto_emision ON punto_emision.id_punto_emision = $idpe INNER JOIN moneda ON moneda.id_moneda = empresa.id_moneda WHERE nota_credito_compra.id_empresa = $idempresa AND nota_credito_compra.modo = 1 ORDER BY nota_credito_compra.fecha_emision DESC");
        } else {
            $recupera = DB::select("SELECT nota_credito_compra.*,nota_credito_compra.estado AS estadof, nota_credito_compra.fmodifica AS fecha_autorizacion, empresa.*, proveedor.*, moneda.nomb_moneda AS moneda, nota_credito_compra.descuento AS descuentototal, establecimiento.codigo AS codigoes, punto_emision.codigo AS codigope, establecimiento.direccion AS direccion_establecimiento FROM nota_credito_compra INNER JOIN empresa ON empresa.id_empresa = $idempresa INNER JOIN proveedor ON proveedor.id_proveedor = nota_credito_compra.id_proveedor INNER JOIN establecimiento ON establecimiento.id_establecimiento = $ide INNER JOIN punto_emision ON punto_emision.id_punto_emision = $idpe INNER JOIN moneda ON moneda.id_moneda = empresa.id_moneda WHERE (proveedor.nombre_proveedor LIKE '%$buscar%' OR proveedor.email LIKE '%$buscar%' OR proveedor.telefono_prov LIKE '%$buscar%' OR proveedor.identif_proveedor LIKE '%$buscar%' OR nota_credito_compra.respuesta LIKE '%$buscar%' OR nota_credito_compra.clave_acceso LIKE '%$buscar%') AND nota_credito_compra.id_empresa = $idempresa AND nota_credito_compra.modo = 1 ORDER BY nota_credito_compra.fecha_emision DESC");
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function buscarfactura(Request $request){
        $factura = FacturaCompra::select("*")->where("descripcion", "like", '%'.$request->factura.'%')->where("id_empresa","=", $request->id_empresa)->get();
        if(count($factura)>0){
            $detalle = DB::select("SELECT dfc.*, p.cod_principal, p.cod_alterno,p.sector FROM detalle_factura_compra dfc INNER JOIN producto p ON p.id_producto=dfc.id_producto WHERE id_factura = " . $factura[0]->id_factcompra);
            $proveedor = Proveedor::select("*")->where("id_proveedor", "=", $factura[0]->id_proveedor)->get();
            $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = " . $request->id_empresa);
            return [
                'factura' => $factura[0],
                'detalle' => $detalle,
                'proveedor' => $proveedor[0],
                'empresa' => $empresa[0]
            ];
        }else{
            return 'error';
        }
    }
    public function guardar_factura(Request $request){
        ini_set('max_execution_time', 1000);
        
        $notc_total = DB::select("SELECT sum(valor_cuota) as total_factura  FROM ctas_pagar WHERE id_factura_compra = $request->id_factcompra AND id_nota_debito_compra IS NULL AND id_liquidacion_compra IS NULL ");
        if($notc_total[0]->total_factura<$request->total){
            return "valores";
        }
        $hoy = Carbon::now();
        $notacredito = new NotacreditoCompra();
        $notacredito->modo = 1;
        $notacredito->ambiente = 1;
        $notacredito->tipo_emision = 1;
        $notacredito->respuesta = "Enviado";
        $notacredito->fecha_emision = $request->factura["fecha"];
        $notacredito->autorizacionfactura= $request->factura["documento"];
        $notacredito->clave_acceso= $request->factura["autorizacion"];
        $notacredito->fechaAutorizacion= $request->factura["fecha_doc"];
        $notacredito->nro_nota_credito= $request->factura["nro_nota_credito"];
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
        $notacredito->id_proveedor = $request->proveedor;
        $notacredito->id_user = $request->usuario["id"];
        $notacredito->id_punto_emision = $request->usuario["id_punto_emision"];
        $notacredito->id_empresa = $request->usuario["id_empresa"];
        $notacredito->id_establecimiento = $request->usuario["id_establecimiento"];
        $notacredito->id_proyecto = $request->factura["proyectos"];
        $notacredito->totalpropinaf = '0.00';
        $notacredito->pp_descuento = $request->descuento;
        $notacredito->motivo = $request->factura["motivo"];
        $notacredito->id_factura_compra = $request->id_factcompra;
        $notacredito->save();

        $id = $notacredito->id_nota_credito_compra;

        //resta los pagos de nota de crédito
        $id_factcompra = $request->id_factcompra;
        $notc = DB::select("SELECT * FROM ctas_pagar WHERE id_factura_compra = $id_factcompra AND id_nota_debito_compra IS NULL AND id_liquidacion_compra IS NULL and ctas_pagar.tipo=1");
        if(count($notc)>0){
            $cont = count($notc);
            $valor = $request->total / $cont;
            $notc1 = DB::select("SELECT * FROM ctas_pagar WHERE id_factura_compra = $id_factcompra AND id_nota_debito_compra IS NULL AND id_liquidacion_compra IS NULL and ctas_pagar.tipo=1");
        }else{
            $notc1=[];
        }
        $emp=DB::select("SELECT nombre_empresa from empresa where id_empresa={$request->usuario["id_empresa"]}");
        $ce_1 = strpos($emp[0]->nombre_empresa, "C.E. FUEGOS");
        $ce_2 = strpos($emp[0]->nombre_empresa, "C.E.FUEGOS");
        $cont1 = count($notc1);
        if($cont1>0){
            
            //dd($notc1[0]->id_ctaspagar);
            $fp=DB::select("SELECT id_forma_pagos from forma_pagos where id_empresa={$request->usuario["id_empresa"]}");
            $pos0=DB::select("SELECT max(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$request->usuario["id_empresa"]}");
            if(count($pos0)>0){
                $pos=DB::select("SELECT count(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$request->usuario["id_empresa"]}");
            }else{
                $pos=[];
            }
            $conteo=1;
            if(count($pos)>0){
                $conteo=$pos[0]->posicion+1;
            }
            $valor1 = $request->total / $cont1;
            DB::update("UPDATE ctas_pagar SET valor_pagado = valor_pagado + $valor1, valor_devolucion =  $valor1 WHERE id_factura_compra =  $id_factcompra AND id_nota_debito_compra IS NULL AND id_liquidacion_compra IS NULL and ctas_pagar.tipo=1");

            $cxcp = new Ctas_pagar_pagos;
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
            $cxcp->nota_credito = 1;
            $cxcp->id_proveedor = $request->proveedor;
            $cxcp->save();
            //recupera el id de la cuenta
            $idcxcp = $cxcp->id_ctas_pagar_pagos;
            $referencia = null;

            for($i=0;$i<count($notc1);$i++){
                if($ce_1 !== false || $ce_2 !== false){
                    $referencia .= $request->factura["nro_nota_credito"] . ";" .$notc1[$i]->id_ctaspagar . ";" . number_format($valor1,2,".","") . ";ntcc:" . $id. ";";
                }else{
                    $referencia .= substr($request->factura["documento"],0,3)."-".substr($request->factura["documento"],3,3)."-".substr($request->factura["documento"],6,9) . ";" .$notc1[$i]->id_ctaspagar . ";" . number_format($valor1,2,".","") . ";ntcc:" . $id. ";";
                }
                
            }
            $ref = substr($referencia,0,-1);
            $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
            $cxcp->referencia = $ref;
            $cxcp->save();
            //return $idcxcp;
            for($i=0;$i<count($notc1);$i++){
                $cta3=DB::select("SELECT * from ctas_pagar where id_ctaspagar={$notc1[$i]->id_ctaspagar} and valor_pagado>valor_cuota and valor_devolucion is not null and id_factura_compra={$id_factcompra}");
                if(count($cta3)>0){
                    //$id_cxc=$cxc->id_ctaspagar;
                    
                    $id_ctapagar=$cta3[0]->id_ctaspagar;
                    $monto=$cta3[0]->valor_pagado-$cta3[0]->valor_cuota;
                    DB::update("UPDATE ctas_pagar set valor_pagado=valor_cuota,pago_favor={$monto} where id_ctaspagar=$id_ctapagar");
                    
                    $id_proveedor=$cta3[0]->id_proveedor;
                    $cxc2 = new Cuentaporpagar();
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
                    $cxc2->id_proveedor = $id_proveedor;
                    $cxc2->fecha_registro = $request->factura["fecha"];
                    $cxc2->fecha_pago = $request->factura["fecha"];
                    $cxc2->posicion = $conteo;
                    $cxc2->id_nota_credito_compra = $id;
                    //$cxc2->ucrea=session()->get('usuariosesion')['id'];
                    $cxc2->save();
                    $id_cxc2=$cxc2->id_ctaspagar;

                    $cxcp2 = new Ctas_pagar_pagos();
                    $cxcp2->pagos_por = "Anticipo";
                    $cxcp2->valor_seleccionado = $monto;
                    $cxcp2->valor_real_pago = $monto;
                    if(count($fp)>0){
                        $cxcp2->id_forma_pagos = $fp[0]->id_forma_pagos;
                    }
                    $cxcp2->fecha_pago = $hoy;
                    $cxcp2->fecha_registro = $request->factura["fecha"];
                    $cxcp2->id_proveedor = $id_proveedor;
                    $cxcp2->posicion = $conteo;
                    $cxcp2->nota_credito = 1;
                    $cxcp2->id_nota_credito_compra = $id;
                    //$cxcp2->anticipo = 1;
                    $cxcp2->referencia = $id_cxc2;
                    //$cxcp2->ucrea = $request->id_user;
                    $cxcp2->save();
                
                }
            }
        }

        
        $savebode = 0;
        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle_nota_credito_compra();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            if(isset($request->productos[$a]["cantidad_dsc"])){
                $detalle->cantidad_dsc = $request->productos[$a]["cantidad_dsc"];
            }
            $detalle->descuento_comp = $request->productos[$a]["descuento"];
			if($request->productos[$a]["sector"]==2){
				$detalle->descuento = number_format($request->productos[$a]["descuento"],2,".","");
				if ($request->productos[$a]["p_descuento"] == 0) {
                    $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 2, ".", "")) * $request->productos[$a]["descuento"]) / 100);
                } else {
                    $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - $request->productos[$a]["descuento"]);
                }
			}else{
				$detalle->descuento = number_format($request->productos[$a]["descuento"]/$request->productos[$a]["cantidad_dsc"]*$request->productos[$a]["cantidad"],2,".","");
				$detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - number_format($request->productos[$a]["descuento"]/$request->productos[$a]["cantidad_dsc"]*$request->productos[$a]["cantidad"],2,".",""));
			}
            
            $detalle->id_iva = $request->productos[$a]["iva"];
            $detalle->id_ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_nota_credito_compra = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
            $detalle->id_proyecto = $request->productos[$a]["proyecto"];
            $detalle->prod_factura = $request->productos[$a]["prod_factura"];
            $detalle->save();

            $cant = $request->productos[$a]["cantidad"];
            $idpb = $request->productos[$a]["id_producto_bodega"];
            if(isset($request->productos[$a]["id_producto_bodega"]) && $idpb!==null){
                DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant WHERE id_producto_bodega = $idpb");
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

                $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = ".$request->productos[$a]["id_producto_bodega"]);
                $idbodega = $reses[0]->id_bodega;

                if ($savebode == 0) {
                    $egreso = new BodegaEgreso();
                    $egreso->num_egreso = $numeroegreso;
                    $egreso->fecha_egreso = $hoy;
                    $egreso->tipo_egreso = "Egreso de Nota de crédito";
                    $egreso->observ_egreso = 'Nota de crédito Compra: '.$request->factura["documento"];
                    $egreso->id_proyecto = $request->factura["proyectos"];
                    $egreso->id_bodega = $idbodega;
                    $egreso->id_empresa = $request->usuario["id_empresa"];
                    $egreso->id_nota_credito_compra = $id;
                    $egreso->save();
                    $id_bodega_egreso = $egreso->id_bodega_egreso;
                    $savebode++;
                }

                $bed = new BodegaEgresoDetalle();
                $bed->cantidad = $request->productos[$a]["cantidad"];
                $bed->costo_unitario = $request->productos[$a]["precio"];
                $bed->costo_total = $request->productos[$a]["cantidad"]*$request->productos[$a]["precio"];
                $bed->id_bodega_egreso = $id_bodega_egreso;
                $bed->id_producto = $request->productos[$a]["id_producto"];
                $bed->id_proyecto = $request->productos[$a]["proyecto"];
                $bed->save();
            }
            

            
        }
        $facturaPDF = new generarPDF();
        $facturaPDF->notaCreditocompra($request);
        return;
    }
    public function generarPDF(Request $request){
        $facturaPDF = new generarPDF();
        $empresa=DB::select("SELECT empresa.*,establecimiento.direccion as direccion_establecimiento FROM empresa,establecimiento where establecimiento.id_empresa=empresa.id_empresa and establecimiento.id_establecimiento={$request->id_establecimiento} and empresa.id_empresa={$request->id_empresa}");
        $proveedor=DB::select("SELECT * FROM proveedor where id_proveedor={$request->id_proveedor}");
        $ntc=DB::select("SELECT * FROM nota_credito_compra where id_nota_credito_compra={$request->id_nota_credito}");
        $detalle=DB::select("SELECT detalle_nota_credito_compra.*,cod_principal FROM detalle_nota_credito_compra,producto where producto.id_producto=detalle_nota_credito_compra.id_producto and id_nota_credito_compra={$request->id_nota_credito}");
        $facturaPDF->notaCreditocompraEjemplo($empresa[0],$proveedor[0],$ntc[0],$detalle);
        //return;
    }
    public function editar_factura(Request $request){
        ini_set('max_execution_time', 1000);
        $notc_total = DB::select("SELECT sum(valor_cuota) as total_factura  FROM ctas_pagar WHERE id_factura_compra = $request->id_factcompra AND id_nota_debito_compra IS NULL AND id_liquidacion_compra IS NULL ");
        if($notc_total[0]->total_factura<$request->total){
            return "valores";
        }
        $hoy = Carbon::now();
        $notacredito = NotacreditoCompra::findOrFail($request->id);
        $notacredito->modo = 1;
        $notacredito->ambiente = 1;
        $notacredito->tipo_emision = 1;
        $notacredito->fecha_emision = $request->factura["fecha"];
        $notacredito->autorizacionfactura= $request->factura["documento"];
        $notacredito->clave_acceso= $request->factura["autorizacion"];
        $notacredito->fechaAutorizacion= $request->factura["fecha_doc"];
        $notacredito->observacion = $request->factura["observacion"];
        $notacredito->nro_nota_credito= $request->factura["nro_nota_credito"];
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
        $notacredito->id_proveedor = $request->proveedor;
        $notacredito->id_user = $request->usuario["id"];
        $notacredito->id_punto_emision = $request->usuario["id_punto_emision"];
        $notacredito->id_empresa = $request->usuario["id_empresa"];
        $notacredito->id_establecimiento = $request->usuario["id_establecimiento"];
        $notacredito->id_proyecto = $request->factura["proyectos"];
        $notacredito->totalpropinaf = '0.00';
        $notacredito->pp_descuento = $request->descuento;
        $notacredito->motivo = $request->factura["motivo"];
        $notacredito->save();

        $id = $request->id;

        $hoy = Carbon::now();
        $valor = "DELETE FROM detalle_nota_credito_compra WHERE id_nota_credito_compra = $request->id AND ";
        $exist_detalle=0;
        for ($a = 0; $a < count($request->productos); $a++) {
            if(isset($request->productos[$a]["id_detalle_nota_credito_compra"])){
                $detalle = Detalle_nota_credito_compra::findOrFail($request->productos[$a]["id_detalle_nota_credito_compra"]);
                $detalle->nombre = $request->productos[$a]["nombre"];
                $detalle->cantidad = $request->productos[$a]["cantidad"];
                $detalle->precio = $request->productos[$a]["precio"];
                if(isset($request->productos[$a]["cantidad_dsc"])){
                    $detalle->cantidad_dsc = $request->productos[$a]["cantidad_dsc"];
                }
                if($request->productos[$a]["sector"]==2){
                    $detalle->descuento = number_format($request->productos[$a]["descuento"],2,".","");
                    if ($request->productos[$a]["p_descuento"] == 0) {
                        $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 2, ".", "")) * $request->productos[$a]["descuento"]) / 100);
                    } else {
                        $detalle->total = (($request->productos[$a]["cantidad"] * number_format($request->productos[$a]["precio"], 4, ".", "")) - $request->productos[$a]["descuento"]);
                    }
                }else{
                    if(isset($request->productos[$a]["descuento_comp"])){
                        $detalle->descuento_comp = $request->productos[$a]["descuento_comp"];
                        $detalle->descuento = number_format($request->productos[$a]["descuento_comp"]/$request->productos[$a]["cantidad_dsc"]*$request->productos[$a]["cantidad"],2,".","");
                        $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - number_format($request->productos[$a]["descuento_comp"]/$request->productos[$a]["cantidad_dsc"]*$request->productos[$a]["cantidad"],2,".",""));
                    }else{
                        $detalle->descuento = $request->productos[$a]["descuento"];
                        $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                    }
                }
                
                
                
                $detalle->id_iva = $request->productos[$a]["iva"];
                $detalle->id_ice = $request->productos[$a]["ice"];
                $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                $detalle->id_nota_credito_compra = $request->id;
                $detalle->id_producto = $request->productos[$a]["id_producto"];
                $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                $detalle->id_proyecto = $request->productos[$a]["proyecto"];
                $detalle->prod_factura = $request->productos[$a]["prod_factura"];
                $detalle->save();
                if(isset($request->productos[$a]["id_producto_bodega"]) && $request->productos[$a]["id_producto_bodega"]!==null){
                    $rees = DB::select("SELECT * FROM detalle_nota_credito_compra WHERE id_detalle_nota_credito_compra = ".$request->productos[$a]["id_detalle_nota_credito_compra"]);
                    $valer = $rees[0]->cantidad;
                    $valorreal = $valer - ($request->productos[$a]["cantidad"]);

                    $cant = $request->productos[$a]["cantidad"];
                    $idpb = $request->productos[$a]["id_producto_bodega"];
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad + ($valorreal) WHERE id_producto_bodega = $idpb");

                    if($valorreal<0){
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

                        $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = ".$request->productos[$a]["id_producto_bodega"]);
                        $idbodega = $reses[0]->id_bodega;
                    }else{
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

                        $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = ".$request->productos[$a]["id_producto_bodega"]);
                        $idbodega = $reses[0]->id_bodega;
                    }
                }
                

            }else{
                $detalle = new Detalle_nota_credito_compra();
                $detalle->nombre = $request->productos[$a]["nombre"];
                $detalle->cantidad = $request->productos[$a]["cantidad"];
                $detalle->precio = $request->productos[$a]["precio"];
                if(isset($request->productos[$a]["cantidad_dsc"])){
                    $detalle->cantidad_dsc = $request->productos[$a]["cantidad_dsc"];
                }
                if(isset($request->productos[$a]["descuento_comp"])){
                    $detalle->descuento_comp = $request->productos[$a]["descuento_comp"];
                }
                $detalle->descuento = $request->productos[$a]["descuento"];
                $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
                $detalle->id_iva = $request->productos[$a]["iva"];
                $detalle->id_ice = $request->productos[$a]["ice"];
                $detalle->p_descuento = $request->productos[$a]["p_descuento"];
                $detalle->id_nota_credito_compra = $request->id;
                $detalle->id_producto = $request->productos[$a]["id_producto"];
                $detalle->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                $detalle->save();
                if(isset($request->productos[$a]["id_producto_bodega"]) && $request->productos[$a]["id_producto_bodega"]!==null){
                    $cant = $request->productos[$a]["cantidad"];
                    $idpb = $request->productos[$a]["id_producto_bodega"];
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cant WHERE id_producto_bodega = $idpb");

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

                    $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = ".$request->productos[$a]["id_producto_bodega"]);
                    $idbodega = $reses[0]->id_bodega;

                    $egreso = new BodegaEgreso();
                    $egreso->num_egreso = $numeroegreso;
                    $egreso->fecha_egreso = $hoy;
                    $egreso->tipo_egreso = "Egreso de Nota de crédito";
                    $egreso->observ_egreso = 'Nota de crédito Compra';
                    $egreso->id_proyecto = $request->factura["proyectos"];
                    $egreso->id_bodega = $idbodega;
                    $egreso->id_empresa = $request->usuario["id_empresa"];
                    $egreso->save();
                    $id_bodega_egreso = $egreso->id_bodega_egreso;

                    $bed = new BodegaEgresoDetalle();
                    $bed->cantidad = $request->productos[$a]["cantidad"];
                    $bed->costo_unitario = $request->productos[$a]["precio"];
                    $bed->costo_total = $request->productos[$a]["cantidad"]*$request->productos[$a]["precio"];
                    $bed->id_bodega_egreso = $id_bodega_egreso;
                    $bed->id_producto = $request->productos[$a]["id_producto"];
                    $bed->save();
                    // $exist_detalle=1;
                    // $idpb = $request->productos[$a]["id_producto_bodega"];
                    // $valor.="id_producto_bodega!= $idpb";
                    // if($a <count($request->productos)-1){
                    //     $valor.=" AND ";
                    // }
                }
                
            }

            
        }
        if($exist_detalle>0){
            DB::delete($valor);
        }
        //resta los pagos de nota de crédito
        $id_factcompra = $request->id_factcompra;
        $notc = DB::select("SELECT * FROM ctas_pagar WHERE id_factura_compra = $id_factcompra AND id_nota_debito_compra IS NULL AND id_liquidacion_compra IS NULL AND valor_devolucion is not null");
        if(count($notc)>0){
            $cont = count($notc);
            $valor = $request->total / $cont;
            $notc1 = DB::select("SELECT * FROM ctas_pagar WHERE id_factura_compra = $id_factcompra AND id_nota_debito_compra IS NULL AND id_liquidacion_compra IS NULL AND valor_devolucion is not null");
        }else{
            $notc1 =[];
        }
        
        $cont1 = count($notc1);
        if(count($notc1)>0){
            $valor1 = $request->total / $cont1;
            DB::update("UPDATE ctas_pagar SET valor_cuota=(select total from factura_compra_pagos where id_factura_compra=ctas_pagar.id_factura_compra and estado=2 limit 1),valor_pagado = valor_pagado -(valor_devolucion - $valor1), valor_devolucion =  $valor1 WHERE id_factura_compra =  $id_factcompra AND id_nota_debito_compra IS NULL AND id_liquidacion_compra IS NULL AND valor_devolucion is not null");
            $cta_pago=DB::select("SELECT ctas_pagar_pagos.* from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where referencia like '%ntcc:$id%' and proveedor.id_empresa={$request->usuario["id_empresa"]}");
            for($i=0;$i<count($notc1);$i++){
                if(isset($cta_pago[$i]->id_ctas_pagar_pagos)){
                    $cxcp = Ctas_pagar_pagos::find($cta_pago[$i]->id_ctas_pagar_pagos);
                    $cxcp->valor_seleccionado = $valor1;
                    $cxcp->valor_real_pago = $valor1;
                    $cxcp->fecha_pago = $hoy;
                    $referencia="";
                    $referencia= substr($request->factura["documento"],0,3)."-".substr($request->factura["documento"],3,3)."-".substr($request->factura["documento"],6,9) . ";" .$notc1[$i]->id_ctaspagar . ";" . number_format($valor1,2,".","") . ";ntcc:" . $id. ";";
                    $ref = substr($referencia,0,-1);
                    $cxcp->referencia = $ref;
                    $cxcp->save();
                }
            }
        }
        

        $facturaPDF = new generarPDF();
        $facturaPDF->notaCreditocompra($request);
        return;
    }
    public function eliminar($id,$documento,$empresa){
        ini_set('max_execution_time', 800);
        $hoy = Carbon::now();

        $result = DB::select("SELECT * FROM factura_compra WHERE descripcion LIKE '%$documento%' and id_empresa={$empresa}");
        //$id_factcompra = $result[0]->id_factcompra;
        $ntc = DB::select("SELECT * from nota_credito_compra where id_nota_credito_compra={$id}");
        $id_factcompra = $ntc[0]->id_factura_compra;
        $ntc_valor=$ntc[0]->valor_total;
        //dd("DELETE FROM ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$empresa} and ctas_pagar_pagos.referencia like '%;ntcc:{$ntc[0]->id_nota_credito_compra}%'");
        $nro_nota_credito_bodega = $ntc[0]->autorizacionfactura;
        $cta_pagar=DB::select("SELECT * from ctas_pagar where id_factura_compra={$id_factcompra} and ctas_pagar.tipo=1 and valor_cuota>valor_pagado");

        if(count($cta_pagar)==0){
            $cta_pagar=DB::select("SELECT * from ctas_pagar where id_factura_compra={$id_factcompra} and ctas_pagar.tipo=1");
        }

        $valor_cuota_anterior=0;
        for($i=0; $i<count($cta_pagar); $i++){
            $valor_cuota_anterior=$valor_cuota_anterior+$cta_pagar[$i]->valor_cuota;
        }

        $valor_por_cuota_nuevo=$ntc_valor/count($cta_pagar);

        for($i=0; $i<count($cta_pagar); $i++){
            $valor_cuota=$valor_por_cuota_nuevo;
            $cuota=$cta_pagar[$i]->valor_cuota;
            $devuelto=$cta_pagar[$i]->valor_devolucion;
            $pagado=$cta_pagar[$i]->valor_pagado;
            if($valor_cuota<=$devuelto){
                $devuelto=$devuelto-$valor_cuota;
                $pagado=$pagado-$valor_cuota;
            }
            else{
                $valor_cuota=$valor_cuota-$devuelto;
                $devuelto=0;
                $pagado=0;
                $cuota=$cuota+$valor_cuota;
            }
            DB::update("UPDATE ctas_pagar SET valor_cuota='".$cuota."', valor_devolucion='".$devuelto."', valor_pagado='".$pagado."' WHERE id_ctaspagar='".$cta_pagar[$i]->id_ctaspagar."'");
        }
        
        /*if(count($cta_pagar)==0){
            DB::update("UPDATE ctas_pagar SET valor_cuota=$valor_cuota_nuevo WHERE id_factura_compra =  $id_factcompra and ctas_pagar.tipo=1 and valor_cuota>valor_pagado");
        }
        else{
            DB::update("UPDATE ctas_pagar SET valor_cuota=$valor_cuota_nuevo WHERE id_factura_compra =  $id_factcompra and ctas_pagar.tipo=1");
        }*/
        
        $egreso = DB::select("SELECT bed.*,be.id_empresa,be.id_nota_credito_compra,be.id_bodega from bodega_egreso_detalle as bed INNER JOIN bodega_egreso as be ON be.id_bodega_egreso=bed.id_bodega_egreso WHERE be.id_empresa = {$empresa} and be.id_nota_credito_compra = {$id}");
        $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $empresa ORDER BY  num_ingreso DESC LIMIT 1;");
        $numeroegreso = "";
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$empresa} limit 1");
        //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
        if (count($numegre) == 1) {
            $dato = $numegre[0]->num_ingreso;
            $tot = $dato + 1;
            $numeroegreso = $tot;
        } else {
            $numeroegreso = 1;
        }
        $savebode = 0;
        $id_bodega_ingreso = "";
        if (count($egreso) > 0) {
            for ($i = 0; $i < count($egreso); $i++) {
                DB::update("UPDATE producto_bodega SET cantidad = cantidad + {$egreso[$i]->cantidad}, costo_total = costo_total + {$egreso[$i]->costo_total},costo_unitario=costo_total/cantidad WHERE id_producto = {$egreso[$i]->id_producto} and id_bodega={$egreso[$i]->id_bodega}");
                if ($savebode == 0) {
                    $egresos = new BodegaIngreso();
                    $egresos->num_ingreso = $numeroegreso;
                    $egresos->fecha_ingreso = $hoy;
                    $egresos->tipo_ingreso = "Egreso de Nota de crédito";
                    $egresos->observ_ingreso = 'Cancelacion Nota de crédito Compra: ' . $nro_nota_credito_bodega;
                    //$egresos->id_proyecto = $proyecto[0]->id_proyecto;
                    //if (isset($egreso[$i]->id_bodega)) {
                    $egresos->id_bodega = $egreso[$i]->id_bodega;
                    //}
                    $egresos->id_empresa = $empresa;
                    //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
                    $egresos->id_nota_credito_compra = $id;
                    $egresos->save();
                    $id_bodega_egreso = $egresos->id_bodega_ingreso;
                    $savebode++;
                }
                $bed = new BodegaIngresoDetalle();
                $bed->cantidad = $egreso[$i]->cantidad;
                $bed->costo_unitario = $egreso[$i]->costo_unitario;
                $bed->costo_total = $egreso[$i]->costo_total;
                $bed->id_bodega_ingreso = $id_bodega_egreso;
                $bed->id_producto = $egreso[$i]->id_producto;
                $bed->id_proyecto = $egreso[$i]->id_proyecto;
                $bed->id_detalle_nota_credito_compra = $egreso[$i]->id_detalle_nota_credito_compra;
                $bed->save();
            }
        }
        NotacreditoCompra::where("id_nota_credito_compra", "=", $id)->delete();
        
    }
    public function rectificar($empresa){

        $cta_pagar = DB::select("SELECT *, fcp.total as totalpagar FROM ctas_pagar cp INNER JOIN factura_compra fc ON fc.id_factcompra=cp.id_factura_compra INNER JOIN factura_compra_pagos fcp ON fcp.id_factura_compra=fc.id_factcompra WHERE fc.id_empresa={$empresa} AND fcp.id_forma_pagos=9 AND cp.tipo=1");

        for($i=0; $i<count($cta_pagar); $i++){
            $valor_cuota = round($cta_pagar[$i]->totalpagar / $cta_pagar[$i]->plazo, 2);
            if($cta_pagar[$i]->valor_cuota!=$valor_cuota){
                echo "cuota factura ".$cta_pagar[$i]->id_factura_compra.": " . round($cta_pagar[$i]->totalpagar / $cta_pagar[$i]->plazo, 2);
                echo "<br>";
                echo "calculo: ".$cta_pagar[$i]->totalpagar." / ".$cta_pagar[$i]->plazo;
                echo "<br>";
                echo "Diferencia factura ".$cta_pagar[$i]->id_factura_compra.":";
                echo "<br>";
                echo "valor actual: ".$cta_pagar[$i]->valor_cuota." valor real: ".$valor_cuota;
                echo "<br>";
                echo "<br>";
                DB::update("UPDATE ctas_pagar SET valor_cuota='".$valor_cuota."' WHERE id_ctaspagar='".$cta_pagar[$i]->id_ctaspagar."'");
            }
        }
        echo "<h1>Cuotas Niveladas</h1>";
    
    }
    public function verAsiento(Request $request,$id){
        $nota_credito=DB::select("SELECT nota_credito_compra.*,(select id_factcompra from factura_compra where id_empresa={$request->id_empresa} and descripcion like concat('%',nota_credito_compra.autorizacionfactura,'%') limit 1) as id_factura_compra,proveedor.nombre_proveedor as nombre,proveedor.tipo_identificacion,proveedor.identif_proveedor as identificacion from proveedor,nota_credito_compra where  proveedor.id_proveedor=nota_credito_compra.id_proveedor and nota_credito_compra.id_nota_credito_compra=".$id);
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'NCC-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
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
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=13 and asientos.codigo_rol={$id} and (asientos.estado='Activo' or asientos.estado is null) and  proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }


        }

        $productos=DB::select("SELECT detalle_nota_credito_compra.total,if(detalle_nota_credito_compra.id_iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
        if(producto.sector=1,linea_producto.id_plan_cuentas_compras_iva,null) as id_plan_cuentas_iva_12,
        if(producto.sector=1,linea_producto.id_plan_cuentas_compras_iva_0,null) as id_plan_cuentas_iva_0,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva is null,0,linea_producto.id_plan_cuentas_compras_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva_0 is null,0,linea_producto.id_plan_cuentas_compras_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
        concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
        null as debe,detalle_nota_credito_compra.total as haber
				FROM detalle_nota_credito_compra
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_nota_credito_compra.id_proyecto
        INNER JOIN producto
        on producto.id_producto=detalle_nota_credito_compra.id_producto
        LEFT JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        LEFT JOIN plan_cuentas
        ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
        where detalle_nota_credito_compra.id_nota_credito_compra={$id}");

        $iva_asiento=DB::select("SELECT detalle_nota_credito_compra.total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as debe,round(if(detalle_nota_credito_compra.id_iva=2,(detalle_nota_credito_compra.total)*(12/100),0),2) as haber
        from nota_credito_compra,retencion,plan_cuentas,detalle_nota_credito_compra
                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle_nota_credito_compra.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle_nota_credito_compra.id_producto
                       where nota_credito_compra.id_nota_credito_compra={$id} and nota_credito_compra.id_nota_credito_compra=detalle_nota_credito_compra.id_nota_credito_compra and retencion.descrip_retencion='IVA. en Compras' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                       ORDER BY detalle_nota_credito_compra.id_detalle_nota_credito_compra asc");
        
        $count_pagos=DB::select("SELECT factura_compra_pagos.* FROM factura_compra_pagos where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra}");
        $exist_credito=DB::select("SELECT factura_compra_pagos.* FROM factura_compra_pagos where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and estado=2");
        $exist_pagos=DB::select("SELECT factura_compra_pagos.* FROM factura_compra_pagos where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and estado=1");
        if(count($exist_credito)>0){
            $total_crd=count($count_pagos);
            $credito=DB::select("SELECT detalle_nota_credito_compra.total,if(proveedor.id_plan_cuentas is null,'no','si') as exist_plc_cl,plan_cuentas.id_plan_cuentas as id_plan_cuentas_cliente,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta_cliente,
            (select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas)) as id_plan_cuenta_grupo,
            (select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas)) as nombre_cuenta_grupo,proyecto.id_proyecto,proyecto.descripcion,if(detalle_nota_credito_compra.id_iva=2,detalle_nota_credito_compra.total+(detalle_nota_credito_compra.total*0.12),detalle_nota_credito_compra.total)/{$total_crd} as debe,null as haber,detalle_nota_credito_compra.id_detalle_nota_credito_compra
            from detalle_nota_credito_compra
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle_nota_credito_compra.id_proyecto
            INNER JOIN nota_credito_compra
            on nota_credito_compra.id_nota_credito_compra=detalle_nota_credito_compra.id_nota_credito_compra
            INNER JOIN proveedor
            on proveedor.id_proveedor=nota_credito_compra.id_proveedor
            LEFT JOIN grupo_proveedor
            on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=proveedor.id_plan_cuentas
            where detalle_nota_credito_compra.id_nota_credito_compra={$id}");
        }else{
            $credito = [];
        }
        if(count($exist_pagos)>0){
            $exist_plan_cuenta = DB::select("SELECT sum(id_plan_cuentas) as existe_plan_cuenta  from factura_compra_pagos where id_factura_compra={$nota_credito[0]->id_factura_compra} and  estado=1");
            $plc=0;
            if(count($exist_plan_cuenta)>0){
                if($exist_plan_cuenta[0]->existe_plan_cuenta>0){
                    $plc=$exist_plan_cuenta[0]->existe_plan_cuenta;
                }
                
            }
            //$exist_anticipo = DB::select("SELECT sum(anticipo) as anticipo from factura_pagos where id_factura={$nota_credito[0]->id_factura} and  estado=1");
            $total_pago=count($count_pagos);
            $pagos_nc = DB::select("SELECT detalle_nota_credito_compra.total,if({$plc}<=0,'no','si') as exist_plc_cl,
            (select plan_cuentas.id_plan_cuentas FROM factura_compra_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=factura_compra_pagos.id_plan_cuentas where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as id_plan_cuentas_cliente,
            (select CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) FROM factura_compra_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=factura_compra_pagos.id_plan_cuentas where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as nombre_cuenta_cliente,
            (select plan_cuentas.bansel FROM factura_compra_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=factura_compra_pagos.id_plan_cuentas where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as bansel_cliente,
            
            (select plan_cuentas.id_plan_cuentas FROM factura_compra_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as id_plan_cuenta_grupo,
            (select CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) FROM factura_compra_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as nombre_cuenta_grupo,
            (select plan_cuentas.bansel FROM factura_compra_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as bansel_grupo,

            (select forma_pagos.descripcion FROM factura_compra_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as nombre_pago,
            (select forma_pagos.id_forma_pagos FROM factura_compra_pagos INNER JOIN forma_pagos ON forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as id_forma_pagos,
            (select factura_compra_pagos.fecha_pago FROM factura_compra_pagos  where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as fecha_pago,
            (select factura_compra_pagos.numero_transaccion FROM factura_compra_pagos  where factura_compra_pagos.id_factura_compra={$nota_credito[0]->id_factura_compra} and factura_compra_pagos.estado=1 limit 1) as numero_transaccion,
            proyecto.id_proyecto,proyecto.descripcion,if(detalle_nota_credito_compra.id_iva=2,detalle_nota_credito_compra.total+(detalle_nota_credito_compra.total*0.12),detalle_nota_credito_compra.total)/{$total_pago} as debe,null as haber,detalle_nota_credito_compra.id_detalle_nota_credito_compra
            from detalle_nota_credito_compra
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle_nota_credito_compra.id_proyecto
            INNER JOIN nota_credito_compra
            on nota_credito_compra.id_nota_credito_compra=detalle_nota_credito_compra.id_nota_credito_compra
            INNER JOIN proveedor
            on proveedor.id_proveedor=nota_credito_compra.id_proveedor
            LEFT JOIN grupo_proveedor
            on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=proveedor.id_plan_cuentas
            where detalle_nota_credito_compra.id_nota_credito_compra={$id}");
        }else{
            $pagos_nc=[];
        }
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
            'proveedor'=>$credito,
            'proyecto'=>$proyecto[0]->id_proyecto,
            'pagos' => $pagos_nc
        ];
    }
    public function agregarAsiento(Request $request){
        NotacreditoCompra::where('id_nota_credito_compra', $request->cod_rol)->update(['contabilidad' => '1']);
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
        $asientos->id_asientos_comprobante = 13;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request){
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
        foreach ($request->creditos as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if($debe["exist_plc_cl"]=="si"){
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas_cliente"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                }else{
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuenta_grupo"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                }

                $asiento->save();
            }
        }
        foreach ($request->pagos as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["exist_plc_cl"] == "si") {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas_cliente"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    if ($debe["bansel_cliente"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                } else {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->debe = $debe["debe"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuenta_grupo"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    if ($debe["bansel_grupo"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                }

                $asiento->save();
            }
        }
    }
    public function recuperar($id,$ide){
        $factura = DB::select("SELECT * FROM nota_credito_compra WHERE id_nota_credito_compra = " . $id);
        $productos = DB::select("SELECT dnc.*, p.cod_principal,p.sector FROM detalle_nota_credito_compra dnc INNER JOIN producto p ON p.id_producto=dnc.id_producto WHERE dnc.id_nota_credito_compra = " . $id);
        $proveedor = DB::select("SELECT * FROM proveedor WHERE id_proveedor = " . $factura[0]->id_proveedor);
        $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = " . $ide);
        return [
            'factura' => $factura[0],
            'productos' => $productos,
            'proveedor' => $proveedor[0],
            'empresa' => $empresa[0]
        ];
    }
    public function reportes(Request $request){
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
                    array_push($queries, "date(f.fecha_validez) between date('{$initial}') and date('{$final}')\n");
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
        if ($request->project) {
            $info_project = json_decode($request->project, true);
            if ($info_project["id"] != 0) {
                array_push($queries, "f.id_proyecto = {$info_project["id"]}\n");
            }

        }
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

        if ($request->wayToPay) {
            $info_payment = json_decode($request->wayToPay, true);
            if ($info_payment["id"] != 0) {
                array_push($queries, "f.id_forma_pagos = {$info_payment["id"]}\n");
            }

        }
        if ($request->invoice) {
            $info_invoice = json_decode($request->invoice);
            if ($info_invoice->all == false) {
                if ($info_invoice->retention) {
                    array_push($inners, "INNER JOIN retencion_factura_comp r ON r.id_factura = f.id_factcompra\n");
                    array_push($fields, "r.cantidadiva,\n");
                    array_push($fields, "r.cantidadrenta,\n");
                } else {
                    array_push($inners, "LEFT JOIN retencion_factura_comp r ON r.id_factura = f.id_factcompra\n");
                    array_push($fields, "r.cantidadiva,\n");
                    array_push($fields, "r.cantidadrenta,\n");
                }
                if ($info_invoice->credit) {
                    array_push($inners, "INNER JOIN factura_compra_pagos cr ON f.id_factcompra = cr.id_factura_compra\n");
                } else {
                    array_push($inners, "LEFT JOIN factura_compra_pagos cr ON f.id_factcompra = cr.id_factura_compra\n");
                }
            } else {
                array_push($inners, "LEFT JOIN factura_compra_pagos cr ON f.id_factcompra = cr.id_factura_compra\n");
                array_push($inners, "LEFT JOIN retencion_factura_comp r ON r.id_factura = f.id_factcompra\n");
                array_push($fields, "r.cantidadiva,\n");
                array_push($fields, "r.cantidadrenta,\n");
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
                array_push($queries, "f.total_factura {$typeSearch} {$info_invoice->totalCount}\n");
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
            f.id_nota_credito_compra as id_factcompra,
            f.fecha_emision,
            f.autorizacionfactura as descripcion,
            f.subtotal_sin_impuesto,
            f.respuesta,
            f.subtotal_0,
            f.subtotal_12,
            f.subtotal_no_obj_iva,
            f.descuento,
            f.iva_12,
            f.valor_total as total_factura,
            p.identif_proveedor,
            p.nombre_proveedor,
            e.id_empresa,
            e.nombre_empresa,
            e.logo
        FROM nota_credito_compra f
        INNER JOIN empresa e
            ON e.id_empresa = f.id_empresa
        INNER JOIN proveedor p
            ON p.id_proveedor = f.id_proveedor
        
        
        WHERE f.id_empresa = {$request->company}   and
        {$queries} ORDER BY f.fecha_emision asc;
        ";
        $retenciones=[];

        //dd($query);
        $reporte = DB::select($query);

        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            $Reportes = new generarReportes();
            $strPDF = $Reportes->nota_credito_compra_reporte($reporte, $initial, $final,$retenciones);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
    //listar servicios
    //lista los productos generales del sistema mediante la empresa sin necesidad de listar cliente
    public function listar_servicios(Request $request)
    {
        $bs = str_replace(" ", "%", $request->buscar);
        $empresa = $request->id_empresa;
        //recupera tanto productos como servicios $res = producto, $res1 = servicio
        $res =  [];
        $res1 = DB::select("SELECT p.*, p.pvp_precio1 AS precio, ice.nombre AS nombreice, presentacion.nombre AS presentacion FROM producto p LEFT JOIN presentacion ON presentacion.id_presentacion = p.id_presentacion LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 AND p.tipo_servicio='Compra' and p.estado>0 ORDER BY p.codigo_barras DESC LIMIT 10");

        //concatena los dos array en uno solo y lista los productos dentro de la lista
        $res2 = array_merge($res1, $res);
        return $res2;
    }
}
