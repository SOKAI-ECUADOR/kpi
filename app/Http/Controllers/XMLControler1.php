<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mtownsend\XmlToArray\XmlToArray;
use XMLWriter;
use App\Models\Detalle;
use App\Models\Detalle_nota_credito;
use App\Models\Detalle_nota_debito;
use App\Models\Detalle_factura_compra;
use App\Models\Pagos;
use App\Models\Cuentaporcobrar;
use App\Models\Empresa;
use App\Models\Retencion_factura;
use App\Models\Retencion_factura_comp;
use App\Models\Retencion;
use App\Models\DetalleGuiaRemision;
use App\Models\Impuesto;
use App\Models\Factura_pagos;
use App\Models\Factura;
use App\Models\Notadebito;
use Illuminate\Support\Facades\DB;

include_once getenv("FILE_CONFIG_PHP");

class XMLControler extends Controller
{
    public function efactura(Request $re){
        $det = Detalle::select("detalle.*", "producto.cod_principal", "producto.cod_alterno", "producto.total_ice", "producto.descripcion", "iva.codigo as codigo_iva", "iva.nombre as nombre_iva", "ice.codigo AS codigo_ice")
            ->join("iva", "iva.id_iva", "=", "detalle.id_iva")
            ->join("ice", "ice.id_ice", "=", "detalle.id_ice")
            ->join("producto", "producto.id_producto", "=", "detalle.id_producto")
            ->where("id_factura", "=", $re->id_factura)->get();
        //$det = DB::select("SELECT detalle.*, producto.cod_principal, producto.cod_alterno, if(detalle.valor_ice>0, ROUND(detalle.valor_ice,2), ROUND(producto.total_ice,2)) AS total_ice, producto.descripcion, iva.codigo as codigo_iva, iva.nombre as nombre_iva, ice.codigo AS codigo_ice FROM detalle INNER JOIN iva ON iva.id_iva = detalle.id_iva INNER JOIN ice ON ice.id_ice = detalle.id_ice INNER JOIN producto ON producto.id_producto = detalle.id_producto WHERE id_factura = " . $re->id_factura);

        $total_ice = 0;
        foreach($det as $t) {
            if($t["total_ice"]){
                $total_ice += $t["total_ice"] * $t["cantidad"];
            }
        }
        $result = array();
        foreach($det as $t) {
            $repeat=false;
            /*for($a=0;$a<count($result);$a++){
                if($result[$a]['codigo_iva']==$t['codigo_iva']){
                    $total = $t["total"];
                    $iva = 0;
                    if($t["total_ice"]){
                        $iva = $t["total_ice"] * $t["cantidad"];
                    }
                    $result[$a]['total'] += ($total + $iva);
                    $repeat=true;
                    break;
                }
            }*/
            if($repeat==false){
                $result[] = array('codigo_iva' => $t['codigo_iva'], 'total' => $t['total']);
            }
        }


        file_put_contents(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/factura/" . $re->clave_acceso . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $re->id_empresa . '/comprobantes/factura/' . $re->clave_acceso . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement('factura');
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");


        $xml->startElement("infoTributaria");

            $xml->startElement("ambiente");
            $xml->text($re->ambiente);
            $xml->endElement();

            $xml->startElement("tipoEmision");
            $xml->text($re->tipo_emision);
            $xml->endElement();

            $xml->startElement("razonSocial");
            $xml->text($re->razon_social);
            $xml->endElement();

            $xml->startElement("nombreComercial");
            $xml->text($re->nombre_empresa);
            $xml->endElement();

            $xml->startElement("ruc");
            $xml->text($re->ruc_empresa);
            $xml->endElement();

            $xml->startElement("claveAcceso");
            $xml->text($re->clave_acceso);
            $xml->endElement();
            $xml->startElement("codDoc");
            $xml->text('01');
            $xml->endElement();

            $xml->startElement("estab");
            $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT));
            $xml->endElement();

            $xml->startElement("ptoEmi");
            $xml->text(str_pad($re->codigope, 3, "0", STR_PAD_LEFT));
            $xml->endElement();

            $xml->startElement("secuencial");
            $xml->text(substr($re->clave_acceso, -19, -10));
            $xml->endElement();

            $xml->startElement("dirMatriz");
            $xml->text(str_replace(array("-","_", ":"), "", $re->direccion_empresa));
            $xml->endElement();

        $xml->endElement();

        $xml->startElement('infoFactura');

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text(str_replace(array("-","_", ":"), "", $re->direccion_establecimiento));
        $xml->endElement();
        if ($re->obligado_contabilidad == 0) {
            $obligado = "NO";
        } else {
            $obligado = "SI";
        }
        $xml->startElement("obligadoContabilidad");
        $xml->text($obligado);
        $xml->endElement();

