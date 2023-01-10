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
use App\Models\Detalle_liquidacion_compra;
use App\Models\Pagos;
use App\Models\Cuentaporcobrar;
use App\Models\Empresa;
use App\Models\Retencion_factura;
use App\Models\Retencion_factura_comp;
use App\Models\Retencion_Liquidacion_Compra;
use App\Models\Retencion;
use App\Models\DetalleGuiaRemision;
use App\Models\Impuesto;
use App\Models\Factura_pagos;
use App\Models\Liquidaction_compra_pagos;
use App\Models\Factura;
use App\Models\Notadebito;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

include_once getenv("FILE_CONFIG_PHP");

class XMLControler extends Controller
{
    public function efactura(Request $re)
    {
        file_put_contents(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/factura/" . $re->clave_acceso . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $re->id_empresa . '/comprobantes/factura/' . $re->clave_acceso . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement('factura');
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");

        $sel_empresa = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");
        $xml->startElement("infoTributaria");

        $xml->startElement("ambiente");
        $xml->text($re->ambiente);
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text($re->tipo_emision);
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text(str_replace(array('Á', 'É', 'Í', 'Ó', 'Ú'), array('A', 'E', 'I', 'O', 'U'), $re->razon_social));
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text(str_replace(array('Á', 'É', 'Í', 'Ó', 'Ú'), array('A', 'E', 'I', 'O', 'U'), $re->nombre_empresa));
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
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->direccion_empresa));
        $xml->endElement();
        if($sel_empresa[0]->leyenda==2){
            $xml->startElement("regimenMicroempresas");
            $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
            $xml->endElement();
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
        }else{
                $bus=strpos($sel_empresa[0]->leyenda,"MICROEMPRESA");
                if($bus==true){
                    $xml->startElement("regimenMicroempresas");
                    $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
                    $xml->endElement();
                }

        }
        if($sel_empresa[0]->leyenda==3){
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
            $xml->startElement("contribuyenteRimpe");
            $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
            $xml->endElement();
            
        }else{
            $bus01=strpos($sel_empresa[0]->leyenda,"CONTRIBUYENTE REGIMEN RIMPE");
            if($sel_empresa[0]->leyenda=="CONTRIBUYENTE REGIMEN RIMPE"){
                $xml->startElement("contribuyenteRimpe");
                $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
                $xml->endElement();
            }else{
                $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                if($bus2==true){
                        $ret=substr($sel_empresa[0]->leyenda,-8);
                        $nro_ret=intval($ret);
                        $xml->startElement("agenteRetencion");
                        $xml->text($nro_ret);
                        $xml->endElement();
                }
            }
        }
        
            // if($sel_empresa[0]->leyenda==2){
            //     $xml->startElement("regimenMicroempresas");
            //     $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
            //     $xml->endElement();
            //     $xml->startElement("agenteRetencion");
            //     $xml->text(1);
            //     $xml->endElement();
            // }else{
            //         $bus=strpos($sel_empresa[0]->leyenda,"MICROEMPRESA");
            //         if($bus==true){
            //             $xml->startElement("regimenMicroempresas");
            //             $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
            //             $xml->endElement();
            //         }
            //         // else{
            //         //     $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
            //         //     if($bus2==true){
            //         //         $ret=substr($sel_empresa[0]->leyenda,-8);
            //         //         $nro_ret=intval($ret);
            //         //         $xml->startElement("agenteRetencion");
            //         //         $xml->text($nro_ret);
            //         //         $xml->endElement();
            //         //     }
            //         // }
                
                
            // }
            
       
        

        $xml->endElement();

        $xml->startElement('infoFactura');

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->direccion_establecimiento));
        $xml->endElement();
        if($re->noresolucion){
            $xml->startElement("contribuyenteEspecial");
            $xml->text($re->noresolucion);
            $xml->endElement();
        }
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
        } else {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('05');
            $xml->endElement();
        }

        $xml->startElement("razonSocialComprador");
        $xml->text(str_replace(array("\"", "'", "", "/", "–", "Ñ", "ñ", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"), array("", "", "", "", "", "N", "n", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U"), $re->nombre));
        $xml->endElement();

        $xml->startElement("identificacionComprador");
        $xml->text($re->identificacion);
        $xml->endElement();

        $xml->startElement("direccionComprador");
        $xml->text(str_replace(array("-", "\"", "'", "/", "–", "ñ", "Ñ","á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"), array("", "", "", "", "", "n", "N","a", "e", "i", "o", "u", "A", "E", "I", "O", "U"), $re->direccion));
        $xml->endElement();
        if($re->financiamiento>0 && $re->financiamiento!==null){
            $xml->startElement("totalSinImpuestos");
            $xml->text(number_format($re->subtotal_sin_impuesto+$re->financiamiento,4,".",""));
            $xml->endElement();
        }else{
            $xml->startElement("totalSinImpuestos");
            $xml->text(number_format($re->subtotal_sin_impuesto,4,".",""));
            $xml->endElement();
        }
        

        $xml->startElement("totalDescuento");
        $xml->text($re->descuentototal);
        $xml->endElement();

        $xml->startElement("totalConImpuestos");
		
        $det = Detalle::select("detalle.*", "producto.cod_principal", "producto.cod_alterno", "producto.total_ice", "producto.descripcion", "iva.codigo as codigo_iva", "iva.nombre as nombre_iva", "ice.codigo AS codigo_ice")
            ->join("iva", "iva.id_iva", "=", "detalle.id_iva")
            ->join("ice", "ice.id_ice", "=", "detalle.id_ice")
            ->join("producto", "producto.id_producto", "=", "detalle.id_producto")
            ->where("id_factura", "=", $re->id_factura)->get();
		if($re->financiamiento>0 && $re->financiamiento!==null){
			$detalles_producto = DB::select("SELECT detalle.precio,detalle.cantidad,detalle.nombre,detalle.descuento,detalle.p_descuento,detalle.id_iva,detalle.id_ice,producto.cod_principal,producto.cod_alterno,producto.total_ice,producto.descripcion,iva.codigo as codigo_iva,iva.nombre as nombre_iva,ice.codigo AS codigo_ice from detalle
												INNER JOIN iva
												ON iva.id_iva=detalle.id_iva
												LEFT JOIN ice
												ON ice.id_ice=detalle.id_ice
												INNER JOIN producto
												ON producto.id_producto=detalle.id_producto
												where id_factura={$re->id_factura}
												UNION
												SELECT $re->financiamiento as precio,1 as cantidad,'Financiamiento' as nombre,null as descuento,1 as p_descuento,1 as id_iva,null as id_ice,'000' as 
												cod_principal,null as  cod_alterno,0 as total_ice,'Financiamiento' as descripcion,0 as codigo_iva,'0%' as nombre_iva,1 as codigo_iva");
		}else{
			$detalles_producto = Detalle::select("detalle.*", "producto.cod_principal", "producto.cod_alterno", "producto.total_ice", "producto.descripcion", "iva.codigo as codigo_iva", "iva.nombre as nombre_iva", "ice.codigo AS codigo_ice")
				->join("iva", "iva.id_iva", "=", "detalle.id_iva")
				->leftjoin("ice", "ice.id_ice", "=", "detalle.id_ice")
				->join("producto", "producto.id_producto", "=", "detalle.id_producto")
				->where("id_factura", "=", $re->id_factura)->get();
		}
        $result = DB::select("SELECT IF(SUM(producto.total_ice)>0, SUM(detalle.total + (detalle.cantidad * producto.total_ice)), SUM(detalle.total)) AS total, iva.codigo as codigo_iva FROM detalle INNER JOIN iva ON iva.id_iva = detalle.id_iva LEFT JOIN ice ON ice.id_ice = detalle.id_ice INNER JOIN producto ON producto.id_producto = detalle.id_producto WHERE id_factura = " . $re->id_factura . " GROUP BY iva.codigo");

        for ($i = 0; $i < count($result); $i++) {
            //cambiar de manera global los valores de iva, ice y irbpnr
            $xml->startElement("totalImpuesto");
            if($re->existe_iva_8>0 && $result[$i]->codigo_iva == 2){
                $xml->startElement('codigo');
                $xml->text(3);
                $xml->endElement();
            }else{
                $xml->startElement('codigo');
                $xml->text(2);
                $xml->endElement();
            }
            
            if($re->existe_iva_8>0 && $result[$i]->codigo_iva == 2){
                $xml->startElement("codigoPorcentaje");
                $xml->text(3072);
                $xml->endElement();
            }else{
                $xml->startElement("codigoPorcentaje");
                $xml->text($result[$i]->codigo_iva);
                $xml->endElement();
            }
            $xml->startElement("baseImponible");
            $xml->text(number_format($result[$i]->total, 4, '.', ''));
            $xml->endElement();
            if ($result[$i]->codigo_iva == 2) {
				if($re->existe_iva_8>0){
					$xml->startElement("tarifa");
					$xml->text(8);
					$xml->endElement();
					$xml->startElement("valor");
					$xml->text(number_format((($result[$i]->total) * 0.08), 4, '.', ''));
					$xml->endElement();
				}else{
					$xml->startElement("tarifa");
					$xml->text(12);
					$xml->endElement();
					$xml->startElement("valor");
					$xml->text(number_format((($result[$i]->total) * 0.12), 4, '.', ''));
					$xml->endElement();
				}
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
        $detices = DB::select("SELECT SUM(detalle.total) AS total, ROUND(SUM(detalle.cantidad * producto.total_ice), 2) AS valor, SUBSTR(ice.codigo,1,4) as codigo_ice FROM detalle INNER JOIN producto ON producto.id_producto = detalle.id_producto INNER JOIN ice ON ice.id_ice = producto.ice WHERE detalle.id_factura = " . $re->id_factura . " AND producto.total_ice > 0 GROUP BY SUBSTR(codigo,1,4)");
        for ($i = 0; $i < count($detices); $i++) {
            $xml->startElement("totalImpuesto");
            $xml->startElement('codigo');
            $xml->text(3);
            $xml->endElement();

            $xml->startElement("codigoPorcentaje");
            $xml->text($detices[$i]->codigo_ice);
            $xml->endElement();

            $xml->startElement("baseImponible");
            $xml->text(number_format($detices[$i]->total, 4, '.', ''));
            $xml->endElement();

            $xml->startElement("tarifa");
            $xml->text(0);
            $xml->endElement();

            $xml->startElement("valor");
            $xml->text(number_format(($detices[$i]->valor), 4, '.', ''));
            $xml->endElement();

            $xml->endElement();
        }
        if($re->financiamiento>0 && $re->financiamiento!==null){
            $xml->startElement("totalImpuesto");
            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            $xml->startElement("codigoPorcentaje");
            $xml->text(0);
            $xml->endElement();

            $xml->startElement("baseImponible");
            $xml->text(number_format($re->financiamiento, 4, '.', ''));
            $xml->endElement();

            $xml->startElement("tarifa");
            $xml->text(0);
            $xml->endElement();

            $xml->startElement("valor");
            $xml->text(number_format(0, 2, '.', ''));
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
            if (isset($cxc[$i]["codigopagos"])) {
                $xml->startElement("formaPago");
                $xml->text($cxc[$i]["codigopagos"]);
                $xml->endElement();
            } else {
                $xml->startElement("formaPago");
                $xml->text('20');
                $xml->endElement();
            }
            $xml->startElement("total");
            if($re->financiamiento>0 && $re->financiamiento!==null && ($cxc[$i]["estado"]==2 || $cxc[$i]["estado"]=="2")){
                $xml->text(number_format($cxc[$i]["total"]+$re->financiamiento,4,".",""));
            }else{
                $xml->text($cxc[$i]["total"]);
            }
            
            $xml->endElement();
            $xml->startElement("plazo");
            if($cxc[$i]["estado"]==2){
                $xml->text($cxc[$i]["tiempos_pagos"]);
            }else{
                $xml->text($cxc[$i]["plazo"]);
            }
            
            $xml->endElement();
            $xml->startElement("unidadTiempo");
            //diferente a la empresa AMERICAN PETROLEUM AND INDUSTRIAL SUPPLY S.A. PETROINSUPPLY
            if($sel_empresa[0]->ruc_empresa!=='1791974808001'){
                $xml->text(str_replace("í", "i", $cxc[$i]["unidad_tiempo"]));
            }else{
                $xml->text(str_replace(array("Días","Dias"), array("dias","dias"), $cxc[$i]["unidad_tiempo"]));
            }
            
            $xml->endElement();
            $xml->endElement();
        }

        $rfi = retencion_factura::where("id_factura", "=", $re->id_factura)->get();
        for ($i = 0; $i < count($rfi); $i++) {
            if($rfi[$i]["cantidadiva"]>0){
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
                $xml->text("dias");
                $xml->endElement();
                $xml->endElement();
            }
        }
        $rfr = retencion_factura::where("id_factura", "=", $re->id_factura)->get();
        for ($i = 0; $i < count($rfr); $i++) {
            if($rfr[$i]["cantidadrenta"]>0){
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
                $xml->text("dias");
                $xml->endElement();
                $xml->endElement();
            }
            
        }
        $xml->endElement();
        $xml->endElement();
        $xml->startElement('detalles');
        for ($i = 0; $i < count($detalles_producto); $i++) {
            $xml->startElement('detalle');
            $xml->startElement('codigoPrincipal');
            $xml->text($detalles_producto[$i]->cod_principal);
            $xml->endElement();
            if ($detalles_producto[$i]->cod_alterno) {
                $xml->startElement('codigoAuxiliar');
                $xml->text(str_replace(array('/', '"', ",", ":", "¨", "-", "–", "é", "á", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ"), array(' ', '', ' ', " ", "", " ", " ", "e", "a", "i", "o", "u", "n", "A", "E", "I", "O", "U", "N"),$detalles_producto[$i]->cod_alterno));
                $xml->endElement();
            }
            $xml->startElement('descripcion');
            $xml->text(str_replace(array('/', '"', ",", ":", "¨", "-", "–", "é", "á", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ",";","|","°"), array(' ', '', ' ', " ", "", " ", " ", "e", "a", "i", "o", "u", "n", "A", "E", "I", "O", "U", "N","","",""), $detalles_producto[$i]->nombre));
            $xml->endElement();
            $xml->startElement('cantidad');
            $xml->text(number_format($detalles_producto[$i]->cantidad,2,".",""));
            $xml->endElement();
            $xml->startElement('precioUnitario');
            $xml->text(number_format($detalles_producto[$i]->precio,4,".",""));
            $xml->endElement();
            if ($detalles_producto[$i]->descuento) {
                $xml->startElement('descuento');
                if ($detalles_producto[$i]->p_descuento == 1) {
                    $xml->text(number_format($detalles_producto[$i]->descuento, 4, '.', ''));
                } else {
                    if (isset($detalles_producto[$i]->descuento)) {
                        $xml->text(number_format((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad * $detalles_producto[$i]->descuento) / 100), 4, '.', ''));
                    } else {
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
            if ($detalles_producto[$i]->p_descuento == 1) {
                $xml->text(number_format((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - $detalles_producto[$i]->descuento), 4, '.', ''));
            } else {
                if (isset($detalles_producto[$i]->descuento)) {
                    $xml->text(number_format(($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - (($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad * $detalles_producto[$i]->descuento) / 100), 4, '.', ''));
                } else {
                    $xml->text(number_format(($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad), 4, '.', ''));
                }
            }
            $xml->endElement();
            /*if ($detalles_producto[$i]->descripcion) {
                $xml->startElement('detallesAdicionales');
                    $xml->writeRaw('<detAdicional nombre="descripcion" valor="'.$detalles_producto[$i]->descripcion.'"></detAdicional>');
                $xml->endElement();
            }*/
            $xml->startElement('impuestos');

            if ($detalles_producto[$i]->id_iva) {
                $xml->startElement('impuesto');
                if($re->existe_iva_8>0 && $detalles_producto[$i]->codigo_iva==2){
                    $xml->startElement('codigo');
                    $xml->text(3);
                    $xml->endElement();
                }else{
                    $xml->startElement('codigo');
                    $xml->text(2);
                    $xml->endElement();  
                }
                
                if($re->existe_iva_8>0 && $detalles_producto[$i]->codigo_iva==2){
                    $xml->startElement("codigoPorcentaje");
                    $xml->text(3072);
                    $xml->endElement();
                }else{
                    $xml->startElement("codigoPorcentaje");
                    $xml->text($detalles_producto[$i]->codigo_iva);
                    $xml->endElement();
                }
                

                if ($detalles_producto[$i]->codigo_iva == 2) {
					if($re->existe_iva_8>0){
						$xml->startElement("tarifa");
						$xml->text(8);
						$xml->endElement();
					}else{
						$xml->startElement("tarifa");
						$xml->text(12);
						$xml->endElement();
					}
                    
                } else {
                    $xml->startElement("tarifa");
                    $xml->text(0);
                    $xml->endElement();
                }
                $xml->startElement('baseImponible');
                if ($detalles_producto[$i]->p_descuento == 1) {
                    $xml->text(number_format((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - $detalles_producto[$i]->descuento) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad), 4, '.', ''));
                } else {
                    if (isset($detalles_producto[$i]->descuento)) {
                        $xml->text(number_format((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - (($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad * $detalles_producto[$i]->descuento) / 100)) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad), 4, '.', ''));
                    } else {
                        $xml->text(number_format(($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad), 4, '.', ''));
                    }
                }

                /*if($detalles_producto[$i]->p_descuento == 1){
                            $xml->text(number_format((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - $detalles_producto[$i]->descuento), 2, '.', ''));
                        }else{
                            if(isset($detalles_producto[$i]->descuento)){
                                $xml->text(number_format((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - (($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad * $detalles_producto[$i]->descuento)/100)), 2, '.', ''));
                            }else{
                                $xml->text(number_format(($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad), 2, '.', ''));
                            }
                        }*/

                $xml->endElement();
                if ($detalles_producto[$i]->codigo_iva == 2) {
					if($re->existe_iva_8>0){
						$xml->startElement("valor");
						//$xml->text(number_format(( ((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - $detalles_producto[$i]->descuento) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad)) * 0.08), 2, '.', ''));
						if ($detalles_producto[$i]->p_descuento == 1) {
							$xml->text(number_format((((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - $detalles_producto[$i]->descuento) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad)) * 0.08), 4, '.', ''));
						} else {
							if (isset($detalles_producto[$i]->descuento)) {
								$xml->text(number_format(((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - (($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad * $detalles_producto[$i]->descuento) / 100) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad)) * 0.08), 4, '.', ''));
							} else {
								$xml->text(number_format(((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad)) * 0.08), 4, '.', ''));
							}
						}
						$xml->endElement();
					}else{
						$xml->startElement("valor");
						//$xml->text(number_format(( ((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - $detalles_producto[$i]->descuento) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad)) * 0.12), 2, '.', ''));
						if ($detalles_producto[$i]->p_descuento == 1) {
							$xml->text(number_format((((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - $detalles_producto[$i]->descuento) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad)) * 0.12), 4, '.', ''));
						} else {
							if (isset($detalles_producto[$i]->descuento)) {
								$xml->text(number_format(((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - (($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad * $detalles_producto[$i]->descuento) / 100) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad)) * 0.12), 4, '.', ''));
							} else {
								$xml->text(number_format(((($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) + ($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad)) * 0.12), 4, '.', ''));
							}
						}
						$xml->endElement();
					}
                } else {
                    $xml->startElement("valor");
                    $xml->text(0.00);
                    $xml->endElement();
                }
                $xml->endElement();
            }

            if ($detalles_producto[$i]->id_ice) {
                if ($detalles_producto[$i]->total_ice) {
                    $xml->startElement('impuesto');
                    $xml->startElement('codigo');
                    $xml->text(3);
                    $xml->endElement();

                    $xml->startElement("codigoPorcentaje");
                    $xml->text(substr($detalles_producto[$i]->codigo_ice, 0, 4));
                    $xml->endElement();

                    $xml->startElement("tarifa");
                    $xml->text(0);
                    $xml->endElement();

                    $xml->startElement('baseImponible');
                    if ($detalles_producto[$i]->p_descuento == 1) {
                        $xml->text(number_format(($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - $detalles_producto[$i]->descuento, 4, '.', ''));
                    } else {
                        if (isset($detalles_producto[$i]->descuento)) {
                            $xml->text(number_format(($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad) - (($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad * $detalles_producto[$i]->descuento) / 100), 4, '.', ''));
                        } else {
                            $xml->text(number_format(($detalles_producto[$i]->precio * $detalles_producto[$i]->cantidad), 4, '.', ''));
                        }
                    }
                    $xml->endElement();

                    $xml->startElement("valor");
                    if ($detalles_producto[$i]->total_ice) {
                        $xml->text($detalles_producto[$i]->total_ice * $detalles_producto[$i]->cantidad);
                    } else {
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
        if(isset($recupera[0]->ruc_empresa)){
            // si es diferente a la empresa  AMERICAN PETROLEUM AND INDUSTRIAL SUPPLY S.A. PETROINSUPPLY
            if($recupera[0]->ruc_empresa!=='1791974808001'){
                $xml->startElement('infoAdicional');
            }else{
                if($re->orden_compra!==null || (isset($re->migo_factura) && $re->migo_factura!==null) || $re->observacion!==null){
                    $xml->startElement('infoAdicional');
                }
            }
        }
        
        if(isset($recupera[0]->ruc_empresa)){
            // si es diferente a la empresa  AMERICAN PETROLEUM AND INDUSTRIAL SUPPLY S.A. PETROINSUPPLY
            if($recupera[0]->ruc_empresa!=='1791974808001'){
                if ($re->direccion) {
                    $xml->startElement('campoAdicional');
                    $xml->writeAttribute("nombre", "Dirección");
                    $xml->text(str_replace(array("-", "\"", "'", "/", "–", "ñ"), array("", "", "", "", "", "n"), $re->direccion));
                    $xml->endElement();
                }
                if ($re->telefono) {
                    $xml->startElement('campoAdicional');
                    $xml->writeAttribute("nombre", "Teléfono");
                    $xml->text(str_replace(array('/', "(", ")", "–", "-"), array(' ', '', '', ' ', ' '), $re->telefono));
                    $xml->endElement();
                }
                if ($re->email) {
                    $xml->startElement('campoAdicional');
                    $xml->writeAttribute("nombre", "Email");
                    $xml->text(str_replace(array(';'), array(' '), $re->email));
                    $xml->endElement();
                }
                if($re->orden_compra!==null){
                    $xml->startElement('campoAdicional');
                    $xml->writeAttribute("nombre", "OrdenCompra");
                    $xml->text(str_replace(array(';'), array(' '), $re->orden_compra));
                    $xml->endElement();
                }
                if(isset($re->migo_factura)){
                    if($re->migo_factura!==null){
                        $xml->startElement('campoAdicional');
                        $xml->writeAttribute("nombre", "Migo");
                        $xml->text(str_replace(array(';'), array(' '), $re->migo_factura));
                        $xml->endElement();
                    }
                }
                if($re->observacion!==null){
                    $xml->startElement('campoAdicional');
                    $xml->writeAttribute("nombre", "Descripcion");
                    $xml->text(str_replace(array(';'), array(' '), $re->observacion));
                    $xml->endElement();
                }
            }else{
                if($re->orden_compra!==null){
                    $xml->startElement('campoAdicional');
                    $xml->writeAttribute("nombre", "ordencompra");
                    $xml->text(str_replace(array(';'), array(' '), $re->orden_compra));
                    $xml->endElement();
                }
                if(isset($re->migo_factura)){
                    if($re->migo_factura!==null){
                        $xml->startElement('campoAdicional');
                        $xml->writeAttribute("nombre", "migo");
                        $xml->text(str_replace(array(';'), array(' '), $re->migo_factura));
                        $xml->endElement();

                        $xml->startElement('campoAdicional');
                        $xml->writeAttribute("nombre", "PreEntrada");
                        $xml->text(str_replace(array(';'), array(' '), $re->migo_factura));
                        $xml->endElement();
                    }
                }
                if(isset($re->clave_acceso_guia)){
                    if($re->clave_acceso_guia!==null){
                        $cod1=substr($re->clave_acceso_guia,24,3);
                        $cod2=substr($re->clave_acceso_guia,27,3);
                        $cod3=substr($re->clave_acceso_guia,30,9);
                        $cod_gen=$cod1."-".$cod2."-".$cod3;
                        $xml->startElement('campoAdicional');
                        $xml->writeAttribute("nombre", "Guia Remision");
                        $xml->text(str_replace(array(';'), array(' '), $cod_gen));
                        $xml->endElement();
                    }
                }
                if($re->observacion!==null){
                    $xml->startElement('campoAdicional');
                    $xml->writeAttribute("nombre", "Descripcion");
                    $xml->text(str_replace(array(';'), array(' '), $re->observacion));
                    $xml->endElement();
                }
            }
        }
        
        
        
        // if($recupera[0]->leyenda==2){
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Leyenda");
        //     $xml->text("AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN MICROEMPRESA");
        //     $xml->endElement();
        // }else{
        //     if($recupera[0]->leyenda!==null && $recupera[0]->leyenda!=='null'){
        //         $xml->startElement('campoAdicional');
        //         $xml->writeAttribute("nombre", "Leyenda");
        //         $xml->text($recupera[0]->leyenda);
        //         $xml->endElement();
        //     }
        // }

        // if ($recupera[0]->leyenda == 3) {
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Regimen");
        //     $xml->text("Contribuyente Regimen RIMPE");
        //     $xml->endElement();
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Agente de Retención");
        //     $xml->text("RESOLUCION NAC No DNCRASC20-00000001");
        //     $xml->endElement();
        // } else {
            ////////////////////////////////
            // $bus0 = strpos($recupera[0]->leyenda, "NEGOCIO POPULAR");
            // if($bus0==true){
            //         $xml->startElement('campoAdicional');
            //         $xml->writeAttribute("nombre", "Regimen");
            //         $xml->text("Contribuyente Negocio Popular Regimen RIMPE");
            //         $xml->endElement();
            // }
            /////////////////////////////////
            // else{
                // $bus3 = strpos($recupera[0]->leyenda, "CONTRIBUYENTE REGIMEN RIMPE");
                // if ($recupera[0]->leyenda == "CONTRIBUYENTE REGIMEN RIMPE") {
                //     $xml->startElement('campoAdicional');
                //     $xml->writeAttribute("nombre", "Regimen");
                //     $xml->text("Contribuyente Regimen RIMPE");
                //     $xml->endElement();
                // } else {
                //     $bus2 = strpos($recupera[0]->leyenda, "RETENCIÓN");
                //     if ($bus2 == true) {
                //         $bus4 = substr($recupera[0]->leyenda, 21, strlen($recupera[0]->leyenda));
                //         $ret = substr($recupera[0]->leyenda, -8);
                //         $nro_ret = intval($ret);
                //         $xml->startElement('campoAdicional');
                //         $xml->writeAttribute("nombre", "Agente de Retención");
                //         $xml->text($bus4);
                //         $xml->endElement();
                //     }
                // }
            // }
            
        //}


        // if (isset($recupera[0]->orden_compra)) {
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "OrdenCompra");
        //     $xml->text($recupera[0]->orden_compra);
        //     $xml->endElement();
        // }
        // if (isset($recupera[0]->migo_factura)) {
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Migo");
        //     $xml->text($recupera[0]->migo_factura);
        //     $xml->endElement();
        // }
        if(isset($recupera[0]->ruc_empresa)){
            // si es diferente a la empresa  AMERICAN PETROLEUM AND INDUSTRIAL SUPPLY S.A. PETROINSUPPLY
            if($recupera[0]->ruc_empresa!=='1791974808001'){
                $xml->endElement();
            }else{
                if($re->orden_compra!==null || (isset($re->migo_factura) && $re->migo_factura!==null) || $re->observacion!==null){
                    $xml->endElement();
                }
            }
        }
        
        $xml->endElement();
        $xml->endDocument();
        return ["recupera" => $recupera[0]];
    }
    public function e_guia(Request $re)
    {
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
        $xml->text(str_replace(array("-", "\"", "'", "", "/", ":", "–"), "", $re->ambiente));
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

        $sel_empresa = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");

        if($sel_empresa[0]->leyenda==2){
            $xml->startElement("regimenMicroempresas");
            $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
            $xml->endElement();
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
        }else{
                $bus=strpos($sel_empresa[0]->leyenda,"MICROEMPRESA");
                if($bus==true){
                    $xml->startElement("regimenMicroempresas");
                    $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
                    $xml->endElement();
                }
                // else{
                //     $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                //     if($bus2==true){
                //         $ret=substr($sel_empresa[0]->leyenda,-8);
                //         $nro_ret=intval($ret);
                //         $xml->startElement("agenteRetencion");
                //         $xml->text($nro_ret);
                //         $xml->endElement();
                //     }
                // }
            
            
        }
        if($sel_empresa[0]->leyenda==3){
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
            $xml->startElement("contribuyenteRimpe");
            $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
            $xml->endElement();
            
        }else{
            $bus01=strpos($sel_empresa[0]->leyenda,"CONTRIBUYENTE REGIMEN RIMPE");
            if($sel_empresa[0]->leyenda=="CONTRIBUYENTE REGIMEN RIMPE"){
                $xml->startElement("contribuyenteRimpe");
                $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
                $xml->endElement();
            }else{
                $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                if($bus2==true){
                        $ret=substr($sel_empresa[0]->leyenda,-8);
                        $nro_ret=intval($ret);
                        $xml->startElement("agenteRetencion");
                        $xml->text($nro_ret);
                        $xml->endElement();
                }
            }
        }

        $xml->endElement();

        $xml->startElement("infoGuiaRemision");

        $xml->startElement("dirEstablecimiento");
        $xml->text($re->direccion_empresa);
        $xml->endElement();
        if($re->dir_partida_tr){
            $xml->startElement("dirPartida");
            $xml->text(str_replace(array("-", "\"", "'", "", "/", ":", "–"), "", $re->dir_partida_tr));
            $xml->endElement();
        }else{
            $xml->startElement("dirPartida");
            $xml->text(str_replace(array("-", "\"", "'", "", "/", ":", "–"), "", $re->direccion));
            $xml->endElement();
        }
        

        $xml->startElement("razonSocialTransportista");
        $xml->text(str_replace(array("-", "\"", "'", "", "/", ":", "–"), "", $re->razon_social_tr));
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
        if($sel_empresa[0]->noresolucion){
            $xml->startElement("contribuyenteEspecial");
            $xml->text($sel_empresa[0]->noresolucion);
            $xml->endElement();
        }
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
        if($re->direccion_tr){
            $xml->startElement("dirDestinatario");
            $xml->text(str_replace(array("-", "\"", "'", "", "/", ":", "–"), "", $re->direccion_tr));
            $xml->endElement();
        }else{
            $xml->startElement("dirDestinatario");
            $xml->text(str_replace(array("-", "\"", "'", "", "/", ":", "–"), "", $re->direccion));
            $xml->endElement();
        }
        

        $xml->startElement("motivoTraslado");
        $xml->text($re->motivo_translado_tr);
        $xml->endElement();
        if ($re->doc_aduanero_tr) {
            $xml->startElement("docAduaneroUnico");
            $xml->text($re->doc_aduanero_tr);
            $xml->endElement();
        }
        if ($re->cod_establecimiento_tr) {
            $xml->startElement("codEstabDestino");
            $xml->text($re->cod_establecimiento_tr);
            $xml->endElement();
        }
        if ($re->ruta_tr) {
            $xml->startElement("ruta");
            $xml->text($re->ruta_tr);
            $xml->endElement();
        }
        if ($re->cod_sustento_tr) {
            $xml->startElement("codDocSustento");
            $xml->text(str_pad($re->cod_sustento_tr, 2, "0", STR_PAD_LEFT));
            $xml->endElement();
        }

        $xml->startElement("numDocSustento");
        $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT) . '-' . str_pad($re->codigope, 3, "0", STR_PAD_LEFT) . "-" . "000000001");
        $xml->endElement();
        $rand = rand(000000001, 9999999999);
        if ($re->num_aut_sustento_tr) {
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
            $xml->text(str_replace(array("-", "\"", "'", "_", "/", ":", "–"), "", $det[$i]["descripcion"]));
            $xml->endElement();

            $xml->startElement("cantidad");
            $xml->text(number_format($det[$i]["cantidad"],2,".",""));
            $xml->endElement();

            $xml->endElement();
        }

        $xml->endElement();

        $xml->endElement();
        $xml->endElement();
        $xml->startElement("infoAdicional");
        if ($re->email) {
            $xml->startElement("campoAdicional");
            $xml->writeAttribute("nombre", "email");
            $xml->text($re->email);
            $xml->endElement();
        }
        if ($re->direccion) {
            $xml->startElement("campoAdicional");
            $xml->writeAttribute("nombre", "direccion");
            $xml->text(str_replace(array("-", "\"", "'", "_", "/", ":", "–"), "", $re->direccion));
            $xml->endElement();
        }
        $recupera = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");
        // $bus0 = strpos($recupera[0]->leyenda, "NEGOCIO POPULAR");
        // if($bus0==true){
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Regimen");
        //     $xml->text("Contribuyente Negocio Popular Regimen RIMPE");
        //     $xml->endElement();
        // }
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();
    }
    public function e_comproretenc(Request $re)
    {
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

        $sel_empresa = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");

        if($sel_empresa[0]->leyenda==2){
            $xml->startElement("regimenMicroempresas");
            $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
            $xml->endElement();
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
        }else{
                $bus=strpos($sel_empresa[0]->leyenda,"MICROEMPRESA");
                if($bus==true){
                    $xml->startElement("regimenMicroempresas");
                    $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
                    $xml->endElement();
                }
                // else{
                //     $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                //     if($bus2==true){
                //         $ret=substr($sel_empresa[0]->leyenda,-8);
                //         $nro_ret=intval($ret);
                //         $xml->startElement("agenteRetencion");
                //         $xml->text($nro_ret);
                //         $xml->endElement();
                //     }
                // }
            
            
        }
        if($sel_empresa[0]->leyenda==3){
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
            $xml->startElement("contribuyenteRimpe");
            $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
            $xml->endElement();
        }else{
            $bus01=strpos($sel_empresa[0]->leyenda,"CONTRIBUYENTE REGIMEN RIMPE");
            if($sel_empresa[0]->leyenda=="CONTRIBUYENTE REGIMEN RIMPE"){
                $xml->startElement("contribuyenteRimpe");
                $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
                $xml->endElement();
            }else{
                $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                if($bus2==true){
                        $ret=substr($sel_empresa[0]->leyenda,-8);
                        $nro_ret=intval($ret);
                        $xml->startElement("agenteRetencion");
                        $xml->text($nro_ret);
                        $xml->endElement();
                }
            }
        }

        $xml->endElement();
        //infoCompRetencion
        $xml->startElement("infoCompRetencion");

        $xml->startElement("fechaEmision");
        if(isset($re->id_liquidacion_compra)){
            $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
        }else{
            $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
        }
        
        $xml->endElement();
        if(isset($re->direccion_establecimiento)){
            if($re->direccion_establecimiento){
                $xml->startElement("dirEstablecimiento");
                $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->direccion_establecimiento));
                $xml->endElement();
            }
        }
        

        if ($re->obligado_contabilidad == 0) {
            $obligado = "NO";
        } else {
            $obligado = "SI";
        }
        if($sel_empresa[0]->noresolucion){
            $xml->startElement("contribuyenteEspecial");
            $xml->text($sel_empresa[0]->noresolucion);
            $xml->endElement();
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
        $xml->text(str_replace('\"', "", $re->nombre_proveedor));
        $xml->endElement();

        $xml->startElement("identificacionSujetoRetenido");
        $xml->text($re->identif_proveedor);
        $xml->endElement();

        $xml->startElement("periodoFiscal");
        if(isset($re->id_liquidacion_compra)){
            $xml->text(date('m/Y', strtotime($re->fecha_emision)));
        }else{
            $xml->text(date('m/Y', strtotime($re->fech_emision)));
        }
        
        $xml->endElement();

        $xml->endElement();
        //impuestos
        $xml->startElement("impuestos");
        if(isset($re->id_liquidacion_compra)){
            $det = Retencion_Liquidacion_Compra::addSelect([
                'codigosri' => Impuesto::select('cod_imp')
                    ->whereColumn('id_imp', 'retencion.id_impuesto')
            ])
                ->select("retencion_liquidacion_compra.*")
                ->join("retencion", "retencion.id_retencion", "=", "retencion_liquidacion_compra.id_retencion_iva")
                ->where("retencion_liquidacion_compra.id_liquidacion_compra", "=", $re->id_liquidacion_compra)->get();
        }else{
            $det = Retencion_factura_comp::addSelect([
                'codigosri' => Impuesto::select('cod_imp')
                    ->whereColumn('id_imp', 'retencion.id_impuesto')
            ])
                ->select("retencion_factura_comp.*")
                ->join("retencion", "retencion.id_retencion", "=", "retencion_factura_comp.id_retencion_iva")
                ->where("retencion_factura_comp.id_factura", "=", $re->id_factcompra)->get();
        }
        

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
                if($det[$i]["baseiva"]>0){
                    $xml->startElement("baseImponible");
                    $xml->text(number_format($det[$i]["baseiva"], 2, '.', ''));
                    $xml->endElement();
                }else{
                    $xml->startElement("baseImponible");
                    $xml->text(number_format(($det[$i]["cantidadiva"] * 100) / str_replace("%", "", $det[$i]["porcentajeiva"]), 2, '.', ''));
                    $xml->endElement();
                }
                

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
                if(isset($re->id_liquidacion_compra)){
                    $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
                }else{
                    $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
                }
                
                $xml->endElement();

                $xml->endElement();
            }
        }
        if(isset($re->id_liquidacion_compra)){
            $det = Retencion_Liquidacion_Compra::addSelect([
                'codigosri' => Impuesto::select('cod_imp')
                    ->whereColumn('id_imp', 'retencion.id_impuesto')
            ])
                ->select("retencion_liquidacion_compra.*", "impuesto.cod_imp", "liquidacion_compra.descripcion")
                ->join("retencion", "retencion.id_retencion", "=", "retencion_liquidacion_compra.id_retencion_renta")
                ->join("impuesto", "impuesto.id_imp", "=", "retencion.id_impuesto")
                ->join("liquidacion_compra", "liquidacion_compra.id_liquidacion_compra", "=", "retencion_liquidacion_compra.id_liquidacion_compra")
                ->where("retencion_liquidacion_compra.id_liquidacion_compra", "=", $re->id_liquidacion_compra)->get();
        }else{
            $det = Retencion_factura_comp::addSelect([
                'codigosri' => Impuesto::select('cod_imp')
                    ->whereColumn('id_imp', 'retencion.id_impuesto')
            ])
                ->select("retencion_factura_comp.*", "impuesto.cod_imp", "factura_compra.descripcion")
                ->join("retencion", "retencion.id_retencion", "=", "retencion_factura_comp.id_retencion_renta")
                ->join("impuesto", "impuesto.id_imp", "=", "retencion.id_impuesto")
                ->join("factura_compra", "factura_compra.id_factcompra", "=", "retencion_factura_comp.id_factura")
                ->where("retencion_factura_comp.id_factura", "=", $re->id_factcompra)->get();
        }
        

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
                if(isset($re->id_liquidacion_compra)){
                    $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
                }else{
                    $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
                }
                
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
        if (isset($re->email)) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Email");
            $xml->text($re->email);
            $xml->endElement();
        }
        if (strlen($re->nrcasa) >= 1) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Numero de casa");
            $xml->text($re->nrcasa);
            $xml->endElement();
        }
        $recupera = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");
        // $bus0 = strpos($recupera[0]->leyenda, "NEGOCIO POPULAR");
        // if($bus0==true){
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Regimen");
        //     $xml->text("Contribuyente Negocio Popular Regimen RIMPE");
        //     $xml->endElement();
        // }
        $xml->endElement();
        $xml->endElement(); //fin comprobanteRetencion
        $xml->endDocument();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }

    public function enotacredito(Request $re)
    {
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

        $sel_empresa = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");

        if($sel_empresa[0]->leyenda==2){
            $xml->startElement("regimenMicroempresas");
            $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
            $xml->endElement();
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
        }else{
                $bus=strpos($sel_empresa[0]->leyenda,"MICROEMPRESA");
                if($bus==true){
                    $xml->startElement("regimenMicroempresas");
                    $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
                    $xml->endElement();
                }
                // else{
                //     $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                //     if($bus2==true){
                //         $ret=substr($sel_empresa[0]->leyenda,-8);
                //         $nro_ret=intval($ret);
                //         $xml->startElement("agenteRetencion");
                //         $xml->text($nro_ret);
                //         $xml->endElement();
                //     }
                // }
            
            
        }
        if($sel_empresa[0]->leyenda==3){
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
            $xml->startElement("contribuyenteRimpe");
            $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
            $xml->endElement();
        }else{
            $bus01=strpos($sel_empresa[0]->leyenda,"CONTRIBUYENTE REGIMEN RIMPE");
            if($sel_empresa[0]->leyenda=="CONTRIBUYENTE REGIMEN RIMPE"){
                $xml->startElement("contribuyenteRimpe");
                $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
                $xml->endElement();
            }else{
                $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                if($bus2==true){
                        $ret=substr($sel_empresa[0]->leyenda,-8);
                        $nro_ret=intval($ret);
                        $xml->startElement("agenteRetencion");
                        $xml->text($nro_ret);
                        $xml->endElement();
                }
            }
        }

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
        } else {
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

        if($sel_empresa[0]->noresolucion){
            $xml->startElement("contribuyenteEspecial");
            $xml->text($sel_empresa[0]->noresolucion);
            $xml->endElement();
        }

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
        $xml->text(substr($re->autorizacionfactura, 0, 3) . "-" . substr($re->autorizacionfactura, 3, 3) . "-" . substr($re->autorizacionfactura, 6, 9));
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
        $det = Detalle_nota_credito::select("detalle_nota_credito.*", "producto.cod_principal", "producto.cod_alterno", "producto.descripcion", "iva.codigo as codigo_iva")
            ->join("producto", "producto.id_producto", "=", "detalle_nota_credito.id_producto")
            ->join("iva", "iva.id_iva", "=", "detalle_nota_credito.id_iva")
            ->where("detalle_nota_credito.id_nota_credito", "=", $re->id_nota_credito)->get();
        $result = DB::select("SELECT SUM(detalle_nota_credito.total) AS total, iva.codigo as codigo_iva FROM detalle_nota_credito INNER JOIN iva ON iva.id_iva = detalle_nota_credito.id_iva 
            INNER JOIN producto ON producto.id_producto = detalle_nota_credito.id_producto WHERE id_nota_credito ={$re->id_nota_credito} GROUP BY iva.codigo");
        for ($i = 0; $i < count($result); $i++) {
            $xml->startElement("totalImpuesto");
            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            $xml->startElement("codigoPorcentaje");
            $xml->text($result[$i]->codigo_iva);
            $xml->endElement();

            $xml->startElement("baseImponible");
            $xml->text(number_format($result[$i]->total, 2, '.', ''));
            $xml->endElement();
            if ($result[$i]->codigo_iva == 2) {
                $xml->startElement("valor");
                $xml->text(number_format((($result[$i]->total) * 0.12), 2, '.', ''));
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
            $xml->text(number_format($det[$i]["cantidad"],2,".",""));
            $xml->endElement();
            $xml->startElement("precioUnitario");
            $xml->text(number_format($det[$i]["precio"],2,".",""));
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
        $recupera = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");
        // $bus0 = strpos($recupera[0]->leyenda, "NEGOCIO POPULAR");
        // if($bus0==true){
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Regimen");
        //     $xml->text("Contribuyente Negocio Popular Regimen RIMPE");
        //     $xml->endElement();
        // }
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
    public function enotadebito(Request $re)
    {
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

        $sel_empresa = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");

        if($sel_empresa[0]->leyenda==2){
            $xml->startElement("regimenMicroempresas");
            $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
            $xml->endElement();
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
        }else{
                $bus=strpos($sel_empresa[0]->leyenda,"MICROEMPRESA");
                if($bus==true){
                    $xml->startElement("regimenMicroempresas");
                    $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
                    $xml->endElement();
                }
                // else{
                //     $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                //     if($bus2==true){
                //         $ret=substr($sel_empresa[0]->leyenda,-8);
                //         $nro_ret=intval($ret);
                //         $xml->startElement("agenteRetencion");
                //         $xml->text($nro_ret);
                //         $xml->endElement();
                //     }
                // }
            
            
        }
        if($sel_empresa[0]->leyenda==3){
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
            $xml->startElement("contribuyenteRimpe");
            $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
            $xml->endElement();
        }else{
            $bus01=strpos($sel_empresa[0]->leyenda,"CONTRIBUYENTE REGIMEN RIMPE");
            if($sel_empresa[0]->leyenda=="CONTRIBUYENTE REGIMEN RIMPE"){
                $xml->startElement("contribuyenteRimpe");
                $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
                $xml->endElement();
            }else{
                $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                if($bus2==true){
                        $ret=substr($sel_empresa[0]->leyenda,-8);
                        $nro_ret=intval($ret);
                        $xml->startElement("agenteRetencion");
                        $xml->text($nro_ret);
                        $xml->endElement();
                }
            }
        }

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
        if($sel_empresa[0]->noresolucion){
            $xml->startElement("contribuyenteEspecial");
            $xml->text($sel_empresa[0]->noresolucion);
            $xml->endElement();
        }
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
        $xml->text(substr($re->clave_acceso, 24, 3) . "-" . substr($re->clave_acceso, 27, 3) . "-" . substr($re->clave_acceso, 30, 9));
        $xml->endElement();
        $xml->startElement("fechaEmisionDocSustento");
        $xml->text(date('d/m/Y', strtotime($re->fechaAutorizacion)));
        $xml->endElement();
        $xml->startElement("totalSinImpuestos");
        $xml->text($re->subtotal_sin_impuesto);
        $xml->endElement();

        //Impuestos
        $xml->startElement('impuestos');
        $det = Detalle_nota_debito::select("detalle_nota_debito.*", "producto.cod_principal", "producto.cod_alterno", "producto.descripcion", "iva.codigo as codigo_iva")
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
        $respe = substr($re->clave_acceso, 24, 3) . substr($re->clave_acceso, 27, 3) . substr($re->clave_acceso, 30, 9);
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
            if (isset($cxc[$i]["codigopagos"])) {
                $xml->startElement("formaPago");
                $xml->text($cxc[$i]["codigopagos"]);
                $xml->endElement();
            } else {
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
        // $bus0 = strpos($sel_empresa[0]->leyenda, "NEGOCIO POPULAR");
        // if($bus0==true){
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Regimen");
        //     $xml->text("Contribuyente Negocio Popular Regimen RIMPE");
        //     $xml->endElement();
        // }
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
    public function r_factura()
    {
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
    public function r_facturas()
    {
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
    public function e_liquidacioncompra(Request $re)
    {
        file_put_contents(constant("DATA_EMPRESA") . $re->id_empresa . "/comprobantes/liquidacion_compra/" . $re->clave_acceso . ".xml", "");
        $hoy = Carbon::now();
        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $re->id_empresa . '/comprobantes/liquidacion_compra/' . $re->clave_acceso . ".xml");
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
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->razon_social));
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->nombre_empresa));
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
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->direccion_empresa));
        $xml->endElement();

        $sel_empresa = DB::select("SELECT * from empresa where id_empresa={$re->id_empresa}");

        if($sel_empresa[0]->leyenda==2){
            $xml->startElement("regimenMicroempresas");
            $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
            $xml->endElement();
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
        }else{
                $bus=strpos($sel_empresa[0]->leyenda,"MICROEMPRESA");
                if($bus==true){
                    $xml->startElement("regimenMicroempresas");
                    $xml->text("CONTRIBUYENTE RÉGIMEN MICROEMPRESAS");
                    $xml->endElement();
                }
                // else{
                //     $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                //     if($bus2==true){
                //         $ret=substr($sel_empresa[0]->leyenda,-8);
                //         $nro_ret=intval($ret);
                //         $xml->startElement("agenteRetencion");
                //         $xml->text($nro_ret);
                //         $xml->endElement();
                //     }
                // }
            
            
        }
        if($sel_empresa[0]->leyenda==3){
            $xml->startElement("agenteRetencion");
            $xml->text(1);
            $xml->endElement();
            $xml->startElement("contribuyenteRimpe");
            $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
            $xml->endElement();
        }else{
            $bus01=strpos($sel_empresa[0]->leyenda,"CONTRIBUYENTE REGIMEN RIMPE");
            if($sel_empresa[0]->leyenda=="CONTRIBUYENTE REGIMEN RIMPE"){
                $xml->startElement("contribuyenteRimpe");
                $xml->text("CONTRIBUYENTE RÉGIMEN RIMPE");
                $xml->endElement();
            }else{
                $bus2=strpos($sel_empresa[0]->leyenda,"RETENCIÓN");
                if($bus2==true){
                        $ret=substr($sel_empresa[0]->leyenda,-8);
                        $nro_ret=intval($ret);
                        $xml->startElement("agenteRetencion");
                        $xml->text($nro_ret);
                        $xml->endElement();
                }
            }
        }

        $xml->endElement();

        $xml->startElement("infoLiquidacionCompra");

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->direccion_establecimiento));
        $xml->endElement();

        
        if($sel_empresa[0]->noresolucion){
            $xml->startElement("contribuyenteEspecial");
            $xml->text($sel_empresa[0]->noresolucion);
            $xml->endElement();
        }
        
        if ($re->obligado_contabilidad == 0) {
            $obligado = "NO";
        } else {
            $obligado = "SI";
        }
        $xml->startElement("obligadoContabilidad");
        $xml->text($obligado);
        $xml->endElement();
        if ($re->tipo_identificacion == "Cédula de Identidad" || $re->tipo_identificacion == "Cedula") {
            $xml->startElement("tipoIdentificacionProveedor");
            $xml->text('05');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Ruc") {
            $xml->startElement("tipoIdentificacionProveedor");
            $xml->text('04');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Pasaporte") {
            $xml->startElement("tipoIdentificacionProveedor");
            $xml->text('06');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Consumidor Final") {
            $xml->startElement("tipoIdentificacionProveedor");
            $xml->text('07');
            $xml->endElement();
        }

        $xml->startElement("razonSocialProveedor");
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->nombre_proveedor));
        $xml->endElement();

        $xml->startElement("identificacionProveedor");
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->identif_proveedor));
        $xml->endElement();

        $xml->startElement("direccionProveedor");
        $xml->text(str_replace(array("-", "_", ":", "–", "Ñ", "ñ", "ì", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "Í"), array("", "", "", "", "N", "n", "i", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "I"), $re->direccion_prov));
        $xml->endElement();

        $xml->startElement("totalSinImpuestos");
        $xml->text($re->subtotal_sin_impuesto);
        $xml->endElement();

        $xml->startElement("totalDescuento");
        $xml->text($re->descuento);
        $xml->endElement();

        // $xml->startElement("codDocReembolso");
        // $xml->text(0);
        // $xml->endElement();

        // $xml->startElement("totalComprobantesReembolso");
        // $xml->text($re->totalComprobantesReembolso);
        // $xml->endElement();

        // $xml->startElement("totalBaseImponibleReembolso");
        // $xml->text($re->totalBaseImponibleReembolso);
        // $xml->endElement();

        // $xml->startElement("totalImpuestoReembolso");
        // $xml->text($re->totalImpuestoReembolso);
        // $xml->endElement();


        $xml->startElement("totalConImpuestos");

        // $xml->startElement("totalImpuesto");

        // $xml->startElement("codigo");
        // $xml->text(2);
        // $xml->endElement();

        // $xml->startElement("codigoPorcentaje");
        // $xml->text($re->codigoPorcentaje);
        // $xml->endElement();

        // $xml->startElement("descuentoAdicional");
        // $xml->text($re->descuentoAdicional);
        // $xml->endElement();

        // $xml->startElement("baseImponible");
        // $xml->text($re->baseImponible);
        // $xml->endElement();

        // $xml->startElement("tarifa");
        // $xml->text($re->tarifa);
        // $xml->endElement();

        // $xml->startElement("valor");
        // $xml->text($re->valor);
        // $xml->endElement();

        $det = Detalle_liquidacion_compra::select("detalle_liquidacion_compra.*", "producto.cod_principal", "producto.cod_alterno", "producto.total_ice", "producto.descripcion", "iva.codigo as codigo_iva", "iva.nombre as nombre_iva", "ice.codigo AS codigo_ice")
            ->join("iva", "iva.id_iva", "=", "detalle_liquidacion_compra.id_iva")
            ->join("ice", "ice.id_ice", "=", "detalle_liquidacion_compra.id_ice")
            ->join("producto", "producto.id_producto", "=", "detalle_liquidacion_compra.id_producto")
            ->where("id_liquidacion_compra", "=", $re->id_liquidacion_compra)->get();
        $result = DB::select("SELECT IF(SUM(producto.total_ice)>0, SUM(detalle_liquidacion_compra.total + (detalle_liquidacion_compra.cantidad * producto.total_ice)), SUM(detalle_liquidacion_compra.total)) AS total, iva.codigo as codigo_iva FROM detalle_liquidacion_compra INNER JOIN iva ON iva.id_iva = detalle_liquidacion_compra.id_iva INNER JOIN ice ON ice.id_ice = detalle_liquidacion_compra.id_ice INNER JOIN producto ON producto.id_producto = detalle_liquidacion_compra.id_producto WHERE id_liquidacion_compra = " . $re->id_liquidacion_compra . " GROUP BY iva.codigo");

        for ($i = 0; $i < count($result); $i++) {
            //cambiar de manera global los valores de iva, ice y irbpnr
            $xml->startElement("totalImpuesto");
            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            $xml->startElement("codigoPorcentaje");
            $xml->text($result[$i]->codigo_iva);
            $xml->endElement();
            $xml->startElement("baseImponible");
            $xml->text(number_format($result[$i]->total, 2, '.', ''));
            $xml->endElement();
            if ($result[$i]->codigo_iva == 2) {
                $xml->startElement("tarifa");
                $xml->text(12);
                $xml->endElement();
                $xml->startElement("valor");
                $xml->text(number_format((($result[$i]->total) * 0.12), 2, '.', ''));
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
        $xml->endElement();

        //$xml->endElement();

        $xml->startElement("importeTotal");
        $xml->text($re->valor_total);
        $xml->endElement();

        $xml->startElement("moneda");
        $xml->text($re->moneda);
        $xml->endElement();

        $xml->startElement("pagos");
        $cxc = Liquidaction_compra_pagos::select("liquidacion_compra_pagos.*", "forma_pagos_sri.codigo as codigopagos")
            ->leftJoin("forma_pagos", "forma_pagos.id_forma_pagos", "=", "liquidacion_compra_pagos.id_formas_pagos")
            ->leftJoin("forma_pagos_sri", "forma_pagos_sri.id_forma_pagos_sri", "=", "forma_pagos.id_forma_pagos_sri")
            ->where("id_liquidacion_compra", "=", $re->id_liquidacion_compra)->get();

        for ($i = 0; $i < count($cxc); $i++) {
            $xml->startElement("pago");
            if (isset($cxc[$i]["codigopagos"])) {
                $xml->startElement("formaPago");
                $xml->text($cxc[$i]["codigopagos"]);
                $xml->endElement();
            } else {
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
            $xml->text(str_replace("í", "i", $cxc[$i]["unidad_tiempo"]));
            $xml->endElement();
            $xml->endElement();
        }

        $rfi = DB::select("SELECT * from retencion_liquidacion_compra where id_retencion_iva is not null and id_liquidacion_compra=".$re->id_liquidacion_compra);
        for ($i = 0; $i < count($rfi); $i++) {
            $xml->startElement("pago");
            $xml->startElement("formaPago");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("total");
            $xml->text($rfi[$i]->cantidadiva);
            $xml->endElement();
            $xml->startElement("plazo");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("unidadTiempo");
            $xml->text("Dias");
            $xml->endElement();
            $xml->endElement();
        }
        $rfr = DB::select("SELECT * from retencion_liquidacion_compra where id_retencion_renta is not null and id_liquidacion_compra=".$re->id_liquidacion_compra);
        for ($i = 0; $i < count($rfr); $i++) {
            $xml->startElement("pago");
            $xml->startElement("formaPago");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("total");
            $xml->text($rfr[$i]->cantidadrenta);
            $xml->endElement();
            $xml->startElement("plazo");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("unidadTiempo");
            $xml->text("Dias");
            $xml->endElement();
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endElement();

        // $xml->startElement("detalles");

        // $xml->startElement("detalle");

        // $xml->startElement("codigoPrincipal");
        // $xml->text($re->codigoPrincipal);
        // $xml->endElement();

        // $xml->startElement("codigoAuxiliar");
        // $xml->text($re->codigoAuxiliar);
        // $xml->endElement();

        // $xml->startElement("descripcion");
        // $xml->text($re->descripcion);
        // $xml->endElement();

        // $xml->startElement("unidadMedida");
        // $xml->text($re->unidadMedida);
        // $xml->endElement();

        // $xml->startElement("cantidad");
        // $xml->text($re->cantidad);
        // $xml->endElement();

        // $xml->startElement("precioUnitario");
        // $xml->text($re->precioUnitario);
        // $xml->endElement();

        // $xml->startElement("descuento");
        // $xml->text($re->descuento);
        // $xml->endElement();

        // $xml->startElement("precioTotalSinImpuesto");
        // $xml->text($re->precioTotalSinImpuesto);
        // $xml->endElement();

        // $xml->startElement("detallesAdicionales");

        // $xml->startElement("detAdicional");
        // $xml->writeAttribute("nombre", "nombre");
        // $xml->writeAttribute("valor", "valor0");
        // $xml->endElement();

        // $xml->endElement();

        // $xml->startElement("impuestos");

        // $xml->startElement("impuesto");

        // $xml->startElement("codigo");
        // $xml->text($re->codigo);
        // $xml->endElement();

        // $xml->startElement("codigoPorcentaje");
        // $xml->text($re->ambcodigoPorcentajeiente);
        // $xml->endElement();

        // $xml->startElement("tarifa");
        // $xml->text($re->tarifa);
        // $xml->endElement();

        // $xml->startElement("baseImponible");
        // $xml->text($re->baseImponible);
        // $xml->endElement();

        // $xml->startElement("valor");
        // $xml->text($re->valor);
        // $xml->endElement();

        // $xml->endElement();

        // $xml->endElement();

        // $xml->endElement();

        // $xml->endElement();
        $xml->startElement('detalles');
        for ($i = 0; $i < count($det); $i++) {
            $xml->startElement('detalle');
            $xml->startElement('codigoPrincipal');
            $xml->text($det[$i]["cod_principal"]);
            $xml->endElement();
            if ($det[$i]["cod_alterno"]) {
                $xml->startElement('codigoAuxiliar');
                $xml->text(str_replace(array('/', '"', ",", ":", "¨", "-", "–", "é", "á", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ"), array(' ', '', ' ', " ", "", " ", " ", "e", "a", "i", "o", "u", "n", "A", "E", "I", "O", "U", "N"),$det[$i]["cod_alterno"]));
                $xml->endElement();
            }
            $xml->startElement('descripcion');
            $xml->text(str_replace(array('/', '"', ",", ":", "¨", "-", "–", "é", "á", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ"), array(' ', '', ' ', " ", "", " ", " ", "e", "a", "i", "o", "u", "n", "A", "E", "I", "O", "U", "N"), $det[$i]["nombre"]));
            $xml->endElement();
            $xml->startElement('cantidad');
            $xml->text($det[$i]["cantidad"]);
            $xml->endElement();
            $xml->startElement('precioUnitario');
            $xml->text(number_format($det[$i]["precio"],2,".",""));
            $xml->endElement();
            if ($det[$i]["descuento"]>0) {
                $xml->startElement('descuento');
                if ($det[$i]["p_descuento"] == 1) {
                    $xml->text(number_format($det[$i]["descuento"], 2, '.', ''));
                } else {
                    if (isset($det[$i]["descuento"])) {
                        $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"]) / 100), 2, '.', ''));
                    } else {
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
            if ($det[$i]["p_descuento"] == 1) {
                $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]), 2, '.', ''));
            } else {
                if (isset($det[$i]["descuento"])) {
                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - (($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"]) / 100), 2, '.', ''));
                } else {
                    $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]), 2, '.', ''));
                }
            }
            $xml->endElement();
            /*if ($det[$i]["descripcion"]) {
                $xml->startElement('detallesAdicionales');
                    $xml->writeRaw('<detAdicional nombre="descripcion" valor="'.$det[$i]["descripcion"].'"></detAdicional>');
                $xml->endElement();
            }*/
            $xml->startElement('impuestos');

            if ($det[$i]["id_iva"]) {
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
                if ($det[$i]["p_descuento"] == 1) {
                    $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) + ($det[$i]["total_ice"] * $det[$i]["cantidad"]), 2, '.', ''));
                } else {
                    if (isset($det[$i]["descuento"])) {
                        $xml->text(number_format((($det[$i]["precio"] * $det[$i]["cantidad"]) - (($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"]) / 100)) + ($det[$i]["total_ice"] * $det[$i]["cantidad"]), 2, '.', ''));
                    } else {
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
                    //$xml->text(number_format(( ((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) + ($det[$i]["total_ice"] * $det[$i]["cantidad"])) * 0.12), 2, '.', ''));
                    if ($det[$i]["p_descuento"] == 1) {
                        $xml->text(number_format((((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) + ($det[$i]["total_ice"] * $det[$i]["cantidad"])) * 0.12), 2, '.', ''));
                    } else {
                        if (isset($det[$i]["descuento"])) {
                            $xml->text(number_format(((($det[$i]["precio"] * $det[$i]["cantidad"]) - (($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"]) / 100) + ($det[$i]["total_ice"] * $det[$i]["cantidad"])) * 0.12), 2, '.', ''));
                        } else {
                            $xml->text(number_format(((($det[$i]["precio"] * $det[$i]["cantidad"]) + ($det[$i]["total_ice"] * $det[$i]["cantidad"])) * 0.12), 2, '.', ''));
                        }
                    }
                    $xml->endElement();
                } else {
                    $xml->startElement("valor");
                    $xml->text(0.00);
                    $xml->endElement();
                }
                $xml->endElement();
            }

            if ($det[$i]["id_ice"]) {
                if ($det[$i]["total_ice"]) {
                    $xml->startElement('impuesto');
                    $xml->startElement('codigo');
                    $xml->text(3);
                    $xml->endElement();

                    $xml->startElement("codigoPorcentaje");
                    $xml->text(substr($det[$i]["codigo_ice"], 0, 4));
                    $xml->endElement();

                    $xml->startElement("tarifa");
                    $xml->text(0);
                    $xml->endElement();

                    $xml->startElement('baseImponible');
                    if ($det[$i]["p_descuento"] == 1) {
                        $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"], 2, '.', ''));
                    } else {
                        if (isset($det[$i]["descuento"])) {
                            $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - (($det[$i]["precio"] * $det[$i]["cantidad"] * $det[$i]["descuento"]) / 100), 2, '.', ''));
                        } else {
                            $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]), 2, '.', ''));
                        }
                    }
                    $xml->endElement();

                    $xml->startElement("valor");
                    if ($det[$i]["total_ice"]) {
                        $xml->text($det[$i]["total_ice"] * $det[$i]["cantidad"]);
                    } else {
                        $xml->text('0.00');
                    }
                    $xml->endElement();
                    $xml->endElement();
                }
            }

            $xml->endElement();
            $xml->endElement();
        }
        //$recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        $recupera =DB::select("SELECT * FROM empresa where id_empresa=".$re->id_empresa);
        $xml->endElement();

        // $xml->startElement("reembolsos");

        // $xml->startElement("reembolsoDetalle");

        // $xml->startElement("tipoIdentificacionProveedorReembolso");
        // $xml->text($re->tipoIdentificacionProveedorReembolso);
        // $xml->endElement();

        // $xml->startElement("identificacionProveedorReembolso");
        // $xml->text($re->identificacionProveedorReembolso);
        // $xml->endElement();

        // $xml->startElement("codPaisPagoProveedorReembolso");
        // $xml->text($re->codPaisPagoProveedorReembolso);
        // $xml->endElement();

        // $xml->startElement("tipoProveedorReembolso");
        // $xml->text($re->tipoProveedorReembolso);
        // $xml->endElement();

        // $xml->startElement("codDocReembolso");
        // $xml->text($re->codDocReembolso);
        // $xml->endElement();

        // $xml->startElement("estabDocReembolso");
        // $xml->text($re->estabDocReembolso);
        // $xml->endElement();

        // $xml->startElement("ptoEmiDocReembolso");
        // $xml->text($re->ptoEmiDocReembolso);
        // $xml->endElement();

        // $xml->startElement("secuencialDocReembolso");
        // $xml->text($re->secuencialDocReembolso);
        // $xml->endElement();

        // $xml->startElement("fechaEmisionDocReembolso");
        // $xml->text($re->fechaEmisionDocReembolso);
        // $xml->endElement();

        // $xml->startElement("numeroautorizacionDocReemb");
        // $xml->text($re->numeroautorizacionDocReemb);
        // $xml->endElement();

        // $xml->startElement("detalleImpuestos");

        // $xml->startElement("detalleImpuesto");

        // $xml->startElement("codigo");
        // $xml->text($re->codigo);
        // $xml->endElement();

        // $xml->startElement("codigoPorcentaje");
        // $xml->text($re->codigoPorcentaje);
        // $xml->endElement();

        // $xml->startElement("tarifa");
        // $xml->text($re->tarifa);
        // $xml->endElement();

        // $xml->startElement("baseImponibleReembolso");
        // $xml->text($re->baseImponibleReembolso);
        // $xml->endElement();

        // $xml->startElement("impuestoReembolso");
        // $xml->text($re->impuestoReembolso);
        // $xml->endElement();

        // $xml->endElement();

        // $xml->endElement();

        // $xml->endElement();

        // $xml->endElement();

        // $xml->startElement("maquinaFiscal");

        // $xml->startElement("marca");
        // $xml->text($re->marca);
        // $xml->endElement();

        // $xml->startElement("modelo");
        // $xml->text($re->modelo);
        // $xml->endElement();

        // $xml->startElement("serie");
        // $xml->text($re->serie);
        // $xml->endElement();

        // $xml->endElement();

        $xml->startElement('infoAdicional');
        if ($re->direccion_prov) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Dirección");
            $xml->text(str_replace(array("-", "\"", "'", "/", "–", "ñ"), array("", "", "", "", "", "n"), $re->direccion_prov));
            $xml->endElement();
        }
        if ($re->telefono_prov) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text(str_replace(array('/', "(", ")", "–", "-"), array(' ', '', '', ' ', ' '), $re->telefono_prov));
            $xml->endElement();
        }
        if ($re->email) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Email");
            $xml->text(str_replace(array(';'), array(' '), $re->email));
            $xml->endElement();
        }
        // $bus0 = strpos($sel_empresa[0]->leyenda, "NEGOCIO POPULAR");
        // if($bus0==true){
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Regimen");
        //     $xml->text("Contribuyente Negocio Popular Regimen RIMPE");
        //     $xml->endElement();
        // }


        /////////////////////////////////
        // if($re->orden_compra!==null){
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Orden de Compra");
        //     $xml->text(str_replace(array(';'), array(' '), $re->orden_compra));
        //     $xml->endElement();
        // }
        // if($re->observacion!==null){
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Descripcion");
        //     $xml->text(str_replace(array(';'), array(' '), $re->observacion));
        //     $xml->endElement();
        // }
        // if (isset($recupera[0]->migo_factura)) {
        //     $xml->startElement('campoAdicional');
        //     $xml->writeAttribute("nombre", "Migo");
        //     $xml->text($recupera[0]->migo_factura);
        //     $xml->endElement();
        // }
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
}