        if ($re->tipo_identificacion == "Cédula de Identidad" || $re->tipo_identificacion == "Cedula de Identidad") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('05');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Ruc") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('04');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Pasaporte") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('06');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Consumidor Final") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('07');
            $xml->endElement();
        }else{
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('05');
            $xml->endElement();
        }
        $xml->startElement("razonSocialComprador");
        $xml->text(str_replace(array("-", "\"","'","","/"),"",$re->nombre));
        $xml->endElement();

        $xml->startElement("identificacionComprador");
        $xml->text($re->identificacion);
        $xml->endElement();

        $xml->startElement("direccionComprador");
        $xml->text(str_replace(array("-", "\"","'","/"),"",$re->direccion));
        $xml->endElement();

        $xml->startElement("totalSinImpuestos");
        $xml->text($re->subtotal_sin_impuesto + $total_ice);
        $xml->endElement();

        $xml->startElement("totalDescuento");
        $xml->text($re->descuentototal);
        $xml->endElement();

        $xml->startElement("totalConImpuestos");

        for ($i = 0; $i < count($result); $i++) {
            //cambiar de manera global los valores de iva, ice y irbpnr
            $xml->startElement("totalImpuesto");
            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            $xml->startElement("codigoPorcentaje");
            $xml->text($result[$i]["codigo_iva"]);
            $xml->endElement();
            $xml->startElement("baseImponible");
            $xml->text(number_format($result[$i]["total"] + $total_ice, 2, '.', ''));
            $xml->endElement();
            if ($result[$i]["codigo_iva"] == 2) {
                $xml->startElement("tarifa");
                $xml->text(12);
                $xml->endElement();
                $xml->startElement("valor");
                $xml->text(number_format((($result[$i]["total"]) * 0.12), 2, '.', ''));
                $xml->endElement();
            } else {
                $xml->startElement("tarifa");
                $xml->text(0);
                $xml->endElement();
                $xml->startElement("valor");
                $xml->text('0.00');
                $xml->endElement();
            }
            $xml->endElement();
        }

        /*$detice = Detalle::select("detalle.*", "producto.cod_principal", "producto.cod_alterno", "producto.descripcion", "producto.total_ice", "ice.codigo as codigo_ice", "ice.nombre as nombre_ice", "ice.valor as valorice")
                    ->join("producto", "producto.id_producto", "=", "detalle.id_producto")
                    ->leftjoin("ice", "ice.id_ice", "=", "producto.ice")
                    ->where("detalle.id_factura", "=", $re->id_factura)->where("producto.total_ice", ">", 0)->groupBy("SUBSTR(codigo,1,4)")->get();
        */
        //$detices = DB::select("SELECT SUM(detalle.total) AS total, IF(detalle.valor_ice>0, ROUND(SUM(detalle.cantidad * detalle.valor_ice), 2), ROUND(SUM(detalle.cantidad * producto.total_ice), 2)) AS valor, SUBSTR(ice.codigo,1,4) as codigo_ice FROM detalle INNER JOIN producto ON producto.id_producto = detalle.id_producto INNER JOIN ice ON ice.id_ice = producto.ice WHERE detalle.id_factura = ". $re->id_factura . " AND producto.total_ice > 0 GROUP BY SUBSTR(codigo,1,4)");
        $detices = DB::select("SELECT SUM(detalle.total) AS total, ROUND(SUM(detalle.cantidad * producto.total_ice), 2) AS valor, SUBSTR(ice.codigo,1,4) as codigo_ice FROM detalle INNER JOIN producto ON producto.id_producto = detalle.id_producto INNER JOIN ice ON ice.id_ice = producto.ice WHERE detalle.id_factura = ". $re->id_factura . " AND producto.total_ice > 0 GROUP BY SUBSTR(codigo,1,4)");
        for ($i = 0; $i < count($detices); $i++) {
            $xml->startElement("totalImpuesto");
                $xml->startElement('codigo');
                $xml->text(3);
                $xml->endElement();

                $xml->startElement("codigoPorcentaje");
                $xml->text($detices[$i]->codigo_ice);
                $xml->endElement();

                $xml->startElement("baseImponible");
                $xml->text(number_format($detices[$i]->total + $detices[$i]->valor, 2, '.', ''));
                $xml->endElement();

                $xml->startElement("tarifa");
                $xml->text(0);
                $xml->endElement();

                $xml->startElement("valor");
                $xml->text(number_format(($detices[$i]->valor), 2, '.', ''));
                $xml->endElement();

            $xml->endElement();
        }

        $xml->endElement();
        if ($re->propina > 0) {
            $xml->startElement("propina");
            $xml->text($re->propina);
            $xml->endElement();
        } else {
            $xml->startElement("propina");
            $xml->text("0.00");
            $xml->endElement();
        }

        $xml->startElement("importeTotal");
        $xml->text($re->valor_total);
        $xml->endElement();

        $xml->startElement("moneda");
        $xml->text(strtoupper($re->moneda));
        $xml->endElement();

        $xml->startElement("pagos");
        $cxc = Factura_pagos::select("factura_pagos.*", "forma_pagos_sri.codigo as codigopagos")
            ->leftJoin("forma_pagos", "forma_pagos.id_forma_pagos", "=", "factura_pagos.id_forma_pagos")
            ->leftJoin("forma_pagos_sri", "forma_pagos_sri.id_forma_pagos_sri", "=", "forma_pagos.id_forma_pagos_sri")
            ->where("id_factura", "=", $re->id_factura)->get();

        for ($i = 0; $i < count($cxc); $i++) {
            $xml->startElement("pago");
                if(isset($cxc[$i]["codigopagos"])){
                    $xml->startElement("formaPago");
                        $xml->text($cxc[$i]["codigopagos"]);
                    $xml->endElement();
                }else{
                    $xml->startElement("formaPago");
                        $xml->text('20');
                    $xml->endElement();
                }
                $xml->startElement("total");
                    $xml->text($cxc[$i]["total"]);
                $xml->endElement();
                $xml->startElement("plazo");
                    $xml->text($cxc[$i]["plazo"]);
                $xml->endElement();
                $xml->startElement("unidadTiempo");
                    $xml->text($cxc[$i]["unidad_tiempo"]);
                $xml->endElement();
            $xml->endElement();
        }

        $rfi = retencion_factura::where("id_factura", "=", $re->id_factura)->get();
        for ($i = 0; $i < count($rfi); $i++) {
            $xml->startElement("pago");
            $xml->startElement("formaPago");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("total");
            $xml->text($rfi[$i]["cantidadiva"]);
            $xml->endElement();
            $xml->startElement("plazo");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("unidadTiempo");
            $xml->text("Días");
            $xml->endElement();
            $xml->endElement();
        }
        $rfr = retencion_factura::where("id_factura", "=", $re->id_factura)->get();
        for ($i = 0; $i < count($rfr); $i++) {
            $xml->startElement("pago");
            $xml->startElement("formaPago");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("total");
            $xml->text($rfr[$i]["cantidadrenta"]);
            $xml->endElement();
            $xml->startElement("plazo");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("unidadTiempo");
            $xml->text("Días");
            $xml->endElement();
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endElement();
        $xml->startElement('detalles');
        for ($i = 0; $i < count($det); $i++) {
            $xml->startElement('detalle');
            $xml->startElement('codigoPrincipal');
            $xml->text($det[$i]["cod_principal"]);
            $xml->endElement();
            if ($det[$i]["cod_alterno"]) {
                $xml->startElement('codigoAuxiliar');
                $xml->text($det[$i]["cod_alterno"]);
                $xml->endElement();
            }
            $xml->startElement('descripcion');
            $xml->text(str_replace(array('/', '"', ",", ":", "¨"), array(' ', '',' '," ",""), $det[$i]["nombre"]));
            $xml->endElement();
            $xml->startElement('cantidad');
            $xml->text($det[$i]["cantidad"]);
            $xml->endElement();
            $xml->startElement('precioUnitario');
            $xml->text($det[$i]["precio"]);
            $xml->endElement();

            if ($det[$i]["descuento"]) {
                $xml->startElement('descuento');
                if($det[$i]["p_descuento"] == 1){
                    $xml->text(number_format($det[$i]["descuento"], 2, '.', ''));
                }else{
                    if(isset($det[$i]["descuento"])){
                        $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"])/100), 2, '.', ''));
                    }else{
                        $xml->text(0);
                    }
                }
                $xml->endElement();
            } else {
                $xml->startElement('descuento');
                $xml->text(0);
                $xml->endElement();
            }
            $xml->startElement('precioTotalSinImpuesto');
            if($det[$i]["p_descuento"] == 1){
                $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) + $total_ice, 2, '.', ''));
            }else{
                if(isset($det[$i]["descuento"])){
                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - (($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"])/100) + $total_ice, 2, '.', ''));
                }else{
                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) + $total_ice, 2, '.', ''));
                }
            }
            $xml->endElement();
            /*if ($det[$i]["descripcion"]) {
                $xml->startElement('detallesAdicionales');
                    $xml->writeRaw('<detAdicional nombre="descripcion" valor="'.$det[$i]["descripcion"].'"></detAdicional>');
                $xml->endElement();
            }*/
            $xml->startElement('impuestos');

                if($det[$i]["id_iva"]){
                    $xml->startElement('impuesto');
                        $xml->startElement('codigo');
                        $xml->text(2);
                        $xml->endElement();

                        $xml->startElement("codigoPorcentaje");
                        $xml->text($det[$i]["codigo_iva"]);
                        $xml->endElement();

                        if ($det[$i]["codigo_iva"] == 2) {
                            $xml->startElement("tarifa");
                            $xml->text(12);
                            $xml->endElement();
                        } else {
                            $xml->startElement("tarifa");
                            $xml->text(0);
                            $xml->endElement();
                        }
                        $xml->startElement('baseImponible');
                        if($det[$i]["p_descuento"] == 1){
                            $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) + ($det[$i]["total_ice"] * $det[$i]["cantidad"]), 2, '.', ''));
                        }else{
                            if(isset($det[$i]["descuento"])){
                                $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"]) - (($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"])/100)) + ($det[$i]["total_ice"] * $det[$i]["cantidad"]), 2, '.', ''));
                            }else{
                                $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) + ($det[$i]["total_ice"] * $det[$i]["cantidad"]), 2, '.', ''));
                            }
                        }

                        /*if($det[$i]["p_descuento"] == 1){
                            $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]), 2, '.', ''));
                        }else{
                            if(isset($det[$i]["descuento"])){
                                $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"]) - (($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"])/100)), 2, '.', ''));
                            }else{
                                $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]), 2, '.', ''));
                            }
                        }*/

                        $xml->endElement();
                        if ($det[$i]["codigo_iva"] == 2) {
                            $xml->startElement("valor");
                            $xml->text(number_format(( ((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) + ($det[$i]["total_ice"] * $det[$i]["cantidad"])) * 0.12), 2, '.', ''));
                            $xml->endElement();
                        } else {
                            $xml->startElement("valor");
                            $xml->text(0.00);
                            $xml->endElement();
                        }
                    $xml->endElement();
                }

                if($det[$i]["id_ice"]){
                    if($det[$i]["total_ice"]){
                        $xml->startElement('impuesto');
                            $xml->startElement('codigo');
                            $xml->text(3);
                            $xml->endElement();

                            $xml->startElement("codigoPorcentaje");
                            $xml->text(substr($det[$i]["codigo_ice"],0,4));
                            $xml->endElement();

                            $xml->startElement("tarifa");
                            $xml->text(0);
                            $xml->endElement();

                            $xml->startElement('baseImponible');
                            if($det[$i]["p_descuento"] == 1){
                                $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"] + $total_ice, 2, '.', ''));
                            }else{
                                if(isset($det[$i]["descuento"])){
                                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - (($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"])/100) + $total_ice, 2, '.', ''));
                                }else{
                                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) + $total_ice, 2, '.', ''));
                                }
                            }
                            $xml->endElement();

                            $xml->startElement("valor");
                            if($det[$i]["total_ice"]){
                                $xml->text($det[$i]["total_ice"] * $det[$i]["cantidad"]);
                            }else{
                                $xml->text('0.00');
                            }
                            $xml->endElement();
                        $xml->endElement();
                    }
                }

            $xml->endElement();
            $xml->endElement();
        }
        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        $xml->endElement();
        $xml->startElement('infoAdicional');
            if($re->direccion){
                $xml->startElement('campoAdicional');
                $xml->writeAttribute("nombre", "Dirección");
                $xml->text(str_replace(array("-", "\"","'",""),"",$re->direccion));
                $xml->endElement();
            }
            if($re->telefono){
                $xml->startElement('campoAdicional');
                $xml->writeAttribute("nombre", "Teléfono");
                $xml->text(str_replace(array('/', "(", ")"), array(' ', '', ''), $re->telefono));
                $xml->endElement();
            }
            if($re->email){
                $xml->startElement('campoAdicional');
                $xml->writeAttribute("nombre", "Email");
                $xml->text($re->email);
                $xml->endElement();
            }

            if(isset($recupera[0]->orden_compra)){
                $xml->startElement('campoAdicional');
                $xml->writeAttribute("nombre", "OrdenCompra");
                $xml->text($recupera[0]->orden_compra);
                $xml->endElement();
            }
            if(isset($recupera[0]->migo_factura)){
                $xml->startElement('campoAdicional');
                $xml->writeAttribute("nombre", "Migo");
                $xml->text($recupera[0]->migo_factura);
                $xml->endElement();
            }
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();
        return ["recupera" => $recupera[0]];
    }
    public function e_guia(Request $re){
        file_put_contents(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/guia/" . $re->clave_acceso . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $re->id_empresa . '/comprobantes/guia/' . $re->clave_acceso . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
            $xml->startElement("guiaRemision");
                $xml->writeAttribute("id", "comprobante");
                $xml->writeAttribute("version", "1.0.0");
                //infoTributaria

                $xml->startElement("infoTributaria");

                    $xml->startElement("ambiente");
                    $xml->text(str_replace(array("-", "\"","'","","/",":"),"",$re->ambiente));
                    $xml->endElement();

                    $xml->startElement("tipoEmision");
                    $xml->text($re->tipo_emision);
                    $xml->endElement();

                    $xml->startElement("razonSocial");
                    $xml->text($re->razon_social);
                    $xml->endElement();

                    $xml->startElement("nombreComercial");
                    $xml->text($re->nombre_empresa);
                    $xml->endElement();

                    $xml->startElement("ruc");
                    $xml->text($re->ruc_empresa);
                    $xml->endElement();

                    $xml->startElement("claveAcceso");
                    $xml->text($re->clave_acceso);
                    $xml->endElement();
                    $xml->startElement("codDoc");
                    $xml->text('06');
                    $xml->endElement();

                    $xml->startElement("estab");
                    $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT));
                    $xml->endElement();

                    $xml->startElement("ptoEmi");
                    $xml->text(str_pad($re->codigope, 3, "0", STR_PAD_LEFT));
                    $xml->endElement();

                    $xml->startElement("secuencial");
                    $xml->text(substr($re->clave_acceso, -19, -10));
                    $xml->endElement();

                    $xml->startElement("dirMatriz");
                    $xml->text($re->direccion_empresa);
                    $xml->endElement();

                $xml->endElement();

                $xml->startElement("infoGuiaRemision");

                    $xml->startElement("dirEstablecimiento");
                    $xml->text($re->direccion_empresa);
                    $xml->endElement();

                    $xml->startElement("dirPartida");
                    $xml->text(str_replace(array("-", "\"","'","","/",":"),"",$re->direccion));
                    $xml->endElement();

                    $xml->startElement("razonSocialTransportista");
                    $xml->text(str_replace(array("-", "\"","'","","/",":"),"",$re->razon_social_tr));
                    $xml->endElement();

                    if ($re->tipo_identificacion_tr == "Cédula de Identidad") {
                        $xml->startElement("tipoIdentificacionTransportista");
                        $xml->text('05');
                        $xml->endElement();
                    } else if ($re->tipo_identificacion_tr == "Ruc") {
                        $xml->startElement("tipoIdentificacionTransportista");
                        $xml->text('04');
                        $xml->endElement();
                    } else if ($re->tipo_identificacion_tr == "Pasaporte") {
                        $xml->startElement("tipoIdentificacionTransportista");
                        $xml->text('06');
                        $xml->endElement();
                    } else if ($re->tipo_identificacion_tr == "Consumidor Final") {
                        $xml->startElement("tipoIdentificacionTransportista");
                        $xml->text('07');
                        $xml->endElement();
                    }

                    $xml->startElement("rucTransportista");
                    $xml->text($re->identificacion_tr);
                    $xml->endElement();

                    if ($re->obligado_contabilidad == 0) {
                        $obligado = "NO";
                    } else {
                        $obligado = "SI";
                    }
                    $xml->startElement("obligadoContabilidad");
                    $xml->text($obligado);
                    $xml->endElement();
                    $xml->startElement("fechaIniTransporte");
                    $xml->text(date('d/m/Y', strtotime($re->fecha_inicio_tr)));
                    $xml->endElement();

                    $xml->startElement("fechaFinTransporte");
                    $xml->text(date('d/m/Y', strtotime($re->fecha_fin_tr)));
                    $xml->endElement();

                    $xml->startElement("placa");
                    $xml->text($re->placa_tr);
                    $xml->endElement();

                $xml->endElement();

                $xml->startElement("destinatarios");
                    $xml->startElement("destinatario");

                        $xml->startElement("identificacionDestinatario");
                        $xml->text($re->identificacion);
                        $xml->endElement();

                        $xml->startElement("razonSocialDestinatario");
                        $xml->text($re->nombre);
                        $xml->endElement();

                        $xml->startElement("dirDestinatario");
                        $xml->text(str_replace(array("-", "\"","'","","/",":"),"",$re->direccion));
                        $xml->endElement();

                        $xml->startElement("motivoTraslado");
                        $xml->text($re->motivo_translado_tr);
                        $xml->endElement();
                        if($re->doc_aduanero_tr){
                            $xml->startElement("docAduaneroUnico");
                            $xml->text($re->doc_aduanero_tr);
                            $xml->endElement();
                        }
                        if($re->cod_establecimiento_tr){
                            $xml->startElement("codEstabDestino");
                            $xml->text($re->cod_establecimiento_tr);
                            $xml->endElement();
                        }
                        if($re->ruta_tr){
                            $xml->startElement("ruta");
                            $xml->text($re->ruta_tr);
                            $xml->endElement();
                        }
                        if($re->cod_sustento_tr){
                            $xml->startElement("codDocSustento");
                            $xml->text(str_pad($re->cod_sustento_tr, 2, "0", STR_PAD_LEFT));
                            $xml->endElement();
                        }

                        $xml->startElement("numDocSustento");
                        $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT).'-'.str_pad($re->codigope, 3, "0", STR_PAD_LEFT)."-"."000000001");
                        $xml->endElement();
                        $rand = rand(000000001, 9999999999);
                        if($re->num_aut_sustento_tr){
                            $xml->startElement("numAutDocSustento");
                            $xml->text($rand);
                            $xml->endElement();
                        }

                        $xml->startElement("fechaEmisionDocSustento");
                        $xml->text(date('d/m/Y', strtotime($re->fecha_inicio_tr)));
                        $xml->endElement();

                        $xml->startElement("detalles");

                            $det = DetalleGuiaRemision::select("*")->where("id_guia_remision", "=", $re->id_guia)->get();
                            for ($i = 0; $i < count($det); $i++) {
                                $xml->startElement("detalle");

                                    $xml->startElement("codigoInterno");
                                    $xml->text($det[$i]["codigo_interno"]);
                                    $xml->endElement();

                                    $xml->startElement("descripcion");
                                    $xml->text(str_replace(array("-", "\"","'","_","/",":"), "", $det[$i]["descripcion"]));
                                    $xml->endElement();

                                    $xml->startElement("cantidad");
                                    $xml->text($det[$i]["cantidad"]);
                                    $xml->endElement();

                                $xml->endElement();
                            }

                        $xml->endElement();

                    $xml->endElement();
                $xml->endElement();
                $xml->startElement("infoAdicional");
                    if($re->email){
                        $xml->startElement("campoAdicional");
                        $xml->writeAttribute("nombre", "email");
                        $xml->text($re->email);
                        $xml->endElement();
                    }
                    if($re->direccion){
                        $xml->startElement("campoAdicional");
                        $xml->writeAttribute("nombre", "direccion");
                        $xml->text(str_replace(array("-", "\"","'","_","/",":"), "", $re->direccion));
                        $xml->endElement();
                    }
                $xml->endElement();
            $xml->endElement();
        $xml->endDocument();
    }
    public function e_comproretenc(Request $re){
        file_put_contents(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/retencioncompra/" . $re->nro_autorizacion . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/retencioncompra/" . $re->nro_autorizacion . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement("comprobanteRetencion");
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");
        //infoTributaria
        $xml->startElement("infoTributaria");

            $xml->startElement("ambiente");
            $xml->text($re->ambiente);
            $xml->endElement();

            $xml->startElement("tipoEmision");
            $xml->text($re->tipo_emision);
            $xml->endElement();

            $xml->startElement("razonSocial");
            $xml->text($re->razon_social);
            $xml->endElement();

            $xml->startElement("nombreComercial");
            $xml->text($re->nombre_empresa);
            $xml->endElement();

            $xml->startElement("ruc");
            $xml->text($re->ruc_empresa);
            $xml->endElement();

            $xml->startElement("claveAcceso");
            $xml->text($re->observacion);
            $xml->endElement();
            $xml->startElement("codDoc");
            $xml->text('07');
            $xml->endElement();

            $xml->startElement("estab");
            $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT));
            $xml->endElement();

            $xml->startElement("ptoEmi");
            $xml->text(str_pad($re->codigope, 3, "0", STR_PAD_LEFT));
            $xml->endElement();

            $xml->startElement("secuencial");
            $xml->text(substr($re->observacion, -19, -10));
            $xml->endElement();

            $xml->startElement("dirMatriz");
            $xml->text($re->direccion_empresa);
            $xml->endElement();

        $xml->endElement();
        //infoCompRetencion
        $xml->startElement("infoCompRetencion");

            $xml->startElement("fechaEmision");
            $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
            $xml->endElement();

            $xml->startElement("dirEstablecimiento");
            $xml->text($re->direccion_prov);
            $xml->endElement();

            if ($re->obligado_contabilidad == 0) {
                $obligado = "NO";
            } else {
                $obligado = "SI";
            }

            $xml->startElement("obligadoContabilidad");
            $xml->text($obligado);
            $xml->endElement();

            if ($re->tipo_identificacion == "Cedula") {
                $xml->startElement("tipoIdentificacionSujetoRetenido");
                $xml->text('05');
                $xml->endElement();
            } else if ($re->tipo_identificacion == "Ruc") {
                $xml->startElement("tipoIdentificacionSujetoRetenido");
                $xml->text('04');
                $xml->endElement();
            } else if ($re->tipo_identificacion == "Pasaporte") {
                $xml->startElement("tipoIdentificacionSujetoRetenido");
                $xml->text('06');
                $xml->endElement();
            } else if ($re->tipo_identificacion == "Consumidor Final") {
                $xml->startElement("tipoIdentificacionSujetoRetenido");
                $xml->text('07');
                $xml->endElement();
            } else if ($re->tipo_identificacion == "Extranjero") {
                $xml->startElement("tipoIdentificacionSujetoRetenido");
                $xml->text('05');
                $xml->endElement();
            }

            $xml->startElement("razonSocialSujetoRetenido");
            $xml->text(str_replace('\"',"",$re->nombre_proveedor));
            $xml->endElement();

            $xml->startElement("identificacionSujetoRetenido");
            $xml->text($re->identif_proveedor);
            $xml->endElement();

            $xml->startElement("periodoFiscal");
            $xml->text(date('m/Y', strtotime($re->fech_emision)));
            $xml->endElement();

        $xml->endElement();
        //impuestos
        $xml->startElement("impuestos");

        $det = Retencion_factura_comp::addSelect([
            'codigosri' => Impuesto::select('cod_imp')
                ->whereColumn('id_imp', 'retencion.id_impuesto')
        ])
        ->select("retencion_factura_comp.*")
        ->join("retencion", "retencion.id_retencion", "=", "retencion_factura_comp.id_retencion_iva")
        ->where("retencion_factura_comp.id_factura", "=", $re->id_factcompra)->get();

        for ($i = 0; $i < count($det); $i++) {
            if (strlen($det[$i]["id_retencion_iva"]) >= 1) {
                $xml->startElement("impuesto");

                    $xml->startElement("codigo");
                        $xml->text(2);
                    $xml->endElement();

                    if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 0) {
                        $xml->startElement("codigoRetencion");
                            $xml->text(7);
                        $xml->endElement();
                    } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 10) {
                        $xml->startElement("codigoRetencion");
                            $xml->text(9);
                        $xml->endElement();
                    } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 20) {
                        $xml->startElement("codigoRetencion");
                            $xml->text(10);
                        $xml->endElement();
                    } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 30) {
                        $xml->startElement("codigoRetencion");
                            $xml->text(1);
                        $xml->endElement();
                    } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 50) {
                        $xml->startElement("codigoRetencion");
                            $xml->text(11);
                        $xml->endElement();
                    } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 70) {
                        $xml->startElement("codigoRetencion");
                            $xml->text(2);
                        $xml->endElement();
                    } else {
                        $xml->startElement("codigoRetencion");
                            $xml->text(3);
                        $xml->endElement();
                    }

                    $xml->startElement("baseImponible");
                        $xml->text(number_format(($det[$i]["cantidadiva"] * 100) / str_replace("%", "", $det[$i]["porcentajeiva"]), 2, '.', ''));
                    $xml->endElement();

                    $xml->startElement("porcentajeRetener");
                        $xml->text(str_replace("%", "", $det[$i]["porcentajeiva"]));
                    $xml->endElement();

                    $xml->startElement("valorRetenido");
                        $xml->text(number_format($det[$i]["cantidadiva"], 2, '.', ''));
                    $xml->endElement();

                    $xml->startElement("codDocSustento");
                        $xml->text('01');
                    $xml->endElement();

                    $xml->startElement("numDocSustento");
                        $xml->text($re->descripcion);
                    $xml->endElement();

                    $xml->startElement("fechaEmisionDocSustento");
                        $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
                    $xml->endElement();

                $xml->endElement();
            }
        }
        $det = Retencion_factura_comp::addSelect([
            'codigosri' => Impuesto::select('cod_imp')
                ->whereColumn('id_imp', 'retencion.id_impuesto')
        ])
        ->select("retencion_factura_comp.*", "impuesto.cod_imp", "factura_compra.descripcion")
        ->join("retencion", "retencion.id_retencion", "=", "retencion_factura_comp.id_retencion_renta")
        ->join("impuesto", "impuesto.id_imp", "=", "retencion.id_impuesto")
        ->join("factura_compra","factura_compra.id_factcompra","=","retencion_factura_comp.id_factura")
        ->where("retencion_factura_comp.id_factura", "=", $re->id_factcompra)->get();

        for ($i = 0; $i < count($det); $i++) {
            if (strlen($det[$i]["id_retencion_renta"]) >= 1) {
                $xml->startElement("impuesto");

                    $xml->startElement("codigo");
                        $xml->text(1);
                    $xml->endElement();

                    $xml->startElement("codigoRetencion");
                        $xml->text($det[$i]["cod_imp"]);
                    $xml->endElement();

                    $xml->startElement("baseImponible");
                        $xml->text($det[$i]["baserenta"]);
                    $xml->endElement();

                    $xml->startElement("porcentajeRetener");
                        $xml->text(str_replace("%", "", $det[$i]["porcentajerenta"]));
                    $xml->endElement();

                        $xml->startElement("valorRetenido");
                    $xml->text(number_format($det[$i]["cantidadrenta"], 2, '.', ''));
                    $xml->endElement();

                    $xml->startElement("codDocSustento");
                        $xml->text('01');
                    $xml->endElement();

                    $xml->startElement("numDocSustento");
                        $xml->text($re->descripcion);
                    $xml->endElement();

                    $xml->startElement("fechaEmisionDocSustento");
                        $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
                    $xml->endElement();

                $xml->endElement();
            }
        }

        $xml->endElement();
        //infoAdicional
        $xml->startElement("infoAdicional");
        if (isset($re->direccion_prov)) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Dirección");
            $xml->text($re->direccion_prov);
            $xml->endElement();
        }
        if (strlen($re->telefono_prov) >= 1) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text($re->telefono_prov);
            $xml->endElement();
        }
        if (isset($re->emailpr)) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Email");
            $xml->text($re->emailpr);
            $xml->endElement();
        }
        if (strlen($re->nrcasa) >= 1) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Numero de casa");
            $xml->text($re->nrcasa);
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endElement(); //fin comprobanteRetencion
        $xml->endDocument();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
    public function enotacredito(Request $re){
        file_put_contents(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/notacredito/" . $re->clave_acceso . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $re->id_empresa . '/comprobantes/notacredito/' . $re->clave_acceso . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement('notaCredito');
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");

        $xml->startElement("infoTributaria");

            $xml->startElement("ambiente");
            $xml->text($re->ambiente);
            $xml->endElement();

            $xml->startElement("tipoEmision");
            $xml->text($re->tipo_emision);
            $xml->endElement();

            $xml->startElement("razonSocial");
            $xml->text($re->razon_social);
            $xml->endElement();

            $xml->startElement("nombreComercial");
            $xml->text($re->nombre_empresa);
            $xml->endElement();

            $xml->startElement("ruc");
            $xml->text($re->ruc_empresa);
            $xml->endElement();

            $xml->startElement("claveAcceso");
            $xml->text($re->clave_acceso);
            $xml->endElement();
            $xml->startElement("codDoc");
            $xml->text('04');
            $xml->endElement();

            $xml->startElement("estab");
            $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT));
            $xml->endElement();

            $xml->startElement("ptoEmi");
            $xml->text(str_pad($re->codigope, 3, "0", STR_PAD_LEFT));
            $xml->endElement();

            $xml->startElement("secuencial");
            $xml->text(substr($re->clave_acceso, -19, -10));
            $xml->endElement();

            $xml->startElement("dirMatriz");
            $xml->text($re->direccion_empresa);
            $xml->endElement();

        $xml->endElement();
        //INFORNOTACREDICO
        $xml->startElement('infoNotaCredito');

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text($re->direccion_establecimiento);
        $xml->endElement();

        if ($re->tipo_identificacion == "Ruc") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('04');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Pasaporte") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('06');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Consumidor Final") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('07');
            $xml->endElement();
        }else{
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('05');
            $xml->endElement();
        }
        $xml->startElement("razonSocialComprador");
        $xml->text($re->nombre);
        $xml->endElement();

        $xml->startElement("identificacionComprador");
        $xml->text($re->identificacion);
        $xml->endElement();

        if ($re->obligado_contabilidad == 0) {
            $obligado = "NO";
        } else {
            $obligado = "SI";
        }
        $xml->startElement("obligadoContabilidad");
        $xml->text($obligado);
        $xml->endElement();

        $xml->startElement("codDocModificado");
        $xml->text("01");
        $xml->endElement();

        $xml->startElement("numDocModificado");
        $xml->text(substr($re->autorizacionfactura,0,3) . "-" . substr($re->autorizacionfactura,3,3) . "-" . substr($re->autorizacionfactura,6,9));
        $xml->endElement();
        $xml->startElement("fechaEmisionDocSustento");
        $xml->text(date('d/m/Y', strtotime($re->fechaAutorizacion)));
        $xml->endElement();
        $xml->startElement("totalSinImpuestos");
        $xml->text($re->subtotal_sin_impuesto);
        $xml->endElement();
        $xml->startElement("valorModificacion");
        $xml->text($re->valor_total);
        $xml->endElement();
        $xml->startElement("moneda");
        $xml->text(strtoupper($re->moneda));
        $xml->endElement();
        //totalConImpuestos1
        $xml->startElement('totalConImpuestos');
            $det = Detalle_nota_credito::select("detalle_nota_credito.*", "producto.cod_principal", "producto.cod_alterno", "producto.descripcion","iva.codigo as codigo_iva")
                    ->join("producto", "producto.id_producto", "=", "detalle_nota_credito.id_producto")
                    ->join("iva", "iva.id_iva", "=", "detalle_nota_credito.id_iva")
                    ->where("detalle_nota_credito.id_nota_credito", "=", $re->id_nota_credito)->get();

            for ($i = 0; $i < count($det); $i++) {
                $xml->startElement("totalImpuesto");
                    $xml->startElement('codigo');
                    $xml->text(2);
                    $xml->endElement();

                    $xml->startElement("codigoPorcentaje");
                    $xml->text($det[$i]["codigo_iva"]);
                    $xml->endElement();

                    $xml->startElement("baseImponible");
                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"], 2, '.', ''));
                    $xml->endElement();
                    if ($det[$i]["codigo_iva"] == 2) {
                        $xml->startElement("valor");
                        $xml->text(number_format(((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) * 0.12), 2, '.', ''));
                        $xml->endElement();
                    } else {
                        $xml->startElement("valor");
                        $xml->text(0.00);
                        $xml->endElement();
                    }
                $xml->endElement();
            }

            $xml->endElement();
            $xml->startElement("motivo");
                $xml->text($re->motivo);
            $xml->endElement();
        $xml->endElement();

        //DETALLE
        $xml->startElement("detalles");
            for ($i = 0; $i < count($det); $i++) {
                $xml->startElement("detalle");
                    $xml->startElement("codigoInterno");
                    $xml->text($det[$i]["id_detalle_nota_credito"]);
                    $xml->endElement();
                    $xml->startElement("codigoAdicional");
                    $xml->text($det[$i]["id_detalle_nota_credito"]);
                    $xml->endElement();
                    $xml->startElement("descripcion");
                    $xml->text($det[$i]["nombre"]);
                    $xml->endElement();
                    $xml->startElement("cantidad");
                    $xml->text($det[$i]["cantidad"]);
                    $xml->endElement();
                    $xml->startElement("precioUnitario");
                    $xml->text($det[$i]["precio"]);
                    $xml->endElement();
                    if ($det[$i]["descuento"]) {
                        $xml->startElement('descuento');
                        $xml->text($det[$i]["descuento"]);
                        $xml->endElement();
                    } else {
                        $xml->startElement('descuento');
                        $xml->text(0);
                        $xml->endElement();
                    }
                    $xml->startElement('precioTotalSinImpuesto');
                    $xml->text(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]);
                    $xml->endElement();

                    $xml->startElement("impuestos");
                        $xml->startElement("impuesto");
                            $xml->startElement('codigo');
                            $xml->text(2);
                            $xml->endElement();

                            $xml->startElement("codigoPorcentaje");
                            $xml->text($det[$i]["codigo_iva"]);
                            $xml->endElement();

                            if ($det[$i]["id_iva"] == 2) {
                                $xml->startElement("tarifa");
                                $xml->text('12');
                                $xml->endElement();
                            } else {
                                $xml->startElement("tarifa");
                                $xml->text('0');
                                $xml->endElement();
                            }

                            $xml->startElement('baseImponible');
                            $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"], 2, '.', ''));
                            $xml->endElement();
                            if ($det[$i]["id_iva"] == 2) {
                                $xml->startElement("valor");
                                $xml->text(number_format(((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) * 0.12), 2, '.', ''));
                                $xml->endElement();
                            } else {
                                $xml->startElement("valor");
                                $xml->text('0.00');
                                $xml->endElement();
                            }
                        $xml->endElement();
                    $xml->endElement();

                $xml->endElement();
            }
        $xml->endElement();

        $xml->startElement('infoAdicional');
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Dirección");
            $xml->text($re->direccion);
            $xml->endElement();

            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text($re->telefono);
            $xml->endElement();
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Email");
            $xml->text($re->email);
            $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
    public function enotadebito(Request $re){
        file_put_contents(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/notadebito/" . $re->clave_acceso . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $re->id_empresa . '/comprobantes/notadebito/' . $re->clave_acceso . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement('notaDebito');
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");

        $xml->startElement("infoTributaria");

            $xml->startElement("ambiente");
            $xml->text($re->ambiente);
            $xml->endElement();

            $xml->startElement("tipoEmision");
            $xml->text($re->tipo_emision);
            $xml->endElement();

            $xml->startElement("razonSocial");
            $xml->text($re->razon_social);
            $xml->endElement();

            $xml->startElement("nombreComercial");
            $xml->text($re->nombre_empresa);
            $xml->endElement();

            $xml->startElement("ruc");
            $xml->text($re->ruc_empresa);
            $xml->endElement();

            $xml->startElement("claveAcceso");
            $xml->text($re->clave_acceso);
            $xml->endElement();
            $xml->startElement("codDoc");
            $xml->text('05');
            $xml->endElement();

            $xml->startElement("estab");
            $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT));
            $xml->endElement();

            $xml->startElement("ptoEmi");
            $xml->text(str_pad($re->codigope, 3, "0", STR_PAD_LEFT));
            $xml->endElement();

            $xml->startElement("secuencial");
            $xml->text(substr($re->clave_acceso, -19, -10));
            $xml->endElement();

            $xml->startElement("dirMatriz");
            $xml->text($re->direccion_empresa);
            $xml->endElement();

        $xml->endElement();
        //INFORNOTACREDICO
        $xml->startElement('infoNotaDebito');

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text($re->direccion_establecimiento);
        $xml->endElement();

        if ($re->tipo_identificacion == "Cédula de Identidad") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('05');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Ruc") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('04');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Pasaporte") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('06');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Consumidor Final") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('07');
            $xml->endElement();
        }
        $xml->startElement("razonSocialComprador");
        $xml->text($re->nombre);
        $xml->endElement();

        $xml->startElement("identificacionComprador");
        $xml->text($re->identificacion);
        $xml->endElement();

        if ($re->obligado_contabilidad == 0) {
            $obligado = "NO";
        } else {
            $obligado = "SI";
        }
        $xml->startElement("obligadoContabilidad");
        $xml->text($obligado);
        $xml->endElement();

        $xml->startElement("codDocModificado");
        $xml->text("01");
        $xml->endElement();

        $xml->startElement("numDocModificado");
        $xml->text(substr($re->clave_acceso,24,3) . "-" . substr($re->clave_acceso,27,3) . "-" . substr($re->clave_acceso,30,9));
        $xml->endElement();
        $xml->startElement("fechaEmisionDocSustento");
        $xml->text(date('d/m/Y', strtotime($re->fechaAutorizacion)));
        $xml->endElement();
        $xml->startElement("totalSinImpuestos");
        $xml->text($re->subtotal_sin_impuesto);
        $xml->endElement();

        //Impuestos
        $xml->startElement('impuestos');
            $det = Detalle_nota_debito::select("detalle_nota_debito.*", "producto.cod_principal", "producto.cod_alterno", "producto.descripcion","iva.codigo as codigo_iva")
                    ->join("producto", "producto.id_producto", "=", "detalle_nota_debito.id_producto")
                    ->join("iva", "iva.id_iva", "=", "detalle_nota_debito.id_iva")
                    ->where("detalle_nota_debito.id_nota_debito", "=", $re->id_nota_debito)->get();

            for ($i = 0; $i < count($det); $i++) {
                $xml->startElement("impuesto");
                    $xml->startElement('codigo');
                    $xml->text(2);
                    $xml->endElement();

                    $xml->startElement("codigoPorcentaje");
                    $xml->text($det[$i]["codigo_iva"]);
                    $xml->endElement();

                    if ($det[$i]["id_iva"] == 2) {
                        $xml->startElement("tarifa");
                        $xml->text('12');
                        $xml->endElement();
                    } else {
                        $xml->startElement("tarifa");
                        $xml->text('0');
                        $xml->endElement();
                    }

                    $xml->startElement("baseImponible");
                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"], 2, '.', ''));
                    $xml->endElement();
                    if ($det[$i]["codigo_iva"] == 2) {
                        $xml->startElement("valor");
                        $xml->text(number_format(((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) * 0.12), 2, '.', ''));
                        $xml->endElement();
                    } else {
                        $xml->startElement("valor");
                        $xml->text(0.00);
                        $xml->endElement();
                    }
                $xml->endElement();
            }
        $xml->endElement();
        $xml->startElement("valorTotal");
            $xml->text($re->valor_total);
        $xml->endElement();
        $respe = substr($re->clave_acceso,24,3) . substr($re->clave_acceso,27,3) . substr($re->clave_acceso,30,9);
        //Pagos
        $xml->startElement("pagos");
        //$cxc = DB::select("select nota_debito_pagos.*, forma_pagos_sri.codigo as codigopagos from nota_debito inner join nota_debito_pagos on nota_debito.id_nota_debito = nota_debito_pagos.id_nota_debito left join forma_pagos on forma_pagos.id_forma_pagos = nota_debito_pagos.id_forma_pagos left join forma_pagos_sri on forma_pagos_sri.id_forma_pagos_sri = forma_pagos.id_forma_pagos_sri where nota_debito.clave_acceso LIKE '%$respe%'");
            $cxc = Notadebito::select("nota_debito_pagos.*", "forma_pagos_sri.codigo as codigopagos")
                ->join("nota_debito_pagos", "nota_debito.id_nota_debito", "=", "nota_debito_pagos.id_nota_debito")
                ->leftJoin("forma_pagos", "forma_pagos.id_forma_pagos", "=", "nota_debito_pagos.id_forma_pagos")
                ->leftJoin("forma_pagos_sri", "forma_pagos_sri.id_forma_pagos_sri", "=", "forma_pagos.id_forma_pagos_sri")
                ->where('nota_debito.clave_acceso', 'LIKE', "%$respe%")->get();
            for ($i = 0; $i < count($cxc); $i++) {
                $xml->startElement("pago");
                    if(isset($cxc[$i]["codigopagos"])){
                        $xml->startElement("formaPago");
                            $xml->text($cxc[$i]["codigopagos"]);
                        $xml->endElement();
                    }else{
                        $xml->startElement("formaPago");
                            $xml->text('01');
                        $xml->endElement();
                    }
                    $xml->startElement("total");
                        $xml->text($cxc[$i]["total"]);
                    $xml->endElement();
                    $xml->startElement("plazo");
                        $xml->text($cxc[$i]["plazo"]);
                    $xml->endElement();
                    $xml->startElement("unidadTiempo");
                        $xml->text($cxc[$i]["unidad_tiempo"]);
                    $xml->endElement();
                $xml->endElement();
            }
        $xml->endElement();
        $xml->endElement();
        //Motivo
        $xml->startElement("motivos");
            for ($i = 0; $i < count($det); $i++) {
                $xml->startElement("motivo");
                    $xml->startElement("razon");
                    $xml->text($det[$i]["nombre"]);
                    $xml->endElement();
                    $xml->startElement("valor");
                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"], 2, '.', ''));
                    $xml->endElement();
                $xml->endElement();
            }
        $xml->endElement();

        $xml->startElement('infoAdicional');
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Dirección");
            $xml->text($re->direccion);
            $xml->endElement();

            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text($re->telefono);
            $xml->endElement();
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Email");
            $xml->text($re->email);
            $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
    public function r_factura(){
        $xml = new XMLWriter();
        $xml->openUri("../base de datos/factura/xmlsokai/r_factura.xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement("comprobanteRetencion");
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");
        //infoTributaria
        $xml->startElement("infoTributaria");

        $xml->startElement("ambiente");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codDoc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();
        //infoCompRetencion
        $xml->startElement("infoCompRetencion");

        $xml->startElement("fechaEmision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("obligadoContabilidad");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoIdentificacionSujetoRetenido");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocialSujetoRetenido");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("identificacionSujetoRetenido");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("periodoFiscal");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();
        //impuestos
        $xml->startElement("impuestos");

        $xml->startElement("impuesto");

        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codigoRetencion");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("porcentajeRetener");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("valorRetenido");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("numDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("fechaEmisionDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();
        //infoAdicional
        $xml->startElement("infoAdicional");

        $xml->startElement("campoAdicional");
        $xml->writeAttribute("nombre", "Dirección");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("campoAdicional");
        $xml->writeAttribute("nombre", "Teléfono");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("campoAdicional");
        $xml->writeAttribute("nombre", "Email");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();
        $xml->endElement(); //fin comprobanteRetencion
        $xml->endDocument();

        $xml->startElement("plazo");
        $xml->text("1");
        $xml->endElement();
    }
    public function r_facturas(){
        $xml = new XMLWriter();
        $xml->openUri("../base de datos/factura/xmlsokai/r_factura.xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement("autorizacion");
        $xml->writeAttribute("version", "1.0.0");

        $xml->startElement('etsi:X509IssuerName');
        $xml->endElement();
        $xml->startElement('etsi:X509SerialNumber');
        $xml->endElement();

        $xml->endElement();


        $xml->endElement();

        $xml->endElement();

        $xml->endElement();

        $xml->startElement('etsi:SignedDataObjectProperties');

        $xml->startElement('etsi:DataObjectFormat');
        $xml->writeAttribute("ObjectReference", "#Reference-ID-363558");

        $xml->startElement("mensaje");

        $xml->startElement("mensaje");

        $xml->startElement("identificador");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("mensaje");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipo");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("InformacionAdicional");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();


        $xml->endElement();
        $xml->endElement(); //fin
        $xml->endDocument();
    }
    public function e_liquidacioncompra(Request $re){
        file_put_contents(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/liquidacioncompra/" . $re->clave_acceso . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $re->id_empresa . '/comprobantes/liquidacioncompra/' . $re->clave_acceso . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

            $xml->startElement('liquidacionCompra');
            $xml->writeAttribute("id", "comprobante");
            $xml->writeAttribute("version", "1.0.0");

                $xml->startElement("infoTributaria");

                    $xml->startElement("ambiente");
                        $xml->text($re->ambiente);
                    $xml->endElement();

                    $xml->startElement("tipoEmision");
                        $xml->text($re->tipo_emision);
                    $xml->endElement();

                    $xml->startElement("razonSocial");
                        $xml->text($re->razon_social);
                    $xml->endElement();

                    $xml->startElement("nombreComercial");
                        $xml->text($re->nombre_empresa);
                    $xml->endElement();

                    $xml->startElement("ruc");
                        $xml->text($re->ruc_empresa);
                    $xml->endElement();

                    $xml->startElement("claveAcceso");
                        $xml->text($re->clave_acceso);
                    $xml->endElement();

                    $xml->startElement("codDoc");
                        $xml->text('03');
                    $xml->endElement();

                    $xml->startElement("estab");
                        $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT));
                    $xml->endElement();

                    $xml->startElement("ptoEmi");
                        $xml->text(str_pad($re->codigope, 3, "0", STR_PAD_LEFT));
                    $xml->endElement();

                    $xml->startElement("secuencial");
                        $xml->text(substr($re->clave_acceso, -19, -10));
                    $xml->endElement();

                    $xml->startElement("dirMatriz");
                        $xml->text($re->direccion_empresa);
                    $xml->endElement();

                $xml->endElement();

                $xml->startElement("infoLiquidacionCompra");

                    $xml->startElement("fechaEmision");
                        $xml->text($re->fechaEmision);
                    $xml->endElement();

                    $xml->startElement("dirEstablecimiento");
                        $xml->text($re->dirEstablecimiento);
                    $xml->endElement();

                    $xml->startElement("contribuyenteEspecial");
                        $xml->text($re->contribuyenteEspecial);
                    $xml->endElement();

                    $xml->startElement("obligadoContabilidad");
                        $xml->text($re->obligadoContabilidad);
                    $xml->endElement();

                    $xml->startElement("tipoIdentificacionProveedor");
                        $xml->text($re->tipoIdentificacionProveedor);
                    $xml->endElement();

                    $xml->startElement("razonSocialProveedor");
                        $xml->text($re->razonSocialProveedor);
                    $xml->endElement();

                    $xml->startElement("identificacionProveedor");
                        $xml->text($re->identificacionProveedor);
                    $xml->endElement();

                    $xml->startElement("direccionProveedor");
                        $xml->text($re->direccionProveedor);
                    $xml->endElement();

                    $xml->startElement("totalSinImpuestos");
                        $xml->text($re->totalSinImpuestos);
                    $xml->endElement();

                    $xml->startElement("totalDescuento");
                        $xml->text($re->totalDescuento);
                    $xml->endElement();

                    $xml->startElement("codDocReembolso");
                        $xml->text($re->codDocReembolso);
                    $xml->endElement();

                    $xml->startElement("totalComprobantesReembolso");
                        $xml->text($re->totalComprobantesReembolso);
                    $xml->endElement();

                    $xml->startElement("totalBaseImponibleReembolso");
                        $xml->text($re->totalBaseImponibleReembolso);
                    $xml->endElement();

                    $xml->startElement("totalImpuestoReembolso");
                        $xml->text($re->totalImpuestoReembolso);
                    $xml->endElement();


                    $xml->startElement("totalConImpuestos");

                        $xml->startElement("totalImpuesto");

                            $xml->startElement("codigo");
                                $xml->text($re->codigo);
                            $xml->endElement();

                            $xml->startElement("codigoPorcentaje");
                                $xml->text($re->codigoPorcentaje);
                            $xml->endElement();

                            $xml->startElement("descuentoAdicional");
                                $xml->text($re->descuentoAdicional);
                            $xml->endElement();

                            $xml->startElement("baseImponible");
                                $xml->text($re->baseImponible);
                            $xml->endElement();

                            $xml->startElement("tarifa");
                                $xml->text($re->tarifa);
                            $xml->endElement();

                            $xml->startElement("valor");
                                $xml->text($re->valor);
                            $xml->endElement();

                        $xml->endElement();

                    $xml->endElement();

                    $xml->startElement("importeTotal");
                        $xml->text($re->importeTotal);
                    $xml->endElement();

                    $xml->startElement("moneda");
                        $xml->text($re->moneda);
                    $xml->endElement();

                    $xml->startElement("pagos");

                        $xml->startElement("pago");

                            $xml->startElement("formaPago");
                                $xml->text($re->formaPago);
                            $xml->endElement();

                            $xml->startElement("total");
                                $xml->text($re->total);
                            $xml->endElement();

                            $xml->startElement("plazo");
                                $xml->text($re->plazo);
                            $xml->endElement();

                            $xml->startElement("unidadTiempo");
                                $xml->text($re->unidadTiempo);
                            $xml->endElement();

                        $xml->endElement();

                    $xml->endElement();
                $xml->endElement();

                $xml->startElement("detalles");

                    $xml->startElement("detalle");

                        $xml->startElement("codigoPrincipal");
                            $xml->text($re->codigoPrincipal);
                        $xml->endElement();

                        $xml->startElement("codigoAuxiliar");
                            $xml->text($re->codigoAuxiliar);
                        $xml->endElement();

                        $xml->startElement("descripcion");
                            $xml->text($re->descripcion);
                        $xml->endElement();

                        $xml->startElement("unidadMedida");
                            $xml->text($re->unidadMedida);
                        $xml->endElement();

                        $xml->startElement("cantidad");
                            $xml->text($re->cantidad);
                        $xml->endElement();

                        $xml->startElement("precioUnitario");
                            $xml->text($re->precioUnitario);
                        $xml->endElement();

                        $xml->startElement("descuento");
                            $xml->text($re->descuento);
                        $xml->endElement();

                        $xml->startElement("precioTotalSinImpuesto");
                            $xml->text($re->precioTotalSinImpuesto);
                        $xml->endElement();

                        $xml->startElement("detallesAdicionales");

                            $xml->startElement("detAdicional");
                            $xml->writeAttribute("nombre", "nombre");
                            $xml->writeAttribute("valor", "valor0");
                            $xml->endElement();

                        $xml->endElement();

                        $xml->startElement("impuestos");

                            $xml->startElement("impuesto");

                                $xml->startElement("codigo");
                                    $xml->text($re->codigo);
                                $xml->endElement();

                                $xml->startElement("codigoPorcentaje");
                                    $xml->text($re->ambcodigoPorcentajeiente);
                                $xml->endElement();

                                $xml->startElement("tarifa");
                                    $xml->text($re->tarifa);
                                $xml->endElement();

                                $xml->startElement("baseImponible");
                                    $xml->text($re->baseImponible);
                                $xml->endElement();

                                $xml->startElement("valor");
                                    $xml->text($re->valor);
                                $xml->endElement();

                            $xml->endElement();

                        $xml->endElement();

                    $xml->endElement();

                $xml->endElement();

                $xml->startElement("reembolsos");

                    $xml->startElement("reembolsoDetalle");

                        $xml->startElement("tipoIdentificacionProveedorReembolso");
                            $xml->text($re->tipoIdentificacionProveedorReembolso);
                        $xml->endElement();

                        $xml->startElement("identificacionProveedorReembolso");
                            $xml->text($re->identificacionProveedorReembolso);
                        $xml->endElement();

                        $xml->startElement("codPaisPagoProveedorReembolso");
                            $xml->text($re->codPaisPagoProveedorReembolso);
                        $xml->endElement();

                        $xml->startElement("tipoProveedorReembolso");
                            $xml->text($re->tipoProveedorReembolso);
                        $xml->endElement();

                        $xml->startElement("codDocReembolso");
                            $xml->text($re->codDocReembolso);
                        $xml->endElement();

                        $xml->startElement("estabDocReembolso");
                            $xml->text($re->estabDocReembolso);
                        $xml->endElement();

                        $xml->startElement("ptoEmiDocReembolso");
                            $xml->text($re->ptoEmiDocReembolso);
                        $xml->endElement();

                        $xml->startElement("secuencialDocReembolso");
                            $xml->text($re->secuencialDocReembolso);
                        $xml->endElement();

                        $xml->startElement("fechaEmisionDocReembolso");
                            $xml->text($re->fechaEmisionDocReembolso);
                        $xml->endElement();

                        $xml->startElement("numeroautorizacionDocReemb");
                            $xml->text($re->numeroautorizacionDocReemb);
                        $xml->endElement();

                        $xml->startElement("detalleImpuestos");

                            $xml->startElement("detalleImpuesto");

                                $xml->startElement("codigo");
                                    $xml->text($re->codigo);
                                $xml->endElement();

                                $xml->startElement("codigoPorcentaje");
                                    $xml->text($re->codigoPorcentaje);
                                $xml->endElement();

                                $xml->startElement("tarifa");
                                    $xml->text($re->tarifa);
                                $xml->endElement();

                                $xml->startElement("baseImponibleReembolso");
                                    $xml->text($re->baseImponibleReembolso);
                                $xml->endElement();

                                $xml->startElement("impuestoReembolso");
                                    $xml->text($re->impuestoReembolso);
                                $xml->endElement();

                            $xml->endElement();

                        $xml->endElement();

                    $xml->endElement();

                $xml->endElement();

                $xml->startElement("maquinaFiscal");

                    $xml->startElement("marca");
                        $xml->text($re->marca);
                    $xml->endElement();

                    $xml->startElement("modelo");
                        $xml->text($re->modelo);
                    $xml->endElement();

                    $xml->startElement("serie");
                        $xml->text($re->serie);
                    $xml->endElement();

                $xml->endElement();

                $xml->startElement("infoAdicional");

                    $xml->startElement("campoAdicional");
                    $xml->writeAttribute("nombre", "nombre0");
                    $xml->text($re->campoAdicional);
                    $xml->endElement();

                $xml->endElement();

            $xml->endElement();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
}
